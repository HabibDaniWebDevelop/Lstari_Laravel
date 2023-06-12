<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\File;

// Library
use Spatie\SimpleExcel\SimpleExcelReader;

// Models
// Erp
use App\Models\erp\absent;
use App\Models\erp\absentitem;
use App\Models\erp\accesscontroltemp;
use App\Models\erp\checkclock;
use App\Models\erp\workhour;
use App\Models\erp\workdate;
use App\Models\erp\lastid;

// TestLaravel
// use App\Models\tes_laravel\absent;
// use App\Models\tes_laravel\absentitem;
// use App\Models\tes_laravel\accesscontroltemp;
// use App\Models\tes_laravel\checkclock;
// use App\Models\tes_laravel\workhour;
// use App\Models\tes_laravel\workdate;

class AbsensiFaceController extends Controller{
    // START REUSABLE FUNCTION
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    private function GetEmployee($keyword){
        $employee = FacadesDB::connection('erp')
        ->table('Employee AS E')
        ->join('Department AS D', function($join){
            $join->on("E.Department","=","D.ID");
        })
        ->selectRaw("
            E.ID,
            E.Description NAME,
            D.Description Bagian,
            E.Department,
            E.WorkRole,
            E.Rank
        ")
        ->where("E.SW", "=", "$keyword")
        ->orWhere("E.ID","=","".$keyword)
        ->orderBy("E.Department","ASC")
        ->get();
        return $employee;
    }
    // END REUSABLE FUNCTION

    public function Index(){
        // Check if accesscontroltemp is empty
        $accessControlTemp = accesscontroltemp::all();
        if (count($accessControlTemp) > 0) {
            // Delete all item in accesscontroltemp
            $deleteaccesscontroltemp = accesscontroltemp::query()->delete();
        }
        return view('Absensi.Absensi.FaceAbsent.index');
    }

    public function KlikCheck(Request $request){
        // Get File
        $file = $request->file('file');
        // Check if file extendsion is .csv
        if ($file->getClientOriginalExtension() != "csv") {
            $data_return = $this->SetReturn(false, "Cannot Process file. File is not .csv", null, null);
            return response()->json($data_return, 400);
        }

        $data = [];
        // Process datas
        $rows = SimpleExcelReader::create($file, type:'csv')->getRows();
        $rows->each(function($rowProperties) use (&$data) {
            $temp = [];
            $temp['Employee'] = $rowProperties['Employee ID'];
            $temp['TransDate'] = $rowProperties['Date'];
            $temp['TransTime'] = $rowProperties['Time'];
            $temp['Status'] = strtotime($rowProperties['Time']) >= strtotime("13:00:00") ? "K" : "M";
            $temp['Machine'] = $rowProperties['Device Name'] == "Absensi 01 (POS 3)" ? 1 : 2;

            $insertaccesscontroltemp = accesscontroltemp::create([
                "Employee"=>$temp['Employee'],
                "TransDate"=>$temp['TransDate'],
                "TransTime"=>$temp['TransTime'],
                "Status"=>$temp['Status'],
                "Machine"=>$temp['Machine']
            ]);

        });
        // Get all accesscontroltemp
        $getaccesscontroltemp = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.*,
                B.Description AS EmployeeName,
                C.Description AS Bagian
            FROM
                accesscontroltemp A
                JOIN employee B ON A.Employee = B.ID
                JOIN department C ON B.Department = C.ID
            ORDER BY
                A.TransDate
        ");
        foreach ($getaccesscontroltemp as $key => $value) {
            $data[] = $value;
        }
        // Delete all item in accesscontroltemp
        $deleteaccesscontroltemp = accesscontroltemp::query()->delete();
        // Return
        $data_return = $this->SetReturn(true, "Data Extraction Success", $data, null);
        return response()->json($data_return, 200);
    }

    public function SaveFaceAbsent(Request $request){
        // Get File
        $file = $request->file('file');
        // Check if file extendsion is .csv
        if ($file->getClientOriginalExtension() != "csv") {
            $data_return = $this->SetReturn(false, "Cannot Process file. File is not .csv", null, null);
            return response()->json($data_return, 400);
        }

        $data = [];
        // Process datas
        $rows = SimpleExcelReader::create($file, type:'csv')->getRows();
        $rows->each(function($rowProperties) use (&$data) {
            $temp = [];
            $temp['Employee'] = $rowProperties['Employee ID'];
            $temp['TransDate'] = $rowProperties['Date'];
            $temp['TransTime'] = $rowProperties['Time'];
            $temp['Status'] = strtotime($rowProperties['Time']) >= strtotime("13:00:00") ? "K" : "M";
            $temp['Machine'] = $rowProperties['Device Name'] == "Absensi 01 (POS 3)" ? 1 : 2;
            
            $insertaccesscontroltemp = accesscontroltemp::create([
                "Employee"=>$temp['Employee'],
                "TransDate"=>$temp['TransDate'],
                "TransTime"=>$temp['TransTime'],
                "Status"=>$temp['Status'],
                "Machine"=>$temp['Machine']
            ]);

        });
        // Get all accesscontroltemp
        $getaccesscontroltemp = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.*,
                B.Description AS EmployeeName,
                C.Description AS Bagian
            FROM
                accesscontroltemp A
                JOIN employee B ON A.Employee = B.ID
                JOIN department C ON B.Department = C.ID
            ORDER BY
                A.TransDate
        ");
        foreach ($getaccesscontroltemp as $key => $value) {
            $data[] = $value;
        }
        // Return
        $data_return = $this->SetReturn(true, "Data Extraction Success", $data, null);
        return response()->json($data_return, 200);
    }

    public function PostingFaceAbsent(){
        // Get accesscontroltemp
        $accesscontroltemp = accesscontroltemp::all();
        
        // Loop accesscontroltemp for insert/update to workhour
        foreach ($accesscontroltemp as $key => $value) {
            // Get workhour
            $workHour = workhour::where('Employee',$value->Employee)->where('TransDate',$value->TransDate)->first();
            // Check if workHour is exists
            if (!is_null($workHour)) {
                if (is_null($workHour->WorkIn)) {
                    $updateWorkHour = workhour::where('Employee',$value->Employee)->where('TransDate',$value->TransDate)->update([
                        "WorkIn"=>$value['TransTime']
                    ]);
                }else{
                    // Jika WorkOut null maka akan coba checkclock akan diinsertkan ke WorkOut
                    if (is_null($workHour->WorkOut)) {
                        $workInEpoch = strtotime($workHour->WorkIn);
                        $timeCheckClock = strtotime($value['TransTime']);
                        // Cek apakah waktu cekclock lebih dari waktu workin
                        if ($timeCheckClock > $workInEpoch) {
                            // kalkulasi selisih antara waktu checkclock dan waktu workIn
                            $selisih = $timeCheckClock - $workInEpoch; //ini menghasilkan selisih dalam satuan detik
                            // konversi detik ke menit
                            $selisih = $selisih / 60;
                            // Jika Selisih lebih dari 10 menit maka akan update workout
                            if ($selisih > 10) {
                                $updateWorkHour = workhour::where('Employee',$value->Employee)->where('TransDate',$value->TransDate)->update([
                                    "WorkOut"=>$value['TransTime']
                                ]);
                            }
                        }
                    }
                }
            } else {
                if (strtotime($value['TransTime']) > strtotime("13:00:00")) {
                    $insertWorkHour = workhour::create([
                        "Employee"=>$value->Employee,
                        "TransDate"=>$value->TransDate,
                        "WorkIn"=>null,
                        "WorkOut"=>$value->TransTime,
                        "Late"=>null,
                        "Absent"=>null
                    ]);
                }else{
                    $insertWorkHour = workhour::create([
                        "Employee"=>$value->Employee,
                        "TransDate"=>$value->TransDate,
                        "WorkIn"=>$value->TransTime,
                        "WorkOut"=>null,
                        "Late"=>null,
                        "Absent"=>null
                    ]);
                }
            }
        }

        // Get Accesscontroltemp group by idEmployee for Getting WorkIn and WorkOut to calculate if that employee is late or not
        $accesscontroltempGroup = FacadesDB::connection('erp')
        ->select("
            SELECT DISTINCT
                A.Employee,
                A.TransDate 
            FROM
                accesscontroltemp A
        ");
        // Loop accesscontroltempGroup for absent
        foreach ($accesscontroltempGroup as $key => $value) {
            // Generate ID for absent
            $idAbsent = absent::orderBy('ID','DESC')->first();
            $idAbsent = $idAbsent->ID+1;

            // Get Holiday
            $holiday = workdate::where('TransDate',$value->TransDate)->first();
            
            // Get Absent for check if that employee have absent on this day or not.
            $absent = absent::where('Employee',$value->Employee)->where('DateStart',$value->TransDate)->first();
            // Check if absent is exists
            if (is_null($absent)) {
                // Check if that day is not Holiday
                if ($holiday->Holiday == 'N') {
                    // Get workhour on that employee on that day for check if that employee is late or not
                    $cekWorkHour = workhour::where('Employee',$value->Employee)->where('TransDate',$value->TransDate)->first();
                    if (!is_null($cekWorkHour)) {
                        // Check if that employee absent above 08:00 and below 09:00 because that hour is Late for work.
                        if (strtotime($cekWorkHour->WorkIn) > strtotime("08:00:00") and strtotime($cekWorkHour->WorkIn) < strtotime("09:00:00")) {
                            $jams1 = strtotime($cekWorkHour->WorkIn);
                            $bandingx =strtotime("08:01:00");			
                            $banding1 =strtotime("08:15:00");			
                            $banding2 =strtotime("08:30:00");
                            $banding3 =strtotime("08:45:00");
                            $banding4 =strtotime("09:00:00");

                            // Calculate absent hour
                            if ($jams1 <= $bandingx) {
                                $abseny = '0';
                            } elseif ($jams1 <= $banding1) {
                                $abseny = '0.25';
                            } elseif ($jams1 <= $banding2) {
                                $abseny = '0.50';
                            } elseif ($jams1  <=  $banding3) {
                                $abseny = '0.75';
                            } elseif ($jams1  <=  $banding4) {
                                $abseny = '1';
                            } else {
                                $abseny = '';
                            }

                            // Insert Absent
                            $insertAbsent = absent::create([
                                "ID"=>$idAbsent,
                                "UserName"=>Auth::user()->name,
                                "Employee"=>$value->Employee,
                                "DateStart"=>$value->TransDate,
                                "DateEnd"=>$value->TransDate,
                                "Type"=>'3',
                                "TimeStart"=>"08:00:00",
                                "TimeEnd"=>$cekWorkHour->WorkIn,
                                "Active"=>'P'
                            ]);

                            // Insert AbsentItem
                            $insertAbsentItem = absentitem::create([
                                "IDM"=>$idAbsent,
                                "Employee"=>$value->Employee,
                                "TransDate"=>$value->TransDate,
                                "TimeFrom"=>"08:00:00",
                                "TimeTo"=>$cekWorkHour->WorkIn,
                                "Absent"=>$abseny,
                                "Ordinal"=>1,
                                "ActualFrom"=>"08:00:00",
                                "ActualTo"=>$cekWorkHour->WorkIn,
                                "AllDay"=>"N",
                                "Active"=>"Y"
                            ]);

                            // Update WorkHour
                            $updateWorkHour = workhour::where('Employee',$value->Employee)->where('TransDate',$value->TransDate)->update([
                                "Late"=>$abseny
                            ]);

                            // update lastid
                            $updatelastid = lastid::where('ID',31)
                            ->update([
                                "Last"=>$idAbsent
                            ]);
                        }
                    }
                }
            }
        }

        // Loop accesscontroltemp for checkclock 
        foreach ($accesscontroltemp as $key => $value) {
            // check checkclock
            $checkClock = checkclock::where('Employee',$value->Employee)->where('TransDate',$value->TransDate)->where('TransTime',$value->TransTime)->first();
            if (is_null($checkClock)) {
                $insertCheckClock = checkclock::insert([
                    "Employee"=>$value->Employee,
                    "TransDate"=>$value->TransDate,
                    "TransTime"=>$value->TransTime,
                    "Status"=>$value->Status,
                    "Type"=>"A",
                    "UserName"=>Auth::user()->name,
                    "Machine"=>$value->Machine
                ]);
            }
        }

        // Remove accesscontroltemp
        $deleteaccesscontroltemp = accesscontroltemp::query()->delete();

        $data_return = $this->SetReturn(true, "Data Absensi Wajah Berhasil Diposting", null, null);
        return response()->json($data_return, 200);
        // next
    }
}

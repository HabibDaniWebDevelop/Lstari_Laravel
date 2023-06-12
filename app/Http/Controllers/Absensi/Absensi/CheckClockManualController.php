<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\checkclock;
use App\Models\erp\clockmanual;
use App\Models\erp\workhour;

class CheckClockManualController extends Controller{

    // Reusable Function

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
            E.WorkRole	
        ")
        ->where("E.SW", "=", "$keyword")
        ->orWhere("E.ID","=","".$keyword)
        ->where("E.Active",'=',"Y")
        ->orderBy("E.Department","ASC")
        ->get();
        return $employee;
    }

    // End Reusable Function

    public function Index(){
        $datenow = date('Y-m-d');
        return view('Absensi.Absensi.CheckClockManual.index', compact('datenow'));
    }

    public function SearchEmployee(Request $request){
        $idEmployee = $request->employee;
        // Get Employee
        $employee = $this->GetEmployee($idEmployee);
        if (count($employee) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Employee with SW ".$idEmployee." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Setup Return Data
        $data_return = [
            'success' => true,
            'title' => 'Successs!!',
            'message' => "Employee Found",
            'data'=> $employee[0]
        ];
        return response()->json($data_return,200);
    }

    public function SaveCheckClockManual(Request $request){
        $idEmployee = $request->idEmployee;
        $tanggal = $request->tanggal;
        $jam = $request->jam;
        $masuk = $request->masuk;
        $catatan = $request->catatan;


        // Check Input if blank or null
        if (is_null($idEmployee) or is_null($tanggal) or is_null($jam) or is_null($masuk) or $idEmployee == "" or $tanggal == "" or $jam == "" or $masuk == "") {
            $data_return = $this->SetReturn(false, "Failed to Create CheckClock Manual. Data Invalid ", null, null);
            return response()->json($data_return,400);
        }

        // Check if employee exists
        $employee = $this->GetEmployee($idEmployee);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Failed to Create CheckClock Manual. Employee with ID '$idEmployee' Not Found ", null, null);
            return response()->json($data_return,404);
        }
        
        // Insert to ClockManual
        $insertClockManual = clockmanual::create([
            "UserName"=>Auth::user()->name,
            "Employee"=>$idEmployee,
            "TransDate"=>$tanggal,
            "TransTime"=>$jam,
            "Reason"=>$catatan,
            "Status"=>$masuk
        ]);

        // Insert to CheckClock
        $insertCheckClock = checkclock::create([
            "Employee"=>$idEmployee,
            "TransDate"=>$tanggal,
            "TransTime"=>$jam,
            "Status"=>$masuk,
            "Type"=>"M",
            "UserName"=>Auth::user()->name,
            "Machine"=>"0"
        ]);

        // Check status if "M" for masuk, "K" for Keluar
        if ($masuk == "M") {
            // Check Workhour
            $cekWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->first();
            if (!is_null($cekWorkHour)) {
                $updateWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->update([
                    "WorkIn"=>$jam
                ]);
                $value_return = [
                    "id"=>$insertClockManual->id,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Created", $value_return, null);
                return response()->json($data_return,200);
            } else {
                $createWorkHour = workhour::create([
                    "Employee"=>$idEmployee,
                    "TransDate"=>$tanggal,
                    "WorkIn"=>$jam,
                    "WorkOut"=>null,
                    "Late"=>null,
                    "Absent"=>null,
                    "OverTime"=>null,
                    "WorkTime"=>null
                ]);
                $value_return = [
                    "id"=>$insertClockManual->id,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Created", $value_return, null);
                return response()->json($data_return,200);
            }
        } elseif ($masuk == "K") {
            // Check Workhour
            $cekWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->first();
            if (!is_null($cekWorkHour)) {
                $updateWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->update([
                    "WorkOut"=>$jam
                ]);
                $value_return = [
                    "id"=>$insertClockManual->id,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Created", $value_return, null);
                return response()->json($data_return,200);
            } else {
                $createWorkHour = workhour::create([
                    "Employee"=>$idEmployee,
                    "TransDate"=>$tanggal,
                    "WorkIn"=>null,
                    "WorkOut"=>$jam,
                    "Late"=>null,
                    "Absent"=>null,
                    "OverTime"=>null,
                    "WorkTime"=>null
                ]);
                $value_return = [
                    "id"=>$insertClockManual->id,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Created", $value_return, null);
                return response()->json($data_return,200);
            }
        } else {
            $data_return = $this->SetReturn(false, "Failed to Create CheckClock Manual. Type must be 'M' or 'K' ", null, null);
            return response()->json($data_return,400);
        }

    }

    public function UpdateCheckClockManual(Request $request){
        $idCheckClockManual = $request->idCheckClockManual;
        $idEmployee = $request->idEmployee;
        $tanggal = $request->tanggal;
        $jam = $request->jam;
        $masuk = $request->masuk;
        $catatan = $request->catatan;

         // Check Input if blank or null
         if (is_null($idCheckClockManual) or is_null($idEmployee) or is_null($tanggal) or is_null($jam) or is_null($masuk) or $idCheckClockManual == "" or $idEmployee == "" or $tanggal == "" or $jam == "" or $masuk == "") {
            $data_return = $this->SetReturn(false, "Failed to Create CheckClock Manual. Data Invalid ", null, null);
            return response()->json($data_return,400);
        }

        // Check if employee exists
        $employee = $this->GetEmployee($idEmployee);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Failed to Create CheckClock Manual. Employee with ID '$idEmployee' Not Found ", null, null);
            return response()->json($data_return,404);
        }

        // Check if CheckClockManualExists
        $clockmanual = clockmanual::where("ID",$idCheckClockManual)->first();

        // Get CheckClock ID
        $checkclock = checkclock::where("TransDate",$clockmanual->TransDate)->Where("TransTime",$clockmanual->TransTime)->where("Status",$clockmanual->Status)->first();
        $idCheckClock = $checkclock->ID;
        
        // Update ClockManual
        $updateClockManual = clockmanual::where("ID", $idCheckClockManual)->update([
            "Employee"=>$idEmployee,
            "TransDate"=>$tanggal,
            "TransTime"=>$jam,
            "Reason"=>$catatan,
            "Status"=>$masuk
        ]);

        // Update CheckClock
        $updateCheckClock = checkclock::where("ID",$idCheckClock)->update([
            "TransDate"=>$tanggal,
            "TransTime"=>$jam,
            "Status"=>$masuk
        ]);

        // Check status if "M" for masuk, "K" for Keluar
        if ($masuk == "M") {
            // Check Workhour
            $cekWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->first();
            if (!is_null($cekWorkHour)) {
                $updateWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->update([
                    "WorkIn"=>$jam
                ]);
                $value_return = [
                    "id"=>$idCheckClockManual,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Updated", $value_return, null);
                return response()->json($data_return,200);
            } else {
                $createWorkHour = workhour::create([
                    "Employee"=>$idEmployee,
                    "TransDate"=>$tanggal,
                    "WorkIn"=>$jam,
                    "WorkOut"=>null,
                    "Late"=>null,
                    "Absent"=>null,
                    "OverTime"=>null,
                    "WorkTime"=>null
                ]);
                $value_return = [
                    "id"=>$idCheckClockManual,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Updated", $value_return, null);
                return response()->json($data_return,200);
            }
        } elseif ($masuk == "K") {
            // Check Workhour
            $cekWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->first();
            if (!is_null($cekWorkHour)) {
                $updateWorkHour = workhour::where("Employee",$idEmployee)->where("TransDate",$tanggal)->update([
                    "WorkOut"=>$jam
                ]);
                $value_return = [
                    "id"=>$idCheckClockManual,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Updated", $value_return, null);
                return response()->json($data_return,200);
            } else {
                $createWorkHour = workhour::create([
                    "Employee"=>$idEmployee,
                    "TransDate"=>$tanggal,
                    "WorkIn"=>null,
                    "WorkOut"=>$jam,
                    "Late"=>null,
                    "Absent"=>null,
                    "OverTime"=>null,
                    "WorkTime"=>null
                ]);
                $value_return = [
                    "id"=>$idCheckClockManual,
                    "Urut"=>1,
                    "TransDate"=>$tanggal,
                    "TransTime"=>$jam,
                    "Employee"=>$employee[0]->NAME,
                    "Divisi"=>$employee[0]->Bagian
                ];
                $data_return = $this->SetReturn(true, "CheckClock Manual Updated", $value_return, null);
                return response()->json($data_return,200);
            }
        } else {
            $data_return = $this->SetReturn(false, "Failed to Create CheckClock Manual. Type must be 'M' or 'K' ", null, null);
            return response()->json($data_return,400);
        }
    }

    public function SearchCheckClockManual(Request $request){
        $keyword = $request->keyword;
        if (is_null($keyword) or $keyword == "") {
            $data_return = $this->SetReturn(false, "Search CheckClock Manual Failed. Keyword required.", null, ["keyword"=>"keyword required in query params"]);
            return response()->json($data_return,404);
        }

        // Get ClockManual
        $clockmanual = clockmanual::where("ID",$keyword)->first();
        if (is_null($clockmanual)) {
            $data_return = $this->SetReturn(false, "CheckClock Manual Not Found.", null, null);
            return response()->json($data_return,404);
        }
        // Get Employee
        $employee = $this->GetEmployee($clockmanual->Employee);
        $employee = $employee[0];
        $data = [
            "Urut"=>1,
            "CheckClockManualID"=>$clockmanual->ID,
            "idEmployee"=>$employee->ID,
            "EmployeeName"=>$employee->NAME,
            "Divisi"=>$employee->Bagian,
            "TransDate"=>$clockmanual->TransDate,
            "TransTime"=>$clockmanual->TransTime,
            "Masuk"=>$clockmanual->Status,
            "Catatan"=>$clockmanual->Reason
        ];
        $data_return = $this->SetReturn(false, "CheckClock Manual Found.", $data, null);
        return response()->json($data_return,200);
    }
}

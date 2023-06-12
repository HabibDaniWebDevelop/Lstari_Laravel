<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\lateexception;
use App\Models\erp\lateexceptionitem;
use App\Models\erp\workhour;
use App\Models\erp\absent;
use App\Models\erp\checkclock;

class DispensasiKeterlambatanController extends Controller{
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
            E.WorkRole	
        ")
        ->where("E.SW", "=", "$keyword")
        ->orWhere("E.ID","=","".$keyword)
        ->orderBy("E.Department","ASC")
        ->get();
        return $employee;
    }
    // END REUSABLE FUNCTION

    public function Index(){
        $datenow = date('Y-m-d');
        return view('Absensi.Absensi.DispensasiKeterlambatan.index', compact('datenow'));
    }

    public function GetDaftarKaryawan(Request $request){
        $tanggal = $request->tanggal;

        $daftarKaryawan = FacadesDB::connection('erp')
        ->select("
            SELECT
                E.ID,
                E.Description Employee,
                D.Description Department,
                E.WorkRole,
                H.WorkIn 
            FROM
                WorkHour H
                JOIN Employee E ON H.Employee = E.ID
                JOIN Department D ON E.Department = D.ID 
            WHERE
                H.TransDate = '$tanggal' 
                AND H.WorkIn > '08:00:00' 
            ORDER BY
                WorkRole,
                Department,
                Employee
        ");
        if (count($daftarKaryawan) == 0) {
            $data_return = $this->SetReturn(false,"Tidak Ada Daftar Karyawan Yang Telat", null, null);
            return response()->json($data_return,404);
        }
        $data_return = $this->SetReturn(true,"Ada Karyawan Yang Telat", $daftarKaryawan, null);
        return response()->json($data_return,200);
    }

    public function SaveDispensasiKeterlambatan(Request $request){
        $tanggal = $request->tanggal;
        $catatan = $request->catatan;
        $items = $request->items;
        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = $this->SetReturn(false, "Save Dispensasi Keterlambatan Failed.",null,["tanggal"=>"tanggal Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false,"Save Dispensasi Keterlambatan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Save Dispensasi Keterlambatan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("idEmployee",$item) and array_key_exists("checkClock",$item)){
                // Check idEmployee in item
                if (is_null($item['idEmployee']) or strval($item['idEmployee']) == ""){
                    $data_return = $this->SetReturn(false,"Save Dispensasi Keterlambatan Failed",null, ["idEmployee"=>"There still any blank or null value on idEmployee"]);
                    return response()->json($data_return,400);
                } else {
                    // Check If Employee exists
                    $employee = $this->GetEmployee($item['idEmployee']);
                    if (count($employee) == 0){
                        $_idEmployee = $item['idEmployee'];
                        $data_return = $this->SetReturn(false, "Save Dispensasi Keterlambatan Failed. Employee with ID '$_idEmployee' Not Found", null, null);
                        return response()->json($data_return,404);
                    }
                }
                // Check checkClock in item
                if (is_null($item['checkClock']) or $item['checkClock'] == ""){
                    $data_return = $this->SetReturn(false,"Save Dispensasi Keterlambatan Failed",null, ["checkClock"=>"There still any blank or null value on checkClock"]);
                    return response()->json($data_return,400);
                }
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Save Dispensasi Keterlambatan Failed",null, ["items"=>"Items required idEmployee, checkClock"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        // Get last id for lateexception
        $idLateException = lateexception::orderBy("ID","DESC")->first();
        $idLateException = $idLateException->ID + 1;

        // Insert to lateexception
        $insertLateException = lateexception::create([
            "ID"=>$idLateException,
            "UserName"=>Auth::user()->name,
            "Remarks"=>$catatan,
            "TransDate"=>$tanggal,
            "Active"=>"A"
        ]);

        // Insert to lateexceptionitem
        foreach ($items as $key => $item) {
            // Insert to lateexceptionitem
            $insertLateExceptionItem = lateexceptionitem::create([
                "IDM"=>$idLateException,
                "Ordinal"=>$key+1,
                "Employee"=>$item['idEmployee'],
                "CheckClock"=>$item['checkClock']
            ]);
        }
        // Return Success
        $data_return = $this->SetReturn(true,"Save Dispensasi Keterlambatan Success",['ID'=>$idLateException, "postingStatus"=>"A"], null);
        return response()->json($data_return,200);
    }

    public function UpdateDispensasiKeterlambatan(Request $request){
        $idLateException = $request->idLateException;
        $tanggal = $request->tanggal;
        $catatan = $request->catatan;
        $items = $request->items;

        // Check if idLateException null or blank
        if (is_null($idLateException) or $idLateException == "") {
            $data_return = $this->SetReturn(false, "Update Dispensasi Keterlambatan Failed.",null,["idLateException"=>"idLateException Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = $this->SetReturn(false, "Update Dispensasi Keterlambatan Failed.",null,["tanggal"=>"tanggal Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false,"Update Dispensasi Keterlambatan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Update Dispensasi Keterlambatan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("idEmployee",$item) and array_key_exists("checkClock",$item)){
                // Check idEmployee in item
                if (is_null($item['idEmployee']) or strval($item['idEmployee']) == ""){
                    $data_return = $this->SetReturn(false,"Update Dispensasi Keterlambatan Failed",null, ["idEmployee"=>"There still any blank or null value on idEmployee"]);
                    return response()->json($data_return,400);
                } else {
                    // Check If Employee exists
                    $employee = $this->GetEmployee($item['idEmployee']);
                    if (count($employee) == 0){
                        $_idEmployee = $item['idEmployee'];
                        $data_return = $this->SetReturn(false, "Update Dispensasi Keterlambatan Failed. Employee with ID '$_idEmployee' Not Found", null, null);
                        return response()->json($data_return,404);
                    }
                }
                // Check checkClock in item
                if (is_null($item['checkClock']) or $item['checkClock'] == ""){
                    $data_return = $this->SetReturn(false,"Update Dispensasi Keterlambatan Failed",null, ["checkClock"=>"There still any blank or null value on checkClock"]);
                    return response()->json($data_return,400);
                }
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Update Dispensasi Keterlambatan Failed",null, ["items"=>"Items required idEmployee, checkClock"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        // update to lateexception
        $updateLateException = lateexception::where('ID',$idLateException)->update([
            "UserName"=>Auth::user()->name,
            "Remarks"=>$catatan,
            "TransDate"=>$tanggal,
            "Active"=>"A"
        ]);

        // Delete lateexceptionitem
        $deletelateexceptionitem = lateexceptionitem::where('IDM',$idLateException)->delete();

        // Insert to lateexceptionitem
        foreach ($items as $key => $item) {
            // Insert to lateexceptionitem
            $insertLateExceptionItem = lateexceptionitem::create([
                "IDM"=>$idLateException,
                "Ordinal"=>$key+1,
                "Employee"=>$item['idEmployee'],
                "CheckClock"=>$item['checkClock']
            ]);
        }
        // Return Success
        $data_return = $this->SetReturn(true,"Update Dispensasi Keterlambatan Success",['ID'=>$idLateException, "postingStatus"=>"A"], null);
        return response()->json($data_return,200);
    }

    public function SearchDispensasiKeterlambatan(Request $request){
        $keyword = $request->keyword;

        // Search dispensasiKeterlambatan
        $dispensasiKeterlambatan = lateexception::where('ID',$keyword)->first();
        if (is_null($dispensasiKeterlambatan)) {
            $data_return = $this->SetReturn(false, "Dispensasi Keterlambatan Dengan ID : '$keyword' Not Found", null, null);
            return response()->json($data_return,404);
        }

        // Get dispensasiKeterlambatanItem
        $dispensasiKeterlambatanItem = lateexceptionitem::where('IDM',$keyword)->get();
        $dataDispensasiKeterlambatan = [];
        foreach ($dispensasiKeterlambatanItem as $key => $value) {
            $temp = [];
            $temp['IDM'] = $value->IDM;
            $temp['Ordinal'] = $value->Ordinal;
            $temp['CheckClock'] = $value->CheckClock;
            // Get Employee 
            $employee = $this->GetEmployee($value->Employee)[0];
            $temp['idEmployee'] = $employee->ID;
            $temp['Karyawan'] = $employee->NAME;
            $temp['Bagian'] = $employee->Bagian;
            $temp['Status'] = $employee->WorkRole;
            array_push($dataDispensasiKeterlambatan, $temp);
        }
        $dispensasiKeterlambatan['dispensasiKeterlambatanItem'] = $dataDispensasiKeterlambatan;
        $data_return = $this->SetReturn(true, "Dispensasi Keterlambatan Dengan ID : '$keyword' Found", $dispensasiKeterlambatan, null);
        return response()->json($data_return,200);
    }

    public function PostingDispensasiKeterlambatan(Request $request){
        $idLateException = $request->idLateException;

        // Search DispensasiKeterlambatan
        $dispensasiKeterlambatan = lateexception::where('ID',$idLateException)->first();
        if (is_null($dispensasiKeterlambatan)) {
            $data_return = $this->SetReturn(false, "Dispensasi Keterlambatan with ID : '$idLateException' Not Found", null, null);
            return response()->json($data_return,404);
        }

        // Update DispensasiKeterlambatan
        $updateDispensasiKeterlambatan = lateexception::where('ID', $idLateException)->update([
            "Active"=>'P',
            "PostDate" => date("Y-m-d H:i:s")
        ]);

        // Get dispensasiKeterlambatanItem
        $dispensasiKeterlambatanItem = lateexceptionitem::where('IDM',$idLateException)->get();
        foreach ($dispensasiKeterlambatanItem as $key => $value) {
            // Update CheckClock
            $updateCheckClock = checkclock::where('Employee',$value->Employee)
            ->where('TransDate',$dispensasiKeterlambatan->TransDate)
            ->where('TransTime', $value->CheckClock)
            ->update([
                "TransTime"=>"07:58:00"
            ]);

            // Update WorkHour
            $updateWorkHour = workhour::where('Employee',$value->Employee)
            ->where('TransDate',$dispensasiKeterlambatan->TransDate)
            ->where('WorkIn', $value->CheckClock)
            ->update([
                "WorkIn"=>"07:58:00",
                "Late"=>null
            ]);
            
            // Update Absent
            $updateAbsent = absent::where('Type',3)
            ->where('Employee',$value->Employee)
            ->where('DateStart',$dispensasiKeterlambatan->TransDate)
            ->where('DateEnd',$dispensasiKeterlambatan->TransDate)
            ->where('TimeEnd', $value->CheckClock)
            ->update([
                "Active"=>"C"
            ]);
        }
        $data_return = $this->SetReturn(true, "Posting Dispensasi Keterlambatan with ID : '$idLateException' Found", ["ID"=>$idLateException, "postingStatus"=>'P'], null);
        return response()->json($data_return,200);
    }
}

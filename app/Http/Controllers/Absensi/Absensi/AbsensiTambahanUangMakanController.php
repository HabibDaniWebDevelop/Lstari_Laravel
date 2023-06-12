<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\additionalfood;
use App\Models\erp\additionalfooditem;
use App\Models\erp\workhour;

class AbsensiTambahanUangMakanController extends Controller{
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
        ->where("E.Active",'=',"Y")
        ->orderBy("E.Department","ASC")
        ->get();
        return $employee;
    }
    // END REUSABLE FUNCTION

    public function Index(){
        $datenow = date('Y-m-d');
        return view('Absensi.Absensi.TambahanUangMakan.index', compact('datenow'));
    }

    public function GetDaftarKaryawan(Request $request){
        $tanggal = $request->tanggal;

        $daftarKaryawan = FacadesDB::connection('erp')
        ->select("
            SELECT
                E.ID,
                E.Description Employee,
                D.Description Department,
                H.WorkOut 
            FROM
                WorkHour H
                JOIN Employee E ON H.Employee = E.ID 
                AND E.Rank IN ( 'Staf', 'Staf Admin', 'Kepala Bagian', 'Supervisor' ) 
                AND E.Department <> 53
                JOIN Department D ON E.Department = D.ID 
            WHERE
                H.TransDate = '$tanggal'
                AND H.WorkOut > '18:00' 
            ORDER BY
                Department,
                Employee
        ");
        if (count($daftarKaryawan) == 0) {
            $data_return = $this->SetReturn(false,"Tidak Ada Daftar Karyawan Yang Lembur", null, null);
            return response()->json($data_return,404);
        }
        $data_return = $this->SetReturn(true,"Ada Karyawan Yang Lembur", $daftarKaryawan, null);
        return response()->json($data_return,200);
    }

    public function SimpanTambahanUangMakan(Request $request){
        $tanggal = $request->tanggal;
        $items = $request->items;
        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = $this->SetReturn(false, "Save Tambahan Uang Makan Failed.",null,["tanggal"=>"tanggal Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false,"Save Tambahan Uang Makan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Save Tambahan Uang Makan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("idEmployee",$item) and array_key_exists("waktuPulang",$item)){
                // Check idEmployee in item
                if (is_null($item['idEmployee']) or strval($item['idEmployee']) == ""){
                    $data_return = $this->SetReturn(false,"Save Tambahan Uang Makan Failed",null, ["idEmployee"=>"There still any blank or null value on idEmployee"]);
                    return response()->json($data_return,400);
                } else {
                    // Check If Employee exists
                    $employee = $this->GetEmployee($item['idEmployee']);
                    if (count($employee) == 0){
                        $_idEmployee = $item['idEmployee'];
                        $data_return = $this->SetReturn(false, "Save Tambahan Uang Makan Failed. Employee with ID '$_idEmployee' Not Found", null, null);
                        return response()->json($data_return,404);
                    }
                }
                // Check waktuPulang in item
                if (is_null($item['waktuPulang']) or $item['waktuPulang'] == ""){
                    $data_return = $this->SetReturn(false,"Save Tambahan Uang Makan Failed",null, ["waktuPulang"=>"There still any blank or null value on waktuPulang"]);
                    return response()->json($data_return,400);
                }
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Save Tambahan Uang Makan Failed",null, ["items"=>"Items required idEmployee, waktuPulang"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        // Get last id for additionalfood
        $idAdditionalFood = additionalfood::orderBy("ID","DESC")->first();
        $idAdditionalFood = $idAdditionalFood->ID + 1;

        // Insert to additionalfood
        $insertAdditionalFood = additionalfood::create([
            "ID"=>$idAdditionalFood,
            "UserName"=>Auth::user()->name,
            "TransDate"=>$tanggal,
            "Active"=>"A"
        ]);

        // Insert to additionalfooditem
        foreach ($items as $key => $item) {
            // Insert to additionalfooditem
            $insertAdditionalFoodItem = additionalfooditem::create([
                "IDM"=>$idAdditionalFood,
                "Ordinal"=>$key+1,
                "Employee"=>$item['idEmployee'],
                "WorkOut"=>$item['waktuPulang']
            ]);
        }
        // Return Success
        $data_return = $this->SetReturn(true,"Save Tambahan Uang Makan Success",['ID'=>$idAdditionalFood, "postingStatus"=>"A"], null);
        return response()->json($data_return,200);
    }

    public function UpdateTambahanUangMakan(Request $request){
        $idAdditionalFood = $request->idTambahanUangMakan;
        $tanggal = $request->tanggal;
        $items = $request->items;
        
        // Check if idTambahanUangMakan null or blank
        if (is_null($idAdditionalFood) or $idAdditionalFood == "") {
            $data_return = $this->SetReturn(false, "Update Tambahan Uang Makan Failed.",null,["idTambahanUangMakan"=>"idTambahanUangMakan Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = $this->SetReturn(false, "Update Tambahan Uang Makan Failed.",null,["tanggal"=>"tanggal Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false,"Update Tambahan Uang Makan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Update Tambahan Uang Makan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("idEmployee",$item) and array_key_exists("waktuPulang",$item)){
                // Check idEmployee in item
                if (is_null($item['idEmployee']) or strval($item['idEmployee']) == ""){
                    $data_return = $this->SetReturn(false,"Update Tambahan Uang Makan Failed",null, ["idEmployee"=>"There still any blank or null value on idEmployee"]);
                    return response()->json($data_return,400);
                } else {
                    // Check If Employee exists
                    $employee = $this->GetEmployee($item['idEmployee']);
                    if (count($employee) == 0){
                        $_idEmployee = $item['idEmployee'];
                        $data_return = $this->SetReturn(false, "Update Tambahan Uang Makan Failed. Employee with ID '$_idEmployee' Not Found", null, null);
                        return response()->json($data_return,404);
                    }
                }
                // Check waktuPulang in item
                if (is_null($item['waktuPulang']) or $item['waktuPulang'] == ""){
                    $data_return = $this->SetReturn(false,"Update Tambahan Uang Makan Failed",null, ["waktuPulang"=>"There still any blank or null value on waktuPulang"]);
                    return response()->json($data_return,400);
                }
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Update Tambahan Uang Makan Failed",null, ["items"=>"Items required idEmployee, waktuPulang"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        // Update to additionalfood
        $updateAdditionalFood = additionalfood::where('ID',$idAdditionalFood)->update([
            "UserName"=>Auth::user()->name,
            "TransDate"=>$tanggal,
            "Active"=>"A"
        ]);

        // Delete additionalfooditem
        $deleteAdditionalFoodItem = additionalfooditem::where('IDM',$idAdditionalFood)->delete();

        // Insert to additionalfooditem
        foreach ($items as $key => $item) {
            // Insert to additionalfooditem
            $insertAdditionalFoodItem = additionalfooditem::create([
                "IDM"=>$idAdditionalFood,
                "Ordinal"=>$key+1,
                "Employee"=>$item['idEmployee'],
                "WorkOut"=>$item['waktuPulang']
            ]);
        }
        // Return Success
        $data_return = $this->SetReturn(true,"Update Tambahan Uang Makan Success",['ID'=>$idAdditionalFood, "postingStatus"=>"A"], null);
        return response()->json($data_return,200);
    }

    public function SearchTambahanUangMakan(Request $request){
        $keyword = $request->keyword;

        // Search TambahanUangMakan
        $tambahanUangMakan = additionalfood::where('ID',$keyword)->first();
        if (is_null($tambahanUangMakan)) {
            $data_return = $this->SetReturn(false, "Tambahan Uang Makan with ID : '$keyword' Not Found", null, null);
            return response()->json($data_return,404);
        }

        // Get TambahanUangMakanitem
        $tambahanUangMakanItem = additionalfooditem::where('IDM',$keyword)->get();
        $dataTambahUangMakan = [];
        foreach ($tambahanUangMakanItem as $key => $value) {
            $temp = [];
            $temp['IDM'] = $value->IDM;
            $temp['Ordinal'] = $value->Ordinal;
            $temp['WorkOut'] = $value->WorkOut;
            // Get Employee 
            $employee = $this->GetEmployee($value->Employee)[0];
            $temp['idEmployee'] = $employee->ID;
            $temp['Karyawan'] = $employee->NAME;
            $temp['Bagian'] = $employee->Bagian;
            array_push($dataTambahUangMakan, $temp);
        }
        $tambahanUangMakan['tambahanUangMakanItem'] = $dataTambahUangMakan;
        $data_return = $this->SetReturn(true, "Tambahan Uang Makan with ID : '$keyword' Found", $tambahanUangMakan, null);
        return response()->json($data_return,200);
    }

    public function PostingTambahanUangMakan(Request $request){
        $idTambahanUangMakan = $request->idTambahanUangMakan;

        // Search TambahanUangMakan
        $tambahanUangMakan = additionalfood::where('ID',$idTambahanUangMakan)->first();
        if (is_null($tambahanUangMakan)) {
            $data_return = $this->SetReturn(false, "Tambahan Uang Makan with ID : '$idTambahanUangMakan' Not Found", null, null);
            return response()->json($data_return,404);
        }

        // Update TambahanUangMakan
        $updateTambahanUangMakan = additionalfood::where('ID', $idTambahanUangMakan)->update([
            "Active"=>'P',
            "PostDate" => date("Y-m-d H:i:s")
        ]);

        // Get TambahanUangMakanitem
        $tambahanUangMakanItem = additionalfooditem::where('IDM',$idTambahanUangMakan)->get();
        foreach ($tambahanUangMakanItem as $key => $value) {
            // Update WorkHour
            $updateWorkHour = workhour::where('Employee',$value->Employee)->where('TransDate',$tambahanUangMakan->TransDate)->update([
                "FoodAdditional"=>"1"
            ]);
        }
        $data_return = $this->SetReturn(true, "Posting Tambahan Uang Makan with ID : '$idTambahanUangMakan' Found", null, null);
        return response()->json($data_return,200);
    }
}

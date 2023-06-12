<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\overtime;
use App\Models\erp\overtimeitem;
use App\Models\erp\workhour;

class AbsensiLemburKerjaController extends Controller{

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
    private function getTimeDiff($dtime,$atime){
        $nextDay = $dtime>$atime?1:0;
        $dep = explode(':',$dtime);
        $arr = explode(':',$atime);
        $diff = abs(mktime($dep[0],$dep[1],0,date('n'),date('j'),date('y'))-mktime($arr[0],$arr[1],0,date('n'),date('j')+$nextDay,date('y')));
        $hours = floor($diff/(60*60));
        $mins = floor(($diff-($hours*60*60))/(60));
        $secs = floor(($diff-(($hours*60*60)+($mins*60))));
        if(strlen($hours)<2){$hours="0".$hours;}
        if(strlen($mins)<2){$mins="0".$mins;}
        if(strlen($secs)<2){$secs="0".$secs;}
        return $hours.':'.$mins.':'.$secs;
    }

    // End Reusable Function

    public function Index(){
        $datenow = date('Y-m-d');
        return view('Absensi.Absensi.LemburKerja.index', compact('datenow'));
    }

    public function CheckDate(Request $request){
        $tanggal = $request->tanggal;
        $jamMulai = FacadesDB::connection('erp')
        ->select("
            SELECT
                CASE
                    WHEN Holiday = 'N' AND DAY IN ( 'Sabtu' ) THEN
                        '14:00:00' 
                    WHEN Holiday = 'N' AND DAY IN ( 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat' ) THEN
                        '16:30:00' ELSE '1' 
                    END AS tgl 
            FROM
                WorkDate 
            WHERE
                TransDate = '$tanggal'
        ");
        $jamMulai = $jamMulai[0];
        if ($jamMulai->tgl == 1) {
            $data_return = $this->SetReturn(false, "Tidak dapat memilih tanggal tersebut. Hari Libur", null, null);
            return response()->json($data_return,400);
        }
        $data_return = $this->SetReturn(true, "Pilih Tanggal Success", ["jamMulai"=>$jamMulai->tgl], null);
        return response()->json($data_return,200);
    }

    public function SearchEmployee(Request $request){
        $employeeSW = $request->employeeSW;
        $tanggal = $request->tanggal;
        $jam_mulai = $request->jam_mulai;
        $jam_selesai = $request->jam_selesai;

        // Check And Get Employee
        $employee = $this->GetEmployee($employeeSW);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Employee Not Found", null, null);
            return response()->json($data_return,404);
        }
        $employee = $employee[0];

        // Get Aktual Selesai
        $aktualSelesai = FacadesDB::connection('erp')
        ->select("
            SELECT
                CASE
                    WHEN WorkOut IS NULL THEN
                        '00:00:00' ELSE WorkOut 
                    END AS aktualSelesai 
            FROM
                WorkHour 
            WHERE
                Employee = '$employee->ID' AND TransDate = '$tanggal'
        ");
        // check length of aktualSelesai is more than 0
        if (count($aktualSelesai) > 0) { 
            $aktualSelesai = $aktualSelesai[0]->aktualSelesai;
        } else {
            $aktualSelesai = "00:00:00";
        }
        
        // Get Lama
        $lama = $this->getTimeDiff($jam_mulai, $aktualSelesai);

        // Get Waktu Tambah
        $seconds_jam_mulai = strtotime($jam_mulai);
        $seconds_aktualSelesai = strtotime($aktualSelesai);
        $seconds_jam_selesai = strtotime($jam_selesai);
        
        if ($seconds_aktualSelesai >= $seconds_jam_selesai) {
            // echo "masuk";
            $hasil = $seconds_jam_selesai - $seconds_jam_mulai;
        }else{
            // echo "gk masuk";
            // Round time 15 mins
            $hasil = round(strtotime($aktualSelesai)/900)*900;
            $hasil = $hasil - $seconds_jam_mulai;
        }
        // 3600 is 1 hour
        $waktuTambah = $hasil/3600;
        
        $data = [
            "employee"=>$employee->NAME,
            "idEmployee"=>$employee->ID,
            "bagian"=>$employee->Bagian,
            "aktualMulai"=>$jam_mulai,
            "aktualSelesai"=>$aktualSelesai,
            "lama"=>$lama,
            "waktuTambah"=>$waktuTambah
        ];
        $data_return = $this->SetReturn(true, "Get Employee Success", $data, null);
        return response()->json($data_return,200);
    }

    public function SimpanLemburKerja(Request $request){
        $tanggal = $request->tanggal;
        $jam_mulai = $request->jam_mulai;
        $jam_selesai = $request->jam_selesai;
        $catatan = $request->catatan;
        $items = $request->items;

        // Check Data
        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = $this->SetReturn(false, "Save Lembur Kerja Failed.",null,["tanggal"=>"tanggal Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if jam_mulai null or blank
        if (is_null($jam_mulai) or $jam_mulai == "") {
            $data_return = $this->SetReturn(false, "Save Lembur Kerja Failed.",null,["jam_mulai"=>"jam_mulai Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if jam_selesai null or blank
        if (is_null($jam_selesai) or $jam_selesai == "") {
            $data_return = $this->SetReturn(false, "Save Lembur Kerja Failed.",null,["jam_selesai"=>"jam_selesai Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("idEmployee",$item) and array_key_exists("aktualMulai",$item) and array_key_exists("aktualSelesai",$item) and array_key_exists("waktuTambah",$item)){
                // Check idEmployee in item
                if (is_null($item['idEmployee']) or strval($item['idEmployee']) == ""){
                    $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed",null, ["idEmployee"=>"There still any blank or null value on idEmployee"]);
                    return response()->json($data_return,400);
                } else {
                    // Check If Employee exists
                    $employee = $this->GetEmployee($item['idEmployee']);
                    if (count($employee) == 0){
                        $_idEmployee = $item['idEmployee'];
                        $data_return = $this->SetReturn(false, "Save Lembur Kerja Failed. Employee with ID '$_idEmployee' Not Found", null, null);
                        return response()->json($data_return,404);
                    }
                }
                // Check aktualMulai in item
                if (is_null($item['aktualMulai']) or $item['aktualMulai'] == ""){
                    $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed",null, ["aktualMulai"=>"There still any blank or null value on aktualMulai"]);
                    return response()->json($data_return,400);
                }
                // Check aktualSelesai in item
                if (is_null($item['aktualSelesai']) or $item['aktualSelesai'] == ""){
                    $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed",null, ["aktualSelesai"=>"There still any blank or null value on aktualSelesai"]);
                    return response()->json($data_return,400);
                }
                // Check waktuTambah in item
                if (is_null($item['waktuTambah']) or $item['waktuTambah'] == ""){
                    $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed",null, ["waktuTambah"=>"There still any blank or null value on waktuTambah"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS
                $TotalQTY += 1;

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Save Lembur Kerja Failed",null, ["items"=>"Items required idEmployee, aktualMulai, aktualSelesai, waktuTambah"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        // Get last id of overtime
        $idOvertime = overtime::orderBy('ID','DESC')->first();
        $idOvertime = $idOvertime->ID + 1;
        
        // Generate OverTime attribute
        $OverTime = $this->getTimeDiff($jam_mulai, $jam_selesai);
        
        // Insert To Overtime
        $insertOvertime = overtime::create([
            "ID"=>$idOvertime,
            "UserName"=>Auth::user()->name,
            "TransDate"=>$tanggal,
            "TimeStart"=>$jam_mulai,
            "TimeEnd"=>$jam_selesai,
            "Reason"=>$catatan,
            "OverTime"=>$OverTime
        ]);

        // Insert To Overtimeitem and Update Workhour
        foreach ($items as $key => $item) {
            // Insert Overtimeitem
            $insertOvertimeitem = overtimeitem::create([
                "IDM"=>$idOvertime,
                "Ordinal"=>$key+1,
                "Employee"=>$item['idEmployee'],
                "ActualFrom"=>$item['aktualMulai'],
                "ActualTo"=>$item['aktualSelesai'],
                "OverTime"=>$item['waktuTambah'],
                "OverTimeAdd"=>"0",
                "Bonus"=>"0"
            ]);

            // Update Workhour
            $updateWorkHour = workhour::where("Employee",$item['idEmployee'])->where("TransDate",$tanggal)->update([
                "Overtime"=>$item['waktuTambah']
            ]);
        }

        $data_return = $this->SetReturn(true,"Save Lembur Kerja Success",["ID"=>$idOvertime], null);
        return response()->json($data_return,200);
    }

    public function SearchLemburKerja(Request $request){
        $keyword = $request->keyword;
        
        // Get Lembur Kerja
        $lemburKerja = overtime::where('ID',$keyword)->first();
        if (is_null($lemburKerja)){
            $data_return = $this->SetReturn(false, "Lembur Kerja with ID : '$keyword' Not Found", null, null);
            return response()->json($data_return,404);
        }
        // Get Lembur Kerja Item
        $lemburKerjaItem = overtimeitem::where('IDM',$lemburKerja->ID)->get();
        $dataLemburKerjaItem = [];
        foreach ($lemburKerjaItem as $key => $item) {
            $temp = [];
            // Get Employee
            $karyawan = $this->GetEmployee($item->Employee)[0];
            $temp['karyawan'] = $karyawan->NAME;
            $temp['idEmployee'] = $karyawan->ID;
            $temp['bagian'] = $karyawan->Bagian;
            $temp['aktualMulai'] = $item['ActualFrom'];
            $temp['aktualSelesai'] = $item['ActualTo'];
            $temp['lama'] = $this->getTimeDiff($item['ActualFrom'], $item['ActualTo']);
            $temp['waktuTambah'] = $item['OverTime'];
            $temp['bonus'] = $item['Bonus'];
            array_push($dataLemburKerjaItem, $temp);
        }
        $data = [
            "idLemburKerja"=>$lemburKerja->ID,
            "tanggal"=>$lemburKerja->TransDate,
            "jam_mulai"=>$lemburKerja->TimeStart,
            "jam_selesai"=>$lemburKerja->TimeEnd,
            "catatan"=>$lemburKerja->Reason,
            "lemburKerjaItem"=>$dataLemburKerjaItem
        ];
        $data_return = $this->SetReturn(true, "Lembur Kerja with ID : '$keyword' Found", $data, null);
        return response()->json($data_return,200);
    }

    public function UpdateLemburKerja(Request $request){
        $idLemburKerja = $request->idLemburKerja;
        $tanggal = $request->tanggal;
        $jam_mulai = $request->jam_mulai;
        $jam_selesai = $request->jam_selesai;
        $catatan = $request->catatan;
        $items = $request->items;

        // Check Data
        // Check if idLemburKerja null or blank
        if (is_null($idLemburKerja) or $idLemburKerja == "") {
            $data_return = $this->SetReturn(false, "Update Lembur Kerja Failed.",null,["idLemburKerja"=>"idLemburKerja Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = $this->SetReturn(false, "Update Lembur Kerja Failed.",null,["tanggal"=>"tanggal Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if jam_mulai null or blank
        if (is_null($jam_mulai) or $jam_mulai == "") {
            $data_return = $this->SetReturn(false, "Update Lembur Kerja Failed.",null,["jam_mulai"=>"jam_mulai Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if jam_selesai null or blank
        if (is_null($jam_selesai) or $jam_selesai == "") {
            $data_return = $this->SetReturn(false, "Update Lembur Kerja Failed.",null,["jam_selesai"=>"jam_selesai Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("idEmployee",$item) and array_key_exists("aktualMulai",$item) and array_key_exists("aktualSelesai",$item) and array_key_exists("waktuTambah",$item)){
                // Check idEmployee in item
                if (is_null($item['idEmployee']) or strval($item['idEmployee']) == ""){
                    $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed",null, ["idEmployee"=>"There still any blank or null value on idEmployee"]);
                    return response()->json($data_return,400);
                } else {
                    // Check If Employee exists
                    $employee = $this->GetEmployee($item['idEmployee']);
                    if (count($employee) == 0){
                        $_idEmployee = $item['idEmployee'];
                        $data_return = $this->SetReturn(false, "Update Lembur Kerja Failed. Employee with ID '$_idEmployee' Not Found", null, null);
                        return response()->json($data_return,404);
                    }
                }
                // Check aktualMulai in item
                if (is_null($item['aktualMulai']) or $item['aktualMulai'] == ""){
                    $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed",null, ["aktualMulai"=>"There still any blank or null value on aktualMulai"]);
                    return response()->json($data_return,400);
                }
                // Check aktualSelesai in item
                if (is_null($item['aktualSelesai']) or $item['aktualSelesai'] == ""){
                    $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed",null, ["aktualSelesai"=>"There still any blank or null value on aktualSelesai"]);
                    return response()->json($data_return,400);
                }
                // Check waktuTambah in item
                if (is_null($item['waktuTambah']) or $item['waktuTambah'] == ""){
                    $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed",null, ["waktuTambah"=>"There still any blank or null value on waktuTambah"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS
                $TotalQTY += 1;

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Update Lembur Kerja Failed",null, ["items"=>"Items required idEmployee, aktualMulai, aktualSelesai, waktuTambah"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        // Check if overtime is exists
        $checkOvertime = overtime::where('ID',$idLemburKerja)->first();
        if (is_null($checkOvertime)){
            $data_return = $this->SetReturn(false, "Update Lembur Kerja Failed. Overtime with ID '$idLemburKerja' Not Found", null, null);
            return response()->json($data_return,404);
        }
        
        // Generate OverTime attribute
        $OverTime = $this->getTimeDiff($jam_mulai, $jam_selesai);
        
        // Update To Overtime
        $updateOvertime = overtime::where('ID',$idLemburKerja)->update([
            "UserName"=>Auth::user()->name,
            "TransDate"=>$tanggal,
            "TimeStart"=>$jam_mulai,
            "TimeEnd"=>$jam_selesai,
            "Reason"=>$catatan,
            "OverTime"=>$OverTime
        ]);

        // Delete Overtimeitem with that id
        $deleteOvertimeItem =  overtimeitem::where('IDM',$idLemburKerja)->delete();

        // Insert To Overtimeitem and Update Workhour
        foreach ($items as $key => $item) {
            // Insert Overtimeitem
            $insertOvertimeitem = overtimeitem::create([
                "IDM"=>$idLemburKerja,
                "Ordinal"=>$key+1,
                "Employee"=>$item['idEmployee'],
                "ActualFrom"=>$item['aktualMulai'],
                "ActualTo"=>$item['aktualSelesai'],
                "OverTime"=>$item['waktuTambah'],
                "OverTimeAdd"=>"0",
                "Bonus"=>"0"
            ]);

            // Update Workhour
            $updateWorkHour = workhour::where("Employee",$item['idEmployee'])->where("TransDate",$tanggal)->update([
                "Overtime"=>$item['waktuTambah']
            ]);
        }

        $data_return = $this->SetReturn(true,"Update Lembur Kerja Success",["ID"=>$idLemburKerja], null);
        return response()->json($data_return,200);
    }
}

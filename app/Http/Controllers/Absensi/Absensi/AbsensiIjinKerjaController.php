<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use DateTime;

// Model
use App\Models\erp\absent;
use App\Models\erp\absentitem;
use App\Models\erp\workhour;
use App\Models\erp\employee;
use App\Models\erp\lastid;


class AbsensiIjinKerjaController extends Controller{
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
        ->where("E.Active", "=", "Y")
        ->orWhere("E.ID","=","".$keyword)
        ->orderBy("E.Department","ASC")
        ->get();
        return $employee;
    }
    // END REUSABLE FUNCTION
    
    public function Index(){
        $datenow = date('Y-m-d');
        // Get jenis ijinkerja
        $jenisijin = FacadesDB::connection('erp')
        ->select("
            SELECT
                ID,
                Description 
            FROM
                AbsentType 
            ORDER BY
                Description
        ");
        return view('Absensi.Absensi.IjinKerja.index', compact('datenow','jenisijin'));
    }

    public function SearchIjinKerja(Request $request){
        $keyword = $request->keyword;
        
        // Get header
        $ijinKerja = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.*,
                E.Description Karyawan,
                E.Rank,
                D.Description AS Bagian
            FROM
                Absent A
                JOIN Employee E ON A.Employee = E.ID 
                JOIN department D ON E.Department = D.ID
            WHERE
                A.ID = '$keyword'
        ");
        if (count($ijinKerja) == 0 ) {
            $data_return = $this->SetReturn(false, "Ijin Kerja Not Found", null, null);
            return response()->json($data_return,404);
            // return '404';
        }
        $ijinKerja = $ijinKerja[0];

        // Get Transaction
        $transaction = FacadesDB::connection('erp')
        ->select("
            SELECT
                *,
                DATE_FORMAT( TransDate, '%d-%m-%Y' ) AS tgl 
            FROM
                AbsentItem 
            WHERE
                IDM = '$keyword'
        ");
        // Get History
        $idEmployee = $ijinKerja->Employee;
        $history = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                T.Description,
                DATE_FORMAT( I.TransDate, '%d-%m-%Y' ) AS tgl,
                I.TimeFrom,
                I.TimeTo 
            FROM
                Absent A
                JOIN AbsentItem I ON A.ID = I.IDM
                JOIN AbsentType T ON A.Type = T.ID 
            WHERE
                A.Employee = '$idEmployee' 
                AND A.Type <> 3 
                AND A.Active <> 'C' 
            ORDER BY
                A.ID,
                I.Ordinal
        ");
        $ijinKerja->transaction = $transaction;
        $ijinKerja->history = $history;
        $data_return = $this->SetReturn(true, "Data Found", $ijinKerja, null);
        return response()->json($data_return,200);
    }

    public function SearchEmployee(Request $request){
        $employeeSW = $request->employeeSW;
        if (is_null($employeeSW) or $employeeSW == "") {
            $data_return = $this->SetReturn(false, "employeeSW can't be null or blank", null, ["employeeSW"=>"employeeSW null or blank"]);
            return response()->json($data_return,400);
        }
        // Get Employee
        $employee = $this->GetEmployee($employeeSW);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Employee Not Found", null, null);
            return response()->json($data_return,404);
        }
        $employee = $employee[0];
        // Get history
        $idEmployee = $employee->ID;
        $history = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                T.Description,
                DATE_FORMAT( I.TransDate, '%d-%m-%Y' ) AS tgl,
                I.TimeFrom,
                I.TimeTo 
            FROM
                Absent A
                JOIN AbsentItem I ON A.ID = I.IDM
                JOIN AbsentType T ON A.Type = T.ID 
            WHERE
                A.Employee = '$idEmployee' 
                AND A.Type <> 3 
                AND A.Active <> 'C' 
            ORDER BY
                A.ID,
                I.Ordinal
        ");
        $employee->history = $history;
        // Return
        $data_return = $this->SetReturn(true, "Employee Found", $employee, null);
        return response()->json($data_return, 200);
    }

    public function SaveIjinKerja(Request $request){
        $id_karyawan = $request->idEmployee;
        $jenis = $request->jenisIjin;
        $tgl_awal = $request->tanggalMulai;
        $tgl_akhir = $request->tanggalSelesai;	
        $jam_awal = $request->waktuMulai;
        $jam_akhir = $request->waktuSelesai;
        $ijin_before = $request->ijinSebelumnya;	
        $notif = $request->pemberitahuan;
        $catatan = $request->catatan;
        $rank = $request->rank;

        if (is_null($id_karyawan) or $id_karyawan == NULL) {
            $data_return = $this->SetReturn(false, "idEmployee can't be null or blank", null, ["idEmployee"=>"idEmployee null or blank"]); 
            return response()->json($data_return,400);
        }
        
        if ($rank != NULL and $rank === 'Operator' or $rank === 'Driver' or $rank === 'Cleaning Service' or $rank === 'Security' or $rank === 'Kepala Bagian'){
            if ($id_karyawan == '1195' or $id_karyawan == '726' or $id_karyawan == '639'){
                $jampul = '17:00:00';
            } else {
                $jampul = '16:30:00';
            }
        } else {
            $jampul = '17:00:00';
        }

        // Get Last Absent ID
        $idAbsent = absent::orderBy('ID','DESC')->first();
        $idAbsent = $idAbsent->ID+1;
        // Insert to Absent
        $insertAbsent = absent::create([
            "ID"=>$idAbsent, 
            "UserName"=>Auth::user()->name, 
            "Employee"=>$id_karyawan, 
            "DateStart"=>$tgl_awal, 
            "DateEnd"=>$tgl_akhir, 
            "Type"=>$jenis, 
            "TimeStart"=>$jam_awal, 
            "TimeEnd"=>$jam_akhir, 
            "Active"=>"A", 
            "Reason"=>$catatan,  
            "Notification"=>$notif, 
            "InformBefore"=>$ijin_before
        ]);

        // Get WorkDate
        $workDate = FacadesDB::connection('erp')
        ->select("
            SELECT
                Day,
                TransDate
            FROM
                WorkDate 
            WHERE
                TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                AND (Holiday = 'N' or Remarks LIKE '%Cuti Bersama%')
        ");
        
        // Loop for insert initial absentitem
        foreach ($workDate as $key => $value) {
            $tgl1 = strtotime($tgl_awal);
            $tgl2 = strtotime($tgl_akhir);
            $jam1 = strtotime($jam_awal);
            $jam2 = strtotime($jam_akhir);
            $hasil = ($jam2 - $jam1)/3600;
            if ($tgl2 == $tgl1){
                // Insert to AbsentItem
                $insertAbsentItem = absentitem::create([
                    "IDM"=>$idAbsent, 
                    "Ordinal"=>$key+1, 
                    "Employee"=>$id_karyawan, 
                    "TransDate"=>$value->TransDate, 
                    "TimeFrom"=>$jam_awal, 
                    "TimeTo"=>$jam_akhir, 
                    "Absent"=>'0', 
                    "ActualFrom"=>$jam_awal, 
                    "ActualTo"=>$jam_akhir, 
                    "AllDay"=> $hasil > 8 ? "Y" : "N", 
                    "Active"=>"Y"
                ]);
            } else {
                if ($value->Day == 'Sabtu') {
                    $jamakhir = '14:00:00';
                } else{
                    $jamakhir = $jampul;
                }
                // Insert to AbsentItem
                $insertAbsentItem = absentitem::create([
                    "IDM"=>$idAbsent, 
                    "Ordinal"=>$key+1, 
                    "Employee"=>$id_karyawan, 
                    "TransDate"=>$value->TransDate, 
                    "TimeFrom"=>'08:00:00', 
                    "TimeTo"=>$jamakhir, 
                    "Absent"=>'0', 
                    "ActualFrom"=>'08:00:00', 
                    "ActualTo"=>$jamakhir, 
                    "AllDay"=> "Y", 
                    "Active"=>"Y"
                ]);
            }
        }

        // Loop to update absent in absentitem if absent type is 1,2,3,4,6,14,21
        foreach ($workDate as $key => $value) {
            if ($jenis == 1 or $jenis == 2 or $jenis == 3 or $jenis ==  4 or $jenis == 6 or $jenis == 14 or $jenis == 16 or $jenis == 21) {
                // Find  Absentitem to update 'Absent' field
                $absentItem = absentitem::where('Employee',$id_karyawan)->where('TransDate',$value->TransDate)->first();

                // Check if absent time is below 12:00:00 and above 12:30:00
                if ($absentItem->TimeFrom <= '12:00:00' and $absentItem->TimeTo >= '12:30:00') {
                    $jam1 = strtotime($absentItem->TimeFrom);
                    $jam2 = round(strtotime($absentItem->TimeTo)/900)*900;
                    $hasil = (($jam2 - $jam1)/3600)-0.5;   
                } else {
                    $jam1 = strtotime($absentItem->TimeFrom);
                    $jam2 = round(strtotime($absentItem->TimeTo)/900)*900;
                    $hasil = ($jam2 - $jam1)/3600;
                }

                if ($jenis == 21) {
                    $hasil = $hasil - 1;
                }

                // Update 'Absent'
                $updateAbsentItem = AbsentItem::where('Employee',$id_karyawan)->where('TransDate',$value->TransDate)
                ->update([
                    "Absent"=>$hasil
                ]);
            }
        }

        // Update lastid
        $updateLastid = lastid::where('Module','Absent')->update(["Last"=>$idAbsent]);

        // Get Transaction
        $transaction = FacadesDB::connection('erp')
        ->select("
            SELECT
                *,
                DATE_FORMAT( TransDate, '%d-%m-%Y' ) AS tgl 
            FROM
                AbsentItem 
            WHERE
                IDM = '$idAbsent'
        ");

        // Get History
        $history = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                T.Description,
                DATE_FORMAT( I.TransDate, '%d-%m-%Y' ) AS tgl,
                I.TimeFrom,
                I.TimeTo 
            FROM
                Absent A
                JOIN AbsentItem I ON A.ID = I.IDM
                JOIN AbsentType T ON A.Type = T.ID 
            WHERE
                A.Employee = '$id_karyawan' 
                AND A.Type <> 3 
                AND A.Active <> 'C' 
            ORDER BY
                A.ID,
                I.Ordinal
        ");
        $data_return = $this->SetReturn(true, "Data Found", ['id'=>$idAbsent, 'postingStatus'=>'A', "transaction"=>$transaction,"history"=>$history], null);
        return response()->json($data_return,200);
    }

    public function UpdateIjinKerja(Request $request){
        $idAbsent = $request->idAbsent;
        $id_karyawan = $request->idEmployee;
        $jenis = $request->jenisIjin;
        $tgl_awal = $request->tanggalMulai;
        $tgl_akhir = $request->tanggalSelesai;	
        $jam_awal = $request->waktuMulai;
        $jam_akhir = $request->waktuSelesai;
        $ijin_before = $request->ijinSebelumnya;	
        $notif = $request->pemberitahuan;
        $catatan = $request->catatan;
        $rank = $request->rank;

        if (is_null($id_karyawan) or $id_karyawan == NULL) {
            $data_return = $this->SetReturn(false, "idEmployee can't be null or blank", null, ["idEmployee"=>"idEmployee null or blank"]); 
            return response()->json($data_return,400);
        }
        
        if ($rank != NULL and $rank === 'Operator' or $rank === 'Driver' or $rank === 'Cleaning Service' or $rank === 'Security' or $rank === 'Kepala Bagian'){
            if ($id_karyawan == '1195' or $id_karyawan == '726' or $id_karyawan == '639'){
                $jampul = '17:00:00';
            } else {
                $jampul = '16:30:00';
            }
        } else {
            $jampul = '17:00:00';
        }

        // update Absent
        $updateAbsent = absent::where('ID',$idAbsent)
        ->update([
            "UserName"=>Auth::user()->name, 
            "Employee"=>$id_karyawan, 
            "DateStart"=>$tgl_awal, 
            "DateEnd"=>$tgl_akhir, 
            "Type"=>$jenis, 
            "TimeStart"=>$jam_awal, 
            "TimeEnd"=>$jam_akhir, 
            "Active"=>"A", 
            "Reason"=>$catatan,  
            "Notification"=>$notif, 
            "InformBefore"=>$ijin_before
        ]);

        // Delete Absentitem
        $deleteAbsentItem = absentitem::where('IDM',$idAbsent)->delete();

        // Get WorkDate
        $workDate = FacadesDB::connection('erp')
        ->select("
            SELECT
                Day,
                TransDate
            FROM
                WorkDate 
            WHERE
                TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                AND (Holiday = 'N' or Remarks LIKE '%Cuti Bersama%')
        ");
        
        // Loop for insert initial absentitem
        foreach ($workDate as $key => $value) {
            $tgl1 = strtotime($tgl_awal);
            $tgl2 = strtotime($tgl_akhir);
            $jam1 = strtotime($jam_awal);
            $jam2 = strtotime($jam_akhir);
            $hasil = ($jam2 - $jam1)/3600;
            if ($tgl2 == $tgl1){
                // Insert to AbsentItem
                $insertAbsentItem = absentitem::create([
                    "IDM"=>$idAbsent, 
                    "Ordinal"=>$key+1, 
                    "Employee"=>$id_karyawan, 
                    "TransDate"=>$value->TransDate, 
                    "TimeFrom"=>$jam_awal, 
                    "TimeTo"=>$jam_akhir, 
                    "Absent"=>'0', 
                    "ActualFrom"=>$jam_awal, 
                    "ActualTo"=>$jam_akhir, 
                    "AllDay"=> $hasil > 8 ? "Y" : "N", 
                    "Active"=>"Y"
                ]);
            } else {
                if ($value->Day == 'Sabtu') {
                    $jamakhir = '14:00:00';
                } else{
                    $jamakhir = $jampul;
                }
                // Insert to AbsentItem
                $insertAbsentItem = absentitem::create([
                    "IDM"=>$idAbsent, 
                    "Ordinal"=>$key+1, 
                    "Employee"=>$id_karyawan, 
                    "TransDate"=>$value->TransDate, 
                    "TimeFrom"=>'08:00:00', 
                    "TimeTo"=>$jamakhir, 
                    "Absent"=>'0', 
                    "ActualFrom"=>'08:00:00', 
                    "ActualTo"=>$jamakhir, 
                    "AllDay"=> "Y", 
                    "Active"=>"Y"
                ]);
            }
        }

        // Loop to update absent in absentitem if absent type is 1,2,3,4,6,14,21
        foreach ($workDate as $key => $value) {
            if ($jenis == 1 or $jenis == 2 or $jenis == 3 or $jenis ==  4 or $jenis == 6 or $jenis == 14 or $jenis == 16 or $jenis == 21) {
                // Find  Absentitem to update 'Absent' field
                $absentItem = absentitem::where('Employee',$id_karyawan)->where('TransDate',$value->TransDate)->first();

                // Check if absent time is below 12:00:00 and above 12:30:00
                if ($absentItem->TimeFrom <= '12:00:00' and $absentItem->TimeTo >= '12:30:00') {
                    $jam1 = strtotime($absentItem->TimeFrom);
                    $jam2 = round(strtotime($absentItem->TimeTo)/900)*900;
                    $hasil = (($jam2 - $jam1)/3600)-0.5;   
                } else {
                    $jam1 = strtotime($absentItem->TimeFrom);
                    $jam2 = round(strtotime($absentItem->TimeTo)/900)*900;
                    $hasil = ($jam2 - $jam1)/3600;
                }

                if ($jenis == 21) {
                    $hasil = $hasil - 1;
                }

                // Update 'Absent'
                $updateAbsentItem = AbsentItem::where('Employee',$id_karyawan)->where('TransDate',$value->TransDate)
                ->update([
                    "Absent"=>$hasil
                ]);
            }
        }

        // Get Transaction
        $transaction = FacadesDB::connection('erp')
        ->select("
            SELECT
                *,
                DATE_FORMAT( TransDate, '%d-%m-%Y' ) AS tgl 
            FROM
                AbsentItem 
            WHERE
                IDM = '$idAbsent'
        ");

        // Get History
        $history = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                T.Description,
                DATE_FORMAT( I.TransDate, '%d-%m-%Y' ) AS tgl,
                I.TimeFrom,
                I.TimeTo 
            FROM
                Absent A
                JOIN AbsentItem I ON A.ID = I.IDM
                JOIN AbsentType T ON A.Type = T.ID 
            WHERE
                A.Employee = '$id_karyawan' 
                AND A.Type <> 3 
                AND A.Active <> 'C' 
            ORDER BY
                A.ID,
                I.Ordinal
        ");
        $data_return = $this->SetReturn(true, "Update Ijin Kerja Success", ['id'=>$idAbsent, 'postingStatus'=>'A', "transaction"=>$transaction,"history"=>$history], null);
        return response()->json($data_return,200);
    }

    public function PostingIjinKerja(Request $request){
        $idAbsent = $request->idAbsent;

        // Get Absent
        $absent = absent::where('ID',$idAbsent)->first();
        
        // Check Absent if exists
        if (is_null($absent)) {
            $data_return = $this->SetReturn(false, "Absent Not Found. Posting Failed", null, null);
            return response()->json($data_return,404);
        }

        // Get Absentitem
        $absentItem = absentitem::where('IDM', $idAbsent)->get();

        $updateStatus = null;

        // IdEmployee
        $idEmployee = $absent->Employee;

        // Get CutiTahunan yang telah terpakai pada absentitem
        $cutiTahunanTerpakai = FacadesDB::connection('erp')
        ->select("
            SELECT count(*) AS cutiTahunanTerpakai FROM Absent A LEFT JOIN absentitem B ON A.ID = B.IDM WHERE A.Employee = '$idEmployee' AND A.Type = '5' AND A.Active = 'P'
        ");
        $cutiTahunanTerpakai = $cutiTahunanTerpakai[0];

        // Get Cuti Employee
        $cutiEmployee = FacadesDB::connection('erp')
        ->select("
            SELECT StartWork as ID, WorkRole FROM Employee WHERE ID = '$idEmployee'
        ");
        $cutiEmployee = $cutiEmployee[0];

        $date1 = new DateTime($cutiEmployee->ID);
        $date2 = new DateTime($absent->DateStart);
        $intervalTanggalMasukDanCuti = $date1->diff($date2);
        $JumlahTahunKerja =  $intervalTanggalMasukDanCuti->y;

        foreach ($absentItem as $key => $value) {
            // Get Workhour
            $valueTransDate = $value->TransDate;
            $workHour = FacadesDB::connection('erp')
            ->select("
                SELECT count(*) as ID FROM workhour WHERE Employee = '$idEmployee' AND TransDate = '$valueTransDate'
            ");
            $workHour = $workHour[0];
            
            if ($workHour->ID > 0) {
                // check if absent Type is equal 1 it means employee is Sakit
                if ($absent->Type == 1) {
                    // check if WorkRole is Training
                    if ($cutiEmployee->WorkRole === 'Training') {
                        $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                            "AbsentPaid"=>$value->Absent,
                            "Note"=>$absent->Reason,
                        ]);
                        $updateStatus = true;
                    } else {
                        $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                            "Absent"=>$value->Absent,
                            "Note"=>$absent->Reason,
                        ]);
                        $updateStatus = true;
                    }
                }

                // check if absent type in 2,3,4,6 it means employee is Ijin Jam, Terlambat, Absen Alpha, Absen Ijin
                if (in_array($absent->Type, [2,3,4,6])) {
                    $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                        "Absent"=>$value->Absent,
                        "Note"=>$absent->Reason,
                    ]);
                    $updateStatus = true;
                }

                // check if absent type in 14 it means employee is Ijin Jam 1 Jam
                if ($absent->Type == 14) {
                    $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                        "Absent1Hour"=>$value->Absent,
                        "Note"=>$absent->Reason,
                    ]);
                    $updateStatus = true;
                }

                // check if absent type in 5 it means employee is Cuti Tahunan
                if ($absent->Type == 5) {
                    // check jumlah Tahun kerja is must be 1 or more and cuti tahunan terpakai is less than 2
                    if ($JumlahTahunKerja >= 1 and $cutiTahunanTerpakai->cutiTahunanTerpakai < 2){
                        // kalkulasi sisa cuti tahunan yang tersedia
                        $sisaCutiTahunan = 2 - $cutiTahunanTerpakai->cutiTahunanTerpakai;
                        // check if sisaCutiTahunan is enough to use
                        if ($sisaCutiTahunan < count($absentItem)){
                            // return warning because sisaCutiTahunan is not enough to use
                            $data_return = $this->SetReturn(false, "Sisa Cuti Tahunan tidak mencukupi untuk menggunakan Cuti Tahunan", null, null);
                            return response()->json($data_return,400);
                        }
                        $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                            "Cuti"=>'1',
                            "Note"=>$absent->Reason,
                        ]);
                        $updateStatus = true;
                    } else {
                        // return warning because maybe that employee has less than 1 year of work or cuti tahunan terpakai is 2 or more
                        $data_return = $this->SetReturn(false, "Karyawan ini tidak dapat menggunakan Cuti Tahunan. Karyawan ini memiliki Cuti Tahunan yang sudah terpakai", null, null);
                        return response()->json($data_return,400);
                    }

                }

                // Check if absent type is 13 it means employee is Cuti Pengganti
                if ($absent->Type == 13) {
                    $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                        "Cuti"=>'1',
                        "Note"=>$absent->Reason,
                    ]);
                    $updateStatus = true;
                }

                // check if absent type in 7,8,9,10,11,12,15,17 it means employee is Cuti Khusus (7 Cuti Melahirkan, 8 Cuti Menikah, 9 Cuti Berduka, 10 Cuti Anak Khitan, 11 Cuti Nikah Anak, 12 Keguguran, 15 Cuti Baptis, 17 Cuti Istri Melahirkan)
                if (in_array($absent->Type, [7,8,9,10,11,12,15,17])) {
                    // check if Jumlah Tahun Kerja is must be 2 or more
                    if ($JumlahTahunKerja >= 2){
                        $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                            "CutiKhusus"=>"1",
                            "Note"=>$absent->Reason,
                        ]);
                        $updateStatus = true;
                    } else {
                        // return warning because maybe that employee has less than 2 year of work
                        $data_return = $this->SetReturn(false, "Karyawan ini tidak dapat menggunakan Cuti Khusus. Karyawan ini belum bekerja lebih dari 2 tahun", null, null);
                        return response()->json($data_return,400);
                    }
                }

                // check if absent type is equal 16 it means employee is cuti bersama
                if ($absent->Type == 16) {
                    // Cuti Bersama tidak masuk workhour
                    $updateStatus = true;
                }

                // Check if absent type is equal 18 it means employee is cuti CS
                if ($absent->Type == 18) {
                    $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                        "CutiKhusus"=>'1',
                        "Note"=>$absent->Reason,
                    ]);
                    $updateStatus = true;
                }

                // check if absent type is equal 19 it means employee is Absen Sakit
                if ($absent->Type == 19){
                    $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                        "AbsentDay"=>"1",
                        "Absent"=>$value->Absent,
                        "Note"=>$absent->Reason,
                    ]);
                    $updateStatus = true;
                } 

                // check if absent type is equal 20 it means employee is Covid
                if ($absent->Type == 20) {
                    // Covid tidak masuk workhour
                    $updateStatus = true;
                } 

                // check if absent type is equal 21 it means employee is Terlambat > jam 9
                if ($absent->Type == 21){
                    $updateWorkHour = workhour::where('Employee',$idEmployee)->where('TransDate',$value->TransDate)->update([
                        "late"=>"1",
                        "Absent"=>$value->Absent,
                        "Note"=>$absent->Reason,
                    ]);
                    $updateStatus = true;
                }
            } else {
                // check if absent Type is equal 1 it means employee is Sakit
                if ($absent->Type == 1) {
                    // check if WorkRole is Training
                    if ($cutiEmployee->WorkRole === 'Training') {
                        $insertWorkHour = workhour::create([
                            "Absent"=>$value->Absent,
                            "Note"=>$absent->Reason,
                            "Employee"=>$idEmployee,
                            "TransDate"=>$value->TransDate
                        ]);
                        $updateStatus = true;
                    } else {
                        $insertWorkHour = workhour::create([
                            "AbsentPaid"=>$value->Absent,
                            "Note"=>$absent->Reason,
                            "Employee"=>$idEmployee,
                            "TransDate"=>$value->TransDate
                        ]);
                        $updateStatus = true;
                    }
                }

                // check if absent type in 2,3,4,6 it means employee is Ijin Jam, Terlambat, Absen Alpha, Absen Ijin
                if (in_array($absent->Type, [2,3,4,6])) {
                    $insertWorkHour = workhour::create([
                        "Absent"=>$value->Absent,
                        "Note"=>$absent->Reason,
                        "Employee"=>$idEmployee,
                        "TransDate"=>$value->TransDate
                    ]);
                    $updateStatus = true;
                }

                // check if absent type in 14 it means employee is Ijin Jam 1 Jam
                if ($absent->Type == 14) {
                    $insertWorkHour = workhour::create([
                        "Absent1Hour"=>$value->Absent,
                        "Note"=>$absent->Reason,
                        "Employee"=>$idEmployee,
                        "TransDate"=>$value->TransDate
                    ]);
                    $updateStatus = true;
                }

                // check if absent type in 5 it means employee is Cuti Tahunan
                if ($absent->Type == 5) {
                    // check jumlah Tahun kerja is must be 1 or more and cuti tahunan terpakai is less than 2
                    if ($JumlahTahunKerja >= 1 and $cutiTahunanTerpakai->cutiTahunanTerpakai <2){
                        // kalkulasi sisa cuti tahunan yang tersedia
                        $sisaCutiTahunan = 2 - $cutiTahunanTerpakai->cutiTahunanTerpakai;
                        // check if sisaCutiTahunan is enough to use
                        if ($sisaCutiTahunan < count($absentItem)){
                            // return warning because sisaCutiTahunan is not enough to use
                            $data_return = $this->SetReturn(false, "Sisa Cuti Tahunan tidak mencukupi untuk menggunakan Cuti Tahunan", null, null);
                            return response()->json($data_return,400);
                        }
                        $insertWorkHour = workhour::create([
                            "Cuti"=>"1",
                            "Note"=>$absent->Reason,
                            "Employee"=>$idEmployee,
                            "TransDate"=>$value->TransDate
                        ]);
                        $updateStatus = true;
                    } else {
                        // return warning because maybe that employee has less than 1 year of work or cuti tahunan terpakai is 2 or more
                        $data_return = $this->SetReturn(false, "Karyawan ini tidak dapat menggunakan Cuti Tahunan. Karyawan ini memiliki Cuti Tahunan yang sudah terpakai", null, null);
                        return response()->json($data_return,400);
                    }
                }

                // check if absent type in 13 it means employee is Cuti Pengganti
                if ($absent->Type == 13) {
                    $insertWorkHour = workhour::create([
                        "Cuti"=>"1",
                        "Note"=>$absent->Reason,
                        "Employee"=>$idEmployee,
                        "TransDate"=>$value->TransDate
                    ]);
                    $updateStatus = true;
                }


                // check if absent type in 7,8,9,10,11,12,15,17 it means employee is Cuti Khusus (7 Cuti Melahirkan, 8 Cuti Menikah, 9 Cuti Berduka, 10 Cuti Anak Khitan, 11 Cuti Nikah Anak, 12 Keguguran, 15 Cuti Baptis, 17 Cuti Istri Melahirkan)

                if (in_array($absent->Type, [7,8,9,10,11,12,15,17])) {
                    // check if Jumlah Tahun Kerja is must be 2 or more
                    if ($JumlahTahunKerja >= 2){
                        $insertWorkHour = workhour::create([
                            "CutiKhusus"=>"1",
                            "Note"=>$absent->Reason,
                            "Employee"=>$idEmployee,
                            "TransDate"=>$value->TransDate
                        ]);
                        $updateStatus = true;
                    } else {
                        // return warning because maybe that employee has less than 2 year of work
                        $data_return = $this->SetReturn(false, "Karyawan ini tidak dapat menggunakan Cuti Khusus. Karyawan ini belum bekerja lebih dari 2 tahun", null, null);
                        return response()->json($data_return,400);
                    }
                }

                // check if absent type is equal 16 it means employee is cuti bersama
                if ($absent->Type == 16) {
                    // Cuti Bersama tidak masuk workhour
                    $updateStatus = true;
                }

                // Check if absent type is equal 18 it means employee is cuti CS
                if ($absent->Type == 18) {
                    $insertWorkHour = workhour::create([
                        "CutiKhusus"=>"1",
                        "Note"=>$absent->Reason,
                        "Employee"=>$idEmployee,
                        "TransDate"=>$value->TransDate
                    ]);
                    $updateStatus = true;
                } 

                // check if absent type is equal 19 it means employee is Absen Sakit
                if ($absent->Type == 19){
                    $insertWorkHour = workhour::create([
                        "AbsentDay"=>"Y",
                        "Absent"=>$value->Absent,
                        "Note"=>$absent->Reason,
                        "Employee"=>$idEmployee,
                        "TransDate"=>$value->TransDate
                    ]);
                    $updateStatus = true;
                } 

                // check if absent type is equal 20 it means employee is Covid
                if ($absent->Type == 20) {
                    // Covid tidak masuk workhour
                    $updateStatus = true;
                } 

                // check if absent type is equal 21 it means employee is Terlambat > jam 9
                if ($absent->Type == 21){
                    $insertWorkHour = workhour::create([
                        "late"=>"1",
                        "Absent"=>$value->Absent,
                        "Note"=>$absent->Reason,
                        "Employee"=>$idEmployee,
                        "TransDate"=>$value->TransDate
                    ]);
                    $updateStatus = true;
                }
            }
        }

        if ($updateStatus != true) {
            $data_return = $this->SetReturn(false, "Something went wrong on posting. Posting Failed", null, null);
            return response()->json($data_return,500);
        }
        // Update absent Status to P
        $updateAbsent = absent::where('ID',$idAbsent)->update([
            "Active"=>'P',
            "PostDate"=>date("Y-m-d H:i:s")
        ]);
        $data_return = $this->SetReturn(true, "Posting Ijin Kerja Success", ['id'=>$idAbsent,'postingStatus'=>"P"], null);
        return response()->json($data_return,200);

    }

    public function GetTanggal(Request $request){
        $tanggal = $request->tanggal;

        // GetWorkDate
        $workDate = FacadesDB::connection('erp')
        ->select("
            SELECT 
                Day
            FROM
                WorkDate 
            WHERE
                TransDate = '$tanggal'
        ");
        $workDate = $workDate[0];
        $data_return = $this->SetReturn(true, "WorkDate Found", $workDate, null);
        return response()->json($data_return,200);
    }

    public function GetKurang(Request $request){
        $employee = $request->employee;
        $tanggalMulai = $request->tangalMulai;
        $data = FacadesDB::connection('erp')
        ->select("
            SELECT 
                COUNT(ID) AS ID 
            From 
                absent 
            WHERE 
                Employee = '$employee' AND DateStart = '$tanggalMulai' AND Type = 3
        ");
        $data = $data[0];
        if ($data->ID > 0) {
            $data_return = $this->SetReturn(true, "Get Kurang Success", $data, null);
            return response()->json($data_return,200);
        }
        $data_return = $this->SetReturn(false, "Get Kurang Failed", $data, null);
        return response()->json($data_return,200);
    }
}

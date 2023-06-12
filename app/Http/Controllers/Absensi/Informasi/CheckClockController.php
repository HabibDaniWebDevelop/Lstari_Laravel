<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use DateTime;
use DateInterval;
use DatePeriod;

class CheckClockController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.Checkclock.index");
    }

    private function GetTidakFaceAbsent($tgl_awal, $tgl_akhir){
        $listTidakFaceAbsent = [];
        $getEmployee = FacadesDB::connection('erp')->select("
            SELECT
                A.ID,
                A.SW,
                A.Description AS Employee,
                B.Description AS Department,
                A.StartWork,
                A.WorkRole,
                A.Rank 
            FROM
                employee A
                JOIN department B ON A.Department = B.ID
            WHERE
                A.Active = 'Y'
        ");
        foreach ($getEmployee as $keyEmployee => $valueEmployee) {
            $idEmployee = $valueEmployee->ID;
            $jumlahTidakAbsen = 0;

            // Get Checklock
            $dataChecklock = [];
            $checkclock = FacadesDB::connection('erp')->select("
                SELECT
                    * 
                FROM
                    checkclock A 
                WHERE
                    A.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                    AND A.Employee = '$idEmployee' 
                    AND A.Machine <> 9
                GROUP BY
                    A.Type,
                    A.TransDate
            ");
            foreach ($checkclock as $keyCheckclock => $valueCheckclock) {
                $type = [];
                $idCheckclock = [];
                foreach ($checkclock as $keyCheckclock2 => $valueCheckclock2) {
                    if ($valueCheckclock2->TransDate == $valueCheckclock->TransDate) {
                        $type[] = $valueCheckclock2->Type;
                        $idCheckclock[] = $valueCheckclock2->ID;
                    }
                }
                $checkclockData = [
                    "tanggal"=>$valueCheckclock->TransDate,
                    "type"=>$type,
                    'id_checkclock'=>$idCheckclock
                ];
                if (!array_search($valueCheckclock->TransDate, array_column($dataChecklock, 'tanggal'))) {
                    $dataChecklock[] = $checkclockData;
                }
            }

            // loop data checkclock to check if this user is has absensi face
            $dataTidakAbsen = [];
            foreach ($dataChecklock as $key => $value) {
                if (!in_array('A',$value['type'])) {
                    $jumlahTidakAbsen+=1;
                    $dataTidakAbsen[] = $value;
                }
            }

            $dataEmployee = [
                "ID"=>$idEmployee,
                "SW"=>$valueEmployee->SW,
                "Employee"=>$valueEmployee->Employee,
                "Department"=>$valueEmployee->Department,
                "StartWork"=>$valueEmployee->StartWork,
                "WorkRole"=>$valueEmployee->WorkRole,
                "Rank"=>$valueEmployee->Rank,
                "Total"=>$jumlahTidakAbsen,
                'Detail_Tidak_Absen'=>$dataTidakAbsen
            ];
            if ($jumlahTidakAbsen > 0) {
                $listTidakFaceAbsent[] = $dataEmployee;
            }
        }
        return $listTidakFaceAbsent; 
    }

    public function GetCheclClock(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    E.Description Employee,
                    D.Description Department,
                    C.TransDate,
                    C.TransTime,
                    C.STATUS,
                    C.Type,
                    C.Machine,
                    C.ID 
                FROM
                    CheckClock C
                    JOIN Employee E ON C.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE 
                    C.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' 
                ORDER BY
                    Department,
                    Employee,
                    TransDate,
                    TransTime
            ");
            return response()->json(["tampil"=>$data],200); 
        } elseif ( $jenis == 2 ){
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    M.ID,
                    M.TransDate,
                    M.TransTime,
                    E.Description Employee,
                    D.Description Department,
                    M.STATUS 
                FROM
                    ClockManual M
                    JOIN Employee E ON M.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    M.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' 
                ORDER BY
                    Department,
                    Employee,
                    TransDate,
                    TransTime
            ");
            return response()->json(["tampil"=>$data],200); 
        } elseif ($jenis == 3){
            $data = $this->GetTidakFaceAbsent($tgl_awal, $tgl_akhir);
            return response()->json(["tampil"=>$data],200); 
        } else {
            return response()->json(["tampil"=>null],400); 
        }
    }

    public function DetailTidakFaceAbsenOld(Request $request){
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $idEmployee = $request->idEmployee;

        // Get Detail Tidak Absen Wajah
        $detailAbsensi = FacadesDB::connection('erp')->select("
            SELECT
                A.*,
                B.Description
            FROM
                CheckClock A
                JOIN employee B ON A.Employee = B.ID
            WHERE
                A.TransDate BETWEEN '$startDate' AND '$endDate' 
                -- AND A.Type = 'F' 
                AND A.Machine <> 9
                AND A.Employee = '$idEmployee'
            -- GROUP BY
            --     TransDate
        ");
        $detailAbsensiHTML = view('Absensi.Informasi.Checkclock.layoutDetail', compact('detailAbsensi'))->render();
        return response()->json(["detailAbsensi"=>$detailAbsensi,"detailAbsensiHTML"=>$detailAbsensiHTML],200); 
    }

    public function DetailTidakFaceAbsen(Request $request){
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $idEmployee = $request->idEmployee;
        // Get Checklock
        $dataChecklock = [];
        $checkclock = FacadesDB::connection('erp')->select("
            SELECT
                * 
            FROM
                checkclock A 
            WHERE
                A.TransDate BETWEEN '$startDate' and '$endDate'
                AND A.Employee = '$idEmployee' 
                AND A.Machine <> 9
            GROUP BY
                A.Type,
                A.TransDate
        ");
        foreach ($checkclock as $keyCheckclock => $valueCheckclock) {
            $type = [];
            foreach ($checkclock as $keyCheckclock2 => $valueCheckclock2) {
                if ($valueCheckclock2->TransDate == $valueCheckclock->TransDate) {
                    $type[] = $valueCheckclock2->Type;
                }
            }
            $checkclockData = [
                "tanggal"=>$valueCheckclock->TransDate,
                "type"=>$type
            ];
            if (!array_search($valueCheckclock->TransDate, array_column($dataChecklock, 'tanggal'))) {
                $dataChecklock[] = $checkclockData;
            }
        }

        // loop data checkclock to check if this user is has absensi face
        $dataTanggalTidakAbsen = [];
        foreach ($dataChecklock as $key => $value) {
            if (!in_array('A',$value['type'])) {
                $dataTanggalTidakAbsen[] = $value['tanggal'];
            }
        }

        // data Checkclock
        $dataChecklock = [];
        foreach ($dataTanggalTidakAbsen as $key => $value) {
            $checkclock = FacadesDB::connection('erp')->select("
                SELECT
                    A.*,
                    B.Description 
                FROM
                    checkclock A
                    JOIN employee B ON A.Employee = B.ID
                WHERE
                    A.TransDate = '$value'
                    AND A.Employee = '$idEmployee'
            ");
            foreach ($checkclock as $key2 => $value2) {
                $dataChecklock[] = $value2;
            }
        }
        $detailAbsensiHTML = view('Absensi.Informasi.Checkclock.layoutDetail', compact('dataChecklock'))->render();
        return response()->json(["detailAbsensi"=>$dataChecklock,"detailAbsensiHTML"=>$detailAbsensiHTML],200); 
    }
}

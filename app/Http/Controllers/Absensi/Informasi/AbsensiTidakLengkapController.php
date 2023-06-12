<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
use App\Models\erp\workhour;

class AbsensiTidakLengkapController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.AbsensiTidakLengkap.index");
    }

    public function GetAbsensiTidakLengkap(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            // Get Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    H.TransDate,
                    H.WorkIn,
                    H.WorkOut,
                    E.Description NAME,
                    D.Description Department,
                    E.WorkRole 
                FROM
                    WorkHour H
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    (((
                                H.WorkIn IS NOT NULL 
                                ) 
                            AND ( H.WorkOut IS NULL )) 
                        OR ((
                                H.WorkIn IS NULL 
                                ) 
                        AND ( H.WorkOut IS NOT NULL ))) 
                    AND ( TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'  ) 
                ORDER BY
                    Department,
                    NAME,
                    TransDate
            ");
        } elseif ( $jenis == 2 ){
            // get Workdate between tgl_awal and tgl_akhir
            $workDate = FacadesDB::connection("erp")->select("
                SELECT
                    TransDate,
                    DAY 
                FROM
                    WorkDate 
                WHERE
                    TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' AND Holiday = 'N'
            ");

            $employees = FacadesDB::connection("erp")->select("
                SELECT
                    E.ID,
                    E.Description NAME,
                    D.Description Department,
                    StartWork,
                    WorkRole 
                FROM
                    Employee E
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    E.Active = 'Y' 
                    AND E.WorkRole IS NOT NULL 
                    AND E.WorkRole NOT IN ( 'Outsourcing', 'Borongan/Tukang Luar', 'Tukang Luar' ) 
                ORDER BY
                    Department,
                    NAME
            ");

            $data_rekap = [];

            // loop employee
            foreach ($employees as $keyEmployee => $valueEmployee) {
                if (!in_array($valueEmployee->ID, [4,5,314,205])) {
                    foreach ($workDate as $keyWorkDate => $valueWorkDate) {
                        // get workhour 
                        $get_workhour = workhour::where('Employee', $valueEmployee->ID)->where('TransDate', $valueWorkDate->TransDate)->first();
                        // check if workhour is exists. if not exists add to data_rekap
                        if(is_null($get_workhour)){
                            // convert StartWork of valueEmployee to second
                            $StartWork = strtotime($valueEmployee->StartWork);
                            // convert TransDate of valueWorkDate to second
                            $TransDate = strtotime($valueWorkDate->TransDate);
                            // check if TransDate is after StartWork
                            if($TransDate >= $StartWork){
                                $data = [
                                    "ID" => $valueEmployee->ID,
                                    "WorkRole" => $valueEmployee->WorkRole,
                                    "Department" => $valueEmployee->Department,
                                    "NAME" => $valueEmployee->NAME,
                                    "TransDate" => $valueWorkDate->TransDate,
                                    "DAY" => $valueWorkDate->DAY
                                ];
                                $data_rekap[] = $data;
                            }
                        }
                    }
                }
            }
            $data = $data_rekap;
        } elseif ( $jenis == 3 ){
            // Get Koreksi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    W.DAY,
                    H.TransDate,
                    H.WorkIn,
                    H.WorkOut,
                    H.Late,
                    E.Description Employee,
                    E.WorkRole,
                    D.Description Department 
                FROM
                    WorkHour H
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN WorkDate W ON H.TransDate = W.TransDate
                    LEFT JOIN WorkHourChange C ON H.TransDate = C.TransDate 
                WHERE
                    ( H.Absent IS NULL ) 
                    AND ( H.TransDate > '2011-05-26' ) 
                    AND ((
                            H.WorkIn > IfNull( C.WorkIn, '09:00:00' )) 
                        OR ((
                                H.WorkOut < IfNull( C.WorkOut, '13:45:00' )) 
                        AND ( W.DAY = 'Sabtu' )) 
                        OR ((
                                H.WorkOut < IfNull( C.WorkOut, '15:45:00' )) 
                        AND ( W.DAY <> 'Sabtu' ))) 
                    AND D.ID NOT IN ( 3, 9 ) 
                    AND E.WorkRole IN ( 'Staff', 'Pekerja' ) 
                    AND H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ORDER BY
                    E.WorkRole,
                    H.TransDate
            ");
        } elseif ( $jenis == 4 ){
            // Get Belum Di-Entry
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    A.ID,
                    I.TransDate,
                    D.Description Department,
                    E.Description Employee,
                    A.TimeStart,
                    A.TimeEnd,
                    H.WorkIn,
                    H.WorkOut 
                FROM
                    Absent A
                    JOIN AbsentItem I ON A.ID = I.IDM
                    JOIN WorkHour H ON ( I.Employee = H.Employee ) 
                    AND ( I.TransDate = H.TransDate )
                    JOIN Employee E ON I.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    ( I.AllDay = 'Y' ) 
                    AND ( A.Type <> 3 ) 
                    AND ( WorkIn IS NOT NULL ) 
                    AND ( WorkOut IS NOT NULL ) 
                    AND ( I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                ORDER BY
                    I.TransDate,
                    D.Description,
                    E.Description
            ");
        } elseif ( $jenis == 5 ){
            // Get Beda Jam Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    H.TransDate,
                    H.WorkIn,
                    H.WorkOut,
                    H.AbsentDay,
                    H.Absent,
                    H.AbsentPaid,
                    H.OverTime,
                    H.WorkTime,
                    E.Description Employee,
                    D.Description Department,
                    E.WorkRole 
                FROM
                    WorkHour H
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    ( H.AbsentDay IS NOT NULL ) 
                    AND ((
                            H.Absent IS NOT NULL 
                            ) 
                    OR ( AbsentPaid IS NOT NULL )) 
                    AND ( H.OverTIme IS NOT NULL ) 
                    AND ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                ORDER BY
                    H.TransDate,
                    D.Description,
                    E.Description
            ");
        } else {
            // Invalid Jenis
            return response()->json(["message"=>"invalid request"],400); 
        }
        return response()->json(["tampil"=>$data],200); 
    }
}

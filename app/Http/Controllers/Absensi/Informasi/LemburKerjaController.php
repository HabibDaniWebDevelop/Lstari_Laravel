<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class LemburKerjaController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.LemburKerja.index");
    }

    public function GetLemburKerja(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            // Get Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    O.ID,
                    O.TransDate,
                    A.DAY,
                    O.TimeStart,
                    O.TimeEnd,
                    O.OverTime,
                    E.WorkRole,
                    E.Description Employee,
                    D.Description Department,
                    O.Reason,
                    I.ActualFrom,
                    I.ActualTo
                FROM
                    OverTime O
                    JOIN OverTimeItem I ON O.ID = I.IDM
                    JOIN Employee E ON I.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN WorkDate A ON O.TransDate = A.TransDate 
                WHERE
                    ( O.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                ORDER BY
                    Department,
                    Employee,
                    TransDate,
                    TimeStart,
                    TimeEnd
            ");
        } elseif ( $jenis == 2 ){
            // Get Rekapitulasi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    E.Description Employee,
                    D.Description Department,
                    H.TransDate,
                    H.WorkIn,
                    H.WorkOut,
                    H.OverTime,
                    H.OverTimeAdd,
                    H.OverTimeRate,
                    E.WorkRole 
                FROM
                    WorkHour H
                    JOIN Employee E ON H.EMployee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    ( H.OverTime IS NOT NULL ) 
                    AND ( H.TransDate > '2009-12-31' ) 
                    AND ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                ORDER BY
                    Department,
                    Employee,
                    TransDate
            ");
        } elseif ( $jenis == 3 ){
            // Get Koreksi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    O.ID,
                    O.TransDate,
                    A.DAY,
                    O.TimeStart,
                    O.TimeEnd,
                    E.Description Employee,
                    D.Description Department,
                    O.Reason,
                    I.ActualFrom,
                    I.ActualTo,
                    E.WorkRole 
                FROM
                    COverTime O
                    JOIN COverTimeItem I ON O.ID = I.IDM
                    JOIN Employee E ON I.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN WorkDate A ON O.TransDate = A.TransDate 
                WHERE
                    ( O.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' )  
                ORDER BY
                    Department,
                    Employee,
                    TransDate,
                    TimeStart,
                    TimeEnd
            ");
        } elseif ( $jenis == 4 ){
            // Get Belum Di-Entry
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    H.TransDate,
                    Y.DAY,
                    E.Description Employee,
                    D.Description Department,
                    E.WorkRole,
                    H.WorkIn,
                    H.WorkOut 
                FROM
                    WorkHour H
                    JOIN WorkDate Y ON H.TransDate = Y.TransDate
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                    AND ( H.WorkOut >= '15:00' ) 
                    AND ( H.OverTime IS NULL ) 
                    AND ( Y.DAY = 'Sabtu' ) 
                ORDER BY
                    TransDate,
                    WorkRole,
                    Employee
            ");
        } elseif ( $jenis == 5 ){
            // Get Beda Jam Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    H.TransDate,
                    E.Description Employee,
                    D.Description Department,
                    E.WorkRole,
                    H.WorkIn,
                    H.WorkOut,
                    H.OverTime,
                    O.ID,
                    O.TimeStart,
                    O.TimeEnd,
                    I.ActualFrom,
                    I.ActualTo,
                IF
                    ( I.ActualTo >= O.TimeEnd, Subtime( I.ActualTo, O.TimeEnd ), 0 ) Difference 
                FROM
                    WorkHour H
                    JOIN OverTime O ON H.TransDate = O.TransDate
                    JOIN OverTimeItem I ON O.ID = I.IDM 
                    AND H.Employee = I.Employee
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                    AND ( SubTime( I.ActualTo, O.TimeEnd ) > '00:30:00' ) 
                ORDER BY
                    TransDate,
                    WorkRole,
                    Employee
            ");
        } elseif ( $jenis == 6 ){
            // Get Pulang Awal
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    H.TransDate,
                    E.Description Employee,
                    D.Description Department,
                    E.WorkRole,
                    H.WorkIn,
                    H.WorkOut,
                    H.OverTime,
                    O.ID,
                    O.TimeStart,
                    O.TimeEnd,
                    I.ActualFrom,
                    I.ActualTo,
                IF
                    ( I.ActualTo >= O.TimeEnd, Subtime( I.ActualTo, O.TimeEnd ), 0 ) Difference 
                FROM
                    WorkHour H
                    JOIN OverTime O ON H.TransDate = O.TransDate
                    JOIN OverTimeItem I ON O.ID = I.IDM 
                    AND H.Employee = I.Employee
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                    AND ( SubTime( O.TimeEnd, I.ActualTo ) > '00:30:00' ) 
                ORDER BY
                    TransDate,
                    WorkRole,
                    Employee
            ");
        } elseif ( $jenis == 7 ){
            // Get Tambahan Uang Makan
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    A.ID,
                    A.TransDate,
                    E.Description Employee,
                    D.Description Department,
                    E.WorkRole,
                    I.WorkOut,
                    A.Active,
                    I.Ordinal 
                FROM
                    AdditionalFood A
                    JOIN AdditionalFoodItem I ON A.ID = I.IDM
                    JOIN Employee E ON I.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    A.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ORDER BY
                    ID,
                    Ordinal
            ");
        } else {
            // Invalid Jenis
            return response()->json(["message"=>"invalid request"],400); 
        }
        return response()->json(["tampil"=>$data],200); 
    }
}

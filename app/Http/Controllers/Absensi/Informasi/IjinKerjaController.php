<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class IjinKerjaController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.IjinKerja.index");
    }

    public function GetIjinkerja(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    A.ID,
                    I.TransDate,
                    I.TimeFrom,
                    I.TimeTo,
                    E.Description Employee,
                    A.Active,
                    D.Description Department,
                    T.Description Type,
                    A.Reason,
                    I.Absent,
                    E.WorkRole,
                    A.Notification,
                    A.InformBefore
                FROM
                    Absent A
                    JOIN AbsentItem I ON A.ID = I.IDM
                    JOIN Employee E ON A.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN AbsentType T ON A.Type = T.ID 
                WHERE
                    I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' 
                    AND A.Active <> 'C' 
                ORDER BY
                    Department,
                    Employee,
                    TransDate
            ");
        } elseif ( $jenis == 2 ){
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    E.Department,
                    E.Employee,
                    E.WorkRole,
                    ConCat( E.SWOrdinal, ' - ', E.Period ) Period,
                    E.StartWork,
                    S.Sakit,
                    I.Ijin,
                    T.Terlambat,
                    A.Absen,
                    C.Cuti,
                    P.Alpha,
                    E.SWYear,
                    E.SWOrdinal 
                FROM
                    (
                    SELECT DISTINCT
                        ( P.Remarks ) Period,
                        E.ID,
                        E.Description Employee,
                        D.Description Department,
                        E.WorkRole,
                        E.StartWork,
                        P.SWYear,
                        P.SWOrdinal 
                    FROM
                        AbsentItem I
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd
                        JOIN Employee E ON I.Employee = E.ID
                        JOIN Department D ON E.Department = D.ID 
                    WHERE
                        E.Active = 'Y' 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                    ) E
                    LEFT JOIN (
                    SELECT
                        Count( A.ID ) Sakit,
                        A.Employee,
                        P.Remarks Period 
                    FROM
                        Absent A
                        JOIN AbsentItem I ON A.ID = I.IDM
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        A.Type = 1 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        AND A.Active = 'P' 
                    GROUP BY
                        A.Employee,
                        P.Remarks 
                    ) S ON E.ID = S.Employee 
                    AND E.Period = S.Period
                    LEFT JOIN (
                    SELECT
                        Count( A.ID ) Ijin,
                        A.Employee,
                        P.Remarks Period 
                    FROM
                        Absent A
                        JOIN AbsentItem I ON A.ID = I.IDM
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        A.Type = 2 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        AND A.Active = 'P' 
                    GROUP BY
                        A.Employee,
                        P.Remarks 
                    ) I ON E.ID = I.Employee 
                    AND E.Period = I.Period
                    LEFT JOIN (
                    SELECT
                        Count( A.ID ) Terlambat,
                        A.Employee,
                        P.Remarks Period 
                    FROM
                        Absent A
                        JOIN AbsentItem I ON A.ID = I.IDM
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        A.Type = 3 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        AND A.Active = 'P' 
                    GROUP BY
                        A.Employee,
                        P.Remarks 
                    ) T ON E.ID = T.Employee 
                    AND E.Period = T.Period
                    LEFT JOIN (
                    SELECT
                        Count( A.ID ) Absen,
                        A.Employee,
                        P.Remarks Period 
                    FROM
                        Absent A
                        JOIN AbsentItem I ON A.ID = I.IDM
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        A.Type = 4 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        AND A.Active = 'P' 
                    GROUP BY
                        A.Employee,
                        P.Remarks 
                    ) A ON E.ID = A.Employee 
                    AND E.Period = A.Period
                    LEFT JOIN (
                    SELECT
                        Count( A.ID ) Cuti,
                        A.Employee,
                        P.Remarks Period 
                    FROM
                        Absent A
                        JOIN AbsentItem I ON A.ID = I.IDM
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        A.Type IN ( 5, 7, 8, 9, 10 ) 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        AND A.Active = 'P' 
                    GROUP BY
                        A.Employee,
                        P.Remarks 
                    ) C ON E.ID = C.Employee 
                    AND E.Period = C.Period
                    LEFT JOIN (
                    SELECT
                        Count( A.ID ) Alpha,
                        A.Employee,
                        P.Remarks Period 
                    FROM
                        Absent A
                        JOIN AbsentItem I ON A.ID = I.IDM
                        JOIN WorkPeriod P ON P.Type = 'B' 
                        AND I.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        A.Type = 6 
                        AND I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        AND A.Active = 'P' 
                    GROUP BY
                        A.Employee,
                        P.Remarks 
                    ) P ON E.ID = P.Employee 
                    AND E.Period = P.Period 
                ORDER BY
                    SWYear,
                    SWOrdinal,
                    WorkRole,
                    Department,
                    Employee
            ");
        }
        return response()->json(["tampil"=>$data],200); 
    }
}

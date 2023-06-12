<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class AbsensiBulananController extends Controller{
    public function Index(){
        $periode = FacadesDB::connection("erp")
        ->select("
            SELECT
                ID,
                SW,
                DateStart,
                DateEnd,
                ConCat(Date_Format( DateStart, '%d-%m-%y' ),' s/d ', Date_Format( DateEnd, '%d-%m-%y' )) PeriodDate 
            FROM
                WorkPeriod 
            WHERE
                Type = 'B' 
                AND YEAR ( DateEnd ) = ".date('Y')." 
            ORDER BY
                DateStart
        ");
        // dd($periode);
        return view("Absensi.Informasi.AbsensiBulanan.index",compact('periode'));
    }

    public function GetAbsensiBulanan(Request $request){
        $periode = $request->periode;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            // Get Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    P.SW Period,
                    E.Description Employee,
                    D.Description Department,
                    A.Absent,
                    A.OverTime,
                    A.AbsentPaid,
                    A.Allowance,
                    A.Food,
                    A.WORK,
                    A.AbsentDay,
                    A.Late,
                    E.WorkRole,
                    A.OverTimeBonus,
                    A.AbsentHalf,
                    A.WorkHour 
                FROM
                    Attendance A
                    JOIN WorkPeriod P ON A.Period = P.ID 
                    AND P.Type = 'B'
                    JOIN Employee E ON A.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID 
                WHERE
                    A.Period = ".$periode." 
                ORDER BY
                    P.DateStart,
                    D.Description,
                    E.Description
            ");
        } elseif ( $jenis == 2 ){
            // Get Rekapitulasi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    E.Employee,
                    E.Department,
                    Total45,
                    Total40,
                    Total35,
                    Total30,
                    Total25,
                    Total20,
                    Total15,
                    Total10,
                    AddFood 
                FROM
                    (
                    SELECT DISTINCT
                        ( E.ID ) ID,
                        E.Description Employee,
                        D.Description Department 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd
                        JOIN Employee E ON H.Employee = E.ID 
                        AND E.WorkRole = 'Staff'
                        JOIN Department D ON E.Department = D.ID 
                    WHERE
                        P.ID = ".$periode." 
                    ORDER BY
                        Department,
                        Employee 
                    ) E
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total45,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 4.5 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H45 ON H45.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total40,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 4 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H40 ON H40.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total35,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 3.5 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H35 ON H35.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total30,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 3 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H30 ON H30.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total25,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 2.5 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H25 ON H25.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total20,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 2 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H20 ON H20.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total15,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 1.5 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H15 ON H15.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) Total10,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime = 1 
                        AND P.ID = ".$periode." 
                    GROUP BY
                        H.Employee 
                    ) H10 ON H10.Employee = E.ID
                    LEFT JOIN (
                    SELECT
                        Count( H.ID ) AddFood,
                        H.Employee 
                    FROM
                        WorkPeriod P
                        JOIN WorkHour H ON H.TransDate BETWEEN P.DateStart 
                        AND P.DateEnd 
                    WHERE
                        H.OverTime IS NULL 
                        AND H.WorkOut >= '17:35' 
                        AND P.ID = ".$periode." 
                    GROUP BY
                    H.Employee 
                    ) HFood ON HFood.Employee = E.ID
            ");
        } elseif ( $jenis == 3 ){
            // Get Koreksi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    P.SW Period,
                    E.Description Employee,
                    D.Description Department,
                    H.TransDate,
                    H.WorkIn,
                    H.WorkOut,
                    H.Late,
                    H.Absent,
                    H.OverTime,
                    H.WorkTime,
                    H.AbsentDay,
                    H.AbsentPaid 
                FROM
                    Attendance A
                    JOIN WorkPeriod P ON A.Period = P.ID 
                    AND P.Type = 'B'
                    JOIN Employee E ON A.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN WorkHour H ON A.Employee = H.Employee 
                    AND H.TransDate BETWEEN DateStart 
                    AND DateEnd 
                WHERE
                    A.Period = ".$periode." 
                ORDER BY
                    P.DateStart,
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

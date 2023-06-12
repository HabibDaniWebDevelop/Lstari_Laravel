<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class KoreksiAbsensiController extends Controller{
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
        return view("Absensi.Informasi.KoreksiAbsensi.index",compact('periode'));
    }

    public function GetKoreksiAbsensi(Request $request){
        $periode = $request->periode;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            // Get Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    A.ID,
                    A.TransDate,
                    P.SW Period,
                    E.Description Employee,
                    E.WorkRole,
                    D.Description Department,
                    T.Description Type,
                    A.DateStart,
                    A.DateEnd,
                    A.TimeStart,
                    A.TimeEnd 
                FROM
                    CAbsent A
                    JOIN WorkPeriod P ON A.Period = P.ID
                    JOIN Employee E ON A.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN AbsentType T ON A.Type = T.ID 
                WHERE
                    ( A.Period = ".$periode." ) 
                ORDER BY
                    Cast(A.ID AS Signed)
            ");
        } elseif ( $jenis == 2 ){
            // Get Rekapitulasi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    O.ID,
                    O.TransDate,
                    P.SW Period,
                    E.Description Employee,
                    E.WorkRole, 
                    D.Description Department,
                    O.OverTimeDate,
                    O.TimeStart,
                    O.TimeEnd
                From COverTime O
                    Join COverTimeItem I On O.ID = I.IDM
                    Join WorkPeriod P On O.Period = P.ID
                    Join Employee E On I.Employee = E.ID
                    Join Department D On E.Department = D.ID
                Where 
                    (O.Period = ".$periode.")
                Order By 
                    Cast(O.ID As Signed)
            ");
        } elseif ( $jenis == 3 ){
            // Get Koreksi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    O.ID,
                    O.TransDate,
                    O.Type,
                    P.SW Period,
                    E.Description Employee,
                    E.WorkRole, 
                    D.Description Department,
                    I.Amount,
                    I.Note
                From CPayroll O
                Join CPayrollItem I On O.ID = I.IDM
                Join WorkPeriod P On O.Period = P.ID
                Join Employee E On I.Employee = E.ID
                Join Department D On E.Department = D.ID
                Where 
                    (O.Period = ".$periode.")
                Order By 
                    Cast(O.ID As Signed)
            ");
        } else {
            // Invalid Jenis
            return response()->json(["message"=>"invalid request"],400); 
        }
        return response()->json(["tampil"=>$data],200); 
    }
}

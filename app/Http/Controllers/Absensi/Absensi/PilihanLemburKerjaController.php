<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class PilihanLemburKerjaController extends Controller{
    public function Index(Request $request){
        return view('Absensi.Absensi.PilihanLemburKerja.index');
    }

    public function GetPilihanLemburKerja(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT
                DISTINCT E.ID,
                H.TransDate,
                Y.DAY,
                E.Description Employee,
                D.Description Department,
                E.WorkRole,
                H.WorkIn,
                H.WorkOut,
                CASE WHEN X.MakaneEmployee IS NULL THEN 'Tidak' ELSE 'Ya' END as iki
            FROM
                WorkHour H
                JOIN WorkDate Y ON H.TransDate = Y.TransDate
                JOIN Employee E ON H.Employee = E.ID
                JOIN Department D ON E.Department = D.ID
                LEFT JOIN (
                SELECT
                    I.Employee OverTimeEmployee,
                    O.TransDate OverTimeDate 
                FROM
                    OverTime O
                    JOIN OverTimeItem I ON O.ID = I.IDM 
                WHERE
                O.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ) O ON ( H.Employee = O.OverTimeEmployee ) 
                AND ( H.TransDate = O.OverTimeDate ) 
                
                LEFT JOIN (
                SELECT
                    S.Employee MakaneEmployee,
                    CASE WHEN S.Employee IS NULL THEN 'Belum Di Input' ELSE 'Sudah Di Input' END as iki,
                    X.TransDate JamDate 
                FROM
                    additionalfood X
                    JOIN additionalfooditem S ON X.ID = S.IDM 
                WHERE
                    X.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ) X ON ( H.Employee = X.MakaneEmployee ) 
                AND ( H.TransDate = X.JamDate ) 
                
            WHERE
            ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                AND (((
                            H.WorkOut >= '17:30' 
                            ) 
                        AND ( Y.DAY <> 'Sabtu' )) 
                    OR ((
                            H.WorkOut >= '15:00' 
                            ) 
                    AND ( Y.DAY = 'Sabtu' ))) 
                AND ( O.OverTimeEmployee IS NULL )
            ORDER BY
                TransDate,
                WorkRole,
                Employee
        ");
        return response()->json(["tampil"=>$data],200); 
    }
}

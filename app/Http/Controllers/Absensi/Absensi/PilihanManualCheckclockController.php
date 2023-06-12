<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class PilihanManualCheckclockController extends Controller{
    public function Index(Request $request){
        return view('Absensi.Absensi.PilihanManualCheckclock.index');
    }

    public function GetPilihanManualCheckclock(Request $request){
        $tanggal_awal = $request->tgl_awal;
        $tanggal_akhir = $request->tgl_akhir;
        $data = FacadesDB::connection('erp')
        ->select("
            SELECT
                DATE_FORMAT(H.TransDate, '%d-%m-%Y') as TransDate,
                CASE WHEN H.WorkIn IS NULL THEN '-' ELSE H.WorkIn END AS WorkIn,
                CASE WHEN H.WorkOut IS NULL THEN '-' ELSE H.WorkOut END AS WorkOut,
                H.OverTime,
                H.WorkTime,
                E.Description Employee,
                E.WorkRole,
                D.Description Department,
                W.DAY,
                E.ID 
            FROM
                WorkHour H
                JOIN Employee E ON H.Employee = E.ID
                JOIN Department D ON E.Department = D.ID
                JOIN WorkDate W ON H.TransDate = W.TransDate 
            WHERE
                ((
                        H.WorkIn IS NULL 
                        ) 
                OR ( H.WorkOut IS NULL )) 
                AND ( H.AbsentDay IS NULL ) 
                AND ( H.TransDate BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ) 
            ORDER BY
                H.TransDate,
                E.Description
        ");
        // dd($data);
        return response()->json(["data"=>$data],200); 
    }
}

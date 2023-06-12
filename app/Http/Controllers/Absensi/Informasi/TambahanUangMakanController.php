<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class TambahanUangMakanController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.TambahanUangMakan.index");
    }

    public function GetTambahanUangMakan(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT
                E.Description,
                A.TransDate,
                B.ordinal,
                A.ID 
            FROM
                additionalfood A
                LEFT JOIN additionalfooditem B ON A.ID = B.IDM
                LEFT JOIN employee E ON E.ID = B.Employee 
            WHERE
                TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
        ");
        return response()->json(["tampil"=>$data],200); 
    }
}

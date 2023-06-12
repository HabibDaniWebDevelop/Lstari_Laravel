<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class BeritaAcaraController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.BeritaAcara.index");
    }

    public function GetBeritaAcara(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT
                B.ID,
                B.TransDate,
                E.Description Employee,
                D.Description Department,
                B.STATUS,
                Y.Description Purpose,
                X.Description Type,
                B.Note,
                B.Solution 
            FROM
                BeritaAcara B
                JOIN Employee E ON B.Employee = E.ID 
                JOIN Department D ON B.Department = D.ID
                JOIN ShortText X ON B.Type = X.ID
                JOIN ShortText Y ON B.Purpose = Y.ID 
            WHERE
                B.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
            ORDER BY
                Department,
                Employee,
                TransDate
        ");
        return response()->json(["data"=>$data],200); 
    }
}

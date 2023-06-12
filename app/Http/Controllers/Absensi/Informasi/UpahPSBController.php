<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class UpahPSBController extends Controller{
    public function Index(){
        return view("Absensi.Informasi.UpahPSB.index");
    }

    public function GetUpahPSB(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis;
        if ($jenis  == 1) {
            // Get Lembur
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    Cast( P.ID AS Signed ) ID,
                    E.Description Employee,
                    P.TransDate,
                    P.Total,
                    P.Food,
                    P.Allowance,
                    P.Bonus,
                    P.GrandTotal,
                    P.TotalStone 
                FROM
                    CPSB P
                    JOIN Employee E ON P.Employee = E.ID 
                WHERE
                    ( P.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                ORDER BY
                    TransDate,
            Employee
            ");
        } elseif ( $jenis == 2 ){
            // Get Rekapitulasi
            $data = FacadesDB::connection("erp")
            ->select("
                SELECT
                    P.ID,
                    E.Description Employee,
                    P.TransDate,
                    A.Allocation,
                    R.Description Product,
                    I.Tanam,
                    I.Gigi,
                    I.Total 
                FROM
                    CPSB P
                    JOIN Employee E ON P.Employee = E.ID
                    JOIN CPSBAllocation A ON P.ID = A.IDM
                    JOIN CPSBItem I ON A.IDM = I.IDM 
                    AND A.Ordinal = I.Ordinal
                    JOIN Product R ON I.Product = R.ID 
                WHERE
                    ( P.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
                ORDER BY
                    P.TransDate,
                    P.Employee
            ");
        } else {
            // Invalid Jenis
            return response()->json(["message"=>"invalid request"],400); 
        }
        return response()->json(["tampil"=>$data],200); 
    }
}

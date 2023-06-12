<?php

namespace App\Http\Controllers\RnD\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class InformasiTMKaretPCBController extends Controller{
    public function Index(Request $request){
        $data = FacadesDB::select("
            SELECT
                A.PostDate,
                B.IDM,
                B.IDRubber,
                PR.SW AS ProductKaret,
                PFG.SKU AS Product,
                B.WorkAllocation
            FROM
                listrubberwax A
                LEFT JOIN listrubberwaxitem B ON A.ID = B.IDM
                LEFT JOIN product PR ON B.Product = PR.ID
                LEFT JOIN product PFG ON B.ProductFG = PFG.ID
            WHERE 
                A.PostDate IS NOT NULL
        ");
        return view('R&D.Informasi.InformasiTMKaretPCB.index',compact('data'));
    }
}

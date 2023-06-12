<?php

namespace App\Http\Controllers\Inventori\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class InformasiBahanPembanuController extends Controller
{
    public function Index(Request $request)
    {
    // dd($data);

        return view('Inventori.Informasi.InformasiBahanPembanu.index');
    }

    public function show(Request $request)
    {
        // Get WIP Informasi
        $data = FacadesDB::connection('erp')->select("SELECT
            p.ID,
            p.SW,
            p.Description,
            l.Description Lokasi,
            p2.Type,
            p2.MaterialFunction,
            p2.Remarks,
        IF
            ( p2.Image1 IS NOT NULL,TRUE, FALSE ) AS Gambar,
            (
            SELECT
                GROUP_CONCAT( d.Description ) gg 
            FROM
                productpurchasedepartment AS pd
                INNER JOIN department AS d ON pd.Department = d.ID 
            WHERE
                pd.ID = p.ID 
            ) Area
            
        FROM
            `productpurchase` AS p
            LEFT JOIN rndnew.productpurchase AS p2 ON p.ID = p2.ID 
            LEFT JOIN location as l ON p.Location = l.ID
        WHERE
            p.Active = 'Y'
                ");
        return response()->json(["data" => $data], 200);
    }
}

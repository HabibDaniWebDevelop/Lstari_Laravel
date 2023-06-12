<?php

namespace App\Http\Controllers\RnD\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class InformasiWIPGrafisController extends Controller{
    public function Index(){
        return view('R&D.Informasi.InformasiWIPGrafis.index');
    }

    public function GetWIPGrafis(Request $request){
        // Get WIP Informasi
        $WIPGrafis = FacadesDB::select("
            SELECT
                A.TransDate,
                A.WorkAllocation AS nthkoSebelum,
                B.Description AS Kadar,
                A.TransferID AS idTM,
                D.Description AS TMdari,
                COUNT(A.ID) AS TotalItem,
                SUM(A.Weight) AS TotalBeratItem,
                A.NextWorkAllocation AS spkoSekarang,
                (SELECT Active FROM erp.workallocation wo WHERE wo.SW = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS statusSPKO,
                (SELECT ID FROM erp.workcompletion wc WHERE wc.WorkAllocation = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS idNTHKO,
                (SELECT TransDate FROM erp.workcompletion wc WHERE wc.WorkAllocation = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS tanggalNTHKO,
                (SELECT Active FROM erp.workcompletion wc WHERE wc.WorkAllocation = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS statusNTHKO
            FROM
                grafisworklist A
                JOIN erp.productcarat B ON A.Carat = B.ID
                LEFT JOIN erp.transferrm C ON A.TransferID = C.ID
                LEFT JOIN erp.location D ON C.FromLoc = D.ID
            WHERE
                A.TransDate > '2023-02-10'
            GROUP BY
                A.WorkAllocation
            ORDER BY
                A.ID DESC
        ");
        return response()->json(["data"=>$WIPGrafis],200);
    }

    public function GetProductWIPGrafis(Request $request){
        // Get WIP Informasi
        $ProductWIPGrafis = FacadesDB::select("
            SELECT
                A.ID AS id_wip_grafis,
                A.TransDate AS tanggal_wip,
                B.SKU AS product,
                A.NextWorkAllocation AS nomor_spko_grafis,
                D.Description AS operator,
                A.StartFoto,
                A.EndFoto
            FROM
                grafisworklist A 
                JOIN product B ON A.Product = B.ID
                LEFT JOIN erp.workallocation C ON A.NextWorkAllocation = C.SW
                LEFT JOIN erp.employee D ON C.employee = D.id
            WHERE
                A.TransDate > '2023-02-10'
            GROUP BY
                A.Product 
            ORDER BY
                A.ID DESC
        ");
        return response()->json(["data"=>$ProductWIPGrafis],200);
    }
}

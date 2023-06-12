<?php

namespace App\Http\Controllers\RnD\Grafis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class InformasiWIPGrafisController extends Controller{
    public function Index(){
        return view('R&D.Grafis.InformasiWIPGrafis.index');
    }

    public function GetWIPGrafis(Request $request){
        // Get WIP Informasi
        $WIPGrafis = FacadesDB::select("
            SELECT
                A.TransDate,
                A.WorkAllocation AS nthkoSebelum,
                B.Description AS Kadar,
                CASE 
                        WHEN A.TransferID IS NULL THEN
                                'VarP'
                        ELSE
                                'TM'
                END AS sumber,
                A.TransferID AS idTM,
                D.Description AS TMdari,
                COUNT(A.ID) AS TotalItem,
                SUM(A.Weight) AS TotalBeratItem,
                A.NextWorkAllocation AS spkoSekarang,
                (SELECT emp.SW FROM erp.workallocation wo LEFT JOIN erp.employee emp ON wo.Employee = emp.ID WHERE wo.SW = A.NextWorkAllocation ORDER BY wo.ID DESC LIMIT 1) AS Operator,
                (SELECT Active FROM erp.workallocation wo WHERE wo.SW = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS statusSPKO,
                (SELECT SUM(woi.Weight) FROM erp.workallocation wo JOIN erp.workallocationitem woi ON wo.ID = woi.IDM WHERE wo.SW = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS BeratSPKO,
                (SELECT COUNT(woi.IDM) FROM erp.workallocation wo JOIN erp.workallocationitem woi ON wo.ID = woi.IDM WHERE wo.SW = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS JumlahItemSPKO,
                (SELECT ID FROM erp.workcompletion wc WHERE wc.WorkAllocation = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS idNTHKO,
                (SELECT SUM(wci.Weight) FROM erp.workcompletion wc JOIN erp.workcompletionitem wci ON wc.ID = wci.IDM WHERE wc.WorkAllocation = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS BeratNTHKO,
                (SELECT COUNT(wci.IDM) FROM erp.workcompletion wc JOIN erp.workcompletionitem wci ON wc.ID = wci.IDM WHERE wc.WorkAllocation = A.NextWorkAllocation ORDER BY ID DESC LIMIT 1) AS JumlahItemNTHKO,
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
}

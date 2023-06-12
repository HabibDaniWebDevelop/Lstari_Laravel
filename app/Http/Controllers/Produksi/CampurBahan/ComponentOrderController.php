<?php
namespace App\Http\Controllers\Produksi\CampurBahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;



class ComponentOrderController extends Controller
{
    public function index(){
        return view('Produksi.CampurBahan.OrderKomponen.index');
    }

    public function getFilter(){
        $data = FacadesDB::connection('erp')->select("
        
        ");

   
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.data', compact('data', 'kadar'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function getSPKProduksi(Request $request){
        $thn = $request->tahun;
        $bln = $request->bln;
        $kadar = $request->kadar;
        
        $dataspk = FacadesDB::connection('erp')->select("
        
        ");

        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.SPKProduksi')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil'=> $dataspk ));
    }

    public function getSPKRouting(Request $request){
        $id = $request->id;

        $datadetail = FacadesDB::connection('erp')->select("
        SELECT 
            A.SW,
            A.TransDate,
            GROUP_CONCAT(H.SW SEPARATOR ' , ') FG,
            CONCAT( D.SW, '-', D.Freq, '-', C.Ordinal ) SPKO,
            M.Description LokasiSPKO,
            D.TransDate TglSPKO,
            I.Description PSPKO,
            K.Description KadarSPKO,
            C.Qty QtySPKO,
            C.Weight BeratSPKO,
            CONCAT( E.WorkAllocation, '-', E.Freq, '-', F.Ordinal ) NTHKO,
            N.Description LokasiNTHKO,
            E.TransDate TglNTHKO,
            J.Description PNTHKO,
            L.Description KadarNTHKO,
            LL.Description KadarWO,
            F.Qty,
            F.ScrapQty,
            F.RepairQty,
            F.Weight,
            F.ScrapWeight,
            F.RepairWeight,
            G.Description Operation,
            F.IDM,
            F.Ordinal,
            C.PrevProcess,
            C.PrevOrd,
            C.IDM,
            C.Ordinal,
            F.LinkID,
            F.LinkOrd 

            FROM `workorder` A 
            JOIN workorderitem B ON B.IDM = A.ID 
            JOIN workallocationitem C ON C.WorkOrder = A.ID 
            JOIN workallocation D ON D.ID = C.IDM 
            JOIN workcompletion E ON E.WorkAllocation = D.SW 
            JOIN workcompletionitem F ON F.IDM = E.ID AND F.LinkID = C.IDM AND F.LinkOrd = C.Ordinal
            JOIN operation G ON G.ID = D.Operation
            JOIN operation GG ON GG.ID = E.Operation
            JOIN product H ON H.ID = B.Product
            JOIN product I ON I.ID = C.Product
            JOIN product J ON J.ID = F.Product
            JOIN productcarat K ON K.ID = C.Carat
            JOIN productcarat L ON L.ID = F.Carat
            JOIN productcarat LL ON LL.ID = A.Carat
            JOIN location M ON M.ID = D.Location
            JOIN location N ON N.ID = E.Location

            WHERE 
            A.Active <> 'C' 
            AND D.Active <> 'C' 
            AND E.Active <> 'C'
            AND A.ID = ".$id."
            GROUP BY F.IDM, F.Ordinal
            ORDER BY A.SWPurpose, SPKO, NTHKO
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.Routing')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil2'=> $datadetail ));
        
    }
}

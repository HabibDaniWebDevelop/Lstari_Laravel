<?php
namespace App\Http\Controllers\Produksi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;



class RoutingProduksiController extends Controller
{
    public function index(){
        return view('Produksi.Informasi.RoutingProduksi.index');
    }

    public function getFilter(){
        $data = FacadesDB::connection('erp')->select("
        SELECT  
            SW, CONCAT(DateStart, '|', DateEnd) AS Tanggal, SWOrdinal,
            CASE WHEN SWOrdinal = 1 THEN 'Januari'
            WHEN  SWOrdinal = 2 THEN 'Februari'
            WHEN  SWOrdinal = 3 THEN 'Maret'
            WHEN  SWOrdinal = 4 THEN 'April'
            WHEN  SWOrdinal = 5 THEN 'Mei'
            WHEN  SWOrdinal = 6 THEN 'Juni'
            WHEN  SWOrdinal = 7 THEN 'Juli'
            WHEN  SWOrdinal = 8 THEN 'Agustus'
            WHEN  SWOrdinal = 9 THEN 'September'
            WHEN  SWOrdinal = 10 THEN 'Oktober'
            WHEN  SWOrdinal = 11 THEN 'November'
            WHEN  SWOrdinal = 12 THEN 'Desember'
            ELSE 'Unknown'
            END AS Bulan
        FROM workperiod 
        WHERE SUBSTRING_INDEX(DateStart, '-', 1) = '".date('Y')."'  AND Type = 'P'
        ");

        $kadar = FacadesDB::connection('erp')->select("
        SELECT ID, Description Kadar FROM `productcarat` A 
        WHERE A.Regular = 'Y' ORDER BY A.ID 
        ");
        //dd($data);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.data', compact('data', 'kadar'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function getSPKProduksi(Request $request){
        $thn = $request->tahun;
        $bln = $request->bln;
        $kadar = $request->kadar;

        if(!empty($kadar)){
            $filterkadar = 'AND A.Carat = "'.$kadar.'"';
        }else{
            $filterkadar = '';
        }
        
        $dataspk = FacadesDB::connection('erp')->select("
        SELECT
            A.ID,
            A.TransDate,
            A.SW,
            A.TotalQty,
            ROUND(A.TotalWeight, 2) TotalWeight,
            A.Active,
            A.SWPurpose,
            A.WIP,
            A.TransferFG,
            A.TransferWeight,
            A.TransferStart,
            A.TransferLast,
            A.Polling,
            A.Outsource,
            A.Enamel,
            A.Wax,
            A.RequireDate,
            D.Description Kadar,
            GROUP_CONCAT( C.SW SEPARATOR ' , ' ) Model 
        FROM
            workorder A
            JOIN workorderitem B ON B.IDM = A.ID
            JOIN product C ON C.ID = B.Product
            JOIN productcarat D ON D.ID = A.Carat 
        WHERE
            A.Active <> 'C' 
            AND YEAR(A.TransDate) = ".$thn."
            AND MONTH(A.TransDate) = ".$bln."
            $filterkadar
        GROUP BY
            A.ID
        ORDER BY A.TransDate DESC
        ");
       // dd($dataspk);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.SPKProduksi')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil'=> $dataspk ));
    }

    public function getSPKRouting(Request $request){
        $id = $request->id;
        $set = FacadesDB::statement(FacadesDB::raw('SET @nomor = 0'));
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT
            CONCAT((@nomor:=@nomor+1),'. ', G.Description) XX,
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
            ORDER BY A.SWPurpose, TglSPKO, TglNTHKOx
        ");
        dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.Routing')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil2'=> $datadetail ));
        
    }

    public function getSPKO(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT 
            A.ID, B.Ordinal, CONCAT(A.SW,'-',A.Freq) SW, A.TransDate tgl, C.Description Location, D.Description Operation, SUM(B.Qty) TotQty, ROUND(SUM(B.Weight), 2) TotBerat, E.SW SWUsed, F.Description Kadar
        FROM workallocation A 
            JOIN workallocationitem B ON B.IDM = A.ID 
            JOIN location C ON C.ID = A.Location
            JOIN operation D ON D.ID = A.Operation
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = E.Carat
        WHERE E.SWUsed = ".$id."
        GROUP BY B.IDM
        ORDER BY A.TransDate
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil3'=> $datadetail ));
        
    }

    public function getSPKOdetails(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT 
            A.ID, B.Ordinal, CONCAT(A.SW,'-',A.Freq) SW, A.TransDate tgl, C.Description Location, D.Description Operation, P.Description Produk, B.Qty , B.Weight Berat, E.SW SWUsed, F.Description Kadar
        FROM workallocation A 
            JOIN workallocationitem B ON B.IDM = A.ID 
            JOIN location C ON C.ID = A.Location
            JOIN operation D ON D.ID = A.Operation
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = B.Carat
            JOIN product P ON P.ID = B.Product
        WHERE E.SWUsed = ".$id."
        ORDER BY A.TransDate
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil7'=> $datadetail ));
        
    }

    public function getNTHKO(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT 
            A.ID, B.Ordinal, CONCAT(A.WorkAllocation,'-',A.Freq) SW, A.TransDate tgl, C.Description Location, D.Description Operation, 
            SUM(B.Qty) TotQtyOK, ROUND(SUM(B.Weight), 2) TotBeratOK, 
            SUM(B.RepairQty) TotQtyRep, ROUND(SUM(B.RepairWeight), 2) TotBeratRep, 
            SUM(B.ScrapQty) TotQtyScrap, ROUND(SUM(B.ScrapWeight), 2) TotBeratScrap, 
            E.SW SWUsed, F.Description Kadar
        FROM workcompletion A 
            JOIN workcompletionitem B ON B.IDM = A.ID 
            JOIN location C ON C.ID = A.Location
            JOIN operation D ON D.ID = A.Operation
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = E.Carat
        WHERE E.SWUsed = ".$id."
        GROUP BY B.IDM
        ORDER BY A.TransDate
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil4'=> $datadetail ));
        
    }

    public function getNTHKOdetails(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT 
            A.ID, B.Ordinal, CONCAT(A.WorkAllocation,'-',A.Freq) SW, A.TransDate tgl, C.Description Location, D.Description Operation, P.Description Produk,
            B.Qty QtyOK, B.Weight BeratOK, 
            B.RepairQty QtyRep, B.RepairWeight BeratRep, 
            B.ScrapQty QtyScrap, B.ScrapWeight BeratScrap, 
            E.SW SWUsed, F.Description Kadar
        FROM workcompletion A 
            JOIN workcompletionitem B ON B.IDM = A.ID 
            JOIN location C ON C.ID = A.Location
            JOIN operation D ON D.ID = A.Operation
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = B.Carat
            JOIN product P ON P.ID = B.Product
        WHERE E.SWUsed = ".$id."
        ORDER BY A.TransDate
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil8'=> $datadetail ));
        
    }

    public function getTRM(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT  
            A.ID ,A.TransDate tgl, C.Description FromLoc, D.Description ToLoc, SUM(B.Qty) TotQty, ROUND(SUM(B.Weight), 2) TotBerat, E.SW SWUsed, F.Description Kadar
        FROM transferrm A 
            JOIN transferrmitem B ON B.IDM = A.ID 
            JOIN location C ON C.ID = A.FromLoc
            JOIN location D ON D.ID = A.ToLoc
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = E.Carat
        WHERE E.SWUsed = ".$id."
        GROUP BY B.IDM
        ORDER BY A.TransDate
        
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil5'=> $datadetail ));
        
    }

    public function getTRMdetails(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT  
            A.ID ,A.TransDate tgl, C.Description FromLoc, D.Description ToLoc, P.Description Produk, B.Qty Qty, B.Weight Berat, E.SW SWUsed, F.Description Kadar,
            CONCAT(B.WorkAllocation,'-',B.LinkFreq, '-', B.LinkOrd) NTHKO
        FROM transferrm A 
            JOIN transferrmitem B ON B.IDM = A.ID 
            JOIN location C ON C.ID = A.FromLoc
            JOIN location D ON D.ID = A.ToLoc
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = B.Carat
            JOIN product P ON P.ID = B.Product
        WHERE E.SWUsed = ".$id."
        ORDER BY A.TransDate
        
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil9'=> $datadetail ));
        
    }

    public function getTFG(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT  
            A.ID, A.TransDate tgl,  SUM(B.Qty) TotQty, ROUND(SUM(B.Weight), 2) TotBerat, E.SW SWUsed, F.Description Kadar
        FROM transferfg A 
            JOIN transferfgitem B ON B.IDM = A.ID 
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = E.Carat
        WHERE E.SWUsed = ".$id."
        GROUP BY B.IDM
        ORDER BY A.TransDate
        
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil6'=> $datadetail ));
        
    }

    public function getTFGdetails(Request $request){
        $id = $request->nospk;
        $datadetail = FacadesDB::connection('erp')->select("
        SELECT  
            A.ID, A.TransDate tgl, P.SW Produk, P.Description ProdukD, B.Qty Qty, B.Weight Berat, E.SW SWUsed, F.Description Kadar
        FROM transferfg A 
            JOIN transferfgitem B ON B.IDM = A.ID 
            JOIN workorder E ON E.ID = B.WorkOrder
            JOIN productcarat F ON F.ID = B.Carat
            JOIN product P ON P.ID = B.Product
        WHERE E.SWUsed = ".$id."
        ORDER BY A.TransDate
        
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.RoutingProduksi.routingdetails')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil10'=> $datadetail ));
        
    }
}

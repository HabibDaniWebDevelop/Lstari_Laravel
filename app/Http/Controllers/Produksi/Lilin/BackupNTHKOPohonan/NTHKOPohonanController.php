<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\rubberplate;
use App\Models\erp\waxtree;
use App\Models\erp\waxtreeitem;

class NTHKOPohonanController extends Controller{
    // Private Function
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    public function Index(){
        // Get Employee
        $employee = FacadesDB::connection('erp')
        ->select("
            SELECT 
                ID, 
                SW, 
                Description 
            FROM 
                employee 
            WHERE 
                Department = 19 AND Active = 'Y'
        ");
        $datenow = date('Y-m-d');
        // history waxtree
        $historyWaxTree = waxtree::where('Purpose','D')->orderBy('ID','DESC')->limit(10)->get();
        return view('Produksi.Lilin.NTHKOPohonan.index', compact('employee', 'datenow', 'historyWaxTree'));
    }

    public function getWaxInjectOrder($IDWaxInjectOrder){
        // Getting WaxInjectOrder
        $WaxInjectOrder = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                A.Qty AS totalQty,
                B.ID AS idKadar,
                B.Description AS Kadar,
                A.Purpose,
                A.RubberPlate AS idPohon,
                C.SW AS nomorPohon,
                C.Weight AS beratPohon
            FROM
                waxinjectorder A
                JOIN productcarat B ON A.Carat = B.ID
                JOIN rubberplate C ON A.RubberPlate = C.ID
            WHERE
                A.ID = '$IDWaxInjectOrder'
        ");
        if (count($WaxInjectOrder) == 0) {
            $data_return = $this->SetReturn(false, "Getting WaxInjectOrder Item Faile. WaxInjectOrder not found", null, null);
            return response()->json($data_return, 404);
        }
        $WaxInjectOrder = $WaxInjectOrder[0];
        if ($WaxInjectOrder->Purpose != 'D') {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Bukanlah Direct Casting", null, null);
            return response()->json($data_return, 400);
        }
        
        // Check if that ID its already NTHKOed.
        $cekNTHKO = waxtree::where('WaxOrder',$IDWaxInjectOrder)->first();
        if (!is_null($cekNTHKO)) {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Sudah di NTHKO", null, null);
            return response()->json($data_return, 400);
        }

        // Getting Items of waxinjectorder from waxinjectorder
        $item = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                C.WorkOrder,
                F.SW,
                D.Product,
                E.SW AS Barang,
                G.Description AS Kadar,
                D.Qty,
                D.Remarks 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WaxOrder AND T.Ordinal = B.WaxOrderOrd
                JOIN waxorderitem C ON C.TransferResinDC = T.IDM AND C.TransferResinDCOrd = T.Ordinal 
                JOIN workorderitem D ON C.WorkOrder = D.IDM AND C.WorkOrderOrd = D.Ordinal
                JOIN product E ON D.Product = E.ID
                JOIN workorder F ON C.WorkOrder = F.ID
                LEFT JOIN productcarat G ON E.VarCarat = G.ID 
            WHERE
                A.ID = '$IDWaxInjectOrder'
        ");

        // Check Item 
        if (count($item) == 0) {
            $data_return = $this->SetReturn(false, "SPKO tidak valid. Karena tidak ada item/barang di Database Untuk SPKO Tersebut.", null, null);
            return response()->json($data_return, 400);
        }

        // Get Berat Batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                T.WorkOrder
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WaxOrder AND T.Ordinal = B.WaxOrderOrd
            WHERE
                A.ID = '$IDWaxInjectOrder'
            GROUP BY
                T.WorkOrder
        ");
        $workOrder = $GetWorkOrder[0]->WorkOrder;
        // Calculate Berat Batu
        $beratBatuTotal = FacadesDB::connection('erp')
        ->select("
            select
                SUM(A.Weight) as totalBeratBatu,
                SUM(A.Qty) as totalBatu
            FROM
                waxstoneusageitem A
            WHERE 
                A.WorkOrder = '$workOrder'
        ");
        $beratBatuTotal = $beratBatuTotal[0];
        $WaxInjectOrder->item = $item;
        $WaxInjectOrder->beratBatu = is_null($beratBatuTotal->totalBeratBatu) ? 0 : $beratBatuTotal->totalBeratBatu ;

        $data_return = $this->SetReturn(true, "Getting WaxInjectOrder Item Success", $WaxInjectOrder, null);
        return response()->json($data_return, 200);
    }

    public function GetPohon(Request $request){
        $keyword = $request->keyword;
        // Get rubberplate atau pohon
        $rubberplate = rubberplate::where('SW',$keyword)->where('Active','Y')->first();
        if (is_null($rubberplate)) {
            $data_return = $this->SetReturn(false, "Getting Pohon Failed. Rubberplate not found", null, null);
            return response()->json($data_return, 404);
        }
        $data_return = $this->SetReturn(true, "Getting Pohon Success", $rubberplate, null);
        return response()->json($data_return, 200);
    }

    public function SimpanNTHKOPohonan(Request $request){
        // Get Required Data
        $idWaxInjectOrder = $request->idWaxInjectOrder;
        $idEmployee = $request->idEmployee;
        $beratPohonTotal = $request->beratPohonTotal;
        $beratBatu = $request->beratBatu;

        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($idWaxInjectOrder) or $idWaxInjectOrder == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Pohonan Failed",
                "data"=>null,
                "error"=>[
                    "idWaxInjectOrder"=>"idWaxInjectOrder Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if idEmployee null or blank
        if (is_null($idEmployee) or $idEmployee == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Pohonan Failed",
                "data"=>null,
                "error"=>[
                    "idEmployee"=>"idEmployee Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if beratBatu null or blank
        if (is_null($beratBatu) or $beratBatu == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Pohonan Failed",
                "data"=>null,
                "error"=>[
                    "beratBatu"=>"beratBatu Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if beratPohonTotal null or blank
        if (is_null($beratPohonTotal) or $beratPohonTotal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Pohonan Failed",
                "data"=>null,
                "error"=>[
                    "beratPohonTotal"=>"beratPohonTotal Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Get WaxInjectOrder
        // Getting WaxInjectOrder
        $WaxInjectOrder = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                A.Qty AS totalQty,
                A.WorkGroup,
	            A.TreeStick,
                B.ID AS idKadar,
                B.Description AS Kadar,
                A.Purpose,
                A.RubberPlate AS idPohon,
                C.SW AS nomorPohon,
                C.Weight AS beratPohon
            FROM
                waxinjectorder A
                JOIN productcarat B ON A.Carat = B.ID
                JOIN rubberplate C ON A.RubberPlate = C.ID
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");
        if (count($WaxInjectOrder) == 0) {
            $data_return = $this->SetReturn(false, "Save NTHKO Pohon Direct Casting Failed. WaxInjectOrder not found", null, null);
            return response()->json($data_return, 404);
        }

        $WaxInjectOrder = $WaxInjectOrder[0];
        if ($WaxInjectOrder->Purpose != 'D') {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Bukanlah Direct Casting", null, null);
            return response()->json($data_return, 400);
        }

        // Get Item
        $items = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                C.WorkOrder,
                F.SW,
                D.Product,
                E.SW AS Barang,
                G.Description AS Kadar,
                D.Qty,
                D.Remarks 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WaxOrder AND T.Ordinal = B.WaxOrderOrd
                JOIN waxorderitem C ON C.TransferResinDC = T.IDM AND C.TransferResinDCOrd = T.Ordinal 
                JOIN workorderitem D ON C.WorkOrder = D.IDM AND C.WorkOrderOrd = D.Ordinal
                JOIN product E ON D.Product = E.ID
                JOIN workorder F ON C.WorkOrder = F.ID
                LEFT JOIN productcarat G ON E.VarCarat = G.ID 
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");

        // Get total Berat Batu & totalBatu
        // Get WorkOrder for kebutuhan batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("
            SELECT DISTINCT
                A.ID,
                T.WorkOrder,
                C.SW AS noSPK,
                D.SW 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WaxOrder AND T.Ordinal = B.WaxOrderOrd
                JOIN workorder C ON T.WorkOrder = C.ID
                JOIN product D ON T.Product = D.ID 
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");
        $workOrder = $GetWorkOrder[0];
        $idworkOrder = $workOrder->WorkOrder;
        // Get  kebutuhan batu
        $KebutuhanBatu = FacadesDB::connection('erp')
        ->select("
            select
                SUM(A.Weight) as totalBeratBatu,
                SUM(A.Qty) as totalBatu
            FROM
                waxstoneusageitem A
            WHERE 
                A.WorkOrder = '$idworkOrder'
        ");
        $KebutuhanBatu = $KebutuhanBatu[0];
        // REVISI BERAT BATU TOTAL JADI INPUTAN 2023-01-23;
        // $beratBatuTotal = is_null($KebutuhanBatu->totalBeratBatu) ? 0 : $KebutuhanBatu->totalBeratBatu; //Ini ngambil dari DB
        $beratBatuTotal = $beratBatu; //Ini inputan dari user
        if ($beratBatu != 0) {
            $totalBatu = is_null($KebutuhanBatu->totalBatu) ? 0 : $KebutuhanBatu->totalBatu;
        } else {
            $totalBatu = 0;
        }

        $berat = $beratPohonTotal - $WaxInjectOrder->beratPohon - $beratBatuTotal;
        
        // Kalkulasi using concat
        $Product = $workOrder->SW;
        $workOrder = ''.$workOrder->noSPK.'['.$WaxInjectOrder->totalQty.']';
        
        // Get Year
        $year = date('y');
        // Get Month
        $month = date('n');
        // Get ID and SWOrdinal for Waxtree
        $IDSWO = FacadesDB::connection('erp')
        ->select("
            SELECT
                CASE 
                WHEN MAX( SWOrdinal ) IS NULL THEN
                    CONCAT('$year' , '$month' ,'0001')
                ELSE
                    CONCAT(SWYear,LPAD(SWMonth, 2, '0'), LPAD(MAX( SWOrdinal )+ 1, 4, '0'))
                END as LastID,
                CASE WHEN MAX( SWOrdinal ) IS NULL THEN  '1'
                ELSE MAX( SWOrdinal) + 1
                END as LastOrdinal
            FROM
                waxtree 
            WHERE
                SWYear = '$year' AND SWMonth = '$month'
        ");
        $IDSWO = $IDSWO[0];
        
        // Insert Waxtree
        $insertWaxtree = waxtree::create([
            "ID"=>$IDSWO->LastID, 
            "UserName"=>Auth::user()->name, 
            "TransDate"=>date('Y-m-d'), 
            "Employee"=>$idEmployee,
            "WorkGroup"=>$WaxInjectOrder->WorkGroup, 
            "SW"=>$WaxInjectOrder->idPohon, 
            "TreeSize"=>"180", 
            "Weight"=>$berat, 
            "WeightPlate"=>$WaxInjectOrder->beratPohon, 
            "WeightStone"=>$beratBatuTotal, 
            "WeightWax"=>$beratPohonTotal, 
            "Priority"=>"N", 
            "Carat"=>$WaxInjectOrder->idKadar, 
            "WeightStoneCalc"=>$beratBatuTotal, 
            "WaxOrder"=>$idWaxInjectOrder, 
            "TreeStick"=>$WaxInjectOrder->TreeStick, 
            "GipsStatus"=>0, 
            "Active"=>"A", 
            "Qty"=>$WaxInjectOrder->totalQty, 
            "WorkOrder"=>$workOrder, 
            "Product"=>$Product, 
            "Model"=>null, 
            "SWYear"=>$year, 
            "SWMonth"=>$month, 
            "SWOrdinal"=>$IDSWO->LastOrdinal, 
            "WaxInjectCalc"=>null, 
            'WaxStoneCalc'=>$totalBatu,
            "Purpose"=>"D"
        ]);
        // Insert To Waxtreeitem
        foreach ($items as $key => $item) {
            $insertWaxtreeitem = waxtreeitem::create([
                "IDM"=>$IDSWO->LastID, 
                "Ordinal"=>$key+1, 
                "WorkOrder"=>$item->WorkOrder, 
                "Product"=>$item->Product, 
                "Qty"=>$item->Qty, 
                "OverTime"=>"N"
            ]);
        }

        // Next Update Waxstoneusage

        $data_return = $this->SetReturn(true, "Save NTHKO Pohonan Sukses", ['ID'=>$IDSWO->LastID], null);
        return response()->json($data_return, 200);
    }

    public function CetakNTHKOPohonan(Request $request){
        // Get idWaxTree
        $idWaxTree = $request->idWaxTree;
        // Get Header
        $data = FacadesDB::connection('erp')
        ->select("
            SELECT
                pp.SW,
                pp.Weight AS beratPohon,
                wt.Weight,
                wt.ID,
                wt.WeightStone,
                wt.WeightStoneCalc,
                e.SW NamaOp,
                wt.WeightWax,
                wt.WorkOrder,
                wt.TransDate,
                pc.Description AS Kadar,
                wt.WaxOrder,
                wt.Remarks,
                wt.Purpose
            FROM
                waxtree wt
                JOIN productcarat pc on wt.Carat = pc.ID
                JOIN rubberplate pp ON wt.SW = pp.ID
                JOIN employee e ON wt.Employee = e.ID
            WHERE
                wt.ID = '$idWaxTree'
        ");
        if (count($data) == 0) {
            abort(404);
        }
        $data = $data[0];
        if ($data->Purpose != 'D') {
            abort(404);
        }
        // IDWaxInjectOrder
        $idWaxInjectOrder = $data->WaxOrder;
        // Get Items
        $items = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                C.WorkOrder,
                F.SW,
                D.Product,
                E.SW AS Barang,
                G.Description AS Kadar,
                D.Qty,
                D.Remarks 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WaxOrder AND T.Ordinal = B.WaxOrderOrd
                JOIN waxorderitem C ON C.TransferResinDC = T.IDM AND C.TransferResinDCOrd = T.Ordinal 
                JOIN workorderitem D ON C.WorkOrder = D.IDM AND C.WorkOrderOrd = D.Ordinal
                JOIN product E ON D.Product = E.ID
                JOIN workorder F ON C.WorkOrder = F.ID
                LEFT JOIN productcarat G ON E.VarCarat = G.ID 
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");
        return view('Produksi.Lilin.NTHKOPohonan.cetak2',compact('data','items'));
    }

    public function SearchNTHKOPohonan(Request $request){
        $keyword = $request->keyword;
        // Cek waxtree if exists
        $waxTree = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                A.WaxOrder,
                A.Employee,
                A.TransDate,
                A.SW as idPohon,
                B.SW as namaPohon,
                B.Weight as BeratPohon,
                A.Qty,
                A.Weight,
                A.WeightWax,
                A.WeightStone,
                A.Carat as idKadar,
                C.Description as Kadar,
                A.Purpose
            FROM
                waxtree A
                JOIN rubberplate B ON A.SW = B.ID
                JOIN productcarat C ON A.Carat = C.ID
            WHERE
                A.ID = '$keyword'
        ");
        if (count($waxTree) == 0) {
            $data_return = $this->SetReturn(false, "Getting WaxTree Failed. WaxTree not found", null, null);
            return response()->json($data_return, 404);
        }
        $waxTree = $waxTree[0];
        if ($waxTree->Purpose != 'D') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut Bukanlah Direct Casting", null, null);
            return response()->json($data_return, 400);
        }
        // IDWaxInjectOrder
        $idWaxInjectOrder = $waxTree->WaxOrder;
        $databawah = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                C.WorkOrder,
                F.SW,
                D.Product,
                E.SW AS Barang,
                G.Description AS Kadar,
                D.Qty,
                D.Remarks 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WaxOrder AND T.Ordinal = B.WaxOrderOrd
                JOIN waxorderitem C ON C.TransferResinDC = T.IDM AND C.TransferResinDCOrd = T.Ordinal 
                JOIN workorderitem D ON C.WorkOrder = D.IDM AND C.WorkOrderOrd = D.Ordinal
                JOIN product E ON D.Product = E.ID
                JOIN workorder F ON C.WorkOrder = F.ID
                LEFT JOIN productcarat G ON E.VarCarat = G.ID 
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");
        $waxTree->items = $databawah;
        $data_return = $this->SetReturn(true, "Getting WaxTree Success. WaxTree found", $waxTree, null);
        return response()->json($data_return, 200);
    }

    public function UpdateNTHKO(Request $request){
        $idWaxTree = $request->idWaxTree;
        $idEmployee = $request->idEmployee;

        // Check if Waxtree Found
        $waxTree = waxtree::where('ID',$idWaxTree)->first();
        if (is_null($waxTree)) {
            $data_return = $this->SetReturn(false, "Getting WaxTree Failed. WaxTree not found", null, null);
            return response()->json($data_return, 404);
        }
        if ($waxTree->Purpose != 'D') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut bukan Direct Casting. Tidak dapat diubah", null, null);
            return response()->json($data_return, 400);
        }

        // update waxtree
        $updateWaxTree = waxtree::where('ID',$idWaxTree)->update([
            "Employee"=>$idEmployee
        ]);
        
        $data_return = $this->SetReturn(true, "Update Waxtree Success", null, null);
        return response()->json($data_return, 200);

    }
}

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

// use App\Models\tes_laravel\waxtree;
// use App\Models\tes_laravel\waxtreeitem;

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
        $historyWaxTree = waxtree::where('Purpose','D')->orderBy('EntryDate','DESC')->limit(10)->get();
        return view('Produksi.Lilin.NTHKOPohonan.index', compact('employee', 'datenow', 'historyWaxTree'));
    }

    public function getWaxInjectOrder($IDWaxInjectOrder){
        // Getting WaxInjectOrder
        $WaxInjectOrder = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                A.Remarks,
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
        // $cekNTHKO = waxtree::where('WaxOrder',$IDWaxInjectOrder)->first();
        // if (!is_null($cekNTHKO)) {
        //     $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Sudah di NTHKO", null, null);
        //     return response()->json($data_return, 400);
        // }

        // Getting Items of waxinjectorder from waxinjectorder
        // $item = FacadesDB::connection('erp')
        // ->select("
        //     SELECT
        //         A.ID,
        //         C.WorkOrder,
        //         F.SW,
        //         T.Product,
        //         E.SW AS Barang,
        //         G.Description AS Kadar,
        //         T.Qty
        //     FROM
        //         waxinjectorder A
        //         JOIN waxinjectorderitem B ON A.ID = B.IDM
        //         JOIN transferresindcitem T ON T.IDM = B.WorkScheduleID AND T.Ordinal = B.WorkScheduleOrdinal
        //         JOIN waxorderitem C ON C.IDM = B.WaxOrder AND C.Ordinal = B.WaxOrderOrd
        //         JOIN workorderitem D ON D.IDM = T.WorkOrder AND T.WorkOrderOrd = D.Ordinal
        //         JOIN product E ON T.Product = E.ID
        //         JOIN workorder F ON D.IDM = F.ID
        //         LEFT JOIN productcarat G ON E.VarCarat = G.ID  
        //     WHERE
        //         A.ID = '$IDWaxInjectOrder'
        // ");

        // Get Items cara baru dengan banyak workorder di tabel worklist3dp
        $item = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                FF.WorkOrder,
                F.SW,
                T.Product,
                E.SW AS Barang,
                G.Description AS Kadar,
                B.Qty 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WorkScheduleID AND T.Ordinal = B.WorkScheduleOrdinal
                JOIN waxorderitem C ON C.IDM = B.WaxOrder AND C.Ordinal = B.WaxOrderOrd
                JOIN workorderitem D ON D.IDM = T.WorkOrder AND T.WorkOrderOrd = D.Ordinal
                JOIN product E ON T.Product = E.ID
                JOIN rndnew.worklist3dpproductionitem FF ON FF.WorkOrder = B.Tatakan AND FF.Ordinal = B.Ordinal
                JOIN workorder F ON FF.WorkOrder = F.ID
                LEFT JOIN productcarat G ON E.VarCarat = G.ID 
            WHERE
                A.ID = '$IDWaxInjectOrder'
        ");

        // Check Item 
        if (count($item) == 0) {
            $data_return = $this->SetReturn(false, "SPKO tidak valid. Karena tidak ada item/barang di Database Untuk SPKO Tersebut.", null, null);
            return response()->json($data_return, 400);
        }

        // Get WorkOrder for kebutuhan batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("
            SELECT DISTINCT
                A.ID,
                C.ID AS idSPK,
                C.SW AS noSPK,
                D.SW 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WorkScheduleID AND T.Ordinal = B.WorkScheduleOrdinal
                JOIN rndnew.worklist3dpproductionitem FF ON FF.WorkOrder = B.Tatakan AND FF.Ordinal = B.Ordinal
                JOIN workorder C ON FF.WorkOrder = C.ID
                JOIN product D ON T.Product = D.ID 
            WHERE
                A.ID = '$IDWaxInjectOrder'
        ");
        $totalKebutuhanBatu = 0;
        $totalBeratKebutuhanBatu = 0;
        foreach ($GetWorkOrder as $key => $value) {
            $idworkOrder = $value->idSPK;
            // Get  kebutuhan batu
            $KebutuhanBatu = FacadesDB::connection('erp')
            ->select("
                SELECT
                    SUM(A.Weight) as totalBeratBatu,
                    SUM(A.Qty) as totalBatu
                FROM
                    waxstoneusageitem A
                WHERE 
                    A.WorkOrder = '$idworkOrder'
            ");
            $KebutuhanBatu = $KebutuhanBatu[0];
            if (!is_null($KebutuhanBatu->totalBeratBatu)) {
                $totalKebutuhanBatu += $KebutuhanBatu->totalBatu;
                $totalBeratKebutuhanBatu += $KebutuhanBatu->totalBeratBatu;
            }
        }

        $beratBatuTotal = $totalBeratKebutuhanBatu;
        $WaxInjectOrder->item = $item;
        $WaxInjectOrder->beratBatu = $beratBatuTotal;

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

        // Get Item Old
        // $items = FacadesDB::connection('erp')
        // ->select("
        //     SELECT
        //         A.ID,
        //         C.WorkOrder,
        //         F.SW,
        //         T.Product,
        //         E.SW AS Barang,
        //         G.Description AS Kadar,
        //         T.Qty,
        //         D.Remarks
        //     FROM
        //         waxinjectorder A
        //         JOIN waxinjectorderitem B ON A.ID = B.IDM
        //         JOIN transferresindcitem T ON T.IDM = B.WorkScheduleID AND T.Ordinal = B.WorkScheduleOrdinal
        //         JOIN waxorderitem C ON C.IDM = B.WaxOrder AND C.Ordinal = B.WaxOrderOrd
        //         JOIN workorderitem D ON D.IDM = T.WorkOrder AND T.WorkOrderOrd = D.Ordinal
        //         JOIN product E ON T.Product = E.ID
        //         JOIN workorder F ON D.IDM = F.ID
        //         LEFT JOIN productcarat G ON E.VarCarat = G.ID  
        //     WHERE
        //         A.ID = '$idWaxInjectOrder'
        // ");

        // Get Items cara baru dengan banyak workorder di tabel worklist3dp
        $items = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                FF.WorkOrder,
                F.SW,
                T.Product,
                E.SW AS Barang,
                G.Description AS Kadar,
                FF.Qty 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WorkScheduleID AND T.Ordinal = B.WorkScheduleOrdinal
                JOIN waxorderitem C ON C.IDM = B.WaxOrder AND C.Ordinal = B.WaxOrderOrd
                JOIN workorderitem D ON D.IDM = T.WorkOrder AND T.WorkOrderOrd = D.Ordinal
                JOIN product E ON T.Product = E.ID
                JOIN rndnew.worklist3dpproductionitem FF ON FF.WorkOrder = B.Tatakan AND FF.Ordinal = B.Ordinal
                JOIN workorder F ON FF.WorkOrder = F.ID
                LEFT JOIN productcarat G ON E.VarCarat = G.ID 
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");

        // Get total Berat Batu & totalBatu
        // Get WorkOrder for kebutuhan batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
                A.ID,
                C.ID AS idSPK,
                C.SW AS noSPK,
                LEFT (C.SW, 1) AS SWO,
                D.SW 
            FROM
                waxinjectorder A
                JOIN waxinjectorderitem B ON A.ID = B.IDM
                JOIN transferresindcitem T ON T.IDM = B.WorkScheduleID AND T.Ordinal = B.WorkScheduleOrdinal
                JOIN rndnew.worklist3dpproductionitem FF ON FF.WorkOrder = B.Tatakan AND FF.Ordinal = B.Ordinal
                JOIN workorder C ON FF.WorkOrder = C.ID
                JOIN product D ON T.Product = D.ID 
            WHERE
                A.ID = '$idWaxInjectOrder'
        ");
        $workOrder = $GetWorkOrder[0];
        $SWO = $workOrder->SWO;

        $totalKebutuhanBatu = 0;
        $totalBeratKebutuhanBatu = 0;
        foreach ($GetWorkOrder as $key => $value) {
            $idworkOrder = $value->idSPK;
            // Get  kebutuhan batu
            $KebutuhanBatu = FacadesDB::connection('erp')
            ->select("
                SELECT
                    SUM(A.Weight) as totalBeratBatu,
                    SUM(A.Qty) as totalBatu
                FROM
                    waxstoneusageitem A
                WHERE 
                    A.WorkOrder = '$idworkOrder'
            ");
            $KebutuhanBatu = $KebutuhanBatu[0];
            if (!is_null($KebutuhanBatu->totalBeratBatu)) {
                $totalKebutuhanBatu += $KebutuhanBatu->totalBatu;
                $totalBeratKebutuhanBatu += $KebutuhanBatu->totalBeratBatu;
            }
        }
        // REVISI BERAT BATU TOTAL JADI INPUTAN 2023-01-23;
        // $beratBatuTotal = is_null($KebutuhanBatu->totalBeratBatu) ? 0 : $KebutuhanBatu->totalBeratBatu; //Ini ngambil dari DB
        $beratBatuTotal = $beratBatu; //Ini inputan dari user
        if ($beratBatu != 0) {
            $totalBatu = $totalKebutuhanBatu;
        } else {
            $totalBatu = 0;
        }

        $berat = $beratPohonTotal - $WaxInjectOrder->beratPohon - $beratBatuTotal;
        
        // Kalkulasi using concat
        $Product = $workOrder->SW;
        $workOrder = ''.$workOrder->noSPK.'['.$WaxInjectOrder->totalQty.']';
        
        // Get Year
        if($SWO=='O'){
            $year = date('y');
        }else{
            $year = date('y') + 50;
        }
        
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
        // dd($IDSWO);
        
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

        // update data ketika insert
        $UpdateWaxTreeA = "UPDATE WaxTree A,(
            SELECT
                A.IDM,
                A.Qty,
                A.WorkOrder,
                Group_Concat( Concat( B.Model, ' ', B.Product ) ORDER BY B.Model SEPARATOR ' ; ' ) Product,
            C.Model 
            FROM
                (
                SELECT
                    I.IDM,
                    Sum( I.Qty ) Qty,
                    Group_Concat(
                        DISTINCT Concat( O.SW, '(', M.SW, ')', '[', T.QtyTree, ']' ) 
                    ORDER BY
                        O.SWOrdinal SEPARATOR ',' 
                    ) WorkOrder 
                FROM
                    WaxTreeItem I
                    JOIN Product P ON I.Product = P.ID
                    JOIN WorkOrder O ON I.WorkOrder = O.ID
                    JOIN Product M ON O.Product = M.ID
                    JOIN ( SELECT WorkOrder, Sum( Qty ) QtyTree FROM WaxTreeItem WHERE IDM = $IDSWO->LastID GROUP BY WorkOrder ) T ON O.ID = T.WorkOrder 
                WHERE
                    I.IDM = $IDSWO->LastID 
                GROUP BY
                    I.IDM 
                ) A
                JOIN (
                SELECT
                    I.IDM,
                    IF(SW NOT LIKE '%-%',SUBSTRING_INDEX(P.SW,'.',1), SUBSTRING_INDEX(P.SW,'-',1)) Model,
                    Group_Concat( DISTINCT IfNull( P.SerialNo, '-' ) ORDER BY P.SerialNo SEPARATOR ',' ) Product 
                FROM
                    WaxTreeItem I
                    JOIN Product P ON I.Product = P.ID
                WHERE
                    I.IDM = $IDSWO->LastID 
                GROUP BY
                    I.IDM,
                    Model 
                ) B ON A.IDM = B.IDM
                JOIN (
                SELECT
                    I.IDM,
                    Group_Concat(Distinct IF(P.SW NOT LIKE '%-%',SUBSTRING_INDEX(P.SW,'.',1), SUBSTRING_INDEX(P.SW,'-',1)) Order By P.SW Separator ', ') Model
                FROM
                    WaxTreeItem I
                    JOIN Product P ON I.Product = P.ID
                WHERE
                    I.IDM = $IDSWO->LastID 
                GROUP BY
                    I.IDM 
                ) C ON A.IDM = C.IDM 
            ) B 
            SET A.Qty = B.Qty,
            A.WorkOrder = B.WorkOrder,
            A.Product = B.Product,
            A.Model = B.Model 
        WHERE
            A.ID = B.IDM 
            AND A.ID = $IDSWO->LastID";
        $UpdateWaxtreeASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeA);

        //Update WaxtreeItem Kolom [Componen]
        $UpdateWaxTreeItemA = "UPDATE WaxTreeItem I, Product P Set P.Component = I.Component 
                            Where P.ID = I.Product And I.Component Is Not Null And I.IDM = $IDSWO->LastID";
        $UpdateWaxTreeItemASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeItemA);
        
        //Update Waxtree kolom [WaxInjectCalc]
        $UpdateWaxTreeB = "UPDATE WaxTree W, (Select A.IDM, Sum(A.Total) TotalInject
        From ( Select S.IDM, S.Ordinal, P.SW Product, Round(S.Qty / If(P.ProdGroup In (6, 10) Or Model In (47739), R.Pcs / 2, R.Pcs)) Total
            From WaxTreeItem S
                Join ProductPart A On S.Product = A.IDM
                Join Rubber R On A.Product = R.Product And R.UnusedDate Is Null And R.Pcs Is Not Null And R.TransDate > '2018-01-01'
                Join Product P On A.Product = P.ID
            Where S.IDM = $IDSWO->LastID
            Group By S.IDM, S.Ordinal, P.SW ) A Group By A.IDM ) R
        Set W.WaxInjectCalc = R.TotalInject
        Where W.ID = $IDSWO->LastID And W.ID = R.IDM";
        $UpdateWaxTreeBSucces = FacadesDB::connection('erp')->update($UpdateWaxTreeB);

        //Update Waxtree Kolom [WaxStoneCalc]
        $UpdateWaxTreeC = "UPDATE WaxTree W,( Select A.IDM, Sum(A.Total) TotalStone
        From ( Select S.IDM, S.Ordinal, Sum(S.Qty * A.Qty) Total
                  From WaxTreeItem S
                    Join ProductAccessories A On S.Product = A.IDM
                    Join Product Q On A.Product = Q.ID And Q.ProdGroup = 126
                  Where S.IDM = $IDSWO->LastID
                  Group By S.IDM, S.Ordinal ) A Group By A.IDM ) R
        Set W.WaxStoneCalc = R.TotalStone
        Where W.ID = $IDSWO->LastID And W.ID = R.IDM";
        $UpdateWaxTreeCSucces = FacadesDB::connection('erp')->update($UpdateWaxTreeC);

        //Update Waxtree Kolom [TotalPoles, TotalPatri, TotalPUK]
        $UpdateWaxTreeD = "UPDATE WaxTree A, (Select I.IDM, M.BrtKunci * I.Qty Poles, M.BrtKompA * I.Qty Patri, M.BrtKompB * I.Qty PUK
        From WaxTreeItem I
            Join Product P On I.ProductVariation = P.ID
            Join Product M On IfNull(P.Marketing, P.Model) = M.ID
        Where I.IDM = $IDSWO->LastID ) B
        Set A.TotalPoles = B.Poles,
            A.TotalPatri = B.Patri,
            A.TotalPUK = B.PUK
        Where A.ID = B.IDM And A.ID = $IDSWO->LastID";
        $UpdateWaxTreeDSucces = FacadesDB::connection('erp')->update($UpdateWaxTreeD);

        //Update Workorder Kolom [WaxTree]
        $UpdateWorkOrder = "UPDATE WorkOrder O, 
        (Select Distinct I.WorkOrder, T.TransDate
            From WaxTree T 
                Join WaxTreeItem I On T.ID = I.IDM
            Where T.ID = $IDSWO->LastID ) W
        Set O.WaxTree = W.TransDate
        Where O.ID = W.WorkOrder";
        $UpdateWorkOrderSucces = FacadesDB::connection('erp')->update($UpdateWorkOrder);

        //Update Waxtree Kolom [WeightFG]
        $UpdateWaxTreeE = "UPDATE WaxTree T, (
            Select W.ID, Sum(S.Weight) Weight
                From ( 
                    Select Distinct T.ID, I.WorkOrder
                        From WaxTree T 
                        Join WaxTreeItem I On T.ID = I.IDM
                        Where T.WaxOrder Is Not Null And T.ID = $IDSWO->LastID ) W
                    Join WorkOrderItem O On O.IDM = W.WorkOrder
                    Join WorkSuggestionItem S On O.WorkSuggestion = S.IDM And O.WorkSuggestionOrd = S.Ordinal
                Group By W.ID ) Z
            Set T.WeightFG = Z.Weight
            Where T.ID = $IDSWO->LastID";
        $UpdateWaxTreeESucces = FacadesDB::connection('erp')->update($UpdateWaxTreeE);

        $data_return = $this->SetReturn(true, "Save NTHKO Pohonan Sukses", ['ID'=>$IDSWO->LastID], null);
        return response()->json($data_return, 200);
    }

    public function CetakNTHKOPohonan(Request $request){
        // Get idWaxTree
        $idWaxTree = $request->idWaxTree;
        // Get Header
        $data = FacadesDB::connection('erp')
        ->select("SELECT
                pp.SW AS SWplate,
                pp.Weight AS BeratPiring,
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
                D.Remarks,
                wt.Purpose,
                wt.WorkGroup
            FROM
                waxtree wt
                JOIN productcarat pc on wt.Carat = pc.ID
                JOIN rubberplate pp ON wt.SW = pp.ID
                JOIN employee e ON wt.Employee = e.ID
                JOIN waxinjectorder D ON wt.WaxOrder = D.ID
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
        ->select("SELECT
                F.ID,
                C.ID AS WorkOrder,
                C.SW AS SPKPPIC,
                D.ID AS Product,
                D.SW AS Barang,
                E.Description AS Kadar,
                B.Qty,
                B.NoteVariation
            FROM
                waxtree A
                JOIN waxtreeitem B ON A.ID = B.IDM
                JOIN workorder C ON B.WorkOrder = C.ID
                JOIN product D ON B.Product = D.ID
                JOIN productcarat E ON D.VarCarat = E.ID
                JOIN waxinjectorder F ON A.WaxOrder=F.ID
                -- LEFT JOIN ShortText L ON D.Logo = L.ID
            WHERE
                A.ID = '$idWaxTree'
        ");

        $pendinginan = facadesDB::connection('erp')->select("SELECT
        P.Description,
        P.Size,
        K.Carat,
        LEFT ( P.Size, 1 ) Size,
        C.SW,
    CASE
            
            WHEN C.SW IN ( '6K', '8K', '10K', '16K', '19K', '20K', '8K.', '17K.', '17K' ) 
            AND P.Size IS NULL THEN
                '10mnt...' 
                WHEN C.SW IN ( '6K', '8K', '10K', '16K', '19K', '20K' ) 
                AND LEFT ( P.Size, 1 )< 4 THEN
                    '30mnt..' 
                    WHEN C.SW IN ( '6K', '8K', '10K', '16K', '19K', '20K' ) 
                    AND LEFT ( P.Size, 1 )>= 4 THEN
                        '1jam' 
                        WHEN C.SW IN ( '8K.', '17K.' ) 
                        AND LEFT ( P.Size, 1 ) < 4 THEN '1jam' WHEN C.SW IN ( '8K.', '17K.' ) AND LEFT ( P.Size, 1 ) >= 4 THEN
                            '90mnt' 
                            WHEN C.SW IN ( '17K' ) 
                            AND LEFT ( P.Size, 1 ) > 0 THEN
                                '30mnt..' ELSE '10mnt...' 
                            END GipsNote 
    FROM
    waxtree A
        LEFT JOIN waxinjectorder W ON W.ID = A.WaxOrder
        LEFT JOIN waxinjectorderitem WI ON W.ID = WI.IDM
        LEFT JOIN waxorderitem XI ON WI.WaxOrder = XI.IDM 
        AND WI.WaxOrderOrd = XI.Ordinal
        LEFT JOIN workorderitem KI ON KI.IDM = XI.WorkOrder 
        AND KI.Ordinal = XI.WorkOrderOrd
        LEFT JOIN workorder K ON XI.WorkOrder = K.ID
        LEFT JOIN waxstoneusage T ON T.WaxOrder = W.ID
        LEFT JOIN waxstoneusageitem TI ON T.ID = TI.IDM
        LEFT JOIN Product P ON P.ID = TI.Product
        LEFT JOIN productcarat C ON C.ID = K.Carat 
    WHERE
        A.ID = '$idWaxTree'
    GROUP BY
        P.ID,
        W.ID,
        T.Purpose 
    ORDER BY
        CHAR_LENGTH( GipsNote )");
        
        return view('Produksi.Lilin.NTHKOPohonan.cetak2',compact('data','items','pendinginan'));
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
                A.Purpose,
                D.Remarks
            FROM
                waxtree A
                JOIN rubberplate B ON A.SW = B.ID
                JOIN productcarat C ON A.Carat = C.ID
                JOIN waxinjectorder D ON A.WaxOrder = D.ID
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
                F.ID,
                C.ID AS WorkOrder,
                C.SW,
                D.ID AS Product,
                D.SW AS Barang,
                E.Description AS Kadar,
                B.Qty
            FROM
                waxtree A
                JOIN waxtreeitem B ON A.ID = B.IDM
                JOIN workorder C ON B.WorkOrder = C.ID
                JOIN product D ON B.Product = D.ID
                JOIN productcarat E ON D.VarCarat = E.ID
                JOIN waxinjectorder F ON A.WaxOrder=F.ID
            WHERE
                A.ID = '$keyword'
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
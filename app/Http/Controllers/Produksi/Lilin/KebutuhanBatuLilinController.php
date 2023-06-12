<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// live
use App\Models\erp\rubberplate;
use App\Models\erp\waxtree;
use App\Models\erp\waxtreeitem;

// lokal
// use App\Models\tes_laravel\rubberplate;
// use App\Models\tes_laravel\waxtree;
// use App\Models\tes_laravel\waxtreeitem;
// use App\Models\tes_laravel\waxtree;
// use App\Models\tes_laravel\waxtreeitem;

class KebutuhanBatuLilinController extends Controller{
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
        $historyWaxTree = waxtree::where('Purpose','I')->orderBy('ID','DESC')->limit(10)->get();
        return view('Produksi.Lilin.KebutuhanBatuLilin.index', compact('employee', 'datenow', 'historyWaxTree'));
    }

    public function getWaxInjectOrder($IDWaxInjectOrder){
        // Getting WaxInjectOrder
        $WaxInjectOrder = FacadesDB::connection('erp')
        ->select("SELECT
                W.ID,
                W.Qty AS totalQty,
                B.ID AS idKadar,
                B.Description AS Kadar,
                W.Purpose,
                W.WorkGroup,
                W.RubberPlate AS IdPiring,
                C.SW AS NomorPiring,
                C.Weight AS BeratPiring,
                T.ID AS idstick,
                CONCAT(T.SW,'-',T.Description) stickpohon
               
            FROM
                waxinjectorder W
                JOIN productcarat B ON W.Carat = B.ID
                JOIN rubberplate C ON W.RubberPlate = C.ID
                JOIN treestick T ON T.ID = W.TreeStick
            WHERE
                W.ID = '$IDWaxInjectOrder'
        ");
        
        if (count($WaxInjectOrder) == 0) {
            $data_return = $this->SetReturn(false, "ID SPK Inject Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $WaxInjectOrder = $WaxInjectOrder[0];
        if ($WaxInjectOrder->Purpose != 'I') {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Bukanlah SPK Inject", null, null);
            return response()->json($data_return, 400);
        }
        
        // Check if that ID its already NTHKOed.
        // $cekNTHKO = waxtree::where('WaxOrder',$IDWaxInjectOrder)->first();
        // if (!is_null($cekNTHKO)) {
        //     $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Sudah di NTHKO", null, null);
        //     return response()->json($data_return, 400);
        // }


        // Get Items cara baru dengan banyak workorder di tabel worklist3dp
        $items = FacadesDB::connection('erp')
        ->select("SELECT
        W.ID,
        XI.WorkOrder,
        XI.WorkOrderOrd,
        K.SW,
        XI.Product,
        P.SW AS Barang,
        PC.Description AS Kadar,
        PC.ID AS IDKadar,
        WI.Qty,
        P.Description,
        CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
		DI.Qty QtyRph,
		DI.Weight,
        K.TotalWeight,
        CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
        KI.Product ProductVariation,
        KI.Remarks NoteVariation
		
    FROM
        waxinjectorder W
        JOIN waxinjectorderitem WI ON W.ID = WI.IDM
		LEFT JOIN workscheduleitem DI ON DI.IDM = WI.WorkScheduleID AND DI.Ordinal = WI.WorkScheduleOrdinal
        JOIN waxorderitem XI ON XI.IDM = WI.WaxOrder 
        AND XI.Ordinal = WI.WaxOrderOrd
        JOIN workorderitem KI ON KI.IDM = XI.WorkOrder 
        AND XI.WorkOrderOrd = KI.Ordinal
        JOIN workorder K ON XI.WorkOrder = K.ID
        JOIN product P ON XI.Product = P.ID
        JOIN productcarat PC ON K.Carat = PC.ID
        LEFT JOIN shorttext S ON S.ID = P.ProdGroup
    WHERE
        W.ID = '$IDWaxInjectOrder'
        ");

        // Check Item 
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false, "SPKO tidak valid. Karena tidak ada item/barang di Database Untuk SPKO Tersebut.", null, null);
            return response()->json($data_return, 400);
        }

        // Get WorkOrder for kebutuhan batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
        W.ID,
        K.ID AS idSPK,
        K.SW AS noSPK,
        P.SW 
    FROM
        waxinjectorder W
        JOIN waxinjectorderitem WI ON W.ID = WI.IDM
        JOIN waxorderitem XI ON WI.WaxOrder = XI.IDM AND WI.WaxOrderOrd = XI.Ordinal
        JOIN workorderitem KI ON KI.IDM = XI.WorkOrder AND KI.Ordinal = XI.WorkOrderOrd
        JOIN workorder K ON XI.WorkOrder = K.ID
        JOIN product P ON XI.Product = P.ID 
    WHERE
        W.ID ='$IDWaxInjectOrder'
        ");

        $getinformasibatu = FacadesDB::connection('erp')
        ->select("SELECT
        T.WaxOrder,
        TI.IDM,
        T.Purpose,
        P.SW,
        P.Description,
        TI.Weight,
        A.berattotal,
        TI.Qty,
        A.jumlahtotal,
        P.Size,
        SUM(TI.Weight / TI.Qty) Avg
        FROM
            waxinjectorder W
        JOIN waxinjectorderitem WI ON W.ID = WI.IDM
        JOIN waxorderitem XI ON WI.WaxOrder = XI.IDM AND WI.WaxOrderOrd = XI.Ordinal
        JOIN workorderitem KI ON KI.IDM = XI.WorkOrder AND KI.Ordinal = XI.WorkOrderOrd
        JOIN workorder K ON XI.WorkOrder = K.ID
            JOIN waxstoneusage T ON T.WaxOrder = W.ID
            JOIN waxstoneusageitem TI ON T.ID = TI.IDM
            JOIN Product P ON P.ID = TI.Product
        LEFT JOIN (
            SELECT
                T.ID,
                SUM( TI.Weight ) berattotal,
                SUM( TI.Qty ) jumlahtotal 
            FROM
                waxstoneusage T
                JOIN waxstoneusageitem TI ON T.ID = TI.IDM
                JOIN waxinjectorder W ON W.ID = T.WaxOrder 
            WHERE
                W.ID = '$IDWaxInjectOrder' 
            ) A ON A.ID = T.ID
        WHERE
            W.ID = '$IDWaxInjectOrder'
            GROUP BY 
            P.ID,
            W.ID,
            T.Purpose
        ");


        $totalKebutuhanBatu = 0;
        $totalBeratKebutuhanBatu = 0;
      

            $idworkOrder = $GetWorkOrder[0]->idSPK;
            // Get  kebutuhan batu
            $KebutuhanBatu = FacadesDB::connection('erp')
            ->select("SELECT SUM(Z.totalBeratBatu) as totalBeratBatu, SUM(Z.totalBatu) as totalBatu FROM(
                SELECT
                   SUM(A.Weight) as totalBeratBatu,
                   SUM(A.Qty) as totalBatu
                FROM
                   waxstoneusageitem A
                     JOIN Waxstoneusage B ON B.ID = A.IDM
                WHERE 
                        A.WorkOrder = '$idworkOrder'
                        AND B.Purpose = 'Tambah'
                UNION
                SELECT
                   CONCAT('-',SUM(A.Weight)) as totalBeratBatu,
                   CONCAT('-',SUM(A.Qty)) as totalBatu
                FROM
                   waxstoneusageitem A
                     JOIN Waxstoneusage B ON B.ID = A.IDM
                WHERE 
                        A.WorkOrder = '$idworkOrder'
                        AND B.Purpose = 'Kurang') Z
            ");
            $KebutuhanBatu = $KebutuhanBatu[0];
            if (!is_null($KebutuhanBatu->totalBeratBatu)) {
                // $totalKebutuhanBatu += $KebutuhanBatu->totalBatu;
                $totalBeratKebutuhanBatu += $KebutuhanBatu->totalBeratBatu;
            }
            if (!is_null($KebutuhanBatu->totalBatu)) {
                $totalKebutuhanBatu += $KebutuhanBatu->totalBatu;
                // $totalBeratKebutuhanBatu += $KebutuhanBatu->totalBeratBatu;
            }
        

        $beratBatuTotal = $totalBeratKebutuhanBatu;
        $totalBatu = $totalKebutuhanBatu;
        $WaxInjectOrder->item = $items;
        $WaxInjectOrder->batu = $getinformasibatu;
        $WaxInjectOrder->beratBatu = $beratBatuTotal;
        // dd($beratBatuTotal);
        $WaxInjectOrder->QtyBatu = $totalBatu;
        // dd($totalBatu);
        
        // $waxInjectOrder->QtyBatu = $totalBatu;

        $data_return = $this->SetReturn(true, "Getting WaxInjectOrder Item Success", $WaxInjectOrder, null);
        return response()->json($data_return, 200);
    }

    
    public function GetPiring(Request $request){
        $keyword = $request->keyword;
        // Get rubberplate atau pohon
        $rubberplate = rubberplate::where('SW',$keyword)->where('Active','Y')->first();
        if (is_null($rubberplate)) {
            $data_return = $this->SetReturn(false, "Getting Plate Failed. Rubberplate not found", null, null);
            return response()->json($data_return, 404);
        }
        $data_return = $this->SetReturn(true, "Getting Plate Success", $rubberplate, null);
        return response()->json($data_return, 200);
    }

    public function cariSWItemProduct(Request $request){
        $SWItemProduct = $request->switem;
        $SWWorkOrder = $request->work;

        $SWData = FacadesDB::connection('erp')->select("SELECT
            W.ID,
            XI.WorkOrder,
            XI.WorkOrderOrd,
            K.SW,
            XI.Product,
            P.SW AS Barang,
            PC.Description AS Kadar,
            PC.ID AS IDKadar,
            WI.Qty,
            P.Description,
            CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
            DI.Qty QtyRph,
            DI.Weight,
            K.TotalWeight,
            
            CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            KI.Product ProductVariation,
            KI.Remarks NoteVariation
            
        FROM
            waxinjectorder W
            LEFT JOIN waxinjectorderitem WI ON W.ID = WI.IDM
            LEFT JOIN workscheduleitem DI ON DI.IDM = WI.WorkScheduleID AND DI.Ordinal = WI.WorkScheduleOrdinal
            JOIN waxorderitem XI ON XI.IDM = WI.WaxOrder 
            AND XI.Ordinal = WI.WaxOrderOrd
            JOIN workorderitem KI ON KI.IDM = XI.WorkOrder 
            AND XI.WorkOrderOrd = KI.Ordinal
            JOIN workorder K ON XI.WorkOrder = K.ID
            JOIN product P ON XI.Product = P.ID
            JOIN productcarat PC ON K.Carat = PC.ID
            LEFT JOIN shorttext S ON S.ID = P.ProdGroup
        WHERE
            P.SW ='$SWItemProduct' AND K.SW = '$SWWorkOrder'
        ");

        $rowcount = count($SWData);
        if($rowcount > 0 ){
            foreach ($SWData as $datas){}
            $WorkOrder = $datas->WorkOrder;
            $SW = $datas->SW;
            $Product = $datas->Product;
            $Barang = $datas->Barang;
            $Kadar = $datas->Kadar;
            $IDKadar = $datas->IDKadar;
            $Qty = $datas->Qty;
            $Description = $datas->Description;
            $dcinfo = $datas->dcinfo;
            $QtyRph = $datas->QtyRph;
            $Weight = $datas->Weight;
            $HexColor = $datas->HexColor;
            $WorkOrderOrd = $datas->WorkOrderOrd;
            $ProductVariation = $datas->ProductVariation;
            $NoteVariation = $datas->NoteVariation;

            $data_Return = array(
                'rowcount' => $rowcount,
                'WorkOrder' => $WorkOrder,
                'SW' => $SW,
                'Product' => $Product,
                'Barang' => $Barang,
                'Kadar' => $Kadar,
                'IDKadar' => $IDKadar,
                'Qty' => $Qty,
                'Description' => $Description,
                'Kadar' => $Kadar,
                'dcinfo' => $dcinfo,
                'HexColor' => $HexColor,
                'QtyRph' => $QtyRph,
                'Weight' => $Weight,
                'WorkOrderOrd' => $WorkOrderOrd,
                'ProductVariation' => $ProductVariation,
                'NoteVariation' => $NoteVariation
            );
        
        }else{
            $data_Return = array ('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }

    public function Simpan(Request $request){
        // Get Required Data
        $idWaxInjectOrder = $request->idWaxInjectOrder;
        $idEmployee = $request->idEmployee;
        $tanggal = $request->tanggal;
        $stickpohon = $request->stickpohon;
        $idstickpohon = $request->idstickpohon;
        $piring = $request->piring;
        $beratpiring = $request->beratpiring;
        $totalQty = $request->totalQty;
        $beratPohonTotal = $request->beratPohonTotal;
        $beratBatu = $request->beratBatu;
        $banyakBatu = $request->banyakBatu;
        // dd($banyakBatu);
        $beratResin = $request->beratResin;
        $idkadar = $request->idKadar;
        $Catatan = $request->Catatan;
        $purpose = $request->purpose;
        $WorkGroup = $request->WorkGroup;
        $chekitem = $request->items;
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();
        $chekheader = [$request->idWaxInjectOrder, $request->idEmployee, $request->tanggal, $request->stickpohon, 
                        $request->idstickpohon, $request->piring, $request->beratpiring, $request->totalQty, $request->beratPohonTotal,
                        $request->beratBatu, $request->beratResin, $request->idKadar, $request->Catatan, $username, $request->purpose, $request->WorkGroup];
        // dd($chekheader);
        // dd($chekitem);

        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($idWaxInjectOrder) or $idWaxInjectOrder == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Inject Failed",
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
                "message"=>"Save NTHKO Inject Failed",
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
                "message"=>"Save NTHKO Inject Failed",
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
                "message"=>"Save NTHKO Inject Failed",
                "data"=>null,
                "error"=>[
                    "beratPohonTotal"=>"beratPohonTotal Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // check if plate null or blank
        if (is_null($piring) or $piring == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Inject Failed",
                "data"=>null,
                "error"=>[
                "platel"=>"plate Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // chek if sticktree null or blank
        if (is_null($idstickpohon) or $idstickpohon == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Save NTHKO Inject Failed",
                "data"=>null,
                "error"=>[
                "idstickpohon"=>"idstickpohon Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
    
        if (is_null($idWaxInjectOrder) or $idWaxInjectOrder == 0) {
            $data_return = $this->SetReturn(false, "Gagal Menyimpan NTHKO. WaxinjectOrder Tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }

        if ($purpose != 'I') {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Bukanlah Inject", null, null);
            return response()->json($data_return, 400);
        }
    
        // Get total Berat Batu & totalBatu
        // Get WorkOrder for kebutuhan batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
            W.ID,
            K.ID AS idSPK,
            K.SW AS noSPK,
            P.SW 
        FROM
            waxinjectorder W
            JOIN waxinjectorderitem WI ON W.ID = WI.IDM
            JOIN waxorderitem XI ON WI.WaxOrder = XI.IDM AND WI.WaxOrderOrd = XI.Ordinal
            JOIN workorderitem KI ON KI.IDM = XI.WorkOrder AND KI.Ordinal = XI.WorkOrderOrd
            JOIN workorder K ON XI.WorkOrder = K.ID
            JOIN product P ON XI.Product = P.ID 
            WHERE
                W.ID = '$idWaxInjectOrder'
        ");
        $workOrder = $GetWorkOrder[0];

        $totalKebutuhanBatu = 0;
        $totalBeratKebutuhanBatu = 0;
        
        $idworkOrder = $workOrder->idSPK;
        
         
        // REVISI BERAT BATU TOTAL JADI INPUTAN 2023-01-23;
        // $beratBatuTotal = is_null($KebutuhanBatu->totalBeratBatu) ? 0 : $KebutuhanBatu->totalBeratBatu; //Ini ngambil dari DB
    
        
        // Kalkulasi using concat
        $Product = $workOrder->SW;
        $workOrder = ''.$workOrder->noSPK.'['.$totalQty.']';
        
        // Get Year
        $year = date('y');
        // Get Month
        $month = date('n');
        // Get ID and SWOrdinal for Waxtree
        $IDSWO = FacadesDB::connection('erp')
        ->select("SELECT
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
        $berat = $beratPohonTotal - $beratpiring - $beratBatu;
        
        // Insert Waxtree
        $insertWaxtree = waxtree::create([
            "ID"=>$IDSWO->LastID, 
            "UserName"=>$username, 
            "TransDate"=>date('Y-m-d'), 
            "Employee"=>$idEmployee,
            "Remarks"=>$Catatan,
            "WorkGroup"=>$WorkGroup, 
            "SW"=>$piring, 
            "TreeSize"=>"180", 
            "Weight"=>$berat, 
            "WeightPlate"=>$beratpiring, 
            "WeightStone"=>$beratBatu, 
            "WeightWax"=>$beratPohonTotal, 
            "Priority"=>"N", 
            "Carat"=>$idkadar, 
            "WeightStoneCalc"=>$beratBatu, 
            "WaxOrder"=>$idWaxInjectOrder, 
            "TreeStick"=>$idstickpohon, 
            "GipsStatus"=>0, 
            "Active"=>"A", 
            "Qty"=>$totalQty, 
            "WorkOrder"=>$workOrder, 
            "Product"=>$Product, 
            "Model"=>null, 
            "SWYear"=>$year, 
            "SWMonth"=>$month, 
            "SWOrdinal"=>$IDSWO->LastOrdinal, 
            "WaxInjectCalc"=>null, 
            "WaxStoneCalc"=>$banyakBatu,
            "WaxInject"=>null,
            "WaxStone"=>null,
            "TotalPatri"=>null,
            "TotalPUK"=>null,
            "WorkTime"=>null,
            "WeightFG"=>null,
            "Purpose"=>"I"
        ]);

        // dd($insertWaxtree);

        
        // Insert To Waxtreeitem
        foreach ($request->items as $key => $item) {
            $insertWaxtreeitem = waxtreeitem::create([
                "IDM"=>$IDSWO->LastID, 
                "Ordinal"=>$key+1, 
                "WorkOrder"=>$item['workOrder'], 
                "Product"=>$item['idProduct'], 
                "Qty"=>$item['itemQty'], //item product
                "OverTime"=>"N",
                "Size"=>NULL,
                "Note"=>$item['remark'],
                "WeightStone"=>NULL,
                "ProductVariation"=>$item['ProductVar'], //product jadi
                "NoteVariation"=>$item['NoteVar'],
                "Component"=>null,
                "LinkID"=>null,
                "LinkOrd"=>null,
                "LinkSubOrd"=>null,
                "WorkOrderOrd"=>$item['WorkOrderOrd']
            ]);
        }
        // dd($insertWaxtreeitem);
        // Update Waxtree Kolom [Qty, Product, WorkORder, Model]
        $UpdateWaxTreeA = "UPDATE WaxTree A,(Select A.IDM, A.Qty, A.WorkOrder, Group_Concat(Concat(B.Model, ' ', B.Product) Order By B.Model Separator ' ; ') Product, C.Model
           From (Select I.IDM, Sum(I.Qty) Qty, Group_Concat(Distinct Concat(O.SW, '(', M.SW, ')', '[', T.QtyTree, ']') Order By O.SWOrdinal Separator ',') WorkOrder
                From WaxTreeItem I
                     Join Product P On I.Product = P.ID
                     Join WorkOrder O On I.WorkOrder = O.ID
                     Join Product M On O.Product = M.ID
                     Join ( Select WorkOrder, Sum(Qty) QtyTree From WaxTreeItem
                Where IDM = $IDSWO->LastID 
                Group By WorkOrder ) T On O.ID = T.WorkOrder
                Where I.IDM = $IDSWO->LastID
                Group By I.IDM ) A
 
                Join (Select I.IDM, M.SW Model, Group_Concat(Distinct IfNull(P.SerialNo, '-') Order By P.SerialNo Separator ',') Product
                    From WaxTreeItem I
                        Join Product P On I.Product = P.ID
                        Join Product M On P.Model = M.ID
                    Where I.IDM = $IDSWO->LastID
                    Group By I.IDM, M.SW ) B On A.IDM = B.IDM
                        Join ( Select I.IDM, Group_Concat(Distinct M.SW Order By M.SW Separator ', ') Model
                            From WaxTreeItem I
                                Join Product P On I.Product = P.ID
                                Join Product M On P.Model = M.ID
                            Where I.IDM = $IDSWO->LastID
                            Group By I.IDM ) C On A.IDM = C.IDM ) B
                            Set A.Qty = B.Qty,
                                A.WorkOrder = B.WorkOrder,
                                A.Product = B.Product,
                                A.Model = B.Model
                            Where A.ID = B.IDM And A.ID = $IDSWO->LastID";
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
        // Next Update Waxstoneusage

        $data_return = $this->SetReturn(true, "Save NTHKO Pohonan Sukses", ['ID'=>$IDSWO->LastID], null);
        return response()->json($data_return, 200);
    }

    public function PrintNTHKOInject(Request $request){
        // Get idWaxTree
        $idWaxTree = $request->idWaxTree;
        // Get Header
        $data = FacadesDB::connection('erp')
        ->select("SELECT
                pp.SW SWplate,
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
                wt.Remarks,
                wt.Purpose,
                wt.WorkGroup
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
        if ($data->Purpose != 'I') {
            abort(404);
        }
        // IDWaxInjectOrder
        $idWaxInjectOrder = $data->WaxOrder;
        // Get Items
        $items = FacadesDB::connection('erp')
        ->select("SELECT
            W.ID IDNTHKO,
            O.ID WorkOrder,
            W.SW,
            P.ID Product,
            W.TransDate,
            Cast( R.SW AS CHAR ) RubberPlate,
            Cast( E.SW AS CHAR ) Employee,
            W.Weight,
            W.WeightStone,
            W.WeightWax,
            W.WaxOrder ID,
            W.WorkGroup,
            I.Ordinal,
            Cast( O.SW AS CHAR ) SPKPPIC,
            C.Description Kadar,
        IF
            (
                M.SW IS NULL,
                P.SW,
            Concat( P.SW )) Barang,
            I.Qty,
            Concat( '*', W.ID, '-', I.Ordinal, '*' ) Barcode,
            L.SW Logo,
            I.NoteVariation,
        CASE
            
            WHEN C.Model IS NOT NULL 
            AND W.WeightStone > 0 THEN
                '90mnt' 
                WHEN W.GipsStatus = 2 THEN
            IF
                (
                    C.Carat <= 70,
                    '10mnt',
                IF
                ( C.Carat = 75, 'Kipas 10mnt + 4 Step', 'Kipas 15mnt + Air Panas 5mnt' )) 
                WHEN W.GipsStatus = 1 THEN
            IF
                (
                    C.Carat <= 70,
                    '1jam',
                IF
                ( C.Carat = 75, 'Kipas 20mnt + 4 step', 'Kipas 20mnt - Air Panas 5mnt' )) 
                WHEN W.GipsStatus = 0 THEN
            IF
                (
                C.Carat <= 70,
                '50mnt',
            IF
            ( C.Carat = 75, 'Kipas 10mnt + 4 Step', 'Kipas 15mnt + Air Panas 5mnt' )) 
        END GipsNote 
    FROM
        WaxTree W
        JOIN WaxTreeItem I ON W.ID = I.IDM
        JOIN RubberPlate R ON W.SW = R.ID
        JOIN Employee E ON W.Employee = E.ID
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN ProductCarat C ON O.Carat = C.ID
        JOIN Product P ON I.Product = P.ID
        LEFT JOIN ShortText L ON P.Logo = L.ID
        LEFT JOIN Product M ON I.ProductVariation = M.ID 
    WHERE
        W.ID = '$idWaxTree'
    ORDER BY
        I.Ordinal
        ");
        return view('Produksi.Lilin.NTHKOInjectLilin.cetak2',compact('data','items'));
    }

    public function Search(Request $request){
        $keyword = $request->keyword;
        // Cek waxtree if exists
        $waxTree = FacadesDB::connection('erp')
        ->select("SELECT
                A.ID,
                A.WaxOrder,
                A.Employee,
                A.TransDate,
                A.SW as IdPiring,
                B.SW as namaPohon,
                B.Weight as BeratPiring,
                A.Qty,
                A.Weight,
                A.WeightWax,
                A.WeightStone,
                A.Carat as idKadar,
                C.Description as Kadar,
                A.Purpose,
                CONCAT(T.SW,'-',T.Description) stickpohon,
                CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
            FROM
                waxtree A
                JOIN rubberplate B ON A.SW = B.ID
                JOIN productcarat C ON A.Carat = C.ID
                JOIN treestick T ON T.ID = A.TreeStick
            WHERE
                A.ID = '$keyword'
        ");
        if (count($waxTree) == 0) {
            $data_return = $this->SetReturn(false, "Getting WaxTree Failed. WaxTree not found", null, null);
            return response()->json($data_return, 404);
        }
        $waxTree = $waxTree[0];
        if ($waxTree->Purpose != 'I') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut Bukanlah Inject", null, null);
            return response()->json($data_return, 400);
        }
        // IDWaxInjectOrder
        $idWaxInjectOrder = $waxTree->WaxOrder;
        $databawah = FacadesDB::connection('erp')
        ->select("	SELECT
            WI.IDM,
            B.Ordinal,
            K.ID AS WorkOrder,
            K.SW,
            P.ID AS Product,
            P.SW AS Barang,                
            E.Description AS Kadar,
            WI.Qty,
            P.Description,
            CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
            DI.Qty QtyRph,
            DI.Weight,
            K.TotalWeight,
            CASE
                WHEN E.SW = '6K' THEN
                '#0000FF' 
                WHEN E.SW = '8K' THEN
                '#00FF00' 
                WHEN E.SW = '8K.' THEN
                '#CFB370' 
                WHEN E.SW = '10K' THEN
                '#FFFF00' 
                WHEN E.SW = '16K' THEN
                '#FF0000' 
                WHEN E.SW = '17K' THEN
                '#FF6E01' 
                WHEN E.SW = '17K.' THEN
                '#FF00FF' 
                WHEN E.SW = '19K' THEN
                '#5F2987' 
                WHEN E.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
        FROM
            waxtree T
            JOIN waxinjectorderitem WI ON T.WaxOrder = WI.IDM
            LEFT JOIN workscheduleitem DI ON DI.IDM = WI.WorkScheduleID AND DI.Ordinal = WI.WorkScheduleOrdinal
            JOIN waxorderitem XI ON XI.IDM = WI.WaxOrder AND XI.Ordinal = WI.WaxOrderOrd
            JOIN workorderitem KI ON KI.IDM = XI.WorkOrder AND XI.WorkOrderOrd = KI.Ordinal
            JOIN waxtreeitem B ON T.ID = B.IDM
            JOIN workorder K ON XI.WorkOrder = K.ID
            JOIN product P ON B.Product = P.ID
            JOIN productcarat E ON T.Carat = E.ID
            LEFT JOIN shorttext S ON S.ID = P.ProdGroup
        WHERE
            T.ID = '$keyword'
        GROUP BY 
            B.Ordinal
        ");

        $databawah2 = FacadesDB::connection('erp')
        ->select("	SELECT
            WI.IDM,
            B.Ordinal,
            K.ID AS WorkOrder,
            K.SW,
            P.ID AS Product,
            P.SW AS Barang,                
            E.Description AS Kadar,
            WI.Qty,
            P.Description,
            CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
            DI.Qty QtyRph,
            DI.Weight,
            K.TotalWeight,
            CASE
                WHEN E.SW = '6K' THEN
                '#0000FF' 
                WHEN E.SW = '8K' THEN
                '#00FF00' 
                WHEN E.SW = '8K.' THEN
                '#CFB370' 
                WHEN E.SW = '10K' THEN
                '#FFFF00' 
                WHEN E.SW = '16K' THEN
                '#FF0000' 
                WHEN E.SW = '17K' THEN
                '#FF6E01' 
                WHEN E.SW = '17K.' THEN
                '#FF00FF' 
                WHEN E.SW = '19K' THEN
                '#5F2987' 
                WHEN E.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
        FROM
            waxtree T
            JOIN waxinjectorderitem WI ON T.WaxOrder = WI.IDM
            LEFT JOIN workscheduleitem DI ON DI.IDM = WI.WorkScheduleID AND DI.Ordinal = WI.WorkScheduleOrdinal
            JOIN waxorderitem XI ON XI.IDM = WI.WaxOrder AND XI.Ordinal = WI.WaxOrderOrd
            JOIN workorderitem KI ON KI.IDM = XI.WorkOrder AND XI.WorkOrderOrd = KI.Ordinal
            JOIN waxtreeitem B ON T.ID = B.IDM
            JOIN workorder K ON XI.WorkOrder = K.ID
            JOIN product P ON B.Product = P.ID
            JOIN productcarat E ON T.Carat = E.ID
            LEFT JOIN shorttext S ON S.ID = P.ProdGroup
        WHERE
            T.ID = '$keyword'
        GROUP BY 
            B.Ordinal
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
        if ($waxTree->Purpose != 'I') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut Bukan Inject. Tidak dapat diubah", null, null);
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
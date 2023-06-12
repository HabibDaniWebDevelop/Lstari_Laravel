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

class NTHKOInjectLilinController extends Controller{
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
                Department IN (19,56)  AND Active = 'Y'
        ");
        $datenow = date('Y-m-d');
        // history waxtree
        $historyWaxTree = waxtree::where('Purpose','I')->orderBy('EntryDate','DESC')->limit(10)->get();
        return view('Produksi.Lilin.NTHKOInjectLilin.index', compact('employee', 'datenow', 'historyWaxTree'));
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
                D.ID as IDpenggunaanbatu,
                CONCAT(T.SW,'-',T.Description) stickpohon
               
            FROM
                waxinjectorder W
                JOIN productcarat B ON W.Carat = B.ID
                JOIN rubberplate C ON W.RubberPlate = C.ID
                JOIN treestick T ON T.ID = W.TreeStick
                LEFT JOIN waxstoneusage D ON D.WaxOrder = W.ID
            WHERE
                W.ID = '$IDWaxInjectOrder'
        ");
        
        if (count($WaxInjectOrder) == 0) {
            $data_return = $this->SetReturn(false, "ID SPK Inject Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $WaxInjectOrder = $WaxInjectOrder[0];
        if ($WaxInjectOrder->Purpose == 'D') {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut adalah SPKO DC", null, null);
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
        ST.Weight beratperbatu,
		ST.Qty jumlahperbatu,
        XI.StoneNote NoteVariation,
		PJ.SW as produkjadiaa
    FROM
        waxinjectorder W
        JOIN waxinjectorderitem WI ON W.ID = WI.IDM
		JOIN workscheduleitem DI ON DI.IDM = WI.WorkScheduleID AND DI.Ordinal = WI.WorkScheduleOrdinal AND DI.Level2 = WI.WaxOrder AND DI.Level3 = WI.WaxOrderOrd -- chek rph
        JOIN waxorderitem XI ON XI.IDM = WI.WaxOrder AND XI.Ordinal = WI.WaxOrderOrd AND XI.WorkOrder = DI.LinkID AND XI.WorkOrderOrd = DI.LinkOrd -- chek spk lilin
        JOIN workorderitem KI ON KI.IDM = XI.WorkOrder AND XI.WorkOrderOrd = KI.Ordinal
        JOIN workorder K ON XI.WorkOrder = K.ID
        JOIN product P ON XI.Product = P.ID
        JOIN productcarat PC ON K.Carat = PC.ID
		JOIN product PJ ON PJ.ID = KI.Product
        LEFT JOIN shorttext S ON S.ID = P.ProdGroup
        LEFT JOIN Waxstoneusageitem ST ON ST.WorkOrder = KI.IDM
    WHERE
        W.ID = '$IDWaxInjectOrder'
        GROUP BY WI.Ordinal
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
        IFNULL(A.berattotal, 0) berattotal,
        TI.Qty,
        IFNULL(A.jumlahtotal, 0) jumlahtotal,
        P.Size,
				CASE WHEN TI.Qty = 0 THEN 0
				ELSE
        (TI.Weight / TI.Qty) END Avg
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
                AND T.Location = 51
            ) A ON A.ID = T.ID
        WHERE
            W.ID = '$IDWaxInjectOrder'
            AND T.Location = 51
            GROUP BY 
            P.ID,
            W.ID,
            T.Purpose
        ");
      
            // Get  kebutuhan batu
            $KebutuhanBatu = FacadesDB::connection('erp')
            ->select("SELECT
            CASE WHEN SUM( Z.totalBeratBatu ) IS NULL THEN 0 ELSE SUM( Z.totalBeratBatu ) END totalBeratBatu,
            CASE WHEN SUM( Z.totalBatu ) IS NULL THEN 0 ELSE SUM( Z.totalBatu ) END totalBatu 
            FROM(
                SELECT
                   SUM(A.Weight) as totalBeratBatu,
                   SUM(A.Qty) as totalBatu
                FROM
                   waxstoneusageitem A
                     JOIN Waxstoneusage B ON B.ID = A.IDM
                WHERE 
                    B.WaxOrder = '$IDWaxInjectOrder'
                        AND B.Purpose = 'Tambah'
                        AND B.Location = 51
                UNION
                SELECT
                   CONCAT('-',SUM(A.Weight)) as totalBeratBatu,
                   CONCAT('-',SUM(A.Qty)) as totalBatu
                FROM
                   waxstoneusageitem A
                     JOIN Waxstoneusage B ON B.ID = A.IDM
                WHERE 
                    B.WaxOrder = '$IDWaxInjectOrder'
                        AND B.Purpose = 'Kurang'
                        AND B.Location = 51) Z
            ");
        
        $KebutuhanBatu1 = $KebutuhanBatu[0];
       
        $WaxInjectOrder->items = $items;
        $WaxInjectOrder->batu = $getinformasibatu;
        // dd($beratBatuTotal);
        $WaxInjectOrder->tbb = $KebutuhanBatu[0]->totalBeratBatu;
        // dd($totalBatu);
        $WaxInjectOrder->Qtyb = $KebutuhanBatu[0]->totalBatu;

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
            ST.Weight beratperbatu,
		ST.Qty jumlahperbatu,
        XI.StoneNote NoteVariation.
        PJ.SW as produkjadiaa
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
            JOIN product PJ ON PJ.ID = KI.Product
            JOIN productcarat PC ON K.Carat = PC.ID
            LEFT JOIN shorttext S ON S.ID = P.ProdGroup
            LEFT JOIN Waxstoneusageitem ST ON ST.WorkOrder = KI.IDM
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
            $BeratPerBatu = $datas->beratperbatu;
            $JumlahPerBatu = $datas->jumlahperbatu;
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
                'BeratPerBatu' => $BeratPerBatu,
                'JumlahPerBatu' => $JumlahPerBatu,
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

        // // Check if beratBatu null or blank
        // if (is_null($beratBatu) or $beratBatu == "") {
        //     $data_return = [
        //         "success"=>false,
        //         "message"=>"Save NTHKO Inject Failed",
        //         "data"=>null,
        //         "error"=>[
        //             "beratBatu"=>"beratBatu Parameters can't be null or blank"
        //         ]
        //     ];
        //     return response()->json($data_return,400);
        // }
        
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

        if ($purpose == 'D') {
            $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Adalah DC", null, null);
            return response()->json($data_return, 400);
        }
    
        // Get total Berat Batu & totalBatu
        // Get WorkOrder for kebutuhan batu
        $GetWorkOrder = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
            W.ID,
            K.ID AS idSPK,
            K.SW AS noSPK,
            LEFT(K.SW,1) AS SWO,
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
        
        $idworkOrder = $workOrder->idSPK;
        $SWO = $workOrder->SWO;
        // dd($SWO);
         
        // REVISI BERAT BATU TOTAL JADI INPUTAN 2023-01-23;
        // $beratBatuTotal = is_null($KebutuhanBatu->totalBeratBatu) ? 0 : $KebutuhanBatu->totalBeratBatu; //Ini ngambil dari DB
        
        // Kalkulasi using concat
        $Product = $workOrder->SW;
        $workOrder = ''.$workOrder->noSPK.'['.$totalQty.']';
        
        // Get Year
        if($SWO == 'O'){
            $year = date('y');
        }else{
            $year = date('y') + 50;
        }
        // dd($year);
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
        // dd($IDSWO);
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
                "Note"=>NULL,
                "WeightStone"=>NULL,
                "ProductVariation"=>$item['ProductVar'], //product jadi
                "NoteVariation"=>$item['variation1'],
                "Component"=>null,
                "LinkID"=>null,
                "LinkOrd"=>null,
                "LinkSubOrd"=>null,
                "WorkOrderOrd"=>$item['WorkOrderOrd']
            ]);
        }
        
        // dd($insertWaxtreeitem);
        // Update Waxtree Kolom [Qty, Product, WorkORder, Model]
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
        if ($data->Purpose == 'D') {
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
        A.ID Product,
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
            A.SW,
        Concat( A.SW )) Barang,
        I.Qty,
        Concat( '*', W.ID, '-', I.Ordinal, '*' ) Barcode,
        L.SW AS Logo,
        I.NoteVariation,
            LEFT(MS.Size, 1) Size,
            C.SW,
            CASE 
            WHEN C.SW IN ('6K','8K','10K','16K','19K','20K','8K.','17K.','17K') AND W.WeightStone = 0 THEN '10mnt'
            WHEN C.SW IN ('6K','8K','10K','16K','19K','20K') AND LEFT(MS.Size, 1)< 4 AND W.WeightStone > 0 THEN '30mnt'
            WHEN C.SW IN ('6K','8K','10K','16K','19K','20K') AND LEFT(MS.Size, 1)>= 4 AND W.WeightStone > 0 THEN '1jam'
            WHEN C.SW IN ('8K.','17K.') AND LEFT(MS.Size, 1) < 4 AND W.WeightStone > 0 THEN '1jam'
            WHEN C.SW IN ('8K.','17K.') AND LEFT(MS.Size, 1) >= 4 AND W.WeightStone > 0 THEN '90mnt'
            WHEN C.SW IN ('17K') AND LEFT(MS.Size, 1) < 4 AND W.WeightStone > 0 THEN '30mnt'
        END GipsNote
FROM
    WaxTree W
    JOIN WaxTreeItem I ON W.ID = I.IDM
    JOIN Product A ON A.ID = I.product
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN Employee E ON W.Employee = E.ID
    JOIN WorkOrder O ON I.WorkOrder = O.ID
            LEFT JOIN Workorderitem OI ON OI.IDM = I.WorkOrder AND OI.Ordinal = I.WorkOrderOrd
            LEFT JOIN productaccessories PA ON PA.IDM = OI.product
            LEFT JOIN Product P ON P.ID = PA.Product AND P.ProdGroup = 126
            LEFT JOIN rndnew.masterstone MS ON MS.LinkProduct = P.ID 
            JOIN ProductCarat C ON O.Carat = C.ID
            LEFT JOIN ShortText L ON P.Logo = L.ID
    LEFT JOIN Product M ON I.ProductVariation = M.ID
    WHERE
        W.ID = '$idWaxTree'
        GROUP BY
				I.Ordinal
    ORDER BY
    A.SW ASC
        
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

        return view('Produksi.Lilin.NTHKOInjectLilin.cetak2',compact('data','items','pendinginan'));
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
                A.UserName,
                A.EntryDate,
                A.Qty,
                A.Weight,
                A.WeightWax,
                A.WeightStone,
                A.Carat as idKadar,
                C.Description as Kadar,
                A.Purpose,
                T.ID idstick,
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
            END HexColor,
            CASE WHEN A.Active = 'G' THEN 'GIPS' ELSE '' END GipsS, 
            D.ID as idbatu,
            A.Remarks
            FROM
                waxtree A
                JOIN rubberplate B ON A.SW = B.ID
                JOIN productcarat C ON A.Carat = C.ID
                LEFT JOIN treestick T ON T.ID = A.TreeStick
				LEFT JOIN waxstoneusage D ON D.WaxOrder = A.WaxOrder
            WHERE
                A.ID = '$keyword'
        ");
        if (count($waxTree) == 0) {
            $data_return = $this->SetReturn(false, "Getting WaxTree Failed. WaxTree not found", null, null);
            return response()->json($data_return, 404);
        }
        $waxTree = $waxTree[0];
        // dd($waxTree);
        if ($waxTree->Purpose == 'D') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut adalah spko DC", null, null);
            return response()->json($data_return, 400);
        }
        // IDWaxInjectOrder
        
        $databawah = FacadesDB::connection('erp')
        ->select("	SELECT
                 WI.IDM,
            TI.Ordinal,
            K.ID AS WorkOrder,
            K.SW,
            P.ID AS Product,
            E.ID AS IDKadar,
            P.SW AS Barang,                
            E.Description AS Kadar,
            TI.Qty,
            P.Description,
            CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
            -- DI.Qty QtyRph,
            -- DI.Weight,
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
            END HexColor,
            TI.NoteVariation,
						TI.ProductVariation,
						TI.WorkOrderOrd,
                        PJ.SW as produkjadiaa
                        
        FROM
            waxtree T
            JOIn Waxtreeitem TI ON T.ID = TI.IDM
            JOIN waxinjectorderitem WI ON T.WaxOrder = WI.IDM
            -- LEFT JOIN workscheduleitem DI ON DI.IDM = WI.WorkScheduleID AND DI.Ordinal = WI.WorkScheduleOrdinal
            JOIN workorder K ON WI.tatakan = K.ID
            JOIN Workorderitem KI ON K.ID = KI.IDM
            JOIN Product PJ ON PJ.ID = KI.Product
            JOIN product P ON TI.Product = P.ID
            JOIN productcarat E ON T.Carat = E.ID
            LEFT JOIN shorttext S ON S.ID = P.ProdGroup
        WHERE
            T.ID = '$keyword'
        GROUP BY 
            TI.Ordinal
            ORDER BY
        P.SW ASC
        ");
        $idWaxInjectOrder = $waxTree->WaxOrder;
            $getinformasibatu = FacadesDB::connection('erp')
            ->select("SELECT
            T.ID as idbatu,
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
            IF(TI.Qty = 0, 0, SUM( TI.Weight / TI.Qty )) as Avg 
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
                    LEFT JOIN waxinjectorder W ON W.ID = T.WaxOrder 
                WHERE
                    W.ID = '$idWaxInjectOrder' 
                ) A ON A.ID = T.ID
            WHERE
                W.ID = '$idWaxInjectOrder'
                GROUP BY 
                P.ID,
                W.ID,
                T.Purpose
            ");

        $waxTree->itemsl = $databawah;
        $waxTree->batusl = $getinformasibatu;
        $data_return = $this->SetReturn(true, "Getting WaxTree Success. WaxTree found", $waxTree, null);
        return response()->json($data_return, 200);
    }

    public function UpdateNTHKO(Request $request){
        // dd($request);
        $idWaxTree = $request->idWaxTree;
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
        $checkitems = $request->items;
        // dd($piring);
        // dd($checkitems);
        // dd($idWaxTree);

        // Check if Waxtree Found
        $waxTree = waxtree::where('ID',$idWaxTree)->first();
        // dd($waxTree);
        if (is_null($waxTree)) {
            $data_return = $this->SetReturn(false, "Getting WaxTree Failed. WaxTree not found", null, null);
            return response()->json($data_return, 404);
        }
        if ($waxTree->Purpose == 'D') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut adalah direcasting. Tidak dapat diubah", null, null);
            return response()->json($data_return, 400);
        }
        if ($waxTree->GipsStatus == 1) {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut Sudah Di GIPS. Tidak dapat diubah", null, null);
            return response()->json($data_return, 400);
        }
        $berat = $beratPohonTotal - $beratpiring - $beratBatu;
        // update waxtree
        $updateWaxTree = waxtree::where('ID',$idWaxTree)->update([
            "Employee"=>$idEmployee,
            "Remarks"=>$Catatan,
            "SW"=>$piring, 
            "TreeSize"=>"180", 
            "Weight"=>$berat, 
            "WeightPlate"=>$beratpiring, 
            "WeightStone"=>$beratBatu, 
            "WeightWax"=>$beratPohonTotal, 
            "Priority"=>"N", 
            "Carat"=>$idkadar, 
            "WeightStoneCalc"=>$beratBatu, 
            "TreeStick"=>$idstickpohon, 
            "Active"=>"A", 
            "Qty"=>$totalQty, 
            "WaxStoneCalc"=>$banyakBatu,
        ]);
       

        $deleteWaxtreeitem = waxtreeitem::where('IDM',$idWaxTree)->delete();

       // Insert To Waxtreeitem
       foreach ($request->items as $key => $item) {
        $insertWaxtreeitem = waxtreeitem::create([
            "IDM"=>$idWaxTree, 
            "Ordinal"=>$key+1, 
            "WorkOrder"=>$item['workOrder'], 
            "Product"=>$item['idProduct'], 
            "Qty"=>$item['itemQty'], //item product
            "OverTime"=>"N",
            "Size"=>NULL,
            "Note"=>NULL,
            "WeightStone"=>NULL,
            "ProductVariation"=>$item['ProductVar'], //product jadi
            "NoteVariation"=>$item['variation1'],
            "Component"=>null,
            "LinkID"=>null,
            "LinkOrd"=>null,
            "LinkSubOrd"=>null,
            "WorkOrderOrd"=>$item['WorkOrderOrd']
        ]);
    }

         // Update Waxtree Kolom [Qty, Product, WorkORder, Model]
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
                    JOIN ( SELECT WorkOrder, Sum( Qty ) QtyTree FROM WaxTreeItem WHERE IDM = $idWaxTree GROUP BY WorkOrder ) T ON O.ID = T.WorkOrder 
                WHERE
                    I.IDM = $idWaxTree 
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
                    I.IDM = $idWaxTree 
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
                    I.IDM = $idWaxTree 
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
            AND A.ID = $idWaxTree";
      $UpdateWaxtreeASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeA);

      //Update WaxtreeItem Kolom [Componen]
      $UpdateWaxTreeItemA = "UPDATE WaxTreeItem I, Product P Set P.Component = I.Component 
                          Where P.ID = I.Product And I.Component Is Not Null And I.IDM = $idWaxTree";
      $UpdateWaxTreeItemASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeItemA);
      
      //Update Waxtree kolom [WaxInjectCalc]
      $UpdateWaxTreeB = "UPDATE WaxTree W, (Select A.IDM, Sum(A.Total) TotalInject
      From ( Select S.IDM, S.Ordinal, P.SW Product, Round(S.Qty / If(P.ProdGroup In (6, 10) Or Model In (47739), R.Pcs / 2, R.Pcs)) Total
          From WaxTreeItem S
              Join ProductPart A On S.Product = A.IDM
              Join Rubber R On A.Product = R.Product And R.UnusedDate Is Null And R.Pcs Is Not Null And R.TransDate > '2018-01-01'
              Join Product P On A.Product = P.ID
          Where S.IDM = $idWaxTree
          Group By S.IDM, S.Ordinal, P.SW ) A Group By A.IDM ) R
      Set W.WaxInjectCalc = R.TotalInject
      Where W.ID = $idWaxTree And W.ID = R.IDM";
      $UpdateWaxTreeBSucces = FacadesDB::connection('erp')->update($UpdateWaxTreeB);

      //Update Waxtree Kolom [WaxStoneCalc]
      $UpdateWaxTreeC = "UPDATE WaxTree W,( Select A.IDM, Sum(A.Total) TotalStone
      From ( Select S.IDM, S.Ordinal, Sum(S.Qty * A.Qty) Total
                From WaxTreeItem S
                  Join ProductAccessories A On S.Product = A.IDM
                  Join Product Q On A.Product = Q.ID And Q.ProdGroup = 126
                Where S.IDM = $idWaxTree
                Group By S.IDM, S.Ordinal ) A Group By A.IDM ) R
      Set W.WaxStoneCalc = R.TotalStone
      Where W.ID = $idWaxTree And W.ID = R.IDM";
      $UpdateWaxTreeCSucces = FacadesDB::connection('erp')->update($UpdateWaxTreeC);

      //Update Waxtree Kolom [TotalPoles, TotalPatri, TotalPUK]
      $UpdateWaxTreeD = "UPDATE WaxTree A, (Select I.IDM, M.BrtKunci * I.Qty Poles, M.BrtKompA * I.Qty Patri, M.BrtKompB * I.Qty PUK
      From WaxTreeItem I
          Join Product P On I.ProductVariation = P.ID
          Join Product M On IfNull(P.Marketing, P.Model) = M.ID
      Where I.IDM = $idWaxTree ) B
      Set A.TotalPoles = B.Poles,
          A.TotalPatri = B.Patri,
          A.TotalPUK = B.PUK
      Where A.ID = B.IDM And A.ID = $idWaxTree";
      $UpdateWaxTreeDSucces = FacadesDB::connection('erp')->update($UpdateWaxTreeD);

      //Update Workorder Kolom [WaxTree]
      $UpdateWorkOrder = "UPDATE WorkOrder O, 
      (Select Distinct I.WorkOrder, T.TransDate
          From WaxTree T 
              Join WaxTreeItem I On T.ID = I.IDM
          Where T.ID = $idWaxTree ) W
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
                      Where T.WaxOrder Is Not Null And T.ID = $idWaxTree ) W
                  Join WorkOrderItem O On O.IDM = W.WorkOrder
                  Join WorkSuggestionItem S On O.WorkSuggestion = S.IDM And O.WorkSuggestionOrd = S.Ordinal
              Group By W.ID ) Z
          Set T.WeightFG = Z.Weight
          Where T.ID = $idWaxTree";
      $UpdateWaxTreeESucces = FacadesDB::connection('erp')->update($UpdateWaxTreeE);
        
        $data_return = $this->SetReturn(true, "Update Waxtree Success", null, null);
        return response()->json($data_return, 200);

    }
}
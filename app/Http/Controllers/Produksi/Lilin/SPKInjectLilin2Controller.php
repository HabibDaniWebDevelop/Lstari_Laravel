<?php

namespace App\Http\Controllers\Produksi\Lilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

// live tabel
// use App\Models\erp\waxinjectorder;
// use App\Models\erp\waxinjectorderitem;
// use App\Models\erp\waxinjectorderrubber;
// use App\Models\rndnew\worklist3dpproduction;
// use App\Models\rndnew\worklist3dpproductionitem;
// use App\Models\erp\workscheduleitem;
// use App\Models\rndnew\worklistwax3dp;
// use App\Models\rndnew\WorkListwax3dpItem;

// lokal tabel
use App\Models\tes_laravel\waxinjectorder;
use App\Models\tes_laravel\waxinjectorderitem;
use App\Models\tes_laravel\waxinjectorderrubber;
use App\Models\tes_laravel\worklist3dpproduction;
use App\Models\tes_laravel\worklist3dpproductionitem;
use App\Models\tes_laravel\workscheduleitem;
use App\Models\tes_laravel\worklistwax3dp;
use App\Models\tes_laravel\WorkListwax3dpItem;

use \DateTime;
use \DateTimeZone;

use Barryvdh\DomPDF\Facade\Pdf;

class SPKInjectLilinController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $IDWaxInject = FacadesDB::connection("erp")
        ->select("SELECT ID FROM waxinjectorder WHERE Active ='A' ORDER BY ID DESC");

        $ProdSW = FacadesDB::connection("erp")
        ->select("SELECT P.ID, P.SW FROM Waxorderitem X JOIN Product P ON P.ID = X.Product where IDM = 999999");


        return view('Produksi.Lilin.SPKInjectLilin.index', compact('IDWaxInject', 'ProdSW'));
    }

    public function jumlahinject(Request $request)
    {
        
        $tambahdatakaret = FacadesDB::connection("erp")
        ->select("SELECT * FROM Waxorderitem X JOIN Product P On X.Product
        ");

        $rowcount = count($SWData);
        if($rowcount > 0 ){
            foreach ($SWData as $datas){}
            $WorkOrder = $datas->WorkOrder;

            $data_return = array(
                'rowcount' => $rowcount,
                'WorkOrder' => $WorkOrder,
            );
            // dd($WorkOrder);

        }else{
            $data_return = array ('rowcount' => $rowcount);
        }
        return response()->json($data_return, 200);
    }

    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    public function form(){
        $karyawan = FacadesDB::connection("erp")
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");

        $kadar = FacadesDB::connection("erp")
        ->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $piring = FacadesDB::connection("erp")
        ->select("SELECT * FROM rubberplate WHERE Active = 'Y' ORDER BY ID DESC LIMIT 20");

        $rphlilin = FacadesDB::connection("erp")
        ->select("SELECT ID FROM workschedule WHERE UserName='Sandi' AND Location = 51 AND DATE(EntryDate) < NOW() ORDER BY ID DESC LIMIT 15 ");

        $stickpohon = FacadesDB::connection("erp")
        ->select("SELECT ID, SW, Description, CONCAT(SW,'-', Description) stickpohon FROM treestick");

        return view('Produksi.Lilin.SPKInjectLilin.form',compact('karyawan', 'kadar', 'piring', 'rphlilin', 'stickpohon'));
    }

    public function inputoperator($IdOperator){
        
        $Operator = FacadesDB::connection('erp')
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND ID='$IdOperator' AND `Rank`='Operator'");
        
        
        if ($Operator){
            return response()->json(
                [
                    'namaop' => $Operator[0]->Description,
                ],
                201,
            );
        } else{
            return response()->json(
                [
                    'namaop' => 'Nama Tidak Ditemukan',
                ],
                201,
            );
        } 
    }

    public function inputpiring($LabelPiring){

        $Piring = FacadesDB::connection('erp')
        ->select("SELECT * FROM rubberplate WHERE Active = 'Y' AND SW = '$LabelPiring'");
        
        if ($Piring){
            return response()->json(
                [
                    'IdPir' => $Piring[0]->ID,
                ],
                201,
            );
        } else{
            return response()->json(
                [
                    'IdPir' => NULL,
                ],
                201,
            );
        }
    }

    public function product($kdr,$rph){
// RND New 12
        $tabeltes = FacadesDB::connection("erp")
        ->select("SELECT
        WS.IDM,
        WS.Ordinal,
        K.SW WorkOrder,
        K.ID IDWorkOrder,
        WS.Level2,
        WS.Level3,
        WS.Level4,
        K.SWUsed,
        P.SW Product,
        P.Description,
        P.Photo,--         XI.Inject - IfNull( B.Ordered, 0 ) Inject,
        XI.IDM waxoerder,
        XI.Ordinal waxorderord,
    IF
        (
            P.ProdGroup IN ( 6, 10 ),
            IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        S.Description ProdGroup,
        KI.Qty,
        XI.Inject,
        I.TQty,
        I.TInject 
    FROM
        workscheduleitem WS
        JOIN WaxOrder X ON WS.Level2 = X.ID
        JOIN WaxOrderItem XI ON XI.IDM = WS.Level2 
        AND XI.Ordinal = WS.Level3 AND WS.Operation != 201 
        JOIN WorkOrder K ON WS.LinkID = K.ID 
        -- AND K.Carat = $kdr
        JOIN WorkOrderItem KI ON WS.LinkID = KI.IDM 
        AND WS.LinkOrd = KI.Ordinal
        JOIN ProductCarat PC ON PC.ID = K.Carat
        JOIN Product P ON P.ID = KI.Product
        LEFT JOIN waxinjectorderitem CI ON CI.WorkScheduleID = WS.IDM 
        AND CI.WorkScheduleOrdinal = WS.Ordinal
        JOIN shorttext S ON P.ProdGroup = S.ID
    
        JOIN (
            SELECT
            WSI.LinkID,
            Sum( WI.Inject ) TInject,
			Sum(WK.Qty) TQty
        FROM
            waxorderitem WI
            JOIN workscheduleitem WSI ON WSI.Level2 = WI.IDM 
            AND WSI.Level3 = WI.Ordinal
			JOIN workorderitem WK ON WK.IDM = WI.WorkOrder AND WK.Ordinal = WI.WorkOrderOrd
        WHERE
            WSI.IDM = $rph 
            AND WSI.Carat = $kdr 
        GROUP BY
            WSI.LinkID 
        ) I ON WS.LinkID = I.LinkID --
        
    WHERE
        WS.IDM = $rph
        AND WS.Carat = $kdr
    GROUP BY
        K.ID 
        ORDER BY
        K.SWUsed ASC");

        return view('Produksi.Lilin.SPKInjectLilin.ProdukList',compact('tabeltes'));
    }

    public function tambahdata($items,$kdr,$rph){
        
        // dd($items);
    $tambahdataitem = FacadesDB::connection("erp")->
    select("SELECT
    WS.IDM Rph,
    WS.Ordinal Ordinal,
    K.SW WorkOrder,
    K.ID IDWorkOrder,-- PP.RubberCarat,
    K.SWUsed,
    P.SW Product,
    P.Description,
    P.Photo,
    CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
    XI.IDM waxorder,
    XI.Ordinal waxorderord,
    K.Carat,
IF
    (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
    IFNULL( P.RubberQty, 1 )) RubberQty,
    P.ID IDprod,
    S.Description ProdGroup,
    XI.Inject,
    KI.Qty 
FROM
    workscheduleitem WS
    JOIN WaxOrder X ON WS.Level2 = X.ID
    JOIN waxorderitem XI ON XI.IDM = WS.Level2 
    AND XI.Ordinal = WS.Level3
    JOIN workorder K ON WS.LinkID = K.ID 
    AND K.Carat = $kdr
    JOIN WorkOrderItem KI ON WS.LinkID = KI.IDM 
    AND WS.LinkOrd = KI.Ordinal
    JOIN ProductCarat PC ON PC.ID = K.Carat
    JOIN Product P ON P.ID = XI.Product
    LEFT JOIN shorttext S ON P.ProdGroup = S.ID 
WHERE
    WS.IDM = $rph
    AND K.ID IN ($items) 
ORDER BY
    WS.ORdinal 
    ");

    $tambahdatakaret = FacadesDB::connection("erp")
    ->select("SELECT
        R.ID,
        P.SW Product,
        CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
		END  Kadar,
        CASE
		
		WHEN PC.SW = '6K' THEN
		'#090cd9' 
		WHEN PC.SW = '8K' THEN
		'#02ba1e' 
		WHEN PC.SW = '16K' THEN
		'#ff1a1a' 
		WHEN PC.SW = '17K' THEN
		'#e65507' 
		WHEN PC.SW = '17K.' THEN
		'#d909cb' 
		WHEN PC.SW = '20K' THEN
		'#ffcba4' 
		WHEN PC.SW = '10K' THEN
		'#f5fc0f' 
		WHEN PC.SW = '8K.' THEN
		'#ebb52d'
		WHEN PC.SW = '19K' THEN
		'#4908a3'
        WHEN PC.SW IS NULL THEN
        '#bcbcbc'
	    END HexColor,
        R.Pcs,
        R.WaxUsage,
        R.WaxCompletion,
        R.WaxScrap,
        R.TransDate,
        R.STATUS,
        R.Size,
        R.StoneCast,
        CONCAT( L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW ) lokasi,
        L.Active,
        L.WaxInjectOrder,
        O.SW 
    FROM
        waxorderitem J 
    JOIN Rubber R ON J.Product = R.Product 
        AND R.Active IN ( 'P', 'K', 'O' ) 
        AND R.TransDate > '2018-01-01'
        AND R.UnUsedDate IS NULL
         JOIN Product P ON J.Product = P.ID
         LEFT JOIN productcarat PC ON PC.ID = R.Carat
        LEFT JOIN RubberLocation L ON R.ID = L.RubberID
        LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
        LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
        LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
        LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
        JOIN workorder O ON J.WorkOrder = O.ID 
    WHERE
        O.ID in ($items)
    GROUP BY
        R.ID,
        P.SW 
    ORDER BY
        P.SW,
        R.Size,
        R.StoneCast,
        R.ID DESC
        ");
// RND New 12
        // dd($tambahdataitem);
        return view('Produksi\Lilin\SPKInjectLilin\TambahItem',compact('tambahdataitem','tambahdatakaret'));
        // Y:\lestari_laravel\resources\views\Produksi\Lilin\SPKInjectLilin\TambahItem.blade.php
    }


    public function tambahkomponendirect($items,$kdr,$rph){ //===========Query item Direcast====================================================
       
        $tambahkomponendirect = FacadesDB::connection("erp")
        ->select("SELECT
        WSI.IDM Rph,
        WO.SWUsed SPK,
        WSI.Ordinal Ordinal,
        WO.SW WorkOrder,
        WO.ID IDWorkOrder,--         PP.RubberCarat,
        P.SW Product,
        P.Description,
        DD.ID ID3d,
        WOI.IDM,
        WOI.Ordinal OrdinalWOI,
        X.IDM waxorder,
        X.Ordinal waxorderord,
        WO.Carat,
        P.TypeProcess,
        Q.TQty,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        WOI.Qty,
    NULL QtySatuPohon
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM 
        AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID AND P.TypeProcess IS NULL
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.ID AND P.TypeProcess
        IS NULL LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        LEFT JOIN (
        SELECT
        WSI.LinkID,
        SUM( KI.Qty ) TQty 
        FROM
        WorkOrderItem KI
        JOIN workscheduleitem WSI ON WSI.LinkID = KI.IDM 
        AND WSI.LinkOrd = KI.Ordinal 
        WHERE
        WSI.IDM = $rph 
        AND WSI.Carat = $kdr
        GROUP BY
        WSI.LinkID 
        ) Q ON WSI.LinkID = Q.LinkID 
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        WO.ID UNION
        SELECT
        WSI.IDM Rph,
        WO.SWUsed SPK,
        WSI.Ordinal Ordinal,
        WO.SW WorkOrder,
        WO.ID IDWorkOrder,--         PP.RubberCarat,
        P.SW Product,
        P.Description,
        DD.ID ID3d,
        WOI.IDM,
        WOI.Ordinal OrdinalWOI,
        X.IDM waxorder,
        X.Ordinal waxorderord,
        WO.Carat,
        P.TypeProcess,
        Q.TQty,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        WOI.Qty,
PP.MinOrder QtySatuPohon 
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID
JOIN rndnew.mastercomponent PP ON PP.ID = P.LinkID AND P.TypeProcess = 25
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 25
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        LEFT JOIN (
        SELECT
        WSI.LinkID,
        SUM( KI.Qty ) TQty 
        FROM
        WorkOrderItem KI
        JOIN workscheduleitem WSI ON WSI.LinkID = KI.IDM 
        AND WSI.LinkOrd = KI.Ordinal 
        WHERE
        WSI.IDM = $rph 
        AND WSI.Carat = $kdr
        GROUP BY
        WSI.LinkID 
        ) Q ON WSI.LinkID = Q.LinkID 
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        WO.ID UNION
        SELECT
        WSI.IDM Rph,
        WO.SWUsed SPK,
        WSI.Ordinal Ordinal,
        WO.SW WorkOrder,
        WO.ID IDWorkOrder,-- PP.RubberCarat,
        P.SW Product,
        P.Description,
        DD.ID ID3d,
        WOI.IDM,
        WOI.Ordinal OrdinalWOI,
        X.IDM waxorder,
        X.Ordinal waxorderord,
        WO.Carat,
        P.TypeProcess,
        Q.TQty,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        WOI.Qty,
PP.MinOrder QtySatuPohon 
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID
JOIN rndnew.mastermainan PP ON PP.ID = P.LinkID AND P.TypeProcess = 26
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 26
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        LEFT JOIN (
        SELECT
        WSI.LinkID,
        SUM( KI.Qty ) TQty 
        FROM
        WorkOrderItem KI
        JOIN workscheduleitem WSI ON WSI.LinkID = KI.IDM 
        AND WSI.LinkOrd = KI.Ordinal 
        WHERE
        WSI.IDM = $rph 
        AND WSI.Carat = $kdr
        GROUP BY
        WSI.LinkID 
        ) Q ON WSI.LinkID = Q.LinkID 
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        WO.ID UNION
        SELECT
        WSI.IDM Rph,
        WO.SWUsed SPK,
        WSI.Ordinal Ordinal,
        WO.SW WorkOrder,
        WO.ID IDWorkOrder,--         PP.RubberCarat,
        P.SW Product,
        P.Description,
        DD.ID ID3d,
        WOI.IDM,
        WOI.Ordinal OrdinalWOI,
        X.IDM waxorder,
        X.Ordinal waxorderord,
        WO.Carat,
        P.TypeProcess,
        Q.TQty,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        WOI.Qty,
PP.MinOrder QtySatuPohon
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID
JOIN rndnew.masterkepala PP ON PP.ID = P.LinkID AND P.TypeProcess = 27
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 27
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        LEFT JOIN (
        SELECT
        WSI.LinkID,
        SUM( KI.Qty ) TQty 
        FROM
        WorkOrderItem KI
        JOIN workscheduleitem WSI ON WSI.LinkID = KI.IDM 
        AND WSI.LinkOrd = KI.Ordinal 
        WHERE
        WSI.IDM = $rph 
        AND WSI.Carat = $kdr
        GROUP BY
        WSI.LinkID 
        ) Q ON WSI.LinkID = Q.LinkID 
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- ND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        WO.ID 
        ORDER BY
        Ordinal
        ");

    return view('Produksi.Lilin.SPKInjectLilin.TambahKomponenDirect',compact('tambahkomponendirect'));
    }

    public function cariSWItemProduct(Request $request){
    
        $SWWorkOrder = $request->work;
        $SWproduct = $request->Product;
        // dd($SWproduct);

        // $chekWorkOrderdospkinject = FacadesDB::connection('erp')
        // ->select("SELECT 
        //         WI.Tatakan,
        //         WI.Tok
        //     FROM
        //         workorder W
        //         JOIN waxinjectorderitem WI ON W.ID = WI.tatakan
        //     WHERE
        //         W.SWUsed = $SWWorkOrder
        // ");
        //     $chektatakan = count($chekWorkOrderdospkinject);
        //     if (is_null($chektatakan) or $chektatakan == 0) {
        //         $data_return = $this->SetReturn(false, "SPK PPIC yang anda masukkan belum pernah di inject, Harap menghubungi mas Habib", null, null);
        //         return response()->json($data_return, 404);
        //     }

            // dd($chekWorkOrderdospkinject);

        $chekSPKLilin = FacadesDB::connection('erp')
            ->select("SELECT 
                    IDM,
                    Ordinal
                FROM
                workorder W
                JOIN waxorderitem X ON X.WorkOrder = W.ID
                JOIN Product P ON P.ID = X.Product
                WHERE
                    W.SWUsed = $SWWorkOrder
                    AND P.SW LIKE '%$SWproduct%'
            ");
            $chekSPKlin = count($chekSPKLilin);
            if (is_null($chekSPKlin) or $chekSPKlin == 0) {
                $data_return = $this->SetReturn(
                    false, 
                    "SPK LILIN dengan product tersebut tidak ditemukan, 
                    1. Harap menghubungi PPIC Untuk dibuatkan SPK LILIN terlebidahulu 
                    2. Setelah itu dilanjutkan supervisor untuk dibuatkan RPHnya
                    --[ini adalah prosedur yang sudah diputuskan oleh PAK TAN]--", null, null);
                return response()->json($data_return, 404);
            }
            // dd($chekSPKLilin);

            $checkRPHarealilin = FacadesDB::connection('erp')
            ->select("SELECT 
                    X.IDM,
                    X.Ordinal,
                    WS.IDM as idrph
                FROM
                workorder W
                JOIN waxorderitem X ON X.Workorder = W.ID
                LEFT JOIN workscheduleitem WS ON WS.LinkID = W.ID
                JOIN Product P ON P.ID = X.Product
                WHERE
                    W.SWUsed = '$SWWorkOrder' 
                    AND P.SW LIKE '%$SWproduct%' 
            ");
            $spklilin = $checkRPHarealilin[0]->IDM;
            $ordinalspklilin = $checkRPHarealilin[0]->Ordinal;
            $chekRPH = $checkRPHarealilin[0]->idrph;
            if (is_null($chekRPH) or $chekRPH == 0) {
                $data_return = $this->SetReturn(
                    false, 
                    "RPH dengan produk tersebut tidak ditemukan, 
                    Harap menghubungi supervisor untuk dibuatkan RPHnya", null, null);
                return response()->json($data_return, 404);
            }

            // dd($checkRPHarealilin);
        $SWData = FacadesDB::connection('erp')->select("SELECT
            WS.IDM Rph,
            WS.Ordinal Ordinal,
            K.SW WorkOrder,
            K.ID IDWorkOrder,-- PP.RubberCarat,
            K.SWUsed,
            P.SW Product,
            P.Description,
            XI.IDM waxorder,
            XI.Ordinal waxorderord,
            K.Carat,
        IF
            (
                P.ProdGroup IN ( 6, 10 ),
                IFNULL( P.RubberQty, 1 ) / 2,
            IFNULL( P.RubberQty, 1 )) RubberQty,
            P.ID IDprod,
            S.Description ProdGroup,
            XI.Inject,
            KI.Qty 
        FROM
            workscheduleitem WS
            JOIN WaxOrder X ON WS.Level2 = X.ID
            JOIN waxorderitem XI ON XI.IDM = WS.Level2 
            AND XI.Ordinal = WS.Level3
            JOIN workorder K ON WS.LinkID = K.ID 
            JOIN WorkOrderItem KI ON WS.LinkID = KI.IDM 
            AND WS.LinkOrd = KI.Ordinal
            JOIN ProductCarat PC ON PC.ID = K.Carat
            JOIN Product P ON P.ID = XI.Product
            LEFT JOIN shorttext S ON P.ProdGroup = S.ID
            LEFT JOIN waxinjectorderitem WI ON WI.Tatakan = KI.IDM
        WHERE
            K.SWUsed = $SWWorkOrder
            AND P.SW LIKE '%$SWproduct%'
        GROUP BY 
            P.ID
        ORDER BY
            WS.ORdinal 
        ");

        
        $tambahdatakaret = FacadesDB::connection("erp")
        ->select("SELECT
            R.ID,
            P.SW Product,
            CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
            END  Kadar,
            CASE
            
            WHEN PC.SW = '6K' THEN
            '#090cd9' 
            WHEN PC.SW = '8K' THEN
            '#02ba1e' 
            WHEN PC.SW = '16K' THEN
            '#ff1a1a' 
            WHEN PC.SW = '17K' THEN
            '#e65507' 
            WHEN PC.SW = '17K.' THEN
            '#d909cb' 
            WHEN PC.SW = '20K' THEN
            '#ffcba4' 
            WHEN PC.SW = '10K' THEN
            '#f5fc0f' 
            WHEN PC.SW = '8K.' THEN
            '#ebb52d'
            WHEN PC.SW = '19K' THEN
            '#4908a3'
            WHEN PC.SW IS NULL THEN
            '#bcbcbc'
            END HexColor,
            R.Pcs,
            R.WaxUsage,
            R.WaxCompletion,
            R.WaxScrap,
            R.TransDate,
            R.STATUS,
            R.Size,
            R.StoneCast,
            CONCAT( L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW ) lokasi,
            L.Active,
            L.WaxInjectOrder,
            O.SW 
        FROM
            waxorderitem J 
        JOIN Rubber R ON J.Product = R.Product 
            AND R.Active IN ( 'P', 'K', 'O' ) 
            AND R.TransDate > '2018-01-01'
            AND R.UnUsedDate IS NULL
            JOIN Product P ON J.Product = P.ID
            LEFT JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN RubberLocation L ON R.ID = L.RubberID
            LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
            LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
            LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
            LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
            JOIN workorder O ON J.WorkOrder = O.ID 
        WHERE
            O.SWUsed = $SWWorkOrder
            AND P.SW LIKE '%$SWproduct%'
        GROUP BY
            R.ID,
            P.SW 
        ORDER BY
            P.SW,
            R.Size,
            R.StoneCast,
            R.ID DESC
        ");

        $rowcount = count($SWData);
        if($rowcount > 0 ){
            foreach ($SWData as $datas){}
            $WorkOrder = $datas->WorkOrder;
            $Product = $datas->Product;
            $Qty = $datas->Qty;
            $Inject = $datas->Inject;
            // $Tok = $chekWorkOrderdospkinject[0]->Tok;
            // $SC = $datas->Sc;
            $WaxOrder = $datas->waxorder;
            $WaxOrderOrd = $datas->waxorderord;
            $Rph = $datas->Rph;
            $Ordinal = $datas->Ordinal;
            $IDWorkOrder = $datas->IDWorkOrder;

            $idkaret = $tambahdatakaret[0]->ID;
            $productkaret = $tambahdatakaret[0]->Product;
            $kadarkaret = $tambahdatakaret[0]->Kadar;
            $Pcs = $tambahdatakaret[0]->Pcs;
            $size = $tambahdatakaret[0]->Size;
            $waxusage = $tambahdatakaret[0]->WaxUsage;
            $transdatekaret = $tambahdatakaret[0]->TransDate;
            $status = $tambahdatakaret[0]->STATUS;
            $stonecast = $tambahdatakaret[0]->StoneCast;
            $lokasi = $tambahdatakaret[0]->lokasi;
            $Active = $tambahdatakaret[0]->Active;
            $waxin = $tambahdatakaret[0]->WaxInjectOrder;

            $data_return = array(
                'rowcount' => $rowcount,
                'WorkOrder' => $WorkOrder,
                'Product' => $Product,
                'Qty' => $Qty,
                'Inject' => $Inject,
                // 'Tok' => $Tok,
                // 'SC' => $SC,
                'WaxOrder' => $WaxOrder,
                'WaxOrderOrd' => $WaxOrderOrd,
                'Rph' => $Rph,
                'Ordinal' => $Ordinal,
                'IDWorkOrder' => $IDWorkOrder,

                'idkaret' => $idkaret,
                'productkaret' => $productkaret,
                'kadarkaret'=>$kadarkaret,
                'pcs'=>$Pcs,
                'size'=>$size,
                'waxusage'=>$waxusage,
                'transdatekaret'=>$transdatekaret,
                'status'=>$status,
                'stonecast'=>$stonecast,
                'lokasi'=>$lokasi,
                'active'=>$Active,
                'waxin'=>$waxin,
            );
            // dd($WorkOrder);

        }else{
            $data_return = array ('rowcount' => $rowcount);
        }

        // $data_tes = array(
        //     'tes1' => $data_return,
        //     'tes2' => $tambahdatakaret
        // );

        // dd($data_return);
        // $data_return1 = $this->SetReturn(true, "Getting tambah komponen Success", $data_tes, null);
        // // dd($data_return1);
        return response()->json($data_return, 200);
    }

    public function lihat($idkaret){
        // dd($idkaret);

        $lihatfoto = FacadesDB::select("
        SELECT
            D.ID IDKARET,
            A.SW,
            A.Description,
            F.Description AS Kadar,
            G.SW SIZE,
            H.Description Logo,
            CONCAT( '/rnd/FotohasilInjectLilin/', '', E.InjectPhoto1 ) f1,
            CONCAT( '/rnd/FotohasilInjectLilin/', '', E.InjectPhoto2 ) f2,
            CONCAT( '/rnd/FotohasilInjectLilin/', '', E.InjectPhoto3 ) f3,
            CONCAT( '/rnd/FotohasilInjectLilin/', '', E.InjectPhoto4 ) f4 
        FROM
            product A
            JOIN rubber D ON D.Product = A.ID
            LEFT JOIN rubberorderitem E ON E.Rubber = D.ID
            LEFT JOIN erp.productcarat F ON F.ID = A.VarCarat
            LEFT JOIN designsize G ON G.ID = A.Size
            LEFT JOIN shorttext H ON H.ID = A.Logo 
        WHERE
            D.ID = '$idkaret'
        --  E.InjectPhoto1 IS NOT NULL 
        GROUP BY
            A.ID
    ");

    $lihatfoto = $lihatfoto[0];
    // dd($lihatfoto);
        return view('Produksi.Lilin.SPKInjectLilin.fotokaret',compact('lihatfoto', 'idkaret'));
    }

    public function show($IDWaxInject)
    {
        
        $cariId = FacadesDB::connection("erp")
        ->select("SELECT
            W.*,
            E.SW emp,
            E.ID IDK,
            C.SW CSW,
            R.SW pkaret,
            Concat( '*', W.ID, '*' ) Barcode,
            C.HexColor,
            C.Description kadar,
            CONCAT( T.SW, '-', T.Description ) stickpohon 
        FROM
            waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID 
        WHERE
            W.ID = '$IDWaxInject'");
           
        return view('Produksi.Lilin.SPKInjectLilin.show',compact('cariId'));
       
    }

    public function tabelitem($IDWaxInject){
        
        $TabelItem = FacadesDB::connection("erp") //shwo tabel item
        ->select("SELECT
        W.*,
        W.StoneCast,
        W.WorkScheduleOrdinal,
        O.SW nospk,
        P.SW product,
    --         F.Photo,
        I.Inject,
        I.StoneNote,
        P.ID PID,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto,
    IF
        (
            F.ProdGroup IN ( 6, 10 ),
            IfNull( P.RubberQty, 1 ) / 2,
        IfNull( P.RubberQty, 1 )) RubberQty 
    FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
    WHERE
        W.IDM = '$IDWaxInject'
    ORDER BY
    I.Ordinal");
            
            return view('Produksi.Lilin.SPKInjectLilin.TabelItem',compact('TabelItem'));
    }

    public function tabelkaretdipilih($IDWaxInject){
        $TKaretDiPilih = FacadesDB::connection("erp") //show tabl karet yang dipilih
        ->select("SELECT
            I.*,
            P.SW Product,
            R.Pcs,
            R.WaxUsage,
            R.WaxCompletion,
            R.WaxScrap,
            R.TransDate,
            R.STATUS,
            R.Size,
            CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
						END  Kadar,
								CASE
						
						WHEN PC.SW = '6K' THEN
						'#090cd9' 
						WHEN PC.SW = '8K' THEN
						'#02ba1e' 
						WHEN PC.SW = '16K' THEN
						'#ff1a1a' 
						WHEN PC.SW = '17K' THEN
						'#e65507' 
						WHEN PC.SW = '17K.' THEN
						'#d909cb' 
						WHEN PC.SW = '20K' THEN
						'#ffcba4' 
						WHEN PC.SW = '10K' THEN
						'#f5fc0f' 
						WHEN PC.SW = '8K.' THEN
						'#ebb52d'
						WHEN PC.SW = '19K' THEN
						'#4908a3' 
							END HexColor,
            R.StoneCast,
            CONCAT( L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW ) lokasi 
        FROM
            WaxInjectOrderRubber I
            JOIN Rubber R ON I.Rubber = R.ID
            JOIN Product P ON R.Product = P.ID
            JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN RubberLocation L ON I.Rubber = L.RubberID
            LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
            LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
            LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
            LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
           
        WHERE
            I.IDM = '$IDWaxInject' 
        ORDER BY
            I.Ordinal");
    return view('Produksi.Lilin.SPKInjectLilin.TabelKaretDipilih',compact('TKaretDiPilih'));
    }

    public function tabelbatu($IDWaxInject){
        $TabelBatu = FacadesDB::connection("erp")
        ->select("SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, If(IfNull(P.Revision, 'A') In ('A', 'C'), T.SW, Z.SW) Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
            FROM WaxInjectOrder J
                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                JOIN WorkOrder O On W.WorkOrder = O.ID
                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
            WHERE J.ID = '$IDWaxInject')  Q
        JOIN Product P On Q.Product = P.ID
        LEFT JOIN ShortText X On Q.StoneColor = X.ID
        JOIN ProductAccessories A On P.ID = A.IDM
        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
        LEFT JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
        GROUP BY Q.IDM, Q.Ordinal, If(IfNull(P.Revision, 'A') In ('A', 'C'), T.SW, Z.SW)
        UNION
        SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
            FROM WaxInjectOrder J
                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                JOIN WorkOrder O On W.WorkOrder = O.ID
                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
            WHERE J.ID = '$IDWaxInject')  Q
        JOIN Product P On Q.Product = P.ID
        LEFT JOIN ShortText X On Q.StoneColor = X.ID
        JOIN ProductAccessories A On P.ID = A.IDM
        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
        GROUP BY Q.IDM, Q.Ordinal, T.SW
        ORDER BY IDM, Ordinal, Stone
        ");

        return view('Produksi.Lilin.SPKInjectLilin.TabelBatu',compact('TabelBatu'));
    }

    public function tabelbatulama($IDWaxInject){
        
        $TabelBatuLama = FacadesDB::connection("erp")
        ->select("SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, If(IfNull(P.Revision, 'A') In ('A', 'C'), T.SW, Z.SW) Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
            FROM WaxInjectOrder J
                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                JOIN WorkOrder O On W.WorkOrder = O.ID
                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
            WHERE J.ID = '$IDWaxInject')  Q
        JOIN Product P On Q.Product = P.ID
        JOIN ShortText X On Q.StoneColor = X.ID
        JOIN ProductAccessories A On P.ID = A.IDM
        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
        LEFT JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
        GROUP BY Q.IDM, Q.Ordinal, If(IfNull(P.Revision, 'A') In ('A', 'C'), T.SW, Z.SW)
        UNION
        SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
            FROM WaxInjectOrder J
                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                JOIN WorkOrder O On W.WorkOrder = O.ID
                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
            WHERE J.ID = '$IDWaxInject')  Q
        JOIN Product P On Q.Product = P.ID
        JOIN ShortText X On Q.StoneColor = X.ID
        JOIN ProductAccessories A On P.ID = A.IDM
        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
        GROUP BY Q.IDM, Q.Ordinal, T.SW
        ORDER BY IDM, Ordinal, Stone");

        return view('Produksi.Lilin.SPKInjectLilin.TabelBatuLama',compact('TabelBatuLama'));
    }

    public function tabeltotalbatu($IDWaxInject){

        $TabelTotalBatu = FacadesDB::connection("erp")
        ->select("SELECT Stone, Sum(Total) Total FROM (
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            LEFT JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            GROUP BY Q.IDM, Q.Ordinal, T.SW
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                Where J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            LEFT JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW)  A
            GROUP BY Stone
            ORDER BY Stone");
        
        return view('Produksi.Lilin.SPKInjectLilin.TabelTotalBatu',compact('TabelTotalBatu'));
    }

    public function tabelkaretpilihan($IDWaxInject){
        
        $TabelKaretPilihan = FacadesDB::connection("erp")
        ->select("SELECT R.ID, P.SW Product, Sum(I.Qty) Qty,  
        CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
        END  Kadar,
        CASE
        WHEN PC.SW = '6K' THEN
        '#090cd9' 
        WHEN PC.SW = '8K' THEN
        '#02ba1e' 
        WHEN PC.SW = '16K' THEN
        '#ff1a1a' 
        WHEN PC.SW = '17K' THEN
        '#e65507' 
        WHEN PC.SW = '17K.' THEN
        '#d909cb' 
        WHEN PC.SW = '20K' THEN
        '#ffcba4' 
        WHEN PC.SW = '10K' THEN
        '#f5fc0f' 
        WHEN PC.SW = '8K.' THEN
        '#ebb52d'
        WHEN PC.SW = '19K' THEN
        '#4908a3' 
            END HexColor, R.Pcs, R.WaxUsage, R.WaxCompletion, R.WaxScrap, R.TransDate, R.Status, R.Size, R.StoneCast,
        CONCAT(L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW) lokasi, L.Active, L.WaxInjectOrder
        FROM WaxInjectOrderItem I
        JOIN WaxOrderItem J On I.WaxOrder = J.IDM And I.WaxOrderOrd = J.Ordinal
        JOIN Rubber R On J.Product = R.Product And R.Active In ('P', 'K', 'O') And R.TransDate > '2018-01-01'
        JOIN Product P On J.Product = P.ID
        JOIN productcarat PC ON PC.ID = R.Carat
        LEFT JOIN RubberLocation L On R.ID = L.RubberID
        LEFT JOIN MasterLemari L1 On L.LemariID = L1.ID
        LEFT JOIN MasterLaci L2 On L.LaciID = L2.ID
        LEFT JOIN MasterBaris L3 On L.BarisID = L3.ID
        LEFT JOIN MasterKolom L4 On L.KolomID = L4.ID
        WHERE I.IDM = '$IDWaxInject' AND R.UnUsedDate IS NULL
        GROUP BY R.ID, P.SW
        ORDER BY P.SW, R.Size, R.StoneCast, R.ID DESC");

        return view('Produksi.Lilin.SPKInjectLilin.TabelKaretPilihan',compact('TabelKaretPilihan'));
    }


    public function save(Request $request)
    {
        // $tes = $request->detail['Stickpohon'];
        // dd($tes);
        $rubberplate = $request->detail['Piring'];
        // dd($rubberplate);

        $kadar = $request->detail['Kadar'];
        $rph = $request->detail['RphLilin'];
        $TotalQty = $request->detail['TotalQty'];
        $idworkorder = $request->items[0]['IDWorkOrder'];

        // dd($rph);
        $jumlahQtyasli = FacadesDB::connection("erp")->select("SELECT
 
        Sum(WK.Qty) TQty
    FROM
        waxorderitem WI
        JOIN workscheduleitem WSI ON WSI.Level2 = WI.IDM 
        AND WSI.Level3 = WI.Ordinal
        JOIN workorderitem WK ON WK.IDM = WI.WorkOrder AND WK.Ordinal = WI.WorkOrderOrd
    WHERE
        WSI.IDM = $rph 
        AND WSI.Carat = $kadar
        AND WK.IDM = $idworkorder
    GROUP BY
        WSI.LinkID ");



        if (is_null($rubberplate) or $rubberplate == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Simpan Gagal",
                "data"=>null,
                "error"=>[
                    "rubberplate"=>"Anda salah memasukkan lapel Piringan harap masukkan label dengan benar"
                ]
            ];
            return response()->json($data_return,400);
        }

        $checkplate = FacadesDB::connection("erp")
        ->select("SELECT Active FROM Rubberplate WHERE ID = $rubberplate");
        if ($checkplate == "N") {
            $data_return = [
                "success"=>false,
                "message"=>"Simpan Gagal",
                "data"=>null,
                "error"=>[
                    "rubberplate"=>"rubberplate yang anda masukkan tidak aktif"
                ]
            ];
            return response()->json($data_return,404);
        }

        

        $GenerateIDSPK = FacadesDB::connection("erp")
        ->select("SELECT
        CASE
                
            WHEN
                MAX( SWOrdinal ) IS NULL THEN
                    '1' ELSE MAX( SWOrdinal )+ 1 
                    END AS ID,
                DATE_FORMAT( CURDATE(), '%y' ) AS tahun,
                LPad( MONTH ( CurDate()), 2, '0' ) AS bulan,
                CONCAT(
                    DATE_FORMAT( CURDATE(), '%y' ),
                    '',
                    LPad( MONTH ( CurDate()), 2, '0' ),
                LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+ 1 END, 4, '0' )) Counter1 
            FROM
                waxinjectorder 
            WHERE
                SWYear = DATE_FORMAT( CURDATE(), '%y' ) 
            AND SWMonth = MONTH (
            CurDate())
        ");

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        $insert_WaxInjectOrder = waxinjectorder::create([
            'ID' => $GenerateIDSPK[0]->Counter1, // dari ganerateIDSPK
            'EntryDate' => date('Y-m-d H:i:s'), // auto isi tanggal saat disimpan
            'UserName' => $username, // username yang login
            'Remarks' => $request->detail['Catatan'], //dari form isisan catatan
            'TransDate' => $request->detail['Date'], //dari form tanggal yang di inputkan
            'Employee' => $request->detail['Operator'], //dari form operator yang dipilih
            'WorkGroup' => $request->detail['Kelompok'], // dari form kelompok
            'WaxOrder' => null,
            'Carat' => $request->detail['Kadar'], // dari form kadar
            'RubberPlate' => $request->detail['Piring'], //dari form piringan karet
            'Qty' => $request->detail['TotalQty'], // dari checkbox kolom QTY
            'TreeStick' => $request->detail['Stickpohon'], // dari form stick pohon
            'SWYear' => $GenerateIDSPK[0]->tahun, // dari ganerate ID
            'SWMonth' => $GenerateIDSPK[0]->bulan, // dari garenate id kolom bulan
            'SWOrdinal' => $GenerateIDSPK[0]->ID, // dari ganerate ID
            'BoxNo' => $request->detail['Kotak'], // dari form piliha kotak
            'Purpose' => 'I', //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            'Active' => 'A', //ntar klo sdh ada dibuatkan pengambilan karet dan/atau batu .. berubah jadi Active = 'P'
        ]);

        // $lihatdata = FacadesDB::select("SELECT * FROM waxinjectorder12 ORDER BY ID DESC LIMIT 1");
        // dd($lihatdata);

        $i = 0;
        foreach ($request->items as $IT => $value) {
            $i++;
            $insert_WaxInjectOrderItem = waxinjectorderitem::create([
                'IDM' => $insert_WaxInjectOrder->ID, //dari ID waxinjectorder
                'Ordinal' => $IT+1, //auto incerement
                'WaxOrder' => $value['waxorder'], // dari tabel form product
                'WaxOrderOrd' => $value['waxorderord'],// dari tabel form product
                'Qty' => $value['Qty'], //dari tabel form product
                'Tok' => $value['Tok'],//
                'StoneCast' => $value['Sc'], //
                'Tatakan' => $value['IDWorkOrder'] ,
                'WorkScheduleID' => $value['Rph'], //dari tabel form product
                'WorkScheduleOrdinal' => $value['Ordinal'] ,
                'Purpose' => $value['purposese'], //penanda untuk item tambahan atau order asli
            ]);
        }

        // $lihatdata = FacadesDB::select("SELECT * FROM waxinjectorderitem12 ORDER BY IDM DESC LIMIT 5");
        // dd($lihatdata);

        $j = 0;
        foreach ($request->rubbers as $KR => $val) {
            $j++;
            $insert_WaxInjectOrderRubber = waxinjectorderrubber::create([ 
                'IDM' => $insert_WaxInjectOrder->ID, //dari ID waxinjectorder
                'Ordinal' => $KR+1, //auto incerement
                'Rubber' => $val['idRubber'] , // darti tabel form karet
                'LinkOrd' => $insert_WaxInjectOrderItem->Tatakan,
            ]);
        }
        // $lihatdata = FacadesDB::select("SELECT * FROM waxinjectorderrubber12 ORDER BY IDM DESC LIMIT 5");
        // dd($lihatdata);
        
        $Qtyasli = $jumlahQtyasli[0]->TQty;
        $sisa = $Qtyasli - $TotalQty;
        
        if($TotalQty < $Qtyasli){
            $k = 0;
            foreach ($request->items as $pp => $operation){
                $k++;
                $update_wordscheduleitem = workscheduleitem:: where('IDM', $operation['Rph'])->where('LinkID', $operation['IDWorkOrder'])
                ->update(['Operation' => 200,
                        'Level4' => $sisa]);
            }
        } else{
            $k = 0;
            foreach ($request->items as $pp => $operation){
                $k++;
                $update_wordscheduleitem = workscheduleitem:: where('IDM', $operation['Rph'])->where('LinkID', $operation['IDWorkOrder'])
                ->update(['Operation' => 201,
                'Level4' => NULL]);
            }
        }
        
        if ($insert_WaxInjectOrderRubber) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'ID' => $insert_WaxInjectOrder->ID,
                    'ID2' => $GenerateIDSPK[0]->Counter1,
                ],
                201,
            );
        }
    }

// ========================================================================================== SPK 3DP
    public function simpanspk3dp(Request $request){
        // $rphssdasd = $request->itemdcs[0]['Rph'];

        // dd($rphssdasd);
        
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rndnew.worklistwax3dp");

        $insert_worklistwax3dp = worklistwax3dp::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'), // auto isi tanggal saat disimpan
            'UserName' => 'Linda', // username yang login
            'Remaks' => NULL, //dari form isisan catatan
            'TransDate' => date('Y-m-d H:i:s'), //dari form tanggal yang di inputkan
            'Stastus' => 'SEMI DC',
            'WorkSchedule' => $request->itemdcs[0]['Rph'],
            'WorkOrder' => $request->itemdcs[0]['IDm'], // dari tabel workorderitem kolom IDM
            'WorkOrderOrd' => $request->itemdcs[0]['OrdinalWOI'], // dari tabel workorderitem kolom ordinal
        ]);

        $generateID2 = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rndnew.worklist3dpproduction");

        $insert_Worklist3dpproduction = worklist3dpproduction::create([
            'ID' => $generateID2[0]->ID,
            'Link3D' => $request->itemdcs[0]['id'], //auto incerement
            'Status' => 'SPK SEMI DC LILIN', // dari tabel form product
            'Description' =>'SPK LILIN item DC',// dari tabel form product
            'Active' => 'A', //dari tabel form product
            'Notes' => 'Direct Casting Dari lilin',//
            'TransDate' => date('Y-m-d'), //
            'Qty' => $request->itemdcs[0]['QtySatuPohon'],
            'Product' => $request->itemdcs[0]['Product'], //dari tabel form product
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'WorkOrder' => $request->itemdcs[0]['IDm'], // dari tabel workorderitem kolom IDM
            'WorkOrderOrd' => $request->itemdcs[0]['OrdinalWOI'], // dari tabel workorderitem kolom ordinal
            'WorkSchedule' => $request->itemdcs[0]['Rph'],
            'Carat' => 0,
            'RequestID' => $insert_worklistwax3dp->ID,
        ]);
        
        $k = 0;
        foreach ($request->itemdcs as $IT => $isi) {
            $k++;
            $insert_worklistwax3dpitem = worklistwax3dpitem::create([
                'IDM' => $insert_worklistwax3dp->ID,
                'Ordinal' => $IT+1,
                'Product' => $isi['Product'],
                'Qty' => $isi['Qty'], //
                'WorkOrder' => $isi['IDm'], // dari tabel workorderitem kolom IDM
                'WorkOrderOrd' => $isi['OrdinalWOI'], // dari tabel workorderitem kolom ordinal
                'SPKPPIC' => $isi['Workorder'],
            ]);

            $insert_Worklist3dpproductionitem = worklist3dpproductionitem::create([
                'IDM' =>  $generateID2[0]->ID, //auto incerement
                'Ordinal' => $IT+1, // dari tabel form product
                'WorkOrder' => $isi['IDm'],// dari tabel form product
                'WaxInjectOrder' => '0', //dari tabel form product
                'Qty' => $isi['Qty'],
                'Product' => $isi['Product'],
            ]);
            
        }
        
        
        if ($insert_Worklist3dpproductionitem) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Berhasil!!',
                    'message' => 'SPK DC dari lilinl!!',
                    'ID3Dp' => $insert_Worklist3dpproduction->ID,
                ],
                201,
            );
        }
        // $printspk3dp = FacadesDB::connection("erp")->select("");
    }
    
    public function printspk3dp($IDSPK3Dp){
        $printspk3dp = FacadesDB::
        select("SELECT
        W3D.*,
        W.SW wow,
        P.SKU SW,
        P.Description descripproduct,
        C.Description kadar,
        SUM( W3D.Qty ) Pasang,
        SUM( W3D.Qty * 2 ) Qty1,
        F.SW ProdukJadi,
        GROUP_CONCAT('(', W3D.SPKPPIC , ')' ) SPKPPIC1,
    CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            WHEN C.SW = '8K' THEN
            '#02ba1e' 
            WHEN C.SW = '16K' THEN
            '#ff1a1a' 
            WHEN C.SW = '17K' THEN
            '#e65507' 
            WHEN C.SW = '17K.' THEN
            '#d909cb' 
            WHEN C.SW = '20K' THEN
            '#ffcba4' 
            WHEN C.SW = '10K' THEN
            '#f5fc0f' 
            WHEN C.SW = '8K.' THEN
            '#ebb52d' 
            WHEN C.SW = '19K' THEN
            '#4908a3' 
        END HexColor,
        Wl.ID
    FROM
        worklistwax3dpitem W3D
		JOIN worklist3dpproduction WL ON WL.RequestID = W3D.IDM
        JOIN erp.Product P ON W3D.Product = P.ID
        JOIN erp.workorder W ON W3D.WorkOrder = W.ID
        JOIN erp.workorderitem WR ON WR.IDM = W.ID
				JOIN erp.Product F ON WR.Product = F.ID
        JOIN erp.productcarat C ON W.Carat = C.ID 
    WHERE
        WL.ID in ($IDSPK3Dp) 
    GROUP BY
        P.ID 
    ORDER BY
        W.SW
        ");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.Lilin.SPKInjectLilin.PrintSPK3dp',compact('printspk3dp','datenow','timenow'));
    }

//======================================================================================== END SPK 3DP

    public function printbarkode($IDWaxInject){
        $printbarcode = FacadesDB::connection("erp")
        ->select("SELECT W.*, E.SW emp, C.SW CSW, R.SW pkaret, Concat('*', W.ID, '*') Barcode, C.HexColor, C.Description kadar, CONCAT(T.SW,'-',T.Description) stickpohon
            FROM waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID
            WHERE W.ID = '$IDWaxInject'");

        $printbarcode1 = FacadesDB::connection("erp")
        ->select("SELECT
            W.*,
            P.SW product,
            P.Photo,
            I.Inject,
            I.StoneNote,
            P.ID PID
        FROM
            WaxInjectOrderItem W
            JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal 
            AND W.WaxOrder = I.IDM
            JOIN Product P ON I.Product = P.ID 
        WHERE W.IDM = '$IDWaxInject'
                ORDER BY W.Ordinal");

        return view('Produksi.Lilin.SPKInjectLilin.PrintBarkode',compact('printbarcode','printbarcode1'));

        $returnHTML = view('Produksi.Lilin.SPKInjectLilin.PrintBarkode',compact('printbarcode','printbarcode1'));
    
        $pdf = Pdf::loadHtml($returnHTML);
        $customPaper = array(0, 0, 210, 835);
        $pdf->setPaper($customPaper, 'portrait');

        $hasilpdf = $pdf->output();            
        file_put_contents(('C:/LestariERP/ProduksiPDF/'.$IDWaxInject.'.pdf'), $hasilpdf);

        return response()->json( 
            array(
                'success' => true, 
                'id' => $IDWaxInject
                ));
    }

    public function printbarkodetes($IDWaxInject){
        $printbarcode = FacadesDB::connection("erp")
        ->select("SELECT W.*, E.SW emp, C.SW CSW, R.SW pkaret, Concat('*', W.ID, '*') Barcode, C.HexColor, C.Description kadar, CONCAT(T.SW,'-',T.Description) stickpohon
            FROM waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID
            WHERE W.ID = '$IDWaxInject'");

        $printbarcode1 = FacadesDB::connection("erp")
        ->select("SELECT
            W.*,
            P.SW product,
            P.Photo,
            I.Inject,
            I.StoneNote,
            P.ID PID
        FROM
            WaxInjectOrderItem W
            JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal 
            AND W.WaxOrder = I.IDM
            JOIN Product P ON I.Product = P.ID 
        WHERE W.IDM = '$IDWaxInject'
                ORDER BY W.Ordinal");

        // return view('Produksi.Lilin.SPKInjectLilin.PrintBarkode',compact('printbarcode','printbarcode1'));

        $returnHTML = view('Produksi.Lilin.SPKInjectLilin.PrintBarkode',compact('printbarcode','printbarcode1'));
    
        $pdf = Pdf::loadHtml($returnHTML);
        $customPaper = array(0, 0, 210, 835);
        $pdf->setPaper($customPaper, 'portrait');

        $hasilpdf = $pdf->output();            
        file_put_contents(('C:/LestariERP/ProduksiPDF/'.$IDWaxInject.'.pdf'), $hasilpdf);

        return response()->json( 
            array(
                'success' => true, 
                'id' => $IDWaxInject
                ));
    }

    public function printspk($IDWaxInject)
    {

        $printdataspk = FacadesDB::connection("erp") //cop spk
        ->select("SELECT W.*, E.SW emp, E.ID IDK, C.SW CSW, T.ID IDpohon, R.SW pkaret, Concat('*', W.ID, '*') Barcode, CASE
		
		WHEN C.SW = '6K' THEN
		'#090cd9' 
		WHEN C.SW = '8K' THEN
		'#02ba1e' 
		WHEN C.SW = '16K' THEN
		'#ff1a1a' 
		WHEN C.SW = '17K' THEN
		'#e65507' 
		WHEN C.SW = '17K.' THEN
		'#d909cb' 
		WHEN C.SW = '20K' THEN
		'#ffcba4'  
		WHEN C.SW = '10K' THEN
		'#f5fc0f' 
		WHEN C.SW = '8K.' THEN
		'#ebb52d'
		WHEN C.SW = '19K' THEN
		'#4908a3' 
	    END HexColor, 
        C.Description kadar, 
        CONCAT(T.SW,'-',T.Description) stickpohon
            FROM waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID
            WHERE W.ID = '$IDWaxInject'");

        $tabelinjectlilin = FacadesDB::connection("erp") // inject lilin
        ->select("SELECT
        W.*,
        W.StoneCast,
        W.WorkScheduleOrdinal,
        O.SW nospk,
        SUBSTRING(P.SW, 1, 20) product,
				SUM(I.Inject) Inject,
				GROUP_CONCAT(W.Qty) Qty1,
       
        I.StoneNote,
        P.ID PID,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto,
    IF
        (
            F.ProdGroup IN ( 6, 10 ),
            IfNull( P.RubberQty, 1 ) / 2,
        IfNull( P.RubberQty, 1 )) RubberQty 
    FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
    WHERE
        W.IDM = '$IDWaxInject'
        GROUP BY
        P.ID,
        O.ID
    ORDER BY
        W.Ordinal");
            
        $jumlahqtydaninject = FacadesDB::connection("erp") // total inject lilin
        ->select("SELECT
        SUM(I.Inject) TotalInject,
        SUM(W.Qty) TotalQty,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto,
        F.SW
        FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
        WHERE
        W.IDM = '$IDWaxInject'
        ORDER BY
        W.Ordinal");

        $tabelfoto = FacadesDB::connection("erp")->select("SELECT
        O.SW nospk,
        F.SW product,
        F.SW,
        P.SW KomponenProuct,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto
        FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID 
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
        WHERE
        W.IDM = '$IDWaxInject'
        GROUP BY
        F.SW,
        O.ID
        ORDER BY
        W.Ordinal");

        $fotokomponen = FacadesDB::connection("erp")->select("SELECT
        P.SW KomponenProuct,
        CONCAT('http://192.168.1.100/image/', P.Photo ,'.jpg') foto
        FROM
        WaxInjectOrderItem W
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN Product P ON I.Product = P.ID
        WHERE
        W.IDM = '$IDWaxInject'
        ORDER BY
        W.Ordinal");

        $tabelinjectkbkaret = FacadesDB::connection("erp") // kebutuhan karet
        ->select("SELECT I.*, O.SW nospk, SUBSTRING(P.SW, 1, 20) Product, W.Qty, R.Pcs, R.TransDate, R.Status, CONCAT(L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW) lokasi
           FROM WaxInjectOrderRubber I
	JOIN waxinjectorderitem W ON W.IDM = I.IDM 
	JOIN waxorderitem X ON W.WaxOrderOrd = X.Ordinal AND W.WaxOrder = X.IDM
	JOIN workorder O ON X.WorkOrder = O.ID
	JOIN workorderitem OI ON O.ID = OI.IDM AND OI.Ordinal = X.WorkOrderOrd
	JOIN Rubber R ON I.Rubber = R.ID
	JOIN Product P ON R.Product = P.ID
	LEFT JOIN RubberLocation L ON I.Rubber = L.RubberID
	LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
	LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
	LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
	LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID 
            WHERE I.IDM = '$IDWaxInject'
            GROUP BY
            I.Ordinal,
            O.ID
            ORDER BY
            I.Ordinal");
        
        $tabelinjectkbbatu = FacadesDB::connection("erp")
        ->select("SELECT
        A.IDM,
        A.ORdinal,
        A.WorkOrder,
        SUBSTRING( A.Product, 1, 20 ) Product,
        A.Inject,
        GROUP_CONCAT( A.StoneNote, '<br>' ) StoneNote,
        GROUP_CONCAT( A.Stone, '<br>' ) Stone,
        GROUP_CONCAT( A.Ordered, '<br>' ) Ordered,
        GROUP_CONCAT( A.EachQty, '<br>' ) EachQty,
        GROUP_CONCAT( A.Total, '<br>' ) Total
    FROM
        (
        SELECT
            Q.IDM,
            Q.Ordinal,
            Q.WorkOrder,
        P.ID IDProductjadi,
            P.SW Product,
            Q.Qty Inject,
            Q.StoneNote,
        IF
            ( IfNull( P.Revision, 'A' ) IN ( 'A', 'C' ), T.SW, Z.SW ) Stone,
                Sum( Q.Qty ) Ordered,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            A.Qty 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            Sum( Q.Qty * A.Qty ) 
        END Total
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
    
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'Y'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
        
        LEFT JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color <> 147
        LEFT JOIN Product Z ON T.Model = Z.Model 
        AND T.Size = Z.Size 
        AND Z.Color = X.Remarks 
        AND Z.ProdGroup = 126 
        AND RIGHT ( Z.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
        Q.Workorder,
    IF
        ( IfNull( P.Revision, 'A' ) IN ( 'A', 'C' ), T.SW, Z.SW ) UNION
    SELECT
        Q.IDM,
        Q.Ordinal,
        Q.WorkOrder,
        P.ID IDProductjadi,
        P.SW Product,
        Q.Qty Inject,
        Q.StoneNote,
        T.SW Stone,
    Sum( Q.Qty ) Ordered,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            A.Qty 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            Sum( Q.Qty * A.Qty ) 
        END Total 
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'Y'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
        
        LEFT JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color = 147 
        AND RIGHT ( T.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
        Q.Workorder,
        T.SW UNION
    SELECT
        Q.IDM,
        Q.Ordinal,
        Q.WorkOrder,
        P.ID IDProductjadi,
        P.SW Product,
        Q.Qty Inject,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END StoneNote,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Stone,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Ordered,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Total 
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
            
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'N'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
        
        LEFT JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color <> 147
        LEFT JOIN Product Z ON T.Model = Z.Model 
        AND T.Size = Z.Size 
        AND Z.Color = X.Remarks 
        AND Z.ProdGroup = 126 
        AND RIGHT ( Z.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
        Q.Workorder,
    IF
        ( IfNull( P.Revision, 'A' ) IN ( 'A', 'C' ), T.SW, Z.SW ) UNION
    SELECT
        Q.IDM,
        Q.Ordinal,
        Q.WorkOrder,
        P.ID IDProductjadi,
        P.SW Product,
        Q.Qty Inject,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END StoneNote,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Stone,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Ordered,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Total
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
            
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'N'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
    
        LEFT JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color = 147 
        AND RIGHT ( T.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
        Q.Workorder,
        T.SW 
    ORDER BY
        IDM,
        Ordinal,
        Stone 
        ) A 
    GROUP BY
        A.IDProductjadi,
        A.Workorder");

        $tabelinjecttkbbatu = FacadesDB::connection("erp")
        ->select("SELECT Stone, Sum(Total) Total FROM (
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            LEFT JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW, Q.WorkOrder
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            GROUP BY Q.IDM, Q.Ordinal, T.SW, Q.WorkOrder
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                Where J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            LEFT JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW, Q.WorkOrder)  A
            GROUP BY Stone
            ORDER BY Stone");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.Lilin.SPKInjectLilin.PrintSPK',compact('tabelfoto','fotokomponen','printdataspk','date','datenow','timenow','jumlahqtydaninject','tabelinjectlilin','tabelinjectkbkaret','tabelinjectkbbatu','tabelinjecttkbbatu'));
   
    }

     ///////////////////////////////////////////////////////// EDIT /////////////////////////////////////////////////
     public function edit($IDWaxInject){

        // dd($IDWaxInject);

        $karyawan = FacadesDB::connection("erp")
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");

        $kadar = FacadesDB::connection("erp")
        ->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $piring = FacadesDB::connection("erp")
        ->select("SELECT * FROM rubberplate ORDER BY ID DESC LIMIT 20");

        $rphlilin = FacadesDB::connection("erp")
        ->select("SELECT ID FROM workschedule WHERE UserName='Sandi' AND Location = 51 AND DATE(EntryDate) < NOW() ORDER BY ID DESC LIMIT 15 ");

        $stickpohon = FacadesDB::connection("erp")
        ->select("SELECT ID, SW, Description, CONCAT(SW,'-', Description) stickpohon FROM treestick");
        
        $cariId = FacadesDB::connection("erp")
        ->select("SELECT
            W.ID,
            W.EntryDate,
            W.UserName,
            W.Remarks,
            W.TransDate,
            W.Employee,
            W.WorkGroup,
            W.WaxOrder,
            W.Carat,
            W.RubberPlate,
            W.Qty,
            W.TreeStick,
            E.SW emp,
            E.ID IDK,
            C.SW CSW,
            R.SW pkaret,
            Concat( '*', W.ID, '*' ) Barcode,
            C.HexColor,
            C.Description kadar,
            CONCAT( T.SW, '-', T.Description ) stickpohon 
        FROM
            waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID 
        WHERE
            W.ID = '$IDWaxInject'");
            // dd($cariId);

        $TabelItem = FacadesDB::connection("erp")
        ->select("SELECT
        W.*,
        W.StoneCast,
        W.WorkScheduleOrdinal,
        O.SW nospk,
        P.SW product,
    --         F.Photo,
        I.Inject,
        I.StoneNote,
        P.ID PID,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto,
    IF
        (
            F.ProdGroup IN ( 6, 10 ),
            IfNull( P.RubberQty, 1 ) / 2,
        IfNull( P.RubberQty, 1 )) RubberQty 
    FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
    WHERE
        W.IDM = '$IDWaxInject'
    ORDER BY
        W.Ordinal
            ");

        $TabelKaretPilihan = FacadesDB::connection("erp")
        ->select("SELECT
        I.*,
        P.SW Product,
        R.Pcs,
        R.WaxUsage,
        R.WaxCompletion,
        R.WaxScrap,
        R.TransDate,
        R.STATUS,
        R.Size,
        CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
                    END  Kadar,
                            CASE
                    
                    WHEN PC.SW = '6K' THEN
                    '#090cd9' 
                    WHEN PC.SW = '8K' THEN
                    '#02ba1e' 
                    WHEN PC.SW = '16K' THEN
                    '#ff1a1a' 
                    WHEN PC.SW = '17K' THEN
                    '#e65507' 
                    WHEN PC.SW = '17K.' THEN
                    '#d909cb' 
                    WHEN PC.SW = '20K' THEN
                    '#ffcba4' 
                    WHEN PC.SW = '10K' THEN
                    '#f5fc0f' 
                    WHEN PC.SW = '8K.' THEN
                    '#ebb52d'
                    WHEN PC.SW = '19K' THEN
                    '#4908a3' 
                        END HexColor,
        R.StoneCast,
        CONCAT( L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW ) lokasi 
    FROM
        WaxInjectOrderRubber I
        JOIN Rubber R ON I.Rubber = R.ID
        JOIN Product P ON R.Product = P.ID
        JOIN productcarat PC ON PC.ID = R.Carat
        LEFT JOIN RubberLocation L ON I.Rubber = L.RubberID
        LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
        LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
        LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
        LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID      
    WHERE
        I.IDM = '$IDWaxInject' 
    ORDER BY
        I.Ordinal");

        return view('Produksi.Lilin.SPKInjectLilin.edit',compact('cariId','TabelItem','TabelKaretPilihan','karyawan', 'kadar', 'piring', 'rphlilin', 'stickpohon'));
    }

    function edit2(Request $request){

        $IDWaxInject = $request->IDWaxInject;
        // dd($IDWaxInject);
// dd($IDWaxInject);
        $cariId = FacadesDB::connection("erp")
        ->select("SELECT
            W.ID,
            W.EntryDate,
            W.UserName,
            W.Remarks,
            W.TransDate,
            W.Employee,
            W.WorkGroup,
            W.WaxOrder,
            W.Carat,
            W.RubberPlate,
            W.Qty,
            W.BoxNo,
            W.TreeStick,
            E.SW emp,
            E.ID IDK,
            C.SW CSW,
            R.SW pkaret,
            Concat( '*', W.ID, '*' ) Barcode,
            C.HexColor,
            C.Description kadar,
            CONCAT( T.SW, '-', T.Description ) stickpohon 
        FROM
            waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID 
        WHERE
            W.ID = '$IDWaxInject'");
           
            // $cariId = $cariId[0];

            $rowcount = count($cariId);
        if($rowcount > 0 ){
            foreach ($cariId as $datas){}
            $IDwaxinject = $datas->ID;
            $EntryDate = $datas->EntryDate;
            $UserName = $datas->UserName;
            $Remarks = $datas->Remarks;
            $BoxNo = $datas->BoxNo;
            $Transdate = $datas->TransDate;
            $Employee = $datas->Employee;
            $WorkGroup = $datas->WorkGroup;
            $WaxOrder = $datas->WaxOrder;
            $Carat = $datas->Carat;
            $RubberPlate = $datas->RubberPlate;
            $Qty = $datas->Qty;
            $TreeStick = $datas->TreeStick;
            $pkaret = $datas->pkaret;
            $HexColor = $datas->HexColor;
            $kadar = $datas->kadar;
            $stickpohon = $datas->stickpohon;

            $data_Return = array(
               'IDwaxinject' => $IDwaxinject,
               'entrydate' => $EntryDate,
               'username' => $UserName,
               'remark' => $Remarks,
               'boxno' => $BoxNo,
               'transdate' => $Transdate,
               'employee' => $Employee,
               'workgroup' => $WorkGroup,
               'waxorder' => $WaxOrder,
               'carat' => $Carat,
               'rubberplate' => $RubberPlate,
               'qtytotal' => $Qty,
               'treestick' => $TreeStick,
               'pkaret' => $pkaret,
               'hexcolor' => $HexColor,
               'kadar' => $kadar,
               'stickpohon' => $stickpohon
            );
        // dd($data_Return);
        }else{
            $data_Return = array ('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);

//             $databawah = FacadesDB::connection('erp')
//             ->select("SELECT * FROM Waxinjectorderitem 
//             WHERE IDM = $IDWaxInject
//             ");
    
//             $databawah2 = FacadesDB::connection('erp')
//             ->select("SELECT * FROM Waxinjectorderrubber
//             WHERE IDM = $IDWaxInject
//             ");
    
//             $cariId->items = $databawah;
// //  dd($cariId);
    }

    function prosesdataedit(Request $request, $IDwaxinjectorder){

//         $extraid = FacadesDB::connection("erp")->select("SELECT ID, SUBSTRING(ID, 1,2) thn, SUBSTRING(ID, 3,2) bln, SUBSTRING(ID, 5,4) ord  
// FROM waxinjectorder WHERE ID='$IDwaxinjectorder'");
        $updateheader = waxinjectorder::find($IDwaxinjectorder);
        $updateheader->ID = $IDwaxinjectorder;
        $updateheader->EntryDate = date('Y-m-d H:i:s');
        $updateheader->UserName = $username;
        $updateheader->Remarks = $request->detail['Catatan'];
        $updateheader->TransDate = $request->detail['Date'];
        $updateheader->Employee =  $request->detail['Operator'];
        $updateheader->WorkGroup = $request->detail['Kelompok'];
        $updateheader->WaxOrder = null;
        $updateheader->Carat = $request->detail['Kadar'];
        $updateheader->RubberPlate = $request->detail['Piring'];
        $updateheader->Qty = $request->detail['TotalQty'];
		$updateheader->TreeStick = $request->detail['Stickpohon'];
		// $updateheader->SWYear = ;
		// $updateheader->SWMonth = ;
		// $updateheader->SWOrdinal = ;
		$updateheader->BoxNo = $request->detail['Kotak'];
		$updateheader->Purpose = 'I';
		$updateheader->Active = 'A';
        $updateheader->update();
        return redirect()->back()->with('status','updateheader Updated Successfully');

        $i = 0;
        foreach ($request->items as $IT => $value) {
            $i++;
            $insert_WaxInjectOrderItem = waxinjectorderitem::create([
                'IDM' => $updateheader->ID, //dari ID waxinject update
                'Ordinal' => $IT+1, //auto incerement
                'WaxOrder' => $value['waxorder'], // dari tabel form product
                'WaxOrderOrd' => $value['waxorderord'],// dari tabel form product
                'Qty' => $value['Qty'], //dari tabel form product
                'Tok' => $value['Tok'],//
                'StoneCast' => $value['Sc'], //
                'Tatakan' => 0 ,
                'WorkScheduleID' => $value['Rph'], //dari tabel form product
                'WorkScheduleOrdinal' => $value['Ordinal'] , // dari tabel form product
            ]);
        }
        
        $updatetitem = FacadesDB::connection("erp")
        ->select("DELETE FROM waxinejctorderitem WHERE ID = '$IDWaxinjectorder'");

        $deletrubber = FacadesDB::connection("erp")
        ->select("DELETE FROM waxinjectorderrubber WHERE ID = '$IDwaxinjectorder'");
    }

    //////////////////////////////////////////////////////////// END EDIT /////////////////////////////////////////////////
    public function printspk2($IDWaxInject)
    {

        $printdataspk = FacadesDB::connection("erp") //cop spk
        ->select("SELECT W.*, E.SW emp, E.ID IDK, C.SW CSW, T.ID IDpohon, R.SW pkaret, Concat('*', W.ID, '*') Barcode, CASE
		
		WHEN C.SW = '6K' THEN
		'#090cd9' 
		WHEN C.SW = '8K' THEN
		'#02ba1e' 
		WHEN C.SW = '16K' THEN
		'#ff1a1a' 
		WHEN C.SW = '17K' THEN
		'#e65507' 
		WHEN C.SW = '17K.' THEN
		'#d909cb' 
		WHEN C.SW = '20K' THEN
		'#ffcba4'  
		WHEN C.SW = '10K' THEN
		'#f5fc0f' 
		WHEN C.SW = '8K.' THEN
		'#ebb52d'
		WHEN C.SW = '19K' THEN
		'#4908a3' 
	    END HexColor, 
        C.Description kadar, 
        CONCAT(T.SW,'-',T.Description) stickpohon
            FROM waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID
            WHERE W.ID = '$IDWaxInject'");

        $tabelinjectlilin = FacadesDB::connection("erp") // inject lilin
        ->select("SELECT
        W.*,
        W.StoneCast,
        W.WorkScheduleOrdinal,
        O.SW nospk,
        SUBSTRING(P.SW, 1, 20) product,
				SUM(I.Inject) Inject,
				GROUP_CONCAT(W.Qty) Qty1,
       
        I.StoneNote,
        P.ID PID,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto,
    IF
        (
            F.ProdGroup IN ( 6, 10 ),
            IfNull( P.RubberQty, 1 ) / 2,
        IfNull( P.RubberQty, 1 )) RubberQty 
    FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
    WHERE
        W.IDM = '$IDWaxInject'
        GROUP BY
        P.ID
    ORDER BY
        W.Ordinal");
            
        $jumlahqtydaninject = FacadesDB::connection("erp") // total inject lilin
        ->select("SELECT
        SUM(I.Inject) TotalInject,
        SUM(W.Qty) TotalQty,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto,
        F.SW
        FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
        WHERE
        W.IDM = '$IDWaxInject'
        ORDER BY
        W.Ordinal");

        $tabelfoto = FacadesDB::connection("erp")->select("SELECT
        O.SW nospk,
        F.SW product,
        F.SW,
        P.SW KomponenProuct,
        CONCAT('http://192.168.1.100/image/', F.Photo ,'.jpg') foto
        FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID 
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID 
        WHERE
        W.IDM = '$IDWaxInject'
        GROUP BY
        F.SW
        ORDER BY
        W.Ordinal");

        $fotokomponen = FacadesDB::connection("erp")->select("SELECT
        P.SW KomponenProuct,
        CONCAT('http://192.168.1.100/image/', P.Photo ,'.jpg') foto
        FROM
        WaxInjectOrderItem W
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN Product P ON I.Product = P.ID
        WHERE
        W.IDM = '$IDWaxInject'
        ORDER BY
        W.Ordinal");

        $tabelinjectkbkaret = FacadesDB::connection("erp") // kebutuhan karet
        ->select("SELECT I.*, O.SW nospk, SUBSTRING(P.SW, 1, 20) Product, W.Qty, R.Pcs, R.TransDate, R.Status, CONCAT(L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW) lokasi
           FROM WaxInjectOrderRubber I
            JOIN waxinjectorderitem W ON W.IDM = I.IDM 
            JOIN waxorderitem X ON W.WaxOrderOrd = X.Ordinal AND W.WaxOrder = X.IDM
            JOIN workorder O ON X.WorkOrder = O.ID
            JOIN workorderitem OI ON O.ID = OI.IDM AND OI.Ordinal = X.WorkOrderOrd
            JOIN Rubber R ON I.Rubber = R.ID
            JOIN Product P ON R.Product = P.ID
            LEFT JOIN RubberLocation L ON I.Rubber = L.RubberID
            LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
            LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
            LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
            LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID 
            WHERE I.IDM = '$IDWaxInject'
            GROUP BY
            I.Ordinal
            ORDER BY
            I.Ordinal");
        
        $tabelinjectkbbatu = FacadesDB::connection("erp")
        ->select("SELECT
        A.IDM,
        A.ORdinal,
        A.WorkOrder,
        SUBSTRING( A.Product, 1, 20 ) Product,
        A.Inject,
        GROUP_CONCAT( A.StoneNote, '<br>' ) StoneNote,
        GROUP_CONCAT( A.Stone, '<br>' ) Stone,
        GROUP_CONCAT( A.Ordered, '<br>' ) Ordered,
        GROUP_CONCAT( A.EachQty, '<br>' ) EachQty,
        GROUP_CONCAT( A.Total, '<br>' ) Total
    FROM
        (
        SELECT
            Q.IDM,
            Q.Ordinal,
            Q.WorkOrder,
        P.ID IDProductjadi,
            P.SW Product,
            Q.Qty Inject,
            Q.StoneNote,
        IF
            ( IfNull( P.Revision, 'A' ) IN ( 'A', 'C' ), T.SW, Z.SW ) Stone,
                Sum( Q.Qty ) Ordered,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            A.Qty 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            Sum( Q.Qty * A.Qty ) 
        END Total
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
    
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'Y'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
        
        JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color <> 147
        LEFT JOIN Product Z ON T.Model = Z.Model 
        AND T.Size = Z.Size 
        AND Z.Color = X.Remarks 
        AND Z.ProdGroup = 126 
        AND RIGHT ( Z.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
    IF
        ( IfNull( P.Revision, 'A' ) IN ( 'A', 'C' ), T.SW, Z.SW ) UNION
    SELECT
        Q.IDM,
        Q.Ordinal,
        Q.WorkOrder,
        P.ID IDProductjadi,
        P.SW Product,
        Q.Qty Inject,
        Q.StoneNote,
        T.SW Stone,
    Sum( Q.Qty ) Ordered,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            A.Qty 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'Y' THEN
            Sum( Q.Qty * A.Qty ) 
        END Total 
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'Y'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
        
        JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color = 147 
        AND RIGHT ( T.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
        T.SW UNION
    SELECT
        Q.IDM,
        Q.Ordinal,
        Q.WorkOrder,
        P.ID IDProductjadi,
        P.SW Product,
        Q.Qty Inject,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END StoneNote,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Stone,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Ordered,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Total 
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
            
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'N'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
        
        JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color <> 147
        LEFT JOIN Product Z ON T.Model = Z.Model 
        AND T.Size = Z.Size 
        AND Z.Color = X.Remarks 
        AND Z.ProdGroup = 126 
        AND RIGHT ( Z.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
    IF
        ( IfNull( P.Revision, 'A' ) IN ( 'A', 'C' ), T.SW, Z.SW ) UNION
    SELECT
        Q.IDM,
        Q.Ordinal,
        Q.WorkOrder,
        P.ID IDProductjadi,
        P.SW Product,
        Q.Qty Inject,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END StoneNote,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Stone,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Ordered,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END EachQty,
    CASE
            
            WHEN Q.StoneCast = 'N' THEN
        NULL 
        END Total
    FROM
        (
        SELECT DISTINCT
            Q.IDM,
            Q.Ordinal,
            Q.Product,
            W.StoneColor,
            O.SW WorkOrder,
            I.Qty,
            W.StoneNote,
            
            I.StoneCast 
        FROM
            WaxInjectOrder J
            JOIN WaxInjectOrderItem I ON J.ID = I.IDM 
            AND I.StoneCast = 'N'
            JOIN WaxOrderItem W ON I.WaxOrder = W.IDM 
            AND I.WaxOrderOrd = W.Ordinal
            JOIN WorkOrder O ON W.WorkOrder = O.ID
            JOIN WorkOrderItem Q ON W.WorkOrder = Q.IDM 
            AND W.WorkOrderOrd = Q.Ordinal 
        WHERE
            J.ID = $IDWaxInject 
        ) Q
        JOIN Product P ON Q.Product = P.ID
    
        JOIN ShortText X ON Q.StoneColor = X.ID
        JOIN ProductAccessories A ON P.ID = A.IDM
        JOIN Product T ON A.Product = T.ID 
        AND T.ProdGroup = 126 
        AND T.Color = 147 
        AND RIGHT ( T.SW, 2 ) <> '-S' 
    GROUP BY
        Q.IDM,
        Q.Ordinal,
        T.SW 
    ORDER BY
        IDM,
        Ordinal,
        Stone 
        ) A 
    GROUP BY
        A.IDProductjadi");

        $tabelinjecttkbbatu = FacadesDB::connection("erp")
        ->select("SELECT Stone, Sum(Total) Total FROM (
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            GROUP BY Q.IDM, Q.Ordinal, T.SW
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                Where J.ID = '$IDWaxInject')  Q
            JOIN Product P On Q.Product = P.ID
            JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW)  A
            GROUP BY Stone
            ORDER BY Stone");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.Lilin.SPKInjectLilin.PrintSPK2',compact('tabelfoto','fotokomponen','printdataspk','date','datenow','timenow','jumlahqtydaninject','tabelinjectlilin','tabelinjectkbkaret','tabelinjectkbbatu','tabelinjecttkbbatu'));
   
    }

}
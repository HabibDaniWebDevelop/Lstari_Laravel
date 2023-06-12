<?php

namespace App\Http\Controllers\Produksi\Lilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

// live tabel
use App\Models\erp\waxinjectorder;
use App\Models\erp\waxinjectorderitem;
use App\Models\erp\waxinjectorderrubber;
use App\Models\rndnew\worklist3dpproduction;
use App\Models\rndnew\worklist3dpproductionitem;
use App\Models\erp\workscheduleitem;
use App\Models\rndnew\worklistwax3dp;
use App\Models\rndnew\WorkListwax3dpItem;

// lokal tabel
// use App\Models\tes_laravel\waxinjectorder;
// use App\Models\tes_laravel\waxinjectorderitem;
// use App\Models\tes_laravel\waxinjectorderrubber;
// use App\Models\tes_laravel\worklist3dpproduction;
// use App\Models\tes_laravel\worklist3dpproductionitem;
// use App\Models\tes_laravel\workscheduleitem;
// use App\Models\tes_laravel\worklistwax3dp;
// use App\Models\tes_laravel\WorkListwax3dpItem;

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
        ->select("SELECT ID FROM waxinjectorder WHERE Active ='A' ORDER BY EntryDate DESC");

        $karyawan = FacadesDB::connection("erp")
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");

        $ProdSW = FacadesDB::connection("erp")
        ->select("SELECT P.ID, P.SW FROM Waxorderitem X JOIN Product P ON P.ID = X.Product where IDM = 999999");

        $kadar = FacadesDB::connection("erp")
        ->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $piring = FacadesDB::connection("erp")
        ->select("SELECT * FROM rubberplate WHERE Active = 'Y' ORDER BY ID DESC LIMIT 20");

        $rphlilin = FacadesDB::connection("erp")
        ->select("SELECT ID FROM workschedule WHERE UserName='Sandi' AND Location = 51 AND DATE(EntryDate) < NOW() ORDER BY ID DESC LIMIT 15 ");

        $stickpohon = FacadesDB::connection("erp")
        ->select("SELECT ID, SW, Description, CONCAT(SW,'-', Description) stickpohon FROM treestick");

        return view('Produksi.Lilin.SPKInjectLilin.index', compact('ProdSW','IDWaxInject','karyawan', 'kadar', 'piring', 'rphlilin', 'stickpohon'));
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
        SUM( KI.Qty ) TQty,
        I.TInject 
    FROM
        workscheduleitem WS
        JOIN WaxOrder X ON WS.Level2 = X.ID
        JOIN WaxOrderItem XI ON XI.IDM = WS.Level2 
        AND XI.Ordinal = WS.Level3 AND WS.Operation != 201 
        JOIN WorkOrder K ON WS.LinkID = K.ID 
        AND K.Carat = $kdr
        JOIN WorkOrderItem KI ON WS.LinkID = KI.IDM 
        AND WS.LinkOrd = KI.Ordinal
        JOIN ProductCarat PC ON PC.ID = K.Carat
        JOIN Product P ON P.ID = KI.Product
        LEFT JOIN waxinjectorderitem CI ON CI.WorkScheduleID = WS.IDM 
        AND CI.WorkScheduleOrdinal = WS.Ordinal
        JOIN shorttext S ON P.ProdGroup = S.ID
    
        LEFT JOIN (
        SELECT
            WSI.LinkID,
            Sum( WI.Inject ) TInject 
        FROM
            waxorderitem WI
            JOIN workscheduleitem WSI ON WSI.Level2 = WI.IDM 
            AND WSI.Level3 = WI.Ordinal 
        WHERE
            WSI.IDM = $rph 
            AND WSI.Carat = $kdr 
        GROUP BY
            WSI.LinkID 
        ) I ON WS.LinkID = I.LinkID --
        
    WHERE
        WS.IDM = $rph
        AND WS.Operation != 201 
--         OR CI.Qty != Q.TQty
        
    GROUP BY
        K.ID 
        ORDER BY
        K.SWUsed ASC");

        return view('Produksi.Lilin.SPKInjectLilin.ProdukList',compact('tabeltes'));
    }

    public function tambahdata($SPKPPICs,$kdr,$rph){

        // $rph = $request->rph;
        // $kdr = $request->kdr;
        // $items = $request->items;

        // dd($rph);
        // dd($kdr);
        // dd($items);
    $dataheader = FacadesDB::connection("erp")
    ->select("SELECT LEFT(SW,1) AS SWO FROM Workorder WHERE ID IN ($SPKPPICs)");

    $dataheader = $dataheader[0];
        
    $tambahdataitem = FacadesDB::connection("erp")->
    select("SELECT
    WS.IDM Rph,
    WS.Ordinal RphOrdinal,
    K.SW WorkOrder,
    K.ID IDWorkOrder,-- PP.RubberCarat,
    K.SWUsed,
    P.SW SWProduct,
    P.Description DescriptionProduct,
    P.Photo,
    CASE WHEN P.Description Like '%DC%' THEN 'badge bg-info' ELSE 'badge text-dark bg-light' END dcinfo,
    XI.IDM waxorder,
    XI.Ordinal waxorderord,
    K.Carat,
    XI.StoneNote,
IF
    (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
    IFNULL( P.RubberQty, 1 )) RubberQty,
    P.ID IDprod,
    S.Description ProdGroup,
    XI.Inject,
    KI.Qty,
    CASE WHEN 
		LEFT (K.SW, 1) LIKE 'O' THEN 'Disabledfalse' ELSE NULL END cek
FROM
    workscheduleitem WS
    JOIN WaxOrder X ON WS.Level2 = X.ID
    JOIN waxorderitem XI ON XI.IDM = WS.Level2 
    AND XI.Ordinal = WS.Level3
    JOIN workorder K ON WS.LinkID = K.ID 
    -- AND K.Carat = $kdr
    JOIN WorkOrderItem KI ON WS.LinkID = KI.IDM 
    AND WS.LinkOrd = KI.Ordinal
    JOIN ProductCarat PC ON PC.ID = K.Carat
    JOIN Product P ON P.ID = XI.Product
    LEFT JOIN shorttext S ON P.ProdGroup = S.ID 
WHERE
    WS.IDM = $rph
    AND WS.Carat = $kdr
    AND K.ID IN ($SPKPPICs) 
ORDER BY
    WS.ORdinal 
    ");


        $tambahdatakaret = FacadesDB::connection("erp")
        ->select("SELECT
            R.ID IDKaret,
            P.SW Product,
            CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
            END  Kadar,
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
            -- AND R.TransDate > '2018-12-01' 
             JOIN Product P ON J.Product = P.ID
             LEFT JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN RubberLocation L ON R.ID = L.RubberID
            LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
            LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
            LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
            LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
            JOIN workorder O ON J.WorkOrder = O.ID 
        WHERE
            O.ID in ($SPKPPICs)
        GROUP BY
            R.ID,
            P.SW 
        ORDER BY
            P.SW,
            R.Size,
            R.StoneCast,
            R.ID DESC
            ");

        $dataheader->datakomponen = $tambahdataitem;
        $dataheader->datakaretkomponen = $tambahdatakaret;
        $data_return = $this->SetReturn(true, "Succes,Data SPK PPIC found ", $dataheader, null);
        return response()->json($data_return, 200);

    }

    public function cariSWItemProduct(Request $request){

        $workorder = $request->workorder;
        $urutitem = $request->urutitem;
        $urutkaret = $request->urutkaret;
        $kadar = $request->kadar;
        
        $dataheaderadd = FacadesDB::connection("erp")
        ->select("SELECT * FROM Workorder WHERE SWUsed = $workorder");
    
        if (count($dataheaderadd) == 0) {
            $data_return = $this->SetReturn(false, "SPK yang anda masukkan tidak ditemukan, masukkan SPK dengan benar", null, null);
            return response()->json($data_return, 400);
        }

        $dataheaderadd = $dataheaderadd[0];
            
        $tambahdataitemadd = FacadesDB::connection("erp")->
        select("SELECT
        WS.IDM Rph,
        WS.Ordinal RphOrdinal,
        K.SW WorkOrder,
        K.ID IDWorkOrder,-- PP.RubberCarat,
        K.SWUsed,
        P.SW SWProduct,
        P.Description DescriptionProduct,
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
			Workorder K
			JOIN Workorderitem KI ON K.ID = KI.IDM
			JOIN Waxorderitem XI ON XI.WorkOrder = KI.IDM AND XI.WorkOrderOrd = KI.Ordinal
			JOIN productcarat PC ON PC.ID = K.Carat
			JOIN Product P ON P.ID = XI.Product
			LEFT JOIN workscheduleitem WS ON WS.LinkID = KI.IDM AND WS.LinkOrd = KI.Ordinal AND WS.Level2 = XI.IDM AND WS.Level3 = XI.Ordinal
			LEFT JOIN shorttext S ON P.ProdGroup = S.ID
    WHERE
        K.Carat = $kadar
        AND K.SWUsed = $workorder
        AND LEFT (K.SW, 1) NOT LIKE 'O' 
    ORDER BY
        WS.ORdinal 
        ");

$tambahdatakaretadd = FacadesDB::connection("erp")
->select("SELECT
    R.ID IDKaret,
    P.SW Product,
    CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
    END  Kadar,
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
    AND R.TransDate > '2018-12-01' 
     JOIN Product P ON J.Product = P.ID
     LEFT JOIN productcarat PC ON PC.ID = R.Carat
    LEFT JOIN RubberLocation L ON R.ID = L.RubberID
    LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
    LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
    LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
    LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
    JOIN workorder O ON J.WorkOrder = O.ID 
WHERE
    O.SWUsed = $workorder
GROUP BY
    R.ID,
    P.SW 
ORDER BY
    P.SW,
    R.Size,
    R.StoneCast,
    R.ID DESC
    ");

    $dataheaderadd->datakomponenadd = $tambahdataitemadd;
    $dataheaderadd->datakaretkomponenadd = $tambahdatakaretadd;
    $data_return = $this->SetReturn(true, "Succes, Data SPK PPIC ditemukan", $dataheaderadd, null);
    return response()->json($data_return, 200);
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
        WSI.Qty qtyitemDCnormalpcs,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        CASE 
				WHEN H.Description LIKE '%Anting%' THEN (WSI.Qty * 2) WHEN H.Description LIKE '%Giwang%' THEN (WSI.Qty * 2)	ELSE WSI.Qty END Qty,
    NULL QtySatuPohon
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
				JOIN product P ON X.Product = P.ID AND P.TypeProcess IS NULL
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN Product H ON H.ID = WOI.Product
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.ID AND P.TypeProcess
        IS NULL LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        X.IDM,
        X.Ordinal 
        UNION
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
        WSI.Qty qtyitemDCnormalpcs,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        CASE 
				WHEN H.Description LIKE '%Anting%' THEN (WSI.Qty * 2) WHEN H.Description LIKE '%Giwang%' THEN (WSI.Qty * 2)	ELSE WSI.Qty END Qty,
PP.MinOrder QtySatuPohon 
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID
        JOIN Product H ON H.ID = WOI.Product
JOIN rndnew.mastercomponent PP ON PP.ID = P.LinkID AND P.TypeProcess = 25
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 25
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        X.IDM,
        X.Ordinal 
        UNION
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
        WSI.Qty qtyitemDCnormalpcs,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        CASE 
				WHEN H.Description LIKE '%Anting%' THEN (WSI.Qty * 2) WHEN H.Description LIKE '%Giwang%' THEN (WSI.Qty * 2)	ELSE WSI.Qty END Qty,
PP.MinOrder QtySatuPohon 
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID
        JOIN Product H ON H.ID = WOI.Product
				JOIN rndnew.mastermainan PP ON PP.ID = P.LinkID AND P.TypeProcess = 26
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 26
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        WSI.IDM,
        WSI.Ordinal
        UNION
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
        WSI.Qty qtyitemDCnormalpcs,
        IF
        (
        P.ProdGroup IN ( 6, 10 ),
        IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        Y.Description ProdGroup,
        CASE 
		WHEN H.Description LIKE '%Anting%' THEN (WSI.Qty * 2) WHEN H.Description LIKE '%Giwang%' THEN (WSI.Qty * 2)	ELSE WSI.Qty END Qty,
PP.MinOrder QtySatuPohon 
        FROM
        workscheduleitem WSI
        JOIN waxorderitem X ON WSI.Level2 = X.IDM AND WSI.Level3 = X.Ordinal
        JOIN workorder WO ON X.WorkOrder = WO.ID AND WO.Carat = $kdr
        JOIN workorderitem WOI ON X.WorkOrder = WOI.IDM AND X.WorkOrderOrd = WOI.Ordinal
        JOIN product P ON X.Product = P.ID
        JOIN Product H ON H.ID = WOI.Product
				JOIN rndnew.masterkepala PP ON PP.ID = P.LinkID AND P.TypeProcess = 27
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 27
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklistwax3dpitem SS ON SS.WorkOrder = WOI.IDM AND SS.WorkOrderOrd = WOI.Ordinal
        WHERE
        WSI.IDM = $rph 
        -- AND WO.ID IN ( $items ) 
        AND P.Description LIKE '%DC%' 
        -- ND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
        AND SS.WorkOrder IS NULL 
        GROUP BY
        X.IDM,
        X.Ordinal 
        ORDER BY
        Ordinal	
        ");
        // dd($tambahkomponendirect);

    return view('Produksi.Lilin.SPKInjectLilin.TambahKomponenDirect',compact('tambahkomponendirect'));
    }


    public function show($IDWaxInject)
    {
        
        $header_cari = FacadesDB::connection("erp")
        ->select("SELECT
            W.ID IDspko,
            W.TransDate,
            W.EntryDate,
            W.UserName,
            W.Qty,
            E.SW employee,
            E.ID IDOperator,
            W.Workgroup Kelompok,
            W.BoxNo Kotak,
            -- W.Carat Kadar,
            WI.WorkScheduleID RPH,
            C.SW CSW,
            R.SW LabelPiring,
            R.ID IDPiring,
            C.HexColor,
            C.ID as IDKadar,
            C.Description Kadar,
            CONCAT( T.SW, '-', T.Description ) StickPohon,
            T.ID as IDstick,
            W.Remarks Catatan
        FROM
            waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            LEFT JOIN treestick T ON W.TreeStick = T.ID
            JOIN waxinjectorderitem WI ON W.ID = WI.IDM
        WHERE
            W.ID = '$IDWaxInject'");

        $header_cari = $header_cari[0];
        // dd($header_cari);
           
        $tabel_komponen_cari = FacadesDB::connection("erp") //shwo tabel item
            ->select("SELECT
            W.*,
            W.StoneCast,
            W.WorkScheduleOrdinal,
            O.SW WorkOrder,
            P.SW SWProduct,
            P.Description DescriptionProduct,
        --         F.Photo,
            I.Inject,
            I.StoneNote,
            P.ID as IDprod,
            CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto,
            W.WorkScheduleID as Rph,
            W.WorkScheduleOrdinal as RphOrdinal,
            W.Tatakan as IDWorkOrder,
            I.StoneNote,
        IF
            (
                F.ProdGroup IN ( 6, 10 ),
                IfNull( P.RubberQty, 1 ) / 2,
            IfNull( P.RubberQty, 1 )) RubberQty,
            CASE WHEN 
		    LEFT (O.SW, 1) LIKE 'O' THEN 'Disabled' ELSE NULL END cek
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

        $tabel_karet_yang_dipilih_cari = FacadesDB::connection("erp") //show tabl karet yang dipilih
        ->select("SELECT
            I.*,
            P.SW Product,
            R.Pcs,
            R.ID AS IDKaret,
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

        $tabel_batu_cari = FacadesDB::connection("erp")
        ->select("SELECT Q.IDM, Q.Ordinal, Q.WorkOrder as SWWorkOrder, P.SW ProductJadi, Q.Qty Inject, Q.StoneNote, If(IfNull(P.Revision, 'A') In ('A', 'C'), T.SW, Z.SW) JenisBatu, Sum(Q.Qty) Kebutuhan, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
            FROM WaxInjectOrder J
                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                JOIN WorkOrder O On W.WorkOrder = O.ID
                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
            WHERE J.ID = '$IDWaxInject' )  Q
        JOIN Product P On Q.Product = P.ID
        LEFT JOIN ShortText X On Q.StoneColor = X.ID
        JOIN ProductAccessories A On P.ID = A.IDM
        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
        LEFT JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
        GROUP BY Q.IDM, Q.Ordinal, If(IfNull(P.Revision, 'A') In ('A', 'C'), T.SW, Z.SW)
        UNION
        SELECT Q.IDM, Q.Ordinal, Q.WorkOrder as SWWorkOrder, P.SW ProductJadi, Q.Qty Inject, Q.StoneNote, T.SW JenisBatu, Sum(Q.Qty) Kebutuhan, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total
        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
            FROM WaxInjectOrder J
                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                JOIN WorkOrder O On W.WorkOrder = O.ID
                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
            WHERE J.ID = '$IDWaxInject' )  Q
        JOIN Product P On Q.Product = P.ID
        LEFT JOIN ShortText X On Q.StoneColor = X.ID
        JOIN ProductAccessories A On P.ID = A.IDM
        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
        GROUP BY Q.IDM, Q.Ordinal, T.SW
        ORDER BY IDM, Ordinal, JenisBatu
        ");

        $tabel_total_batu_cari = FacadesDB::connection("erp")
        ->select("SELECT Stone, Sum(Total) as TotalBatu FROM (
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

        $tabel_karet_cari = FacadesDB::connection("erp")
        ->select("SELECT R.ID as IDKaret, P.SW Product, Sum(I.Qty) Qty,  
            CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
            END  Kadar,
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
            END HexColor, R.Pcs, R.WaxUsage, R.WaxCompletion, R.WaxScrap, R.TransDate, R.Status as STATUSk, R.Size, R.StoneCast,
                IF(Z.Rubber IS NULL,'','checked') AS Dipilih,
                IF(Z.Rubber IS NULL,'','table-primary') AS cssdipilih,
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
            LEFT JOIN waxinjectorderrubber Z ON Z.IDM = I.IDM AND R.ID = Z.Rubber
            WHERE I.IDM = '$IDWaxInject' AND R.UnUsedDate IS NULL
            GROUP BY R.ID, P.SW
            ORDER BY P.SW, R.Size, R.StoneCast, R.ID DESC");
       
        $header_cari->Kpn = $tabel_komponen_cari;
        $header_cari->krtdpl = $tabel_karet_yang_dipilih_cari;
        $header_cari->bt = $tabel_batu_cari;
        $header_cari->tbt = $tabel_total_batu_cari;
        $header_cari->krt = $tabel_karet_cari;
        $data_return = $this->SetReturn(true, "Succes, Data SPK0o PPIC found ", $header_cari, null);
        return response()->json($data_return, 200); 
    }


    public function save(Request $request)
    {
      
        // dd($request);
        $rubberplate = $request->detail['Piring'];
        $kadar = $request->detail['Kadar'];
        $rph = $request->detail['RphLilin'];
        $TotalQty = $request->detail['TotalQty'];
        $idworkorder = $request->items[0]['WorkOrder'];

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

        $Qtyasli = $jumlahQtyasli[0]->TQty;
// dd($jumlahQtyasli[0]->TQty);

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
        $qwer = $request->detail['checkSWO'] ;
        // dd($qwer);
        if($qwer == 'O') {
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
        }else{
            $GenerateIDSPK = FacadesDB::connection("erp")
            ->select("SELECT
            CASE
                    
                WHEN
                    MAX( SWOrdinal ) IS NULL THEN
                        '1' ELSE MAX( SWOrdinal )+ 1 
                        END AS ID,
                        DATE_FORMAT( CURDATE(), '%y' ) +50 AS tahun,
                    LPad( MONTH ( CurDate()), 2, '0' ) AS bulan,
                    CONCAT(
                        DATE_FORMAT( CURDATE(), '%y' ) +50,
                        '',
                        LPad( MONTH ( CurDate()), 2, '0' ),
                    LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+ 1 END, 4, '0' )) Counter1 
                FROM
                    waxinjectorder 
                WHERE
                    SWYear = DATE_FORMAT( CURDATE(), '%y' ) +50
                AND SWMonth = MONTH (
                CurDate())
            ");
        }
       
        // dd($GenerateIDSPK);
        
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
                'WaxOrder' => $value['WaxOrder'], // dari tabel form product
                'WaxOrderOrd' => $value['WaxOrderOrd'],// dari tabel form product
                'Qty' => $value['Qty'], //dari tabel form product
                'Tok' => $value['Tok'],//
                'StoneCast' => $value['Sc'], //
                'Tatakan' => $value['WorkOrder'] ,
                'WorkScheduleID' => $value['Rph'], //dari tabel form product
                'WorkScheduleOrdinal' => $value['RphOrdinal'] , // dari tabel form product
                'Purpose' => $value['purposeitem'],
            ]);
        }

        $j = 0;
        foreach ($request->rubbers as $KR => $val) {
            $j++;
            $insert_WaxInjectOrderRubber = waxinjectorderrubber::create([ 
                'IDM' => $insert_WaxInjectOrder->ID, //dari ID waxinjectorder
                'Ordinal' => $KR+1, //auto incerement
                'Rubber' => $val['idRubber'] , // darti tabel form karet
                
            ]);
        }

        // $lihatdata = FacadesDB::select("SELECT * FROM waxinjectorderitem12 ORDER BY IDM DESC LIMIT 5");
        // dd($lihatdata);
        $sisa = $Qtyasli - $TotalQty;

        if($TotalQty < $Qtyasli){
            $k = 0;
            foreach ($request->items as $pp => $operation){
                $k++;
                $update_wordscheduleitem = workscheduleitem:: where('IDM', $operation['Rph'])->where('LinkID', $operation['WorkOrder'])
                ->update(['Operation' => 200,
                'Level4' => $sisa]);
            }
        } else{
            $k = 0;
            foreach ($request->items as $pp => $operation){
                $k++;
                $update_wordscheduleitem = workscheduleitem:: where('IDM', $operation['Rph'])->where('LinkID', $operation['WorkOrder'])
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
        
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rndnew.worklistwax3dp");
//  $rph = $request->itemdcs[0]['IDm'];
// dd($rph);
        $insert_worklistwax3dp = worklistwax3dp::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'), // auto isi tanggal saat disimpan
            'UserName' => 'Linda', // username yang login
            'Remaks' => NULL, //dari form isisan catatan
            'TransDate' => date('Y-m-d H:i:s'), //dari form tanggal yang di inputkan
            'Stastus' => 'SEMI DC',
            'WorkSchedule' =>  $request->detail['RphLilin'],
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
            'WorkSchedule' =>  $request->detail['RphLilin'],
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
                'WorkOrderOrd' => $isi['OrdinalWOI'],
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

    function Update(Request $request){

        // dd($request);
        $IDspko = $request->detail['idspko'];
        $rubberplate = $request->detail['Piring'];
        $kadar = $request->detail['Kadar'];
        $rph = $request->detail['RphLilin'];
        $TotalQty = $request->detail['TotalQty'];
        $idworkorder = $request->items[0]['WorkOrder'];
   
        if (is_null($rubberplate) or $rubberplate == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Simpan Gagal",
                "data"=>null,
                "error"=>[
                    "rubberplate"=>"Anda salah memasukkan label Piringan harap masukkan label dengan benar"
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
   
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();
   
        $update_WaxInjectOrder = waxinjectorder::where('ID',$IDspko)->update([
            'EntryDate' => date('Y-m-d H:i:s'), // auto isi tanggal saat disimpan
            'UserName' => $username, // username yang login
            'Remarks' => $request->detail['Catatan'], //dari form isisan catatan
            'Employee' => $request->detail['Operator'], //dari form operator yang dipilih
            'WorkGroup' => $request->detail['Kelompok'], // dari form kelompok
            'WaxOrder' => null,
            'Carat' => $request->detail['Kadar'], // dari form kadar
            'RubberPlate' => $request->detail['Piring'], //dari form piringan karet
            'Qty' => $request->detail['TotalQty'], // dari checkbox kolom QTY
            'TreeStick' => $request->detail['Stickpohon'], // dari form stick pohon
            'BoxNo' => $request->detail['Kotak'], // dari form piliha kotak
            'Purpose' => 'I', //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            'Active' => 'A', //ntar klo sdh ada dibuatkan pengambilan karet dan/atau batu .. berubah jadi Active = 'P'
        ]);
   
        $delete_waxinjectorderitem = waxinjectorderitem::where('IDM',$IDspko)->delete();

        $i = 0;
        foreach ($request->items as $IT => $value) {
            $i++;
            $insert_WaxInjectOrderItem = waxinjectorderitem::create([
                'IDM' => $IDspko, //dari ID waxinjectorder
                'Ordinal' => $IT+1, //auto incerement
                'WaxOrder' => $value['WaxOrder'], // dari tabel form product
                'WaxOrderOrd' => $value['WaxOrderOrd'],// dari tabel form product
                'Qty' => $value['Qty'], //dari tabel form product
                'Tok' => $value['Tok'],//
                'StoneCast' => $value['Sc'], //
                'Tatakan' => $value['WorkOrder'] ,
                'WorkScheduleID' => $value['Rph'], //dari tabel form product
                'WorkScheduleOrdinal' => $value['RphOrdinal'] , // dari tabel form product
                'Purpose' => $value['purposeitem'],
            ]);
        }
   
        $delete_waxinjectorderrubber = waxinjectorderrubber::where('IDM',$IDspko)->delete();
        $j = 0;
        foreach ($request->rubbers as $KR => $val) {
            $j++;
            $insert_WaxInjectOrderRubber = waxinjectorderrubber::create([ 
                'IDM' => $IDspko, //dari ID waxinjectorder
                'Ordinal' => $KR+1, //auto incerement
                'Rubber' => $val['idRubber'] , // darti tabel form karet
            ]);
        }
   
        
        if ($insert_WaxInjectOrderRubber) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'ID' => $IDspko,
                    'ID2' => $IDspko,
                ],
                201,
            );
        }
    }
    
    public function printspk3dp($IDSPK3Dp){
        $printspk3dp = FacadesDB::connection('erp')->select("SELECT
        P.SKU SW,
        P.Description descripproduct,
        C.Description kadar,
        F.SW ProdukJadi,
		WR.IDM,
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
        Wl.ID,
        GROUP_CONCAT('(',W.SW,' [',WLI.Qty,'] ',')') SPKPPIC1,
				IF(COUNT(WLI.Qty) > 1,SUM(WLI.Qty),WLI.Qty) Qty
    FROM
				rndnew.worklist3dpproduction WL
				JOIN rndnew.worklist3dpproductionitem WLI ON WL.ID = WLI.IDM 
        JOIN Product P ON WLI.Product = P.ID
        JOIN workorder W ON WLI.WorkOrder = W.ID
        JOIN workorderitem WR ON WR.IDM = W.ID AND WR.Ordinal = WLI.WorkOrderOrd
				JOIN Product F ON WR.Product = F.ID
        JOIN productcarat C ON W.Carat = C.ID 
    WHERE
        WL.ID IN ($IDSPK3Dp)
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
        ->select("SELECT W.*, E.SW emp, E.ID IDK, C.SW CSW, T.ID IDpohon, R.SW pkaret, Concat('*', W.ID, '*') Barcode, 
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
        C.Description kadar, 
        CONCAT(T.SW,'-',T.Description) stickpohon
            FROM waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            LEFT JOIN treestick T ON W.TreeStick = T.ID
            WHERE W.ID = '$IDWaxInject'");
            
        // $getidmwaxorder = FacadesDB::connection("erp")->select("SELECT Waxorder FROM WaxInjectOrderItem WHERE IDM = $IDWaxInject");
        // $idwaxorder = $getidmwaxorder->Waxorder;
        
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
        CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto,
    IF
        (
            F.ProdGroup IN ( 6, 10 ),
            IfNull( P.RubberQty, 1 ) / 2,
        IfNull( P.RubberQty, 1 )) RubberQty,
        B.h
    FROM
        WaxInjectOrderItem W
        JOIN WaxInjectOrder V ON W.IDM = V.ID
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrder O ON I.WorkOrder = O.ID
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN productcarat PC ON PC.ID = O.Carat
        JOIN Product P ON I.Product = P.ID
        JOIN Product F ON J.Product = F.ID
        LEFT JOIN (select A.IDM , A.ordinal, SUM(A.INject) as h FROM waxinjectorderitem B JOIN waxorderitem A ON B.WaxOrder = A.IDM AND B.WaxOrderOrd = A.Ordinal WHERE B.IDM = $IDWaxInject GROUP BY A.Product) B ON B.IDM = I.IDM AND B.Ordinal = I.Ordinal
    WHERE
        W.IDM = '$IDWaxInject'
        GROUP BY
        W.Waxorder,
        W.WaxorderOrd
    ORDER BY
        W.Ordinal");
            
        $jumlahqtydaninject = FacadesDB::connection("erp") // total inject lilin
        ->select("SELECT
        SUM(I.Inject) TotalInject,
        SUM(W.Qty) TotalQty,
        CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto,
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
        -- GROUP BY
        -- W.Waxorder,
        -- W.WaxorderOrd
        ORDER BY
        W.Ordinal");

        $tabelfoto = FacadesDB::connection("erp")->select("SELECT
        O.SW nospk,
        F.SW product,
        F.SW,
        P.SW KomponenProuct,
        CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto
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
        W.Waxorder,
        W.WaxorderOrd
        ORDER BY
        W.Ordinal");

        $fotokomponen = FacadesDB::connection("erp")->select("SELECT
        P.SW KomponenProuct,
        CONCAT('http://192.168.3.100/image/', P.Photo ,'.jpg') foto
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
        ->select("SELECT I.*, O.SW nospk, SUBSTRING(P.SW, 1, 20) Product, X.Inject as Qty, R.Pcs, R.TransDate, R.Status, CONCAT(L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW) lokasi
           FROM WaxInjectOrderRubber I
            JOIN waxinjectorderitem W ON W.IDM = I.IDM 
            -- AND I.Oordinal = W.Ordinal
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
            R.ID,
	        W.Tatakan
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

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.Lilin.SPKInjectLilin.PrintSPK',compact('fotokomponen','tabelfoto','printdataspk','date','datenow','timenow','jumlahqtydaninject','tabelinjectlilin','tabelinjectkbkaret','tabelinjectkbbatu','tabelinjecttkbbatu'));
   
    }
   

    //////////////////////////////////////////////////////////// END EDIT /////////////////////////////////////////////////
    public function printspk2($IDWaxInject)
    {

        $printdataspk = FacadesDB::connection("erp") //cop spk
        ->select("SELECT W.*, E.SW emp, E.ID IDK, C.SW CSW, T.ID IDpohon, R.SW pkaret, Concat('*', W.ID, '*') Barcode, 
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
        CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto,
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
        CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto,
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
        CONCAT('http://192.168.3.100/image/', F.Photo ,'.jpg') foto
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
        CONCAT('http://192.168.3.100/image/', P.Photo ,'.jpg') foto
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
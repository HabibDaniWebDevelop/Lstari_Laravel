<?php

namespace App\Http\Controllers\Produksi\Lilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;
//live
use App\Models\erp\waxinjectorder;
use App\Models\erp\waxinjectorderitem;
use App\Models\erp\waxinjectorderrubber;
use App\Models\erp\worklist3dpproduction;
use App\Models\erp\worklist3dpproductionitem;
use App\Models\erp\workscheduleitem;

//local
// use App\Models\tes_laravel\waxinjectorder12;
// use App\Models\tes_laravel\waxinjectorderitem12;
// use App\Models\tes_laravel\waxinjectorderrubber12;
// use App\Models\tes_laravel\workscheduleitem;
// use App\Models\tes_laravel\worklistwax3dp;
// use App\Models\tes_laravel\WorkListwax3dpItem;

use \DateTime;
use \DateTimeZone;

use Barryvdh\DomPDF\Facade\Pdf;

class OrderTambahanLilinController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $IDMWaxOrderItem = FacadesDB::connection("erp")
        ->select("SELECT * FROM waxinjectorder WHERE Purpose = 'T' AND Employee != 117 ORDER BY EntryDate DESC");
       
        return view('Produksi.Lilin.OrderTambahanLilin.index',compact('IDMWaxOrderItem'));
    }

    public function search()
    {
        $IDMWaxOrderItem = FacadesDB::connection("erp")
        ->select("SELECT * FROM waxinjectorder WHERE Purpose = 'T' AND Employee != 117 ORDER BY EntryDate DESC");

        return view('Produksi.Lilin.OrderTambahanLilin.data', compact('IDMWaxOrderItem'));
    }

    public function formSPKO(){
        $karyawan = FacadesDB::connection("erp")
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");

        $kadar = FacadesDB::connection("erp")
        ->select("SELECT ID, CASE
        WHEN SW = '6K' THEN
        '#0000FF' 
        WHEN SW = '8K' THEN
        '#00FF00' 
        WHEN SW = '8K.' THEN
        '#CFB370' 
        WHEN SW = '10K' THEN
        '#FFFF00' 
        WHEN SW = '16K' THEN
        '#FF0000' 
        WHEN SW = '17K' THEN
        '#FF6E01' 
        WHEN SW = '17K.' THEN
        '#FF00FF' 
        WHEN SW = '19K' THEN
        '#5F2987' 
        WHEN SW = '20K' THEN
        '#FFC0CB'
        ELSE '#808080'
    END HexColor, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $piring = FacadesDB::connection("erp")
        ->select("SELECT * FROM rubberplate ORDER BY ID DESC LIMIT 20");

        $ListSWWorkOrder = FacadesDB::connection("erp")
        ->select("SELECT SW FROM workorder ORDER BY ID DESC LIMIT 15");

        $stickpohon = FacadesDB::connection("erp")
        ->select("SELECT ID, SW, Description, CONCAT(SW,'-', Description) stickpohon FROM treestick");

        return view('Produksi.Lilin.OrderTambahanLilin.formSPKO',compact('karyawan', 'kadar', 'piring', 'ListSWWorkOrder', 'stickpohon'));
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
                    'IdPir' => 'Label Salah',
                ],
                201,
            );
        }
    }

    public function ProdukList($SWWorkOrder){
        // RND New 12
                $tabeltes = FacadesDB::connection("erp")
                ->select("SELECT
                CASE WHEN WS.IDM IS NULL THEN 0 WHEN WS.IDM IS NOT NULL THEN WS.IDM END IDM,
                CASE WHEN WS.Ordinal IS NULL THEN 0 WHEN WS.Ordinal IS NOT NULL THEN WS.Ordinal END RPHOrdinal,
                K.Carat,
                PC.Description DesCarat,
                K.SW WorkOrder,
                K.ID IDWorkOrder,
                WS.Level2,
                WS.Level3,
                K.SWUsed,
                P.SW Product,
                P.Description,
                P.Photo,--         XI.Inject - IfNull( B.Ordered, 0 ) Inject,
                XI.IDM waxorder,
                XI.Ordinal waxorderord,
                K.TotalQty TQty,
                F.Description descriptionPSJ,
				F.SW swPSJ,
            IF
                (
                    P.ProdGroup IN ( 6, 10 ),
                    IFNULL( P.RubberQty, 1 ) / 2,
                IFNULL( P.RubberQty, 1 )) RubberQty,
                P.ID IDprod,
                S.Description ProdGroup,
                KI.Qty,
                XI.Inject,
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
            END HexColor
			 
--                 I.TInject 
            FROM
				WorkOrder K 
				JOIN WorkOrderItem KI ON K.ID = KI.IDM 
				JOIN workscheduleitem WS ON WS.LinkID = KI.IDM AND WS.LinkOrd = KI.Ordinal	
                JOIN WaxOrderItem XI ON XI.WorkOrderOrd = KI.Ordinal 
                AND XI.WorkOrder = KI.IDM 
				JOIN WaxOrder X ON X.ID = XI.IDM
                JOIN ProductCarat PC ON PC.ID = K.Carat
                JOIN Product P ON P.ID = KI.Product -- AND P.Description LIKE '%DC%'
                JOIN Product F ON F.ID = XI.Product 
                LEFT JOIN waxinjectorderitem CI ON CI.WorkScheduleID = WS.IDM 
                AND CI.WorkScheduleOrdinal = WS.Ordinal
                LEFT JOIN shorttext S ON P.ProdGroup = S.ID
            WHERE
                K.SWUsed in ($SWWorkOrder)
                -- GROUP BY F.ID
            ORDER BY
                WS.Ordinal");

                return view('Produksi.Lilin.OrderTambahanLilin.ProdukList',compact('tabeltes'));
            }

    public function produklistpohon($SWWorkOrderDC){
        // RND New 12
        $tabeltes = FacadesDB::connection("erp")
        ->select("SELECT
        WS.IDM,
        WS.Ordinal RPHOrdinal,
        K.Carat,
        PC.Description DesCarat,
        K.SW WorkOrder,
        K.ID IDWorkOrder,
        WS.Level2,
        WS.Level3,
        K.SWUsed,
        P.SW Product,
        P.Description,
        P.Photo,--         XI.Inject - IfNull( B.Ordered, 0 ) Inject,
        XI.IDM waxoerder,
        XI.Ordinal waxorderord,
        K.TotalQty TQty,
        F.Description descriptionPSJ,
        F.SW swPSJ,
    IF
        (
            P.ProdGroup IN ( 6, 10 ),
            IFNULL( P.RubberQty, 1 ) / 2,
        IFNULL( P.RubberQty, 1 )) RubberQty,
        P.ID IDprod,
        S.Description ProdGroup,
        KI.Qty,
        XI.Inject,
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
            END HexColor
     
--                 I.TInject 
    FROM
        WorkOrder K 
        JOIN WorkOrderItem KI ON K.ID = KI.IDM 
        JOIN workscheduleitem WS ON WS.LinkID = KI.IDM AND WS.LinkOrd = KI.Ordinal	
        JOIN WaxOrder X ON WS.Level2 = X.ID
        JOIN WaxOrderItem XI ON XI.IDM = WS.Level2 
        AND XI.Ordinal = WS.Level3 
        JOIN ProductCarat PC ON PC.ID = K.Carat
        JOIN Product P ON P.ID = KI.Product -- AND P.Description LIKE '%DC%'
        JOIN Product F ON F.ID = XI.Product 
        LEFT JOIN waxinjectorderitem CI ON CI.WorkScheduleID = WS.IDM 
        AND CI.WorkScheduleOrdinal = WS.Ordinal
        JOIN shorttext S ON P.ProdGroup = S.ID
    WHERE
        K.SWUsed IN ($SWWorkOrderDC)
        AND F.Description LIKE '%DC%'
    ORDER BY
        WS.Ordinal");
                return view('Produksi.Lilin.OrderTambahanLilin.ProdukListPohonan',compact('tabeltes'));
            }
            
    public function ItemSPKO($XIOrdinal,$SWWorkOrder){
        $tambahitemSPKO = FacadesDB::connection("erp")->
        select("SELECT
        WS.IDM Rph,
        WS.Ordinal Ordinal,
        K.SW WorkOrder,
        K.ID IDWorkOrder,-- PP.RubberCarat,
        K.SWUsed,
        P.SW Product,
        P.Description,
        P.Photo,
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
				JOIN workorderitem KI ON K.ID = KI.IDM
        JOIN waxorderitem XI ON XI.WorkOrder = KI.IDM AND XI.WorkOrderOrd = KI.Ordinal
				JOIN Product P ON P.ID = XI.Product
        LEFT JOIN ProductCarat PC ON PC.ID = K.Carat
        LEFT JOIN shorttext S ON P.ProdGroup = S.ID
				LEFT JOIN workscheduleitem WS ON WS.Level2 = XI.IDM AND WS.Level3 = XI.Ordinal	
    WHERE
        XI.Ordinal IN ($XIOrdinal)
        AND K.SWUsed IN ($SWWorkOrder) 
    ORDER BY
        WS.ORdinal
        ");
    
        $tambahkaretSPKO = FacadesDB::connection("erp")
        ->select("SELECT
        R.ID,
        P.SW Product,
                    R.Carat,
                    CASE WHEN PC.Description IS NULL THEN '????' ELSE PC.Description END Kadar,
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
        K.SW,
                    CASE WHEN PC.Description IS NULL THEN 'Tidak Tahu' ELSE PC.Description
                    END  Kadar,
                    PP.RubberCarat
    FROM
        waxorderitem J 
    JOIN Rubber R ON J.Product = R.Product 
        AND R.Active IN ( 'P', 'K', 'O' ) 
        AND R.TransDate > '2018-01-01' 
         JOIN Product P ON J.Product = P.ID
                     LEFT JOIN ProductPart PP ON PP.IDM = P.ID
                     LEFT JOIN productcarat PC ON PC.ID = R.Carat
        LEFT JOIN RubberLocation L ON R.ID = L.RubberID
        LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
        LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
        LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
        LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
        JOIN workorder K ON J.WorkOrder = K.ID 
        WHERE
            K.SWUsed IN ($SWWorkOrder)
            AND J.Ordinal IN ($XIOrdinal)
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
            return view('Produksi\Lilin\OrderTambahanLilin\TambahItemSPKO',compact('tambahitemSPKO','tambahkaretSPKO'));
    }

    public function TambahKomponenDirect($items,$kdr,$rph){ //===========Query item Direcast====================================================
       
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
        JOIN product P ON X.Product = P.ID
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.ID AND P.TypeProcess
        IS NULL LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM AND Wr.WorkOrderOrd = WOI.Ordinal AND Wr.ID IS NOT NULL
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
        AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
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
        LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM AND Wr.WorkOrderOrd = WOI.Ordinal AND Wr.ID IS NOT NULL
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
        AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL  
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
        JOIN rndnew.mastermainan PP ON PP.ID = P.LinkID AND P.TypeProcess = 26
        LEFT JOIN rndnew.drafter3d DD ON DD.Product = P.LinkID AND P.TypeProcess = 26
        LEFT JOIN shorttext Y ON P.ProdGroup = Y.ID
        LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM AND Wr.WorkOrderOrd = WOI.Ordinal AND Wr.ID IS NOT NULL
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
        AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL 
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
        LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM AND Wr.WorkOrderOrd = WOI.Ordinal AND Wr.ID IS NOT NULL
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
        AND DD.ID IS NOT NULL 
        AND P.SerialNo IS NOT NULL 
        AND P.Revision IS NULL
        GROUP BY
        WO.ID 
        ORDER BY
        Ordinal
        ");

    return view('Produksi.Lilin.OrderTambahanLilin.TambahKomponenDirect',compact('tambahkomponendirect'));
    }

    public function save(Request $request)
    {
        // $tes = $request->detail['Stickpohon'];
        // dd($tes);

        $GenerateIDSPK = FacadesDB::connection("erp")
        ->select("SELECT
        CASE
                
            WHEN
                MAX( SWOrdinal ) IS NULL THEN
                    '1' ELSE MAX( SWOrdinal )+ 1 
                    END AS ID,
                DATE_FORMAT( CURDATE(), '%y' )+ 50 AS tahun,
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
            'Purpose' => 'T', //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
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
                'WorkScheduleOrdinal' => $value['Ordinal'] , // dari tabel form product
                'Purpose' => 'T',
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
                'LinkOrd' => null,
            ]);
        }
        // $lihatdata = FacadesDB::select("SELECT * FROM waxinjectorderrubber12 ORDER BY IDM DESC LIMIT 5");
        // dd($lihatdata);
        
        $k = 0;
        foreach ($request->items as $pp => $operation){
            $k++;
            $update_wordscheduleitem = workscheduleitem:: where('IDM', $operation['Rph'])->where('LinkID', $operation['IDWorkOrder'])
            ->update(['Operation' => 201]);
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
            LEFT JOIN treestick T ON W.TreeStick = T.ID 
        WHERE
            W.ID = '$IDWaxInject'");

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
        W.Ordinal");

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
            R.StoneCast,
            CONCAT( L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW ) lokasi 
        FROM
            WaxInjectOrderRubber I
            JOIN Rubber R ON I.Rubber = R.ID
            JOIN Product P ON R.Product = P.ID
            LEFT JOIN RubberLocation L ON I.Rubber = L.RubberID
            LEFT JOIN MasterLemari L1 ON L.LemariID = L1.ID
            LEFT JOIN MasterLaci L2 ON L.LaciID = L2.ID
            LEFT JOIN MasterBaris L3 ON L.BarisID = L3.ID
            LEFT JOIN MasterKolom L4 ON L.KolomID = L4.ID
        
        WHERE
            I.IDM = '$IDWaxInject' 
        ORDER BY
            I.Ordinal");

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
        ORDER BY IDM, Ordinal, Stone
        ");
                
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

        $TabelKaretPilihan = FacadesDB::connection("erp")
        ->select("SELECT R.ID, P.SW Product, Sum(I.Qty) Qty, R.Pcs, R.WaxUsage, R.WaxCompletion, R.WaxScrap, R.TransDate, R.Status, R.Size, R.StoneCast,
        CONCAT(L1.SW, ' ', L2.SW, ' ', L3.SW, ' ', L4.SW) lokasi, L.Active, L.WaxInjectOrder
        FROM WaxInjectOrderItem I
        JOIN WaxOrderItem J On I.WaxOrder = J.IDM And I.WaxOrderOrd = J.Ordinal
        JOIN Rubber R On J.Product = R.Product And R.Active In ('P', 'K', 'O') And R.TransDate > '2018-01-01'
        JOIN Product P On J.Product = P.ID
        LEFT JOIN RubberLocation L On R.ID = L.RubberID
        LEFT JOIN MasterLemari L1 On L.LemariID = L1.ID
        LEFT JOIN MasterLaci L2 On L.LaciID = L2.ID
        LEFT JOIN MasterBaris L3 On L.BarisID = L3.ID
        LEFT JOIN MasterKolom L4 On L.KolomID = L4.ID
        WHERE I.IDM = '$IDWaxInject' AND R.UnUsedDate IS NULL
        GROUP BY R.ID, P.SW
        ORDER BY P.SW, R.Size, R.StoneCast, R.ID DESC");

        return view('Produksi.Lilin.OrderTambahanLilin.show',compact('cariId','TabelItem','TKaretDiPilih','TabelBatu','TabelTotalBatu','TabelKaretPilihan'));
       
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
        P.ID,
        W.Tatakan
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
        return view('Produksi.Lilin.OrderTambahanLilin.PrintSPK',compact('tabelfoto','fotokomponen','printdataspk','date','datenow','timenow','jumlahqtydaninject','tabelinjectlilin','tabelinjectkbkaret','tabelinjectkbbatu','tabelinjecttkbbatu'));   
    }

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

        return view('Produksi.Lilin.OrderTambahanLilin.PrintBarkode',compact('printbarcode','printbarcode1')); 
    }

    ///////////////////////////////////////////////////////// Order Tambahan DC /////////////////////////////////////////////////////// 
    public function formSPKPohonan(){


        $DaftarIdTmResinSudahDiposting = FacadesDB::connection("erp")->select("SELECT ID FROM transferresindc WHERE Active = 'P' ORDER BY ID DESC LIMIT 7");

        $ListSWWorkOrderDC = FacadesDB::connection("erp")
        ->select("SELECT
        W.SW
        -- SI.Operation,
            -- P.Description 
        FROM
            workorder W 
            JOIN workorderitem WI ON WI.IDM = W.ID
            -- JOIN product P ON WI.product = P.ID
            -- JOIN workscheduleitem SI ON SI.LinkID = WI.IDM
        -- WHERE
            -- P.Description LIKE '%DC%'
            GROUP BY W.SW
            ORDER BY W.SW ASC
            ");

        return view('Produksi.Lilin.OrderTambahanLilin.formSPKPohonan', compact('ListSWWorkOrderDC','DaftarIdTmResinSudahDiposting'));
    }

    public function dapattm3dp($IdTmResinSudahDiposting) {

    $employees = FacadesDB::connection('erp')
            ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");
        
            $stickPohon = FacadesDB::connection('erp')
            ->select("SELECT ID, CONCAT(SW,'-', Description) stickpohon FROM treestick");
        
            $plate = FacadesDB::connection('erp')->select("SELECT * FROM rubberplate WHERE SW LIKE '%D%' ORDER BY ID DESC ");
        
            $dapattm3dp = FacadesDB::connection("erp")->select("SELECT							
            SI.IDM rph,						
            P.SW,						
            DP.ID,						
            SI.IDM,						
            PC.Description descriptioncarat,						
            PC.ID carat,
            WI.IDM workorder,
            TI.Qty,
			SUM(TI.Qty) TQty
            FROM							
            transferresindc T 							
            JOIN transferresindcitem TI ON T.ID = TI.IDM AND T.Active = 'P'							
            JOIN workscheduleitem SI ON TI.WorkOrder = SI.LinkID AND TI.WorkOrderOrd = SI.LinkOrd				
            JOIN workschedule S	ON SI.IDM = S.ID						
            JOIN WaxOrderitem XI ON XI.IDM = SI.Level2 						
            AND XI.ORdinal = SI.Level3						
            JOIN Workorderitem WI ON WI.IDM = SI.LinkID 						
            AND WI.Ordinal = SI.LinkOrd						
            JOIN rndnew.worklist3dpproduction DP ON DP.WorkOrder = WI.IDM 						
            AND DP.WorkOrderOrd = WI.Ordinal						
            JOIN Product P ON P.ID = XI.Product						
            JOIN Productcarat PC ON PC.ID = SI.Carat 						
            WHERE							
            T.ID = $IdTmResinSudahDiposting
            ORDER BY							
            DP.ID DESC	");
            // dd($dapattm3dp);

                
            return view('Produksi\Lilin\OrderTambahanLilin\formSPKPohonan2', compact('dapattm3dp', 'employees','stickPohon','plate'));
    }

    public function tambahdataSPKPohonan($workorder,$kdrspk,$rphspk){

    $tambahdataitemSPK = FacadesDB::connection("erp")
            ->select("SELECT
            WS.IDM Rph,
            WS.Ordinal Ordinal,
            K.SW WorkOrder,
            K.ID IDWorkOrder,-- PP.RubberCarat,
            K.SWUsed,
            TI.Product,
            P.SW,
            P.SKU,
            P.Description,
            PC.Description Kadar,
            T.ID,
            TI.Qty,
            P.Photo,
            XI.TransferResinDC,
			XI.TransferResinDCOrd,
            XI.IDM WaxOrder,
            XI.Ordinal WaxOrderOrd,
            K.Carat,
            TI.WorkOrder,
						TI.WorkOrderOrd,
            
        IF
            (
                P.ProdGroup IN ( 6, 10 ),
                IFNULL( P.RubberQty, 1 ) / 2,
            IFNULL( P.RubberQty, 1 )) RubberQty,
            P.ID IDprod,
            S.Description ProdGroup
             
        FROM
			transferresindc T
			JOIN transferresindcitem TI ON T.ID = TI.IDM
			JOIN workscheduleitem WS ON TI.WorkOrder = WS.LinkID AND TI.WorkOrderOrd = WS.LinkOrd
			JOIN waxorderitem XI ON XI.IDM = WS.Level2 AND XI.Ordinal = WS.Level3
          JOIN workorder K ON TI.WorkOrder = K.ID AND K.Carat = $kdrspk
          JOIN WorkOrderItem KI ON TI.WorkOrder = KI.IDM AND TI.WorkOrderOrd = KI.Ordinal
					JOIN ProductCarat PC ON PC.ID = K.Carat
          JOIN Product P ON P.ID = XI.Product
          JOIN shorttext S ON P.ProdGroup = S.ID 
        WHERE
            WS.IDM = $rphspk
			AND K.ID IN ($workorder) 
        ORDER BY
            WS.Ordinal");
            // dd($tambahdataitem);

            return view('Produksi\Lilin\OrderTambahanLilin\ItemSPKPohonan',compact('tambahdataitemSPK'));
            }
    
            /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemSPKPohonan($XIOrdinal,$SWWorkOrder)
    {
        $tambahitemSPKPohonan = FacadesDB::connection("erp")->
        select("SELECT
        WS.IDM Rph,
        WS.Ordinal Ordinal,
        K.SW WorkOrder,
        K.ID IDWorkOrder,-- PP.RubberCarat,
        K.SWUsed,
        P.SW Product,
        P.Description,
        P.Photo,
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
        JOIN shorttext S ON P.ProdGroup = S.ID 
    WHERE
        XI.Ordinal IN ($XIOrdinal)
        AND K.SW = '$SWWorkOrder' 
    ORDER BY
        WS.ORdinal 
        ");

    return view('Produksi\Lilin\OrderTambahanLilin\TambahItemSPKPohonan',compact('tambahitemSPKPohonan'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rr(){
        return view('Produksi.Lilin.OrderTambahanLilin.form');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function list()
    {
        
    }
}
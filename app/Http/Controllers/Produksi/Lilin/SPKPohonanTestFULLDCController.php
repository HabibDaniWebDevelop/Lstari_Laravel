<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\erp\waxinjectorder;
use App\Models\erp\waxinjectorderitem;
use App\Models\erp\transferresindc;
use App\Models\erp\workscheduleitem;
use App\Models\erp\worklistwax3dp;
use App\Models\erp\WorkListwax3dpItem;
use App\Models\erp\worklist3dpproduction;
use App\Models\erp\worklist3dpproductionitem;

// use App\Models\tes_laravel\worklist3dpproduction12;

use \DateTime;
use \DateTimeZone;

use Barryvdh\DomPDF\Facade\Pdf;

class SPKPohonanTestFULLDCController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $IDMWaxOrderItem = FacadesDB::connection("erp")
        ->select("SELECT * FROM waxinjectorder WHERE Purpose = 'D' AND Employee != 117 ORDER BY ID DESC");

        return view('Produksi.Lilin.SPKPohonanTestFULLDC.index', compact('IDMWaxOrderItem'));
    }

    public function form(){
        $IDMWaxOrderItem = FacadesDB::connection("erp")
        ->select("SELECT * FROM waxinjectorder WHERE Purpose = 'D' AND Employee != 117 ORDER BY ID DESC");

        $carat = FacadesDB::connection('erp')->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $rphlilin = FacadesDB::connection("erp")
        ->select("SELECT W.ID FROM workschedule W JOIN workscheduleitem WI ON W.ID = WI.IDM WHERE WI.Operation = 210 AND W.UserName='Sandi' AND W.Location = 51 AND DATE(EntryDate) < NOW() GROUP BY W.ID ORDER BY W.ID DESC LIMIT 10");

        $idworklist3d = FacadesDB::connection("erp")->select("SELECT ID FROM rndnew.worklist3dpproduction ORDER BY ID DESC LIMIT 7");

        $DaftarIdTmResinSudahDiposting = FacadesDB::connection("erp")->select("SELECT ID FROM transferresindc WHERE Active = 'P' ORDER BY ID DESC LIMIT 7");

        return view('Produksi.Lilin.SPKPohonanTestFULLDC.form', compact('DaftarIdTmResinSudahDiposting','IDMWaxOrderItem','rphlilin','carat'));
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
        ->select("SELECT * FROM rubberplate WHERE Active = 'Y' AND SW LIKE '%D%' AND SW = '$LabelPiring'");
        
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
                    'IdPir' => 'ID tidak Ditemukan',
                ],
                201,
            );
        }
    }
    public function search($IDcari)
    {        
        $getWaxinjectOrder = FacadesDB::connection('erp')->select("SELECT
        W.*,
		XIOI.IDM,
		TR.IDM IDMtf,
		TR.WorkOrderOrd,
        E.SW karyawan,
        C.SW CSW,
        E.ID IDK, 
        R.SW pkaret, 
        Concat('*', W.ID, '*') Barcode, 
        C.HexColor, 
        C.Description kadar, 
        concat(T.SW,'-',T.Description) stickpohon
        FROM waxinjectorder W
		JOIN waxinjectorderitem XIOI ON W.ID = XIOI.IDM
        JOIN employee E ON W.Employee = E.ID
        JOIN productcarat C ON W.Carat = C.ID
        JOIN rubberplate R ON W.RubberPlate = R.ID
        JOIN treestick T ON W.TreeStick = T.ID
        JOIN transferresindcitem TR ON TR.WorkOrder = XIOI.Tatakan
        WHERE W.ID = $IDcari AND W.Purpose = 'D'
        GROUP BY
	    W.ID
       
        ");

        $datatabel = FacadesDB::connection('erp')->select("SELECT
        
        O.SW nospk, 
        P.SW product,
        W.Qty,
		W.Tatakan,
		W.WaxOrder,
		W.WaxOrderOrd,
        V.ID,
        P.SKU, 
        P.Photo, 
        I.Inject, 
        I.StoneNote, 
        P.ID PID,
        If(F.ProdGroup In (6, 10), IfNull(P.RubberQty, 1) / 2, IfNull(P.RubberQty, 1)) RubberQty -- concat('/image/',J.photo,'.jpg') foto
        FROM WaxInjectOrderItem W
        JOIN WaxInjectOrder V On W.IDM = V.ID
        JOIN WaxOrderItem I On W.WaxOrderOrd = I.Ordinal And W.WaxOrder = I.IDM
        JOIN Product P On W.WorkScheduleOrdinal = P.ID
        JOIN WorkOrder O On I.WorkOrder = O.ID 
        JOIN WorkOrderItem J On I.WorkOrder = J.IDM And I.WorkOrderOrd = J.Ordinal
        JOIN Product F On J.Product = F.ID
        WHERE W.IDM = $IDcari
        ORDER BY W.Ordinal
        ");
        
    return view('Produksi.Lilin.SPKPohonanTestFULLDC.show', compact('getWaxinjectOrder','datatabel'));
       
    }
    public function ListTabel( $carat, $idtm){

        $datas = FacadesDB::connection('erp')->select("SELECT
        F.Product,
        P.SW,
        P.SKU,
        P.Description,
        PC.Description Kadar,
        T.ID,
        F.Qty,
        WX.TransferResinDC,
        WX.TransferResinDCOrd,
        WX.IDM WaxOrder,
        WX.Ordinal WaxOrderOrd,
        F.WorkOrder,
        F.WorkOrderOrd 
    FROM
        transferresindc T
        JOIN transferresindcitem F ON T.ID = F.IDM
        JOIN product P ON P.ID = F.Product
        LEFT JOIN workorderitem WR ON WR.IDM = F.WorkOrder 
        AND WR.Ordinal = F.WorkOrderOrd
        LEFT JOIN waxorderitem WX ON WX.TransferResinDC = F.IDM 
        AND WX.TransferResinDCOrd = F.Ordinal
        LEFT JOIN workorder W ON W.ID = WR.IDM
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        LEFT JOIN productcarat PC ON PC.ID = W.Carat 
    WHERE
        -- T.Active = 'P' 
        -- AND 
        E.ID = $carat 
        AND T.ID = $idtm
            ");
            // dd($datas);
        $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Staf Admin'");

        return view('Produksi.Lilin.SPKPohonanTestFULLDC.add', compact('datas', 'employees'));
        
    }

    public function ListTabelTM(Request $request){
        
        $idProducts =  $request->input('id');

        $listProduct = [];
        foreach ($idProducts as $key => $item) {
            dd($idProducts);
            // Get Product
            $product = FacadesDB::connection("erp")->select("SELECT * FROM waxorderitem W ORDER BY W.Ordinal DESC LIMIT 20
            ");
            array_push($listProduct,$product[0]);
        }
        
        // $data_return = $this->SetReturn(false, "Model Found", $listProduct, null);

        // return view('Produksi.Lilin.SPKPohonanTestFULLDC.ListTabel',compact('ListTabelTM'));
    }
    

    public function save(Request $request)
    {
        // $tes = $request->detail['Employee'];
        // dd($tes);

        $makeID = FacadesDB::connection('erp')
        ->select("SELECT CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            DATE_FORMAT(CURDATE(), '%y') as tahun,
            LPad(MONTH(CurDate()), 2, '0' ) as bulan,
            CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) makeID
            FROM waxinjectorder
            Where SWYear = DATE_FORMAT(CURDATE(), '%y') AND SWMonth =  MONTH(CurDate())");

        $username = Auth::user()->name;
            DB::beginTransaction();
        
        $insert_WaxInjectOrder = waxinjectorder::create([
            'ID' => $makeID[0]->makeID, // dari ganerate makeID
            'EntryDate' => date('Y-m-d H:i:s'), // auto isi tanggal saat disimpan
            'UserName' => $username, // username login
            'Remarks' => $request->detail['note'], //dari form isisan catatan
            'TransDate' => $request->detail['tgl'], //dari form tanggal yang di inputkan
            'Employee' => $request->detail['Employee'], //dari form operator yang dipilih
            'WorkGroup' => $request->detail['WorkGroup'], // dari form kelompok
            'WaxOrder' => null,
            'Carat' => $request->detail['kadar'], // dari form kadar
            'RubberPlate' => $request->detail['Plate'], //dari form piringan karet
            'Qty' => $request->detail['totalQty'], // dari checkbox kolom QTY
            'TreeStick' => $request->detail['StickPohon'], // dari form stick pohon
            'SWYear' => $makeID[0]->tahun, // dari ganerate ID
            'SWMonth' => $makeID[0]->bulan, // dari garenate id kolom bulan
            'SWOrdinal' => $makeID[0]->ID, // dari ganerate ID
            'BoxNo' => $request->detail['BoxNo'], // dari form piliha kotak
            'Purpose' => 'D', 
            'Active' => 'A',
        ]);
        
        $i = 0;
        foreach ($request->items as $IT => $value) {
            $i++;
            $insert_WaxInjectOrderItem = waxinjectorderitem::create([
                'IDM' => $insert_WaxInjectOrder->ID, //dari ID waxinjectorder
                'Ordinal' => $IT+1, //auto incerement
                'WaxOrder' => $value['WaxOrder'], // darti tabel waxorderitem kolom ID
                'WaxOrderOrd' => $value['WaxOrderOrd'],// darti tabel twaxorderitem kolom Ordinal
                'Qty' => $value['Qty'], //dari tabel transferresindcitem kolom Qty
                'Tok' => $insert_WaxInjectOrder->carat,//dari tabel Waxinjectorder kolom carat
                'StoneCast' => 'N', // berisi 'Y' jika menggunkan batu 'null' berarti tidak menggunakan batu
                'Tatakan' => $value['WorkOrder'],
                'WorkScheduleID' => $value['TransferResinDC'], //dari tabel tansferiditem kolom workorder
                'WorkScheduleOrdinal' => $value['TransferResinDCOrd'], // dari tabel transferid item kolom workorederord
            ]);

        }
        
        $j = 0;
        foreach ($request->items as $ITT => $valueup) {
            $j++;
            $update_transferresindc = transferresindc::where('ID', $valueup['TransferResinDC'])
            ->update(['Active' => 'X']);

            $update_wordscheduleitem = workscheduleitem:: where('IDM', $valueup['Rph'])->where('LinkID', $valueup['IDWorkOrder'])
            ->update(['Operation' => 202]);
            
            $update_Worklist3dpproductionitem = worklist3dpproductionitem::where('WorkOrder', $valueup['WorkOrder'])
            ->where('WaxInjectOrder', '0')->update(['WaxInjectOrder' => $insert_WaxInjectOrder->ID]);
        }
        // $update_transferresindc = transferresindc::update([
            
        // ])

        if ($insert_WaxInjectOrderItem) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'ID2' => $makeID[0]->makeID,
                ],
                201,
            );
           
        }
        
    }


    public function cetak( $idspkpohon, $carat, $idtm){
        
        //data kop print
        $datacetak = FacadesDB::connection('erp')->select("SELECT 
        W.*, 
        E.SW emp,
        E.ID IDK, 
        C.SW CSW, 
        R.SW pkaret, 
        Concat('*', W.ID, '*') Barcode,
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
        C.Description kadar, 
        T.ID IDpohon,
        Concat(T.SW,'-',T.Description) stickpohon
        FROM waxinjectorder W
        JOIN employee E ON W.Employee = E.ID
        JOIN productcarat C ON W.Carat = C.ID
        JOIN rubberplate R ON W.RubberPlate = R.ID
        JOIN treestick T ON W.TreeStick = T.ID
        WHERE W.ID = $idspkpohon
        ");

        //data tabel print
        $datatabelcetak = FacadesDB::connection('erp')->select("SELECT
        F.Product,
        P.SW,
        P.SKU,
        P.Description,
        PC.Description Kadar,
        T.ID,
        F.Qty,
        WX.TransferResinDC WaxOrder,
		WX.TransferResinDCOrd WaxOrderOrd,
        F.WorkOrder,
        F.WorkOrderOrd,
        W.SW swworkorder
        FROM
        transferresindc T
        JOIN transferresindcitem F ON T.ID = F.IDM
        JOIN product P ON P.ID = F.Product
        LEFT JOIN workorderitem WR ON WR.IDM = F.WorkOrder AND WR.Ordinal = F.WorkOrderOrd
		LEFT JOIN waxorderitem WX ON WX.TransferResinDC = F.IDM AND WX.TransferResinDCOrd = F.Ordinal
 		LEFT JOIN waxinjectorderitem WJ ON WJ.WaxOrder = WX.TransferResinDC AND WJ.WaxOrderOrd = WX.TransferResinDCOrd
        LEFT JOIN workorder W ON W.ID = WR.IDM 
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        LEFT JOIN productcarat PC ON PC.ID = W.Carat
        WHERE
        E.ID = $carat AND T.ID = $idtm
        GROUP BY
        F.WorkOrder
        ");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.Lilin.SPKPohonanTestFULLDC.cetak', compact('datacetak','datenow','timenow', 'datatabelcetak'));
    }

    public function printplate($idspkpohon){
        $printbarcode = FacadesDB::connection("erp")
        ->select("SELECT W.*, E.SW emp, C.SW CSW, R.SW pkaret, Concat('*', W.ID, '*') Barcode, C.HexColor, C.Description kadar, CONCAT(T.SW,'-',T.Description) stickpohon
            FROM waxinjectorder W
            JOIN employee E ON W.Employee = E.ID
            JOIN productcarat C ON W.Carat = C.ID
            JOIN rubberplate R ON W.RubberPlate = R.ID
            JOIN treestick T ON W.TreeStick = T.ID
            WHERE W.ID = '$idspkpohon'");

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
        WHERE W.IDM = '$idspkpohon'
                ORDER BY W.Ordinal");

        return view('Produksi\Lilin\SPKPohonanTestFULLDC\cetakcatatanplate',compact('printbarcode','printbarcode1'));
    }

    public function produklist($kdr,$rph){
        // RND New 12
                $tabeltes = FacadesDB::connection("erp")
                ->select("SELECT
                WS.IDM,
                WS.Ordinal,
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
            IF
                (
                    P.ProdGroup IN ( 6, 10 ),
                    IFNULL( P.RubberQty, 1 ) / 2,
                IFNULL( P.RubberQty, 1 )) RubberQty,
                P.ID IDprod,
                S.Description ProdGroup,
                KI.Qty,
                XI.Inject,
                Q.TQty,
                I.TInject 
            FROM
                workscheduleitem WS
                JOIN WaxOrder X ON WS.Level2 = X.ID
                JOIN WaxOrderItem XI ON XI.IDM = WS.Level2 
                AND XI.Ordinal = WS.Level3 AND WS.Operation = 210 
                JOIN WorkOrder K ON WS.LinkID = K.ID 
                AND K.Carat = $kdr
                JOIN WorkOrderItem KI ON WS.LinkID = KI.IDM 
                AND WS.LinkOrd = KI.Ordinal
                JOIN ProductCarat PC ON PC.ID = K.Carat
                JOIN Product P ON P.ID = KI.Product AND P.Description LIKE '%DC%'
                LEFT JOIN waxinjectorderitem CI ON CI.WorkScheduleID = WS.IDM 
                AND CI.WorkScheduleOrdinal = WS.Ordinal
                JOIN shorttext S ON P.ProdGroup = S.ID
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
                ) Q ON WS.LinkID = Q.LinkID
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
                OR CI.Qty != Q.TQty
                
            GROUP BY
                K.ID 
            ORDER BY
                WS.Ordinal");
        
                return view('Produksi.Lilin.SPKPohonanTestFULLDC.ProdukList',compact('tabeltes'));
            }

            public function tambahdataSPK($workorder,$kdrspk,$rphspk){
        
                // dd($items);
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
            GROUP BY
	        TI.Ordinal	
        ORDER BY
            WS.Ordinal");
            // dd($tambahdataitem);

            return view('Produksi\Lilin\SPKPohonanTestFULLDC\ItemSPK',compact('tambahdataitemSPK'));
            }

function itemproductDC($items,$kdr,$rph){
    
    $permintaanitem3dp = FacadesDB::connection("erp")
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
    LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM AND Wr.WorkOrderOrd = WOI.Ordinal
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
    AND WO.ID IN ( $items ) 
    AND P.Description LIKE '%DC%' 
    AND DD.ID IS NOT NULL 
    AND P.SerialNo IS NOT NULL 
    AND P.Revision IS NULL 
    -- AND Wr.ID IS NULL 
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
    LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM 
    AND Wr.WorkOrderOrd = WOI.Ordinal
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
    AND WO.ID IN ( $items ) 
    AND P.Description LIKE '%DC%' 
    AND DD.ID IS NOT NULL 
    AND P.SerialNo IS NOT NULL 
    AND P.Revision IS NULL 
    -- AND Wr.ID IS NULL 
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
    LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM 
    AND Wr.WorkOrderOrd = WOI.Ordinal
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
    AND WO.ID IN ( $items ) 
    AND P.Description LIKE '%DC%' 
    AND DD.ID IS NOT NULL 
    AND P.SerialNo IS NOT NULL 
    AND P.Revision IS NULL 
    -- AND Wr.ID IS NULL 
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
    LEFT JOIN rndnew.worklist3dpproduction Wr ON Wr.WorkOrder = WOI.IDM 
    AND Wr.WorkOrderOrd = WOI.Ordinal
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
    AND WO.ID IN ( $items ) 
    AND P.Description LIKE '%DC%' 
    AND DD.ID IS NOT NULL 
    AND P.SerialNo IS NOT NULL 
    AND P.Revision IS NULL 
    -- AND Wr.ID IS NULL 
    GROUP BY
    WO.ID 
    ORDER BY
    Ordinal
    ");

    return view('Produksi\Lilin\SPKPohonanTestFULLDC\TambahItem',compact('permintaanitem3dp'));
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

                
            return view('Produksi\Lilin\SPKPohonanTestFULLDC\formspkpohonan', compact('dapattm3dp', 'employees','stickPohon','plate'));
        }


// ========================================================================================== SPK 3DP
    public function Requesti(Request $request){

        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rndnew.worklistwax3dp");

        // dd( $generateID);
        
        $insert_worklistwax3dp = worklistwax3dp::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'), // auto isi tanggal saat disimpan
            'UserName' => 'Linda', // username yang login
            'Remaks' => NULL, //dari form isisan catatan
            'TransDate' => date('Y-m-d H:i:s'), //dari form tanggal yang di inputkan
            'Stastus' => 'FULL DC',
            'WorkSchedule' => $request->ItemRequests[0]['Rph'],
            'WorkOrder' => $request->ItemRequests[0]['IDWorkOrder'], // dari tabel workorderitem kolom IDM
            'WorkOrderOrd' => $request->ItemRequests[0]['Ordinal'], // dari tabel workorderitem kolom ordinal
        ]);
        
        $generateID2 = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rndnew.worklist3dpproduction");

        $insert_Worklist3dpproduction = worklist3dpproduction::create([
            'ID' => $generateID2[0]->ID,
            'Link3D' => $request->ItemRequests[0]['ID3d'], //auto incerement
            'Status' => 'SPK LILIN DC', // dari tabel form product
            'Description' =>'SPK LILIN item DC',// dari tabel form product
            'Active' => 'A', //dari tabel form product
            'Notes' => 'Direct Casting Dari lilin',//
            'TransDate' => date('Y-m-d'), //
            'Qty' => $request->DetailRequest['totalQty'],
            'Product' => $request->ItemRequests[0]['Product'], //dari tabel form product
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'WorkOrder' => $request->ItemRequests[0]['IDWorkOrder'], // dari tabel workorderitem kolom IDM
            'WorkOrderOrd' => $request->ItemRequests[0]['Ordinal'], // dari tabel workorderitem kolom ordinal
        ]);

        $j = 0;
        foreach ($request->ItemRequests as $IT => $isi) {
            $j++;
            $insert_worklistwax3dpitem = worklistwax3dpitem::create([
                'IDM' => $insert_worklistwax3dp->ID,
                'Ordinal' => $IT+1,
                'Product' => $isi['Product'],
                'Qty' => $isi['Qty'], // 
                'WorkOrder' => $isi['IDWorkOrder'], // dari tabel workorderitem kolom IDM
                'WorkOrderOrd' => $isi['Ordinal'], // dari tabel workorderitem kolom ordinal
                'SPKPPIC' => $isi['WorkOrder'], 
            ]);

            $insert_Worklist3dpproductionitem = worklist3dpproductionitem::create([
                'IDM' =>  $generateID2[0]->ID, //auto incerement
                'Ordinal' => $IT+1, // dari tabel form product
                'WorkOrder' => $isi['IDWorkOrder'],// dari tabel form product
                'WaxInjectOrder' => '0', //dari tabel form product
                'Qty' => $isi['Qty'],
            ]);

            $update_wordscheduleitem = workscheduleitem:: where('IDM', $isi['Rph'])->where('LinkID', $isi['IDWorkOrder'])
            ->update(['Operation' => 211]);
        }
        
    
        // dd($insert_Worklist3dpproduction);
        // $lihatdata = FacadesDB::select("SELECT * FROM worklist3dpproduction ORDER BY ID DESC LIMIT 1");
        // dd($lihatdata);
        
        if ($insert_Worklist3dpproduction) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Berhasil!!',
                    'message' => 'SPK DC dari lilinl!!',
                    'ID1' => $insert_worklistwax3dp->ID,
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
        W3D.Qty,
        W.SW wow,
        P.SW,
        P.Description descripproduct,
        C.Description kadar,
        -- SUM(W3D.Qty) TQty,
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
	    END HexColor
    FROM
    worklistwax3dpitem W3D
        JOIN erp.Product P ON W3D.Product = P.ID

        JOIN erp.workorder W ON W3D.WorkOrder = W.ID
        JOIN erp.productcarat C ON W.Carat = C.ID
    WHERE
        W3D.IDM in ($IDSPK3Dp)
        ");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.Lilin.SPKPohonanTestFULLDC.PrintSPK3dp',compact('printspk3dp','datenow','timenow'));
    }

}
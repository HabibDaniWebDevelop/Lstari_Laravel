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
use App\Models\rndnew\worklist3dpproductionitem;

//tes data ke local
// use App\Models\tes_laravel\waxinjectorder12;
// use App\Models\tes_laravel\waxinjectorderitem12;


use \DateTime;
use \DateTimeZone;

use Barryvdh\DomPDF\Facade\Pdf;

class SPKPohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $IDMWaxOrderItem = FacadesDB::connection("erp")
        ->select("SELECT * FROM waxinjectorder WHERE Purpose = 'D' AND Active = 'A' AND Employee != 117 ORDER BY EntryDate DESC");

        $employees = FacadesDB::connection('erp')
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");

        $stickPohon = FacadesDB::connection('erp')
        ->select("SELECT ID, CONCAT(SW,'-', Description) stickpohon FROM treestick");

        $plate = FacadesDB::connection('erp')->select("SELECT * FROM rubberplate WHERE SW LIKE '%D%' ORDER BY ID DESC ");

        $carat = FacadesDB::connection('erp')->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $IDtm = FacadesDB::connection('erp')->select("SELECT ID FROM transferresindc WHERE Active = 'P' ORDER BY ID DESC LIMIT 10");
        
        return view('Produksi.Lilin.SPKPohonan.index', compact('IDMWaxOrderItem', 'employees','stickPohon','plate','carat','IDtm'));
    }

    public function form(){
        $IDMWaxOrderItem = FacadesDB::connection("erp")
        ->select("SELECT * FROM waxinjectorder WHERE Purpose = 'D' AND Employee != 117 ORDER BY ID DESC");

        $employees = FacadesDB::connection('erp')
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Operator'");

        $stickPohon = FacadesDB::connection('erp')
        ->select("SELECT ID, CONCAT(SW,'-', Description) stickpohon FROM treestick");

        $plate = FacadesDB::connection('erp')->select("SELECT * FROM rubberplate WHERE SW LIKE '%D%' ORDER BY ID DESC ");

        $carat = FacadesDB::connection('erp')->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14) ORDER BY Description");

        $IDtm = FacadesDB::connection('erp')->select("SELECT ID FROM transferresindc WHERE Active = 'P' ORDER BY ID DESC LIMIT 10");

        return view('Produksi.Lilin.SPKPohonan.form', compact('IDMWaxOrderItem', 'employees','stickPohon','plate','carat','IDtm'));
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
       
        ");

        $datatabel = FacadesDB::connection('erp')->select("SELECT
        W.*,
        O.SW nospk, 
        P.SW product,
        P.SKU, 
        P.Photo, 
        I.Inject, 
        I.StoneNote, 
        P.ID PID,
        If(F.ProdGroup In (6, 10), IfNull(P.RubberQty, 1) / 2, IfNull(P.RubberQty, 1)) RubberQty -- concat('/image/',J.photo,'.jpg') foto
        FROM WaxInjectOrderItem W
        JOIN WaxInjectOrder V On W.IDM = V.ID
        JOIN WaxOrderItem I On W.WaxOrderOrd = I.Ordinal And W.WaxOrder = I.IDM
        JOIN Product P On I.Product = P.ID
        JOIN WorkOrder O On I.WorkOrder = O.ID 
        JOIN WorkOrderItem J On I.WorkOrder = J.IDM And I.WorkOrderOrd = J.Ordinal
        JOIN Product F On J.Product = F.ID
        WHERE W.IDM = $IDcari
        ORDER BY W.Ordinal");
        
        
    return view('Produksi.Lilin.SPKPohonan.show', compact('getWaxinjectOrder','datatabel'));
       
    }
    public function ListTabel( $carat, $idtm){
      
// dd($idtm);
        $datas = FacadesDB::connection('erp')->select("SELECT
        -- F.Product,
        P.ID Product,
        P.SW,
        P.SKU,
        P.Description,
		PC.ID,
        PC.Description Kadar,
        T.ID TransferResinDC,
	    F.Ordinal TransferResinDCOrd,
        S.Qty,
        WX.IDM WaxOrder,
        W.SW SWWorkOrder,
       WX.Ordinal WaxOrderOrd,
        F.WorkOrder,
        F.WorkOrderOrd,
		S.Level2,
		S.Level3,
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
		Q.TQty,
        DP.IDM idworklist
    FROM
        transferresindc T
        JOIN transferresindcitem F ON T.ID = F.IDM
        JOIN workorderitem WR ON WR.IDM = F.WorkOrder AND WR.Ordinal = F.WorkOrderOrd
        JOIN workorder W ON W.ID = WR.IDM
		JOIN waxorderitem WX ON WX.WorkOrder = WR.IDM AND WX.WorkOrderOrd = WR.Ordinal AND WX.TransferResinDC = F.IDM AND WX.TransferResinDCOrd = F.Ordinal
		JOIN workscheduleitem S ON S.LinkID = WR.IDM AND S.LinkOrd = WR.ordinal AND S.Level2 = WX.IDM AND S.Level3 = WX.Ordinal
        JOIn Product P On P.ID = WX.Product
        JOIN productcarat E ON E.ID = P.VarCarat
        JOIN productcarat PC ON PC.ID = W.Carat
        JOIN rndnew.worklist3dpproductionitem DP ON DP.WorkOrder = F.WorkOrder
				LEFT JOIN (
        SELECT
        WR.IDM,
				TI.IDM idm2,
        SUM( TI.Qty ) TQty 
        FROM
        transferresindcitem TI
		JOIN workorderitem WR ON WR.IDM = TI.WorkOrder AND WR.Ordinal = TI.WorkOrderOrd
        WHERE
        TI.IDM = $idtm
        GROUP BY
        TI.IDM
        ) Q ON F.IDM = Q.idm2
    WHERE
        T.Active = 'P' 
        AND PC.ID = $carat
        AND T.ID = $idtm
        AND P.Description LIKE '%DC%'
	GROUP BY
		WR.IDM,
		WX.Ordinal
            ");
    // $totalQty = $datas[0]->TQty;
            // dd($datas);
   
    $returnHTML = view('Produksi.Lilin.SPKPohonan.add', compact('datas'))->render();
 
    return response()->json( array(
        'success' => true, 
        'html' => $returnHTML, 
        'status' => 'OK', 
        'totalQty' => $datas[0]->TQty));
    }

    public function savespkpohonan(Request $request)
    {
        $checkitems = $request->itemss;
        // dd($checkitems);
        $SWO = $request->itemss[0]['WorkOrder'];
        // dd($SWO);

        $checkSWO = FacadesDB::connection('erp')->select("SELECT LEFT (SW, 1) AS SWO FROM Workorder WHERE ID = $SWO");
        // dd($checkSWO);
        $checkSWO = $checkSWO[0];

        if($checkSWO->SWO == 'O'){
            $makeID = FacadesDB::connection('erp')
            ->select("SELECT CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                DATE_FORMAT(CURDATE(), '%y') as tahun,
                LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) makeID
                FROM waxinjectorder
                Where SWYear = DATE_FORMAT(CURDATE(), '%y') AND SWMonth =  MONTH(CurDate())");
        }else{
            $makeID = FacadesDB::connection('erp')
            ->select("SELECT CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                DATE_FORMAT(CURDATE(), '%y') + 50 as tahun,
                LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                CONCAT(DATE_FORMAT(CURDATE(), '%y') + 50,'',LPad(MONTH(CurDate()), 2, '0' ),LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) makeID
                FROM waxinjectorder
                Where SWYear = DATE_FORMAT(CURDATE(), '%y') +50 AND SWMonth =  MONTH(CurDate())");
        }
        
// dd($makeID);

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
        foreach ($request->itemss as $IT => $value) {
            $i++;
            $update_transferresindc = transferresindc::where('ID', $value['Tfdc'])
            ->update(['Active' => 'X']);
            $update_Worklist3dpproductionitem = worklist3dpproductionitem::where('IDM', $value['idworklist'])
            ->update(['WaxInjectOrder' => $insert_WaxInjectOrder->ID]);
            
            $insert_WaxInjectOrderItem = waxinjectorderitem::create([
                'IDM' => $insert_WaxInjectOrder->ID, //dari ID waxinjectorder
                'Ordinal' => $IT+1, //auto incerement
                'WaxOrder' => $value['WaxOrder'], // darti tabel waxorderitem kolom ID
                'WaxOrderOrd' => $value['WaxOrderOrd'],// darti tabel twaxorderitem kolom Ordinal
                'Qty' => $value['Qty'], //dari tabel transferresindcitem kolom Qty
                'Tok' => $insert_WaxInjectOrder->carat,//dari tabel Waxinjectorder kolom carat
                'StoneCast' => $value['Sc'], // berisi 'Y' jika menggunkan batu 'null' berarti tidak menggunakan batu
                'Tatakan' => $value['WorkOrder'],
                'WorkScheduleID' => $value['Tfdc'], //dari tabel tansferiditem kolom workorder
                'WorkScheduleOrdinal' => $value['Tfdcor'], // dari tabel transferid item kolom workorederord
                'Purpose' => 'A',
            ]);
          
        }
        

        if ($insert_WaxInjectOrderItem) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'ID' => $insert_WaxInjectOrder->ID,
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
        WR.Product,
        P.SW,
        SUBSTRING(P.SKU, 1, 20) SKU,
        P.Description,
        PC.Description Kadar,
        T.IDM ID,
        WJ.StoneCast,
        S.Qty,
        SUBSTRING(H.SW , 1, 20) ProdukJadi,
--         WX.TransferResinDC WaxOrder,
-- 		WX.TransferResinDCOrd WaxOrderOrd,
        WX.WorkOrder,
        WX.WorkOrderOrd,
        W.SW swworkorder
        FROM
		waxinjectorderitem WJ 
				JOIN waxinjectorder J ON J.ID = WJ.IDM
				JOIN Waxorderitem WX ON WJ.WaxOrder = WX.IDM AND WJ.WaxOrderOrd = WX.Ordinal
				JOIN workorderitem WR ON WR.IDM = WJ.Tatakan AND WR.Ordinal = WX.WorkOrderOrd
        JOIn Workscheduleitem S ON S.LinkID = WR.IDM AND S.LinkOrd = WR.Ordinal AND S.Level2 = WX.IDM AND S.Level3 = WX.Ordinal  		
		JOIN workorder W ON WR.IDM = W.ID		
        JOIN product P ON P.ID = WX.Product
        JOIN product H ON H.ID = WR.product
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        LEFT JOIN productcarat PC ON PC.ID = W.Carat
        JOIN transferresindcitem T ON T.WorkOrder = WR.IDM AND T.WorkOrderOrd = WR.Ordinal
        WHERE
        WJ.IDM = $idspkpohon
        AND P.Description LIKE '%DC%'
        
        ");

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
            J.ID = $idspkpohon
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
            J.ID = $idspkpohon
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
            J.ID = $idspkpohon
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
            J.ID = $idspkpohon
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
                WHERE J.ID = '$idspkpohon')  Q
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
                WHERE J.ID = '$idspkpohon')  Q
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
                Where J.ID = '$idspkpohon')  Q
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

        
        $fotokomponen = FacadesDB::connection("erp")->select("SELECT
        P.SW KomponenProuct,
        CONCAT('http://192.168.1.100/image/', P.Photo ,'.jpg') foto
        FROM
        WaxInjectOrderItem W
        JOIN WaxOrderItem I ON W.WaxOrderOrd = I.Ordinal AND W.WaxOrder = I.IDM
        JOIN WorkOrderItem J ON I.WorkOrder = J.IDM AND I.WorkOrderOrd = J.Ordinal
        JOIN Product P ON I.Product = P.ID
        WHERE
        W.IDM = '$idspkpohon'
        ORDER BY
        W.Ordinal");

        $tabelfoto = FacadesDB::connection("erp")->select("SELECT
        O.SW nospk,
        F.SW product,
        F.SW,
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
        W.IDM = $idspkpohon
        GROUP BY
        F.SW
        ORDER BY
        W.Ordinal");

        return view('Produksi.Lilin.SPKPohonan.cetak', compact('tabelinjectkbbatu','fotokomponen','datacetak','datenow','timenow', 'datatabelcetak', 'tabelinjecttkbbatu', 'tabelfoto'));
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

        return view('Produksi\Lilin\SPKPohonan\cetakcatatanplate',compact('printbarcode','printbarcode1'));
    }
}
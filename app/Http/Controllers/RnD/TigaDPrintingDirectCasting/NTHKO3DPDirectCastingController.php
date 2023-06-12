<?php

namespace App\Http\Controllers\RnD\TigaDPrintingDirectCasting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\tes_laravel\resindirectcastingcompletion;
use App\Models\tes_laravel\resindirectcastingcompletionitem;
use App\Models\tes_laravel\worklist3dpproduction;
use App\Models\tes_laravel\resindirectcastingallocation;

class NTHKO3DPDirectCastingController extends Controller
{
    public function index()
    {
        $carilists = FacadesDB::select('SELECT SW, Active FROM `resindirectcastingcompletion` ORDER BY ID DESC LIMIT 20');
        $enve = FacadesDB::select("SELECT ID, SW, Active,  CONCAT('ENV', RIGHT(SW, 7)) AS ENVEP FROM `resindirectcastingallocation` WHERE Active = 'A' ORDER BY ID DESC");
        $spek = FacadesDB::select("SELECT * FROM `resinspecification` WHERE ID = 3");
        $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE Department='15' AND Active='Y' AND SW='Adrianus' LIMIT 1");
        //    dd('tess');
        return view('R&D.3DPrintingDirectCasting.NTHKO3DPDirectCasting.index', compact('carilists','enve', 'spek', 'employees'));
    }

    public function speky($id){
        dd($id);    
        $dataspek = FacadesDB::select("
            SELECT * FROM `resinspecification` WHERE ID = '$id'
        ");
        return response()->json(
            [
                'success' => true,
                'material' => $dataspek['Material'],
                'luas' => $dataspek['SizeEnvelope'],
                'ketebalan' => $dataspek['Thickness']
            ],
        );
    }

    public function Lihat($no, $id)
    {
        //dd($no, $id);

     
            $nthko = FacadesDB::select("SELECT
            Y.ID as ID,
            CASE WHEN E.Description IS NULL THEN '300' ELSE E.Description END AS Description,
            P.SW as codes,
            P.SKU,
            P.Description DD,
            WO.SW WO,
            RCI.QtyOK Qty,
            RD.SW SPKO,
            CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
            RCI.IDM RCIIDM,
            RCI.Ordinal RCIOrdinal,
            RC.SW SWNTHKO,
            RC.Material,
            WO.ID WOO,
            Y.WorkOrderOrd WOI,
            P.ID Product
           
        FROM
            resindirectcastingcompletionitem RCI
            JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
            JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
            JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
            JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
            JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
            JOIN product P ON P.ID = RCI.Product
            JOIN product B ON P.Model = B.ID
            LEFT JOIN drafter3d X ON X.ID = Y.Link3D
            LEFT JOIN shorttext C ON B.Color = C.ID
            LEFT JOIN productcarat E ON E.ID = P.VarCarat
            JOIN workorder WO ON WO.ID = Y.WorkOrder
        WHERE   P.TypeProcess IS NULL  AND RC.SW = '".$id."'
        UNION
        SELECT
        Y.ID as ID,
            CASE WHEN E.Description IS NULL THEN '300' ELSE E.Description END AS Description,
            P.SW as codes,
            P.SKU,
            P.Description DD,
            WO.SW WO,
            RCI.QtyOK Qty,
            RD.SW SPKO,
            CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
            RCI.IDM RCIIDM,
            RCI.Ordinal RCIOrdinal,
            RC.SW SWNTHKO,
            RC.Material,
            WO.ID WOO,
            Y.WorkOrderOrd WOI,
            P.ID Product
           
        FROM
            resindirectcastingcompletionitem RCI
            JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
            JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
            JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
            JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
            JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
            JOIN product P ON P.ID = RCI.Product
            LEFT JOIN drafter3d X ON X.ID = Y.Link3D
            LEFT JOIN productcategory B ON B.ProductID = P.Model
            LEFT JOIN shorttext C ON C.ID = P.ProdGroup
            JOIN productcarat E ON E.ID = P.VarCarat
            JOIN workorder WO ON WO.ID = Y.WorkOrder
           
        WHERE  P.TypeProcess IS NULL   AND RC.SW = '".$id."'
        UNION
        SELECT
            Y.ID as ID,
            CASE WHEN E.Description IS NULL THEN '300' ELSE E.Description END AS Description,
            PP.SW as codes,
            PP.SKU,
            PP.Description DD,
            WO.SW WO,
            RCI.QtyOK Qty,
            RD.SW SPKO,
            CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
            RCI.IDM RCIIDM,
            RCI.Ordinal RCIOrdinal,
            RC.SW SWNTHKO,
            RC.Material,
            WO.ID WOO,
            Y.WorkOrderOrd WOI,
            PP.ID Product

        FROM
            resindirectcastingcompletionitem RCI
            JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
            JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
            JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
            JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
            JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
            JOIN product PP ON PP.ID = RCI.Product
            LEFT JOIN drafter3d X ON X.ID = Y.Link3D
            LEFT JOIN drafter2d W ON X.LinkID = W.ID
            LEFT JOIN mastercomponent P ON P.ID = X.Product 
            LEFT JOIN componentheader B ON B.ID = P.Header
            JOIN productcarat E ON E.ID = PP.VarCarat  
            JOIN workorder WO ON WO.ID = Y.WorkOrder      
            
        WHERE   PP.TypeProcess = 25   AND RC.SW = '".$id."'
        UNION
        SELECT
            Y.ID as ID,
            CASE WHEN E.Description IS NULL THEN '300' ELSE E.Description END AS Description,
            PP.SW as codes,
            PP.SKU,
            PP.Description DD,
            WO.SW WO,
            RCI.QtyOK Qty,
            RD.SW SPKO,
            CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
            RCI.IDM RCIIDM,
            RCI.Ordinal RCIOrdinal,
            RC.SW SWNTHKO,
            RC.Material,
            WO.ID WOO,
            Y.WorkOrderOrd WOI,
            PP.ID Product

        FROM
            resindirectcastingcompletionitem RCI
            JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
            JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
            JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
            JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
            JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
            JOIN product PP ON PP.ID = RCI.Product
            LEFT JOIN drafter3d X ON X.ID = Y.Link3D
            LEFT JOIN drafter2d W ON X.LinkID = W.ID
            LEFT JOIN mastermainan P ON P.ID = X.Product 
            LEFT JOIN mainanheader B ON B.ID = P.Header
            JOIN productcarat E ON E.ID = PP.VarCarat  
            JOIN workorder WO ON WO.ID = Y.WorkOrder      
            
        WHERE  PP.TypeProcess = 26  AND RC.SW = '".$id."'
        UNION
        SELECT
            Y.ID as ID,
            CASE WHEN E.Description IS NULL THEN '300' ELSE E.Description END AS Description,
            PP.SW as codes,
            PP.SKU,
            PP.Description DD,
            WO.SW WO,
            RCI.QtyOK Qty,
            RD.SW SPKO,
            CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
            RCI.IDM RCIIDM,
            RCI.Ordinal RCIOrdinal,
            RC.SW SWNTHKO,
            RC.Material,
            WO.ID WOO,
            Y.WorkOrderOrd WOI,
            PP.ID Product
           

        FROM
            resindirectcastingcompletionitem RCI
            JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
            JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
            JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
            JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
            JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
            JOIN product PP ON PP.ID = RCI.Product
            LEFT JOIN drafter3d X ON X.ID = Y.Link3D
            LEFT JOIN drafter2d W ON X.LinkID = W.ID
            LEFT JOIN employee D ON D.ID = X.Employee
            LEFT JOIN masterkepala P ON P.ID = X.Product 
            LEFT JOIN kepalaheader B ON B.ID = P.Header
            JOIN workorder WO ON WO.ID = Y.WorkOrder
            JOIN productcarat E ON E.ID = PP.VarCarat   
        WHERE   PP.TypeProcess = 27  AND RC.SW = '".$id."'");
    



        $datas = FacadesDB::select("SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        B.SW mm,
        B.Description mname,
        C.SW cm,
        C.Description cname,
        CASE WHEN E.Description IS NULL THEN '300' ELSE E.Description END AS Description,
        P.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/form/form Desain SPB/','',REPLACE(CONCAT(P.Photo,'','.jpg'),' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        P.SKU,
        WO.SW WO,
       YY.Qty Qty,
        RD.SW SPKO,
        CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
        RDI.IDM idm,
        RDI.Ordinal ord,
        P.ID Product
    FROM
        resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product P ON P.ID = YY.Product 
        JOIN product B ON P.Model = B.ID
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN shorttext C ON B.Color = C.ID
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = Y.WorkOrder
    WHERE   P.TypeProcess IS NULL AND RD.ID = '$id'
    UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        B.SW mm,
        B.Description mname,
        C.SW cm,
        C.Description cname,
        E.Description,
        P.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/rnd/Drafter 2D/Original/','',REPLACE(P.Photo,' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        P.SKU,
        WO.SW WO,
       YY.Qty Qty,
        RD.SW SPKO,
        CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
        RDI.IDM idm,
        RDI.Ordinal ord,
        P.ID Product
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product P ON P.ID = YY.Product 
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN productcategory B ON B.ProductID = P.Model
        LEFT JOIN shorttext C ON C.ID = P.ProdGroup
        JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = Y.WorkOrder
    WHERE  P.TypeProcess IS NULL  AND RD.ID = '$id'
        UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        PP.SW mm,
        PP.Description mname,
        B.Type cm,
        B.Type cname,
        E.Description,
        PP.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/rnd/Drafter 2D/Komponen/','',REPLACE(PP.Photo,' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        PP.SKU,
        WO.SW WO,
       YY.Qty Qty,
        RD.SW SPKO,
        CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
        RDI.IDM idm,
        RDI.Ordinal ord,
        PP.ID Product
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastercomponent P ON P.ID = X.Product 
        LEFT JOIN componentheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = Y.WorkOrder       
    WHERE PP.TypeProcess = 25  AND RD.ID = '$id'
        UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        PP.SW mm,
        PP.Description mname,
        B.Type cm,
        B.Type cname,
        E.Description,
        PP.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/rnd/Drafter 2D/Mainan/','',REPLACE(PP.Photo,' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        PP.SKU,
        WO.SW WO,
       YY.Qty Qty,
        RD.SW SPKO,
        CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
        RDI.IDM idm,
        RDI.Ordinal ord,
        PP.ID Product
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastermainan P ON P.ID = X.Product 
        LEFT JOIN mainanheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = Y.WorkOrder       
    WHERE  PP.TypeProcess = 26  AND RD.ID = '$id'
        UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        PP.SW mm,
        PP.Description mname,
        B.Type cm,
        B.Type cname,
        E.Description,
        PP.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/rnd/Drafter 2D/Kepala/','',REPLACE(PP.Photo,' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        PP.SKU,
        WO.SW WO,
       YY.Qty Qty,
        RD.SW SPKO,
        CONCAT('ENV', RIGHT(RD.SW, 7)) ENVE,
        RDI.IDM idm,
        RDI.Ordinal ord,
        PP.ID Product
    FROM
        resindirectcastingallocation RD
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN employee D ON D.ID = X.Employee
        LEFT JOIN masterkepala P ON P.ID = X.Product 
        LEFT JOIN kepalaheader B ON B.ID = P.Header
        JOIN erp.workorder WO ON WO.ID = Y.WorkOrder
        JOIN productcarat E ON E.ID = PP.VarCarat         
         WHERE  PP.TypeProcess = 27  AND RD.ID = '$id'
        ");

        $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE Department='15' AND Active='Y' AND SW='Adrianus' LIMIT 1");

        // dd($datas);
        //lihat
        if ($no == '1') {
            $nthkodc = FacadesDB::select("SELECT A.ID, A.Remarks, A.TransDate, A.Active, B.SW as penerima FROM `resindirectcastingcompletion` A INNER JOIN employee B ON A.Employee=B.ID WHERE A.SW='$id'");
            // dd($tmresinkelilins);
            return view('R&D.3DPrintingDirectCasting.NTHKO3DPDirectCasting.see', compact('nthko', 'employees'));
        }
        //tambah
        if ($no == '2') {
            return view('R&D.3DPrintingDirectCasting.NTHKO3DPDirectCasting.create', compact('no', 'datas', 'employees'));
        }
        //edit
        if ($no == '3') {
            return view('R&D.3DPrintingDirectCasting.NTHKO3DPDirectCasting.edit', compact('no', 'datas'));
        }
    }

    public function saveCompletion(Request $request)
    {
        // dd($request);
        // dd($request->formData1); 
        // DB::beginTransaction();
        //dd($request->request);

        $sw = FacadesDB::select("SELECT CONCAT('FDC','',DATE_FORMAT( CURDATE(), '%y' ),'',LPad(MONTH (CurDate()), 2, 0),'',LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN 0 ELSE MAX( SWOrdinal ) END + 1, 3, '0' )) SW, CASE WHEN MAX( SWOrdinal ) IS NULL THEN 0 ELSE MAX( SWOrdinal ) END  + 1 AS SWOrdinal, RIGHT(YEAR (CurDate()), 2) SWYear, MONTH (CurDate()) SWMonth
        FROM
            resindirectcastingcompletion  
        WHERE YEAR ( TransDate ) = YEAR (CurDate()) AND MONTH ( TransDate ) = MONTH (CurDate())");


        $insert_resincompletion = resindirectcastingcompletion::create([
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => Auth::user()->name,
            'Remarks' => $request->detail['catatan2'],
            'Employee' => $request->detail['employee'],
            'TransDate' => $request->detail['tanggal2'],
            'Active' => 'A',
            'SW' => $sw[0]->SW,
            'SWYear'=> $sw[0]->SWYear,
            'SWMonth'=> $sw[0]->SWMonth,
            'SWOrdinal' => $sw[0]->SWOrdinal,
            'Envelope' => $request->detail['envelope2'],
            'LogFile' => $request->detail['log1'],
            'Tray' => $request->detail['trak'],
            'Thickness' => $request->detail['thickness1'],
            'Material' => $request->detail['material1'],
            'TubeNo' => $request->detail['tabug'],
            'Lamp' => $request->detail['lamp'],
            'Brightness' => $request->detail['brigness1'],
            'Machine' => $request->detail['mesin1'],
            'SizeEnvelope' => $request->detail['dias']
        ]);
//dd($insert_resinallocation);
        $i = 0;
        //dd($request->item);
        foreach ($request->item as $key => $value) {

            $insert_resincompletionitem = resindirectcastingcompletionitem::create([
                'IDM' =>  $insert_resincompletion->id,
                'Ordinal' => $key+1,
                'Product' => $value['product'],
                'QtyOK' => $value['qtygood'],
                'QtyRep' => 0,
                'QtyScrap' => $value['qtynogood'],
                'Result' => 'OK',
                'Defect' => NULL,
                'STLFile' => $value['sku'],
                'LinkID' => $value['idm'],
                'LinkOrd' => $value['ord'],
                'Active' => 'F'
            ]);
        
            DB::table('resindirectcastingallocation')
            ->where('ID',$value['idm'])
            ->update(['Active'=>'P']);
        }

        if ($insert_resincompletion) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'id' => $insert_resincompletion->id
                   
                ],
                201,
            );
        }
    }

    
}
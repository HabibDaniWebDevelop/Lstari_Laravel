<?php

namespace App\Http\Controllers\RnD\TigaDPrintingDirectCasting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// use App\Models\tes_laravel\tmresinkelilinitem;
// use App\Models\tes_laravel\tmresinkelilin;
use App\Models\erp\transferresindc;
use App\Models\erp\transferresindcitem;
use App\Models\rndnew\resindirectcastingcompletionitem;


class TMResinkeLilinController extends Controller
{
    public function index()
    {
        $carilists = FacadesDB::connection('erp')->select("SELECT ID, Active FROM `transferresindc` WHERE Active != 'X' ORDER BY ID DESC LIMIT 20");
        //    dd('tess');
        return view('R&D.3DPrintingDirectCasting.TMResinkeLilin.index', compact('carilists'));
    }

    public function Lihat($no, $id)
    {
        // dd($no, $id);

        if ($no == '1') {
            $filter2 = 'JOIN erp.transferresindcitem TMR ON TMR.LinkID = RCI.IDM AND TMR.LinkOrd = RCI.Ordinal
            JOIN erp.transferresindc TM ON TM.ID = TMR.IDM';
            $filter3 = "TM.Active!='X'";
            $filter = 'AND TMR.IDM IN(';
            $tmresinkelilinitem = FacadesDB::connection('erp')->select("SELECT IDM FROM `transferresindcitem` WHERE IDM = '$id'");
            foreach ($tmresinkelilinitem as $key => $data) {
                if ($key != '0') {
                    $filter .= ',';
                }
                $filter .= "'" . $data->IDM . "'";
            }
            $filter .= ')';
            // dd($filter, $filter2, $filter3);
        }elseif($no == '4'){
            $filter2 = 'JOIN erp.transferresindcitem TMR ON TMR.LinkID = RCI.IDM AND TMR.LinkOrd = RCI.Ordinal
            JOIN erp.transferresindc TM ON TM.ID = TMR.IDM';
            $filter3 = "TMR.Active='A'";
            $filter = 'AND TMR.IDM IN(';
            $tmresinkelilinitem = FacadesDB::connection('erp')->select("SELECT IDM FROM `transferresindcitem` WHERE IDM = '$id'");
            foreach ($tmresinkelilinitem as $key => $data) {
                if ($key != '0') {
                    $filter .= ',';
                }
                $filter .= "'" . $data->IDM . "'";
            }
            $filter .= ')';
        } else {
            $filter = '';
            $filter2 = '';
            $filter3 = "RCI.Active ='F'";
        }
        $datas = FacadesDB::select("SELECT
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
                    P.ID Product,
                    CONCAT(RCI.IDM,'-', RCI.Ordinal) IDX
                
                FROM
                    resindirectcastingcompletionitem RCI
                    JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
                    JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
                    JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
                    JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
                    JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
                    JOIN erp.product P ON P.ID = RCI.Product
                    JOIN erp.product B ON P.Model = B.ID
                    LEFT JOIN drafter3d X ON X.ID = Y.Link3D
                    LEFT JOIN shorttext C ON B.Color = C.ID
                    LEFT JOIN productcarat E ON E.ID = P.VarCarat
                    JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
                    $filter2
                WHERE  $filter3 AND P.TypeProcess IS NULL  $filter
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
                    P.ID Product,
                    CONCAT(RCI.IDM,'-', RCI.Ordinal) IDX
                   
                FROM
                    resindirectcastingcompletionitem RCI
                    JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
                    JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
                    JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
                    JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
                    JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
                 
                    JOIN erp.product P ON P.ID = RCI.Product
                    LEFT JOIN drafter3d X ON X.ID = Y.Link3D
                    LEFT JOIN productcategory B ON B.ProductID = P.Model
                    LEFT JOIN shorttext C ON C.ID = P.ProdGroup
                    JOIN productcarat E ON E.ID = P.VarCarat
                    JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
                    $filter2
                WHERE  $filter3 AND P.TypeProcess IS NULL  $filter
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
                    PP.ID Product,
                    CONCAT(RCI.IDM,'-', RCI.Ordinal) IDX

                FROM
                    resindirectcastingcompletionitem RCI
                    JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
                    JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
                    JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
                    JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
                    JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
                   
                    JOIN erp.product PP ON PP.ID = RCI.Product
                    LEFT JOIN drafter3d X ON X.ID = Y.Link3D
                    LEFT JOIN drafter2d W ON X.LinkID = W.ID
                    LEFT JOIN mastercomponent P ON P.ID = X.Product 
                    LEFT JOIN componentheader B ON B.ID = P.Header
                    JOIN productcarat E ON E.ID = PP.VarCarat  
                    JOIN erp.workorder WO ON WO.ID = YY.WorkOrder      
                    $filter2 
                WHERE $filter3 AND  PP.TypeProcess = 25  $filter
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
                    PP.ID Product,
                    CONCAT(RCI.IDM,'-', RCI.Ordinal) IDX

                FROM
                    resindirectcastingcompletionitem RCI
                    JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
                    JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
                    JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
                    JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
                    JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
                   
                    JOIN erp.product PP ON PP.ID = RCI.Product
                    LEFT JOIN drafter3d X ON X.ID = Y.Link3D
                    LEFT JOIN drafter2d W ON X.LinkID = W.ID
                    LEFT JOIN mastermainan P ON P.ID = X.Product 
                    LEFT JOIN mainanheader B ON B.ID = P.Header
                    JOIN productcarat E ON E.ID = PP.VarCarat  
                    JOIN erp.workorder WO ON WO.ID = YY.WorkOrder      
                    $filter2 
                WHERE  $filter3  AND PP.TypeProcess = 26 $filter
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
                    PP.ID Product,
                    CONCAT(RCI.IDM,'-', RCI.Ordinal) IDX
                   

                FROM
                    resindirectcastingcompletionitem RCI
                    JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
                    JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
                    JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
                    JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
                    JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
                    JOIN erp.product PP ON PP.ID = RCI.Product
                    LEFT JOIN drafter3d X ON X.ID = Y.Link3D
                    LEFT JOIN drafter2d W ON X.LinkID = W.ID
                    LEFT JOIN employee D ON D.ID = X.Employee
                    LEFT JOIN masterkepala P ON P.ID = X.Product 
                    LEFT JOIN kepalaheader B ON B.ID = P.Header
                    JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
                    JOIN productcarat E ON E.ID = PP.VarCarat   
                    $filter2 
                WHERE  $filter3 AND PP.TypeProcess = 27 $filter
        ");

        $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND `Rank`='Staf Admin'");

        // dd($datas);
        //lihat
        if ($no == '1') {
            $tmresinkelilins = FacadesDB::connection('erp')->select("SELECT A.ID, A.Remarks, A.TransDate, A.Active, B.SW as penerima FROM `transferresindc` A INNER JOIN employee B ON A.Employee=B.ID WHERE A.ID='$id'");
            //dd($tmresinkelilins);
            // dd($datas);
            return view('R&D.3DPrintingDirectCasting.TMResinkeLilin.show', compact('datas', 'tmresinkelilins'));
        }
        //tambah
        if ($no == '2') {
            return view('R&D.3DPrintingDirectCasting.TMResinkeLilin.create', compact('no', 'datas', 'employees'));
        }
        //edit
        if ($no == '3') {
            return view('R&D.3DPrintingDirectCasting.TMResinkeLilin.edit', compact('no', 'datas'));
        }

        if ($no == '4') {
            //dd($datas);
            return view('R&D.3DPrintingDirectCasting.TMResinkeLilin.show', compact('datas', 'tmresinkelilins'));
        }
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $tglfull = date('Y-m-d h:i:s');

        $insert_tmresinkelilin = transferresindc::create([
            'UserName' => $username,
            'EntryDate' => $tglfull,
            'Remarks' => $request->detail['note'],
            'Employee' => $request->detail['employe'],
            'TransDate' => $request->detail['tgl'],
            'Active' => 'A'
        ]);

        // dd($request);

        foreach ($request->item as $key => $value) {
            $insert_tmresinkelilinitem = transferresindcitem::create([
                'IDM' =>  $insert_tmresinkelilin->ID,
                'Ordinal' => $key+1,
                'Product' => $value['product'],
                'Qty' => $value['qty'],
                'WorkOrder' => $value['wo'],
                'WorkOrderOrd' => $value['woi'],
                'LinkID' => $value['idm'],
                'LinkOrd' => $value['ord']
            ]);
            
            $updateresindirectcastingcompletionitem = resindirectcastingcompletionitem::where('IDM', $value['idm'])
            ->where('Ordinal', $value['ord'])
            ->update(['Active' => 'P']);
        }


        // dd($request->id, $a);

        if ($insert_tmresinkelilinitem) {
            return response()->json(
                [
                    'success' => true,
                    'idm' => $insert_tmresinkelilin->ID,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                ],
                201,
            );
        }
    }

    public function cetak(Request $request)
    {
        // dd($request->id);
        $id = $request->id;
        $filter = "AND TMR.IDM = '$id'";
        // $filter = 'AND W.Product IN(';
        // $tmresinkelilinitem = FacadesDB::connection('erp')->select("SELECT LinkID FROM `transferresindcitem` WHERE IDM = '$id'");

        // foreach ($tmresinkelilinitem as $key => $data) {
        //     if ($key != '0') {
        //         $filter .= ',';
        //     }
        //     $filter .= "'" . $data->LinkID . "'";
        // }
        // $filter .= ')';

        $datas = FacadesDB::select("SELECT
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
        erp.transferresindcitem TMR
        JOIN resindirectcastingcompletionitem RCI ON RCI.IDM = TMR.LinkID AND RCI.Ordinal = TMR.LinkOrd
        JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
        JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
        JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
        JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
        JOIN product P ON P.ID = RCI.Product
        JOIN product B ON P.Model = B.ID
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN shorttext C ON B.Color = C.ID
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
    WHERE P.TypeProcess IS NULL  $filter
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
        erp.transferresindcitem TMR
        JOIN resindirectcastingcompletionitem RCI ON RCI.IDM = TMR.LinkID AND RCI.Ordinal = TMR.LinkOrd
        JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
        JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
        JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
        JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
        JOIN product P ON P.ID = RCI.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN productcategory B ON B.ProductID = P.Model
        LEFT JOIN shorttext C ON C.ID = P.ProdGroup
        JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
    WHERE P.TypeProcess IS NULL $filter
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
        erp.transferresindcitem TMR
        JOIN resindirectcastingcompletionitem RCI ON RCI.IDM = TMR.LinkID AND RCI.Ordinal = TMR.LinkOrd
        JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
        JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
        JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
        JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
        JOIN product PP ON PP.ID = RCI.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastercomponent P ON P.ID = X.Product 
        LEFT JOIN componentheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder       
    WHERE  PP.TypeProcess = 25 $filter
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
        erp.transferresindcitem TMR
        JOIN resindirectcastingcompletionitem RCI ON RCI.IDM = TMR.LinkID AND RCI.Ordinal = TMR.LinkOrd
        JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
        JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
        JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
        JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
        JOIN product PP ON PP.ID = RCI.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastermainan P ON P.ID = X.Product 
        LEFT JOIN mainanheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder       
    WHERE  PP.TypeProcess = 26 $filter
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
        erp.transferresindcitem TMR
        JOIN resindirectcastingcompletionitem RCI ON RCI.IDM = TMR.LinkID AND RCI.Ordinal = TMR.LinkOrd
        JOIN resindirectcastingcompletion RC ON RC.ID = RCI.IDM
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RCI.LinkID AND RDI.Ordinal = RCI.LinkOrd
        JOIN resindirectcastingallocation RD ON RDI.IDM = RD.ID
        JOIN worklist3dpproductionitem YY ON YY.IDM = RDI.LinkID AND YY.Ordinal = RDI.LinkOrd
        JOIN worklist3dpproduction Y ON Y.ID = YY.IDM 
        JOIN product PP ON PP.ID = RCI.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN employee D ON D.ID = X.Employee
        LEFT JOIN masterkepala P ON P.ID = X.Product 
        LEFT JOIN kepalaheader B ON B.ID = P.Header
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
        JOIN productcarat E ON E.ID = PP.VarCarat    
    WHERE  PP.TypeProcess = 27 $filter
        ");

            $tmresinkelilins = FacadesDB::connection('erp')->select("SELECT
                    A.ID,
                    A.Remarks,
                    A.TransDate,
                    B.Description ke,
                    c.Description dari,
                    A.Employee
                FROM
                    `transferresindc` A
                    INNER JOIN employee B ON A.Employee = B.ID
                    INNER JOIN employee C ON A.UserName = C.SW 
                    WHERE A.ID='$id'");

            return view('R&D.3DPrintingDirectCasting.TMResinkeLilin.cetak', compact('datas', 'tmresinkelilins','id'));
    }
}
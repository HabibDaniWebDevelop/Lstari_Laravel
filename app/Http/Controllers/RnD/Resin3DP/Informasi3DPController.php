<?php

namespace App\Http\Controllers\RnD\TigaDPrintingDirectCasting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Stroage;

use App\Models\tes_laravel\resindirectcastingallocation;
use App\Models\tes_laravel\resindirectcastingallocationitem;
use App\Models\tes_laravel\worklist3dpproduction;




class SPKO3DPDirectCastingController extends Controller
{
    public function index(){
        $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE Department='15' AND Active='Y' AND SW LIKE 'Adrianus%' ORDER BY Description");
        $carilists = FacadesDB::select('SELECT ID, SW FROM `resindirectcastingallocation` ORDER BY ID DESC LIMIT 20');
        return view('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.index', compact('employees', 'carilists'));
    }

    public function download(Request $request, $file){
       return response()->download(rnd($file));
    }

    public function getDirectCastingRequest($no, $id){
    $data = FacadesDB::select("SELECT
        ID,
        isssk,
        Width,
        Length,
        Depth,
        Area,
        SW,
        mm,
        mname,
        cm,
        cname,
        Description,
        codes,
        File3DM,
        wkid,
        foto,
        Status_Permintaan,
        LinkIDs,
        SKU,
        WO,
        Product,
        Qty,
        ID3D,
        Worklist,
        WorklistOrd,
        IDX
        
FROM
(SELECT
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
        P.ID Product,
        YY.Qty Qty,
        X.ID ID3D,
        Y.ID Worklist,
        YY.Ordinal WorklistOrd,
        CONCAT(YY.IDM,'-', YY.Ordinal) IDX

      
    FROM
        worklist3dpproduction Y
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID
        JOIN product P ON P.ID = YY.Product 
        JOIN product B ON P.Model = B.ID
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN shorttext C ON B.Color = C.ID
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
        LEFT JOIN resindirectcastingallocationitem XX ON XX.LinkID = YY.IDM AND XX.LinkOrd = YY.Ordinal      
    WHERE Y.Active = 'A' AND P.TypeProcess IS NULL AND XX.IDM IS NULL
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
        P.ID Product,
        YY.Qty Qty,
        X.ID ID3D,
        Y.ID Worklist,
        YY.Ordinal WorklistOrd,
        CONCAT(YY.IDM,'-', YY.Ordinal) IDX
      
    FROM
    worklist3dpproduction Y
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID
        JOIN product P ON P.ID = YY.Product 
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN productcategory B ON B.ProductID = P.Model
        LEFT JOIN shorttext C ON C.ID = P.ProdGroup
        JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
        LEFT JOIN resindirectcastingallocationitem XX ON XX.LinkID = YY.IDM AND XX.LinkOrd = YY.Ordinal      
    WHERE Y.Active = 'A' AND P.TypeProcess IS NULL AND XX.IDM IS NULL
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
        PP.ID Product,
        YY.Qty Qty,
        X.ID ID3D,
        Y.ID Worklist,
        YY.Ordinal WorklistOrd,
        CONCAT(YY.IDM,'-', YY.Ordinal) IDX
      
    FROM
    worklist3dpproduction Y
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastercomponent P ON P.ID = X.Product 
        LEFT JOIN componentheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder 
        LEFT JOIN resindirectcastingallocationitem XX ON XX.LinkID = YY.IDM AND XX.LinkOrd = YY.Ordinal      
    WHERE Y.Active = 'A'  AND PP.TypeProcess = 25 AND XX.IDM IS NULL
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
        PP.ID Product,
        YY.Qty Qty,
        X.ID ID3D,
        Y.ID Worklist,
        YY.Ordinal WorklistOrd,
        CONCAT(YY.IDM,'-', YY.Ordinal) IDX
        
     
    FROM
        worklist3dpproduction Y
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastermainan P ON P.ID = X.Product 
        LEFT JOIN mainanheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder  
        LEFT JOIN resindirectcastingallocationitem XX ON XX.LinkID = YY.IDM AND XX.LinkOrd = YY.Ordinal         
    WHERE Y.Active = 'A'  AND PP.TypeProcess = 26 AND XX.IDM IS NULL
        UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        P.SW mm,
        P.Description mname,
        B.Type cm,
        B.Type cname,
        E.Description,
        P.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/rnd/Drafter 2D/Kepala/','',REPLACE(PP.Photo,' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        PP.SKU,
        WO.SW WO,
        PP.ID Product,
        YY.Qty Qty,
        X.ID ID3D,
        Y.ID Worklist,
        YY.Ordinal WorklistOrd,
        CONCAT(YY.IDM,'-', YY.Ordinal) IDX
    FROM
        worklist3dpproduction Y
        JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN employee D ON D.ID = X.Employee
        LEFT JOIN masterkepala P ON P.ID = X.Product 
        LEFT JOIN kepalaheader B ON B.ID = P.Header
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
        JOIN productcarat E ON E.ID = PP.VarCarat
        LEFT JOIN resindirectcastingallocationitem XX ON XX.LinkID = YY.IDM AND XX.LinkOrd = YY.Ordinal         
    WHERE Y.Active = 'A' AND PP.TypeProcess = 27 AND XX.IDM IS NULL)hasil
    ORDER BY Status_Permintaan ASC
        ");
        //die(print_r($data));

        $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE Department='15' AND Active='Y' LIMIT 1");
       
        if ($no == '1') {
            $resindirectcastingallocation = FacadesDB::select("SELECT A.ID, A.Remarks, A.TransDate, A.Active, B.SW as penerima FROM `resindirectcastingallocation` A INNER JOIN employee B ON A.Employee=B.ID WHERE A.ID='$id'");
            return view('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.tampilkan', compact('data', 'resindirectcastingallocation'));
        }
        //tambah
        if ($no == '2') {
            return view('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.tampilkan', compact('no', 'data', 'employees'));
        }
        //edit
        if ($no == '3') {
            return view('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.edit', compact('no', 'data'));
        }
    }

    public function saveAllocation(Request $request)
    {
        // dd($request);
        // dd($request->formData1); 
        // DB::beginTransaction();

        $sw = FacadesDB::select("SELECT CONCAT('FKDC','',DATE_FORMAT( CURDATE(), '%y' ),'',LPad(MONTH (CurDate()), 2, 0),'',LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN 0 ELSE MAX( SWOrdinal ) END + 1, 3, '0' )) SW, CASE WHEN MAX( SWOrdinal ) IS NULL THEN 0 ELSE MAX( SWOrdinal ) END  + 1 AS SWOrdinal, RIGHT(YEAR (CurDate()), 2) SWYear, MONTH (CurDate()) SWMonth
        FROM
            resindirectcastingallocation   
        WHERE YEAR ( TransDate ) = YEAR (CurDate()) AND MONTH ( TransDate ) = MONTH (CurDate())");


        $insert_resinallocation = resindirectcastingallocation::create([
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => Auth::user()->name,
            'Remarks' => $request->detail['note'],
            'Employee' => $request->detail['employee'],
            'TransDate' => $request->detail['tgl'],
            'Active' => 'A',
            'SW' => $sw[0]->SW,
            'SWYear'=> $sw[0]->SWYear,
            'SWMonth'=> $sw[0]->SWMonth,
            'SWOrdinal' => $sw[0]->SWOrdinal
        ]);

        $i = 0;
        foreach ($request->item as $key => $value) {
            $insert_resinallocationitem = resindirectcastingallocationitem::create([
                'IDM' =>  $insert_resinallocation->id,
                'Ordinal' => $key+1,
                'Product' => $value['product'],
                'Qty' => $value['qty'],
                'Link3D' => $value['id3d'],
                'LinkID' => $value['worklistid'],
                'LinkOrd' => $value['worklistidord'],
                'Active' => 'A'
            ]);
            // DB::table('worklist3dpproduction')
            // ->where('ID',$value['id'])
            // ->update(['Active'=>'P']);
        }

        // if ($request->id == null) {
        //     DB::rollBack();
        // } else {
        //     DB::commit();
        // }

        // dd($request->id, $a);

        // $data_return = $this->SetReturn(true, ["ID"=>'1',"SW"=>$sw[0]->SW], null);
        // return response()->json($data_return);

        if ($insert_resinallocation) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'id' => $insert_resinallocation->id,
                    'sw' => $sw[0]->SW
                ],
                201,
            );
        }
    }

    public function cetak(Request $request)
    {
        // dd($request->id);
        $id = $request->id;

        $datap = FacadesDB::select("SELECT
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
        YY.Qty Qty
    FROM
        resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product P ON P.ID = YY.Product 
        JOIN product B ON P.Model = B.ID
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN shorttext C ON B.Color = C.ID
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
    WHERE Y.Active = 'A' AND P.TypeProcess IS NULL AND RD.ID = '$id'
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
        YY.Qty Qty
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product P ON P.ID = YY.Product 
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN productcategory B ON B.ProductID = P.Model
        LEFT JOIN shorttext C ON C.ID = P.ProdGroup
        JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
    WHERE Y.Active = 'A' AND P.TypeProcess IS NULL AND RD.ID = '$id'
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
        YY.Qty Qty
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastercomponent P ON P.ID = X.Product 
        LEFT JOIN componentheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder       
    WHERE Y.Active = 'A'  AND PP.TypeProcess = 25 AND RD.ID = '$id'
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
        YY.Qty Qty
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastermainan P ON P.ID = X.Product 
        LEFT JOIN mainanheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder       
    WHERE Y.Active = 'A'  AND PP.TypeProcess = 26 AND RD.ID = '$id'
        UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        P.SW mm,
        P.Description mname,
        B.Type cm,
        B.Type cname,
        E.Description,
        P.SW as codes,
        CONCAT('/Drafter 3D/File Rhino/', X.File3DM) File3DM,
        Y.ID as wkid,
        CONCAT('/rnd/Drafter 2D/Kepala/','',REPLACE(PP.Photo,' ','%20')) foto,
        Y.Status as Status_Permintaan,
        CONCAT(Y.Link3D,'-',Y.Status,'-',Y.ID) as LinkIDs,
        PP.SKU,
       WO.SW WO,
        YY.Qty Qty
    FROM
        resindirectcastingallocation RD
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN employee D ON D.ID = X.Employee
        LEFT JOIN masterkepala P ON P.ID = X.Product 
        LEFT JOIN kepalaheader B ON B.ID = P.Header
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
        JOIN productcarat E ON E.ID = PP.VarCarat         
         WHERE Y.Active = 'A' AND PP.TypeProcess = 27 AND RD.ID = '$id'
        ");

        $spko = FacadesDB::select("SELECT
                A.ID,
                A.Remarks,
                A.TransDate,
                B.Description ke,
                c.Description dari,
                A.Employee,
                A.SW spkoo
            FROM
                `resindirectcastingallocation` A
                INNER JOIN employee B ON A.Employee = B.ID
                INNER JOIN employee C ON A.UserName = C.SW 
                WHERE A.ID='$id'");

            return view('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.cetak', compact('datap', 'spko','id'));
    }

    public function see(Request $request)
    {
        //dd($request->id);
        $id = $request->id;

        $datap = FacadesDB::select("SELECT
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
        P.ID Product,
        X.ID ID3D,
        Y.ID Worklist
    FROM
        resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product P ON P.ID = YY.Product 
        JOIN product B ON P.Model = B.ID
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN shorttext C ON B.Color = C.ID
        LEFT JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
    WHERE Y.Active = 'A' AND P.TypeProcess IS NULL AND RD.ID = '$id'
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
        P.ID Product,
        X.ID ID3D,
        Y.ID Worklist
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product P ON P.ID = YY.Product 
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN productcategory B ON B.ProductID = P.Model
        LEFT JOIN shorttext C ON C.ID = P.ProdGroup
        JOIN productcarat E ON E.ID = P.VarCarat
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
    WHERE Y.Active = 'A' AND P.TypeProcess IS NULL AND RD.ID = '$id'
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
        PP.ID Product,
        X.ID ID3D,
        Y.ID Worklist
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastercomponent P ON P.ID = X.Product 
        LEFT JOIN componentheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder       
    WHERE Y.Active = 'A'  AND PP.TypeProcess = 25 AND RD.ID = '$id'
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
        PP.ID Product,
        X.ID ID3D,
        Y.ID Worklist
    FROM
       	resindirectcastingallocation RD
		JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN mastermainan P ON P.ID = X.Product 
        LEFT JOIN mainanheader B ON B.ID = P.Header
        JOIN productcarat E ON E.ID = PP.VarCarat  
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder       
    WHERE Y.Active = 'A'  AND PP.TypeProcess = 26 AND RD.ID = '$id'
        UNION
    SELECT
        Y.ID as ID,
        X.ID as isssk,
        X.Width,
        X.Length,
        X.Depth,
        X.Area,
        X.SW,
        P.SW mm,
        P.Description mname,
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
        PP.ID Product,
        X.ID ID3D,
        Y.ID Worklist

    FROM
        resindirectcastingallocation RD
        JOIN resindirectcastingallocationitem RDI ON RDI.IDM = RD.ID
        JOIN worklist3dpproduction Y ON Y.ID = RDI.LinkID
        LEFT JOIN worklist3dpproductionitem YY ON YY.IDM = Y.ID AND YY.Ordinal = RDI.LinkOrd
        JOIN product PP ON PP.ID = YY.Product
        LEFT JOIN drafter3d X ON X.ID = Y.Link3D
        LEFT JOIN drafter2d W ON X.LinkID = W.ID
        LEFT JOIN employee D ON D.ID = X.Employee
        LEFT JOIN masterkepala P ON P.ID = X.Product 
        LEFT JOIN kepalaheader B ON B.ID = P.Header
        JOIN erp.workorder WO ON WO.ID = YY.WorkOrder
        JOIN productcarat E ON E.ID = PP.VarCarat         
         WHERE Y.Active = 'A' AND PP.TypeProcess = 27 AND RD.ID = '$id'
        ");
//dd($datap);
        $spko = FacadesDB::select("SELECT
                A.ID,
                A.Remarks,
                A.TransDate,
                B.Description ke,
                c.Description dari,
                A.Employee,
                A.SW spkoo
            FROM
                `resindirectcastingallocation` A
                INNER JOIN employee B ON A.Employee = B.ID
                INNER JOIN employee C ON A.UserName = C.SW 
                WHERE A.ID='$id'");

            return view('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.see', compact('datap', 'spko','id'));
    }

}
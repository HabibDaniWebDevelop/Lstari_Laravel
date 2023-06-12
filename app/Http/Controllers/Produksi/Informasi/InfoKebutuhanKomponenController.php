<?php
namespace App\Http\Controllers\Produksi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;



class InfoKebutuhanKomponenController extends Controller
{
    public function index(){
        return view('Produksi.Informasi.CampurBahan.index');
    }

    public function getFilter(){
        $data = FacadesDB::connection('erp')->select("
        SELECT  
            SW, SWOrdinal,
            CASE WHEN SWOrdinal = 1 THEN 'Januari'
            WHEN  SWOrdinal = 2 THEN 'Februari'
            WHEN  SWOrdinal = 3 THEN 'Maret'
            WHEN  SWOrdinal = 4 THEN 'April'
            WHEN  SWOrdinal = 5 THEN 'Mei'
            WHEN  SWOrdinal = 6 THEN 'Juni'
            WHEN  SWOrdinal = 7 THEN 'Juli'
            WHEN  SWOrdinal = 8 THEN 'Agustus'
            WHEN  SWOrdinal = 9 THEN 'September'
            WHEN  SWOrdinal = 10 THEN 'Oktober'
            WHEN  SWOrdinal = 11 THEN 'November'
            WHEN  SWOrdinal = 12 THEN 'Desember'
            ELSE 'Unknown'
            END AS Bulan
        FROM workperiod 
        WHERE SUBSTRING_INDEX(DateStart, '-', 1) = '".date('Y')."'  AND Type = 'P'
        ");

        $kadar = FacadesDB::connection('erp')->select("
        SELECT ID, Description Kadar FROM `productcarat` A 
        WHERE A.Regular = 'Y' ORDER BY A.ID 
        ");
        //dd($data);
        $returnHTML =  view('Produksi.Informasi.CampurBahan.data', compact('data', 'kadar'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function getSPKProduksi(Request $request){
        $thn = $request->tahun;
        $bln = $request->bln;
        // $kadar = $request->kadar;
        $tgl1 = $request->tgl1;
        $tgl2 = $request->tgl2;
        $jenis = $request->jenis;
        $tipe = $request->tipe;
        if($tipe == 'O'){
            $text = "AND LEFT(A.SWPurpose, 1) = 'O'";
        }else{
            $text = "AND LEFT(A.SWPurpose, 1) <> 'O'";
        }
        if($jenis == 1){
            if(empty($tgl1) && empty($tgl2)){
                $dataspk = FacadesDB::connection('erp')->select("
                SELECT 
                        A.ID, 
                        GROUP_CONCAT(A.ID SEPARATOR ',') SW, 
                        DATE_FORMAT(A.TransDate, '%d/%m/%Y')TransDate, A.SWPurpose, F.Description Kadar, B.Ordinal, 
                        CASE WHEN PP.Produk IS NULL THEN E.SW ELSE PP.Produk END  AS ACC, 
                        SUM(B.Qty) QtyFG,
                        SUM((B.Qty*C.Qty)) QtyKomponen,
                        A.ID, C.Ordinal Urut,
                        CASE WHEN X.Qty IS NULL THEN '0' ELSE X.Qty END AS QtyStock,
                        CASE WHEN X.Weight IS NULL THEN '0' ELSE X.Weight END AS WeightStock,
                        A.Carat KDR,
                        E.ID IDKOM,
                        CASE WHEN PP.Description IS NULL THEN E.Description ELSE PP.Description END AS DesPro,
                        CASE 
                        WHEN X.Qty < SUM((B.Qty*C.Qty)) THEN 'Stok Tidak Mencukupi'
                        WHEN (X.Weight = 0 OR X.Weight IS NULL) THEN 'Tidak Tersedia'
                        ELSE 'Stok Mencukupi' END AS Status 
    
                FROM 
                        workorder A 
                        JOIN workorderitem B ON B.IDM = A.ID 
                        JOIN productaccessories C ON C.IDM = B.Product 
                        JOIN product D ON B.Product = D.ID 
                        JOIN product E ON E.ID = C.Product AND E.ProdGroup <> 126
                        JOIN productcarat F On F.ID = A.Carat
                        LEFT JOIN (SELECT 
                        CASE WHEN SW LIKE '%-%' THEN SUBSTRING_INDEX(SW,'-',1) WHEN SW LIKE '%.%' THEN SUBSTRING_INDEX(SW,'.',1) ELSE '' END AS Model, 
                        SerialNo, CASE WHEN SKU IS NULL THEN SW ELSE SKU END AS Produk, VarCarat, ID, Description, SKU
                        FROM 
                        product 
                        WHERE ProdGroup IN (150, 99)) PP ON PP.Model = CASE WHEN E.SW LIKE '%-%' THEN SUBSTRING_INDEX(E.SW,'-',1) WHEN E.SW LIKE '%.%' THEN SUBSTRING_INDEX(E.SW,'.',1) ELSE '' END
                        AND PP.SerialNo = E.SerialNo 
                        AND PP.VarCarat = A.Carat
                        LEFT JOIN producttrans X ON X.Product = PP.ID AND X.Carat = A.Carat AND X.Location = 22
                        
                WHERE 
                        YEAR(A.TransDate) = ".$thn."
                        AND A.SWPurpose <> 'PCB'
                        AND A.Active = 'A'
                        AND PP.SKU IS NOT NULL 
                        ".$text."
                        GROUP BY E.ID, A.Carat
                        ORDER BY ACC
                ");
            }elseif(!empty($tgl1) && !empty($tgl2)){
                $dataspk = FacadesDB::connection('erp')->select("
                SELECT 
                        A.ID, 
                        GROUP_CONCAT(A.ID SEPARATOR ',') SW, 
                        DATE_FORMAT(A.TransDate, '%d/%m/%Y')TransDate, A.SWPurpose, F.Description Kadar, B.Ordinal, 
                        CASE WHEN PP.Produk IS NULL THEN E.SW ELSE PP.Produk END  AS ACC, 
                        SUM(B.Qty) QtyFG,
                        SUM((B.Qty*C.Qty)) QtyKomponen,
                        A.ID, C.Ordinal Urut,
                        CASE WHEN X.Qty IS NULL THEN '0' ELSE X.Qty END AS QtyStock,
                        CASE WHEN X.Weight IS NULL THEN '0' ELSE X.Weight END AS WeightStock,
                        A.Carat KDR,
                        E.ID IDKOM,
                        CASE WHEN PP.Description IS NULL THEN E.Description ELSE PP.Description END AS DesPro,
                        CASE 
                        WHEN X.Qty < SUM((B.Qty*C.Qty)) THEN 'Stok Tidak Mencukupi'
                        WHEN (X.Weight = 0 OR X.Weight IS NULL) THEN 'Tidak Tersedia'
                        ELSE 'Stok Mencukupi' END AS Status 

                FROM 
                        workorder A 
                        JOIN workorderitem B ON B.IDM = A.ID 
                        JOIN productaccessories C ON C.IDM = B.Product 
                        JOIN product D ON B.Product = D.ID 
                        JOIN product E ON E.ID = C.Product AND E.ProdGroup <> 126
                        JOIN productcarat F On F.ID = A.Carat
                        LEFT JOIN (SELECT 
                        CASE WHEN SW LIKE '%-%' THEN SUBSTRING_INDEX(SW,'-',1) WHEN SW LIKE '%.%' THEN SUBSTRING_INDEX(SW,'.',1) ELSE '' END AS Model, 
                        SerialNo, CASE WHEN SKU IS NULL THEN SW ELSE SKU END AS Produk, VarCarat, ID, Description, SKU
                        FROM 
                        product 
                        WHERE ProdGroup IN (150, 99)) PP ON PP.Model = CASE WHEN E.SW LIKE '%-%' THEN SUBSTRING_INDEX(E.SW,'-',1) WHEN E.SW LIKE '%.%' THEN SUBSTRING_INDEX(E.SW,'.',1) ELSE '' END
                        AND PP.SerialNo = E.SerialNo 
                        AND PP.VarCarat = A.Carat
                        LEFT JOIN producttrans X ON X.Product = PP.ID AND X.Carat = A.Carat AND X.Location = 22
                        
                WHERE 
                        A.TransDate BETWEEN '".$tgl1."' AND '".$tgl2."'
                        AND A.SWPurpose <> 'PCB'
                        AND A.Active = 'A'
                        AND PP.SKU IS NOT NULL 
                        ".$text."
                        GROUP BY E.ID, A.Carat
                        ORDER BY ACC");
            }
        }
        //Info Permintaan Komponen
        if($jenis == 2){
            if(empty($tgl1) && empty($tgl2)){
                $dataspk = FacadesDB::connection('erp')->select("
                SELECT 
                    A.ID, 
                    A.UserName,
                    A.TransDate,
                    A.SW,
                    J.Description Kadar,
                    B.Ordinal,
                    CASE WHEN C.SKU IS NULL THEN C.SW ELSE C.SKU END AS FG,

                    I.SW IDRequest,
                    H.Ordinal UrutRR,
                    DATE_FORMAT(I.TransDate, '%d/%m/%Y') TglRequest,
                    I.UserName,
                    K.Description Area,
                    H.Ordinal,
                    CASE WHEN E.SKU IS NULL THEN E.SW ELSE E.SKU END AS Komponen,
                    H.Qty QtyRequest,
                    H.Weight BeratRequest,
                    CASE WHEN I.`Status` = 1 THEN 'Sudah Ditransfer' WHEN I.`Status` = 0 THEN 'Belum Ditransfer' ELSE 'Batal' END AS StatusTM,

                    TM.ID IDTTM,
                    TMI.Ordinal urutTM,
                    DATE_FORMAT(TM.TransDate, '%d/%m/%Y') tglTM,
                    TMI.Qty QtyTM,
                    TMI.Weight BeratTM

                FROM 
                    workorder A 
                    JOIN workorderitem B ON B.IDM = A.ID 
                    JOIN product C ON C.ID = B.Product
                    JOIN productaccessories D ON D.IDM = C.ID 
                    JOIN transferrmitem F ON F.WorkOrder = A.ID AND F.FG = B.Product 
                    JOIN workscheduleitem G ON G.LinkID = F.IDM AND G.LinkOrd = F.Ordinal
                    JOIN componentrequestitem H ON H.LinkID = G.IDM AND H.LinkOrd = G.Ordinal 
                    JOIN componentrequest I ON I.ID = H.IDM 
                    JOIN product E ON E.ID = H.Product
                    JOIN productcarat J ON J.ID = A.Carat
                    JOIN location K ON K.ID = I.Location
                    LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation = H.IDM AND TMI.LinkOrd = H.Ordinal
                    LEFT JOIN transferrm TM ON TM.ID = TMI.IDM

                WHERE 
                    YEAR(I.TransDate) = ".$thn."
                    AND MONTH(I.TransDate) = ".$bln."
                    AND A.SWPurpose <> 'PCB'
                    GROUP BY H.IDM, H.Ordinal
                    ORDER BY I.TransDate DESC
                ");
            }elseif(!empty($tgl1) && !empty($tgl2)){
                $dataspk = FacadesDB::connection('erp')->select("
                SELECT 
                    A.ID, 
                    A.UserName,
                    A.TransDate,
                    A.SW,
                    J.Description Kadar,
                    B.Ordinal,
                    CASE WHEN C.SKU IS NULL THEN C.SW ELSE C.SKU END AS FG,

                    I.SW IDRequest,
                    H.Ordinal UrutRR,
                    DATE_FORMAT(I.TransDate, '%d/%m/%Y') TglRequest,
                    I.UserName,
                    K.Description Area,
                    H.Ordinal,
                    CASE WHEN E.SKU IS NULL THEN E.SW ELSE E.SKU END AS Komponen,
                    H.Qty QtyRequest,
                    H.Weight BeratRequest,
                    CASE WHEN I.`Status` = 1 THEN 'Sudah Ditransfer' WHEN I.`Status` = 0 THEN 'Belum Ditransfer' ELSE 'Batal' END AS StatusTM,

                    TM.ID IDTTM,
                    TMI.Ordinal urutTM,
                    DATE_FORMAT(TM.TransDate, '%d/%m/%Y') tglTM,
                    TMI.Qty QtyTM,
                    TMI.Weight BeratTM

                FROM 
                    workorder A 
                    JOIN workorderitem B ON B.IDM = A.ID 
                    JOIN product C ON C.ID = B.Product
                    JOIN productaccessories D ON D.IDM = C.ID 
                    JOIN transferrmitem F ON F.WorkOrder = A.ID AND F.FG = B.Product 
                    JOIN workscheduleitem G ON G.LinkID = F.IDM AND G.LinkOrd = F.Ordinal
                    JOIN componentrequestitem H ON H.LinkID = G.IDM AND H.LinkOrd = G.Ordinal 
                    JOIN componentrequest I ON I.ID = H.IDM 
                    JOIN product E ON E.ID = H.Product
                    JOIN productcarat J ON J.ID = A.Carat
                    JOIN location K ON K.ID = I.Location
                    LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation = H.IDM AND TMI.LinkOrd = H.Ordinal
                    LEFT JOIN transferrm TM ON TM.ID = TMI.IDM

                WHERE 
                    I.TransDate BETWEEN '".$tgl1."' AND '".$tgl2."'
                    AND A.SWPurpose <> 'PCB'
                    GROUP BY H.IDM, H.Ordinal
                    ORDER BY I.TransDate DESC");
                    //dd($dataspk);
            }
        }

        if($jenis == 3){
            if(empty($tgl1) && empty($tgl2)){
                $dataspk = FacadesDB::connection('erp')->select("
                SELECT 
                        A.ID, 
                        GROUP_CONCAT(A.ID SEPARATOR ',') SW, 
                        DATE_FORMAT(A.TransDate, '%d/%m/%Y')TransDate, A.SWPurpose, F.Description Kadar, B.Ordinal, 
                        CASE WHEN PP.Produk IS NULL THEN E.SW ELSE PP.Produk END  AS ACC, 
                        SUM(B.Qty) QtyFG,
                        SUM((B.Qty*C.Qty)) QtyKomponen,
                        A.ID, C.Ordinal Urut,
                        CASE WHEN X.Qty IS NULL THEN '0' ELSE X.Qty END AS QtyStock,
                        CASE WHEN X.Weight IS NULL THEN '0' ELSE X.Weight END AS WeightStock,
                        A.Carat KDR,
                        E.ID IDKOM,
                        CASE WHEN PP.Description IS NULL THEN E.Description ELSE PP.Description END AS DesPro,
                        CASE 
                        WHEN X.Qty < SUM((B.Qty*C.Qty)) THEN 'Stok Tidak Mencukupi'
                        WHEN (X.Weight = 0 OR X.Weight IS NULL) THEN 'Tidak Tersedia'
                        ELSE 'Stok Mencukupi' END AS Status 
    
                FROM 
                        workorder A 
                        JOIN workorderitem B ON B.IDM = A.ID 
                        JOIN productaccessories C ON C.IDM = B.Product 
                        JOIN product D ON B.Product = D.ID 
                        JOIN product E ON E.ID = C.Product AND E.ProdGroup <> 126
                        JOIN productcarat F On F.ID = A.Carat
                        LEFT JOIN (SELECT 
                        CASE WHEN SW LIKE '%-%' THEN SUBSTRING_INDEX(SW,'-',1) WHEN SW LIKE '%.%' THEN SUBSTRING_INDEX(SW,'.',1) ELSE '' END AS Model, 
                        SerialNo, CASE WHEN SKU IS NULL THEN SW ELSE SKU END AS Produk, VarCarat, ID, Description, SKU
                        FROM 
                        product 
                        WHERE ProdGroup IN (150, 99)) PP ON PP.Model = CASE WHEN E.SW LIKE '%-%' THEN SUBSTRING_INDEX(E.SW,'-',1) WHEN E.SW LIKE '%.%' THEN SUBSTRING_INDEX(E.SW,'.',1) ELSE '' END
                        AND PP.SerialNo = E.SerialNo 
                        AND PP.VarCarat = A.Carat
                        LEFT JOIN producttrans X ON X.Product = PP.ID AND X.Carat = A.Carat AND X.Location = 22
                        
                WHERE 
                        YEAR(A.TransDate) = ".$thn."
                        AND A.SWPurpose <> 'PCB'
                        AND A.Active = 'A'
                        AND PP.SKU IS  NULL 
                        ".$text."
                        GROUP BY E.ID, A.Carat
                        ORDER BY ACC
                ");
            }elseif(!empty($tgl1) && !empty($tgl2)){
                $dataspk = FacadesDB::connection('erp')->select("
                SELECT 
                        A.ID, 
                        GROUP_CONCAT(A.ID SEPARATOR ',') SW, 
                        DATE_FORMAT(A.TransDate, '%d/%m/%Y')TransDate, A.SWPurpose, F.Description Kadar, B.Ordinal, 
                        CASE WHEN PP.Produk IS NULL THEN E.SW ELSE PP.Produk END  AS ACC, 
                        SUM(B.Qty) QtyFG,
                        SUM((B.Qty*C.Qty)) QtyKomponen,
                        A.ID, C.Ordinal Urut,
                        CASE WHEN X.Qty IS NULL THEN '0' ELSE X.Qty END AS QtyStock,
                        CASE WHEN X.Weight IS NULL THEN '0' ELSE X.Weight END AS WeightStock,
                        A.Carat KDR,
                        E.ID IDKOM,
                        CASE WHEN PP.Description IS NULL THEN E.Description ELSE PP.Description END AS DesPro,
                        CASE 
                        WHEN X.Qty < SUM((B.Qty*C.Qty)) THEN 'Stok Tidak Mencukupi'
                        WHEN (X.Weight = 0 OR X.Weight IS NULL) THEN 'Tidak Tersedia'
                        ELSE 'Stok Mencukupi' END AS Status 

                FROM 
                        workorder A 
                        JOIN workorderitem B ON B.IDM = A.ID 
                        JOIN productaccessories C ON C.IDM = B.Product 
                        JOIN product D ON B.Product = D.ID 
                        JOIN product E ON E.ID = C.Product AND E.ProdGroup <> 126
                        JOIN productcarat F On F.ID = A.Carat
                        LEFT JOIN (SELECT 
                        CASE WHEN SW LIKE '%-%' THEN SUBSTRING_INDEX(SW,'-',1) WHEN SW LIKE '%.%' THEN SUBSTRING_INDEX(SW,'.',1) ELSE '' END AS Model, 
                        SerialNo, CASE WHEN SKU IS NULL THEN SW ELSE SKU END AS Produk, VarCarat, ID, Description, SKU
                        FROM 
                        product 
                        WHERE ProdGroup IN (150, 99)) PP ON PP.Model = CASE WHEN E.SW LIKE '%-%' THEN SUBSTRING_INDEX(E.SW,'-',1) WHEN E.SW LIKE '%.%' THEN SUBSTRING_INDEX(E.SW,'.',1) ELSE '' END
                        AND PP.SerialNo = E.SerialNo 
                        AND PP.VarCarat = A.Carat
                        LEFT JOIN producttrans X ON X.Product = PP.ID AND X.Carat = A.Carat AND X.Location = 22
                        
                WHERE 
                        A.TransDate BETWEEN '".$tgl1."' AND '".$tgl2."'
                        AND A.SWPurpose <> 'PCB'
                        AND A.Active = 'A'
                        AND PP.SKU IS NULL 
                        ".$text."
                        GROUP BY E.ID, A.Carat
                        ORDER BY ACC");
            }
        }

        //dd($dataspk);
        $returnHTML =  view('Produksi.Informasi.CampurBahan.SPKProduksi')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil'=> $dataspk ));
    }

    public function getSPKRouting(Request $request){
        $id = $request->id;
        $product = $request->product;
        $datadetail = FacadesDB::connection('erp')->select("
            SELECT 
                    A.ID, 
                    A.SW, 
                    DATE_FORMAT(A.TransDate, '%d/%m/%Y')TransDate, 
                    A.SWPurpose, 
                    F.Description Kadar, 
                    B.Ordinal, 
                    CASE WHEN D.SKU IS NULL THEN D.SW ELSE D.SKU END AS FGX, 
                    SUM(B.Qty) QtyFG,
                    (SUM(B.Qty)*C.Qty) Qty,
                    C.Qty QtyKom
                    

            FROM 
                    workorder A 
                    JOIN workorderitem B ON B.IDM = A.ID 
                    JOIN productaccessories C ON C.IDM = B.Product 
                    JOIN product D ON B.Product = D.ID 
                    JOIN product E ON E.ID = C.Product AND E.ProdGroup <> 126
                    JOIN productcarat F On F.ID = A.Carat
            WHERE 
                A.ID IN (".$id.")
                AND A.Active = 'A'
                AND E.ID = ".$product."
                GROUP BY D.ID 
                ORDER BY A.TransDate
        ");
        //dd($datadetail);
        $returnHTML =  view('Produksi.Informasi.CampurBahan.Routing')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil2'=> $datadetail ));
        
    }
}

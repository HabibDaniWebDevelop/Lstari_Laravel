<?php
namespace App\Http\Controllers\Produksi\CampurBahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Models\erp\transferrm;
use App\Models\erp\transferrmitem;

class OrderGTCampurBahanController extends Controller
{
    public function index(){
        return view('Produksi.CampurBahan.PermintaanGT.index');
    }

    public function scanMaterial(){
        $returnHTML =  view('Produksi.CampurBahan.PermintaanGT.material')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function getFilter(){
        // $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE  SW IN ('Dhora', 'Herlin') ORDER BY Description");
        $carilists = FacadesDB::connection('erp')->select('SELECT ID FROM `componentorder` WHERE Active = 2 ORDER BY ID DESC LIMIT 20');
        $returnHTML =  view('Produksi.CampurBahan.PermintaanGT.data', compact( 'carilists'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
       
    }

    // public function getJenis(Request $request){
    //     $jenis = $request->jenis;
    //     if($jenis == 'material'){
    //         $produklist = FacadesDB::connection('erp')->select("
    //             SELECT CASE WHEN PlateType = 23 THEN SUBSTRING_INDEX(Description,' ',3) ELSE SUBSTRING_INDEX(Description,' ',2) END AS Produk
    //             FROM `plate` 
    //             GROUP BY PlateType
    //             ORDER BY Produk");
    //     }else{
    //         $produklist = FacadesDB::connection('erp')->select("
    //             SELECT SUBSTRING_INDEX(B.SW, '-', 1) Produk 
    //             FROM  product B
                
    //             WHERE SUBSTRING_INDEX(B.SW,'-',1) IN (
    //             'SVC',
    //             'LBC',
    //             'KCKL',
    //             'KCATKMN'
    //             )
    //             AND B.SerialNo IS NOT NULL
    //             AND RIGHT(B.SKU, 1) NOT IN ('A', 'B')
    //             GROUP BY Produk
    //             ORDER BY B.SW");
    //     }

    //     $returnHTML =  view('Produksi.CampurBahan.PermintaanGT.produk', compact('produklist'))->render();
    //     return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    // }

    public function getProduk(Request $request){
        $kom = $request->komponen;
        //dd($kom);
        if($kom == 'Stick Kawat Patri'){
            $komponenlist = FacadesDB::connection('erp')->select("
            SELECT
                AA.ID IDPlate, A.ID, A.SW, A.Description, B.Description Kadar, B.ID IDKadar
            FROM
                plate AA
                JOIN product A ON A.ID = AA.Product
                JOIN productcarat B ON B.ID = A.VarCarat 
            WHERE
                 SUBSTRING_INDEX(AA.Description, ' ', 3) = '".$kom."' 
            ORDER BY
               B.ID
            ");
        }
        $returnHTML =  view('Produksi.CampurBahan.PermintaanGT.komponen', compact('komponenlist'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function getSPK(Request $request){
        $kom = $request->komponen;
        if($kom == 'Patri'){
            $komponenlist = FacadesDB::connection('erp')->select("
            SELECT 
                    E.SW,
                    E.Description,
                    F.Description Kadar,
                    C.Qty,
                    D.SW WO,
                    F.ID IDKadar,
                    D.ID IDWO,
                    LEFT(D.SWPurpose, 1) LWO,
                    DATE_FORMAT(D.TransDate, '%d/%m/%Y') tglWO,
                    D.UserName,
                    D.Active,
                    C.Qty
            FROM 
            workorder D
            JOIN workorderitem C ON C.IDM = D.ID 
            JOIN product E ON E.ID = C.Product
            JOIN productcarat F ON F.ID = D.Carat
            LEFT JOIN transferrmitem G ON G.WorkOrder = D.ID 
            WHERE G.IDM IS NULL 
            AND D.SWOrdinal Between 70000 And 79999 
            AND D.SWPurpose IN ('ASP', 'OASP')
            /*AND D.Active = 'A'*/
            ");
            $returnHTML =  view('Produksi.CampurBahan.PermintaanGT.patri', compact('komponenlist'))->render();
            return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
    }

    public function getMaterial(Request $request){
        $material = $request->material;
        $idkadar = $request->idkadar;
        $labelwo = $request->labelwo;

        $mat = explode('-',  $material);

        if($labelwo == 'O'){
            $materialdetail = FacadesDB::connection('erp')->select("
            SELECT 
                CONCAT(A.WorkAllocation, '-', A.Freq, '-', B.Ordinal) NTHKO,
                C.SW Produk,
                C.Description DesProd,
                D.Description Kadar,
                B.Weight Berat,
                E.SW WO
            FROM 
            workcompletion A 
            JOIN workcompletionitem B ON B.IDM = A.ID 
            JOIN product C ON C.ID = B.Product
            JOIN productcarat D ON D.ID = B.Carat
            JOIN workorder E ON E.ID = B.WorkOrder
            WHERE 
            A.WorkAllocation = ".$mat[0]."
            AND A.Freq = ".$mat[1]."
            AND B.Ordinal = ".$mat[2]."
            AND B.Carat = ". $idkadar."
            AND LEFT(E.SWPurpose, 1) = 'O'
           
            ");
        }else{
            $materialdetail = FacadesDB::connection('erp')->select("
            SELECT 
                CONCAT(A.WorkAllocation, '-', A.Freq, '-', B.Ordinal) NTHKO,
                C.SW Produk,
                C.Description DesProd,
                D.Description Kadar,
                B.Weight Berat,
                E.SW WO,
                A.WorkAllocation,
                A.Freq,
                B.Ordinal,
                B.Carat,
                B.Product,
                B.Qty 

            FROM 
            workcompletion A 
            JOIN workcompletionitem B ON B.IDM = A.ID 
            JOIN product C ON C.ID = B.Product
            JOIN productcarat D ON D.ID = B.Carat
            JOIN workorder E ON E.ID = B.WorkOrder
            WHERE
            A.WorkAllocation = ".$mat[0]."
            AND A.Freq = ".$mat[1]."
            AND B.Ordinal = ".$mat[2]."
            AND B.Carat = ". $idkadar."
            AND LEFT(E.SWPurpose, 1) <> 'O'
           
            ");
            //dd($materialdetail);
        }
            foreach ($materialdetail as $datas){}
            $id = $datas->WorkAllocation;
            $kadar = $datas->Kadar;
            $deskripsi = $datas->DesProd;
            $produk = $datas->Produk;
            $berat = $datas->Berat;
            $ordinal = $datas->Ordinal;
            $carat = $datas->Carat;
            $idproduk = $datas->Product;
            $freq = $datas->Freq;
            $qty = $datas->Qty;
            $wo = $datas->WO;
        $returnHTML =  view('Produksi.CampurBahan.PermintaanGT.material', compact('materialdetail'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK',  
        'id' =>$id ,
        'kadar' =>$kadar,
        'deskripsi' =>$deskripsi,
        'produk' =>$produk,
        'berat' =>$berat,
        'ordinal' =>$ordinal,
        'carat' =>$carat,
        'idproduk' =>$idproduk ,
        'freq' =>$freq,
        'qty' =>$qty ,
        'wo' =>$wo
        ) );
    }

    public function saveOrder(Request $request){
            //$labelspk = $request->swwo;
            //dd($labelspk);
            
            // if($labelspk == 'O'){
            //     $sw = FacadesDB::connection('erp')->select("
            //             SELECT
            //                 CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                 DATE_FORMAT(CURDATE(), '%y') as tahun,
            //                 LPad(MONTH(CurDate()), 2, '0' ) as bulan,
            //                 CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'22',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //             FROM transferrm
            //             Where SWYear = DATE_FORMAT(CURDATE(), '%y') AND SWMonth =  MONTH(CurDate())
            //     ");
            // }else{
            //     $sw = FacadesDB::connection('erp')->select("
            //             SELECT
            //                 CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                 DATE_FORMAT(CURDATE(), '%y')+50 as tahun,
            //                 LPad(MONTH(CurDate()), 2, '0' ) as bulan,
            //                 CONCAT(DATE_FORMAT(CURDATE(), '%y')+50,'',LPad(MONTH(CurDate()), 2, '0' ),'22',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //             FROM transferrm
            //             Where SWYear = DATE_FORMAT(CURDATE(), '%y')+50 AND SWMonth =  MONTH(CurDate())
            //     ");
            // }

            $sw = "SELECT
                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                        DATE_FORMAT(CURDATE(), '%y')+50 as tahun,
                        LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                        CONCAT(DATE_FORMAT(CURDATE(), '%y')+50,'',LPad(MONTH(CurDate()), 2, '0' ),'22',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                    FROM transferrm
                    Where SWYear = DATE_FORMAT(CURDATE(), '%y')+50 AND SWMonth =  MONTH(CurDate())";
            //dd($sw);

            $data = FacadesDB::connection('erp')->select($sw);
            foreach ($data as $datas){}
            $idtm = $datas->Counter1;
            //dd($idtm);
            $insertTM= transferrm::create([
                'ID' => $datas->Counter1,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => Auth::user()->name,
                'Remarks' => NULL,
                'TransDate' => $request->tgl,
                'Employee' => 149,
                'FromLoc' => '22',
                'ToLoc' => 17,
                'TotalQty' => 0,
                'TotalWeight' => 0,
                'Active' => 'A',
                'SWYear' => $datas->tahun,
                'SWMonth' => $datas->bulan,
                'SWOrdinal' => $datas->ID
            ]);

            for($i=0; $i< 1; $i++){
                // $insertTMI= transferrmitem::create([
                //     'IDM' => $datas->Counter1,
                //     'Ordinal' => 1,
                //     'Product' =>  424372,
                //     'Carat' => 3,
                //     'Qty' => 0,
                //     'Weight' => 327.2,
                //     'WorkOrder' => 191331,
                //     'WorkAllocation' => 7306070125,
                //     'LinkFreq' => 2,
                //     'LinkOrd' => 1
                // ]);
            }
            if($insertTM == TRUE && $insertTMI == TRUE){
                $response = array('status' => 'Sukses', 'id' => $datas->Counter1);		
            }else{
                $response = array('status' => 'Gagal');		
            }
            return response()->json($response);
    }

    public function cetak(Request $request)
    {
        $id = $request->id;
        $printdatas = FacadesDB::connection('erp')->select("
        SELECT 
        A.ID IDOrder,
        D.SW WO,
        D.TransDate tgl,
        F.Description kadar,
        E.SW,
        E.Description Produk,
        C.Qty,
        G.Description Employee,
        H.Description penerima,
        H.ID IDP,
        D.Remarks Remarks,
        D.SWUsed SWUsed
         
        FROM componentorder A 
        JOIN componentorderitem B ON B.IDM = A.ID 
        JOIN workorderitem C ON C.WorkSuggestion = B.IDM AND C.WorkSuggestionOrd = B.Ordinal
        JOIN workorder D ON D.ID = C.IDM 
        JOIN product E ON E.ID = C.Product
        JOIN productcarat F ON F.ID = D.Carat
        JOIN employee G ON G.SW = A.UserName
        CROSS JOIN employee H ON H.SW LIKE 'Novandre%'
        WHERE A.ID = ".$id."
        AND D.SWOrdinal Between 70000 And 79999 
        AND D.SWPurpose = 'ASP'
        ");

        return view('Produksi.PelaporanProduksi.OrderKomponen.cetak', compact('printdatas'));
    }

    public function lihat(Request $request){
        $id = $request->id;
        //dd($id);
        $lihatdatas = FacadesDB::connection('erp')->select("
        SELECT 
        A.ID IDOrder,
        D.SW WO,
        D.TransDate tgl,
        F.Description kadar,
        E.SW,
        E.Description Produk,
        C.Qty,
        G.Description Employee,
        H.Description penerima,
        H.ID IDP,
        D.Remarks Remarks,
        D.SWUsed SWUsed,
        E.ID,
        F.ID IDKadar
         
        FROM componentorder A 
        JOIN componentorderitem B ON B.IDM = A.ID 
        JOIN workorderitem C ON C.WorkSuggestion = B.IDM AND C.WorkSuggestionOrd = B.Ordinal
        JOIN workorder D ON D.ID = C.IDM 
        JOIN product E ON E.ID = C.Product
        JOIN productcarat F ON F.ID = D.Carat
        JOIN employee G ON G.SW = A.UserName
        CROSS JOIN employee H ON H.SW LIKE 'Novandre%'
        WHERE A.ID = $id
        AND D.SWOrdinal Between 70000 And 79999 
        AND D.SWPurpose = 'ASP'
        ");
        //dd($lihatdatas);
        //return view('Produksi.PelaporanProduksi.OrderKomponen.lihat', compact('lihatdatas'));
        $returnHTML =  view('Produksi.PelaporanProduksi.OrderKomponen.lihat', compact('lihatdatas'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );

    }

}

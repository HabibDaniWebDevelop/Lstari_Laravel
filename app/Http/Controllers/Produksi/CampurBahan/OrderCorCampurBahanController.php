<?php
namespace App\Http\Controllers\Produksi\CampurBahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class OrderCorCampurBahanController extends Controller
{
    public function index(){
        return view('Produksi.CampurBahan.PermintaanCor.index');
    }

    public function getFilter(){
        // $employees = FacadesDB::connection('erp')->select("SELECT ID,Description,Department FROM employee WHERE  SW IN ('Dhora', 'Herlin') ORDER BY Description");
        $carilists = FacadesDB::connection('erp')->select('SELECT ID FROM `componentorder` WHERE Active = 2 ORDER BY ID DESC LIMIT 20');
        $returnHTML =  view('Produksi.CampurBahan.PermintaanCor.data', compact( 'carilists'))->render();
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

    //     $returnHTML =  view('Produksi.CampurBahan.PermintaanCor.produk', compact('produklist'))->render();
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
        $returnHTML =  view('Produksi.CampurBahan.PermintaanCor.komponen', compact('komponenlist'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function saveOrder(Request $request){

        $query = "SELECT MAX(ID)+1 AS ID FROM componentorder";
        $dataid = FacadesDB::connection('erp')->select($query);
        foreach ($dataid as $dataids){}

            $insert_componentorder = componentorder::create([
                'ID' => $dataids->ID,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => Auth::user()->name,
                'Remarks' => $request->detail['note'],
                'TransDate' => $request->detail['tgl'],
                'Active' => '2'
            ]);

            //die(print_r($insert_componentorder->id));
            foreach ($request->item as $key => $value) {
                $insert_componentorderitem = componentorderitem::create([
                    'IDM' =>  $dataids->ID,
                    'Ordinal' => $key+1,
                    'Component' => $value['product'],
                    'Qty' => $value['qty']
                ]);

                $query = "SELECT LAST+1 AS ID FROM lastid Where Module = 'WorkOrder' ";
                $data = FacadesDB::connection('erp')->select($query);
                foreach ($data as $datas){}
        
                $query2 = "UPDATE lastid SET LAST = $datas->ID WHERE Module = 'WorkOrder' ";
                $data2 = FacadesDB::connection('erp')->update($query2);

                $sw = FacadesDB::connection('erp')->select("SELECT 
                                DATE_FORMAT(CURDATE(), '%y') as tahun,
                                LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                                LPad(Count(ID) + 1, 5, '70000') as id,
                                CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'',LPad(Count(ID) + 1, 5, '70000')) Counter,
                                CONCAT('ASP','',DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'',LPad(Count(ID) + 1, 5, '70000')) Counter1
                                From workorder
                                Where Year(TransDate) = Year(CurDate()) 
                                And Month(TransDate) = Month(CurDate()) 
                                AND SWOrdinal Between 70000 And 79999");
                
                $insert_componentorder= workorder::create([
                    'ID' => $datas->ID,
                    'EntryDate' => date('Y-m-d H:i:s'),
                    'UserName' => Auth::user()->name,
                    'Remarks' => $request->detail['note'],
                    'TransDate' => $request->detail['tgl'],
                    'Active' => 'A',
                    'Product' => $value['product'],
                    'Carat' => $value['kadar'],
                    'TotalQty' => 1,
                    'TotalType' => 1,
                    'SW' => $sw[0]->Counter1,
                    'SWPurpose' => 'ASP',
                    'SWUsed' => $sw[0]->Counter,
                    'SWYear'=> $sw[0]->tahun,
                    'SWMonth'=> $sw[0]->bulan,
                    'SWOrdinal' => $sw[0]->id,
                    'ModelList' => $value['sw']
                ]);

                $insert_componentorder= workorderitem::create([
                    'IDM' =>  $datas->ID,
                    'Ordinal' =>  $key+1,
                    'Product' => $value['product'],
                    'Qty' => $value['qty'],
                    'WorkSuggestion' =>  $dataids->ID,
                    'WorkSuggestionOrd' => $key+1,
                    'IDMInt' => $datas->ID
                ]);

            }


    
            if ($insert_componentorder) {
                return response()->json(
                    [
                        'success' => true,
                        'title' => 'Register Berhasil !!',
                        'message' => 'Register Berhasil !!',
                        'id' => $dataids->ID,
                        'sw' => $sw[0]->Counter1
                    ],
                    201,
                );
            }
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

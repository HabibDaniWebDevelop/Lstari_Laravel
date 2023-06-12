<?php

namespace App\Http\Controllers\Produksi\PelaporanProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Http\Controllers\Public_Function_Controller;

use \DateTime;
use \DateTimeZone;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\erp\workcompletion;
use App\Models\erp\workcompletionitem;
use App\Models\erp\workallocationresult;

// use App\Models\tes_laravel\workcompletion;
// use App\Models\tes_laravel\workcompletionitem;
// use App\Models\tes_laravel\workallocationresult;

class NTHKOController extends Controller
{

    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller){
        $this->Public_Function = $Public_Function_Controller;
    }

    public function testKoneksi(){ //OK
        $query = "SELECT ID, SW, Description FROM Employee WHERE Active='Y' LIMIT 100 ";
        $data = FacadesDB::connection('erp')->select($query);

        return view('Produksi.PelaporanProduksi.NTHKO.testKoneksi', compact('data'));
    }

    public function index(){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $query = "SELECT 
                    ID, CONCAT(WorkAllocation,'-',Freq) NTHKO 
                FROM 
                    workcompletion 
                WHERE 
                    Location = $location 
                    AND Active <> 'C'
                ORDER BY ID DESC
                -- LIMIT 500
                ";
        
        // Query History
        // $query = "SELECT * From UserHistory
        //             Where UserID = '$UserEntry' 
        //             And Module = 'Work Completion'
        //             Order By Ordinal";


        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.NTHKO.index', compact('data','rowcount'));
    }

    public function lihat(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        $swfreq = $request->swfreq;
        $nthkodata = explode("-", $swfreq);
        $wcwa = $nthkodata[0];
        $wcfreq = $nthkodata[1];

        $dataAll = array(
            'idnthko' => $request->idnthko,
            'datenow' => $datenow
        );

        // Get Data WorkAllocationResult
        // $queryWAR = "SELECT * FROM workallocationresult WHERE SW=$wcwa";
        // $dataWAR = FacadesDB::connection('erp')->select($queryWAR);

        $queryWAR = "SELECT A.*, SUM(B.TargetQty) qtySPKO, SUM(B.Weight) weightSPKO, SUM(C.Qty) qtyNTHKO, SUM(C.Weight) weightNTHKO
                        FROM workallocationresult A 
                        LEFT JOIN workallocation B ON A.SW=B.SW
                        LEFT JOIN workcompletion C ON A.SW=C.WorkAllocation AND C.Active<>'C'
                        WHERE A.SW=$wcwa";
        $dataWAR = FacadesDB::connection('erp')->select($queryWAR);
        // dd($queryWAR);

        // Get ID NTHKO
        $queryGetID = "SELECT * FROM workcompletion WHERE workallocation=$wcwa AND freq=$wcfreq ";
        $getID = FacadesDB::connection('erp')->select($queryGetID);
        foreach ($getID as $getIDs){}      
        $idnthko = $getIDs->ID;

        // Tampil Header NTHKO
        $query_Header = "SELECT C.*, IfNull(G.WorkGroup, E.SW) WorkBy, U.TargetQty, U.Weight TargetWeight,
                        S.Shrink, S.Difference, R.Description Carat, R.ID RID, L.Description LDescription, L.Department,
                        O.Description ODescription, Concat(C.WorkAllocation, '-', C.Freq) Code, U.WaxOrder,
                        E.SW ESW
                    From WorkCompletion C
                        Join Employee E On C.Employee = E.ID
                        Join Location L On C.Location = L.ID
                        Join Operation O On C.Operation = O.ID
                        Join WorkAllocationResult U On C.WorkAllocation = U.SW
                        Left Join WorkShrink S On U.SW = S.Allocation And S.Active <> 'C'
                        Left Join ProductCarat R On U.Carat = R.ID
                        Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup
                                    From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On C.WorkGroup = G.IDM
                    Where C.ID = $idnthko ";
                    // dd($query_Header);
        $header = FacadesDB::connection('erp')->select($query_Header);
        $countHeader = count($header);

        foreach ($header as $datas){}      
        $postingStatus = $datas->Active;
        $tglSPKO = $datas->TransDate;

        // Tampil Item NTHKO
        $query_Item = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                        F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                        O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                        A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                        If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                        X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, If(A.FG Is Null, H.Photo, G.Photo) GPhoto
                    From WorkCompletionItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                    Where A.IDM = $idnthko
                    Order By A.Ordinal ";
                    // dd($query_Item);
        $item = FacadesDB::connection('erp')->select($query_Item);

        // Cek Stok Harian NTHKO
        // $query_cekSH = "SELECT ID FROM Location WHERE ID = $location AND StockDate = (SELECT MAX(TransDate) TransDate FROM WorkDate WHERE TransDate < '$tglSPKO' and holiday = 'N') ";
        // $cekSH = FacadesDB::connection('erp')->select($query_cekSH);
        // $countSH = count($cekSH);

        // if($countSH > 0){
        //     $status_SH = 'Y';
        // }else{
        //     $status_SH = 'N';
        // }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($location, $tglSPKO); //Return True or False

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.lihat', compact('header','item','countHeader','dataAll','status_SH','location','dataWAR'))->render();
        return response()->json( array('html' => $returnHTML, 'postingStatus' => $postingStatus, 'idnthko' => $idnthko) );
    }

    public function lihatNext(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }


        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        $idnthko = $request->idnthko;

        $dataAll = array(
            'idnthko' => $request->idnthko,
            'datenow' => $datenow
        );

        // Get WA
        $queryWA = "SELECT * FROM workcompletion WHERE ID=$idnthko";
        $dataWA = FacadesDB::connection('erp')->select($queryWA);
        foreach ($dataWA as $datasWA){}      
        $wcwa = $datasWA->WorkAllocation;

        // Get Data WorkAllocationResult
        //  $queryWAR = "SELECT * FROM workallocationresult WHERE SW=$wcwa";
        //  $dataWAR = FacadesDB::connection('erp')->select($queryWAR); 

         $queryWAR = "SELECT A.*, SUM(B.TargetQty) qtySPKO, SUM(B.Weight) weightSPKO, SUM(C.Qty) qtyNTHKO, SUM(C.Weight) weightNTHKO
                        FROM workallocationresult A 
                        LEFT JOIN workallocation B ON A.SW=B.SW
                        LEFT JOIN workcompletion C ON A.SW=C.WorkAllocation
                        WHERE A.SW=$wcwa";
        $dataWAR = FacadesDB::connection('erp')->select($queryWAR);

        // Tampil Header NTHKO
        $query_Header = "SELECT C.*, IfNull(G.WorkGroup, E.SW) WorkBy, U.TargetQty, U.Weight TargetWeight,
                        S.Shrink, S.Difference, R.Description Carat, R.ID RID, L.Description LDescription, L.Department,
                        O.Description ODescription, Concat(C.WorkAllocation, '-', C.Freq) Code, U.WaxOrder,
                        E.SW ESW
                    From WorkCompletion C
                        Join Employee E On C.Employee = E.ID
                        Join Location L On C.Location = L.ID
                        Join Operation O On C.Operation = O.ID
                        Join WorkAllocationResult U On C.WorkAllocation = U.SW
                        Left Join WorkShrink S On U.SW = S.Allocation And S.Active <> 'C'
                        Left Join ProductCarat R On U.Carat = R.ID
                        Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup
                                    From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On C.WorkGroup = G.IDM
                    Where C.ID = $idnthko ";
                    // dd($query_Header);
        $header = FacadesDB::connection('erp')->select($query_Header);
        $countHeader = count($header);

        foreach ($header as $datas){}      
        $postingStatus = $datas->Active;
        $tglSPKO = $datas->TransDate;

        // Tampil Item NTHKO
        $query_Item = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                        F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                        O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                        A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                        If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                        X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, If(A.FG Is Null, H.Photo, G.Photo) GPhoto
                    From WorkCompletionItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                    Where A.IDM = $idnthko
                    Order By A.Ordinal ";
                    // dd($query_Item);
        $item = FacadesDB::connection('erp')->select($query_Item);

        // Cek Stok Harian NTHKO
        // $query_cekSH = "SELECT ID FROM Location WHERE ID = $location AND StockDate = (SELECT MAX(TransDate) TransDate FROM WorkDate WHERE TransDate < '$tglSPKO' and holiday = 'N') ";
        // $cekSH = FacadesDB::connection('erp')->select($query_cekSH);
        // $countSH = count($cekSH);

        // if($countSH > 0){
        //     $status_SH = 'Y';
        // }else{
        //     $status_SH = 'N';
        // }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($location, $tglSPKO); //Return True or False

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.lihat', compact('header','item','countHeader','dataAll','status_SH','location','dataWAR'))->render();
        return response()->json( array('html' => $returnHTML, 'postingStatus' => $postingStatus, 'idnthko' => $idnthko) );
    }

    public function baru(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }
        
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        $swspko = $request->id;

        $queryfreq = "SELECT MAX(Freq)+1 Freq FROM workcompletion WHERE WorkAllocation=$swspko";
        $datafreq = FacadesDB::connection('erp')->select($queryfreq);
        foreach($datafreq as $datasfreq){}

        if($datasfreq->Freq == NULL){
            $nthkofreq = 1;
        }else{  
            $nthkofreq = $datasfreq->Freq;
        }

        $query = "SELECT A.Freq, A.TransDate, I.Ordinal, P.Description Product, R.Description Carat, I.Qty, I.Weight, A.Active,
                        I.StoneLoss, I.QtyLossStone, O.SW WorkOrder, F.SW FinishGood, T.Description FinishCarat, A.Purpose
                From WorkAllocation A
                    Join WorkAllocationItem I On A.ID = I.IDM
                    Join Product P On I.Product = P.ID
                    Left Join ProductCarat R On I.Carat = R.ID
                    Left Join WorkOrder O On I.WorkOrder = O.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                Where A.SW = $swspko And A.Active <> 'C'
                Order By A.Freq, I.Ordinal ";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT A.ID, A.SW, A.TransDate, L.Description Location, E.Description Employee, A.Carat,
                    Sum(A.TargetQty) TargetQty, Sum(A.Weight) Weight, A.Location LID, A.Employee EID, A.Freq,
                    O.Description Operation, A.Operation OID, O.Product, C.SW CSW, C.ID CID, A.WorkGroup, L.Department
                From WorkAllocation A
                    Join Location L On A.Location = L.ID
                    Join Employee E On A.Employee = E.ID
                    Join Operation O On A.Operation = O.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                Where A.SW In (Select Distinct(SW) ID From WorkAllocation) And A.Active = 'P'
                And A.SW = $swspko
                ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $dataAll = array(
            'swspko' => $swspko,
            'datenow' => $datenow,
            'location' => $location
        );

        // Cek Jika SPKO Sudah Disusutkan
        // $querycek = "SELECT SW From WorkAllocationResult WHERE SW = $swspko AND ShrinkDate IS NOT NULL";
        // $datacek = FacadesDB::connection('erp')->select($querycek);
        // $rowcount = count($datacek);

        // Cek Jika SPKO Sudah Diposting
        $querycek = "SELECT SW From WorkAllocation WHERE SW = $swspko AND Active='P' ";
        $datacek = FacadesDB::connection('erp')->select($querycek);
        $rowcount = count($datacek);

        if($rowcount == 0){
            return response()->json( array('status' => 'SudahSusut') );
        }else{
            $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.baruView', compact('data','data2','dataAll','nthkofreq'))->render();
            return response()->json( array('html' => $returnHTML, 'status' => 'BelumSusut') );
        }
    }

    public function ubah(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        $idnthko = $request->id;

        $query = "SELECT C.*, IfNull(G.WorkGroup, E.SW) WorkBy, U.TargetQty, U.Weight TargetWeight,
                        S.Shrink, S.Difference, R.Description Carat, R.ID RID, L.Description LDescription, L.Department,
                        O.Description ODescription, Concat(C.WorkAllocation, '-', C.Freq) Code, U.WaxOrder
                    From WorkCompletion C
                        Join Employee E On C.Employee = E.ID
                        Join Location L On C.Location = L.ID
                        Join Operation O On C.Operation = O.ID
                        Join WorkAllocationResult U On C.WorkAllocation = U.SW
                        Left Join WorkShrink S On U.SW = S.Allocation And S.Active <> 'C'
                        Left Join ProductCarat R On U.Carat = R.ID
                        Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup
                                    From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On C.WorkGroup = G.IDM
                    Where C.ID = $idnthko ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        foreach ($data as $datas){}      
        $postingStatus = $datas->Active;
        $tglSPKO = $datas->TransDate;

        $query2 = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                        F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                        O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                        A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                        If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                        X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model
                    From WorkCompletionItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                    Where A.IDM = $idnthko
                    Order By A.Ordinal ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $dataAll = array(
            'idnthko' => $idnthko,
            'datenow' => $datenow,
            'location' => $location
        );

        // // brgSiapList
        // $queryBrgSiap = "SELECT * FROM PRODUCT WHERE TYPE IN ('S','R','F') AND USECARAT IN ('Y','N') AND STOCKUNIT<>0 ";
        // $brgSiapList = FacadesDB::connection('erp')->select($queryBrgSiap);

        // Cek Stok Harian NTHKO
        // $query_cekSH = "SELECT ID FROM Location WHERE ID = $location AND StockDate = (SELECT MAX(TransDate) TransDate FROM WorkDate WHERE TransDate < '$tglSPKO' and holiday = 'N' )";
        // $cekSH = FacadesDB::connection('erp')->select($query_cekSH);
        // $countSH = count($cekSH);

        // if($countSH > 0){
        //     $status_SH = 'Y';
        // }else{
        //     $status_SH = 'N';
        // }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($location, $tglSPKO); //Return True or False

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.ubahView', compact('data','data2','dataAll','status_SH'))->render();
        return response()->json( array('html' => $returnHTML, 'postingStatus' => $postingStatus) );
    }

    public function cariSPK(Request $request){ //OK
        $sw = $request->sw;
        $carat = $request->carat;

        if($sw == 'Stock' || $sw == 'stock' || $sw == 'STOCK'){
            $sw = 'Stock';
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW = '$sw' AND A.ACTIVE='A' ";
        }else if($sw == 'OStock' || $sw == 'ostock' || $sw == 'OSTOCK'){
            $sw = 'OStock';
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW = '$sw' AND A.ACTIVE='A' ";
        }else{
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW LIKE '%$sw%' AND A.ACTIVE='A' AND A.CARAT=$carat ";
        }
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){

            foreach ($data as $datas){}
            $NoSPK = $datas->SW;
            $WorkOrder = $datas->ID;
            $TotalQty = $datas->TotalQty;
            $ProductName = $datas->ProductName;

            $queryCarat = "SELECT * FROM productcarat WHERE ID=$carat ";
            $data2 = FacadesDB::connection('erp')->select($queryCarat);
            foreach ($data2 as $datas2){}
            $CaratID = $datas2->ID;
            $CaratName = $datas2->Description;

            // $ID = $datas->ID;
            // $SWPuspose = $datas->SWPURPOSE;

            // $arrList = array('CBK','CBP','CBR','CBC','CBD');
            // if(in_array($SWPuspose, $arrList)){
            //     // Tampil SPK CB
            //     $querySPK = "SELECT ID, SW FROM WORKORDER WHERE SWPURPOSE IN ('CBK','CBP','CBR','CBC','CBD') AND ACTIVE='A' AND ID=$ID ";
            //     $dataSPK = FacadesDB::connection('erp')->select($querySPK);
            // }else{
            //     // Tampil SPK PPIC
            //     $querySPK = "SELECT ID, SW FROM WORKORDER WHERE SWPURPOSE NOT IN ('CBK','CBP','CBR','CBC','CBD') AND ACTIVE='A' AND ID=$ID ";
            //     $dataSPK = FacadesDB::connection('erp')->select($querySPK);
            // }
    
            // foreach ($dataSPK as $datas_SPK){}
            // $NoSPK = $datas_SPK->SW;
            // $WorkOrder = $datas_SPK->ID;

            $dataReturn = array('NoSPK' => $NoSPK, 'WorkOrder' => $WorkOrder, 'TotalQty' => $TotalQty, 'ProductName' => $ProductName, 'Carat' => $CaratID, 'Kadar' => $CaratName, 'rowcount' => $rowcount);

        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }

    public function cariProduct(Request $request){ //OK

        $location = session('location');

        if($location == NULL){
            $location = 12;
        }

        $sw = $request->sw;
        $carat = $request->carat;
        $woid = $request->woid;

        // if($woid == NULL || $woid == ''){
        //     $json_return = array('message' => 'Harap Isi Kolom NoSPK!');
        //     return response()->json($json_return,404);
        // }

        // if($woid <> NULL || $woid <> ''){
        //     $dataWO = FacadesDB::connection('erp')->select("SELECT A.SWPurpose, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK FROM workorder A WHERE A.ID=$woid");
        //     foreach ($dataWO as $datasWO){}
        //     $woPurpose = $datasWO->SWPurpose;
        //     $charSPK = $datasWO->CharSPK;
        // }

        

  
        
        $dataCarat = FacadesDB::connection('erp')->select("SELECT * FROM productcarat WHERE ID=$carat");
        foreach ($dataCarat as $datasCarat){}
        $caratID = $datasCarat->ID;
        $caratName = $datasCarat->Description;
     
        $query = "SELECT * FROM PRODUCT WHERE TYPE IN ('S','R','F') AND USECARAT IN ('Y','N') AND STOCKUNIT<>0 AND SW = '$sw' ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){
            foreach ($data as $datas){}
            
            $Product = $datas->ID;
            $ProductLabel = $datas->Description;
            $UseCarat = $datas->UseCarat;

            // if($Product == 251 || $Product == 2231){ // Ktk / Ktk Cor

            //     $SW = '';
            //     // if($location == 7){
            //     //     if($woPurpose == 'OCBP'){
            //     //         $SW = 'OStock';
            //     //         $SWID = 818;
            //     //         $jmlSPK = 0;
            //     //         $produkSPK = 'ATK';
            //     //     }else if($woPurpose == 'CBP'){
            //     //         $SW = 'Stock';
            //     //         $SWID = 0;
            //     //         $jmlSPK = 0;
            //     //         $produkSPK = 'ATK';
            //     //     }
            //     // }
            //     // if($location == 17 && $Product == 251){ // && $Product == 251
            //     //     if($woPurpose == 'OCBK'){
            //     //         $SW = 'OStock';
            //     //         $SWID = 818;
            //     //         $jmlSPK = 0;
            //     //         $produkSPK = 'ATK';
            //     //     }else if($woPurpose == 'CBK'){
            //     //         $SW = 'Stock';
            //     //         $SWID = 0;
            //     //         $jmlSPK = 0;
            //     //         $produkSPK = 'ATK';
            //     //     }
            //     // }
            //     // if(($location <> 7 || $location <> 17) && $Product == 251){
            //     //     if($charSPK == 0){
            //     //         $SW = 'OStock';
            //     //         $SWID = 818;
            //     //         $jmlSPK = 0;
            //     //         $produkSPK = 'ATK';
            //     //     }else{
            //     //         $SW = 'Stock';
            //     //         $SWID = 0;
            //     //         $jmlSPK = 0;
            //     //         $produkSPK = 'ATK';
            //     //     }
            //     // }

            //     if($SW != ''){
            //         $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose, 'WO' => $SW, 'WOID' => $SWID, 'jmlSPK' => $jmlSPK, 'produkSPK' => $produkSPK);
            //     }else{
            //         $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose);
            //     }

            // }else if($Product == 7215){ // Serbuk
            //     if($location == 49){
            //         $SW = '';
            //         // if($charSPK == 0){
            //         //     $SW = 'OStock';
            //         //     $SWID = 818;
            //         //     $jmlSPK = 0;
            //         //     $produkSPK = 'ATK';
            //         // }else{
            //         //     $SW = 'Stock';
            //         //     $SWID = 0;
            //         //     $jmlSPK = 0;
            //         //     $produkSPK = 'ATK';
            //         // }

            //         if($SW != ''){
            //             $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose, 'WO' => $SW, 'WOID' => $SWID, 'jmlSPK' => $jmlSPK, 'produkSPK' => $produkSPK);
            //         }else{
            //             $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose);
            //         }
            //         // $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose, 'WO' => $SW, 'WOID' => $SWID, 'jmlSPK' => $jmlSPK, 'produkSPK' => $produkSPK);
            //     }else{
            //         $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose);
            //     }

               

            // }else{
            //     $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName, 'Location' => $location, 'woPurpose' => $woPurpose);
            // }
    
            $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName);
            return response()->json($dataReturn,200);

        }else{
            $dataReturn = array('rowcount' => $rowcount);
            return response()->json($dataReturn,200);
        }



    }

    public function nthkoList(){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $query = "SELECT A.ID, CONCAT(A.WorkAllocation,'-',A.Freq) NTHKO
                    FROM workcompletion A
                    WHERE A.Location = $location AND Active <> 'C' 
                    ORDER BY A.TransDate DESC, A.ID DESC
                    LIMIT 100";
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.nthkoList', compact('data'))->render();
        return response()->json( array('html' => $returnHTML) );
    }

    public function barcodeUnit(Request $request){ //OK

        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $SWSPKO = $request->swspko;
        $NTHKO = $request->barcodeunit;
        $CaratID = $request->kadar;
        $nthkoArr = explode("-", $NTHKO);
        $WA = $nthkoArr[0];
        $Freq = $nthkoArr[1];
        $Ord = $nthkoArr[2];

        // Tampil BrgSiap
        // $queryBrgSiap = "SELECT ID, Description FROM PRODUCT WHERE DESCRIPTION LIKE 'Brg Siap%' OR DESCRIPTION LIKE 'Rusak%' OR DESCRIPTION LIKE 'Reparasi%' OR DESCRIPTION LIKE 'Selesai%' ";
        // $brgSiapList = FacadesDB::connection('erp')->select($queryBrgSiap);

        // // Tampil SPK PPIC
        // $querySPKPPIC = "SELECT ID, SW FROM WORKORDER WHERE SWPURPOSE NOT IN ('CBK','CBP','CBR','CBC','CBD') AND ACTIVE='A' ";
        // $dataSPKPPIC = FacadesDB::connection('erp')->select($querySPKPPIC);

        // // Tampil SPK CB
        // $querySPKCB = "SELECT ID, SW FROM WORKORDER WHERE SWPURPOSE IN ('CBK','CBP','CBR','CBC','CBD') AND ACTIVE='A' ";
        // $dataSPKCB = FacadesDB::connection('erp')->select($querySPKCB);

        // // Tampil SPK ALL
        // $querySPK = "SELECT ID, SW FROM WORKORDER WHERE ACTIVE='A' ";
        // $dataSPK = FacadesDB::connection('erp')->select($querySPK);

        // Tampil DataList
        $query = "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription, O.ID OID, 
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.SW
                    From WorkAllocationItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                        LEFT JOIN WorkAllocation WA ON A.IDM=WA.ID
                    Where WA.SW = $WA AND WA.Freq = $Freq AND A.Ordinal = $Ord
                    Order By A.Ordinal ";
        $data = FacadesDB::connection('erp')->select($query);
        $row = count($data);

        foreach ($data as $datas){}

        // dd($datas);

        if($row == 0){
            $goto = array('status' => 'Kosong');	
        }else{
            // Data
            // $Product = $datas->Product;
            // $Carat = $datas->Carat;
            // $Qty = $datas->Qty;
            // $Weight = $datas->Weight;
            // $WorkOrder = $datas->WorkOrder;
            // $FG = $datas->FG;
            // $Part = $datas->FG;
            $Product = ((isset($datas->Product)) ? $datas->Product : '');
            $Carat = ((isset($datas->Carat)) ? $datas->Carat : '');
            $Qty = ((isset($datas->Qty)) ? $datas->Qty : '');
            $Weight = ((isset($datas->Weight)) ? $datas->Weight : '');
            $WorkOrder = ((isset($datas->WorkOrder)) ? $datas->WorkOrder : '');
            $FG = ((isset($datas->FG)) ? $datas->FG : '');
            $Part = ((isset($datas->Part)) ? $datas->Part : '');

            // View
            // $NoSPK = $datas->OSW;
            // $ProdukSPK = $datas->FDescription;
            // $JmlSPK = $datas->QtyOrder;
            // $Kadar = $datas->CSW;
            // $BrgSiap = $datas->PDescription;
            // $StoneLoss = $datas->StoneLoss;
            // $QtyLossStone = $datas->QtyLossStone;
            // $BarcodeNote = $datas->BarcodeNote;
            // $Note = $datas->Note;
            // $RubberPlate = $datas->RubberPlate;
            // $ProductDetail = $datas->GDescription;
            // $SPKOID = $datas->IDM;
            // $SPKOUrut = $datas->Ordinal;
            // $TreeID = $datas->TreeID;
            // $TreeOrd = $datas->TreeOrd;
            // $BatchNo = $datas->BatchNo;
            // $ProductPhoto = $datas->ProductPhoto;
            $NoSPK = ((isset($datas->OSW)) ? $datas->OSW : '');
            $ProdukSPK = ((isset($datas->FDescription)) ? $datas->FDescription : '');
            $JmlSPK = ((isset($datas->QtyOrder)) ? $datas->QtyOrder : '');
            $Kadar = ((isset($datas->CSW)) ? $datas->CSW : '');
            $BrgSiap = ((isset($datas->PDescription)) ? $datas->PDescription : '');
            $StoneLoss = ((isset($datas->StoneLoss)) ? $datas->StoneLoss : '');
            $QtyLossStone = ((isset($datas->QtyLossStone)) ? $datas->QtyLossStone : '');
            $BarcodeNote = ((isset($datas->BarcodeNote)) ? $datas->BarcodeNote : '');
            $Note = ((isset($datas->Note)) ? $datas->Note : '');
            $RubberPlate = ((isset($datas->RubberPlate)) ? $datas->RubberPlate : '');
            $ProductDetail = ((isset($datas->GDescription)) ? $datas->GDescription : '');
            $SPKOID = ((isset($datas->IDM)) ? $datas->IDM : '');
            $SPKOUrut = ((isset($datas->Ordinal)) ? $datas->Ordinal : '');
            $TreeID = ((isset($datas->TreeID)) ? $datas->TreeID : '');
            $TreeOrd = ((isset($datas->TreeOrd)) ? $datas->TreeOrd : '');
            $BatchNo = ((isset($datas->BatchNo)) ? $datas->BatchNo : '');
            $GPhoto = ((isset($datas->GPhoto)) ? $datas->GPhoto : '');
            $OOrd = ((isset($datas->WorkOrderOrd)) ? $datas->WorkOrderOrd : '');

            $PrevProcess = $datas->PrevProcess;
            $PrevOrd = $datas->PrevOrd;

            $goto = array('NoSPK' => $NoSPK, 'ProdukSPK' => $ProdukSPK, 'JmlSPK' => $JmlSPK, 'Kadar' => $Kadar, 'Qty' => $Qty, 'Weight' => $Weight,
                            'StoneLoss' => $StoneLoss, 'QtyLossStone' => $QtyLossStone, 'BarcodeNote' => $BarcodeNote, 'Note' => $Note, 'RubberPlate' => $RubberPlate, 
                            'ProductDetail' => $ProductDetail, 'SPKOID' => $SPKOID, 'SPKOUrut' => $SPKOUrut, 'TreeID' => $TreeID, 'TreeOrd' => $TreeOrd, 'BatchNo' => $BatchNo,
                            'WorkOrder' => $WorkOrder, 'OOrd' => $OOrd, 'Product' => $Product, 'Carat' => $Carat, 'FG' => $FG, 'Part' => $Part, 'GPhoto' => $GPhoto,
                            'PrevProcess' => $PrevProcess, 'PrevOrd' => $PrevOrd, 'BrgSiap' => $BrgSiap, 'CaratID' => $CaratID
                        );
        }
        return response()->json($goto);
 
    }

    public function simpan(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        // Form1
        $Tgl = $request->tgl;
        $Emp = $request->emp;
        $SWSPKO = $request->swspko;
        $FreqNTHKO = $request->nthkofreq;
        $LocationSPKO = $request->bagian;
        $Operation = $request->proses;
        $QtyNTHKO = $request->qtynthko;
        $WeightNTHKO = $request->weightnthko;
        $QtySPKO = $request->qtyspko;
        $WeightSPKO = $request->weightspko;

        // Form2
        $WorkOrder = $request->WorkOrder;
        $jmlItem = count($WorkOrder);
        $Product = $request->Product;
        $Carat = $request->Carat;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $RepairQty = $request->RepairQty;
        $RepairWeight = $request->RepairWeight;
        $ScrapQty = $request->ScrapQty;
        $ScrapWeight = $request->ScrapWeight;
        $BarcodeNote = $request->BarcodeNote;
        $Note = $request->Note;
        $LinkID = $request->LinkID;
        $LinkOrd = $request->LinkOrd;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $Part = $request->Part;
        $FG = $request->FG;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $BatchNo = $request->BatchNo;
        $OOrd = $request->OOrd;

        // Check Product with OK, Rep, or SS
        $jmlProduct = count($Product);
        $reparasi = array(254, 99, 98, 2403);
        $rusak = array(255, 2561, 2572);

        for($i=0;$i<$jmlProduct;$i++){
            if(in_array($Product[$i], $reparasi)){

                if( ($RepairQty[$i] == '' || $RepairWeight[$i] == '') || ($Qty[$i] != '' && $Qty[$i] != 0) || ($Weight[$i] != '' && $Weight[$i] != 0) || ($ScrapQty[$i] != '' && $ScrapQty[$i] != 0) || ($ScrapWeight[$i] != '' && $ScrapWeight[$i] != 0) ){
                    $json_return = array(
                        'status' => 'rep', 
                        'message' => 'Barang Rep hanya boleh di kolom Rep !'
                    );
                    return response()->json($json_return,200);
                }

            }else if(in_array($Product[$i], $rusak)){

                if( ($ScrapQty[$i] == '' || $ScrapWeight[$i] == '') || ($Qty[$i] != '' && $Qty[$i] != 0) || ($Weight[$i] != '' && $Weight[$i] != 0) || ($RepairQty[$i] != '' && $RepairQty[$i] != 0) || ($RepairWeight[$i] != '' && $RepairWeight[$i] != 0) ){
                    $json_return = array(
                        'status' => 'ss',
                        'message' => 'Barang SS, SSTK, SSTP hanya boleh di kolom SS !');
                    return response()->json($json_return,200);
                }

            }else{

                // if( ($Qty[$i] == '' || $Weight[$i] == '') || ($ScrapQty[$i] != '' && $ScrapQty[$i] != 0) || ($ScrapWeight[$i] != '' && $ScrapWeight[$i] != 0) || ($RepairQty[$i] != '' && $RepairQty[$i] != 0) || ($RepairWeight[$i] != '' && $RepairWeight[$i] != 0) ){
                if( ($Qty[$i] == '' || $Weight[$i] == '') ){
                    $json_return = array(
                        'status' => 'ok',
                        'message' => 'Barang OK hanya boleh di kolom OK !');
                    return response()->json($json_return,200);
                }

            }
        }

        // LAST ID (WorkCompletion)
        $wcID = "SELECT LAST+1 AS ID FROM lastid Where Module = 'WorkCompletion' ";
        $wcData = FacadesDB::connection('erp')->select($wcID);

        foreach ($wcData as $wcDatas){}
        $nthkoLastID = $wcDatas->ID;

        $wcUpdatekan = "UPDATE lastid SET LAST = $nthkoLastID WHERE Module = 'WorkCompletion' ";
        $wcUpdate = FacadesDB::connection('erp')->update($wcUpdatekan);

        FacadesDB::connection('erp')->beginTransaction();
        try {
    
            // Insert WorkCompletion Raw
            $insertWC = "INSERT INTO workcompletion (ID, EntryDate, UserName, TransDate, Employee, WorkAllocation, Freq, Location, Operation, Qty, Weight, Active, Remarks) 
                            VALUES ($nthkoLastID, now(), '$UserEntry', '$Tgl', $Emp, $SWSPKO, $FreqNTHKO, $LocationSPKO, $Operation, 0, 0, 'A', 'Laravel') ";    
            $goInsertWC = FacadesDB::connection('erp')->insert($insertWC);

            // Insert WorkCompletion Eloquent
            // $goInsertWC= workcompletion::create([
            //     'ID' => $nthkoLastID,
            //     'EntryDate' => date('Y-m-d H:i:s'),
            //     'UserName' => Auth::user()->name,
            //     'Remarks' => 'Laravel',
            //     'TransDate' => $Tgl,
            //     'Employee' => $Emp,
            //     'WorkAllocation' => $SWSPKO,
            //     'Freq' => $FreqNTHKO,
            //     'Location' => $LocationSPKO,
            //     'Operation' => $Operation,
            //     'Qty' => 0,
            //     'Weight' => 0,
            //     'Active' => 'A'
            // ]);

            // Insert WorkCompletionItem
            $no=1;
            for($i=0; $i<$jmlItem; $i++){

                $WorkOrderOK = ((isset($WorkOrder[$i])) ? $WorkOrder[$i] : 'NULL');
                $ProductOK = ((isset($Product[$i])) ? $Product[$i] : 'NULL');
                $CaratOK = ((isset($Carat[$i])) ? $Carat[$i] : 'NULL');
                $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
                $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
                $RepairQtyOK = ((isset($RepairQty[$i])) ? $RepairQty[$i] : 0);
                $RepairWeightOK = ((isset($RepairWeight[$i])) ? $RepairWeight[$i] : 0);
                $ScrapQtyOK = ((isset($ScrapQty[$i])) ? $ScrapQty[$i] : 0);
                $ScrapWeightOK = ((isset($ScrapWeight[$i])) ? $ScrapWeight[$i] : 0);
                $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".addslashes($BarcodeNote[$i])."'" : 'NULL');
                $NoteOK = ((isset($Note[$i])) ? "'".addslashes($Note[$i])."'" : 'NULL');
                $LinkIDOK = ((isset($LinkID[$i])) ? $LinkID[$i] : 'NULL');
                $LinkOrdOK = ((isset($LinkOrd[$i])) ? $LinkOrd[$i] : 'NULL');
                $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
                $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
                $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
                $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
                $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
                $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
                $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');
                $OOrdOK = ((isset($OOrd[$i])) ? $OOrd[$i] : 'NULL');

                // Insert WorkCompletionItem Raw
                $insertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, WorkOrder, WorkOrderOrd, LinkID, LinkOrd, TreeID, TreeOrd, Part, FG, StoneLoss, QtyLossStone, BatchNo, BarcodeNote, Note) 
                                VALUES ($nthkoLastID, $no, $ProductOK, $CaratOK, $QtyOK, $WeightOK, $RepairQtyOK, $RepairWeightOK, $ScrapQtyOK, $ScrapWeightOK, $WorkOrderOK, $OOrdOK, $LinkIDOK, $LinkOrdOK, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, $StoneLossOK, $QtyLossStoneOK, $BatchNoOK, $BarcodeNoteOK, $NoteOK) ";
                $goInsertWCI = FacadesDB::connection('erp')->insert($insertWCI);

                // Insert WorkCompletionItem Eloquent
                // $goInsertWCI= workcompletionitem::create([
                //     'IDM' => $nthkoLastID,
                //     'Ordinal' => $no,
                //     'Product' => $ProductOK,
                //     'Carat' => $CaratOK,
                //     'Qty' => $QtyOK,
                //     'Weight' => $WeightOK,
                //     'RepairQty' => $RepairQtyOK,
                //     'RepairWeight' => $RepairWeightOK,
                //     'ScrapQty' => $ScrapQtyOK,
                //     'ScrapWeight' => $ScrapWeightOK,
                //     'WorkOrder' => $WorkOrderOK,
                //     'WorkOrderOrd' => $OOrdOK,
                //     'LinkID' => $LinkIDOK,
                //     'LinkOrd' => $LinkOrdOK,
                //     'TreeID' => $TreeIDOK,
                //     'TreeOrd' => $TreeOrdOK,
                //     'Part' => $PartOK,
                //     'FG' => $FGOK,
                //     'StoneLoss' => $StoneLossOK,
                //     'QtyLossStone' => $QtyLossStoneOK,
                //     'BatchNo' => $BatchNoOK,
                //     'BarcodeNote' => $BarcodeNoteOK,
                //     'Note' => $NoteOK
                // ]);
            
                $no++;
            }

            if($goInsertWCI == TRUE){
                // Get SUM Qty and Weight
                $queryGetWC = "SELECT SUM(Qty+RepairQty+ScrapQty) Qty, SUM(Weight+RepairWeight+ScrapWeight) Weight FROM workcompletionitem WHERE IDM=$nthkoLastID ";
                $getWC = FacadesDB::connection('erp')->select($queryGetWC);
                foreach($getWC as $getWCs){}

                // Update WorkCompletion
                $updateWC = "UPDATE workcompletion SET Qty=$getWCs->Qty, Weight=$getWCs->Weight WHERE ID=$nthkoLastID ";    
                $goUpdateWC = FacadesDB::connection('erp')->update($updateWC);
            }

            FacadesDB::connection('erp')->commit();
            $json_return = array(
                'success' => true,
                'idnthko' => $nthkoLastID
            );
            return response()->json($json_return,200);

        
            // if($goInsertWC == TRUE && $goInsertWCI == TRUE){
            //     $goto = array('success' => true, 'idnthko' => $nthkoLastID);
            // }else{
            //     $goto = array('success' => false);
            // }
            // return response()->json($goto);

            
        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'status' => 'Failed',
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }

    }

    public function update(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }
   
        // Form1
        $IDNTHKO = $request->idnthko;
        $Tgl = $request->tgl;
        $Emp = $request->emp;
        $SWSPKO = $request->swspko;
        $FreqNTHKO = $request->nthkofreq;
        $LocationSPKO = $request->bagian;
        $Operation = $request->proses;
        $QtyNTHKO = $request->qtynthko;
        $WeightNTHKO = $request->weightnthko;
        $QtySPKO = $request->qtyspko;
        $WeightSPKO = $request->weightspko;

        // Form
        $WorkOrder = $request->WorkOrder;
        $jmlItem = count($WorkOrder);
        $Product = $request->Product;
        $Carat = $request->Carat;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $RepairQty = $request->RepairQty;
        $RepairWeight = $request->RepairWeight;
        $ScrapQty = $request->ScrapQty;
        $ScrapWeight = $request->ScrapWeight;
        $BarcodeNote = $request->BarcodeNote;
        $Note = $request->Note;
        $LinkID = $request->LinkID;
        $LinkOrd = $request->LinkOrd;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $Part = $request->Part;
        $FG = $request->FG;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $BatchNo = $request->BatchNo;
        $OOrd = $request->OOrd;

        // Check Product with OK, Rep, or SS
        $jmlProduct = count($Product);
        $reparasi = array(254, 99, 98, 2403);
        $rusak = array(255, 2561, 2572);

        for($i=0;$i<$jmlProduct;$i++){
            if(in_array($Product[$i], $reparasi)){

                if( ($RepairQty[$i] == '' || $RepairWeight[$i] == '') || ($Qty[$i] != '' && $Qty[$i] != 0) || ($Weight[$i] != '' && $Weight[$i] != 0) || ($ScrapQty[$i] != '' && $ScrapQty[$i] != 0) || ($ScrapWeight[$i] != '' && $ScrapWeight[$i] != 0) ){
                    $json_return = array(
                        'status' => 'rep', 
                        'message' => 'Barang Rep hanya boleh di kolom Rep !'
                    );
                    return response()->json($json_return,200);
                }

            }else if(in_array($Product[$i], $rusak)){

                if( ($ScrapQty[$i] == '' || $ScrapWeight[$i] == '') || ($Qty[$i] != '' && $Qty[$i] != 0) || ($Weight[$i] != '' && $Weight[$i] != 0) || ($RepairQty[$i] != '' && $RepairQty[$i] != 0) || ($RepairWeight[$i] != '' && $RepairWeight[$i] != 0) ){
                    $json_return = array(
                        'status' => 'ss',
                        'message' => 'Barang SS, SSTK, SSTP hanya boleh di kolom SS !');
                    return response()->json($json_return,200);
                }

            }else{

                // if( ($Qty[$i] == '' || $Weight[$i] == '') || ($ScrapQty[$i] != '' && $ScrapQty[$i] != 0) || ($ScrapWeight[$i] != '' && $ScrapWeight[$i] != 0) || ($RepairQty[$i] != '' && $RepairQty[$i] != 0) || ($RepairWeight[$i] != '' && $RepairWeight[$i] != 0) ){
                if( ($Qty[$i] == '' || $Weight[$i] == '') ){
                    $json_return = array(
                        'status' => 'ok',
                        'message' => 'Barang OK hanya boleh di kolom OK !');
                    return response()->json($json_return,200);
                }

            }
        }

        FacadesDB::connection('erp')->beginTransaction();
        try {
        
            // Delete WorkCompletionItem
            $QueryDeleteWCI = "DELETE FROM workcompletionitem WHERE IDM = $IDNTHKO ";
            $deleteWCI = FacadesDB::connection('erp')->delete($QueryDeleteWCI);

            // Insert WorkCompletionItem
            $no=1;
            for($i=0; $i < $jmlItem; $i++){

                $WorkOrderOK = ((isset($WorkOrder[$i])) ? $WorkOrder[$i] : 'NULL');
                $ProductOK = ((isset($Product[$i])) ? $Product[$i] : 'NULL');
                $CaratOK = ((isset($Carat[$i])) ? $Carat[$i] : 'NULL');
                $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
                $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
                $RepairQtyOK = ((isset($RepairQty[$i])) ? $RepairQty[$i] : 0);
                $RepairWeightOK = ((isset($RepairWeight[$i])) ? $RepairWeight[$i] : 0);
                $ScrapQtyOK = ((isset($ScrapQty[$i])) ? $ScrapQty[$i] : 0);
                $ScrapWeightOK = ((isset($ScrapWeight[$i])) ? $ScrapWeight[$i] : 0);
                $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".addslashes($BarcodeNote[$i])."'" : 'NULL');
                $NoteOK = ((isset($Note[$i])) ? "'".addslashes($Note[$i])."'" : 'NULL');
                $LinkIDOK = ((isset($LinkID[$i])) ? $LinkID[$i] : 'NULL');
                $LinkOrdOK = ((isset($LinkOrd[$i])) ? $LinkOrd[$i] : 'NULL');
                $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
                $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
                $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
                $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
                $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
                $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
                $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');
                $OOrdOK = ((isset($OOrd[$i])) ? $OOrd[$i] : 'NULL');

                // Insert WorkCompletionItem Raw
                $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, WorkOrder, WorkOrderOrd, LinkID, LinkOrd, TreeID, TreeOrd, Part, FG, StoneLoss, QtyLossStone, BatchNo, BarcodeNote, Note)
                                VALUES ($IDNTHKO, $no, $ProductOK, $CaratOK, $QtyOK, $WeightOK, $RepairQtyOK, $RepairWeightOK, $ScrapQtyOK, $ScrapWeightOK, $WorkOrderOK, $OOrdOK, $LinkIDOK, $LinkOrdOK, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, $StoneLossOK, $QtyLossStoneOK, $BatchNoOK, $BarcodeNoteOK, $NoteOK) ";
                $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);

                // Insert WorkCompletionItem Eloquent
                // $goInsertWCI= workcompletionitem::create([
                //     'IDM' => $IDNTHKO,
                //     'Ordinal' => $no,
                //     'Product' => $ProductOK,
                //     'Carat' => $CaratOK,
                //     'Qty' => $QtyOK,
                //     'Weight' => $WeightOK,
                //     'RepairQty' => $RepairQtyOK,
                //     'RepairWeight' => $RepairWeightOK,
                //     'ScrapQty' => $ScrapQtyOK,
                //     'ScrapWeight' => $ScrapWeightOK,
                //     'WorkOrder' => $WorkOrderOK,
                //     'WorkOrderOrd' => $OOrdOK,
                //     'LinkID' => $LinkIDOK,
                //     'LinkOrd' => $LinkOrdOK,
                //     'TreeID' => $TreeIDOK,
                //     'TreeOrd' => $TreeOrdOK,
                //     'Part' => $PartOK,
                //     'FG' => $FGOK,
                //     'StoneLoss' => $StoneLossOK,
                //     'QtyLossStone' => $QtyLossStoneOK,
                //     'BatchNo' => $BatchNoOK,
                //     'BarcodeNote' => $BarcodeNoteOK,
                //     'Note' => $NoteOK
                // ]);

                $no++;
            }

            if($insertWCI == TRUE){
                // Get Sum Qty and Weight
                $queryGetWC = "SELECT SUM(Qty+RepairQty+ScrapQty) Qty, SUM(Weight+RepairWeight+ScrapWeight) Weight FROM workcompletionitem WHERE IDM=$IDNTHKO ";
                $getWC = FacadesDB::connection('erp')->select($queryGetWC);
                foreach($getWC as $getWCs){}

                // Update WorkCompletion
                $queryUpdateWC= "UPDATE workcompletion SET UserName = '$UserEntry', TransDate = '$Tgl', Qty = $getWCs->Qty, Weight = $getWCs->Weight, Remarks = 'Update Laravel' WHERE ID = $IDNTHKO";
                $updateWC = FacadesDB::connection('erp')->update($queryUpdateWC);
            }

            FacadesDB::connection('erp')->commit();
                $json_return = array(
                    'success' => true,
                    'idnthko' => $IDNTHKO
                );
            return response()->json($json_return,200);
        
            // if($insertWCI == TRUE && $updateWC == TRUE){
            //     $goto = array('success' => true, 'idnthko' => $IDNTHKO);
            // }else{
            //     $goto = array('success' => false);
            // }
            // return response()->json($goto);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'status' => 'Failed',
                'message' => 'Update Error !'
            );
            return response()->json($json_return,500);
        }
    }

    public function insTransferFG($idwc,$username){
        // Check SPK 'O' AND 'Non-O'
        $dataWCI = FacadesDB::connection('erp')->select("SELECT * FROM workcompletionitem WHERE IDM=$idwc");
        $rowWCI = count($dataWCI);

        $arrChar = array();
        $arrTFGID = array();
        $arrTFGOrd = array();

        foreach ($dataWCI as $datasWCI) {
            if($datasWCI->WorkOrder != NULL){
                $queryCharSPK = "SELECT A.SW, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK
                                FROM workorder A
                                WHERE A.ID=$datasWCI->WorkOrder ";
                $dataCharSPK = FacadesDB::connection('erp')->select($queryCharSPK);
                foreach($dataCharSPK as $datasCharSPK){}

                array_push($arrChar,$datasCharSPK->CharSPK);
            }else{
                array_push($arrChar,1);
            }
        }

        $jmlItem = array_sum($arrChar);

        if($jmlItem == 0){ 
            $cekspk = 0; // SPK 'O'
        }else{ 
            $cekspk = 1; // SPK 'Non-O'
        }

        // Check WCItem and Group By 'Jenis_SPK, Jenis_Stock, Carat, Model, WorkOrder'
        $queryCek = "SELECT Result.*, CONCAT(Weight,'(',Qty,')') Calc FROM (
                        SELECT IF(LEFT(D.SWPurpose,1)='O',0,1) Jenis_SPK, IF(D.SWPurpose IN ('STO','DSTO','OSTO','STA','DSTA','OSTA','STP','DSTP','OSTP'),0,1) Jenis_Stock, 
                            D.SWPurpose, A.ID, B.FG, IF(D.SWPurpose='PCB',C.Model,D.Product) Model, B.Carat, B.WorkOrder, SUM(B.Qty) Qty, FORMAT(SUM(B.Weight),2) Weight
                        FROM workcompletion A
                            LEFT JOIN workcompletionitem B ON A.ID=B.IDM
                            LEFT JOIN product C ON B.FG=C.ID
                            LEFT JOIN workorder D ON B.WorkOrder=D.ID
                            LEFT JOIN product E ON C.Model=E.ID
                        WHERE A.ID=$idwc
                            AND B.Product=256
                        GROUP BY B.Carat, IF(D.SWPurpose='PCB',C.Model,D.Product), B.WorkOrder
                    ) Result
                    GROUP BY Jenis_SPK, Jenis_Stock, Carat, Model, WorkOrder
                    ";
        $dataCek = FacadesDB::connection('erp')->select($queryCek);

        if(count($dataCek) > 0){

            // Looping Through the Results Above, then Insert/Update to "transferfg" and "transferfgitem"
            foreach($dataCek as $datasCek){

                // Check "transferfg" Header
                $queryCekFG = "SELECT * FROM (
                                SELECT IF(LEFT(C.SWPurpose,1)='O',0,1) Jenis_SPK, IF(C.SWPurpose IN ('STO','DSTO','OSTO','STA','DSTA','OSTA','STP','DSTP','OSTP'),0,1) Jenis_Stock, A.*, B.*, C.SW
                                FROM transferfg A
                                LEFT JOIN transferfgitem B ON A.ID=B.IDM
                                LEFT JOIN workorder C ON B.WorkOrder=C.ID
                                WHERE A.Active='E'
                            ) Result
                            WHERE Jenis_SPK=$datasCek->Jenis_SPK
                                AND Jenis_Stock=$datasCek->Jenis_Stock
                                AND Carat=$datasCek->Carat
                            GROUP BY A.ID
                            ORDER BY A.ID DESC
                            LIMIT 1
                            ";
                $dataCekFG = FacadesDB::connection('erp')->select($queryCekFG);
                $rowcount = count($dataCekFG);

                if($rowcount == 0){

                    // Create New SW CURDATE
                    if($cekspk == 0){
                        $queryID = "SELECT
                                    CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                    DATE_FORMAT(CURDATE(), '%y') as tahun,
                                    LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                                    CONCAT(DATE_FORMAT(CURDATE(), '%y'),LPad(MONTH(CurDate()), 2, '0' ),LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1,
                                    CURDATE() tglNow
                                FROM transferfg
                                Where SWYear = DATE_FORMAT(CURDATE(), '%y') AND SWMonth =  MONTH(CurDate())";
                    }else{
                        $queryID = "SELECT
                                    CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                    DATE_FORMAT(CURDATE(), '%y')+50 as tahun,
                                    LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                                    CONCAT(DATE_FORMAT(CURDATE(), '%y')+50,LPad(MONTH(CurDate()), 2, '0' ),LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1,
                                    CURDATE() tglNow
                                FROM transferfg
                                Where SWYear = DATE_FORMAT(CURDATE(), '%y')+50 AND SWMonth =  MONTH(CurDate())";
                    }
                    $dataID = FacadesDB::connection('erp')->select($queryID);
                    foreach ($dataID as $datasID){}
                    $swTFG = $datasID->Counter1;
                    $ordTFG = $datasID->ID;
                    $tglNow = $datasID->tglNow;
                    $tahun = $datasID->tahun;

                    if($datasCek->SWPurpose == 'PCB'){
                        $tfgEmployee = 1656;
                    }else{
                        if($datasCek->Carat == 1){
                            $tfgEmployee = 1240;
                        }else if($datasCek->Carat == 3){
                            $tfgEmployee = 1147;
                        }else if($datasCek->Carat == 4){
                            $tfgEmployee = 1194;
                        }else if($datasCek->Carat == 5 || $datasCek->Carat == 12){
                            $tfgEmployee = 1225;
                        }else{
                            $tfgEmployee = 1838;
                        }
                    }
                    
                    $queryInsTFG = "INSERT INTO transferfg (ID, EntryDate, UserName, Remarks, TransDate, Purpose, Employee, Total, Active, SWYear, SWMonth, SWOrdinal) 
                                    VALUES ('$swTFG', now(), '$username', 'Laravel', '$tglNow', 'F', '$tfgEmployee', '0', 'E', '$tahun', MONTH('$tglNow'), '$ordTFG')";
                    $insTFG = FacadesDB::connection('erp')->insert($queryInsTFG);

                    $queryInsTFGItem = "INSERT INTO transferfgitem (IDM, Ordinal, Product, Carat, Qty, Weight, WorkOrder, Note) 
                                        VALUES ('$swTFG', '1', '$datasCek->Model', '$datasCek->Carat', '$datasCek->Qty', '$datasCek->Weight', '$datasCek->WorkOrder', 'Laravel')";
                    $insTFGItem = FacadesDB::connection('erp')->insert($queryInsTFGItem);

                    $updFGTotal = FacadesDB::connection('erp')->update("UPDATE transferfg SET Total=(SELECT SUM(Weight) Weight FROM transferfgitem WHERE IDM='$swTFG') WHERE ID='$swTFG' ");

                    array_push($arrTFGID,$swTFG);
                    array_push($arrTFGOrd,1);

                }else{

                    foreach($dataCekFG as $datasCekFG){}
                    $idTFG = $datasCekFG->ID;

                    // Check "transferfgitem"
                    $queryTFGItem = "SELECT * 
                                    FROM transferfg A
                                        LEFT JOIN transferfgitem B ON A.ID=B.IDM
                                    WHERE 
                                        A.Active='E'
                                        AND B.IDM=$idTFG
                                        AND B.Carat=$datasCek->Carat
                                        AND B.Product=$datasCek->Model
                                        AND B.WorkOrder=$datasCek->WorkOrder
                                    ";
                    $tfgItem = FacadesDB::connection('erp')->select($queryTFGItem);
                    $rowcountTFGItem = count($tfgItem);

                    if($rowcountTFGItem == 0){
                        $dataOrd = FacadesDB::connection('erp')->select("SELECT MAX(Ordinal)+1 MaxOrdinal FROM transferfgitem WHERE IDM=$idTFG");
                        foreach($dataOrd as $datasOrd){}

                        $queryInsTFGItem = "INSERT INTO transferfgitem (IDM, Ordinal, Product, Carat, Qty, Weight, WorkOrder, Note) 
                                            VALUES ('$idTFG', '$datasOrd->MaxOrdinal', '$datasCek->Model', '$datasCek->Carat', '$datasCek->Qty', '$datasCek->Weight', '$datasCek->WorkOrder', 'Laravel')";
                        $insTFGItem = FacadesDB::connection('erp')->insert($queryInsTFGItem);

                        array_push($arrTFGID,$idTFG);
                        array_push($arrTFGOrd,$datasOrd->MaxOrdinal);

                    }else{
                        $tfgItem = FacadesDB::connection('erp')->select("SELECT A.*, CONCAT(A.Weight,'(',A.Qty,')') Calc FROM transferfgitem A WHERE A.IDM=$idTFG AND A.Product=$datasCek->Model AND A.Carat=$datasCek->Carat AND A.WorkOrder=$datasCek->WorkOrder");
                        foreach ($tfgItem as $tfgItems){}
                        $qtyItem = $tfgItems->Qty + $datasCek->Qty;
                        $weightItem = $tfgItems->Weight + $datasCek->Weight;
                        $ordItem = $tfgItems->Ordinal;

                        if($tfgItems->Calculation == NULL){
                            $calc = $tfgItems->Calc . ' + ' . $datasCek->Calc;
                        }else{
                            $calc = $tfgItems->Calculation . ' + ' . $datasCek->Calc;
                        }
                        
                        $queryUpdTFGItem = "UPDATE transferfgitem SET Qty='$qtyItem', Weight='$weightItem', Calculation='$calc', Note='Laravel' WHERE IDM=$idTFG AND Carat=$datasCek->Carat AND Product=$datasCek->Model AND WorkOrder=$datasCek->WorkOrder";
                        $updTFGItem = FacadesDB::connection('erp')->update($queryUpdTFGItem);

                        array_push($arrTFGID,$idTFG);
                        array_push($arrTFGOrd,$ordItem);

                    }

                    $updFGTotal = FacadesDB::connection('erp')->update("UPDATE transferfg SET Total=(SELECT SUM(Weight) Weight FROM transferfgitem WHERE IDM='$idTFG') WHERE ID='$idTFG' ");
                    
                }
            
            }

        }

        return array('ID'=>$arrTFGID, 'Ord'=>$arrTFGOrd);



    }

    public function posting(Request $request){ //OK
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->idnthko;

        $querystatus = "SELECT * FROM workcompletion WHERE ID=$id ";
        $data = FacadesDB::connection('erp')->select($querystatus);

        foreach ($data as $datas){}
        $statuscek = $datas->Active;
        $wanthko = $datas->WorkAllocation;
        $transdateNTHKO = $datas->TransDate;
        $locationNTHKO = $datas->Location;

        // Cek Stok Harian (Public Function)
        $cekStokHarian = $this->Public_Function->CekStokHarianERP($locationNTHKO, $transdateNTHKO); //Return True or False

        if($statuscek == 'P'){
            $json_return = array('status' => 'sdhposting');
            return response()->json($json_return,200);

        }else if($statuscek == 'S'){
            $json_return = array('status' => 'sdhsusut');
            return response()->json($json_return,200);
            
        }else if($statuscek == 'C'){
            $json_return = array('status' => 'sdhbatal');
            return response()->json($json_return,200);

        }else if ($statuscek == 'A' && $cekStokHarian == true){

            FacadesDB::connection('erp')->beginTransaction();
            try {

          
                $postikan = FacadesDB::connection('erp')->select("SELECT WCI.*, WC.WorkAllocation, WC.Location FROM workcompletionitem WCI JOIN workcompletion WC ON WC.ID=WCI.IDM WHERE WCI.IDM = $id ORDER BY WCI.Ordinal ASC");     

                foreach ($postikan as $janda) {
            
                    $status = "D";                      // For Debit (NTHKO)
                    $tableitem = "workcompletionitem";  // Tabel item
                    $userName = Auth::user()->name;     // User who post this transaction
                    $location = $janda->Location;       // Location NTHKO
                    $product = $janda->Product;         // Ini nanti looping dari workcompletionitem
                    $carat = $janda->Carat;             // Ini nanti looping dari workcompletionitem
                    $Process = 'Production';            // Default
                    $cause = 'Completion';              // todo: Completion (Stok Bertambah) (Untuk NTHKO)
                    $SW = $janda->WorkAllocation;       // Ini nanti dapat dari tabel workcompletionitem
                    $idSPKO = $janda->IDM;              // Ini nanti dapat dari tabel workcompletionitem karena looping
                    $ordinal = $janda->Ordinal;         // Ini nanti dapat dari tabel workcompletionitem karena looping
                    $workorder = $janda->WorkOrder;     // Ini nanti dapat dari tabel workcompletionitem

                    $postingFunction = $this->Public_Function->PostingERP($status, $tableitem, $userName, $location, $product, $carat, $Process, $cause, $SW, $idSPKO, $ordinal, $workorder);
                
                }

                if ($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']) {

                    // Update 'Active' WorkCompletion
                    $statusaktif = "UPDATE workcompletion SET Active='P', PostDate=now(), Remarks='Posting Laravel' Where ID=$id ";
                    $aktif = FacadesDB::connection('erp')->update($statusaktif);

                    // Update WorkAllocationResult
                    $dataWC = FacadesDB::connection('erp')->select("SELECT * FROM workcompletion WHERE ID=$id");
                    foreach ($dataWC as $super){}

                    // Get Sum Qty and Weight WorkCompletion
                    $dataWCSum = FacadesDB::connection('erp')->select("SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM workcompletion WHERE WorkAllocation=$wanthko AND Active IN ('P')");
                    foreach ($dataWCSum as $super2){}

                    // Update Qty and Weight WorkAllocationResult
                    $queryWAR = "UPDATE workallocationresult SET CompletionQty = $super2->Qty, CompletionWeight = $super2->Weight, CompletionDate = now(), CompletionFreq = $super->Freq WHERE SW = $wanthko ";
                    $updateWAR = FacadesDB::connection('erp')->update($queryWAR);

                }

                // Insert TransferFG 'Selesai Cor' QC
                if($location == 10){
                    $dataTFG = $this->insTransferFG($id,$username);
                    $jmlList = count($dataTFG['ID']);

                    if($jmlList > 0){
                        $arrList = array();
                        for ($i=0; $i < $jmlList; $i++) { 
                            $concatList = 'Transfer FG ID ' . $dataTFG['ID'][$i] . ' Baris ' . $dataTFG['Ord'][$i];
                            array_push($arrList,$concatList);
                        }
                        $returnList = implode("<br>",$arrList);
    
                        FacadesDB::connection('erp')->commit();
                        $json_return = array(
                            'status' => 'sukses',
                            'idnthko' => $id,
                            'location' => 10,
                            'list' => $returnList,
                            'baris' => 1
                        );
                        return response()->json($json_return,200);

                    }else{
                        FacadesDB::connection('erp')->commit();
                        $json_return = array(
                            'status' => 'sukses',
                            'idnthko' => $id,
                            'location' => 10,
                            'baris' => 0
                        );
                        return response()->json($json_return,200);

                    }



                }else{
                    FacadesDB::connection('erp')->commit();
                    $json_return = array(
                        'status' => 'sukses',
                        'idnthko' => $id,
                        'location' => $location
                    );
                    return response()->json($json_return,200);

                }

           

            } catch (Exception $e) {
                FacadesDB::connection('erp')->rollBack();
                $json_return = array(
                    'status' => 'Failed',
                    'message' => 'Posting Error !'
                );
                return response()->json($json_return,500);
            }

        }else{	
            $json_return = array('status' => 'belumstokharian');
            return response()->json($json_return,200);
        }

        // return response()->json($data);
    }

    public function cetak(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $wa = $request->wa;
  
        // Query Header
        $query = "SELECT C.*, IfNull(G.WorkGroup, E.SW) WorkBy, U.TargetQty, U.Weight TargetWeight,
                        S.Shrink, S.Difference, R.Description Carat, R.ID RID, L.Description LDescription, L.Department,
                        O.Description ODescription, Concat(C.WorkAllocation, '-', C.Freq) Code, U.WaxOrder
                    From WorkCompletion C
                        Join Employee E On C.Employee = E.ID
                        Join Location L On C.Location = L.ID
                        Join Operation O On C.Operation = O.ID
                        Join WorkAllocationResult U On C.WorkAllocation = U.SW
                        Left Join WorkShrink S On U.SW = S.Allocation And S.Active <> 'C'
                        Left Join ProductCarat R On U.Carat = R.ID
                        Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup
                            From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On C.WorkGroup = G.IDM
                    Where C.ID = $id
                ";
        $data = FacadesDB::connection('erp')->select($query);

        // QueryItem
        $queryItem = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                            F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                            O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                            A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                            If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                            X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, O.RequireDate, G.SW GSW,
                            CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',A.Ordinal) NTHKOBefore, CONCAT(P.Description, ' ', IF(C.Description IS NULL, '', C.Description)) BarangName,
                            Z.Qty QtyNTHKO, Z.Weight WeightNTHKO, SUM(WA.TargetQty) QtySPKO, SUM(WA.Weight) WeightSPKO, CONCAT(TreeID,'-',TreeOrd) NoPohon, (A.StoneLoss+A.QtyLossStone) JmlBatu
                        From WorkCompletionItem A
                            Join Product P On A.Product = P.ID
                            Left Join ProductCarat C On A.Carat = C.ID
                            Left Join WorkOrder O On A.WorkOrder = O.ID
                            Left Join Product F On O.Product = F.ID
                            Left Join ProductCarat T On O.Carat = T.ID
                            Left Join WaxTree W On A.TreeID = W.ID
                            Left Join RubberPlate X On W.SW = X.ID
                            Left Join Product G On A.FG = G.ID
                            Left Join Product H On A.Part = H.ID
                            LEFT JOIN WorkCompletion Z On A.IDM = Z.ID
                            LEFT JOIN WorkAllocation WA ON Z.WorkAllocation=WA.SW
                        Where A.IDM = $id
                        Group By A.IDM, A.Ordinal
                        Order By A.Ordinal";
        $dataItem = FacadesDB::connection('erp')->select($queryItem);

        // WorkAllocationResult
        $queryInfoSPKO = "SELECT * FROM WorkAllocationResult WHERE SW=$wa ";
        $dataInfo = FacadesDB::connection('erp')->select($queryInfoSPKO);

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        return view('Produksi.PelaporanProduksi.NTHKO.cetak', compact('location','data','dataItem','username','datenow','timenow','dataInfo'));
    }

    public function cetakBarcode(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        $query = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                    F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                    O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                    A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                    If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                    X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, G.SW FGName, W.TransDate PohonDate, WC.TransDate NTHKODate, 
                    CONCAT(WC.WorkAllocation,'-',WC.Freq,'-',A.Ordinal) NoSPKO, CONCAT(A.TreeID,'-',A.TreeOrd) Tree, P.SW PSW, EM.SW EMSW
                    -- , WOI.Remarks NoteMarketing
                From WorkCompletionItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    LEFT JOIN WorkCompletion WC ON A.IDM=WC.ID
                    LEFT JOIN Employee EM ON WC.Employee=EM.ID
                    -- LEFT JOIN WorkOrderItem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG
                Where A.IDM = $id
                Order By A.Ordinal ";

        $queryQC = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                    F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                    O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                    A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                    If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                    X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, G.SW FGName, W.TransDate PohonDate, WC.TransDate NTHKODate, 
                    CONCAT(WC.WorkAllocation,'-',WC.Freq,'-',A.Ordinal) NoSPKO, CONCAT(A.TreeID,'-',A.TreeOrd) Tree, P.SW PSW, EM.SW EMSW
                From WorkCompletionItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    LEFT JOIN WorkCompletion WC ON A.IDM=WC.ID
                    LEFT JOIN Employee EM ON WC.Employee=EM.ID
                Where A.IDM = $id
                AND A.Product<>256
                Order By A.Ordinal ";

        if($location == 10){
            $data = FacadesDB::connection('erp')->select($queryQC);
        }else{
            $data = FacadesDB::connection('erp')->select($query);
        }
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.NTHKO.cetakbarcode', compact('location','data','username','sw','rowcount'));
    }

    public function cetakBarcodeDirect(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        $query = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                    F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                    O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                    A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                    If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                    X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, G.SW FGName, W.TransDate PohonDate, WC.TransDate NTHKODate, 
                    CONCAT(WC.WorkAllocation,'-',WC.Freq,'-',A.Ordinal) NoSPKO, CONCAT(A.TreeID,'-',A.TreeOrd) Tree, P.SW PSW, EM.SW EMSW
                    -- , WOI.Remarks NoteMarketing
                From WorkCompletionItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    LEFT JOIN WorkCompletion WC ON A.IDM=WC.ID
                    LEFT JOIN Employee EM ON WC.Employee=EM.ID
                    -- LEFT JOIN WorkOrderItem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG
                Where A.IDM = $id
                Order By A.Ordinal ";

        $queryQC = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                    F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                    O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                    A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                    If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                    X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, G.SW FGName, W.TransDate PohonDate, WC.TransDate NTHKODate, 
                    CONCAT(WC.WorkAllocation,'-',WC.Freq,'-',A.Ordinal) NoSPKO, CONCAT(A.TreeID,'-',A.TreeOrd) Tree, P.SW PSW, EM.SW EMSW
                From WorkCompletionItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    LEFT JOIN WorkCompletion WC ON A.IDM=WC.ID
                    LEFT JOIN Employee EM ON WC.Employee=EM.ID
                Where A.IDM = $id
                AND A.Product<>256
                Order By A.Ordinal ";

        if($location == 10){
            $data = FacadesDB::connection('erp')->select($queryQC);
        }else{
            $data = FacadesDB::connection('erp')->select($query);
        }
        $rowcount = count($data);

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.cetakbarcodedirect', compact('data','rowcount'));

        $pdf = Pdf::loadHtml($returnHTML);
        $customPaper = array(0, 0, 210, 835);
        $pdf->setPaper($customPaper, 'portrait');

        $hasilpdf = $pdf->output();             
        file_put_contents(('C:/LestariERP/ProduksiPDF/'.$id.'.pdf'), $hasilpdf);

        return response()->json( array('success' => true, 'id' => $id) );
    }

    public function cekProduct(){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $listrep = array(254,99,98,2403);
        $listrusak = array(255,2561,2572);

        if($location == 4){
            $list = array(249,253,2229);
        }elseif($location == 7){
            $list = array(257,261,2190,5630,5633,5634,5635,5636);
        }elseif($location == 10){
            $list = array(256,753);
        }elseif($location == 12){
            $list = array(86,87,88,258,739,8232);
        }elseif($location == 17){
            $list = array(250,2240,2542);
        }elseif($location == 47){
            $list = array(247);
        }elseif($location == 48){
            $list = array(246,2232,2233,2247);
        }elseif($location == 49){
            $list = array(248,262,18881);
        }elseif($location == 50){
            $list = array(260,2234,2242);
        }elseif($location == 52){
            $list = array(91,2249);
        }elseif($location == 53){
            $list = array(98,99,254,259,2206);
        }elseif($location == 55){
            $list = array(100);
        }elseif($location == 56){
            $list = array(5632);
        }elseif($location == 73){
            $list = array(101,2403,2404);
        }elseif($location == 83){
            $list = array(2674,2676,2677,2678);
        }

        // // List Brg Siap
        // 249,253,2229,
        // 257,261,2190,5630,5633,5634,5635,5636,
        // 256,753,
        // 86,87,88,258,739,8232,
        // 250,2240,2542,
        // 247,
        // 246,2232,2233,2247,
        // 248,262,18881,
        // 260,2234,2242,
        // 91,2249,
        // 98,99,254,259,2206,
        // 100,
        // 5632,
        // 101,2403,2404,
        // 2674,2676,2677,2678

        // // Get Product with DB Query
        // $query = "SELECT * FROM Product WHERE FileCost=$location AND Active='Y' AND ProdGroup=119 ";
        // $data = FacadesDB::connection('erp')->select($query);

        // $arrProduct = array();
        // foreach ($data as $datas){
        //     array_push($arrProduct, $datas->ID); 
        // }

        return response()->json( array('list' => $list) );
    }

    public function detailSPK(Request $request, $swspko){

        $jenis = 'detailSPK';
        // $query = "SELECT 
        //             C.ID WOID, C.SW WOSW, D.SW PSW, 
        //             A.ACTIVE SPKOActive, B.QTY JmlSPKO, FORMAT(B.WEIGHT,2) BrtSPKO, 
        //             F.ACTIVE NTHKOActive, 
        //             IF(E.QTY IS NULL || E.REPAIRWEIGHT IS NULL || E.SCRAPWEIGHT IS NULL, 0, SUM(E.QTY+E.REPAIRQTY+E.SCRAPQTY)) JmlNTHKO, 
        //             FORMAT( IF(E.WEIGHT IS NULL || E.REPAIRWEIGHT IS NULL || E.SCRAPWEIGHT IS NULL, 0, SUM(E.WEIGHT+E.REPAIRWEIGHT+E.SCRAPWEIGHT)) ,2) BrtNTHKO,
        //             (B.QTY-IF(E.QTY IS NULL || E.REPAIRWEIGHT IS NULL || E.SCRAPWEIGHT IS NULL, 0, SUM(E.QTY+E.REPAIRQTY+E.SCRAPQTY))) SelisihJml, 
        //             FORMAT( B.WEIGHT-IF(E.WEIGHT IS NULL || E.REPAIRWEIGHT IS NULL || E.SCRAPWEIGHT IS NULL, 0, SUM(E.WEIGHT+E.REPAIRWEIGHT+E.SCRAPWEIGHT)) ,2) SelisihBrt
        //         FROM   
        //         WORKALLOCATION A
        //             JOIN WORKALLOCATIONITEM B ON A.ID=B.IDM
        //             JOIN WORKORDER C ON B.WORKORDER=C.ID
        //             JOIN PRODUCT D ON C.PRODUCT=D.ID
        //             LEFT JOIN WORKCOMPLETIONITEM E ON E.LINKID=B.IDM AND E.LINKORD=B.ORDINAL
        //             LEFT JOIN WORKCOMPLETION F ON F.ID=E.IDM
        //         WHERE A.SW=$swspko AND A.FREQ=1 
        //         GROUP BY B.WORKORDER
        //         ORDER BY C.ID  ";

        $query = "SELECT W.SW, P.SW Production, A.QtyAllocation, C.QtyCompletion, A.WeightAllocation,
                        C.WeightCompletion, A1.QtyAllocationActive, C1.QtyCompletionActive, A1.WeightAllocationActive,
                        C1.WeightCompletionActive, W.SWYear, W.SWOrdinal,
                        IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) QtyAllocationTotal,
                        IfNull(C.QtyCompletion, 0) + IfNull(C1.QtyCompletionActive, 0) QtyCompletionTotal,
                        IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) WeightAllocationTotal,
                        IfNull(C.WeightCompletion, 0) + IfNull(C1.WeightCompletionActive, 0) WeightCompletionTotal,
                        IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) - IfNull(C.QtyCompletion, 0) - IfNull(C1.QtyCompletionActive, 0) QtyDifference,
                        IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) - IfNull(C.WeightCompletion, 0) - IfNull(C1.WeightCompletionActive, 0) WeightDifference
                    From (  Select Distinct I.WorkOrder
                            From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                            Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $swspko
                            Union
                            Select Distinct I.WorkOrder
                            From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                            Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $swspko ) O
                    Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocation,
                                Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocation
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $swspko
                                Group By I.WorkOrder ) A On A.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletion,
                                Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletion
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $swspko
                                Group By I.WorkOrder ) C On C.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocationActive,
                                Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocationActive
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active = 'A' And A.SW = $swspko
                                Group By I.WorkOrder ) A1 On A1.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletionActive,
                                Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletionActive
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active = 'A' And A.WorkAllocation = $swspko
                                Group By I.WorkOrder ) C1 On C1.WorkOrder = O.WorkOrder
                    Join WorkOrder W On O.WorkOrder = W.ID
                    Join Product P On W.Product = P.ID
                    Order By W.SWYear, W.SWOrdinal";
                    // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.detailtab', compact('data','jenis'))->render();
        return response()->json( array('html' => $returnHTML) );
    }

    public function detailSPKO(Request $request, $swspko, $fregspko){
        $jenis = 'detailSPKO';
        $query = "SELECT * FROM workallocation WHERE SW=$swspko AND Freq=1";
        $data = FacadesDB::connection('erp')->select($query);
        foreach($data as $datas){}

        $query2 = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                    Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.SW, WA.Active, WA.TransDate, WA.Freq, WA.Purpose
                From WorkAllocationItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    JOIN WorkAllocation WA ON A.IDM=WA.ID
                Where A.IDM = $datas->ID
                Order By A.Ordinal";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.detailtab', compact('data2','jenis'))->render();
        return response()->json( array('html' => $returnHTML) );
    }

    public function detailNTHKO(Request $request, $idnthko){
        $jenis = 'detailNTHKO';
        $query = "SELECT A.*, P.Description PDescription, C.Description CSW, O.SW OSW, P.UseCarat,
                    F.SW FDescription, F.ID FID, T.Description FCarat, O.TotalQty QtyOrder,
                    O.SWPurpose, A.Qty + A.RepairQty + A.ScrapQty QtyComplete,
                    A.Weight + A.RepairWeight + A.ScrapWeight WeightComplete, G.Photo,
                    If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW)))) GDescription,
                    X.SW ZSW, 0 Point, A.Var1 / A.Var2 Mass, P.ProdGroup, P.Type, P.Model, If(A.FG Is Null, H.Photo, G.Photo) GPhoto
                From WorkCompletionItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                Where A.IDM = $idnthko
                Order By A.Ordinal";
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.PelaporanProduksi.NTHKO.detailtab', compact('data','jenis'))->render();
        return response()->json( array('html' => $returnHTML) );

    }

}
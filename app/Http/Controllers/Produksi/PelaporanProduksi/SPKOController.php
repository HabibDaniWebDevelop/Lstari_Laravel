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

use App\Models\erp\lastid;
use App\Models\erp\workallocation;
use App\Models\erp\workallocationitem;
use App\Models\erp\workallocationresult;

use App\Models\rndnew\appworkpercent;

// use App\Models\tes_laravel\lastid;
// use App\Models\tes_laravel\workallocation;
// use App\Models\tes_laravel\workallocationitem;
// use App\Models\tes_laravel\workallocationresult;

class SPKOController extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller){
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index(){

        $location = session('location');
        $username = session('UserEntry');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        // show spko list
        $query = "SELECT 
                    WA.ID, WA.SW
                FROM
                    workallocation WA
                WHERE
                    WA.Location = $location
                    AND WA.Active <> 'C' 
                ORDER BY WA.ID DESC
                LIMIT 1000";

        // Query History
        // $query = "SELECT * From UserHistory
        //             Where UserID = '$UserEntry' 
        //             And Module = 'Material Withdraw'
        //             Order By Ordinal";

        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.SPKO.index', compact('data','rowcount','iddept','location'));

    }

    public function lihat(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $swspko = $request->id;

        // get time now
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // show rph
        $queryRPH = "SELECT ID, DATE_FORMAT(TransDate,'%d-%m-%y') TransDate FROM WorkSchedule WHERE location=$location AND Active='P' ORDER BY ID DESC";
        $dataRPH = FacadesDB::connection('erp')->select($queryRPH);

        // show spko header
        $queryHeader = "SELECT 
                            W.*, Cast(E.SW As Char) ESW, Cast(G.WorkGroup As Char) EGroup, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                            O.Description ODescription, C.Description CSW, Cast(IfNull(G.WorkGroup, E.SW) As Char) EmployeeGroup, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA
                        From 
                            WorkAllocation W
                            Join Employee E On W.Employee = E.ID
                            Join Location L On W.Location = L.ID
                            Join Operation O On W.Operation = O.ID
                            Left Join ProductCarat C On W.Carat = C.ID
                            Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On W.WorkGroup = G.IDM
                        Where W.SW = $swspko And W.Freq = 1";
        $header = FacadesDB::connection('erp')->select($queryHeader);
        $rowcountHeader = count($header);

        if($rowcountHeader == 0){
            $json_return = array('message' => 'SPKO Tidak Ditemukan');
            return response()->json($json_return,404); // 400 = Bad Request , 404 = Not Found
        }

        if($location <> $header[0]->Location){
            $json_return = array('message' => 'SPKO Bukan Area Anda');
            return response()->json($json_return,404); // 400 = Bad Request , 404 = Not Found
        }

        // set data header
        foreach ($header as $headers){}
        $activeSPKO = $headers->Active;
        $idWA = $headers->ID;
        $transdateSPKO = $headers->TransDate;
        $locationSPKO = $headers->Location;

        // get workpercent
        $queryWorkPercent = "SELECT * FROM rndnew.appworkpercent WHERE IDSPKO = $idWA";
        $workPercent = FacadesDB::connection('erp')->select($queryWorkPercent);
        $rowcountWP = count($workPercent);
        if($rowcountWP > 0){
            foreach ($workPercent as $workPercents){}
            $wpTotalTime = $workPercents->TotalSecond;
            $wpPersen = $workPercents->Percent;
        }else{
            $wpTotalTime = 0;
            $wpPersen = 0;
        }

        // Khusus Enamel - Cek Proses
        $enmOpExclude = array(48,89);
        if(in_array($headers->Operation, $enmOpExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

        // Tampil SPKO Item
        $queryItem= "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                        WA.SW
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
                    Where A.IDM = $idWA
                    Order By A.Ordinal ";
        $item = FacadesDB::connection('erp')->select($queryItem);

        $returnHTML = view('Produksi.PelaporanProduksi.SPKO.lihat', compact('location','datenow','status_SH','header','rowcountHeader','item','dataRPH','wpTotalTime','wpPersen'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'active' => $activeSPKO, 'excludepic' => $excludepic, 'swspko' => $swspko) );
    
    }

    public function baru(){

        $location = session('location');
        $username = session('UserEntry');
        $iduser = session('iduser');
        $iddept = session('iddept');
        $leveluser = session('LevelUser');

        if($location == NULL){
            $location = 12;
        }

        // Tampil Bagian
        $queryBagian = "SELECT ID, Description FROM Location WHERE ID = $location ";
        $bagian = FacadesDB::connection('erp')->select($queryBagian);

        // Tampil Kadar
        $queryKadar = "SELECT ID, SW, Description FROM productcarat WHERE Regular='Y' ORDER BY Description ";
        $kadar = FacadesDB::connection('erp')->select($queryKadar);

        // Tampil Proses
        $queryProses= "SELECT * FROM operation WHERE Location = $location AND Active='Y' Order By Description ";
        $proses = FacadesDB::connection('erp')->select($queryProses);

         // Tampil RPH
         $queryRPH = "SELECT ID, DATE_FORMAT(TransDate,'%d-%m-%y') TransDate FROM WorkSchedule WHERE location=$location AND Active='P' ORDER BY ID DESC";
         $rph = FacadesDB::connection('erp')->select($queryRPH);
 
        $returnHTML = view('Produksi.PelaporanProduksi.SPKO.baruView', compact('location','bagian','kadar','proses','rph','iddept'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );

    }

    public function ubah(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $swspko = $request->swspko;

        if($location == 12){
            $appoperation = 1; //Poles Manual
        }else{
            $appoperation = 1;
        }

        // Get Time Now
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Tampil RPH
        $queryRPH = "SELECT ID, DATE_FORMAT(TransDate,'%d-%m-%y') TransDate FROM WorkSchedule WHERE location=$location AND Active='P' ORDER BY ID DESC";
        $rph = FacadesDB::connection('erp')->select($queryRPH);

        // List Carat
        $queryKadar = "SELECT ID, SW, Description FROM productcarat WHERE Regular='Y' ORDER BY Description ";
        $kadar = FacadesDB::connection('erp')->select($queryKadar);

        // List Proses
        $queryProses = "SELECT * FROM operation WHERE Location = $location AND Active='Y' Order By Description ";
        $proses = FacadesDB::connection('erp')->select($queryProses);

        // Tampil SPKO Header
        $queryHeader = "SELECT 
                            W.*, Cast(E.SW As Char) ESW, Cast(G.WorkGroup As Char) EGroup, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                            O.Description ODescription, C.Description CSW, Cast(IfNull(G.WorkGroup, E.SW) As Char) EmployeeGroup, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA,
                            O.Product ProsesProduct
                        From 
                            WorkAllocation W
                            Join Employee E On W.Employee = E.ID
                            Join Location L On W.Location = L.ID
                            Join Operation O On W.Operation = O.ID
                            Left Join ProductCarat C On W.Carat = C.ID
                            Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On W.WorkGroup = G.IDM
                        Where W.SW = $swspko And W.Freq = 1";
        $header = FacadesDB::connection('erp')->select($queryHeader);
        $rowcountHeader = count($header);

        foreach ($header as $headers){}
        $activeSPKO = $headers->Active;
        $idWA = $headers->ID;
        $transdateSPKO = $headers->TransDate;
        $locationSPKO = $headers->Location;

        $enmOpExclude = array(48,89);
        if(in_array($headers->Operation, $enmOpExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        // // Cek Stok Harian Manual
        // $querySH = "SELECT ID FROM Location WHERE ID = $locationSPKO AND StockDate = (SELECT MAX(TransDate) TransDate FROM WorkDate WHERE TransDate < '$transdateSPKO' AND Holiday = 'N' )";
        // $stokHarian = FacadesDB::connection('erp')->select($querySH);
        // $rowcountSH = count($stokHarian);
        // if($rowcountSH > 0){
        //     $status_SH = 'Y';
        // }else{
        //     $status_SH = 'N';
        // }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

        // Tampil SPKO Item
        $queryItem = "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                        WA.SW, O.ID OID,
                        (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                        CASE 
                            WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                        END
                        )) AS TotalTime,
                        ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                        CASE 
                            WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                        END
                        )) / 
                        CASE 
                            WHEN DAYNAME('WA.TransDate') IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                            WHEN (DAYNAME('WA.TransDate') = 'Friday' AND EM.Sex = 'L') THEN 22500 
                            WHEN (DAYNAME('WA.TransDate') = 'Friday' AND EM.Sex = 'P') THEN 26100 
                            WHEN DAYNAME('WA.TransDate') = 'Saturday' THEN 17640 
                            ELSE 26100
                        END
                        ) * 100) , 2) AS Persen
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
                        Left Join WorkAllocation WA ON A.IDM=WA.ID
                        Left Join Employee EM ON EM.ID=WA.Employee
                        LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                    Where A.IDM = $idWA
                    Order By A.Ordinal ";
        $item = FacadesDB::connection('erp')->select($queryItem);

        $returnHTML = view('Produksi.PelaporanProduksi.SPKO.ubahView', compact('location','datenow','status_SH','header','rowcountHeader','item','kadar','proses','rph'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'active' => $activeSPKO, 'excludepic' => $excludepic) );

    }

    public function cariKaryawan(Request $request){ //OK

        $location = session('location');

        if($location == NULL){
            $location = 12;
        }

        $proses = $request->proses;
        $id = $request->id;
        $tgl = $request->tgl;

        if($location == 12){
            $appoperation = 1; //ID=1 -> Poles Manual /rndnew.appoperation
        }else{
            $appoperation = 1;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");
        
        // Get Data Employee
        $query = "SELECT ID, SW FROM Employee WHERE ID = $id ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        // Get WorkPercent
        // $query2 = "SELECT SUM(Percent) Percent FROM rndnew.appworkpercent WHERE Operator=$id AND WorkDate=(SELECT MAX(WorkDate) FROM rndnew.appworkpercent WHERE Location=$location AND Operation=$appoperation) ";
        $query2 = "SELECT SUM(TotalSecond) TotalSecond, FORMAT(SUM(Percent),2) Percent FROM rndnew.appworkpercent WHERE Operator=$id AND WorkDate='$tgl' ";
        $data2 = FacadesDB::connection('erp')->select($query2);
        $rowcount2 = count($data2);

        if($rowcount2 > 0){
            foreach ($data2 as $datas2){}
            $worktotalsecond = $datas2->TotalSecond;
            $workpercent = $datas2->Percent;
        }else{
            $worktotalsecond = 0;
            $workpercent = 0;
        }

        if($rowcount == 0){
            $jsondata = array('success' => false);
        }else{
            foreach ($data as $datas){}
            $idkary = $datas->ID;
            $swkary = $datas->SW;

            $jsondata = array('success' => true, 'idkary' => $idkary, 'swkary' => $swkary, 'worktotalsecond' => $worktotalsecond, 'workpercent' => $workpercent, 'rowcount2' => $rowcount2, 'query2' => $query2);
        }
        return response()->json($jsondata);

    }

    public function cariWorkgroup(Request $request){ //OK

        $location = session('location');
        $id = $request->id;
        $empid = $request->empid;

        if($location == NULL){
            $location = 12;
        }

        $wgArr = array();
        $wgArrID = array();

        if($id == ''){
            // $goto = array('status' => 'setnull');
            $json_return = array(
                'status' => 'setnull'
            );
            return response()->json($json_return,200);
        }else{
            // Get Data WorkGroup
            $query = "SELECT A.ID, B.Employee, E.SW ESW
                    FROM workgroup A
                    JOIN workgroupitem B ON B.IDM=A.ID
                    JOIN employee E ON E.ID=B.Employee
                    WHERE 
                    A.Department = $location
                    AND A.ID = $id ";
            $data = FacadesDB::connection('erp')->select($query);
            $rowcount = count($data);

            if($rowcount == 0){
                // $goto = array('status' => 'notfound');
                $json_return = array(
                    'status' => 'notfound'
                );
                return response()->json($json_return,200);
            }else{
                foreach ($data as $datas){
                    $wgArr[] = $datas->ESW; 
                    $wgArrID[] = $datas->Employee;
                }
            
                if(in_array($empid, $wgArrID)){
                    $idwg = $id;
                    $swwg = implode(", ", $wgArr);
                    // $goto = array('idwg' => $idwg, 'swwg' => $swwg, 'status' => 'sukses');
                    $json_return = array(
                        'status' => 'sukses',
                        'idwg' => $idwg, 
                        'swwg' => $swwg
                    );
                    return response()->json($json_return,200);
                    
                }else{
                    // $goto = array('status' => 'notsame');
                    $json_return = array(
                        'status' => 'notsame'
                    );
                    return response()->json($json_return,200);
                }
            }
        }
        return response()->json($goto);

    }

    public function cekSPK(Request $request){

        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $OID = $request->OID;
        $jmlItem = count($OID);

        $arrChar = array();
        $arrActive = array();

        // set $arrChar dan $arrActive
        foreach ($OID as $OIDs) {
            $queryCek = "SELECT A.SW, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK, A.Active
                            FROM workorder A
                            WHERE A.ID=$OIDs ";
            $dataCek = FacadesDB::connection('erp')->select($queryCek);
            foreach($dataCek as $datasCek){}

            array_push($arrChar,$datasCek->CharSPK);
            array_push($arrActive,$datasCek->Active);
        }

        // jml count $arrChar
        $jmlItem2 = array_sum($arrChar);

        // cek status spk active
        $arrActiveList = array('D','C','T');
        if(in_array($arrActiveList, $arrActive)){
            $cekActive = true;
        }else{
            $cekActive = false;
        }

        // // cek status only checking O and Non-O
        // if($jmlItem2 == 0 || $jmlItem2 == $jmlItem){
        //     if($jmlItem2 == 0){ // SPK 'O'
        //         $cekspk = 0;
        //     }else{              // SPK Non 'O'
        //         $cekspk = 1;
        //     }
        //     $datajson = array('status' => 'Sukses', 'cekspk' => $cekspk);
        // }else{
        //     $datajson = array('status' => 'Gagal');
        // }

        // check status of spk O and Non-O , spk open or not
        if($cekActive == true){
            $datajson = array('status' => 'SPK_Closed');
        }else{
            if($jmlItem2 == 0 || $jmlItem2 == $jmlItem){
                if($jmlItem2 == 0){ // SPK 'O'
                    $cekspk = 0;
                }else{              // SPK Non 'O'
                    $cekspk = 1;
                }
                $datajson = array('status' => 'Sukses', 'cekspk' => $cekspk);
            }else{
                $datajson = array('status' => 'Gagal');
            }
        }

        return response()->json($datajson);

    }

    public function cekSPKTest(Request $request){

        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $OID = $request->OID;
        $jmlItem = count($OID);

        $arrChar = array();
        $arrActive = array();

        foreach ($OID as $OIDs) {
            $queryCek = "SELECT A.SW, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK, A.Active
                            FROM workorder A
                            WHERE A.ID=$OIDs ";
            $dataCek = FacadesDB::connection('erp')->select($queryCek);
            foreach($dataCek as $datasCek){}

            array_push($arrChar,$datasCek->CharSPK);
            array_push($arrActive,$datasCek->Active);
        }

        $jmlItem2 = array_sum($arrChar);

        $arrActiveList = array('D','C','T');
        if(in_array($arrActiveList, $arrActive)){
            $cekActive = true;
        }else{
            $cekActive = false;
        }

        dd($cekActive);

        if($cekActive == true){
            $datajson = array('status' => 'SPK_Closed');
        }else{
            if($jmlItem2 == 0 || $jmlItem2 == $jmlItem){
                if($jmlItem2 == 0){ // SPK 'O'
                    $cekspk = 0;
                }else{              // SPK Non 'O'
                    $cekspk = 1;
                }
                $datajson = array('status' => 'Sukses', 'cekspk' => $cekspk);
            }else{
                $datajson = array('status' => 'Gagal');
            }
        }
        dd($datajson);
        return response()->json($datajson);

    }

    public function simpan(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $locationSW = sprintf("%02d", $location);

        $stone = 0;
        $karyawan = $request->karyawan;
        $karyawanid = $request->karyawanid;
        $tgl = $request->tgl;
        $kadar = $request->kadar;
        $proses = $request->proses;
        $workgroup = $request->workgroupid;
        $targetqtyWA = $request->qtyspko;
        $weightWA = $request->weightspko;

        $cekspk = $request->cekspk;
        if($cekspk == 0){
            $swtahun = 0;
        }else{
            $swtahun = 50;
        }

        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        $WorkAllocation = $request->WorkAllocation;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $Carat = $request->Carat;
        $WorkOrder = $request->WorkOrder;
        $Note = $request->Note;
        $BarcodeNote = $request->BarcodeNote;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $PID = $request->PID;
        $PrevProcess = $request->PrevProcess;
        $PrevOrd = $request->PrevOrd;
        $FG = $request->FG;
        $Part = $request->Part;
        $RID = $request->RID;
        $OID = $request->OID;
        $OOrd = $request->OOrd;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $BatchNo = $request->BatchNo;
        
        $jmlArr = count($WorkAllocation);
        
        // Khusus Enamel - Cek Proses
        $enamelExclude = array(48,89);
        if(in_array($prosesID, $enamelExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        if($location == 47){
            if($prosesID == 161 || $prosesID == 166){

                $querywg = "SELECT * FROM workgroupitem
                            WHERE IDM IN (647)
                            AND Employee = $karyawanid ";
                $sqlwg = FacadesDB::connection('erp')->select($querywg);
                $rowcount = count($sqlwg);
        
                if($rowcount == 0){
                    $statuswg = 'Kosong';
                }else{
                    $statuswg = 'Ada';
                }

            }else if($prosesID == 48){
        
                $querywg = "SELECT * FROM workgroupitem
                            WHERE IDM IN (594)
                            AND Employee = $karyawanid
                            ";
                $sqlwg = FacadesDB::connection('erp')->select($querywg);
                $rowcount = count($sqlwg);
        
                if($rowcount == 0){
                    $statuswg = 'Kosong';
                }else{
                    $statuswg = 'Ada';
                }
                
            }else{
                $statuswg = 'Kosong';
            }
        
        }else{
            $statuswg = 'Ada';
        }

        // LAST ID
        $query = "SELECT LAST+1 AS ID FROM lastid Where Module = 'WorkAllocation' ";
        $data = FacadesDB::connection('erp')->select($query);
        foreach ($data as $datas){}

        // Raw
        // $query2 = "UPDATE lastid SET LAST = $datas->ID WHERE Module = 'WorkAllocation' ";
        // $data2 = FacadesDB::connection('erp')->update($query2);

        // Eloquent
        $data2 = lastid::where('Module', 'WorkAllocation')->update([
            'Last' => $datas->ID
        ]);

        FacadesDB::connection('erp')->beginTransaction();
        try {

            // CREATE NEW SW MAX 9999 SWOrdinal
            // $query3 = "SELECT
            //                 CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                 DATE_FORMAT('$tgl', '%y') as tahun,
            //                 LPad(MONTH('$tgl'), 2, '0' ) as bulan,
            //                 CONCAT(DATE_FORMAT('$tgl', '%y'),'',LPad(MONTH('$tgl'), 2, '0' ),'".$location."',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //             FROM workallocation
            //             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
            // $data3 = FacadesDB::connection('erp')->select($query3);
            // foreach ($data3 as $datas3){}

            if($cekspk == 0){ // SPK AWALAN 'O'

                // CREATE NEW SW - SWOrdinal MORE THAN 9999
                $queryMaxOrdinal = "SELECT Max(SWOrdinal)+1 SWOrdinal From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And SWMonth = MONTH('$tgl') And Location = $location ";
                $maxOrdinal = FacadesDB::connection('erp')->select($queryMaxOrdinal);
                foreach ($maxOrdinal as $maxOrdinals){}
    
                $dateMonth = date("n", strtotime($tgl));

            }else{ // SPK AWALAN NON 'O'

                // CREATE NEW SW - SWOrdinal MORE THAN 9999
                $queryMaxOrdinal = "SELECT Max(SWOrdinal)+1 SWOrdinal From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y')+50 And SWMonth = MONTH('$tgl') And Location = $location ";
                $maxOrdinal = FacadesDB::connection('erp')->select($queryMaxOrdinal);
                foreach ($maxOrdinal as $maxOrdinals){}

                $dateMonth = date("n", strtotime($tgl));

            }

            // // CREATE NEW SW - SWOrdinal MORE THAN 9999
            // $queryMaxOrdinal = "SELECT Max(SWOrdinal)+1 SWOrdinal From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And SWMonth = MONTH('$tgl') And Location = $location ";
            // $maxOrdinal = FacadesDB::connection('erp')->select($queryMaxOrdinal);
            // foreach ($maxOrdinal as $maxOrdinals){}

            // $dateMonth = date("n", strtotime($tgl));

        
            // SPK NORMAL
            // if($maxOrdinals->SWOrdinal > 9999){

            //     $querySW = "SELECT Max(SWMonth) SWMonth From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And Location = $location AND SWMONTH LIKE '%$dateMonth' AND SWMONTH > 12";
            //     $numSW = FacadesDB::connection('erp')->select($querySW);

            //     foreach ($numSW as $result){
            //         if($result->SWMonth == ($dateMonth+20)){
            //             $queryenvelope = "SELECT
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                                 DATE_FORMAT('$tgl', '%y') as tahun,
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END as bulan, 
            //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //                             FROM workallocation
            //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+20 ";
            //         }else if($result->SWMonth == ($dateMonth+40)){
            //             $queryenvelope = "SELECT
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                                 DATE_FORMAT('$tgl', '%y') as tahun,
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END as bulan, 
            //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //                             FROM workallocation
            //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+40 ";
            
            //         }else if($result->SWMonth == ($dateMonth+60)){
            //             $queryenvelope = "SELECT
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                                 DATE_FORMAT('$tgl', '%y') as tahun,
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END as bulan, 
            //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //                             FROM workallocation
            //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+60 ";
            
            //         }else if($result->SWMonth == ($dateMonth+80)){
            //             $queryenvelope = "SELECT
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                                 DATE_FORMAT('$tgl', '%y') as tahun,
            //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END as bulan, 
            //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END,'$location',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //                             FROM workallocation
            //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+80 ";
            //         }
            //     }

            // }else{
            //     $queryenvelope = "SELECT
            //                         CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            //                         DATE_FORMAT('$tgl', '%y') as tahun,
            //                         LPad(MONTH('$tgl'), 2, '0' ) as bulan,
            //                         CONCAT(DATE_FORMAT('$tgl', '%y'),'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            //                     FROM workallocation
            //                     Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
            // }


            if($cekspk == 0){
                // SPK AWALAN 'O'
                if($maxOrdinals->SWOrdinal > 9999){

                    $querySW = "SELECT Max(SWMonth) SWMonth From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And Location = $location AND SWMONTH LIKE '%$dateMonth' AND SWMONTH > 12";
                    $numSW = FacadesDB::connection('erp')->select($querySW);

                    foreach ($numSW as $result){
                        if($result->SWMonth == ($dateMonth+20)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+20 ";
                        }else if($result->SWMonth == ($dateMonth+40)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+40 ";
                
                        }else if($result->SWMonth == ($dateMonth+60)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+60 ";
                
                        }else if($result->SWMonth == ($dateMonth+80)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+80 ";
                        }
                    }

                }else{
                    $queryenvelope = "SELECT
                                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                        DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                        LPad(MONTH('$tgl'), 2, '0' ) as bulan,
                                        CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                    FROM workallocation
                                    Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
                }
            }else{
                // SPK AWALAN NON 'O'
                if($maxOrdinals->SWOrdinal > 9999){

                    $querySW = "SELECT Max(SWMonth) SWMonth From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y')+50 And Location = $location AND SWMONTH LIKE '%$dateMonth' AND SWMONTH > 12";
                    $numSW = FacadesDB::connection('erp')->select($querySW);

                    foreach ($numSW as $result){
                        if($result->SWMonth == ($dateMonth+20)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y')+50 AND SWMonth = MONTH('$tgl')+20 ";
                        }else if($result->SWMonth == ($dateMonth+40)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y')+50 AND SWMonth = MONTH('$tgl')+40 ";
                
                        }else if($result->SWMonth == ($dateMonth+60)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y')+50 AND SWMonth = MONTH('$tgl')+60 ";
                
                        }else if($result->SWMonth == ($dateMonth+80)){
                            $queryenvelope = "SELECT
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                                DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                                CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END as bulan, 
                                                CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                            FROM workallocation
                                            Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y')+50 AND SWMonth = MONTH('$tgl')+80 ";
                        }
                    }

                }else{
                    $queryenvelope = "SELECT
                                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                                        DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
                                        LPad(MONTH('$tgl'), 2, '0' ) as bulan,
                                        CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                                    FROM workallocation
                                    Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y')+50 AND SWMonth =  MONTH('$tgl')";
                }
            }



            $sqlenvelope = FacadesDB::connection('erp')->select($queryenvelope);
            foreach ($sqlenvelope as $datas3){}

            // Insert WorkAllocation //

            // Raw
            // $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
            // $queryWA = "INSERT INTO workallocation 
            //             (ID, EntryDate, UserName, SW, Freq, TransDate, Purpose, Carat, Location, Operation, Employee, TargetQty, Weight, Active, 
            //             SWYear, SWMonth, SWOrdinal, WorkGroup, Stone, Outsource, Remarks) 
            //             VALUES 
            //             ($datas->ID, now(), '$username', $datas3->Counter1, 1, '$tgl', 'Tambah', $kadar, $location, $prosesID, $karyawanid, $targetqtyWA, $weightWA, 'A', 
            //             $datas3->tahun, $datas3->bulan, $datas3->ID, $WorkGroupOK, $stone, 'N', 'Laravel') ";
            // $dataWA = FacadesDB::connection('erp')->insert($queryWA);

            // Eloquent
            $dataWA = workallocation::create([
                'ID' => $datas->ID,
                'EntryDate' => now(),
                'UserName' => $username,
                'SW' => $datas3->Counter1,
                'Freq' => 1,
                'TransDate' => $tgl,
                'Purpose' => 'Tambah',
                'Carat' => $kadar,
                'Location' => $location,
                'Operation' => $prosesID,
                'Employee' => $karyawanid,
                'TargetQty' => $targetqtyWA,
                'Weight' => $weightWA,
                'Active' => 'A',
                'SWYear' => $datas3->tahun,
                'SWMonth' => $datas3->bulan,
                'SWOrdinal' => $datas3->ID,
                'WorkGroup' => $workgroup,
                'Stone' => $stone,
                'Outsource' => 'N',
                'Remarks' => 'Laravel'
            ]);

            // Insert WorkAllocationItem //
            $no = 1;
            for ($i = 0; $i < $jmlArr; $i++) {

                // Raw
                $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
                $WorkAllocationOK = ((isset($WorkAllocation[$i])) ? $WorkAllocation[$i] : 'NULL');
                $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
                $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
                $CaratOK = ((isset($Carat[$i])) ? $Carat[$i] : 'NULL');
                $WorkOrderOK = ((isset($WorkOrder[$i])) ? $WorkOrder[$i] : 'NULL');
                $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".addslashes($BarcodeNote[$i])."'" : 'NULL');
                $NoteOK = ((isset($Note[$i])) ? "'".addslashes($Note[$i])."'" : 'NULL');
                $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
                $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
                $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
                $PrevProcessOK = ((isset($PrevProcess[$i])) ? $PrevProcess[$i] : 'NULL');
                $PrevOrdOK = ((isset($PrevOrd[$i])) ? $PrevOrd[$i] : 'NULL');
                $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
                $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
                $RIDOK = ((isset($RID[$i])) ? $RID[$i] : 'NULL');
                $OIDOK = ((isset($OID[$i])) ? $OID[$i] : 'NULL');
                $OOrdOK = ((isset($OOrd[$i])) ? $OOrd[$i] : 'NULL');
                $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
                $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
                $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');
            
                $queryWAI = "INSERT INTO workallocationitem 
                            (IDM, Ordinal, Product, Carat, Qty, Weight, WorkOrder, WorkOrderOrd, Note, BarcodeNote,
                            PrevProcess, PrevOrd, PrevType, TreeID, TreeOrd, Part, FG, StoneLoss, QtyLossStone, WaxOrder, BatchNo, WorkSchedule, WorkScheduleOrd) 
                            VALUES 
                            ($datas->ID, $no, $PIDOK, $RIDOK, $QtyOK, $WeightOK, $OIDOK, $OOrdOK, $NoteOK, $BarcodeNoteOK,
                            $PrevProcessOK, $PrevOrdOK, NULL, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, $StoneLossOK, $QtyLossStoneOK, NULL, $BatchNoOK, NULL, NULL)";
                $dataWAI = FacadesDB::connection('erp')->insert($queryWAI);

                // Eloquent
                // $dataWAI = workallocationitem::create([
                //     'IDM' => $datas->ID,
                //     'Ordinal' => $no,
                //     'Product' => $PID[$i],
                //     'Carat' => $RID[$i],
                //     'Qty' => $Qty[$i],
                //     'Weight' => $Weight[$i],
                //     'WorkOrder' => $OID[$i],
                //     'WorkOrderOrd' => $OOrd[$i],
                //     'Note' => $Note[$i],
                //     'BarcodeNote' => $BarcodeNote[$i],
                //     'PrevProcess' => $PrevProcess[$i],
                //     'PrevOrd' => $PrevOrd[$i],
                //     'PrevType' => NULL,
                //     'TreeID' => $TreeID[$i],
                //     'TreeOrd' => $TreeOrd[$i],
                //     'Part' => $Part[$i],
                //     'FG' => $FG[$i],
                //     'StoneLoss' => $StoneLoss[$i],
                //     'QtyLossStone' => $QtyLossStone[$i],
                //     'WaxOrder' => NULL,
                //     'BatchNo' => $BatchNo[$i],
                //     'WorkSchedule' => NULL,
                //     'WorkScheduleOrd' => NULL
                // ]);

                $no++;
            }

            // if($dataWA == TRUE && $dataWAI == TRUE){
            //     $json_return = array('status' => 'Sukses', 'idspko' => $datas->ID, 'swspko' => $datas3->Counter1, 'excludepic' => $excludepic, 'statuswg' => $statuswg, 'location' => $location);		
            // }else{
            //     $json_return = array('status' => 'Gagal');		
            // }
            // return response()->json($data);

            FacadesDB::connection('erp')->commit();
            // return response(
            //     [
            //         'success' => true,
            //         'status' => 'Sukses',
            //         'idspko' => $datas->ID,
            //         'swspko' => $datas3->Counter1,
            //         'excludepic' => $excludepic,
            //         'statuswg' => $statuswg,
            //         'location' => $location,
            //         'message' => 'Simpan Berhasil !',
            //     ],
            //     200,
            // );
            $json_return = array(
                'success' => true,
                'status' => 'Sukses',
                'idspko' => $datas->ID,
                'swspko' => $datas3->Counter1,
                'excludepic' => $excludepic,
                'statuswg' => $statuswg,
                'location' => $location,
                'message' => 'Simpan Berhasil !'
            );
            return response()->json($json_return,200);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            // return response(
            //     [
            //         'message' => 'Simpan Error !',
            //     ],
            //     500,
            // );
            $json_return = array(
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }

    }

    public function update(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $idspko = $request->idspko;
        $swspko = $request->swspko;
        $stone = 0;
        $karyawan = $request->karyawan;
        $karyawanid = $request->karyawanid;
        $tgl = $request->tgl;
        $kadar = $request->kadar;
        $proses = $request->proses;
        $workgroup = $request->workgroupid;
        $targetqtyWA = $request->qtyspko;
        $weightWA = $request->weightspko;

        $cekspk = $request->cekspk;
        $kodesw = substr($swspko,0,2);

        if($kodesw == 23){
            $ceksw = 0;
        }else{
            $ceksw = 1;
        }

        if($cekspk != $ceksw){
            // $data = array('status' => 'Beda_Kode');
            $json_return = array('status' => 'Beda_Kode');
            return response()->json($json_return,200);

        }else{

            $prosesAll = explode(",", $proses);
            $prosesID = $prosesAll[0];
            $prosesProduct = $prosesAll[1];

            $WorkAllocation = $request->WorkAllocation;
            $Qty = $request->Qty;
            $Weight = $request->Weight;
            $Carat = $request->Carat;
            $WorkOrder = $request->WorkOrder;
            $Note = $request->Note;
            $BarcodeNote = $request->BarcodeNote;
            $StoneLoss = $request->StoneLoss;
            $QtyLossStone = $request->QtyLossStone;
            $PID = $request->PID;
            $PrevProcess = $request->PrevProcess;
            $PrevOrd = $request->PrevOrd;
            $FG = $request->FG;
            $Part = $request->Part;
            $RID = $request->RID;
            $OID = $request->OID;
            $TreeID = $request->TreeID;
            $TreeOrd = $request->TreeOrd;
            $BatchNo = $request->BatchNo;
            $OOrd = $request->OOrd;

            $jmlArr = count($WorkAllocation);
        
            // Khusus Enamel - Cek Proses
            $enamelExclude = array(48,89);
            if(in_array($prosesID, $enamelExclude)){
                $excludepic = 1;
            }else{
                $excludepic = 0;
            }

            if($location == 47){
                if($prosesID == 161 || $prosesID == 166){
            
                    $querywg = "SELECT * FROM workgroupitem
                                WHERE IDM IN (647)
                                AND Employee = $karyawanid
                                ";
                    $sqlwg = FacadesDB::connection('erp')->select($querywg);
                    $rowcount = count($sqlwg);
            
                    if($rowcount == 0){
                        $statuswg = 'Kosong';
                    }else{
                        $statuswg = 'Ada';
                    }
                }else if($prosesID == 48){
            
                    $querywg = "SELECT * FROM workgroupitem
                                WHERE IDM IN (594)
                                AND Employee = $karyawanid
                                ";
                    $sqlwg = FacadesDB::connection('erp')->select($querywg);
                    $rowcount = count($sqlwg);
            
                    if($rowcount == 0){
                        $statuswg = 'Kosong';
                    }else{
                        $statuswg = 'Ada';
                    }
                    
                }else{
                    $statuswg = 'Kosong';
                }
            
            }else{
                $statuswg = 'Ada';
            }

            FacadesDB::connection('erp')->beginTransaction();
            try {

                // UPDATE WORKALLOCATION - Update jika tidak ada perubahan pada value, return nya "Affected rows: 0", sehingga dianggap return valuenya "False"
                
                // Raw
                // $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
                // $queryWA = "UPDATE workallocation 
                //             SET
                //                 UserName = '$username',
                //                 TransDate = '$tgl',
                //                 Carat = $kadar,
                //                 Operation = $prosesID,
                //                 Employee = $karyawanid,
                //                 WorkGroup = $WorkGroupOK,
                //                 TargetQty = $targetqtyWA,
                //                 Weight = $weightWA,
                //                 Remarks = 'Update Laravel'
                //             WHERE 
                //                 ID = $idspko ";
                // $dataWA = FacadesDB::connection('erp')->update($queryWA);

                // Eloquent
                $dataWA = workallocation::where('ID', $idspko)->update([
                    'UserName' => $username,
                    'TransDate' => $tgl,
                    'Carat' => $kadar,
                    'Operation' => $prosesID,
                    'Employee' => $karyawanid,
                    'WorkGroup' => $workgroup,
                    'TargetQty' => $targetqtyWA,
                    'Weight' => $weightWA,
                    'Remarks' => 'Update Laravel',
                ]);

                // UPDATE WORKALLOCATIONITEM
                $deleteWAI = "DELETE FROM workallocationitem WHERE IDM = $idspko ";
                $sqldeleteWAI = FacadesDB::connection('erp')->delete($deleteWAI);

                $no = 1;
                for ($i = 0; $i < $jmlArr; $i++) {

                    // Raw
                    $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
                    $RIDOK = ((isset($RID[$i])) ? $RID[$i] : 'NULL');
                    $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
                    $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
                    $OIDOK = ((isset($OID[$i])) ? $OID[$i] : 'NULL');
                    $OOrdOK = ((isset($OOrd[$i])) ? $OOrd[$i] : 'NULL');
                    $NoteOK = ((isset($Note[$i])) ? "'".addslashes($Note[$i])."'" : 'NULL');
                    $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".addslashes($BarcodeNote[$i])."'" : 'NULL');
                    $PrevProcessOK = ((isset($PrevProcess[$i])) ? $PrevProcess[$i] : 'NULL');
                    $PrevOrdOK = ((isset($PrevOrd[$i])) ? $PrevOrd[$i] : 'NULL');
                    $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
                    $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
                    $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
                    $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
                    $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
                    $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
                    $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');

                    $queryWAI = "INSERT INTO workallocationitem 
                                (IDM, Ordinal, Product,	Carat, Qty,	Weight,	WorkOrder, WorkOrderOrd, Note, BarcodeNote, PrevProcess, PrevOrd, PrevType, TreeID, TreeOrd, Part, FG, 
                                StoneLoss, QtyLossStone, WaxOrder, BatchNo, WorkSchedule, WorkScheduleOrd) 
                                VALUES 
                                ($idspko, $no, $PIDOK, $RIDOK, $QtyOK, $WeightOK, $OIDOK, $OOrdOK, $NoteOK, $BarcodeNoteOK, $PrevProcessOK, $PrevOrdOK, NULL, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, 
                                $StoneLossOK, $QtyLossStoneOK, NULL, $BatchNoOK, NULL, NULL) ";
                    $dataWAI = FacadesDB::connection('erp')->insert($queryWAI);


                    // // Eloquent
                    // $dataWAI = workallocationitem::create([
                    //     'IDM' => $datas->ID,
                    //     'Ordinal' => $no,
                    //     'Product' => $PID[$i],
                    //     'Carat' => $RID[$i],
                    //     'Qty' => $Qty[$i],
                    //     'Weight' => $Weight[$i],
                    //     'WorkOrder' => $OID[$i],
                    //     'WorkOrderOrd' => $OOrd[$i],
                    //     'Note' => $Note[$i],
                    //     'BarcodeNote' => $BarcodeNote[$i],
                    //     'PrevProcess' => $PrevProcess[$i],
                    //     'PrevOrd' => $PrevOrd[$i],
                    //     'PrevType' => NULL,
                    //     'TreeID' => $TreeID[$i],
                    //     'TreeOrd' => $TreeOrd[$i],
                    //     'Part' => $Part[$i],
                    //     'FG' => $FG[$i],
                    //     'StoneLoss' => $StoneLoss[$i],
                    //     'QtyLossStone' => $QtyLossStone[$i],
                    //     'WaxOrder' => NULL,
                    //     'BatchNo' => $BatchNo[$i],
                    //     'WorkSchedule' => NULL,
                    //     'WorkScheduleOrd' => NULL
                    // ]);

                    $no++;
                }
            
                FacadesDB::connection('erp')->commit();
                $json_return = array(
                    'status' => 'Sukses',
                    'idspko' => $idspko,
                    'swspko' => $swspko,
                    'excludepic' => $excludepic,
                    'statuswg' => $statuswg,
                    'location' => $location
                );
                return response()->json($json_return,200);

            } catch (Exception $e) {
                FacadesDB::connection('erp')->rollBack();
                $json_return = array(
                    'status' => 'Failed',
                    'message' => 'Update Error !'
                );
                return response()->json($json_return,500);
            }
    
            // if($dataWAI == TRUE ){
            //     $data = array('status' => 'Sukses', 'idspko' => $idspko, 'swspko' => $swspko, 'excludepic' => $excludepic, 'statuswg' => $statuswg, 'location' => $location);		
            // }else{
            //     $data = array('status' => 'Gagal');		
            // }
        }

        // return response()->json($data);

    }

    public function updateTest(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        dd($request);
        $idspko = $request->idspko;
        $swspko = $request->swspko;
        $stone = 0;
        $karyawan = $request->karyawan;
        $karyawanid = $request->karyawanid;
        $tgl = $request->tgl;
        $kadar = $request->kadar;
        $proses = $request->proses;
        $workgroup = $request->workgroupid;
        $targetqtyWA = $request->qtyspko;
        $weightWA = $request->weightspko;

        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        $WorkAllocation = $request->WorkAllocation;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $Carat = $request->Carat;
        $WorkOrder = $request->WorkOrder;
        $Note = $request->Note;
        $BarcodeNote = $request->BarcodeNote;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $PID = $request->PID;
        $PrevProcess = $request->PrevProcess;
        $PrevOrd = $request->PrevOrd;
        $FG = $request->FG;
        $Part = $request->Part;
        $RID = $request->RID;
        $OID = $request->OID;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $BatchNo = $request->BatchNo;

        $jmlArr = count($WorkAllocation);
        
        // // Khusus Enamel - Cek Proses
        // $enamelExclude = array(48,89);
        // if(in_array($prosesID, $enamelExclude)){
        //     $excludepic = 1;
        // }else{
        //     $excludepic = 0;
        // }

        // if($location == 47){
        //     if($prosesID == 161 || $prosesID == 166){
        
        //         $querywg = "SELECT * FROM workgroupitem
        //                     WHERE IDM IN (647)
        //                     AND Employee = $karyawanid
        //                     ";
        //         $sqlwg = FacadesDB::connection('erp')->select($querywg);
        //         $rowcount = count($sqlwg);
        
        //         if($rowcount == 0){
        //             $statuswg = 'Kosong';
        //         }else{
        //             $statuswg = 'Ada';
        //         }
        //     }else if($prosesID == 48){
        
        //         $querywg = "SELECT * FROM workgroupitem
        //                     WHERE IDM IN (594)
        //                     AND Employee = $karyawanid
        //                     ";
        //         $sqlwg = FacadesDB::connection('erp')->select($querywg);
        //         $rowcount = count($sqlwg);
        
        //         if($rowcount == 0){
        //             $statuswg = 'Kosong';
        //         }else{
        //             $statuswg = 'Ada';
        //         }
                
        //     }else{
        //         $statuswg = 'Kosong';
        //     }
        
        // }else{
        //     $statuswg = 'Ada';
        // }

        // // UPDATE WORKALLOCATION - Update jika tidak ada perubahan pada value, return nya "Affected rows: 0", sehingga dianggap return valuenya "False"
        // $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
        // $queryWA = "UPDATE workallocation 
        //             SET
        //                 UserName = '$username',
        //                 TransDate = '$tgl',
        //                 Carat = $kadar,
        //                 Operation = $prosesID,
        //                 Employee = $karyawanid,
        //                 WorkGroup = $WorkGroupOK,
        //                 TargetQty = $targetqtyWA,
        //                 Weight = $weightWA,
        //                 Remarks = 'Update Laravel'
        //             WHERE 
        //                 ID = $idspko ";
        // // $dataWA = FacadesDB::connection('erp')->update($queryWA);

        // // UPDATE WORKALLOCATIONITEM
        // $deleteWAI = "DELETE FROM workallocationitem WHERE IDM = $idspko ";
        // // $sqldeleteWAI = FacadesDB::connection('erp')->delete($deleteWAI);

        // $no = 1;
        // for ($i = 0; $i < $jmlArr; $i++) {

        //     $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
        //     $RIDOK = ((isset($RID[$i])) ? $RID[$i] : 'NULL');
        //     $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
        //     $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
        //     $OIDOK = ((isset($OID[$i])) ? $OID[$i] : 'NULL');
        //     $NoteOK = ((isset($Note[$i])) ? "'".$Note[$i]."'" : 'NULL');
        //     $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".$BarcodeNote[$i]."'" : 'NULL');
        //     $PrevProcessOK = ((isset($PrevProcess[$i])) ? $PrevProcess[$i] : 'NULL');
        //     $PrevOrdOK = ((isset($PrevOrd[$i])) ? $PrevOrd[$i] : 'NULL');
        //     $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
        //     $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
        //     $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
        //     $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
        //     $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
        //     $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
        //     $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');

        //     $queryWAI = "INSERT INTO workallocationitem 
        //                 (IDM, Ordinal, Product,	Carat, Qty,	Weight,	WorkOrder, Note, BarcodeNote, PrevProcess, PrevOrd, PrevType, TreeID, TreeOrd, Part, FG, 
        //                 StoneLoss, QtyLossStone, WaxOrder, BatchNo, WorkSchedule, WorkScheduleOrd) 
        //                 VALUES 
        //                 ($idspko, $no, $PIDOK, $RIDOK, $QtyOK, $WeightOK, $OIDOK, $NoteOK, $BarcodeNoteOK, $PrevProcessOK, $PrevOrdOK, NULL, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, 
        //                 $StoneLossOK, $QtyLossStoneOK, NULL, $BatchNoOK, NULL, NULL) ";
        //     // $dataWAI = FacadesDB::connection('erp')->insert($queryWAI);

        //     $no++;
        // }
   
        // if($dataWAI == TRUE ){
        //     $data = array('status' => 'Sukses', 'idspko' => $idspko, 'swspko' => $swspko, 'excludepic' => $excludepic, 'statuswg' => $statuswg);		
        // }else{
        //     $data = array('status' => 'Gagal');		
        // }

        // return response()->json($data);
    }

    public function test3(Request $request){

        $note = addslashes($request->Note[0]);

        $NoteOK = ((isset($request->Note[0])) ? "'".addslashes($request->Note[0])."'" : 'NULL');

        $queryWAI = "INSERT INTO workallocationitem (note) values($NoteOK)";

        dd($queryWAI);
        $abc = mysql_real_escape_string($note);

        dd($abc);
    }

    public function simpanTest(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 4;
        }

        $location22 = sprintf("%02d", $location);

        dd($location22);

        $stone = 0;
        $karyawan = $request->karyawan;
        $karyawanid = $request->karyawanid;
        $tgl = $request->tgl;
        $kadar = $request->kadar;
        $proses = $request->proses;
        $workgroup = $request->workgroupid;
        $targetqtyWA = $request->qtyspko;
        $weightWA = $request->weightspko;

        // $cekspk = $request->cekspk;
        // if($cekspk == 0){
        //     $swtahun = 50;
        // }else{
        //     $swtahun = 0;
        // }

        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        $WorkAllocation = $request->WorkAllocation;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $Carat = $request->Carat;
        $WorkOrder = $request->WorkOrder;
        $Note = $request->Note;
        $BarcodeNote = $request->BarcodeNote;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $PID = $request->PID;
        $PrevProcess = $request->PrevProcess;
        $PrevOrd = $request->PrevOrd;
        $FG = $request->FG;
        $Part = $request->Part;
        $RID = $request->RID;
        $OID = $request->OID;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $BatchNo = $request->BatchNo;
        
        $jmlArr = count($WorkAllocation);
        
        // // Khusus Enamel - Cek Proses
        // $enamelExclude = array(48,89);
        // if(in_array($prosesID, $enamelExclude)){
        //     $excludepic = 1;
        // }else{
        //     $excludepic = 0;
        // }

        // if($location == 47){
        //     if($prosesID == 161 || $prosesID == 166){

        //         $querywg = "SELECT * FROM workgroupitem
        //                     WHERE IDM IN (647)
        //                     AND Employee = $karyawanid ";
        //         $sqlwg = FacadesDB::connection('erp')->select($querywg);
        //         $rowcount = count($sqlwg);
        
        //         if($rowcount == 0){
        //             $statuswg = 'Kosong';
        //         }else{
        //             $statuswg = 'Ada';
        //         }

        //     }else if($prosesID == 48){
        
        //         $querywg = "SELECT * FROM workgroupitem
        //                     WHERE IDM IN (594)
        //                     AND Employee = $karyawanid
        //                     ";
        //         $sqlwg = FacadesDB::connection('erp')->select($querywg);
        //         $rowcount = count($sqlwg);
        
        //         if($rowcount == 0){
        //             $statuswg = 'Kosong';
        //         }else{
        //             $statuswg = 'Ada';
        //         }
                
        //     }else{
        //         $statuswg = 'Kosong';
        //     }
        
        // }else{
        //     $statuswg = 'Ada';
        // }

        // // LAST ID - (WorkAllocation)
        // $query = "SELECT LAST+1 AS ID FROM lastid Where Module = 'WorkAllocation' ";
        // $data = FacadesDB::connection('erp')->select($query);
        // foreach ($data as $datas){}

        // $query2 = "UPDATE lastid SET LAST = $datas->ID WHERE Module = 'WorkAllocation' ";
        // $data2 = FacadesDB::connection('erp')->update($query2);

        // // CREATE NEW SW MAX 9999 SWOrdinal
        // // $query3 = "SELECT
        // //                 CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        // //                 DATE_FORMAT('$tgl', '%y') as tahun,
        // //                 LPad(MONTH('$tgl'), 2, '0' ) as bulan,
        // //                 CONCAT(DATE_FORMAT('$tgl', '%y'),'',LPad(MONTH('$tgl'), 2, '0' ),'".$location."',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        // //             FROM workallocation
        // //             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
        // // $data3 = FacadesDB::connection('erp')->select($query3);
        // // foreach ($data3 as $datas3){}

        // // CREATE NEW SW - SWOrdinal MORE THAN 9999
        // $queryMaxOrdinal = "SELECT Max(SWOrdinal)+1 SWOrdinal From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And SWMonth = MONTH('$tgl') And Location = $location ";
        // $maxOrdinal = FacadesDB::connection('erp')->select($queryMaxOrdinal);
        // foreach ($maxOrdinal as $maxOrdinals){}

        // $dateMonth = date("n", strtotime($tgl));

        // if($maxOrdinals->SWOrdinal > 9999){

        //     $querySW = "SELECT Max(SWMonth) SWMonth From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And Location = $location AND SWMONTH LIKE '%$dateMonth' AND SWMONTH > 12";
        //     $numSW = FacadesDB::connection('erp')->select($querySW);

        //     foreach ($numSW as $result){
        //         if($result->SWMonth == ($dateMonth+20)){
        //             $queryenvelope = "SELECT
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        //                                 DATE_FORMAT('$tgl', '%y') as tahun,
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END as bulan, 
        //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        //                             FROM workallocation
        //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+20 ";
        //         }else if($result->SWMonth == ($dateMonth+40)){
        //             $queryenvelope = "SELECT
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        //                                 DATE_FORMAT('$tgl', '%y') as tahun,
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END as bulan, 
        //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        //                             FROM workallocation
        //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+40 ";
        
        //         }else if($result->SWMonth == ($dateMonth+60)){
        //             $queryenvelope = "SELECT
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        //                                 DATE_FORMAT('$tgl', '%y') as tahun,
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END as bulan, 
        //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        //                             FROM workallocation
        //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+60 ";
        
        //         }else if($result->SWMonth == ($dateMonth+80)){
        //             $queryenvelope = "SELECT
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        //                                 DATE_FORMAT('$tgl', '%y') as tahun,
        //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END as bulan, 
        //                                 CONCAT(DATE_FORMAT('$tgl', '%y'),CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        //                             FROM workallocation
        //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+80 ";
        //         }
        //     }

        // }else{
        //     $queryenvelope = "SELECT
        //                         CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        //                         DATE_FORMAT('$tgl', '%y') as tahun,
        //                         LPad(MONTH('$tgl'), 2, '0' ) as bulan,
        //                         CONCAT(DATE_FORMAT('$tgl', '%y'),'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        //                     FROM workallocation
        //                     Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
        // }

        // // SPK AWALAN 'O'
        // // if($maxOrdinals->SWOrdinal > 9999){

        // //     $querySW = "SELECT Max(SWMonth) SWMonth From WorkAllocation Where SWYear = DATE_FORMAT('$tgl', '%y') And Location = $location AND SWMONTH LIKE '%$dateMonth' AND SWMONTH > 12";
        // //     $numSW = FacadesDB::connection('erp')->select($querySW);

        // //     foreach ($numSW as $result){
        // //         if($result->SWMonth == ($dateMonth+20)){
        // //             $queryenvelope = "SELECT
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        // //                                 DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END as bulan, 
        // //                                 CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '4' ) ELSE LPad(MONTH('$tgl'), 2, '2' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        // //                             FROM workallocation
        // //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+20 ";
        // //         }else if($result->SWMonth == ($dateMonth+40)){
        // //             $queryenvelope = "SELECT
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        // //                                 DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END as bulan, 
        // //                                 CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '6' ) ELSE LPad(MONTH('$tgl'), 2, '4' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        // //                             FROM workallocation
        // //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+40 ";
        
        // //         }else if($result->SWMonth == ($dateMonth+60)){
        // //             $queryenvelope = "SELECT
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        // //                                 DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END as bulan, 
        // //                                 CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '8' ) ELSE LPad(MONTH('$tgl'), 2, '6' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        // //                             FROM workallocation
        // //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+60 ";
        
        // //         }else if($result->SWMonth == ($dateMonth+80)){
        // //             $queryenvelope = "SELECT
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        // //                                 DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
        // //                                 CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END as bulan, 
        // //                                 CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN LPad(MONTH('$tgl'), 2, '9' ) ELSE LPad(MONTH('$tgl'), 2, '8' ) END,'$locationSW',LPad(CASE WHEN MAX( SWOrdinal )+1 > 9999 THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        // //                             FROM workallocation
        // //                             Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth = MONTH('$tgl')+80 ";
        // //         }
        // //     }

        // // }else{
        // //     $queryenvelope = "SELECT
        // //                         CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
        // //                         DATE_FORMAT('$tgl', '%y')+$swtahun as tahun,
        // //                         LPad(MONTH('$tgl'), 2, '0' ) as bulan,
        // //                         CONCAT(DATE_FORMAT('$tgl', '%y')+$swtahun,'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
        // //                     FROM workallocation
        // //                     Where Location = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
        // // }

        // $sqlenvelope = FacadesDB::connection('erp')->select($queryenvelope);
        // foreach ($sqlenvelope as $datas3){}

        // // INSERT WORKALLOCATION
        // $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
        // $queryWA = "INSERT INTO workallocation 
        //             (ID, EntryDate, UserName, SW, Freq, TransDate, Purpose, Carat, Location, Operation, Employee, TargetQty, Weight, Active, 
        //             SWYear, SWMonth, SWOrdinal, WorkGroup, Stone, Outsource, Remarks) 
        //             VALUES 
        //             ($datas->ID, now(), '$username', $datas3->Counter1, 1, '$tgl', 'Tambah', $kadar, $location, $prosesID, $karyawanid, $targetqtyWA, $weightWA, 'A', 
        //             $datas3->tahun, $datas3->bulan, $datas3->ID, $WorkGroupOK, $stone, 'N', 'Laravel') ";
        // $dataWA = FacadesDB::connection('erp')->insert($queryWA);

        // // INSERT WORKALLOCATIONITEM
        // $no = 1;
        // for ($i = 0; $i < $jmlArr; $i++) {

        //     $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
        //     $WorkAllocationOK = ((isset($WorkAllocation[$i])) ? $WorkAllocation[$i] : 'NULL');
        //     $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
        //     $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
        //     $CaratOK = ((isset($Carat[$i])) ? $Carat[$i] : 'NULL');
        //     $WorkOrderOK = ((isset($WorkOrder[$i])) ? $WorkOrder[$i] : 'NULL');
        //     $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".$BarcodeNote[$i]."'" : 'NULL');
        //     $NoteOK = ((isset($Note[$i])) ? "'".$Note[$i]."'" : 'NULL');
        //     $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
        //     $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
        //     $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
        //     $PrevProcessOK = ((isset($PrevProcess[$i])) ? $PrevProcess[$i] : 'NULL');
        //     $PrevOrdOK = ((isset($PrevOrd[$i])) ? $PrevOrd[$i] : 'NULL');
        //     $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
        //     $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
        //     $RIDOK = ((isset($RID[$i])) ? $RID[$i] : 'NULL');
        //     $OIDOK = ((isset($OID[$i])) ? $OID[$i] : 'NULL');
        //     $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
        //     $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
        //     $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');
           
        //     $queryWAI = "INSERT INTO workallocationitem 
        //                 (IDM, Ordinal, Product, Carat, Qty, Weight, WorkOrder, Note, BarcodeNote,
        //                 PrevProcess, PrevOrd, PrevType, TreeID, TreeOrd, Part, FG, StoneLoss, QtyLossStone, WaxOrder, BatchNo, WorkSchedule, WorkScheduleOrd) 
        //                 VALUES 
        //                 ($datas->ID, $no, $PIDOK, $RIDOK, $QtyOK, $WeightOK, $OIDOK, $NoteOK, $BarcodeNoteOK,
        //                 $PrevProcessOK, $PrevOrdOK, NULL, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, $StoneLossOK, $QtyLossStoneOK, NULL, $BatchNoOK, NULL, NULL)";
        //     $dataWAI = FacadesDB::connection('erp')->insert($queryWAI);

        //     $no++;
        // }

        // if($dataWA == TRUE && $dataWAI == TRUE){
        //     $data = array('status' => 'Sukses', 'idspko' => $datas->ID, 'swspko' => $datas3->Counter1, 'excludepic' => $excludepic, 'statuswg' => $statuswg, 'location' => $location);		
        // }else{
        //     $data = array('status' => 'Gagal');		
        // }

        // return response()->json($data);
    }

    public function apiApp(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        $curl = curl_init();

        $idspko = $request->idspko;
        $username = $request->username;

        if($location == NULL){
            $location = 12;
        }

        if($location == 12){
            $appoperation = 1; //Poles Manual
        }else{
            $appoperation = 1;
        }

        $query = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, IF(P2.SW IS NULL, NULL, P2.SW) FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq, Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                    CASE
                        WHEN A.FG IS NOT NULL THEN IF(G.SKU IS NULL, G.SW, G.SKU)
                        WHEN A.Part IS NOT NULL THEN IF(H.SKU IS NULL, H.SW, H.SKU)
                        ELSE 'NoData'
                    END PartSKU,
                    CASE
                        WHEN A.FG IS NOT NULL THEN G.Description
                        WHEN A.Part IS NOT NULL THEN H.Description
                        ELSE 'NoData'
                    END PartDesc,
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, WA.Employee EmployeeID, WA.Location, L.Description LocationName, ST.Description FGGroup,

                    (SELECT MAX(ValidDate) FROM rndnew.appmastercycletime) AS ValidDate,

                    IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                    WHEN F.Description LIKE '%Cincin%' THEN 80
                    WHEN F.Description LIKE '%Liontin%' THEN 70
                    WHEN F.Description LIKE '%Gelang%' THEN 170
                    ELSE 30
                    END ) AS MasterCycleTime,

                    (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                    WHEN F.Description LIKE '%Cincin%' THEN 80
                    WHEN F.Description LIKE '%Liontin%' THEN 70
                    WHEN F.Description LIKE '%Gelang%' THEN 170
                    ELSE 30
                    END
                    )) AS TotalTime,
                        
                    ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                    WHEN F.Description LIKE '%Cincin%' THEN 80
                    WHEN F.Description LIKE '%Liontin%' THEN 70
                    WHEN F.Description LIKE '%Gelang%' THEN 170
                    ELSE 30
                    END
                    )) / 
                    CASE 
                    WHEN DAYNAME(WA.TransDate) IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                    WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'L') THEN 22500 
                    WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'P') THEN 26100 
                    WHEN DAYNAME(WA.TransDate) = 'Saturday' THEN 17640 
                    ELSE 26100
                    END
                    ) * 100) , 2) AS Persen

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
                    Left Join WorkAllocation WA ON A.IDM=WA.ID
                    LEFT JOIN PRODUCT P1 ON A.FG=P1.ID
                    LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                    LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                    LEFT JOIN LOCATION L ON WA.LOCATION=L.ID
                    LEFT JOIN Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                Where A.IDM = $idspko
                Order By A.Ordinal";
        $data = FacadesDB::connection('erp')->select($query);


        foreach ($data as $datas){

            $arrayName = array(
                        "owner"=> $username,
                        "no_spko"=> $datas->NoSPKO, 
                        "item"=> $datas->PartSKU, 
                        "item_name"=> $datas->PartDesc,
                        "barang_jadi"=> $datas->PartSKU,
                        "sub_kategori"=> $datas->FDescription,
                        "employee_id"=> $datas->EmployeeID,
                        "jumlah"=> $datas->Qty,
                        "berat"=> $datas->Weight,
                        "workstation"=> $datas->LocationName,
                        "validdate"=> $datas->ValidDate,
                        "cycletime"=> $datas->MasterCycleTime,
                        "totalsecond"=> $datas->TotalTime,
                        "percent"=> $datas->Persen,
                        "finish_good_group"=> $datas->FGGroup
            );

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://erpnext.lestarigold.co.id/api/resource/SPKO',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  json_encode($arrayName),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: token a63e269289b1391:46e5bc866f0009a', //192.168.1.8 / token erp / API Key: a63e269289b1391 / API Secret: 46e5bc866f0009a
                    'Content-Type: application/json',
                    'Cookie: full_name=Guest; sid=Guest; system_user=no; user_id=Guest; user_image='
                ),
            ));
            $response = curl_exec($curl);
        }

        curl_close($curl);
        // echo $response;

    }

    public function insertWorkPercent(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        $idspko = $request->idspko;

        if($location == NULL){
            $location = 12;
        }

        if($location == 12){
            $appoperation = 1; //Poles Manual
        }else{
            $appoperation = 1;
        }

        $queryCek = "SELECT * FROM rndnew.appworkpercent WHERE IDSPKO=$idspko";
        $dataCek = FacadesDB::connection('erp')->select($queryCek);
        $rowcount = count($dataCek);


        $query = "SELECT IDM, WALocation, MCTOperation, SUM(TotalTime) TotalTime, SUM(Persen) Persen, WAEmployee, WADate FROM (
                    SELECT A.*,
                            P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                            T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                            Concat(A.TreeID, '-', A.TreeOrd) Tree,
                            If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                            -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto,
                            If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                            WA.TransDate WADate, WA.Location WALocation, W.TransDate PohonDate, Z.WorkAllocation, Z.ID IDD, 
                            CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, CONCAT(P.SW, ' ', C.Description) BarangName, O.RequireDate,
                            CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',WCI.Ordinal) NTHKOBefore, TMI.WorkSchedule IDRPH, WS.TransDate TglRPH, WOI.Remarks NoteMarketing, (A.StoneLoss+A.QtyLossStone) JmlBatu,
                            IF(MCT.Operation IS NOT NULL, MCT.Operation, CASE WHEN WA.Location=12 THEN 1 ELSE 1 END) MCTOperation, WA.Employee WAEmployee,
                            IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) AS Pcs, 
                            IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                            CASE 
                                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                    WHEN F.Description LIKE '%Cincin%' THEN 80
                                    WHEN F.Description LIKE '%Liontin%' THEN 70
                                    WHEN F.Description LIKE '%Gelang%' THEN 170
                                    ELSE 30
                            END ) AS MasterCycleTime,
                            (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                            CASE 
                                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                    WHEN F.Description LIKE '%Cincin%' THEN 80
                                    WHEN F.Description LIKE '%Liontin%' THEN 70
                                    WHEN F.Description LIKE '%Gelang%' THEN 170
                                    ELSE 30
                            END
                            )) AS TotalTime,
                            ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                                CASE 
                                WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                WHEN F.Description LIKE '%Cincin%' THEN 80
                                WHEN F.Description LIKE '%Liontin%' THEN 70
                                WHEN F.Description LIKE '%Gelang%' THEN 170
                                ELSE 30
                                END
                            )) / 
                                CASE 
                                WHEN DAYNAME(WA.TransDate) IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                                WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'L') THEN 22500 
                                WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'P') THEN 26100 
                                WHEN DAYNAME(WA.TransDate) = 'Saturday' THEN 17640 
                                ELSE 26100
                                END
                            ) * 100) , 2) AS Persen

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
                            Left Join WorkAllocation WA ON WA.ID=A.IDM
                            Left Join Employee EM ON EM.ID=WA.Employee
                            LEFT JOIN workcompletionitem WCI ON WCI.IDM=Z.ID AND WCI.Ordinal=A.PrevOrd
                            LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
                            LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=Z.WorkAllocation AND TMI.LinkFreq=Z.Freq AND TMI.LinkOrd=WCI.Ordinal
                            LEFT JOIN workschedule WS ON WS.ID=TMI.WorkSchedule
                            LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                        Where A.IDM = $idspko
                        Order By A.Ordinal
                    ) Results";
        $data = FacadesDB::connection('erp')->select($query);


        if($rowcount > 0){
            foreach($data as $datas){

                // Raw
                // $queryUpdate = "UPDATE rndnew.appworkpercent SET EntryDate=now(), TotalSecond=$datas->TotalTime, Percent=$datas->Persen, Operator=$datas->WAEmployee, WorkDate='$datas->WADate' WHERE IDSPKO=$idspko";
                // $dataWP = FacadesDB::connection('erp')->update($queryUpdate);

                // Eloquent
                $dataWP = appworkpercent::where('IDSPKO', $idspko)->update([
                    'EntryDate' => now(), 
                    'TotalSecond' => $datas->TotalTime,
                    'Percent' => $datas->Persen,
                    'Operator' => $datas->WAEmployee,
                    'WorkDate' => $datas->WADate
                ]);
            }

        }else{
            foreach($data as $datas){

                // Raw
                // $queryInsert = "INSERT INTO rndnew.appworkpercent(IDSPKO, EntryDate, Location, Operation, TotalSecond, Percent, Operator, WorkDate)
                //                 VALUES ($datas->IDM, now(), $datas->WALocation, $datas->MCTOperation, $datas->TotalTime, $datas->Persen, $datas->WAEmployee, '$datas->WADate')";
                // $dataWP = FacadesDB::connection('erp')->insert($queryInsert);

                // Eloquent
                $dataWP = appworkpercent::create([
                    'IDSPKO' => $datas->IDM,
                    'EntryDate' => now(),
                    'Location' => $datas->WALocation,
                    'Operation' => $datas->MCTOperation,
                    'TotalSecond' => $datas->TotalTime,
                    'Percent' => $datas->Persen,
                    'Operator' => $datas->WAEmployee,
                    'WorkDate' => $datas->WADate
                ]);
            }
        }
        
        if($dataWP == TRUE){
            $data = array('status' => 'Sukses');		
        }else{
            $data = array('status' => 'Gagal');		
        }

        return response()->json($data);


    }

    public function apiAppTest(Request $request, $idspko, $username){

        $location = session('location');
        $username = session('UserEntry');

        $curl = curl_init();

        $idspko = $request->idspko;
        $username = $request->username;

        if($location == NULL){
            $location = 12;
        }

        if($location == 12){
            $appoperation = 1; //Poles Manual
        }else{
            $appoperation = 1;
        }

        $query = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, IF(P2.SW IS NULL, NULL, P2.SW) FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq, Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, 
                    If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                    CASE
                        WHEN A.FG IS NOT NULL THEN IF(G.SKU IS NULL, G.SW, G.SKU)
                        WHEN A.Part IS NOT NULL THEN IF(H.SKU IS NULL, H.SW, H.SKU)
                        ELSE 'NoData'
                    END PartSKU,
                    CASE
                        WHEN A.FG IS NOT NULL THEN G.Description
                        WHEN A.Part IS NOT NULL THEN H.Description
                        ELSE 'NoData'
                    END PartDesc,
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, WA.Employee EmployeeID, WA.Location, L.Description LocationName,

                    (SELECT MAX(ValidDate) FROM rndnew.appmastercycletime) AS ValidDate,

                    IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                    WHEN F.Description LIKE '%Cincin%' THEN 80
                    WHEN F.Description LIKE '%Liontin%' THEN 70
                    WHEN F.Description LIKE '%Gelang%' THEN 170
                    ELSE 30
                    END ) AS MasterCycleTime,

                    (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                    WHEN F.Description LIKE '%Cincin%' THEN 80
                    WHEN F.Description LIKE '%Liontin%' THEN 70
                    WHEN F.Description LIKE '%Gelang%' THEN 170
                    ELSE 30
                    END
                    )) AS TotalTime,
                        
                    ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                    WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                    WHEN F.Description LIKE '%Cincin%' THEN 80
                    WHEN F.Description LIKE '%Liontin%' THEN 70
                    WHEN F.Description LIKE '%Gelang%' THEN 170
                    ELSE 30
                    END
                    )) / 
                    CASE 
                    WHEN DAYNAME(WA.TransDate) IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                    WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'L') THEN 22500 
                    WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'P') THEN 26100 
                    WHEN DAYNAME(WA.TransDate) = 'Saturday' THEN 17640 
                    ELSE 26100
                    END
                    ) * 100) , 2) AS Persen

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
                    Left Join WorkAllocation WA ON A.IDM=WA.ID
                    LEFT JOIN PRODUCT P1 ON A.FG=P1.ID
                    LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                    LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                    LEFT JOIN Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                    LEFT JOIN LOCATION L ON WA.LOCATION=L.ID
                Where A.IDM = $idspko
                Order By A.Ordinal";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);


        foreach ($data as $datas){

            $arrayName = array(
                        "owner"=> $username,
                        "no_spko"=> $datas->NoSPKO, 
                        "item"=> $datas->PartSKU, 
                        "item_name"=> $datas->PartDesc,
                        "barang_jadi"=> $datas->PartSKU,
                        "sub_kategori"=> $datas->FDescription,
                        "employee_id"=> $datas->EmployeeID,
                        "jumlah"=> $datas->Qty,
                        "berat"=> $datas->Weight,
                        "workstation"=> $datas->LocationName,
                        "validdate"=> $datas->ValidDate,
                        "cycletime"=> $datas->MasterCycleTime,
                        "totalsecond"=> $datas->TotalTime,
                        "percent"=> $datas->Persen
            );

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://erpnext.lestarigold.co.id/api/resource/SPKO',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  json_encode($arrayName),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: token a63e269289b1391:46e5bc866f0009a', //192.168.1.8 / token erp / API Key: a63e269289b1391 / API Secret: 46e5bc866f0009a
                    'Content-Type: application/json',
                    'Cookie: full_name=Guest; sid=Guest; system_user=no; user_id=Guest; user_image='
                ),
            ));
            $response = curl_exec($curl);
        }

        curl_close($curl);
        echo $response;

    }

    public function posting(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->idspko;

        if($id == NULL || $id == ''){
            $json_return = array('message' => 'ID SPKO Not Found!');
            return response()->json($json_return,404);
        }

        $querystatus = "SELECT A.*, B.SKU FROM workallocation A 
                        LEFT JOIN productcarat B ON A.Carat=B.ID
                        WHERE A.ID=$id ";
        $data = FacadesDB::connection('erp')->select($querystatus);

        if(count($data) == 0){
            $json_return = array('message' => 'Data SPKO Not Found!');
            return response()->json($json_return,404);
        }

        foreach ($data as $datas){}
        $statuscek = $datas->Active;
        $swspko = $datas->SW;
        $transdateSPKO = $datas->TransDate;
        $locationSPKO = $datas->Location;
        $caratSPKO = $datas->Carat;
        $operationSPKO = $datas->Operation;
        $swYearSPKO = $datas->SWYear;
        $swMonthSPKO = $datas->SWMonth;
        $swOrdinalSPKO = $datas->SWOrdinal;
        $caratSKU = $datas->SKU;

        // Cek Stok Harian (Public Function)
        $cekStokHarian = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

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

                $postikan = FacadesDB::connection('erp')->select("SELECT WAI.*, WA.SW, WA.Location FROM workallocationitem WAI JOIN workallocation WA ON WA.ID=WAI.IDM WHERE WAI.IDM = $id ORDER BY Ordinal ASC");     

                foreach ($postikan as $janda) {
            
                    $status = "C";                      // For credit (SPKO)
                    $tableitem = "workallocationitem";  // Tabel item
                    $userName = Auth::user()->name;     // User who post this transaction
                    $location = $janda->Location;       // Location SPKO
                    $product = $janda->Product;         // Ini nanti looping dari workallocationitem
                    $carat = $janda->Carat;             // Ini nanti looping dari workallocationitem
                    $Process = 'Production';            // Default
                    $cause = 'Usage';                   // todo: Usage (Stok Berkurang) (Untuk SPKO)
                    $SW = $janda->SW;                   // Ini nanti dapat dari tabel workallocationitem
                    $idSPKO = $janda->IDM;              // Ini nanti dapat dari tabel workallocationitem karena looping
                    $ordinal = $janda->Ordinal;         // Ini nanti dapat dari tabel workallocationitem karena looping
                    $workorder = $janda->WorkOrder;     // Ini nanti dapat dari tabel workallocationitem

                    // Run Posting Function
                    $postingFunction = $this->Public_Function->PostingERP($status, $tableitem, $userName, $location, $product, $carat, $Process, $cause, $SW, $idSPKO, $ordinal, $workorder);
                
                }

                if ($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']) {

                    // Update Status //

                    // Raw
                    // $statusaktif = "UPDATE workallocation SET Active='P', PostDate=now(), Remarks='Posting Laravel' Where ID=$id ";
                    // $aktif = FacadesDB::connection('erp')->update($statusaktif);

                    // Eloquent
                    $aktif = workallocation::where('ID', $id)->update([
                        'Active' => 'P',
                        'PostDate' => now(),
                        'Remarks' => 'Posting Laravel'
                    ]);


                    // Insert WorkAllocationResult //
                    $dataWA = FacadesDB::connection('erp')->select("SELECT * FROM workallocation WHERE ID=$id");
                    foreach ($dataWA as $super){}

                    // Raw
                    // $queryWAR = "INSERT INTO workallocationresult (SW, TargetQty, Weight, CompletionQty, CompletionWeight, AllocationDate, CompletionDate, AllocationFreq, CompletionFreq, Shrink, Carat, Location, Operation, Employee, ShrinkDate, WorkGroup, WaxOrder)
                    //             VALUES ($super->SW, $super->TargetQty, $super->Weight, 0, 0, now(), NULL, $super->Freq, NULL, NULL, $super->Carat, 
                    //             $super->Location, $super->Operation, $super->Employee, NULL, NULL, NULL)";
                    // $insertWAR = FacadesDB::connection('erp')->insert($queryWAR);

                    // Eloquent
                    $insertWAR = workallocationresult::create([
                        'SW' => $super->SW,
                        'TargetQty' => $super->TargetQty,
                        'Weight' => $super->Weight,
                        'CompletionQty' => 0,
                        'CompletionWeight' => 0,
                        'AllocationDate' => now(),
                        'CompletionDate' => NULL,
                        'AllocationFreq' => $super->Freq,
                        'CompletionFreq' => NULL,
                        'Shrink' => NULL,
                        'Carat' => $super->Carat,
                        'Location' => $super->Location,
                        'Operation' => $super->Operation,
                        'Employee' => $super->Employee,
                        'ShrinkDate' => NULL,
                        'WorkGroup' => NULL,
                        'WaxOrder' => NULL
                    ]);

                }

                $arrOp = array(123,124,128);
                if(in_array($operationSPKO, $arrOp) ){
                    if($operationSPKO == 124){
                        $type = 'C';
                    }else{
                        $type = 'M';
                    }

                    $dataBatchCB = FacadesDB::connection('erp')->select("SELECT A.*, LPAD(A.SWOrdinal, 4, 0) SWOrdinalBatch FROM BatchCB A WHERE A.Type='$type' AND A.Carat='$caratSPKO' AND A.SWYear='$swYearSPKO' AND A.SWMonth='$swMonthSPKO' ");
                    $rowcountOp = count($dataBatchCB);
                    foreach ($dataBatchCB as $datasBatchCB){}
                    $swOrd = $datasBatchCB->SWOrdinal;
                    $swOrdinalBatch = $datasBatchCB->SWOrdinalBatch;

                    if($operationSPKO == 124){
                        $type = 'C';
                        $swMonthOK = sprintf("%02d", $swMonthSPKO);
                        $batchno = 'BMC' . $swYearSPKO . $swMonthOK . $caratSKU . '-' . $swOrdinalBatch;
                    }else{
                        $type = 'M';
                        $swMonthOK = sprintf("%02d", $swMonthSPKO);
                        $batchno = 'BMP' . $swYearSPKO . $swMonthOK . $caratSKU . '-' . $swOrdinalBatch;
                    }

                    if($rowcountOp > 0){
                        $SWOrdinal = 1;
                        $queryInsBatch = "INSERT INTO BatchCB (Type, Carat, SWYear, SWMonth, SWOrdinal)
                                            VALUES ('$type', '$caratSPKO', '$swYearSPKO', '$swMonthSPKO', '$SWOrdinal') ";
                        $insBatch = FacadesDB::connection('erp')->insert($queryInsStockSC);
                    }else{
                        $SWOrdinal = $swOrd + 1;
                        $queryUpdBatch = "UPDATE BatchCB SET SWOrdinal='$SWOrdinal' WHERE Type='$type' AND Carat='$caratSPKO' AND SWYear='$swYearSPKO' AND SWMonth='$swMonthSPKO' ";
                        $insBatch = FacadesDB::connection('erp')->update($queryUpdBatch);
                    }

                    // Update WorkAllocationItem
                    $dataWAI = FacadesDB::connection('erp')->select("SELECT * FROM workallocationitem WHERE IDM=$id");
                    foreach ($dataWAI as $datasWAI){
                        $queryUpdWAI = "UPDATE WorkAllocationItem SET BatchNo='$batchno' WHERE IDM='$id' ";
                        $updWAI = FacadesDB::connection('erp')->update($queryUpdWAI);
                    }

                }

                if($location == 7){
                    $dataWAI = FacadesDB::connection('erp')->select("SELECT IF(Weight IS NULL, 0, Weight) Weight FROM (
                                                                        SELECT FORMAT(SUM(Weight),2) Weight FROM workallocationitem WHERE IDM=$id AND Product=93) AS result");
                    foreach ($dataWAI as $datasWAI){}
                    $weightSC = $datasWAI->Weight;

                    $dataSC = FacadesDB::connection('erp')->select("SELECT * FROM StockSC WHERE ID=(SELECT MAX(ID) FROM StockSC WHERE Carat='$caratSPKO')");
                    foreach ($dataSC as $datasSC){}
                    $weightSaldoSC = $datasSC->WeightSaldo;

                    $dataSaldo = FacadesDB::connection('erp')->select("SELECT MAX(ID) ID FROM StockSC WHERE Carat='$caratSPKO' ");
                    $rowcount = count($dataSaldo);
                    if($rowcount > 0){
                        $weightSaldo = $weightSaldoSC - $weightSC;
                    }else{
                        $weightSaldo = $weightSC * -1;
                    }

                    $queryInsStockSC = "INSERT INTO StockSC (UserName, Process, TransDate, LinkID, Carat, WeightIn, WeightOut, WeightSaldo)
                                        VALUES ('$username', 'Allocation', '$transdateSPKO', '$id', '$caratSPKO', 0, '$weightSC', '$weightSaldo') ";
                    $insStockSC = FacadesDB::connection('erp')->insert($queryInsStockSC);
                }

                FacadesDB::connection('erp')->commit();
                $json_return = array(
                    'status' => 'sukses',
                    'swspko' => $swspko,
                    'idspko' => $id,
                    'location' => $location,
                    'username' => $username
                );
                return response()->json($json_return,200);

            } catch (Exception $e) {
                FacadesDB::connection('erp')->rollBack();
                $json_return = array(
                    'status' => 'Failed',
                    'message' => 'Posting Error !'
                );
                return response()->json($json_return,500);
            }

            // if($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns'] && $insertWAR == TRUE){
            //     $data = array('status' => 'sukses', 'swspko' => $swspko, 'idspko' => $id, 'location' => $location, 'username' => $username);	
            // }else{
            //     $data = array('status' => 'gagal');	
            // }

        }else{
            // $data = array('status' => 'belumstokharian');
            $json_return = array('status' => 'belumstokharian');
            return response()->json($json_return,200);
        }

        // return response()->json($data,200);
    }

    public function barcodeUnit(Request $request){ //OK

        // Session
        $location = session('location');
        $username = session('UserEntry');

        // Default Location
        if($location == NULL){
            $location = 12;
        }

        // Default AppOperation Per Location
        if($location == 12){
            $appoperation = 1; // 1 = Operation Poles Manual
        }else{
            $appoperation = 1;
        }

        // Get Request Data
        $id = $request->id;
        $kadar = $request->carat;
        $karyawanid = $request->karyawanid;
        $wa = explode("-", $id);

        // Get Date
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Cek Gender Operator
        $queryOperator = "SELECT Sex From employee WHERE ID=$karyawanid";
        $dataOperator = FacadesDB::connection('erp')->select($queryOperator);
        foreach ($dataOperator as $datasOperator){}
        $genderOperator = $datasOperator->Sex;

        // Check Condition Input Scan
        if(count($wa) <> 3){
            $json_return = array('status' => 'NotFound');
            return response()->json($json_return,200);

        }else{
            // Get Data SW, Freq, and Ordinal
            $wasw = $wa[0];
            $wafreq = $wa[1];
            $waord = $wa[2];

            // Query Check TM Have WorkSchedule or Not
            $queryCek = "SELECT IDM, WorkOrder FROM transferrmitem WHERE WorkAllocation=$wasw AND LinkFreq=$wafreq AND LinkOrd=$waord AND WorkSchedule IS NOT NULL";
            $dataCek = FacadesDB::connection('erp')->select($queryCek);
            $rowcount = count($dataCek);

            // Query Get ID WorkOrder
            $queryCek2 = "SELECT B.IDM, B.WorkOrder 
                            FROM workcompletion A
                            LEFT JOIN workcompletionitem B ON A.ID=B.IDM 
                            WHERE A.WorkAllocation=$wasw AND A.Freq=$wafreq AND B.Ordinal=$waord";
            $dataCek2 = FacadesDB::connection('erp')->select($queryCek2);
            $rowcount2 = count($dataCek2);
            $idWO = $dataCek2[0]->WorkOrder;

            // Query Check If WorkOrder Still Active or Not 
            $dataWO = FacadesDB::connection('erp')->select("SELECT Active FROM workorder WHERE ID=$idWO");
            
            if(count($dataWO) == 0){
                $activeWO = true;
            }else{
                foreach ($dataWO as $datasWO){}
                $activeWOID = $datasWO->Active;
                if($activeWOID == 'A'){
                    $activeWO = true;
                }else{
                    $activeWO = false;
                }
            }

            // Get IDM and Ordinal NTHKO
            $queryDuplicate = "SELECT B.IDM, B.Ordinal FROM 
                                WorkCompletion A 
                                JOIN WorkCompletionItem B ON A.ID=B.IDM
                                WHERE A.WorkAllocation=$wasw AND A.Freq=$wafreq AND B.Ordinal=$waord
                                AND A.Active IN ('S') 
                                ";
                                // ('P','S') 
            $dataDuplicate= FacadesDB::connection('erp')->select($queryDuplicate);
            $rowcountDuplicate = count($dataDuplicate);

            // Cek SPKO Duplicate
            if($rowcountDuplicate > 0){

                foreach ($dataDuplicate as $datasDuplicate){}
                $IdmNthko = $datasDuplicate->IDM;
                $OrdNthko = $datasDuplicate->Ordinal;

                $queryCekDuplicate = "SELECT IDM FROM workallocationitem WAI
                                        JOIN workallocation WA ON WAI.IDM=WA.ID
                                        WHERE WAI.PrevProcess=$IdmNthko AND WAI.PrevOrd=$OrdNthko AND WA.Location=$location AND WA.Active IN ('S')"; // ('P','S') 
                $dataCekDuplicate= FacadesDB::connection('erp')->select($queryCekDuplicate);
                $rowNumDuplicate = count($dataCekDuplicate);

            }else{
                $rowNumDuplicate = 0;
            }

            $queryTrue = " IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', (I.Qty + I.RepairQty)*2, (I.Qty + I.RepairQty)) AS Pcs,
                            (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', (I.Qty + I.RepairQty)*2, (I.Qty + I.RepairQty)) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                            CASE 
                                WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                WHEN F.Description LIKE '%Cincin%' THEN 80
                                WHEN F.Description LIKE '%Liontin%' THEN 70
                                WHEN F.Description LIKE '%Gelang%' THEN 170
                                ELSE 30
                            END
                            )) AS TotalTime,
                            ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', (I.Qty + I.RepairQty)*2, (I.Qty + I.RepairQty)) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                            CASE 
                                WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                WHEN F.Description LIKE '%Cincin%' THEN 80
                                WHEN F.Description LIKE '%Liontin%' THEN 70
                                WHEN F.Description LIKE '%Gelang%' THEN 170
                                ELSE 30
                            END
                            )) / 
                            CASE 
                                WHEN DAYNAME('$datenow') IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                                WHEN (DAYNAME('$datenow') = 'Friday' AND '$genderOperator' = 'L') THEN 22500 
                                WHEN (DAYNAME('$datenow') = 'Friday' AND '$genderOperator' = 'P') THEN 26100 
                                WHEN DAYNAME('$datenow') = 'Saturday' THEN 17640
                                ELSE 26100
                            END
                            ) * 100) , 2) AS Persen ";

            $queryElse = "0 AS Pcs, 0 AS TotalTime, 0 AS Persen";

            $query2 = "SELECT C.ID, C.WorkAllocation, C.Freq, P.Description PDescription, R.SW RSW, I.Ordinal, Cast(F.SW As Char) FinishGood, Cast(O.SW As Char) WorkOrder, I.WorkOrderOrd,
                            I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight, I.StoneLoss, I.QtyLossStone, P.Description Product,  T.Description Carat,
                            O.SW OSW, F.SW FDescription, T.SW TSW, P.UseCarat, P.SW PSW, I.BatchNo, O.SWPurpose,
                            If(F.SW = 'RPRS', G.SW, If(I.FG Is Null, H.SW, If(I.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) ProductDetail,
                            -- If(F.SW = 'RPRS', G.Photo, If(I.FG Is Null, H.Photo, G.Photo)) ProductPhoto,
                            If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(I.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) ProductPhoto,
                            G.ID GID, P.ID PID, R.ID RID, O.ID OID, O.TotalQty, I.Note, I.BarcodeNote, I.LinkID,
                            I.LinkOrd, I.TreeID, I.TreeOrd, X.SW RubberPlate, I.Part, I.FG, I.IDM PrevProcess, I.Ordinal PrevOrd, 
                            CONCAT(C.workallocation,'-',C.Freq,'-',I.Ordinal) NTHKO, TMI.WorkSchedule RPH, ";
                        
                            if($location == 12){
                                $query2 .= $queryTrue;
                            }else{
                                $query2 .= $queryElse;
                            }

            $query2 .=  "   From WorkCompletion C
                            Join WorkCompletionItem I On C.ID = I.IDM And I.Carat = $kadar And If(C.Location <> 7, (((I.Qty + I.RepairQty) <> 0) Or ((I.Weight + I.RepairWeight) <> 0)), I.Product <> 255)
                            Join Product P On I.Product = P.ID
                            Join ProductCarat R On I.Carat = R.ID
                            Join WorkOrder O On I.WorkOrder = O.ID
                            Join Product F On O.Product = F.ID
                            Left Join ProductCarat T On O.Carat = T.ID
                            Left Join WaxTree W On I.TreeID = W.ID
                            Left Join RubberPlate X On W.SW = X.ID
                            Left Join Product G On I.FG = G.ID
                            Left Join Product H On I.Part = H.ID
                            LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=C.WorkAllocation AND TMI.LinkFreq=C.Freq AND TMI.LinkOrd=I.Ordinal
                            LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                        Where 
                            C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And I.Ordinal = $waord
                        ";

          
            $query = "SELECT C.ID, C.WorkAllocation, C.Freq, P.Description PDescription, R.SW RSW, I.Ordinal, Cast(F.SW As Char) FinishGood, Cast(O.SW As Char) WorkOrder, I.WorkOrderOrd,
                        I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight, I.StoneLoss, I.QtyLossStone, P.Description Product,  T.Description Carat,
                        O.SW OSW, F.SW FDescription, T.SW TSW, P.UseCarat, P.SW PSW, I.BatchNo, O.SWPurpose,
                        If(F.SW = 'RPRS', G.SW, If(I.FG Is Null, H.SW, If(I.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) ProductDetail,
                        -- If(F.SW = 'RPRS', G.Photo, If(I.FG Is Null, H.Photo, G.Photo)) ProductPhoto,
                        If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(I.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) ProductPhoto,
                        G.ID GID, P.ID PID, R.ID RID, O.ID OID, O.TotalQty, I.Note, I.BarcodeNote, I.LinkID,
                        I.LinkOrd, I.TreeID, I.TreeOrd, X.SW RubberPlate, I.Part, I.FG, I.IDM PrevProcess, I.Ordinal PrevOrd, ";

                        if($location == 12){
                            $query .= $queryTrue;
                        }else{
                            $query .= $queryElse;
                        }

            $query .= " From WorkCompletion C
                        Join WorkCompletionItem I On C.ID = I.IDM And I.Carat = $kadar And If(C.Location <> 7, (((I.Qty + I.RepairQty) <> 0) Or ((I.Weight + I.RepairWeight) <> 0)), I.Product <> 255)
                        Join Product P On I.Product = P.ID
                        Join ProductCarat R On I.Carat = R.ID
                        Join WorkOrder O On I.WorkOrder = O.ID
                        Join Product F On O.Product = F.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join WaxTree W On I.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On I.FG = G.ID
                        Left Join Product H On I.Part = H.ID
                        LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=C.WorkAllocation AND TMI.LinkFreq=C.Freq AND TMI.LinkOrd=I.Ordinal
                        LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                    Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And I.Ordinal = $waord ";
 
            // Filter Duplicate NTHKO Saat Membuat SPKO
            if($rowNumDuplicate > 0){
                $goto = array('status' => 'Duplicate');
                
            }else{

                if($rowcount2 > 0){
                    if($rowcount == 0){
                        $hasil = FacadesDB::connection('erp')->select($query);
                    }else{
                        $hasil = FacadesDB::connection('erp')->select($query2);
                    }
                }else{
                    $hasil = FacadesDB::connection('erp')->select($query);
                }
                
                $row = count($hasil);
                foreach ($hasil as $hasils){}

                if($row == 0){
                    $goto = array('status' => 'Kosong');	
                }else{

                    if($activeWO == false){

                        if($activeWOID == 'T'){
                            $goto = array('status' => 'SPK_T');
                        }elseif ($activeWOID == 'D') {
                            $goto = array('status' => 'SPK_D');
                        }elseif ($activeWOID == 'C') {
                            $goto = array('status' => 'SPK_C');
                        }
                
                    }else{

                        $WorkAllocation = ((isset($hasils->WorkAllocation)) ? $hasils->WorkAllocation : '');
                        $Freq = ((isset($hasils->Freq)) ? $hasils->Freq : '');
                        $Ordinal = ((isset($hasils->Ordinal)) ? $hasils->Ordinal : '');
                        $WorkOrder = ((isset($hasils->WorkOrder)) ? $hasils->WorkOrder : '');
                        $FinishGood = ((isset($hasils->FinishGood)) ? $hasils->FinishGood : '');
                        $Carat = ((isset($hasils->Carat)) ? $hasils->Carat : '');
                        $TotalQty = ((isset($hasils->TotalQty)) ? $hasils->TotalQty : '');
                        $Product = ((isset($hasils->Product)) ? $hasils->Product : '');
                        $Qty = ((isset($hasils->Qty)) ? $hasils->Qty : '');
                        $Weight = ((isset($hasils->Weight)) ? $hasils->Weight : '');
                        $StoneLoss = ((isset($hasils->StoneLoss)) ? $hasils->StoneLoss : '');
                        $QtyLossStone = ((isset($hasils->QtyLossStone)) ? $hasils->QtyLossStone : '');
                        $BarcodeNote = ((isset($hasils->BarcodeNote)) ? $hasils->BarcodeNote : '');
                        $Note = ((isset($hasils->Note)) ? $hasils->Note : '');
                        $RubberPlate = ((isset($hasils->RubberPlate)) ? $hasils->RubberPlate : '');
                        $ProductDetail = ((isset($hasils->ProductDetail)) ? $hasils->ProductDetail : '');
                        $TreeID = ((isset($hasils->TreeID)) ? $hasils->TreeID : '');
                        $TreeOrd = ((isset($hasils->TreeOrd)) ? $hasils->TreeOrd : '');
                        $BatchNo = ((isset($hasils->BatchNo)) ? $hasils->BatchNo : '');
                        $ProductPhoto = ((isset($hasils->ProductPhoto)) ? $hasils->ProductPhoto : '');
                        $PID = ((isset($hasils->PID)) ? $hasils->PID : '');
                        $PrevProcess = ((isset($hasils->PrevProcess)) ? $hasils->PrevProcess : '');
                        $PrevOrd = ((isset($hasils->PrevOrd)) ? $hasils->PrevOrd : '');
                        $FG = ((isset($hasils->FG)) ? $hasils->FG : '');
                        $Part = ((isset($hasils->Part)) ? $hasils->Part : '');
                        $RID = ((isset($hasils->RID)) ? $hasils->RID : '');
                        $OID = ((isset($hasils->OID)) ? $hasils->OID : '');
                        $OOrd = ((isset($hasils->WorkOrderOrd)) ? $hasils->WorkOrderOrd : '');

                        $TotalTime = ((isset($hasils->TotalTime)) ? $hasils->TotalTime : 0);
                        $Persen = ((isset($hasils->Persen)) ? $hasils->Persen : 0);
                    
                        $goto = array('WorkAllocation' => $WorkAllocation, 'Freq' => $Freq, 'Ordinal' => $Ordinal, 'WorkOrder' => $WorkOrder, 'FinishGood' => $FinishGood, 'Carat' => $Carat, 'TotalQty' => $TotalQty,
                                'Product' => $Product, 'Qty' => $Qty, 'Weight' => $Weight, 'StoneLoss' => $StoneLoss, 'QtyLossStone' => $QtyLossStone, 'BarcodeNote' => $BarcodeNote, 'Note' => $Note, 
                                'RubberPlate' => $RubberPlate, 'ProductDetail' => $ProductDetail, 'TreeID' => $TreeID, 'TreeOrd' => $TreeOrd, 'BatchNo' => $BatchNo, 'ProductPhoto' => $ProductPhoto,
                                'PID' => $PID, 'PrevProcess' => $PrevProcess, 'PrevOrd' => $PrevOrd, 'FG' => $FG, 'Part' => $Part, 'RID' => $RID, 'OID' => $OID, 'OOrd' => $OOrd,
                                'CaratID' => $kadar, 'TotalTime' => $TotalTime, 'Persen' => $Persen, 'status' => 'Sukses');
                    }
                }
            }
        }
        return response()->json($goto);
    }

    public function barcodeAll(Request $request){ //OK

        // Session
        $location = session('location');
        $username = session('UserEntry');

        // Default Location
        if($location == NULL){
            $location = 12;
        }

        // Default AppOperation Per Location
        if($location == 12){
            $appoperation = 1; // 1 = Operation Poles Manual
        }else{
            $appoperation = 1;
        }

        // Get Request Data
        $id = $request->id;
        $kadar = $request->carat;
        $karyawanid = $request->karyawanid;
        $wa = explode("-", $id);
        $proses = $request->proses;
        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        // Get Date
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Check Gender Operator
        $queryOperator = "SELECT Sex From employee WHERE ID=$karyawanid";
        $dataOperator = FacadesDB::connection('erp')->select($queryOperator);
        foreach ($dataOperator as $datasOperator){}
        $genderOperator = $datasOperator->Sex;
  
        // Check Condition Input Scan
        if(count($wa) > 3 || count($wa) < 2){
            $json_return = array('status' => 'NotFound');
            return response()->json($json_return,200);

        }else{
            // Get Data SW and Freq
            $wasw = $wa[0];
            $wafreq = $wa[1];

            // Query IF Location is 'Poles'
            $queryTrue = " IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', (I.Qty + I.RepairQty)*2, (I.Qty + I.RepairQty)) AS Pcs,
                            (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', (I.Qty + I.RepairQty)*2, (I.Qty + I.RepairQty)) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                            CASE 
                                WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                WHEN F.Description LIKE '%Cincin%' THEN 80
                                WHEN F.Description LIKE '%Liontin%' THEN 70
                                WHEN F.Description LIKE '%Gelang%' THEN 170
                                ELSE 30
                            END
                            )) AS TotalTime,
                            ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', (I.Qty + I.RepairQty)*2, (I.Qty + I.RepairQty)) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                            CASE 
                                WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                                WHEN F.Description LIKE '%Cincin%' THEN 80
                                WHEN F.Description LIKE '%Liontin%' THEN 70
                                WHEN F.Description LIKE '%Gelang%' THEN 170
                                ELSE 30
                            END
                            )) / 
                            CASE 
                                WHEN DAYNAME('$datenow') IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                                WHEN (DAYNAME('$datenow') = 'Friday' AND '$genderOperator' = 'L') THEN 22500 
                                WHEN (DAYNAME('$datenow') = 'Friday' AND '$genderOperator' = 'P') THEN 26100 
                                WHEN DAYNAME('$datenow') = 'Saturday' THEN 17640
                                ELSE 26100
                            END
                            ) * 100) , 2) AS Persen ";

            // Query IF Location is not 'Poles'
            $queryElse = "0 AS Pcs, 0 AS TotalTime, 0 AS Persen";

            // Main Query to Show Data from Scan Barcode
            $query = "SELECT C.ID, C.WorkAllocation, C.Freq, P.Description PDescription, R.SW RSW, I.Ordinal, Cast(F.SW As Char) FinishGood, Cast(O.SW As Char) WorkOrder, I.WorkOrderOrd,
                            I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight, I.StoneLoss, I.QtyLossStone, P.Description Product,  T.Description Carat,
                            O.SW OSW, F.SW FDescription, T.SW TSW, P.UseCarat, P.SW PSW, I.BatchNo, O.SWPurpose,
                            If(F.SW = 'RPRS', G.SW, If(I.FG Is Null, H.SW, If(I.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) ProductDetail,
                            If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(I.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) ProductPhoto,
                            G.ID GID, P.ID PID, R.ID RID, O.ID OID, O.TotalQty, I.Note, I.BarcodeNote, I.LinkID,
                            I.LinkOrd, I.TreeID, I.TreeOrd, X.SW RubberPlate, I.Part, I.FG, I.IDM PrevProcess, I.Ordinal PrevOrd, ";

                        // Query Condition based on Location
                        if($location == 12){
                            $query .= $queryTrue;
                        }else{
                            $query .= $queryElse;
                        }

            // Continuation of the Main Query
            $query .= " From WorkCompletion C
                            Join WorkCompletionItem I On C.ID = I.IDM And I.Carat = ".$kadar." And If(C.Location <> 7, (((I.Qty + I.RepairQty) <> 0) Or ((I.Weight + I.RepairWeight) <> 0)), I.Product <> 255)
                            Join Product P On I.Product = P.ID
                            Join ProductCarat R On I.Carat = R.ID
                            Join WorkOrder O On I.WorkOrder = O.ID
                            Join Product F On O.Product = F.ID
                            Left Join ProductCarat T On O.Carat = T.ID
                            Left Join WaxTree W On I.TreeID = W.ID
                            Left Join RubberPlate X On W.SW = X.ID
                            Left Join Product G On I.FG = G.ID
                            Left Join Product H On I.Part = H.ID
                            LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                        Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq AND P.ID = $prosesProduct";
                        
                        // Query Condition based on Location
                        // $queryProses = " AND P.ID = $prosesProduct";
                        // if($location == 48 || $location == 12){
                        //     $query .= $queryProses;
                        // }
            
            // Run the Query
            $hasil = FacadesDB::connection('erp')->select($query);

            // Get RowCount of the Query
            $rowcountHasil = count($hasil);

            // Check Condition based on RowCount Main Query
            if($rowcountHasil == 0){
                $json_return = array('status' => 'NotFound');
                return response()->json($json_return,200);

            }else{

                // Declare Array Variable 
                $arrWorkAllocation = array();
                $arrFreq = array();
                $arrOrdinal = array();
                $arrWorkOrder = array();
                $arrFinishGood = array();
                $arrCarat = array();
                $arrTotalQty = array();
                $arrProduct = array();
                $arrQty= array();
                $arrWeight = array();
                $arrStoneLoss = array();
                $arrQtyLossStone = array();
                $arrBarcodeNote = array();
                $arrNote = array();
                $arrRubberPlate = array();
                $arrProductDetail = array();
                $arrTreeID = array();
                $arrTreeOrd = array();
                $arrBatchNo = array();
                $arrProductPhoto = array();
                $arrPID = array();
                $arrPrevProcess = array();
                $arrPrevOrd = array();
                $arrFG = array();
                $arrPart = array();
                $arrRID = array();
                $arrOID = array();
                $arrOOrd = array();
                $arrTotalTime = array();
                $arrPersen = array();
                
                // Looping through the Main Query Results
                foreach ($hasil as $hasils){

                    // Check if the Result is Null or Empty
                    $WorkAllocation = ((isset($hasils->WorkAllocation)) ? $hasils->WorkAllocation : '');
                    $Freq = ((isset($hasils->Freq)) ? $hasils->Freq : '');
                    $Ordinal = ((isset($hasils->Ordinal)) ? $hasils->Ordinal : '');
                    $WorkOrder = ((isset($hasils->WorkOrder)) ? $hasils->WorkOrder : '');
                    $FinishGood = ((isset($hasils->FinishGood)) ? $hasils->FinishGood : '');
                    $Carat = ((isset($hasils->Carat)) ? $hasils->Carat : '');
                    $TotalQty = ((isset($hasils->TotalQty)) ? $hasils->TotalQty : '');
                    $Product = ((isset($hasils->Product)) ? $hasils->Product : '');
                    $Qty = ((isset($hasils->Qty)) ? $hasils->Qty : '');
                    $Weight = ((isset($hasils->Weight)) ? $hasils->Weight : '');
                    $StoneLoss = ((isset($hasils->StoneLoss)) ? $hasils->StoneLoss : '');
                    $QtyLossStone = ((isset($hasils->QtyLossStone)) ? $hasils->QtyLossStone : '');
                    $BarcodeNote = ((isset($hasils->BarcodeNote)) ? $hasils->BarcodeNote : '');
                    $Note = ((isset($hasils->Note)) ? $hasils->Note : '');
                    $RubberPlate = ((isset($hasils->RubberPlate)) ? $hasils->RubberPlate : '');
                    $ProductDetail = ((isset($hasils->ProductDetail)) ? $hasils->ProductDetail : '');
                    $TreeID = ((isset($hasils->TreeID)) ? $hasils->TreeID : '');
                    $TreeOrd = ((isset($hasils->TreeOrd)) ? $hasils->TreeOrd : '');
                    $BatchNo = ((isset($hasils->BatchNo)) ? $hasils->BatchNo : '');
                    $ProductPhoto = ((isset($hasils->ProductPhoto)) ? $hasils->ProductPhoto : '');
                    $PID = ((isset($hasils->PID)) ? $hasils->PID : '');
                    $PrevProcess = ((isset($hasils->PrevProcess)) ? $hasils->PrevProcess : '');
                    $PrevOrd = ((isset($hasils->PrevOrd)) ? $hasils->PrevOrd : '');
                    $FG = ((isset($hasils->FG)) ? $hasils->FG : '');
                    $Part = ((isset($hasils->Part)) ? $hasils->Part : '');
                    $RID = ((isset($hasils->RID)) ? $hasils->RID : '');
                    $OID = ((isset($hasils->OID)) ? $hasils->OID : '');
                    $OOrd = ((isset($hasils->WorkOrderOrd)) ? $hasils->WorkOrderOrd : '');
                    $TotalTime = ((isset($hasils->TotalTime)) ? $hasils->TotalTime : 0);
                    $Persen = ((isset($hasils->Persen)) ? $hasils->Persen : 0);
                    
                    // Insert the Checking Above to the Array Variable
                    $arrWorkAllocation[] = $WorkAllocation;
                    $arrFreq[] = $Freq;
                    $arrOrdinal[] = $Ordinal;
                    $arrWorkOrder[] = $WorkOrder;
                    $arrFinishGood[] = $FinishGood;
                    $arrCarat[] = $Carat;
                    $arrTotalQty[] = $TotalQty;
                    $arrProduct[] = $Product;
                    $arrQty[] = $Qty;
                    $arrWeight[] = $Weight;
                    $arrStoneLoss[] = $StoneLoss;
                    $arrQtyLossStone[] = $QtyLossStone;
                    $arrBarcodeNote[] = $BarcodeNote;
                    $arrNote[] = $Note;
                    $arrRubberPlate[] = $RubberPlate;
                    $arrProductDetail[] = $ProductDetail;
                    $arrTreeID[] = $TreeID;
                    $arrTreeOrd[] = $TreeOrd;
                    $arrBatchNo[] = $BatchNo;
                    $arrProductPhoto[] = $ProductPhoto;
                    $arrPID[] = $PID;
                    $arrPrevProcess[] = $PrevProcess;
                    $arrPrevOrd[] = $PrevOrd;
                    $arrFG[] = $FG;
                    $arrPart[] = $Part;
                    $arrRID[] = $RID;
                    $arrOID[] = $OID;
                    $arrOOrd[] = $OOrd;
                    $arrTotalTime[] = $TotalTime;
                    $arrPersen[] = $Persen;   
                }
  
                // Push Array Variable to New Variable that ready to be returned to Ajax via 'JSON Return'
                $json_return = array('WorkAllocation' => $arrWorkAllocation, 'Freq' => $arrFreq, 'Ordinal' => $arrOrdinal, 'WorkOrder' => $arrWorkOrder,
                            'FinishGood' => $arrFinishGood, 'Carat' => $arrCarat, 'TotalQty' => $arrTotalQty, 'Product' => $arrProduct,
                            'Qty' => $arrQty, 'Weight' => $arrWeight, 'StoneLoss' => $arrStoneLoss, 'QtyLossStone' => $arrQtyLossStone,
                            'BarcodeNote' => $arrBarcodeNote, 'Note' => $arrNote, 'RubberPlate' => $arrRubberPlate, 'ProductDetail' => $arrProductDetail,
                            'TreeID' => $arrTreeID, 'TreeOrd' => $arrTreeOrd, 'BatchNo' => $arrBatchNo, 'ProductPhoto' => $arrProductPhoto, 
                            'PID' => $arrPID, 'PrevProcess' => $arrPrevProcess, 'PrevOrd' => $arrPrevOrd, 'FG' => $arrFG, 'Part' => $arrPart, 
                            'RID' => $arrRID, 'OID' => $arrOID, 'OOrd' => $arrOOrd, 'baris' => $rowcountHasil, 'CaratID' => $kadar, 'baris' => $rowcountHasil,
                            'TotalTime' => $arrTotalTime, 'Persen' => $arrPersen, 'status' => 'Success'
                            );
                return response()->json($json_return,200);
            }
        }

    }

    public function cetakGambar(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        if($location == 12){
            $pembagi = 4;
        }else{
            $pembagi = 3;
        }

        $query = "SELECT 
                    W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                    O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                    0 AS WeightOK
                From 
                    WorkAllocation W
                    Join Employee E On W.Employee = E.ID
                    Join Location L On W.Location = L.ID
                    Join Operation O On W.Operation = O.ID
                    Left Join ProductCarat C On W.Carat = C.ID
                Where W.SW = $sw And W.Freq = 1 ";
        $data = FacadesDB::connection('erp')->select($query);

        foreach ($data as $datas) {}
        $date1 = date("d/m/Y", strtotime($datas->TransDate));

        $query2 = "SELECT A.*,
				O.SW OSW, F.SW FDescription, 
                -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, 
                If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                G.SW GSW
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
			Where A.IDM = $id
			Order By A.Ordinal";
        $data2 = FacadesDB::connection('erp')->select($query2);

        return view('Produksi.PelaporanProduksi.SPKO.cetakgambar', compact('location','data','data2','pembagi','date1'));
    }

    public function cetak(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        // Query Header
        $query = "SELECT 
                    W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                    O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                    CASE
                        WHEN (SIGN(WAR.Shrink) = 1) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) = -1) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) = 0) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) IS NULL) THEN FORMAT(WAR.CompletionWeight,2)
                    END AS WeightOK, GROUP_CONCAT(EE.SW SEPARATOR ', ') WorkBy
                From 
                    WorkAllocation W
                    Join Employee E On W.Employee = E.ID
                    Join Location L On W.Location = L.ID
                    Join Operation O On W.Operation = O.ID
                    Left Join ProductCarat C On W.Carat = C.ID
                    LEFT JOIN workallocationresult WAR ON WAR.SW=W.SW
                    LEFT JOIN workgroupitem WGI ON W.WorkGroup=WGI.IDM
                    LEFT JOIN employee EE ON WGI.Employee=EE.ID
                Where W.SW = $sw And W.Freq = 1
                ";

        $query2 = "SELECT 
                        W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                        O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                        0 AS WeightOK, GROUP_CONCAT(EE.SW SEPARATOR ', ') WorkBy
                    From 
                        WorkAllocation W
                        Join Employee E On W.Employee = E.ID
                        Join Location L On W.Location = L.ID
                        Join Operation O On W.Operation = O.ID
                        Left Join ProductCarat C On W.Carat = C.ID
                        LEFT JOIN workgroupitem WGI ON W.WorkGroup=WGI.IDM
                        LEFT JOIN employee EE ON WGI.Employee=EE.ID
                    Where W.SW = $sw And W.Freq = 1
                    ";

        $querycek = "SELECT * FROM workallocationresult WHERE SW = $sw ";
        $datacek = FacadesDB::connection('erp')->select($querycek);
        $rowcek = count($datacek);

        if($rowcek > 0) {
            $data = FacadesDB::connection('erp')->select($query);
        }else{
            $data = FacadesDB::connection('erp')->select($query2);
        }
    
        foreach ($data as $datas){}
        $date1 = date("d/m/Y", strtotime($datas->TransDate));
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");
        $sisa = $datas->WWeight - $datas->WeightOK;

        // QueryItem
        $queryItem = "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto,
                        If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                        WA.TransDate WADate, W.TransDate PohonDate, Z.workallocation, Z.ID IDD, 
                        CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, CONCAT(P.SW, ' ', C.Description) BarangName, O.RequireDate,
                        CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',WCI.Ordinal) NTHKOBefore, TMI.WorkSchedule, WS.TransDate TglRPH, WOI.Remarks NoteMarketing, (A.StoneLoss+A.QtyLossStone) JmlBatu
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
                        Left Join WorkAllocation WA ON WA.ID=A.IDM
                        Left Join Employee EM ON EM.ID=WA.Employee
                        LEFT JOIN workcompletionitem WCI ON WCI.IDM=Z.ID AND WCI.Ordinal=A.PrevOrd
                        LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
                        LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=Z.WorkAllocation AND TMI.LinkFreq=Z.Freq AND TMI.LinkOrd=WCI.Ordinal AND (SELECT Active FROM transferrm WHERE ID=TMI.IDM) <> 'C' 
                        -- AND (SELECT ToLoc FROM transferrm WHERE ID=TMI.IDM) = $location
                        LEFT JOIN workschedule WS ON WS.ID=TMI.WorkSchedule
                    Where A.IDM = $id
                    GROUP BY Z.WorkAllocation, Z.Freq, WCI.Ordinal
                    Order By A.Ordinal";
        $dataItem = FacadesDB::connection('erp')->select($queryItem);

        $queryTotal = "SELECT SUM(A.TargetQty) qtySPKO, SUM(A.Weight) weightSPKO, IF(SUM(B.Qty) IS NULL,0,SUM(B.Qty)) qtyNTHKO, IF(SUM(B.Weight) IS NULL,0,SUM(B.Weight)) weightNTHKO
                        FROM workallocation A
                        LEFT JOIN workcompletion B ON A.SW=B.WorkAllocation
                        WHERE A.SW=$sw";
        $dataTotal = FacadesDB::connection('erp')->select($queryTotal);

        return view('Produksi.PelaporanProduksi.SPKO.cetak', compact('location','data','dataItem','date','date1','datenow','timenow','sisa','username','sw','dataTotal'));
    }

    public function cetak2(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        if($location == 12){
            $appoperation = 1; //Poles Manual
        }else{
            $appoperation = 1;
        }

        // Query Header
        $query = "SELECT 
                    W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                    O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                    CASE
                        WHEN (SIGN(WAR.Shrink) = 1) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) = -1) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) = 0) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) IS NULL) THEN FORMAT(WAR.CompletionWeight,2)
                    END AS WeightOK, GROUP_CONCAT(EE.SW SEPARATOR ', ') WorkBy
                From 
                    WorkAllocation W
                    Join Employee E On W.Employee = E.ID
                    Join Location L On W.Location = L.ID
                    Join Operation O On W.Operation = O.ID
                    Left Join ProductCarat C On W.Carat = C.ID
                    LEFT JOIN workallocationresult WAR ON WAR.SW=W.SW
                    LEFT JOIN workgroupitem WGI ON W.WorkGroup=WGI.IDM
                    LEFT JOIN employee EE ON WGI.Employee=EE.ID
                Where W.SW = $sw And W.Freq = 1
                ";

        $query2 = "SELECT 
                        W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                        O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                        0 AS WeightOK, GROUP_CONCAT(EE.SW SEPARATOR ', ') WorkBy
                    From 
                        WorkAllocation W
                        Join Employee E On W.Employee = E.ID
                        Join Location L On W.Location = L.ID
                        Join Operation O On W.Operation = O.ID
                        Left Join ProductCarat C On W.Carat = C.ID
                        LEFT JOIN workgroupitem WGI ON W.WorkGroup=WGI.IDM
                        LEFT JOIN employee EE ON WGI.Employee=EE.ID
                    Where W.SW = $sw And W.Freq = 1
                    ";

        $querycek = "SELECT ID FROM workallocationresult WHERE SW = $sw ";
        $datacek = FacadesDB::connection('erp')->select($querycek);
        $rowcek = count($datacek);

        if($rowcek > 0) {
            $data = FacadesDB::connection('erp')->select($query);
        }else{
            $data = FacadesDB::connection('erp')->select($query2);
        }
    
        foreach ($data as $datas){}
        $date1 = date("d/m/Y", strtotime($datas->TransDate));
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");
        $sisa = $datas->WWeight - $datas->WeightOK;

        // QueryItem
        $queryItem = "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, 
                        If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                        WA.TransDate WADate, W.TransDate PohonDate, Z.workallocation, Z.ID IDD, 
                        CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, CONCAT(P.SW, ' ', C.Description) BarangName, O.RequireDate,
                        CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',WCI.Ordinal) NTHKOBefore, TMI.WorkSchedule, WS.TransDate TglRPH, WOI.Remarks NoteMarketing, (A.StoneLoss+A.QtyLossStone) JmlBatu,
                        IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) AS Pcs, 
                        IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                        CASE 
                            WHEN F.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                        END ) AS MasterCycleTime,
                        (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                        CASE 
                            WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                        END
                        )) AS TotalTime,
                        ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                        CASE 
                            WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                        END
                        )) / 
                        CASE 
                            WHEN DAYNAME('WA.TransDate') IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                            WHEN (DAYNAME('WA.TransDate') = 'Friday' AND EM.Sex = 'L') THEN 22500 
                            WHEN (DAYNAME('WA.TransDate') = 'Friday' AND EM.Sex = 'P') THEN 26100 
                            WHEN DAYNAME('WA.TransDate') = 'Saturday' THEN 17640 
                            ELSE 26100
                        END
                        ) * 100) , 2) AS Persen

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
                        Left Join WorkAllocation WA ON WA.ID=A.IDM
                        Left Join Employee EM ON EM.ID=WA.Employee
                        LEFT JOIN workcompletionitem WCI ON WCI.IDM=Z.ID AND WCI.Ordinal=A.PrevOrd
                        LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
                        LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=Z.WorkAllocation AND TMI.LinkFreq=Z.Freq AND TMI.LinkOrd=WCI.Ordinal AND (SELECT Active FROM transferrm WHERE ID=TMI.IDM) <> 'C' AND (SELECT ToLoc FROM transferrm WHERE ID=TMI.IDM) = $location
                        LEFT JOIN workschedule WS ON WS.ID=TMI.WorkSchedule
                        LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                    Where A.IDM = $id
                    GROUP BY Z.WorkAllocation, Z.Freq, WCI.Ordinal
                    Order By A.Ordinal";
                    // dd($queryItem);
        $dataItem = FacadesDB::connection('erp')->select($queryItem);

        return view('Produksi.PelaporanProduksi.SPKO.cetak2', compact('location','data','dataItem','date','date1','datenow','timenow','sisa','username','sw'));
    }

    public function lihatPersenKerja(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
            $iddept = 26;
        }



        if($location == 12){
            $appoperation = 1; //ID=1 -> Poles Manual
        }else{
            $appoperation = 1;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");
        $dateyesterday = $date->modify("-1 days")->format('Y-m-d'); 

        $queryMaxDate = "SELECT MAX(MaxDate) MaxDate FROM
                            (SELECT DATE(creation) MaxDate FROM tabSPKO GROUP BY DATE(creation)) as Result
                            WHERE MaxDate < '$datenow'";
        $dataMaxDate = FacadesDB::connection('erpnext')->select($queryMaxDate);
        foreach ($dataMaxDate as $datasMaxDate){}
        $lastMaxDate = $datasMaxDate->MaxDate;


        // $query = "SELECT 
        //                 A.IDSPKO, A.WorkDate, SUM(TotalSecond) WorkTime, SUM(Percent) WorkPercent, B.Name OperationName, C.Description OperatorName
        //             FROM 
        //                 rndnew.appworkpercent A
        //                 LEFT JOIN rndnew.appoperation B ON A.Operation=B.ID
        //                 LEFT JOIN employee C ON A.Operator=C.ID
        //             WHERE 
        //                 A.Location = $location
        //                 AND A.WorkDate BETWEEN '$dateyesterday' AND '$datenow'
        //             GROUP BY A.WorkDate, A.Operator
        //             ORDER BY A.WorkDate DESC, SUM(A.Percent) ASC ";

        $query = "SELECT 
                    B.IDSPKO, IF(B.WorkDate IS NOT NULL, B.WorkDate, CURDATE()) WorkDate, SUM(B.TotalSecond) WorkTime, SUM(B.Percent) WorkPercent, C.Name OperationName, A.Description OperatorName, A.ID AID
                FROM employee A
                    LEFT JOIN rndnew.appworkpercent B ON A.ID=B.Operator AND B.Location=$location AND B.Operation=$appoperation AND B.WorkDate BETWEEN '$datenow' AND '$datenow'
                    LEFT JOIN rndnew.appoperation C ON B.Operation=C.ID
                WHERE 
                    A.Department=$iddept AND A.Active='Y' AND `RANK` = 'Operator'
                GROUP BY B.WorkDate, A.Description
                ORDER BY B.WorkDate DESC, SUM(B.Percent) ASC, A.Description ASC";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT 
                        A.ID, IF(B.Percent IS NULL, 0, SUM(B.Percent)) Persen
                    FROM employee A
                        LEFT JOIN rndnew.appworkpercent B ON A.ID=B.Operator AND B.Location=$location AND B.Operation=$appoperation AND B.WorkDate = (SELECT MAX(WorkDate) FROM rndnew.appworkpercent WHERE Location=$location AND Operation=$appoperation AND WorkDate < '$datenow') 
                    WHERE 
                        A.Department=$iddept AND A.Active='Y' AND A.`RANK` = 'Operator'
                    GROUP BY A.Description
                    ORDER BY A.Description ASC ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        // cek spko hari ini yg belum sempat dikerjakan hari ini
        $query3 = "SELECT employee_id, sum(totalsecond) totalsecond, sum(percent) percent, employee_name
                from (
                    select a.*, b.total_detik, b.employee_name
                    from tabSPKO a
                    left join `tabWork Log` b on a.no_spko=b.spko
                    where 
                    a.creation like '%$lastMaxDate%'
                    and b.creation not like '%$lastMaxDate%'
                    -- and b.creation is null
                    group by a.no_spko
                ) result
                group by employee_id ";
        $data3 = FacadesDB::connection('erpnext')->select($query3);
        $rowcount3 = count($data3);

        // cek spko total di hari kemarin
        $query4 = "SELECT employee_id, sum(totalsecond) totalsecond, sum(percent) percent, employee_name
        from (
            select a.*, b.total_detik, b.employee_name
            from tabSPKO a
            left join `tabWork Log` b on a.no_spko=b.spko
            where 
            a.creation like '%$lastMaxDate%'
            group by a.no_spko
        ) result
        group by employee_id";
        $data4 = FacadesDB::connection('erpnext')->select($query4);


         // cek spko hari ini yg sudah dikerjakan hari ini
         $query5 = "SELECT employee_id, sum(totalsecond) totalsecond, sum(percent) percent, employee_name
                from (
                    select a.*, b.total_detik, b.employee_name
                    from tabSPKO a
                    left join `tabWork Log` b on a.no_spko=b.spko
                    where 
                    a.creation like '%$datenow%'
                    and b.creation like '%$datenow%'
                    group by a.no_spko
                ) result
                group by employee_id ";
        $data5 = FacadesDB::connection('erpnext')->select($query5);

        // cek worklog hari ini dan kemarin
        $query6 = "SELECT
                        A.employee_name, A.employee_id,
                        SUM(IF( DATE ( A.waktu_selesai ) = CURRENT_DATE(), A.total_detik, 0 )) as totaldetik_today,
                        SUM(IF( DATE ( A.waktu_selesai ) = CURRENT_DATE() - 1, A.total_detik, 0 )) as totaldetik_yesterday,
        
                        CASE WHEN B.gender = 'Male' AND DAYNAME(CURRENT_DATE()) = 'Friday' THEN 22500
                        WHEN DAYNAME(CURRENT_DATE()) = 'Saturday' THEN 17640
                        ELSE 26100 END AS WorkTime,
                        
                        ROUND(((SUM(
                        IF( DATE ( A.waktu_selesai ) = CURRENT_DATE(), A.total_detik, 0 )) / 
                        CASE WHEN B.gender = 'Male' AND DAYNAME(CURRENT_DATE()) = 'Friday' THEN 22500
                        WHEN DAYNAME(CURRENT_DATE()) = 'Saturday' THEN 17640
                        ELSE 26100 END)*100), 2) persentase_today,
                        
                        ROUND(((SUM(
                        IF( DATE ( A.waktu_selesai ) = CURRENT_DATE() - 1, A.total_detik, 0 )) / 
                        CASE WHEN B.gender = 'Male' AND DAYNAME(CURRENT_DATE() - 1) = 'Friday' THEN 22500
                        WHEN DAYNAME(CURRENT_DATE() - 1) = 'Saturday' THEN 17640
                        ELSE 26100 END)*100), 2) persentase_yesterday
        
                    FROM
                        `tabWork Log` A 
                        JOIN `tabEmployee` B ON B.name = A.employee
                    WHERE
                        DATE ( A.waktu_selesai ) = CURRENT_DATE() OR DATE ( A.waktu_selesai ) = CURRENT_DATE() - 1 
                    GROUP BY
                        A.employee_name";
                    // dd($query6);
        $data6 = FacadesDB::connection('erpnext')->select($query6);


        // $query3 = "SELECT * FROM tabSPKO LIMIT 100";
        // $data3 = FacadesDB::connection('erpnext')->select($query3);
        // dd($data3);

        // $query = "SELECT employee_id, sum(totalsecond) totalsecond, sum(percent) percent FROM tabSPKO where creation like '%2023-04-05%'
        //             group by employee_id ";
        
        // $query = "SELECT employee_id, employee_name, SUM(total_detik) total_detik, FORMAT(((SUM(total_detik)/26100)*100),2) persen FROM `tabWork Log` where creation like '%2023-04-05%'
        // group by employee_id ";

        $returnHTML = view('Produksi.PelaporanProduksi.SPKO.persenkerja', compact('data','data2','data3','data4','data6','rowcount3'))->render();
        return response()->json( array('html' => $returnHTML) );

    }

    // public function simpanWorkPercent(Request $request){
    //     $location = session('location');
    //     $UserEntry = session('UserEntry');
    //     $iddept = session('iddept');

    //     if($location == NULL){
    //         $location = 12;
    //     }

    //     $idspko = $request->idspko;

    //     if($location == 12){
    //         $appoperation = 1; //ID=1 -> Poles Manual /rndnew.appoperation
    //     }else{
    //         $appoperation = 1;
    //     }

    //     $query = "SELECT IDM, WALocation, MCTOperation, SUM(TotalTime) TotalTime, SUM(Persen) Persen, WAEmployee, WADate FROM (
    //                 SELECT A.*,
    //                         P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
    //                         T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
    //                         Concat(A.TreeID, '-', A.TreeOrd) Tree,
    //                         If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
    //                         If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.TransDate WADate, WA.Location WALocation, W.TransDate PohonDate, Z.WorkAllocation, Z.ID IDD, 
    //                         CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, CONCAT(P.SW, ' ', C.Description) BarangName, O.RequireDate,
    //                         CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',WCI.Ordinal) NTHKOBefore, TMI.WorkSchedule IDRPH, WS.TransDate TglRPH, WOI.Remarks NoteMarketing, (A.StoneLoss+A.QtyLossStone) JmlBatu,
    //                         IF(MCT.Operation IS NOT NULL, MCT.Operation, CASE WHEN WA.Location=12 THEN 1 ELSE 1 END) MCTOperation, WA.Employee WAEmployee,
    //                         IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) AS Pcs, 
    //                         IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
    //                         CASE 
    //                                 WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
    //                                 WHEN F.Description LIKE '%Cincin%' THEN 80
    //                                 WHEN F.Description LIKE '%Liontin%' THEN 70
    //                                 WHEN F.Description LIKE '%Gelang%' THEN 170
    //                                 ELSE 30
    //                         END ) AS MasterCycleTime,
    //                         (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
    //                         CASE 
    //                                 WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
    //                                 WHEN F.Description LIKE '%Cincin%' THEN 80
    //                                 WHEN F.Description LIKE '%Liontin%' THEN 70
    //                                 WHEN F.Description LIKE '%Gelang%' THEN 170
    //                                 ELSE 30
    //                         END
    //                         )) AS TotalTime,
    //                         ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
    //                         CASE 
    //                                 WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
    //                                 WHEN F.Description LIKE '%Cincin%' THEN 80
    //                                 WHEN F.Description LIKE '%Liontin%' THEN 70
    //                                 WHEN F.Description LIKE '%Gelang%' THEN 170
    //                                 ELSE 30
    //                         END
    //                         )) / 
    //                         CASE 
    //                                 WHEN DAYNAME('WA.TransDate') IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
    //                                 WHEN (DAYNAME('WA.TransDate') = 'Friday' AND EM.Sex = 'L') THEN 22500 
    //                                 WHEN (DAYNAME('WA.TransDate') = 'Friday' AND EM.Sex = 'P') THEN 26100 
    //                                 WHEN DAYNAME('WA.TransDate') = 'Saturday' THEN 17640 
    //                                 ELSE 26100
    //                         END
    //                         ) * 100) , 2) AS Persen
                
    //                     From WorkAllocationItem A
    //                         Join Product P On A.Product = P.ID
    //                         Left Join ProductCarat C On A.Carat = C.ID
    //                         Left Join WorkOrder O On A.WorkOrder = O.ID
    //                         Left Join ProductCarat T On O.Carat = T.ID
    //                         Left Join Product F On O.Product = F.ID
    //                         Left Join WorkCompletion Z On A.PrevProcess = Z.ID
    //                         Left Join WaxTree W On A.TreeID = W.ID
    //                         Left Join RubberPlate X On W.SW = X.ID
    //                         Left Join Product G On A.FG = G.ID
    //                         Left Join Product H On A.Part = H.ID
    //                         Left Join WorkAllocation WA ON WA.ID=A.IDM
    //                         Left Join Employee EM ON EM.ID=WA.Employee
    //                         LEFT JOIN workcompletionitem WCI ON WCI.IDM=Z.ID AND WCI.Ordinal=A.PrevOrd
    //                         LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
    //                         LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=Z.WorkAllocation AND TMI.LinkFreq=Z.Freq AND TMI.LinkOrd=WCI.Ordinal
    //                         LEFT JOIN workschedule WS ON WS.ID=TMI.WorkSchedule
    //                         LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
    //                     Where A.IDM = $idspko
    //                     Order By A.Ordinal
    //                 ) Results";
    //     $data = FacadesDB::connection('erp')->select($query);

    //     foreach ($data as $datas){
    //         $queryInsert = "INSERT INTO rndnew.appworkpercent(IDSPKO, EntryDate, Location, Operation, TotalSecond, Percent, Operator, WorkDate) 
    //                             VALUES ($datas->IDM, now(), $datas->WALocation, $datas->MCTOperation, $datas->TotalTime, $datas->Persen, $datas->WAEmployee, '$datas->WADate') ";
    //         $dataInsert = FacadesDB::connection('erp')->insert($queryInsert);
    //         // dd($queryInsert);
    //     }

    //     if($dataInsert == TRUE){
    //         $data = array('status' => 'Sukses');		
    //     }else{
    //         $data = array('status' => 'Gagal');		
    //     }

    //     return response()->json($data);

    // }

    public function cetakBarcode(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        $query = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                    Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, 
                    If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                    WA.TransDate WADate, W.TransDate PohonDate, Z.workallocation, Z.ID IDD, 
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, WOI.Remarks NoteMarketing
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
                    Left Join WorkAllocation WA ON WA.ID=A.IDM
                    Left Join Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
                Where A.IDM = $id
                Order By A.Ordinal
                ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.SPKO.cetakbarcode', compact('location','data','username','sw','rowcount'));
    }

    public function cetakBarcodeDirect(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $id = $request->id;
        $sw = $request->sw;

        $query = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                    Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    -- If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, 
                    If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                    WA.TransDate WADate, W.TransDate PohonDate, Z.workallocation, Z.ID IDD, 
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, WOI.Remarks NoteMarketing
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
                    Left Join WorkAllocation WA ON WA.ID=A.IDM
                    Left Join Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
                Where A.IDM = $id
                Order By A.Ordinal
                ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        $returnHTML = view('Produksi.PelaporanProduksi.SPKO.cetakbarcodedirect', compact('data','rowcount'));

        $pdf = Pdf::loadHtml($returnHTML);
        $customPaper = array(0, 0, 210, 835);
        $pdf->setPaper($customPaper, 'portrait');

        $hasilpdf = $pdf->output();             
        file_put_contents(('C:/LestariERP/ProduksiPDF/'.$id.'.pdf'), $hasilpdf);

        return response()->json( array('success' => true, 'id' => $id) );
    }

    public function listspko(){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $query = "SELECT WA.ID, WA.SW FROM workallocation WA WHERE WA.Location = $location ORDER BY WA.ID DESC LIMIT 100";
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( array('listspko' => $data) );
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

            $dataReturn = array('NoSPK' => $NoSPK, 'WorkOrder' => $WorkOrder, 'TotalQty' => $TotalQty, 'ProductName' => $ProductName, 'Carat' => $CaratID, 'Kadar' => $CaratName, 'rowcount' => $rowcount);

        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }

    public function cariProduct(Request $request){ //OK
        $sw = $request->sw;
        $carat = $request->carat;
    
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

            $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName);
        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }

    public function testInsertWorkPercent(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        $idspko = $request->idspko;

        dd($request);
        
        if($location == NULL){
            $location = 99;
        }

        $appoperation = 1;

        $query = "SELECT IDM, WALocation, MCTOperation, SUM(TotalTime) TotalTime, SUM(Persen) Persen, WAEmployee, WADate FROM (
            SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                    Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    If(F.SW = 'RPRS', REPLACE(G.Photo, '.jpg', ''), If(A.FG Is Null, REPLACE(H.Photo, '.jpg', ''), REPLACE(G.Photo, '.jpg', ''))) GPhoto,
                    WA.TransDate WADate, WA.Location WALocation, W.TransDate PohonDate, Z.WorkAllocation, Z.ID IDD, 
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, CONCAT(P.SW, ' ', C.Description) BarangName, O.RequireDate,
                    CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',WCI.Ordinal) NTHKOBefore, TMI.WorkSchedule IDRPH, WS.TransDate TglRPH, WOI.Remarks NoteMarketing, (A.StoneLoss+A.QtyLossStone) JmlBatu,
                    IF(MCT.Operation IS NOT NULL, MCT.Operation, CASE WHEN WA.Location=12 THEN 1 ELSE 1 END) MCTOperation, WA.Employee WAEmployee,
                    IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) AS Pcs, 
                    IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                            WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                    END ) AS MasterCycleTime,
                    (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                    CASE 
                            WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                            WHEN F.Description LIKE '%Cincin%' THEN 80
                            WHEN F.Description LIKE '%Liontin%' THEN 70
                            WHEN F.Description LIKE '%Gelang%' THEN 170
                            ELSE 30
                    END
                    )) AS TotalTime,
                    ROUND( (( (IF(F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%', A.Qty*2, A.Qty) * IF(MCT.CycleTime IS NOT NULL,MCT.CycleTime,
                        CASE 
                        WHEN F.Description LIKE '%Anting%' OR F.Description LIKE '%Giwang%' THEN 30
                        WHEN F.Description LIKE '%Cincin%' THEN 80
                        WHEN F.Description LIKE '%Liontin%' THEN 70
                        WHEN F.Description LIKE '%Gelang%' THEN 170
                        ELSE 30
                        END
                    )) / 
                        CASE 
                        WHEN DAYNAME(WA.TransDate) IN ('Monday','Tuesday','Wednesday','Thursday') THEN 26100
                        WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'L') THEN 22500 
                        WHEN (DAYNAME(WA.TransDate) = 'Friday' AND EM.Sex = 'P') THEN 26100 
                        WHEN DAYNAME(WA.TransDate) = 'Saturday' THEN 17640 
                        ELSE 26100
                        END
                    ) * 100) , 2) AS Persen

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
                    Left Join WorkAllocation WA ON WA.ID=A.IDM
                    Left Join Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN workcompletionitem WCI ON WCI.IDM=Z.ID AND WCI.Ordinal=A.PrevOrd
                    LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG AND (SELECT COUNT(IDM) Jml FROM workorderitem WHERE IDM=WOI.IDM AND Product=WOI.Product)=1
                    LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=Z.WorkAllocation AND TMI.LinkFreq=Z.Freq AND TMI.LinkOrd=WCI.Ordinal
                    LEFT JOIN workschedule WS ON WS.ID=TMI.WorkSchedule
                    LEFT JOIN rndnew.appmastercycletime MCT ON O.Product=MCT.SubCategory AND MCT.Active=1 AND MCT.Location=$location AND MCT.Operation=$appoperation
                Where A.IDM = $idspko
                Order By A.Ordinal
            ) Results";
            // dd($query);
        $data = FacadesDB::connection('erp')->select($query);
        foreach ($data as $datas){}
        $jmlItem = count($data);

        // NULL, insert REAL NULL, when the type is int
        // 'NULL'/"NULL", insert 0, when the type is int
        // NULL, insert REAL NULL, when the type is varchar
        // 'NULL'/"NULL", insert string 'NULL', when the type is varchar

        $TotalTime = NULL;
        $TotalTime2 = NULL;
        $TotalTimeOK = ((isset($TotalTime)) ? $TotalTime : "NULL");
        $TotalTime2OK = ((isset($TotalTime2)) ? $TotalTime2 : NULL);
        $Remarks = ((isset($TotalTime)) ? $TotalTime : NULL);

        // // Cara Insert Loop 1
        // $no = 1;
        // for($i=0; $i<$jmlItem; $i++){
        //     // dd($datas);
        //     $testInsertWorkPercent = appworkpercent::create([
        //         'IDSPKO' => 999999,
        //         'EntryDate' => now(),
        //         'Location' => $location,
        //         'Operation' => $datas->MCTOperation,
        //         'TotalSecond' => $TotalTimeOK,
        //         'Percent' => $datas->Persen,
        //         'Operator' => $datas->WAEmployee,
        //         'WorkDate' => $datas->WADate,
        //         'Remarks' => $TotalTimeOK
        //     ]);
        //     $no++;
        // }

        // // Cara Insert Loop 2
        foreach ($data as $datas){
            // dd($datas);
            $testInsertWorkPercent = appworkpercent::create([
                'IDSPKO' => 1000000,
                'EntryDate' => now(),
                'Location' => $location,
                'Operation' => $datas->MCTOperation,
                'TotalSecond' => $TotalTime2OK,
                'Percent' => $datas->Persen,
                'Operator' => $datas->WAEmployee,
                'WorkDate' => $datas->WADate,
                // 'Remarks' => $request->workgroupid
                'Remarks' => NULL
            ]);
        }

        // $queryInsert = "INSERT INTO rndnew.appworkpercent(IDSPKO, EntryDate, Location, Operation, TotalSecond, Percent, Operator, WorkDate, Remarks)
        //                 VALUES ('1000001', now(), '$location', '$datas->MCTOperation', '$TotalTime2OK', '$datas->Persen', '$datas->WAEmployee', '$datas->WADate', '$Remarks')";
        // dd($queryInsert);
        // $dataInsert = FacadesDB::connection('erp')->insert($queryInsert);

        $datajson = array('status' => true);
        return response()->json($datajson);

    }
 
}
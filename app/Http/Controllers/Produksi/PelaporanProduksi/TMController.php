<?php

namespace App\Http\Controllers\Produksi\PelaporanProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Http\Controllers\Public_Function_Controller;

use \DateTime;
use \DateTimeZone;

use App\Models\erp\lastid;
use App\Models\erp\transferrm;
use App\Models\erp\transferrmitem;

use App\Models\tes_laravel\transferrm as transferrmtest;
use App\Models\tes_laravel\transferrmitem as transferrmitemtest;


class TMController extends Controller
{
    // Setup Public Function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index(){
        $location = session('location');
        $username = session('UserEntry');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 48;
        }

        // Tampil SPKO List
        $query = "SELECT 
                    TM.*
                FROM
                    transferrm TM
                WHERE
                    TM.FromLoc = $location
                    AND TM.Active IN ('P','A') 
                ORDER BY TM.ID DESC
                LIMIT 1000";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.TransferMaterial.index', compact('data','rowcount','iddept'));
    }

    public function lihat(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $id = $request->id;

        // query header
        $query = "SELECT 
                        T.*, E.SW ESW, E.Description EDescription, F.Department FDepartment, O.Department ODepartment, F.Description FDescription, O.Description ODescription
                    From TransferRM T
                        Join Employee E On T.Employee = E.ID
                        Join Location F On T.FromLoc = F.ID
                        Join Location O On T.ToLoc = O.ID
                    Where T.ID = $id 
                        AND (T.FromLoc = $location || T.ToLoc = $location)
                    ";
                    // dd($query);
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount == 0){
            $status  = 'notfound';
            return response()->json( array('status' => $status) );
        }else{

            foreach($data as $datas){}
            $fromloc = $datas->FromLoc;
            $toloc = $datas->ToLoc;
            $transdate = $datas->TransDate;
            $active = $datas->Active;

            if($fromloc == $location){
                if($active == 'A'){
                    $status = 1;
                }else{
                    $status = 0;
                }
            }else{
                $status = 0;
            }

            if($fromloc == $location){
                $statusLoc = 1;
            }else{
                $statusLoc = 0;
            }

            // Cek Stok Harian
            $cekStokHarianTM = $this->Public_Function->CekStokHarian2ERP($fromloc, $toloc, $transdate);

            $tglnow = date('Y-m-d');
            if($transdate <= $tglnow){
                $tglcek = true;
            }else{
                $tglcek = false;
            }

            // query item
            $query2 = "SELECT T.*, 
                            P.Description PDescription, 
                            C.Description CSW, 
                            O.SW OSW, 
                            F.SW FDescription, 
                            F.SW FSW, 
                            R.Description FCarat, 
                            O.SWPurpose,
                            O.TotalQty QtyOrder, 
                            F.ID FID, 
                            P.UseCarat, 
                            ConCat(WorkAllocation, '-', LinkFreq, '-', LinkOrd) Allocation,
                            If(T.FG Is Null, H.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, 
                            Concat(H.SW, ' - ', G.SW))) GSW, X.SW RubberPlate, Z.Photo ZPhoto, ST.Description OperationProcess, 
                            ST2.Description Level2Process, ST3.Description Level3Process, ST4.Description Level4Process 
                        From TransferRMItem T
                            Join Product P On T.Product = P.ID
                            Left Join ProductCarat C On T.Carat = C.ID
                            Left Join WorkOrder O On T.WorkOrder = O.ID
                            Left Join Product F On O.Product = F.ID
                            Left Join ProductCarat R On O.Carat = R.ID
                            Left Join WaxTree W On T.TreeID = W.ID
                            Left Join RubberPlate X On W.SW = X.ID
                            Left Join Product G On T.FG = G.ID
                            Left Join Product H On T.Part = H.ID
                            Left Join Product Z ON IFNULL(T.FG,T.Part)=Z.ID
                            Left Join Operation ST ON ST.ID=T.Operation
                            Left Join ShortText ST2 ON ST2.ID=T.Level2
                            Left Join ShortText ST3 ON ST3.ID=T.Level3
                            Left Join ShortText ST4 ON ST4.ID=T.Level4
                        Where T.IDM = $id
                        Order By T.Carat, T.IDM, T.Ordinal";
                        // dd($query2);
            $data2 = FacadesDB::connection('erp')->select($query2);
            $rowcountItem = count($data2);

            // query operation
            $query3 = "SELECT IDM, SUM(CASE WHEN Operation IS NULL THEN 1 ELSE 0 END) AS OperationProcess, SUM(CASE WHEN Level2 IS NULL THEN 1 ELSE 0 END) AS Level2Process,
                        SUM(CASE WHEN Level3 IS NULL THEN 1 ELSE 0 END) AS Level3Process, SUM(CASE WHEN Level4 IS NULL THEN 1 ELSE 0 END) AS Level4Process
                        FROM transferrmitem WHERE IDM=$id GROUP BY IDM";
            
            $data3 = FacadesDB::connection('erp')->select($query3);
            $rowcountOperation = count($data3);

            foreach($data3 as $datas3){}
            if($datas3->OperationProcess > 0){
                $statusOp = 1;
            }else{
                $statusOp = 0;
            }

            $arrLocation = array(47,12,52,4,50,48);

            if(in_array($location, $arrLocation)){

                // Operation Area
                $arrEnamel = array(160,161,166);
                $arrPoles = array(32,19,167);
                $arrBrush = array(7,163,164,165);
                $arrKikir = array(17,6,50,162);
                $arrSepuh = array(171,168,169,170,171,172,173,174,175,133);
                $arrBombing = array(8,109,110,9);

                if($location == 47){
                    $listOp = implode(",",$arrEnamel);
                }elseif($location == 12){
                    $listOp = implode(",",$arrPoles);
                }elseif($location == 52){
                    $listOp = implode(",",$arrBrush);
                }elseif($location == 4){
                    $listOp = implode(",",$arrKikir);
                }elseif($location == 50){
                    $listOp = implode(",",$arrSepuh);
                }elseif($location == 48){
                    $listOp = implode(",",$arrBombing);
                }

                // Operation
                $queryOperation = "SELECT * FROM OPERATION WHERE ID IN ($listOp)";
                $dataOperation = FacadesDB::connection('erp')->select($queryOperation);

                // Shorttext Area
                $arrEnamelLv2 = array(538,539);
                $arrEnamelLv3 = array(540,541,542,543);
                $arrEnamelLv4 = array(544,545,546,547,548,549,550);
                $arrPolesLv2 = array(555,556,557,558,559,560); 
                
                $listEnamelLv2 = implode(",",$arrEnamelLv2);
                $listEnamelLv3 = implode(",",$arrEnamelLv3);
                $listEnamelLv4 = implode(",",$arrEnamelLv4);
                $listPolesLv2 = implode(",",$arrPolesLv2);

                // EnmLvl2
                $queryEnmLvl2 = "SELECT * FROM SHORTTEXT WHERE ID IN ($listEnamelLv2)";
                $enmLvl2 = FacadesDB::connection('erp')->select($queryEnmLvl2);

                // EnmLvl3
                $queryEnmLvl3 = "SELECT * FROM SHORTTEXT WHERE ID IN ($listEnamelLv3)";
                $enmLvl3 = FacadesDB::connection('erp')->select($queryEnmLvl3);

                // EnmLvl4
                $queryEnmLvl4 = "SELECT * FROM SHORTTEXT WHERE ID IN ($listEnamelLv4)";
                $enmLvl4 = FacadesDB::connection('erp')->select($queryEnmLvl4);

                // PolesLvl2
                $queryPolesLvl2 = "SELECT * FROM SHORTTEXT WHERE ID IN ($listPolesLv2)";
                $polesLvl2 = FacadesDB::connection('erp')->select($queryPolesLvl2);

                // Cek ProductOperation
                $arrOperationID = array();
                $arrLevel2ID = array();
                $arrLevel3ID = array();
                $arrLevel4ID = array();

                $arrOperation = array();
                $arrLevel2 = array();
                $arrLevel3 = array();
                $arrLevel4 = array();

                foreach ($data2 as $datas2){

                    if($datas2->FG == NULL){
                        array_push($arrOperationID, NULL);
                        array_push($arrLevel2ID, NULL);
                        array_push($arrLevel3ID, NULL);
                        array_push($arrLevel4ID, NULL);
            
                        array_push($arrOperation, NULL);
                        array_push($arrLevel2, NULL);
                        array_push($arrLevel3, NULL);
                        array_push($arrLevel4, NULL);
                    }else {
                        $queryProductOperation = "SELECT 
                                                        A.*, B.Description OperationProcess, C.Description Level2Process, D.Description Level3Process, E.Description Level4Process 
                                                    FROM productoperation A
                                                        LEFT JOIN operation B ON A.Operation=B.ID
                                                        LEFT JOIN shorttext C ON A.Level2=C.ID
                                                        LEFT JOIN shorttext D ON A.Level3=D.ID
                                                        LEFT JOIN shorttext E ON A.Level4=E.ID
                                                    WHERE A.Location=$location AND A.IDM=$datas2->FG ";
                                                    // dd($queryProductOperation);
                        $productOperation = FacadesDB::connection('erp')->select($queryProductOperation);
                        $rowcount = count($productOperation);
                        foreach($productOperation as $productOperations){}

                        if($rowcount > 0){
                            array_push($arrOperationID, $productOperations->Operation);
                            array_push($arrLevel2ID, $productOperations->Level2);
                            array_push($arrLevel3ID, $productOperations->Level3);
                            array_push($arrLevel4ID, $productOperations->Level4);
            
                            array_push($arrOperation, $productOperations->OperationProcess);
                            array_push($arrLevel2, $productOperations->Level2Process);
                            array_push($arrLevel3, $productOperations->Level3Process);
                            array_push($arrLevel4, $productOperations->Level4Process);
                        }else{
                            array_push($arrOperationID, NULL);
                            array_push($arrLevel2ID, NULL);
                            array_push($arrLevel3ID, NULL);
                            array_push($arrLevel4ID, NULL);
                
                            array_push($arrOperation, NULL);
                            array_push($arrLevel2, NULL);
                            array_push($arrLevel3, NULL);
                            array_push($arrLevel4, NULL);
                        }
                    }
                }

                $returnHTML = view('Produksi.PelaporanProduksi.TransferMaterial.lihat', compact('location','data','rowcount','data2','data3','dataOperation','enmLvl2','enmLvl3','enmLvl4','polesLvl2',
                'arrOperationID','arrLevel2ID','arrLevel3ID','arrLevel4ID','arrOperation','arrLevel2','arrLevel3','arrLevel4','cekStokHarianTM','tglcek','statusLoc','status','statusOp'))->render();

            }else{

                // Operation
                $queryOperation = "SELECT * FROM OPERATION WHERE LOCATION=$location AND ACTIVE='Y' ";
                $dataOperation = FacadesDB::connection('erp')->select($queryOperation);

                // Cek ProductOperation
                $arrOperationID = array();
                $arrOperation = array();

                foreach ($data2 as $datas2){

                    if($datas2->FG == NULL){
                        array_push($arrOperationID, NULL);
                        array_push($arrOperation, NULL);
                    }else {
                        $queryProductOperation = "SELECT 
                                                        A.*, B.Description OperationProcess, C.Description Level2Process, D.Description Level3Process, E.Description Level4Process 
                                                    FROM productoperation A
                                                        LEFT JOIN operation B ON A.Operation=B.ID
                                                        LEFT JOIN shorttext C ON A.Level2=C.ID
                                                        LEFT JOIN shorttext D ON A.Level3=D.ID
                                                        LEFT JOIN shorttext E ON A.Level4=E.ID
                                                    WHERE A.Location=$location AND A.IDM=$datas2->FG ";
                                                    // dd($queryProductOperation);
                        $productOperation = FacadesDB::connection('erp')->select($queryProductOperation);
                        $rowcount = count($productOperation);
                        foreach($productOperation as $productOperations){}

                        if($rowcount > 0){
                            array_push($arrOperationID, $productOperations->Operation);
                            array_push($arrOperation, $productOperations->OperationProcess);
                        }else{
                            array_push($arrOperationID, NULL);
                            array_push($arrOperation, NULL);
                        }
                    }
                }

                // dd($arrOperationID);

                $returnHTML = view('Produksi.PelaporanProduksi.TransferMaterial.lihat', compact('location','data','rowcount','data2','data3','cekStokHarianTM','tglcek','statusLoc','status','statusOp','active','dataOperation','arrOperationID','arrOperation'))->render();
            }

            return response()->json( array('html' => $returnHTML, 'status' => $status, 'statusLoc' => $statusLoc, 'rowcountItem' => $rowcountItem) );

        }


    }

    public function cekSPK(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $OID = $request->OID;
        $jmlItem = count($OID);

        $arrChar = array();

        // if($location == 7 || $location == 17 || $location == 22 || $location == 4){

        // }

        // dd($request);

        foreach ($OID as $OIDs) {
            if($OIDs != NULL){
                $queryCek = "SELECT A.SW, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK
                                FROM workorder A
                                WHERE A.ID=$OIDs ";
                $dataCek = FacadesDB::connection('erp')->select($queryCek);
                foreach($dataCek as $datasCek){}

                array_push($arrChar,$datasCek->CharSPK);
            }else{
                array_push($arrChar,1);
            }
        }

        $jmlItem2 = array_sum($arrChar);

        // dd($jmlItem2);

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

        return response()->json($datajson);
    }

    public function simpan(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $locationSW = sprintf("%02d", $location);
        $tgl = $request->tgl;

        $cekspk = $request->cekspk;
        if($cekspk == 0){
            $swtahun = 0;
        }else{
            $swtahun = 50;
        }

        // Get Item Count
        $jmlItem = count($request->PID);

        // cekInputTM
        $arrInputTM = array();
        for($i=0;$i<$jmlItem;$i++){
            $concatItem = $request->WorkAllocation[$i] . '-' . $request->Freq[$i] . '-' . $request->Ordinal[$i];
            array_push($arrInputTM,$concatItem);
        }
        $arrInputUnik = array_unique($arrInputTM);
        $jmlInputUnik = count($arrInputUnik);

        if($jmlItem <> $jmlInputUnik){
            $json_return = array(
                'message' => 'Ada NTHKO Sama di TM yang Mau Dibuat!'
            );
            return response()->json($json_return,400);
        }

        // CekTM Double
        for($i=0;$i<$jmlItem;$i++){
            $queryInTM = "SELECT * FROM TRANSFERRM A JOIN TRANSFERRMITEM B ON A.ID=B.IDM 
                            WHERE B.WORKALLOCATION=".$request->WorkAllocation[$i]."
                            AND B.LINKFREQ=".$request->Freq[$i]."
                            AND B.LINKORD=".$request->Ordinal[$i]."
                            AND A.ACTIVE IN ('A','P','S')
                            ";
            $inTM = FacadesDB::connection('erp')->select($queryInTM); 

            if(count($inTM) > 0){
                foreach($inTM as $insTM){}
                $concatItem = $request->WorkAllocation[$i] . '-' . $request->Freq[$i] . '-' . $request->Ordinal[$i];
                $json_return = [
                    "success"=>false,
                    "message"=>"Ada TM Duplicate, NTHKO $concatItem, TM $insTM->ID",
                    "data"=>null,
                    "error"=>[
                        "CekTM"=>"Ada TM Duplicate"
                    ]
                ];
                return response()->json($json_return,400);
                
            }
        }
        

        // Create New SW Tgl
        if($cekspk == 0 && $cekspk != Null){
            $query = "SELECT
                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                        DATE_FORMAT('$tgl', '%y') as tahun,
                        LPad(MONTH('$tgl'), 2, '0' ) as bulan,
                        CONCAT(DATE_FORMAT('$tgl', '%y'),'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                    FROM transferrm
                    Where FromLoc = $location AND SWYear = DATE_FORMAT('$tgl', '%y') AND SWMonth =  MONTH('$tgl')";
        }else{
            $query = "SELECT
                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                        DATE_FORMAT('$tgl', '%y')+50 as tahun,
                        LPad(MONTH('$tgl'), 2, '0' ) as bulan,
                        CONCAT(DATE_FORMAT('$tgl', '%y')+50,'',LPad(MONTH('$tgl'), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                    FROM transferrm
                    Where FromLoc = $location AND SWYear = DATE_FORMAT('$tgl', '%y')+50 AND SWMonth =  MONTH('$tgl')";
        }

        $data = FacadesDB::connection('erp')->select($query);
        foreach ($data as $datas){}
        $idtm = $datas->Counter1;

        FacadesDB::connection('erp')->beginTransaction();
        try {

            // Insert into table transferrm
            $insertTM= transferrm::create([
                'ID' => $datas->Counter1,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => Auth::user()->name,
                'Remarks' => 'Laravel',
                'TransDate' => $request->tgl,
                'Employee' => $request->karyawanid,
                'FromLoc' => $request->daribagian,
                'ToLoc' => $request->kebagian,
                'TotalQty' => 0,
                'TotalWeight' => 0,
                'Active' => 'A',
                'SWYear' => $datas->tahun,
                'SWMonth' => $datas->bulan,
                'SWOrdinal' => $datas->ID
            ]);

            // Insert into table transferrmitem
            $no = 1;
            for($i=0; $i<$jmlItem; $i++){

                $insertTMI= transferrmitem::create([
                    'IDM' => $datas->Counter1,
                    'Ordinal' => $no,
                    'Product' => $request->PID[$i],
                    'Carat' => $request->RID[$i],
                    'Qty' => $request->Qty[$i],
                    'Weight' => $request->Weight[$i],
                    'WorkOrder' => $request->OID[$i],
                    'WorkOrderOrd' => $request->OOrd[$i],
                    'Note' => $request->Note[$i],
                    'WorkAllocation' => $request->WorkAllocation[$i],
                    'LinkFreq' => $request->Freq[$i],
                    'LinkOrd' => $request->Ordinal[$i],
                    'TreeID' => $request->TreeID[$i],
                    'TreeOrd' => $request->TreeOrd[$i],
                    'Part' => $request->Part[$i],
                    'FG' => $request->FG[$i],
                    'BatchNo' => $request->BatchNo[$i],
                ]);

                $no++;
            }

            // Get Qty & Weight from table transferrmitem
            $query2 = "SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM transferrmitem WHERE IDM=$idtm ";
            $data2 = FacadesDB::connection('erp')->select($query2);
            foreach($data2 as $datas2){}

            // Update TotalQty & TotalWeight
            $updateTM = transferrm::where('ID', $idtm)->update([
                'TotalQty' => $datas2->Qty, 
                'TotalWeight' => $datas2->Weight
            ]);

            FacadesDB::connection('erp')->commit();
            $json_return = array(
                'status' => 'Sukses',
                'id' => $datas->Counter1
            );
            return response()->json($json_return,200);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'status' => 'Failed',
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }

        

        // Return JSON data
        // if($insertTM == TRUE && $insertTMI == TRUE){
        //     $goto = array('status' => 'Sukses', 'id' => $datas->Counter1);		
        // }else{
        //     $goto = array('status' => 'Gagal');		
        // }

        // return response()->json($goto);
    }

    public function simpanTest(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $locationSW = sprintf("%02d", $location);

        $cekspk = $request->cekspk;

        // dd($request);

        $PID = $request->PID;
        // dd($PID);
        $Qty = $request->Qty;
        $Weight = $request->Weight;

        // Get Item Count
        $jmlItem = count($request->PID);
    
        // for($i=0; $i<$jmlItem; $i++){
        //     if (is_null($PID) or $PID == "") {
        //         $data_return = [
        //             "success"=>false,
        //             "message"=>"Barang can't be null or blank",
        //             "data"=>null,
        //             "error"=>[
        //                 "PID"=>"PID Parameters can't be null or blank"
        //             ]
        //         ];
        //         return response()->json($data_return,400);
        //     }
        // }
        

 

        // dd($request);

       

        // Create New SW CURDATE
        if($cekspk == 0){
            $query = "SELECT
                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                        DATE_FORMAT(CURDATE(), '%y') as tahun,
                        LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                        CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                    FROM transferrm
                    Where FromLoc = $location AND SWYear = DATE_FORMAT(CURDATE(), '%y') AND SWMonth =  MONTH(CurDate())";
        }else{
            $query = "SELECT
                        CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
                        DATE_FORMAT(CURDATE(), '%y')+50 as tahun,
                        LPad(MONTH(CurDate()), 2, '0' ) as bulan,
                        CONCAT(DATE_FORMAT(CURDATE(), '%y')+50,'',LPad(MONTH(CurDate()), 2, '0' ),'$locationSW',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
                    FROM transferrm
                    Where FromLoc = $location AND SWYear = DATE_FORMAT(CURDATE(), '%y')+50 AND SWMonth =  MONTH(CurDate())";
        }

        $data = FacadesDB::connection('erp')->select($query);
        foreach ($data as $datas){}
        $idtm = $datas->Counter1;

        // Insert into table transferrm
        $insertTM= transferrmtest::create([
            'ID' => $datas->Counter1,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => Auth::user()->name,
            'Remarks' => $request->note,
            'TransDate' => $request->tgl,
            'Employee' => $request->karyawanid,
            'FromLoc' => $request->daribagian,
            'ToLoc' => $request->kebagian,
            'TotalQty' => 0,
            'TotalWeight' => 0,
            'Active' => 'A',
            'SWYear' => $datas->tahun,
            'SWMonth' => $datas->bulan,
            'SWOrdinal' => $datas->ID
        ]);

        // Insert into table transferrmitem
        $no = 1;
        for($i=0; $i<$jmlItem; $i++){

            $Qty = ((isset($request->Qty[$i])) ? $request->Qty[$i] : 0);
            $Weight = ((isset($request->Weight[$i])) ? $request->Weight[$i] : 0);

            $insertTMI= transferrmitemtest::create([
                'IDM' => $datas->Counter1,
                'Ordinal' => $no,
                'Product' => $request->PID[$i],
                'Carat' => $request->RID[$i],
                'Qty' => $Qty,
                'Weight' => $Weight,
                'WorkOrder' => $request->OID[$i],
                'Note' => $request->Note[$i],
                'WorkAllocation' => $request->WorkAllocation[$i],
                'LinkFreq' => $request->Freq[$i],
                'LinkOrd' => $request->Ordinal[$i],
                'TreeID' => $request->TreeID[$i],
                'TreeOrd' => $request->TreeOrd[$i],
                'Part' => $request->Part[$i],
                'FG' => $request->FG[$i],
                'BatchNo' => $request->BatchNo[$i],
            ]);

            $no++;
        }

        // Get Qty & Weight from table transferrmitem
        $query2 = "SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM transferrmitem WHERE IDM=$idtm ";
        $data2 = FacadesDB::connection('dev')->select($query2);
        foreach($data2 as $datas2){}

        // Update TotalQty & TotalWeight
        $updateTM = transferrmtest::where('ID', $idtm)->update([
            'TotalQty' => $datas2->Qty, 
            'TotalWeight' => $datas2->Weight
        ]);

        // Return JSON data
        if($insertTM == TRUE && $insertTMI == TRUE){
            $data_return = array('status' => 'Sukses', 'id' => $datas->Counter1);		
        }else{
            $data_return = array('status' => 'Gagal');		
        }

        return response()->json($data_return);
    }

    public function update(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        // dd($request);
        // $idtm = 2304470661;

        $idtm = $request->idtm;
        
        // Get Item Count
        $jmlItem = count($request->PID);


        FacadesDB::connection('erp')->beginTransaction();
        try {

            // Delete transferrmitem
            $deleteTMI = transferrmitem::where('IDM', $idtm)->delete();

            // Insert into table transferrmitem
            $no = 1;
            for($i=0; $i<$jmlItem; $i++){

                $insertTMI= transferrmitem::create([
                    'IDM' => $idtm,
                    'Ordinal' => $no,
                    'Product' => $request->PID[$i],
                    'Carat' => $request->RID[$i],
                    'Qty' => $request->Qty[$i],
                    'Weight' => $request->Weight[$i],
                    'WorkOrder' => $request->OID[$i],
                    'WorkOrderOrd' => $request->OOrd[$i],
                    'Note' => $request->Note[$i],
                    'WorkAllocation' => $request->WorkAllocation[$i],
                    'LinkFreq' => $request->Freq[$i],
                    'LinkOrd' => $request->Ordinal[$i],
                    'TreeID' => $request->TreeID[$i],
                    'TreeOrd' => $request->TreeOrd[$i],
                    'Part' => $request->Part[$i],
                    'FG' => $request->FG[$i],
                    'BatchNo' => $request->BatchNo[$i],
                ]);

                $no++;
            }

            // Get Qty & Weight from table transferrmitem
            $query2 = "SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM transferrmitem WHERE IDM=$idtm ";
            $data2 = FacadesDB::connection('erp')->select($query2);
            foreach($data2 as $datas2){}

            // Update TotalQty & TotalWeight
            $updateTM = transferrm::where('ID', $idtm)->update([
                'TotalQty' => $datas2->Qty, 
                'TotalWeight' => $datas2->Weight,
                'Remarks' => 'Update Laravel',
                'TransDate' => $request->tgl,
                'Employee' => $request->karyawanid,
                'FromLoc' => $request->daribagian,
                'ToLoc' => $request->kebagian
            ]);

            FacadesDB::connection('erp')->commit();
            $json_return = array(
                'status' => 'Sukses',
                'id' => $idtm
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

        // Return JSON data
        // if($deleteTMI == TRUE && $insertTMI == TRUE && $updateTM == TRUE){
        //     $goto = array('status' => 'Sukses', 'id' => $idtm);		
        // }else{
        //     $goto = array('status' => 'Gagal');		
        // }
        // return response()->json($goto);
    }

    public function baru(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }
       
        $query  = "SELECT * FROM LOCATION WHERE ID=$location";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT * FROM LOCATION WHERE HIGHERRANK=2 ORDER BY DESCRIPTION";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $returnHTML = view('Produksi.PelaporanProduksi.TransferMaterial.baruView', compact('location','data','data2'))->render();
        return response()->json( array('html' => $returnHTML) );
    }

    public function ubah(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $id = $request->id;

        // Get Time Now
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Tampil Area
        $queryArea = "SELECT * FROM LOCATION WHERE HIGHERRANK=2 ORDER BY DESCRIPTION";
        $area = FacadesDB::connection('erp')->select($queryArea);
  
        // query header
        $query = "SELECT 
                        T.*, E.SW ESW, E.Description EDescription, F.Department FDepartment, O.Department ODepartment, F.Description FDescription, O.Description ODescription
                    From TransferRM T
                        Join Employee E On T.Employee = E.ID
                        Join Location F On T.FromLoc = F.ID
                        Join Location O On T.ToLoc = O.ID
                    Where T.ID = $id ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);
        foreach($data as $datas){}

        // query item
        $queryItem = "SELECT T.*, 
                        P.Description PDescription, 
                        C.Description CSW, 
                        O.SW OSW, 
                        F.SW FDescription, 
                        F.SW FSW, 
                        R.Description FCarat, 
                        O.SWPurpose,
                        O.TotalQty QtyOrder, 
                        F.ID FID, 
                        P.UseCarat, 
                        ConCat(WorkAllocation, '-', LinkFreq, '-', LinkOrd) Allocation,
                        If(T.FG Is Null, H.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, 
                        Concat(H.SW, ' - ', G.SW))) GSW, X.SW RubberPlate, Z.Photo ZPhoto, ST.Description OperationProcess, 
                        ST2.Description Level2Process, ST3.Description Level3Process, ST4.Description Level4Process 
                    From TransferRMItem T
                        Join Product P On T.Product = P.ID
                        Left Join ProductCarat C On T.Carat = C.ID
                        Left Join WorkOrder O On T.WorkOrder = O.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join ProductCarat R On O.Carat = R.ID
                        Left Join WaxTree W On T.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On T.FG = G.ID
                        Left Join Product H On T.Part = H.ID
                        Left Join Product Z ON IFNULL(T.FG,T.Part)=Z.ID
                        Left Join Operation ST ON ST.ID=T.Operation
                        Left Join ShortText ST2 ON ST2.ID=T.Level2
                        Left Join ShortText ST3 ON ST3.ID=T.Level3
                        Left Join ShortText ST4 ON ST4.ID=T.Level4
                    Where T.IDM = $id
                    Order By T.Carat, T.IDM, T.Ordinal";
        $item = FacadesDB::connection('erp')->select($queryItem);

        $returnHTML = view('Produksi.PelaporanProduksi.TransferMaterial.ubahView', compact('location','data','area','item'))->render();
        return response()->json( array('html' => $returnHTML) );

    }

    public function cariKaryawan(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 48;
        }

        $input = $request->input;

        if(is_numeric($input) == 1){
            $query = "SELECT ID, SW FROM Employee WHERE ID = $input ";
        }else{
            $query = "SELECT ID, SW FROM Employee WHERE SW = $input ";
        }
      
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount == 0){
            $jsondata = array('success' => false);
        }else{
            foreach ($data as $datas){}
            $idkary = $datas->ID;
            $swkary = $datas->SW;

            $jsondata = array('success' => true, 'idkary' => $idkary, 'swkary' => $swkary);
        }
        return response()->json($jsondata);
    }

    public function insTransferFG($idtm,$username){
        // Check SPK 'O' AND 'Non-O'
        $dataTMI = FacadesDB::connection('erp')->select("SELECT * FROM transferrmitem WHERE IDM=$idtm");
        $rowTMI = count($dataTMI);

        $arrChar = array();
        foreach ($dataTMI as $datasTMI) {
            if($datasTMI->WorkOrder != NULL){
                $queryCharSPK = "SELECT A.SW, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK
                                FROM workorder A
                                WHERE A.ID=$datasTMI->WorkOrder ";
                $dataCharSPK = FacadesDB::connection('erp')->select($queryCharSPK);
                foreach($dataCharSPK as $datasCharSPK){}

                array_push($arrChar,$datasCharSPK->CharSPK);
            }else{
                array_push($arrChar,1);
            }
        }

        $jmlItem = array_sum($arrChar);
        $arrTFGID = array();
        $arrTFGOrd = array();

        if($jmlItem == 0){ 
            $cekspk = 0; // SPK 'O'
        }else{ 
            $cekspk = 1; // SPK 'Non-O'
        }

        // Check TMItem and Group By 'Jenis_SPK, Jenis_Stock, Carat, Model, WorkOrder'
        $queryCek = "SELECT Result.*, CONCAT(Weight,'(',Qty,')') Calc FROM (
                        SELECT IF(LEFT(D.SWPurpose,1)='O',0,1) Jenis_SPK, IF(D.SWPurpose IN ('STO','DSTO','OSTO','STA','DSTA','OSTA','STP','DSTP','OSTP'),0,1) Jenis_Stock, 
                            D.SWPurpose, A.ID, B.FG, D.Product Model, B.Carat, B.WorkOrder, SUM(B.Qty) Qty, FORMAT(SUM(B.Weight),2) Weight
                        FROM transferrm A
                            LEFT JOIN transferrmitem B ON A.ID=B.IDM
                            LEFT JOIN product C ON B.FG=C.ID
                            LEFT JOIN workorder D ON B.WorkOrder=D.ID
                        WHERE A.ID=$idtm
                            AND B.Product=256
                        GROUP BY B.Carat, D.Product, B.WorkOrder
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
                                AND C.SWPurpose <> 'PCB'
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

    public function posting(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }
        
        $idtm = $request->idtm;
        $operationData = $request->operation;
        $level2Data = $request->level2;
        $level3Data = $request->level3;
        $level4Data = $request->level4;
        $fg = $request->fg;
        $ordinal = $request->ordinal;
        $jmlItem = count($operationData);

        $query = "SELECT * FROM transferrm WHERE ID=$idtm";
        $data = FacadesDB::connection('erp')->select($query);
        foreach($data as $datas){}

        $fromloc = $datas->FromLoc;
        $toloc = $datas->ToLoc;
        $transdate = $datas->TransDate;

        $cekStokHarianTM = $this->Public_Function->CekStokHarian2ERP($fromloc, $toloc, $transdate);

        $tglnow = date('Y-m-d');
        if($transdate <= $tglnow){
            $tglcek = true;
        }else{
            $tglcek = false;
        }

        if($cekStokHarianTM == true && $tglcek == true){

            FacadesDB::connection('erp')->beginTransaction();
            try {

                // Update TM
                $queryStatus = "UPDATE transferrm SET Active='P', PostDate=now(), Remarks='Posting Laravel' Where ID = $idtm ";
                $status = FacadesDB::connection('erp')->update($queryStatus);

                // Update TMItem
                for ($i = 0; $i < $jmlItem; $i++) {

                    $operation = ((isset($operationData[$i])) ? $operationData[$i] : 'NULL');
                    $level2 = ((isset($level2Data[$i])) ? $level2Data[$i] : 'NULL');
                    $level3 = ((isset($level3Data[$i])) ? $level3Data[$i] : 'NULL');
                    $level4 = ((isset($level4Data[$i])) ? $level4Data[$i] : 'NULL');

                    $queryUpdateTMI = "UPDATE transferrmitem SET Operation = $operation, Level2 = $level2, Level3 = $level3, Level4 = $level4 WHERE IDM = $idtm AND Ordinal = $ordinal[$i] ";
                    $updateTMI = FacadesDB::connection('erp')->update($queryUpdateTMI);

                    if($fg[$i] <> NULL){
                        $queryPO = "SELECT * FROM productoperation WHERE IDM = $fg[$i] AND Location = $location";
                        $dataPO = FacadesDB::connection('erp')->select($queryPO);
                        $rowcountPO = count($dataPO);
                    
                        if($rowcountPO > 0){
                            $queryPO2 = "UPDATE productoperation SET Operation = $operation, Level2 = $level2, Level3 = $level3, Level4 = $level4 WHERE IDM = $fg[$i] AND Location = $location";
                            $dataPO2 = FacadesDB::connection('erp')->update($queryPO2);
                        }else{
                            $queryPO3 = "INSERT INTO productoperation(IDM, Location, Operation, Level2, Level3, Level4) VALUES($fg[$i], $location, $operation, $level2, $level3, $level4) ";
                            $dataPO3 = FacadesDB::connection('erp')->insert($queryPO3);
                        }
                    }
                }

                $postingFunction = $this->Public_Function->PostingTMERP($idtm, $username);

                if($location == 10){
                    $dataTFG = $this->insTransferFG($idtm,$username);
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
                            'status' => 'Sukses',
                            'id' => $idtm,
                            'location' => 10,
                            'list' => $returnList,
                            'baris' => 1
                        );
                        return response()->json($json_return,200);

                    }else{
                        FacadesDB::connection('erp')->commit();
                        $json_return = array(
                            'status' => 'Sukses',
                            'id' => $idtm,
                            'location' => 10,
                            'baris' => 0
                        );
                        return response()->json($json_return,200);

                    }  

                }else{
                    FacadesDB::connection('erp')->commit();
                    $json_return = array(
                        'status' => 'Sukses',
                        'id' => $idtm,
                        'location' => $location
                    );
                    return response()->json($json_return,200);

                }



                // if($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']){
                //     if($location == 10){
                //         $this->insTransferFG($idtm);
                //     }
                //     $datajson = array('status' => 'Sukses', 'id' => $idtm);
                // }else{
                //     $datajson = array('status' => 'Gagal');	
                // }


            } catch (Exception $e) {
                FacadesDB::connection('erp')->rollBack();
                $json_return = array(
                    'status' => 'Failed',
                    'message' => 'Posting Error !'
                );
                return response()->json($json_return,500);
            }

        }else{
            // $datajson = array('status' => 'BelumStokHarian');	
            $json_return = array('status' => 'BelumStokHarian');
            return response()->json($json_return,200);
        }

        // return response()->json($datajson);

    }

    public function postingTest(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        // dd($request);
        $idtm = $request->idtm;
        $operationData = $request->operation;
        $level2Data = $request->level2;
        $level3Data = $request->level3;
        $level4Data = $request->level4;
        $fg = $request->fg;
        $ordinal = $request->ordinal;
        $jmlItem = count($operationData);

        dd(is_null($operationData));

        // $query = "SELECT * FROM transferrm WHERE ID=$idtm";
        // $data = FacadesDB::connection('erp')->select($query);
        // foreach($data as $datas){}

        // $fromloc = $datas->FromLoc;
        // $toloc = $datas->ToLoc;
        // $transdate = $datas->TransDate;

        // $cekStokHarianTM = $this->Public_Function->CekStokHarian2ERP($fromloc, $toloc, $transdate);

        // $tglnow = date('Y-m-d');
        // if($transdate <= $tglnow){
        //     $tglcek = true;
        // }else{
        //     $tglcek = false;
        // }

        // // dd($cekStokHarianTM);

        // if($cekStokHarianTM == true && $tglcek == true){

      

        // //     // Update TM
        //     // $queryStatus = "UPDATE transferrm SET Active='P', PostDate=now(), Remarks='Posting Laravel' Where ID = $idtm ";
        //     // dd($queryStatus);
        // //     $status = FacadesDB::connection('erp')->update($queryStatus);

        // //     // Update TMItem
        //     for ($i = 0; $i < $jmlItem; $i++) {

        //         $operation = ((isset($operationData[$i])) ? $operationData[$i] : 'NULL');
        //         $level2 = ((isset($level2Data[$i])) ? $level2Data[$i] : 'NULL');
        //         $level3 = ((isset($level3Data[$i])) ? $level3Data[$i] : 'NULL');
        //         $level4 = ((isset($level4Data[$i])) ? $level4Data[$i] : 'NULL');

        //         $queryUpdateTMI = "UPDATE transferrmitem SET Operation = $operation, Level2 = $level2, Level3 = $level3, Level4 = $level4 WHERE IDM = $idtm AND Ordinal = $ordinal[$i] ";
                
        // //         $updateTMI = FacadesDB::connection('erp')->update($queryUpdateTMI);

        //         if($fg[$i] <> NULL){
        //             $queryPO = "SELECT * FROM productoperation WHERE IDM = $fg[$i] AND Location = $location";
                    
        //             $dataPO = FacadesDB::connection('erp')->select($queryPO);
        //             $rowcountPO = count($dataPO);
        //             // dd($rowcountPO);
                
        //             if($rowcountPO > 0){
        //                 $queryPO2 = "UPDATE productoperation SET Operation = $operation, Level2 = $level2, Level3 = $level3, Level4 = $level4 WHERE IDM = $fg[$i] AND Location = $location";
        //                 // $dataPO2 = FacadesDB::connection('erp')->update($queryPO2);
        //                 dd($queryPO2);
        //             }else{
        //                 $queryPO3 = "INSERT INTO productoperation(IDM, Location, Operation, Level2, Level3, Level4) VALUES($fg[$i], $location, $operation, $level2, $level3, $level4) ";
        //                 // $dataPO3 = FacadesDB::connection('erp')->insert($queryPO3);
        //                 dd($queryPO3);
        //             }
        //         }
        //     }

        // //     $postingFunction = $this->Public_Function->PostingTMERP($idtm, $username);
            
        // //     if($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']){
        // //         $datajson = array('status' => 'Sukses', 'id' => $idtm);
        // //     }else{
        // //         $datajson = array('status' => 'Gagal');	
        // //     }

        // // }else{
        // //     $datajson = array('status' => 'BelumStokHarian');	
        // }

        // $datajson = array('status' => 'Sukses', 'id' => $idtm);
        // dd($datajson);

        // return response()->json($datajson);

    }

    public function updateOperation(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }
        // dd($request);
        $idtm = $request->idtm;
        $operationData = $request->operation;
        $level2Data = $request->level2;
        $level3Data = $request->level3;
        $level4Data = $request->level4;
        $fg = $request->fg;
        $ordinal = $request->ordinal;
        $jmlItem = count($operationData);

        // Update TMItem
        for ($i = 0; $i < $jmlItem; $i++) {

            $operation = ((isset($operationData[$i])) ? $operationData[$i] : 'NULL');
            $level2 = ((isset($level2Data[$i])) ? $level2Data[$i] : 'NULL');
            $level3 = ((isset($level3Data[$i])) ? $level3Data[$i] : 'NULL');
            $level4 = ((isset($level4Data[$i])) ? $level4Data[$i] : 'NULL');

            // $queryUpdateTMI = "UPDATE transferrmitem SET Operation = $operation[$i], Level2 = $level2[$i], Level3 = $level3[$i], Level4 = $level4[$i] WHERE IDM = $idtm AND Ordinal = $ordinal[$i] ";
            $queryUpdateTMI = "UPDATE transferrmitem SET Operation = $operation, Level2 = $level2, Level3 = $level3, Level4 = $level4 WHERE IDM = $idtm AND Ordinal = $ordinal[$i] ";
            $updateTMI = FacadesDB::connection('erp')->update($queryUpdateTMI);

            if($fg[$i] <> NULL){
                $queryPO = "SELECT * FROM productoperation WHERE IDM = $fg[$i] AND Location = $location";
                $dataPO = FacadesDB::connection('erp')->select($queryPO);
                $rowcountPO = count($dataPO);

                if($rowcountPO > 0){
                    // $queryPO2 = "UPDATE productoperation SET Operation = $operation[$i], Level2 = $level2[$i], Level3 = $level3[$i], Level4 = $level4[$i] WHERE IDM = $fg[$i] AND Location = $location";
                    $queryPO2 = "UPDATE productoperation SET Operation = $operation, Level2 = $level2, Level3 = $level3, Level4 = $level4 WHERE IDM = $fg[$i] AND Location = $location";
                    $dataPO2 = FacadesDB::connection('erp')->update($queryPO2);
                }else{
                    // $queryPO3 = "INSERT INTO productoperation(IDM, Location, Operation, Level2, Level3, Level4) VALUES($fg[$i], $location, $operation[$i], $level2[$i], $level3[$i], $level4[$i]) ";
                    $queryPO3 = "INSERT INTO productoperation(IDM, Location, Operation, Level2, Level3, Level4) VALUES($fg[$i], $location, $operation, $level2, $level3, $level4) ";
                    $dataPO3 = FacadesDB::connection('erp')->insert($queryPO3);
                }
            }
        }

        // Return JSON data
        if($queryUpdateTMI == TRUE){
            $goto = array('status' => 'Sukses', 'id' => $idtm);		
        }else{
            $goto = array('status' => 'Gagal');		
        }

        return response()->json($goto);

    }

    public function cetak(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $id = $request->id;

        // Query Header
        $query = "SELECT A.*, B.Description KeBagian, C.Description DariBagian, D.Description Penerima FROM transferrm A
                    JOIN location B ON A.ToLoc=B.ID
                    JOIN location C ON A.FromLoc=C.ID
                    JOIN employee D ON A.Employee=D.ID
                    WHERE A.ID=$id";
        $data = FacadesDB::connection('erp')->select($query);
 
        foreach ($data as $datas){}
        $date1 = date("d/m/Y", strtotime($datas->TransDate));
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        // QueryItem
        $queryItem = "SELECT B.Description Produk, A.Carat, A.Qty, A.Weight, C.Description CSW, D.SW WOSW, E.SW PSW, CONCAT(A.WorkAllocation,'-',A.LinkFreq,'-',A.LinkOrd) NTHKO, CONCAT(A.TreeID,'-',A.TreeOrd) NoPohon, A.Note Keterangan
                        FROM transferrmitem A
                        LEFT JOIN product B ON A.Product=B.ID
                        LEFT JOIN productcarat C ON A.Carat=C.ID
                        LEFT JOIN workorder D ON A.WorkOrder=D.ID
                        LEFT JOIN product E ON D.Product=E.ID
                        WHERE A.IDM=$id
                        ORDER BY A.Carat";
        $dataItem = FacadesDB::connection('erp')->select($queryItem);

        return view('Produksi.PelaporanProduksi.TransferMaterial.cetak', compact('location','data','dataItem','date','date1','datenow','timenow','username','id'));
    }

    public function barcodeUnit(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        // dd($request);

        $fromloc = $request->fromloc;
        $toloc = $request->toloc;
        $id = $request->id;
        $wa = explode("-", $id);

        if(count($wa) == 2 || count($wa) == 3){

            $wasw = $wa[0];
            $wafreq = $wa[1];

            $arrList = array();
            $query = "SELECT C.ID, I.Ordinal, P.Description Product, R.Description Carat, I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight,
                            Cast(O.SW As Char) WorkOrder, O.TotalQty, Cast(F.SW As Char) FinishGood, P.ID PID, R.ID RID, O.ID OID, I.BarcodeNote, I.BatchNo,
                            I.LinkID, I.LinkOrd, I.TreeID, I.TreeOrd, Cast(X.SW As Char) RubberPlate, P.UseCarat, C.WorkAllocation, C.Freq, I.Note, I.WorkOrderOrd,
                            I.FG, I.Part, If(I.FG Is Null, H.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))) GSW
                        From WorkCompletion C
                            Join WorkCompletionItem I On C.ID = I.IDM And I.Product Not In (255) 
                            Join Product P On I.Product = P.ID
                            Join ProductCarat R On I.Carat = R.ID
                            Join WorkOrder O On I.WorkOrder = O.ID
                            Join Product F On O.Product = F.ID
                            Join Location L On If((P.ID = 253) And (C.Location = 17), 4, If(P.FileCost = 0, $toloc, P.FileCost)) = L.ID And L.ID = $toloc
                            Left Join WaxTree W On I.TreeID = W.ID
                            Left Join RubberPlate X On W.SW = X.ID
                            Left Join Product G On I.FG = G.ID
                            Left Join Product H On I.Part = H.ID
                        Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And C.Location = $fromloc AND C.Active <> 'C'
                        Having ((Qty <> 0 Or Weight <> 0) And PID <> 101) Or P.ID = 101
                        Order By I.Ordinal";
                        
            $queryPCB = "SELECT C.ID, I.Ordinal, P.Description Product, R.Description Carat, I.Qty + I.RepairQty Qty, I.BatchNo,
                                I.Weight + I.RepairWeight Weight, Cast(O.SW As Char) WorkOrder, O.TotalQty, Cast(F.SW As Char) FinishGood,
                                P.ID PID, R.ID RID, O.ID OID, I.BarcodeNote, I.LinkID, I.LinkOrd, I.TreeID, I.TreeOrd, I.FG, I.Part,
                                If(I.FG Is Null, H.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))) GSW,
                                Cast(X.SW As Char) RubberPlate, P.UseCarat, C.WorkAllocation, C.Freq, I.Note, I.WorkOrderOrd
                        From WorkCompletion C
                        Join WorkCompletionItem I On C.ID = I.IDM
                        Join Product P On I.Product = P.ID
                        Join ProductCarat R On I.Carat = R.ID
                        Join WorkOrder O On I.WorkOrder = O.ID And O.SWPurpose = 'PCB'
                        Join Product F On O.Product = F.ID
                        Left Join WaxTree W On I.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On I.FG = G.ID
                        Left Join Product H On I.Part = H.ID
                        Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And C.Location = $fromloc AND C.Active <> 'C'
                        Order By I.Ordinal";
            
            if($toloc == 56){
                $data = FacadesDB::connection('erp')->select($queryPCB);
            }else{
                $data = FacadesDB::connection('erp')->select($query);
            }
            
            $row = count($data);
      
            if($row == 0){
                $json_return = array('status' => 'Kosong');	
            }else{
                
                // CEK LOGIC NYA LAGI, KARENA MASIH ADA MASALAH (TIDAK USAH DI CEK DI BARCODEUNIT KRN SDH DICEK DI SIMPAN)
                // foreach($data as $datas){
                //     $queryInTM = "SELECT * FROM TRANSFERRM A JOIN TRANSFERRMITEM B ON A.ID=B.IDM 
                //                     WHERE B.WORKALLOCATION=$datas->WorkAllocation
                //                     AND B.LINKFREQ=$datas->Freq
                //                     AND B.LINKORD=$datas->Ordinal
                //                     AND A.ACTIVE IN ('A','P','S')
                //                     ";
                //     $inTM = FacadesDB::connection('erp')->select($queryInTM); 

                //     if(count($inTM) > 0){
                //         foreach($inTM as $insTM){}
                //         $concatItem = $datas->WorkAllocation . '-' . $datas->Freq . '-' . $datas->Ordinal;
                //         $json_return = [
                //             "success"=>false,
                //             "message"=>"Ada TM Duplicate, NTHKO $concatItem, TM $insTM->ID",
                //             "data"=>null,
                //             "error"=>[
                //                 "CekTM"=>"Ada TM Duplicate"
                //             ]
                //         ];
                //         return response()->json($json_return,400);
                        
                //     }
                // }

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
                $arrBarcodeNote = array();
                $arrNote = array();
                $arrRubberPlate = array();
                $arrProductDetail = array();
                $arrTreeID = array();
                $arrTreeOrd = array();
                $arrBatchNo = array();
                $arrPID = array();
                $arrFG = array();
                $arrPart = array();
                $arrRID = array();
                $arrOID = array();
                $arrOOrd = array();

                foreach ($data as $datas){

                    $WorkAllocation = ((isset($datas->WorkAllocation)) ? $datas->WorkAllocation : '');
                    $Freq = ((isset($datas->Freq)) ? $datas->Freq : '');
                    $Ordinal = ((isset($datas->Ordinal)) ? $datas->Ordinal : '');
                    $WorkOrder = ((isset($datas->WorkOrder)) ? $datas->WorkOrder : '');
                    $FinishGood = ((isset($datas->FinishGood)) ? $datas->FinishGood : '');
                    $Carat = ((isset($datas->Carat)) ? $datas->Carat : '');
                    $TotalQty = ((isset($datas->TotalQty)) ? $datas->TotalQty : '');
                    $Product = ((isset($datas->Product)) ? $datas->Product : '');
                    $Qty = ((isset($datas->Qty)) ? $datas->Qty : '');
                    $Weight = ((isset($datas->Weight)) ? $datas->Weight : '');
                    $BarcodeNote = ((isset($datas->BarcodeNote)) ? $datas->BarcodeNote : '');
                    $Note = ((isset($datas->Note)) ? $datas->Note : '');
                    $RubberPlate = ((isset($datas->RubberPlate)) ? $datas->RubberPlate : '');
                    $ProductDetail = ((isset($datas->GSW)) ? $datas->GSW : '');
                    $TreeID = ((isset($datas->TreeID)) ? $datas->TreeID : '');
                    $TreeOrd = ((isset($datas->TreeOrd)) ? $datas->TreeOrd : '');
                    $BatchNo = ((isset($datas->BatchNo)) ? $datas->BatchNo : '');
                    $PID = ((isset($datas->PID)) ? $datas->PID : '');
                    $FG = ((isset($datas->FG)) ? $datas->FG : '');
                    $Part = ((isset($datas->Part)) ? $datas->Part : '');
                    $RID = ((isset($datas->RID)) ? $datas->RID : '');
                    $OID = ((isset($datas->OID)) ? $datas->OID : '');
                    $OOrd = ((isset($datas->WorkOrderOrd)) ? $datas->WorkOrderOrd : '');

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
                    $arrBarcodeNote[] = $BarcodeNote;
                    $arrNote[] = $Note;
                    $arrRubberPlate[] = $RubberPlate;
                    $arrProductDetail[] = $ProductDetail;
                    $arrTreeID[] = $TreeID;
                    $arrTreeOrd[] = $TreeOrd;
                    $arrBatchNo[] = $BatchNo;
                    $arrPID[] = $PID;
                    $arrFG[] = $FG;
                    $arrPart[] = $Part;
                    $arrRID[] = $RID;
                    $arrOID[] = $OID;
                    $arrOOrd[] = $OOrd;
                }

                $json_return = array('WorkAllocation' => $arrWorkAllocation, 'Freq' => $arrFreq, 'Ordinal' => $arrOrdinal, 'WorkOrder' => $arrWorkOrder, 'FinishGood' => $arrFinishGood, 
                            'Carat' => $arrCarat, 'TotalQty' => $arrTotalQty, 'Product' => $arrProduct, 'Qty' => $arrQty, 'Weight' => $arrWeight, 'BarcodeNote' => $arrBarcodeNote, 
                            'Note' => $arrNote, 'RubberPlate' => $arrRubberPlate, 'ProductDetail' => $arrProductDetail, 'TreeID' => $arrTreeID, 'TreeOrd' => $arrTreeOrd, 
                            'BatchNo' => $arrBatchNo, 'PID' => $arrPID, 'FG' => $arrFG, 'Part' => $arrPart, 'RID' => $arrRID, 'OID' => $arrOID, 'OOrd' => $arrOOrd, 'baris' => $row, 'status' => 'Sukses');
                
            }
        }else{
            $json_return = array('status' => 'NotFound');
        }
   
        return response()->json($json_return);
    }

    public function barcodeKomponen(Request $request){
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 48;
        }

        $fromloc = $request->fromloc;
        $id = $request->id;
        $wa = explode("-", $id);

        if(count($wa) == 2 || count($wa) == 3){

            $wasw = $wa[0];
            $wafreq = $wa[1];

            // $fromloc = 22; //CB
            $query = "SELECT C.ID, I.Ordinal, P.Description Product, R.Description Carat, I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight,
                            Cast(O.SW As Char) WorkOrder, O.TotalQty, Cast(F.SW As Char) FinishGood, P.ID PID, R.ID RID, O.ID OID, I.BarcodeNote, I.BatchNo,
                            I.LinkID, I.LinkOrd, P.UseCarat, C.WorkAllocation, C.Freq, I.Note, I.WorkOrderOrd
                        From WorkCompletion C
                            Join WorkCompletionItem I On C.ID = I.IDM And I.Product Not In (255)
                            Join Product P On I.Product = P.ID And P.Type = 'S' And P.ProdGroup <> 119
                            Join ProductCarat R On I.Carat = R.ID
                            Join WorkOrder O On I.WorkOrder = O.ID
                            Join Product F On O.Product = F.ID
                        Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And C.Location = $fromloc
                        Order By I.Ordinal";
                        // dd($query);
            $data = FacadesDB::connection('erp')->select($query);
            $row = count($data);
      
            if($row == 0){
                $json_return = array('status' => 'Kosong');	
            }else{

                foreach($data as $datas){
                    $queryInTM = "SELECT * FROM TRANSFERRM A JOIN TRANSFERRMITEM B ON A.ID=B.IDM 
                                    WHERE B.WORKALLOCATION=$datas->WorkAllocation
                                    AND B.LINKFREQ=$datas->Freq
                                    AND B.LINKORD=$datas->Ordinal";
                    $inTM = FacadesDB::connection('erp')->select($queryInTM); 

                    if(count($inTM) > 0){

                        $concatItem = $datas->WorkAllocation . '-' . $datas->Freq . '-' . $datas->Ordinal;
                        $json_return = [
                            "success"=>false,
                            "message"=>"Ada TM Duplicate, NTHKO $concatItem",
                            "data"=>null,
                            "error"=>[
                                "CekTM"=>"Ada TM Duplicate"
                            ]
                        ];
                        return response()->json($json_return,400);
                        
                    }
                }

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
                $arrBarcodeNote = array();
                $arrNote = array();
                $arrPID = array();
                $arrRID = array();
                $arrOID = array();
                $arrOOrd = array();

                $arrRubberPlate = array();
                $arrProductDetail = array();
                $arrTreeID = array();
                $arrTreeOrd = array();
                $arrBatchNo = array();
                $arrFG = array();
                $arrPart = array();

                foreach ($data as $datas){

                    $WorkAllocation = ((isset($datas->WorkAllocation)) ? $datas->WorkAllocation : '');
                    $Freq = ((isset($datas->Freq)) ? $datas->Freq : '');
                    $Ordinal = ((isset($datas->Ordinal)) ? $datas->Ordinal : '');
                    $WorkOrder = ((isset($datas->WorkOrder)) ? $datas->WorkOrder : '');
                    $FinishGood = ((isset($datas->FinishGood)) ? $datas->FinishGood : '');
                    $Carat = ((isset($datas->Carat)) ? $datas->Carat : '');
                    $TotalQty = ((isset($datas->TotalQty)) ? $datas->TotalQty : '');
                    $Product = ((isset($datas->Product)) ? $datas->Product : '');
                    $Qty = ((isset($datas->Qty)) ? $datas->Qty : '');
                    $Weight = ((isset($datas->Weight)) ? $datas->Weight : '');
                    $BarcodeNote = ((isset($datas->BarcodeNote)) ? $datas->BarcodeNote : '');
                    $Note = ((isset($datas->Note)) ? $datas->Note : '');
                    $PID = ((isset($datas->PID)) ? $datas->PID : '');
                    $RID = ((isset($datas->RID)) ? $datas->RID : '');
                    $OID = ((isset($datas->OID)) ? $datas->OID : '');
                    $OOrd = ((isset($datas->WorkOrderOrd)) ? $datas->WorkOrderOrd : '');

                    $RubberPlate = ((isset($datas->RubberPlate)) ? $datas->RubberPlate : '');
                    $ProductDetail = ((isset($datas->GSW)) ? $datas->GSW : '');
                    $TreeID = ((isset($datas->TreeID)) ? $datas->TreeID : '');
                    $TreeOrd = ((isset($datas->TreeOrd)) ? $datas->TreeOrd : '');
                    $BatchNo = ((isset($datas->BatchNo)) ? $datas->BatchNo : '');
                    $FG = ((isset($datas->FG)) ? $datas->FG : '');
                    $Part = ((isset($datas->Part)) ? $datas->Part : '');

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
                    $arrBarcodeNote[] = $BarcodeNote;
                    $arrNote[] = $Note;
                    $arrPID[] = $PID;
                    $arrRID[] = $RID;
                    $arrOID[] = $OID;
                    $arrOOrd[] = $OOrd;

                    $arrRubberPlate[] = $RubberPlate;
                    $arrProductDetail[] = $ProductDetail;
                    $arrTreeID[] = $TreeID;
                    $arrTreeOrd[] = $TreeOrd;
                    $arrBatchNo[] = $BatchNo;
                    $arrFG[] = $FG;
                    $arrPart[] = $Part;

                }
       
                $json_return = array('WorkAllocation' => $arrWorkAllocation, 'Freq' => $arrFreq, 'Ordinal' => $arrOrdinal, 'WorkOrder' => $arrWorkOrder, 'FinishGood' => $arrFinishGood, 
                            'Carat' => $arrCarat, 'TotalQty' => $arrTotalQty, 'Product' => $arrProduct, 'Qty' => $arrQty, 'Weight' => $arrWeight, 'BarcodeNote' => $arrBarcodeNote, 
                            'Note' => $arrNote, 'RubberPlate' => $arrRubberPlate, 'ProductDetail' => $arrProductDetail, 'TreeID' => $arrTreeID, 'TreeOrd' => $arrTreeOrd, 
                            'BatchNo' => $arrBatchNo, 'PID' => $arrPID, 'FG' => $arrFG, 'Part' => $arrPart, 'RID' => $arrRID, 'OID' => $arrOID, 'OOrd' => $arrOOrd, 'baris' => $row, 'status' => 'Sukses');
            }

        }else{
            $json_return = array('status' => 'NotFound');
        }

        return response()->json($json_return);
    }

    public function cariSPK(Request $request){ //OK
        $sw = $request->sw;

        if($sw == 'Stock' || $sw == 'stock' || $sw == 'STOCK'){
            $sw = 'Stock';
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW = '$sw' AND A.ACTIVE='A' ";
        }elseif($sw == 'OStock' || $sw == 'ostock' || $sw == 'OSTOCK'){
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
                    WHERE A.SW LIKE '%$sw%' AND A.ACTIVE='A' ";
        }

        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){

            foreach ($data as $datas){}
            $NoSPK = $datas->SW;
            $WorkOrder = $datas->ID;
            $TotalQty = $datas->TotalQty;
            $ProductName = $datas->ProductName;

            // $queryCarat = "SELECT * FROM productcarat WHERE ID=$carat ";
            // $data2 = FacadesDB::connection('erp')->select($queryCarat);
            // foreach ($data2 as $datas2){}
            // $CaratID = $datas2->ID;
            // $CaratName = $datas2->Description;

            $dataReturn = array('NoSPK' => $NoSPK, 'WorkOrder' => $WorkOrder, 'TotalQty' => $TotalQty, 'ProductName' => $ProductName, 'rowcount' => $rowcount);

        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }

    public function cariProduct(Request $request){ //OK
        $sw = $request->sw;
        // $carat = $request->carat;
    
        // $dataCarat = FacadesDB::connection('erp')->select("SELECT * FROM productcarat WHERE ID=$carat");
        // foreach ($dataCarat as $datasCarat){}
        // $caratID = $datasCarat->ID;
        // $caratName = $datasCarat->Description;
     
        $query = "SELECT * FROM PRODUCT WHERE TYPE IN ('S','R','F') AND USECARAT IN ('Y','N') AND STOCKUNIT<>0 AND SW = '$sw' ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){
            foreach ($data as $datas){}
            
            $PID = $datas->ID;
            $Product = $datas->Description;
            $UseCarat = $datas->UseCarat;

            $dataReturn = array('rowcount' => $rowcount, 'PID' => $PID, 'Product' => $Product, 'UseCarat' => $UseCarat);
        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);


    }



    public function cariKadar(Request $request){ //OK
        $sw = $request->sw;

        $query = "SELECT A.* FROM PRODUCTCARAT A WHERE A.SW = '$sw' AND A.REGULAR='Y' ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){

            foreach ($data as $datas){}
            $CaratId = $datas->ID;
            $CaratName = $datas->Description;

            $dataReturn = array('CaratId' => $CaratId, 'CaratName' => $CaratName, 'rowcount' => $rowcount);

        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }

    public function cariSPKO(Request $request){ //OK
        $wa = $request->wa;
        $freq = $request->freq;
        $ordinal = $request->ordinal;

        // dd($request);

        if($wa != Null && $freq != Null && $ordinal != Null){

            $query = "SELECT 
                            A.WorkAllocation SW, A.Freq, B.Ordinal, B.WorkOrder, E.SW NoSPK, E.Product IdProdukSPK, F.SW ProdukSPK, B.Carat, D.Description Kadar, 
                            E.TotalQty, B.Product PID, C.Description Barang, B.Qty, B.Weight, B.TreeID, B.TreeOrd, H.SW NoPohon, B.BatchNo
                        FROM 
                            WORKCOMPLETION A
                            LEFT JOIN WORKCOMPLETIONITEM B ON A.ID=B.IDM
                            LEFT JOIN PRODUCT C ON B.PRODUCT=C.ID
                            LEFT JOIN PRODUCTCARAT D ON B.CARAT=D.ID
                            LEFT JOIN WORKORDER E ON B.WORKORDER=E.ID
                            LEFT JOIN PRODUCT F ON E.PRODUCT=F.ID
                            LEFT JOIN WAXTREE G On B.TreeID = G.ID
                            LEFT JOIN RUBBERPLATE H On G.SW = H.ID
                        WHERE 
                            A.WORKALLOCATION = '$wa' AND A.FREQ='$freq' AND B.ORDINAL='$ordinal' 
                        ";
                        // dd($query);
            $data = FacadesDB::connection('erp')->select($query);
            $rowcount = count($data);

            if($rowcount > 0){
                foreach ($data as $datas){}
                $SW = $datas->SW;
                $Freq = $datas->Freq;
                $Ordinal = $datas->Ordinal;
                $WorkOrder = $datas->WorkOrder;
                $NoSPK = $datas->NoSPK;
                $IdProdukSPK = $datas->IdProdukSPK;
                $ProdukSPK = $datas->ProdukSPK;
                $Carat = $datas->Carat;
                $Kadar = $datas->Kadar;
                $TotalQty = $datas->TotalQty;
                $PID = $datas->PID;
                $Barang = $datas->Barang;
                $Qty = $datas->Qty;
                $Weight = $datas->Weight;
                $TreeID = $datas->TreeID;
                $TreeOrd = $datas->TreeOrd;
                $NoPohon = $datas->NoPohon;
                $BatchNo = $datas->BatchNo;

                $dataReturn = array(
                    'rowcount' => $rowcount,
                    'SW' => $SW,
                    'Freq' => $Freq,
                    'Ordinal' => $Ordinal,
                    'WorkOrder' => $WorkOrder,
                    'NoSPK' => $NoSPK,
                    'IdProdukSPK' => $IdProdukSPK,
                    'ProdukSPK' => $ProdukSPK,
                    'Carat' => $Carat,
                    'Kadar' => $Kadar,
                    'TotalQty' => $TotalQty,
                    'PID' => $PID,
                    'Barang' => $Barang,
                    'Qty' => $Qty,
                    'Weight' => $Weight,
                    'TreeID' => $TreeID,
                    'TreeOrd' => $TreeOrd,
                    'NoPohon' => $NoPohon,
                    'BatchNo' => $BatchNo
                );
            }else{
                $dataReturn = array('rowcount' => $rowcount);
            }
        }else{
            $dataReturn = array('rowcount' => 0);
        }

        // dd($dataReturn);

        return response()->json($dataReturn);
    }
   
}

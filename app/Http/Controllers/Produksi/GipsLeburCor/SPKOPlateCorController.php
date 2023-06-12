<?php

namespace app\Http\Controllers\Produksi\GipsLeburCor;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal dev
// use App\Models\tes_laravel\workallocation;
// use App\Models\tes_laravel\cast;
// use App\Models\tes_laravel\workallocationitem;
// use App\Models\tes_laravel\castitem;

// live
use App\Models\erp\workallocation;
use App\Models\erp\cast;
use App\Models\erp\workallocationitem;
use App\Models\erp\castitem;


// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class SPKOPlateCorController extends Controller{
    // set public function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }
    
    // Private Function
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    public function Index(){
        // Get Employee
        $employee = FacadesDB::connection('erp')
        ->select("SELECT 
            ID, 
            SW, 
            Description 
        FROM 
            employee 
        WHERE 
            Department = 6 AND Active = 'Y'
        ");
        $datenow = date('Y-m-d');
        // history waxstoneusage
        $historyPlatecor = FacadesDB::connection('erp')->select("SELECT SW FROM Workallocation WHERE Location = 7 AND Operation = 15 ORDER BY EntryDate DESC LIMIT 15");
        $historyTMPohon = FacadesDB::connection('erp')->select("SELECT ID FROM waxtreetransfer WHERE Active = 'P' ORDER BY EntryDate DESC LIMIT 15");
        $infoSPKOPlateCorBelumposting = FacadesDB::connection('erp')->select("SELECT
        W.ID as workallocationID,
        DATE_FORMAT( W.TransDate, '%d %M %Y' ) AS tgl,
        W.SW as spkocor,
        W.Carat,
        W.Weight as totalWeight,
        C.Description as Kadar,
        REPLACE(I.product,';','<br>') as product,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
        P.Description product 
    FROM
        workallocation W
        LEFT JOIN cast T ON T.WorkAllocationCast = W.SW
        JOIN workallocationitem I ON W.ID = I.IDM
        JOIN product P ON I.Product = P.ID
        JOIN productcarat C ON W.Carat = C.ID 
    WHERE
        W.Active = 'A' 
        AND W.Operation = 15 
        AND I.Product = 195237 
    GROUP BY
        W.SW 
    ORDER BY
    W.EntryDate ASC");
        return view('Produksi.GipsLeburCor.SPKOPlateCor.index', compact('employee', 'datenow', 'historyPlatecor','historyTMPohon','infoSPKOPlateCorBelumposting'));
    }

    public function ListPosted(){

        $headinfoSPKOPlateCorSudahposting = FacadesDB::connection('erp')
        ->select("SELECT ID
            FROM 
                employee 
            WHERE 
                ID = 1893
        ");
        
        if (count($headinfoSPKOPlateCorSudahposting) == 0) {
            return response()->json($data_return, 404);
        }
        
        $headinfoSPKOPlateCorSudahposting = $headinfoSPKOPlateCorSudahposting[0];
        
        $infoSPKOPlateCorSudahposting = FacadesDB::connection('erp')->select("SELECT
        W.ID as workallocationID,
        DATE_FORMAT( W.TransDate, '%d %M %Y' ) AS tgl,
        W.SW spkocor,
        W.Carat,
        W.Weight totalWeight,
        C.Description as Kadar,
        REPLACE(I.product,';','<br>') as IDProd,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
        P.Description product 
    FROM
        workallocation W
        LEFT JOIN cast T ON T.WorkAllocationCast = W.SW
        JOIN workallocationitem I ON W.ID = I.IDM
        JOIN product P ON I.Product = P.ID
        JOIN productcarat C ON W.Carat = C.ID 
    WHERE
        W.Active IN ( 'P', 'S' ) 
        AND W.Operation = 15 
        AND I.Product = 195237 
    GROUP BY
        W.SW 
    ORDER BY
    W.PostDate DESC 
        LIMIT 20");

        if (count($infoSPKOPlateCorSudahposting) == 0) {
            $data_return = $this->SetReturn(false, "dBelum ada SPKO Plate Cor yang di posting.", null, null);
            return response()->json($data_return, 400);
        }
        $headinfoSPKOPlateCorSudahposting->items = $infoSPKOPlateCorSudahposting;

        $data_return = $this->SetReturn(true, "Getting Waxtreetransfer Item Success", $headinfoSPKOPlateCorSudahposting, null);
        return response()->json($data_return, 200);
    }


    Public function isiSPKOplatecor(Request $request){

        // dd($request->keyword);
        $headisiSPKOPlatecor = FacadesDB::connection('erp')
        ->select("SELECT 
        ID, SW, UserName, EntryDate FROM workallocation WHERE ID = $request->keyword
        ");
        
        if (count($headisiSPKOPlatecor) == 0) {
            $data_return = $this->SetReturn(false, "ID TM tersebut Tidak Ditemukan di data waxtreeTransfer", null, null);
            return response()->json($data_return, 404);
        }
        
        $headisiSPKOPlatecor = $headisiSPKOPlatecor[0];     

        $isiSPKOPlatecordata = FacadesDB::connection('erp')->select("SELECT
        I.TreeID as IDPohon,
        IF(C.Description IS NOT NULL, C.Description, '') as Kadar,
        IF(I.BatchNo IS NOT NULL, I.BatchNo, '') as BatchNo,
        I.Weight,
        CASE 
        WHEN P.Description Like '%Batu%' THEN 'badge bg-secondary' ELSE 'badge bg-warning' END batucorinfo,
        P.Description,
        TR.IDM,
        PP.SW,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
        IF(P.Description = 'Plat Cor', GROUP_CONCAT(DISTINCT PP.SW,'<br>'), '') as Product
    -- 	TR.Ordinal
    FROM
        workallocation W
        LEFT JOIN cast T ON W.SW = T.WorkAllocationCast
        JOIN workallocationitem I ON W.ID = I.IDM
        JOIN waxtreeitem TR ON TR.IDM = I.TreeID
        JOIN Product PP ON PP.ID = TR.Product
        JOIN product P ON I.Product = P.ID 
        LEFT JOIN productcarat C ON I.Carat = C.ID 
    WHERE
        W.ID = $request->keyword
        GROUP BY
        I.TreeID,
        P.Description
    ORDER BY
        I.Ordinal");
        // dd($isiSPKOPlatecordata);

    if (count($isiSPKOPlatecordata) == 0) {
        $data_return = $this->SetReturn(false, "data TM tidak ditemuk, harap diperiksa kembali.", null, null);
        return response()->json($data_return, 400);
    }
    $headisiSPKOPlatecor->items = $isiSPKOPlatecordata;

    $data_return = $this->SetReturn(true, "Getting Waxtreetransfer Item Success", $headisiSPKOPlatecor, null);
    return response()->json($data_return, 200);
    }

   
    public function GetIDTMPohon(Request $request){
        // Getting WaxInjectOrder
        // dd($request->keyword);
        $headTMPohon = FacadesDB::connection('erp')
        ->select("SELECT 
        A.ID, A.UserName, A.EntryDate, IF (LEFT (C.WorkOrder,1) = 'O','=','!=') as OO FROM waxtreetransfer A JOIN waxtreetransferitem B On A.ID = B.IDM JOIN waxtree C ON B.WaxTree = C. ID WHERE A.Active = 'P' AND A.ID = $request->keyword
        ");
        
        if (count($headTMPohon) == 0) {
            $data_return = $this->SetReturn(false, "ID TM tersebut Tidak Ditemukan di data waxtreeTransfer", null, null);
            return response()->json($data_return, 404);
        }
        
        $headTMPohon = $headTMPohon[0];
        //generate item 
        $dataTMPohon = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
        W.ID IDPohon,
        R.SW NoPohon,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
    -- 	W.Weight * C.Gips +
    -- IF
    -- 	( W.Carat = 15, 4, 0 ) GoldNeed,
        If(Left(R.SW, 1) = 'D', (W.Weight - 2) * C.GipsDC, W.Weight * C.Gips + If(W.Carat = 15, 4, 0)) GoldNeed,
        O.SW SPKPPIC,
        C.Gips,
        T.IDM,
        C.ID idKadar,
        I.WorkOrder,
        W.WeightStone,
        W.WorkOrder grupOrderWax,
        REPLACE(W.Product,';','<br>') as grupProduct,
        W.Qty,
        W.Weight as WeightWax
    FROM
        waxtreetransferitem T
        JOIN waxtree W ON T.WaxTree = W.ID
        JOIN rubberplate R ON W.SW = R.ID
        JOIN waxtreeitem I ON W.ID = I.IDM
        JOIN workorder O ON I.WorkOrder = O.ID
        JOIN productcarat C ON W.Carat = C.ID 
    WHERE
        T.IDM = $headTMPohon->ID
    GROUP BY
        W.ID 
    ORDER BY
    IF
        ( W.WeightStone = 0, 0, 1 ),
        R.SW
        ");
// dd($dataTMPohon);
        // Check Item 
        if (count($dataTMPohon) == 0) {
            $data_return = $this->SetReturn(false, "data TM tidak ditemuk, harap diperiksa kembali.", null, null);
            return response()->json($data_return, 400);
        }
        $headTMPohon->items = $dataTMPohon;

        $data_return = $this->SetReturn(true, "Getting Waxtreetransfer Item Success", $headTMPohon, null);
        return response()->json($data_return, 200);
    }

    public function getNTHKO(Request $request){
        // dd($request);
        $workallocation = $request->workallocation;
        $Freq = $request->Freq;
        $ordinal = $request->ordinal;
        $IDPohon = $request->IDPohon;

        if ($Freq ==  NULL ) {
            $data_return = $this->SetReturn(false,"Format NTHKO tidak sesuai", null, null);
            return response()->json($data_return, 404);
        }

        if ($ordinal ==  NULL ) {
            $data_return = $this->SetReturn(false,"Format NTHKO tidak sesuai", null, null);
            return response()->json($data_return, 404);
        }

        $getNthko = FacadesDB::connection('erp')->select("SELECT
        C.ID as WorkcompletionID,
        C.Freq as WorkcompletionFreq,
        CONCAT( C.WorkAllocation, '-', C.Freq, '-', I.Ordinal ) nthko,
        I.Ordinal as WcompliOrd,
        I.Weight,
        I.BatchNo 
    FROM
        workcompletion C
        JOIN workcompletionitem I ON C.ID = I.IDM 
        AND I.Ordinal = $ordinal
    WHERE
        C.WorkAllocation = $workallocation
        AND C.Freq = $Freq
        -- AND TreeID = $IDPohon
        ");
        if (count($getNthko) == 0) {
            $data_return = $this->SetReturn(false,"NTHKO yang di scan tidak cocok untuk id pohon $IDPohon", null, null);
            return response()->json($data_return, 404);
        }
        
        $rowcount = count($getNthko);
        if($rowcount > 0){
            foreach ($getNthko as $datas){}
            $WorkcompletionID = $datas->WorkcompletionID;
            $nthko = $datas->nthko;
            $WorkcompletionFreq = $datas->WorkcompletionFreq;
            $WcompliOrd = $datas->WcompliOrd;
            $Weight = $datas->Weight;
            $BatchNo = $datas->BatchNo;

            // dd($ItemProduct, $Description, $IDProd, $HexColor, $Kadar, $IDKadar, $IDKaret, $Lokasi);
            $data_Return = array(
                'rowcount' => $rowcount,
                'WorkcompletionID' => $WorkcompletionID, 
                'WorkcompletionFreq' => $WorkcompletionFreq,
                'nthko' => $nthko,
                'WcompliOrd' => $WcompliOrd,
                'Weight' => $Weight, 
                'BatchNo' => $BatchNo);
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);        
    } 

    
    public function Simpan(Request $request){
        // Get Required Data

        $kadar = $request->items[0]['idkadar'];
        // dd($request);
        // Check if date null or blank
        if (is_null($request->date) or $request->date == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan gips order tanggal kosng",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // update last id workallocation and cast 
        $getlastIDWorkallocation = FacadesDB::connection('erp')
        ->select("SELECT CASE WHEN MAX( Last ) IS NULL THEN '1' ELSE MAX( Last )+1 END AS ID
                FROM lastid 
				WHERE Module = 'WorkAllocation'");
        
        $idworkallocation = $getlastIDWorkallocation[0]->ID;
        // dd($idlast);
        $updateworkallocation = FacadesDB::connection('erp')
        ->update("UPDATE Lastid SET Last = $idworkallocation WHERE Module = 'WorkAllocation'");
   
        $getlastIDcast = FacadesDB::connection('erp')
        ->select("SELECT CASE WHEN MAX( Last ) IS NULL THEN '1' ELSE MAX( Last )+1 END AS ID
                FROM lastid 
				WHERE Module = 'Cast'");
        
        $idcast = $getlastIDcast[0]->ID;
        // dd($idlast);
        $updatecast = FacadesDB::connection('erp')
        ->update("UPDATE Lastid SET Last = $idcast WHERE  Module = 'Cast'");


        if($request->OOO == '='){
            $GenerateSWworkallocation = FacadesDB::connection('erp')
            ->select("SELECT CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            DATE_FORMAT(CURDATE(), '%y') as tahun,
            LPad(MONTH(CurDate()), 2, '0' ) as bulan,
            CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'07',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            FROM workallocation
            Where Location = 07 AND SWYear = DATE_FORMAT(CURDATE(), '%y') AND SWMonth =  MONTH(CurDate())
            ");
        }else{
            $GenerateSWworkallocation = FacadesDB::connection('erp')
            ->select("SELECT CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END AS ID,
            DATE_FORMAT(CURDATE(), '%y') +50 as tahun,
            LPad(MONTH(CurDate()), 2, '0' ) as bulan,
            CONCAT(DATE_FORMAT(CURDATE(), '%y') +50,'',LPad(MONTH(CurDate()), 2, '0' ),'07',LPad(CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+1 END, 4, '0')) Counter1
            FROM workallocation
            Where Location = 07 AND SWYear = DATE_FORMAT(CURDATE(), '%y')+50 AND SWMonth =  MONTH(CurDate())
            ");
        }
        // dd($GenerateIDSPK);
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        // pakai pengkondiasian dulu buat nyari apaka ada SWnya atau belum baru di generate query
        // $getFreqWorkallocation = FacadesDB::connection('erp')->selelct("SELECT * FROM workallocation WHERE SW = $GenerateSWworkallocation")
        FacadesDB::connection('erp')->beginTransaction();
        try {
            $insert_workallocatioan = workallocation::create([
                'ID' => $idworkallocation,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => $username,
                'Remarks' => $request->Catatan,
                'SW' => $GenerateSWworkallocation[0]->Counter1,
                'Freq' => 1,
                'TransDate' => $request->date,
                'Purpose' => 'Tambah',
                'Carat' => $kadar,
                'Location' => 7,
                'Operation' => 15,
                'Employee' =>  $request->employee,
                'TargetQty' => 0,
                'Weight' => $request->totaltimbangan,
                'Active' => 'A',
                'SWYear' => $GenerateSWworkallocation[0]->tahun,
                'SWMonth' =>$GenerateSWworkallocation[0]->bulan,
                'SWOrdinal' =>$GenerateSWworkallocation[0]->ID,
                'PostDate' => NULL,
                'WorkGroup' =>NULL,
                'Stone' =>NULL,
                'DailyStock' => NULL,
                'PrevProcess' => NULL,
                'WaxOrder' => NULL,
                'Outsource' => 'N',
            ]);
            // dd($insert_GipsOrder);
            
            $insert_cast = cast::create([
                'ID' => $idcast,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => $username,
                'WaxTreeTransfer' => $request->IDTMPohon,
                'WorkAllocationMelt' =>	NULL,
                'WorkAllocationCast' => $insert_workallocatioan->SW,
                'Carat' => $kadar,
                'WeightMelt' => $request->totaltimbangan,
            ]);

            $k = 0;
            foreach ($request->items as $IT => $isi) {
                $k++;
                $insert_workallocation_item = workallocationitem::create([
                    'IDM'=> $idworkallocation,
                    'Ordinal' => $IT+1,
                    'Product'=> 195237,
                    'Carat'	=> $isi['idkadar'],
                    'Qty'=> 0,
                    'Weight' =>	$isi['weightinput'],
                    'WorkOrder' => $isi['idspkppic'],
                    'WorkOrderOrd' => NULL,
                    'Note'=> NULL,
                    'BarcodeNote' => NULL,
                    'PrevProcess'=> $isi['WorkcompletionIDinput'],
                    'PrevOrd'=> $isi['wcompletionord'], 
                    'PrevType' => NULL,
                    'TreeID' => $isi['IDWaxtree'],
                    'TreeOrd' => 0,
                    'Part' => NULL,
                    'FG' => NULL,
                    'BatchNo' => $isi['BatchNo'],
                    'StoneLoss' => 0,
                    'QtyLossStone' => 0,	
                    'WaxOrder' => NULL,
                    'WorkSchedule' => NULL,
                    'WorkScheduleOrd' => NULL,
                ]);
                if($isi['groupproduk'] != NULL)
                $insert_workallocation_item_batu = Workallocation::create([
                    'IDM'=> $idworkallocation,
                    'Ordinal' => $IT+1,
                    'Product'=> 93,
                    'Carat'	=> $isi['idkadar'],
                    'Qty'=> 0,
                    'Weight' =>	$isi['weightinput'],
                    'WorkOrder' => $isi['idspkppic'],
                    'WorkOrderOrd' => NULL,
                    'Note'=> NULL,
                    'BarcodeNote' => NULL,
                    'PrevProcess'=> $isi['WorkcompletionIDinput'],
                    'PrevOrd'=> $isi['wcompletionord'], 
                    'PrevType' => NULL,
                    'TreeID' => $isi['IDWaxtree'],
                    'TreeOrd' => 0,
                    'Part' => NULL,
                    'FG' => NULL,
                    'BatchNo' => $isi['BatchNo'],
                    'StoneLoss' => 0,
                    'QtyLossStone' => 0,	
                    'WaxOrder' => NULL,
                    'WorkSchedule' => NULL,
                    'WorkScheduleOrd' => NULL,
                ]);
            }

            $k = 0;
            foreach ($request->items as $IT => $isi) {
                $k++;
                if($isi['groupproduk'] != NULL){
                $insert_cast_item = castitem::create([
                    'IDM' =>$idcast,
                    'Ordinal'=> $isi['urutaninput'],
                    'WaxTree' => $isi['IDWaxtree'],
                    'WorkOrder' => $isi['swspkppiclengkap'],
                    'Product' => $isi['groupproduk'],
                    'QtyWax' => $isi['qty'],
                    'WeightWax' => $isi['weightwax'],
                    'WeightNeed' => $isi['weightneed'],
                    'WeightGold' => $isi['weightinput'],
                    'MachineOven' => NULL,
                    'TempratureOven' =>	NULL,
                    'EmployeeOven' => NULL,
                    'MachineCast' => NULL,
                    'TempratureCast' =>	NULL,
                    'EmployeeCast' => NULL,
                    'EmployeeWash' => NULL,
                    'EmployeeCut' => NULL,
                ]);
            }
            }

            // $data_return = $this->SetReturn(true, "Save PlateCOr Sukses", ['ID'=>$GenerateSWworkallocation[0]->Counter1], null);
            // return response()->json($data_return, 200);
            FacadesDB::connection('erp')->commit();
            $data_return = $this->SetReturn(true, "Save PlateCOr Sukses", ['ID'=>$GenerateSWworkallocation[0]->Counter1], null);
            return response()->json($data_return, 200);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'status' => 'Failed',
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }
    }

    public function Search(Request $request){
       
        // dd($request->keyword);
        // Cek waxtree if exists
        $Workallocation_Header = FacadesDB::connection('erp')
        ->select("SELECT
            A.ID as idwokallocation,
            A.SW as swworkallocation,
            B.ID as idcast,
            B.WaxTreeTransfer as IDTMpohon,
            A.TransDate as datetanggal,
            A.Carat,
            A.Employee,
            A.Remarks,
            A.UserName,
            A.EntryDate,
            A.Active,
            IF( A.Active = 'P', 'POSTED', '' ) AS Posting,
            IF(LEFT(D.WorkOrder,1) = 'O', '=', '!=') as OOO,
            A.Weight as totaltimbangan
        FROM
            workallocation A
            JOIN Cast B ON A.SW = B.WorkAllocationCast AND A.Weight = B.WeightMelt
            JOIN castitem D ON B.ID = D.IDM
        WHERE
            A.SW = $request->keyword
            LIMIT 1 ");

    if (count($Workallocation_Header) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOPlateCor tersebut Tidak Ditemukan di Workallocation dan cast Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Workallocation_Header = $Workallocation_Header[0];
    //    dd($Workallocation_Header);
        // IDWaxInjectOrder
        $IDWorkallocation = $Workallocation_Header->idwokallocation;

        $Workallocation_tabel = FacadesDB::connection('erp')
        ->select("SELECT
        A.ID as idwokallocation,
        A.SW as swworkallocation,

        C.Product,
        C.Carat as caratitem,
        IF(C.Weight IS NULL, 0, C.Weight) as weightworkitem,
        C.WorkOrder as WorkOrder,
        C.PrevProcess,
        IF(C.Product = 93, '', CONCAT(F.WorkAllocation,'-',F.Freq,'-',C.PrevOrd)) as nthko,
        F.Workallocation,
        F.Freq, -- jangan lupa di ganti C.PrevType rencanannya mau aku isi Frequensinya Wokcompelton mana yang digunakan kalo seandainya ngedit biar ngga error
        C.PreVOrd,
        C.PrevType,
        C.TreeID as IDpohonwork,
        IF(C.BatchNo IS NULL, '',C.BatchNo) as BatchNo,
        CASE
                WHEN G.SW = '6K' THEN
                '#0000FF' 
                WHEN G.SW = '8K' THEN
                '#00FF00' 
                WHEN G.SW = '8K.' THEN
                '#CFB370' 
                WHEN G.SW = '10K' THEN
                '#FFFF00' 
                WHEN G.SW = '16K' THEN
                '#FF0000' 
                WHEN G.SW = '17K' THEN
                '#FF6E01' 
                WHEN G.SW = '17K.' THEN
                '#FF00FF' 
                WHEN G.SW = '19K' THEN
                '#5F2987' 
                WHEN G.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
        I.SW as NoPohon,
        IF(C.Product = 93, 0,D.WeightNeed) as WeightNeed,
        IF(C.Product = 93, 'Disabled', '') as inputnthko,
        D.WorkOrder as SPKPPIC,
        IF(C.Product = 93, '',REPLACE(H.Product,';','<br>')) as grupProduct,
        C.Product as jenisproduct,
        H.WorkOrder as grupOrderWax,
        D.WeightGold,
        D.QtyWax,         
        -- If(Left(I.SW, 1) = 'D', (H.Weight - 2) * G.GipsDC, H.Weight * G.Gips + If(H.Carat = 15, 4, 0)) GoldNeed,
        D.WeightWax

        FROM
        workallocation A
            JOIN Cast B ON A.SW = B.WorkAllocationCast AND B.WeightMelt = A.Weight
        JOIN workallocationitem C ON A.ID = C.IDM
            JOIN castitem D ON D.IDM = B.ID AND D.WaxTree = C.TreeID
        LEFT JOIN workcompletionitem E ON E.IDM = C.PrevProcess AND E.Ordinal = C.PrevOrd
        LEFT JOIN workcompletion F ON E.IDM = F.ID
        JOIN ProductCarat G ON G.ID = A.carat
            JOIN Waxtree H ON H.ID = D.WaxTree
            JOIN rubberplate I ON I.ID 	 = H.SW
        WHERE
        A.ID = $IDWorkallocation
        GROUP BY
        C.Ordinal
        ORDER BY 
        C.Ordinal
      ");
    // dd($Workallocation_tabel);

        $Workallocation_Header->items2 = $Workallocation_tabel;
        $data_return = $this->SetReturn(true, "Getting data workallocationitem and castitem Success.data found", $Workallocation_Header, null);
        return response()->json($data_return, 200);
    }


    public function Update(Request $request){

        $SPKPlateCor = $request->SWworkallocation;

        $getIDworkallocation = FacadesDB::connection('erp')->select("SELECT ID FROM Workallocation WHERE SW = $SPKPlateCor");
        $getidCast = FacadesDB::connection('erp')
        ->select("SELECT ID FROM cast WHERE WorkAllocationCast = $SPKPlateCor");
        $IDWorkallocation = $getIDworkallocation[0]->ID;
        $IDCast = $getidCast[0]->ID; 
        // dd($IDWorkallocation,$IDCast);
        // Check if date null or blank
        if (is_null($request->date) or $request->date == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan gips order tanggal kosng",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        FacadesDB::connection('erp')->beginTransaction();
        try {
            $update_workallocatioan = workallocation::where('SW',$SPKPlateCor)->update([
                'UserName' => $username,
                'Remarks' => $request->Catatan,
                'TransDate' => $request->date,
                'Employee' =>  $request->employee,
                'Weight' => $request->totaltimbangan,
            ]);
           
            
            $update_cast = cast::where('WorkAllocationCast',$SPKPlateCor)->update([
                'UserName' => $username,
                'WeightMelt' => $request->totaltimbangan,
            ]);

            $deleteworkallocationItem = workallocationitem::where('IDM',$IDWorkallocation)->delete();
            $deletecastItem = castitem::where('IDM',$IDCast)->delete();

            $k = 0;
            foreach ($request->items as $IT => $isi) {
                $k++;
                $insert_workallocation_item = workallocationitem::create([
                    'IDM'=> $IDWorkallocation,
                    'Ordinal' => $IT+1,
                    'Product'=> $isi['jenisproduk'],
                    'Carat'	=> $isi['idkadar'],
                    'Qty'=> 0,
                    'Weight' =>	$isi['weightinput'],
                    'WorkOrder' => $isi['idspkppic'],
                    'WorkOrderOrd' => NULL,
                    'Note'=> NULL,
                    'BarcodeNote' => NULL,
                    'PrevProcess'=> $isi['WorkcompletionIDinput'],
                    'PrevOrd'=> $isi['wcompletionord'], 
                    'PrevType' => NULL,
                    'TreeID' => $isi['IDWaxtree'],
                    'TreeOrd' => 0,
                    'Part' => NULL,
                    'FG' => NULL,
                    'BatchNo' => $isi['BatchNo'],
                    'StoneLoss' => 0,
                    'QtyLossStone' => 0,	
                    'WaxOrder' => NULL,
                    'WorkSchedule' => NULL,
                    'WorkScheduleOrd' => NULL,
                ]);
            }

            $k = 0;
            foreach ($request->items as $IT => $isi) {
                $k++;
                if($isi['groupproduk'] != NULL){
                $insert_cast_item = castitem::create([
                    'IDM' =>$IDCast,
                    'Ordinal'=> $isi['urutaninput'],
                    'WaxTree' => $isi['IDWaxtree'],
                    'WorkOrder' => $isi['swspkppiclengkap'],
                    'Product' => $isi['groupproduk'],
                    'QtyWax' => $isi['qty'],
                    'WeightWax' => $isi['weightwax'],
                    'WeightNeed' => $isi['weightneed'],
                    'WeightGold' => $isi['weightinput'],
                    'MachineOven' => NULL,
                    'TempratureOven' =>	NULL,
                    'EmployeeOven' => NULL,
                    'MachineCast' => NULL,
                    'TempratureCast' =>	NULL,
                    'EmployeeCast' => NULL,
                    'EmployeeWash' => NULL,
                    'EmployeeCut' => NULL,
                ]);
            }
            }

            // $data_return = $this->SetReturn(true, "Save PlateCOr Sukses", ['ID'=>$GenerateSWworkallocation[0]->Counter1], null);
            // return response()->json($data_return, 200);
            FacadesDB::connection('erp')->commit();
            $data_return = $this->SetReturn(true, "Update PlateCOr Sukses", ['ID'=>$SPKPlateCor], null);
            return response()->json($data_return, 200);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'status' => 'Failed',
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }

    }

    public function cetak(Request $request){
        $IDSPKOPlateCor = $request->IDSPKOPlateCor;
        // dd($IDSPKOPlateCor);
        
        $Workallocation_Headercetak = FacadesDB::connection('erp')
        ->select("SELECT
        A.ID,
        A.EntryDate,
        A.UserName,
        A.Active,
    IF
        ( A.Active = 'P', 'POSTED', '' ) AS Posting,
        A.TransDate,
        A.Remarks,
        IF (LEFT (W.WorkOrder,1) = 'O','=','!=') as OOO
    FROM
        gipsorder AS A
        JOIN gipsorderitem AS B ON A.ID = B.IDM
        JOIN waxtree AS W ON W.ID = B.WaxTree
    WHERE
        A.ID = $IDSPKOPlateCor
        LIMIT 1 ");

    if (count($Workallocation_Headercetak) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOPlateCor tersebut Tidak Ditemukan di data Gips Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Workallocation_Headercetak1 = $Workallocation_Headercetak[0];
    //    dd($Workallocation_Header);
        // IDWaxInjectOrder
        $IDGipsOrder = $Workallocation_Headercetak1->ID;

        $Workallocation_tabelcetak06k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
		IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
			W.WeightFG,
            CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
		        C.SW = '6K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
    WHERE
        T.IDM = $IDGipsOrder
    ORDER BY
        W.urutan,
        W.Plate
      ");

        $Workallocation_tabelcetak08k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '8K' THEN
            '#02ba1e' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '8K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");

        $Workallocation_tabelcetak16k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            
            WHEN C.SW = '16K' THEN
            '#ff1a1a' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '16K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetak17k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '17K' THEN
            '#e65507' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '17K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetak17kp = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '17K.' THEN
            '#d909cb' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '17K.'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetak20k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '20K' THEN
            '#ffcba4' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '20K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetak10k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '10K' THEN
            '#f5fc0f' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '10K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetak8kp = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '8K.' THEN
            '#ebb52d' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '8K.'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetak19k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '19K' THEN
            '#4908a3' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '19K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Workallocation_tabelcetakperak = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '19K' THEN
            '#4908a3' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = 'Perak'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");

$Workallocation_tabelcetakallkadar = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");

$date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$datenow = $date->format("d/m/Y");
$timenow = $date->format("H:i");

$username = Auth::user()->name;
FacadesDB::beginTransaction();

return view('Produksi.GipsLeburCor.SPKOPlateCor.cetak',
compact('username','Gips_Headercetak',
'Gips_tabelcetak06k',
'Gips_tabelcetak08k',
'Gips_tabelcetak16k',
'Gips_tabelcetak17k',
'Gips_tabelcetak17kp',
'Gips_tabelcetak19k',
'Gips_tabelcetak8kp',
'Gips_tabelcetak20k',
'Gips_tabelcetak10k',
'Gips_tabelcetakperak',
'Gips_tabelcetakallkadar','date','datenow','timenow'));
    }

    public function cetakrekap(Request $request){
        $IDSPKOPlateCor = $request->IDSPKOPlateCor;
        // dd($IDSPKOPlateCor);
        
        $Workallocation_Headercetak = FacadesDB::connection('erp')
        ->select("SELECT
        A.ID,
        A.EntryDate,
        A.UserName,
        A.Active,
    IF
        ( A.Active = 'P', 'POSTED', '' ) AS Posting,
        A.TransDate,
        A.Remarks,
        IF (LEFT (W.WorkOrder,1) = 'O','=','!=') as OOO
    FROM
        gipsorder AS A
        JOIN gipsorderitem AS B ON A.ID = B.IDM
        JOIN waxtree AS W ON W.ID = B.WaxTree
    WHERE
        A.ID = $IDSPKOPlateCor
        LIMIT 1 ");

    if (count($Workallocation_Headercetak) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOPlateCor tersebut Tidak Ditemukan di data W Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Workallocation_Headercetak1 = $Workallocation_Headercetak[0];
    //    dd($Workallocation_Header);
        // IDWaxInjectOrder
        $IDGipsOrder = $Workallocation_Headercetak1->ID;

    $Workallocation_tabelcetak06ksedang = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
		IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
			W.WeightFG,
            CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
		        C.SW = '6K'
				AND LEFT(R.SW,1) = 'B'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");

        $Workallocation_tabelcetak06kbesar = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '6K'
                AND LEFT(R.SW,1) = 'C'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");
        $Gips_tabelcetak06k = FacadesDB::connection('erp')
        ->select("SELECT
        COUNT(W.ID) AS jumlah
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '6K'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ");



        $Gips_tabelcetak16ksedang = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '16K'
                AND LEFT(R.SW,1) = 'B'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");

        $Gips_tabelcetak16kbesar = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '16K'
                AND LEFT(R.SW,1) = 'C'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");
        $Gips_tabelcetak16k = FacadesDB::connection('erp')
        ->select("SELECT
        COUNT(W.ID) AS jumlah
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '16K'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ");



        $Gips_tabelcetak08ksedang = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '8K'
                AND LEFT(R.SW,1) = 'B'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");

        $Gips_tabelcetak08kbesar = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '8K'
                AND LEFT(R.SW,1) = 'C'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");
        $Gips_tabelcetak08k = FacadesDB::connection('erp')
        ->select("SELECT
        COUNT(W.ID) AS jumlah
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '8K'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ");


$Gips_tabelcetak17ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak17kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak17k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");



$Gips_tabelcetak17kpsedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K.'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak17kpbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K.'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak17kp = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K.'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");




$Gips_tabelcetak20ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '20K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak20kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '20K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak20k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '20K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");



$Gips_tabelcetak10ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '10K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak10kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '10K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak10k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '10K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");
    

$Gips_tabelcetak08kpsedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '8K.'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak08kpbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '8K.'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak08kp = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '8K.'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");


$Gips_tabelcetak19ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '19K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak19kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '19K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak19k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '19K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");


$Gips_tabelcetakperaksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = 'Perak'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetakperakbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = 'Perak'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetakperak = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = 'Perak'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");


$Gips_tabelcetakallkadar = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");

$date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$datenow = $date->format("d/m/Y");
$timenow = $date->format("H:i");

$username = Auth::user()->name;
FacadesDB::beginTransaction();

return view('Produksi.GipsLeburCor.SPKOPlateCor.cetakrekap',
compact('username','Gips_Headercetak',
'Gips_tabelcetak06ksedang',
'Gips_tabelcetak06kbesar',
'Gips_tabelcetak06k',
'Gips_tabelcetak08ksedang',
'Gips_tabelcetak08kbesar',
'Gips_tabelcetak08k',
'Gips_tabelcetak16ksedang',
'Gips_tabelcetak16kbesar',
'Gips_tabelcetak16k',
'Gips_tabelcetak17ksedang',
'Gips_tabelcetak17kbesar',
'Gips_tabelcetak17k',
'Gips_tabelcetak17kpsedang',
'Gips_tabelcetak17kpbesar',
'Gips_tabelcetak17kp',
'Gips_tabelcetak20ksedang',
'Gips_tabelcetak20kbesar',
'Gips_tabelcetak20k',
'Gips_tabelcetak10ksedang',
'Gips_tabelcetak10kbesar',
'Gips_tabelcetak10k',
'Gips_tabelcetak08kpsedang',
'Gips_tabelcetak08kpbesar',
'Gips_tabelcetak08kp',
'Gips_tabelcetak19ksedang',
'Gips_tabelcetak19kbesar',
'Gips_tabelcetak19k',
'Gips_tabelcetakperaksedang',
'Gips_tabelcetakperakbesar',
'Gips_tabelcetakperak',
'Gips_tabelcetakallkadar',
'date','datenow','timenow'));
    }
}
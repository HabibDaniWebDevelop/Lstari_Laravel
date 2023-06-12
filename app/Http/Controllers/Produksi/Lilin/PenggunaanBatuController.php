<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\waxstoneusage;
// use App\Models\tes_laravel\waxstoneusageitem;

// live
use App\Models\erp\waxstoneusage;
use App\Models\erp\waxstoneusageitem;
use App\Models\erp\lastid;

// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class PenggunaanBatuController extends Controller{
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
        ->select("
            SELECT 
                ID, 
                SW, 
                Description 
            FROM 
                employee 
            WHERE 
                Department = 19 AND Active = 'Y'
        ");
        $datenow = date('Y-m-d');
        // history waxstoneusage
        $historyWaxStoneUsage = waxstoneusage::orderBy('ID','DESC')->limit(10)->get();
        return view('Produksi.Lilin.PenggunaanBatu.index', compact('employee', 'datenow', 'historyWaxStoneUsage'));
    }

    public function inputoperator($IdOperator){
        
        $Operator = FacadesDB::connection('erp')
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND ID='$IdOperator' AND `Rank`='Operator'");
        
        
        if ($Operator){
            return response()->json(
                [
                    'namaop' => $Operator[0]->Description,
                ],
                201,
            );
        } else{
            return response()->json(
                [
                    'namaop' => 'Nama Tidak Ditemukan',
                ],
                201,
            );
        }
    }

    public function getWaxInjectOrder($IDWaxInjectOrder){
        // Getting WaxInjectOrder
        $Waxstoneusage = FacadesDB::connection('erp')
        ->select("SELECT SUM(totals) TotalAll FROM (
            SELECT WorkOrder, Sum(Total) Totals FROM (
                        SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total, T.Description
                        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                            FROM WaxInjectOrder J
                                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                                JOIN WorkOrder O On W.WorkOrder = O.ID
                                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                            WHERE J.ID = $IDWaxInjectOrder)  Q
                        JOIN Product P On Q.Product = P.ID
                        LEFT JOIN ShortText X On Q.StoneColor = X.ID
                        JOIN ProductAccessories A On P.ID = A.IDM
                        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
                        JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
                        GROUP BY Q.IDM, Q.Ordinal, T.SW
                        UNION
                        SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total, T.Description
                        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                            FROM WaxInjectOrder J
                                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                                JOIN WorkOrder O On W.WorkOrder = O.ID
                                JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                            WHERE J.ID = $IDWaxInjectOrder)  Q
                        JOIN Product P On Q.Product = P.ID
                        JOIN ProductAccessories A On P.ID = A.IDM
                        JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
                        GROUP BY Q.IDM, Q.Ordinal, T.SW
                        UNION
                        SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total, T.Description
                        FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote
                            FROM WaxInjectOrder J
                                JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                                JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                            JOIN WorkOrder O On W.WorkOrder = O.ID
                            JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                        Where J.ID = $IDWaxInjectOrder)  Q
                    JOIN Product P On Q.Product = P.ID
                    LEFT JOIN ShortText X On Q.StoneColor = X.ID
                    JOIN ProductAccessories A On P.ID = A.IDM
                    JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
                    GROUP BY Q.IDM, Q.Ordinal, T.SW)  A
                    GROUP BY Stone
                    ORDER BY Stone) B
                    GROUP BY B.WorkOrder
        ");
        if (count($Waxstoneusage) == 0) {
            $data_return = $this->SetReturn(false, "ID SPKO Inject Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        
        $Waxstoneusage = $Waxstoneusage[0];
        // Check if that ID its already NTHKOed.
        // $cekNTHKO = waxstoneusage::where('WaxOrder',$IDWaxInjectOrder)->first();
        // if (!is_null($cekNTHKO)) {
        //     $data_return = $this->SetReturn(false, "Nomor SPK Tersebut Sudah DiGunakan", null, null);
        //     return response()->json($data_return, 400);
        // }

        // Get Items cara baru dengan banyak workorder di tabel worklist3dp
        $item = FacadesDB::connection('erp')
        ->select("SELECT WorkOrder, IDWorkOrder, Description, IDProduct, Stone, Sum(Total) Qty, Size FROM (
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total, T.Description, T.ID IDProduct, T.Size, Q.IDWorkOrder
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote, O.ID IDWorkOrder
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = $IDWaxInjectOrder)  Q
            JOIN Product P On Q.Product = P.ID
            LEFT JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            JOIN Product Z On T.Model = Z.Model And T.Size = Z.Size And Z.Color = X.Remarks And Z.ProdGroup = 126 And Right(Z.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total, T.Description, T.ID IDProduct, T.Size, Q.IDWorkOrder
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote, O.ID IDWorkOrder
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                WHERE J.ID = $IDWaxInjectOrder)  Q
            JOIN Product P On Q.Product = P.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color <> 147
            GROUP BY Q.IDM, Q.Ordinal, T.SW
            UNION
            SELECT Q.IDM, Q.Ordinal, Q.WorkOrder, P.SW Product, Q.Qty Inject, Q.StoneNote, T.SW Stone, Sum(Q.Qty) Ordered, A.Qty EachQty, Sum(Q.Qty * A.Qty) Total, T.Description, T.ID IDProduct, T.Size, Q.IDWorkOrder
            FROM (SELECT DISTINCT Q.IDM, Q.Ordinal, Q.Product, W.StoneColor, O.SW WorkOrder, I.Qty, W.StoneNote, O.ID IDWorkOrder
                FROM WaxInjectOrder J
                    JOIN WaxInjectOrderItem I On J.ID = I.IDM And I.StoneCast = 'Y'
                    JOIN WaxOrderItem W On I.WaxOrder = W.IDM And I.WaxOrderOrd = W.Ordinal
                    JOIN WorkOrder O On W.WorkOrder = O.ID
                    JOIN WorkOrderItem Q On W.WorkOrder = Q.IDM And W.WorkOrderOrd = Q.Ordinal
                Where J.ID = $IDWaxInjectOrder)  Q
            JOIN Product P On Q.Product = P.ID
            LEFT JOIN ShortText X On Q.StoneColor = X.ID
            JOIN ProductAccessories A On P.ID = A.IDM
            JOIN Product T On A.Product = T.ID And T.ProdGroup = 126 And T.Color = 147 And Right(T.SW, 2) <> '-S'
            GROUP BY Q.IDM, Q.Ordinal, T.SW)  A
            GROUP BY Stone
            ORDER BY Stone
        ");

        // Check Item 
        if (count($item) == 0) {
            $data_return = $this->SetReturn(false, "SPKO tidak valid. Karena tidak ada item/barang di Database Untuk SPKO Tersebut.", null, null);
            return response()->json($data_return, 400);
        }

        
    
        $Waxstoneusage->item = $item;

        $data_return = $this->SetReturn(true, "Getting WaxInjectOrder Item Success", $Waxstoneusage, null);
        return response()->json($data_return, 200);
    }

    public function BeratBatu(Request $request){
        $beratbatu = $request->value;
        $urut = $request->id;

        $k = 0;
        foreach ($request->items as $pp => $berat){
            $k++;
           $TotalBeratBatu += $berat['value'];
        }
        dd($TotalBeratBatu);
    }
    
    public function Simpan(Request $request){
        // Get Required Data
        $idWaxInjectOrder = $request->idWaxInjectOrder;
        $idEmployee = $request->idEmployee;
        $date = $request->date;
        $TotalBerat = $request->TotalBerat;
        $TotalJumlah = $request->TotalJumlah;
        $Keperluan = $request->Keperluan;
        $Catatan = $request->Catatan;
        $chekitem = $request->items;
        $chekheader = [$request->idWaxInjectOrder, $request->idEmployee, $request->date, $request->TotalBerat, $request->TotalJumlah, $request->Keperluan, $request->Catatan];
        
        $getFreq = FacadesDB::connection("erp")
        ->select("SELECT IF(COUNT(WaxOrder) = 0,1,COUNT(WaxOrder)+1) as insertFreq FROM waxstoneusage WHERE WaxOrder = $idWaxInjectOrder");

        $freq = $getFreq[0]->insertFreq;
        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($idWaxInjectOrder) or $idWaxInjectOrder == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal membuat spk batu",
                "data"=>null,
                "error"=>[
                    "idWaxInjectOrder"=>"idWaxInjectOrder Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if idEmployee null or blank
        if (is_null($idEmployee) or $idEmployee == "") {
            $data_return = [
                "success"=>false,
                "message"=>"gagal membuat spk batu",
                "data"=>null,
                "error"=>[
                    "idEmployee"=>"idEmployee Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if beratBatu null or blank
        // if (is_null($TotalBerat) or $TotalBerat == "") {
        //     $data_return = [
        //         "success"=>false,
        //         "message"=>"Gagal menyimpan spk batu",
        //         "data"=>null,
        //         "error"=>[
        //             "TotalBerat"=>"TotalBerat Parameters can't be null or blank"
        //         ]
        //     ];
        //     return response()->json($data_return,400);
        // }
        
        // Check if beratPohonTotal null or blank
        if (is_null($TotalJumlah) or $TotalJumlah == "") {
            $data_return = [
                "success"=>false,
                "message"=>"gagal Menyimpan SPK Batu",
                "data"=>null,
                "error"=>[
                "Totaljumlah"=>"TotalJumlahbatu Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        
        //generateID urut untuk waxstoeneusage
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( Last ) IS NULL THEN '1' ELSE MAX( Last )+1 END AS ID
                FROM lastid 
				WHERE ID = 113");
        
        $idlast = $generateID[0]->ID;
        // dd($idlast);

        $update_lastidsoneusage = "UPDATE Lastid SET Last = $idlast WHERE ID = 113";
                // 'last' => $generateID[0]->ID
        $update_lastidsoneusage_Succes = FacadesDB::connection('erp')->update($update_lastidsoneusage);

        $insert_waxstoneusage = waxstoneusage::create([
            'ID' => $idlast,
            'EntryDate' => $date, // auto isi tanggal saat disimpan
            'UserName' => $username, // username yang login
            'Remarks' => $Catatan, //dari form isisan catatan
            'TransDate' => date('Y-m-d H:i:s'), //dari form tanggal yang di inputkan
            'Employee' => $idEmployee,
            'Purpose' => $Keperluan,
            'Active' =>'A',
            'DailyStock' =>NULL,
            'PostDate' =>NULL,
            'WaxOrder'=>$idWaxInjectOrder,
            'Location'=>51,
            'Freq'=>$freq,
        ]);

        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_waxstoneusageitem = waxstoneusageitem::create([
                'IDM' => $insert_waxstoneusage->ID,
                'Ordinal' => $IT+1,
                'WorkOrder' =>$isi['WorkOrder'],
                'Product' => $isi['IdProduct'],
                'Qty' => $isi['itemQty'], //
                'Weight' => $isi['beratbatu'], // dari tabel workorderitem kolom IDM
                'Note'=>$isi['Note'],
            ]);
        }
        
        $WaxStoneHeader = FacadesDB::connection('erp')
        ->select("SELECT
            SUM( TI.Weight ) berattotal,
            SUM( TI.Qty ) jumlahtotal
        FROM
            waxstoneusage T
            JOIN waxstoneusageitem TI ON T.ID = TI.IDM
            JOIN waxinjectorder W ON W.ID = T.WaxOrder
            JOIN Product PB ON PB.ID = TI.Product
            JOIn employee E ON E.ID = T.Employee
            WHERE T.ID = $idlast");
            
        $berat = $WaxStoneHeader[0]->berattotal;
        $jumlah = $WaxStoneHeader[0]->jumlahtotal;

        $data_return = $this->SetReturn(true, "Save SPKO Batu Sukses", ['berattotaltes'=>$berat, 'IDtes'=>$insert_waxstoneusage->ID, 'jumlahtotaltes'=>$jumlah], null);
        return response()->json($data_return, 200);
    }

    public function tesposting(Request $request)
    {
        $idwaxstoneusage = $request->waxstoneusage;
        if (is_null($idwaxstoneusage) or $idwaxstoneusage == "") {
            $data_return = $this->SetReturn(false, "waxstoneusage can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        $findwaxstoneusage = waxstoneusage::where('ID',$idwaxstoneusage)->first();
        if (is_null($findwaxstoneusage)) {
            $data_return = $this->SetReturn(false, "SPKO Batu Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $cekkeperluanstone = waxstoneusage::where('ID',$idwaxstoneusage)->where('Purpose','Kurang')->first();
        // $findWorkAllocation = workallocation::where('SW',$swWorkAllocation)->first();
        // if (is_null($findWorkAllocation)) {
        //     $data_return = $this->SetReturn(false, "SPKO WorkAllocation NotFound", null, null);
        //     return response()->json($data_return, 404);
        // }
        // Check if workallocation is already posted or not
        if ($findwaxstoneusage->Active != 'A') {
            $data_return = $this->SetReturn(false, "SPKO Sudah Pernah di Posting", null, null);
            return response()->json($data_return, 400);
        }
        
        $LinkID = $request->waxstoneusage; //* id field
        $UserName = Auth::user()->name; //* User Login
        $tablename = 'waxstoneusageitem'; //todo: - workallocationitem    - workcompletionitem  - transferrmitem
        $tablemain = 'waxstoneusage'; //todo: - workallocation    - workcompletion    - transferrm
        $status = 'D'; //todo: C = Credit (Stok Berkurang)   // D = Debit (Stok Bertambah)
        $Location = '51'; //* lokasi departemen
        $Process = 'Production'; //* Production
        $cause = 'Stone Usage'; //todo: Usage (Stok Berkurang)   // Completion (Stok Bertambah)

        if(is_null($cekkeperluanstone)){
            $status = 'C';
        }

        // $id = '2301501440';
        $cekwaxstoneusage = FacadesDB::connection('erp')->select("SELECT * FROM $tablemain WHERE id = '$LinkID' ");
        $TransDate = $cekwaxstoneusage[0]->TransDate;
        $ToLoc = $cekwaxstoneusage[0]->Location;

        $CekStokHarian = $this->Public_Function->CekStokHarianERP($ToLoc, $TransDate);
        if($CekStokHarian == false){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Posting Gagal!!',
                ],
                400,
            );
        }

        $data = FacadesDB::connection('erp')
            // * select untuk workcompletionitem
            // ->select("SELECT A.Product, A.Carat, A.Ordinal, A.WorkOrder, B.WorkAllocation as SW FROM $tablename AS A INNER JOIN $tablemain AS B ON A.IDM=B.ID WHERE A.IDM='$LinkID' ORDER BY A.Ordinal");
   
            //  * select untuk workallocationitem
             ->select("SELECT
             A.Product,
             A.IDM,
             A.Ordinal,
             A.WorkOrder,
             B.ID SW 
         FROM
            $tablename AS A
             INNER JOIN $tablemain AS B ON A.IDM = B.ID 
         WHERE
             A.IDM = '$LinkID' 
         ORDER BY
             A.Ordinal");
// dd($data);
            //  * select untuk transferrmitem
            // ->select("SELECT A.Product, A.Carat, A.Ordinal, A.WorkOrder, B.ID SW FROM $tablename A INNER JOIN $tablemain B ON A.IDM=B.ID WHERE A.IDM='$LinkID'");

        //urutan    = status, tablename, UserName, Location, Product, Carat, Process, cause, LinkSW, LinkID, LinkOrd, workorder
        foreach ($data as $datas) {
            $Posting = $this->Public_Function
            ->PostingERP($status, $tablename, $UserName, $Location, $datas->Product, NULL, $Process, $cause, $datas->SW, $LinkID, $datas->Ordinal, $datas->WorkOrder);
            // dd($status, $tablename, $UserName, $Location, $datas->Product, NULL, $Process, $cause, $datas->SW, $LinkID, $datas->Ordinal, $datas->WorkOrder);
            // dd($Posting['validasi']);
        }
        // dd($Posting);

        $UpdateUserHistory = $this->Public_Function->SetStatustransactionERP($tablemain, $LinkID);
        
        $dateupdate = date('Y-m-d H:i:s');
        $updatewaxstoneusage = "UPDATE waxstoneusage
        SET Active = 'P', PostDate = '$dateupdate'
        WHERE ID = $LinkID";
        $updatewaxstoneusageSucces = FacadesDB::connection('erp')->update($updatewaxstoneusage);

        if ($Posting['validasi'] && $Posting['insertstok'] && $Posting['update_ptrns']) {
        // if ($Posting) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Posting Berhasil!!',
                ],
                201,
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Posting Gagal!!',
                ],
                400,
            );

            // return response('Post deleted successfully.', 400);
        }
    }

    public function PrintSPKOBatu(Request $request){
        // Get idwaxstoneusage
        $idWaxstoneusage = $request->idWaxstoneusage;
        // Get Header
        $WaxStoneHeader = FacadesDB::connection('erp')
        ->select("SELECT
        W.ID IDSPKOInject,
        T.ID IDSPKOBatu,
        T.Purpose,
        PB.SW,
        PB.Description,
        PB.Size,
        TI.Weight,
        TI.Qty,
        SUM( TI.Weight ) berattotal,
        SUM( TI.Qty ) jumlahtotal,
        E.SW Employee,
        E.ID idEmployee,
        T.UserName,
        T.EntryDate,
        T.TransDate,
        T.Active,
        T.Remarks
    -- 	P.Size
        
    FROM
        waxstoneusage T
        JOIN waxstoneusageitem TI ON T.ID = TI.IDM
        JOIN waxinjectorder W ON W.ID = T.WaxOrder
        JOIN Product PB ON PB.ID = TI.Product
        JOIn employee E ON E.ID = T.Employee
        WHERE T.ID = $idWaxstoneusage
    ");
        if (count($WaxStoneHeader) == 0) {
            abort(404);
        }
        $WaxStoneHeader = $WaxStoneHeader[0];
        // if ($WaxStoneHeader->Active != 'P') {
        //     abort(404);
        // }
        // IDWaxInjectOrder
        $idWaxInjectOrder = $WaxStoneHeader->IDSPKOInject;
        $IDSPKObatu = $WaxStoneHeader->IDSPKOBatu;
        // Get Items
        $WaxStoneTabel = FacadesDB::connection('erp')
        ->select("SELECT
                W.ID IDSPKOInject,
                T.ID IDSPKOBatu,
                K.SW SWWorkOrder,
                T.Purpose,
                PB.ID IdProduct,
                PB.SW Stone,
                PB.Description,
                PB.Size,
                TI.Weight,
                Ti.Qty,
                TI.Note
            -- 	P.Size
                
            FROM
                waxstoneusage T
                JOIN waxstoneusageitem TI ON T.ID = TI.IDM
                JOIN waxinjectorder W ON W.ID = T.WaxOrder
                LEFT JOIN Workorder K ON K.ID = TI.Workorder
                JOIN Product PB ON PB.ID = TI.Product
                WHERE T.ID = $idWaxstoneusage");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        return view('Produksi.Lilin.PenggunaanBatu.cetak2',compact('username','WaxStoneHeader','IDSPKObatu','idWaxInjectOrder','WaxStoneTabel','date','datenow','timenow'));
    }

    public function cariBatu(Request $request){
        $idbatu = $request->idston;

        $cariswstone= FacadesDB::connection('erp')
        ->select("SELECT SW FROM product WHERE ID = $idbatu");

        $swstone = $cariswstone[0]->SW;
        
        $data_return = $this->SetReturn(true, "Dapat SW workorder", ['swstone'=>$swstone], null);
        return response()->json($data_return, 200);
    }

    public function cariWorkOrder(Request $request){
        $idworkorder = $request->idwork;
        
        $cariswwork= FacadesDB::connection('erp')
        ->select("SELECT SW FROM workorder WHERE ID = $idworkorder");

        $rowcount = count($cariswwork);
        if($rowcount > 0){
            foreach ($cariswwork as $datas){}
            $Sw = $datas->SW;

            $data_Return = array(
                'swworkorder'=>$Sw);
              
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);

    }

    public function SWBatu(Request $request){
        $SWBatu = $request->value;

        $BatuSWdata = FacadesDB::connection('erp')
        ->select("SELECT
            P.SW Stone,
            P.Description,
            P.ID IDProduct
        FROM
            product P 
        WHERE
            P.ProdGroup = 126 AND 
            P.Active = 'Y' AND
            P.SW = '$SWBatu'");

        $rowcount = count($BatuSWdata);
        if($rowcount > 0){
            foreach ($BatuSWdata as $datas){}
            $Stone = $datas->Stone;
            $Description = $datas->Description;
            $IDProduct = $datas->IDProduct;

            // dd($Stone, $Description, $IDProduct);
            $data_Return = array(
                'rowcount' => $rowcount,
                'Stone' => $Stone, 
                'Description' => $Description,
                'IDProduct' => $IDProduct);
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }

    public function Search(Request $request){
        $keyword = $request->keyword;
        // Cek waxtree if exists
        $waxstoneusage = FacadesDB::connection('erp')
            ->select("SELECT
            W.ID WaxOrder,
            T.ID IDSPKOBatu,
            T.Purpose,
            PB.SW,
            PB.Description,
            PB.Size,
            TI.Weight,
            TI.Qty,
            SUM( TI.Weight ) berattotal,
            SUM( TI.Qty ) jumlahtotal,
            E.SW Employee,
            E.ID idEmployee,
            T.UserName,
            T.EntryDate,
            T.TransDate,
            T.Active,
            IF(T.Active = 'P','POSTED','') as Posting,
            T.Remarks
        -- 	P.Size
            
        FROM
            waxstoneusage T
            JOIN waxstoneusageitem TI ON T.ID = TI.IDM
            JOIN waxinjectorder W ON W.ID = T.WaxOrder
            JOIN Product PB ON PB.ID = TI.Product
            JOIn employee E ON E.ID = T.Employee
            WHERE T.ID = $keyword");
        if (count($waxstoneusage) == 0) {
            $data_return = $this->SetReturn(false, "gagal mencari data batu ide yang dimasukkan tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $waxstoneusage = $waxstoneusage[0];
       
        // IDWaxInjectOrder
        $idWaxInjectOrder = $waxstoneusage->WaxOrder;
        $databawah = FacadesDB::connection('erp')
        ->select("SELECT
                W.ID IDSPKOInject,
                T.ID IDSPKOBatu,
                K.SW SWWorkOrder,
                K.ID IDWorkOrder,
                T.Purpose,
                PB.ID IdProduct,
                PB.SW Stone,
                PB.Description,
                PB.Size,
                TI.Weight,
                Ti.Qty,
                A.berattotal,
                A.jumlahtotal,
                A.Avg,
                TI.Note
            -- 	P.Size
                
            FROM
                waxstoneusage T
                JOIN waxstoneusageitem TI ON T.ID = TI.IDM
                JOIN waxinjectorder W ON W.ID = T.WaxOrder
                left JOIN Workorder K ON K.ID = TI.Workorder
                JOIN Product PB ON PB.ID = TI.Product
                LEFT JOIN (
                SELECT
                    T.ID,
                    SUM( TI.Weight ) berattotal,
                    SUM( TI.Qty ) jumlahtotal,
                    SUM(TI.Weight / TI.Qty) Avg
                FROM
                    waxstoneusage T
                    JOIN waxstoneusageitem TI ON T.ID = TI.IDM
                WHERE
                    T.ID =  $keyword
                ) A ON A.ID = T.ID
                WHERE T.ID = $keyword");
        $waxstoneusage->items = $databawah;
        $data_return = $this->SetReturn(true, "Getting Waxstone Success. Waxstone found", $waxstoneusage, null);
        return response()->json($data_return, 200);
    }

    public function Update(Request $request){

        // dd($request);
        
        $IDPenggunaanbatu = $request->idpenggunaanbatu;
        // dd($IDPenggunaanbatu);
        $idEmployee = $request->idEmployee;
        $date = $request->date;
        $TotalBerat = $request->TotalBerat;
        $TotalJumlah = $request->TotalJumlah;
        $Keperluan = $request->Keperluan;
        $Catatan = $request->Catatan;
        
        // Check if idEmployee null or blank
        if (is_null($idEmployee) or $idEmployee == "") {
            $data_return = [
                "success"=>false,
                "message"=>"gagal membuat spk batu",
                "data"=>null,
                "error"=>[
                    "idEmployee"=>"idEmployee Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if beratPohonTotal null or blank
        if (is_null($TotalJumlah) or $TotalJumlah == "") {
            $data_return = [
                "success"=>false,
                "message"=>"gagal Menyimpan SPK Batu",
                "data"=>null,
                "error"=>[
                "Totaljumlah"=>"TotalJumlahbatu Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        $update_waxstoneusage = waxstoneusage::where('ID',$IDPenggunaanbatu)->update([
            'UserName' => $username, // username yang login
            'Remarks' => $Catatan, //dari form isisan catatan
            'TransDate' => date('Y-m-d H:i:s'), //dari form tanggal yang di inputkan
            'Employee' => $idEmployee,
            'Purpose' => $Keperluan,
        ]);

        $delete_waxstoneusageItem = waxstoneusageitem::where('IDM',$IDPenggunaanbatu)->delete();

        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_waxstoneusageitem = waxstoneusageitem::create([
                'IDM' => $IDPenggunaanbatu,
                'Ordinal' => $IT+1,
                'WorkOrder' =>$isi['WorkOrder'],
                'Product' => $isi['IdProduct'],
                'Qty' => $isi['itemQty'], //
                'Weight' => $isi['beratbatu'], // dari tabel workorderitem kolom IDM
                'Note'=>$isi['Note'],
            ]);
        }
        
        $WaxStoneHeader = FacadesDB::connection('erp')
        ->select("SELECT
            SUM( TI.Weight ) berattotal,
            SUM( TI.Qty ) jumlahtotal
        FROM
            waxstoneusage T
            JOIN waxstoneusageitem TI ON T.ID = TI.IDM
            JOIN waxinjectorder W ON W.ID = T.WaxOrder
            JOIN Product PB ON PB.ID = TI.Product
            JOIn employee E ON E.ID = T.Employee
            WHERE T.ID = $IDPenggunaanbatu");
            
        $berat = $WaxStoneHeader[0]->berattotal;
        $jumlah = $WaxStoneHeader[0]->jumlahtotal;

        $data_return = $this->SetReturn(true, "Update SPKO Batu Sukses", ['berattotaltes'=>$berat, 'IDtes'=>$IDPenggunaanbatu, 'jumlahtotaltes'=>$jumlah], null);
        return response()->json($data_return, 200);
    }
}
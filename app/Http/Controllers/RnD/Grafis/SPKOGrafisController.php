<?php

namespace App\Http\Controllers\RnD\Grafis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
// Public Function
use App\Http\Controllers\Public_Function_Controller;

// Models
use App\Models\erp\lastid;
use App\Models\rndnew\grafisworklist;
use App\Models\erp\workallocation;
use App\Models\erp\workallocationitem;
use App\Models\erp\workallocationresult;

// use App\Models\tes_laravel\grafisworklist;
// use App\Models\tes_laravel\workallocation;
// use App\Models\tes_laravel\workallocationitem;
// use App\Models\tes_laravel\workallocationresult;

class SPKOGrafisController extends Controller{
    // Setup Public Function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    // START REUSABLE FUNCTION
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    private function GetEmployee($keyword){
        $employee = FacadesDB::connection('erp')
        ->table('Employee AS E')
        ->join('Department AS D', function($join){
            $join->on("E.Department","=","D.ID");
        })
        ->selectRaw("
            E.ID,
            E.Description NAME,
            D.Description Bagian,
            E.Department,
            E.WorkRole,
            E.Rank
        ")
        ->where("E.SW", "=", "$keyword")
        ->orWhere("E.ID","=","".$keyword)
        ->orderBy("E.Department","ASC")
        ->get();
        return $employee;
    }
    // END REUSABLE FUNCTION


    public function Index(Request $request){
        // Generate Session for file 
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');
        $employees = FacadesDB::connection('erp')
        ->select("
            SELECT
                ID,
                SW,
                Description 
            FROM
                employee
            WHERE
                department = 51
                AND Active = 'Y'
        ");
        $now = date("Y-m-d");
        $history = FacadesDB::select("
            SELECT
                A.NextWorkAllocation AS workAllocation
            FROM
                grafisworklist A 
            WHERE
                A.NextWorkAllocation IS NOT NULL
            GROUP BY
                A.NextWorkAllocation
            ORDER BY
                A.NextWorkAllocation DESC
            LIMIT 10
        ");
        return view('R&D.Grafis.SPKOGrafis.Index', compact('employees','now', 'history'));
    }

    public function GetWIP(Request $request){
        $noNTHKO = $request->noNTHKO;
        if (is_null($noNTHKO) or $noNTHKO == "") {
            $data_return = $this->SetReturn(false, "noNTHKO can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check if that noNTHKO is in grafisworklist
        $cekGrafisWorkList = grafisworklist::where('WorkAllocation',$noNTHKO)->get();
        if(count($cekGrafisWorkList) < 1){
            $data_return = $this->SetReturn(false, "noNTHKO Tersebut tidak ada di WIP Grafis", null, null);
            return response()->json($data_return, 400);
        }

        // Calculate Jumlah and Berat
        $totalJumlah = 0;
        $totalBerat = 0;
        foreach ($cekGrafisWorkList as $key => $value) {
            $totalJumlah+=$value->Qty;
            $totalBerat+=$value->Weight;
        }

        // Get Items
        $items = FacadesDB::select("
            SELECT
                A.*,
                B.SKU AS namaProduct,
                C.Description AS kadar,
                C.ID AS IDKadar,
                A.Qty AS jumlah,
                A.Weight AS berat,
                B.Photo,
                E.WorkOrder,
                E.IDM AS PrevProcess,
                E.Ordinal AS PrevOrd,
                E.BatchNo
            FROM
                grafisworklist A
                JOIN erp.product B ON A.Product = B.ID
                JOIN erp.productcarat C ON A.Carat = C.ID
                JOIN erp.workcompletion D ON A.WorkAllocation = D.WorkAllocation AND A.LinkFreq = D.Freq
                JOIN erp.workcompletionitem E ON D.ID = E.IDM AND A.LinkOrd = E.Ordinal 
            WHERE
                A.WorkAllocation = '$noNTHKO'
        ");

        // Get Items From DEV
        // $items = FacadesDB::connection('dev')->select("
        //     SELECT
        //         A.*,
        //         B.SW AS namaProduct,
        //         C.Description AS kadar,
        //         C.ID AS IDKadar,
        //         A.Qty AS jumlah,
        //         A.Weight AS berat,
        //         B.Photo,
        //         E.WorkOrder,
        //         E.IDM AS PrevProcess,
        //         E.Ordinal AS PrevOrd,
        //         E.BatchNo
        //     FROM
        //         grafisworklist A
        //         JOIN product B ON A.Product = B.ID
        //         JOIN productcarat C ON A.Carat = C.ID
        //         JOIN workcompletion D ON A.WorkAllocation = D.WorkAllocation
        //         JOIN workcompletionitem E ON D.ID = E.IDM AND A.LinkOrd = E.Ordinal 
        //     WHERE
        //         A.WorkAllocation = '$noNTHKO'
        // ");

        $itemsHTML = view('R&D.Grafis.SPKOGrafis.layoutTableItem', compact('items'))->render();
        $data = [
            "totalJumlah"=>$totalJumlah,
            "totalBerat"=>$totalBerat,
            "items"=>$items,
            "itemsHTML"=>$itemsHTML
        ];
        $data_return = $this->SetReturn(true, "WIP Grafis Found", $data, null);
        return response()->json($data_return, 200);
    }
    // Next here

    public function SaveSPKO(Request $request){
        // dd("oke");
        $noNTHKO = $request->noNTHKO;
        $employee = $request->employee;
        $total_berat = $request->total_berat;
        $beratItems = $request->beratItems;
        if (is_null($noNTHKO) or $noNTHKO == "") {
            $data_return = $this->SetReturn(false, "noNTHKO can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        if (is_null($employee) or $employee == "") {
            $data_return = $this->SetReturn(false, "employee can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // check total_berat is null or blank
        if (is_null($total_berat) or $total_berat == "") {
            $data_return = $this->SetReturn(false, "total_berat can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check beratItems is blank or null or is not array or is empty
        if (is_null($beratItems) or $beratItems == "" or !is_array($beratItems) or count($beratItems) < 1) {
            $data_return = $this->SetReturn(false, "beratItems can't be null or blank or is not array or is empty", null, null);
            return response()->json($data_return, 400);
        }

        // CheckEmployee
        $cekEmployee = $this->GetEmployee($employee);
        if (count($cekEmployee) == 0) {
            $data_return = $this->SetReturn(false, "Employee Not Found", null, null);
            return response()->json($data_return, 404);
        }

        // Check if that noNTHKO is in grafisworklist
        $cekGrafisWorkList = grafisworklist::where('WorkAllocation',$noNTHKO)->get();
        if(count($cekGrafisWorkList) < 1){
            $data_return = $this->SetReturn(false, "noNTHKO Tersebut tidak ada di WIP Grafis", null, null);
            return response()->json($data_return, 400);
        }

        // Check if that noNTHKO is SPKOed (NextWorkAllocation must be null.)
        if (!is_null($cekGrafisWorkList[0]->NextWorkAllocation)) {
            $data_return = $this->SetReturn(false, "noNTHKO Tersebut Sudah Pernah di SPKO", null, null);
            return response()->json($data_return, 400);
        }

        // Calculate Jumlah and Berat
        $totalJumlah = 0;
        $totalBerat = 0;
        // Loop beratItems for calculate total berat
        foreach ($beratItems as $key => $value) {
            $totalBerat+=$value;
        }

        // Loop cekGrafisWorkList for calculate total jumlah
        foreach ($cekGrafisWorkList as $key => $value) {
            $totalJumlah+=$value->Qty;
        }

        // Get Items
        $items = FacadesDB::select("
            SELECT
                A.*,
                B.SW AS namaProduct,
                C.Description AS kadar,
                C.ID AS IDKadar,
                A.Qty AS jumlah,
                A.Weight AS berat,
                B.Photo,
                E.WorkOrder,
                E.IDM AS PrevProcess,
                E.Ordinal AS PrevOrd,
                E.BatchNo
            FROM
                grafisworklist A
                JOIN erp.product B ON A.Product = B.ID
                JOIN erp.productcarat C ON A.Carat = C.ID
                JOIN erp.workcompletion D ON A.WorkAllocation = D.WorkAllocation AND A.LinkFreq = D.Freq
                JOIN erp.workcompletionitem E ON D.ID = E.IDM AND A.LinkOrd = E.Ordinal 
            WHERE
                A.WorkAllocation = '$noNTHKO'
        ");

        // Get Items from DEV
        // $items = FacadesDB::connection("dev")->select("
        //     SELECT
        //         A.*,
        //         B.SW AS namaProduct,
        //         C.Description AS kadar,
        //         C.ID AS IDKadar,
        //         A.Qty AS jumlah,
        //         A.Weight AS berat,
        //         B.Photo,
        //         E.WorkOrder,
        //         E.IDM AS PrevProcess,
        //         E.Ordinal AS PrevOrd,
        //         E.BatchNo
        //     FROM
        //         grafisworklist A
        //         JOIN product B ON A.Product = B.ID
        //         JOIN productcarat C ON A.Carat = C.ID
        //         JOIN workcompletion D ON A.WorkAllocation = D.WorkAllocation
        //         JOIN workcompletionitem E ON D.ID = E.IDM AND A.LinkOrd = E.Ordinal 
        //     WHERE
        //         A.WorkAllocation = '$noNTHKO'
        // ");


        // check length of items is equals with length of beratItems
        if (count($items) != count($beratItems)) {
            $data_return = $this->SetReturn(false, "beratItems and items length is not equals", null, null);
            return response()->json($data_return, 400);
        }

        // Check if that workallocation(ID WorkCompletion) from Grafisworklist is exists on workallocationitem
        // $idWorkCompletion = $items[0]->PrevProcess;
        // $cekWorkAllocationitem = workallocationitem::where('PrevProcess',$idWorkCompletion)->first();
        // if (!is_null($cekWorkAllocationitem)) {
        //     $data_return = $this->SetReturn(false, "noNTHKO Tersebut Sudah Pernah di SPKO", null, null);
        //     return response()->json($data_return, 400);
        // }

        // All checking success
        // dd("Woke Aman");

        // Generate ID for workAllocation
        $IDWorkAllocationGenerate = FacadesDB::connection('erp')
        ->select("
            SELECT
                CASE 
                WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+ 1 END AS ID,
                DATE_FORMAT( CURDATE(), '%y' ) + 50 AS tahun,
                LPad( MONTH ( CurDate()), 2, '0' ) AS bulan,
                CONCAT(
                    DATE_FORMAT( CURDATE(), '%y' ) + 50,
                    '',
                    LPad( MONTH ( CurDate()), 2, '0' ),
                    '56',
                LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+ 1 END, 4, '0' )) Counter1 
            FROM
                workallocation 
            WHERE
                Location = 56 
                AND SWYear = DATE_FORMAT( CURDATE(), '%y' ) + 50
                AND SWMonth = MONTH (CurDate())
        ");

        // GetLastID for workAllocation
        $lastid = lastid::where('Module','WorkAllocation')->first();
        $lastid = $lastid->Last+1;

        // Update lastid
        $updateLastid = lastid::where('Module','WorkAllocation')->update(["Last"=>$lastid]);

        // Create workallocation;
        $createWorkAllocation = workallocation::create([
            "ID"=>$lastid,
            "UserName"=>Auth::user()->name,
            "SW"=>$IDWorkAllocationGenerate[0]->Counter1,
            "Freq"=>1,
            "TransDate"=>date("Y-m-d"),
            "Purpose"=>"Tambah",
            "Carat"=>$cekGrafisWorkList[0]->Carat,
            "Location"=>56,
            "Operation"=>178,
            "Employee"=>$employee,
            "TargetQty"=>$totalJumlah,
            "Weight"=>$totalBerat,
            "Active"=>"A",
            "SWYear"=>$IDWorkAllocationGenerate[0]->tahun,
            "SWMonth"=>$IDWorkAllocationGenerate[0]->bulan,
            "SWOrdinal"=>$IDWorkAllocationGenerate[0]->ID,
            "OutSource"=>"N"
        ]);
        
        // Loop item for insert to WorkAllocationItem
        foreach ($items as $key => $value) {
            $indexItem = $key;
            // Insert to Workallocationitem
            $createWorkAllocationItem = workallocationitem::create([
                "IDM"=>$lastid,
                "Ordinal"=>$key+1,
                "Product"=>5632,
                "Carat"=>$value->IDKadar,
                "Qty"=>$value->jumlah,
                "Weight"=>$beratItems[$indexItem],
                "WorkOrder"=>$value->WorkOrder,
                "PrevProcess"=>$value->PrevProcess,
                "PrevOrd"=>$value->PrevOrd,
                "TreeID"=>$value->TreeID,
                "TreeOrd"=>$value->TreeOrd,
                "FG"=>$value->Product,
                "BatchNo"=>$value->BatchNo
            ]);
        }

        // // Insert To WorkAllocationResult
        // $createWorkAllocationResult = workallocationresult::create([
        //     "SW"=>$IDWorkAllocationGenerate[0]->Counter1,
        //     "Location"=>56,
        //     "Operation"=>178,
        //     "Employee"=>$employee,
        //     "Carat"=>$cekGrafisWorkList[0]->Carat,
        //     "TargetQty"=>$totalJumlah,
        //     "Weight"=>$totalBerat,
        //     "AllocationDate"=>date("Y-m-d H:i:s"),
        //     "AllocationFreq"=>1
        // ]);

        // Update NextWorkAllocation
        $updateGrafisWorkList = grafisworklist::where('WorkAllocation',$noNTHKO)->update([
            "StartFoto"=>date("Y-m-d H:i:s"),
            "NextWorkAllocation"=>$IDWorkAllocationGenerate[0]->Counter1
        ]);

        $idSPKO = $createWorkAllocation->id;
        $data_return = $this->SetReturn(true, "SPKO Grafis Berhasil Dibuat", ['idSPKO'=>$idSPKO, "WorkAllocation"=>$IDWorkAllocationGenerate[0]->Counter1], null);
        return response()->json($data_return, 200);    
    }

    public function PostingSPKO(Request $request){
        $swWorkAllocation = $request->workAllocation;
        if (is_null($swWorkAllocation) or $swWorkAllocation == "") {
            $data_return = $this->SetReturn(false, "workAllocation can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Find that workallocation in grafisworklist on nextworkallocation
        $findGrafisWorklist = grafisworklist::where('NextWorkAllocation',$swWorkAllocation)->first();
        if (is_null($findGrafisWorklist)) {
            $data_return = $this->SetReturn(false, "SPKO Grafis NotFound", null, null);
            return response()->json($data_return, 404);
        }

        // Find that workallocation in workallocation on SW
        $findWorkAllocation = workallocation::where('SW',$swWorkAllocation)->first();
        if (is_null($findWorkAllocation)) {
            $data_return = $this->SetReturn(false, "SPKO WorkAllocation NotFound", null, null);
            return response()->json($data_return, 404);
        }

        // Check if workallocation is already posted or not
        if ($findWorkAllocation->Active != 'A') {
            $data_return = $this->SetReturn(false, "SPKO Sudah Pernah di Posting", null, null);
            return response()->json($data_return, 400);
        }

        // Ger workallocation and item for run public function posting
        $getWorkAllocationItem = workallocationitem::where('IDM',$findWorkAllocation->ID)->get();

        // Check stok harian before posting
        $cekStokHarian = $this->Public_Function->CekStokHarianERP($findWorkAllocation->Location, $findWorkAllocation->TransDate);
        if (!$cekStokHarian) {
            $data_return = $this->SetReturn(false, "Posting SPKO Gagal. Belum Stok Harian", null, null);
            return response()->json($data_return, 400);
        }
        
        // Loop WorkAllocationItem for execute public function posting
        foreach ($getWorkAllocationItem as $key => $value) {
            // Consume Public Function for posting
            $status = "C"; //For credit (SPKO)
            $tableitem = "workallocationitem"; // Tabel item
            $userName = Auth::user()->name; // User who post this transaction;
            $location = $findWorkAllocation->Location; //Location PCB
            $product = $value->Product; // Ini nanti looping dari workallocationitem;
            $carat = $value->Carat; // Ini nanti looping dari workallocationitem;
            $Process = 'Production'; //Default
            $cause = 'Usage'; //todo: Usage (Stok Berkurang) (Untuk SPKO)
            $SW = $findWorkAllocation->SW; //Ini nanti dapat dari tabel workallocation
            $idSPKO = $value->IDM; // Ini nanti dapat dari tabel workallocationitem karena looping
            $ordinal = $value->Ordinal; // Ini nanti dapat dari tabel workallocationitem karena looping
            $workorder = $value->WorkOrder; //Ini nanti dapat dari tabel workallocationitme

            // Execute public Function posting
            $postingFunction = $this->Public_Function->PostingERP($status, $tableitem, $userName, $location, $product, $carat, $Process, $cause, $SW, $idSPKO, $ordinal, $workorder);
            // Execute public Function posting DEV
            // $postingFunction = $this->Public_Function->PostingDEV($status, $tableitem, $userName, $location, $product, $carat, $Process, $cause, $SW, $idSPKO, $ordinal, $workorder);
        }
        
        // Check Posting function Status
        if ($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']) {
            // Create WorkAllocation Result
            $createWorkAllocationResult = workallocationresult::create([
                "SW"=>$findWorkAllocation['SW'],
                "Location"=>56,
                "Operation"=>178,
                "Employee"=>$findWorkAllocation['Employee'],
                "Carat"=>$findWorkAllocation['Carat'],
                "TargetQty"=>$findWorkAllocation['TargetQty'],
                "Weight"=>$findWorkAllocation['Weight'],
                "AllocationDate"=>date("Y-m-d H:i:s"),
                "AllocationFreq"=>1
            ]);

            // Update WorkAllocation to posting
            $updateWorkAllocation = workallocation::where('ID',$findWorkAllocation->ID)
            ->update([
                "Active"=>"P",
                "PostDate"=>date("Y-m-d H:i:s"),
                "Remarks"=>"Posting Laravel"
            ]);
    
            // Success
            $data_return = $this->SetReturn(true, "Posting SPKO Success", null, null);
            return response()->json($data_return, 200);
        } else {
            // Success
            $data_return = $this->SetReturn(false, "Posting SPKO Gagal. Hubungi IT", null, null);
            return response()->json($data_return, 500);
        }
    }

    public function FindSPKO(Request $request){
        $swWorkAllocation = $request->workAllocation;
        if (is_null($swWorkAllocation) or $swWorkAllocation == "") {
            $data_return = $this->SetReturn(false, "workAllocation can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Find that workallocation in grafisworklist on nextworkallocation
        $findGrafisWorklist = grafisworklist::where('NextWorkAllocation',$swWorkAllocation)->first();
        if (is_null($findGrafisWorklist)) {
            $data_return = $this->SetReturn(false, "SPKO Grafis NotFound", null, null);
            return response()->json($data_return, 404);
        }
        
        // Get Items
        $items = FacadesDB::select("
            SELECT
                B.SW AS namaProduct,
                C.Description AS kadar,
                A.Qty AS jumlah,
                A.Weight AS berat,
                B.Photo,
                A.ProbablyDone
            FROM
                grafisworklist A
                JOIN erp.product B ON A.Product = B.ID
                JOIN erp.productcarat C ON A.Carat = C.ID
            WHERE
                A.NextWorkAllocation = '$swWorkAllocation'
        ");

        // Get Items from DEV
        // $items = FacadesDB::connection("dev")->select("
        //     SELECT
        //         B.SW AS namaProduct,
        //         C.Description AS kadar,
        //         A.Qty AS jumlah,
        //         A.Weight AS berat,
        //         B.Photo
        //     FROM
        //         grafisworklist A
        //         JOIN erp.product B ON A.Product = B.ID
        //         JOIN erp.productcarat C ON A.Carat = C.ID
        //     WHERE
        //         A.NextWorkAllocation = '$swWorkAllocation'
        // ");

        // Get WorkAllocation
        $getWorkAllocation = workallocation::where('SW',$swWorkAllocation)->first();
        if ($getWorkAllocation['Active'] == 'C') {
            $data_return = $this->SetReturn(false, "SPKO Grafis Ini di Cancle", null, null);
            return response()->json($data_return, 400);
        }

        $totalJumlah = 0;
        $totalBerat = 0;
        // Loop items for calculate Qty and weight
        foreach ($items as $key => $value) {
            $totalJumlah+=$value->jumlah;
            $totalBerat+=$value->berat;
        }

        $itemsHTML = view('R&D.Grafis.SPKOGrafis.layoutTableItem', compact('items'))->render();
        
        $getWorkAllocation['totalBerat'] = $totalBerat;
        $getWorkAllocation['totalJumlah'] = $totalJumlah;
        $getWorkAllocation['noNTHKO'] = $findGrafisWorklist->WorkAllocation;
        $getWorkAllocation['items'] = $items;
        $getWorkAllocation['itemsHTML'] = $itemsHTML;
        
        $data_return = $this->SetReturn(true, "SPKO Grafis Found", $getWorkAllocation, null);
        return response()->json($data_return, 200);

    }

    public function CetakSPKO(Request $request){
        $swWorkAllocation = $request->workAllocation;
        if (is_null($swWorkAllocation) or $swWorkAllocation == "") {
            abort(404);
        }

        // Find that workallocation in grafisworklist on nextworkallocation
        $findGrafisWorklist = grafisworklist::where('NextWorkAllocation',$swWorkAllocation)->first();
        if (is_null($findGrafisWorklist)) {
            abort(404);
        }

        // Get WorkAllocation
        $workAllocation = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.ID,
                A.SW,
                A.TransDate,
                A.TargetQty,
                A.Weight,
                B.ID AS idEmployee,
                B.SW AS swEmployee,
                B.Description AS employeeName,
                C.ID AS idAdmin,
                C.SW AS swAdmin,
                C.Description AS adminName
            FROM
                workallocation A
                JOIN employee B ON A.Employee = B.ID
                JOIN employee C ON A.UserName = C.SW
            WHERE
                A.SW = '$swWorkAllocation'
                AND A.Active = 'P'
        ");
        // Get WorkAllocation DEV
        // $workAllocation = FacadesDB::connection('dev')
        // ->select("
        //     SELECT
        //         A.ID,
        //         A.SW,
        //         A.TransDate,
        //         B.ID AS idEmployee,
        //         B.SW AS swEmployee,
        //         B.Description AS employeeName,
        //         C.ID AS idAdmin,
        //         C.SW AS swAdmin,
        //         C.Description AS adminName
        //     FROM
        //         workallocation A
        //         JOIN employee B ON A.Employee = B.ID
        //         JOIN employee C ON A.UserName = C.SW
        //     WHERE
        //         A.SW = '$swWorkAllocation'
        // ");
        if (count($workAllocation) == 0) {
            abort(404);
        }

        $workAllocation = $workAllocation[0];

        // Get Items
        $workAllocationItems = FacadesDB::connection('erp')
        ->select("
            SELECT
                C.SW AS noSPK,
                D.SW AS namaProduct,
                D.Description AS descriptionProduct,
                E.Description AS kadar,
                B.Qty AS jumlah,
                B.Weight AS berat
            FROM
                workallocation A
                JOIN workallocationitem B ON A.ID = B.IDM
                JOIN workorder C ON B.WorkOrder = C.ID
                JOIN product D ON B.FG = D.ID
                JOIN productcarat E ON B.Carat = E.ID
            WHERE
                A.SW = '$swWorkAllocation'
                AND A.Active = 'P'
        ");

        // Get Items DEV
        // $workAllocationItems = FacadesDB::connection('dev')
        // ->select("
        //     SELECT
        //         C.SW AS noSPK,
        //         D.SW AS namaProduct,
        //         D.Description AS descriptionProduct,
        //         E.Description AS kadar,
        //         B.Qty AS jumlah,
        //         B.Weight AS berat
        //     FROM
        //         workallocation A
        //         JOIN workallocationitem B ON A.ID = B.IDM
        //         JOIN workorder C ON B.WorkOrder = C.ID
        //         JOIN product D ON B.FG = D.ID
        //         JOIN productcarat E ON B.Carat = E.ID
        //     WHERE
        //         A.SW = '$swWorkAllocation'
        // ");
        
        return view('R&D.Grafis.SPKOGrafis.cetak', compact('workAllocation','workAllocationItems'));
    }
}
<?php

namespace App\Http\Controllers\LainLain\Korespondensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;


use App\Models\erp\employee;
use App\Models\messaging\recipient;
use App\Models\messaging\pengantarpengembalian;
use App\Models\messaging\pengantarpengembalianitem;

// Import public function controller for logger
use App\Http\Controllers\Public_Function_Controller;

class SuratPengantarController extends Controller
{
    //!  ------------------------     Public Function     ------------------------ !!
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }
    //!  ------------------------     End Public Function     ------------------------ !!

    //!  ------------------------     Reuseable Function     ------------------------ !!
    private function UserType(){
        // Get Employee Department for getting recipient according with UserAccess
        $employee = employee::select("Department")->where("SW",Auth::user()->name)->get();
        $department = $employee[0]->Department;

        // Define UserAccess for recipient
        if ($department == 3) {
            // Sales
            $UserAccess = 1;
        } elseif ($department == 11) {
            // Akunting
            $UserAccess = 2;
        } elseif ($department == 17) {
            // Keuangan
            $UserAccess = 3;
        } elseif ($department == 16) {
            // Pembelian
            $UserAccess = 4;
        } else {
            // DEV
            $UserAccess = 0;
        }

        return $UserAccess;

    }

    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    private function GetIDModule()
    {
        $IDModule = "290";
        return $IDModule;
    }

    private function CreateHistory($idsuratpengantar)
    {
        // Insert this activity to useractivityweb
        // ->Getting ID Employee first
        $employee = FacadesDB::connection('erp')
        ->table('Employee AS E')
        ->join('Department AS D', function($join){
            $join->on("E.Department","=","D.ID");
        })
        ->selectRaw("
            E.ID,
            E.Description Name
        ")
        ->where("E.SW","=","".Auth::User()->name)
        ->where("E.Active",'=',"Y")
        ->get();

        // ->set UserID == employee[0]->ID
        $UserID = $employee[0]->ID;

        // ->Get IDModule
        $Module = $this->GetIDModule();

        // Insert to UserHistory web
        $UpdateUserHistory  = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $idsuratpengantar);
    }
    //!  ------------------------     End Reuseable Function     ------------------------ !!
    
    public function Index(){
        $datenow = date('Y-m-d');
        $history = pengantarpengembalian::where('UserName',Auth::user()->name)->limit(10)->orderBy('ID','desc')->get();
        return view("Lain-lain.Korespondensi.SuratPengantar.index",compact('datenow','history'));
    }

    public function FindRecipient(Request $request){
        $UserAccess = $this->UserType();

        $sw = $request->sw;
        if ($sw == null or $sw == ""){
            $data_return = [
                "success"=>false,
                "message"=>"Get Recipient failed",
                "data"=>null,
                "error"=>[
                    "sw"=>"SW Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        $dataRecipient = recipient::select('SW as value','Address','ID')
        ->where("UserAccess", "=" ,$UserAccess)
        ->where("SW", "LIKE", '%'.$sw.'%')
        ->orderBy('ID','desc')
        ->limit(10)
        ->get();
        return response()->json($dataRecipient,200);
    }

    public function CreateSuratPengantar(Request $request){
        // Getting Required Data
        $toemployee = $request->toemployee;
        $tanggal = $request->tanggal;
        $recipient = $request->recipient;
        $alamat = $request->alamat;
        $items = $request->items;

        // Check if jenis surat null or blank
        if (is_null($toemployee) or $toemployee == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "toemployee"=>"toemployee Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "tanggal"=>"tanggal Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if recipient null or blank
        if (is_null($recipient) or $recipient == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "recipient"=>"recipient Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if alamat null or blank
        if (is_null($alamat) or $alamat == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "alamat"=>"alamat Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "items"=>"items Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("qty",$item) and array_key_exists("satuan",$item) and array_key_exists("namabarang",$item)){
                // Check qty in item
                if (is_numeric($item['qty']) or is_null($item['qty'])){
                    // Add qty to total qty
                    if (is_null($item['qty'])){
                        $TotalQTY += 0;
                    }else {
                        $TotalQTY += $item['qty'];   
                    }
                }else {
                    $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed",null, ["qty"=>"qty must be integer"]);
                    return response()->json($data_return,400);
                }
                // Check namabarang in item
                if (is_null($item['namabarang']) or $item['namabarang'] == ""){
                    $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed",null, ["namabarang"=>"There still any blank or null value on namabarang"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed",null, ["items"=>"Items required qty, satuan, and namabarang"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        $userType = $this->UserType();

        // Generate SW and SWOrdinal
        $Gen_SW_SWO = FacadesDB::connection("messaging")
        ->select("
            SELECT
                CONCAT('".$userType."','',DATE_FORMAT( CURDATE(), '%y' ),'', LPad( Count( ID ) + 1, 4, '0' )) as SW,
                Count( ID ) + 1 AS id 
            FROM
                pengantarpengembalian 
            WHERE
                YEAR ( TransDate ) = YEAR (
                CurDate()) 
        ");
        
        // Generate ID for suratpengantar
        $idsuratpengantar = pengantarpengembalian::orderBy('ID','desc')->first();
        $idsuratpengantar = ($idsuratpengantar->ID)+1;

        // Check recipient if having same SW and Address
        $dataRecipient = recipient::where("SW",$recipient)
        ->where("Address",$alamat)
        ->where("UserAccess", $userType)
        ->get();
        

        if (count($dataRecipient) != 0){
            // lanjut transaksi
            $IDRecipient = $dataRecipient[0]->ID;

            // Create Surat Pengantar
            $suratpengantar = pengantarpengembalian::create([
                "ID"=>$idsuratpengantar,
                "EntryDate"=> date('Y-m-d H:i:s'),
                "UserName"=>Auth::user()->name,
                "SW"=>$Gen_SW_SWO[0]->SW,
                "TransDate"=>$tanggal,
                "ToUser"=>$IDRecipient,
                "Address"=>$dataRecipient[0]->Address,
                "TotalQty"=>$TotalQTY,
                "ToEmployee"=>$toemployee
            ]);
            
            // // Create Surat Pengantar Item
            foreach ($items as $key => $item) {
                $suratpengantaritem = pengantarpengembalianitem::create([
                    "IDM"=>$idsuratpengantar,
                    "Ordinal"=>$key+1,
                    "Item"=>$item['namabarang'],
                    "Qty"=>$item['qty'],
                    "Satuan"=>$item['satuan']
                ]);
            }

            // Insert Log
            $this->CreateHistory($idsuratpengantar);

            $data_return = $this->SetReturn(true,"Create Surat Pengantar Success",["ID"=>$idsuratpengantar,"SW"=>$Gen_SW_SWO[0]->SW], null);
            return response()->json($data_return,200);
            
        }
        
        // Generate IDRecipient
        $IDRecipient = recipient::orderBy('ID','desc')->first();
        $IDRecipient = ($IDRecipient->ID)+1;

        // Create new Recipient
        $dataRecipient = recipient::create([
            "SW"=>$recipient,
            "Address"=>$alamat,
            "UserAccess"=>$this->UserType(),
            "UserName"=>Auth::User()->name,
            "Active"=>1,
            "Disposable"=>"N"
        ]);

        // Create Surat Pengantar
        $suratpengantar = pengantarpengembalian::create([
            "ID"=>$idsuratpengantar,
            "EntryDate"=> date('Y-m-d H:i:s'),
            "UserName"=>Auth::user()->name,
            "SW"=>$Gen_SW_SWO[0]->SW,
            "TransDate"=>$tanggal,
            "ToUser"=>$IDRecipient,
            "Address"=>$alamat,
            "TotalQty"=>$TotalQTY,
            "ToEmployee"=>$toemployee
        ]);
        
        // // Create Surat Pengantar Item
        foreach ($items as $key => $item) {
            $suratpengantaritem = pengantarpengembalianitem::create([
                "IDM"=>$idsuratpengantar,
                "Ordinal"=>$key+1,
                "Item"=>$item['namabarang'],
                "Qty"=>$item['qty'],
                "Satuan"=>$item['satuan']
            ]);
        }

        // Insert Log
        $this->CreateHistory($idsuratpengantar);

        $data_return = $this->SetReturn(true,"Create Surat Pengantar Success",["ID"=>$idsuratpengantar,"SW"=>$Gen_SW_SWO[0]->SW], null);
        return response()->json($data_return,200);
    }

    public function UpdateSuratPengantar(Request $request){
        // Getting Required Data
        $sw = $request->sw;
        $toemployee = $request->toemployee;
        $tanggal = $request->tanggal;
        $recipient = $request->recipient;
        $alamat = $request->alamat;
        $items = $request->items;

        // Check if jenis surat null or blank
        if (is_null($toemployee) or $toemployee == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "toemployee"=>"toemployee Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "tanggal"=>"tanggal Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if recipient null or blank
        if (is_null($recipient) or $recipient == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "recipient"=>"recipient Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if alamat null or blank
        if (is_null($alamat) or $alamat == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "alamat"=>"alamat Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Pengantar Failed",
                "data"=>null,
                "error"=>[
                    "items"=>"items Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("qty",$item) and array_key_exists("satuan",$item) and array_key_exists("namabarang",$item)){
                // Check qty in item
                if (is_numeric($item['qty']) or is_null($item['qty'])){
                    // Add qty to total qty
                    if (is_null($item['qty'])){
                        $TotalQTY += 0;
                    }else {
                        $TotalQTY += $item['qty'];   
                    }
                }else {
                    $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed",null, ["qty"=>"qty must be integer"]);
                    return response()->json($data_return,400);
                }
                // Check namabarang in item
                if (is_null($item['namabarang']) or $item['namabarang'] == ""){
                    $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed",null, ["namabarang"=>"There still any blank or null value on namabarang"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Create Surat Pengantar Failed",null, ["items"=>"Items required qty, satuan, and namabarang"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        $userType = $this->UserType();

        // Check if surat pengantar exists
        $suratpengantar = pengantarpengembalian::where("SW",$sw)->get();
        if(count($suratpengantar) == 0){
            $data_return = $this->SetReturn(false,"Update Surat pengantar Failed", null, ["sw"=>"Surat pengantar with that sw not found"]);
            return response()->json($data_return,404);
        }

        // Set ID Surat Pengantar
        $idsuratpengantar = $suratpengantar[0]->ID;

        // Check recipient if having same SW and Address
        $dataRecipient = recipient::where("SW",$recipient)
        ->where("Address",$alamat)
        ->where("UserAccess", $userType)
        ->get();

        if (count($dataRecipient) != 0){
            // lanjut transaksi
            $IDRecipient = $dataRecipient[0]->ID;

            // Update Surat Pengantar
            $suratpengantar = pengantarpengembalian::where("ID",$idsuratpengantar)->update([
                "TransDate"=>$tanggal,
                "ToUser"=>$IDRecipient,
                "ToEmployee"=>$toemployee,
                "Address"=>$dataRecipient[0]->Address,
                "TotalQty"=>$TotalQTY
            ]);

            // Delete suratpengantaritem
            $deletesuratpengantaritem = pengantarpengembalianitem::where("IDM",$idsuratpengantar)->delete();
            
            // // Create Surat Pengantar Item
            foreach ($items as $key => $item) {
                $suratpengantaritem = pengantarpengembalianitem::create([
                    "IDM"=>$idsuratpengantar,
                    "Ordinal"=>$key+1,
                    "Item"=>$item['namabarang'],
                    "Qty"=>$item['qty'],
                    "Satuan"=>$item['satuan']
                ]);
            }
            $data_return = $this->SetReturn(true,"Update Surat Pengantar Success",["ID"=>$idsuratpengantar,"SW"=>$sw], null);
            return response()->json($data_return,200);
        }
        // Generate IDRecipient
        $IDRecipient = recipient::orderBy('ID','desc')->first();
        $IDRecipient = ($IDRecipient->ID)+1;

        // Create new Recipient
        $dataRecipient = recipient::create([
            "SW"=>$recipient,
            "Address"=>$alamat,
            "UserAccess"=>$this->UserType(),
            "UserName"=>Auth::User()->name,
            "Active"=>1,
            "Disposable"=>"N"
        ]);

        // Update Surat Pengantar
        $suratpengantar = pengantarpengembalian::where("ID",$idsuratpengantar)->update([
            "TransDate"=>$tanggal,
            "ToUser"=>$IDRecipient,
            "Address"=>$alamat,
            "TotalQty"=>$TotalQTY,
            "ToEmployee"=>$toemployee,
        ]);
        
        // Delete suratpengantaritem
        $deletesuratpengantaritem = pengantarpengembalianitem::where("IDM",$idsuratpengantar)->delete();
            
        // // Create Surat Jalan Item
        foreach ($items as $key => $item) {
            $suratpengantaritem = pengantarpengembalianitem::create([
                "IDM"=>$idsuratpengantar,
                "Ordinal"=>$key+1,
                "Item"=>$item['namabarang'],
                "Qty"=>$item['qty'],
                "Satuan"=>$item['satuan']
            ]);
        }
        $data_return = $this->SetReturn(true,"Update Surat Jalan Success",["ID"=>$idsuratpengantar,"SW"=>$sw], null);
        return response()->json($data_return,200);
    }

    public function CariSuratPengantar($SW){
        $SW = $SW;

        $suratpengantar = FacadesDB::connection("messaging")
        ->select("
            SELECT 
                A.ID,
                A.SW,
                A.TransDate,
                A.ToUser,
                A.ToEmployee,
                A.Address as address1,
                B.Item,
                B.Qty,
                B.Satuan,
                C.SW as recipient,
                C.Address as address2
            FROM 
                pengantarpengembalian A
                JOIN pengantarpengembalianitem B on A.ID = B.IDM
                LEFT JOIN reciepent C on A.ToUser = C.ID
            WHERE
                A.SW = ".$SW."
        ");

        if (count($suratpengantar) == 0) {
            abort(404);
        }
        $sw = $suratpengantar[0]->SW;
        $tanggal = $suratpengantar[0]->TransDate;
        $toemployee = $suratpengantar[0]->ToEmployee;
        if ($suratpengantar[0]->recipient == null){
            $recipient = $suratpengantar[0]->ToEmployee;
        }else {
            $recipient = $suratpengantar[0]->recipient;
        }
        if ($suratpengantar[0]->address2 == null){
            $alamat = $suratpengantar[0]->address1;
        }else {
            $alamat = $suratpengantar[0]->address2;
        }
        $data = [
            "items"=>$suratpengantar,
            "sw"=>$sw,
            "tanggal"=>$tanggal,
            "toemployee"=>$toemployee,
            "recipient"=>$recipient,
            "alamat"=>$alamat
        ];
        $data_return = $this->SetReturn(true, "Search Surat Pengantar Success", $data, null);
        return $data_return;
    }

    public function Cetak($SW){
        // Query Surat jalan
        $suratpengantar = FacadesDB::connection("messaging")
        ->select("
            SELECT
                A.ID,
                A.TransDate,
                A.SW,
                A.ToUser,
                A.ToEmployee,
                A.Address as address1,
                B.SW as recipient,
                B.Address as address2
            FROM 
                pengantarpengembalian A
                LEFT JOIN reciepent B on A.ToUser=B.ID
            WHERE
                A.SW =".$SW."
        ");
        if (count($suratpengantar) == 0){
            abort(404);
        }
        $id = $suratpengantar[0]->ID;
        $sw = $suratpengantar[0]->SW;
        $tanggal = $suratpengantar[0]->TransDate;
        $toemployee = $suratpengantar[0]->ToEmployee;
        if ($suratpengantar[0]->recipient == null){
            $recipient = $suratpengantar[0]->ToEmployee;
        }else {
            $recipient = $suratpengantar[0]->recipient;
        }
        if ($suratpengantar[0]->address2 == null){
            $alamat = $suratpengantar[0]->address1;
        }else {
            $alamat = $suratpengantar[0]->address2;
        }
        $suratpengantaritem = pengantarpengembalianitem::where("IDM",$id)->get();
        return view("Lain-lain.Korespondensi.SuratPengantar.cetak",compact('id','sw','tanggal','toemployee','recipient','alamat','suratpengantaritem'));
    }

    public function Informasi(Request $request){
        return view('Lain-lain.Korespondensi.SuratPengantar.informasi');
    }

    public function SuratPengantarInformationResource(Request $request){
        $userType = $this->UserType();
        // check if userType is 0
        if ($userType == 0){
            $suratPengantar = FacadesDB::connection("messaging")->select("
                SELECT
                    A.SW,
                    A.TransDate,
                    A.ToEmployee,
                    A.ID,
                    B.Ordinal,
                    B.Item,
                    B.Qty,
                    B.Satuan,
                    C.SW AS recipient,
                    C.Address AS Addresss,
                    CONCAT(B.Qty, ' ', B.Satuan) jumlah,
                    MONTH(A.TransDate) Bulan,
                    YEAR(A.TransDate) Tahun,
                    CONCAT('/Lain-lain/Korespondensi/SuratPengantar/cetak/',A.SW) linkPopupLT
                FROM
                    pengantarpengembalian A
                    JOIN pengantarpengembalianitem B ON A.ID = B.IDM
                    LEFT JOIN reciepent C ON A.ToUser = C.ID
                ORDER BY
                    A.EntryDate DESC
            ");
        } else {
            $suratPengantar = FacadesDB::connection("messaging")->select("
                SELECT
                    A.SW,
                    A.TransDate,
                    A.ToEmployee,
                    A.ID,
                    B.Ordinal,
                    B.Item,
                    B.Qty,
                    B.Satuan,
                    C.SW AS recipient,
                    C.Address AS Addresss,
                    CONCAT(B.Qty, ' ', B.Satuan) jumlah,
                    MONTH(A.TransDate) Bulan,
                    YEAR(A.TransDate) Tahun,
                    CONCAT('/Lain-lain/Korespondensi/SuratPengantar/cetak/',A.SW) linkPopupLT
                FROM
                    pengantarpengembalian A
                    JOIN pengantarpengembalianitem B ON A.ID = B.IDM
                    LEFT JOIN reciepent C ON A.ToUser = C.ID
                WHERE
                    C.UserAccess = '$userType'
                ORDER BY
                    A.EntryDate DESC
            ");
        }

        // set data return
        $data_return = $this->SetReturn(true,"Search Surat Pengantar Success",$suratPengantar,null);
        return response()->json($data_return,200);
    }
}

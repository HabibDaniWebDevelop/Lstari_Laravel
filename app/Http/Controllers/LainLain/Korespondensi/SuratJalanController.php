<?php

namespace App\Http\Controllers\LainLain\Korespondensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
// DEV
// use App\Models\tes_laravel\recipient;
// use App\Models\tes_laravel\employee;
// use App\Models\tes_laravel\suratjalan;
// use App\Models\tes_laravel\suratjalanitem;

use App\Models\erp\employee;
use App\Models\messaging\recipient;
use App\Models\messaging\suratjalan;
use App\Models\messaging\suratjalanitem;

// Import public function controller for logger
use App\Http\Controllers\Public_Function_Controller;

class SuratJalanController extends Controller
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
        $IDModule = "291";
        return $IDModule;
    }

    private function CreateHistory($idsuratjalan)
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
        $UpdateUserHistory  = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $idsuratjalan);
    }

    //!  ------------------------     End Reuseable Function     ------------------------ !!


    public function Index()
    {
        $datenow = date('Y-m-d');
        $history = suratjalan::where('UserName',Auth::user()->name)->limit(10)->orderBy('ID','desc')->get();
        return view("Lain-lain.Korespondensi.SuratJalan.index",compact('datenow','history'));
    }

    public function Cetak($SW)
    {
        // Check if SW null or blank
        if (is_null($SW) or $SW == "") {
            abort(404);
        }

        // Query Surat jalan
        $suratjalan = FacadesDB::connection("messaging")
        ->select("
            SELECT
                A.ID,
                DATE_FORMAT(A.TransDate, '%d/%m/%Y') TransDate,
                A.SW,
                B.SW as recipient,
                B.Address
            FROM 
                suratjalan A
                JOIN reciepent B ON A.ToUser = B.ID
            WHERE
                A.SW =".$SW."
        ");
        if (count($suratjalan) == 0){
            abort(404);
        }
        $suratjalan = $suratjalan[0];
        $suratjalanitem = suratjalanitem::where("IDM",$suratjalan->ID)->get();

        return view("Lain-lain.Korespondensi.SuratJalan.cetak",compact('suratjalan','suratjalanitem'));
    }

    public function FindRecipient(Request $request)
    {
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

    public function CreateSuratJalan(Request $request)
    {
        // Getting Required Data
        $jenissurat = $request->jenissurat;
        $tanggal = $request->tanggal;
        $recipient = $request->recipient;
        $alamat = $request->alamat;
        $items = $request->items;

        // Check if jenis surat null or blank
        if (is_null($jenissurat) or $jenissurat == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Jalan Failed",
                "data"=>null,
                "error"=>[
                    "jenissurat"=>"jenissurat Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check jenis surat is in [1,2]
        if ($jenissurat == 1) {
            $jenissurat="Barang";
        } elseif ($jenissurat == 2){
            $jenissurat="Surat";
        } else {
            $data_return = $this->SetReturn(false,"Create Surat Jalan Failed", null, ["jenissurat"=>"jenissurat must be 1 for Barang or 2 for Surat"]);
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Surat Jalan Failed",
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
                "message"=>"Create Surat Jalan Failed",
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
                "message"=>"Create Surat Jalan Failed",
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
                "message"=>"Create Surat Jalan Failed",
                "data"=>null,
                "error"=>[
                    "items"=>"items Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Create Surat Jalan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("qty",$item) and array_key_exists("satuan",$item) and array_key_exists("jenisbarang",$item) and array_key_exists("keterangan",$item)){
                // Check qty in item
                if (is_null($item['qty']) or strval($item['qty']) == ""){
                    $data_return = $this->SetReturn(false,"Create Surat Jalan Failed",null, ["qty"=>"There still any blank or null value on qty"]);
                    return response()->json($data_return,400);
                }
                // Check satuan in item
                if (is_null($item['satuan']) or $item['satuan'] == ""){
                    $data_return = $this->SetReturn(false,"Create Surat Jalan Failed",null, ["satuan"=>"There still any blank or null value on satuan"]);
                    return response()->json($data_return,400);
                }
                // Check jenisbarang in item
                if (is_null($item['jenisbarang']) or $item['jenisbarang'] == ""){
                    $data_return = $this->SetReturn(false,"Create Surat Jalan Failed",null, ["jenisbarang"=>"There still any blank or null value on jenisbarang"]);
                    return response()->json($data_return,400);
                }
                // Check keterangan in item
                if (is_null($item['keterangan']) or $item['keterangan'] == ""){
                    $data_return = $this->SetReturn(false,"Create Surat Jalan Failed",null, ["keterangan"=>"There still any blank or null value on keterangan"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS
                // Add qty to total qty
                $TotalQTY += $item['qty'];

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Create Surat Jalan Failed",null, ["items"=>"Items required qty, satuan, jenisbarang, and keterangan"]);
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
                suratjalan 
            WHERE
                YEAR ( TransDate ) = YEAR (
                CurDate()) 
                AND SWPurpose = ".$userType."
        ");
        
        // Generate ID for suratjalan
        $idsuratjalan = suratjalan::orderBy('ID','desc')->first();
        $idsuratjalan = ($idsuratjalan->ID)+1;

        // Check recipient if having same SW and Address
        $dataRecipient = recipient::where("SW",$recipient)
        ->where("Address",$alamat)
        ->where("UserAccess", $userType)
        ->get();
        

        if (count($dataRecipient) != 0){
            // lanjut transaksi
            $IDRecipient = $dataRecipient[0]->ID;

            // Create Surat Jalan
            $suratjalan = suratjalan::create([
                "ID"=>$idsuratjalan,
                "EntryDate"=> date('Y-m-d H:i:s'),
                "UserName"=>Auth::user()->name,
                "SW"=>$Gen_SW_SWO[0]->SW,
                "TransDate"=>$tanggal,
                "ToUser"=>$IDRecipient,
                "Address"=>$dataRecipient[0]->Address,
                "TotalQty"=>$TotalQTY,
                "SWPurpose"=>$this->UserType(),
                "SWOrdinal"=>$Gen_SW_SWO[0]->id,
                "Type"=>$jenissurat
            ]);
            
            // // Create Surat Jalan Item
            foreach ($items as $key => $item) {
                $suratjalanitem = suratjalanitem::create([
                    "IDM"=>$idsuratjalan,
                    "Ordinal"=>$key+1,
                    "Item"=>$item['jenisbarang'],
                    "Qty"=>$item['qty'],
                    "Note"=>$item['keterangan'],
                    "Satuan"=>$item['satuan']
                ]);
            }

            // Insert Log
            $this->CreateHistory($idsuratjalan);

            $data_return = $this->SetReturn(true,"Create Surat Jalan Success",["ID"=>$idsuratjalan,"SW"=>$Gen_SW_SWO[0]->SW], null);
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

        // Create Surat Jalan
        $suratjalan = suratjalan::create([
            "ID"=>$idsuratjalan,
            "EntryDate"=> date('Y-m-d H:i:s'),
            "UserName"=>Auth::user()->name,
            "SW"=>$Gen_SW_SWO[0]->SW,
            "TransDate"=>$tanggal,
            "ToUser"=>$IDRecipient,
            "Address"=>$alamat,
            "TotalQty"=>$TotalQTY,
            "SWPurpose"=>$this->UserType(),
            "SWOrdinal"=>$Gen_SW_SWO[0]->id,
            "Type"=>$jenissurat
        ]);
        
        // // Create Surat Jalan Item
        foreach ($items as $key => $item) {
            $suratjalanitem = suratjalanitem::create([
                "IDM"=>$idsuratjalan,
                "Ordinal"=>$key+1,
                "Item"=>$item['jenisbarang'],
                "Qty"=>$item['qty'],
                "Note"=>$item['keterangan'],
                "Satuan"=>$item['satuan']
            ]);
        }

        // Insert Log
        $this->CreateHistory($idsuratjalan);

        $data_return = $this->SetReturn(true,"Create Surat Jalan Success",["ID"=>$idsuratjalan,"SW"=>$Gen_SW_SWO[0]->SW], null);
        return response()->json($data_return,200);
    }

    public function CariSuratJalan($SW)
    {
        $SW = $SW;

        $suratjalan = FacadesDB::connection("messaging")
        ->select("
            SELECT 
                A.ID,
                A.SW,
                A.TransDate,
                A.Type,
                B.Qty,
                B.Satuan,
                B.Item as jenisBarang,
                B.Note as Keterangan,
                C.SW as recipient,
                C.Address
            FROM 
                suratjalan A
                JOIN suratjalanitem B on A.ID = B.IDM
                JOIN reciepent C on A.ToUser = C.ID
            WHERE
                A.SW = ".$SW."
        ");

        if (count($suratjalan) == 0) {
            abort(404);
        }
        $sw = $suratjalan[0]->SW;
        $tanggal = $suratjalan[0]->TransDate;
        if($suratjalan[0]->Type == "Surat"){
            $jenissurat = 2;
        } else {
            $jenissurat = 1;
        }
        $recipient = $suratjalan[0]->recipient;
        $alamat = $suratjalan[0]->Address;
        $data = [
            "items"=>$suratjalan,
            "sw"=>$sw,
            "tanggal"=>$tanggal,
            "jenissurat"=>$jenissurat,
            "recipient"=>$recipient,
            "alamat"=>$alamat
        ];
        $data_return = $this->SetReturn(true, "Search Surat Jalan Success", $data, null);
        return $data_return;
    }

    public function UpdateSuratJalan(Request $request)
    {
        // Getting Required Data
        $sw = $request->sw;
        $jenissurat = $request->jenissurat;
        $tanggal = $request->tanggal;
        $recipient = $request->recipient;
        $alamat = $request->alamat;
        $items = $request->items;

        // Check if sw null or blank
        // Check if jenis surat null or blank
        if (is_null($sw) or $sw == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Update Surat Jalan Failed",
                "data"=>null,
                "error"=>[
                    "sw"=>"sw Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if jenis surat null or blank
        if (is_null($jenissurat) or $jenissurat == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Update Surat Jalan Failed",
                "data"=>null,
                "error"=>[
                    "jenissurat"=>"jenissurat Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check jenis surat is in [1,2]
        if ($jenissurat == 1) {
            $jenissurat="Barang";
        } elseif ($jenissurat == 2){
            $jenissurat="Surat";
        } else {
            $data_return = $this->SetReturn(false,"Update Surat Jalan Failed", null, ["jenissurat"=>"jenissurat must be 1 for Barang or 2 for Surat"]);
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Update Surat Jalan Failed",
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
                "message"=>"Update Surat Jalan Failed",
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
                "message"=>"Update Surat Jalan Failed",
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
                "message"=>"Update Surat Jalan Failed",
                "data"=>null,
                "error"=>[
                    "items"=>"items Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Update Surat Jalan Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("qty",$item) and array_key_exists("satuan",$item) and array_key_exists("jenisbarang",$item) and array_key_exists("keterangan",$item)){
                // Check qty in item
                if (is_null($item['qty']) or strval($item['qty']) == ""){
                    $data_return = $this->SetReturn(false,"Update Surat Jalan Failed",null, ["qty"=>"There still any blank or null value on qty"]);
                    return response()->json($data_return,400);
                }
                // Check satuan in item
                if (is_null($item['satuan']) or $item['satuan'] == ""){
                    $data_return = $this->SetReturn(false,"Update Surat Jalan Failed",null, ["satuan"=>"There still any blank or null value on satuan"]);
                    return response()->json($data_return,400);
                }
                // Check jenisbarang in item
                if (is_null($item['jenisbarang']) or $item['jenisbarang'] == ""){
                    $data_return = $this->SetReturn(false,"Update Surat Jalan Failed",null, ["jenisbarang"=>"There still any blank or null value on jenisbarang"]);
                    return response()->json($data_return,400);
                }
                // Check keterangan in item
                if (is_null($item['keterangan']) or $item['keterangan'] == ""){
                    $data_return = $this->SetReturn(false,"Update Surat Jalan Failed",null, ["keterangan"=>"There still any blank or null value on keterangan"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS
                // Add qty to total qty
                $TotalQTY += $item['qty'];

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Update Surat Jalan Failed",null, ["items"=>"Items required qty, satuan, jenisbarang, and keterangan"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        $userType = $this->UserType();

        
        // Check suratjalan if exists
        $suratjalan = suratjalan::where("SW",$sw)->get();
        if(count($suratjalan) == 0){
            $data_return = $this->SetReturn(false,"Update Surat Jalan Failed", null, ["sw"=>"Surat jalan with that sw not found"]);
            return response()->json($data_return,404);
        }
        
        // Set ID Surat Jalan
        $idsuratjalan = $suratjalan[0]->ID;

        // Check recipient if having same SW and Address
        $dataRecipient = recipient::where("SW",$recipient)
        ->where("Address",$alamat)
        ->where("UserAccess", $userType)
        ->get();
        
        if (count($dataRecipient) != 0){
            // lanjut transaksi
            $IDRecipient = $dataRecipient[0]->ID;

            // Update Surat Jalan
            $suratjalan = suratjalan::where("ID",$idsuratjalan)->update([
                "TransDate"=>$tanggal,
                "ToUser"=>$IDRecipient,
                "Address"=>$dataRecipient[0]->Address,
                "TotalQty"=>$TotalQTY,
                "Type"=>$jenissurat
            ]);

            // Delete suratjalanitem
            $deletesuratjalanitem = suratjalanitem::where("IDM",$idsuratjalan)->delete();
            
            // // Create Surat Jalan Item
            foreach ($items as $key => $item) {
                $suratjalanitem = suratjalanitem::create([
                    "IDM"=>$idsuratjalan,
                    "Ordinal"=>$key+1,
                    "Item"=>$item['jenisbarang'],
                    "Qty"=>$item['qty'],
                    "Note"=>$item['keterangan'],
                    "Satuan"=>$item['satuan']
                ]);
            }
            $data_return = $this->SetReturn(true,"Update Surat Jalan Success",["ID"=>$idsuratjalan,"SW"=>$sw], null);
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

        // Update Surat Jalan
        $suratjalan = suratjalan::where("ID",$idsuratjalan)->update([
            "TransDate"=>$tanggal,
            "ToUser"=>$IDRecipient,
            "Address"=>$alamat,
            "TotalQty"=>$TotalQTY,
            "Type"=>$jenissurat
        ]);
        
        // Delete suratjalanitem
        $deletesuratjalanitem = suratjalanitem::where("IDM",$idsuratjalan)->delete();
            
        // // Create Surat Jalan Item
        foreach ($items as $key => $item) {
            $suratjalanitem = suratjalanitem::create([
                "IDM"=>$idsuratjalan,
                "Ordinal"=>$key+1,
                "Item"=>$item['jenisbarang'],
                "Qty"=>$item['qty'],
                "Note"=>$item['keterangan'],
                "Satuan"=>$item['satuan']
            ]);
        }
        $data_return = $this->SetReturn(true,"Update Surat Jalan Success",["ID"=>$idsuratjalan,"SW"=>$sw], null);
        return response()->json($data_return,200);
    }

    public function SuratJalanInformation(){
        return view("Lain-lain.Korespondensi.SuratJalan.informasi");
    }
    public function SuratJalanInformationResource(){
        $userType = $this->UserType();
        if ($userType == 0) {
            $suratjalan = FacadesDB::connection('messaging')
            ->select("
                SELECT
                    A.SW,
                    A.TransDate TransDate,
                    A.Type Typee,
                    A.ID ID,
                    A.SWPurpose,
                    B.Ordinal Ordinal,
                    B.Qty Qty,
                    B.Satuan Satuan,
                    B.Note Note,
                    B.Item Item,
                    C.SW AS recipient,
                    C.Address AS Addresss,
                    CONCAT(B.Qty, ' ', B.Satuan) jumlah,
                    MONTH(A.TransDate) Bulan,
                    YEAR(A.TransDate) Tahun,
                    CONCAT('/Lain-lain/Korespondensi/SuratJalan/cetak/',A.SW) linkPopupLT
                FROM
                    suratjalan A
                    LEFT JOIN suratjalanitem B ON B.IDM = A.ID
                    LEFT JOIN reciepent C ON A.ToUser = C.ID 
                ORDER BY
                    A.EntryDate DESC
            ");
            $data_return = $this->SetReturn(true,"Get Informasi Data Surat Jalan Sukses", $suratjalan, null);
            return response()->json($data_return,200);
        } else {
            $suratjalan = FacadesDB::connection('messaging')->select("
                SELECT
                    A.SW,
                    A.TransDate TransDate,
                    A.Type Typee,
                    A.ID ID,
                    A.SWPurpose,
                    B.Ordinal Ordinal,
                    B.Qty Qty,
                    B.Satuan Satuan,
                    B.Note Note,
                    B.Item Item,
                    C.SW AS recipient,
                    C.Address AS Addresss,
                    CONCAT(B.Qty, ' ', B.Satuan) jumlah,
                    MONTH(A.TransDate) Bulan,
                    YEAR(A.TransDate) Tahun,
                    CONCAT('/Lain-lain/Korespondensi/SuratJalan/cetak/',A.SW) linkPopupLT
                FROM
                    suratjalan A
                    LEFT JOIN suratjalanitem B ON B.IDM = A.ID
                    LEFT JOIN reciepent C ON A.ToUser = C.ID 
                WHERE
                    A.SWPurpose = '$userType'
                ORDER BY
                    A.EntryDate DESC
            ");
            $data_return = $this->SetReturn(true,"Get Informasi Data Surat Jalan Sukses", $suratjalan, null);
            return response()->json($data_return,200);
        }
    }

    public function ListRecipientInformation(){
        $UserAccess = $this->UserType();
        $dataRecipient = recipient::select('SW as value','Address','ID')
        ->where("UserAccess", "=" ,$UserAccess)
        ->orderBy('ID','desc')
        ->get();
        return view("Lain-lain.Korespondensi.SuratJalan.recipient",compact('dataRecipient'));
    }

    public function UpdateRecipient(Request $request){
        $UserAccess = $this->UserType();
        $IDRecipient = $request->idRecipient;
        $recipient = $request->recipient;
        $address = $request->address;

        if (is_null($IDRecipient) or $IDRecipient == "") {
            $data_return = $this->SetReturn(false,"Update Recipient Failed. idRecipient can't be null or blank",null, ['idRecipient'=>"Can't be null or Blank"]);
            return response()->json($data_return,400);
        }

        if (is_null($recipient) or $recipient == "") {
            $data_return = $this->SetReturn(false,"Update Recipient Failed. recipient can't be null or blank",null, ['recipient'=>"Can't be null or Blank"]);
            return response()->json($data_return,400);
        }

        if (is_null($address) or $address == "") {
            $data_return = $this->SetReturn(false,"Update Recipient Failed. address can't be null or blank",null, ['address'=>"Can't be null or Blank"]);
            return response()->json($data_return,400);
        }

        // Check if recipient exists
        $CheckRecipient = recipient::where('ID',$IDRecipient)->first();
        if (is_null($CheckRecipient)) {
            $data_return = $this->SetReturn(false,"Recipient not found",null, null);
            return response()->json($data_return,404);
        }

        if ($CheckRecipient->UserAccess != $UserAccess) {
            $data_return = $this->SetReturn(false,"Alamat tersebut bukan hak akses anda",null, null);
            return response()->json($data_return,400);
        }

        // Update Recipient
        $updateRecipient = recipient::where('ID',$IDRecipient)->update([
            "SW"=>$recipient,
            "Address"=>$address
        ]);
        $data_return = $this->SetReturn(true,"Alamat tersebut berhasil di update",null, null);
        return response()->json($data_return,200);
    }

    public function RemoveRecipient(Request $request){
        $UserAccess = $this->UserType();
        $IDRecipient = $request->idRecipient;
        if (is_null($IDRecipient) or $IDRecipient == "") {
            $data_return = $this->SetReturn(false,"Delete Recipient Failed. idRecipient can't be null or blank",null, ['idRecipient'=>"Can't be null or Blank"]);
            return response()->json($data_return,400);
        }

        // Check if recipient exists
        $recipient = recipient::where('ID',$IDRecipient)->first();
        if (is_null($recipient)) {
            $data_return = $this->SetReturn(false,"Recipient not found",null, null);
            return response()->json($data_return,404);
        }

        if ($recipient->UserAccess != $UserAccess) {
            $data_return = $this->SetReturn(false,"Alamat tersebut bukan hak akses anda",null, null);
            return response()->json($data_return,400);
        }

        // delete recipient
        $deleteRecipient = recipient::where('ID',$IDRecipient)->delete();
        $data_return = $this->SetReturn(true,"Alamat Berhasil Dihapus",null, null);
        return response()->json($data_return,200);
    }
}

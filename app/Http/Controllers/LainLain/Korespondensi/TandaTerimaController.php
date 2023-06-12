<?php

namespace App\Http\Controllers\LainLain\Korespondensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Models\erp\employee;
use App\Models\messaging\tandaterima;
use App\Models\messaging\tandaterimaitem;

// Import public function controller for logger
use App\Http\Controllers\Public_Function_Controller;

class TandaTerimaController extends Controller
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
        $IDModule = "292";
        return $IDModule;
    }

    //!  ------------------------     End Reuseable Function     ------------------------ !!

    // Function for Index
    public function Index(){
        $datenow = date('Y-m-d');
        $history = tandaterima::where('UserName',Auth::user()->name)->limit(10)->orderBy('ID','desc')->get();
        return view("Lain-lain.Korespondensi.TandaTerima.index",compact('datenow','history'));
    }

    // Function for saving tanda terima
    public function CreateTandaTerima(Request $request){
        // Getting Required Data
        $fromuser = $request->fromuser;
        $tanggal = $request->tanggal;
        $items = $request->items;

        // Check if fromuser null or blank
        if (is_null($fromuser) or $fromuser == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "fromuser"=>"fromuser Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Create Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "tanggal"=>"tanggal Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = [
                "success"=>false,
                "message"=>"Create Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "items"=>"items Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Create Tanda Terima Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("qty",$item) and array_key_exists("satuan",$item) and array_key_exists("namabarang",$item) and array_key_exists("keterangan",$item)){
                // Check qty in item
                if (is_numeric($item['qty']) or is_null($item['qty'])){
                    // Add qty to total qty
                    if (is_null($item['qty'])){
                        $TotalQTY += 0;
                    }else {
                        $TotalQTY += $item['qty'];   
                    }
                }else {
                    $data_return = $this->SetReturn(false,"Create Tanda Terima Failed",null, ["qty"=>"qty must be integer"]);
                    return response()->json($data_return,400);
                }

                // Check satuan in item
                if (is_null($item['satuan']) or $item['satuan'] == ""){
                    $data_return = $this->SetReturn(false,"Create Tanda Terima Failed",null, ["satuan"=>"There still any blank or null value on satuan"]);
                    return response()->json($data_return,400);
                }

                // Check namabarang in item
                if (is_null($item['namabarang']) or $item['namabarang'] == ""){
                    $data_return = $this->SetReturn(false,"Create Tanda Terima Failed",null, ["namabarang"=>"There still any blank or null value on namabarang"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Create Tanda Terima Failed",null, ["items"=>"Items required qty, satuan, namabarang, and keterangan"]);
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
                tandaterima 
            WHERE
                YEAR ( TransDate ) = YEAR (
                CurDate()) 
        ");

        // Generate ID for tamdaterima
        $idtandaterima = tandaterima::orderBy('ID','desc')->first();
        $idtandaterima = ($idtandaterima->ID)+1;

        // Create tanda terima
        $tandaterima = tandaterima::create([
            "ID"=>$idtandaterima,
            "EntryDate"=> date('Y-m-d H:i:s'),
            "UserName"=>Auth::user()->name,
            "SW"=>$Gen_SW_SWO[0]->SW,
            "TransDate"=>$tanggal,
            "FromUser"=>$fromuser,
            "TotalQty"=>$TotalQTY
        ]);

        // // Create Tanda Terima Item
        foreach ($items as $key => $item) {
            $tandaterimaitem = tandaterimaitem::create([
                "IDM"=>$idtandaterima,
                "Ordinal"=>$key+1,
                "Item"=>$item['namabarang'],
                "Qty"=>$item['qty'],
                "Satuan"=>$item['satuan'],
                "Note"=> is_null($item['keterangan']) ? " " : $item['keterangan']
            ]);
        }

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
        $UpdateUserHistory  = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $idtandaterima);

        $data_return = $this->SetReturn(true,"Create Tanda Terima Success",["ID"=>$idtandaterima,"SW"=>$Gen_SW_SWO[0]->SW], null);
        return response()->json($data_return,200);
    }

    // Function for update tanda terima
    public function UpdateTandaTerima(Request $request){
        // Getting Required Data
        $sw = $request->sw;
        $fromuser = $request->fromuser;
        $tanggal = $request->tanggal;
        $items = $request->items;

        // Check if sw is null
        if (is_null($sw) or $sw == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Update Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "sw"=>"sw Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if fromuser null or blank
        if (is_null($fromuser) or $fromuser == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Update Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "fromuser"=>"fromuser Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if tanggal null or blank
        if (is_null($tanggal) or $tanggal == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Update Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "tanggal"=>"tanggal Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check items
        if (is_null($items) or !is_array($items)) {
            $data_return = [
                "success"=>false,
                "message"=>"Update Tanda Terima Failed",
                "data"=>null,
                "error"=>[
                    "items"=>"items Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check Items length
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false,"Update Tanda Terima Failed", null, ["items"=>"items Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        $TotalQTY = 0;
        // Check items item key
        foreach ($items as $key => $item) {
            // Check Item key is exists
            if(array_key_exists("qty",$item) and array_key_exists("satuan",$item) and array_key_exists("namabarang",$item) and array_key_exists("keterangan",$item)){
                // Check qty in item
                if (is_numeric($item['qty']) or is_null($item['qty'])){
                    // Add qty to total qty
                    if (is_null($item['qty'])){
                        $TotalQTY += 0;
                    }else {
                        $TotalQTY += $item['qty'];   
                    }
                }else {
                    $data_return = $this->SetReturn(false,"Update Tanda Terima Failed",null, ["qty"=>"qty must be integer"]);
                    return response()->json($data_return,400);
                }

                // Check satuan in item
                if (is_null($item['satuan']) or $item['satuan'] == ""){
                    $data_return = $this->SetReturn(false,"Update Tanda Terima Failed",null, ["satuan"=>"There still any blank or null value on satuan"]);
                    return response()->json($data_return,400);
                }

                // Check namabarang in item
                if (is_null($item['namabarang']) or $item['namabarang'] == ""){
                    $data_return = $this->SetReturn(false,"Update Tanda Terima Failed",null, ["namabarang"=>"There still any blank or null value on namabarang"]);
                    return response()->json($data_return,400);
                }
                
                // CHECKING ITEM SUCCESS

            } else{
                // Return if one of the the key on item not included
                $data_return = $this->SetReturn(false,"Update Tanda Terima Failed",null, ["items"=>"Items required qty, satuan, namabarang, and keterangan"]);
                return response()->json($data_return,400);
            }
        }
        // CHECKING ITEMS SUCCESS

        $userType = $this->UserType();

        // Check if tanda terima exists
        $tandaterima = tandaterima::where('SW',$sw)->first();
        if (is_null($tandaterima)){
            $data_return = $this->SetReturn(false,"Update Tanda Terima Failed",null, ["sw"=>"Tanda Terima with that sw Not Found"]);
            return response()->json($data_return,404);
        }

        $idtandaterima = $tandaterima->ID;
        
        // Update Tanda Terima
        $tandaterima = tandaterima::where("ID",$idtandaterima)->update([
            "TransDate"=>$tanggal,
            "FromUser"=>$fromuser,
        ]);

        // Delete Tanda Terima item
        $deleteTandaTerimaItem = tandaterimaitem::where("IDM",$idtandaterima)->delete();

        // // Create Tanda Terima Item
        foreach ($items as $key => $item) {
            $tandaterimaitem = tandaterimaitem::create([
                "IDM"=>$idtandaterima,
                "Ordinal"=>$key+1,
                "Item"=>$item['namabarang'],
                "Qty"=>$item['qty'],
                "Satuan"=>$item['satuan'],
                "Note"=> is_null($item['keterangan']) ? " " : $item['keterangan']
            ]);
        }
        $data_return = $this->SetReturn(true,"Update Tanda Terima Success",["ID"=>$idtandaterima,"SW"=>$sw], null);
        return response()->json($data_return,200);
    }

    // Function for Find tanda terima
    public function CariTandaTerima($SW){
        $tandaterima = FacadesDB::connection("messaging")
        ->select("
            SELECT
                A.ID,
                A.SW,
                A.TransDate as tanggal,
                A.FromUser as fromuser,
                B.Qty,
                B.Satuan,
                B.Item,
                B.Note
            FROM 
                tandaterima A
                JOIN tandaterimaitem B ON A.ID = B.IDM
            WHERE
                A.SW = ".$SW."
        ");
        if (count($tandaterima) == 0) {
            $data_return = $this->SetReturn(false,"Search Tanda Terima Failed", null, ["SW"=>"SW not found"]);
            return response()->json($data_return,404);
        }

        $sw = $tandaterima[0]->SW;
        $tanggal = $tandaterima[0]->tanggal;
        $fromuser = $tandaterima[0]->fromuser;
        $data = [
            "items"=>$tandaterima,
            "sw"=>$sw,
            "tanggal"=>$tanggal,
            "fromuser"=>$fromuser,
        ];
        $data_return = $this->SetReturn(true, "Search Tanda Terima Success", $data, null);
        return $data_return;
    }

    // Function for Cetak Tanda Terima
    public function Cetak($SW){
        // Query tanda terima
        $tandaterima = tandaterima::where('SW',$SW)->first();
        if (is_null($tandaterima)){
            abort(404);
        }
        $tandaterimaitem = tandaterimaitem::where('IDM',$tandaterima->ID)->get();
        return view("Lain-lain.Korespondensi.TandaTerima.cetak",compact('tandaterima','tandaterimaitem'));
    }

    public function Informasi(Request $request){
        return view('Lain-lain.Korespondensi.TandaTerima.informasi');
    }

    public function TandaTerimaInformationResource(Request $request){
        $userType = $this->UserType();
        // check if userType is 0
        if ($userType == 0){
            $tandaTerima = FacadesDB::connection("messaging")->select("
                SELECT
                    A.SW,
                    A.TransDate,
                    A.ID,
                    B.Ordinal,
                    B.Item,
                    B.Qty,
                    B.Satuan,
                    A.FromUser AS recipient,
                    '' AS address,
                    CONCAT(B.Qty, ' ', B.Satuan) jumlah,
                    B.Note AS catatan,
                    LEFT(A.SW,1) AS SWPurpose,
                    MONTH(A.TransDate) Bulan,
                    YEAR(A.TransDate) Tahun,
                    CONCAT('/Lain-lain/Korespondensi/TandaTerima/cetak/',A.SW) linkPopupLT
                FROM
                    tandaterima A
                    JOIN tandaterimaitem B ON A.ID = B.IDM
                ORDER BY
                    A.ID DESC
            ");
        } else {
            $tandaTerima = FacadesDB::connection("messaging")->select("
                SELECT
                    A.SW,
                    A.TransDate,
                    A.ID,
                    B.Ordinal,
                    B.Item,
                    B.Qty,
                    B.Satuan,
                    A.FromUser AS recipient,
                    '' AS address,
                    CONCAT(B.Qty, ' ', B.Satuan) jumlah,
                    B.Note AS catatan,
                    LEFT(A.SW,1) AS SWPurpose,
                    MONTH(A.TransDate) Bulan,
                    YEAR(A.TransDate) Tahun,
                    CONCAT('/Lain-lain/Korespondensi/TandaTerima/cetak/',A.SW) linkPopupLT
                FROM
                    tandaterima A
                    JOIN tandaterimaitem B ON A.ID = B.IDM
                WHERE
                    LEFT(A.SW,1) = '$userType'
                ORDER BY
                    A.ID DESC
            ");
        }

        // set data return
        $data_return = $this->SetReturn(true,"Search Surat Pengantar Success",$tandaTerima,null);
        return response()->json($data_return,200);
    }
}

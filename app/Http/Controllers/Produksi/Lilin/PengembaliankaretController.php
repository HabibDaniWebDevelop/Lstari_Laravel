<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\rubberret;
// use App\Models\tes_laravel\rubberreturn;
// use App\Models\tes_laravel\rubber;
// use App\Models\tes_laravel\rubberlocation;
// use App\Models\tes_laravel\rubberoutitem;

// live
use App\Models\rndnew\rubberrr;
use App\Models\rndnew\rubberreturn;
use App\Models\erp\rubber;
use App\Models\erp\rubberlocation;
use App\Models\erp\rubberoutitem;

// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class PengembalianKaretController extends Controller{
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
        $historyRubberout = FacadesDB::connection('erp')->select("SELECT LinkID FROM rubberout ORDER BY ID DESC LIMIT 15");
        return view('Produksi.Lilin.PengembalianKaret.index', compact('employee', 'datenow', 'historyRubberout'));
    }

    public function Simpan(Request $request){
        // Get Required Data
        // dd($request->items);
        $chekitem = $request->items;
    
        // Checking Data
        // Check if idWaxInjectOrder null or blank

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();
        $datenow = date('Y-m-d');
        
        //generate id di database rndnew
        $generateID = FacadesDB::select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rubberrr");
        // dd($generateID);

        // $insert_rubberret = rubberrr::create([
        //     'ID' => $generateID[0]->ID,
        //     'UserName' => $username,
        // ]);
        // dd($insert_rubberret);
        
        $i = 0;
        foreach ($request->items as $IT => $isi) {
            $i++;
            $update_rubberlocation = rubberlocation::where('RubberID', $isi['IDKaret'])->update([
                'Active' => 'K',
                'ReturnDate' => $datenow,
            ]);
            
            $update_rubber = rubber::where('ID', $isi['IDKaret'])
            ->update(['Active' => 'K']);
            
            // $insert_rubberreturn = rubberreturn::create([
            //     'IDM' =>$insert_rubberret->ID,
            //     'RubberID' => $isi['IDKaret'],
            //     'Condition' => $isi['Condition'],
            //     'LinkID' => $isi['IDMRubberOut'],
            //     'LinkOrd' => $isi['OrdinalRubberOut'],
            // ]);
            
            $update_rubberoutitem = rubberoutitem::where('IDM', $isi['IDMRubberOut'])
            ->where('Ordinal',$isi['OrdinalRubberOut'])->where('RubberID',$isi['IDKaret'])
            ->update([
                'ReturnDate' => date('Y-m-d H:i:s'),
                'ReturnGood' => $isi['Condition'],
            ]);
        }
        $data_Return = array(
            'Idrub' => $isi['IDKaret']);
        return response()->json($data_Return, 200);

        // $data_return = $this->SetReturn(true, "Simpan Data Pengembalian Karet Sukses", ['RubberID'=>$isi['IDKaret']], null);
        // return response()->json($data_return, 200);
    }

    public function Cetak(){

        return view('Produksi.Lilin.PengembalianKaret.cetak');
    }

    public function IDKaret(Request $request){
        $IDKaret = $request->value;

        $RubberIDdata = FacadesDB::connection('erp')
        ->select("SELECT
            P.SW ItemProduct,
            P.Description,
            P.ID IDProd,
            CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            PC.Description Kadar,
            PC.ID AS IDKadar,
            R.ID IDKaret,
            RO.IDM,
            RO.Ordinal,
        CASE WHEN RL.ID IS NULL THEN 'Karet Belum Di Registrasi' ELSE CONCAT(ML.SW, ' ', MC.SW, ' ', MK.SW, ' ', MB.SW) END Lokasi
        FROM
            rubber R
            JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN rubberlocation RL ON R.ID = RL.RubberID
            JOIN product P ON P.ID = R.Product
            LEFT JOIN masterlemari ML ON RL.LemariID = ML.ID
            LEFT JOIN masterlaci MC ON RL.LaciID = MC.ID
            LEFT JOIN masterkolom MK ON RL.KolomID = MK.ID
            LEFT JOIN masterbaris MB ON RL.BarisID = MB.ID
            JOIN rubberoutitem RO ON RO.RubberID = R.ID
        WHERE
            R.ID = $IDKaret
            AND RO.IDM IS NOT NULL
        ORDER BY
            RO.IDM DESC
            LIMIT 1");

        $rowcount = count($RubberIDdata);
        if($rowcount > 0){
            foreach ($RubberIDdata as $datas){}
            $ItemProduct = $datas->ItemProduct;
            $Description = $datas->Description;
            $IDProd = $datas->IDProd;
            $HexColor = $datas->HexColor;
            $Kadar = $datas->Kadar;
            $IDKadar = $datas->IDKadar;
            $IDKaret = $datas->IDKaret;
            $Lokasi = $datas->Lokasi;
            $IDMRubberOut = $datas->IDM;
            $OrdinalRubberOut = $datas->Ordinal;

            // dd($ItemProduct, $Description, $IDProd, $HexColor, $Kadar, $IDKadar, $IDKaret, $Lokasi);
            $data_Return = array(
                'rowcount' => $rowcount,
                'ItemProduct' => $ItemProduct, 
                'Description' => $Description,
                'IDProd' => $IDProd,
                'HexColor' => $HexColor, 
                'Kadar' => $Kadar, 
                'IDKadar' => $IDKadar, 
                'IDKaret' => $IDKaret, 
                'Lokasi' => $Lokasi,
                'IDMRubberOut'=> $IDMRubberOut,
                'OrdinalRubberOut' => $OrdinalRubberOut);
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }

    public function Search(Request $request){
      
    }

    public function UpdateSPKOKaret(Request $request){
        $idWaxTree = $request->idWaxTree;
        $idEmployee = $request->idEmployee;

        // Check if Waxtree Found
        $waxstoneusage = waxtree::where('ID',$idWaxTree)->first();
        if (is_null($waxstoneusage)) {
            $data_return = $this->SetReturn(false, "Getting WaxTree Failed. WaxTree not found", null, null);
            return response()->json($data_return, 404);
        }
        if ($waxstoneusage->Purpose != 'I') {
            $data_return = $this->SetReturn(false, "NTHKO Tersebut Bukan Inject. Tidak dapat diubah", null, null);
            return response()->json($data_return, 400);
        }

        // update waxtree
        $updateWaxTree = waxtree::where('ID',$idWaxTree)->update([
            "Employee"=>$idEmployee
        ]);
        
        $data_return = $this->SetReturn(true, "Update Waxtree Success", null, null);
        return response()->json($data_return, 200);
    }
}
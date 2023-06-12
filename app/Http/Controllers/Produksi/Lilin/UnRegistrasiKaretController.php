<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
use App\Models\erp\rubber;
use App\Models\erp\rubberlocation;


// live
// use App\Models\erp\rubber;
// use App\Models\erp\rubberlocation;

// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class UnRegistrasiKaretController extends Controller{
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
    
        // history waxstoneusage
        $historyRubberout = FacadesDB::connection('erp')->select("SELECT LinkID FROM rubberout ORDER BY ID DESC LIMIT 15");
        return view('Produksi.Lilin.UnRegistrasiKaret.index', compact('historyRubberout'));
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
                '#090cd9' 
                WHEN PC.SW = '8K' THEN
                '#02ba1e' 
                WHEN PC.SW = '16K' THEN
                '#ff1a1a' 
                WHEN PC.SW = '17K' THEN
                '#e65507' 
                WHEN PC.SW = '17K.' THEN
                '#d909cb' 
                WHEN PC.SW = '20K' THEN
                '#ffcba4' 
                WHEN PC.SW = '10K' THEN
                '#f5fc0f' 
                WHEN PC.SW = '8K.' THEN
                '#ebb52d' 
                WHEN PC.SW = '19K' THEN
                '#4908a3' 
            END HexColor,
            PC.Description Kadar,
            PC.ID AS IDKadar,
            R.ID IDKaret,
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
        WHERE
            R.ID = $IDKaret
            AND RL.ID IS NOT NULL
           ");

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
                'Lokasi' => $Lokasi);
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }


    public function Simpan(Request $request){
        // Get Required Data
        $checkitem =  $request->items;
        // dd($checkitem);
        
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();
        
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            
            $update_rubber = rubber::where('ID', $isi['IDKaret'])
            ->update([
                'Active' => 'U',
                'Remarks' => $isi['Keterangan'],
            ]);
            
            $update_rubberlocation = rubberlocation::where('RubberID', $isi['IDKaret'])->update([
                'Active' => NULL,
                'WaxInjectOrder' => NULL,
                'UseDate' => NULL,
                'ReturnDate' => NULL,
                'RubberID' => NULL,
            ]);
        }
       
// $update_rubber = "UPDATE rubber SET Active = '$Keperluan' WHERE ID = ";
            // $UpdateWaxtreeASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeA);

        $data_return = $this->SetReturn(true, "UnReg Karet Sukses", NULL, null);
        return response()->json($data_return, 200);
    }

}
<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Models\erp\workorder;
// PROD
use App\Models\rndnew\wipworkshop;
use App\Models\rndnew\wipworkshopfg;
// DEV
// use App\Models\tes_laravel\wipworkshop;
// use App\Models\tes_laravel\wipworkshopfg;

class VerifikasiWorkshopController extends Controller{
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

    private function GetSPKPCB($noSPK){
        // $spkPCB = 230280040;
        $spkPCB = $noSPK;
        
        // Get Product in SPK
        $getSPK = FacadesDB::connection('erp')->select("
            SELECT
                A.ID,
                A.SW,
                B.IDM,
                B.Ordinal,
                C.ID AS idProduct,
                C.SW AS swProduct
            FROM
                workorder A
                JOIN workorderitem B ON A.ID = B.IDM
                JOIN product C ON B.Product = C.ID
            WHERE
                A.SWUsed = '$spkPCB'
        ");

        // Final data
        $finalData = [];

        // Loop Product yang ada di dalam SPK untuk dicari part nya.
        foreach ($getSPK as $key => $value) {
            
            // Cari Component yang ada di product
            $getProductComponent = FacadesDB::select("
                SELECT
                    B.SW,
                    B.ID,
                    ( SELECT ID FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 25 ORDER BY ID DESC LIMIT 1) AS IDDrafter2D,
                    ( SELECT ImageOriginal FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 25 ORDER BY ID DESC LIMIT 1) AS Image,
                    ( SELECT Corel FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 25 ORDER BY ID DESC LIMIT 1) AS Corel,
                    ( SELECT ID FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS IDDrafter3D,
                    ( SELECT Image FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS Image3D,
                    ( SELECT File3DM FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS File3DM
                FROM
                    productcomponent A
                    JOIN product B ON A.Component = B.LinkID AND B.TypeProcess = 25
                WHERE
                    A.IDM = '$value->idProduct'
                    AND A.Status = 'GT'
            ");

            // Loop Component product yang sudah dicari untuk ditambahkan ke final item
            foreach ($getProductComponent as $keyComponent => $valueComponent) {
                // jika finalitem nya masih kosong, maka Component tersebut akan langsung dimasukkan ke finaldata
                if (count($finalData) != 0) {
                    // Loop finaldata untuk mengecek apakah Component tersebut sudah ada. Jika sudah ada, maka finalProductnya yang ditambah dalam Component tersebut.
                    $found = false;
                    foreach ($finalData as $keyFinal => $valueFinal) {
                        if ($finalData[$keyFinal]['idProduct'] == $valueComponent->ID) {
                            $finalData[$keyFinal]['productFG'][] = ['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal];
                            $found = true;
                            break;
                        }
                    }

                    // Jika tidak ditemukan maka Component tersebut akan ditambahkan ke finaldata;
                    if (!$found) {
                        $component = [
                            "swProduct"=>$valueComponent->SW,
                            "idProduct"=>$valueComponent->ID,
                            "idDrafter2D"=>$valueComponent->IDDrafter2D,
                            "imageProduct"=>$valueComponent->Image,
                            "corelFile"=>$valueComponent->Corel,
                            "idDrafter3D"=>$valueComponent->IDDrafter3D,
                            "imageProduct3D"=>$valueComponent->Image3D,
                            "file3DM"=>$valueComponent->File3DM,
                            "idWorkOrder"=>$value->ID,
                            "jenisPart"=>"Komponen",
                            'productFG'=>[['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal]]
                        ];
                        $finalData[] = $component;
                    }
                } else {
                    $component = [
                        "swProduct"=>$valueComponent->SW,
                        "idProduct"=>$valueComponent->ID,
                        "idDrafter2D"=>$valueComponent->IDDrafter2D,
                        "imageProduct"=>$valueComponent->Image,
                        "corelFile"=>$valueComponent->Corel,
                        "idDrafter3D"=>$valueComponent->IDDrafter3D,
                        "imageProduct3D"=>$valueComponent->Image3D,
                        "file3DM"=>$valueComponent->File3DM,
                        "idWorkOrder"=>$value->ID,
                        "jenisPart"=>"Komponen",
                        'productFG'=>[['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal]]
                    ];
                    $finalData[] = $component;
                }
            }

            // Cari Mainan yang ada di product
            $getProductMainan = FacadesDB::select("
                SELECT
                    B.SW,
                    B.ID,
                    ( SELECT ID FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 26 ORDER BY ID DESC LIMIT 1) AS IDDrafter2D,
                    ( SELECT ImageOriginal FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 26 ORDER BY ID DESC LIMIT 1) AS Image,
                    ( SELECT Corel FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 26 ORDER BY ID DESC LIMIT 1) AS Corel,
                    ( SELECT ID FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS IDDrafter3D,
                    ( SELECT Image FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS Image3D,
                    ( SELECT File3DM FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS File3DM
                FROM
                    productmn A
                    JOIN product B ON A.Mainan = B.LinkID AND B.TypeProcess = 26
                WHERE
                    A.IDM = '$value->idProduct'
                    AND A.Status = 'GT'
            ");

            // Loop Mainan product yang sudah dicari untuk ditambahkan ke final item
            foreach ($getProductMainan as $keyMainan => $valueMainan) {
                // jika finalitem nya masih kosong, maka Mainan tersebut akan langsung dimasukkan ke finaldata
                if (count($finalData) != 0) {
                    // Loop finaldata untuk mengecek apakah Mainan tersebut sudah ada. Jika sudah ada, maka finalProductnya yang ditambah dalam Mainan tersebut.
                    $found = false;
                    foreach ($finalData as $keyFinal => $valueFinal) {
                        if ($finalData[$keyFinal]['idProduct'] == $valueMainan->ID) {
                            $finalData[$keyFinal]['productFG'][] = ['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal];
                            $found = true;
                            break;
                        }
                    }

                    // Jika tidak ditemukan maka Mainan tersebut akan ditambahkan ke finaldata;
                    if (!$found) {
                        $Mainan = [
                            "swProduct"=>$valueMainan->SW,
                            "idProduct"=>$valueMainan->ID,
                            "idDrafter2D"=>$valueMainan->IDDrafter2D,
                            "imageProduct"=>$valueMainan->Image,
                            "corelFile"=>$valueMainan->Corel,
                            "idDrafter3D"=>$valueMainan->IDDrafter3D,
                            "imageProduct3D"=>$valueMainan->Image3D,
                            "file3DM"=>$valueMainan->File3DM,
                            "idWorkOrder"=>$value->ID,
                            "jenisPart"=>"Mainan",
                            'productFG'=>[['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal]]
                        ];
                        $finalData[] = $Mainan;
                    }
                } else {
                    $Mainan = [
                        "swProduct"=>$valueMainan->SW,
                        "idProduct"=>$valueMainan->ID,
                        "idDrafter2D"=>$valueMainan->IDDrafter2D,
                        "imageProduct"=>$valueMainan->Image,
                        "corelFile"=>$valueMainan->Corel,
                        "idDrafter3D"=>$valueMainan->IDDrafter3D,
                        "imageProduct3D"=>$valueMainan->Image3D,
                        "file3DM"=>$valueMainan->File3DM,
                        "idWorkOrder"=>$value->ID,
                        "jenisPart"=>"Mainan",
                        'productFG'=>[['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal]]
                    ];
                    $finalData[] = $Mainan;
                }
            }

            // Cari Kepala yang ada di product
            $getProductKepala = FacadesDB::select("
                SELECT
                    B.SW,
                    B.ID,
                    ( SELECT ID FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 27 ORDER BY ID DESC LIMIT 1) AS IDDrafter2D,
                    ( SELECT ImageOriginal FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 27 ORDER BY ID DESC LIMIT 1) AS Image,
                    ( SELECT Corel FROM drafter2d DFT WHERE DFT.Product = B.ID AND DFT.TypeProcess = 27 ORDER BY ID DESC LIMIT 1) AS Corel,
                    ( SELECT ID FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS IDDrafter3D,
                    ( SELECT Image FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS Image3D,
                    ( SELECT File3DM FROM drafter3d WHERE Product = B.LinkID AND ResultStatusID = 1 ORDER BY ID DESC LIMIT 1 ) AS File3DM
                FROM
                    productkepala A
                    JOIN product B ON A.Kepala = B.LinkID AND B.TypeProcess = 27
                WHERE
                    A.IDM = '$value->idProduct'
                    AND A.Status = 'GT'
            ");

            // Loop Kepala product yang sudah dicari untuk ditambahkan ke final item
            foreach ($getProductKepala as $keyKepala => $valueKepala) {
                // jika finalitem nya masih kosong, maka Kepala tersebut akan langsung dimasukkan ke finaldata
                if (count($finalData) != 0) {
                    // Loop finaldata untuk mengecek apakah Kepala tersebut sudah ada. Jika sudah ada, maka finalProductnya yang ditambah dalam Kepala tersebut.
                    $found = false;
                    foreach ($finalData as $keyFinal => $valueFinal) {
                        if ($finalData[$keyFinal]['idProduct'] == $valueKepala->ID) {
                            $finalData[$keyFinal]['productFG'][] = ['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal];
                            $found = true;
                            break;
                        }
                    }

                    // Jika tidak ditemukan maka Kepala tersebut akan ditambahkan ke finaldata;
                    if (!$found) {
                        $Kepala = [
                            "swProduct"=>$valueKepala->SW,
                            "idProduct"=>$valueKepala->ID,
                            "idDrafter2D"=>$valueKepala->IDDrafter2D,
                            "imageProduct"=>$valueKepala->Image,
                            "corelFile"=>$valueKepala->Corel,
                            "idDrafter3D"=>$valueKepala->IDDrafter3D,
                            "imageProduct3D"=>$valueKepala->Image3D,
                            "file3DM"=>$valueKepala->File3DM,
                            "idWorkOrder"=>$value->ID,
                            "jenisPart"=>"Kepala",
                            'productFG'=>[['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal]]
                        ];
                        $finalData[] = $Kepala;
                    }
                } else {
                    $Kepala = [
                        "swProduct"=>$valueKepala->SW,
                        "idProduct"=>$valueKepala->ID,
                        "idDrafter2D"=>$valueKepala->IDDrafter2D,
                        "imageProduct"=>$valueKepala->Image,
                        "corelFile"=>$valueKepala->Corel,
                        "idDrafter3D"=>$valueKepala->IDDrafter3D,
                        "imageProduct3D"=>$valueKepala->Image3D,
                        "file3DM"=>$valueKepala->File3DM,
                        "idWorkOrder"=>$value->ID,
                        "jenisPart"=>"Kepala",
                        'productFG'=>[['idProduct'=>$value->idProduct,'namaProduct'=>$value->swProduct, 'IDM'=>$value->IDM, 'Ordinal'=>$value->Ordinal]]
                    ];
                    $finalData[] = $Kepala;
                }
            }
        }
        $return_html = view('Workshop.VerifikasiWorkshop.SpkItemsTemplate',compact('finalData'))->render();
        $data = [
            "returnHTML"=>$return_html,
            "return"=>$finalData
        ];
        $data_return = $this->SetReturn(false, "Get spk PCB Success", $data, null);
        return $data_return;
    }
    // End Private Function

    public function Index(Request $request){
        // Generate Session for file 
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');
        return view('Workshop.VerifikasiWorkshop.index');
    }

    public function FindSPKPCB(Request $request){
        ini_set('max_execution_time', 300);
        $spkPCB = $request->spkPCB;
        if ($spkPCB == "" or is_null($spkPCB)) {
            $data_return = $this->SetReturn(false, "Cari SPK PCB Gagal. spkPCB tidak boleh null atau blank", null, null);
            return response()->json($data_return, 400);
        }

        $itemSPK = $this->GetSPKPCB($spkPCB);
        return $itemSPK;
    }

    public function SavetoWIP(Request $request){
        $swSPK = $request->swSPK;
        $items = $request->items;

        // check if swspk and items is not null or blank
        if (is_null($swSPK) or $swSPK == "") {
            $data_return = $this->SetReturn(false, "Simpan WIP Gagal. swSPK tidak boleh null / blank", null, null);
            return response()->json($data_return, 400);
        }
        if (is_null($items) or !is_array($items) or count($items) == 0) {
            $data_return = $this->SetReturn(false, "Simpan WIP Gagal. items tidak boleh null / blank", null, null);
            return response()->json($data_return, 400);
        }

        // get workorder for checking
        $getWorkOrder = workorder::where('SWUsed', $swSPK)->first();

        // check if idworkorder is already exists on wipworkshop. if exists, it will be returned and not inserted to db
        $checkWIP = wipworkshop::where('IDWorkOrder',$getWorkOrder->ID)->first();
        if (!is_null($checkWIP)) {
            $data_return = $this->SetReturn(false, "Simpan WIP Gagal. SPK tersebut sudah ada di WIP", null, null);
            return response()->json($data_return, 400);
        }

        // Loop items for insert to wip
        foreach ($items as $key => $value) {
            // StatusWIP = {
            //     0:"Verification",
            //     1:"Active",
            //     2:"Exists"
            //     3:"Revisi",
            //     4:"Revisied"
            //     5:"SPKO",
            //     6:"NTHKO",
            //     7:"GambarTeknik"
            // }

            // Insert wipworkshop
            $insertWipWorkshop = wipworkshop::create([
                "UserName"=>Auth::user()->name,
                "TransDate"=>date('Y-m-d'),
                "IDWorkOrder"=>$value['idWorkOrder'],
                "IDProduct"=>$value['idProduct'],
                "ProgressStatus"=>0,
                "IDDrafter2D"=>$value['idDrafter2D'],
                "IDDrafter3D"=>$value['idDrafter3D'],
                "Active"=>"A"
            ]);

            foreach ($value['productFG'] as $key2 => $value2) {
                // Insert wipworkshopfg
                $insertWipWorkshopFG = wipworkshopfg::create([
                    "IDWIPWorkshop"=>$insertWipWorkshop->id,
                    "IDWorkOrder"=>$value2['IDM'],
                    "OrdinalWorkOrder"=>$value2['Ordinal'],
                    "IDProductFG"=>$value2['idProduct']
                ]);
            }
        }
        $data_return = $this->SetReturn(false, "Simpan WIP berhasil.", null, null);
        return response()->json($data_return, 200);
    }
}

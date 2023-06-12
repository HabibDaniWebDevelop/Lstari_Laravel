<?php

namespace App\Http\Controllers\Master\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Models\erp\productcategory;

class CatalogController extends Controller{

    //!  ------------------------     Reuseable Function     ------------------------ !!
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }
    
    private function DetailItem($SWProduct){
        // Get product by SW
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT DISTINCT
                Photo,
                ID
            FROM
                product 
            WHERE
                SW = '$SWProduct'
        ");
        // Check if product exists
        if (count($data) == 0) {
            abort(404);
        }
        // Treat Foto Filename
        $filename = $data[0]->Photo;
        $foto = basename($filename, ".jpg");

        $productID = $data[0]->ID;
        // Get Product Part
        $data = FacadesDB::connection("erp")->
        select("
            SELECT
                p.SW,
                pp.Qty
            FROM
                productpart pp
                JOIN product p ON pp.Product = p.ID
            WHERE
                IDM = '$productID'
        ");
        $part = $data;

        // Get Product Accesories
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT
                p.SW,
                pp.Qty
            FROM
                productaccessories pp
                JOIN product p ON pp.Product = p.ID
            WHERE
                IDM = '$productID'
        ");
        $accesories = $data;

        $data = [
            "foto"=>$foto,
            "part"=>$part,
            "accesories"=>$accesories
        ];
        return $data;
    }
    //!  ------------------------     End Reuseable Function     ------------------------ !!

    public function Index(Request $request){

        // Generate Session for file 
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');

        // Get product category for searching by model
        $productCategory = FacadesDB::connection("erp")
        ->select("
            SELECT
                mm.*,
                P.Description
            FROM
                productcategory mm
                JOIN product P ON mm.ProductID = P.ID
            WHERE mm.Active = 1
        ");

        $productCategoryTukangLuar = FacadesDB::select("
            SELECT
                mm.*,
                P.Description 
            FROM
                productcategory mm
                JOIN product P ON mm.ProductID = P.ID 
            WHERE
                mm.Active = 1
                AND mm.Outsourcing = 1
        ");

        return view('Master.Item.Catalog.index', compact('productCategory', 'productCategoryTukangLuar'));
    }

    // Function for getting detail of product
    public function DetailItemSPK($SWProduct){
        // Get Detail Item
        $detailItem = $this->DetailItem($SWProduct);
        $foto = $detailItem['foto'];
        $part = $detailItem['part'];
        $accesories = $detailItem['accesories'];
        return view('Master.Item.Catalog.detailItemSPK',compact('foto','part','accesories'));
    }

    // Function for getting detail of product
    public function DetailItemModel($SWProduct){
        // Get Detail Item
        $detailItem = $this->DetailItem($SWProduct);
        $foto = $detailItem['foto'];
        $part = $detailItem['part'];
        $accesories = $detailItem['accesories'];
        return view('Master.Item.Catalog.detailItemModel',compact('foto','part','accesories'));
    }

    // Function for getting detail of product Tukang Luar
    public function DetailItemTukangLuar($SWProduct){
        // Get Detail Item
        $detailItem = $this->DetailItem($SWProduct);
        $fotoRaw = substr_replace($detailItem['foto'] ,"0",-1);
        $foto = $detailItem['foto'];
        $part = $detailItem['part'];
        $accesories = $detailItem['accesories'];
        return view('Master.Item.Catalog.detailItemTukangLuar',compact('foto','fotoRaw','part','accesories'));
    }

    // Function for checking spk
    public function CekSPK(Request $request){
        // Get SW from parameters
        $SWSPK = $request->sw;
        
        // Query Database
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT 
                DISTINCT( P.ID ),
                P.SW,
                P.Description,
                M.Description Model,
                L.SW Logo,
                M.SW CODE,
                P.SerialNo,
                P.Photo,
                I.Qty,
                C.SW Carat,
                Z.Weight,
                P.BrtKunci,
                I.Remarks 
            FROM
                WorkOrder O
                JOIN WorkOrderItem I ON I.IDM = O.ID
                JOIN Product P ON P.ID = I.Product
                JOIN Product M ON P.Model = M.ID
                LEFT JOIN ProductSize Z ON P.ID = Z.IDM 
                AND Z.Ordinal = 1
                LEFT JOIN ProductCarat C ON Z.Carat = C.ID
                LEFT JOIN ShortText L ON P.Logo = L.ID 
            WHERE
                O.SWUsed = '$SWSPK'
            ORDER BY
                M.Description,
                P.SerialNo
        ");
        
        // Check if spk found
        if (count($data) == 0) {
            // Return if spk not found
            $data_return = $this->SetReturn(false, "SPK Not Found", null, null);
            return response()->json($data_return,404);
        }
        
        // Return if spk found
        $data_return = $this->SetReturn(false, "SPK Found", $data, null);
        return response()->json($data_return,200);
    }

    // Function for getting product from spk
    public function GetSPK($SWSPK){        
        // Query Database
        $data = FacadesDB::connection('erp')
        ->select("
            SELECT 
                DISTINCT( P.ID ),
                P.SW,
                P.Description,
                M.Description Model,
                L.SW Logo,
                M.SW CODE,
                P.SerialNo,
                REPLACE(P.Photo, '.jpg', '') as Photo,
                I.Qty,
                C.SW Carat,
                Z.Weight,
                P.BrtKunci,
                I.Remarks,
                O.Remarks SPKNOTE,
                O.SW AS SPKPPIC
            FROM
                WorkOrder O
                JOIN WorkOrderItem I ON I.IDM = O.ID
                JOIN Product P ON P.ID = I.Product
                JOIN Product M ON P.Model = M.ID
                LEFT JOIN ProductSize Z ON P.ID = Z.IDM 
                AND Z.Ordinal = 1
                LEFT JOIN ProductCarat C ON Z.Carat = C.ID
                LEFT JOIN ShortText L ON P.Logo = L.ID 
            WHERE
                O.SWUsed = '$SWSPK'
            ORDER BY
                M.Description,
                P.SerialNo
        ");
        foreach ($data as $key => $value) {
            $idProduct = $value->ID;
            $getBatu = FacadesDB::connection('erp')->select("
                SELECT
                    P.SW SW,
                    A.Qty QTY,
                    A.Gigi Gigi,
                    A.Tanam Tanam 
                FROM
                    ProductAccessories A
                    JOIN Product P ON A.Product = P.ID 
                    AND P.ProdGroup = 126 
                WHERE
                    A.IDM = '$idProduct'
            ");
            $data[$key]->batu = $getBatu;
            $getPart = FacadesDB::connection('erp')->select("
                SELECT
                    p.SW,
                    pp.Qty
                FROM
                    productpart pp
                    JOIN product p ON pp.Product = p.ID
                WHERE
                    IDM = '$idProduct'
            ");
            $data[$key]->part = $getPart;
        }

        // Get Detail SPK
        $detailInfoSPK = FacadesDB::connection('erp')->select("
            SELECT
                A.SW AS NomorSPK,
                B.Description AS Kadar,
                A.Remarks AS KeteranganSPK
            FROM
                workorder A 
                JOIN productcarat B ON A.Carat = B.ID
            WHERE
                A.SWUsed = '$SWSPK'
        ");
        if (count($detailInfoSPK)) {
            // SPK Note
            $spkNote = $detailInfoSPK[0]->KeteranganSPK;
            $kadar = $detailInfoSPK[0]->Kadar;
            $spkPPIC = $detailInfoSPK[0]->NomorSPK;
        } else {
            $spkNote = "";
            $kadar = "";
            $spkPPIC = "";
        }

        // Get infoproduct
        $infoProduct = FacadesDB::connection('erp')
        ->select("
            SELECT 
                DISTINCT ( P.ID ),
                P.SW,
                P.Description,
                G.Description ProdGroup,
                M.Description Model,
                L.SW Logo,
                M.SW CODE,
                P.SerialNo,
                REPLACE(P.Photo, '.jpg', '') as Photo,
                I.Qty,
                C.SW Carat,
                Z.Weight,
                P.BrtKunci,
                W.SW PSB,
                I.Remarks,
                S.Enamel,
                S.Slep,
                S.Marking,
                CASE WHEN dz.ID IS NULL THEN 'Tanpa' ELSE dz.SW  END AS UkRing 
            FROM
                WorkOrder O
                JOIN WorkOrderItem I ON I.IDM = O.ID
                JOIN Product P ON P.ID = I.Product
                JOIN ShortText G ON P.ProdGroup = G.ID
                JOIN Product M ON P.Model = M.ID
                LEFT JOIN ProductSize Z ON P.ID = Z.IDM AND Z.Ordinal = 1
                LEFT JOIN ProductCarat C ON Z.Carat = C.ID
                LEFT JOIN WagePSB W ON P.PSBCost = W.ID
                LEFT JOIN ShortText L ON P.Logo = L.ID
                LEFT JOIN WorkSuggestionItem S ON I.WorkSuggestion = S.IDM AND I.WorkSuggestionOrd = S.Ordinal
                LEFT JOIN designsize dz ON P.VarSize = dz.ID 
            WHERE
                O.SWUsed = '$SWSPK'
            ORDER BY
                G.Description,
                M.Description,
                P.SerialNo
        ");
        
        // Get Stone
        $stone = FacadesDB::connection('erp')
        ->select("
            SELECT
                CC.SW,
                D.SW Stone,
                ( B.Qty * C.Qty ) Total 
            FROM
                workorder A
                JOIN workorderitem B ON B.IDM = A.ID
                JOIN product CC ON CC.ID = B.Product
                JOIN productaccessories C ON C.IDM = B.Product
                JOIN product D ON D.ID = C.Product AND D.Description LIKE 'Batu%' 
            WHERE
                A.SWUsed = '$SWSPK'
        ");

        return view('Master.Item.Catalog.spkview2',compact('data', 'stone', 'infoProduct', 'SWSPK', 'spkNote', 'kadar', 'spkPPIC'));
    }

    // Function for checking product by model
    public function CekNoModel(Request $request){
        // Get required parameters
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;

        // Get Product
        $product = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                B.SerialNo,
                B.Photo
            FROM 
                productcategory A
                JOIN product B ON A.ProductID = B.Model
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.EnamelGroup IS NOT NULL 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
                AND B.VarCarat = 3
        ");
        // Check if null
        if (count($product) == 0) {
            $data_return = $this->SetReturn(false, "Model Not Found", null, null);
            return response()->json($data_return,404);
        }
        // Return if spk found
        $data_return = $this->SetReturn(false, "Model Found", $product, null);
        return response()->json($data_return,200);
    }

    // Function for showing result of product by model
    public function GetNoModel($idCategory, $fromNumber, $toNumber){

        // Get Product
        $product = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                B.SerialNo,
                B.Photo
            FROM 
                productcategory A
                JOIN product B ON A.ProductID = B.Model
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.EnamelGroup IS NOT NULL 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
                AND B.VarCarat = 3
        ");
        return view('Master.Item.Catalog.nomodelview',compact('product'));
    }

    // Function for checking product by model Tukang Luar
    public function CekTukangLuar(Request $request){
        // Get required parameters
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;

        // Get Product
        $product = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                B.SerialNo,
                B.Photo
            FROM 
                productcategory A
                JOIN product B ON A.ProductID = B.Model
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y'  
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
        ");
        // Check if null
        if (count($product) == 0) {
            $data_return = $this->SetReturn(false, "Model Tukang Luar Not Found", null, null);
            return response()->json($data_return,404);
        }
        // Return if spk found
        $data_return = $this->SetReturn(false, "Model Tukang Luar Found", $product, null);
        return response()->json($data_return,200);
    }

    // Function for checking product Tukang Luar by SPK PPIC
    public function CekTukangLuarBySPK(Request $request){
        $noSPK = $request->noSPK;

        // Get Product 
        $product = FacadesDB::connection('erp')->select("
            SELECT
                C.ID,
                C.SW,
                C.Model,
                C.SerialNo,
                C.Photo
            FROM
                workorder A
                JOIN workorderitem B ON A.ID = B.IDM
                JOIN product C ON B.Product = C.ID
            WHERE
                A.SWUsed = '$noSPK'
        ");
        // Check if null
        if (count($product) == 0) {
            $data_return = $this->SetReturn(false, "Model Tukang Luar dengan SPK '$noSPK' Not Found", null, null);
            return response()->json($data_return,404);
        }
        // Return if spk found
        $data_return = $this->SetReturn(false, "Model Tukang Luar Found", $product, null);
        return response()->json($data_return,200);
    }

    // Function for showing result of product by tukang luar
    public function GetTukangLuar($idCategory, $fromNumber, $toNumber){
        // Get Product
        $product = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                B.SerialNo,
                B.Photo
            FROM 
                productcategory A
                JOIN product B ON A.ProductID = B.Model
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
        ");
        return view('Master.Item.Catalog.tukangluarview',compact('product'));
    }

    // Function for showing result of product tukang luar by SPK PPIC
    public function GetTukangLuarBySPK($noSPK){
        // Get Product
        $product = FacadesDB::connection('erp')->select("
            SELECT
                C.ID,
                C.SW,
                C.Model,
                C.SerialNo,
                C.Photo
            FROM
                workorder A
                JOIN workorderitem B ON A.ID = B.IDM
                JOIN product C ON B.Product = C.ID
            WHERE
                A.SWUsed = '$noSPK'
        ");
        return view('Master.Item.Catalog.tukangluarview',compact('product'));
    }

    // Function for checking Lilin
    public function CekLilin(Request $request){
        // Get idKaret from parameters
        $idKaret = $request->idKaret;
        
        // Query Database
        $data = FacadesDB::select("
            SELECT
                D.ID IDKARET,
                A.SW,
                A.Description,
                F.Description AS Kadar,
                G.SW SIZE,
                H.Description Logo,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto1 ) f1,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto2 ) f2,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto3 ) f3,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto4 ) f4 
            FROM
                product A
                JOIN rubber D ON D.Product = A.ID
                JOIN rubberorderitem E ON E.Rubber = D.ID
                LEFT JOIN erp.productcarat F ON F.ID = A.VarCarat
                LEFT JOIN designsize G ON G.ID = A.Size
                LEFT JOIN shorttext H ON H.ID = A.Logo 
            WHERE
                D.ID = '$idKaret'
            --  E.InjectPhoto1 IS NOT NULL 
            GROUP BY
                A.ID
        ");
        
        // Check if karet found
        if (count($data) == 0) {
            // Return if karet not found
            $data_return = $this->SetReturn(false, "Karet Not Found", null, null);
            return response()->json($data_return,404);
        }
        
        // Return if karet found
        $data_return = $this->SetReturn(false, "Karet Found", $data, null);
        return response()->json($data_return,200);
    }

    // Function for getting product from Lilin
    public function GetLilin($idKaret){        
        // Query Database
        $data = FacadesDB::select("
            SELECT
                D.ID IDKARET,
                A.SW,
                A.Description,
                F.Description AS Kadar,
                G.SW SIZE,
                H.Description Logo,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto1 ) f1,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto2 ) f2,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto3 ) f3,
                CONCAT( '/rnd2/FotohasilInjectLilin/', '', E.InjectPhoto4 ) f4 
            FROM
                product A
                JOIN rubber D ON D.Product = A.ID
                JOIN rubberorderitem E ON E.Rubber = D.ID
                LEFT JOIN erp.productcarat F ON F.ID = A.VarCarat
                LEFT JOIN designsize G ON G.ID = A.Size
                LEFT JOIN shorttext H ON H.ID = A.Logo 
            WHERE
                D.ID = '$idKaret'
            --  E.InjectPhoto1 IS NOT NULL 
            GROUP BY
                A.ID
        ");
        $data = $data[0];

        return view('Master.Item.Catalog.lilinview',compact('data', 'idKaret'));
    }

    public function CetakNoModel(Request $request){
        // Get required parameters
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;

        // Get Product
        $product = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                B.SerialNo,
                B.Photo
            FROM 
                productcategory A
                JOIN product B ON A.ProductID = B.Model
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.EnamelGroup IS NOT NULL 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
                AND B.VarCarat = 3
        ");
        $data_return = $this->SetReturn(false, "Model Found", $product, null);
        // dd($data_return);
        return view('Master.Item.Catalog.cetakModel',compact('data_return'));
    }

    public function PilihCetakModel(Request $request){
        // Get required parameters
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;

        // Get Product
        $product = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                B.SerialNo,
                B.Photo
            FROM 
                productcategory A
                JOIN product B ON A.ProductID = B.Model
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.EnamelGroup IS NOT NULL 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
                AND B.VarCarat = 3
        ");
        $data_return = $this->SetReturn(false, "Model Found", $product, null);
        // dd($data_return);
        return view('Master.Item.Catalog.cetakModelSelected',compact('data_return'));
    }

    public function CetakSelectedModel(Request $request){
        // Get required parameters
        $idProducts =  $request->input('product');

        $listProduct = [];
        foreach ($idProducts as $key => $item) {
            // Get Product
            $product = FacadesDB::select("
                SELECT
                    B.ID,
                    B.SW,
                    B.Model,
                    B.SerialNo,
                    B.Photo
                FROM 
                    productcategory A
                    JOIN product B ON A.ProductID = B.Model
                WHERE
                    B.ID = '$item'
            ");
            array_push($listProduct,$product[0]);
        }
        
        $data_return = $this->SetReturn(false, "Model Found", $listProduct, null);
        // dd($data_return);
        return view('Master.Item.Catalog.cetakModel',compact('data_return'));
    }

    public function TrySomething(){
        return view('Master.Item.Catalog.cetakModelSelected');
    }

    public function GetKatalogMarketing(Request $request){
        // Get required parameters
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;

        // Get Product
        $products = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                A.SW AS NamaModel,
                B.SerialNo,
                B.Photo,
                REPLACE(C.ImageOriginal, '.jpg', '') as Photo2D,
                C.DesignStart
            FROM 
                productcategory A
                LEFT JOIN product B ON A.ProductID = B.Model
                LEFT JOIN drafter2d C ON C.Product = B.EnamelGroup
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
            GROUP BY
                B.SerialNo, B.VarEnamel, B.VarStone
        ");

        // create empty product array
        $empty_product = [
            'ID'=>null,
            'SW'=>null,
            'Model'=>null,
            'NamaModel'=>null,
            'SerialNo'=>null,
            'Photo'=>'nophoto',
            'Photo2D'=>"nophoto",
            'DesignStart'=>null
        ];

        $list_of_serial_numbers = [];

        // loop product and check if that product serial number is in list of serial number
        foreach ($products as $key => $value) {
            $serial_number = ['serial_number' => $value->SerialNo, 'model' => $value->NamaModel, 'items' => []];
            if (!in_array($serial_number, $list_of_serial_numbers)) {
                $list_of_serial_numbers[] = $serial_number;
            }
        }

        // loop list of serial number for adding product by that serial number
        foreach ($list_of_serial_numbers as $key_serial_number => $serial_number) {
            // loop product for inserting item by serial_number
            foreach ($products as $key_product => $product) {
                // check if serial number of product is equal with serial_number in list of serial_number. if match add to items of that serial_number
                if ($serial_number['serial_number'] == $product->SerialNo) {
                    // Convert product to array
                    $product = json_decode(json_encode($product), true);
                    // check length of list_of_serial_numbers[key_serial_number]['items']. if length is below 4 it will append to items of serial_number
                    if (count($list_of_serial_numbers[$key_serial_number]['items']) < 4) {
                        // add product to items of serial_number
                        $list_of_serial_numbers[$key_serial_number]['items'][] = $product;
                    }
                }
            }
        }

        // loop list of serial_number for check length items. if length is below 4 it will add empty product to items of serial_number
        foreach ($list_of_serial_numbers as $key_serial_number => $serial_number) {
            if (count($list_of_serial_numbers[$key_serial_number]['items']) < 4) { 
                // loop as much as 4 - length of list_of_serial_numbers[key_serial_number]['items']
                $length_of_minus_item = 4-count($list_of_serial_numbers[$key_serial_number]['items']);
                for ($i = 0; $i < $length_of_minus_item; $i++) {
                    // add empty product to items of serial_number
                    $list_of_serial_numbers[$key_serial_number]['items'][] = $empty_product;
                }
            }
        }

        // loop list of serial_number for adding ukuran by that serial_number
        foreach ($list_of_serial_numbers as $key_serial_number => $serial_number) {
            // get ukuran by serial_number
            $ukuran = FacadesDB::select("
                SELECT
                    C.Description
                FROM
                    productcategory A
                    LEFT JOIN product B ON A.ProductID = B.Model
                    LEFT JOIN designsize C ON B.VarSize = C.ID 
                WHERE
                    A.ID = '$idCategory' 
                    AND B.Active = 'Y' 
                    AND B.EnamelGroup IS NOT NULL 
                    AND B.SerialNo = '$serial_number[serial_number]'
                GROUP BY
                    B.VarSize
            ");

            $list_ukuran = [];
            foreach ($ukuran as $key => $value) {
                $list_ukuran[] = $value->Description;
            }

            // add list_ukuran to list of serial_number
            $list_of_serial_numbers[$key_serial_number]['ukuran'] = $list_ukuran;

            // set gambar2d of list_of_serial_numbers by serial_number items index 0 Photo2D
            $list_of_serial_numbers[$key_serial_number]['gambar2d'] = $list_of_serial_numbers[$key_serial_number]['items'][0]['Photo2D'];

            // set tanggal_stp of list_of_serial_numbers by serial_number items index 0 DesignStart
            $list_of_serial_numbers[$key_serial_number]['tanggal_stp'] = !is_null($list_of_serial_numbers[$key_serial_number]['items'][0]['DesignStart']) ? date('F Y',strtotime($list_of_serial_numbers[$key_serial_number]['items'][0]['DesignStart'])) : "";
        }

        // rename list of product by serial_number (list_of_serial_numbers) to items
        $items = $list_of_serial_numbers;
        // dd($list_of_serial_numbers);

        $html = view('Master.Item.Catalog.marketingItemLayout',compact('items'))->render();

        // build json data for returning json of items and html
        $data = [
            'items'=>$items,
            'html'=>$html
        ];

        $data_return = $this->SetReturn(false, "Model Found", $data, null);
        // return data_return with 200 status code
        return response()->json($data_return, 200);
    }

    public function CetakNoModelMarketing(Request $request){
        // Get required parameters
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;

        // Get Product
        $products = FacadesDB::select("
            SELECT
                B.ID,
                B.SW,
                B.Model,
                A.SW AS NamaModel,
                B.SerialNo,
                B.Photo,
                REPLACE(C.ImageOriginal, '.jpg', '') as Photo2D,
                C.DesignStart
            FROM 
                productcategory A
                LEFT JOIN product B ON A.ProductID = B.Model
                LEFT JOIN drafter2d C ON C.Product = B.EnamelGroup
            WHERE
                A.ID = '$idCategory'
                AND B.Active = 'Y' 
                AND B.SerialNo BETWEEN '$fromNumber' AND '$toNumber'
            GROUP BY
                B.SerialNo, B.VarEnamel, B.VarStone
        ");

        // create empty product array
        $empty_product = [
            'ID'=>null,
            'SW'=>null,
            'Model'=>null,
            'NamaModel'=>null,
            'SerialNo'=>null,
            'Photo'=>'nophoto',
            'Photo2D'=>"nophoto",
            'DesignStart'=>null
        ];

        $list_of_serial_numbers = [];

        // loop product and check if that product serial number is in list of serial number
        foreach ($products as $key => $value) {
            $serial_number = ['serial_number' => $value->SerialNo, 'model' => $value->NamaModel, 'items' => []];
            if (!in_array($serial_number, $list_of_serial_numbers)) {
                $list_of_serial_numbers[] = $serial_number;
            }
        }

        // loop list of serial number for adding product by that serial number
        foreach ($list_of_serial_numbers as $key_serial_number => $serial_number) {
            // loop product for inserting item by serial_number
            foreach ($products as $key_product => $product) {
                // check if serial number of product is equal with serial_number in list of serial_number. if match add to items of that serial_number
                if ($serial_number['serial_number'] == $product->SerialNo) {
                    // Convert product to array
                    $product = json_decode(json_encode($product), true);
                    // check length of list_of_serial_numbers[key_serial_number]['items']. if length is below 4 it will append to items of serial_number
                    if (count($list_of_serial_numbers[$key_serial_number]['items']) < 4) {
                        // add product to items of serial_number
                        $list_of_serial_numbers[$key_serial_number]['items'][] = $product;
                    }
                }
            }
        }

        // loop list of serial_number for check length items. if length is below 4 it will add empty product to items of serial_number
        foreach ($list_of_serial_numbers as $key_serial_number => $serial_number) {
            if (count($list_of_serial_numbers[$key_serial_number]['items']) < 4) { 
                // loop as much as 4 - length of list_of_serial_numbers[key_serial_number]['items']
                $length_of_minus_item = 4-count($list_of_serial_numbers[$key_serial_number]['items']);
                for ($i = 0; $i < $length_of_minus_item; $i++) {
                    // add empty product to items of serial_number
                    $list_of_serial_numbers[$key_serial_number]['items'][] = $empty_product;
                }
            }
        }

        // loop list of serial_number for adding ukuran by that serial_number
        foreach ($list_of_serial_numbers as $key_serial_number => $serial_number) {
            // get ukuran by serial_number
            $ukuran = FacadesDB::select("
                SELECT
                    C.Description
                FROM
                    productcategory A
                    LEFT JOIN product B ON A.ProductID = B.Model
                    LEFT JOIN designsize C ON B.VarSize = C.ID 
                WHERE
                    A.ID = '$idCategory' 
                    AND B.Active = 'Y' 
                    AND B.EnamelGroup IS NOT NULL 
                    AND B.SerialNo = '$serial_number[serial_number]'
                GROUP BY
                    B.VarSize
            ");

            $list_ukuran = [];
            foreach ($ukuran as $key => $value) {
                $list_ukuran[] = $value->Description;
            }

            // add list_ukuran to list of serial_number
            $list_of_serial_numbers[$key_serial_number]['ukuran'] = $list_ukuran;

            // set gambar2d of list_of_serial_numbers by serial_number items index 0 Photo2D
            $list_of_serial_numbers[$key_serial_number]['gambar2d'] = $list_of_serial_numbers[$key_serial_number]['items'][0]['Photo2D'];

            // set tanggal_stp of list_of_serial_numbers by serial_number items index 0 DesignStart
            $list_of_serial_numbers[$key_serial_number]['tanggal_stp'] = !is_null($list_of_serial_numbers[$key_serial_number]['items'][0]['DesignStart']) ? date('F Y',strtotime($list_of_serial_numbers[$key_serial_number]['items'][0]['DesignStart'])) : "";
        }

        $data_return = $this->SetReturn(false, "Model Found", $list_of_serial_numbers, null);
        // dd($data_return);
        return view('Master.Item.Catalog.cetakModelMarketing',compact('data_return'));
    }

    public function NewMarketingCatalog(Request $request){
        // $idCategory = 3;
        // $fromNumber = 10000;
        // $toNumber = 10051;
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;
        
        $product_marketing_list = [];

        // create empty product array
        $empty_product = [
            'ID'=>null,
            'SW'=>null,
            'Model'=>null,
            'NamaModel'=>null,
            'SerialNo'=>null,
            'Photo'=>'/image/nophoto.jpg',
            'Photo2D'=>"/image/nophoto.jpg",
            'DesignStart'=>null
        ];

        // get product category with idCategory
        $product_category = productcategory::where('ID', $idCategory)->first();
        if (is_null($product_category)) {
            // create data return for prduct category not found
            $data_return = $this->SetReturn(true, "Product Category Not Found", null, null);
            // return 404
            return response()->json($data_return, 404);
        }

        // get max serial number
        $get_max_serial_number = FacadesDB::connection('erp')->select("
            SELECT B.* FROM productcategory A JOIN product B ON A.ProductID = B.Model WHERE A.ID = 3 ORDER BY B.SerialNo DESC LIMIT 1
        ");
        $max_serial_number = $get_max_serial_number[0]->SerialNo;
        if ($toNumber > $max_serial_number) {
            $toNumber = $max_serial_number;
        }

        // loop fromNumber to toNumber
        for ($serialNo = $fromNumber; $serialNo <= $toNumber; $serialNo++) {
            $item = ['serial_number' => $serialNo, 'model' => $product_category->Description, 'items' => []];
            // Get product with idCategory and serialNo
            $products = FacadesDB::connection('erp')->select("
                SELECT
                    B.*
                FROM
                    productcategory A
                    LEFT JOIN product B ON A.ProductID = B.Model
                WHERE
                    A.ID = '$idCategory'
                    AND B.Active = 'Y'
                    AND B.SerialNo = '$serialNo'
            ");
            // check length of products. if length is 0 it will continue to next loop iteration
            if (count($products) == 0) {
                // skip this iteration
                continue;
            }

            $have_var_carat = false;
            
            // loop products for check if have var carat
            foreach ($products as $keyProduct => $valueProduct) {
                // convert product to array
                $product = json_decode(json_encode($valueProduct), true);
                // check if have var carat
                if (!is_null($product['VarCarat'])) {
                    // set have_var_carat to true
                    $have_var_carat = true;
                }
            }
            // check product VarCarat is null or not. if is null then this product is product lama.
            if (!$have_var_carat) {
                // get all product with idCategory and SerialNo
                $products = FacadesDB::connection('erp')->select("
                    SELECT
                        B.ID,
                        B.SW,
                        B.Model,
                        B.SerialNo,
                        CONCAT('/image/',REPLACE(B.Photo, '.jpg', ''),'.jpg') AS Photo,
                        CONCAT('/image/',CONCAT(A.SW, ' ', B.SerialNo),'.jpg') AS Photo2D,
                        null AS DesignStart
                    FROM
                        productcategory A
                        LEFT JOIN product B ON A.ProductID = B.Model
                    WHERE
                        A.ID = '$idCategory'
                        AND B.Active = 'Y'
                        AND B.SerialNo = '$serialNo'
                ");

                foreach ($products as $key => $value) {
                    // convert product to array
                    $product = json_decode(json_encode($value), true);
                    // check length items of item. if length is less than 4 it will add product to items of item
                    if (count($item['items']) < 4) {
                        // add product to items of item
                        $item['items'][] = $product;
                    }
                }

                if (count($item['items']) < 4) {
                    // loop as much as 4 - length of list_of_serial_numbers[key_serial_number]['items']
                    $length_of_minus_item = 4-count($item['items']);
                    for ($i = 0; $i < $length_of_minus_item; $i++) {
                        // add empty product to items of serial_number
                        $item['items'][] = $empty_product;
                    }
                }
            } else {
                // get all product with idCategory and SerialNo
                $products = FacadesDB::connection('erp')->select("
                    SELECT
                        B.ID,
                        B.SW,
                        B.Model,
                        A.SW AS NamaModel,
                        B.SerialNo,
                        CONCAT('/image/',REPLACE(B.Photo, '.jpg', ''),'.jpg') AS Photo,
                        CONCAT('/rnd2/Drafter 2D/Original/',REPLACE(C.ImageOriginal, '.jpg', ''),'.jpg') AS Photo2D,
                        C.DesignStart
                    FROM 
                        productcategory A
                        JOIN product B ON A.ProductID = B.Model
                        JOIN rndnew.drafter2d C ON C.Product = B.EnamelGroup
                    WHERE
                        A.ID = '$idCategory'
                        AND B.Active = 'Y' 
                        AND B.SerialNo = '$serialNo'
                        AND (B.VarCarat = 3 OR B.VarCarat = 1)
                    GROUP BY
                        B.SerialNo, B.VarEnamel, B.VarStone
                ");

                foreach ($products as $key => $value) {
                    // convert product to array
                    $product = json_decode(json_encode($value), true);
                    // check length items of item. if length is less than 4 it will add product to items of item
                    if (count($item['items']) < 4) {
                        // add product to items of item
                        $item['items'][] = $product;
                    }
                }

                if (count($item['items']) < 4) {
                    // loop as much as 4 - length of list_of_serial_numbers[key_serial_number]['items']
                    $length_of_minus_item = 4-count($item['items']);
                    for ($i = 0; $i < $length_of_minus_item; $i++) {
                        // add empty product to items of serial_number
                        $item['items'][] = $empty_product;
                    }
                }
            }

            // add item to product_marketing_list
            $product_marketing_list[] = $item;
        }

        // loop product_marketing_list for adding ukuran by that serial_number
        foreach ($product_marketing_list as $keyProduct => $valueProduct) {
            // get ukuran by serial_number
            $ukuran = FacadesDB::select("
                SELECT
                    C.Description
                FROM
                    productcategory A
                    LEFT JOIN product B ON A.ProductID = B.Model
                    LEFT JOIN designsize C ON B.VarSize = C.ID 
                WHERE
                    A.ID = '$idCategory' 
                    AND B.Active = 'Y' 
                    AND B.SerialNo = '$valueProduct[serial_number]'
                GROUP BY
                    B.VarSize
            ");

            $list_ukuran = [];
            foreach ($ukuran as $key => $value) {
                $list_ukuran[] = $value->Description;
            }

            // add list_ukuran to list of serial_number
            $product_marketing_list[$keyProduct]['ukuran'] = $list_ukuran;

            // set gambar2d of list_of_serial_numbers by serial_number items index 0 Photo2D
            if (count($product_marketing_list[$keyProduct]['items']) < 1) {
                $product_marketing_list[$keyProduct]['gambar2d'] = "/image/nophoto.jpg";
                $product_marketing_list[$keyProduct]['tanggal_stp'] = "";
                continue;
            } 
            $product_marketing_list[$keyProduct]['gambar2d'] = $product_marketing_list[$keyProduct]['items'][0]['Photo2D'];

            // set tanggal_stp of list_of_serial_numbers by serial_number items index 0 DesignStart
            $product_marketing_list[$keyProduct]['tanggal_stp'] = !is_null($product_marketing_list[$keyProduct]['items'][0]['DesignStart']) ? date('F Y',strtotime($product_marketing_list[$keyProduct]['items'][0]['DesignStart'])) : "";
        }
        // return  response()->json($product_marketing_list, 200);
        $items = $product_marketing_list;

        $html = view('Master.Item.Catalog.marketingItemLayout',compact('items'))->render();

        // build json data for returning json of items and html
        $data = [
            'items'=>$items,
            'html'=>$html
        ];

        $data_return = $this->SetReturn(false, "Model Found", $data, null);
        // return data_return with 200 status code
        return response()->json($data_return, 200);
    }

    public function NewMarketingCatalogPrint(Request $request){
        // $idCategory = 3;
        // $fromNumber = 10000;
        // $toNumber = 10051;
        $idCategory = $request->idcategory;
        $fromNumber = $request->fromnumber;
        $toNumber = $request->tonumber;
        
        $product_marketing_list = [];

        // create empty product array
        $empty_product = [
            'ID'=>null,
            'SW'=>null,
            'Model'=>null,
            'NamaModel'=>null,
            'SerialNo'=>null,
            'Photo'=>'/image/nophoto.jpg',
            'Photo2D'=>"/image/nophoto.jpg",
            'DesignStart'=>null
        ];

        // get product category with idCategory
        $product_category = productcategory::where('ID', $idCategory)->first();
        if (is_null($product_category)) {
            // create data return for prduct category not found
            $data_return = $this->SetReturn(true, "Product Category Not Found", null, null);
            // return 404
            return response()->json($data_return, 404);
        }

        // get max serial number
        $get_max_serial_number = FacadesDB::connection('erp')->select("
            SELECT B.* FROM productcategory A JOIN product B ON A.ProductID = B.Model WHERE A.ID = 3 ORDER BY B.SerialNo DESC LIMIT 1
        ");
        $max_serial_number = $get_max_serial_number[0]->SerialNo;
        if ($toNumber > $max_serial_number) {
            $toNumber = $max_serial_number;
        }

        // loop fromNumber to toNumber
        for ($serialNo = $fromNumber; $serialNo <= $toNumber; $serialNo++) {
            $item = ['serial_number' => $serialNo, 'model' => $product_category->Description, 'items' => []];
            // Get product with idCategory and serialNo
            $products = FacadesDB::connection('erp')->select("
                SELECT
                    B.*
                FROM
                    productcategory A
                    LEFT JOIN product B ON A.ProductID = B.Model
                WHERE
                    A.ID = '$idCategory'
                    AND B.Active = 'Y'
                    AND B.SerialNo = '$serialNo'
            ");
            // check length of products. if length is 0 it will continue to next loop iteration
            if (count($products) == 0) {
                // skip this iteration
                continue;
            }

            $have_var_carat = false;
            
            // loop products for check if have var carat
            foreach ($products as $keyProduct => $valueProduct) {
                // convert product to array
                $product = json_decode(json_encode($valueProduct), true);
                // check if have var carat
                if (!is_null($product['VarCarat'])) {
                    // set have_var_carat to true
                    $have_var_carat = true;
                }
            }
            // check product VarCarat is null or not. if is null then this product is product lama.
            if (!$have_var_carat) {
                // get all product with idCategory and SerialNo
                $products = FacadesDB::connection('erp')->select("
                    SELECT
                        B.ID,
                        B.SW,
                        B.Model,
                        B.SerialNo,
                        CONCAT('/image/',REPLACE(B.Photo, '.jpg', ''),'.jpg') AS Photo,
                        CONCAT('/image/',CONCAT(A.SW, ' ', B.SerialNo),'.jpg') AS Photo2D,
                        null AS DesignStart
                    FROM
                        productcategory A
                        LEFT JOIN product B ON A.ProductID = B.Model
                    WHERE
                        A.ID = '$idCategory'
                        AND B.Active = 'Y'
                        AND B.SerialNo = '$serialNo'
                ");

                foreach ($products as $key => $value) {
                    // convert product to array
                    $product = json_decode(json_encode($value), true);
                    // add product to items of item
                    $item['items'][] = $product;
                }
                if (count($item['items']) < 4) {
                    // loop as much as 4 - length of list_of_serial_numbers[key_serial_number]['items']
                    $length_of_minus_item = 4-count($item['items']);
                    for ($i = 0; $i < $length_of_minus_item; $i++) {
                        // add empty product to items of serial_number
                        $item['items'][] = $empty_product;
                    }
                }
            } else {
                // get all product with idCategory and SerialNo
                $products = FacadesDB::connection('erp')->select("
                    SELECT
                        B.ID,
                        B.SW,
                        B.Model,
                        A.SW AS NamaModel,
                        B.SerialNo,
                        CONCAT('/image/',REPLACE(B.Photo, '.jpg', ''),'.jpg') AS Photo,
                        CONCAT('/rnd2/Drafter 2D/Original/',REPLACE(C.ImageOriginal, '.jpg', ''),'.jpg') AS Photo2D,
                        C.DesignStart
                    FROM 
                        productcategory A
                        JOIN product B ON A.ProductID = B.Model
                        JOIN rndnew.drafter2d C ON C.Product = B.EnamelGroup
                    WHERE
                        A.ID = '$idCategory'
                        AND B.Active = 'Y' 
                        AND B.SerialNo = '$serialNo'
                        AND (B.VarCarat = 3 OR B.VarCarat = 1)
                    GROUP BY
                        B.SerialNo, B.VarEnamel, B.VarStone
                ");

                foreach ($products as $key => $value) {
                    // convert product to array
                    $product = json_decode(json_encode($value), true);
                    // check length items of item. if length is less than 4 it will add product to items of item
                    if (count($item['items']) < 4) {
                        // add product to items of item
                        $item['items'][] = $product;
                    }
                }

                if (count($item['items']) < 4) {
                    // loop as much as 4 - length of list_of_serial_numbers[key_serial_number]['items']
                    $length_of_minus_item = 4-count($item['items']);
                    for ($i = 0; $i < $length_of_minus_item; $i++) {
                        // add empty product to items of serial_number
                        $item['items'][] = $empty_product;
                    }
                }
            }

            // add item to product_marketing_list
            $product_marketing_list[] = $item;
        }

        // loop product_marketing_list for adding ukuran by that serial_number
        foreach ($product_marketing_list as $keyProduct => $valueProduct) {
            // get ukuran by serial_number
            $ukuran = FacadesDB::select("
                SELECT
                    C.Description
                FROM
                    productcategory A
                    LEFT JOIN product B ON A.ProductID = B.Model
                    LEFT JOIN designsize C ON B.VarSize = C.ID 
                WHERE
                    A.ID = '$idCategory' 
                    AND B.Active = 'Y' 
                    AND B.SerialNo = '$valueProduct[serial_number]'
                GROUP BY
                    B.VarSize
            ");

            $list_ukuran = [];
            foreach ($ukuran as $key => $value) {
                $list_ukuran[] = $value->Description;
            }

            // add list_ukuran to list of serial_number
            $product_marketing_list[$keyProduct]['ukuran'] = $list_ukuran;

            // set gambar2d of list_of_serial_numbers by serial_number items index 0 Photo2D
            if (count($product_marketing_list[$keyProduct]['items']) < 1) {
                $product_marketing_list[$keyProduct]['gambar2d'] = "/image/nophoto.jpg";
                $product_marketing_list[$keyProduct]['tanggal_stp'] = "";
                continue;
            } 
            $product_marketing_list[$keyProduct]['gambar2d'] = $product_marketing_list[$keyProduct]['items'][0]['Photo2D'];

            // set tanggal_stp of list_of_serial_numbers by serial_number items index 0 DesignStart
            $product_marketing_list[$keyProduct]['tanggal_stp'] = !is_null($product_marketing_list[$keyProduct]['items'][0]['DesignStart']) ? date('F Y',strtotime($product_marketing_list[$keyProduct]['items'][0]['DesignStart'])) : "";
        }
        // return  response()->json($product_marketing_list, 200);
        $items = $product_marketing_list;

        $data_return = $this->SetReturn(false, "Model Found", $items, null);
        // dd($data_return);
        return view('Master.Item.Catalog.cetakModelMarketing',compact('data_return'));
    }
}

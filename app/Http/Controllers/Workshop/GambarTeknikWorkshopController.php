<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

// Model
// PROD
use App\Models\rndnew\wipworkshop;
use App\Models\rndnew\wipworkshopfg;
use App\Models\rndnew\matras;
use App\Models\rndnew\matrasitem;
use App\Models\rndnew\knives;
use App\Models\rndnew\jenismatras;
use App\Models\rndnew\jenismatrasitem;
use App\Models\rndnew\rawmaterialworkshop;
use App\Models\rndnew\mastergambarteknik;
use App\Models\rndnew\gambarteknikmatras;
use App\Models\rndnew\gambarteknikmatrasitem;
use App\Models\rndnew\materialmatras;
// DEV
// use App\Models\tes_laravel\wipworkshop;
// use App\Models\tes_laravel\wipworkshopfg;
// use App\Models\tes_laravel\matras;
// use App\Models\tes_laravel\matrasitem;
// use App\Models\tes_laravel\knives;
// use App\Models\tes_laravel\jenismatras;
// use App\Models\tes_laravel\jenismatrasitem;
// use App\Models\tes_laravel\rawmaterialworkshop;
// use App\Models\tes_laravel\mastergambarteknik;
// use App\Models\tes_laravel\gambarteknikmatras;
// use App\Models\tes_laravel\gambarteknikmatrasitem;
// use App\Models\tes_laravel\materialmatras;

// Message: "Gambar Teknik Sudah sampai create baru dengan tambahan foto gambar teknik. Kurang edit gambar teknik dan search gambar teknik"

class GambarTeknikWorkshopController extends Controller{
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
        $jenisMatras = jenismatras::with('Items')->get();
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');
        // $request->session()->put('hostfoto', 'http://192.168.1.39:8383');
        return view('Workshop.GambarTeknik.index',compact('jenisMatras'));
    }

    public function GenerateForm(Request $request){
        $jenisMatras = $request->jenisMatras;
        $jumlahMatras = $request->jumlahMatras;
        $jumlahItemMatras = $request->jumlahItemMatras;
        $pakaiPisau = $request->pakaiPisau;

        if ($jenisMatras == 'BBGC' or $jenisMatras == 'BGC') {

            if ($jumlahMatras < 1) {
                $data_return = $this->SetReturn(false, 'Minimum Jumlah Matras Adalah 1', null, null);
                return response()->json($data_return, 400);
            }

            if ($jumlahMatras > 2) {
                $data_return = $this->SetReturn(false, 'Maksimal Jumlah Matras Adalah 2', null, null);
                return response()->json($data_return, 400);
            }

            if ($jumlahItemMatras != 1) {
                $data_return = $this->SetReturn(false, 'Maksimal Jumlah Item Matras Adalah 1', null, null);
                return response()->json($data_return, 400);
            }

            // Get BBGC Product from WIP Workshop
            $BBGCProduct = [];
            $getBBGCProduct = FacadesDB::select("
                SELECT
                    A.ID,
                    B.SW AS nomorPCB,
                    C.SW AS SWProduct,
                    CASE
                        WHEN C.TypeProcess = 27 THEN (SELECT kepalaheader.SW FROM product JOIN kepalaheader ON product.Model = kepalaheader.ID WHERE product.ID = C.ID)
                        WHEN C.TypeProcess = 26 THEN (SELECT mainanheader.SW FROM product JOIN mainanheader ON product.Model = mainanheader.ID WHERE product.ID = C.ID)
                        WHEN C.TypeProcess = 25 THEN (SELECT componentheader.SW FROM product JOIN componentheader ON product.Model = componentheader.ID WHERE product.ID = C.ID)
                    END AS modelProduk
                FROM
                    wipworkshop A
                    JOIN erp.workorder B ON A.IDWorkOrder = B.ID
                    JOIN product C ON A.IDProduct = C.ID
                WHERE
                    A.Active = 'A'
                    AND A.ProgressStatus = 1
            ");
            foreach ($getBBGCProduct as $key => $value) {
                if ($value->modelProduk == $jenisMatras) {
                    $BBGCProduct[] = $value;
                }
            }
            // Get RawMaterial for Workshop
            $rawMaterial = rawmaterialworkshop::all();
            $HTMLView = view('Workshop.GambarTeknik.formBBGC',compact('BBGCProduct','jumlahMatras','rawMaterial'))->render();
            $data = [
                "BBGCProduct"=>$BBGCProduct,
                "layout"=>$HTMLView,
                "rawMaterial"=>$rawMaterial
            ];
            $data_return = $this->SetReturn(true, 'Generate Form Success', $data, null);
            return response()->json($data_return, 200);
        }
    }

    public function SaveGambarTeknik(Request $request){
        $jenisMatras = $request->jenisMatras;
        if ($jenisMatras == "" or is_null($jenisMatras)) {
            $data_return = $this->SetReturn(false, 'jenisMatras Tidak Boleh Kosong', null, null);
            return response()->json($data_return, 400);
        }
        
        if ($jenisMatras == "BBGC") {
            $jumlahMatras = $request->jumlahMatras;
            $jumlahItemMatras = $request->jumlahItemMatras;
            $matras = $request->matras;
            $fileAutocad = $request->fileAutocad;

            // Create MasterGambarTeknik
            $newMasterGambarTeknik = mastergambarteknik::create([
                "UserName"=>Auth::user()->name,
                "Active"=>"A",
                "JenisMatras"=>$jenisMatras,
                "JumlahMatras"=>$jumlahMatras,
                "JumlahItemMatras"=>$jumlahItemMatras
            ]);

            foreach ($matras as $key => $value) {
                $uuid = Str::uuid()->getHex();
                $filename = $uuid.".".$fileAutocad[$key]->extension();
                $fileAutocad[$key]->storeAs("RND DATA/Workshop/AutocadFile",$filename,'Server_F');
                // Decode dataMatras
                $dataMatras = json_decode(base64_decode($value));

                // Decode base64 
                $fotoGambarTeknik = explode(',',$dataMatras->fotoGambarTeknik);
                $fileFotoGambarTeknik = base64_decode($fotoGambarTeknik[1]);
                $filenameGambar = $uuid.".".strtolower($dataMatras->extensionFoto);
                $saveFotoGambarTeknik = Storage::disk('Server_F')->put('RND DATA/Workshop/AutocadFile/' . $filenameGambar, $fileFotoGambarTeknik);

                // Get Product in WIP
                $productWIP = wipworkshop::with('Product')->where('ID',$dataMatras->produkMatras)->first();
                
                // Create gambarTeknikMatras
                $newGambarTeknikMatras = gambarteknikmatras::create([
                    "UserName"=>Auth::user()->name,
                    "TransDate"=>date('Y-m-d'),
                    "IDMasterGambarTeknik"=>$newMasterGambarTeknik->id,
                    "JenisMatras"=>$jenisMatras,
                    "FileAutocad"=>$filename,
                    "FotoGambarTeknik"=>$filenameGambar
                ]);

                // Create gambarTeknikMatrasItem
                $newGambarTeknikMatrasItem = gambarteknikmatrasitem::create([
                    "IDGambarTeknikMatras"=>$newGambarTeknikMatras->id,
                    "IDWIPWorkshop"=>$dataMatras->produkMatras
                ]);

                // Generate SW for Matras
                $generateSW = FacadesDB::select("
                    SELECT
                        CONCAT(
                            'FKW',
                            '',
                            DATE_FORMAT( CURDATE(), '%y' ),
                            '',
                            LPad( MONTH ( CurDate()), 2, '0' ),
                            '01',
                        LPad( Count( ID ) + 1, 3, '0' )) Counter,
                        CONCAT(
                            '',
                            DATE_FORMAT( CURDATE(), '%y' ),
                            '',
                            LPad( MONTH ( CurDate()), 2, '0' ),
                            '01',
                        LPad( Count( ID ) + 1, 3, '0' )) SW
                    FROM
                        gambarteknikmatras 
                    WHERE
                        YEAR ( TransDate ) = YEAR (CurDate()) 
                        AND MONTH ( TransDate ) = MONTH (CurDate())
                ");
                $generateSW = $generateSW[0];

                $SWMatras = $jenisMatras.".".$dataMatras->tipeMatras[0].".".$productWIP->Product->SerialNo;
                
                // Create Matras
                $newMatras = matras::create([
                    "UserName"=>Auth::user()->name,
                    "TransDate"=>Date('Y-m-d'),
                    "Employee"=>$this->GetEmployee(Auth::user()->name)[0]->ID,
                    "SW"=>$SWMatras,
                    "JenisMatras"=>$jenisMatras,
                    "TipeMatras"=>$dataMatras->tipeMatras,
                    "IDGambarTeknikMatras"=>$newGambarTeknikMatras->id,
                    "Status"=>"G",
                    "Active"=>1
                ]);

                // Create MatrasItem
                $newMatrasItem = matrasitem::create([
                    "IDMatras"=>$newMatras->id,
                    "IDProduct"=>$productWIP->IDProduct
                ]);

                // Create MaterialMatras
                foreach ($dataMatras->rawMaterial as $key => $value) {
                    $newMaterialMatras = materialmatras::create([
                        "IDMatras"=>$newMatras->id,
                        "IDRawMaterialWorkshop"=>$value->idMaterial,
                        "Qty"=>$value->jumlahMaterial
                    ]);
                }
            }
            $data_return = $this->SetReturn(true, 'Gambar Teknik Berhasil disimpan', $newMasterGambarTeknik, null);
            return response()->json($data_return, 200);
        } else {
            $data_return = $this->SetReturn(false, 'Feature not yet developed', null, null);
            return response()->json($data_return, 500);
        }
    }

    public function UpdateGambarTeknik(Request $request){
        $jenisMatras = $request->jenisMatras;
        if ($jenisMatras == "" or is_null($jenisMatras)) {
            $data_return = $this->SetReturn(false, 'jenisMatras Tidak Boleh Kosong', null, null);
            return response()->json($data_return, 400);
        }
        
        if ($jenisMatras == "BBGC") {
            $idGambarTeknik = $request->idGambarTeknik;
            $jumlahMatras = $request->jumlahMatras;
            $matras = $request->matras;
            $fileAutocad = $request->fileAutocad;
            // dd($idGambarTeknik);
            // Search MasterGambarTeknik
            $getGambarTeknik = mastergambarteknik::where('ID', $idGambarTeknik)->first();
            
            // Search GambarTeknik
            $gambarTeknik = gambarteknikmatras::where('IDMasterGambarTeknik',$getGambarTeknik->ID)->get();
            // Loop GambarTeknik for deleteting data;
            foreach ($gambarTeknik as $key => $value) {
                // Get matras with this gambarteknik id
                $getMatras = matras::where('IDGambarTeknikMatras',$value->ID)->first();
                
                // Delete matras
                $deleteMatras = matras::where('ID',$getMatras->ID)->delete();

                // Delete matrasitem
                $deleteMatrasItem = matrasitem::where('IDMatras',$getMatras->ID)->delete();

                // Delete materialmatras
                $deleteMaterialMatras = materialmatras::where('IDMatras',$getMatras->ID)->delete();

                // Delete GambarTeknikMatrasItem
                $deleteGambarTeknikMatrasItem = gambarteknikmatrasitem::where('IDGambarTeknikMatras', $value->ID)->delete();
                
            }

            foreach ($matras as $key => $value) {
                
                
                // Decode dataMatras
                $dataMatras = json_decode(base64_decode($value));

                // Get Product in WIP
                $productWIP = wipworkshop::with('Product')->where('ID',$dataMatras->produkMatras)->first();
                
                $idGambarTeknikMatras = $gambarTeknik[$key]->ID;

                // Check if fotoGambarTeknik is not null
                if (!is_null($dataMatras->fotoGambarTeknik)) {
                    // Update GambarTeknikMatras
                    $oldFileName = $gambarTeknik[$key]->FotoGambarTeknik;
                    Storage::disk('Server_F')->delete('RND DATA/Workshop/AutocadFile/'.$oldFileName);
                    $uuid = Str::uuid()->getHex();
                    $fotoGambarTeknik = explode(',',$dataMatras->fotoGambarTeknik);
                    $fileFotoGambarTeknik = base64_decode($fotoGambarTeknik[1]);
                    $filenameGambar = $uuid.".".strtolower($dataMatras->extensionFoto);
                    $saveFotoGambarTeknik = Storage::disk('Server_F')->put('RND DATA/Workshop/AutocadFile/' . $filenameGambar, $fileFotoGambarTeknik);
                    $updateGambarTeknikMatras = gambarteknikmatras::where("ID",$idGambarTeknikMatras)->update([
                        "FotoGambarTeknik"=>$filenameGambar
                    ]);
                }

                // Create gambarTeknikMatrasItem
                $newGambarTeknikMatrasItem = gambarteknikmatrasitem::create([
                    "IDGambarTeknikMatras"=>$idGambarTeknikMatras,
                    "IDWIPWorkshop"=>$dataMatras->produkMatras
                ]);

                // Generate SW for Matras
                $generateSW = FacadesDB::select("
                    SELECT
                        CONCAT(
                            'FKW',
                            '',
                            DATE_FORMAT( CURDATE(), '%y' ),
                            '',
                            LPad( MONTH ( CurDate()), 2, '0' ),
                            '01',
                        LPad( Count( ID ) + 1, 3, '0' )) Counter,
                        CONCAT(
                            '',
                            DATE_FORMAT( CURDATE(), '%y' ),
                            '',
                            LPad( MONTH ( CurDate()), 2, '0' ),
                            '01',
                        LPad( Count( ID ) + 1, 3, '0' )) SW
                    FROM
                        gambarteknikmatras 
                    WHERE
                        YEAR ( TransDate ) = YEAR (CurDate()) 
                        AND MONTH ( TransDate ) = MONTH (CurDate())
                ");
                $generateSW = $generateSW[0];

                $SWMatras = $jenisMatras.".".$dataMatras->tipeMatras[0].".".$productWIP->Product->SerialNo;
                
                // Create Matras
                $newMatras = matras::create([
                    "UserName"=>Auth::user()->name,
                    "TransDate"=>Date('Y-m-d'),
                    "Employee"=>$this->GetEmployee(Auth::user()->name)[0]->ID,
                    "SW"=>$SWMatras,
                    "JenisMatras"=>$jenisMatras,
                    "TipeMatras"=>$dataMatras->tipeMatras,
                    "IDGambarTeknikMatras"=>$idGambarTeknikMatras,
                    "Status"=>"G",
                    "Active"=>1
                ]);

                // Create MatrasItem
                $newMatrasItem = matrasitem::create([
                    "IDMatras"=>$newMatras->id,
                    "IDProduct"=>$productWIP->IDProduct
                ]);

                // Create MaterialMatras
                foreach ($dataMatras->rawMaterial as $key => $value) {
                    $newMaterialMatras = materialmatras::create([
                        "IDMatras"=>$newMatras->id,
                        "IDRawMaterialWorkshop"=>$value->idMaterial,
                        "Qty"=>$value->jumlahMaterial
                    ]);
                }
            }
            $data_return = $this->SetReturn(true, 'Gambar Teknik Berhasil diUpdate', null, null);
            return response()->json($data_return, 200);
        } else {
            $data_return = $this->SetReturn(false, 'Feature not yet developed', null, null);
            return response()->json($data_return, 500);
        }
    }

    public function SearchGambarTeknik(Request $request){
        $idGambarTeknik = $request->idGambarTeknik;

        // Get MasterGambarTeknik
        $gambarTeknik = mastergambarteknik::with('GambarTeknik', 'GambarTeknik.Items', 'GambarTeknik.Items.wipWorkshop', 'GambarTeknik.Items.wipWorkshop.Product', 'GambarTeknik.Matras', 'GambarTeknik.Matras.Materials')->where('ID',$idGambarTeknik)->first();
        // Check if MasterGambarTeknik is exists
        if (is_null($gambarTeknik)) {
            $data_return = $this->SetReturn(false, 'Gambar Teknik Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Get BBGC Product from WIP Workshop
        $BBGCProduct = [];
        $getBBGCProduct = FacadesDB::select("
            SELECT
                A.ID,
                B.SW AS nomorPCB,
                C.SW AS SWProduct,
                CASE
                    WHEN C.TypeProcess = 27 THEN (SELECT kepalaheader.SW FROM product JOIN kepalaheader ON product.Model = kepalaheader.ID WHERE product.ID = C.ID)
                    WHEN C.TypeProcess = 26 THEN (SELECT mainanheader.SW FROM product JOIN mainanheader ON product.Model = mainanheader.ID WHERE product.ID = C.ID)
                    WHEN C.TypeProcess = 25 THEN (SELECT componentheader.SW FROM product JOIN componentheader ON product.Model = componentheader.ID WHERE product.ID = C.ID)
                END AS modelProduk
            FROM
                wipworkshop A
                JOIN erp.workorder B ON A.IDWorkOrder = B.ID
                JOIN product C ON A.IDProduct = C.ID
            WHERE
                A.Active = 'A'
        ");
        foreach ($getBBGCProduct as $key => $value) {
            if ($value->modelProduk == "BBGC") {
                $BBGCProduct[] = $value;
            }
        }
        // Get RawMaterial for Workshop
        $rawMaterial = rawmaterialworkshop::all();
        $renderHTML = view('Workshop.GambarTeknik.formLihatBBGC',compact('rawMaterial','BBGCProduct', 'gambarTeknik'))->render();
        $data = [
            "rawMaterial"=>$rawMaterial,
            "MasterGambarTeknik"=>$gambarTeknik,
            "layout"=>$renderHTML
        ];
        $data_return = $this->SetReturn(true, 'Berhasil Ditemukan', $data, null);
        return response()->json($data_return, 200);
    }

    public function PostingGambarTeknik(Request $request){
        $idGambarTeknik = $request->idGambarTeknik;

        if ($idGambarTeknik == "" or is_null($idGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'idGambarTeknik Cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }


        // Get MasterGambarTeknik
        $masterGambarTeknik = mastergambarteknik::with('GambarTeknik','GambarTeknik.Items','GambarTeknik.Items.wipWorkshop')->where('ID',$idGambarTeknik)->first();
        if (is_null($masterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'Gambar Teknik Dengan ID Tersebut Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if MasterGambarTeknik Active Status is A
        if ($masterGambarTeknik->Active != 'A') {
            $data_return = $this->SetReturn(false, 'Gambar Teknik Dengan ID Tersebut Sudah Pernah Di Posting', null, null);
            return response()->json($data_return, 400);
        }

        // Cek WIP
        foreach ($masterGambarTeknik->GambarTeknik as $key => $value) {
            foreach ($value->Items as $key2 => $value2) {
                if ($value2->wipWorkshop->ProgressStatus != 1) {
                    $data_return = $this->SetReturn(false, 'Produk Sudah Pernah dipakai. Ganti ke produk yang lain.', null, null);
                    return response()->json($data_return, 400);
                }
            }
        }

        // Update WIP
        foreach ($masterGambarTeknik->GambarTeknik as $key => $value) {
            foreach ($value->Items as $key2 => $value2) {
                $updateWIP = wipworkshop::where('ID',$value2->IDWIPWorkshop)->update([
                    'ProgressStatus'=>7
                ]);
            }
        }

        // Update MasterGambarTeknik
        $updateMasterGambarTeknik = mastergambarteknik::where('ID', $idGambarTeknik)->update([
            'Active'=>'P'
        ]);
        
        // Success
        $data_return = $this->SetReturn(false, 'Posting Gambar Teknik Sukses', null, null);
        return response()->json($data_return, 200);
    }

    public function CetakGambarTeknik(Request $request){
        $idGambarTeknik = $request->idGambarTeknik;
        if ($idGambarTeknik == "" or is_null($idGambarTeknik)) {
            return aboart(400);
        }

        // Get MasterGambarTeknik
        $gambarTeknik = mastergambarteknik::with('GambarTeknik', 'GambarTeknik.Items', 'GambarTeknik.Items.wipWorkshop', 'GambarTeknik.Items.wipWorkshop.Product', 'GambarTeknik.Matras', 'GambarTeknik.Matras.Materials', 'GambarTeknik.Matras.Materials.rawMaterial')->where('ID',$idGambarTeknik)->first();
        // Check if MasterGambarTeknik is exists
        if (is_null($gambarTeknik)) {
            return aboart(404);
        }
        if ($gambarTeknik->JenisMatras == 'BBGC') {
            return view('Workshop.GambarTeknik.cetakBBGC', compact('gambarTeknik'));
        } else {
            return abort(500);
        }

    }

    public function SimpanGambarTeknik2(Request $request){
        $jenisMatras = $request->jenisMatras;
        if ($jenisMatras == "" or is_null($jenisMatras)) {
            $data_return = $this->SetReturn(false, 'jenisMatras Tidak Boleh Kosong', null, null);
            return response()->json($data_return, 400);
        }
        
        if ($jenisMatras == "BBGC") {
            $jumlahMatras = $request->jumlahMatras;
            $jumlahItemMatras = $request->jumlahItemMatras;
            $matras = $request->matras;
            $fileAutocad = $request->fileAutocad;

            foreach ($matras as $key => $value) {
                $uuid = Str::uuid()->getHex();
                $filename = $uuid.".".$fileAutocad[$key]->extension();
                $fileAutocad[$key]->storeAs("Workshop/AutocadFile",$filename,'rnd');
                // Decode dataMatras
                $dataMatras = json_decode(base64_decode($value));
                dd('oke');
            }
            $data_return = $this->SetReturn(true, 'Gambar Teknik Berhasil disimpan', $newMasterGambarTeknik, null);
            return response()->json($data_return, 200);
        } else {
            $data_return = $this->SetReturn(false, 'Feature not yet developed', null, null);
            return response()->json($data_return, 500);
        }
    }
}

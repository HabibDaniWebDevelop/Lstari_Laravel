<?php

namespace App\Http\Controllers\Workshop\SPKO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// PROD
use App\Models\rndnew\wipworkshop;
use App\Models\rndnew\wipworkshopfg;
use App\Models\rndnew\workshopallocation;
use App\Models\rndnew\workshopallocationitem;
use App\Models\rndnew\matras;
use App\Models\rndnew\matrasitem;
use App\Models\rndnew\knives;
use App\Models\rndnew\jenismatras;
use App\Models\rndnew\jenismatrasitem;
use App\Models\rndnew\lastid;
use App\Models\rndnew\rawmaterialworkshop;
use App\Models\rndnew\mastergambarteknik;
use App\Models\rndnew\gambarteknikmatras;
use App\Models\rndnew\gambarteknikmatrasitem;
use App\Models\rndnew\materialmatras;
use App\Models\rndnew\matrasallocation;
use App\Models\rndnew\matrasallocationitem;
// DEV
// use App\Models\tes_laravel\wipworkshop;
// use App\Models\tes_laravel\wipworkshopfg;
// use App\Models\tes_laravel\workshopallocation;
// use App\Models\tes_laravel\workshopallocationitem;
// use App\Models\tes_laravel\matras;
// use App\Models\tes_laravel\matrasitem;
// use App\Models\tes_laravel\knives;
// use App\Models\tes_laravel\jenismatras;
// use App\Models\tes_laravel\jenismatrasitem;
// use App\Models\rndnew\lastid;
// use App\Models\tes_laravel\rawmaterialworkshop;
// use App\Models\tes_laravel\mastergambarteknik;
// use App\Models\tes_laravel\gambarteknikmatras;
// use App\Models\tes_laravel\gambarteknikmatrasitem;
// use App\Models\tes_laravel\materialmatras;
// use App\Models\tes_laravel\matrasallocation;
// use App\Models\tes_laravel\matrasallocationitem;

class SPKOMatrasWorkshopController extends Controller{
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
        $now = date('Y-m-d');
        $employees = FacadesDB::connection('erp')->select("
            SELECT
                ID,
                SW,
                Description 
            FROM
                employee
            WHERE
                department IN (9,10,43)
                AND Active = 'Y'
            ORDER BY
                Department ASC
        ");
        return view('Workshop.SPKO.Matras.index',compact('now', 'employees'));
    }

    public function getGambarTeknik(Request $request){
        $idMasterGambarTeknik = $request->idMasterGambarTeknik;
        if ($idMasterGambarTeknik == "" or is_null($idMasterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'idMaster Gambar Teknik cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }

        // Get Master Matras
        $masterGambarTeknik = mastergambarteknik::with('GambarTeknik', 'GambarTeknik.Items', 'GambarTeknik.Items.wipWorkshop', 'GambarTeknik.Items.wipWorkshop.Product', 'GambarTeknik.Matras')->where('ID', $idMasterGambarTeknik)->first();
        if (is_null($masterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'Master Gambar Teknik Matras Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if Master Gambar Teknik Status == 'P'
        if ($masterGambarTeknik->Active != "P") {
            $data_return = $this->SetReturn(false, 'Master Gambar Teknik Matras Belum di Posting', null, null);
            return response()->json($data_return, 400);
        }

        $itemsHTML = view('Workshop.SPKO.Matras.rowItemMatras', compact('masterGambarTeknik'))->render();
        
        $data = [
            "masterGambarTeknik"=>$masterGambarTeknik,
            "itemsHTML"=>$itemsHTML
        ];

        $data_return = $this->SetReturn(true, 'Master Gambar Teknik ditemukan', $data, null);
        return response()->json($data_return, 200);
    }

    public function SaveSPKOMatrasWorkshop(Request $request){
        $idMasterGambarTeknik = $request->idMasterGambarTeknik;
        $idEmployee = $request->idEmployee;
        $catatan = $request->catatan;
        
        // Check if idMasterGambarTeknik is null or blank
        if ($idMasterGambarTeknik == "" or is_null($idMasterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'idMasterGambarTeknik Tidak Boleh kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Check if idEmployee is null or blank
        if ($idEmployee == "" or is_null($idEmployee)) {
            $data_return = $this->SetReturn(false, 'idEmployee Tidak Boleh kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Get MasterGambarTeknik
        $masterGambarTeknik = mastergambarteknik::with('GambarTeknik', 'GambarTeknik.Items', 'GambarTeknik.Items.wipWorkshop', 'GambarTeknik.Matras')->where('ID', $idMasterGambarTeknik)->first();
        // Check if idMasterGambarTeknik is exists
        if (is_null($masterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'Gambar Teknik Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if idMasterGambar Teknik is in matrasAllocation
        $matrasAllocation = matrasallocation::where('IDMasterGambarTeknik',$idMasterGambarTeknik)->first();
        if (!is_null($matrasAllocation)) {
            $data_return = $this->SetReturn(false, 'Gambar Teknik Sudah Pernah di SPKO', null, null);
            return response()->json($data_return, 400);
        }

        // Check WIP ProgressStatus
        foreach ($masterGambarTeknik->GambarTeknik as $key => $value) {
            foreach ($value->Items as $key => $valueWIP) {
                $idWIP = $valueWIP->wipWorkshop->ID;
                $progressStatus = $valueWIP->wipWorkshop->ProgressStatus;
                if ($progressStatus != 7) {
                    $data_return = $this->SetReturn(false, "Invalid WIP Progress StatusCode '$progressStatus' on WIP ID '$idWIP' ", null, null);
                    return response()->json($data_return, 400);
                }
            }
        }

        // Check Success
        
        // Generate SW
        $SW = "FKWM".date('y').date('m')."01".str_pad( matrasallocation::count()+1, 3, "0", STR_PAD_LEFT );

        // Create Matras Allocation
        $newMatrasAllocation = matrasallocation::create([
           "UserName"=>Auth::user()->name,
           "Remarks"=>$catatan,
           "TransDate"=>date('Y-m-d'),
           "Active"=>"A",
           "SW"=>$SW,
           "Employee"=>$idEmployee,
           "IDMasterGambarTeknik"=>$idMasterGambarTeknik
        ]);

        $matrasAllocationItem = [];
        foreach ($masterGambarTeknik->GambarTeknik as $key => $value) {
            // CreateMatrasAllocationitem
            $newMatrasAllocationItem = matrasallocationitem::create([
                "IDMatrasAllocation"=>$newMatrasAllocation->id,
                "IDMatras"=>$value->Matras->ID,
                "IDGambarTeknikMatras"=>$value->ID
            ]);
            $matrasAllocationItem[] = $newMatrasAllocationItem;
        }
        $newMatrasAllocation['MatrasAllocationItem'] = $matrasAllocationItem;
        
        $data_return = $this->SetReturn(true, 'SPKO Sukses dibuat', $newMatrasAllocation, null);
        return response()->json($data_return, 200);
    }

    public function UpdateSPKOMatrasWorkshop(Request $request){
        $idSPKOMatras = $request->idSPKOMatras;
        $idMasterGambarTeknik = $request->idMasterGambarTeknik;
        $idEmployee = $request->idEmployee;
        $catatan = $request->catatan;
        
        // Check if idSPKOMatras is null or blank
        if ($idSPKOMatras == "" or is_null($idSPKOMatras)) {
            $data_return = $this->SetReturn(false, 'idSPKOMatras Tidak Boleh kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Check if idMasterGambarTeknik is null or blank
        if ($idMasterGambarTeknik == "" or is_null($idMasterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'idMasterGambarTeknik Tidak Boleh kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Check if idEmployee is null or blank
        if ($idEmployee == "" or is_null($idEmployee)) {
            $data_return = $this->SetReturn(false, 'idEmployee Tidak Boleh kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Get MasterGambarTeknik
        $masterGambarTeknik = mastergambarteknik::with('GambarTeknik', 'GambarTeknik.Items', 'GambarTeknik.Items.wipWorkshop', 'GambarTeknik.Matras')->where('ID', $idMasterGambarTeknik)->first();
        // Check if idMasterGambarTeknik is exists
        if (is_null($masterGambarTeknik)) {
            $data_return = $this->SetReturn(false, 'Gambar Teknik Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if matrasAllocation is exists
        $matrasAllocation = matrasallocation::where('ID',$idSPKOMatras)->first();
        if (is_null($matrasAllocation)) {
            $data_return = $this->SetReturn(false, 'SPKO Matras Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if idMasterGambar Teknik is in matrasAllocation
        $cekmatrasAllocation = matrasallocation::where('IDMasterGambarTeknik',$idMasterGambarTeknik)->first();
        if (!is_null($cekmatrasAllocation)) {
            if ($cekmatrasAllocation->ID != $idSPKOMatras) {
                $data_return = $this->SetReturn(false, 'Gambar Teknik Sudah Pernah di SPKO', null, null);
                return response()->json($data_return, 400);
            }
        }

        // Check matrasAllocation Active status
        if ($matrasAllocation->Active != 'A') {
            $data_return = $this->SetReturn(false, 'SPKO Sudah Diposting. Tidak dapat diubah.', null, null);
            return response()->json($data_return, 400);
        }

        // Check WIP ProgressStatus
        foreach ($masterGambarTeknik->GambarTeknik as $key => $value) {
            foreach ($value->Items as $key => $valueWIP) {
                $idWIP = $valueWIP->wipWorkshop->ID;
                $progressStatus = $valueWIP->wipWorkshop->ProgressStatus;
                if ($progressStatus != 7) {
                    $data_return = $this->SetReturn(false, "Invalid WIP Progress StatusCode '$progressStatus' on WIP ID '$idWIP' ", null, null);
                    return response()->json($data_return, 400);
                }
            }
        }

        // Checking Success
        
        // delete matrasallocationitem
        $deleteMatrasAllocationItem = matrasallocationitem::where('IDMatrasAllocation',$idSPKOMatras)->delete();

        // Update MatrasAllocation 
        $updateMatrasAllocation = matrasallocation::where('ID', $idSPKOMatras)->update([
            "Remarks"=>$catatan,
            "Employee"=>$idEmployee,
            "IDMasterGambarTeknik"=>$idMasterGambarTeknik
        ]);

        foreach ($masterGambarTeknik->GambarTeknik as $key => $value) {
            // CreateMatrasAllocationitem
            $newMatrasAllocationItem = matrasallocationitem::create([
                "IDMatrasAllocation"=>$idSPKOMatras,
                "IDMatras"=>$value->Matras->ID,
                "IDGambarTeknikMatras"=>$value->ID
            ]);
        }

        $data_return = $this->SetReturn(true, 'SPKO Sukses Diubah', null, null);
        return response()->json($data_return, 200);
    }

    public function PostingSPKOMatrasWorkshop(Request $request){
        $idSPKOMatras = $request->idSPKOMatras;
        if ($idSPKOMatras == "" or is_null($idSPKOMatras)) {
            $data_return = $this->SetReturn(false, 'idSPKO Tidak Boleh kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Check if SPKO is exists
        $matrasAllocation = matrasallocation::with('MatrasAllocationItems', 'MatrasAllocationItems.GambarTeknikMatras', 'MatrasAllocationItems.GambarTeknikMatras.Items', 'MatrasAllocationItems.GambarTeknikMatras.Items.wipWorkshop')->where("ID",$idSPKOMatras)->first();
        if (is_null($matrasAllocation)) {
            $data_return = $this->SetReturn(false, 'SPKO Matras Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if SPKO is already Posted
        if ($matrasAllocation->Active != 'A'){
            $data_return = $this->SetReturn(false, 'SPKO Tersebut Sudah Di Posting', null, null);
            return response()->json($data_return, 400);
        }

        // Checking Success

        // Update status SPKO
        $updateSPKOMatras = matrasallocation::where('ID',$idSPKOMatras)->update([
            "Active"=>"P"
        ]);

        // Update WIP & Matras Status
        foreach ($matrasAllocation->MatrasAllocationItems as $key => $value) {
            foreach ($value->GambarTeknikMatras->Items as $key => $valueWIP) {
                // Update WIP
                $idWIP = $valueWIP->wipWorkshop->ID;
                $updateWIP = wipworkshop::where('ID',$idWIP)->update([
                    "ProgressStatus"=>5
                ]);
            }
            // Update Matras Status
            $updateMatras = matras::where('ID', $value['IDMatras'])->update([
                "Status"=>'B'
            ]);
        }

        $data_return = $this->SetReturn(true, 'SPKO Berhasil Diposting', null, null);
        return response()->json($data_return, 200);
    }

    public function FindSPKO(Request $request){
        $idSPKOMatras = $request->idSPKOMatras;
        if (is_null($idSPKOMatras) or $idSPKOMatras == "") {
            $data_return = $this->SetReturn(false, 'idSPKOMatras Tidak Boleh Kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Try to get SPKO matras
        $matrasAllocation = matrasAllocation::with('MatrasAllocationItems')->where('ID',$idSPKOMatras)->first();
        // Check if spko exists
        if (is_null($matrasAllocation)) {
            $data_return = $this->SetReturn(false, 'SPKO Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Get GambarTeknik
        $masterGambarTeknik = mastergambarteknik::with('GambarTeknik', 'GambarTeknik.Items', 'GambarTeknik.Items.wipWorkshop', 'GambarTeknik.Items.wipWorkshop.Product', 'GambarTeknik.Matras')->where('ID', $matrasAllocation->IDMasterGambarTeknik)->first();
        
        $itemsHTML = view('Workshop.SPKO.Matras.rowItemMatras', compact('masterGambarTeknik'))->render();
        
        $data = [
            "matrasAllocation"=>$matrasAllocation,
            "itemsHTML"=>$itemsHTML
        ];

        $data_return = $this->SetReturn(true, 'SPKO Ditemukan', $data, null);
        return response()->json($data_return, 200);
    }

    public function CetakSPKO(Request $request){
        $idSPKOMatras = $request->idSPKOMatras;
        // check if idSPKO is not null or blank
        if ($idSPKOMatras == "" or is_null($idSPKOMatras)) {
            return abort(404);
        }

        // Get SPKOMatras
        $matrasAllocation = matrasallocation::with('MatrasAllocationItems', 'MatrasAllocationItems.Matras', 'MatrasAllocationItems.Matras.Items', 'MatrasAllocationItems.Matras.Items.Product')->where('ID',$idSPKOMatras)->first();
        // Check if spkoWorkshop is exists
        if (is_null($matrasAllocation)) {
            return abort(404);
        }

        // Check if spkoworkshop is Workshop New
        if ($matrasAllocation->Active != 'P') {
            return abort(404);
        }
        
        // Get detail of Admin
        $admin = $this->GetEmployee($matrasAllocation['UserName']);
        $admin = $admin[0]->NAME;
        // Get detail of Operator
        $operator = $this->GetEmployee($matrasAllocation['Employee']);
        $operator = $operator[0]->NAME;
        $matrasAllocation['Admin'] = $admin;
        $matrasAllocation['Operator'] = $operator;

        // Get SPK PCB
        $listSpkPCB = [];
        $spkPCB = FacadesDB::select("
            SELECT
                E.SW
            FROM
                matrasallocation A
                JOIN matrasallocationitem B ON A.ID = B.IDMatrasAllocation
                JOIN gambarteknikmatrasitem C ON C.IDGambarTeknikMatras = B.IDGambarTeknikMatras
                JOIN wipworkshop D ON C.IDWIPWorkshop = D.ID
                JOIN erp.workorder E ON D.IDWorkOrder = E.ID
            WHERE
                A.ID = '$idSPKOMatras'
            GROUP BY
                E.ID
        ");
        foreach ($spkPCB as $key => $value) {
            $listSpkPCB[] = $value->SW;
        }
        $matrasAllocation['spkPCB'] = $listSpkPCB;

        // Get MaterialMatras
        $materialMatras = FacadesDB::select("
            SELECT
                E.Name AS NamaMaterial,
                SUM(D.Qty) AS Qty
            FROM
                matrasallocation A
                JOIN matrasallocationitem B ON A.ID = B.IDMatrasAllocation
                JOIN matras C ON B.IDMatras = C.ID
                JOIN materialmatras D ON C.ID = D.IDMatras
                JOIN rawmaterialworkshop E ON D.IDRawMaterialWorkshop = E.ID
            WHERE
                A.ID = '$idSPKOMatras'
            GROUP BY
                D.IDRawMaterialWorkshop    
        ");
        $matrasAllocation['materialMatras'] = $materialMatras;

        return view('Workshop.SPKO.Matras.cetak',compact('matrasAllocation'));
    }
}

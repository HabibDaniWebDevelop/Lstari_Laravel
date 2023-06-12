<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
// PROD
use App\Models\rndnew\matras;
use App\Models\rndnew\matrasitem;
use App\Models\rndnew\knives;
use App\Models\rndnew\jenismatras;
use App\Models\rndnew\jenismatrasitem;
use App\Models\rndnew\matrastransfer;
use App\Models\rndnew\matrastransferitem;
// DEV
// use App\Models\tes_laravel\matras;
// use App\Models\tes_laravel\matrasitem;
// use App\Models\tes_laravel\knives;
// use App\Models\tes_laravel\jenismatras;
// use App\Models\tes_laravel\jenismatrasitem;
// use App\Models\tes_laravel\matrastransfer;
// use App\Models\tes_laravel\matrastransferitem;

class TMMatrasWorkshopController extends Controller{
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

    public function index(Request $request){
        return view('Workshop.TMMatras.index');
    }

    public function GetMatras(Request $request){
        $idMatras = $request->idMatras;
        // Check if idMatras is blank or null
        if ($idMatras == "" or is_null($idMatras)) {
            $data_return = $this->SetReturn(false, 'idMatras cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }

        // Check if matras is exists
        $matras = matras::where('ID',$idMatras)->first();
        if (is_null($matras)) {
            $data_return = $this->SetReturn(false, 'Matras Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if matras status is 'A'
        if ($matras->Status != 'A') {
            $data_return = $this->SetReturn(false, 'Status Matras bukan Active', null, null);
            return response()->json($data_return, 400);
        }

        // Success
        $data_return = $this->SetReturn(true, 'Matras Ditemukan', $matras, null);
        return response()->json($data_return, 200);
    }

    public function SaveTMMatras(Request $request){
        $listIDMatras = $request->listIdMatras;
        // Check if List ID Matras
        if (!is_array($listIDMatras)) {
            $data_return = $this->SetReturn(false, 'listIDMatras must be array', null, null);
            return response()->json($data_return, 400);
        }
        
        // Loop listidmatras for check idmatras is exists
        foreach ($listIDMatras as $key => $value) {
            // Check matras
            $matras = matras::where('ID',$value)->first();
            if (is_null($matras)) {
                $data_return = $this->SetReturn(false, 'Matras Tidak Ditemukan', null, null);
                return response()->json($data_return, 404);
            }
            
            // Check if matras status is 'A'
            if ($matras->Status != 'A') {
                $data_return = $this->SetReturn(false, 'Status Matras bukan Active', null, null);
                return response()->json($data_return, 400);
            }

            // Check if that matras is alread TMed
            $TMMatrasItem = matrastransferitem::where('IDMatras',$value)->first();
            if (!is_null($TMMatrasItem)) {
                $data_return = $this->SetReturn(false, "Matras dengan ID '$value' sudah pernah di TM ", null, null);
                return response()->json($data_return, 400);
            }
        }

        // Create TMMatras
        $TMMatras = matrastransfer::create([
            "UserName"=>Auth::user()->name,
            "TransDate"=>date('Y-m-d'),
            "Employee"=>$this->GetEmployee(Auth::user()->name)[0]->ID,
            "Active"=>"A"
        ]);
        // Loop listidmatras and insert it to TMMatrasItem
        foreach ($listIDMatras as $key => $value) {
            $TMMatrasItem = matrastransferitem::create([
                "IDMatrasTransfer"=>$TMMatras->id,
                "IDMatras"=>$value
            ]);
        }
        $data_return = $this->SetReturn(true, 'TM Matras Berhasil', $TMMatras, null);
        return response()->json($data_return, 200);
    }

    public function UpdateTMMatras(Request $request){
        $idTMMatras = $request->idTMMatras;
        $listIDMatras = $request->listIdMatras;
        // Check if List ID Matras
        if (!is_array($listIDMatras)) {
            $data_return = $this->SetReturn(false, 'listIDMatras must be array', null, null);
            return response()->json($data_return, 400);
        }

        // Check if idTMMatras is not null or blank
        if ($idTMMatras == "" or is_null($idTMMatras)) {
            $data_return = $this->SetReturn(false, 'idTMMatras cant be null or blank', null, null);
            return response()->json($data_return, 400);
        }
        
        // Check if that idTM is exists
        $TMMatras = matrastransfer::where('ID',$idTMMatras)->first();
        if (is_null($TMMatras)) {
            $data_return = $this->SetReturn(false, 'TM Matras Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Loop listidmatras for check idmatras is exists
        foreach ($listIDMatras as $key => $value) {
            // Check matras
            $matras = matras::where('ID',$value)->first();
            if (is_null($matras)) {
                $data_return = $this->SetReturn(false, 'Matras Tidak Ditemukan', null, null);
                return response()->json($data_return, 404);
            }
            // Check if that matras is alread TMed
            $TMMatrasItem = matrastransferitem::where('IDMatras',$value)->first();
            if (!is_null($TMMatrasItem)) {
                if ($TMMatrasItem->IDMatrasTransfer != $idTMMatras) {
                    $data_return = $this->SetReturn(false, "Matras dengan ID '$value' sudah pernah di TM ", null, null);
                    return response()->json($data_return, 400);
                }
            }
        }

        // Update TMMatras
        $TMMatras = matrastransfer::where("ID",$idTMMatras)->update([
            "UserName"=>Auth::user()->name,
            "TransDate"=>date('Y-m-d'),
            "Employee"=>$this->GetEmployee(Auth::user()->name)[0]->ID,
            "Active"=>"A"
        ]);

        // Delete TMMatrasItem
        $deleteTMMatrasItem = matrastransferitem::where('IDMatrasTransfer',$idTMMatras)->delete();
        // Loop listidmatras and insert it to TMMatrasItem
        foreach ($listIDMatras as $key => $value) {
            $TMMatrasItem = matrastransferitem::create([
                "IDMatrasTransfer"=>$idTMMatras,
                "IDMatras"=>$value
            ]);
        }
        $data_return = $this->SetReturn(true, 'TM Matras Berhasil diubah', null, null);
        return response()->json($data_return, 200);
    }

    public function SearchTMMatras(Request $request){
        $idTMMatras = $request->idTMMatras;
        if ($idTMMatras == "" or is_null($idTMMatras)) {
            $data_return = $this->SetReturn(false, 'ID TM Matras Tidak Boleh Kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Check if TM is exists
        $TMMatras = matrastransfer::with("Items", "Items.Matras")->where('ID',$idTMMatras)->first();
        if (is_null($TMMatras)) {
            $data_return = $this->SetReturn(false, 'TM Matras dengan ID Tersebut tidak ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        $data_return = $this->SetReturn(true, 'ID TM Matras Tidak Boleh Kosong', $TMMatras, null);
        return response()->json($data_return, 200);
    }

    public function CetakTMMatras(Request $request){
        $idTMMatras = $request->idTMMatras;
        if ($idTMMatras == "" or is_null($idTMMatras)) {
            return abort(404);
        }

        // Check if TM is exists
        $TMMatras = matrastransfer::with("Items", "Items.Matras")->where('ID',$idTMMatras)->first();
        if (is_null($TMMatras)) {
            $data_return = $this->SetReturn(false, 'TM Matras dengan ID Tersebut tidak ditemukan', null, null);
            return response()->json($data_return, 404);
        }
        return view('Workshop.TMMatras.cetak', compact('TMMatras'));
    }
}

<?php

namespace App\Http\Controllers\Workshop\NTHKO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
// PROD
use App\Models\rndnew\wipworkshop;
use App\Models\rndnew\wipworkshopfg;
use App\Models\rndnew\workshopallocation;
use App\Models\rndnew\workshopallocationitem;
use App\Models\rndnew\workshopcompletion;
use App\Models\rndnew\workshopcompletionitem;
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
use App\Models\rndnew\matrascompletion;
use App\Models\rndnew\matrascompletionitem;
// DEV
// use App\Models\tes_laravel\wipworkshop;
// use App\Models\tes_laravel\wipworkshopfg;
// use App\Models\tes_laravel\workshopallocation;
// use App\Models\tes_laravel\workshopallocationitem;
// use App\Models\tes_laravel\workshopcompletion;
// use App\Models\tes_laravel\workshopcompletionitem;
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
// use App\Models\tes_laravel\matrascompletion;
// use App\Models\tes_laravel\matrascompletionitem;


class NTHKOMatrasWorkshopController extends Controller{
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
        $jenisMatras = jenismatras::with('Items')->get();
        return view('Workshop.NTHKO.Matras.index',compact('now', 'employees', 'jenisMatras'));
    }

    public function GetSPKOWorkshop(Request $request){
        $idSPKOMatras = $request->idSPKOMatras;
        if (is_null($idSPKOMatras) or $idSPKOMatras == "") {
            $data_return = $this->SetReturn(false, 'idSPKOMatras Tidak Boleh Kosong', null, null);
            return response()->json($data_return, 400);
        }

        // Try to get SPKO matras
        $matrasAllocation = matrasAllocation::with('MatrasAllocationItems', 'MatrasAllocationItems.Matras', 'MatrasAllocationItems.Matras.Items', 'MatrasAllocationItems.Matras.Items.Product')->where('ID',$idSPKOMatras)->first();
        // dd($matrasAllocation);
        // Check if spko exists
        if (is_null($matrasAllocation)) {
            $data_return = $this->SetReturn(false, 'SPKO Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Check if matrasAllocation is already posted
        if ($matrasAllocation->Active != 'P') {
            $data_return = $this->SetReturn(false, 'SPKO Belum Diposting', null, null);
            return response()->json($data_return, 400);
        }
        
        $itemsHTML = view('Workshop.NTHKO.Matras.rowItemMatras', compact('matrasAllocation'))->render();
        
        $data = [
            "matrasAllocation"=>$matrasAllocation,
            "itemsHTML"=>$itemsHTML
        ];

        $data_return = $this->SetReturn(true, 'SPKO Matras Ditemukan', $data, null);
        return response()->json($data_return, 200);
    }

    public function saveNTHKOWorkshop(Request $request){
        $idSPKOMatras = $request->idSPKOMatras;
        $hasilNTHKO = $request->hasilNTHKO;
        
        // ----- Verified Inputs ----- //
        // Check if idSPKOMatras is null or blank
        if (is_null($idSPKOMatras) or $idSPKOMatras == "") {
            $data_return = $this->SetReturn(false, 'idSPKOMatras Cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }

        // Check if spkoMatras exists
        $matrasAllocation = matrasallocation::with('MatrasAllocationItems', 'MatrasAllocationItems.Matras', 'MatrasAllocationItems.Matras.Items', 'MatrasAllocationItems.GambarTeknikMatras', 'MatrasAllocationItems.GambarTeknikMatras.Items', 'MatrasAllocationItems.GambarTeknikMatras.Items.wipWorkshop')->where('ID',$idSPKOMatras)->first();
        if (is_null($matrasAllocation)) {
            $data_return = $this->SetReturn(false, "SPKO Matras dengan id '$idSPKOMatras' Tidak Ditemukan ", null, null);
            return response()->json($data_return, 404);
        }

        // Check if MatrasAllocation is already posted
        if ($matrasAllocation->Active != 'P') {
            $data_return = $this->SetReturn(false, "SPKO Matras dengan id '$idSPKOMatras' Belum Diposting ", null, null);
            return response()->json($data_return, 400);
        }

        // Check if that idSPKO is already NTHKOED
        $matrasCompletion = matrascompletion::where('Allocation',$matrasAllocation->SW)->first();
        if (!is_null($matrasCompletion)) {
            $data_return = $this->SetReturn(false, 'SPKO Tersebut Sudah Pernah Di NTHKO', null, null);
            return response()->json($data_return, 400);
        }

        // Checking WIP ProgressStatusCode
        foreach ($matrasAllocation->matrasAllocationItems as $key => $value) {
            foreach ($value->GambarTeknikMatras->Items as $key => $valueWIP) {
                $idWIP = $valueWIP->wipWorkshop->ID;
                $progressStatus = $valueWIP->wipWorkshop->ProgressStatus;
                if ($progressStatus != 5) {
                    $data_return = $this->SetReturn(false, "Invalid WIP Progress StatusCode '$progressStatus' on WIP ID '$idWIP' ", null, null);
                    return response()->json($data_return, 400);
                }
            }
        }

        // Checking success
        
        // Insert to matrasCompletion
        $newMatrasCompletion = matrascompletion::create([
            "UserName"=>Auth::user()->name,
            "Employee"=>$matrasAllocation->Employee,
            "TransDate"=>date('Y-m-d'),
            "Active"=>"A",
            "IDMatrasAllocation"=>$matrasAllocation->ID,
            "Allocation"=>$matrasAllocation->SW,
            "Result"=>$hasilNTHKO
        ]);
        $matrasCompletionItems = [];
        // Loop SPKOItems for insert to NTHKOItems
        foreach ($matrasAllocation->matrasAllocationItems as $key => $value) {
            $newMatrasCompletionItems = matrascompletionitem::create([
                "IDMatrasCompletion"=>$newMatrasCompletion->id,
                "IDMatras"=>$value->IDMatras,
                "IDGambarTeknikMatras"=>$value->IDGambarTeknikMatras,
                "IDMatrasAllocationItem"=>$value->ID
            ]);
            $matrasCompletionItems[] = $newMatrasCompletionItems;
        }
        $newMatrasCompletion['items'] = $matrasCompletionItems;

        // Update WIP & Status Matras
        foreach ($matrasAllocation->matrasAllocationItems as $key => $value) {
            foreach ($value->GambarTeknikMatras->Items as $key => $valueWIP) {
                // Update WIP
                $updateWIP = wipworkshop::where('ID',$valueWIP->IDWIPWorkshop)->update([
                    'ProgressStatus'=>6
                ]);
            }
            // Update Status Matras
            if ($hasilNTHKO == 'GOOD') {
                $updateMatras = matras::where('ID',$value['IDMatras'])->update([
                    "Status"=>'A'
                ]);
            } else {
                $updateMatras = matras::where('ID',$value['IDMatras'])->update([
                    "Status"=>'C'
                ]);
            }
        }

        $data_return = $this->SetReturn(true, 'Simpan NTHKO Sukses', $newMatrasCompletion, null);
        return response()->json($data_return, 200);
    }

    public function updateNTHKOMatrasWorkshop(Request $request){
        $idNTHKOWorkshop = $request->idNTHKOWorkshop;
        $hasilNTHKO = $request->hasilNTHKO;

        // ----- Verified Inputs ----- //
        // Check if idNTHKOWorkshop is null or blank
        if (is_null($idNTHKOWorkshop) or $idNTHKOWorkshop == "") {
            $data_return = $this->SetReturn(false, 'idNTHKOWorkshop Cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }

        // Check if hasilNTHKO is "GOOD" or "NO GOOD"
        if (!in_array($hasilNTHKO, ["GOOD","NO GOOD"])) {
            $data_return = $this->SetReturn(false, 'Invalid hasilNTHKO', null, null);
            return response()->json($data_return, 400);
        }

        // Check if nthko exists
        $matrasCompletion = matrascompletion::where('ID',$idNTHKOWorkshop)->first();
        if (is_null($matrasCompletion)) {
            $data_return = $this->SetReturn(false, "NTHKO dengan id '$idNTHKOWorkshop' Tidak Ditemukan ", null, null);
            return response()->json($data_return, 404);
        }

        // Checking success

        // Update NTHKO Matras Workshop
        if ($hasilNTHKO == 'GOOD') {
            $updateMatrasCompletion = matrascompletion::where('ID',$idNTHKOWorkshop)->update([
                "Result"=>$hasilNTHKO,
                "Status"=>'A'
            ]);
        } else {
            $updateMatrasCompletion = matrascompletion::where('ID',$idNTHKOWorkshop)->update([
                "Result"=>$hasilNTHKO,
                "Status"=>'C'
            ]);
        }
        
        $data_return = $this->SetReturn(true, 'SUCCESS', null, null);
        return response()->json($data_return, 200);   
    }

    public function SearchNTHKOMatrasWorkshop(Request $request){
        $idNTHKOMatras = $request->idNTHKOMatras;
        // Check input
        if ($idNTHKOMatras == "" or is_null($idNTHKOMatras)) {
            $data_return = $this->SetReturn(false, 'idNTHKOMatras cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }
        
        // Find matrasCompletion
        $matrasCompletion = matrascompletion::with('MatrasCompletionItems')->where('ID', $idNTHKOMatras)->first();
        if (is_null($matrasCompletion)) {
            $data_return = $this->SetReturn(false, 'NTHKO Matras Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Build Items
        $matrasAllocation = matrasAllocation::with('MatrasAllocationItems', 'MatrasAllocationItems.Matras', 'MatrasAllocationItems.Matras.Items', 'MatrasAllocationItems.Matras.Items.Product')->where('ID',$matrasCompletion->IDMatrasAllocation)->first();
        $itemsHTML = view('Workshop.NTHKO.Matras.rowItemMatras', compact('matrasAllocation'))->render();
        
        $data = [
            "matrasCompletion"=>$matrasCompletion,
            "itemsHTML"=>$itemsHTML
        ];
        
        $data_return = $this->SetReturn(true, 'Search Success', $data, null);
        return response()->json($data_return, 200);
    }

    public function CetakNTHKOMatrasWorkshop(Request $request){
        $idNTHKOMatras = $request->idNTHKOMatras;
        // Check input
        if ($idNTHKOMatras == "" or is_null($idNTHKOMatras)) {
            return abort(400);
        }
        
        $matrasCompletion = matrascompletion::with('MatrasCompletionItems', 'MatrasCompletionItems.Matras', 'MatrasCompletionItems.Matras.Items', 'MatrasCompletionItems.Matras.Items.Product', 'MatrasCompletionItems.Matras.Items.knive')->where('ID', $idNTHKOMatras)->first();
        if (is_null($matrasCompletion)) {
            return abort(404);
        }
        

        // Get Employee
        $employee = $this->GetEmployee($matrasCompletion->Employee);
        $employee = $employee[0];
        $admin = $this->GetEmployee($matrasCompletion->UserName);
        $admin = $admin[0];
        $matrasCompletion->Karyawan = $employee->NAME;
        $matrasCompletion->Admin = $admin->NAME;
        
        return view('Workshop.NTHKO.Matras.cetak', compact('matrasCompletion'));
    }
}
?>
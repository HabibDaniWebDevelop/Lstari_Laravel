<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
// use App\Models\tes_laravel\listrubberwax;
// use App\Models\tes_laravel\listrubberwaxitem;
use App\Models\rndnew\listrubberwax;
use App\Models\rndnew\listrubberwaxitem;
use App\Models\rndnew\lastid;

class TMKaretPCBKeLilinController extends Controller{
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

    private function FindRubberCommon($idRubber){
        $rubber = FacadesDB::connection('erp')->select("
            SELECT
                A.ID,
                A.Product,
                B.SW,
                B.TypeProcess
            FROM
                rubber A
                JOIN product B ON A.Product = B.ID
            WHERE
                A.ID = '$idRubber'
        ");
        return $rubber;
    }
    // END REUSABLE FUNCTION

    public function Index(){
        $listhist = FacadesDB::select("
            SELECT
                A.ID
            FROM
                listrubberwax A
                JOIN listrubberwaxitem B ON A.ID = B.IDM
            WHERE
                A.Location = 'Lilin'
                AND B.WorkAllocation IS NULL
            GROUP BY
                B.IDM
            ORDER BY
                B.IDM DESC
            LIMIT 10
        ");
        return view('R&D.Percobaan.TMKaretPCBKeLilin.Index', compact('listhist'));
    }

    public function GetRubber(Request $request){
        // Get Input
        $idRubber = $request->idRubber;
        // Check if idRubber is blank or null
        if (is_null($idRubber) or $idRubber == "") {
            $data_return = $this->SetReturn(false, "ID Karet Tidak Boleh Null atau blank", null, null);
            return response()->json($data_return, 400);
        }

        // Get Rubber
        $rubber = $this->FindRubberCommon($idRubber);

        if (count($rubber) == 0) {
            $data_return = $this->SetReturn(false, "ID Karet Tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $rubber = $rubber[0];
        $data_return = $this->SetReturn(true, "ID Karet ditemukan", $rubber, null);
        return response()->json($data_return, 200);
    }

    public function SaveTMKaretPCBKeLilin(Request $request){
        $listIDRubber = $request->listIDRubber;
        // Check listIDRubber
        if (is_null($listIDRubber) or !is_array($listIDRubber)) {
            $data_return = [
                "success"=>false,
                "message"=>"Create TM Karet Lilin Failed",
                "data"=>null,
                "error"=>[
                    "listIDRubber"=>"listIDRubber Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check listIDRubber length
        if (count($listIDRubber) == 0) {
            $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed", null, ["listIDRubber"=>"listIDRubber Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if that rubber is exists
        foreach ($listIDRubber as $key => $value) {
            $rubber = $this->FindRubberCommon($value);
            if (count($rubber) == 0) {
                $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed. Rubber dengan ID '$value' Tidak Ditemukan", null, null);
                return response()->json($data_return,404);
            }
        }

        // Check if that rubber is exists in listrubberwax
        foreach ($listIDRubber as $key => $value) {
            // $cekListRubberWaxitem = listrubberwaxitem::where('IDRubber',$value)->first();
            $cekListRubberWaxitem = FacadesDB::select("
                SELECT
                    A.Product,
                    A.IDRubber,
                    B.* 
                FROM
                    listrubberwaxitem A
                    JOIN listrubberwax B ON A.IDM = B.ID
                WHERE
                    A.IDRubber = '$value'
            ");
            if (count($cekListRubberWaxitem) != 0) {
                // $cekListRubberWaxitem = $cekListRubberWaxitem[0];
                foreach ($cekListRubberWaxitem as $keyItem => $valueItem) {
                    if ($valueItem->Location == 'Lilin') {
                        if (is_null($valueItem->PostDate)) {
                            $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed. Rubber dengan ID '$value' sudah pernah dibuatkan TM. Tapi Masih belum di posting. ID TM Karet : ".$valueItem->ID, null, null);
                            return response()->json($data_return,400);
                        } else {
                            $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed. Rubber dengan ID '$value' sudah pernah dibuatkan TM dan Sudah di posting. ID TM Karet : ".$valueItem->ID, null, null);
                            return response()->json($data_return,400);
                        }
                    }   
                }
            }
        }

        // GetEmployee
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];

        // All checking success
        // Insert listrubberwax
        // Get lastid
        $lastid = lastid::where('Module','listrubberwax')->first();
        $lastid = $lastid->Last+1;

        // Insert to ListRubberWax
        $insertListRubberWax = listrubberwax::create([
            "ID"=>$lastid,
            "UserName"=>Auth::user()->name,
            "Remarks"=>"",
            "Employee"=>$employee->ID,
            "TransDate"=>date("Y-m-d"),
            "Location"=>"Lilin"
        ]);

        $ordinal = 1;
        // loop Insert ListRubberWaxItem
        foreach ($listIDRubber as $key => $value) {
            $rubber = $this->FindRubberCommon($value);
            $rubber = $rubber[0];
            $insertListRubberWaxItem = listrubberwaxitem::create([
                "IDM"=>$insertListRubberWax->id,
                "Ordinal"=>$ordinal,
                "Product"=>$rubber->Product,
                "IDRubber"=>$rubber->ID,
                "Active"=>"A"
            ]);
            $ordinal+=1;
        }

        // Update Lastid
        $updatelastid = lastid::where('Module','listrubberwax')
        ->update([
            "Last"=>$lastid
        ]);

        $data_return = $this->SetReturn(true, "Save TM Karet PCB ke Lilin Success", ['idTMKaretLilin'=>$insertListRubberWax->id], null);
        return response()->json($data_return, 200);    
    }

    public function UpdateTMKaretLilin(Request $request){
        $idTMKaretPCBKeLilin = $request->idTMKaretPCBKeLilin;
        $listIDRubber = $request->listIDRubber;
        // Check listIDRubber
        if (is_null($listIDRubber) or !is_array($listIDRubber)) {
            $data_return = [
                "success"=>false,
                "message"=>"Update TM Karet Lilin Failed",
                "data"=>null,
                "error"=>[
                    "listIDRubber"=>"listIDRubber Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check listIDRubber length
        if (count($listIDRubber) == 0) {
            $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed", null, ["listIDRubber"=>"listIDRubber Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Check idTMKaretPCBKeLilin input
        if (is_null($idTMKaretPCBKeLilin) or $idTMKaretPCBKeLilin == "") {
            $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed", null, ["idTMKaretPCBKeLilin"=>"idTMKaretPCBKeLilin Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if idTMKaretPCBKeLilin is exists
        $cekListRubberWax = listrubberwax::where('ID',$idTMKaretPCBKeLilin)->where('Location','Lilin')->first();
        if (is_null($cekListRubberWax)) {
            $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed. TM dengan ID '$value' Tidak Ditemukan", null, null);
            return response()->json($data_return,404);
        }

        // Check if that rubber is exists
        foreach ($listIDRubber as $key => $value) {
            $rubber = $this->FindRubberCommon($value);
            if (count($rubber) == 0) {
                $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed. Rubber dengan ID '$value' Tidak Ditemukan", null, null);
                return response()->json($data_return,404);
            }
        }

        // Check if that rubber is exists in listrubberwax
        foreach ($listIDRubber as $key => $value) {
            // $cekListRubberWaxitem = listrubberwaxitem::where('IDRubber',$value)->first();
            $cekListRubberWaxitem = FacadesDB::select("
                SELECT
                    A.Product,
                    A.IDRubber,
                    B.* 
                FROM
                    listrubberwaxitem A
                    JOIN listrubberwax B ON A.IDM = B.ID
                WHERE
                    A.IDRubber = '$value'
            ");
            foreach ($cekListRubberWaxitem as $keyItem => $valueItem) {
                if ($valueItem->Location == 'Lilin') {
                    if ($valueItem->ID != $idTMKaretPCBKeLilin) {
                        if (is_null($valueItem->PostDate)) {
                            $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed. Rubber dengan ID '$value' sudah pernah dibuatkan TM. Tapi Masih belum di posting. ID TM Karet : ".$valueItem->ID, null, null);
                            return response()->json($data_return,400);
                        } else {
                            $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed. Rubber dengan ID '$value' sudah pernah dibuatkan TM dan Sudah di posting. ID TM Karet : ".$valueItem->ID, null, null);
                            return response()->json($data_return,400);
                        }
                    }
                }
            }
            // if (!is_null($cekListRubberWaxitem)) {
            //     if ($cekListRubberWaxitem->IDM != $idTMKaretPCBKeLilin) {
            //         $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed. Rubber dengan ID '$value' sudah pernah di TM", null, null);
            //         return response()->json($data_return,400);
            //     }
            // }
        }

        // GetEmployee
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];

        // All checking success
        // update listrubberwax
        // update to ListRubberWax
        $updateListRubberWax = listrubberwax::where('ID',$idTMKaretPCBKeLilin)->where('Location','Lilin')
        ->update([
            "UserName"=>Auth::user()->name,
            "Remarks"=>"",
            "Employee"=>$employee->ID,
            "TransDate"=>date("Y-m-d")
        ]);

        // Delete listrubberwaxitem
        $deleteListRubberWaxItem = listrubberwaxitem::where('IDM',$idTMKaretPCBKeLilin)->delete();

        $ordinal = 1;
        // loop Insert ListRubberWaxItem
        foreach ($listIDRubber as $key => $value) {
            $rubber = $this->FindRubberCommon($value);
            $rubber = $rubber[0];
            $insertListRubberWaxItem = listrubberwaxitem::create([
                "IDM"=>$idTMKaretPCBKeLilin,
                "Ordinal"=>$ordinal,
                "Product"=>$rubber->Product,
                "IDRubber"=>$rubber->ID,
                "Active"=>"A"
            ]);
            $ordinal+=1;
        }

        $data_return = $this->SetReturn(true, "Update TM Karet PCB ke Lilin Success", ['idTMKaretLilin'=>$idTMKaretPCBKeLilin], null);
        return response()->json($data_return, 200);    
    }

    public function SearchTMKaretLilin(Request $request){
        $keyword = $request->keyword;
        if ($keyword == "" or is_null($keyword)) {
            $data_return = $this->SetReturn(false, "keyword Tidak Boleh Null atau blank", null, null);
            return response()->json($data_return, 400);
        }
        // Get TM
        $listRubberWax = listrubberwax::where('ID',$keyword)->where('Location','Lilin')->first();
        if (is_null($listRubberWax)) {
            $data_return = $this->SetReturn(false, "ID TM Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        
        // Check if that item is from pcb ("Having location 'Lilin' and workallocation is null")
        $items = FacadesDB::select("
            SELECT
                A.ID,
                C.ID AS Product,
                C.SW AS NamaProduct,
                D.ID AS IDRubber,
                D.TypeProcess
            FROM
                listrubberwax A
                JOIN listrubberwaxitem B ON A.ID = B.IDM
                JOIN ERP.product C ON B.Product = C.ID
                JOIN ERP.rubber D ON B.IDRubber = D.ID
            WHERE 
                A.ID = $keyword
                AND A.Location = 'Lilin'
                AND B.WorkAllocation IS NULL
        ");
        // If length of items is 0 then that ID is not found or not TM Karet PCB
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false, "TM Item Kosong", null, null);
            return response()->json($data_return, 404);
        }
        $listRubberWax['items'] = $items;
        $data_return = $this->SetReturn(false, "TM Found", $listRubberWax, null);
        return response()->json($data_return, 200);
    }

    public function CetakTMKaretLilin(Request $request){
        $idTMKaretLilin = $request->idTMKaretLilin;
        if (is_null($idTMKaretLilin) or $idTMKaretLilin == "") {
            abort(404);
        }

        // Check if id exists
        $TMKaretLilin = listrubberwax::where('ID',$idTMKaretLilin)->where('Location','Lilin')->first();
        if (is_null($TMKaretLilin)) {
            abort(404);
        }

        // Get items 
        $items = FacadesDB::select("
            SELECT
                A.ID,
                C.ID AS Product,
                C.SW AS NamaProduct,
                D.ID AS IDRubber,
                D.TypeProcess
            FROM
                listrubberwax A
                JOIN listrubberwaxitem B ON A.ID = B.IDM
                JOIN ERP.product C ON B.Product = C.ID
                JOIN ERP.rubber D ON B.IDRubber = D.ID
            WHERE 
                A.ID = $idTMKaretLilin
                AND A.Location = 'Lilin'
                AND B.WorkAllocation IS NULL
        ");
        // If length of items is 0 then that ID is not found or not TM Karet PCB
        if (count($items) == 0) {
            abort(404);
        }
        $dataitems = [];
        foreach ($items as $key => $value) {
            $temp = [];
            $temp['Product'] = $value->Product;
            $temp['NamaProduct'] = $value->NamaProduct;
            $temp['IDRubber'] = $value->IDRubber;
            if ($value->TypeProcess == 27) {
                $temp['jenisPart'] = 'Kepala';
            } else if ($value->TypeProcess == 26) {
                $temp['jenisPart'] = 'Mainan';
            } else if ($value->TypeProcess == 25) {
                $temp['jenisPart'] = 'Component';
            } else {
                $temp['jenisPart'] = 'Component';
            }
            $dataitems[] = $temp;
        }
        $TMKaretLilin['items'] = $dataitems;
        return view('R&D.Percobaan.TMKaretPCBKeLilin.cetak', compact('TMKaretLilin'));
    }

}

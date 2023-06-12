<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
use App\Models\rndnew\listrubberwax;
use App\Models\rndnew\listrubberwaxitem;
// use App\Models\tes_laravel\listrubberwax;
// use App\Models\tes_laravel\listrubberwaxitem;

class PostingKaretPcbController extends Controller{

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

    private function GetItemTM($idTMKaretLilin){
        $items = FacadesDB::select("
            SELECT
                B.WorkAllocation,
                B.ProductFG,
                C.SerialNo,
                C.SKU AS namaProduct,
                D.SW AS productCategory,
                E.TransDate AS TanggalSTP,
                F.Description2 AS Kadar
            FROM
                listrubberwax A
                JOIN listrubberwaxitem B ON A.ID = B.IDM
                JOIN product C ON B.ProductFG = C.ID
                JOIN productcategory D ON C.Model = D.ProductID
                JOIN drafter2d E ON B.ProductFG = E.Product
                JOIN productcarat F ON C.VarCarat = F.ID
            WHERE
                A.ID = '$idTMKaretLilin'
            GROUP BY
                B.ProductFG
            ORDER BY
                B.WorkAllocation
        ");
        return $items;
    }

    private function GetRubberTM($idTMKaretLilin){
        $rubber = FacadesDB::select("
            SELECT
                B.WorkAllocation,
                B.Product,
                B.ProductFG,
                C.ID,
                C.TypeProcess,
                D.SW
            FROM
                listrubberwax A 
                JOIN listrubberwaxitem B ON A.ID = B.IDM
                JOIN ERP.rubber C ON B.IDRubber = C.ID
                JOIN ERP.product D ON C.Product = D.ID
            WHERE
                A.ID = '$idTMKaretLilin'
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
            WHERE
                A.Location = 'Lilin'
            ORDER BY
                A.ID DESC
            LIMIT 10
        ");
        return view('Produksi.Lilin.PostingKaretPcb.index', compact('listhist'));
    }

    private function SearchTMKaretLilinQC($idTMKaretLilinValue){
        $idTMKaretLilin = $idTMKaretLilinValue;
        if (is_null($idTMKaretLilin) or $idTMKaretLilin == "") {
            $data_return = $this->SetReturn(false, "keyword Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        // Check if id exists
        $TMKaretLilin = listrubberwax::where('ID',$idTMKaretLilin)->where('Location','Lilin')->first();
        if (is_null($TMKaretLilin)) {
            $data_return = $this->SetReturn(false, "TM Karet PCB ke Lilin with id '$idTMKaretLilin' Not Found", null, null);
            return response()->json($data_return, 404);
        }

        // Get Items
        $items = $this->GetItemTM($idTMKaretLilin);
        
        // Get rubbers
        $rubber = $this->GetRubberTM($idTMKaretLilin);

        $listWorkAllocation = [];
        $TMItems = [];
        foreach ($items as $key => $value) {
            $temp = [];
            $temp['nthkoqc'] = $value->WorkAllocation;
            if (!in_array($value->WorkAllocation,$listWorkAllocation)) {
                $listWorkAllocation[] = $value->WorkAllocation;
            }
            $temp['idProduct'] = $value->ProductFG;
            $temp['Product'] = $value->namaProduct;
            $temp['bulanSTP'] = date("F Y", strtotime($value->TanggalSTP));
            $listIdProductKepala = [];
            $listIdProductMainan = [];
            $listIdProductComponent = [];
            $listRubberKepala = [];
            $listRubberMainan = [];
            $listRubberComponent = [];
            $listNamaProductKepala = [];
            $listNamaProductMainan = [];
            $listNamaProductComponent = [];
            foreach ($rubber as $keyRubber => $valueRubber) {
                if ($valueRubber->TypeProcess == 27) {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listIdProductKepala[] = $valueRubber->Product;
                        $listRubberKepala[] = $valueRubber->ID;
                        $listNamaProductKepala[] = $valueRubber->SW;
                    }
                } else if ($valueRubber->TypeProcess == 26) {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listIdProductMainan[] = $valueRubber->Product;
                        $listRubberMainan[] = $valueRubber->ID;
                        $listNamaProductMainan[] = $valueRubber->SW;
                    }
                } else if ($valueRubber->TypeProcess == 25) {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listIdProductComponent[] = $valueRubber->Product;
                        $listRubberComponent[] = $valueRubber->ID;
                        $listNamaProductComponent[] = $valueRubber->SW;
                    }
                } else {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listIdProductKepala[] = $valueRubber->Product;
                        $listRubberKepala[] = $valueRubber->ID;
                        $listNamaProductKepala[] = $valueRubber->SW;
                    }
                }
            }
            $temp['idProductKepala'] = $listIdProductKepala;
            $temp['idProductMainan'] = $listIdProductMainan;
            $temp['idProductComponent'] = $listIdProductComponent;
            $temp['rubberKepala'] = $listRubberKepala;
            $temp['rubberMainan'] = $listRubberMainan;
            $temp['rubberComponent'] = $listRubberComponent;
            $temp['namaProductKepala'] = $listNamaProductKepala;
            $temp['namaProductMainan'] = $listNamaProductMainan;
            $temp['namaProductComponent'] = $listNamaProductComponent;
            $TMItems[] = $temp;
        }
        $TMKaretLilin['items'] = $TMItems;
        $TMKaretLilin['WorkAllocations'] = $listWorkAllocation;
        // dd($TMKaretLilin);
        $resultHTML = view('Produksi.Lilin.PostingKaretPcb.layoutTableRubberQc', compact('TMKaretLilin'))->render();
        $data = [
            'result'=>$TMKaretLilin,
            'resultHTML'=>$resultHTML
        ];
        $data_return = $this->SetReturn(true, "TM Karet PCB ke Lilin Found", $data, null);
        return response()->json($data_return, 200);
    }

    public function SearchTMKaretLilinCommon($idTMKaretLilinValue){
        $keyword = $idTMKaretLilinValue;
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
        $listRubberWax['items'] = $dataitems;
        $resultHTML = view('Produksi.Lilin.PostingKaretPcb.layoutTableRubberCommon', compact('listRubberWax'))->render();
        $data = [
            'result'=>$listRubberWax,
            'resultHTML'=>$resultHTML
        ];
        $data_return = $this->SetReturn(true, "TM Karet PCB ke Lilin Found", $data, null);
        return response()->json($data_return, 200);
    }

    public function SearchTMKaretLilin(Request $request){
        $idTMKaretLilin = $request->keyword;
        if (is_null($idTMKaretLilin) or $idTMKaretLilin == "") {
            $data_return = $this->SetReturn(false, "keyword Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        // Check if id exists
        $TMKaretLilin = listrubberwax::where('ID',$idTMKaretLilin)->where('Location','Lilin')->first();
        if (is_null($TMKaretLilin)) {
            $data_return = $this->SetReturn(false, "TM Karet PCB ke Lilin with id '$idTMKaretLilin' Not Found", null, null);
            return response()->json($data_return, 404);
        }

        // Check if listrubberwaxitem have workallocation or not. if it have we will run SearchTMKaretLilinQC Function to get the item. otherwise we will run common Function.
        $TMKaretLilinItem = listrubberwaxitem::where('IDM',$idTMKaretLilin)->first();
        if (!is_null($TMKaretLilinItem->WorkAllocation)) {
            return $this->SearchTMKaretLilinQC($idTMKaretLilin);
        } else {
            return $this->SearchTMKaretLilinCommon($idTMKaretLilin);
        }
    }

    public function PostingTMKaretLilin(Request $request){
        $idTMKaretLilin = $request->idTMKaretLilin;
        if (is_null($idTMKaretLilin) or $idTMKaretLilin == "") {
            $data_return = $this->SetReturn(false, "idTMKaretLilin Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        // Check if id exists
        $TMKaretLilin = listrubberwax::where('ID',$idTMKaretLilin)->where('Location','Lilin')->first();
        if (is_null($TMKaretLilin)) {
            $data_return = $this->SetReturn(false, "TM Karet PCB ke Lilin with id '$idTMKaretLilin' Not Found", null, null);
            return response()->json($data_return, 404);
        }
        // Check if that TM is already posted
        if (!is_null($TMKaretLilin['PostDate'])) {
            $data_return = $this->SetReturn(false, "TM Karet PCB ke Lilin with id '$idTMKaretLilin' Sudah pernah di Posting", null, null);
            return response()->json($data_return, 400);
        }

        // Posting
        $updatelistrubberwax = listrubberwax::where('ID',$idTMKaretLilin)->update([
            "PostDate"=>date("Y-m-d H:i:s")
        ]);
        // Return sukses
        $data_return = $this->SetReturn(true, "TM Karet PCB ke Lilin with id '$idTMKaretLilin' Berhasil di Posting", null, null);
        return response()->json($data_return, 200);
    }
}

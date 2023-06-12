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

class TMKaretQcPCBKeLilinController extends Controller{
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

    private function GetItemCommon($workAllocation){
        $items = FacadesDB::select("
            SELECT
                DISTINCT
                A.WorkAllocation,
                B.Product,
                C.SerialNo,
                C.SKU AS namaProduct,
                D.SW AS productCategory,
                E.TransDate AS TanggalSTP,
                F.Description2 AS Kadar
            FROM
                qc AS A
                JOIN qcitem AS B ON A.ID = B.IDM
                JOIN product AS C on B.Product = C.ID
                JOIN productcategory D ON C.Model = D.ProductID
                JOIN drafter2d E ON C.ID = E.Product
                JOIN productcarat F ON C.VarCarat = F.ID
            WHERE
                A.WorkAllocation = '$workAllocation' 
                AND B.Weight > 0
                AND B.NextProcess = 256
            UNION
            SELECT
                DISTINCT
                A.WorkAllocation,
                B.Product,
                C.SerialNo,
                C.SKU AS namaProduct,
                D.SW AS productCategory,
                E.TransDate AS TanggalSTP,
                F.Description2 AS Kadar
            FROM
                enamel AS A
                JOIN enamelitem AS B ON A.ID = B.IDM
                JOIN product AS C on B.Product = C.ID
                JOIN productcategory D ON C.Model = D.ProductID
                JOIN drafter2d E ON C.ID = E.Product
                JOIN productcarat F ON C.VarCarat = F.ID
            WHERE
                A.WorkAllocation = '$workAllocation'
                AND B.Weight > 0
                AND B.NextProcess = 256
            ORDER BY
                Product
        ");
        return $items;
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

    // private function GetRubberCommon($workAllocation){
    //     $rubber = FacadesDB::select("
    //         SELECT
    //             B.Product,
    //             p.SerialNo,
    //             RA.ID AS RubberKepala,
    //             RB.ID AS RubberMainan,
    //             RC.ID AS RubberComponent,
    //             RA.Product AS ProductRubberKepala,
    //             RB.Product AS ProductRubberMainan,
    //             RC.Product AS ProductRubberComponent,
    //             PA.SW AS NamaProductKepala,
    //             PB.SW AS NamaProductMainan,
    //             PC.SW AS NamaProductComponent
    //         FROM
    //             qc AS A
    //             JOIN qcitem AS B ON A.ID = B.IDM
    //             JOIN product p ON B.Product = p.ID
    //             LEFT JOIN productkepala C ON B.Product = C.IDM
    //             LEFT JOIN productmn D ON B.Product = D.IDM
    //             LEFT JOIN productcomponent E ON B.Product = E.IDM
    //             LEFT JOIN product PA ON C.Kepala = PA.LinkID AND PA.TypeProcess = 27 AND PA.Model != 6 AND PA.Model != 12
    //             LEFT JOIN product PB ON D.Mainan = PB.LinkID AND PB.TypeProcess = 26
    //             LEFT JOIN product PC ON E.Component = PC.LinkID AND PC.TypeProcess = 25 AND PC.Model != 56 AND PC.Model != 55
    //             LEFT JOIN rubber RA ON PA.ID = RA.Product
    //             LEFT JOIN rubber RB ON PB.ID = RB.Product
    //             LEFT JOIN rubber RC ON PC.ID = RC.Product
    //         WHERE
    //             A.WorkAllocation = '$workAllocation'
    //             AND B.Weight > 0
    //             AND B.NextProcess = 256
    //             AND RA.UnUsedDate IS NULL 
    //             AND RB.UnUsedDate IS NULL 
    //             AND RC.UnUsedDate IS NULL
    //         GROUP BY
    //             B.Product          
    //     ");
    //     return $rubber;
    // }

    private function GetRubberCommon2($idProduct){
        $rubber = FacadesDB::connection('erp')->select("
            SELECT
                A.IDM,
                PA.ID AS ProductRubber,
                PA.SKU AS NamaProductRubber,
                RA.ID AS Rubber,
                PA.TypeProcess
            FROM
                productpart A
                LEFT JOIN product PA ON A.Product = PA.ID AND PA.Model NOT IN ( 6, 12, 55, 56 )
                LEFT JOIN rubber RA ON PA.ID = RA.Product
            WHERE
                A.IDM = '$idProduct'
                AND RA.UnUsedDate IS NULL
        ");
        return $rubber;
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
                JOIN rubber C ON B.IDRubber = C.ID
                JOIN product D ON C.Product = D.ID
            WHERE
                A.ID = '$idTMKaretLilin'
        ");
        return $rubber;
    }
    // END REUSABLE FUNCTION

    public function Index(){
        // History
        $listhist = FacadesDB::select("
            SELECT
                A.IDM 
            FROM
                listrubberwaxitem A 
            WHERE
                A.WorkAllocation IS NOT NULL
            GROUP BY
                A.IDM
            ORDER BY
                A.IDM DESC
        ");
        return view('R&D.Percobaan.TMKaretQcPCBKeLilin.Index2',compact('listhist'));
    }

    // // Cara lama yang query ke productkepala, productmn, productcomponent
    // public function GetWorkAllocation3(Request $request){
    //     // Get workAllocation from input
    //     $workAllocation = $request->workAllocation;
    //     // Get Items
    //     $items = $this->GetItemCommon($workAllocation);
    //     if (count($items) == 0) {
    //         $data_return = $this->SetReturn(false, "QC WorkAllocation Not Found", null, null);
    //         return response()->json($data_return, 404);
    //     }

    //     // Get rubber
    //     $rubber = $this->GetRubberCommon($workAllocation);

    //     $productItems = [];
    //     // Transform items from object to dictionary
    //     foreach ($items as $key => $value) {
    //         $temp = [];
    //         $temp['nthkoqc'] = $workAllocation;
    //         $temp['idProduct'] = $value->Product;
    //         $temp['Product'] = $value->namaProduct;
    //         $temp['bulanSTP'] = date("F Y", strtotime($value->TanggalSTP));
    //         // Transform rubber
    //         $listRubberKepala = [];
    //         $listRubberMainan = [];
    //         $listRubberComponent = [];
    //         $listNamaProductKepala = [];
    //         $listNamaProductMainan = [];
    //         $listNamaProductComponent = [];
    //         foreach ($rubber as $keyRubber => $valueRubber) {
    //             if ($value->Product == $valueRubber->Product) {
    //                 if ($valueRubber->RubberKepala != null) {
    //                     $listRubberKepala[] = $valueRubber->RubberKepala;
    //                     $listNamaProductKepala[] = $valueRubber->NamaProductKepala;
    //                 }
    //                 if ($valueRubber->RubberMainan != null) {
    //                     $listRubberMainan[] = $valueRubber->RubberMainan;
    //                     $listNamaProductMainan[] = $valueRubber->NamaProductMainan;
    //                 }
    //                 if ($valueRubber->RubberComponent != null) {
    //                     $listRubberComponent[] = $valueRubber->RubberComponent;
    //                     $listNamaProductComponent[] = $valueRubber->NamaProductComponent;
    //                 }
    //             }
    //         }
    //         $temp['rubberKepala'] = $listRubberKepala;
    //         $temp['rubberMainan'] = $listRubberMainan;
    //         $temp['rubberComponent'] = $listRubberComponent;
    //         $temp['namaProductKepala'] = $listNamaProductKepala;
    //         $temp['namaProductMainan'] = $listNamaProductMainan;
    //         $temp['namaProductComponent'] = $listNamaProductComponent;
    //         array_push($productItems,$temp);
    //     }
    //     $data_return = $this->SetReturn(true, "Get Item WorkAllocation Success", $productItems, null);
    //     return response()->json($data_return, 200);
    // }

    // Cara baru yang query ke productpart
    public function GetWorkAllocation4(Request $request){
        $workAllocation = $request->workAllocation;
        $items = $this->GetItemCommon($workAllocation);
        if (count($items) == 0) {
            $data_return = $this->SetReturn(false, "QC WorkAllocation Not Found", null, null);
            return response()->json($data_return, 404);
        }
        $listItem = [];
        foreach ($items as $key => $value) {
            $temp = [];
            $idProduct = $value->Product;
            $temp['nthkoqc'] = $workAllocation;
            $temp['idProduct'] = $value->Product;
            $temp['Product'] = $value->namaProduct;
            $temp['bulanSTP'] = date("F Y", strtotime($value->TanggalSTP));

            // Get Rubber
            $rubber = $this->GetRubberCommon2($idProduct);
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
                    if (!is_null($valueRubber->Rubber)) {
                        $listIdProductKepala[] = $valueRubber->ProductRubber;
                        $listRubberKepala[] = $valueRubber->Rubber;
                        $listNamaProductKepala[] = $valueRubber->NamaProductRubber;
                    }
                } else if ($valueRubber->TypeProcess == 26) {
                    if (!is_null($valueRubber->Rubber)) {
                        $listIdProductMainan[] = $valueRubber->ProductRubber;
                        $listRubberMainan[] = $valueRubber->Rubber;
                        $listNamaProductMainan[] = $valueRubber->NamaProductRubber;
                    }
                } else if ($valueRubber->TypeProcess == 25) {
                    if (!is_null($valueRubber->Rubber)) {
                        $listIdProductComponent[] = $valueRubber->ProductRubber;
                        $listRubberComponent[] = $valueRubber->Rubber;
                        $listNamaProductComponent[] = $valueRubber->NamaProductRubber;
                    }
                } else {
                    if (!is_null($valueRubber->Rubber)) {
                        $listIdProductKepala[] = $valueRubber->ProductRubber;
                        $listRubberKepala[] = $valueRubber->Rubber;
                        $listNamaProductKepala[] = $valueRubber->NamaProductRubber;
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
            array_push($listItem,$temp);
        }
        $data_return = $this->SetReturn(true, "Get Item WorkAllocation Success", $listItem, null);
        return response()->json($data_return, 200);
    }

    public function SaveTMKaretLilin(Request $request){
        // Get workAllocation from input
        $workAllocations = $request->workAllocations;
        
        // Check workAllocations
        if (is_null($workAllocations) or !is_array($workAllocations)) {
            $data_return = [
                "success"=>false,
                "message"=>"Create TM Karet Lilin Failed",
                "data"=>null,
                "error"=>[
                    "workAllocations"=>"workAllocations Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check workAllocations length
        if (count($workAllocations) == 0) {
            $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed", null, ["workAllocations"=>"workAllocations Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        
        // Loop workallocation to check if that workallocation is already exists in listrubberwaxitem
        foreach ($workAllocations as $key => $value) {
            $cekListRubberWaxItem = listrubberwaxitem::where('WorkAllocation',$value)->first();
            if (!is_null($cekListRubberWaxItem)) {
                $data_return = $this->SetReturn(false,"Create TM Karet Lilin Failed. NTHKO QC '$value' already exists", null, null);
                return response()->json($data_return,400);
            }
        }

        // Loop for check if that workallocation exists
        foreach ($workAllocations as $key => $value) {
            // Get Items
            $items = $this->GetItemCommon($value);
            if (count($items) == 0) {
                $data_return = $this->SetReturn(false, "QC WorkAllocation '$value' Not Found", null, null);
                return response()->json($data_return, 404);
            }
        }

        $listWorkAllocation = $workAllocations;

        // GetEmployee
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];

        // Get lastid
        $lastid = lastid::where('Module','listrubberwax')->first();
        $lastid = $lastid->Last+1;

         // Update Lastid
        $updatelastid = lastid::where('Module','listrubberwax')
        ->update([
            "Last"=>$lastid
        ]);

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
        // Loop for inserting to items
        foreach ($listWorkAllocation as $key => $valueWorkAllocation) {
            // Get Items
            $items = $this->GetItemCommon($valueWorkAllocation);
            // Loop items for getting rubbers
            foreach ($items as $keyItem => $valueItem) {
                // Get Rubbers
                $rubber = $this->GetRubberCommon2($valueItem->Product);
                // loop Rubbers and insert to listrubberwaxitem
                foreach ($rubber as $keyRubber => $valueRubber) {
                    if (!is_null($valueRubber->Rubber)) {
                        $insertListRubberWaxItem = listrubberwaxitem::create([
                            "IDM"=>$insertListRubberWax->id,
                            "Ordinal"=>$ordinal,
                            "Product"=>$valueRubber->ProductRubber,
                            "IDRubber"=>$valueRubber->Rubber,
                            "Active"=>"A",
                            "ProductFG"=>$valueRubber->IDM,
                            "WorkAllocation"=>$valueWorkAllocation
                        ]);
                        $ordinal+=1;
                    }
                }
            }
        }

        $data_return = $this->SetReturn(true, "Save TM Karet PCB ke Lilin Success", ['idTMKaretLilin'=>$insertListRubberWax->id], null);
        return response()->json($data_return, 200);
    }

    public function SaveTMKaretLilin2(Request $request){
        $noNthkoQcs = $request->noNthkoQcs;
        $idProducts = $request->idProducts;
        $idRubbers = $request->idRubbers;
        $idProductRubbers = $request->idProductRubbers;

        // Check noNthkoQcs
        if (is_null($noNthkoQcs) or !is_array($noNthkoQcs) or count($noNthkoQcs) == 0) {
            $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed.", null, ["noNthkoQcs"=>"noNthkoQcs Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check idProducts
        if (is_null($idProducts) or !is_array($idProducts) or count($idProducts) == 0) {
            $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed.", null, ["idProducts"=>"idProducts Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check idRubbers
        if (is_null($idRubbers) or !is_array($idRubbers) or count($idRubbers) == 0) {
            $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed.", null, ["idRubbers"=>"idRubbers Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check idProductRubbers
        if (is_null($idProductRubbers) or !is_array($idProductRubbers) or count($idProductRubbers) == 0) {
            $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed.", null, ["idProductRubbers"=>"idProductRubbers Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Loop for checking
        for ($i=0; $i < count($noNthkoQcs); $i++) { 
            $idProductCek = $idProducts[$i];
            $noNthkoQcsCek = $noNthkoQcs[$i];
            $idRubbersCek = $idRubbers[$i];
            $idProductRubbersCek = $idProductRubbers[$i];
            // Check NTHKO with idProduct exists in qcitems
            $cek = FacadesDB::select("
                SELECT
                    A.IDM,
                    A.Product,
                    A.WorkAllocation 
                FROM
                    qcitem A
                WHERE
                    A.Product = '$idProductCek'
                    AND A.WorkAllocation = '$noNthkoQcsCek'
                GROUP BY
                    A.Product
            ");
            if (count($cek) == 0) {
                $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed. Data NTHKO '$noNthkoQcsCek' dengan Produk '$idProductCek' Tidak Sama ", null, null);
                return response()->json($data_return,400);
            }

            // Check idRubber with idProductRubbers is suitable
            $cek = FacadesDB::select("
                SELECT
                    A.ID,
                    A.Product
                FROM
                    rubber A
                WHERE
                    A.ID = '$idRubbersCek'
                    AND A.Product = '$idProductRubbersCek'
            ");
            if (count($cek) == 0) {
                $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed. Data idRubber '$idRubbersCek' dengan Produk '$idProductRubbersCek' Tidak Sama ", null, null);
                return response()->json($data_return,400);
            }

            // Check if idRubber with that nthko exists. if exists than it will return failed to insert
            $cek = listrubberwaxitem::where('IDRubber',$idRubbersCek)->where('WorkAllocation', $noNthkoQcsCek)->first();
            if (!is_null($cek)) {
                $data_return = $this->SetReturn(false,"Create TM Karet QC PCB ke Lilin Failed. Data idRubber '$idRubbersCek' dengan NTHKO '$noNthkoQcsCek' Sudah Pernah di TM ", null, null);
                return response()->json($data_return,400);
            }
        }

        // --------------------------------- CHECKING SUCCESS --------------------------------- //
        // GetEmployee
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];

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

        // Loop to insert ListRubberWaxItem
        $ordinal = 1;
        for ($i=0; $i < count($noNthkoQcs); $i++) { 
            $idProduct = $idProducts[$i];
            $noNthkoQc = $noNthkoQcs[$i];
            $idRubber = $idRubbers[$i];
            $idProductRubber = $idProductRubbers[$i];
            $insertListRubberWaxItem = listrubberwaxitem::create([
                "IDM"=>$insertListRubberWax->id,
                "Ordinal"=>$ordinal,
                "Product"=>$idProductRubber,
                "IDRubber"=>$idRubber,
                "Active"=>"A",
                "ProductFG"=>$idProduct,
                "WorkAllocation"=>$noNthkoQc
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

    public function UpdateTMKaretLilin2(Request $request){
        $noNthkoQcs = $request->noNthkoQcs;
        $idProducts = $request->idProducts;
        $idRubbers = $request->idRubbers;
        $idProductRubbers = $request->idProductRubbers;
        $idTMKaretLilin = $request->idTMKaretLilin;

        if (is_null($idTMKaretLilin) or $idTMKaretLilin == "") {
            $data_return = $this->SetReturn(false, "idTMKaretLilin cant be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check noNthkoQcs
        if (is_null($noNthkoQcs) or !is_array($noNthkoQcs) or count($noNthkoQcs) == 0) {
            $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed.", null, ["noNthkoQcs"=>"noNthkoQcs Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check idProducts
        if (is_null($idProducts) or !is_array($idProducts) or count($idProducts) == 0) {
            $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed.", null, ["idProducts"=>"idProducts Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check idRubbers
        if (is_null($idRubbers) or !is_array($idRubbers) or count($idRubbers) == 0) {
            $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed.", null, ["idRubbers"=>"idRubbers Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }
        // Check idProductRubbers
        if (is_null($idProductRubbers) or !is_array($idProductRubbers) or count($idProductRubbers) == 0) {
            $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed.", null, ["idProductRubbers"=>"idProductRubbers Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Check if idTMKaretLilin is exists
        $TMKaretLilin = listrubberwax::where('ID',$idTMKaretLilin)->first();
        if (is_null($TMKaretLilin)) {
            $data_return = $this->SetReturn(false, "TM Karet Lilin Not Found", null, null);
            return response()->json($data_return, 404);
        }

        // Loop for checking
        for ($i=0; $i < count($noNthkoQcs); $i++) { 
            $idProductCek = $idProducts[$i];
            $noNthkoQcsCek = $noNthkoQcs[$i];
            $idRubbersCek = $idRubbers[$i];
            $idProductRubbersCek = $idProductRubbers[$i];
            // Check NTHKO with idProduct exists in qcitems
            $cek = FacadesDB::select("
                SELECT
                    A.IDM,
                    A.Product,
                    A.WorkAllocation 
                FROM
                    qcitem A
                WHERE
                    A.Product = '$idProductCek'
                    AND A.WorkAllocation = '$noNthkoQcsCek'
                GROUP BY
                    A.Product
            ");
            if (count($cek) == 0) {
                $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed. Data NTHKO '$noNthkoQcsCek' dengan Produk '$idProductCek' Tidak Sama ", null, null);
                return response()->json($data_return,400);
            }

            // Check idRubber with idProductRubbers is suitable
            $cek = FacadesDB::select("
                SELECT
                    A.ID,
                    A.Product
                FROM
                    rubber A
                WHERE
                    A.ID = '$idRubbersCek'
                    AND A.Product = '$idProductRubbersCek'
            ");
            if (count($cek) == 0) {
                $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed. Data idRubber '$idRubbersCek' dengan Produk '$idProductRubbersCek' Tidak Sama ", null, null);
                return response()->json($data_return,400);
            }

            // Check if idRubber with that nthko exists. if exists than it will return failed to insert
            $cek = listrubberwaxitem::where('IDRubber',$idRubbersCek)->where('WorkAllocation', $noNthkoQcsCek)->first();
            if (!is_null($cek)) {
                if ($cek->IDM != $idTMKaretLilin) {
                    $data_return = $this->SetReturn(false,"Update TM Karet QC PCB ke Lilin Failed. Data idRubber '$idRubbersCek' dengan NTHKO '$noNthkoQcsCek' Sudah Pernah di TM ", null, null);
                    return response()->json($data_return,400);
                }
            }
        }

        // --------------------------------- CHECKING SUCCESS --------------------------------- //
        // GetEmployee
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];

        // update to ListRubberWax
        $updatelistrubberwax = listrubberwax::where('ID',$idTMKaretLilin)->update([
            "UserName"=>Auth::user()->name,
            "Remarks"=>"",
            "Employee"=>$employee->ID,
            "TransDate"=>date("Y-m-d"),
            "Location"=>"Lilin"
        ]);

        // Delete listrubberwaxitem
        $deletelistrubberwaxitem = listrubberwaxitem::where('IDM',$idTMKaretLilin)->delete();

        // Loop to insert ListRubberWaxItem
        $ordinal = 1;
        for ($i=0; $i < count($noNthkoQcs); $i++) { 
            $idProduct = $idProducts[$i];
            $noNthkoQc = $noNthkoQcs[$i];
            $idRubber = $idRubbers[$i];
            $idProductRubber = $idProductRubbers[$i];
            $insertListRubberWaxItem = listrubberwaxitem::create([
                "IDM"=>$idTMKaretLilin,
                "Ordinal"=>$ordinal,
                "Product"=>$idProductRubber,
                "IDRubber"=>$idRubber,
                "Active"=>"A",
                "ProductFG"=>$idProduct,
                "WorkAllocation"=>$noNthkoQc
            ]);
            $ordinal+=1;
        }

        $data_return = $this->SetReturn(true, "Update TM Karet PCB ke Lilin Success", ['idTMKaretLilin'=>$idTMKaretLilin], null);
        return response()->json($data_return, 200);

    }

    public function UpdateTMKaretLilin(Request $request){
        // Get workAllocations and idTMKaretLilin from input
        $workAllocations = $request->workAllocations;
        $idTMKaretLilin = $request->idTMKaretLilin;

        if (is_null($idTMKaretLilin) or $idTMKaretLilin == "") {
            $data_return = $this->SetReturn(false, "idTMKaretLilin cant be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check workAllocations
        if (is_null($workAllocations) or !is_array($workAllocations)) {
            $data_return = [
                "success"=>false,
                "message"=>"Update TM Karet Lilin Failed",
                "data"=>null,
                "error"=>[
                    "workAllocations"=>"workAllocations Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check workAllocations length
        if (count($workAllocations) == 0) {
            $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed", null, ["workAllocations"=>"workAllocations Parameters can't be null or blank"]);
            return response()->json($data_return,400);
        }

        // Loop for check if that workallocation exists
        foreach ($workAllocations as $key => $value) {
            // Get Items
            $items = $this->GetItemCommon($value);
            if (count($items) == 0) {
                $data_return = $this->SetReturn(false, "QC WorkAllocation '$value' Not Found", null, null);
                return response()->json($data_return, 404);
            }
        }

        // Check if idTMKaretLilin is exists
        $TMKaretLilin = listrubberwax::where('ID',$idTMKaretLilin)->first();
        if (is_null($TMKaretLilin)) {
            $data_return = $this->SetReturn(false, "TM Karet Lilin Not Found", null, null);
            return response()->json($data_return, 404);
        }

        // Loop workallocation to check if that workallocation is already exists in listrubberwaxitem
        foreach ($workAllocations as $key => $value) {
            $cekListRubberWaxItem = listrubberwaxitem::where('WorkAllocation',$value)->first();
            if (!is_null($cekListRubberWaxItem)) {
                // Jika IDM sama dengan inputan maka tidak masalah. namun jika beda, Berarti no NTHKO Tersebut sudah pernah di TM dengan ID yang lain
                if ($cekListRubberWaxItem->IDM != $idTMKaretLilin) {
                    $data_return = $this->SetReturn(false,"Update TM Karet Lilin Failed. NTHKO QC '$value' Sudah Pernah di TM", null, null);
                    return response()->json($data_return,400);
                }
            }
        }

        // GetEmployee
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];

        // update to ListRubberWax
        $updatelistrubberwax = listrubberwax::where('ID',$idTMKaretLilin)->update([
            "UserName"=>Auth::user()->name,
            "Remarks"=>"",
            "Employee"=>$employee->ID,
            "TransDate"=>date("Y-m-d"),
            "Location"=>"Lilin"
        ]);

        // Delete listrubberwaxitem
        $deletelistrubberwaxitem = listrubberwaxitem::where('IDM',$idTMKaretLilin)->delete();

        $ordinal = 1;
        // Loop for inserting to items
        foreach ($workAllocations as $key => $valueWorkAllocation) {
            // Get Items
            $items = $this->GetItemCommon($valueWorkAllocation);
            
            // Loop items for getting rubbers
            foreach ($items as $keyItem => $valueItem) {
                // Get Rubbers
                $rubber = $this->GetRubberCommon2($valueItem->Product);
                // loop Rubbers and insert to listrubberwaxitem
                foreach ($rubber as $keyRubber => $valueRubber) {
                    if (!is_null($valueRubber->Rubber)) {
                        $insertListRubberWaxItem = listrubberwaxitem::create([
                            "IDM"=>$idTMKaretLilin,
                            "Ordinal"=>$ordinal,
                            "Product"=>$valueRubber->ProductRubber,
                            "IDRubber"=>$valueRubber->Rubber,
                            "Active"=>"A",
                            "ProductFG"=>$valueRubber->IDM,
                            "WorkAllocation"=>$valueWorkAllocation
                        ]);
                        $ordinal+=1;
                    }
                }
            }
        }
        $data_return = $this->SetReturn(true, "Update TM Karet PCB ke Lilin Success", ['idTMKaretLilin'=>$idTMKaretLilin], null);
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
        $data_return = $this->SetReturn(true, "TM Karet PCB ke Lilin Found", $TMKaretLilin, null);
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

        // Get Items
        $items = $this->GetItemTM($idTMKaretLilin);
        
        // Get rubbers
        $rubber = $this->GetRubberTM($idTMKaretLilin);
        $TotalRubber = count($rubber);

        $TMItems = [];
        foreach ($items as $key => $value) {
            $temp = [];
            $temp['nthkoqc'] = $value->WorkAllocation;
            $temp['idProduct'] = $value->ProductFG;
            $temp['Product'] = $value->namaProduct;
            $temp['bulanSTP'] = date("F Y", strtotime($value->TanggalSTP));
            $listRubberKepala = [];
            $listRubberMainan = [];
            $listRubberComponent = [];
            $listNamaProductKepala = [];
            $listNamaProductMainan = [];
            $listNamaProductComponent = [];
            foreach ($rubber as $keyRubber => $valueRubber) {
                if ($valueRubber->TypeProcess == 27) {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listRubberKepala[] = $valueRubber->ID;
                        $listNamaProductKepala[] = $valueRubber->SW;
                    }
                } else if ($valueRubber->TypeProcess == 26) {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listRubberMainan[] = $valueRubber->ID;
                        $listNamaProductMainan[] = $valueRubber->SW;
                    }
                } else if ($valueRubber->TypeProcess == 25) {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listRubberComponent[] = $valueRubber->ID;
                        $listNamaProductComponent[] = $valueRubber->SW;
                    }
                } else {
                    if ($value->ProductFG == $valueRubber->ProductFG and $value->WorkAllocation == $valueRubber->WorkAllocation) {
                        $listRubberKepala[] = $valueRubber->ID;
                        $listNamaProductKepala[] = $valueRubber->SW;
                    }
                }
            }
            $temp['rubberKepala'] = $listRubberKepala;
            $temp['rubberMainan'] = $listRubberMainan;
            $temp['rubberComponent'] = $listRubberComponent;
            $temp['namaProductKepala'] = $listNamaProductKepala;
            $temp['namaProductMainan'] = $listNamaProductMainan;
            $temp['namaProductComponent'] = $listNamaProductComponent;
            $TMItems[] = $temp;
        }
        // Next here
        return view('R&D.Percobaan.TMKaretQcPCBKeLilin.cetak',compact('TMItems','TMKaretLilin','TotalRubber'));
    }

    public function Information(){
        $data = FacadesDB::select("
            SELECT
                A.PostDate,
                B.IDM,
                B.IDRubber,
                PR.SKU AS ProductKaretSKU,
                PR.SW AS ProductKaretSW,
                PFG.SKU AS Product,
                B.WorkAllocation
            FROM
                listrubberwax A
                LEFT JOIN listrubberwaxitem B ON A.ID = B.IDM
                LEFT JOIN product PR ON B.Product = PR.ID
                LEFT JOIN product PFG ON B.ProductFG = PFG.ID
            WHERE 
                A.PostDate IS NOT null
        ");
        return view('R&D.Percobaan.TMKaretQcPCBKeLilin.information',compact('data'));
    }
}

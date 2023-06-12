<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
use App\Models\rndnew\grafisworklist;
use App\Models\erp\workcompletion;
use App\Models\erp\workcompletionitem;

class WipGrafisController extends Controller{
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

    private function FindNTHKO($noNTHKO){
        // Get NTHKO
        $getNTHKO = workcompletion::where('WorkAllocation',$noNTHKO)->where('Operation',99)->first();
        if (is_null($getNTHKO)) {
            $data_return = $this->SetReturn(false, "NTHKO dengan nomor '$noNTHKO' Tidak Ditemukan ", null, null);
            return $data_return;
        }
        // Get Items
        $getNTHKOItems = FacadesDB::connection('erp')
        ->select("
            SELECT
                B.*,
                D.SW AS nomorSPK,
                C.SW AS namaProduct,
                E.Description AS kadar,
                B.Qty AS jumlah,
                B.Weight AS berat,
                C.Photo
            FROM
                workcompletion A
                JOIN workcompletionitem B ON A.ID = B.IDM
                JOIN product C ON B.FG = C.ID
                JOIN workorder D ON B.WorkOrder = D.ID
                JOIN productcarat E ON B.Carat = E.ID
            WHERE
                A.WorkAllocation = '$noNTHKO'
                AND A.Operation = 99
                AND B.Product = 5632
        ");
        if (count($getNTHKOItems) == 0) {
            $data_return = $this->SetReturn(false, "NTHKO dengan nomor '$noNTHKO' Bukan Barang Siap Grafis ", null, null);
            return $data_return;
        }
        $getNTHKO['nthkoItems'] = $getNTHKOItems;
        $getNTHKO['Qty'] = count($getNTHKOItems);
        $getNTHKO['Weight'] = workcompletionitem::where('IDM',$getNTHKO['ID'])->where('Product',5632)->sum('Weight');
        $resultHTML = view('R&D.Percobaan.WipGrafis.layoutTableItem', compact('getNTHKO'))->render();
        $data = [
            "result"=>$getNTHKO,
            "resultHTML"=>$resultHTML
        ];
        $data_return = $this->SetReturn(true, "noNTHKO found", $data, null);
        return $data_return;
    }
    // END REUSABLE FUNCTION

    public function Index(Request $request){
        $history = FacadesDB::select("
            SELECT
                WorkAllocation 
            FROM
                grafisworklist
            WHERE
                TransferID IS NULL
            GROUP BY
                WorkAllocation
            ORDER BY
                ID DESC
            LIMIT 10
        ");
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');
        return view('R&D.Percobaan.WipGrafis.Index',compact('history'));
    }

    public function GetNTHKOVarp(Request $request){
        $noNTHKO = $request->noNTHKO;
        if (is_null($noNTHKO) or $noNTHKO == "") {
            $data_return = $this->SetReturn(false, "noNTHKO can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Get NTHKO
        $getNTHKO = $this->FindNTHKO($noNTHKO);
        if (!$getNTHKO['success']) {
            return response()->json($getNTHKO, 404);
        }
        return response()->json($getNTHKO, 200);
    }

    public function SaveWIPGrafis(Request $request){
        $noNTHKO = $request->noNTHKO;
        if (is_null($noNTHKO) or $noNTHKO == "") {
            $data_return = $this->SetReturn(false, "noNTHKO can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Get NTHKO
        $getNTHKO = $this->FindNTHKO($noNTHKO);
        if (!$getNTHKO['success']) {
            return response()->json($getNTHKO, 404);
        }

        // Check if that noNTHKO is in grafisworklist
        $cekGrafisWorklist = grafisworklist::where('WorkAllocation',$noNTHKO)->first();
        if (!is_null($cekGrafisWorklist)) {
            $data_return = $this->SetReturn(false, "noNTHKO Tersebut sudah pernah di buat WIP Grafis", null, null);
            return response()->json($data_return, 400);
        }
        
        // fixed data
        $fixed_data = [];

        // loop getNTHKO data result nthkoItems for check if each product is in grafisworklist
        foreach ($getNTHKO['data']['result']->nthkoItems  as $key => $value) {
            $probablyDone = false;
            $grafisWorkList = grafisworklist::where('Product',$value->FG)->get();
            if (count($grafisWorkList) > 1) {
                $probablyDone = true;
            }
            $_data = [
                "ProbablyDone"=>$probablyDone,
                "Product"=>$value->FG,
                "WorkAllocation"=>$getNTHKO['data']['result']['WorkAllocation'],
                "LinkOrd"=>$value->Ordinal,
                "TreeID"=>$value->TreeID,
                "TreeOrd"=>$value->TreeOrd,
                "LinkFreq"=>$getNTHKO['data']['result']['Freq'],
                "Weight"=>$value->Weight,
                "Qty"=>$value->Qty,
                "Carat"=>$value->Carat  
            ];
            $fixed_data[] = $_data;
        }
        
        // insert Grafisworklist
        foreach ($fixed_data as $key => $value) {
            // insert grafisworklist
            $insertGrafisWorklist = grafisworklist::create([
                "UserName"=>Auth::user()->name,
                "TransDate"=>date("Y-m-d"),
                "Product"=>$value['Product'],
                "WorkAllocation"=>$value['WorkAllocation'],
                "LinkOrd"=>$value['LinkOrd'],
                "TreeID"=>$value['TreeID'],
                "TreeOrd"=>$value['TreeOrd'],
                "LinkFreq"=>$value['LinkFreq'],
                "Weight"=>$value['Weight'],
                "Qty"=>$value['Qty'],
                "Carat"=>$value['Carat'],
                "Status"=>"Baru",
                "Progress"=>6,
                "Active"=>"A",
                "ProbablyDone"=>$value['ProbablyDone']
            ]);
        }
        $data_return = $this->SetReturn(true, "noNTHKO '$noNTHKO' Berhasil Dibuat WIP", $getNTHKO['data'], null);
        return response()->json($data_return, 200);
    }

    public function SearchWIPGrafis(Request $request){
        $noNTHKO = $request->noNTHKO;
        if (is_null($noNTHKO) or $noNTHKO == "") {
            $data_return = $this->SetReturn(false, "noNTHKO can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check if noNTHKO tersebut sudah ada di grafisworklist
        $grafisWorkList = grafisworklist::where('WorkAllocation', $noNTHKO)->first();
        if (is_null($grafisWorkList)) {
            $data_return = $this->SetReturn(false, "WIP Grafis Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }

        // Get NTHKO
        $getNTHKO = workcompletion::where('WorkAllocation',$noNTHKO)->first();
        if (is_null($getNTHKO)) {
            $data_return = $this->SetReturn(false, "NTHKO dengan nomor '$noNTHKO' Tidak Ditemukan ", null, null);
            return response()->json($data_return, 404);
        }

        // Get NTHKOItems
        $getNTHKOItems = FacadesDB::connection('erp')->select("
            SELECT
                D.SW AS nomorSPK,
                C.SW AS namaProduct,
                E.Description AS kadar,
                B.Qty AS jumlah,
                B.Weight AS berat,
                C.Photo 
            FROM
                rndnew.grafisworklist gw
                JOIN workcompletion A ON gw.WorkAllocation = A.WorkAllocation
                JOIN workcompletionitem B ON A.ID = B.IDM AND gw.LinkOrd = B.Ordinal
                JOIN product C ON B.FG = C.ID
                JOIN workorder D ON B.WorkOrder = D.ID
                JOIN productcarat E ON B.Carat = E.ID 
            WHERE
                A.WorkAllocation = '$noNTHKO'
        ");
        $jumlahItem = 0;
        $totalBeratItem = 0;
        foreach ($getNTHKOItems as $key => $value) {
            $jumlahItem += $value->jumlah;
            $totalBeratItem += $value->berat;
        }
        $getNTHKO['nthkoItems'] = $getNTHKOItems;
        $getNTHKO['Qty'] = $jumlahItem;
        $getNTHKO['Weight'] = $totalBeratItem;
        $resultHTML = view('R&D.Percobaan.WipGrafis.layoutTableItem', compact('getNTHKO'))->render();
        $data = [
            "result"=>$getNTHKO,
            "resultHTML"=>$resultHTML
        ];
        $data_return = $this->SetReturn(true, "noNTHKO found", $data, null);
        return response()->json($data_return, 200);
    }

    public function CetakWIPGrafis(Request $request){
        $noNTHKO = $request->workAllocation;
        if (is_null($noNTHKO) or $noNTHKO == "") {
            return abort(404);
        }

        // Check if noNTHKO tersebut sudah ada di grafisworklist
        $grafisWorkList = grafisworklist::where('WorkAllocation', $noNTHKO)->first();
        if (is_null($grafisWorkList)) {
            return abort(404);
        }

        $datenow = $grafisWorkList->TransDate;
        $noNTHKO = $grafisWorkList->WorkAllocation;
        $wipItems = FacadesDB::select("
            SELECT
                B.Photo,
                D.ID AS idKadar,
                D.SW AS kadar,
                B.SerialNo,
                E.SW AS productCategory,
                B.SKU,
                B.Description AS productDescription,
                C.LinkOrd AS variasi,
                A.Weight AS berat
            FROM
                grafisworklist A
                JOIN product B ON A.Product = B.ID
                JOIN erp.productcarat D ON B.VarCarat = D.ID
                JOIN erp.productcategory E ON B.Model = E.ProductID
                LEFT JOIN waxtreeitem C ON A.TreeID = C.IDM AND A.TreeOrd = C.Ordinal
            WHERE 
                A.WorkAllocation = '$noNTHKO'
        ");
        $jumlahItem = count($wipItems);
        $totalBeratItem = 0;
        foreach ($wipItems as $key => $value) {
            $totalBeratItem += $value->berat;
        }
        return view('R&D.Percobaan.WipGrafis.cetak',compact('datenow','noNTHKO','jumlahItem','totalBeratItem','wipItems'));
    }
}

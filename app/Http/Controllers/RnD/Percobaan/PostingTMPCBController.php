<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Public Function
use App\Http\Controllers\Public_Function_Controller;

// Models
use App\Models\rndnew\grafisworklist;

class PostingTMPCBController extends Controller{
    // Setup Public Function
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

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

    private function FindTM($idTM){
        $getTM = FacadesDB::connection('erp')
        ->select("
            SELECT
                *
            FROM
                transferrm A
                JOIN transferrmitem B ON A.ID = B.IDM
            WHERE
                A.ToLoc = 56 
                AND A.FromLoc IN ( 47, 50 ) 
            --  AND A.Active = 'P' 
             	AND A.Active = 'A' 
                AND A.ID = '$idTM'
                AND B.Product = 5632
            -- 	AND (B.Product = 5632 OR B.Product = 753)
        ");
        if (count($getTM) == 0) {
            $data_return = $this->SetReturn(false, "TM with id '$idTM' Not Found", null, null);
            return $data_return;
        }
        $getTM = $getTM[0];
        // Get TM Item
        $getTMItem = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.*,
                C.SW as nomorSPK,
                B.SW as namaProduct,
                D.Description as kadar,
                A.Qty as jumlah,
                A.Weight as berat,
                B.Photo
            FROM
                transferrmitem A
                JOIN product B ON A.FG = B.ID
                JOIN workorder C ON A.WorkOrder = C.ID
                JOIN productcarat D ON A.Carat = D.ID
            WHERE
                A.IDM = '$idTM'
                AND A.Product = 5632
        ");
        $getTM->tmItem = $getTMItem;
        $resultHTML = view('R&D.Percobaan.PostingTMPCB.layoutTableTMItem', compact('getTM'))->render();
        $data = [
            'result'=>$getTM,
            'resultHTML'=>$resultHTML
        ];
        $data_return = $this->SetReturn(true, "TM with id '$idTM' found", $data, null);
        return $data_return;
    }
    // END REUSABLE FUNCTION

    public function Index(Request $request){
        // Generate Session for file 
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');
        return view('R&D.Percobaan.PostingTMPCB.Index');
    }

    public function GetTM(Request $request){
        $idTM = $request->idTM;
        // Check if idTM is null or blank
        if (is_null($idTM) or $idTM == "") {
            $data_return = $this->SetReturn(false, "idTM Tidak Boleh Null atau blank", null, null);
            return response()->json($data_return, 400);
        }
        // Get TM
        $getTM = $this->FindTM($idTM);
        if (!$getTM['success']) {
            $data_return = $this->SetReturn(false, "TM with id '$idTM' Not Found", null, null);
            return response()->json($data_return, 404);
        }
        return response()->json($getTM, 200);
    }

    public function PostingTM(Request $request){
        $idTM = $request->idTM;
        // Get TM
        $getTM = $this->FindTM($idTM);
        if (!$getTM['success']) {
            $data_return = $this->SetReturn(false, "TM with id '$idTM' Not Found", null, null);
            return response()->json($data_return, 404);
        }
        
        // Check if transferrm status is active
        if ($getTM["data"]["result"]->Active != 'A') {
            $data_return = $this->SetReturn(false, "TM with id '$idTM' Sudah pernah di Posting", null, null);
            return response()->json($data_return, 400);
        }

        // Run Cek Stok Harian
        $FromLoc = $getTM["data"]["result"]->FromLoc;
        $ToLoc = $getTM["data"]["result"]->ToLoc;
        $TransDate = $getTM["data"]["result"]->TransDate;
        // dd($FromLoc, $ToLoc, $TransDate);
        $cekStokHarian = $this->Public_Function->CekStokHarian2ERP($FromLoc, $ToLoc, $TransDate);
        if (!$cekStokHarian) {
            $data_return = $this->SetReturn(false, "Posting TM Gagal, Belum Stok Harian", null, null);
            return response()->json($data_return, 400);
        }

        // Run Public Function PostingTM for Edit Stock and set status at transferrm
        $postingTMFunction = $this->Public_Function->PostingTMERP($idTM, Auth::user()->name);
        if (!$postingTMFunction['validasi']) {
            $data_return = $this->SetReturn(false, "Posting TM Gagal saat insert stock. Hubungi IT", null, null);
            return response()->json($data_return, 400);
        }

        // fixed data
        $fixed_data = [];

        // loop getNTHKO data result nthkoItems for check if each product is in grafisworklist
        foreach ($getTM["data"]["result"]->tmItem  as $key => $value) {
            $probablyDone = false;
            $grafisWorkList = grafisworklist::where('Product',$value->FG)->get();
            if (count($grafisWorkList) > 1) {
                $probablyDone = true;
            }
            $_data = [
                "ProbablyDone"=>$probablyDone,
                "Product"=>$value->FG,
                "WorkAllocation"=>$value->WorkAllocation,
                "LinkOrd"=>$value->LinkOrd,
                "TreeID"=>$value->TreeID,
                "TreeOrd"=>$value->TreeOrd,
                "LinkFreq"=>$value->LinkFreq,
                "Weight"=>$value->Weight,
                "Qty"=>$value->Qty,
                "Carat"=>$value->Carat,
                "TransferID"=>$value->IDM,
                "TransferOrd"=>$value->Ordinal
            ];
            $fixed_data[] = $_data;
        }

        // After Cek Stok Harian and Function Stok success, insert to grafisworklist + update transferrm Active+PostDate
        foreach ($fixed_data as $key => $value) {
            // insert grafisworklist
            $insertGrafisWorklist = grafisworklist::create([
                "UserName"=>Auth::user()->name,
                "Remarks"=>"Laravel",
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
                "TransferID"=>$value['TransferID'],
                "TransferOrd"=>$value['TransferOrd'],
                "ProbablyDone"=>$value['ProbablyDone']
            ]);
        }

        // Return success
        $data_return = $this->SetReturn(true, "TM with id '$idTM' Berhasil di Posting", null, null);
        return response()->json($data_return, 200);
    }

    public function CetakWIPGrafis(Request $request){
        $idTM = $request->idTM;
        if (is_null($idTM) or $idTM == "") {
            $data_return = $this->SetReturn(false, "idTM Tidak Boleh Null atau blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check if TM exists
        $TM = FacadesDB::connection('erp')->select("
            SELECT * FROM transferrm WHERE ID = '$idTM'
        ");
        
        if (count($TM) == 0) {
            return abort(404);
        }
        $TM = $TM[0];
        
        // Get WIPGrafis with idTM
        $getTM = grafisworklist::where('TransferID',$idTM)->first();
        if (is_null($getTM)) {
            return abort(404);
        }

        $datenow = $getTM->TransDate;
        $noNTHKO = $getTM->WorkAllocation;
        if ($TM->FromLoc == 50) {
            $wipItems = FacadesDB::select("
                SELECT
                    B.Photo,
                    D.ID AS idKadar,
                    D.SW AS kadar,
                    B.SerialNo,
                    E.SW AS productCategory,
                    B.SKU,
                    B.Description AS productDescription,
                    A.Weight AS berat,
                    H.Color AS stoneColor
                FROM
                    grafisworklist A
                    JOIN product B ON A.Product = B.ID
                    JOIN erp.productcarat D ON B.VarCarat = D.ID
                    JOIN erp.productcategory E ON B.Model = E.ProductID
                    LEFT JOIN sepuhitem F ON A.WorkAllocation = F.WorkAllocation AND A.LinkOrd = F.Ordinal
                    LEFT JOIN sepuhitemstone G ON F.IDM = G.IDM AND F.Ordinal = G.Ordinal
                    LEFT JOIN masterstone H ON G.Product = H.LinkProduct 
                WHERE
                    A.WorkAllocation = '$noNTHKO' 
                GROUP BY
                    G.Ordinal 
                ORDER BY
                    G.IDM,
                    G.Ordinal,
                    G.OrdinalStone
            ");
        } elseif ($TM->FromLoc == 47) {
            $wipItems = FacadesDB::select("
                SELECT
                    B.Photo,
                    D.ID AS idKadar,
                    D.SW AS kadar,
                    B.SerialNo,
                    E.SW AS productCategory,
                    B.SKU,
                    B.Description AS productDescription,
                    A.Weight AS berat,
                    F.OrdinalVariation
                FROM
                    grafisworklist A
                    JOIN product B ON A.Product = B.ID
                    JOIN erp.productcarat D ON B.VarCarat = D.ID
                    JOIN erp.productcategory E ON B.Model = E.ProductID
                    LEFT JOIN enamelitem F ON A.WorkAllocation = F.WorkAllocation AND A.LinkOrd = F.Ordinal
                WHERE
                    A.WorkAllocation = '$noNTHKO'
                GROUP BY
                    F.Ordinal
                ORDER BY
                    A.Product
            ");
        } else {
            $wipItems = FacadesDB::select("
                SELECT
                    B.Photo,
                    D.ID AS idKadar,
                    D.SW AS kadar,
                    B.SerialNo,
                    E.SW AS productCategory,
                    B.SKU,
                    B.Description AS productDescription,
                    A.Weight AS berat,
                    H.Color AS stoneColor
                FROM
                    grafisworklist A
                    JOIN product B ON A.Product = B.ID
                    JOIN erp.productcarat D ON B.VarCarat = D.ID
                    JOIN erp.productcategory E ON B.Model = E.ProductID
                    LEFT JOIN varpitem F ON A.WorkAllocation = F.WorkAllocation AND A.LinkOrd = F.Ordinal
                    LEFT JOIN varpitemstone G ON F.IDM = G.IDM AND F.Ordinal = G.Ordinal
                    LEFT JOIN masterstone H ON G.Product = H.LinkProduct 
                WHERE
                    A.WorkAllocation = '$noNTHKO'
                GROUP BY
                    G.Ordinal
                ORDER BY
                    A.Product
            ");
        }
        $location = $TM->FromLoc;
        $jumlahItem = count($wipItems);
        $totalBeratItem = 0;
        foreach ($wipItems as $key => $value) {
            $totalBeratItem += $value->berat;
        }
        return view('R&D.Percobaan.PostingTMPCB.cetak',compact('datenow','noNTHKO','jumlahItem','totalBeratItem','wipItems','location'));
    }
}

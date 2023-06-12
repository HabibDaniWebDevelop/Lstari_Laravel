<?php

namespace App\Http\Controllers\Produksi\TukangLuar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

class ProduksiTukangLuarController extends Controller{
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

    private function GetTukangLuarTransaction(String $id_SPKO){
        $sisa = 0 ;
        // Get WorkAllocation and WorkAllocationItem
        $get_workallocation = FacadesDB::connection('erp')->select("
            SELECT
                A.ID AS IDSPKO,
                A.SW AS SWSPKO,
                A.TransDate AS TanggalSPKO,
                A.PostDate AS TanggalPosting,
                'WA' AS JenisTransaksi,
                A.Purpose,
                C.Description AS NamaProdukSPKO,
                D.Description AS Kadar,
                B.Qty AS QtySPKO,
                B.Weight AS WeightSPKO,
                B.WorkOrder AS WorkOrderSPKO,
                B.WorkOrderOrd AS WorkOrderOrdSPKO,
                B.Note AS NoteSPKO,
                B.BarcodeNote AS BarcodeNoteSPKO,
                B.FG AS IDFGSPKO,
                E.SW AS NomorSPKSPKO
            FROM
                workallocation AS A
                LEFT JOIN workallocationitem AS B ON A.ID = B.IDM
                LEFT JOIN product AS C ON B.Product = C.ID
                LEFT JOIN productcarat AS D ON B.Carat = D.ID
                LEFT JOIN workorder AS E ON B.WorkOrder = E.ID
            WHERE
                A.SW = '$id_SPKO'
        ");

        // Check length of get_workallocation
        if (count($get_workallocation) < 1) {
            $data_return = $this->SetReturn(false,"Data tidak ditemukan",null,null);
            return $data_return;
        }

        // loop get_workallocation and get data finish good for each item
        foreach ($get_workallocation as $key => $value) {
            // Check if IDFGSPKO is null. if IDFGSPKO is null, then get data workorderitem, else get data productFG
            if (!is_null($value->IDFGSPKO)) {
                // Getting ID Finish Good SPKO
                $id_product_FG = $value->IDFGSPKO;
                // Getting Product Finish Good SPKO
                $get_product_FG = FacadesDB::connection('erp')->select("
                    SELECT
                        A.ID AS IDProductFGSPKO,
                        A.SW AS NamaProductFGSPKO,
                        B.SW AS CategoryProductFGSPKO
                    FROM
                        product AS A
                        JOIN productcategory AS B ON A.Model = B.ProductID
                    WHERE
                        A.ID = '$id_product_FG'
                ");
                // Check If Product is found
                if (!is_null($get_product_FG)) {
                    $FGSPKO = $get_product_FG[0];
                    $get_workallocation[$key]->IDProductFGSPKO = $FGSPKO->IDProductFGSPKO;
                    $get_workallocation[$key]->NamaProductFGSPKO = $FGSPKO->NamaProductFGSPKO;
                    $get_workallocation[$key]->CategoryProductFGSPKO = $FGSPKO->CategoryProductFGSPKO;
                } else {
                    $get_workallocation[$key]->IDProductFGSPKO = null;
                    $get_workallocation[$key]->NamaProductFGSPKO = null;
                    $get_workallocation[$key]->CategoryProductFGSPKO = null;
                }
            } else if (!is_null($value->WorkOrderSPKO)) {
                // Get WorkOrder from SPKO
                $Workorder = $value->WorkOrderSPKO;

                // Check WorkOrderOrd from SPKO is null. if WorkOrderOrd is null, then set WorkOrderOrd to 1.
                if(!is_null($value->WorkOrderOrdSPKO)){
                    $WorkorderOrd = $value->WorkOrderOrdSPKO;
                } else {
                    $WorkorderOrd = 1;
                }
                // Getting Product Finish Good SPKO from WorkOrderItem
                $get_product_FG = FacadesDB::connection('erp')->select("
                    SELECT
                        B.ID AS IDProductFGSPKO,
                        B.SW AS NamaProductFGSPKO,
                        C.SW AS CategoryProductFGSPKO
                    FROM
                        workorderitem AS A
                        JOIN product AS B ON A.Product = B.ID
                        JOIN productcategory AS C ON B.Model = C.ProductID
                    WHERE
                        A.IDM = '$Workorder' AND A.Ordinal = '$WorkorderOrd'
                ");
                // Check If Product is found
                if (!is_null($get_product_FG)) {
                    $FGSPKO = $get_product_FG[0];
                    $get_workallocation[$key]->IDProductFGSPKO = $FGSPKO->IDProductFGSPKO;
                    $get_workallocation[$key]->NamaProductFGSPKO = $FGSPKO->NamaProductFGSPKO;
                    $get_workallocation[$key]->CategoryProductFGSPKO = $FGSPKO->CategoryProductFGSPKO;
                } else {
                    $get_workallocation[$key]->IDProductFGSPKO = null;
                    $get_workallocation[$key]->NamaProductFGSPKO = null;
                    $get_workallocation[$key]->CategoryProductFGSPKO = null;
                }
            } else {
                $get_workallocation[$key]->IDProductFGSPKO = null;
                $get_workallocation[$key]->NamaProductFGSPKO = null;
                $get_workallocation[$key]->CategoryProductFGSPKO = null;
            }
        }

        // Get WorkCompletion, WorkCompletionItem and Cjepsi
        $get_workcompletion_and_lab = FacadesDB::connection('erp')->select("
            SELECT
                A.TransDate AS TanggalNTHKO,
                'WC' AS JenisTransaksi,
                '' AS Purpose,
                A.PostDate AS TanggalPosting,
                substring_index(B.Note,' ',1) AS Tukang,
                substring_index(B.Note,' ',-1) AS NoModel,
                C.Description AS NamaProdukNTHKO,
                D.SW AS NomorSPKNTHKO,
                E.ID AS IDProductFGNTHKO,
                E.SW AS NamaProductFGNTHKO,
                F.SW AS CategoryProductFGNTHKO,
                CASE WHEN B.Qty > 0 THEN B.Qty ELSE B.RepairQty END AS QtyNTHKO,
                CASE WHEN B.Weight > 0 THEN B.Weight ELSE B.RepairWeight END AS WeightNTHKO,
                B.ScrapQty,
                B.ScrapWeight,
                G.TransDate AS TanggalLab,
                G.WeightDiff AS SelisihBerat,
                '' AS Denda,
                G.Remarks AS CatatanLab
            FROM
                workcompletion AS A
                LEFT JOIN workcompletionitem AS B ON A.ID = B.IDM
                LEFT JOIN product C ON B.Product = C.ID
                LEFT JOIN workorder D ON B.WorkOrder = D.ID
                LEFT JOIN product E ON B.FG = E.ID
                LEFT JOIN productcategory F ON E.Model = F.ProductID
                LEFT JOIN cjepsi G ON B.IDM = G.WorkCompletion AND B.Ordinal = G.WorkCompletionOrd
            WHERE
                A.WorkAllocation = '$id_SPKO' AND A.Active != 'C'
        ");

        $final_data = [];

        // Loop get_workallocation and add to final_data
        foreach ($get_workallocation as $key => $value) {
            // convert TanggalPosting from string to timestamp
            $get_workallocation[$key]->TanggalPosting = strtotime($get_workallocation[$key]->TanggalPosting);
            // format date of TanggalSPKO to d/m/Y
            $get_workallocation[$key]->TanggalSPKO = date('d/m/Y', strtotime($get_workallocation[$key]->TanggalSPKO));
            // add data to final_data
            $final_data[] = json_decode(json_encode($get_workallocation[$key]), true);
        }

        // Loop get_workcompletion_and_lab and add to final_data
        foreach ($get_workcompletion_and_lab as $key => $value) {
            // convert TanggalPosting from string to timestamp
            $get_workcompletion_and_lab[$key]->TanggalPosting = strtotime($get_workcompletion_and_lab[$key]->TanggalPosting);
            
            // format date of TanggalNTHKO to d/m/Y
            $get_workcompletion_and_lab[$key]->TanggalNTHKO = date('d/m/Y', strtotime($get_workcompletion_and_lab[$key]->TanggalNTHKO));

            // check if TanggalLab is not null
            if (!is_null($get_workcompletion_and_lab[$key]->TanggalLab)) {
                // format date of TanggalLab to d/m/Y
                $get_workcompletion_and_lab[$key]->TanggalLab = date('d/m/Y', strtotime($get_workcompletion_and_lab[$key]->TanggalLab));
            }

            // add data to final_data
            $final_data[] = json_decode(json_encode($get_workcompletion_and_lab[$key]), true);
        }

        // sort final_data by TanggalPosting
        usort($final_data, function ($item1, $item2) {
            return $item1['TanggalPosting'] <=> $item2['TanggalPosting'];
        });

        // Loop Final data and calculate sisa
        foreach ($final_data as $key => $value) {
            // Check if Purpose is 'Tambah'
            if ($value['Purpose'] == 'Tambah') {
                // add sisa with WeightSPKO
                $sisa+=$value['WeightSPKO'];
            } else {
                // Check if JenisTransaksi is 'WA'
                if ($value['JenisTransaksi'] == 'WA') {
                    // deduct sisa with WeightSPKO
                    $sisa-=$value['WeightSPKO'];
                } else {
                    // deduct sisa with WeightNTHKO
                    $sisa-=$value['WeightNTHKO'];
                    // deduct sisa with ScrapWeight
                    $sisa-=$value['ScrapWeight'];
                }
            }
            // format sisa to 2 decimal and add to final_data
            $final_data[$key]['Sisa'] = round($sisa,2);
        }
        
        // Get detail of nomor nota
        $detail = FacadesDB::connection('erp')->select("
            SELECT
                D.SW AS NamaTukang,
                C.SW AS Kadar,
                B.Description AS Proses
                
            FROM
                workallocation A 
                JOIN operation B ON A.Operation = B.ID
                JOIN productcarat C ON A.Carat = C.ID
                JOIN employee D ON A.Employee = D.ID
            WHERE
                A.SW = '$id_SPKO'
            LIMIT 1
        ");
        
        $data = [
            'transaction'=>$final_data,
            'tukang'=>$detail[0]->NamaTukang,
            'kadar'=>$detail[0]->Kadar,
            'proses'=>$detail[0]->Proses
        ];

        $data_return = $this->SetReturn(true,"Success",$data,null);

        return $data_return;
    }
    
    public function index(){
        return view('Produksi.TukangLuar.index');
    }

    // Function for get TukangLuarTransaction
    public function GetTukangLuar(Request $request){
        $nomorNota = $request->nomorNota;
        // Check if nomorNota is null
        if(is_null($nomorNota)){
            // generate data_return from SetReturn function
            $data_return = $this->SetReturn(false,"nomorNota can't be null or blank",null,null);
            // Return data_return with 400 status code
            return response()->json($data_return,400);
        }

        // execute GetTukangLuarTransaction
        $final_data = $this->GetTukangLuarTransaction($nomorNota);
        if (!$final_data['success']) {
            $data_return = $this->SetReturn(false,$final_data['message'],null,null);
            // return 400
            return response()->json($data_return,400);
        }
        $transactions = $final_data['data']['transaction'];
        $html = view('Produksi.TukangLuar.table',compact('transactions'))->render();
        // add html to final_data data
        $final_data['data']['html'] = $html;
        // Return data_return with 200 status code
        return response()->json($final_data,200);
    }

    public function CetakTukangLuar(Request $request){
        $nomorNota = $request->nomorNota;
        // check if nomorNota is null or blank
        if(is_null($nomorNota) || $nomorNota == ""){
            // generate data_return from SetReturn function
            $data_return = $this->SetReturn(false,"nomorNota can't be null or blank",null,null);
            // return 400
            return response()->json($data_return,400);
        }
        // execute GetTukangLuarTransaction
        $final_data = $this->GetTukangLuarTransaction($nomorNota);
        if (!$final_data['success']) {
            return aboart(404);
        }
        $transactions = $final_data['data']['transaction'];
        $tukang = $final_data['data']['tukang'];
        $kadar = $final_data['data']['kadar'];
        $proses = $final_data['data']['proses'];
        $border = true;
        return view('Produksi.TukangLuar.cetak',compact('transactions','tukang','kadar','proses', 'nomorNota', 'border'));
    }
}

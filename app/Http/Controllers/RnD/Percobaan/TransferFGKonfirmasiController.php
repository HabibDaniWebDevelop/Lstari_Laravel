<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
use App\Models\erp\transferfg;
use App\Models\erp\transferfgconfirm;

use App\Models\tes_laravel\transferfg as transferfg_test;
use App\Models\tes_laravel\transferfgconfirm as transferfgconfirm_test;


class TransferFGKonfirmasiController extends Controller{

    public function index(Request $request){

        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $query = "SELECT 
                    * 
                FROM 
                    transferfg 
                WHERE 
                    Active <> 'C'
                ORDER BY ID DESC
                LIMIT 100
                ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('R&D.Percobaan.TransferFGKonfirmasi.index', compact('data','rowcount'));
    }

    public function lihat(Request $request){

    }

    public function getItemTM(Request $request){

        $location = session('location');
        $UserEntry = session('UserEntry');

        $idtm = $request->idtm;

        $getInfo  = "SELECT * FROM transferfg WHERE ID = $idtm ";
        $data = FacadesDB::connection('erp')->select($getInfo);
        foreach ($data as $datas);

        $cekTransferFG = "SELECT * FROM transferfgconfirm WHERE IDM = $idtm ";
        $data2 = FacadesDB::connection('erp')->select($cekTransferFG);
        $baris = count($data2);

        if ($baris > 0) {
            $getItem = "SELECT
                            tg.ID,
                            tgi.Ordinal,
                            tgi.Product IDProduk,
                            p.SW Model, 
                            tgi.Carat,
                            tgi.Qty,
                            tgi.Weight,
                            tgi.WorkOrder,
                            wo.SW NoSPK,
                            wo.Polling
                        FROM
                            transferfg tg
                            JOIN transferfgconfirm tgi ON tg.ID = tgi.IDM
                            JOIN product p ON tgi.Product = p.ID
                            JOIN workorder wo ON tgi.WorkOrder = wo.ID
                        WHERE tg.ID = ".$idtm."
                        ORDER BY tgi.WorkOrder";
        }
        else{
            $getItem = "SELECT
                            tg.ID,
                            tgi.Ordinal,
                            tgi.Product IDProduk,
                            p.SW Model, 
                            tgi.Carat,
                            tgi.Qty,
                            tgi.Weight,
                            tgi.WorkOrder,
                            wo.SW NoSPK,
                            wo.Polling,
                            tgi.Calculation
                        FROM
                            transferfg tg
                            JOIN transferfgitem tgi ON tg.ID = tgi.IDM
                            JOIN product p ON tgi.Product = p.ID
                            JOIN workorder wo ON tgi.WorkOrder = wo.ID
                        WHERE tg.ID = ".$idtm."
                        ORDER BY tgi.WorkOrder";
        }
        $data3 = FacadesDB::connection('erp')->select($getItem);

        $getEmployee = "SELECT ID, SW, Description FROM employee WHERE Department = 62 AND Active = 'Y'";
        $data4 = FacadesDB::connection('erp')->select($getEmployee);

        $returnHTML = view('R&D.Percobaan.TransferFGKonfirmasi.getItemTM', compact('location','data','data2','data3','data4','idtm'))->render();
        $jsondata = array('success' => true, 'html' => $returnHTML, 'status' => $datas->Active, 'idtm' => $idtm);

        return response()->json($jsondata);

    }

    public function simpan(Request $request){

    }

    public function posting(Request $request){

    }

    public function cetak(Request $request){

    }

    




}

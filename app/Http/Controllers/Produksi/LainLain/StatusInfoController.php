<?php

namespace App\Http\Controllers\Produksi\LainLain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as FacadesDB;
use \DateTime;
use \DateTimeZone;


class StatusInfoController extends Controller
{
    public function cekSession(){
        $data = session()->all();
        dd($data);
    }

    public function index(){
        return view('Produksi.Lain-Lain.StatusInfo.index');
    }

    public function testData(){

        $location = session('location');
        $data = FacadesDB::connection('erp')->select("SELECT
                A.*, B.Description EmpName
            FROM
                transferrm A
                JOIN employee B ON A.Employee=B.ID
            WHERE
                A.ToLoc = $location
                AND A.Active='A'
        ");

        // compact(var 1, var 2, etc) => send multiple variable to views
        // return view('Produksi.Lain-Lain.StatusInfo.data', compact('data'));
        $returnHTML = view('Produksi.Lain-Lain.StatusInfo.operation', compact('data'))->render(); // or method that you prefer to return, data + RENDER is the key here
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }

    // OK
    public function tmItem(){

        $location = session('location');
        $jenis = 1;
        $data = FacadesDB::connection('erp')->select("SELECT
                A.*, B.Description Asal, C.Description Tujuan
            FROM
                transferrm A
                JOIN location B ON A.FromLoc=B.ID
                JOIN location C ON A.ToLoc=C.ID
            WHERE
                (A.ToLoc = $location OR A.FromLoc = $location)
                AND A.Active = 'A'
            ORDER BY A.ID ASC
        ");

        $returnHTML = view('Produksi.Lain-Lain.StatusInfo.tmItem', compact('data','jenis'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }

    // OK
    public function spkoItem(){

        $location = session('location');
        $jenis = 2;
        $data = FacadesDB::connection('erp')->select("SELECT
                A.*, B.ID OpID, B.SW OpName, C.Description Proses
            FROM
                workallocation A
                JOIN employee B ON A.Employee=B.ID
                JOIN operation C ON A.Operation=C.ID
            WHERE
                A.Location = $location
                AND A.Active = 'A'
            ORDER BY A.ID ASC
        ");

        $returnHTML = view('Produksi.Lain-Lain.StatusInfo.spkoItem', compact('data','jenis'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }

    // PENDING
    public function kodeItem(){

    }

    // PENDING
    public function tfStockItem(){

    }

    // PENDING
    public function tfFGItem(){

    }

    // OK
    public function tfFGPersiapanItem(){

        $dept = session('iddept');
        $jenis = 6;
        $query = "SELECT
                    A.*, C.Description DeptName, B.Description EmpName
                FROM
                    transferfg A
                    JOIN employee B ON A.Employee=B.ID
                    JOIN department C ON B.Department=C.ID
                WHERE
                    B.Department = $dept
                    AND A.Active = 'E'
                ORDER BY A.ID ASC";
        $data = FacadesDB::connection('erp')->select($query);
        // dd($query);
        $returnHTML = view('Produksi.Lain-Lain.StatusInfo.tfFGPersiapanItem', compact('data','jenis'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }

    // OK
    public function operationItem(){

        $location = session('location');
        $jenis = 7;
        $data = FacadesDB::connection('erp')->select("SELECT
                A.*
            FROM 
                transferrm A 
                JOIN transferrmitem B ON A.ID=B.IDM
            WHERE
                B.Operation IS NULL
                AND A.ToLoc = $location
            GROUP BY A.ID
            ORDER BY A.ID ASC
        ");

        $returnHTML = view('Produksi.Lain-Lain.StatusInfo.operationItem', compact('data','jenis'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }

    // PENDING 
    public function showInfo($ID, $Jenis){

        $dataID = $ID;
        $dataJenis = $Jenis;

        if($dataJenis == 1){

            $jenis = 1;
            $data = FacadesDB::connection('erp')->select("SELECT
                    A.*, B.Description Kadar, CONCAT(A.WorkAllocation,'-', A.LinkFreq,'-',A.LinkOrd) NTHKO
                FROM
                    transferrmitem A
                    JOIN productcarat B ON A.Carat=B.ID
                WHERE
                    A.IDM = $dataID
                ORDER BY A.IDM, A.Ordinal ASC
            ");

            $returnHTML = view('Produksi.Lain-Lain.StatusInfo.showInfo', compact('data','jenis'))->render();
            return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OKShow') );

        }else if($dataJenis == 2){

        }else if($dataJenis == 3){

        }else if($dataJenis == 4){

        }else if($dataJenis == 5){

        }else if($dataJenis == 6){

        }else if($dataJenis == 7){

        }

    }


    
}
<?php

namespace App\Http\Controllers\Workshop\SPKO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\tes_laravel\wipworkshop;
use App\Models\tes_laravel\wipworkshopfg;
use App\Models\tes_laravel\workshopallocation;
use App\Models\tes_laravel\workshopallocationitem;
use App\Models\tes_laravel\workshopcompletion;
use App\Models\tes_laravel\workshopcompletionitem;
use App\Models\tes_laravel\matras;
use App\Models\tes_laravel\matrasitem;
use App\Models\tes_laravel\knives;
use App\Models\rndnew\lastid;

class SPKOPercobaanKuninganWorkshopController extends Controller{
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
        return view('Workshop.SPKO.PercobaanKuningan.index', compact('now','employees'));
    }

    public function SearchMatras(Request $request){
        $idMatras = $request->idMatras;
        // Check if idNTHKO Matras is NULL or blank
        if ($idMatras == "" or is_null($idMatras)) {
            $data_return = $this->SetReturn(false, 'idMatras Cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }

        // Get Matras
        $matras = matras::with('Items', 'Items.knive', 'Items.Product')->where('ID',$idMatras)->first();

        // Check if Matras is exists
        if (is_null($matras)) {
            $data_return = $this->SetReturn(false, 'Matras Dengan ID Tersebut Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        $matrasHTML = view('Workshop.SPKO.PercobaanKuningan.rowMatras',compact('matras'))->render();
        $data = [
            "matras"=>$matras,
            "matrasHTML"=>$matrasHTML
        ];

        $data_return = $this->SetReturn(false, 'NTHKO Matras Ditemukan', $data, null);
        return response()->json($data_return, 200);
    }

    public function SimpanPSKOPercobaanKuningan(Request $request){
        $idMatras = $request->idMatras;
        $idEmployee = $request->idEmployee;
        $catatan = $request->catatan;
        // Check if idMatras is null or blank
        if ($idMatras == "" or is_null($idMatras)) {
            $data_return = $this->SetReturn(false, 'idMatras Cant be blank or null', null, null);
            return response()->json($data_return, 400);
        }

        // Check if idMatras is exists
        $getMatras = matras::with('Items', 'Items.knive', 'Items.Product')->where('ID',$idMatras)->first();
        if (is_null($getMatras)) {
            $data_return = $this->SetReturn(false, 'Matras Dengan ID Tersebut Tidak Ditemukan', null, null);
            return response()->json($data_return, 404);
        }

        // Generate SW
        $generateSW = FacadesDB::select("
            SELECT
                CONCAT(
                    'FKWKN',
                    '',
                    DATE_FORMAT( CURDATE(), '%y' ),
                    '',
                    LPad( MONTH ( CurDate()), 2, '0' ),
                    '02',
                LPad( Count( ID ) + 1, 3, '0' )) Counter,
                CONCAT(
                    '',
                    DATE_FORMAT( CURDATE(), '%y' ),
                    '',
                    LPad( MONTH ( CurDate()), 2, '0' ),
                    '02',
                LPad( Count( ID ) + 1, 3, '0' )) SW
            FROM
                workshopallocation 
            WHERE
                YEAR ( TransDate ) = YEAR (CurDate()) 
                AND MONTH ( TransDate ) = MONTH (CurDate()) 
                AND Process = 'Percobaan Kuningan'
        ");
        $generateSW = $generateSW[0];

        // Get Lastid
        $lastid = lastid::where('Module','workshopallocation')->first();
        $lastid = $lastid['Last']+1;

        // Create SPKO Percobaan Matras Kuningan
        $createWorkshopAllocation = workshopallocation::create([
            "ID"=>$lastid,
            "UserName"=>Auth::user()->name,
            "Employee"=>$idEmployee,
            "TransDate"=>date("Y-m-d"),
            "Active"=>"A",
            "Process"=>"Percobaan Kuningan",
            "Freq"=>1,
            "SW"=>$generateSW->Counter,
            "TransTime"=>date('H:i:s'),
            "Status"=>"Baru",
            "MatrasID"=>$idMatras
        ]);

        // loop Item Matras for insert to Item SPKO Percobaan Matras Kuningan
        foreach ($getMatras->Items as $key => $value) {
            $createWorkshopAllocationItem = workshopallocationitem::create([
                "IDM"=>$lastid,
                "Ordinal"=>$key+1,
                "Product"=>$value->IDProduct
            ]);
        }
    }
}

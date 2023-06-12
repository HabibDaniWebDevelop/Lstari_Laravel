<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\employeeguarantee;

class InformasiJaminanKaryawanController extends Controller{
    public function Index(){
        $employeeguarantee = FacadesDB::connection("erp")
        ->select("
            SELECT
                E.ID AS IDk,
                E.Description AS NAME,
                X.Description,
                A.ID,
                A.Employee,
                D.Description AS Type,
                A.SW,
                DATE_FORMAT(A.TransDate, '%d-%m-%Y') TransDate,
                DATE_FORMAT(A.ReturnDate, '%d-%m-%Y') ReturnDate,
                A.Remarks 
            FROM
                employeeguarantee A
                LEFT JOIN Employee E ON E.ID = A.Employee 
                LEFT JOIN department X ON E.Department = X.ID
                LEFT JOIN shorttext D ON D.ID = A.Type
        ");
        return view("Absensi.Informasi.JaminanKaryawan.index",compact('employeeguarantee'));
    }

    public function ProcessEmployeeGuarantee($ID){
        if ($ID == "" or is_null($ID)){
            abort(400);
        }

        $returndate =  date('Y-m-d');
        // Update EmployeeGuarantee
        $employeeguarantee = employeeguarantee::where('ID',$ID)->update([
            "ReturnDate"=>$returndate
        ]);

        $data_return = [
            'success' => true,
            'title' => 'Success!!',
            'message' => "EmployeeGuarantee Updated",
            'data'=> null
        ];
        return response()->json($data_return,200);
    }
}

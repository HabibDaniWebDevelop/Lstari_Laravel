<?php

namespace App\Http\Controllers\Absensi\JaminanKaryawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Model
use App\Models\erp\employeeguarantee;

class JaminanKaryawanController extends Controller{
 
    public function Index(){
        $datenow = date('Y-m-d');
        return view("Absensi.JaminanKaryawan.index", compact("datenow"));
    }

    public function SearchEmployee(Request $request){
        $employeeSW = $request->employee;
        // Get Employee
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
            E.WorkRole	
        ")
        ->where("E.SW","=","".$employeeSW)
        ->orWhere("E.ID","=","".$employeeSW)
        ->where("E.Active",'=',"Y")
        ->orderBy("E.Department","ASC")
        ->get();
        if (count($employee) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Employee with SW ".$employeeSW." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Setup Return Data
        $data_return = [
            'success' => true,
            'title' => 'Successs!!',
            'message' => "Employee Found",
            'data'=> $employee[0]
        ];
        return response()->json($data_return,200);
    }

    public function SimpanJaminanKaryawan(Request $request){
        $idEmployee = $request->idEmployee;
        $tanggal_diterima = $request->tanggal_diterima;
        $jaminan = $request->jaminan;
        $nomor_sk = $request->nomor_sk;
        $keterangan = $request->keterangan;


        // Check if Employee exists
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
            E.WorkRole	
        ")
        ->Where("E.ID","=","".$idEmployee)
        ->where("E.Active",'=',"Y")
        ->orderBy("E.Department","ASC")
        ->get();
        if (count($employee) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Employee Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Check if Type exists
        $guaranteetype = FacadesDB::connection('erp')
        ->table('shorttext AS A')
        ->selectRaw("
            A.ID
        ")
        ->where("A.ID","=","".$jaminan)
        ->get();
        if (count($guaranteetype) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Jaminan With Code ".$jaminan." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Save EmployeeGuarantee
        $employeeguarantee = employeeguarantee::create([
            "EntryDate"=>date('Y-m-d H:i:s'),
            "UserName"=>Auth::user()->name,
            "Remarks"=>$keterangan,
            "SW"=>$nomor_sk,
            "Employee"=>$idEmployee,
            "Type"=>$jaminan,
            "TransDate"=>$tanggal_diterima
        ]);
        $data_return = [
            'success' => true,
            'title' => 'Success!!',
            'message' => "Jaminan Karyawan Created",
            'data' => $employeeguarantee
        ];
        return response()->json($data_return,200);
    }

    public function UbahJaminanKaryawan(Request $request){
        $idJaminanKaryawan = $request->idJaminanKaryawan;
        $idEmployee = $request->idEmployee;
        $tanggal_diterima = $request->tanggal_diterima;
        $jaminan = $request->jaminan;
        $nomor_sk = $request->nomor_sk;
        $keterangan = $request->keterangan;

        // Check if Employeeguarantee exists
        $employeeguarantee = employeeguarantee::where('ID',$idJaminanKaryawan)->first();
        if (is_null($employeeguarantee)) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Jaminan Karyawan With ID ".$idJaminanKaryawan." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Check if Employee exists
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
            E.WorkRole	
        ")
        ->Where("E.ID","=","".$idEmployee)
        ->where("E.Active",'=',"Y")
        ->orderBy("E.Department","ASC")
        ->get();
        if (count($employee) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Employee Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Check if Type exists
        $guaranteetype = FacadesDB::connection('erp')
        ->table('shorttext AS A')
        ->selectRaw("
            A.ID
        ")
        ->where("A.ID","=","".$jaminan)
        ->get();
        if (count($guaranteetype) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Jaminan With Code ".$jaminan." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }

        // Save EmployeeGuarantee
        $employeeguarantee = employeeguarantee::where('ID',$idJaminanKaryawan)
        ->update([
            "Remarks"=>$keterangan,
            "SW"=>$nomor_sk,
            "Employee"=>$idEmployee,
            "Type"=>$jaminan,
            "TransDate"=>$tanggal_diterima
        ]);
        $data_return = [
            'success' => true,
            'title' => 'Success!!',
            'message' => "Jaminan Karyawan Updated",
            'data' => $employeeguarantee
        ];
        return response()->json($data_return,200);
    }

    public function SearchJaminanKaryawan(Request $request){
        $idJaminanKaryawan = $request->keyword;
        $jaminanKaryawan = FacadesDB::connection('erp')
        ->select("
            SELECT
                A.*,
                B.Description AS NAME
            FROM
                employeeguarantee A
                JOIN employee B on A.Employee = B.ID
            WHERE
                A.ID = '$idJaminanKaryawan'
        ");
        if (count($jaminanKaryawan) == 0) {
            $data_return = [
                'success' => false,
                'title' => 'Failed!!',
                'message' => "Jaminan With Code ".$idJaminanKaryawan." Not Found",
                'data' => null
            ];
            return response()->json($data_return,404);
        }
        $data_return = [
            'success' => true,
            'title' => 'Success!!',
            'message' => "Jaminan Karyawan Found",
            'data' => $jaminanKaryawan[0]
        ];
        return response()->json($data_return,200);
    }

    public function CetakJaminanKaryawan($id){
        $datenow = date('Y-m-d');
        $jaminanKaryawan = FacadesDB::connection('erp')
        ->select("
            SELECT
                E.ID IDk,
                E.Description NAME,
                X.Description,
                A.ID,
                A.Employee,
                D.Description Type,
                A.SW,
                A.TransDate,
                A.ReturnDate,
                A.Remarks 
            FROM
                employeeguarantee A
                LEFT JOIN Employee E ON E.ID = A.Employee 
                LEFT JOIN department X ON E.Department = X.ID
                LEFT JOIN shorttext D ON D.ID = A.Type
            WHERE
                A.ID = '$id'
        ");
        if (count($jaminanKaryawan) == 0) {
            abort(404);
        }
        $jaminanKaryawan = $jaminanKaryawan[0];
        return view("Absensi.JaminanKaryawan.cetak", compact("datenow", "jaminanKaryawan"));
    }
    
}

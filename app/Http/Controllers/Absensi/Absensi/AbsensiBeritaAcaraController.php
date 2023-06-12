<?php

namespace App\Http\Controllers\Absensi\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
use App\Models\erp\beritaacara;

class AbsensiBeritaAcaraController extends Controller{

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
        $datenow = date('Y-m-d');
        return view('Absensi.Absensi.BeritaAcara.index', compact('datenow'));
    }

    public function SearchEmployee(Request $request){
        $SWEmployee = $request->employee;
        if (is_null($SWEmployee) or $SWEmployee == "") {
            $data_return = $this->SetReturn(false, "SWEmployee cant be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Get Employee
        $employee = $this->GetEmployee($SWEmployee);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Employee Not Found", null, null);
            return response()->json($data_return, 404);
        }
        $employee = $employee[0];

        $data_return = $this->SetReturn(true, "Employee Found", $employee, null);
        return response()->json($data_return, 200);
    }

    public function SaveBeritaAcara(Request $request){
        // Get input
        $idEmployee = $request->idEmployee;
        $tanggal = $request->tanggal;
        $keperluan = $request->keperluan;
        $jenis = $request->jenis;
        $keterangan = $request->keterangan;
        $solusi = $request->solusi;

        // Check Input
        if ($idEmployee == "" or $idEmployee == null){
            $data_return = $this->SetReturn(false, "idEmployee Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } 
        if ($tanggal == "" or $tanggal == null) {
            $data_return = $this->SetReturn(false, "tanggal Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } 
        if ($keperluan == "" or $keperluan == null) {
            $data_return = $this->SetReturn(false, "keperluan Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        if ($jenis == "" or $jenis == null) {
            $data_return = $this->SetReturn(false, "jenis Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        if ($keterangan == "" or $keterangan == null) {
            $data_return = $this->SetReturn(false, "keterangan Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } if ($solusi == "" or $solusi == null) {
            $data_return = $this->SetReturn(false, "solusi Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check Employee
        $employee = $this->GetEmployee($idEmployee);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Employee With id '$idEmployee' Not Found ", null, null);
            return response()->json($data_return, 404);
        }
        $employee = $employee[0];
        // Insert to Beritaacara
        $beritaAcara = beritaacara::create([
            "UserName"=>Auth::user()->name,
            "TransDate"=>$tanggal,
            "Employee"=>$employee->ID,
            "Department"=>$employee->Department,
            "Status"=>$employee->WorkRole,
            "Type"=>$jenis,
            "Purpose"=>$keperluan,
            "Note"=>$keterangan,
            "Solution"=>$solusi
        ]);

        // Success
        $data_return = $this->SetReturn(true, "Berita Acara Saved ", $beritaAcara, null);
        return response()->json($data_return, 200);
    }

    public function UpdateBeritaAcara(Request $request){
        // Get input
        $idBeritaAcara = $request->idBeritaAcara;
        $idEmployee = $request->idEmployee;
        $tanggal = $request->tanggal;
        $keperluan = $request->keperluan;
        $jenis = $request->jenis;
        $keterangan = $request->keterangan;
        $solusi = $request->solusi;

        // Check Input
        if ($idBeritaAcara == "" or $idEmployee == null){
            $data_return = $this->SetReturn(false, "idBeritaAcara Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } 
        if ($idEmployee == "" or $idEmployee == null){
            $data_return = $this->SetReturn(false, "idEmployee Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } 
        if ($tanggal == "" or $tanggal == null) {
            $data_return = $this->SetReturn(false, "tanggal Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } 
        if ($keperluan == "" or $keperluan == null) {
            $data_return = $this->SetReturn(false, "keperluan Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        if ($jenis == "" or $jenis == null) {
            $data_return = $this->SetReturn(false, "jenis Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }
        if ($keterangan == "" or $keterangan == null) {
            $data_return = $this->SetReturn(false, "keterangan Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        } if ($solusi == "" or $solusi == null) {
            $data_return = $this->SetReturn(false, "solusi Can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // Check Employee
        $employee = $this->GetEmployee($idEmployee);
        if (count($employee) == 0) {
            $data_return = $this->SetReturn(false, "Employee With id '$idEmployee' Not Found ", null, null);
            return response()->json($data_return, 404);
        }
        $employee = $employee[0];
        // Check Beritaacara
        $beritaAcara = beritaacara::where('ID',$idBeritaAcara)->first();
        if (is_null($beritaAcara)) {
            $data_return = $this->SetReturn(false, "BeritaAcara With id '$idBeritaAcara' Not Found ", null, null);
            return response()->json($data_return, 404);
        }
        // Update to Beritaacara
        $beritaAcara = beritaacara::where('ID',$idBeritaAcara)->update([
            "UserName"=>Auth::user()->name,
            "TransDate"=>$tanggal,
            "Employee"=>$employee->ID,
            "Department"=>$employee->Department,
            "Status"=>$employee->WorkRole,
            "Type"=>$jenis,
            "Purpose"=>$keperluan,
            "Note"=>$keterangan,
            "Solution"=>$solusi
        ]);
        
        // Success
        $data_return = $this->SetReturn(true, "Berita Acara Updated ", null, null);
        return response()->json($data_return, 200);
    }

    public function SearchBeritaAcara(Request $request){
        // Get idBerita acara from input
        $keyword = $request->keyword;
        // Query berita acara
        $beritaAcara = FacadesDB::connection('erp')
        ->select("
            SELECT
                E.ID,
                E.Description NAME,
                D.Description Bagian,
                E.Department,
                E.WorkRole,
                A.TransDate,
                A.Note,
                A.Solution,
                A.ID IDM,
                A.Type,
                A.Purpose 
            FROM
                beritaacara A
                JOIN Employee E ON A.Employee = E.ID
                JOIN Department D ON E.Department = D.ID 
            WHERE
                A.ID = '$keyword'
        ");
        // Check if berita acara is exists
        if (count($beritaAcara) == 0 ) {
            // Return if berita acara is not found
            $data_return = $this->SetReturn(false, "Berita Acara Not Found", null, null);
            return response()->json($data_return, 404);
        }
        // Berita acara found
        $beritaAcara = $beritaAcara[0];
        $data_return = $this->SetReturn(true, "Berita Acara found", $beritaAcara, null);
        return response()->json($data_return, 200);
    }
}

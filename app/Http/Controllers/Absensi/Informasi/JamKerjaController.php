<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class JamKerjaController extends Controller{

    public function Index(){
        $user = Auth::user();
        return view("Absensi.Informasi.JamKerja.index");
    }

    public function GetJamKerja(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $user = Auth::user();
        $all_access_level = [1,2,66,10,15];
        // Check user level is in all_access_level
        if(!in_array($user->level, $all_access_level)){
            $swUser = $user->name;
            // get user department
            $user_department = FacadesDB::connection("erp")->select("
                SELECT
                    A.Department
                FROM
                    employee A
                WHERE
                    A.SW = '$swUser'
            ");

            // department user
            $idDepartment = $user_department[0]->Department;
            $data = FacadesDB::connection("erp")->select("
                SELECT
                    H.*,
                    E.Description EDescription,
                    D.Description DDescription,
                    E.WorkRole,
                    H.OverTime + IfNull( H.OverTimeAdd, 0 ) OverTimeTotal,
                    T.DAY,
                    IfNull( H.OverTimeBonus, 0 ) + (( H.OverTime + IfNull( H.OverTimeAdd, 0 )) * H.OverTimeRate ) OverTimePay,
                    ConCat(Date_Format( H.TransDate, '%m' ), ' - ', MonthName( H.TransDate )) MONTH 
                FROM
                    WorkHour H
                    JOIN Employee E ON H.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN WorkDate T ON H.TransDate = T.TransDate 
                WHERE
                    ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) AND E.Department = $idDepartment
                ORDER BY
                    DDescription,
                    E.Description,
                    TransDate
            ");
            return response()->json(["tampil"=>$data],200); 
        }

        $data = FacadesDB::connection("erp")->select("
            SELECT
                H.*,
                E.Description EDescription,
                D.Description DDescription,
                E.WorkRole,
                H.OverTime + IfNull( H.OverTimeAdd, 0 ) OverTimeTotal,
                T.DAY,
                IfNull( H.OverTimeBonus, 0 ) + (( H.OverTime + IfNull( H.OverTimeAdd, 0 )) * H.OverTimeRate ) OverTimePay,
                ConCat(Date_Format( H.TransDate, '%m' ), ' - ', MonthName( H.TransDate )) MONTH 
            FROM
                WorkHour H
                JOIN Employee E ON H.Employee = E.ID
                JOIN Department D ON E.Department = D.ID
                JOIN WorkDate T ON H.TransDate = T.TransDate 
            WHERE
                ( H.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' ) 
            ORDER BY
                DDescription,
                E.Description,
                TransDate
        ");
        return response()->json(["tampil"=>$data],200); 
    }

}

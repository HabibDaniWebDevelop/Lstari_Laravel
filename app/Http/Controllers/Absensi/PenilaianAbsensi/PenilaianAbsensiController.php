<?php

namespace App\Http\Controllers\Absensi\PenilaianAbsensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class PenilaianAbsensiController extends Controller{
    public function Index(){
        $department = FacadesDB::connection("erp")
        ->select("
            SELECT
                H.ID,
                H.Description 
            FROM
                department S
                JOIN Department H ON H.HigherRank = S.ID
            WHERE
                H.HigherRank IS NOT NULL
            ORDER BY
                H.Description ASC
        ");
        return view("Absensi.PenilaianAbsensi.index",compact('department'));
    }

    public function SearchPenilaianAbsensi(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $department = $request->department;
        $penilaianabsensi = FacadesDB::connection("erp")
        ->select("
            SELECT
                E.Description Employee,
                D.Description Department,
                H.Description HigherRank,
                E.StartWork,
                SUM(CASE WHEN A.Type = 1 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS Sakit,
                SUM(CASE WHEN A.Type = 2 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS Ijin,   
                SUM(CASE WHEN A.Type = 3 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS Telat,  
                SUM(CASE WHEN A.Type = 4 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS Absen,      
                SUM(CASE WHEN A.Type = 6
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS AbsenIjin,                              
                SUM(CASE WHEN A.Type IN ( 5, 13 ) 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS CutiTahunan,
                SUM(CASE WHEN A.Type IN ( 7, 8, 9, 10, 11, 12, 15, 16, 17, 18 ) 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS CutiKhusus,                 
                                        SUM((CASE WHEN A.Type IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20) 
                        AND A.Active = 'P' THEN 1 ELSE NULL END)) AS Total, 
                SUM(CASE WHEN A.Type = 14 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS Ijin60, 
                SUM(CASE WHEN A.Type = 20 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS Covid,      
                SUM(CASE WHEN A.Type = 19 
                        AND A.Active = 'P' THEN 1 ELSE NULL END) AS AbsenSakit,                         
                E.WorkRole
            FROM
                Employee E
                JOIN Department D ON E.Department = D.ID
                JOIN Department H ON D.HigherRank = H.ID
                JOIN Absent A ON A.Employee = E.ID
                JOIN AbsentItem I ON A.ID = I.IDM 
            WHERE 
                I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir'
                AND E.Active = 'Y'
                AND E.Department = '$department'
            GROUP BY
                E.ID
            ORDER BY
                E.Description,
                D.Description
        ");
        $totalAbsen = [
            "Sakit"=>0,
            "Ijin"=>0,
            "Telat"=>0,
            "Absen"=>0,
            "AbsenIjin"=>0,
            "CutiTahunan"=>0,
            "CutiKhusus"=>0,
            "Ijin60"=>0,
            "Covid"=>0,
            "AbsenSakit"=>0
        ];

        foreach ($penilaianabsensi as $key => $value) {
            $totalAbsen["Sakit"] += $value->Sakit;
            $totalAbsen["Ijin"] += $value->Ijin;
            $totalAbsen["Telat"] += $value->Telat;
            $totalAbsen["Absen"] += $value->Absen;
            $totalAbsen["AbsenIjin"] += $value->AbsenIjin;
            $totalAbsen["CutiTahunan"] += $value->CutiTahunan;
            $totalAbsen["CutiKhusus"] += $value->CutiKhusus;
            $totalAbsen["Ijin60"] += $value->Ijin60;
            $totalAbsen["Covid"] += $value->Covid;
            $totalAbsen["AbsenSakit"] += $value->AbsenSakit;
        }

        $tgl_awal = date("j F Y",strtotime($tgl_awal));
        $tgl_akhir = date("j F Y",strtotime($tgl_akhir));

        $data_return = [
            "success"=>true,
            "message"=>"Success Getting Penilaian Absensi",
            "data"=>[
                "PenilaianAbsensi"=>$penilaianabsensi,
                "Total"=>$totalAbsen,
                "TanggalAwal"=>$tgl_awal,
                "TanggalAkhir"=>$tgl_akhir
            ]
        ];
        // dd($data_return);
        return view("Absensi.PenilaianAbsensi.cetak",compact('data_return'));
    }
}

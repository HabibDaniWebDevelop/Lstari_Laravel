<?php

namespace App\Http\Controllers\Absensi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class PenilaianKehadiranController extends Controller{
    
    public function Index(){
        return view("Absensi.Informasi.PenilaianKehadiran.index");
    }

    public function GetPenilaian(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $data = FacadesDB::connection("erp")
        ->select("
            SELECT
                E.Description Employee,
                D.Description Department,
                H.Description HigherRank,
                E.StartWork,
                SUM(CASE WHEN A.Type = 1 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS A1,
                SUM(CASE WHEN A.Type = 2 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS A2,	
                SUM(CASE WHEN A.Type = 3 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS A3,	
                SUM(CASE WHEN A.Type = 4 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS A4,		
                SUM(CASE WHEN A.Type = 6
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS absenijin,								
                SUM(CASE WHEN A.Type IN ( 5, 13 ) 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS cutitahunan,
                SUM(CASE WHEN A.Type IN ( 7, 8, 9, 10, 11, 12, 15, 16, 17, 18 ) 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS cutikhusus,					
                            SUM((CASE WHEN A.Type IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) 
                    AND A.Active = 'P' THEN 1 ELSE NULL END)) AS total,	
                SUM(CASE WHEN A.Type = 14 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS cutikurang,	
                SUM(CASE WHEN A.Type = 19 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS absensakit,
                SUM(CASE WHEN A.Type = 20 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS covid,
                SUM(CASE WHEN A.Type = 21 
                    AND A.Active = 'P' THEN 1 ELSE NULL END) AS terlambat_lebih_jam_9,
                E.WorkRole
            FROM
                Employee E
                JOIN Department D ON E.Department = D.ID
                JOIN Department H ON D.HigherRank = H.ID
                JOIN Absent A ON A.Employee = E.ID
                JOIN AbsentItem I ON A.ID = I.IDM 
            WHERE I.TransDate BETWEEN '$tgl_awal' AND '$tgl_akhir' AND E.Active = 'Y'
            GROUP BY
                E.ID
            ORDER BY
                E.Description,
                D.Description
        ");
        return response()->json(["data"=>$data],200); 
    }

}

<?php

namespace App\Http\Controllers\Produksi\Lilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class InfoLemariKaretController extends Controller
{
    public function index()
    {
        $lemari = FacadesDB::connection("erp")
        ->select("SELECT * FROM masterlemari WHERE Location = 51");

        $laci = FacadesDB::connection("erp")
        ->select("SELECT * FROM masterlaci WHERE Location = 51");
        
        return view('Produksi.Lilin.InfoLemariKaret.index', compact('lemari','laci'));
    }


    public function search()
    {
       //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function lemari($lemari,$laci)
    {
        
        $kolom1 = FacadesDB::connection("erp")->select("SELECT
        CASE
            WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 1 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom2 = FacadesDB::connection("erp")->select("SELECT
       CASE
       WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 2
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom3 = FacadesDB::connection("erp")->select("SELECT
       CASE
       WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 3
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom4 = FacadesDB::connection("erp")->select("SELECT
          CASE
          WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 4
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom5 = FacadesDB::connection("erp")->select("SELECT
       CASE
       WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 5
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID"); 

        $kolom6 = FacadesDB::connection("erp")->select("SELECT
        CASE
        WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 6
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom7 = FacadesDB::connection("erp")->select("SELECT
        CASE
        WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 7
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom8 = FacadesDB::connection("erp")->select("SELECT
      CASE
      WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 8 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom9 = FacadesDB::connection("erp")->select("SELECT
          CASE
          WHEN
                A.RubberID IS NOT NULL THEN SUBSTRING( P.SW, 1, 20 )
                        ELSE NULL
            END datamu,
				 CASE
            WHEN
                A.RubberID IS NOT NULL THEN A.RubberID
                        ELSE NULL
            END ahahaha,
				CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ) lokasi,
        CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 9
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        // dd($kolom1);
        //return response()->json(["data"=>$kolom9],200);
        $returnHTML = view('Produksi.Lilin.InfoLemariKaret.lalala', compact('kolom9','kolom8','kolom7','kolom6','kolom5','kolom4','kolom3','kolom2','kolom1',))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
        // return response()->json([$kolom9,$kolom8,$kolom7,$kolom6,$kolom5,$kolom4,$kolom3,$kolom2,$kolom1],200);
        // return view('R&D.DivisiLilin.InfoLemariKaret.lalala', compact('kolom1','kolom2','kolom3','kolom4','kolom5','kolom6','kolom7','kolom8','kolom9'));
    }

    
    public function print($lemari){
        $infolemari = FacadesDB::connection("erp")
        ->select("SELECT Description FROM masterlemari WHERE Location = 51 AND ID=$lemari");

        $kolom1 = FacadesDB::connection("erp")->select("SELECT
       CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
        CASE
                
                WHEN A.Active = 'O' THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID
            LEFT JOIN rubber F ON F.ID = A.RubberID
            LEFT JOIN product P ON P.ID = F.Product 
        WHERE
            A.LemariID = $lemari 
         
            AND A.KolomID = 1 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom2 = FacadesDB::connection("erp")->select("SELECT
     CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
                CASE
                
                WHEN A.Active = 'o' THEN
                CONCAT('<button class=\"btn btn-warning p-0\">') ELSE 1 
            END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
            
            AND A.KolomID = 2 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom3 = FacadesDB::connection("erp")->select("SELECT
     CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE
                    
                    WHEN A.Active = 'O' THEN
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
             
            AND A.KolomID = 3 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom4 = FacadesDB::connection("erp")->select("SELECT
    CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE
                    
                    WHEN A.Active = 'O' THEN
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
           
            AND A.KolomID = 4 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom5 = FacadesDB::connection("erp")->select("SELECT
     CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE
                    
                    WHEN A.Active = 'O' THEN
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
            
            AND A.KolomID = 5 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");      

        $kolom6 = FacadesDB::connection("erp")->select("SELECT
      CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE
                    
                    WHEN A.Active = 'O' THEN
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
             
            AND A.KolomID = 6 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom7 = FacadesDB::connection("erp")->select("SELECT
    CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE
                    
                    WHEN A.Active = 'O' THEN
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
             
            AND A.KolomID = 7 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom8 = FacadesDB::connection("erp")->select("SELECT
    CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE
                    
                    WHEN A.Active = 'O' THEN
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
        A.LemariID = $lemari 
            
            AND A.KolomID = 8 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        $kolom9 = FacadesDB::connection("erp")->select("SELECT
     CASE
                WHEN
                    A.RubberID IS NOT NULL THEN
                        CONCAT(
                            SUBSTRING( P.SW, 1, 8 ), '<BR>', A.RubberID,
                            CONCAT('<BR><div style=\"font-size: 10px;\"', B.SW, '-<b>', C.SW, '</b>-', D.SW, '-', E.SW ))
                            ELSE CONCAT ('&nbsp; <BR><div style=\"font-size: 10px;\"> Kosong </div> &nbsp;' )
                END datamu,
        CONCAT( '<BR>', B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
            CASE
                    
                    WHEN A.RubberID IS NULL THEN
                    0 ELSE 1 
                END AS Available,
            CASE    
                    WHEN A.Active = 'O' THEN -- O artinya Out 
                    0 ELSE 1 
                END AS Available,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID
                LEFT JOIN rubber F ON F.ID = A.RubberID
                LEFT JOIN product P ON P.ID = F.Product 
            WHERE
            A.LemariID = $lemari 
            
            AND A.KolomID = 9 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        return view('Produksi.Lilin.InfoLemariKaret.Print', compact('infolemari','kolom9','kolom8','kolom7','kolom6','kolom5','kolom4','kolom3','kolom2','kolom1'));

    }

}
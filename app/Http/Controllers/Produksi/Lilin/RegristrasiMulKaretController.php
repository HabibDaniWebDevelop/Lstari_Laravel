<?php

namespace App\Http\Controllers\RnD\DivisiLilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class RegristrasiMulKaretController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $lemari = FacadesDB::connection("erp")
        ->select("SELECT * FROM masterlemari WHERE Location = 51");

        $laci = FacadesDB::connection("erp")
        ->select("SELECT * FROM masterlaci WHERE Location = 51");

        return view('R&D.DivisiLilin.RegristrasiMulKaret.index', compact('lemari', 'laci'));
    }

    public function CariBarkode()
    {
        $data = FacadesDB::connection('dev')->select(
            "SELECT
                P.SW,
                P.Description 
             FROM
                rubber A
                JOIN product P ON P.ID = A.Product"
            );
        $result= FacadesDB::connection('dev');
                                                                                                                                                                                                                                                        return ($data);
    }

    public function Searchh($idkaret)
    {
        
        $result = FacadesDB::connection('dev')
        ->select("SELECT
        P.SW,
        P.Description 
    FROM
        rubber A
        JOIN product P ON P.ID = A.Product 
    WHERE
        A.ID = $idkaret
        ");

        $row = mysqli_fetch_array($result);
        //$rows = mysqli_num_rows($result);

            $returnHTML = view('R&D.DivisiLilin.RegristrasiMulKaret.index', compact('data','row',))->render();
            return response()->json(array('success' => true, 'desk' => $returnHtml['Description'], 'kode' =>  $returnHtml['SW'],'status' => 'OK'));
            
            //return response()->json( array('status' => 'gagal'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = FacadesDB::connection('dev');
        return view('R&D.DivisiLilin.RegristrasiMulKaret.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function list()
    {
        $tablename  = 'workallocation';
        $UserID     = '327';
        $Module     = '166';
        $carilists = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);

        // dd($carilists);

        return view('R&D.DivisiLilin.RegristrasiMulKaret.data', compact('carilists'));
    }

    public function lemari($lemari,$laci)
    {

        $kolom1 = FacadesDB::connection("erp")->select("SELECT
        CASE
                
        WHEN
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
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
                A.RubberID IS NULL THEN
                CONCAT(
                        '<button class=\"btn btn-dark p-1 m-1\" style=\"width: 110px\">',
                        CONCAT( B.SW, '-', C.SW, '-', D.SW, '-', E.SW ),'</button>' 
                  ) ELSE '<button class=\"btn btn-warning p-1 m-1\" style=\"width: 110px\">Terisi</button>' 
            END datamu,
            CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
        CASE
                
                WHEN A.RubberID IS NULL THEN
                0 ELSE 1 
            END AS Available,
            A.ID 
        FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci
            AND A.KolomID = 9 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

        
        $returnHTML = view('R&D.DivisiLilin.RegristrasiMulKaret.showLemari', compact('kolom9','kolom8','kolom7','kolom6','kolom5','kolom4','kolom3','kolom2','kolom1',))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }
}
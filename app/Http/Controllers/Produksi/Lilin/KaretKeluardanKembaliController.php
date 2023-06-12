<?php

namespace App\Http\Controllers\RnD\DivisiLilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class KaretKeluardanKembaliController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FacadesDB::connection('erp')
        ->table("rubberout AS R")
        ->Join("rubberoutitem AS I", function ($join) {
            $join->on("R.ID", "=", "I.IDM");
        })
        ->Join("waxinjectorder AS W", function ($join) {
            $join->on("R.LinkID", "=" ,"W.ID");
        })
        ->leftJoin("rubber AS U", function ($join) {
            $join->on("U.ID", "=", "I.RubberID");
        })
        ->Join("product AS P", function ($join) {
            $join->on("P.ID", "=", "I.Product");
        })
        ->Join("productcarat AS C", function ($join) {
            $join->on("C.ID", "=", "W.carat");
        })
        ->selectRaw('
        R.TransDate,
        I.ReturnDate,
        R.LinkID,
        R.ID,
        I.RubberID AS idKaret,
        W.WorkGroup,
        P.SW AS product,
        C.Description kadar 
        ',)
        ->Paginate(50);
        
        return view('R&D.DivisiLilin.KaretKeluardanKembali.index', compact('data'));
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
    public function create()
    {
        $data = FacadesDB::connection('dev');
        return view('R&D.DivisiLilin.KaretKeluardanKembali.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //->table("hardware AS a")
        $data = FacadesDB::connection('erp')->leftJoin("data_cpu AS b", function ($join) {
            $join->on("a.cpu", "=", "b.id");
        })
        ->leftJoin("department AS c", function ($join) {
            $join->on("a.department", "=", "c.id");
        })
        ->selectRaw('
                A.ID,
        CASE

                WHEN B.ComputerName IS NULL THEN
                "-" ELSE B.ComputerName
            END AS ComputerName,
            A.SW,
            CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.Var4 ) AS Description,
            A.Brand,
            A.Var4 AS PORT,
            A.Series,
        CASE

                WHEN A.SerialNo IS NULL THEN
                "-" ELSE A.SerialNo
            END AS SerialNo,
        CASE

                WHEN A.Supplier IS NULL THEN
                "-" ELSE A.Supplier
            END AS Supplier,
        CASE

                WHEN A.PurchaseDate IS NULL THEN
                "-" ELSE A.PurchaseDate
            END AS PurchaseDate,
            A.STATUS,
            A.Note,
            A.EntryDate,
            A.Active
        ',)
        ->where("a.type", "=", "mouse")
        ->orderBy("a.sw", "desc")
        ->Paginate(25);
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

        dd($carilists);

        return view('R&D.DivisiLilin.PermintaanKaret.data', compact('carilists'));
    }
}
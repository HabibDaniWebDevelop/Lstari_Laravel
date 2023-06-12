<?php

namespace App\Http\Controllers\IT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataStorageController extends Controller
{
    public function index()
    {
        $data = FacadesDB::connection('dev')
            ->table('hardware AS a')
            ->leftJoin('data_cpu AS b', function ($join) {
                $join->on('a.cpu', '=', 'b.id');
            })
            ->leftJoin('data_cpu AS c', function ($join) {
                $join->on('a.cpu', '=', 'c.id');
            })
            ->selectRaw(
                '
                A.ID,
        CASE

                WHEN B.ComputerName IS NULL THEN
                "Tidak Ada" ELSE B.ComputerName
            END AS ComputerName,
            A.SW,
            CONCAT( A.SW, " - ", A.Brand, " ", A.SubType, " ", A.Var2 ) AS Description,
            A.Brand,
            A.SubType,
            A.Var2 AS Size,
            A.Var3 AS Interface,
            A.Series,
            A.SerialNo,
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
        ',
            )
            ->where('a.type', '=', 'storage')
            ->orderBy('A.SW', 'asc')
            ->Paginate(25);

        // dd($data);
        return view('IT.MasterDataHardware.DataStorage.index', compact('data'));
    }

    public function search(Request $request)
    {
        $id = $request->id;

        $data = FacadesDB::connection('dev')
        ->table('hardware AS a')
        ->leftJoin('data_cpu AS b', function ($join) {
            $join->on('a.cpu', '=', 'b.id');
        })
            ->leftJoin('data_cpu AS c', function ($join) {
                $join->on('a.cpu', '=', 'c.id');
            })
            ->selectRaw('
                    A.ID,
                CASE

                        WHEN B.ComputerName IS NULL THEN
                        "Tidak Ada" ELSE B.ComputerName
                    END AS ComputerName,
                    A.SW,
                    CONCAT( A.SW, " - ", A.Brand, " ", A.SubType, " ", A.Var2 ) AS Description,
                    A.Brand,
                    A.SubType,
                    A.Var2 AS Size,
                    A.Var3 AS Interface,
                    A.Series,
                    A.SerialNo,
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
            ')
            ->where('a.type', '=', 'storage')
            ->Where(function ($query) use ($id) {
                $query
                ->where('A.SW', 'LIKE', '%'.$id.'%')
                ->orwhere('A.Brand', 'LIKE',  '%'.$id.'%')
                ->orwhere('A.SubType', 'LIKE',  '%'.$id.'%')
                ->orwhere('A.Var2', 'LIKE',  '%'.$id.'%');
            })
            ->orderBy('A.SW', 'asc')
            ->Paginate(50);
            // ->get();

        return view('IT.MasterDataHardware.DataStorage.index', compact('data'));
    }

    public function create()
    {
        $department = FacadesDB::connection('dev')->select("
            SELECT * FROM department ORDER BY Description
        ");
        // dd($department);
        return view('IT.MasterDataHardware.DataStorage.tambah', compact('department'));

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
}

<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataPrinterController extends Controller
{
    public function index()
    {
        $data = FacadesDB::connection('dev')->table("hardware AS a")
        ->leftJoin("data_cpu AS b", function ($join) {
            $join->on("a.cpu", "=", "b.id");
        })
        ->leftJoin("department AS c", function ($join) {
            $join->on("a.department", "=", "c.id");
        })
        ->selectRaw('
                A.ID,
                CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.SerialNo ) AS Description,
            CASE

                    WHEN B.ComputerName IS NULL THEN
                    "Tidak Ada" ELSE B.ComputerName
                END AS ComputerName,
                A.SW,
            CASE

                    WHEN A.Var1 IS NULL THEN
                    "-" ELSE A.Var1
                END AS IPAddress,
                A.SerialNo,
                A.Series,
                A.Note,
                A.EntryDate,
                A.STATUS,
            CASE

                    WHEN A.PurchaseDate IS NULL THEN
                    "-" ELSE A.PurchaseDate
                END AS PurchaseDate,
                A.Brand,
            CASE

                    WHEN A.Supplier IS NULL THEN
                    "-" ELSE A.Supplier
                END AS Supplier,
                A.SubType,
                A.Active,
            CASE

                    WHEN C.Description IS NULL THEN
                    "-" ELSE C.Description
                END AS Department
        ')
        ->where("a.type", "=", "printer")
        ->orderBy("a.sw", "desc")
        ->Paginate(25);
        //  ->paginate($perPage = 25, $columns = ['*'], $pageName = 'users');
        // ->get();
        $isi='';
        foreach ($data as $data1) {
            // $isi .= $data1->all;
            // $a= $paginator->count();
        }

        // dd();

        return view('IT.MasterDataHardware.DataPrinter.index', compact('data'));
    }

    public function search(Request $request)
    {
        $id = $request->id;
        $data = FacadesDB::connection('dev')->table("hardware AS a")
        ->leftJoin("data_cpu AS b", function ($join) {
            $join->on("a.cpu", "=", "b.id");
        })
            ->leftJoin("department AS c", function ($join) {
                $join->on("a.department", "=", "c.id");
            })
            ->selectRaw('
                A.ID,
                CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.SerialNo ) AS Description,
            CASE

                    WHEN B.ComputerName IS NULL THEN
                    "Tidak Ada" ELSE B.ComputerName
                END AS ComputerName,
                A.SW,
            CASE

                    WHEN A.Var1 IS NULL THEN
                    "-" ELSE A.Var1
                END AS IPAddress,
                A.SerialNo,
                A.Series,
                A.Note,
                A.EntryDate,
                A.STATUS,
            CASE

                    WHEN A.PurchaseDate IS NULL THEN
                    "-" ELSE A.PurchaseDate
                END AS PurchaseDate,
                A.Brand,
            CASE

                    WHEN A.Supplier IS NULL THEN
                    "-" ELSE A.Supplier
                END AS Supplier,
                A.SubType,
                A.Active,
            CASE

                    WHEN C.Description IS NULL THEN
                    "-" ELSE C.Description
                END AS Department
        ')
            ->where("a.type", "=", "printer")
            ->Where(function ($query) use ($id) {
                $query
                    ->where('A.SW', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.Brand', 'LIKE',  '%' . $id . '%')
                    ->orwhere('A.Series', 'LIKE',  '%' . $id . '%')
                    ->orwhere('A.SerialNo', 'LIKE',  '%' . $id . '%');
            })
            ->orderBy("a.sw", "desc")
            ->Paginate(50);
        // ->get();

        return view('IT.MasterDataHardware.DataPrinter.index', compact('data'));
    }

    public function cetak(Request $request)
    {
        $id = $request->id;
        $data = FacadesDB::connection('dev')->select("
            SELECT*FROM hardware WHERE Type='Printer' AND ID = $id
        ");
        return view('IT.MasterDataHardware.DataPrinter.cetak', compact('data', 'id'));
    }

    public function create()
    {
        $department = FacadesDB::connection('dev')->select("
            SELECT * FROM department ORDER BY Description
        ");
        // dd($department);
        return view('IT.MasterDataHardware.DataPrinter.tambah', compact('department'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        dd('tes');

    }

    public function edit($id)
    {
        // dd($id);
        $data1 = FacadesDB::connection('dev')->select("
            SELECT
                B.Description AS Department,
                A.Brand,
                A.SubType,
                A.Var1,
                A.Series,
                A.SerialNo,
                A.Supplier,
                A.PurchaseDate,
                A.STATUS,
                A.Note
            FROM
                hardware A
                LEFT JOIN department B ON A.Department = B.ID
            WHERE
                A.Type = 'Printer'
                AND A.ID = $id
        ");
        // dd($id);
        $department = FacadesDB::connection('dev')->select("
            SELECT ID, Description FROM department WHERE Type = 'S' ORDER BY Description ASC
        ");
        // dd($department);
        return view('IT.MasterDataHardware.DataPrinter.edit', compact('data1', 'department'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

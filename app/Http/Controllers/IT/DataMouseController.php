<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataMouseController extends Controller
{
    public function index()
    {

        $data = FacadesDB::connection('dev')
        ->table("hardware AS a")
        ->leftJoin("data_cpu AS b", function ($join) {
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

        $isi='';
        foreach ($data as $data1) {
        }

        return view('IT.MasterDataHardware.DataMouse.index', compact('data'));
    }

    public function search(Request $request)
    {

        $id = $request->id;
        $data = FacadesDB::connection('dev')
            ->table('hardware AS a')
            ->leftJoin('data_cpu AS b', function ($join) {
                $join->on('a.cpu', '=', 'b.id');
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
           ')
                ->where('a.type', '=', 'mouse')
                ->Where(function ($query) use ($id) {
                    $query
                        ->where('A.SW', 'LIKE', '%' . $id . '%')
                        ->orwhere('A.Brand', 'LIKE', '%' . $id . '%')
                        ->orwhere('A.Series', 'LIKE', '%' . $id . '%')
                        ->orwhere('A.SubType', 'LIKE', '%' . $id . '%');
                })
        ->orderBy('a.SW', 'desc')
        ->Paginate(25);
        $count = count($data);
        // dd($count);

        return view('IT.MasterDataHardware.DataMouse.index', compact('data'));
    }
    public function edit($id)
    {
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
                A.Type = 'mouse'
                AND A.ID = $id
        ");
        $department = FacadesDB::connection('dev')->select("
            SELECT ID, Description FROM department WHERE Type = 'S' ORDER BY Description ASC
        ");
        // dd($id);
        return view('IT.MasterDataHardware.DataMouse.edit', compact('data1', 'departemen'));
    }
}

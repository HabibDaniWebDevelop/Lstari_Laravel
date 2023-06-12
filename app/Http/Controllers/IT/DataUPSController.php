<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataUPSController extends Controller
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
                    "Tidak Ada" ELSE B.ComputerName
                END AS ComputerName,
                A.SW,
                CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.SerialNo ) AS Description,
                A.Brand,
                A.Series,
                A.SerialNo,
            CASE
                    WHEN A.Supplier IS NULL THEN
                    "-" ELSE A.Supplier
                END AS Supplier,
                A.Var7 AS Voltage,
                A.STATUS,
                A.Note,
                A.Active,
                A.EntryDate,
            CASE
			    WHEN A.PurchaseDate IS NULL THEN "-" ELSE A.PurchaseDate END AS PurchaseDate
        ',)
        ->where("a.type", "=", "ups")
        ->orderBy("a.sw", "desc")
        ->Paginate(25);

        $isi='';
        foreach ($data as $data1) {
        }
        //dd($data);
        return view('IT.MasterDataHardware.DataUPS.index', compact('data'));
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
                        "Tidak Ada" ELSE B.ComputerName
                    END AS ComputerName,
                    A.SW,
                    CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.SerialNo ) AS Description,
                    A.Brand,
                    A.Series,
                    A.SerialNo,
                CASE
                        WHEN A.Supplier IS NULL THEN
                        "-" ELSE A.Supplier
                    END AS Supplier,
                    A.Var7 AS Voltage,
                    A.STATUS,
                    A.Note,
                    A.Active,
                    A.EntryDate,
                CASE
                    WHEN A.PurchaseDate IS NULL THEN "-" ELSE A.PurchaseDate END AS PurchaseDate
           ')
                ->where('a.type', '=', 'ups')
                ->Where(function ($query) use ($id) {
                    $query
                        ->where('A.SW', 'LIKE', '%' . $id . '%')
                        ->orwhere('A.Brand', 'LIKE', '%' . $id . '%')
                        ->orwhere('A.Series', 'LIKE', '%' . $id . '%')
                        ->orwhere('A.SubType', 'LIKE', '%' . $id . '%');
                })
        ->orderBy('a.SW', 'desc')
        ->Paginate(50);
        $count = count($data);
        // dd($count);
        // dd($data);
        return view('IT.MasterDataHardware.DataUPS.index', compact('data'));
    }
    public function edit($id)
    {
        
        $data1 = FacadesDB::connection('dev')->select("
        SELECT
            B.ID,
            A.Brand,
            A.Series,
            A.SerialNo,
            A.Var7 AS Voltage,
            A.Supplier,
            A.PurchaseDate,
            A.STATUS,
            A.Note,
            A.EntryDate 
        FROM
            hardware A
            LEFT JOIN data_cpu B ON A.CPU = B.ID
        WHERE
            A.Type = 'ups' 
            AND A.ID = $id
        ");
        $department = FacadesDB::connection('dev')->select("
            SELECT ID, Description FROM department WHERE Type = 'S' ORDER BY Description ASC
        ");
        dd($department);
        return view('IT.MasterDataHardware.DataUPS.edit', compact('data1', 'departemen'));
    }
    // public function cetak(Request $request)
    // {
    //     $id = $request->id;
    //     $data = FacadesDB::connection('dev')->select("
    //         SELECT*FROM hardware WHERE Type='Printer' AND ID = $id
    //     ");
    //     return view('IT.MasterDataHardware.DataUPS.cetak', compact('data', 'id'));
    // }
}

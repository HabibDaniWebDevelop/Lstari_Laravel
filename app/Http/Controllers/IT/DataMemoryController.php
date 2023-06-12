<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataMemoryController extends Controller
{
    public function index()
    {
        $data = FacadesDB::connection('dev')
            ->table('hardware AS a')
            ->leftJoin('data_cpu AS b', function ($join) {
                $join->on('a.cpu', '=', 'b.id');
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
                    A.SubType,
                    A.Var2 AS Size,
                    A.Brand,
                    A.Series,
                CASE
                        
                        WHEN A.SerialNo IS NULL THEN
                        "-" ELSE A.SerialNo
                    END AS SerialNo,
                CASE
                        
                        WHEN A.Supplier IS NULL THEN
                        "-" ELSE A.Supplier
                    END AS Supplier,
                    A.STATUS,
                CASE
                        
                        WHEN A.PurchaseDate IS NULL THEN
                        "-" ELSE A.PurchaseDate
                    END AS PurchaseDate,
                    A.Note,
                    A.EntryDate,
                    A.Active
            ',
            )
            ->where('a.type', '=', 'memory')
            ->orderBy('SW', 'desc')
            ->Paginate(25);

        // dd($data);
        return view('IT.MasterDataHardware.DataMemory.index', compact('data'));
    }

    public function search(Request $request)
    {
        $id = $request->id;
        $data = FacadesDB::connection('dev')
            ->table('hardware AS a')
            ->leftJoin('data_cpu AS b', function ($join) {
                $join->on('a.cpu', '=', 'b.id');
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
                    A.SubType,
                    A.Var2 AS Size,
                    A.Brand,
                    A.Series,
                CASE
                        
                        WHEN A.SerialNo IS NULL THEN
                        "-" ELSE A.SerialNo
                    END AS SerialNo,
                CASE
                        
                        WHEN A.Supplier IS NULL THEN
                        "-" ELSE A.Supplier
                    END AS Supplier,
                    A.STATUS,
                CASE
                        
                        WHEN A.PurchaseDate IS NULL THEN
                        "-" ELSE A.PurchaseDate
                    END AS PurchaseDate,
                    A.Note,
                    A.EntryDate,
                    A.Active
            ',
            )
            ->where('a.type', '=', 'memory')
            ->Where(function ($query) use ($id) {
                $query
                    ->where('A.SW', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.Brand', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.SubType', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.Var2', 'LIKE', '%' . $id . '%');
            })
            ->orderBy('SW', 'desc')
            ->Paginate(50);

        // dd($data);
        return view('IT.MasterDataHardware.DataMemory.index', compact('data'));
    }
}

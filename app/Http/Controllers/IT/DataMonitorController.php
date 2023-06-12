<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataMonitorController extends Controller
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
                    CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.Var5 ) AS Description,
                    A.Brand,
                    A.Var5 AS Dimention,
                    A.Series,
                    A.STATUS,
                    A.EntryDate,
                CASE
                        
                        WHEN A.Supplier IS NULL THEN
                        "-" ELSE A.Supplier
                    END AS Supplier,
                CASE
                        
                        WHEN A.PurchaseDate IS NULL THEN
                        "-" ELSE A.PurchaseDate
                    END AS PurchaseDate,
                    A.Note,
                    A.SerialNo,
                    A.Active
            ',
            )
            ->where('a.type', '=', 'monitor')
            ->orderBy('SW', 'desc')
            ->Paginate(25);

        // dd($data);
        return view('IT.MasterDataHardware.DataMonitor.index', compact('data'));
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
                    CONCAT( A.SW, " - ", A.Brand, " ", A.Series, " ", A.Var5 ) AS Description,
                    A.Brand,
                    A.Var5 AS Dimention,
                    A.Series,
                    A.STATUS,
                    A.EntryDate,
                CASE
                        
                        WHEN A.Supplier IS NULL THEN
                        "-" ELSE A.Supplier
                    END AS Supplier,
                CASE
                        
                        WHEN A.PurchaseDate IS NULL THEN
                        "-" ELSE A.PurchaseDate
                    END AS PurchaseDate,
                    A.Note,
                    A.SerialNo,
                    A.Active
            ',
            )
            ->where('a.type', '=', 'monitor')
            ->Where(function ($query) use ($id) {
                $query
                    ->where('A.SW', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.Brand', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.Series', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.Var5', 'LIKE', '%' . $id . '%');
            })
            ->orderBy('SW', 'desc')
            ->Paginate(50);

        // dd($data);
        return view('IT.MasterDataHardware.DataMonitor.index', compact('data'));
    }
}
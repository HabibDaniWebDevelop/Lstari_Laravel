<?php

namespace App\Http\Controllers\RnD\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;
use App\Models\erp\product;
use App\Models\erp\worksuggestion;
use App\Models\erp\worksuggestionitem;


class CheckFormOrderController extends Controller
{
    public function index() {
        $fo = FacadesDB::connection('erp')
        ->table('worksuggestion as ws')
        ->join('worksuggestionitem AS wsi', function($join){
            $join->on("ws.ID","=","wsi.IDM");
        })
        ->join('product AS p', function($join){
            $join->on("wsi.Product","=","p.ID");
        })
        ->join('product AS pt', function($join){
            $join->on("p.Model","=","pt.ID");
        })
        ->selectRaw('
            pt.SW,
            p.Model,
            p.SKU,
            p.SerialNo,
            p.EnamelGroup,
            ws.ID IDFormOrder
        ')
        ->whereNull('ws.ProductChecked')
        ->whereNull('p.IsChecked')
        ->whereNotNull('p.EnamelGroup')
        ->whereNotNull('p.SKU')
        ->where('ws.TransDate','>','2023-03-15')
        ->groupBy('p.ID')
        ->orderBy('ws.ID', 'DESC')
        ->get();

        return view("R&D.Informasi.CheckFormOrder.index", compact('fo'));
    }
}
?>
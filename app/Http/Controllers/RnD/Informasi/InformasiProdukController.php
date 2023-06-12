<?php

namespace App\Http\Controllers\RnD\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;
use App\Models\rndnew\product;
use App\Models\rndnew\productkepala;
use App\Models\rndnew\productmn;
use App\Models\rndnew\productcomponent;
use App\Models\rndnew\masterkepala;
use App\Models\rndnew\mastermainan;
use App\Models\rndnew\mastercomponent;
use App\Models\rndnew\designsize;


class InformasiProdukController extends Controller
{
    // Index function
    public function index() {
        //$datenow = date('Y-m-d');

        $kepala = FacadesDB::table('masterkepala')
                ->select('ID', 'SKU', 'Part')
                ->where('Header', '=', '94')
                ->groupBy('Header', 'SerialNo', 'Size')
                ->get();
        return view("R&D.Informasi.InformasiProduk.index", compact('kepala'));
    }

    public function GetListProdukKomponen(Request $request) {
        $html = '';
        $data = FacadesDB::table('mastercomponent AS mc')
        ->join('productcomponent AS pc', function($join){
            $join->on("mc.ID","=","pc.Component");
        })
        ->join('product AS p', function($join){
            $join->on("pc.IDM","=","p.ID");
        })
        ->join('product AS pt', function($join){
            $join->on("pt.ID","=","p.Model");
        })
        ->join('designsize AS dz', function($join){
            $join->on("p.VarSize","=","dz.ID");
        })
        ->selectRaw("
           p.SW,
           pt.SW as Model,
           p.SerialNo,
           p.VarSize,
           dz.SW Ukuran
        ")
        ->where('mc.SW','LIKE',$request->subka.'%')
        ->where('mc.SerialNo', '=',$request->noseri)
        ->groupBy('pt.SW')
        ->groupBy('p.SerialNo')
        ->groupBy('p.VarSize')
        ->orderBy("p.SW","ASC")
        ->get();

        //->paginate(30);

        $no = 1;    
        foreach ($data as $key => $value) {
           $html .= '<tr>';
                $html .= '<td align="center">
                                <b>'.$no++.'</b>
                            </td>';
                $html .= '<td align="center">
                                <b>'.$value->Model.'</b>';
                $html .= '</td>';
                $html .= '<td align="center">
                                <b>'.$value->SerialNo.'</b>';
                $html .= '</td>';
                $html .= '<td align="center">
                                <b>'.$value->Ukuran.'</b>';
                $html .= '</td>';                
            $html .= '</tr>';
        }
        // Setup Return Data
        $data_return = [
            "html" => $html
        ];
        return response()->json($data_return,200);

        // dd($query);

        //return view('R&D.Informasi.InformasiProduk.component', compact('query'));
    }

    public function GetListProdukMainan(Request $request) {
        $html = '';
        $data = FacadesDB::table('mastermainan AS mc')
        ->join('productmn AS pc', function($join){
            $join->on("mc.ID","=","pc.Mainan");
        })
        ->join('product AS p', function($join){
            $join->on("pc.IDM","=","p.ID");
        })
        ->join('product AS pt', function($join){
            $join->on("pt.ID","=","p.Model");
        })
        ->join('designsize AS dz', function($join){
            $join->on("p.VarSize","=","dz.ID");
        })
        ->selectRaw("
           p.SW,
           pt.SW as Model,
           p.SerialNo,
           p.VarSize,
           dz.SW Ukuran
        ")
        ->where('mc.SW','LIKE',$request->subka.'%')
        ->where('mc.SerialNo', '=',$request->noseri)
        ->groupBy('pt.SW')
        ->groupBy('p.SerialNo')
        ->groupBy('p.VarSize')
        ->orderBy("p.SW","ASC")
        ->get();

        //->paginate(30);

        $no = 1;    
        foreach ($data as $key => $value) {
           $html .= '<tr>';
                $html .= '<td align="center">
                                <b>'.$no++.'</b>
                            </td>';
                $html .= '<td align="center">
                                <b>'.$value->Model.'</b>';
                $html .= '</td>';
                $html .= '<td align="center">
                                <b>'.$value->SerialNo.'</b>';
                $html .= '</td>';
                $html .= '<td align="center">
                                <b>'.$value->Ukuran.'</b>';
                $html .= '</td>';                
            $html .= '</tr>';
        }
        // Setup Return Data
        $data_return = [
            "html" => $html
        ];
        return response()->json($data_return,200);

        // dd($query);

        //return view('R&D.Informasi.InformasiProduk.component', compact('query'));
    }

    public function GetListProdukKepala(Request $request) {
        $html = '';
        $data = FacadesDB::table('masterkepala AS mc')
        ->join('productkepala AS pc', function($join){
            $join->on("mc.ID","=","pc.Kepala");
        })
        ->join('product AS p', function($join){
            $join->on("pc.IDM","=","p.ID");
        })
        ->join('designsize AS dz', function($join){
            $join->on("p.VarSize","=","dz.ID");
        })
        ->selectRaw("
           p.SW,
           p.SW as Model,
           p.SerialNo,
           dz.SW Ukuran
        ")
        ->where('mc.SW','LIKE',$request->subka.'%')
        ->where('mc.SerialNo','=',$request->noseri)
        ->whereNull('LinkID')
        ->groupBy('p.Model')
        ->groupBy('p.SerialNo')
        ->groupBy('p.VarSize')
        ->orderBy("p.SW","ASC")
        ->get();

        $no = 1;    
        foreach ($data as $key => $value) {
           $html .= '<tr>';
                $html .= '<td align="center">
                                <b>'.$no++.'</b>
                            </td>';
                $html .= '<td align="center">
                                <b>'.$value->Model.'</b>';
                $html .= '</td>';
                $html .= '<td align="center">
                                <b>'.$value->SerialNo.'</b>';
                $html .= '</td>';
                $html .= '<td align="center">
                                <b>'.$value->Ukuran.'</b>';
                $html .= '</td>';
            $html .= '</tr>';
        }
        // Setup Return Data
        $data_return = [
            "html" => $html
        ];
        return response()->json($data_return,200);

        // dd($query);

        //return view('R&D.Informasi.InformasiProduk.component', compact('query'));
    }
}
?>
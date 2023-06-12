<?php

namespace App\Http\Controllers\Penjualan\MPC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Http\Controllers\Public_Function_Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\rndnew\productcarat;
use App\Models\erp\product;
use App\Models\erp\productsize;

use App\Models\rndnew\worksuggestion;
use App\Models\rndnew\worksuggestionitem;


class FormOrderProduksiDCController extends Controller{
    public function __construct(Public_Function_Controller $Public_Function_Controller) {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function simpanws(Request $request){
        $tahun = (int)date('y');
        $bln = date('n');
        $bulan = date('m');

        $jumlahBaris = count($request->idprod);

        $produk = $request->idprod;

        //var_dump($request->idprod);

        $qty = $request->qty;

        $username = Auth::user()->name;
        $kadar = $request->kadar;

        $lastid = FacadesDB::select(
            "SELECT
            CASE                
                WHEN MAX( SWOrdinal ) IS NULL THEN  CONCAT( '$tahun', '$bulan', '0001' ) 
                ELSE CONCAT( SWYear, LPAD( SWMonth, 2, '0' ), LPAD(MAX( SWOrdinal )+ 1, 4, '0')  )
            END AS LastID,
            CASE            
                WHEN MAX( SWOrdinal ) IS NULL THEN '0001' 
                ELSE MAX( SWOrdinal )+ 1
            END AS LastOrdinal
            FROM
                worksuggestion
            WHERE
                SWYear = '$tahun' AND SWMonth = '$bln'"
        );
        if ($lastid) {
            $lastSWOrdinal = $lastid[0]->LastID;
            $lastOrdinal = $lastid[0]->LastOrdinal;
        }

        $insert_worksuggestion = worksuggestion::create([
                'ID' => $lastSWOrdinal,
                'UserName' => $username,
                'TransDate' => now(),
                'Active' => 'A',
                'Purpose' => 'Stock',
                'Period' => 'Jun',
                'Urgent' => 'N',
                'SWYear' => $tahun,
                'SWMonth' => $bln,
                'SWOrdinal' => $lastOrdinal,
            ]);
        if ($insert_worksuggestion) {
            
            for ($i=0; $i < $jumlahBaris; $i++) { 

                $exp = explode('-',$produk[$i]);

                $getProduct = FacadesDB::connection('erp')
                            ->table('product as p')
                            ->join('productsize as pz', function($join){
                                $join->on("pz.IDM","=","p.ID");
                            })
                            ->selectRaw('p.ID, p.Model, p.VarCarat, pz.Weight')
                            ->where('p.EnamelGroup',$exp[0])
                            ->where('p.VarStone',$exp[1])
                            ->where('p.VarEnamel',$exp[2])
                            ->where('p.VarSlep',$exp[3])
                            ->where('p.VarMarking',$exp[4])
                            ->where('p.VarCarat',$exp[5])
                            ->get();

                    $beratperkiraan = $getProduct[0]->Weight * $qty[$i];

                    $insert_worksuggestionitem = worksuggestionitem::create([
                        'IDM' => $lastSWOrdinal,
                        'Ordinal' => $i+1,
                        'Product' => $getProduct[0]->ID,
                        'FinishGood' => $getProduct[0]->Model,
                        'Carat' => $getProduct[0]->VarCarat,
                        'Qty' => $qty[$i],
                        'Weight' => $beratperkiraan,
                        'Customer'=> 'TTS'
                    ]); 
                }    
        }
    }

    public function index(){

        $carats = FacadesDB::table('productcarat')
                ->select('ID', 'SKU', 'Alloy')
                ->where('Regular', '=', 'Y')
                ->get();
        //  return view("Penjualan.MPC.FormOrderProduksi.index", compact('carats'));


        $fo = FacadesDB::connection('erp')
        ->table('productcategory as pc')
        ->join('product as p', function($join){
            $join->on("pc.ProductID","=","p.ID");
        })
        ->selectRaw('
            pc.ProductID,
            pc.SW,
            pc.Description
        ')        
        ->where('pc.Active','=','1')
        ->get();
        return view('Penjualan.MPC.FormOrderProduksi.index' , compact('fo', 'carats'));
    }

    public function cekProduk(Request $request)  {

        $html ='';

        //$idproduk = product::where('EnamelGroup',$request->idprod)->where('VarStone',$request->varstone)->


        $cekProd = product::where('EnamelGroup','=',$request->idprod)
                ->where('Varstone','=',$request->varstone)
                ->where('VarEnamel','=',$request->varenamel)
                ->where('VarMarking','=',$request->varmarking)
                ->where('VarCarat','=',$request->kadar)
                ->where('VarSlep','=',$request->varslep)
                ->get();

        //$html =  view('Penjualan.MPC.FormOrderProduksi.addcart', compact('cekProd'))->render();

        /*$cekProd = FacadesDB::select("
                SELECT
                    * 
                FROM
                    erp.product
                WHERE
                    EnamelGroup = $request->idprod 
                    AND VarStone = $request->varstone 
                    AND VarEnamel = $request->varenamel 
                    AND VarMarking = $request->varmarking 
                    AND VarSlep = $request->varslep 
                    AND VarCarat = $request->kadar
            ");*/

        $data = [
            'sku'=>$cekProd[0]->SKU,
            'idprod'=>$cekProd[0]->ID
        ];
        return response()->json($data, 200);
    }

    public function listProduk(Request $request) {

        $html = '';

        $getList = FacadesDB::select(
            "SELECT
                p.SW, 
                REPLACE(p.Photo,'.jpg','') as Photo,
                pc.SW Subka,
                pc.Description DescSubka,
                p.SerialNo,
                p.EnamelGroup,
                ds.SW VarSize,
                p.VarStone,
                p.VarEnamel,
                p.VarSlep,
                p.VarMarking,
                st.Type VarEnamel2,
                bt.Description as VarBatu,
                $request->kadar as Kadar,
                CASE 
                    WHEN GROUP_CONCAT(CASE 
                                        WHEN pcar.ID IS NULL THEN '0'
                                        ELSE '1'
                                    END) LIKE '%1%' THEN
                    'Available'
                    ELSE
                    'Not Able'
                END as Stat
            FROM
                erp.product p 
                JOIN erp.product pc ON p.Model = pc.ID
                JOIN designsize ds ON p.VarSize = ds.ID
                JOIN shorttext st ON p.VarEnamel = st.ID
                JOIN shorttext bt ON p.VarStone = bt.ID
                LEFT JOIN (SELECT * FROM productcarat WHERE ID = $request->kadar)pcar ON p.VarCarat = pcar.ID 
            WHERE
                p.Model = $request->idsubka AND p.SerialNo BETWEEN $request->noawal AND $request->noakhir AND p.EnamelGroup IS NOT NULL AND p.Type = 'F' AND p.Active = 'Y'
            GROUP BY p.SerialNo, p.VarSize, p.VarStone, p.VarEnamel, p.VarSlep, p.VarMarking"
        );        

        $html = view('Penjualan.MPC.FormOrderProduksi.listsubka', compact('getList'))->render();

        $data = [
            'html'=>$html
        ];

        return response()->json($data, 200);

        /*$html .= '<div class="row">';

        foreach ($getList as $value) {
            $html .= '<div class="col-3">
                        <div class="card border-dark mb-3" style="text-align:center;">';
            $html .= '       <img src="http://192.168.3.100:8383/image/'.$value->Photo.'.jpg" width="50px" align="center" height="auto" style="object-fit: cover; margin: 0 auto;" class="card-img-top" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></img>';
                    $html .= '<div class="card-body">
                                <span>'.$value->SerialNo.'</span>
                            </div>';
            $html .= '  </div>';
            $html .= '</div>';

        $html .= '</div>';

        $data_return = [
            "html" => $html
        ];
        return response()->json($data_return,200);*/
    }
}

?>

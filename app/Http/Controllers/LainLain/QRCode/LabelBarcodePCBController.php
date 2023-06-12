<?php

namespace App\Http\Controllers\LainLain\QRCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class LabelBarcodePCBController extends Controller
{
    public function index()
    {
        return view('Lain-Lain.QRCode.LabelBarcodePCB.index');
    }

    public function generate($no, $id)
    {

        if ($no == 1) {
            return view('Lain-Lain.QRCode.LabelBarcodePCB.detail');
        } elseif ($no == 2) {
            return view('Lain-Lain.QRCode.LabelBarcodePCB.sku');
        } elseif ($no == 3) {
            return view('Lain-Lain.QRCode.LabelBarcodePCB.cetak1', compact('id'));
        } elseif ($no == 4) {

            //meng uraikan input form
            $id2 = preg_split('/&/', $id);
            $id2 = str_replace("[]", "", $id2);
            $a=1; $b=0;
            for ($i = 0; $i < count($id2); $i++) {
                
                $id3 = preg_split('/=/', $id2[$i]);

                if ($id3[0] != 'no') {
                    $b++;
                    $datas[$a][$b] = $id3[1];

                    if ($b == 6) {
                        $a++;
                        $b = 0;
                    } 
                }
                
            }

            // dd($id, $id2, $id3, $datas );
            return view('Lain-Lain.QRCode.LabelBarcodePCB.cetak2', compact('datas'));
        } elseif ($no == 5) {

            // dd('fygrybr');

            $data = FacadesDB::select("SELECT
                    p.ID,
                    p.SW,
                    p.SKU,
                    c.Description2 Carat,
                    s.SW ukuran,
                    b.Weight berat,
                    DATE_FORMAT( d2.DesignStart, '%b' ) bulan 
                FROM
                    erp.product p
                    LEFT JOIN drafter2d d2 ON p.EnamelGroup = d2.Product
                    LEFT JOIN productcarat c ON p.VarCarat = c.ID
                    LEFT JOIN designsize s ON p.VarSize = s.ID
                    LEFT JOIN erp.productsize b ON p.ID = b.IDM 
                WHERE
                    p.ID = '$id' OR p.SKU = '$id' OR p.SW = '$id'");

            $get = substr($data[0]->SW, 0, 10);

            if (preg_match("/-/i", $get)) {
                $str_arr = explode("-", $data[0]->SW); 
                $sw = $str_arr[0]."-". $str_arr[1];
            }
            else{
                $str_arr = explode(".", $data[0]->SW);
                $sw = $str_arr[0] . "." . $str_arr[1];
            }

            if ($data[0]->SKU != '') {
                $barcode = $data[0]->SKU;
            } else {
                $barcode = $data[0]->SW;
            }

            if ($data[0]->ukuran == '000') {
                $data[0]->ukuran = '';
            }
            // dd($sw);

            if ($data) {
                return response()->json([
                    'success' => true,
                    'barcode' => $barcode,
                    'SW' => $sw,
                    'Carat' => $data[0]->Carat,
                    'ukuran' => $data[0]->ukuran,
                    'berat' => $data[0]->berat,
                    'bulan' => $data[0]->bulan,
                ]);
            }
        }
    }
}

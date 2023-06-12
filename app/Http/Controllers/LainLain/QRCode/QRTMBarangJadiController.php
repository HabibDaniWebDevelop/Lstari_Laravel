<?php

namespace App\Http\Controllers\LainLain\QRCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use PhpParser\Node\Expr\AssignOp\Concat;

class QRTMBarangJadiController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->put('hostfoto', 'http://192.168.1.100:8383');
        return view('Lain-Lain.QRCode.QRTMBarangJadi.index');
    }

    public function generate($no, $id)
    {

        if ($no == 1) {
            $datas = FacadesDB::select(" SELECT
                    p.ID,
                    p.SW,
                    p.SKU,
                    p.Photo,
                    c.Description2 Carat,
                    s.SW ukuran,
                    b.Weight berat,
                    DATE_FORMAT( d2.DesignStart, '%b' ) bulan 
                FROM
                    `workorder` wo
                    INNER JOIN workorderitem woi ON wo.ID = woi.IDM
                    INNER JOIN product p ON woi.Product = p.ID
                    LEFT JOIN drafter2d d2 ON p.ID = d2.Product
                    LEFT JOIN productcarat c ON p.VarCarat = c.ID
                    LEFT JOIN designsize s ON p.VarSize = s.ID
                    LEFT JOIN productsize b ON p.ID = b.IDM 
                WHERE
                    wo.SWPurpose = 'PCB' 
                    AND wo.SW ='$id'
                        
                    ");

            $Count = count($datas);
            //di bulatkan ke atas dan di bagi 2
            $rowcount = ceil($Count / 2);
            // $rowcount = $Count / 2;

            // dd($rowcount);

            return view('Lain-Lain.QRCode.QRTMBarangJadi.show', compact('datas', 'rowcount'));
        } elseif ($no == 2) {

            //meng uraikan input form
            $id2 = preg_split('/&,/', $id);
            $a = 1;
            $b = 0;
            for ($i = 0; $i < count($id2); $i++) {

                $b++;

                if($b == 1 && $id2[$i] == ''){
                    break;
                }else{
                    if ($id2[$i] == 'null') {
                        $id2[$i] = '';
                    }
                    $datas[$a][$b] = $id2[$i];
                }

                if ($b == 6) {
                    $b = 0;
                    $a++;
                }

            }

            // dd($id,$id2, $datas);

            return view('Lain-Lain.QRCode.LabelBarcodePCB.cetak2', compact('datas'));
        }
    }

    public function generate2(Request $request)
    {

        // dd($request);

        $isi = '';
        $i = 0;
        foreach ($request->id as $data1s) {
            $i++;
            // $isi .= $request->Model[$data1s].', ';
            $datas[$i][1] = $request->Model[$data1s];
            $datas[$i][2] = $request->Kadar[$data1s];
            $datas[$i][3] = $request->Berat[$data1s];
            $datas[$i][4] = $request->Ring[$data1s];
            $datas[$i][5] = $request->Bulan[$data1s];
            $datas[$i][6] = $request->SW[$data1s];
        }

        return response()->json([
            'success' => true,
            'datas' => $datas,
        ]);

        // return $this->redirect("http://Lain-Lain.QRCode.LabelBarcodePCB.cetak2, ['target' => '_blank']);

        // return view('Lain-Lain.QRCode.LabelBarcodePCB.cetak2', compact('datas'));

    }

}

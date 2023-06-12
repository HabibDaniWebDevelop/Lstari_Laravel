<?php

namespace App\Http\Controllers\RnD\Grafis;

use Exception;
use Illuminate\Http\Request;

use App\Models\rndnew\grafisspk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB as FacadesDB;

class UploadFoto extends Controller
{
    public function index()
    {

        $data = FacadesDB::connection('erp')
        ->table('workcompletion as a')
        ->select('a.ID', 'a.WorkAllocation', 'a.TransDate', 'a.UserName', 'a.Active', FacadesDB::connection('erp')->raw('(SELECT upload FROM rndnew.grafisspk WHERE workallocation = a.WorkAllocation ORDER BY upload ASC LIMIT 1) AS Upload'))
        ->where('a.Location', '=', '56')
        ->where('a.Operation', '=', '178')
        ->where('a.Active', '=', 'P')
        ->orderBy('a.ID', 'desc')
        ->Paginate(13);
        return view('R&D.Grafis.UploadFoto.index', compact('data'));
    }

    public function search(Request $request){
        $id = $request->id;
        $data = FacadesDB::connection('erp')
            ->table('workcompletion as a')
            ->select('a.ID', 'a.WorkAllocation', 'a.TransDate', 'a.UserName', 'a.Active', FacadesDB::connection('erp')->raw('(SELECT upload FROM rndnew.grafisspk WHERE workallocation = a.WorkAllocation ORDER BY upload ASC LIMIT 1) AS Upload'))
            ->where('a.Location', '=', '56')
            ->where('a.Operation', '=', '178')
            ->where('a.Active', '!=', 'A')
            ->when($id, function ($query, $id) {
                return $query->where('a.WorkAllocation', '=', $id);
            })
            ->groupBy('a.ID')
            ->orderByDesc('a.ID')
            ->Paginate(13);
        // dd($data);
        return view('R&D.Grafis.UploadFoto.index', compact('data'));
    }

    public function show($no, $id)
    {
        // dd($no, $id);

        $cekgrafisspk = FacadesDB::select("SELECT workallocation FROM grafisspk WHERE workallocation = '$id' ");

        if ($cekgrafisspk) {
            // echo"OK";
        } else {
            $data = FacadesDB::select("
                SELECT 
                    gw.Product,
                    REPLACE(dd.ImageOriginal, '.jpg', '') as Photo2D,
                CASE
                        
                        WHEN mss.Color = 'Putih' THEN
                        'Putih' 
                        WHEN mss.Color = 'Merah' THEN
                        'Merah' 
                        WHEN mss.Color = 'Merah Muda' THEN
                        'Merah Muda' ELSE 'Tanpa Batu' 
                    END AS Warna,
                CASE
                        
                        WHEN mss.Color = 'Putih' THEN
                        '1' 
                        WHEN mss.Color = 'Merah' THEN
                        '2' 
                        WHEN mss.Color = 'Merah Muda' THEN
                        '3' ELSE '0' 
                    END AS OrdWarna,
                CASE
                        
                        WHEN ei.OrdinalVariation IS NULL THEN
                        0 ELSE ei.OrdinalVariation 
                    END AS Enamel,
                    ei.OrdinalVariation,
                    CONCAT(
                        pt.SW,
                        '.',
                        p.SerialNo,
                        '.',
                        pc.SKU,
                        '.',
                        pc.Alloy,
                        '.',
                        lg.SW,
                        pc.SNI,
                        pc.Description,
                        '.',
                    CASE
                            
                            WHEN mss.Color = 'Putih' THEN
                            '101' 
                            WHEN mss.Color = 'Merah' THEN
                            '201' 
                            WHEN mss.Color = 'Merah Muda' THEN
                            '301' ELSE '000' 
                        END,
                        '.',
                    CASE
                            
                            WHEN p.VarSlep = 272 THEN
                            '1' 
                            WHEN p.VarSlep = 275 THEN
                            '1' 
                            WHEN p.VarSlep = 276 THEN
                            '1' 
                            WHEN p.VarSlep = 235 THEN
                            '1' 
                            WHEN p.VarSlep = 519 THEN
                            '0' 
                            WHEN p.VarSlep = 520 THEN
                            '1' ELSE '0' 
                        END,
                        '.',
                    CASE
                            
                            WHEN p.VarMarking = 273 THEN
                            '1' 
                            WHEN p.VarMarking = 275 THEN
                            '1' 
                            WHEN p.VarMarking = 277 THEN
                            '1' 
                            WHEN p.VarMarking = 235 THEN
                            '1' 
                            WHEN p.VarMarking = 521 THEN
                            '0' 
                            WHEN p.VarMarking = 522 THEN
                            '1' ELSE '0' 
                        END,
                        '.',
                    CASE
                            
                            WHEN p.VarSepuh = 240 
                            AND ( pc.ID = 1 OR pc.ID = 3 ) THEN
                                '1' 
                                WHEN p.VarSepuh = 240 
                                AND ( pc.ID = 4 ) THEN
                                    '2' ELSE '0' 
                                    END,
                                '.',
                            CASE
                                    
                                    WHEN p.VarPutih = 274 THEN
                                    '1' 
                                    WHEN p.VarPutih = 276 THEN
                                    '1' 
                                    WHEN p.VarPutih = 277 THEN
                                    '1' 
                                    WHEN p.VarPutih = 235 THEN
                                    '1' 
                                    WHEN p.VarPutih = 530 THEN
                                    '0' 
                                    WHEN p.VarPutih = 531 THEN
                                    '1' ELSE '0' 
                                END,
                                '.',
                            CASE
                                    
                                    WHEN p.VarEnamel IS NULL THEN
                                    '00' 
                                    WHEN p.VarEnamel = 238 THEN
                                    '00' ELSE CONCAT( '0', ei.OrdinalVariation ) 
                                END,
                                '.',
                            CASE
                                    
                                    WHEN p.VarSize IS NULL THEN
                                    '000' ELSE dz.SW 
                                END 
                                ) AS SKUFix,
                                wc.WorkAllocation 
                            FROM
                                erp.workcompletion wc
                                INNER JOIN erp.workcompletionitem wci ON wc.ID = wci.IDM
                                INNER JOIN grafisworklist gw ON wc.workallocation = gw.NextWorkAllocation 
                                AND wci.TreeOrd = gw.TreeOrd
                                JOIN product p ON gw.Product = p.ID
                                JOIN product pt ON p.Model = pt.ID
                                JOIN productcarat pc ON gw.Carat = pc.ID
                                JOIN drafter2d dd ON p.ID = dd.Product 
                                AND dd.TypeProcess = 24
                                LEFT JOIN shorttext lg ON dd.Logo = lg.ID
                                LEFT JOIN designsize dz ON p.VarSize = dz.ID
                                LEFT JOIN sepuhitem si ON si.Product = gw.Product 
                                AND si.Weight > 0 
                                AND si.TreeID = gw.TreeID 
                                AND si.TreeOrd = gw.TreeOrd
                                LEFT JOIN sepuhitemstone sis ON si.IDM = sis.IDM 
                                AND si.Ordinal = sis.Ordinal
                                LEFT JOIN masterstone mss ON sis.Product = mss.LinkProduct
                                LEFT JOIN enamelitem ei ON ei.Product = gw.Product 
                                AND ei.Weight > 0
                                AND ei.TreeID = gw.TreeID 
                                AND ei.TreeOrd = gw.TreeOrd
                                LEFT JOIN enamelitemstone eis ON ei.IDM = eis.IDM 
                                AND ei.Ordinal = eis.Ordinal 
                            WHERE
                                wc.workallocation = '$id' AND
				                wc.Active != 'A'
                            GROUP BY
                                gw.Product,
                                mss.Color,
                                ei.OrdinalVariation
                            ORDER BY
                            p.SW,
                    SkuFix
            ");

            foreach ($data as $key => $value) {
                //insert workcompletion
                $insert_grafisspk = grafisspk::create([
                    'WorkAllocation' => $value->WorkAllocation,
                    'Product' => $value->Product,
                    'ImageOriginal' => $value->Photo2D,
                    'SKU' => $value->SKUFix,
                    'Warna' => $value->Warna,
                    'Enamel' => $value->Enamel,
                    'created_at' => now(),
                    'Upload' => 'A',
                ]);
            }
        }

        $data = FacadesDB::select("
                SELECT 
                    g.*,
                    w.TransDate,
                    CONCAT( p.Photo, '.jpg' ) AS gambar 
                FROM
                    erp.workcompletion w
                    LEFT JOIN grafisspk g ON g.WorkAllocation = w.WorkAllocation 
                    INNER JOIN erp.product p ON g.Product = p.ID
                    
                WHERE
                    w.WorkAllocation = '$id'
                    AND w.Active != 'A'
                ORDER BY g.SKU, g.Warna, g.Enamel
                ");

        foreach ($data as $key => $value) {
            if ($value->Upload == 'A') {

                for ($i = 0; $i <= 6; $i++) {
                    if($i == 0){
                        $nama = $value->SKU . '.jpg';
                    } else{
                        $nama = $value->SKU . '-' . $i . '.jpg';
                    }

                    if (Storage::disk('Server_E')->exists('Jpg Form Marketing' . '/' . $nama)) {
                        $update_grafisspk = grafisspk::where('ID', $value->ID)->update([
                            'Upload' => 'S',
                        ]);
                    } 
                }

            }
        }

        // dd($data);

        return view('R&D.Grafis.UploadFoto.show', compact('data', 'id'));
    }

    public function store(Request $request)
    {
        // dd($request);
        //insert ke folder Jpg Form Marketing
        foreach ($request->id as $key => $value) {
            for ($i = 1; $i <= 7; $i++) {
                if ($request->g[$key][$i] != 'undefined') {
                    //Jpg Form Marketing
                    if ($i == '1') {
                        $urut = '';

                        // update last id
                        $update_grafisspk = grafisspk::where('ID', $request->id[$key])->update([
                            'Upload' => 'S',
                        ]);
                    } else {
                        $urut = '-' . $i - 1;
                    }
                    $fileName = $request->sku[$key] . $urut . '.jpg';
                    $lokasi = 'Jpg Form Marketing';
                    // $request->g[$key][$i]->storeAs($lokasi, $fileName, 'UploadGrafis');
                    $request->g[$key][$i]->storeAs($lokasi, $fileName, 'Server_E');
                    $request->g[$key][$i]->storeAs($lokasi, $fileName, 'UploadGambar'); 
                }
            }
        }

        if ($request->loc != null) {
            //insert ke folder grafis
            foreach ($request->loc as $key => $value) {
                foreach ($value as $key2 => $elemen) {
                    $urut = '-' . $key2 + 1;

                    $fileName = $request->sku[$key] . $urut . '.jpg';
                    $lokasi = $request->TransDate . '/' . $request->sku[$key];
                    $elemen->storeAs($lokasi, $fileName, 'UploadGrafis');
                }
            }
        }

        return response(
            [
                'success' => true,
                'message' => 'Berhasil',
            ],
            200,
        );
    }
}

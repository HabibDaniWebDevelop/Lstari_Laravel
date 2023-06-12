<?php

namespace App\Http\Controllers\RnD\Grafis;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Public Function
use App\Http\Controllers\Public_Function_Controller;

use App\Models\erp\lastid;
use App\Models\rndnew\lastid as lastidrnd;

use App\Models\erp\workshrink;
use App\Models\erp\workcompletion;
use App\Models\erp\workcompletionitem;
use App\Models\erp\workallocationresult;

use App\Models\rndnew\grafis;
use App\Models\rndnew\grafisitem;
use App\Models\rndnew\grafisworklist;

class NTHKOGrafis extends Controller
{
    // Setup Public Function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index()
    {
        $carilists = FacadesDB::connection('erp')->select("SELECT ID, SW, Active FROM `workallocation` WHERE Operation='178' AND Active = 'P' ORDER BY ID DESC LIMIT 20");
        // dd($carilists);

        return view('R&D.Grafis.NTHKOGrafis.index', compact('carilists'));
    }

    public function show($no, $id)
    {
        // dd($no, $id);

        //lihat
        if ($no == '1') {
            $data1 = FacadesDB::connection('erp')->select("SELECT
                        w.ID,
                        w.SW,
                        DATE( w.EntryDate ) AS EntryDate,
                        e.Description `name`,
                        w.Employee,
                        w.TargetQty,
                        w.Weight
                    FROM
                        `workallocation` w
                        INNER JOIN employee e ON e.ID = Employee
                    WHERE
                        Location = '56' 
	                    AND Operation = '178' 
                        AND w.SW = '$id'
                        ");


            $listwas = FacadesDB::connection('erp')->select(
                "SELECT
                        wi.TreeID,
                        wi.TreeOrd,
                        wi.Weight
                    FROM
                        workallocationitem wi
                    WHERE
                        wi.IDM = '" .
                    $data1[0]->ID .
                    "'
                        ORDER BY FG, TreeOrd
                ",
            );

            $TreeID = $listwas[0]->TreeID;

            $TreeOrd = '';
            foreach ($listwas as $listwa) {
                if ($TreeOrd == '') {
                    $TreeOrd = $listwa->TreeOrd;
                } else {
                    $TreeOrd .= ', ' . $listwa->TreeOrd;
                }
            }

            $getheaderwaxtree = FacadesDB::select("SELECT
                    w.LinkID,
                    CONCAT( p.Photo ) AS gambar,
                    -- p.SKU
                    IF	( p.SKU IS NULL OR p.SKU = '', p.SW, p.SKU ) AS SKU
                FROM
                    waxtreeitem AS w
                    LEFT JOIN product AS p ON p.ID = w.Product
                WHERE
                    w.IDM = '$TreeID' AND
                    w.Ordinal IN ($TreeOrd)
                GROUP BY
                    w.LinkID
                ORDER BY
                    w.LinkID ASC,
                    w.LinkOrd ASC
                ");

            $getitemwaxtree = FacadesDB::select("SELECT
                    w.Ordinal,
                    w.Product,
                    w.LinkID,
                    w.LinkOrd,
                    -- p.SKU,
                    IF	( p.SKU IS NULL OR p.SKU = '', p.SW, p.SKU ) AS SKU,
                    w.IDM
                FROM
                    waxtreeitem w
                    INNER JOIN product p ON p.ID = w.Product
                WHERE
                    w.IDM = '$TreeID'
                    AND w.Ordinal IN ($TreeOrd)
                ORDER BY
                    w.LinkID,
                    w.LinkOrd
                ");

            foreach ($getitemwaxtree as $getitem) {
                $Ordinal[$getitem->LinkID][$getitem->LinkOrd] = $getitem->Ordinal;
                $timbang[$getitem->LinkID][$getitem->LinkOrd] = $TreeID . '-' . $getitem->Product . '-' . $getitem->LinkOrd;
                $ids[$getitem->LinkID][$getitem->LinkOrd] = $getitem->IDM . '-' . $getitem->Ordinal;
                $SKU[$getitem->LinkID][$getitem->LinkOrd] = $getitem->SKU . '-' . $getitem->LinkOrd;
            }

            foreach ($listwas as $key => $value) {
                $berat[$value->TreeID . '-' . $value->TreeOrd] = $value->Weight;
            }

            $get_status = FacadesDB::connection('erp')->select("SELECT Active FROM `workcompletion` WHERE WorkAllocation ='$id' ");

            // dd($get_status);
            if ($get_status) {
                $status = $get_status[0]->Active;
            } else {
                $status = '0';
            }

            $count = count($getitemwaxtree);

            // dd($data1, $listwas,$getheaderwaxtree,$Ordinal,$Product,$timbang,$gambar,$count, $SKU);

            return view('R&D.Grafis.NTHKOGrafis.show', compact('data1', 'getheaderwaxtree', 'Ordinal', 'timbang', 'ids', 'count', 'SKU', 'berat', 'status'));
        }

        //generate barcode
        elseif ($no == '2') {
            $data1 = FacadesDB::connection('erp')->select("SELECT
                        w.ID,
                        w.SW,
                        DATE( w.EntryDate ) AS EntryDate,
                        e.Description `name`,
                        w.Employee,
                        w.TargetQty,
                        w.Weight
                    FROM
                        `workallocation` w
                        INNER JOIN employee e ON e.ID = Employee
                    WHERE
                        w.SW = '$id'");

            $listwas = FacadesDB::connection('erp')->select(
                "SELECT
                        wi.TreeID,
                        wi.TreeOrd
                    FROM
                        workallocationitem wi
                    WHERE
                        wi.IDM = '" .
                    $data1[0]->ID .
                    "'
                        ORDER BY FG, TreeOrd
                ",
            );

            $TreeID = $listwas[0]->TreeID;

            $TreeOrd = '';
            foreach ($listwas as $listwa) {
                if ($TreeOrd == '') {
                    $TreeOrd = $listwa->TreeOrd;
                } else {
                    $TreeOrd .= ', ' . $listwa->TreeOrd;
                }
            }

            $getitemwaxtree = FacadesDB::select("SELECT
                    w.IDM,
                    w.Ordinal,
                    w.Product,
                    w.LinkID,
                    w.LinkOrd
                FROM
                    waxtreeitem w
                WHERE
                    w.IDM = '$TreeID'
                    AND w.Ordinal IN ($TreeOrd)
                ORDER BY
                    w.LinkID,
                    w.LinkOrd
                ");

            // dd($data1, $listwas, $TreeOrd, $timbang);
            return view('R&D.Grafis.NTHKOGrafis.barcode', compact('getitemwaxtree'));
        }

        //Cetak
        elseif ($no == '3') {
            // dd($id);
            $header = FacadesDB::connection('erp')->select("SELECT
                    A.ID,
                    A.WorkAllocation,
                    A.TransDate,
                    A.Qty,
	                A.Weight,
                    C.ID AS idAdmin,
                    C.SW AS swAdmin,
                    C.Description AS adminName,
                    B.ID AS idoperator,
                    B.SW AS swoperator,
                    B.Description AS operatorName
                FROM
                    workcompletion A
                    JOIN employee B ON A.Employee = B.ID
                    JOIN employee C ON A.UserName = C.SW
                WHERE
                    A.WorkAllocation = '$id'");

            $items = FacadesDB::connection('erp')->select("SELECT
                    D.SW AS namaProduct,
                    D.Description AS descriptionProduct,
                    E.Description AS kadar,
                    B.Qty AS jumlah,
                    B.Weight AS berat,
                    f.SW AS swwo
                FROM
                    workcompletion A
                    JOIN workcompletionitem B ON A.ID = B.IDM
                    JOIN product D ON B.FG = D.ID
                    JOIN productcarat E ON B.Carat = E.ID 
                    JOIN workorder f on f.ID = b.WorkOrder
                WHERE
                    A.WorkAllocation = '$id'");

            // dd($data1, $listwas, $TreeOrd, $timbang);
            return view('R&D.Grafis.NTHKOGrafis.cetak', compact('header', 'items'));
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $idworkallocation = $request->idworkallocation;
        $tree = explode('-', $request->nama[0]);
        $UserName = Auth::user()->name;

        FacadesDB::connection('erp')->beginTransaction();
        try {
            $datawa = FacadesDB::connection('erp')->select("SELECT
                    wai.Ordinal,
                    wai.Carat,
                    wai.workorder,
                    wai.TreeID,
                    wai.TreeOrd,
                    wai.FG,
                    wa.employee,
                    wa.SW,
                    wa.Location,
                    wa.Operation,
                    wa.Freq,
                    wa.Weight,
                    CONCAT(wai.TreeID,'-',wai.TreeOrd) AS tree
                FROM
                    workallocationitem AS wai
                    INNER JOIN
                    workallocation AS wa
                    ON
                        wai.IDM = wa.ID
                WHERE
                    wai.IDM = $idworkallocation
                    AND wai.TreeID = $tree[0]
            ");

            $total_berat = 0;
            foreach ($request->brthasilgrf as $value) {
                $total_berat += $value;
            }
            $susutan = $datawa[0]->Weight - $total_berat;

            $GetLastID = $this->Public_Function->GetLastIDERP('WorkCompletion');

            //update last id
            $update_lastid = lastid::where('Module', 'WorkCompletion')->update([
                'Last' => $GetLastID['ID'],
            ]);

            //insert workcompletion
            $insert_workcompletion = workcompletion::create([
                'ID' => $GetLastID['ID'],
                'EntryDate' => now(),
                'UserName' => $UserName,
                'TransDate' => now(),
                'Employee' => $datawa[0]->employee,
                'WorkAllocation' => $datawa[0]->SW,
                'Freq' => $datawa[0]->Freq,
                'Location' => $datawa[0]->Location,
                'Operation' => $datawa[0]->Operation,
                'Qty' => $request->count,
                'Weight' => $total_berat,
                'Active' => 'A',
            ]);

            foreach ($datawa as $key => $value) {
                $Weight = $request->brthasilgrf[$value->TreeID . '-' . $value->TreeOrd];
                if ($Weight == null) {
                    $Weight = 0;
                }
                $insert_tmresinkelilinitem[] = workcompletionitem::create([
                    'IDM' => $GetLastID['ID'],
                    'Ordinal' => $key + 1,
                    'Product' => $request->next[$value->TreeID . '-' . $value->TreeOrd],
                    'Carat' => $value->Carat,
                    'Qty' => 1,
                    'Weight' => $Weight,
                    'WorkOrder' => $value->workorder,
                    'LinkID' => $idworkallocation,
                    'LinkOrd' => $value->Ordinal,
                    'TreeID' => $value->TreeID,
                    'TreeOrd' => $value->TreeOrd,
                    'FG' => $value->FG,
                ]);
            }

            FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'message' => 'Berhasil',
                    'insert_workcompletion' => $insert_workcompletion,
                    'insert_tmresinkelilinitem' => $insert_tmresinkelilinitem,
                ],
                200,
            );
        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'success' => false,
                    'message' => 'Gagal',
                    'errors1' => $insert_workcompletion->errors(),
                    'errors2' => $insert_tmresinkelilinitem->errors(),
                ],
                500,
            );
        }
    }

    public function posting(Request $request)
    {
        // dd($request);
        //upload gambar
        foreach ($request->nama as $key => $value) {
            if (isset($request->$value)) {
                foreach ($request->$value as $key2 => $value2) {
                    $sku = $value . 'SKU';
                    $sku2 = $request->$sku;
                    $sku2 = str_replace('-1', '-A', $sku2);
                    $sku2 = str_replace('-2', '-B', $sku2);
                    $sku2 = str_replace('-3', '-C', $sku2);
                    $fileName = $sku2 . ' (' . $key2 + 1 . ').' . $value2->extension();
                    $lokasi = substr($request->$sku, 0, -2) . '/' . $sku2;
                    $value2->storeAs($lokasi, $fileName, 'UploadGrafis');
                }
            }
        }

        $UserName = Auth::user()->name;
        $iduser = $request->session()->get('iduser');

        $datawa = FacadesDB::connection('erp')->select("SELECT * FROM `workcompletion` WHERE WorkAllocation = '$request->SW'");

        $total_berat = $datawa[0]->Weight;
        $susutan = $request->tberat - $total_berat;

        // generate sw
        $gensw = 'PHO' . date('ym');
        $getsw = FacadesDB::select("SELECT ID, SW FROM `grafis` WHERE SW LIKE '$gensw%' ORDER BY ID DESC LIMIT 1 ");

        if ($getsw) {
            $SW = $gensw . sprintf('%03d', substr($getsw[0]->SW, -3) + 1);
        } else {
            $SW = $gensw . sprintf('%03d', 1);
        }

        //get last id grafis dan update
        $GetLastID = $this->Public_Function->GetLastIDRND('grafis');
        $update_lastid = lastidrnd::where('Module', 'grafis')->update([
            'Last' => $GetLastID['ID'],
        ]);

        //insert ke grafis
        $insert_grafis = grafis::create([
            'ID' => $GetLastID['ID'],
            'EntryDate' => now(),
            'UserName' => $UserName,
            'TransDate' => now(),
            'Employee' => $iduser,
            'Process' => 'Foto',
            'SW' => $SW,
            'Active' => 'A',
            'TransferID' => $request->SW,
        ]);
        //insert ke grafisitem
        $getgrafisworklist = FacadesDB::select(" SELECT
                    g.ID,
                    g.Product,
                    g.TreeID,
                    g.TreeOrd,
                    w.LinkOrd
                FROM
                    grafisworklist g
                    INNER JOIN waxtreeitem w ON g.TreeID = w.IDM AND g.TreeOrd = w.Ordinal
                WHERE
                    g.NextWorkAllocation = $request->SW
                ");

        foreach ($getgrafisworklist as $key => $value) {
            $insert_grafisitem[] = grafisitem::create([
                'IDM' => $GetLastID['ID'],
                'Ordinal' => $key + 1,
                'Product' => $value->Product,
                'Variation' => $value->LinkOrd,
                'WorkList' => $value->ID,
                'Active' => '0',
            ]);

            $update_grafisworklist[] = grafisworklist::where('ID', $value->ID)->update([
                'EndFoto' => now(),
            ]);
        }

        FacadesDB::connection('erp')->beginTransaction();
        try {
            $update_workallocationresult = workallocationresult::where('SW', $request->SW)->update([
                'CompletionQty' => $request->count,
                'CompletionWeight' => $total_berat,
                'CompletionDate' => now(),
                'CompletionFreq' => '1',
                'Shrink' => $susutan,
                'ShrinkDate' => now(),
            ]);

            $GetLastID = $this->Public_Function->GetLastIDERP('workshrink');

            $update_lastid = lastid::where('Module', 'workshrink')->update([
                'Last' => $GetLastID['ID'],
            ]);

            $insert_workshrink = workshrink::create([
                'ID' => $GetLastID['ID'],
                'EntryDate' => now(),
                'UserName' => $UserName,
                'TransDate' => now(),
                'Allocation' => $request->SW,
                'Shrink' => $susutan,
                'Tolerance' => 0,
                'Difference' => 0,
                'Active' => 'P',
            ]);

            $data = FacadesDB::connection('erp')->select('SELECT A.Product, A.Carat, A.Ordinal, A.WorkOrder, B.WorkAllocation as SW FROM workcompletionitem AS A INNER JOIN workcompletion AS B ON A.IDM=B.ID WHERE A.IDM=' . $datawa[0]->ID . ' ORDER BY A.Ordinal');
            foreach ($data as $datas) {
                // dd('D', 'transferrmitem', $UserName, '56', $datas->Product, $datas->Carat, 'Production', 'Completion', $datas->SW, $datawa[0]->ID, $datas->Ordinal, $datas->WorkOrder);
                $Posting = $this->Public_Function->PostingERP('D', 'workcompletionitem', $UserName, '56', $datas->Product, $datas->Carat, 'Production', 'Completion', $datas->SW, $datawa[0]->ID, $datas->Ordinal, $datas->WorkOrder);
            }

            //update status telah terposting
            $UpdateUserHistory = $this->Public_Function->SetStatustransactionERP('workcompletion', $datawa[0]->ID);

            FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'message' => 'Berhasil',
                    'update_workallocationresult' => $update_workallocationresult,
                    // 'insert_workshrink' => $insert_workshrink,
                ],
                200,
            );
        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'success' => false,
                    'message' => 'Gagal',
                ],
                500,
            );
        }
    }

    public function upload(Request $request)
    {
        // dd($request);

//         $getsw = FacadesDB::select(" SELECT
// gw.Product,
// CASE
		
// 		WHEN msv.Color = 'Putih' THEN
// 		'Putih' 
// 		WHEN msv.Color = 'Merah' THEN
// 		'Merah' 
// 		WHEN msv.Color = 'Merah Muda' THEN
// 		'Merah Muda' ELSE 'Tanpa Batu' 
// 	END AS Warna,
// CASE
		
// 		WHEN msv.Color = 'Putih' THEN
// 		'1' 
// 		WHEN msv.Color = 'Merah' THEN
// 		'2' 
// 		WHEN msv.Color = 'Merah Muda' THEN
// 		'3' ELSE '1' 
// 	END AS OrdWarna,
// CASE
		
// 		WHEN ei.OrdinalVariation IS NULL THEN
// 		0 ELSE ei.OrdinalVariation 
// 	END AS Enamel,
// 	ei.OrdinalVariation,
// 	CONCAT(
// 		pt.SW,
// 		'.',
// 		p.SerialNo,
// 		'.',
// 		pc.SKU,
// 		'.',
// 		pc.Alloy,
// 		'.',
// 		lg.SW,
// 		pc.SNI,
// 		pc.Description,
// 		'.',
// 	CASE
			
// 			WHEN msv.Color = 'Putih' THEN
// 			'101' 
// 			WHEN msv.Color = 'Merah' THEN
// 			'201' 
// 			WHEN msv.Color = 'Merah Muda' THEN
// 			'301' ELSE '000' 
// 		END,
// 		'.',
// 	CASE
			
// 			WHEN p.VarSlep = 272 THEN
// 			'1' 
// 			WHEN p.VarSlep = 275 THEN
// 			'1' 
// 			WHEN p.VarSlep = 276 THEN
// 			'1' 
// 			WHEN p.VarSlep = 235 THEN
// 			'1' 
// 			WHEN p.VarSlep = 519 THEN
// 			'0' 
// 			WHEN p.VarSlep = 520 THEN
// 			'1' ELSE '0' 
// 		END,
// 		'.',
// 	CASE
			
// 			WHEN p.VarMarking = 273 THEN
// 			'1' 
// 			WHEN p.VarMarking = 275 THEN
// 			'1' 
// 			WHEN p.VarMarking = 277 THEN
// 			'1' 
// 			WHEN p.VarMarking = 235 THEN
// 			'1' 
// 			WHEN p.VarMarking = 521 THEN
// 			'0' 
// 			WHEN p.VarMarking = 522 THEN
// 			'1' ELSE '0' 
// 		END,
// 		'.',
// 	CASE
			
// 			WHEN p.VarSepuh = 240 
// 			AND ( pc.ID = 1 OR pc.ID = 3 ) THEN
// 				'1' 
// 				WHEN p.VarSepuh = 240 
// 				AND ( pc.ID = 4 ) THEN
// 					'2' ELSE '0' 
// 					END,
// 				'.',
// 			CASE
					
// 					WHEN p.VarPutih = 274 THEN
// 					'1' 
// 					WHEN p.VarPutih = 276 THEN
// 					'1' 
// 					WHEN p.VarPutih = 277 THEN
// 					'1' 
// 					WHEN p.VarPutih = 235 THEN
// 					'1' 
// 					WHEN p.VarPutih = 530 THEN
// 					'0' 
// 					WHEN p.VarPutih = 531 THEN
// 					'1' ELSE '0' 
// 				END,
// 				'.',
// 			CASE
					
// 					WHEN p.VarEnamel IS NULL THEN
// 					'00' 
// 					WHEN p.VarEnamel = 238 THEN
// 					'00' ELSE CONCAT( '0', ei.OrdinalVariation ) 
// 				END,
// 				'.',
// 			CASE
					
// 					WHEN p.VarSize IS NULL THEN
// 					'000' ELSE dz.SW 
// 				END 
// 				) AS SKUFix
// 			FROM
// 				erp.workcompletion wc
// 				INNER JOIN erp.workcompletionitem wci ON wc.ID = wci.IDM
// 				INNER JOIN grafisworklist gw ON wc.workallocation = gw.NextWorkAllocation 
// 				AND wci.TreeOrd = gw.TreeOrd
// 				JOIN product p ON gw.Product = p.ID
// 				JOIN product pt ON p.Model = pt.ID
// 				JOIN productcarat pc ON gw.Carat = pc.ID
// 				JOIN drafter2d dd ON p.ID = dd.Product 
// 				AND dd.TypeProcess = 24
// 				LEFT JOIN shorttext lg ON dd.Logo = lg.ID
// 				LEFT JOIN designsize dz ON p.VarSize = dz.ID
// 				LEFT JOIN varpitem vi ON vi.Product = gw.Product 
// 				AND vi.TreeID = gw.TreeID 
// 				AND vi.TreeOrd = gw.TreeOrd
// 				LEFT JOIN varpitemstone vis ON vi.IDM = vis.IDM 
// 				AND vi.Ordinal = vis.Ordinal
// 				LEFT JOIN masterstone msv ON vis.Product = msv.LinkProduct
// 				LEFT JOIN sepuhitem si ON si.Product = gw.Product 
// 				AND si.TreeID = gw.TreeID 
// 				AND si.TreeOrd = gw.TreeOrd
// 				LEFT JOIN sepuhitemstone sis ON si.IDM = sis.IDM 
// 				AND si.Ordinal = sis.Ordinal
// 				LEFT JOIN masterstone mss ON vis.Product = mss.LinkProduct
// 				LEFT JOIN enamelitem ei ON ei.Product = gw.Product 
// 				AND ei.TreeID = gw.TreeID 
// 				AND ei.TreeOrd = gw.TreeOrd
// 				LEFT JOIN enamelitemstone eis ON ei.IDM = eis.IDM 
// 				AND ei.Ordinal = eis.Ordinal 
// 			WHERE
// 				wc.workallocation = '$request->SW' 
// 			GROUP BY
// 				gw.Product,
// 				msv.Color 
// 			ORDER BY
// 			p.SW,
// 	SkuFix
//                 ");

//         dd($getsw);

        // upload gambar
        foreach ($request->nama as $key => $value) {
            if (isset($request->$value)) {
                foreach ($request->$value as $key2 => $value2) {
                    $sku = $value . 'SKU';
                    $sku2 = $request->$sku;
                    $sku2 = str_replace('-1', '-A', $sku2);
                    $sku2 = str_replace('-2', '-B', $sku2);
                    $sku2 = str_replace('-3', '-C', $sku2);
                    $fileName = $sku2 . ' (' . $key2 + 1 . ').' . $value2->extension();
                    $lokasi = substr($request->$sku, 0, -2) . '/' . $sku2;
                    $value2->storeAs($lokasi, $fileName, 'UploadGrafis');
                }
            }
        }
        

        
    }

    public function cetak(Request $request)
    {
        // dd($request);

        $carilists = FacadesDB::connection('erp')->select("SELECT ID, SW, Active FROM `workallocation` WHERE Operation='178' AND Active = 'P' ORDER BY ID DESC LIMIT 20");
        // dd($carilists);

        return view('R&D.Grafis.NTHKOGrafis.index', compact('carilists'));
    }
}

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

use App\Models\erp\workcompletion;
use App\Models\erp\workcompletionitem;
use App\Models\erp\workshrink;
use App\Models\erp\workallocationresult;
use App\Models\erp\workallocation;
use App\Models\erp\workallocationitem;

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

    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    public function index()
    {
        $carilists = FacadesDB::connection('erp')->select("SELECT ID, WorkAllocation AS SW, Active FROM workcompletion WHERE Operation='178' AND Active <> 'C' ORDER BY ID DESC LIMIT 20");
        $nthbaru = FacadesDB::connection('erp')->select("SELECT
                        a.ID,
                        a.SW 
                    FROM
                        workallocation AS a 
                    WHERE
                        a.Active = 'P' 
                        AND a.Operation = '178' 
                        AND a.SW NOT IN (
                        SELECT
                            WorkAllocation 
                        FROM
                        workcompletion 
                        )
                ");
        // dd($carilists);

        return view('R&D.Grafis.NTHKOGrafis.index', compact('carilists', 'nthbaru'));
    }

    public function show($no, $id)
    {
        // dd($no, $id);
        //lihat
        if ($no == '1') {
            $id = explode(',', $id);
            //cek lihat nth
            if ($id[1] == '1') {
                $data1 = FacadesDB::connection('erp')->select("SELECT
                            w.ID,
                            w.WorkAllocation SW,
                            w.Active,
                            DATE ( w.EntryDate ) AS EntryDate,
                            e.Description `name`,
                            w.Employee,
                            w.Qty TargetQty,
                            w.Weight
                        FROM
                            `workcompletion` w
                            INNER JOIN employee e ON e.ID = Employee
                        WHERE
                            Location = '56'
                            AND Operation = '178'
                            AND w.Active <> 'C'
                            AND w.WorkAllocation = '$id[0]'
                        ORDER BY
                            ID DESC
                    ");

                if (empty($data1)) {
                    return response()->json('data NTHKO tidak di temukan', 400);
                }

                $listwa = FacadesDB::connection('erp')->select(
                    "SELECT
                        IF	( d.SKU IS NULL OR d.SKU = '', d.SW, d.SKU ) AS Product,
                            E.Description AS kadar,
                            B.Qty AS jumlah,
                            B.Weight AS berat,
                            CONCAT( D.Photo, '.jpg' ) AS gambar,
                            b.Ordinal,
                            CONCAT( 'Variasi ', wt.LinkOrd ) AS Variasi,
                            b.Product AS next
                        FROM
                            workcompletion w
                            JOIN workcompletionitem B ON w.ID = B.IDM
                            JOIN product D ON B.FG = D.ID
                            JOIN productcarat E ON B.Carat = E.ID
                            LEFT JOIN rndnew.waxtreeitem wt ON wt.IDM = b.TreeID
                            AND wt.Ordinal = b.TreeOrd
                        WHERE
                            w.ID = '" . $data1[0]->ID . "'
                    ");
            }
            //cek tambah nth
            elseif ($id[1] == '2') {
                $data1 = FacadesDB::connection('erp')->select("SELECT
                            w.ID,
                            w.SW,
                            w.Active,
                            DATE ( w.EntryDate ) AS EntryDate,
                            e.Description `name`,
                            w.Employee,
                            w.TargetQty,
                            w.Weight
                        FROM
                            `workallocation` w
                            LEFT JOIN workcompletion x ON w.SW = x.WorkAllocation
                            INNER JOIN employee e ON e.ID = w.Employee
                        WHERE
                            w.Location = '56'
                            AND w.Operation = '178'
                            AND w.Active = 'P'
                            AND (x.ID IS NULL OR x.Active = 'C')
                            AND w.SW = '$id[0]'
                        ORDER BY
                            w.ID DESC, w.Freq DESC
                        ");

                if (empty($data1)) {
                    return response()->json('data SPKO tidak di temukan', 400);
                }

                $listwa = FacadesDB::connection('erp')->select(
                    " SELECT
                        IF	( d.SKU IS NULL OR d.SKU = '', d.SW, d.SKU ) AS Product,
                        E.Description AS kadar,
                        B.Qty AS jumlah,
                        B.Weight AS berat,
                        CONCAT( D.Photo, '.jpg' ) AS gambar,
                        b.Ordinal,
                        CONCAT('Variasi ',wt.LinkOrd) AS Variasi,
                        '753' AS next
                    FROM
                        workallocation w
                        JOIN workallocationitem B ON w.ID = B.IDM
                        JOIN product D ON B.FG = D.ID
                        JOIN productcarat E ON B.Carat = E.ID
                        LEFT JOIN rndnew.waxtreeitem wt ON wt.IDM = b.TreeID AND wt.Ordinal = b.TreeOrd
                    WHERE
                        w.ID = '" . $data1[0]->ID . "'
                ",
                );
            }

            // dd($data1, $id);

            $get_status = FacadesDB::connection('erp')->select("SELECT w.Active FROM `workcompletion` w WHERE w.workallocation ='$id[0]' AND w.Active <> 'C' ");

            // dd($get_status);
            if ($get_status) {
                $status = $get_status[0]->Active;
            } else {
                $status = '0';
            }

            $berat_qc = 0;
            $berat_cor = 0;
            $berat_rep = 0;
            $berat_sc = 0;
            $berat_varp = 0;
            $berat_sepuh = 0;
            foreach ($listwa as $key => $value) {
                // Check if value 'next' is '753' then it will add berat to berat_qc
                if ($value->next == '753') {
                    // add berat to berat_qc
                    $berat_qc = $berat_qc+=$value->berat;
                }

                // check if value 'next' is '256' then it will add berat to berat_cor
                if ($value->next == '256') {
                    // add berat to berat_cor
                    $berat_cor = $berat_cor+=$value->berat;
                }

                // check if value 'next' is '254' then it will add berat to berat_rep'
                if ($value->next == '254') {
                    // add berat to berat_rep
                    $berat_rep = $berat_rep+=$value->berat;
                }

                // check if value 'next' is '98' then it will add berat to berat_sc
                if ($value->next == '98') {
                    // add berat to berat_sc
                    $berat_sc = $berat_sc+=$value->berat;
                }

                // check if value 'next' is '2234' then it will add berat to berat_varp
                if ($value->next == '2234') {
                    // add berat to berat_varp
                    $berat_varp = $berat_varp+=$value->berat;
                }

                // check if value 'next' is '260' then it will add berat to berat_sepuh
                if ($value->next == '260') {
                    // add berat to berat_sepuh
                    $berat_sepuh = $berat_sepuh+=$value->berat;
                }
            }

            // convert berat to array
            $berat = [
                'berat_qc' => $berat_qc,
                'berat_cor' => $berat_cor,
                'berat_rep' => $berat_rep,
                'berat_sc' => $berat_sc,
                'berat_varp' => $berat_varp,
                'berat_sepuh' => $berat_sepuh,
            ];

            // dd($data1, $listwa, $status);

            return view('R&D.Grafis.NTHKOGrafis.show', compact('data1', 'listwa', 'status', 'berat'));
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
                    A.ID = '$id'");

            $items = FacadesDB::connection('erp')->select("SELECT
                    D.SW AS namaProduct,
                    D.Description AS descriptionProduct,
                    E.Description AS kadar,
                    B.Qty AS jumlah,
                    B.Weight AS berat,
                    f.SW AS swwo,
                    b.Product,
                    c.Description AS `next`
                FROM
                    workcompletion A
                    JOIN workcompletionitem B ON A.ID = B.IDM
                    JOIN product c ON B.Product = c.ID
                    JOIN product D ON B.FG = D.ID
                    JOIN productcarat E ON B.Carat = E.ID
                    JOIN workorder f ON f.ID = b.WorkOrder
                WHERE
                    A.ID = '$id'
                ORDER BY b.Product, d.SW ");

            // dd($data1, $listwas, $TreeOrd, $timbang);
            return view('R&D.Grafis.NTHKOGrafis.cetak', compact('header', 'items'));
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $idworkallocation = $request->idworkallocation;
        $UserName = Auth::user()->name;

        try {
            FacadesDB::connection('erp')->beginTransaction();
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
                    wa.TargetQty
                FROM
                    workallocationitem AS wai
                    INNER JOIN
                    workallocation AS wa
                    ON
                        wai.IDM = wa.ID
                WHERE
                    wai.IDM = $idworkallocation
            ");
            $Freq = FacadesDB::connection('erp')->select("SELECT
                                COUNT(*) + 1 AS 'count'
                                FROM
                                workcompletion
                                WHERE
                                WorkAllocation = '".$datawa[0]->SW."'
                        ");

            $total_berat = 0;
            foreach ($request->brthasilgrf as $value) {
                $total_berat += $value;
            }

            $GetLastID = $this->Public_Function->GetLastIDERP('WorkCompletion');

            //update last id
            $update_lastid = lastid::where('Module', 'WorkCompletion')->update([
                'Last' => $GetLastID['ID'],
            ]);
            // FacadesDB::enableQueryLog();
            // insert workcompletion
            $insert_workcompletion = workcompletion::create([
                'ID' => $GetLastID['ID'],
                'EntryDate' => now(),
                'UserName' => $UserName,
                'TransDate' => now(),
                'Employee' => $datawa[0]->employee,
                'WorkAllocation' => $datawa[0]->SW,
                'Freq' => $Freq[0]->count,
                'Location' => $datawa[0]->Location,
                'Operation' => $datawa[0]->Operation,
                'Qty' => $datawa[0]->TargetQty,
                'Weight' => $total_berat,
                'Active' => 'A',
            ]);

            // dd(FacadesDB::getQueryLog());

            foreach ($datawa as $key => $value) {
                $Weight = $request->brthasilgrf[$value->Ordinal];
                if ($Weight == null) {
                    $Weight = 0;
                }
                $insert_tmresinkelilinitem[] = workcompletionitem::create([
                    'IDM' => $GetLastID['ID'],
                    'Ordinal' => $key + 1,
                    'Product' => $request->next[$value->Ordinal],
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

    public function update(Request $request){
        $nomorNTHKO = $request->nomorNTHKO;
        $next_product = $request->next_product;
        $berat_items = $request->berat_items;

        // check if nomorNTHKO is null or blank 
        if(is_null($nomorNTHKO) or $nomorNTHKO == ""){
            // set data_return
            $data_return = $this->SetReturn(false, "nomorNTHKO tidak boleh kosong", null, null);
            // return data_return with 400 status code
            return response()->json($data_return, 400);
        }

        // check if berat_items is array and is not null or array length is more than 0
        if(!is_array($berat_items) or count($berat_items) == 0){
            // set data_return
            $data_return = $this->SetReturn(false, "berat_items tidak boleh kosong", null, null);
            // return data_return with 400 status code
            return response()->json($data_return, 400);
        }

        // check if next_product is array and is not null or array length is less than 1
        if(!is_array($next_product) or count($next_product) < 1){
            // set data_return
            $data_return = $this->SetReturn(false, "next_product tidak boleh kosong", null, null);
            // return data_return with 400 status code
            return response()->json($data_return, 400);
        }

        // Get NTHKO with nomorNTHKO
        $NTHKO = workcompletion::where('WorkAllocation', $nomorNTHKO)->where('Active', '!=', 'C')->first();
        // check if NTHKO is null
        if(is_null($NTHKO)){
            // set data_return
            $data_return = $this->SetReturn(false, "NTHKO tidak ditemukan", null, null);
            // return data_return with 404 status code
            return response()->json($data_return, 404);
        }

        // check if NTHKO 'Active' is 'P'
        if($NTHKO->Active == 'P'){
            // set data_return
            $data_return = $this->SetReturn(false, "NTHKO sudah posting", null, null);
            // return data_return with 400 status code
            return response()->json($data_return, 400);
        }

        // Get Items of NTHKO with 'ID' from NTHKO
        $NTHKOItems = workcompletionitem::where('IDM',$NTHKO->ID)->get();

        // check length of next_product, berat_items is equal with NTHKOItems
        if(count($NTHKOItems) != count($next_product) or count($NTHKOItems) != count($berat_items)){
            // set data_return
            $data_return = $this->SetReturn(false, "Jumlah next_product, berat_items tidak sama dengan NTHKOItems", null, null);
            // return data_return with 400 status code
            return response()->json($data_return, 400);
        }

        $total_berat = 0;
        // loop berat_items and add to total_berat
        foreach($berat_items as $key => $berat){
            // add berat to total_berat
            $total_berat += $berat;
        }
        

        // remove NTHKO Items from db
        $removeNTHKOItems = workcompletionitem::where('IDM',$NTHKO->ID)->delete();

        // update Weight of NTHKO
        $updateNTHKO = workcompletion::where('ID',$NTHKO->ID)->update([
            'Weight' => $total_berat,
        ]);

        // loop NTHKOItems and insert NTHKOItems to db
        foreach($NTHKOItems as $key => $NTHKOItem){
            $insertNTHKOItems = workcompletionitem::create([
                'IDM'=>$NTHKOItem->IDM,
                'Ordinal'=>$NTHKOItem->Ordinal,
                'Product'=>$next_product[$key],
                'Carat'=>$NTHKOItem->Carat,
                'Qty'=>$NTHKOItem->Qty,
                'Weight'=>$berat_items[$key],
                'WorkOrder'=>$NTHKOItem->WorkOrder,
                'LinkID'=>$NTHKOItem->LinkID,
                'LinkOrd'=>$NTHKOItem->LinkOrd,
                'TreeID'=>$NTHKOItem->TreeID,
                'TreeOrd'=>$NTHKOItem->TreeOrd,
                'FG'=>$NTHKOItem->FG
            ]);
        }

        // set return success
        $data_return = $this->SetReturn(true, "Berhasil Mengubah Data NTHKO '$nomorNTHKO'", null, null);
        // return data_return with 200 status code
        return response()->json($data_return, 200);
    }

    public function posting(Request $request)
    {
        // dd($request);

        $UserName = Auth::user()->name;
        $iduser = $request->session()->get('iduser');

        //mencek data workcompletion
        $datawa = FacadesDB::connection('erp')->select("SELECT * FROM `workcompletion` WHERE WorkAllocation = '$request->SW' AND Active <> 'C' order by ID DESC LIMIT 1 ");

        if ($datawa[0]->Active == 'P' || $datawa[0]->Active == 'C') {
            return response(
                [
                    'success' => false,
                    'message' => 'Gagal',
                ],
                500,
            );
        }

        // get data workallocation
        $workallocation = workallocation::where('SW', $request->SW)->where('Active', '!=', 'C')->first();
        // check if workallocation is null
        if (is_null($workallocation)) {
            // create data_return
            $data_return = $this->SetReturn(false, "workallocation tidak ditemukan, Silahkan Hubungi Programmer", null, null);
            // return data_return with 404 status code
            return response()->json($data_return, 404);
        }

        // check workallocation 'Active' is 'P'
        if ($workallocation->Active != 'P') {
            // create data_return failed because workallocation 'Active' is not 'P'
            $data_return = $this->SetReturn(false, "Status SPKO Bukan Posting,  Silahkan Hubungi Programmer", null, null);
            // return data_return with 400 status code
            return response()->json($data_return, 400);
        }

        $berat_NTHKO = $datawa[0]->Weight;
        $berat_SPKO = $workallocation->Weight;

        $susutan = $berat_SPKO - $berat_NTHKO;

        // dd($UserName, $iduser, $datawa, $total_berat, $susutan);

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

        try {
            FacadesDB::connection('erp')->beginTransaction();
            $update_workallocationresult = workallocationresult::where('SW', $request->SW)->update([
                'CompletionQty' => $datawa[0]->Qty,
                'CompletionWeight' => $berat_NTHKO,
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
                    'insert_workshrink' => $insert_workshrink,
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
}

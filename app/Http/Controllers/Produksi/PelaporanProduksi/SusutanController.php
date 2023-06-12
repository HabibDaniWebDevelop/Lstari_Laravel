<?php

namespace App\Http\Controllers\Produksi\PelaporanProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Http\Controllers\Public_Function_Controller;

use \DateTime;
use \DateTimeZone;

use App\Models\erp\lastid;
use App\Models\erp\workshrink;
use App\Models\erp\workallocationresult;
use App\Models\erp\workallocation;
use App\Models\erp\workcompletion;

// use App\Models\tes_laravel\workshrink;
// use App\Models\tes_laravel\workallocationresult;
// use App\Models\tes_laravel\workallocation;
// use App\Models\tes_laravel\workcompletion;


class SusutanController extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller){
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index(){

        $location = session('location');
        $username = session('UserEntry');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 47;
        }

        // Susutan List
        $query = "SELECT * FROM (SELECT WS.*, SUBSTRING(WS.ALLOCATION, 5, 2) AS LOC FROM WORKSHRINK WS) AS RESULT
                    WHERE LOC=$location
                    ORDER BY ID DESC
                    LIMIT 100
                    ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.Susutan.index', compact('data','rowcount','iddept'));

    }

    public function lihat(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;

        if($id == NULL || $id == ''){
            $json_return = array('message' => 'Harap Isi Kolom Cari!');
            return response()->json($json_return,404);
        }

        // Query Header
        $query = "SELECT E.Description Pekerja, F.Description Kadar, D.ID IdSusutan, A.SW NoSPKO, FORMAT(A.Weight,2) BrtSPKO, FORMAT(A.CompletionWeight,2) BrtNTHKO, G.Description Bagian, H.Description Proses, 
                        DATE_FORMAT(D.TransDate, '%d/%m/%y') Tgl, FORMAT(D.Shrink,2) Susut, FORMAT(D.Difference,2) Perbedaan, FORMAT(D.Tolerance,2) Toleransi,
                        FORMAT((D.Shrink/A.Weight)*100,2) SusutPersen, D.Remarks Catatan, D.UserName User, DATE_FORMAT(D.EntryDate, '%d/%m/%y') EntryDate,
                        D.Active
                    FROM workallocationresult A 
                        LEFT JOIN workshrink D ON A.SW=D.Allocation
                        LEFT JOIN employee E ON A.Employee=E.ID
                        LEFT JOIN productcarat F ON A.Carat=F.ID
                        LEFT JOIN location G ON A.Location=G.ID
                        LEFT JOIN operation H ON A.Operation=H.ID
                    WHERE A.SW=$id AND A.Shrink IS NOT NULL
                    ";
        $data = FacadesDB::connection('erp')->select($query);

        if(count($data) == 0){
            $json_return = array('message' => 'Data Not Found!');
            return response()->json($json_return,404);
        }

        // Query Item
        $query2 = "SELECT W.SW NoSPK, P.SW Production, P.Description Produk, A.QtyAllocation, C.QtyCompletion, A.WeightAllocation,
                        C.WeightCompletion, A1.QtyAllocationActive, C1.QtyCompletionActive, A1.WeightAllocationActive,
                        C1.WeightCompletionActive, W.SWYear, W.SWOrdinal,
                        IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) QtyAllocationTotal,
                        IfNull(C.QtyCompletion, 0) + IfNull(C1.QtyCompletionActive, 0) QtyCompletionTotal,
                        IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) WeightAllocationTotal,
                        IfNull(C.WeightCompletion, 0) + IfNull(C1.WeightCompletionActive, 0) WeightCompletionTotal,
                        IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) - IfNull(C.QtyCompletion, 0) - IfNull(C1.QtyCompletionActive, 0) QtyDifference,
                        IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) - IfNull(C.WeightCompletion, 0) - IfNull(C1.WeightCompletionActive, 0) WeightDifference
                    From (  Select Distinct I.WorkOrder
                            From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                            Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $id
                            Union
                            Select Distinct I.WorkOrder
                            From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                            Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $id ) O
                    Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocation,
                                Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocation
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $id
                                Group By I.WorkOrder ) A On A.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletion,
                                Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletion
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $id
                                Group By I.WorkOrder ) C On C.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocationActive,
                                Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocationActive
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active = 'A' And A.SW = $id
                                Group By I.WorkOrder ) A1 On A1.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletionActive,
                                Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletionActive
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active = 'A' And A.WorkAllocation = $id
                                Group By I.WorkOrder ) C1 On C1.WorkOrder = O.WorkOrder
                    Join WorkOrder W On O.WorkOrder = W.ID
                    Join Product P On W.Product = P.ID
                    Order By W.SWYear, W.SWOrdinal
                    ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $returnHTML = view('Produksi.PelaporanProduksi.Susutan.lihat', compact('data','data2'))->render();

        $json_return = array(
            'success' => 'true',
            'html' => $returnHTML
        );
        return response()->json($json_return,200);

    }

    public function baru(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }
        
        $id = $request->id;

        // Query Cek Status
        $queryCek = "SELECT * FROM workcompletion WHERE WorkAllocation=$id LIMIT 1";
        $dataCek = FacadesDB::connection('erp')->select($queryCek);
        foreach($dataCek as $datasCek){}
        
        if($datasCek->Active == 'S'){
            $json_return = array('message' => 'NTHKO Sudah Disusutkan', 'jenisFunction'=>'baru');
            return response()->json($json_return,404);

        }else if($datasCek->Active == 'A'){
            $json_return = array('message' => 'NTHKO Belum Diposting', 'jenisFunction'=>'baru');
            return response()->json($json_return,404);

        }else{
            // Query Header
            $query = "SELECT E.Description Pekerja, F.Description Kadar, D.ID IdSusutan, A.SW NoSPKO, FORMAT(A.Weight,2) BrtSPKO, FORMAT(A.CompletionWeight,2) BrtNTHKO, G.Description Bagian, H.Description Proses, 
                            H.ID ProsesID, DATE_FORMAT(D.TransDate, '%d/%m/%y') Tgl, FORMAT(A.Weight-A.CompletionWeight,2) Susut, FORMAT(A.CompletionWeight-A.Weight,2) Perbedaan, 
                            FORMAT(((A.Weight-A.CompletionWeight)/A.Weight)*100,2) SusutPersen, D.Remarks Catatan, D.UserName User, DATE_FORMAT(D.EntryDate, '%d/%m/%y') EntryDate
                        FROM workallocationresult A 
                            LEFT JOIN workshrink D ON A.SW=D.Allocation
                            LEFT JOIN employee E ON A.Employee=E.ID
                            LEFT JOIN productcarat F ON A.Carat=F.ID
                            LEFT JOIN location G ON A.Location=G.ID
                            LEFT JOIN operation H ON A.Operation=H.ID
                        WHERE A.SW=$id
                        ";
            $data = FacadesDB::connection('erp')->select($query);

            if(count($data) == 0){
                $json_return = array('message' => 'Data Not Found!', 'jenisFunction'=>'baru');
                return response()->json($json_return,404);
            }

            // Query Item
            // $query2 = "SELECT 
            //         F.SW NoSPK, G.Description Produk, FORMAT(A.TargetQty,2) QtySpko, FORMAT(A.CompletionQty,2) QtyNthko, FORMAT(A.Weight,2) WeightSpko, FORMAT(A.CompletionWeight,2) WeightNthko, 
            //         FORMAT((A.TargetQty-A.CompletionQty),2) JmlSelisih, FORMAT((A.Weight-A.CompletionWeight),2) BrtSelisih 
            //     FROM workallocationresult A
            //         LEFT JOIN workallocation B ON A.SW=B.SW
            //         LEFT JOIN workallocationitem C ON B.ID=C.IDM 
            //         LEFT JOIN workcompletion D ON A.SW=D.WorkAllocation
            //         LEFT JOIN workcompletionitem E ON D.ID=E.IDM AND C.WorkOrder=E.WorkOrder
            //         LEFT JOIN workorder F ON C.WorkOrder=F.ID
            //         LEFT JOIN product G ON F.Product=G.ID
            //     WHERE A.SW=$id
            //     GROUP BY F.ID
            //     ORDER BY F.ID
            //     ";
            $query2 = "SELECT W.SW NoSPK, P.SW Production, P.Description Produk, A.QtyAllocation, C.QtyCompletion, A.WeightAllocation,
                            C.WeightCompletion, A1.QtyAllocationActive, C1.QtyCompletionActive, A1.WeightAllocationActive,
                            C1.WeightCompletionActive, W.SWYear, W.SWOrdinal,
                            IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) QtyAllocationTotal,
                            IfNull(C.QtyCompletion, 0) + IfNull(C1.QtyCompletionActive, 0) QtyCompletionTotal,
                            IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) WeightAllocationTotal,
                            IfNull(C.WeightCompletion, 0) + IfNull(C1.WeightCompletionActive, 0) WeightCompletionTotal,
                            IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) - IfNull(C.QtyCompletion, 0) - IfNull(C1.QtyCompletionActive, 0) QtyDifference,
                            IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) - IfNull(C.WeightCompletion, 0) - IfNull(C1.WeightCompletionActive, 0) WeightDifference
                        From (  Select Distinct I.WorkOrder
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $id
                                Union
                                Select Distinct I.WorkOrder
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $id ) O
                        Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocation,
                                    Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocation
                                    From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                    Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $id
                                    Group By I.WorkOrder ) A On A.WorkOrder = O.WorkOrder
                        Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletion,
                                    Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletion
                                    From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                    Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $id
                                    Group By I.WorkOrder ) C On C.WorkOrder = O.WorkOrder
                        Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocationActive,
                                    Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocationActive
                                    From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                    Where I.WorkOrder Is Not Null And A.Active = 'A' And A.SW = $id
                                    Group By I.WorkOrder ) A1 On A1.WorkOrder = O.WorkOrder
                        Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletionActive,
                                    Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletionActive
                                    From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                    Where I.WorkOrder Is Not Null And A.Active = 'A' And A.WorkAllocation = $id
                                    Group By I.WorkOrder ) C1 On C1.WorkOrder = O.WorkOrder
                        Join WorkOrder W On O.WorkOrder = W.ID
                        Join Product P On W.Product = P.ID
                        Order By W.SWYear, W.SWOrdinal
                        ";
            $data2 = FacadesDB::connection('erp')->select($query2);

            $returnHTML = view('Produksi.PelaporanProduksi.Susutan.baruView', compact('location','data','data2'))->render();

            $json_return = array(
                'status' => 'Success',
                'html' => $returnHTML
            );

            return response()->json($json_return,200);
        }

    }

    public function cekOperation($swspko,$prosesid){
        
        if($prosesid == 48 || $prosesid == 89){ // Rep Enamel
            $repType = 'Rep'; // Brg Rep selisihBrtSPK harus 0 atau lebih dari 0
        }else{
            $repType = 'NonRep'; //  Brg NonRep selisihBrtSPK harus 0 atau kurang dari 0
        }

        // Query Selisih Berat SPK
        $query = "SELECT W.SW, P.SW Production, A.QtyAllocation, C.QtyCompletion, A.WeightAllocation,
                        C.WeightCompletion, A1.QtyAllocationActive, C1.QtyCompletionActive, A1.WeightAllocationActive,
                        C1.WeightCompletionActive, W.SWYear, W.SWOrdinal,
                        IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) QtyAllocationTotal,
                        IfNull(C.QtyCompletion, 0) + IfNull(C1.QtyCompletionActive, 0) QtyCompletionTotal,
                        IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) WeightAllocationTotal,
                        IfNull(C.WeightCompletion, 0) + IfNull(C1.WeightCompletionActive, 0) WeightCompletionTotal,
                        IfNull(A.QtyAllocation, 0) + IfNull(A1.QtyAllocationActive, 0) - IfNull(C.QtyCompletion, 0) - IfNull(C1.QtyCompletionActive, 0) QtyDifference,
                        IfNull(A.WeightAllocation, 0) + IfNull(A1.WeightAllocationActive, 0) - IfNull(C.WeightCompletion, 0) - IfNull(C1.WeightCompletionActive, 0) WeightDifference
                    From (  Select Distinct I.WorkOrder
                            From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                            Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $swspko
                            Union
                            Select Distinct I.WorkOrder
                            From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                            Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $swspko ) O
                    Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocation,
                                Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocation
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.SW = $swspko
                                Group By I.WorkOrder ) A On A.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletion,
                                Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletion
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active In ('P', 'S') And A.WorkAllocation = $swspko
                                Group By I.WorkOrder ) C On C.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(If(A.Purpose='Tambah', I.Qty, I.Qty*-1)) QtyAllocationActive,
                                Sum(If(A.Purpose='Tambah', I.Weight, I.Weight*-1)) WeightAllocationActive
                                From WorkAllocation A Join WorkAllocationItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active = 'A' And A.SW = $swspko
                                Group By I.WorkOrder ) A1 On A1.WorkOrder = O.WorkOrder
                    Left Join ( Select I.WorkOrder, Sum(I.Qty+I.RepairQty+I.ScrapQty) QtyCompletionActive,
                                Sum(I.Weight+I.RepairWeight+I.ScrapWeight) WeightCompletionActive
                                From WorkCompletion A Join WorkCompletionItem I On A.ID = I.IDM
                                Where I.WorkOrder Is Not Null And A.Active = 'A' And A.WorkAllocation = $swspko
                                Group By I.WorkOrder ) C1 On C1.WorkOrder = O.WorkOrder
                    Join WorkOrder W On O.WorkOrder = W.ID
                    Join Product P On W.Product = P.ID
                    Order By W.SWYear, W.SWOrdinal";
        $data = FacadesDB::connection('erp')->select($query);

        foreach ($data as $datas){
            if($datas->WeightDifference > 0){
                $statusNilai = 'Positive';
            }else if($datas->WeightDifference < 0){
                $statusNilai = 'Negative';
            }else{
                $statusNilai = 'Zero';
            }

            if($repType == 'NonRep' && $statusNilai == 'Positive' ){
                $statusFix = 'NonRep';
            }
            // else if($repType == 'Rep' && $statusNilai == 'Negative'){
            //     $statusFix = 'Rep';
            // }
            else{
                $statusFix = 'Next';
            }
            // dd($statusFix);
            if($statusFix == 'NonRep'){
                $json_return = array(
                    "success"=>false,
                    "message"=>"Barang NonRep, SPK Tidak Seimbang, $datas->SW",
                    "data"=>null,
                    "error"=>[
                        "repType"=>"Barang NonRep, Selisih Berat SPK harus 0 atau Kurang dari 0 !"
                    ]
                );
                return $json_return;
            }else if($statusFix == 'Rep'){
                $json_return = array(
                    "success"=>false,
                    "message"=>"Barang Rep, SPK Tidak Seimbang, $datas->SW",
                    "data"=>null,
                    "error"=>[
                        "repType"=>"Barang Rep, Selisih Berat SPK harus 0 atau Lebih dari 0 !"
                    ]
                );
                return $json_return;
            }else{
                $json_return = array(
                    "success"=>true
                );
                return $json_return;
            }
        }

    }

    public function CheckPercentage($limitPersen,$brtSusutan,$brtSPKO){
        $cekSusutan = ($brtSusutan / $brtSPKO * 100);
        if($cekSusutan > $limitPersen){
            $json_return = array('message' => 'Nilai Susutan terlalu besar. Hubungi Dhora untuk menyusutkan Nota Terima.');
            return response()->json($json_return,404);
        }
    }

    public function simpan(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        // Cek StokHarian Susutan
        // $querySH = "SELECT id FROM location WHERE id = $location AND stockdate = (SELECT max( transdate ) transdate FROM workdate WHERE transdate < CURDATE() AND holiday = 'N')";
        
        $swspko = $request->nospko;
        $prosesid = $request->prosesid;
        $catatan = ((isset($request->catatan)) ? $request->catatan : 'Laravel');

        if($location == 10 || $location == 47 || $location == 49 || $location == 12 || $location == 52 || $location == 48 || $location == 17 || $location == 53){
            
            $queryCekBalance = "SELECT * FROM workallocationresult A WHERE A.SW=$swspko";
            $cekBalance = FacadesDB::connection('erp')->select($queryCekBalance);
            foreach ($cekBalance as $cekBalances){}

            if($cekBalances->TargetQty <> $cekBalances->CompletionQty){
                $json_return = array('message' => 'Qty SPKO dan NTHKO Tidak Seimbang!');
                return response()->json($json_return,404);
            }
        }else if($location == 50){
            $queryCekBalance = "SELECT
                                    A.SW, SUM(B.Qty) qtySPKO, SUM(B.Weight) weightSPKO, SUM(D.Qty+D.RepairQty+D.ScrapQty) qtyNTHKO, SUM(D.Weight) weightNTHKO 
                                FROM workallocation A
                                    JOIN workallocationitem B ON A.ID=B.IDM
                                    JOIN workcompletion C ON A.SW=C.WorkAllocation
                                    JOIN workcompletionitem D ON C.ID=D.IDM
                                    JOIN product E ON B.Product=E.ID
                                WHERE A.SW=$swspko
                                    AND C.Active<>'C'
                                    AND E.ProdGroup=119
                                    AND E.Model=64
                                ";
            $cekBalance = FacadesDB::connection('erp')->select($queryCekBalance);
            foreach ($cekBalance as $cekBalances){}  
            
            if($cekBalances->qtySPKO <> $cekBalances->qtyNTHKO){
                $json_return = array('message' => 'Qty SPKO dan NTHKO Tidak Seimbang!');
                return response()->json($json_return,404);
            }
        }

        // Check Susutan Besar
        // if($location == 10 || $location == 15){
        //     $this->CheckPercentage(3);
        // }


        if($location == 47){

            if($prosesid == 48 || $prosesid == 89){ // Rep Enamel
                $repType = 'Rep'; // Brg Rep selisihBrtSPK harus 0 atau lebih dari 0
            }else{
                $repType = 'NonRep'; //  Brg NonRep selisihBrtSPK harus 0 atau kurang dari 0
            }
            
            // dd($prosesid);
            $statusOp = $this->cekOperation($swspko,$prosesid);
            // dd($statusOp);
            if($statusOp['success'] == false){
                return response()->json($statusOp,400);
            }

        }

        // Last ID
        $query = "SELECT LAST+1 AS ID FROM lastid Where Module = 'WorkShrink' ";
        $data = FacadesDB::connection('erp')->select($query);
        foreach ($data as $datas){}

        $data2 = lastid::where('Module', 'WorkShrink')->update([
            'Last' => $datas->ID
        ]);

        try { 
            FacadesDB::connection('erp')->beginTransaction();
        
            $insertSusutan = workshrink::create([
                'ID' => $datas->ID,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => Auth::user()->name,
                'Remarks' => $catatan,
                'TransDate' => $request->tgl,
                'Allocation' => $request->nospko,
                'Shrink' => $request->susutbrt,
                'Tolerance' => $request->toleransi,
                'Difference' => $request->perbedaan,
                'Active' => 'P'
            ]);

            $updateWAR = workallocationresult::where('SW', $request->nospko)->update([
                'Shrink' => $request->susutbrt, 
                'ShrinkDate' => now()
            ]);

            $updateSPKO = workallocation::where('SW', $request->nospko)->update([
                'Active' => 'S'
            ]);

            $updateNTHKO = workcompletion::where('WorkAllocation', $request->nospko)->where('Active','P')->update([
                'Active' => 'S'
            ]);

            FacadesDB::connection('erp')->commit();
            $json_return = array(
                'status' => 'Success',
                'id' => $swspko,
                'message' => 'Simpan Berhasil !'
            );
            return response()->json($json_return,200);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'status' => 'Failed',
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }

    }

    public function simpanTest(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        // dd($request);
        $swspko = $request->nospko;
        $prosesid = $request->prosesid;
        $catatan = ((isset($request->catatan)) ? $request->catatan : 'Laravel');

        $json_return = array(
            'id' => $swspko
        );
        // dd($json_return);
        return response()->json($json_return,200);

    }

    public function cetak(Request $request){

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;

        if($id == NULL || $id == ''){
            $json_return = array('message' => 'Harap Isi Kolom Cari!');
            return response()->json($json_return,404);
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        // Query Header
        $query = "SELECT E.Description Pekerja, F.Description Kadar, D.ID IdSusutan, A.SW NoSPKO, FORMAT(A.Weight,2) BrtSPKO, FORMAT(A.CompletionWeight,2) BrtNTHKO, G.Description Bagian, H.Description Proses, 
                    DATE_FORMAT(D.TransDate, '%d/%m/%y') Tgl, FORMAT(D.Shrink,2) Susut, FORMAT(D.Difference,2) Perbedaan, FORMAT((D.Shrink/A.Weight)*100,2) SusutPersen, D.Remarks Catatan, 
                    FORMAT(D.Tolerance,2) Toleransi, D.UserName User, DATE_FORMAT(D.EntryDate, '%d/%m/%y') EntryDate
                FROM workallocationresult A 
                    LEFT JOIN workshrink D ON A.SW=D.Allocation
                    LEFT JOIN employee E ON A.Employee=E.ID
                    LEFT JOIN productcarat F ON A.Carat=F.ID
                    LEFT JOIN location G ON A.Location=G.ID
                    LEFT JOIN operation H ON A.Operation=H.ID
                WHERE A.SW=$id";
                $data = FacadesDB::connection('erp')->select($query);

        return view('Produksi.PelaporanProduksi.Susutan.cetak', compact('location','data','username','timenow','datenow'));

    }

}
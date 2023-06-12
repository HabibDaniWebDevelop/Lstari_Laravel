<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\erp\lastid;
use App\Models\erp\workorder as erp_workorder;
use App\Models\erp\workorderitem as erp_workorderitem;
use App\Models\rndnew\workorder;
use App\Models\rndnew\workorderitem;
use App\Models\rndnew\tempstonepcb;

class SPKPercobaanTanpaKaretController extends Controller
{
    public function index()
    {
        return view('R&D.Percobaan.SPKPercobaanTanpaKaret.index');
    }

    public function show($no, $id)
    {
        // dd($no, $id);

        //lihat
        if ($no == '1') {
            $datas = FacadesDB::table('workorder')
                ->where('swpurpose', '=', 'PCB')
                ->orderBy('transdate', 'desc')
                ->orderBy('SW', 'desc')
                ->Paginate(25);

            return view('R&D.Percobaan.SPKPercobaanTanpaKaret.show', compact('datas'));
        }

        //tambah
        if ($no == '2') {
            $username = Auth::user()->name;
            $datas = FacadesDB::select("SELECT
                        ID, SKU, Alloy
                    FROM productcarat
                    WHERE Regular = 'Y'
                    ORDER BY SKU, Alloy
                ");

            // dd($datas);

            return view('R&D.Percobaan.SPKPercobaanTanpaKaret.create', compact('no', 'datas'));
        }

        //getproduct
        if ($no == '4') {
            $datas = FacadesDB::select("SELECT
                    p.SKU,
                    p.ID idprod,
                    p.SW SWProduk,
                    st.Description Kategori
                FROM
                    erp.product p
                    JOIN shorttext st ON p.ProdGroup = st.ID
                WHERE
                    (
                    p.SW = '$id'
                    OR p.SKU = '$id'
                    OR p.ID = '$id')
                ");
            
            $cari = $datas[0]->idprod;

            //kepala
            $getkepala = FacadesDB::select("SELECT
                            pc.IDM,
                            GROUP_CONCAT( CASE WHEN mc.SW IS NULL THEN pc.Note ELSE mc.SW END ORDER BY pc.Ordinal ASC SEPARATOR ', ' ) AS Serial,
                            COUNT( pc.Ordinal ) AS JumlahKepala
                        FROM
                            erp.product p
                            JOIN productkepala pc ON p.ID = pc.IDM
                            LEFT JOIN masterkepala mc ON pc.Kepala = mc.ID
                        WHERE
                            p.ID = '$cari'
                            AND pc.STATUS = 'Cor'");

            //component
            $getcomponent = FacadesDB::select("SELECT
                    pc.IDM,
                    GROUP_CONCAT( CASE WHEN pc.Note IS NULL THEN mc.SW ELSE pc.Note END ORDER BY pc.Ordinal ASC SEPARATOR ', ' ) AS Serial,
                    COUNT( pc.Ordinal ) AS Jumlahcomponent
                FROM
                    erp.product p
                    JOIN productcomponent pc ON p.ID = pc.IDM
                    LEFT JOIN mastercomponent mc ON pc.Component = mc.ID
                WHERE
                    p.ID = '$cari'
                    AND pc.STATUS = 'Cor'");

            //mainan
            $getmainan = FacadesDB::select("SELECT
                    pc.IDM,
                    GROUP_CONCAT( CASE WHEN mc.OldSW IS NULL THEN mc.SW ELSE mc.OldSW END ORDER BY pc.Ordinal ASC SEPARATOR ', ' ) AS Serial,
                    COUNT( pc.Ordinal ) AS Jumlahmainan
                FROM
                    erp.product p
                    JOIN productmn pc ON p.ID = pc.IDM
                    JOIN mastermainan mc ON pc.Mainan = mc.ID
                WHERE
                    p.ID = '$cari'
                    AND pc.Note IS NULL
                    AND pc.STATUS = 'Cor'");

            // dd ($getkepala[0]->Serial, $getcomponent[0]->Serial, $getmainan[0]->Serial);

            if ($datas) {
                return response()->json([
                    'success' => true,
                    'SKU' => $datas[0]->SKU,
                    'idprod' => $datas[0]->idprod,
                    'SWProduk' => $datas[0]->SWProduk,
                    'Kategori' => $datas[0]->Kategori,
                    'kepala' => $getkepala[0]->Serial,
                    'component' => $getcomponent[0]->Serial,
                    'mainan' => $getmainan[0]->Serial,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $tahun = 50 + (int)date('y');
        $bulan = date('m');
        $bln = date('n');
        $tahunPolling = date('y');
        $bulanSkrng = date('M');

        $jumlahKadar = count($request->idKadar);
        $jumlahBaris = count($request->no);

        $type = 0;
        $totalqty = 0;
        for ($i = 0; $i < $jumlahBaris; $i++) {
            $totalqty += 3;
            $type++;
        }

        for ($y = 0; $y < $jumlahKadar; $y++) {
            //ambil SWOrdinal Terakhir di tahun ini
            $getOrdProses = FacadesDB::connection('erp')->select("SELECT
                    CASE
                            
                        WHEN
                            MAX( SWOrdinal ) IS NULL THEN
                                CONCAT( 50+'$tahun', '01', '80001' ) ELSE CONCAT( SWYear, LPAD( SWMonth, 2, '0' ), MAX( SWOrdinal )+ 1 )
                                END AS LastID,
                    CASE
                            
                            WHEN MAX( SWOrdinal ) IS NULL THEN
                            '80001' ELSE MAX( SWOrdinal )+ 1
                        END AS LastOrdinal
                    FROM
                        workorder
                    WHERE
                        SWYear = '$tahun'
                    AND SWMonth = '$bln'
                    AND SWPurpose = 'PCB'
                ");

            if ($getOrdProses) {
                $lastSWOrdinal = $getOrdProses[0]->LastOrdinal;
                $lastOrdinal = $getOrdProses[0]->LastID;
                $idDesainAwal = 'PCB' . $lastOrdinal;
                $SWUsed = $getOrdProses[0]->LastID;
            }

            $getID = FacadesDB::connection('erp')->select("SELECT Last+1 as maxID FROM lastid WHERE Module = 'WorkOrder' ");
            $idWO = $getID[0]->maxID;

            $insert_workorder_erp = erp_workorder::create([
                'ID' => $idWO,
                'UserName' => $username,
                'SW' => $idDesainAwal,
                'TransDate' => now(),
                'Product' => 4857,
                'Carat' => $request->idKadar[$y],
                'TotalType' => $type,
                'TotalQty' => $totalqty,
                'Active' => 'A',
                'Polling' => $bulanSkrng,
                'SWPurpose' => 'PCB',
                'SWOrdinal' => $lastSWOrdinal,
                'SWYear' => $tahun,
                'Outsource' => 'N',
                'SWMonth' => $bulan,
                'SWUsed' => $SWUsed,
                'IDInt' => $idWO,
            ]);

            $insert_workorder = workorder::create([
                'ID' => $idWO,
                'UserName' => $username,
                'SW' => $idDesainAwal,
                'TransDate' => now(),
                'Product' => 4857,
                'Carat' => $request->idKadar[$y],
                'TotalType' => $type,
                'TotalQty' => $totalqty,
                'Active' => 'A',
                'Polling' => $bulanSkrng,
                'SWPurpose' => 'PCB',
                'SWOrdinal' => $lastSWOrdinal,
                'SWYear' => $tahun,
                'Outsource' => 'N',
                'SWMonth' => $bulan,
                'SWUsed' => $SWUsed,
                'IDInt' => $idWO,
            ]);

            $updateID = lastid::where('Module', 'WorkOrder')
                ->update(['Last' => $idWO]);

            $no = 0;
            for ($t = 0; $t < $jumlahBaris; $t++) {
                $no++;
                $insert_workorderitem_erp = erp_workorderitem::create([
                    'IDM' => $idWO,
                    'Ordinal' => $no,
                    'Product' => $request->idprod[$t],
                    'Qty' => '3',
                    'Remarks' => 'Tanpa Karet',
                    'IDMInt' => $idWO,
                ]);

                $insert_workorderitem = workorderitem::create([
                    'IDM' => $idWO,
                    'Ordinal' => $no,
                    'Product' => $request->idprod[$t],
                    'Qty' => '3',
                    'Remarks' => 'Tanpa Karet',
                    'IDMInt' => $idWO,
                ]);
            }
        }

        // dd($jumlahKadar, $jumlahBaris, $totalqty, $type);
        if ($insert_workorder) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Berhasil!!',
                ],
                201,
            );
        }
    }

    public function cetak(Request $request)
    {
        $id = $request->id;
        $fullname = $request->session()->get('fullname');
        $iduser = $request->session()->get('iduser');
        $username = Auth::user()->name;

        $getheader = FacadesDB::select(" SELECT
                wo.TransDate,
                wo.SW,
                pc.Description,
                pc.OtherDescription
            FROM
                workorder wo
                JOIN productcarat pc ON wo.Carat = pc.ID
            WHERE
                wo.ID = '$id'
            ");

        $getbodys = FacadesDB::select("SELECT
                wo.TotalQty,
                wo.TotalType,
                wo.TotalWeight,
                woi.Ordinal,
                woi.Product,
                woi.Remarks,
            CASE
                    
                    WHEN p.SKU IS NULL THEN
                    p.SW ELSE p.SKU
                END AS SW,
                woi.Qty,
                r.ID IDkaret,
                st.Description Kategori,
            CASE
                    
                    WHEN ds.SW IS NULL THEN
                    ''
                    WHEN ds.ID = 0 THEN
                    '' ELSE ds.SW
                END AS Ukuran
            FROM
                workorder wo
                JOIN workorderitem woi ON wo.ID = woi.IDM
                LEFT JOIN rubber r ON woi.Remarks = r.ID
                JOIN erp.product p ON woi.Product = p.ID
                LEFT JOIN erp.product pt ON p.Model = pt.ID
                LEFT JOIN shorttext st ON pt.ProdGroup = st.ID
                LEFT JOIN designsize ds ON p.VarSize = ds.ID
            WHERE
                wo.ID = '$id'
            ORDER BY
                p.SW,
                ds.SW");
        //kepala
        $i = 0;
        foreach ($getbodys as $datas) {
            $i++;
            $getkepala[$i] = FacadesDB::select("SELECT
                            pc.IDM,
                            GROUP_CONCAT( CASE WHEN mc.SW IS NULL THEN pc.Note ELSE mc.SW END ORDER BY pc.Ordinal ASC SEPARATOR ', ' ) AS Serial,
                            COUNT( pc.Ordinal ) AS JumlahKepala
                        FROM
                            erp.product p
                            JOIN productkepala pc ON p.ID = pc.IDM
                            LEFT JOIN masterkepala mc ON pc.Kepala = mc.ID
                        WHERE
                            p.ID = '$datas->Product'
                            AND pc.STATUS = 'Cor'");
        }

        //component
        $i = 0;
        foreach ($getbodys as $datas) {
            $i++;
            $getcomponent[$i] = FacadesDB::select("SELECT
                    pc.IDM,
                    GROUP_CONCAT( CASE WHEN pc.Note IS NULL THEN mc.SW ELSE pc.Note END ORDER BY pc.Ordinal ASC SEPARATOR ', ' ) AS Serial,
                    COUNT( pc.Ordinal ) AS Jumlahcomponent
                FROM
                    erp.product p
                    JOIN productcomponent pc ON p.ID = pc.IDM
                    LEFT JOIN mastercomponent mc ON pc.Component = mc.ID
                WHERE
                    p.ID = '$datas->Product'
                    AND pc.STATUS = 'Cor'");
        }

        //mainan
        $i = 0;
        foreach ($getbodys as $datas) {
            $i++;
            $getmainan[$i] = FacadesDB::select("SELECT
                    pc.IDM,
                    GROUP_CONCAT( CASE WHEN mc.OldSW IS NULL THEN mc.SW ELSE mc.OldSW END ORDER BY pc.Ordinal ASC SEPARATOR ', ' ) AS Serial,
                    COUNT( pc.Ordinal ) AS Jumlahmainan
                FROM
                    erp.product p
                    JOIN productmn pc ON p.ID = pc.IDM
                    JOIN mastermainan mc ON pc.Mainan = mc.ID
                WHERE
                    p.ID = '$datas->Product'
                    AND pc.Note IS NULL
                    AND pc.STATUS = 'Cor'");
        }

        $kikiss = FacadesDB::select("SELECT
                wo.TotalQty,
                wo.TotalType,
                wo.TotalWeight,
                woi.Ordinal,
                woi.Product IDProduk,
                wo.Carat
            FROM
                workorder wo
                JOIN workorderitem woi ON wo.ID = woi.IDM
            WHERE
                wo.ID = '$id'");

        foreach ($kikiss as $kikis) {
            $i++;
            $cekStone = FacadesDB::select("SELECT
                    COUNT( dd.ID ) AS SW
                FROM
                    drafter3d dd
                    JOIN erp.product p ON dd.Product = p.ID
                WHERE
                    p.ID = '$kikis->IDProduk' AND dd.SW IS NOT NULL");

            if ($cekStone[0]->SW == '0') {
                $getStoneS = FacadesDB::select("SELECT
                        ds.IDM,
                        ds.Ordinal,
                        ds.Product,
                        pt.SW KodeBatu,
                        ds.Qty3D Qty,
                        ds.`Status`,
                        pt.Fee
                    FROM
                        drafter3d dd
                        JOIN drafter3dstone ds ON dd.ID = ds.IDM
                        JOIN erp.product p ON dd.Product = p.ID
                        JOIN erp.product pt ON ds.Product = pt.ID
                    WHERE
                        p.ID = '$kikis->IDProduk'
                        GROUP BY ds.Ordinal");

                foreach ($getStoneS as $getStone) {
                    $insert_tempstonepcb = tempstonepcb::create([
                        'SW' => $getStone->KodeBatu,
                        'Stone' => $getStone->Product,
                        'Qty' => $getStone->Qty,
                        'UserName' => $username,
                        'Ordinal' => $kikis->Ordinal,
                    ]);
                }
            } else {
                $getStone2 = FacadesDB::select("SELECT
                        ds.IDM,
                        ds.Ordinal,
                        ds.Product,
                        p.SW KodeBatu,
                        ds.Qty3D Qty,
                        ds.`Status`,
                        p.Weight
                    FROM
                        drafter3d dd
                        JOIN drafter3dstone ds ON dd.ID = ds.IDM
                        JOIN masterstone p ON ds.Product = p.ID
                    WHERE
                        dd.Product =  '$kikis->IDProduk'
                        GROUP BY ds.Ordinal");

                foreach ($getStone2 as $getStone) {
                    $insert_tempstonepcb = tempstonepcb::create([
                        'SW' => $getStone->KodeBatu,
                        'Stone' => $getStone->Product,
                        'Qty' => $getStone->Qty,
                        'UserName' => $username,
                        'Ordinal' => $kikis->Ordinal,
                    ]);
                }
            }
        }

        $getStone3 = FacadesDB::select("SELECT
                Stone,
                SW,
                SUM( Qty ) AS Jumlah
            FROM
                tempstonepcb
            WHERE
                UserName = '$username'
            GROUP BY Stone
            ORDER BY Stone");
        $deleted = tempstonepcb::truncate();
        // dd($getStone3, $getStone3);

        return view('R&D.Percobaan.SPKPercobaanTanpaKaret.cetak', compact('id', 'getheader', 'getbodys', 'getkepala', 'getcomponent', 'getmainan', 'fullname', 'iduser', 'getStone3'));
    }
}

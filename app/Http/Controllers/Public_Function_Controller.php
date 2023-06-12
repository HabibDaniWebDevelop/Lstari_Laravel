<?php

namespace App\Http\Controllers;

use App\Models\tes_laravel\Stock as dev_Stock;
use App\Models\tes_laravel\ProductTrans as dev_ProductTrans;
use App\Models\tes_laravel\userhistoryweb as dev_userhistoryweb;
use App\Models\tes_laravel\lastid as dev_lastid;
use App\Models\tes_laravel\transferrm as dev_transferrm;
use App\Models\tes_laravel\transferrmitem as dev_transferrmitem;

use App\Models\erp\Stock as erp_Stock;
use App\Models\erp\ProductTrans as erp_ProductTrans;
use App\Models\erp\userhistoryweb as erp_userhistoryweb;
use App\Models\erp\lastid as erp_lastid;
use App\Models\erp\transferrm as erp_transferrm;
use App\Models\erp\transferrmitem as erp_transferrmitem;

use App\Models\rndnew\lastid as rnd_lastid;

use Illuminate\Http\Request;
use App\Http\Controllers\tesfungsi;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB as FacadesDB;

class public_function_Controller extends Controller
{
    protected $tesfungsi;

    public function __construct(tesfungsi $tesfungsi)
    {
        $this->tesfungsi = $tesfungsi;
    }

    public function CekStokHarianERP($idlocation, $transdateposting)
    {
        $CekStokHarian = FacadesDB::connection('erp')->select("SELECT
                        id
                    FROM
                        location
                    WHERE
                        id = '$idlocation'
                        AND
                        stockdate = (
                        SELECT
                            max( transdate ) transdate
                        FROM
                            workdate
                        WHERE
                            transdate < '$transdateposting'
                        AND
                        holiday = 'N'
                        )
                ");

        if ($CekStokHarian) {
            $cek = true;
        } else {
            $cek = false;
        }

        return $cek;
    }

    public function CekStokHarianDEV($idlocation, $transdateposting)
    {
        $CekStokHarian = FacadesDB::connection('dev')->select("SELECT
                        id
                    FROM
                        location
                    WHERE
                        id = '$idlocation'
                        AND
                        stockdate = (
                        SELECT
                            max( transdate ) transdate
                        FROM
                            workdate
                        WHERE
                            transdate < '$transdateposting'
                        AND
                        holiday = 'N'
                        )
                ");

        if ($CekStokHarian) {
            $cek = true;
        } else {
            $cek = false;
        }

        return $cek;
    }

    public function CekStokHarian2ERP($FromLoc, $ToLoc, $TransDate)
    {
        $CekStokHarian1 = FacadesDB::connection('erp')->select("SELECT
                        id
                    FROM
                        location
                    WHERE
                        id = '$FromLoc'
                        AND
                        stockdate = (
                        SELECT
                            max( transdate ) transdate
                        FROM
                            workdate
                        WHERE
                            transdate < '$TransDate'
                        AND
                        holiday = 'N'
                        )
                ");

        $CekStokHarian2 = FacadesDB::connection('erp')->select("SELECT
                        id
                    FROM
                        location
                    WHERE
                        id = '$ToLoc'
                        AND
                        stockdate = (
                        SELECT
                            max( transdate ) transdate
                        FROM
                            workdate
                        WHERE
                            transdate < '$TransDate'
                        AND
                        holiday = 'N'
                        )
                ");

        // dd($CekStokHarian1, $CekStokHarian2);

        if ($CekStokHarian1 && $CekStokHarian2) {
            $cek = true;
        } else {
            $cek = false;
        }

        return $cek;
    }

    public function CekStokHarian2DEV($FromLoc, $ToLoc, $TransDate)
    {
        $CekStokHarian1 = FacadesDB::connection('dev')->select("SELECT
                        id
                    FROM
                        location
                    WHERE
                        id = '$FromLoc'
                        AND
                        stockdate = (
                        SELECT
                            max( transdate ) transdate
                        FROM
                            workdate
                        WHERE
                            transdate < '$TransDate'
                        AND
                        holiday = 'N'
                        )
                ");

        $CekStokHarian2 = FacadesDB::connection('dev')->select("SELECT
                        id
                    FROM
                        location
                    WHERE
                        id = '$ToLoc'
                        AND
                        stockdate = (
                        SELECT
                            max( transdate ) transdate
                        FROM
                            workdate
                        WHERE
                            transdate < '$TransDate'
                        AND
                        holiday = 'N'
                        )
                ");

        // dd($CekStokHarian1, $CekStokHarian2);

        if ($CekStokHarian1 && $CekStokHarian2) {
            $cek = true;
        } else {
            $cek = false;
        }

        return $cek;
    }

    public function PostingERP($status, $tablename, $UserName, $Location, $Product, $Carat, $Process, $cause, $LinkSW, $LinkID, $LinkOrd, $workorder)
    {
        $validator = Validator::make(
            [
                'status' => $status,
                'tablename' => $tablename,
                'UserName' => $UserName,
                'Location' => $Location,
                'Product' => $Product,
                'Process' => $Process,
                'cause' => $cause,
                'LinkSW' => $LinkSW,
                'LinkID' => $LinkID,
                'LinkOrd' => $LinkOrd,
            ],
            [
                'status' => 'required',
                'tablename' => 'required',
                'UserName' => 'required',
                'Location' => 'required|numeric',
                'Product' => 'required|numeric',
                'Process' => 'required',
                'cause' => 'required',
                'LinkSW' => 'required|numeric',
                'LinkID' => 'required|numeric',
                'LinkOrd' => 'required|numeric',
            ],
        );

        if ($validator->fails()) {
            $failed = $validator->failed();
            // dd($failed);
            return ['validasi' => $failed, 'insertstok' => false, 'update_ptrns' => false];
        }

        // dd($validator);

        $tgl = date('Y-m-d');
        $tglfull = date('Y-m-d h:i:s');
        // $workorder == '' ? ($workorder = 'NULL') : ($workorder = "'$workorder'");

        $data = FacadesDB::connection('erp')->select(' SELECT * FROM ' . $tablename . ' WHERE IDM = ' . $LinkID . ' AND Ordinal = ' . $LinkOrd . ' ORDER BY Ordinal ASC ');
        foreach ($data as $datas) {
            $producttrans = FacadesDB::connection('erp')->select(' SELECT Qty QtySaldo, Weight QtyWeight, ID From ProductTrans Where (Product = ' . $Product . ') And (Location = ' . $Location . ") And(IfNull(Carat, '') = '" . $Carat . "') LIMIT 1 ");
            // dd($producttrans);

            foreach ($producttrans as $prodtrns) {
            }

            //! penyesuaiaan untuk workcompletion mengcover barang reparasi dan barang rusak
            if ($tablename == 'workcompletionitem') {
                $datas->Weight = $datas->Weight + $datas->RepairWeight + $datas->ScrapWeight;
                $datas->Qty = $datas->Qty + $datas->RepairQty + $datas->ScrapQty;
            }

            $weight = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) + (isset($datas->Weight) ? $datas->Weight : 0);
            $qty = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) + (isset($datas->Qty) ? $datas->Qty : 0);
            $weightmin = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) - (isset($datas->Weight) ? $datas->Weight : 0);
            $qtymin = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) - (isset($datas->Qty) ? $datas->Qty : 0);

            if ($status == 'D') {
                $QtyInWeight = $datas->Weight;
                $QtyOutWeight = '0';
                $QtyWeight = $weight;
                $QtyIn = $datas->Qty;
                $QtyOut = '0';
                $QtySaldo = $qty;
            } elseif ($status == 'C') {
                $QtyInWeight = '0';
                $QtyOutWeight = $datas->Weight;
                $QtyWeight = $weightmin;
                $QtyIn = '0';
                $QtyOut = $datas->Qty;
                $QtySaldo = $qtymin;
            }
        }

        $insertstok = erp_Stock::create([
            'EntryDate' => $tglfull,
            'UserName' => $UserName,
            'Location' => $Location,
            'Product' => $Product,
            'Carat' => $Carat,
            'TransDate' => $tgl,
            'Process' => $Process,
            'Cause' => $cause,
            'LinkSW' => $LinkSW,
            'LinkID' => $LinkID,
            'LinkOrd' => $LinkOrd,
            'WorkOrder' => $workorder,
            'Value' => '0',
            'QtyInWeight' => $QtyInWeight,
            'QtyOutWeight' => $QtyOutWeight,
            'QtyWeight' => $QtyWeight,
            'QtyIn' => $QtyIn,
            'QtyOut' => $QtyOut,
            'QtySaldo' => $QtySaldo,
        ]);
        // dd($insertstok);

        $producttrans2 = FacadesDB::connection('erp')->select(' SELECT Qty QtySaldo, Weight QtyWeight, ID From ProductTrans Where (Product = ' . $Product . ') And (Location = ' . $Location . ") And(IfNull(Carat, '') = '" . $Carat . "') LIMIT 1  ");

        $wordCount = count($producttrans2);
        if ($wordCount > 0) {
            foreach ($producttrans2 as $prodtrns) {
                $update_ptrns = erp_ProductTrans::find($prodtrns->ID);
                $update_ptrns->Qty = $QtySaldo;
                $update_ptrns->Weight = $QtyWeight;
                $update_ptrns->save();
            }
        } else {
            $update_ptrns = erp_ProductTrans::create([
                'Product' => $Product,
                'Carat' => $Carat,
                'Location' => $Location,
                'Qty' => $QtySaldo,
                'Weight' => $QtyWeight,
                'Value' => '0',
            ]);
        }

        if ($insertstok) {
            $insertstok = true;
        } else {
            $insertstok = false;
        }
        if ($update_ptrns) {
            $update_ptrns = true;
        } else {
            $update_ptrns = false;
        }

        return ['validasi' => true, 'insertstok' => $insertstok, 'update_ptrns' => $update_ptrns];
    }

    public function PostingDEV($status, $tablename, $UserName, $Location, $Product, $Carat, $Process, $cause, $LinkSW, $LinkID, $LinkOrd, $workorder)
    {
        $validator = Validator::make(
            [
                'status' => $status,
                'tablename' => $tablename,
                'UserName' => $UserName,
                'Location' => $Location,
                'Product' => $Product,
                'Process' => $Process,
                'cause' => $cause,
                'LinkSW' => $LinkSW,
                'LinkID' => $LinkID,
                'LinkOrd' => $LinkOrd,
            ],
            [
                'status' => 'required',
                'tablename' => 'required',
                'UserName' => 'required',
                'Location' => 'required|numeric',
                'Product' => 'required|numeric',
                'Process' => 'required',
                'cause' => 'required',
                'LinkSW' => 'required|numeric',
                'LinkID' => 'required|numeric',
                'LinkOrd' => 'required|numeric',
            ],
        );

        if ($validator->fails()) {
            $failed = $validator->failed();
            // dd($failed);
            return ['validasi' => $failed, 'insertstok' => false, 'update_ptrns' => false];
        }

        // dd($validator);

        $tgl = date('Y-m-d');
        $tglfull = date('Y-m-d h:i:s');
        // $workorder == '' ? ($workorder = 'NULL') : ($workorder = "'$workorder'");

        $data = FacadesDB::connection('dev')->select(' SELECT * FROM ' . $tablename . ' WHERE IDM = ' . $LinkID . ' AND Ordinal = ' . $LinkOrd . ' ORDER BY Ordinal ASC ');
        foreach ($data as $datas) {
            $producttrans = FacadesDB::connection('dev')->select(' SELECT Qty QtySaldo, Weight QtyWeight, ID From ProductTrans Where (Product = ' . $Product . ') And (Location = ' . $Location . ") And(IfNull(Carat, '') = '" . $Carat . "') LIMIT 1 ");
            // dd($producttrans);

            foreach ($producttrans as $prodtrns) {
            }

            //! penyesuaiaan untuk workcompletion mengcover barang reparasi dan barang rusak
            if ($tablename == 'workcompletionitem') {
                $datas->Weight = $datas->Weight + $datas->RepairWeight + $datas->ScrapWeight;
                $datas->Qty = $datas->Qty + $datas->RepairQty + $datas->ScrapQty;
            }

            $weight = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) + (isset($datas->Weight) ? $datas->Weight : 0);
            $qty = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) + (isset($datas->Qty) ? $datas->Qty : 0);
            $weightmin = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) - (isset($datas->Weight) ? $datas->Weight : 0);
            $qtymin = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) - (isset($datas->Qty) ? $datas->Qty : 0);

            if ($status == 'D') {
                $QtyInWeight = $datas->Weight;
                $QtyOutWeight = '0';
                $QtyWeight = $weight;
                $QtyIn = $datas->Qty;
                $QtyOut = '0';
                $QtySaldo = $qty;
            } elseif ($status == 'C') {
                $QtyInWeight = '0';
                $QtyOutWeight = $datas->Weight;
                $QtyWeight = $weightmin;
                $QtyIn = '0';
                $QtyOut = $datas->Qty;
                $QtySaldo = $qtymin;
            }
        }

        $insertstok = dev_Stock::create([
            'EntryDate' => $tglfull,
            'UserName' => $UserName,
            'Location' => $Location,
            'Product' => $Product,
            'Carat' => $Carat,
            'TransDate' => $tgl,
            'Process' => $Process,
            'Cause' => $cause,
            'LinkSW' => $LinkSW,
            'LinkID' => $LinkID,
            'LinkOrd' => $LinkOrd,
            'WorkOrder' => $workorder,
            'Value' => '0',
            'QtyInWeight' => $QtyInWeight,
            'QtyOutWeight' => $QtyOutWeight,
            'QtyWeight' => $QtyWeight,
            'QtyIn' => $QtyIn,
            'QtyOut' => $QtyOut,
            'QtySaldo' => $QtySaldo,
        ]);
        // dd($insertstok);

        $producttrans2 = FacadesDB::connection('dev')->select(' SELECT Qty QtySaldo, Weight QtyWeight, ID From ProductTrans Where (Product = ' . $Product . ') And (Location = ' . $Location . ") And(IfNull(Carat, '') = '" . $Carat . "') LIMIT 1  ");

        $wordCount = count($producttrans2);
        if ($wordCount > 0) {
            foreach ($producttrans2 as $prodtrns) {
                $update_ptrns = dev_ProductTrans::find($prodtrns->ID);
                $update_ptrns->Qty = $QtySaldo;
                $update_ptrns->Weight = $QtyWeight;
                $update_ptrns->save();
            }
        } else {
            $update_ptrns = dev_ProductTrans::create([
                'Product' => $Product,
                'Carat' => $Carat,
                'Location' => $Location,
                'Qty' => $QtySaldo,
                'Weight' => $QtyWeight,
                'Value' => '0',
            ]);
        }

        if ($insertstok) {
            $insertstok = true;
        } else {
            $insertstok = false;
        }
        if ($update_ptrns) {
            $update_ptrns = true;
        } else {
            $update_ptrns = false;
        }

        return ['validasi' => true, 'insertstok' => $insertstok, 'update_ptrns' => $update_ptrns];
    }

    public function PostingNewDEV($status, $UserName, $Location, $Product, $Carat, $Process, $cause, $LinkSW, $LinkID, $LinkOrd, $Qty, $Weight, $workorder)
    {
        //! validasi kelengkapan data
        $validator = Validator::make(
            [
                'status' => $status,
                'UserName' => $UserName,
                'Location' => $Location,
                'Product' => $Product,
                'Process' => $Process,
                'cause' => $cause,
                'LinkSW' => $LinkSW,
                'LinkID' => $LinkID,
                'LinkOrd' => $LinkOrd,
                'Qty' => $Qty,
                'Weight' => $Weight,
            ],
            [
                'status' => 'required',
                'UserName' => 'required',
                'Location' => 'required|numeric',
                'Product' => 'required|numeric',
                'Process' => 'required',
                'cause' => 'required',
                'LinkSW' => 'required|numeric',
                'LinkID' => 'required|numeric',
                'LinkOrd' => 'required|numeric',
                'Qty' => 'required|numeric',
                'Weight' => 'required|numeric',
            ],
        );

        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }

        $tgl = date('Y-m-d');
        $tglfull = date('Y-m-d h:i:s');
        $workorder == '' ? ($workorder = 'NULL') : ($workorder = "'$workorder'");
        $producttrans = FacadesDB::connection('dev')->select(" SELECT Qty QtySaldo, Weight QtyWeight, ID From ProductTrans Where (Product = '$Product') And (Location = '$Location') And(IfNull(Carat, '') = '$Carat') LIMIT 1 ");

        foreach ($producttrans as $prodtrns) {
        }

        $weightPlus = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) + (isset($Weight) ? $Weight : 0);
        $qtyPlus = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) + (isset($Qty) ? $Qty : 0);
        $weightMin = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) - (isset($Weight) ? $Weight : 0);
        $qtyMin = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) - (isset($Qty) ? $Qty : 0);

        if ($status == 'D') {
            $QtyInWeight = $Weight;
            $QtyOutWeight = '0';
            $QtyWeight = $weightPlus;
            $QtyIn = $Qty;
            $QtyOut = '0';
            $QtySaldo = $qtyPlus;
        } elseif ($status == 'C') {
            $QtyInWeight = '0';
            $QtyOutWeight = $Weight;
            $QtyWeight = $weightMin;
            $QtyIn = '0';
            $QtyOut = $Qty;
            $QtySaldo = $qtyMin;
        }

        $insertstok = dev_Stock::create([
            'EntryDate' => $tglfull,
            'UserName' => $UserName,
            'Location' => $Location,
            'Product' => $Product,
            'Carat' => $Carat,
            'TransDate' => $tgl,
            'Process' => $Process,
            'Cause' => $cause,
            'LinkSW' => $LinkSW,
            'LinkID' => $LinkID,
            'LinkOrd' => $LinkOrd,
            'workorder' => $workorder,
            'Value' => '0',
            'QtyInWeight' => $QtyInWeight,
            'QtyOutWeight' => $QtyOutWeight,
            'QtyWeight' => $QtyWeight,
            'QtyIn' => $QtyIn,
            'QtyOut' => $QtyOut,
            'QtySaldo' => $QtySaldo,
        ]);

        $wordCount = count($producttrans);
        if ($wordCount > 0) {
            foreach ($producttrans as $prodtrns) {
                $update_ptrns = dev_ProductTrans::find($prodtrns->ID);
                $update_ptrns->Qty = $QtySaldo;
                $update_ptrns->Weight = $QtyWeight;
                $update_ptrns->save();
            }
        } else {
            $update_ptrns = dev_ProductTrans::create([
                'Product' => $Product,
                'Carat' => $Carat,
                'Location' => $Location,
                'Qty' => $QtySaldo,
                'Weight' => $QtyWeight,
                'Value' => '0',
            ]);
        }

        if ($insertstok && $update_ptrns) {
            return ['success' => true, 'message' => 'Posting Berhasil!!'];
        } else{
            return ['success' => false, 'message' => 'Posting Gagal!!'];
        }

    }

    public function PostingNewERP($status, $UserName, $Location, $Product, $Carat, $Process, $cause, $LinkSW, $LinkID, $LinkOrd, $Qty, $Weight, $workorder)
    {
        //! validasi kelengkapan data
        $validator = Validator::make(
            [
                'status' => $status,
                'UserName' => $UserName,
                'Location' => $Location,
                'Product' => $Product,
                'Process' => $Process,
                'cause' => $cause,
                'LinkSW' => $LinkSW,
                'LinkID' => $LinkID,
                'LinkOrd' => $LinkOrd,
                'Qty' => $Qty,
                'Weight' => $Weight,
            ],
            [
                'status' => 'required',
                'UserName' => 'required',
                'Location' => 'required|numeric',
                'Product' => 'required|numeric',
                'Process' => 'required',
                'cause' => 'required',
                'LinkSW' => 'required|numeric',
                'LinkID' => 'required|numeric',
                'LinkOrd' => 'required|numeric',
                'Qty' => 'required|numeric',
                'Weight' => 'required|numeric',
            ],
        );

        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()];
        }

        $tgl = date('Y-m-d');
        $tglfull = date('Y-m-d h:i:s');
        $producttrans = FacadesDB::connection('erp')->select(" SELECT Qty QtySaldo, Weight QtyWeight, ID From ProductTrans Where (Product = '$Product') And (Location = '$Location') And(IfNull(Carat, '') = '$Carat') LIMIT 1 ");

        foreach ($producttrans as $prodtrns) {
        }

        $weightPlus = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) + (isset($Weight) ? $Weight : 0);
        $qtyPlus = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) + (isset($Qty) ? $Qty : 0);
        $weightMin = (isset($prodtrns->QtyWeight) ? $prodtrns->QtyWeight : 0) - (isset($Weight) ? $Weight : 0);
        $qtyMin = (isset($prodtrns->QtySaldo) ? $prodtrns->QtySaldo : 0) - (isset($Qty) ? $Qty : 0);

        if ($status == 'D') {
            $QtyInWeight = $Weight;
            $QtyOutWeight = '0';
            $QtyWeight = $weightPlus;
            $QtyIn = $Qty;
            $QtyOut = '0';
            $QtySaldo = $qtyPlus;
        } elseif ($status == 'C') {
            $QtyInWeight = '0';
            $QtyOutWeight = $Weight;
            $QtyWeight = $weightMin;
            $QtyIn = '0';
            $QtyOut = $Qty;
            $QtySaldo = $qtyMin;
        }

        $insertstok = erp_Stock::create([
            'EntryDate' => $tglfull,
            'UserName' => $UserName,
            'Location' => $Location,
            'Product' => $Product,
            'Carat' => $Carat,
            'TransDate' => $tgl,
            'Process' => $Process,
            'Cause' => $cause,
            'LinkSW' => $LinkSW,
            'LinkID' => $LinkID,
            'LinkOrd' => $LinkOrd,
            'workorder' => $workorder,
            'Value' => '0',
            'QtyInWeight' => $QtyInWeight,
            'QtyOutWeight' => $QtyOutWeight,
            'QtyWeight' => $QtyWeight,
            'QtyIn' => $QtyIn,
            'QtyOut' => $QtyOut,
            'QtySaldo' => $QtySaldo,
        ]);

        $wordCount = count($producttrans);
        if ($wordCount > 0) {
            foreach ($producttrans as $prodtrns) {
                $update_ptrns = erp_ProductTrans::find($prodtrns->ID);
                $update_ptrns->Qty = $QtySaldo;
                $update_ptrns->Weight = $QtyWeight;
                $update_ptrns->save();
            }
        } else {
            $update_ptrns = erp_ProductTrans::create([
                'Product' => $Product,
                'Carat' => $Carat,
                'Location' => $Location,
                'Qty' => $QtySaldo,
                'Weight' => $QtyWeight,
                'Value' => '0',
            ]);
        }

        if ($insertstok && $update_ptrns) {
            return ['success' => true, 'message' => 'Posting Berhasil!!'];
        } else {
            return ['success' => false, 'message' => 'Posting Gagal!!'];
        }
    }

    public function PostingTMDEV($id, $UserName)
    {
        $validator = Validator::make(
            [
                'id' => $id,
                'UserName' => $UserName,
            ],
            [
                'id' => 'required|numeric',
                'UserName' => 'required',
            ],
        );

        if ($validator->fails()) {
            $failed = $validator->failed();
            // dd($failed);
            return ['validasi' => $failed, 'insertstok' => false, 'update_ptrns' => false];
        }

        $tgl = date('Y-m-d');
        $tglfull = date('Y-m-d h:i:s');
        $weightmin = 0;
        $qtymin = 0;
        $weight = 0;
        $qty = 0;

        $gettransferrm = FacadesDB::connection('dev')->select("SELECT * FROM transferrm WHERE id = '$id' ");

        $TransDate = $gettransferrm[0]->TransDate;
        $ToLoc = $gettransferrm[0]->ToLoc;
        $FromLoc = $gettransferrm[0]->FromLoc;

        FacadesDB::connection('dev')->beginTransaction();
        try {
            $gettransferrmitem = FacadesDB::connection('dev')->select(" SELECT * FROM transferrmitem WHERE IDM = '$id' ORDER BY Ordinal ASC ");
            foreach ($gettransferrmitem as $data) {
                // STOK KELUAR
                $getProductTrans = FacadesDB::connection('dev')->select("SELECT ID,Qty QtySaldo,Weight QtyWeight FROM ProductTrans WHERE (Product='$data->Product') AND (Location='$FromLoc') AND (IfNull(Carat,'')='$data->Carat') ");

                $weightmin = (isset($getProductTrans[0]->QtyWeight) ? $getProductTrans[0]->QtyWeight : 0) - (isset($data->Weight) ? $data->Weight : 0);
                $qtymin = (isset($getProductTrans[0]->QtySaldo) ? $getProductTrans[0]->QtySaldo : 0) - (isset($data->Qty) ? $data->Qty : 0);

                // - insert stock
                $insertstokout[] = dev_Stock::create([
                    'EntryDate' => $tglfull,
                    'UserName' => $UserName,
                    'Location' => $FromLoc,
                    'Product' => $data->Product,
                    'Carat' => $data->Carat,
                    'TransDate' => $tgl,
                    'Process' => 'Production',
                    'Cause' => 'Transfer Material',
                    'LinkSW' => $data->IDM,
                    'LinkID' => $data->IDM,
                    'LinkOrd' => $data->Ordinal,
                    'WorkOrder' => $data->WorkOrder,
                    'Value' => '0',
                    'QtyInWeight' => '0',
                    'QtyOutWeight' => $data->Weight,
                    'QtyWeight' => $weightmin,
                    'QtyIn' => '0',
                    'QtyOut' => $data->Qty,
                    'QtySaldo' => $qtymin,
                ]);

                // - update and insert ProductTrans
                $wordCount = count($getProductTrans);
                if ($wordCount > 0) {
                    foreach ($getProductTrans as $prodtrns) {
                        $update_ptrns = dev_ProductTrans::find($prodtrns->ID);
                        $update_ptrns->Qty = $qtymin;
                        $update_ptrns->Weight = $weightmin;
                        $update_ptrns->save();
                        $uptrns[] = $update_ptrns;
                    }
                } else {
                    $update_ptrns = dev_ProductTrans::create([
                        'Product' => $data->Product,
                        'Carat' => $data->Carat,
                        'Location' => $FromLoc,
                        'Qty' => $qtymin,
                        'Weight' => $weightmin,
                        'Value' => '0',
                    ]);
                    $uptrns[] = $update_ptrns;
                }

                // echo$getProductTrans[0]->QtyWeight." = QtyInWeight => 0 QtyOutWeight =>". $data->Weight ."QtyWeight =>". $weightmin. ",";

                // START STOK MASUK
                $getProductTrans2 = FacadesDB::connection('dev')->select("SELECT ID,Qty QtySaldo,Weight QtyWeight FROM ProductTrans WHERE (Product='$data->Product') AND (Location='$ToLoc') AND (IfNull(Carat,'')='$data->Carat') ");

                $weight = (isset($getProductTrans2[0]->QtyWeight) ? $getProductTrans2[0]->QtyWeight : 0) + (isset($data->Weight) ? $data->Weight : 0);
                $qty = (isset($getProductTrans2[0]->QtySaldo) ? $getProductTrans2[0]->QtySaldo : 0) + (isset($data->Qty) ? $data->Qty : 0);

                // - insert stock
                $insertstokin[] = dev_Stock::create([
                    'EntryDate' => $tglfull,
                    'UserName' => $UserName,
                    'Location' => $ToLoc,
                    'Product' => $data->Product,
                    'Carat' => $data->Carat,
                    'TransDate' => $tgl,
                    'Process' => 'Production',
                    'Cause' => 'Transfer Material',
                    'LinkSW' => $data->IDM,
                    'LinkID' => $data->IDM,
                    'LinkOrd' => $data->Ordinal,
                    'WorkOrder' => $data->WorkOrder,
                    'Value' => '0',
                    'QtyInWeight' => $data->Weight,
                    'QtyOutWeight' => '0',
                    'QtyWeight' => $weight,
                    'QtyIn' => $data->Qty,
                    'QtyOut' => '0',
                    'QtySaldo' => $qty,
                ]);

                // - update and insert ProductTrans
                $wordCount2 = count($getProductTrans2);
                if ($wordCount2 > 0) {
                    foreach ($getProductTrans2 as $prodtrns) {
                        $update_ptrns = dev_ProductTrans::find($prodtrns->ID);
                        $update_ptrns->Qty = $qty;
                        $update_ptrns->Weight = $weight;
                        $update_ptrns->save();
                        $uptrns[] = $update_ptrns;
                    }
                } else {
                    $update_ptrns = dev_ProductTrans::create([
                        'Product' => $data->Product,
                        'Carat' => $data->Carat,
                        'Location' => $ToLoc,
                        'Qty' => $qty,
                        'Weight' => $weight,
                        'Value' => '0',
                    ]);
                    $uptrns[] = $update_ptrns;
                }
                // echo $getProductTrans2[0]->QtyWeight . " = QtyInWeight => $data->Weight QtyOutWeight => 0 QtyWeight =>" . $weight . ",";
            }

            $update_statusaktif = dev_transferrm::find($id);
            $update_statusaktif->Active = 'P';
            $update_statusaktif->PostDate = now();
            $update_statusaktif->Remarks = 'Posting Laravel';
            $update_statusaktif->save();

            FacadesDB::connection('dev')->commit();
        } catch (Exception $e) {
            FacadesDB::connection('dev')->rollBack();
            return ['validasi' => false];
        }

        // dd($insertstokout, $insertstokin, $uptrns, $update_statusaktif);
        if ($insertstokout && $insertstokin) {
            $insertstok = count($insertstokout) + count($insertstokin);
        } else {
            $insertstok = false;
        }
        if ($uptrns) {
            $uptrns = count($uptrns);
        } else {
            $uptrns = false;
        }

        return ['validasi' => true, 'insertstok' => $insertstok, 'update_ptrns' => $uptrns];
    }

    public function PostingTMERP($id, $UserName)
    {
        $validator = Validator::make(
            [
                'id' => $id,
                'UserName' => $UserName,
            ],
            [
                'id' => 'required|numeric',
                'UserName' => 'required',
            ],
        );

        if ($validator->fails()) {
            $failed = $validator->failed();
            // dd($failed);
            return ['validasi' => $failed, 'insertstok' => false, 'update_ptrns' => false];
        }

        $tgl = date('Y-m-d');
        $tglfull = date('Y-m-d h:i:s');
        $weightmin = 0;
        $qtymin = 0;
        $weight = 0;
        $qty = 0;

        $gettransferrm = FacadesDB::connection('erp')->select("SELECT * FROM transferrm WHERE id = '$id' ");

        $ToLoc = $gettransferrm[0]->ToLoc;
        $FromLoc = $gettransferrm[0]->FromLoc;

        FacadesDB::connection('erp')->beginTransaction();
        try {
            $gettransferrmitem = FacadesDB::connection('erp')->select(" SELECT * FROM transferrmitem WHERE IDM = '$id' ORDER BY Ordinal ASC ");
            foreach ($gettransferrmitem as $data) {
                // STOK KELUAR
                $getProductTrans = FacadesDB::connection('erp')->select("SELECT ID,Qty QtySaldo,Weight QtyWeight FROM ProductTrans WHERE (Product='$data->Product') AND (Location='$FromLoc') AND (IfNull(Carat,'')='$data->Carat') ");

                $weightmin = (isset($getProductTrans[0]->QtyWeight) ? $getProductTrans[0]->QtyWeight : 0) - (isset($data->Weight) ? $data->Weight : 0);
                $qtymin = (isset($getProductTrans[0]->QtySaldo) ? $getProductTrans[0]->QtySaldo : 0) - (isset($data->Qty) ? $data->Qty : 0);

                // - insert stock
                $insertstokout[] = erp_Stock::create([
                    'EntryDate' => $tglfull,
                    'UserName' => $UserName,
                    'Location' => $FromLoc,
                    'Product' => $data->Product,
                    'Carat' => $data->Carat,
                    'TransDate' => $tgl,
                    'Process' => 'Production',
                    'Cause' => 'Transfer Material',
                    'LinkSW' => $data->IDM,
                    'LinkID' => $data->IDM,
                    'LinkOrd' => $data->Ordinal,
                    'WorkOrder' => $data->WorkOrder,
                    'Value' => '0',
                    'QtyInWeight' => '0',
                    'QtyOutWeight' => $data->Weight,
                    'QtyWeight' => $weightmin,
                    'QtyIn' => '0',
                    'QtyOut' => $data->Qty,
                    'QtySaldo' => $qtymin,
                ]);

                // - update and insert ProductTrans
                $wordCount = count($getProductTrans);
                if ($wordCount > 0) {
                    foreach ($getProductTrans as $prodtrns) {
                        $update_ptrns = erp_ProductTrans::find($prodtrns->ID);
                        $update_ptrns->Qty = $qtymin;
                        $update_ptrns->Weight = $weightmin;
                        $update_ptrns->save();
                        $uptrns[] = $update_ptrns;
                    }
                } else {
                    $update_ptrns = erp_ProductTrans::create([
                        'Product' => $data->Product,
                        'Carat' => $data->Carat,
                        'Location' => $FromLoc,
                        'Qty' => $qtymin,
                        'Weight' => $weightmin,
                        'Value' => '0',
                    ]);
                    $uptrns[] = $update_ptrns;
                }

                // START STOK MASUK
                $getProductTrans2 = FacadesDB::connection('erp')->select("SELECT ID,Qty QtySaldo,Weight QtyWeight FROM ProductTrans WHERE (Product='$data->Product') AND (Location='$ToLoc') AND (IfNull(Carat,'')='$data->Carat') ");

                $weight = (isset($getProductTrans2[0]->QtyWeight) ? $getProductTrans2[0]->QtyWeight : 0) + (isset($data->Weight) ? $data->Weight : 0);
                $qty = (isset($getProductTrans2[0]->QtySaldo) ? $getProductTrans2[0]->QtySaldo : 0) + (isset($data->Qty) ? $data->Qty : 0);

                // - insert stock
                $insertstokin[] = erp_Stock::create([
                    'EntryDate' => $tglfull,
                    'UserName' => $UserName,
                    'Location' => $ToLoc,
                    'Product' => $data->Product,
                    'Carat' => $data->Carat,
                    'TransDate' => $tgl,
                    'Process' => 'Production',
                    'Cause' => 'Transfer Material',
                    'LinkSW' => $data->IDM,
                    'LinkID' => $data->IDM,
                    'LinkOrd' => $data->Ordinal,
                    'WorkOrder' => $data->WorkOrder,
                    'Value' => '0',
                    'QtyInWeight' => $data->Weight,
                    'QtyOutWeight' => '0',
                    'QtyWeight' => $weight,
                    'QtyIn' => $data->Qty,
                    'QtyOut' => '0',
                    'QtySaldo' => $qty,
                ]);

                // - update and insert ProductTrans
                $wordCount2 = count($getProductTrans2);
                if ($wordCount2 > 0) {
                    foreach ($getProductTrans2 as $prodtrns) {
                        $update_ptrns = erp_ProductTrans::find($prodtrns->ID);
                        $update_ptrns->Qty = $qty;
                        $update_ptrns->Weight = $weight;
                        $update_ptrns->save();
                        $uptrns[] = $update_ptrns;
                    }
                } else {
                    $update_ptrns = erp_ProductTrans::create([
                        'Product' => $data->Product,
                        'Carat' => $data->Carat,
                        'Location' => $ToLoc,
                        'Qty' => $qty,
                        'Weight' => $weight,
                        'Value' => '0',
                    ]);
                    $uptrns[] = $update_ptrns;
                }
            }

            $update_statusaktif = erp_transferrm::find($id);
            $update_statusaktif->Active = 'P';
            $update_statusaktif->PostDate = now();
            $update_statusaktif->Remarks = 'Posting Laravel';
            $update_statusaktif->save();

            FacadesDB::connection('erp')->commit();
        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return ['validasi' => false];
        }

        // dd($insertstokout, $insertstokin, $uptrns, $update_statusaktif);

        if ($insertstokout && $insertstokin) {
            $insertstok = count($insertstokout) + count($insertstokin);
        } else {
            $insertstok = false;
        }
        if ($uptrns) {
            $uptrns = count($uptrns);
        } else {
            $uptrns = false;
        }

        return ['validasi' => true, 'insertstok' => $insertstok, 'update_ptrns' => $uptrns];
    }

    public function SetStatustransactionERP($tabel, $id)
    {
        $updatestatus = FacadesDB::connection('erp')
            ->table($tabel)
            ->where('ID', $id)
            ->update([
                'Active' => 'P',
                'PostDate' => now(),
                'Remarks' => 'Posting Laravel',
            ]);

        if ($updatestatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    public function SetStatustransactionDEV($tabel, $id)
    {
        $updatestatus = FacadesDB::connection('dev')
            ->table($tabel)
            ->where('ID', $id)
            ->update([
                'Active' => 'P',
                'PostDate' => now(),
                'Remarks' => 'Posting Laravel',
            ]);

        if ($updatestatus) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }

    public function GetLastIDDEV($ModuleName)
    {
        // dd('tess');
        $GetLastID = FacadesDB::connection('dev')->select("SELECT LAST+1 AS ID FROM lastid Where Module = '$ModuleName' ");
        foreach ($GetLastID as $GetLastIDs) {
        }

        if ($GetLastID) {
            $id = $GetLastIDs->ID;
        } else {
            $insertLastID = dev_lastid::create([
                'Module' => $ModuleName,
                'Last' => '1',
            ]);
            $id = '1';
        }
        // dd($GetLastID);
        return ['ID' => $id];
    }

    public function GetLastIDERP($ModuleName)
    {
        // dd('tess');
        $GetLastID = FacadesDB::connection('erp')->select("SELECT LAST+1 AS ID FROM lastid Where Module = '$ModuleName' ");
        foreach ($GetLastID as $GetLastIDs) {
        }

        if ($GetLastID) {
            $id = $GetLastIDs->ID;
        } else {
            $insertLastID = erp_lastid::create([
                'Module' => $ModuleName,
                'Last' => '1',
            ]);
            $id = '1';
        }
        // dd($GetLastID);
        return ['ID' => $id];
    }

    public function GetLastIDRND($ModuleName)
    {
        // dd('tess');
        $GetLastID = FacadesDB::select("SELECT LAST+1 AS ID FROM lastid Where Module = '$ModuleName' ");
        foreach ($GetLastID as $GetLastIDs) {
        }

        if ($GetLastID) {
            $id = $GetLastIDs->ID;
        } else {
            $insertLastID = rnd_lastid::create([
                'Module' => $ModuleName,
                'Last' => '1',
            ]);
            $id = '1';
        }
        // dd($GetLastID);
        return ['ID' => $id];
    }

    public function ListUserHistoryDEV($tablename, $UserID, $Module)
    {
        // dd($UserID);
        $ListUserHistory = FacadesDB::connection('dev')->select(
            " SELECT
            U.UserID UserID,
            U.Module Module,
            U.HistList HistList,
            U.Description Description,
            U.EntryDate EntryDate,
            W.ID SW
        FROM
            userhistoryweb U
            JOIN " .
                $tablename .
                " W ON U.HistList = W.ID
        WHERE
            U.UserID = '$UserID'
            AND U.Module = '$Module'
        ORDER BY
            U.EntryDate DESC ",
        );
        // dd($ListUserHistory);
        return $ListUserHistory;
    }

    public function ListUserHistoryERP($tablename, $UserID, $Module)
    {
        // dd($UserID);
        $ListUserHistory = FacadesDB::connection('erp')->select(
            " SELECT
            U.UserID UserID,
            U.Module Module,
            U.HistList HistList,
            U.Description Description,
            U.EntryDate EntryDate,
            W.ID SW
        FROM
            userhistoryweb U
            JOIN " .
                $tablename .
                " W ON U.HistList = W.ID
        WHERE
            U.UserID = '$UserID'
            AND U.Module = '$Module'
        ORDER BY
            U.EntryDate DESC ",
        );
        // dd($ListUserHistory);
        return $ListUserHistory;
    }

    public function UpdateUserHistoryDEV($UserID, $Module, $ID_Field)
    {
        // dd($UserID, $Module, $ID_Field);

        $insert_userhistorywe = dev_userhistoryweb::firstOrCreate(['UserID' => $UserID, 'Module' => $Module, 'HistList' => $ID_Field], ['Description' => 'Laravel']);

        $ambillast_userhistorywe = FacadesDB::connection('dev')->select("SELECT COUNT(*) as count FROM userhistoryweb where UserID= '$UserID' and Module ='$Module'");
        foreach ($ambillast_userhistorywe as $ambillast_userhistorywes) {
        }
        if ($ambillast_userhistorywes->count > 10) {
            $deleted = dev_userhistoryweb::where('UserID', $UserID)
                ->where('Module', $Module)
                ->orderBy('EntryDate', 'asc')
                ->limit(1)
                ->delete();
        }
        // dd($deleted);
        return [$insert_userhistorywe];
    }

    public function UpdateUserHistoryERP($UserID, $Module, $ID_Field)
    {
        // dd($UserID, $Module, $ID_Field);

        $insert_userhistorywe = erp_userhistoryweb::firstOrCreate(['UserID' => $UserID, 'Module' => $Module, 'HistList' => $ID_Field], ['Description' => 'Laravel']);

        $ambillast_userhistorywe = FacadesDB::connection('erp')->select("SELECT COUNT(*) as count FROM userhistoryweb where UserID= '$UserID' and Module ='$Module'");
        foreach ($ambillast_userhistorywe as $ambillast_userhistorywes) {
        }
        if ($ambillast_userhistorywes->count > 10) {
            $deleted = erp_userhistoryweb::where('UserID', $UserID)
                ->where('Module', $Module)
                ->orderBy('EntryDate', 'asc')
                ->limit(1)
                ->delete();
        }
        // dd($deleted);
        return [$insert_userhistorywe];
    }

    public function ViewSelection(Request $request)
    {
        $TabName = $request->tb;
        $id = $request->id;
        $all = $request->all;

        // dd($Groups);

        $query1 = FacadesDB::connection('erp')->select("SELECT ID, SW, Description, TabName, CONCAT(ID,' - ',SW,' - ',Description) tampil1 FROM `setselectionm` WHERE TabName = '$TabName'");
        $query2 = FacadesDB::connection('erp')->select("SELECT  FieldName, Description, DefValue, DefOperator, FieldRef, DefUse  FROM `setselectiond` WHERE IDM = '$id' ORDER BY Ordinal");
        if ($id) {
            $select = '';
            $join = '';
            $where = '';
            $where2 = '';
            $i = 0;
            $ii = 0;
            foreach ($query1 as $query) {
                $tabel = $query->TabName;
                break;
            }

            foreach ($query2 as $query) {
                $i++;
                if ($i != '1') {
                    $select .= ', ';
                }
                if ($query->DefUse == '1') {
                    $ii++;
                    if ($ii == '1') {
                        $where .= "WHERE T.$query->FieldName = '$query->DefValue' ";
                    } else {
                        $where .= "AND T.$query->FieldName = '$query->DefValue'";
                    }
                }
                if ($query->FieldRef != '') {
                    $select .= $query->FieldRef . '.Description ' . $query->FieldName;
                    $join .= ' LEFT JOIN ' . $query->FieldRef . ' ON T.' . $query->FieldName . ' = ' . $query->FieldRef . '.ID ';
                } else {
                    $select .= 'T.' . $query->FieldName;
                }
                $cek1 = 'N_' . $query->FieldName;
                $cek2 = 'T_' . $query->FieldName;
                if ($request->$cek1 != null) {
                    if ($request->$cek2 == '') {
                        $request->$cek2 = '=';
                    }

                    if ($request->$cek2 == 'Like') {
                        $data = "'%" . $request->$cek1 . "%'";
                    } elseif ($request->$cek2 == 'In' || $request->$cek2 == 'Not In') {
                        $data = "('" . str_replace(',', "','", $request->$cek1) . "')";
                    } else {
                        $data = "'" . $request->$cek1 . "'";
                    }

                    $where2 .= ' AND T.' . $query->FieldName . ' ' . $request->$cek2 . ' ' . $data . ' ';
                }
            }
            // dd($where2);

            if ($all == '1') {
                $limit = ' LIMIT 1000 ';
            } else {
                $limit = ' LIMIT 20 ';
            }

            // dd($select, $join, $tabel, $where, $all, $join2);

            $query3 = FacadesDB::connection('erp')->select(" SELECT
            $select
        FROM
            $tabel AS T
            $join
            $where
            $where2
        ORDER BY
            T.ID DESC
            $limit ");

            // dd($query3);
        } else {
            $query3 = [0, 0];
            return view('setting.publick_function.ViewSelection', compact('query1', 'id', 'TabName'));
        }
        return view('setting.publick_function.ViewSelection', compact('query1', 'query2', 'query3', 'id', 'TabName'));
    }
}

<?php

namespace App\Http\Controllers\Inventori\Transfer;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Public_Function_Controller;

// models
use App\Models\erp\lastid;
use App\Models\erp\materialrequest;
use App\Models\erp\materialrequestitem;
use App\Models\erp\otherusage;
use App\Models\erp\otherusageitem;

use App\Models\erp\stockother;
use App\Models\erp\productpurchasetrans;


class BahanPembantuController extends Controller
{

    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }


    public function Index()
    {

        //menyimpan List User History
        $tablename = 'OtherUsage';
        $UserID = session('iduser');
        $UserEntry = session('UserEntry');
        $Module = '194';
        $ListUserHistory = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);

        //Buat Sw List
        $names = array('Niko', 'Aditya', 'EndangS', 'Ika M', 'kharies', 'Ahmad H');
        if (in_array($UserEntry, $names)) {
            // SW Yang Bisa Membuat Transfer Bahan Pembantu
            $Akses = 1; 
        } else {
            // SW Yang Bisa Meng Posting
            $Akses = 2;
        }

        // dd($ListUserHistory);
        return view('Inventori.Transfer.BahanPembantu.index', compact('ListUserHistory', 'Akses'));
    }

    public function show(Request $request)
    {

        //lihta
        if ($request->no == 1) {
            $lihat = 1;

            $headers = FacadesDB::connection('erp')->select("SELECT
                    U.*,
                    D.Description,
                    L.Description LDescription 
                FROM
                    otherusage U
                    JOIN Location L ON U.Location = L.ID
                    LEFT JOIN Department D ON U.Department = D.ID 
                WHERE
                    U.ID = '$request->id';
            ");

            foreach ($headers as $key => $header) {
            }

            $item = FacadesDB::connection('erp')->select("SELECT
                    S.*,
                    M.TransDate,
                    P.Description PDescription,
                    O.Description ODescription,
                    U.SW Unit 
                FROM
                    OtherUsageItem S
                    JOIN MaterialRequest M ON S.LinkID = M.ID
                    JOIN ProductPurchase P ON S.Product = P.ID
                    JOIN OperationUsage O ON S.Operation = O.ID
                    LEFT JOIN Unit U ON P.Unit = U.ID 
                WHERE
                    S.IDM = '$request->id'
                ORDER BY
                    S.Ordinal
            ");

            // dd($header);

            $Department = FacadesDB::connection('erp')->select("SELECT ID, Description From Department Where Type = 'S' Order By Description");
            $Location = FacadesDB::connection('erp')->select("SELECT ID, Description From Location Where HigherRank = 63 Order By Description");

            $UserEntry = session('UserEntry');
            if($UserEntry == 'Niko' || $UserEntry == 'Ahmad H'){
                $kunci = 0;
            } 
            else {
                $kunci = 1;
            }

            // dd($item,$header,$Department,$Location,$lihat);

            // dd($Department, $Location);
            return view('Inventori.Transfer.BahanPembantu.show', compact('item', 'header', 'Department', 'Location', 'lihat','kunci'));
        }

        //tambah
        if ($request->no == 2) {

            // dd($request);
            $lihat = 0;
            $tgl = date('Y-m-d');

            $iddept = session('iddept');
            $UserEntry = session('UserEntry');
            $userdata = FacadesDB::connection('erp')->select("SELECT
                B.ID depid,
                B.Responsibility depres,
                B.Description bgn 
            FROM
                Department B
            WHERE
                B.ID = '$iddept';
            ");

            // $userdata[0]->depid = 48;
            // $userdata[0]->depres = 48;

            if ($userdata[0]->depid == 13 || $userdata[0]->depres == 13) {
                $gudang = '67';
            } else if ($userdata[0]->depid == 48 || $userdata[0]->depres == 48) {
                $gudang = '64';
            } else if ($userdata[0]->depid == 35 || $userdata[0]->depres == 35) {
                $gudang = '65';
            } else if ($userdata[0]->depid == 10 || $userdata[0]->depres == 10) {
                $gudang = '68';
            } else if ($userdata[0]->depid == 43 || $userdata[0]->depres == 43) {
                $gudang = '69';
            } else if ($userdata[0]->depid == 64 || $userdata[0]->depres == 64) {
                $gudang = '66';
            }

            $header = (object)[
                'ID' => '',
                'Remarks' => '',
                'TransDate' => $tgl,
                'Reason' => '',
                'Department' => '',
                'Location' => $gudang,
                'Active' => '',
            ];

            $Department = FacadesDB::connection('erp')->select("SELECT ID, Description From Department Where Type = 'S' Order By Description");
            $Location = FacadesDB::connection('erp')->select("SELECT ID, Description From Location Where HigherRank = 63 Order By Description");

            if($UserEntry == 'Niko' || $UserEntry == 'Ahmad H'){
                $kunci = 0;
            } 
            else {
                $kunci = 1;
            }

            // dd($UserEntry, $kunci, $request->session()->all());
            return view('Inventori.Transfer.BahanPembantu.show', compact('header', 'Department', 'Location', 'lihat','kunci'));
        }

        //get_form
        if ($request->no == 3) {
            $DId = $request->idm ;
            $LId = $request->ord ;

            $item = FacadesDB::connection('erp')->select("SELECT
                        R.ID,
                        R.TransDate,
                        I.Ordinal,
                        P.Description Product,
                        I.Product IDP,
                        I.Qty,
                        E.Description Employee,
                        D.Description Department,
                        O.Description Operation,
                        P.ID PID,
                        E.ID EID,
                        D.ID DID,
                        O.ID OID,
                        U.SW Unit,
                        I.UNIT IDU,
                        I.Department IDD,
                        'N' Pick 
                    FROM
                        MaterialRequest R
                        JOIN MaterialRequestItem I ON R.ID = I.IDM
                        JOIN Department D ON R.Department = D.ID 
                        AND D.ID = '$DId'
                        JOIN Employee E ON R.Employee = E.ID
                        JOIN ProductPurchase P ON I.Product = P.ID 
                        AND IfNull( P.Location, 64 ) = '$LId'
                        JOIN OperationUsage O ON I.Department = O.ID
                        LEFT JOIN Unit U ON I.Unit = U.ID 
                    WHERE
                        R.Active IN ( 'A', 'P' ) 
                        AND R.TransDate > '2019-08-01' 
                        AND I.Department <> '0'
                        AND ( R.ID, I.Ordinal ) NOT IN (
                        SELECT
                            I.LinkID,
                            I.LinkOrd 
                        FROM
                            OtherUsage O
                            JOIN OtherUsageItem I ON O.ID = I.IDM 
                        WHERE
                            O.Active <> 'C' 
                            AND O.Department = '$DId'
                        ) 
                    ORDER BY
                        R.ID,
                        I.Ordinal
                ");
            // dd($item);
            return view('Inventori.Transfer.BahanPembantu.get_form', compact('item'));
        }

        dd($request);
    }

    public function store(Request $request){
        // dd($request);

        $iduser = session('iduser');
        $iddept = session('iddept');

        //dapatkan last id
        $GetLastID = $this->Public_Function->GetLastIDERP('OtherUsage');
        $LastID = $GetLastID['ID'];

        FacadesDB::connection('erp')->beginTransaction();
        try {

            //update last id
            $update_lastid = lastid::where('Module', 'OtherUsage')->update([
                'Last' => $LastID,
            ]);

            // masukan ke user history
            $Module = '194';
            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($iduser, $Module, $LastID);

            // Insert to OtherUsage
            $insertOtherUsage = OtherUsage::create([
                'ID' => $LastID,
                'UserName' => Auth::user()->name,
                'Remarks' => $request->catatan,
                'TransDate' => $request->tgl_masuk,
                'Reason' => $request->tipe,
                'Department' => $request->bagian,
                'Active' => 'A',
                'Location' => $request->gudang,
            ]);

            $no = 0;
            foreach ($request->pilih  as $key => $value) {
                $no++;
                // Insert to OtherUsageItem
                $insertOtherUsageItem = OtherUsageItem::create([
                    'IDM' => $LastID,
                    'Ordinal' => $no,
                    'Product' => $request->barangid[$key],
                    'Qty' => $request->jumlah[$key],
                    'Employee' => $request->karid[$key],
                    'Department' => $request->bagian,
                    'Operation' => $request->id_proses[$key],
                    'Note' => $request->keterangan[$key],
                    'LinkID' => $request->idm[$key],
                    'LinkOrd' => $request->ord[$key],
                ]);
            }

            FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'id' => $LastID,
                    'message' => 'Simpan Berhasil !!!',
                ],
                200,
            );

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'message' => 'Gagal',
                ],
                500,
            );
        }

        dd($request);
    }

    public function update(Request $request)
    {
        // dd($request);

        $iduser = session('iduser');
        $iddept = session('iddept');

        FacadesDB::connection('erp')->beginTransaction();
        try {

            // masukan ke user history
            $Module = '194';
            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($iduser, $Module, $request->id);

            //update to OtherUsage
            $update_OtherUsage = OtherUsage::where('ID', $request->id)->update([
                'UserName' => Auth::user()->name,
                'Remarks' => $request->catatan,
                'TransDate' => $request->tgl_masuk,
                'Reason' => $request->tipe,
                'Department' => $request->bagian,
                'Active' => 'A',
                'Location' => $request->gudang,
            ]);

            // hapus OtherUsageItem
            $deleteOtherUsageItem = OtherUsageItem::where('IDM', $request->id)->delete();

            $no = 0;
            foreach ($request->pilih  as $key => $value) {
                $no++;
                // Insert to OtherUsageItem
                $insertOtherUsageItem = OtherUsageItem::create([
                    'IDM' => $request->id,
                    'Ordinal' => $no,
                    'Product' => $request->barangid[$key],
                    'Qty' => $request->jumlah[$key],
                    'Employee' => $request->karid[$key],
                    'Department' => $request->bagian,
                    'Operation' => $request->id_proses[$key],
                    'Note' => $request->keterangan[$key],
                    'LinkID' => $request->idm[$key],
                    'LinkOrd' => $request->ord[$key],
                ]);
            }
            
            FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'id' => $request->id,
                    'message' => 'Simpan Berhasil !!!',
                ],
                200,
            );

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'message' => 'Gagal',
                ],
                500,
            );
        }
    }

    public function Posting(Request $request)
    {
        // dd($request);

        FacadesDB::connection('erp')->beginTransaction();
        try {

            $getOtherUsageItem = FacadesDB::connection('erp')->select("SELECT Distinct LinkID From OtherUsageItem Where IDM = '$request->id' ");
            foreach ($getOtherUsageItem as $key => $value) {
                //update to MaterialRequest

                $update_MaterialRequest = MaterialRequest::where('ID', $value->LinkID)->where('Active', '!=', 'P')
                ->update([
                    'Active' => 'P',
                    'PostDate' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($request->InOut == 'Keluar'){
                $getOtherUsageItem2 = FacadesDB::connection('erp')->select("SELECT IDM,Ordinal,Product,Qty,Employee,Department,Operation,Note FROM otherusageitem WHERE IDM = '$request->id' ");

                foreach ($getOtherUsageItem2 as $key => $value) {
                    //get productpurchasetrans
                    $getLastStocks = FacadesDB::connection('erp')->select("SELECT Qty-'$value->Qty' AS LastStok FROM productpurchasetrans WHERE Product='$value->Product' AND Location='$request->gudang'; ");
                    
                    //cek apa produk ada di productpurchasetrans
                    $wordCount = count($getLastStocks);
                    if ($wordCount > 0) {
                        $stockTerakhir = $getLastStocks[0]->LastStok;
                    }
                    else{
                        $insert_productpurchasetrans = productpurchasetrans::create([
                            'Product' => $value->Product,
                            'Location' => $request->gudang,
                            'Qty' => 0 - $value->Qty,
                        ]);
                        $stockTerakhir = 0 - $value->Qty;
                    }

                    //insert to stockother
                    $insertstockother = stockother::create([
                        'UserName' => Auth::user()->name,
                        'TransDate' => date('Y-m-d H:i:s'),
                        'Process' => 'Inventory',
                        'Cause' => 'Usage',
                        'Product' => $value->Product,
                        'QtyIn' => '0',
                        'QtyOut' => $value->Qty,
                        'QtySaldo' => $stockTerakhir,
                        'Value' => '0',
                        'AvgValue' => '0',
                        'LinkID' => $value->IDM,
                        'LinkOrd' => $value->Ordinal,
                        'Location' => $request->gudang,
                    ]);

                    //update to productpurchasetrans
                    $update_productpurchasetrans = productpurchasetrans::where('Product', $value->Product)->where('Location', $request->gudang)
                    ->update([
                        'Qty' => $stockTerakhir,
                    ]);  
                }

                $UpdateOtherUsageItem = FacadesDB::connection('erp')->statement(" UPDATE MaterialRequestItem A
                    INNER JOIN (
                        SELECT I.LinkID, I.LinkOrd, SUM(I.Qty) AS Qty
                        FROM OtherUsage O
                        JOIN OtherUsageItem I ON O.ID = I.IDM AND I.LinkID IS NOT NULL AND I.LinkOrd IS NOT NULL
                        JOIN (
                            SELECT DISTINCT LinkID, LinkOrd
                            FROM OtherUsageItem
                            WHERE IDM = '$request->id'
                        ) Z ON I.LinkID = Z.LinkID AND I.LinkOrd = Z.LinkOrd
                        WHERE O.Active = 'P'
                        GROUP BY I.LinkID, I.LinkOrd
                    ) B ON A.IDM = B.LinkID AND A.Ordinal = B.LinkOrd
                    SET A.QtyBuy = A.Qty - B.Qty
                ");

                $update_OtherUsage = OtherUsage::where('ID', $request->id)
                ->update([
                    'Active' => 'P',
                    'PostDate' => now(),
                ]);

                $InsertProductPurchaseDepartment = FacadesDB::connection('erp')->statement(" INSERT INTO ProductPurchaseDepartment (ID, Department)
                        SELECT DISTINCT Product, Operation
                        FROM OtherUsageItem
                        WHERE Product IS NOT NULL
                        AND Operation <> 0
                        AND IDM = '$request->id'
                        AND (Product, Operation) NOT IN (
                            SELECT Product, Department
                            FROM ProductPurchaseDepartment
                        )
                    ");          
            }

            else if ($request->InOut == 'Terima'){

                $getOtherUsageItem2 = FacadesDB::connection('erp')->select("SELECT IDM,Ordinal,Product,Qty,Employee,Department,Operation,Note FROM otherusageitem WHERE IDM = '$request->id' ");
                foreach ($getOtherUsageItem2 as $key => $value) {
                    $getLastStocks = FacadesDB::connection('erp')->select("SELECT Qty+'$value->Qty' AS LastStok FROM productpurchasetrans WHERE Product='$value->Product' AND Location='$request->gudang'; ");
                    
                    //cek apa produk ada di productpurchasetrans
                    $wordCount = count($getLastStocks);
                    if ($wordCount > 0) {
                        $stockTerakhir = $getLastStocks[0]->LastStok;
                    }
                    else{
                        $insert_productpurchasetrans = productpurchasetrans::create([
                            'Product' => $value->Product,
                            'Location' => $request->gudang,
                            'Qty' => 0 + $value->Qty,
                        ]);
                        $stockTerakhir = 0 + $value->Qty;
                    }

                    //insert to stockother
                    $insertstockother = stockother::create([
                        'UserName' => Auth::user()->name,
                        'TransDate' => date('Y-m-d H:i:s'),
                        'Process' => 'Inventory',
                        'Cause' => 'Usage',
                        'Product' => $value->Product,
                        'QtyIn' => $value->Qty, 
                        'QtyOut' => '0',
                        'QtySaldo' => $stockTerakhir,
                        'Value' => '0',
                        'AvgValue' => '0',
                        'LinkID' => $value->IDM,
                        'LinkOrd' => $value->Ordinal,
                        'Location' => $request->gudang,
                    ]);

                    //update to productpurchasetrans
                    $update_productpurchasetrans = productpurchasetrans::where('Product', $value->Product)->where('Location', $request->gudang)
                    ->update([
                        'Qty' => $stockTerakhir,
                    ]);  

                }

                $update_OtherUsage = OtherUsage::where('ID', $request->id)
                ->update([
                    'Active' => 'P',
                    'PostDate' => now(),
                ]);

            }

        FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'id' => $request->id,
                    'message' => 'Simpan Berhasil !!!',
                ],
                200,
            );

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'message' => 'Gagal',
                ],
                500,
            );
        }

        // dd($request);

    }

    public function cetak(Request $request)
    {
        $id = $request->id;
        // dd($id, $request);
        $header = FacadesDB::connection('erp')->select("SELECT
                    U.*,
                    DATE_FORMAT( U.TransDate, '%d/%m/%y' ) AS PAPA,
                    D.Description,
                    L.Description LDescription 
                FROM
                    OtherUsage U
                    JOIN Location L ON U.Location = L.ID
                    LEFT JOIN Department D ON U.Department = D.ID 
                WHERE
                    U.ID ='$id'
        ");

        $body = FacadesDB::connection('erp')->select("SELECT
                        S.*,
                        P.Description PDescription,
                        O.Description ODescription,
                        U.SW Unit 
                    FROM
                        OtherUsageItem S
                        JOIN ProductPurchase P ON S.Product = P.ID
                        JOIN OperationUsage O ON S.Operation = O.ID
                        LEFT JOIN Unit U ON P.Unit = U.ID 
                    WHERE
                        S.IDM = '$id'
                    ORDER BY
                        S.Ordinal
            ");
        // dd($header, $body);
        return view('Inventori.Transfer.BahanPembantu.cetak', compact('id', 'header', 'body'));
    }
}

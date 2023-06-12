<?php

namespace App\Http\Controllers\Workshop;
// namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

use App\Models\erp\workshoporder;
use App\Models\erp\workshoporderitem;
use App\Models\erp\lastid;

use App\Http\Controllers\Public_Function_Controller;
use Exception;

class SPKWorkshopController extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index(Request $request)
    {
        $tablename = 'workshoporder';
        $UserID = $request->session()->get('iduser');
        $Module = 77;
        $hiscaris = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);

        // dd($hiscaris);
        return view('Workshop.SPKWorkshop.index', compact('hiscaris'));
    }

    public function Lihat(Request $request, $no, $id)
    {
        // dd($no, $id);

        //lihat
        if ($no == '1') {
            //update user history modul
            $iduser = $request->session()->get('iduser');
            $Module = 77;
            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($iduser, $Module, $id);

            $data1 = FacadesDB::connection('erp')->select("SELECT
                    A.*,
                    C.Description AS jabatan,
                    DATE_FORMAT( A.TransDate, '%d-%m-%Y' ) AS aku,
                    DATE_FORMAT( A.EntryDate, '%Y-%m-%d' ) AS inputtgl,
                    B.SW AS usercin,
                    B.Description AS namakar,
                    A.SW AS noor,
                    A.ID AS IDwk
                FROM
                    workshoporder AS A
                    LEFT JOIN employee AS B ON B.ID = A.Employee
                    LEFT JOIN Department AS C ON C.ID = B.Department
                WHERE
                    A.ID = '$id'");

            $WorkShopOrderItems = FacadesDB::connection('erp')->select("SELECT
                    I.*,
                    V.Description VDescription,
                    X.Description XDescription,
                    DATE_FORMAT( DateNeeded, '%Y-%m-%d' ) AS DateNeeded1,
                    I.Description AS IDescription
                FROM
                    WorkShopOrderItem I
                    LEFT JOIN Inventory V ON I.Inventory = V.ID
                    LEFT JOIN Department X ON V.Department = X.ID
                WHERE
                    I.IDM = '$id'
                ORDER BY
                    I.Ordinal");
            // dd($datas);
            return view('Workshop.SPKWorkshop.show', compact('data1', 'WorkShopOrderItems'));
        }

        //tambah
        if ($no == '2') {
            $username = Auth::user()->name;
            $data = FacadesDB::connection('erp')->select("SELECT
                    A.Description AS nama,
                    A.ID AS karid,
                    A.SW AS username,
                    B.ID AS idbgn,
                    B.Description AS jabatan
                FROM
                    employee AS A
                    LEFT JOIN Department AS B ON B.ID = A.department
                WHERE
                    A.SW = '$username'");

            return view('Workshop.SPKWorkshop.create', compact('no', 'data'));
        }

        //edit
        if ($no == '3') {
            $data1 = FacadesDB::connection('erp')->select("SELECT
                    A.*,
                    B.Description AS nama,
                    B.ID AS karid,
                    B.SW AS username,
                    C.Description AS jabatan,
                    DATE_FORMAT( A.TransDate, '%Y-%m-%d' ) AS aku,
                    DATE_FORMAT( A.EntryDate, '%Y-%m-%d' ) AS inputtgl,
                    B.SW AS usercin,
                    B.Description AS namakar,
                    A.SW AS noor,
                    A.ID AS IDwk,
                    C.ID AS idbgn
                FROM
                    workshoporder AS A
                    LEFT JOIN employee AS B ON B.ID = A.Employee
                    LEFT JOIN Department AS C ON C.ID = B.Department
                WHERE
                    A.ID = '$id'");

            $WorkShopOrderItems = FacadesDB::connection('erp')->select("SELECT
                    I.*,
                    V.Description VDescription,
                    X.Description XDescription,
                    DATE_FORMAT( DateNeeded, '%Y-%m-%d' ) AS DateNeeded1,
                    I.Description AS IDescription
                FROM
                    WorkShopOrderItem I
                    LEFT JOIN Inventory V ON I.Inventory = V.ID
                    LEFT JOIN Department X ON V.Department = X.ID
                WHERE
                    I.IDM = '$id'
                ORDER BY
                    I.Ordinal");

            return view('Workshop.SPKWorkshop.edit', compact('no', 'data1', 'WorkShopOrderItems'));
        }

        //produk
        if ($no == '4') {
            $ids = explode(',', $id);
            $id = $ids[0];
            $iddept = $ids[1];
            $produk = FacadesDB::connection('erp')->select("SELECT
                    V.ID AS ID,
                    V.Description AS Dsc,
                    D.Description Department
                FROM
                    Inventory V
                    JOIN Department D ON V.Department = D.ID
                    JOIN Department X ON X.Responsibility = D.Responsibility
                WHERE
                    ( V.STATUS = 'Dipakai' )
                    AND ( V.Active = 'Y' )
                    AND ( V.ID LIKE '$id' )
                    AND ( X.ID = '$iddept' )
                ORDER BY
                    D.Description,
                    V.Description");

            if ($produk) {
                return response()->json([
                    'success' => true,
                    'Dsc' => $produk[0]->Dsc,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }
        }

        //getkaryawan
        if ($no == '5') {
            if (ctype_digit($id)) {
                $hasil = 'WHERE A.ID = ' . $id;
            } else {
                $hasil = 'WHERE A.SW LIKE "' . $id . '"';
            }

            $getkary = FacadesDB::connection('erp')->select("SELECT
                    A.Description AS nama,
                    A.ID AS karid,
                    A.SW AS username,
                    B.ID AS idbgn,
                    B.Description AS jabatan
                FROM
                    employee AS A
                    LEFT JOIN Department AS B ON B.ID = A.department
                    $hasil
                ");

            // dd($getkary);

            if ($getkary) {
                return response()->json([
                    'success' => true,
                    'nama' => $getkary[0]->nama,
                    'karid' => $getkary[0]->karid,
                    'jabatan' => $getkary[0]->jabatan,
                    'idbgn' => $getkary[0]->idbgn,
                    'username' => $getkary[0]->username,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }
        }

        //getkaryawan
        if ($no == '6') {
            if ($id === 'Biasa') {
                $tgl = date('Y-m-d', strtotime('+7 days'));
            } elseif ($id === 'Penting') {
                $tgl = date('Y-m-d', strtotime('+3 days'));
            } elseif ($id === 'Darurat') {
                $tgl = date('Y-m-d', strtotime('+0 days'));
            }

            // dd($no, $id, $tgl);
            return response()->json([
                'success' => true,
                'tgl' => $tgl,
            ]);
        }
    }

    public function store(Request $request)
    {

        $username = Auth::user()->name;
        $iduser = $request->session()->get('iduser');
        $Module = 77;

        $getLastID = FacadesDB::connection('erp')->select("SELECT Last+1 as ID FROM lastid WHERE Module = 'WorkshopOrder' ");
        $ID = $getLastID[0]->ID;

        $getLastSW = FacadesDB::connection('erp')->select("SELECT Last+1 as ID FROM lastid WHERE Module = 'WorkShopOrderCode' ");
        $SW = $getLastSW[0]->ID;

        FacadesDB::connection('erp')->beginTransaction();

        try {
            $update_lastid_wo = lastid::where('Module', 'WorkshopOrder')->update(['Last' => $ID]);
            $update_lastid_woc = lastid::where('Module', 'WorkShopOrderCode')->update(['Last' => $SW]);

            $insert_workshoporder = workshoporder::create([
                'ID' => $ID,
                'UserName' => $request->username,
                'Remarks' => $request->catatan,
                'SW' => $SW,
                'TransDate' => $request->tgl_masuk,
                'Employee' => $request->karid,
                'Status' => 'A',
                'Department' => $request->idbagian,
                'Purpose' => $request->Keperluan,
            ]);

            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($iduser, $Module, $ID);

            for ($i = 0; $i < count($request->no); $i++) {
                $getIDitem1 = FacadesDB::connection('erp')->select('SELECT MAX(ID) + 1 as ID FROM workshoporderitem');
                $IDitem = $getIDitem1[0]->ID;

                $insert_workshoporderitem[] = workshoporderitem::create([
                    'IDM' => $ID,
                    'Ordinal' => $request['no'][$i],
                    'Product' => $request['barang'][$i],
                    'Qty' => $request['jumlah'][$i],
                    'Type' => $request['tipe'][$i],
                    'Category' => $request['kategori'][$i],
                    'Description' => $request['deskripsi'][$i],
                    'Status' => 'A',
                    'DateNeeded' => $request['tgl_butuh'][$i],
                    'Inventory' => $request['id_inv'][$i],
                    'ID' => $IDitem,
                ]);
            }

            $update_lastid_woi = lastid::where('Module', 'workshoporderitem')->update(['Last' => $IDitem]);

            FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'id' => $ID,
                    'message' => 'Berhasil',
                    'update_lastid_wo' => $update_lastid_wo,
                    'update_lastid_woc' => $update_lastid_woc,
                    'insert_workshoporder' => $insert_workshoporder,
                    'UpdateUserHistory' => $UpdateUserHistory,
                    'insert_workshoporderitem' => $insert_workshoporderitem,
                    'update_lastid_woi' => $update_lastid_woi,
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

    public function update(Request $request, $id)
    {
        $iduser = $request->session()->get('iduser');
        $Module = 77;

        // dd($request);

        FacadesDB::connection('erp')->beginTransaction();
        try {
            $deleted_wo = workshoporderitem::where('IDM', $id)->delete();

            $update_workshoporder = workshoporder::where('ID', $id)
            ->update([
                'UserName' => $request->username,
                'Remarks' => $request->catatan,
                'TransDate' => $request->tgl_masuk,
                'Employee' => $request->karid,
                'Status' => 'A',
                'Department' => $request->idbagian,
                'Purpose' => $request->Keperluan
            ]);

            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($iduser, $Module, $id);

            for ($i = 0; $i < count($request->no); $i++) {
                $getIDitem1 = FacadesDB::connection('erp')->select('SELECT MAX(ID) + 1 as ID FROM workshoporderitem');
                $IDitem = $getIDitem1[0]->ID;

                $insert_workshoporderitem[] = workshoporderitem::create([
                    'IDM' => $id,
                    'Ordinal' => $request['no'][$i],
                    'Product' => $request['barang'][$i],
                    'Qty' => $request['jumlah'][$i],
                    'Type' => $request['tipe'][$i],
                    'Category' => $request['kategori'][$i],
                    'Description' => $request['deskripsi'][$i],
                    'Status' => 'A',
                    'DateNeeded' => $request['tgl_butuh'][$i],
                    'Inventory' => $request['id_inv'][$i],
                    'ID' => $IDitem,
                ]);
            }

            $update_lastid_woi = lastid::where('Module', 'workshoporderitem')->update(['Last' => $IDitem]);


            FacadesDB::connection('erp')->commit();
            return response(
                [
                    'success' => true,
                    'id' => $id,
                    'message' => 'Berhasil',
                    'deleted_wo' => $deleted_wo,
                    'update_workshoporder' => $update_workshoporder,
                    'insert_workshoporderitem' => $insert_workshoporderitem,
                    'update_lastid_woi' => $update_lastid_woi
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

    public function cetak(Request $request)
    {
        $id = $request->id;
        // dd($id, $request);
        $header = FacadesDB::connection('erp')->select("SELECT
                    A.*,
                    C.Description AS jabatan,
                    DATE_FORMAT( A.TransDate, '%Y-%m-%d' ) AS aku,
                    DATE_FORMAT( A.EntryDate, '%d/%m/%y' ) AS inputtgl,
                    DATE_FORMAT( A.EntryDate, '%H:%i' ) AS inputtgl1,
                    B.SW AS usercin,
                    B.Description AS namakar,
                    A.SW AS noor,
                    A.ID AS IDwk 
                FROM
                    workshoporder AS A
                    LEFT JOIN employee AS B ON B.ID = A.Employee
                    LEFT JOIN Department AS C ON C.ID = B.Department 
                WHERE
                    A.ID = '$id'
        ");

        $body = FacadesDB::connection('erp')->select("SELECT
                    I.*,
                    V.Description VDescription,
                    X.Description XDescription,
                    DATE_FORMAT( U.TransDate, '%d/%m/%y' ) AS DateNeeded1,
                    I.Description AS IDescription 
                FROM
                    WorkShopOrderItem I
                    LEFT JOIN Inventory V ON I.Inventory = V.ID
                    LEFT JOIN Department X ON V.Department = X.ID
                    LEFT JOIN workshoporder AS U ON I.IDM = U.ID 
                WHERE
                    I.IDM = '$id'
                ORDER BY
                    I.Ordinal ASC

            ");
        // dd($header, $body);
        return view('Workshop.SPKWorkshop.cetak', compact('id', 'header', 'body'));
    }
}
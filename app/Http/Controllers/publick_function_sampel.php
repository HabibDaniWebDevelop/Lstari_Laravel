<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB as FacadesDB;
use App\Http\Controllers\Public_Function_Controller;

class publick_function_sampel extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function tesposting(Request $request)
    {
        $LinkID = $request->LinkID; //! ID Field
        $UserName = $request->UserName; //! User Login
        $tablelitem = 'workcompletionitem'; //? - workallocationitem    - workcompletionitem  - transferrmitem
        $tableheader = 'workcompletion'; //? - workallocation    - workcompletion    - transferrm
        $status = 'D'; //? C = Credit (Stok Berkurang)   // D = Debit (Stok Bertambah)
        $Location = '50'; //! Lokasi Departemen
        $Process = 'Production'; //! Production // Inventory
        $cause = 'Completion'; //! Usage (Stok Berkurang)   // Completion (Stok Bertambah)

        //! LinkOrd      => ordinal spko/nthko/tm
        //! LinkSW       => SW / WorkAllocation jika tidak ada di buat sama dengan LinkOrd
        //! Product      => ID Product
        //! Carat        => boleh null
        //! workorder    => boleh null

        $data = FacadesDB::connection('dev')
            // * select untuk workcompletionitem
            ->select("SELECT A.Product, A.Carat, A.Ordinal, A.WorkOrder, B.WorkAllocation as SW FROM $tablelitem AS A INNER JOIN $tableheader AS B ON A.IDM=B.ID WHERE A.IDM='$LinkID' ORDER BY A.Ordinal");

        //  * select untuk workallocationitem
        // ->select("SELECT A.Product, A.Carat, A.Ordinal, A.WorkOrder, B.SW FROM $tablelitem AS A INNER JOIN $tableheader AS B ON A.IDM=B.ID WHERE A.IDM='$LinkID' ORDER BY A.Ordinal");

        //  * select untuk transferrmitem
        // ->select("SELECT A.Product, A.Carat, A.Ordinal, A.WorkOrder, B.ID SW FROM $tablelitem A INNER JOIN $tableheader B ON A.IDM=B.ID WHERE A.IDM='$LinkID'");

        // dd($data);
        //!     urutan    = status, tablelitem, UserName, Location, Product, Carat, Process, cause, LinkSW, LinkID, LinkOrd, workorder
        foreach ($data as $datas) {
            $Posting = $this->Public_Function->PostingDEV($status, $tablelitem, $UserName, $Location, $datas->Product, $datas->Carat, $Process, $cause, $datas->SW, $LinkID, $datas->Ordinal, $datas->WorkOrder);
            // dd($status, $tablelitem, $UserName, $Location, $datas->Product, $datas->Carat, $Process, $cause, $datas->SW, $LinkID, $datas->Ordinal, $datas->WorkOrder);
            // dd($Posting['validasi']);
        }

        if ($Posting['validasi'] && $Posting['insertstok'] && $Posting['update_ptrns']) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Posting Berhasil!!',
                ],
                201,
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Posting Gagal!!',
                ],
                400,
            );

            // return response('Post deleted successfully.', 400);
        }
    }

    public function TesPostingNew(Request $request)
    {
        $UserName = Auth::user()->name;
        $LinkID = $request->id; //! id tabel
        $status = 'C'; //! C = Credit (Stok Berkurang)   // D = Debit (Stok Bertambah)
        $cause = 'Usage'; //! Usage (Stok Berkurang)   // Completion (Stok Bertambah)
        $Process = 'Production'; //! Production // Inventory

        //  * select untuk workallocation
        $data = FacadesDB::connection('dev')->select("SELECT A.SW, A.Location, B.Ordinal, B.Product, B.Carat, B.Qty, B.Weight, B.WorkOrder FROM workallocation AS A INNER JOIN workallocationitem AS B ON A.ID=B.IDM WHERE A.ID='$LinkID' ORDER BY B.Ordinal");

        // ? mengunakan DBTransaction
        FacadesDB::connection('dev')->beginTransaction();
        try {

            //! urutan    = status, UserName, Location, Product, Carat, Process, cause, LinkSW, LinkID, LinkOrd, Qty, Weight, workorder
            foreach ($data as $datas) {
                // dd($status, $UserName, $datas->Location, $datas->Product, $datas->Carat, $Process, $cause, $datas->SW, $LinkID, $datas->Ordinal,$datas->Qty, $datas->Weight, $datas->WorkOrder);
                $Posting = $this->Public_Function->PostingNewDEV($status, $UserName, $datas->Location, $datas->Product, $datas->Carat, $Process, $cause, $datas->SW, $LinkID, $datas->Ordinal, $datas->Qty, $datas->Weight, $datas->WorkOrder);
            }

            if (!$Posting['success']) {
                // Rollback transaksi secara manual
                FacadesDB::connection('dev')->rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $Posting['message'],
                ]);
            }

            // Commit transaksi
            FacadesDB::connection('dev')->commit();
            return response()->json(
                [
                    'success' => true,
                    'message' => $Posting['message'],
                ],
                201,
            );
            
        } catch (Exception $e) {
            // Rollback transaksi secara manual
            FacadesDB::connection('dev')->rollBack();

            // Handle kesalahan dalam transaksi
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Posting Gagal!!',
                ],
                400,
            );
        }
    }

    public function tespostingTMlihat()
    {
        return view('setting.publick_function.tespostingTM');
    }

    public function TespostingTM(Request $request)
    {
        $id = $request->LinkID;
        $UserName = $request->UserName; //* User Login

        $gettransferrm = FacadesDB::connection('dev')->select("SELECT
                    *
                FROM
                    transferrm
                WHERE
                    id = '$id'
                ");
        $TransDate = $gettransferrm[0]->TransDate;
        $ToLoc = $gettransferrm[0]->ToLoc;
        $Active = $gettransferrm[0]->Active;

        // ToLoc dan TransDate dari tabel transferrm
        // $CekStokHarian = $this->Public_Function->CekStokHarianDEV($ToLoc, $TransDate);
        $CekStokHarian = 1;

        if ($Active == 'P') {
            $status = 'sudah posting';
        } elseif ($Active == 'A' && $CekStokHarian == 1) {
            $status = 'proses posting';
            $PostingTM = $this->Public_Function->PostingTMDEV($id, $UserName);
            // if($PostingTM['validasi']){
            //     $status = 'Terposting';
            // }
        } else {
            $status = 'Tanggal Stok harian Tidak Sesuai';
        }

        if ($status == 'Terposting') {
            return response()->json(
                [
                    'success' => true,
                    'message' => $status,
                ],
                201,
            );
        } else {
            return response()->json([
                'success' => false,
                'message' => $status,
            ]);

            // return response('Post deleted successfully.', 400);
        }

        dd($status);

        // dd($querystatus, $queryTM, $queryTMAll, $multipleTM, $status);
    }

    public function tesCekStokHarian()
    {
        $id = '2301501440';
        //! untuk nama tabel di atur sesuai nama tabel yang mau di posting (transferrm, workcompletion, workallocation, waxstoneusage)
        $cektransferrm = FacadesDB::connection('dev')->select("SELECT * FROM transferrm WHERE id = '$id' ");
        $TransDate = $cektransferrm[0]->TransDate;
        $ToLoc = $cektransferrm[0]->ToLoc;

        // ToLoc dan TransDate dari tabel transferrm
        $CekStokHarian = $this->Public_Function->CekStokHarianDEV($ToLoc, $TransDate);

        //! kasih respon jika stock harian tidak sesuai
        if ($CekStokHarian == false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Cek Stock Harian Tidak Sesuai!!',
                ],
                400,
            );
        }
        dd($CekStokHarian);
    }

    public function tesCekStokHarian2()
    {
        $id = '2301501440';
        //! untuk nama tabel di atur sesuai nama tabel yang mau di posting (transferrm, workcompletion, workallocation, waxstoneusage)
        $cektransferrm = FacadesDB::connection('dev')->select("SELECT * FROM transferrm WHERE id = '$id' ");
        $TransDate = $cektransferrm[0]->TransDate;
        $FromLoc = $cektransferrm[0]->FromLoc;
        $ToLoc = $cektransferrm[0]->ToLoc;

        // ToLoc dan TransDate dari tabel transferrm
        $CekStokHarian = $this->Public_Function->CekStokHarian2DEV($FromLoc, $ToLoc, $TransDate);

        //! kasih respon jika stock harian tidak sesuai
        if ($CekStokHarian == false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Cek Stock Harian Tidak Sesuai!!',
                ],
                400,
            );
        }
        dd($CekStokHarian);
    }

    public function tesGetLastID()
    {
        //! untuk $ModuleName nama tabel yang mau di cek LastID nya
        $ModuleName = 'PurchaseOrder';
        $GetLastID = $this->Public_Function->GetLastIDDEV($ModuleName);

        dd($GetLastID['ID']);
    }

    public function TesListUserHistory(Request $request)
    {
        //! untuk $Module di isi id_modul di tabel master_module_laravel, untuk $tablename nama tabel yang mau di cek historynya
        $tablename = 'componentrequest';
        $UserID = $request->session()->get('iduser'); // $UserID = '1393';
        $Module = '122';
        $ListUserHistory = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);
        dd($ListUserHistory);
    }

    public function TesUpdateUserHistory(Request $request)
    {
        //! untuk $Module di isi id_modul di tabel master_module_laravel, untuk $ID_Field di isi dengan id yang ada di tabel yang di simpan historinya
        $UserID = $request->session()->get('iduser'); // $UserID = '1316';
        $Module = '242';
        $ID_Field = '123';
        $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryDEV($UserID, $Module, $ID_Field);
        dd($UpdateUserHistory);
    }

    public function TesSetStatustransaction()
    {
        //! untuk $tabel di atur sesuai nama tabel yang mau di update Statustransaction nya (transferrm, workcompletion, workallocation, waxstoneusage)
        // $tabel = 'workallocation';
        // $id = '493516';
        // $tabel = 'workcompletion';
        // $id = '506863';
        $tabel = 'transferrm';
        $id = '2301531170';
        $UpdateUserHistory = $this->Public_Function->SetStatustransactionDEV($tabel, $id);
        dd($UpdateUserHistory);
    }

    public function TesViewSelection()
    {
        return view('setting.publick_function.TesViewSelection');
    }
}

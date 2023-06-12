<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\rubberout;
// use App\Models\tes_laravel\rubberoutitem;
// use App\Models\tes_laravel\rubber;
// use App\Models\tes_laravel\rubberlocation;
// use App\Models\tes_laravel\waxinjectorderrubber;

// live
use App\Models\erp\rubberout;
use App\Models\erp\rubberoutitem;
use App\Models\erp\rubber;
use App\Models\erp\rubberlocation;
use App\Models\erp\waxinjectorderrubber;

// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class PenggunaanKaretController extends Controller{
    // set public function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }
    
    // Private Function
    private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    public function Index(){
        // Get Employee
        $employee = FacadesDB::connection('erp')
        ->select("
            SELECT 
                ID, 
                SW, 
                Description 
            FROM 
                employee 
            WHERE 
                Department = 19 AND Active = 'Y'
        ");
        $datenow = date('Y-m-d');
        // history waxstoneusage
        $historyRubberout = FacadesDB::connection('erp')->select("SELECT LinkID FROM rubberout ORDER BY ID DESC LIMIT 15");
        return view('Produksi.Lilin.PenggunaanKaret.index', compact('employee', 'datenow', 'historyRubberout'));
    }

    public function getWaxInjectOrder($IDSPKOInject){
        // Getting WaxInjectOrder
        $isiformheadertambah = FacadesDB::connection('erp')
        ->select("SELECT
            F.ID IDOperator,
            F.SW Operator,
            A.ID as spk,
			PC.Description Kadar,
            PC.ID as IDKadar,
            CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
        FROM
            waxinjectorder A
            JOIN employee F ON F.ID = A.Employee
			JOIN productcarat PC ON PC.ID = A.Carat
        WHERE A.ID = $IDSPKOInject
        ");
        if (count($isiformheadertambah) == 0) {
            $data_return = $this->SetReturn(false, "ID SPKO Inject Tidak Ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        
        $isiformheadertambah = $isiformheadertambah[0];
        // Check if that ID its already SPKOKareted.
        // $cekSPKO = rubberout::where('LinkID',$IDSPKOInject)->first();
        // if (!is_null($cekSPKO)) {
        //     $data_return = $this->SetReturn(false, "Nomor SPKO Tersebut Sudah DiGunakan", null, null);
        //     return response()->json($data_return, 400);
        // }

        // Check status SPKOienjct.
        // $cekSPKO = rubberout::where('LinkID',$IDSPKOInject)->first();
        // if (!is_null($cekSPKO)) {
        //     $data_return = $this->SetReturn(false, "Sedang di Non-Actifkan", null, null);
        //     return response()->json($data_return, 400);
        // }

        // Get Items cara baru dengan banyak workorder di tabel worklist3dp
        $isiformitemtambah = FacadesDB::connection('erp')
        ->select("SELECT	
                K.SW SPKPPIC,
                K.ID IDWorkOrder,
        P.SW as ItemProduct,
                P.Description,
        P.ID IDProd,                          
        G.ID as IDKaret,
        CASE WHEN C.ID IS NULL THEN 'Karet Belum Di Registrasi' ELSE 
        CONCAT(LE.SW, ' ', LA.SW, ' ', BA.SW, ' ', KO.SW) END Lokasi,
        PC.Description Kadar,
        PC.ID IDKadar,
        CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
        FROM
        waxinjectorder A
        JOIN waxinjectorderrubber D ON D.IDM = A.ID
        LEFT JOIN WorkOrder K ON K.ID = D.LinkOrd
        LEFT JOIN rubberlocation C ON C.RubberID = D.Rubber
        JOIN employee F ON F.ID = A.Employee
        JOIN rubber G ON G.ID = D.Rubber
        JOIN product P ON P.ID = G.Product
        LEFT JOIN masterlemari LE ON C.LemariID = LE.ID
        LEFT JOIN masterlaci LA ON C.LaciID = LA.ID
        LEFT JOIN masterkolom KO ON C.KolomID = KO.ID
        LEFT JOIN masterbaris BA ON C.BarisID = BA.ID 
        LEFT JOIN productcarat PC ON PC.ID = G.Carat
        WHERE A.ID = $IDSPKOInject
        ");

        // Check Item 
        if (count($isiformitemtambah) == 0) {
            $data_return = $this->SetReturn(false, "SPKO tidak valid. Karena tidak ada item/barang di Database Untuk SPKO Tersebut.", null, null);
            return response()->json($data_return, 400);
        }
        $isiformheadertambah->items = $isiformitemtambah;

        $data_return = $this->SetReturn(true, "Getting WaxInjectOrder Item Success", $isiformheadertambah, null);
        return response()->json($data_return, 200);
    }

    public function Simpan(Request $request){
        // Get Required Data
        $IDSPKOInject = $request->IDSPKOInject;
        $Operator = $request->Operator;
        $date = $request->date;
        $Keperluan = $request->Keperluan;
        $Kadar = $request->Kadar;
        $Catatan = $request->Catatan;
        $chekitem = $request->items;
        $chekheader = [$request->IDSPKOInject, $request->Operator, $request->date, $request->Keperluan, $request->Kadar];
        
        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($IDSPKOInject) or $IDSPKOInject == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data pengeluaran karet",
                "data"=>null,
                "error"=>[
                    "IDSPKOInject"=>"IDSPKOInject Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if idEmployee null or blank
        if (is_null($Operator) or $Operator == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data pengeluaran karet",
                "data"=>null,
                "error"=>[
                "Operator"=>"Operator Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // Check if beratBatu null or blank
        if (is_null($date) or $date == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data pengeluaran karet",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if beratPohonTotal null or blank
        if (is_null($Keperluan) or  $Keperluan == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data pengeluaran karet",
                "data"=>null,
                "error"=>[
                "Keperluan"=>"Keperluan Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        //generateID urut untuk waxstoeneusage
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rubberout");

        $generateSW = FacadesDB::connection("erp")
        ->select("SELECT CONCAT('',DATE_FORMAT(CURDATE(), '%y'),'',LPad(Month(CurDate()),2, '0'),'',LPad(Count(ID) + 1, 5, '0')) Counter
        From rubberout
        Where Year(EntryDate) = Year(CurDate()) And Month(EntryDate) = Month(CurDate())");

        $insert_rubberout = rubberout::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => $date,
            'UserName' => $username,
            'TransDate' => date('Y-m-d H:i:s'),
            'Employee' => $Operator,
            'SW' => $generateSW[0]->Counter,
            'Area' => 51,
            'Remarks' => $Catatan,
            'LinkID' => $IDSPKOInject,
            'Status' => $Keperluan,
        ]);
        
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_rubberoutitem = rubberoutitem::create([
                'IDM' => $generateID[0]->ID,
                'Ordinal' => $IT+1,
                'RubberID' => $isi['IDKaret'],
                'Product' => $isi['IDprod'],
                'ReturnDate' => NULL,
                'ReturnGood' => NULL,
            ]);
            
            $update_rubber = rubber::where('ID', $isi['IDKaret'])
            ->update(['Active' => $insert_rubberout->Status]);
            
            $update_rubberlocation = rubberlocation::where('RubberID', $isi['IDKaret'])->update([
                'Active' => $insert_rubberout->Status,
                'UseDate' => date('Y-m-d H:i:s'),
            ]);
            
            // $delete_waxinjectorderrubber = waxinjectorderrubber::where('IDM',$IDSPKOInject)->delete();

            // $insert_waxinjectorderrubber = waxinjectorderrubber::create([
            //     'IDM' => $IDSPKOInject,
            //     'Ordinal' => $IT+1,
            //     'Rubber' => $isi['IDKaret'],
            //     'LinkOrd' => $generateID[0]->ID,
            // ]);
           
        }

        $data_return = $this->SetReturn(true, "Save SPKO Karet Sukses", ['ID'=>$insert_rubberout->LinkID], null);
        return response()->json($data_return, 200);
    }


    public function Print(Request $request){
        // Get idwaxstoneusage
        $idRubberOut = $request->idRubberOut;
        // Get Header
        $RubberOutHeader = FacadesDB::connection('erp')
        ->select("SELECT
        T.Employee IDOperator,
        T.ID IDSPKOKaret,
        E.SW Operator,
        T.LinkID AS IDSPKOInject,
        PC.Description Kadar,
        PC.ID AS IDKadar,
        T.Remarks,
        T.EntryDate,
        CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
    FROM
        rubberout T
        JOIN employee E ON E.ID = T.Employee
        JOIN waxinjectorder W ON W.ID = T.LinkID
        LEFT JOIN productcarat PC ON PC.ID = W.Carat 
    WHERE
        T.LinkID = $idRubberOut
    
    ");
        if (count($RubberOutHeader) == 0) {
            abort(404);
        }
        $RubberOutHeader = $RubberOutHeader[0];

        // IDWaxInjectOrder
        $IDSPKOInject = $RubberOutHeader->IDSPKOInject;
        $IDSPKOKaret = $RubberOutHeader->IDSPKOKaret;
        // Get Items
        $RubberOutTabel = FacadesDB::connection('erp')
        ->select("SELECT
            T.LinkID AS SPKOInject,
            PC.Description Kadar,
            PC.ID AS IDKadar,
            R.ID IDKaret,
            K.SW SPKPPIC,
            DATE_FORMAT(T.TransDate, '%d-%m-%Y') as TglDipinjam,
            P.SW Produk,
            P.Description NamaProduk,
        CASE WHEN RL.ID IS NULL THEN 'Karet Belum Di Registrasi' ELSE CONCAT(ML.SW, ' ', MC.SW, ' ', MB.SW, ' ', MK.SW) END datamu
        FROM
            rubberout T
            JOIN rubberoutitem TI ON TI.IDM = T.ID
            JOIN employee E ON E.ID = T.Employee
            JOIN rubber R ON R.ID = TI.RubberID
            LEFT JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN Waxinjectorderitem B ON B.IDM = T.LinkID
            LEFT JOIN WorkOrder K ON K.ID = B.Tatakan 
            LEFT JOIN rubberlocation RL ON TI.RubberID = RL.RubberID
            JOIN product P ON P.ID = R.Product
            LEFT JOIN masterlemari ML ON RL.LemariID = ML.ID
            LEFT JOIN masterlaci MC ON RL.LaciID = MC.ID
            LEFT JOIN masterkolom MK ON RL.KolomID = MK.ID
            LEFT JOIN masterbaris MB ON RL.BarisID = MB.ID
        WHERE
            T.LinkID = $idRubberOut
            GROUP BY TI.RubberID");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        return view('Produksi.Lilin.PenggunaanKaret.cetak',compact('username','RubberOutHeader','IDSPKOKaret','IDSPKOInject','RubberOutTabel','date','datenow','timenow'));
    }

    public function IDKaret(Request $request){
        $IDKaret = $request->value;

        $RubberIDdata = FacadesDB::connection('erp')
        ->select("SELECT
            P.SW ItemProduct,
            P.Description,
            P.ID IDProd,
            CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            PC.Description Kadar,
            PC.ID AS IDKadar,
            R.ID IDKaret,
        CASE WHEN RL.ID IS NULL THEN 'Karet Belum Di Registrasi' ELSE CONCAT(ML.SW, ' ', MC.SW, ' ', MK.SW, ' ', MB.SW) END Lokasi
        FROM
            rubber R
            LEFT JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN rubberlocation RL ON R.ID = RL.RubberID
            JOIN product P ON P.ID = R.Product
            LEFT JOIN masterlemari ML ON RL.LemariID = ML.ID
            LEFT JOIN masterlaci MC ON RL.LaciID = MC.ID
            LEFT JOIN masterkolom MK ON RL.KolomID = MK.ID
            LEFT JOIN masterbaris MB ON RL.BarisID = MB.ID
        WHERE
            R.ID = $IDKaret");

        $rowcount = count($RubberIDdata);
        if($rowcount > 0){
            foreach ($RubberIDdata as $datas){}
            $ItemProduct = $datas->ItemProduct;
            $Description = $datas->Description;
            $IDProd = $datas->IDProd;
            $HexColor = $datas->HexColor;
            $Kadar = $datas->Kadar;
            $IDKadar = $datas->IDKadar;
            $IDKaret = $datas->IDKaret;
            $Lokasi = $datas->Lokasi;

            // dd($ItemProduct, $Description, $IDProd, $HexColor, $Kadar, $IDKadar, $IDKaret, $Lokasi);
            $data_Return = array(
                'rowcount' => $rowcount,
                'ItemProduct' => $ItemProduct, 
                'Description' => $Description,
                'IDProd' => $IDProd,
                'HexColor' => $HexColor, 
                'Kadar' => $Kadar, 
                'IDKadar' => $IDKadar, 
                'IDKaret' => $IDKaret, 
                'Lokasi' => $Lokasi);
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }

    public function Search(Request $request){
        $keyword = $request->keyword;
        // Cek waxtree if exists
        $RubberOutHeader = FacadesDB::connection('erp')
        ->select("SELECT
        T.Employee IDOperator,
        T.ID IDSPKOKaret,
        E.SW Operator,
        T.LinkID AS IDSPKOInject,
        PC.Description Kadar,
        PC.ID AS IDKadar,
        T.Remarks,
        T.TransDate,
        T.UserName,
        T.EntryDate,
        T.Status,
        CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
    FROM
        rubberout T
        JOIN employee E ON E.ID = T.Employee
        JOIN waxinjectorder W ON W.ID = T.LinkID
        JOIN productcarat PC ON PC.ID = W.Carat 
    WHERE
        T.LinkID = $keyword");

        if (count($RubberOutHeader) == 0) {
            $data_return = $this->SetReturn(false, "gagal mencari data Karet Keluar id yang dimasukkan tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $RubberOutHeader = $RubberOutHeader[0];
       
        // IDWaxInjectOrder
        $IDSPKOInject = $RubberOutHeader->IDSPKOInject;

        $RubberOutTabel = FacadesDB::connection('erp')
        ->select("SELECT
            T.LinkID AS SPKOInject,
            PC.Description Kadar,
            PC.ID AS IDKadar,
            R.ID IDKaret,
            K.SW SPKPPIC,
            DATE_FORMAT(T.TransDate, '%d-%m-%Y') as TglDipinjam,
            P.ID IDProd,
            P.SW ItemProduk,
            P.Description,
        CASE WHEN RL.ID IS NULL THEN 'Karet Belum Di Registrasi' ELSE CONCAT(ML.SW, ' ', MC.SW, ' ', MB.SW, ' ', MK.SW) END Lokasi,
        CASE
                WHEN PC.SW = '6K' THEN
                '#0000FF' 
                WHEN PC.SW = '8K' THEN
                '#00FF00' 
                WHEN PC.SW = '8K.' THEN
                '#CFB370' 
                WHEN PC.SW = '10K' THEN
                '#FFFF00' 
                WHEN PC.SW = '16K' THEN
                '#FF0000' 
                WHEN PC.SW = '17K' THEN
                '#FF6E01' 
                WHEN PC.SW = '17K.' THEN
                '#FF00FF' 
                WHEN PC.SW = '19K' THEN
                '#5F2987' 
                WHEN PC.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor
        FROM
            rubberout T
            JOIN rubberoutitem TI ON TI.IDM = T.ID
            JOIN employee E ON E.ID = T.Employee
            JOIN rubber R ON R.ID = TI.RubberID
            JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN Waxinjectorderitem B ON B.IDM = T.LinkID
            LEFT JOIN WorkOrder K ON K.ID = B.Tatakan 
            LEFT JOIN rubberlocation RL ON TI.RubberID = RL.RubberID
            JOIN product P ON P.ID = R.Product
            LEFT JOIN masterlemari ML ON RL.LemariID = ML.ID
            LEFT JOIN masterlaci MC ON RL.LaciID = MC.ID
            LEFT JOIN masterkolom MK ON RL.KolomID = MK.ID
            LEFT JOIN masterbaris MB ON RL.BarisID = MB.ID
        WHERE
            T.LinkID = $keyword
            GROUP BY TI.RubberID");

        $RubberOutHeader->items = $RubberOutTabel;
        $data_return = $this->SetReturn(true, "Getting RubberOut Success. RubberOut found", $RubberOutHeader, null);
        return response()->json($data_return, 200);
    }

    public function UpdateSPKOKaret(Request $request){
    }

}
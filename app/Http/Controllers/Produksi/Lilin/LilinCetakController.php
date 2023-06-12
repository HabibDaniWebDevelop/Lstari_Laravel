<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\wax;
// use App\Models\tes_laravel\waxitem;


// live
use App\Models\erp\wax;
use App\Models\erp\waxitem;


// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class LilinCetakController extends Controller{
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
        $historyRubberout = FacadesDB::connection('erp')->select("SELECT ID FROM wax ORDER BY ID DESC LIMIT 15");
        return view('Produksi.Lilin.LilinCetak.index', compact('employee', 'datenow', 'historyRubberout'));
    }

    public function inputoperator($IdOperator){
        
        $Operator = FacadesDB::connection('erp')
        ->select("SELECT ID,Description,Department FROM employee WHERE Department='19' AND Active='Y' AND ID='$IdOperator' AND `Rank`='Operator'");
        
        
        if ($Operator){
            return response()->json(
                [
                    'namaop' => $Operator[0]->Description,
                ],
                201,
            );
        } else{
            return response()->json(
                [
                    'namaop' => 'Nama Tidak Ditemukan',
                ],
                201,
            );
        } 
    }

    public function getWaxInjectOrder($IDSPKOInject){
        // Getting WaxInjectOrder
        $isiformheadertambah = FacadesDB::connection('erp')
        ->select("SELECT
            A.ID as spk,
			PC.Description Kadar,
            PC.ID as IDKadar,
            A.WorkGroup,
            A.Employee,
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
        K.SW WorkOrder,
        K.ID IDWorkOrder,
        P.SW AS ItemProduct,
        P.Description,
        P.ID IDProd,
        G.ID AS IDKaret,
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
            END HexColor,
        WI.Qty
    FROM
        Waxinjectorderitem WI
        JOIN waxinjectorderrubber D ON D.IDM = WI.IDM AND WI.Ordinal = D.Ordinal
        JOIN WorkOrder K ON K.ID = WI.Tatakan
        JOIN workscheduleitem WS ON WS.LinkID = K.ID AND WS.Level2 = WI.WaxOrder AND WS.Level3 = WI.WaxOrderOrd
        JOIN rubber G ON G.ID = D.Rubber
        JOIN product P ON P.ID = G.Product
        LEFT JOIN productcarat PC ON PC.ID = G.Carat 
    WHERE
        WI.IDM = $IDSPKOInject
        GROUP BY
        D.Ordinal
        ORDER BY 
        D.Ordinal
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

        // dd($request);
        $IDSPKOInject = $request->IDSPKOInject;
        $KadarSPK = $request->KadarSPK;
        $date = $request->date;
        $IDOperator = $request->IDOperator;
        $kelompok = $request->kelompok;
        $JumlahScrap = $request->JumlahScrap;
        $JumlahCompletion = $request->JumlahCompletion;
        $JumlahMold = $request->JumlahMold;
        $Catatan = $request->Catatan;
        $items = $request->items;
        
        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($IDSPKOInject) or $IDSPKOInject == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data Cetak Lilin",
                "data"=>null,
                "error"=>[
                    "IDSPKOInject"=>"IDSPKOInject Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if idEmployee null or blank
        if (is_null($IDOperator) or $IDOperator == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data Cetak Lilin",
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
                "message"=>"Gagal menyimpan data Cetak Lilin",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if beratPohonTotal null or blank
        if (is_null($kelompok) or  $kelompok == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data Cetak Lilin",
                "data"=>null,
                "error"=>[
                "kelompok"=>"kelompok Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        //generateID urut untuk waxstoeneusage
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM wax");

        // $generateSW = FacadesDB::connection("erp")
        // ->select("SELECT CONCAT('',DATE_FORMAT(CURDATE(), '%y'),'',LPad(Month(CurDate()),2, '0'),'',LPad(Count(ID) + 1, 5, '0')) Counter
        // From rubberout
        // Where Year(EntryDate) = Year(CurDate()) And Month(EntryDate) = Month(CurDate())");

        $insert_wax = wax::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName'	=> $username,
            'Remarks'	=> $Catatan,
            'TransDate'	=> $date,
            'Employee'	=> $IDOperator,
            'Mold'	=> $JumlahMold,
            'Scrap'	=> $JumlahScrap,
            'Completion'=>$JumlahCompletion,
            'WorkGroup'	=>$kelompok,
            'WaxOrder'	=>$IDSPKOInject,
            'WorkTime'=> NULL,
        ]);
        
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_waxitem = waxitem::create([
                'IDM' => $generateID[0]->ID,
                'Ordinal' => $IT+1,
                'WorkOrder'	=> $isi['IDWorkOrder'],
                'Product'=> $isi['IDProduct'],
                'Rubber'=> $isi['IDKaret'],
                'Qty'	=> $isi['Qty'],
                'Mold'	=> $isi['Mold'],
                'Scrap'	=> $isi['Scrap'],
                'Completion'=> $isi['Completion'],
                'Inject'	=> $isi['Inject'],
                'OverTime'	=> 'N',
                'Carat'=> $KadarSPK,
            ]);
            
            // // $insert_waxinjectorderrubber = waxinjectorderrubber::where('IDM', $IDSPKOInject)->where('Rubber','!=',$isi['IDKaret'])
            // // ->create([
            // //     'IDM' => $IDSPKOInject,
            // //     'Ordinal' => 
            // //     'Rubber' => $isi['IDKaret'],
            // //     'LinkOrd' => $generateID[0]->ID,
            // // ]);
        }
       
// $update_rubber = "UPDATE rubber SET Active = '$Keperluan' WHERE ID = ";
            // $UpdateWaxtreeASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeA);

        $data_return = $this->SetReturn(true, "Save Lilin Cetak Sukses", ['ID'=>$insert_wax->ID], null);
        return response()->json($data_return, 200);
    }

    //     public function Print(Request $request){
    //         // Get idwaxstoneusage
    //     $idRubberOut = $request->idRubberOut;
    //     // Get Header
    //     $RubberOutHeader = FacadesDB::connection('erp')
    //     ->select("SELECT
    //     T.Employee IDOperator,
    //     T.ID IDSPKOKaret,
    //     E.SW Operator,
    //     T.LinkID AS IDSPKOInject,
    //     PC.Description Kadar,
    //     PC.ID AS IDKadar,
    //     T.Remarks,
    //     T.EntryDate,
    // CASE
            
    //         WHEN PC.SW = '6K' THEN
    //         '#090cd9' 
    //         WHEN PC.SW = '8K' THEN
    //         '#02ba1e' 
    //         WHEN PC.SW = '16K' THEN
    //         '#ff1a1a' 
    //         WHEN PC.SW = '17K' THEN
    //         '#e65507' 
    //         WHEN PC.SW = '17K.' THEN
    //         '#d909cb' 
    //         WHEN PC.SW = '20K' THEN
    //         '#ffcba4' 
    //         WHEN PC.SW = '10K' THEN
    //         '#f5fc0f' 
    //         WHEN PC.SW = '8K.' THEN
    //         '#ebb52d' 
    //         WHEN PC.SW = '19K' THEN
    //         '#4908a3' 
    //     END HexColor 
    // FROM
    //     rubberout T
    //     JOIN employee E ON E.ID = T.Employee
    //     JOIN waxinjectorder W ON W.ID = T.LinkID
    //     LEFT JOIN productcarat PC ON PC.ID = W.Carat 
    // WHERE
    //     T.LinkID = $idRubberOut
    
    // ");
    //     if (count($RubberOutHeader) == 0) {
    //         abort(404);
    //     }
    //     $RubberOutHeader = $RubberOutHeader[0];

    //     // IDWaxInjectOrder
    //     $IDSPKOInject = $RubberOutHeader->IDSPKOInject;
    //     $IDSPKOKaret = $RubberOutHeader->IDSPKOKaret;
    //     // Get Items
    //     $RubberOutTabel = FacadesDB::connection('erp')
    //     ->select("SELECT
    //         T.LinkID AS SPKOInject,
    //         PC.Description Kadar,
    //         PC.ID AS IDKadar,
    //         R.ID IDKaret,
    //         K.SW SPKPPIC,
    //         DATE_FORMAT(T.TransDate, '%d-%m-%Y') as TglDipinjam,
    //         P.SW Produk,
    //         P.Description NamaProduk,
    //     CASE WHEN RL.ID IS NULL THEN 'Karet Belum Di Registrasi' ELSE CONCAT(ML.SW, ' ', MC.SW, ' ', MB.SW, ' ', MK.SW) END datamu
    //     FROM
    //         rubberout T
    //         JOIN rubberoutitem TI ON TI.IDM = T.ID
    //         JOIN employee E ON E.ID = T.Employee
    //         JOIN rubber R ON R.ID = TI.RubberID
    //         LEFT JOIN productcarat PC ON PC.ID = R.Carat
    //         LEFT JOIN Waxinjectorderitem B ON B.IDM = T.LinkID
    //         LEFT JOIN WorkOrder K ON K.ID = B.Tatakan 
    //         LEFT JOIN rubberlocation RL ON TI.RubberID = RL.RubberID
    //         JOIN product P ON P.ID = R.Product
    //         LEFT JOIN masterlemari ML ON RL.LemariID = ML.ID
    //         LEFT JOIN masterlaci MC ON RL.LaciID = MC.ID
    //         LEFT JOIN masterkolom MK ON RL.KolomID = MK.ID
    //         LEFT JOIN masterbaris MB ON RL.BarisID = MB.ID
    //     WHERE
    //         T.LinkID = $idRubberOut
    //         GROUP BY TI.RubberID");

    //     $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
    //     $datenow = $date->format("d/m/Y");
    //     $timenow = $date->format("H:i");

    //     $username = Auth::user()->name;
    //     FacadesDB::beginTransaction();

    //     return view('Produksi.Lilin.LilinCetak.cetak',compact('username','RubberOutHeader','IDSPKOKaret','IDSPKOInject','RubberOutTabel','date','datenow','timenow'));
    // }

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
            R.ID IDKaret
       
        FROM
            rubber R
            LEFT JOIN productcarat PC ON PC.ID = R.Carat
            JOIN product P ON P.ID = R.Product
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
    

            // dd($ItemProduct, $Description, $IDProd, $HexColor, $Kadar, $IDKadar, $IDKaret, $Lokasi);
            $data_Return = array(
                'rowcount' => $rowcount,
                'ItemProduct' => $ItemProduct, 
                'Description' => $Description,
                'IDProd' => $IDProd,
                'HexColor' => $HexColor, 
                'Kadar' => $Kadar, 
                'IDKadar' => $IDKadar, 
                'IDKaret' => $IDKaret);
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }

    public function Search(Request $request){
        $IDWaxORder_tbWax = $request->keyword;
        // dd($IDWaxORder_tbWax);
        // Cek waxtree if exists
        $wax_Header = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
        A.ID,
        A.WaxOrder as spk,
        PC.Description Kadar,
        PC.ID as IDKadar,
        A.WorkGroup,
        A.Employee,
		A.Mold AS Cetak,
		A.Scrap AS Rusak,
		A.`Completion` AS Setor,
		A.TransDate AS tanggal,
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
    A.Remarks,
		A.UserName,
        A.EntryDate
    FROM
        wax A
        JOIN WaxItem AI ON AI.IDM = A.ID
		JOIN waxinjectorder W On W.ID = A.WaxOrder
        JOIN productcarat PC ON PC.ID = W.Carat
    WHERE A.ID = $IDWaxORder_tbWax
	
    ");
    if (count($wax_Header) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKO tersebut Tidak Ditemukan di data lilin cetak", null, null);
        return response()->json($data_return, 404);
    }
    
    $wax_Header = $wax_Header[0];
       
        // IDWaxInjectOrder
        $IDWax = $wax_Header->ID;

        $WaxItem_tabel = FacadesDB::connection('erp')
        ->select("SELECT
        W.SW WorkOrder,
        W.ID IDWorkOrder,
        P.SW AS ItemProduct,
        P.Description,
        P.ID IDProd,
        R.ID AS IDKaret,
        PC.Description Kadar,
        PC.ID IDKadar,
				AI.Mold AS Cetak,
				AI.Scrap AS Rusak,
				AI.`Completion` AS Setor,
				AI.Inject AS Inject,
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
        AI.Qty
    FROM
			WaxItem AI
			JOIN workorder W ON W.ID = AI.WorkOrder
				JOIN product P ON P.ID = AI.Product
				JOIN Rubber R ON R.ID = AI.Rubber
				LEFT JOIN productcarat PC ON PC.ID = R.Carat
    WHERE
      AI.IDM = $IDWaxORder_tbWax
      ");
    // dd($WaxItem_tabel);
        $wax_Header->items = $WaxItem_tabel;
        $data_return = $this->SetReturn(true, "Getting data wax and waxitem Success. wax found", $wax_Header, null);
        return response()->json($data_return, 200);
    }

    public function Update(Request $request){
        // dd($request);
        $IDwax = $request->IDwax;
        $IDSPKOInject = $request->IDSPKOInject;
        $KadarSPK = $request->KadarSPK;
        $date = $request->date;
        $IDOperator = $request->IDOperator;
        $kelompok = $request->kelompok;
        $JumlahScrap = $request->JumlahScrap;
        $JumlahCompletion = $request->JumlahCompletion;
        $JumlahMold = $request->JumlahMold;
        $Catatan = $request->Catatan;
        $items = $request->items;
        
        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($IDwax) or $IDwax == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal Update data Cetak Lilin",
                "data"=>null,
                "error"=>[
                    "IDwaxt"=>"IDwax Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        if (is_null($IDSPKOInject) or $IDSPKOInject == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal Update data Cetak Lilin",
                "data"=>null,
                "error"=>[
                    "IDSPKOInject"=>"IDSPKOInject Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if idEmployee null or blank
        if (is_null($IDOperator) or $IDOperator == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal Update data Cetak Lilin",
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
                "message"=>"Gagal Update data Cetak Lilin",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }
        
        // Check if beratPohonTotal null or blank
        if (is_null($kelompok) or  $kelompok == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal Update data Cetak Lilin",
                "data"=>null,
                "error"=>[
                "kelompok"=>"kelompok Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        $update_wax = wax::where('ID',$IDwax)->update([
            'UserName'	=> $username,
            'Remarks'	=> $Catatan,
            'TransDate'	=> $date,
            'Employee'	=> $IDOperator,
            'Mold'	=> $JumlahMold,
            'Scrap'	=> $JumlahScrap,
            'Completion'=>$JumlahCompletion,
            'WorkGroup'	=>$kelompok,
            'WaxOrder'	=>$IDSPKOInject,
            'WorkTime'=> NULL,
        ]);
        
        $deleteWaxitem = waxitem::where('IDM',$IDwax)->delete();

        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_waxitem = waxitem::create([
                'IDM' => $IDwax,
                'Ordinal' => $IT+1,
                'WorkOrder'	=> $isi['IDWorkOrder'],
                'Product'=> $isi['IDProduct'],
                'Rubber'=> $isi['IDKaret'],
                'Qty'	=> $isi['Qty'],
                'Mold'	=> $isi['Mold'],
                'Scrap'	=> $isi['Scrap'],
                'Completion'=> $isi['Completion'],
                'Inject'	=> $isi['Inject'],
                'OverTime'	=> 'N',
                'Carat'=> $KadarSPK,
            ]);
            
            // // $insert_waxinjectorderrubber = waxinjectorderrubber::where('IDM', $IDSPKOInject)->where('Rubber','!=',$isi['IDKaret'])
            // // ->create([
            // //     'IDM' => $IDSPKOInject,
            // //     'Ordinal' => 
            // //     'Rubber' => $isi['IDKaret'],
            // //     'LinkOrd' => $generateID[0]->ID,
            // // ]);
        }
       
// $update_rubber = "UPDATE rubber SET Active = '$Keperluan' WHERE ID = ";
            // $UpdateWaxtreeASucces = FacadesDB::connection('erp')->update($UpdateWaxTreeA);

        $data_return = $this->SetReturn(true, "Update Lilin Cetak Sukses", ['ID'=>$IDwax], null);
        return response()->json($data_return, 200);
    }
}
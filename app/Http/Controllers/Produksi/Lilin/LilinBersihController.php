<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\waxclean;
// use App\Models\tes_laravel\waxcleanitem;


// live
use App\Models\erp\waxclean;
use App\Models\erp\waxcleanitem;


// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class LilinBersihController extends Controller{
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
        $historyRubberout = FacadesDB::connection('erp')->select("SELECT ID FROM waxclean ORDER BY ID DESC LIMIT 15");
        return view('Produksi.Lilin.LilinBersih.index', compact('employee', 'datenow', 'historyRubberout'));
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
        PC.Description Kadar,
        PC.ID IDKadar,
				H.SW AS Productjadi,
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
        JOIN WorkOrder K ON K.ID = WI.Tatakan
				JOIn Workorderitem KI ON KI.IDM = K.ID
				JOIN Product J ON J.ID = KI.Product
				JOIn Product H ON H.ID = J.Model
        JOIN Waxorderitem XI ON XI.IDM = WI.WaxOrder 
        AND XI.Ordinal = WI.WaxOrderOrd 
        AND XI.WorkOrder = WI.Tatakan
        JOIN workscheduleitem WS ON WS.Level2 = XI.IDM AND WS.Level3 = XI.Ordinal AND WS.LinkID = WI.Tatakan AND WS.LinkOrd = KI.Ordinal
        JOIN product P ON P.ID = XI.Product
        LEFT JOIN productcarat PC ON PC.ID = WS.Carat
			
    WHERE
        WI.IDM = $IDSPKOInject
    ORDER BY
        WI.Ordinal
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
        $JumlahKomponen = $request->JumlahKomponen;
        $Catatan = $request->Catatan;
        $items = $request->items;
        
        // Checking Data
        // Check if idWaxInjectOrder null or blank
        if (is_null($IDSPKOInject) or $IDSPKOInject == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan data Lilin Bersih",
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
                "message"=>"Gagal menyimpan data Lilin Bersih",
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
                "message"=>"Gagal menyimpan data Lilin Bersih",
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
                "message"=>"Gagal menyimpan data LiLin Bersih",
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
        FROM waxclean");
   

        $insert_waxClean = waxclean::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName'	=> $username,
            'Remarks'	=> $Catatan,
            'TransDate'	=> $date,
            'Employee'	=> $IDOperator,
            'Total' => $JumlahKomponen,
            'WorkGroup'=> $kelompok,
            'WaxOrder'=> $IDSPKOInject,
            'WorkTime'=> NULL,
        ]);
        
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_waxCleanItem = waxcleanitem::create([
                'IDM' => $generateID[0]->ID,
                'Ordinal' => $IT+1,
                'WorkOrder'	=> $isi['IDWorkOrder'],
                'Product'=> $isi['IDProduct'],
                'Qty'	=> $isi['Qty'],
                'OverTime'	=> 'N',
                'Size' => NULL,
                'Note' => $isi['Keterangan'],
            ]);
           
        }

        $data_return = $this->SetReturn(true, "Save Lilin Bersih Sukses", ['ID'=>$insert_waxClean->ID], null);
        return response()->json($data_return, 200);
    }

    public function Search(Request $request){
        $IDWaxClean_tbWax = $request->keyword;
        // dd($IDWaxORder_tbWax);
        // Cek waxtree if exists
        $waxClean_Header = FacadesDB::connection('erp')
        ->select("SELECT DISTINCT
        A.ID,
        A.WaxOrder as spk,
        PC.Description Kadar,
        PC.ID as IDKadar,
        A.WorkGroup,
        A.Employee,
		A.TransDate AS tanggal,
        A.Total AS JumlahKomponen,
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
        waxclean A
		JOIN waxinjectorder W ON W.ID = A.WaxOrder
        JOIN productcarat PC ON PC.ID = W.Carat
    WHERE A.ID = $IDWaxClean_tbWax
	
    ");
    if (count($waxClean_Header) == 0) {
        $data_return = $this->SetReturn(false, "ID tersebut Tidak Ditemukan di data lilin Bersih", null, null);
        return response()->json($data_return, 404);
    }
    
    $waxClean_Header = $waxClean_Header[0];

        $WaxCleanItem_tabel = FacadesDB::connection('erp')
        ->select("SELECT
        W.SW WorkOrder,
        W.ID IDWorkOrder,
        P.SW AS ItemProduct,
        P.Description,
        P.ID IDProd,
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
        AI.Qty,
				AI.Note AS Keterangan
                FROM
	waxcleanitem AI
	JOIN workorder W ON W.ID = AI.WorkOrder
	JOIN product P ON P.ID = AI.Product
	LEFT JOIN productcarat PC ON PC.ID = W.Carat 
WHERE
      AI.IDM = $IDWaxClean_tbWax
      ");
    // dd($WaxCleanItem_tabel);
        $waxClean_Header->items = $WaxCleanItem_tabel;
        $data_return = $this->SetReturn(true, "Getting data wax and waxcleanitem Success. waxcleanitem found", $waxClean_Header, null);
        return response()->json($data_return, 200);
    }

    public function Update(Request $request){
        // dd($request);
        $IDwax = $request->IDwax;
        // dd($IDwax);
        $IDSPKOInject = $request->IDSPKOInject;
        $KadarSPK = $request->KadarSPK;
        $date = $request->date;
        $IDOperator = $request->IDOperator;
        $kelompok = $request->kelompok;
        $JumlahKomponen = $request->JumlahKomponen;
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

        $insert_waxStone = waxclean::where('ID',$IDwax)->update([
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName'	=> $username,
            'Remarks'	=> $Catatan,
            'TransDate'	=> $date,
            'Employee'	=> $IDOperator,
            'Total' => $JumlahKomponen,
            'WorkGroup'=> $kelompok,
            'WaxOrder'=> $IDSPKOInject,
            'WorkTime'=> NULL,
          
        ]);
        
        $deleteWaxstoneitem = waxcleanitem::where('IDM',$IDwax)->delete();

        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_waxitem = waxcleanitem::create([
                'IDM' => $IDwax,
                'Ordinal' => $IT+1,
                'WorkOrder'	=> $isi['IDWorkOrder'],
                'Product'=> $isi['IDProduct'],
                'Qty'	=> $isi['Qty'],
                'OverTime'	=> 'N',
                'Size' => NULL,
                'Note' => $isi['Keterangan'],
            ]);
        }

        $data_return = $this->SetReturn(true, "Update Lilin StoneCast Sukses", ['ID'=>$IDwax], null);
        return response()->json($data_return, 200);
    }
}
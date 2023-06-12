<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\rubberlocation;
// use App\Models\tes_laravel\rubberregistration;
// use App\Models\tes_laravel\rubberregistrationitem;

// live
use App\Models\erp\rubberlocation;
use App\Models\erp\rubberregistration;
use App\Models\erp\rubberregistrationitem;

// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class RegistrasiKaretController extends Controller{
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
       
        $lemari = FacadesDB::connection("erp")
        ->select("SELECT * FROM masterlemari WHERE Location = 51");

        $laci = FacadesDB::connection("erp")
        ->select("SELECT * FROM masterlaci WHERE Location = 51");
        // history waxstoneusage
        $historyRubberout = FacadesDB::connection('erp')->select("SELECT ID FROM rubberregistration ORDER BY ID DESC");
        
        return view('Produksi.Lilin.RegistrasiKaret.index', compact('historyRubberout','lemari','laci'));
    }

    public function Simpan(Request $request){
        // Get Required Data
       
        $chekitem = $request->items;
        $cekidlokasi = $request->items[0]['IDLocation'];
        // dd($cekidlokasi);
        
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        //get lemari laci kolom baris ID
        $getIDLemari_Laci_Kolom_Baris = FacadesDB::connection("erp")->select("SELECT 
        LemariID, LaciID, BarisID, KolomID FROM Rubberlocation WHERE ID = $cekidlokasi");
        
        $getIDLemari_Laci_Kolom_Baris1 = $getIDLemari_Laci_Kolom_Baris[0];
        // dd($getIDLemari_Laci_Kolom_Baris1->LemariID);
        //generateID urut untuk rubberregistration
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rubberregistration");

        $insert_rubberregistration = rubberregistration::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => $username,
            'TransDate' => date('Y-m-d'),
            'Status' => 0,
            'CreateAt' => NULL,
            'UpdateAt' => NULl,
        ]);
        
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_rubberregistrationitem = rubberregistrationitem::create([
                'IDM' => $generateID[0]->ID,
                'Ordinal' => $IT+1,
                'RubberID' => $isi['IDKaret'],
                'LemariID' => $getIDLemari_Laci_Kolom_Baris1->LemariID,
                'LaciID' => $getIDLemari_Laci_Kolom_Baris1->LaciID,
                'KolomID' => $getIDLemari_Laci_Kolom_Baris1->KolomID,
                'BarisID' => $getIDLemari_Laci_Kolom_Baris1->BarisID,
                'RubberLocationID' => $isi['IDLocation'],
            ]);
            
            $update_rubberlocation = rubberlocation::where('ID', $isi['IDLocation'])->update([
                'RubberID' => $isi['IDKaret'],
                'Update_At' => date('Y-m-d H:i:s'),
            ]);
            
        }

        $data_return = $this->SetReturn(true, "Registrasi Karet Sukses", ['ID'=>$insert_rubberregistration->ID], null);
        return response()->json($data_return, 200);
    }

    public function Update(Request $request){
        // Get Required Data
        $IDRegist = $request->IDRegist;
        $chekitem = $request->items;
        $cekidlokasi = $request->items[0]['IDLocation'];
        dd($IDRegist);
        
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        //get lemari laci kolom baris ID
        $getIDLemari_Laci_Kolom_Baris = FacadesDB::connection("erp")->select("SELECT 
        LemariID, LaciID, BarisID, KolomID FROM Rubberlocation WHERE ID = $cekidlokasi");
        
        $getIDLemari_Laci_Kolom_Baris1 = $getIDLemari_Laci_Kolom_Baris[0];
        // dd($getIDLemari_Laci_Kolom_Baris1->LemariID);
        //generateID urut untuk rubberregistration
        $generateID = FacadesDB::connection("erp")
        ->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM rubberregistration");

        $insert_rubberregistration = rubberregistration::create([
            'ID' => $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => $username,
            'TransDate' => date('Y-m-d'),
            'Status' => 0,
            'CreateAt' => NULL,
            'UpdateAt' => NULl,
        ]);
        
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_rubberregistrationitem = rubberregistrationitem::create([
                'IDM' => $generateID[0]->ID,
                'Ordinal' => $IT+1,
                'RubberID' => $isi['IDKaret'],
                'LemariID' => $getIDLemari_Laci_Kolom_Baris1->LemariID,
                'LaciID' => $getIDLemari_Laci_Kolom_Baris1->LaciID,
                'KolomID' => $getIDLemari_Laci_Kolom_Baris1->KolomID,
                'BarisID' => $getIDLemari_Laci_Kolom_Baris1->BarisID,
                'RubberLocationID' => $isi['IDLocation'],
            ]);
            
            $update_rubberlocation = rubberlocation::where('ID', $isi['IDLocation'])->update([
                'RubberID' => $isi['IDKaret'],
                'Update_At' => date('Y-m-d H:i:s'),
            ]);
            
        }

        $data_return = $this->SetReturn(true, "Registrasi Karet Sukses", ['ID'=>$insert_rubberregistration->ID], null);
        return response()->json($data_return, 200);
    }


    public function Print(Request $request){
        // Get idwaxstoneusage
        $idRubberregis = $request->idRubberregistration;
        // Get Header
        $RubberregistrationHeader = FacadesDB::connection('erp')
        ->select("SELECT
        RG.UserName,
        RG.ID IDrubberregis,
				RG.EntryDate
    FROM
        rubberregistration RG
    WHERE
        RG.ID = $idRubberregis
    
    ");
        if (count($RubberregistrationHeader) == 0) {
            abort(404);
        }
        $RubberregistrationHeader = $RubberregistrationHeader[0];
     
        // Get Items
        $Rubberregistrationdata = FacadesDB::connection('erp')
        ->select("SELECT
        P.SW ItemProduct,
        P.Description,
    CASE
            
            WHEN PC.Description IS NOT NULL THEN
            PC.Description ELSE '? ? ?' 
        END Kadar,
			R.ID,	
    CASE
            
            WHEN RL.ID IS NULL THEN
            'Karet Belum Di Registrasi' ELSE CONCAT( ML.SW, '   ', MC.SW, '   ', MK.SW, '   ', MB.SW ) 
        END Lokasi,
        ML.SW ML,
        MC.SW MC,
        MK.SW MK,
        MB.SW MB,
        CASE
		
		WHEN X.SW = 'Yusak H' THEN
		'YS' 
		WHEN X.SW = 'Mud' THEN
		'MZ' 
		WHEN X.SW = 'eko u' THEN
		'PE' 
		WHEN X.SW = 'Fatkhur' THEN
		'FT' 
	END AS CodeOp,
CASE
		
		WHEN R.STATUS = '8' THEN
		'BR' 
		WHEN R.STATUS = '9' THEN
		'UL' 
		WHEN R.STATUS = '16' THEN
		'TB' 
		WHEN R.STATUS = 'Baru' THEN
		'BR' 
		WHEN R.STATUS = 'Ulang' THEN
		'UL' 
		WHEN R.STATUS = 'Tambah' THEN
		'TB' 
		WHEN R.STATUS = 't' THEN
		'TB' 
		WHEN R.STATUS = '36' THEN
		'UL' ELSE R.STATUS 
	END st 
    FROM
        rubberregistrationitem GI 
        JOIN rubber R ON R.ID = GI.RubberID
        JOIN employee X ON X.ID = R.Employee
        LEFT JOIN productcarat PC ON PC.ID = R.Carat
        LEFT JOIN rubberlocation RL ON R.ID = RL.RubberID
        JOIN product P ON P.ID = R.Product
        JOIN masterlemari ML ON RL.LemariID = ML.ID
        JOIN masterlaci MC ON RL.LaciID = MC.ID
        JOIN masterkolom MK ON RL.KolomID = MK.ID
        JOIN masterbaris MB ON RL.BarisID = MB.ID 
    WHERE
        GI.IDM = $idRubberregis");

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        return view('Produksi.Lilin.RegistrasiKaret.cetak',compact('username','RubberregistrationHeader','Rubberregistrationdata','date','datenow','timenow'));
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
            ML.ID ML,
            MC.ID MC,
            MK.ID MK,
            MB.ID MB,
            CASE WHEN 
            PC.Description IS NOT NULL THEN PC.Description ELSE '? ? ?' END Kadar,
            CASE WHEN 
            PC.ID IS NOT NULL THEN PC.ID ELSE NULL END IDKadar,
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
            R.ID = $IDKaret
            AND RL.ID IS NULL");

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
            $ML = $datas->ML;
            $MC = $datas->MC;
            $MK = $datas->MK;
            $MB = $datas->MB;

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
                'Lokasi' => $Lokasi,
                'ML' => $ML,
                'MC' => $MC,
                'MK' => $MK,
                'MB' => $MB
            );
                // dd($dataReturn);
        }else{
            $data_Return = array('rowcount' => $rowcount);
        }
        return response()->json($data_Return, 200);
    }

    public function Search(Request $request){
        $keyword = $request->keyword;
        // Cek waxtree if exists
        $Rubberregistration = FacadesDB::connection('erp')
        ->select("SELECT
        G.UserName,
        G.EntryDate
    FROM
        rubberregistration G
    WHERE
        G.ID = $keyword");

        if (count($Rubberregistration) == 0) {
            $data_return = $this->SetReturn(false, "gagal mencari data regisrasi karet, id yang dimasukkan tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }
        $Rubberregistration = $Rubberregistration[0];
       

        $RubberregistrationTabel = FacadesDB::connection('erp')
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
            ML.ID ML,
            MC.ID MC,
            MK.ID MK,
            MB.ID MB,
        CASE
                
                WHEN PC.Description IS NOT NULL THEN
                PC.Description ELSE '? ? ?' 
            END Kadar,
        CASE
                
                WHEN PC.ID IS NOT NULL THEN
                PC.ID ELSE NULL 
            END IDKadar,
            R.ID IDKaret,
        CASE
                
                WHEN RL.ID IS NULL THEN
                'Karet Belum Di Registrasi' ELSE CONCAT( ML.SW, ' ', MC.SW, ' ', MK.SW, ' ', MB.SW ) 
            END Lokasi,
            GI.RubberLocationID
        FROM
            rubberregistrationitem GI 
            JOIN rubber R ON R.ID = GI.RubberID
            LEFT JOIN productcarat PC ON PC.ID = R.Carat
            LEFT JOIN rubberlocation RL ON R.ID = RL.RubberID
            JOIN product P ON P.ID = R.Product
            LEFT JOIN masterlemari ML ON RL.LemariID = ML.ID
            LEFT JOIN masterlaci MC ON RL.LaciID = MC.ID
            LEFT JOIN masterkolom MK ON RL.KolomID = MK.ID
            LEFT JOIN masterbaris MB ON RL.BarisID = MB.ID 
        WHERE
            GI.IDM = $keyword");

            // dd($RubberregistrationTabel);

        $Rubberregistration->items = $RubberregistrationTabel;
        $data_return = $this->SetReturn(true, "Getting dataregistrasi Success. data found", $Rubberregistration, null);
        return response()->json($data_return, 200);
    }

    public function lemari($lemari,$laci)
    {
        
        $kolom1 = FacadesDB::connection("erp")->select("SELECT
        CASE
                
                WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 1
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");
// dd($kolom1);
        $kolom2 = FacadesDB::connection("erp")->select("SELECT
      CASE
                
                WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 2
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom3 = FacadesDB::connection("erp")->select("SELECT
       CASE
                
       WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 3
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom4 = FacadesDB::connection("erp")->select("SELECT
         CASE
                
         WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 4
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom5 = FacadesDB::connection("erp")->select("SELECT
       CASE
                
       WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 5
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom6 = FacadesDB::connection("erp")->select("SELECT
       CASE
                
       WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 6
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom7 = FacadesDB::connection("erp")->select("SELECT
         CASE
                
         WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 7
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom8 = FacadesDB::connection("erp")->select("SELECT
      CASE
                
      WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
                rubberlocation A
                JOIN masterlemari B ON A.LemariID = B.ID
                JOIN masterlaci C ON A.LaciID = C.ID
                JOIN masterkolom D ON A.KolomID = D.ID
                JOIN masterbaris E ON A.BarisID = E.ID 
            WHERE
                A.LemariID = $lemari
                AND A.LaciID = $laci 
                AND A.KolomID = 8
            ORDER BY
                A.LemariID,
                A.LaciID,
                A.BarisID");

        $kolom9 = FacadesDB::connection("erp")->select("SELECT
      CASE
      WHEN
                    A.RubberID IS NULL THEN
                        CONCAT( B.SW, ' ', C.SW, ' ', D.SW, ' ', E.SW ) ELSE 'Terisi' 
                    END datamu,
                CONCAT( B.ID, '*', C.ID, '*', D.ID, '*', E.ID ) dataku,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'a' ELSE 'b' 
                END AS Available,
                CASE
                    WHEN A.RubberID IS NULL THEN
                    'btn btn-dark a' ELSE 'btn btn-info b' 
                END AS ClassButton,
                A.ID 
            FROM
            rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
        WHERE
            A.LemariID = $lemari
            AND A.LaciID = $laci 
            AND A.KolomID = 9 
        ORDER BY
            A.LemariID,
            A.LaciID,
            A.BarisID");

      
        return view('Produksi.Lilin.RegistrasiKaret.lemari',compact('kolom1','kolom2','kolom3','kolom4','kolom5','kolom6','kolom7','kolom8','kolom9'));
    }

    public function pilihlokasi($lokasi){
        // dd($lokasi);
        $carilokasi= FacadesDB::connection("erp")->select("SELECT 
        A.ID, CONCAT(B.SW,' ',C.SW,' ',D.SW,' ',E.SW) lokasi from
        rubberlocation A
            JOIN masterlemari B ON A.LemariID = B.ID
            JOIN masterlaci C ON A.LaciID = C.ID
            JOIN masterkolom D ON A.KolomID = D.ID
            JOIN masterbaris E ON A.BarisID = E.ID 
					WHERE A.ID = $lokasi
                    AND A.RubberID IS NULL
        ");
            $rowcount = count($carilokasi);
            if($rowcount > 0 ){
                foreach ($carilokasi as $datas){}
                $dapatlokasi = $datas->lokasi;
                $IDLokasi = $datas->ID;
    
                $data_Return = array(
                    'rowcount' => $rowcount,
                   'dapatlokasi' => $dapatlokasi,
                   'IDLokasi' => $IDLokasi
                );
            // dd($data_Return);
            }else{
                $data_Return = array ('rowcount' => $rowcount);
            }
            return response()->json($data_Return, 200);
    }

    public function save(Request $request){
        
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

        $data_return = $this->SetReturn(true, "Registrasi karet berhasil", ['ID'=>$insert_waxClean->ID], null);
        return response()->json($data_return, 200);
    }
}
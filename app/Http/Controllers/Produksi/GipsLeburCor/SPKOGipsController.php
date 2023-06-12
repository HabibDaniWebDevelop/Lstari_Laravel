<?php

namespace app\Http\Controllers\Produksi\GipsLeburCor;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\GipsOrder;
// use App\Models\tes_laravel\GipsOrderitem;
// use App\Models\tes_laravel\waxtree;

// live
use App\Models\erp\GipsOrder;
use App\Models\erp\GipsOrderitem;
use App\Models\erp\waxtree;

// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class SPKOGipsController extends Controller{
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
        $historyGips = FacadesDB::connection('erp')->select("SELECT ID FROM GipsOrder ORDER BY EntryDate DESC LIMIT 15");
        return view('Produksi.GipsLeburCor.SPKOGips.index', compact('employee', 'datenow', 'historyGips'));
    }

    public function DafatarPohon($WorkOrderOO){
        // Getting WaxInjectOrder
        $headdaftarpohon = FacadesDB::connection('erp')
        ->select("SELECT ID
            FROM 
                employee 
            WHERE 
                ID = 1893
        ");
        
        if (count($headdaftarpohon) == 0) {
            return response()->json($data_return, 404);
        }
        
        $headdaftarpohon = $headdaftarpohon[0];
        //generate item 
        $datadaftarpohon = FacadesDB::connection('erp')
        ->select("SELECT
        W.ID IDWaxtree,
        W.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        X.Description TreeSize,
        C.Description Carat,
        IFNULL ( W.Weight, 0 ) Weight,
        IFNULL ( W.WeightStone, 0 ) WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            IF
	( W.Priority = 'Y', 1, 9 ) Priority,
	IF
	( W.Priority = 'Y', 'Checked', '' ) Checked,
    IF
	( W.Priority = 'Y', 'table-primary', '' ) style,
    CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IF
	( G.Ordinal IS NOT NULL, 'Y', W.Priority ) status,
	IFNULL ( W.WeightFG, 0 ) WeightFG 
    FROM
        WaxTree W
        JOIN RubberPlate R ON W.SW = R.ID
        JOIN ShortText X ON W.TreeSize = X.ID
        JOIN ProductCarat C ON W.Carat = C.ID
        LEFT JOIN GipsOrderItem G ON G.IDM = 0 
        AND G.WaxTree = W.ID 
    WHERE
        W.Active = 'A' 
        AND W.TransDate >= Date_Add( CurDate(), INTERVAL - 150 DAY ) 
    AND
    IF
        ( W.Carat = 15, 0, 1 ) = 1 
        AND LEFT(W.WorkOrder,1) $WorkOrderOO 'O'
    ORDER BY
        Priority,
        C.Carat,
        C.Model,
    IF
        ( W.WeightStone = 0, 0, 1 ),
        IDWaxtree
        ");
// dd($datadaftarpohon);
        // Check Item 
        if (count($datadaftarpohon) == 0) {
            $data_return = $this->SetReturn(false, "data tidak ditemukan, perhatikan jenis SPK yang dipilih harap coba kembali.", null, null);
            return response()->json($data_return, 400);
        }
        $headdaftarpohon->items = $datadaftarpohon;

        $data_return = $this->SetReturn(true, "Getting WaxInjectOrder Item Success", $headdaftarpohon, null);
        return response()->json($data_return, 200);
    }

    public function DafatarPohonPerak($WorkOrderOO){
        // Getting WaxInjectOrder
        $headdaftarpohon = FacadesDB::connection('erp')
        ->select("SELECT ID
            FROM 
                employee 
            WHERE 
                ID = 1893
        ");
        
        if (count($headdaftarpohon) == 0) {
            return response()->json($data_return, 404);
        }
        
        $headdaftarpohon = $headdaftarpohon[0];
        //generate item 
        $datadaftarpohon = FacadesDB::connection('erp')
        ->select("SELECT
        W.ID IDWaxtree,
        W.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        X.Description TreeSize,
        C.Description Carat,
        IFNULL ( W.Weight, 0 ) Weight,
        IFNULL ( W.WeightStone, 0 ) WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            IF
	( W.Priority = 'Y', 1, 9 ) Priority,
	IF
	( W.Priority = 'Y', 'Checked', '' ) Checked,
    IF
	( W.Priority = 'Y', 'table-primary', '' ) style,
    CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IF
	( G.Ordinal IS NOT NULL, 'Y', W.Priority ) status,
	IFNULL ( W.WeightFG, 0 ) WeightFG 
    FROM
        WaxTree W
        JOIN RubberPlate R ON W.SW = R.ID
        JOIN ShortText X ON W.TreeSize = X.ID
        JOIN ProductCarat C ON W.Carat = C.ID
        LEFT JOIN GipsOrderItem G ON G.IDM = 0 
        AND G.WaxTree = W.ID 
    WHERE
        W.Active = 'A' 
        AND W.TransDate >= Date_Add( CurDate(), INTERVAL - 150 DAY ) 
    AND
    IF
        ( W.Carat = 15, 0, 1 ) = 0 
    ORDER BY
        Priority,
        C.Carat,
        C.Model,
    IF
        ( W.WeightStone = 0, 0, 1 ),
        IDWaxtree
        ");
// dd($datadaftarpohon);
        // Check Item 
        if (count($datadaftarpohon) == 0) {
            $data_return = $this->SetReturn(false, "data tidak ditemukan, perhatikan jenis SPK yang dipilih harap coba kembali.", null, null);
            return response()->json($data_return, 400);
        }
        $headdaftarpohon->items = $datadaftarpohon;

        $data_return = $this->SetReturn(true, "Getting WaxInjectOrder Item Success", $headdaftarpohon, null);
        return response()->json($data_return, 200);
    }

    public function PilihPohon($Pilihpohon,$WorkOrderOO){

        $headpilihpohon = FacadesDB::connection('erp')
        ->select("SELECT ID
            FROM 
                employee 
            WHERE 
                ID = 1893
        ");
        
        if (count($headpilihpohon) == 0) {
            return response()->json($data_return, 404);
        }
        
        $headpilihpohon = $headpilihpohon[0];

        $datapilihpohon = FacadesDB::connection('erp')
        ->select("SELECT
        W.ID IDWaxtree,
        W.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        X.Description TreeSize,
        C.Description Carat,
        IFNULL ( W.Weight, 0 ) Weight,
        IFNULL ( W.WeightStone, 0 ) WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            IF
	( W.Priority = 'Y', 1, 9 ) Priority,
	IF
	( W.Priority = 'Y', 'Checked', '' ) Checked,
    IF
	( W.Priority = 'Y', 'table-primary', '' ) style,
    CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IF
	( G.Ordinal IS NOT NULL, 'Y', W.Priority ) status,
	IFNULL ( W.WeightFG, 0 ) WeightFG 
    FROM
        WaxTree W
        JOIN RubberPlate R ON W.SW = R.ID
        JOIN ShortText X ON W.TreeSize = X.ID
        JOIN ProductCarat C ON W.Carat = C.ID
        LEFT JOIN GipsOrderItem G ON G.IDM = 0 
        AND G.WaxTree = W.ID 
    WHERE
        W.Active = 'A' 
        AND W.TransDate >= Date_Add( CurDate(), INTERVAL - 150 DAY ) 
        AND W.ID IN ($Pilihpohon)
    ORDER BY
        Priority,
        C.Carat,
        C.Model,
    IF
        ( W.WeightStone = 0, 0, 1 ),
        IDWaxtree
        ");
// dd($datadaftarpohon);

        // Check Item 
        if (count($datapilihpohon) == 0) {
            $data_return = $this->SetReturn(false, "data tidak ditemukan, Tidak ada data yang di centang.", null, null);
            return response()->json($data_return, 400);
        }
        $headpilihpohon->items2 = $datapilihpohon;

        $data_return = $this->SetReturn(true, "Getting data waxtree Success", $headpilihpohon, null);
        return response()->json($data_return, 200);
    }
    
    public function Simpan(Request $request){
        // Get Required Data

        // Check if date null or blank
        if (is_null($request->date) or $request->date == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal menyimpan gips order tanggal kosng",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        // $getlastID = FacadesDB::connection('erp')
        // ->select("SELECT CASE WHEN MAX( Last ) IS NULL THEN '1' ELSE MAX( Last )+1 END AS ID
        //         FROM lastid 
		// 		WHERE ID = 116");
        
        // $idlast = $getlastID[0]->ID;
        // // dd($idlast);

        // $update_lastidsoneusage =FacadesDB::connection('erp')->select("UPDATE Lastid SET Last = $idlast WHERE ID = 116");
   
        if($request->OOO == '='){
            $GenerateIDSPK = FacadesDB::connection('erp')
            ->select("SELECT
           CASE
                
                WHEN
                    MAX( SWOrdinal ) IS NULL THEN
                        '1' ELSE MAX( SWOrdinal )+ 1 
                        END AS ID,
                    DATE_FORMAT( CURDATE(), '%y' ) AS tahun,
                    LPad( MONTH ( CurDate()), 2, '0' ) AS bulan,
                    CONCAT(
                        DATE_FORMAT( CURDATE(), '%y' ),
                        '',
                        LPad( MONTH ( CurDate()), 2, '0' ),
                    LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+ 1 END, 4, '0' )) Counter1 
                FROM
                    GipsOrder 
                WHERE
                    SWYear = DATE_FORMAT( CURDATE(), '%y' ) 
                AND SWMonth = MONTH (
                CurDate())
            ");
        }else{
            $GenerateIDSPK = FacadesDB::connection('erp')
            ->select("SELECT
            CASE
                
                WHEN
                    MAX( SWOrdinal ) IS NULL THEN
                        '1' ELSE MAX( SWOrdinal )+ 1 
                        END AS ID,
                        DATE_FORMAT( CURDATE(), '%y' ) +50 AS tahun,
                    LPad( MONTH ( CurDate()), 2, '0' ) AS bulan,
                    CONCAT(
                        DATE_FORMAT( CURDATE(), '%y' ) +50,
                        '',
                        LPad( MONTH ( CurDate()), 2, '0' ),
                    LPad( CASE WHEN MAX( SWOrdinal ) IS NULL THEN '1' ELSE MAX( SWOrdinal )+ 1 END, 4, '0' )) Counter1 
                FROM
                GipsOrder  
                WHERE
                    SWYear = DATE_FORMAT( CURDATE(), '%y' ) +50
                AND SWMonth = MONTH (
                CurDate())
            ");
        }

        // dd($GenerateIDSPK);
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        FacadesDB::connection('erp')->beginTransaction();
        try {
            $insert_GipsOrder = GipsOrder::create([
                'ID' => $GenerateIDSPK[0]->Counter1,
                'EntryDate' => date('Y-m-d H:i:s'),
                'UserName' => $username,
                'Remarks' => $request->Catatan,
                'TransDate' => $request->date,
                'Active' => 'A',
                // PostDate => 
                'SWYear' => $GenerateIDSPK[0]->tahun,
                'SWMonth' => $GenerateIDSPK[0]->bulan,
                'SWOrdinal' => $GenerateIDSPK[0]->ID,
            ]);
            // dd($insert_GipsOrder);
            $k = 0;
            foreach ($request->items as $IT => $isi) {
                $k++;
                $insert_GipsOrderItem = GipsOrderItem::create([
                    'IDM' => $insert_GipsOrder->ID,
                    'Ordinal' => $IT+1,
                    'WaxTree' => $isi['IDWaxtree'],
                ]);
                // $update_materialrequest = waxtree::where('ID', $isi['IDWaxtree'])->update([
                //     'Active' => 'G',
                // ]);
            }
            // dd($insert_GipsOrderItem);
        

            // $data_return = $this->SetReturn(true, "Save GipsOrder Sukses", ['ID'=>$insert_GipsOrder->ID], null);
            // return response()->json($data_return, 200);
        FacadesDB::connection('erp')->commit();
        $data_return = $this->SetReturn(true, "Save GipsOrder Sukses", ['ID'=>$insert_GipsOrder->ID], null);
        return response()->json($data_return, 200);

    } catch (Exception $e) {
        FacadesDB::connection('erp')->rollBack();
        $data_return = [
            "success"=>false,
            "message"=>"Gagal menyimpan gips order ",
            "data"=>null,
            "error"=>[
            "date"=>"Parameters can't be null or blank"
            ]
        ];
        return response()->json($json_return,500);
    }
    }

    public function Search(Request $request){
       
        // dd($request->keyword);
        // Cek waxtree if exists
        $Gips_Header = FacadesDB::connection('erp')
        ->select("SELECT
        A.ID,
        A.EntryDate,
        A.UserName,
        A.Active,
    IF
        ( A.Active = 'P', 'POSTED', '' ) AS Posting,
        A.TransDate,
        A.Remarks,
        IF (LEFT (W.WorkOrder,1) = 'O','=','!=') as OOO
    FROM
        gipsorder AS A
        JOIN gipsorderitem AS B ON A.ID = B.IDM
        JOIN waxtree AS W ON W.ID = B.WaxTree
    WHERE
        A.ID = $request->keyword
        LIMIT 1 ");

    if (count($Gips_Header) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOGips tersebut Tidak Ditemukan di data Gips Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Gips_Header = $Gips_Header[0];
    //    dd($Gips_Header);
        // IDWaxInjectOrder
        $IDGipsOrder = $Gips_Header->ID;

        $Gips_tabel = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
		IFNULL ( W.WeightFG, 0 ) WeightFG 
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
			W.WeightFG,
            CASE
                WHEN C.SW = '6K' THEN
                '#0000FF' 
                WHEN C.SW = '8K' THEN
                '#00FF00' 
                WHEN C.SW = '8K.' THEN
                '#CFB370' 
                WHEN C.SW = '10K' THEN
                '#FFFF00' 
                WHEN C.SW = '16K' THEN
                '#FF0000' 
                WHEN C.SW = '17K' THEN
                '#FF6E01' 
                WHEN C.SW = '17K.' THEN
                '#FF00FF' 
                WHEN C.SW = '19K' THEN
                '#5F2987' 
                WHEN C.SW = '20K' THEN
                '#FFC0CB'
                ELSE '#808080'
            END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
    WHERE
        T.IDM =  $IDGipsOrder 
    ORDER BY
        T.Ordinal
      ");
    // dd($Gips_tabel);
        $Gips_Header->items2 = $Gips_tabel;
        $data_return = $this->SetReturn(true, "Getting data GipsOrder and GipsOrderitem Success.data found", $Gips_Header, null);
        return response()->json($data_return, 200);
    }

    public function posting(Request $request){
        
        $IDSPKOGips = $request->IDSPKOGips;
        // dd($IDSPKOGips);

        if (is_null($IDSPKOGips) or $IDSPKOGips == "") {
            $data_return = $this->SetReturn(false, "IDSPKGips tidakboleh kosong", null, null);
            return response()->json($data_return, 400);
        }
        $findGipsOrder = GipsOrder::where('ID',$IDSPKOGips)->first();
        if (is_null($findGipsOrder)) {
            $data_return = $this->SetReturn(false, "SPKOGips Tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }

        if ($findGipsOrder->Active != 'A') {
            $data_return = $this->SetReturn(false, "SPKO ini Sudah Pernah di Posting", null, null);
            return response()->json($data_return, 400);
        }

        // $getwaxtree = FacadesDB::connection('erp')->select("SELECT waxtree FROM GipsOrderItem WHERE IDM = $IDSPKOGips");
        // dd($getwaxtree);

        
        $update_waxtree = "UPDATE Waxtree A, (SELECT DISTINCT
                    J.ID,
                    I.WaxTree 
                FROM
                    GipsOrder G
                    JOIN GipsOrderItem I ON G.ID = I.IDM
                    JOIN WaxTree J ON I.WaxTree = J.ID
                WHERE
                    G.ID = $IDSPKOGips) AS B SET A.Active = 'G' 
                    WHERE A.ID = B.Waxtree";
        $update_waxtreeSucces = FacadesDB::connection('erp')->update($update_waxtree);


        // $updatewaxtree = "UPDATE waxtree (select"
        
        $updateworkorder = "UPDATE WorkOrder O, (Select Distinct J.WorkOrder, G.TransDate
                                  From GipsOrder G
                                    Join GipsOrderItem I On G.ID = I.IDM
                                    Join WaxTreeItem J On I.WaxTree = J.IDM
                                  Where G.ID = $IDSPKOGips) W
            Set O.GipsOrder = W.TransDate
            Where O.ID = W.WorkOrder";
        $updateworkorderSucces = FacadesDB::connection('erp')->update($updateworkorder);

        $dateupdate = date('Y-m-d H:i:s');
        $updategipsorder = "UPDATE GipsOrder SET Active = 'P' , PostDate = '$dateupdate' WHERE ID = $IDSPKOGips";
        $updategipsorderSucces = FacadesDB::connection('erp')->update($updategipsorder);

        if ($updategipsorderSucces > 0) {
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

    public function Update(Request $request){

        // dd($request);
        $SPKGips = $request->IDSPKOGips;
        $Catatan = $request->Catatan;
        // dd($SPKGips);

        // Check if date null or blank
        if (is_null($request->date) or $request->date == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal Update data Gips order from tanggal kosong",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        $update_GipsOrder = GipsOrder::where('ID',$SPKGips)->update([
            'UserName' => $username,
            'Remarks' => $request->Catatan,
            'TransDate' => $request->date,
        ]);
        
        $deleteGipsOrderItem = GipsOrderItem::where('IDM',$SPKGips)->delete();

        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_GipsOrderItem = GipsOrderItem::create([
                'IDM' => $SPKGips,
                'Ordinal' => $IT+1,
                'WaxTree' => $isi['IDWaxtree'],
            ]);
            
        }

        $data_return = $this->SetReturn(true, "Update Gips Order Sukses", ['ID'=>$SPKGips], null);
        return response()->json($data_return, 200);
    }

    public function cetak(Request $request){
        $IDSPKOGips = $request->IDSPKOGips;
        // dd($IDSPKOGips);
        
        $Gips_Headercetak = FacadesDB::connection('erp')
        ->select("SELECT
        A.ID,
        A.EntryDate,
        A.UserName,
        A.Active,
    IF
        ( A.Active = 'P', 'POSTED', '' ) AS Posting,
        A.TransDate,
        A.Remarks,
        IF (LEFT (W.WorkOrder,1) = 'O','=','!=') as OOO
    FROM
        gipsorder AS A
        JOIN gipsorderitem AS B ON A.ID = B.IDM
        JOIN waxtree AS W ON W.ID = B.WaxTree
    WHERE
        A.ID = $IDSPKOGips
        LIMIT 1 ");

    if (count($Gips_Headercetak) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOGips tersebut Tidak Ditemukan di data Gips Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Gips_Headercetak1 = $Gips_Headercetak[0];
    //    dd($Gips_Header);
        // IDWaxInjectOrder
        $IDGipsOrder = $Gips_Headercetak1->ID;

        $Gips_tabelcetak06k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
		IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
			W.WeightFG,
            CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
		        C.SW = '6K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
    WHERE
        T.IDM = $IDGipsOrder
    ORDER BY
        W.urutan,
        W.Plate
      ");

        $Gips_tabelcetak08k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '8K' THEN
            '#02ba1e' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '8K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");

        $Gips_tabelcetak16k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            
            WHEN C.SW = '16K' THEN
            '#ff1a1a' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '16K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetak17k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '17K' THEN
            '#e65507' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '17K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetak17kp = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '17K.' THEN
            '#d909cb' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '17K.'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetak20k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '20K' THEN
            '#ffcba4' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '20K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetak10k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '10K' THEN
            '#f5fc0f' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '10K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetak8kp = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '8K.' THEN
            '#ebb52d' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '8K.'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetak19k = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '19K' THEN
            '#4908a3' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = '19K'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");
        $Gips_tabelcetakperak = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG,
        W.urutan
    FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            IF(W.WeightStone = 0, 1, 5) as urutan,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            WHEN C.SW = '19K' THEN
            '#4908a3' ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID
            WHERE
                C.SW = 'Perak'
        GROUP BY
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.urutan,
        W.Plate
        ");

$Gips_tabelcetakallkadar = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");

$date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$datenow = $date->format("d/m/Y");
$timenow = $date->format("H:i");

$username = Auth::user()->name;
FacadesDB::beginTransaction();

return view('Produksi.GipsLeburCor.SPKOGips.cetak',
compact('username','Gips_Headercetak',
'Gips_tabelcetak06k',
'Gips_tabelcetak08k',
'Gips_tabelcetak16k',
'Gips_tabelcetak17k',
'Gips_tabelcetak17kp',
'Gips_tabelcetak19k',
'Gips_tabelcetak8kp',
'Gips_tabelcetak20k',
'Gips_tabelcetak10k',
'Gips_tabelcetakperak',
'Gips_tabelcetakallkadar','date','datenow','timenow'));
    }

    public function cetakrekap(Request $request){
        $IDSPKOGips = $request->IDSPKOGips;
        // dd($IDSPKOGips);
        
        $Gips_Headercetak = FacadesDB::connection('erp')
        ->select("SELECT
        A.ID,
        A.EntryDate,
        A.UserName,
        A.Active,
    IF
        ( A.Active = 'P', 'POSTED', '' ) AS Posting,
        A.TransDate,
        A.Remarks,
        IF (LEFT (W.WorkOrder,1) = 'O','=','!=') as OOO
    FROM
        gipsorder AS A
        JOIN gipsorderitem AS B ON A.ID = B.IDM
        JOIN waxtree AS W ON W.ID = B.WaxTree
    WHERE
        A.ID = $IDSPKOGips
        LIMIT 1 ");

    if (count($Gips_Headercetak) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOGips tersebut Tidak Ditemukan di data Gips Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Gips_Headercetak1 = $Gips_Headercetak[0];
    //    dd($Gips_Header);
        // IDWaxInjectOrder
        $IDGipsOrder = $Gips_Headercetak1->ID;

    $Gips_tabelcetak06ksedang = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
		IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
			W.WeightFG,
            CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
		        C.SW = '6K'
				AND LEFT(R.SW,1) = 'B'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");

        $Gips_tabelcetak06kbesar = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.HexColor,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            CASE
            
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            ELSE '#000' 
        END HexColor,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '6K'
                AND LEFT(R.SW,1) = 'C'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");
        $Gips_tabelcetak06k = FacadesDB::connection('erp')
        ->select("SELECT
        COUNT(W.ID) AS jumlah
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '6K'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ");



        $Gips_tabelcetak16ksedang = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '16K'
                AND LEFT(R.SW,1) = 'B'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");

        $Gips_tabelcetak16kbesar = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '16K'
                AND LEFT(R.SW,1) = 'C'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");
        $Gips_tabelcetak16k = FacadesDB::connection('erp')
        ->select("SELECT
        COUNT(W.ID) AS jumlah
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '16K'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ");



        $Gips_tabelcetak08ksedang = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '8K'
                AND LEFT(R.SW,1) = 'B'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");

        $Gips_tabelcetak08kbesar = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.WaxTree,
        W.TreeDate,
        W.Plate,
        W.TreeSize,
        W.Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
        IFNULL ( W.WeightFG, 0 ) WeightFG 
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID,
            W.TransDate TreeDate,
            R.SW Plate,
            X.Description TreeSize,
            C.Description Carat,
            W.Weight,
            W.WeightStone,
            W.WorkOrder,
            W.WeightFG,
            W.Qty 
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '8K'
                AND LEFT(R.SW,1) = 'C'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ORDER BY
        W.Plate
        ");
        $Gips_tabelcetak08k = FacadesDB::connection('erp')
        ->select("SELECT
        COUNT(W.ID) AS jumlah
        FROM
        GipsOrderItem T
        JOIN (
        SELECT
            W.ID
        FROM
            WaxTree W
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
            WHERE
                C.SW = '8K'
        GROUP BY	
            W.ID 
        ) W ON T.WaxTree = W.ID 
        WHERE
        T.IDM = $IDGipsOrder
        ");


$Gips_tabelcetak17ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak17kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak17k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");



$Gips_tabelcetak17kpsedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K.'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak17kpbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K.'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak17kp = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '17K.'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");




$Gips_tabelcetak20ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '20K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak20kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '20K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak20k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '20K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");



$Gips_tabelcetak10ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '10K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak10kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '10K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak10k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '10K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");
    

$Gips_tabelcetak08kpsedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '8K.'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak08kpbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '8K.'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak08kp = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '8K.'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");


$Gips_tabelcetak19ksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '19K'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetak19kbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '19K'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetak19k = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = '19K'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");


$Gips_tabelcetakperaksedang = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = 'Perak'
        AND LEFT(R.SW,1) = 'B'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");

$Gips_tabelcetakperakbesar = FacadesDB::connection('erp')
->select("SELECT
T.IDM,
T.Ordinal,
T.WaxTree,
W.TreeDate,
W.Plate,
W.TreeSize,
W.Carat,
W.Weight,
W.WeightStone,
W.Qty,
W.WorkOrder,
CASE WHEN LEFT (W.WorkOrder,1) = 'O' THEN '#913030' ELSE '#000' END WorkText,
IFNULL ( W.WeightFG, 0 ) WeightFG 
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID,
    W.TransDate TreeDate,
    R.SW Plate,
    X.Description TreeSize,
    C.Description Carat,
    W.Weight,
    W.WeightStone,
    W.WorkOrder,
    W.WeightFG,
    W.Qty 
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = 'Perak'
        AND LEFT(R.SW,1) = 'C'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
ORDER BY
W.Plate
");
$Gips_tabelcetakperak = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        C.SW = 'Perak'
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");


$Gips_tabelcetakallkadar = FacadesDB::connection('erp')
->select("SELECT
COUNT(W.ID) AS jumlah
FROM
GipsOrderItem T
JOIN (
SELECT
    W.ID
FROM
    WaxTree W
    JOIN RubberPlate R ON W.SW = R.ID
    JOIN ShortText X ON W.TreeSize = X.ID
    JOIN ProductCarat C ON W.Carat = C.ID 
GROUP BY	
    W.ID 
) W ON T.WaxTree = W.ID 
WHERE
T.IDM = $IDGipsOrder
");

$date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$datenow = $date->format("d/m/Y");
$timenow = $date->format("H:i");

$username = Auth::user()->name;
FacadesDB::beginTransaction();

return view('Produksi.GipsLeburCor.SPKOGips.cetakrekap',
compact('username','Gips_Headercetak',
'Gips_tabelcetak06ksedang',
'Gips_tabelcetak06kbesar',
'Gips_tabelcetak06k',
'Gips_tabelcetak08ksedang',
'Gips_tabelcetak08kbesar',
'Gips_tabelcetak08k',
'Gips_tabelcetak16ksedang',
'Gips_tabelcetak16kbesar',
'Gips_tabelcetak16k',
'Gips_tabelcetak17ksedang',
'Gips_tabelcetak17kbesar',
'Gips_tabelcetak17k',
'Gips_tabelcetak17kpsedang',
'Gips_tabelcetak17kpbesar',
'Gips_tabelcetak17kp',
'Gips_tabelcetak20ksedang',
'Gips_tabelcetak20kbesar',
'Gips_tabelcetak20k',
'Gips_tabelcetak10ksedang',
'Gips_tabelcetak10kbesar',
'Gips_tabelcetak10k',
'Gips_tabelcetak08kpsedang',
'Gips_tabelcetak08kpbesar',
'Gips_tabelcetak08kp',
'Gips_tabelcetak19ksedang',
'Gips_tabelcetak19kbesar',
'Gips_tabelcetak19k',
'Gips_tabelcetakperaksedang',
'Gips_tabelcetakperakbesar',
'Gips_tabelcetakperak',
'Gips_tabelcetakallkadar',
'date','datenow','timenow'));
    }
}
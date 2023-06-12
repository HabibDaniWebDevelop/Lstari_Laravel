<?php

namespace app\Http\Controllers\Produksi\Lilin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\WaxtreeTransfer;
// use App\Models\tes_laravel\WaxtreeTransferitem;
// use App\Models\tes_laravel\StockSC;
// use App\Models\tes_laravel\waxtree;

// live
use App\Models\erp\WaxtreeTransfer;
use App\Models\erp\WaxtreeTransferitem;
// use App\Models\erp\waxtree;
use App\Models\erp\StockSC;


// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class TransferPohonLilinController extends Controller{
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
        $kadar = FacadesDB::connection("erp")
        ->select("SELECT ID, Description FROM productcarat WHERE ID in(1,3,4,5,6,7,12,13,14,15) ORDER BY Description");
        // history waxstoneusage
        $historyTMPohon = FacadesDB::connection('erp')->select("SELECT ID FROM Waxtreetransfer ORDER BY EntryDate DESC LIMIT 15");
        $historyGips = FacadesDB::connection('erp')->select("SELECT ID FROM Gipsorder WHERE Active = 'P' ORDER BY EntryDate DESC LIMIT 15");
        return view('Produksi.Lilin.TransferPohonLilin.index', compact('historyTMPohon','employee', 'datenow', 'historyGips','kadar'));
    }

    public function DafatarPohon(Request $request){

        $IDSPKGips = $request->IDSPKGips;
        $WorkOOO = $request->WorkOrderOO;
        $Kadar = $request->Kadar;

        // dd($request);
        // Getting WaxInjectOrder
        $headdaftarpohon = FacadesDB::connection('erp')
        ->select("SELECT A.UserName,B.ID FROM gipsorder A JOIN employee B ON A.UserName = B.SW WHERE A.ID = $IDSPKGips");
        
        if (count($headdaftarpohon) == 0) {
            return response()->json($data_return, 404);
        }
        
        $headdaftarpohon = $headdaftarpohon[0];
        //generate item 
        $datadaftarpohon = FacadesDB::connection('erp')
        ->select("SELECT
        W.ID as IDWaxtree,
        W.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        W.SW,
        X.Description TreeSize,
        C.Description Carat,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        IF(LEFT(W.WorkOrder,1) = 'O','#913030','#000') as WorkText,
        C.ID as OID,
        CASE
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            WHEN C.SW = '8K' THEN
            '#02ba1e' 
            WHEN C.SW = '16K' THEN
            '#ff1a1a' 
            WHEN C.SW = '17K' THEN
            '#e65507' 
            WHEN C.SW = '17K.' THEN
            '#d909cb' 
            WHEN C.SW = '20K' THEN
            '#ffcba4' 
            WHEN C.SW = '10K' THEN
            '#f5fc0f' 
            WHEN C.SW = '8K.' THEN
            '#ebb52d'
            WHEN C.SW = '19K' THEN
            '#4908a3' ELSE '#808080'
                END HexColor
    FROM
        GipsOrder G
        JOIN GipsOrderItem I ON G.ID = I.IDM
        JOIN WaxTree W ON I.WaxTree = W.ID 
        AND W.Active = 'G'
        JOIN ProductCarat C ON W.Carat = C.ID 
        AND C.ID = $Kadar
        JOIN RubberPlate R ON W.SW = R.ID
        JOIN ShortText X ON W.TreeSize = X.ID
        JOIN (
        SELECT
            J.IDM,
            Group_Concat(
            DISTINCT
            IF
            ( LEFT ( O.SWPurpose, 1 ) = 'O', 1, 0 )) SWO 
        FROM
            GipsOrderItem J
            JOIN WaxTreeItem I ON J.WaxTree = I.IDM
            JOIN WorkOrder O ON I.WorkOrder = O.ID 
        WHERE
            J.IDM = $IDSPKGips
        GROUP BY
            J.IDM 
        ) Z ON G.ID = Z.IDM 
        AND Z.SWO = $WorkOOO 
    WHERE
        G.ID = $IDSPKGips 
        AND G.Active = 'P' 
    ORDER BY
    IF
        ( W.WeightStone = 0, 0, 1 ),
        R.SW
        ");
        // dd($datadaftarpohon);
    
        $descriptionkadar = FacadesDB::connection('erp')->select("SELECT Description FROM ProductCarat WHERE ID = $Kadar");
        $kdar = $descriptionkadar[0]->Description;

        // Check Item 
        if (count($datadaftarpohon) == 0) {
            $data_return = $this->SetReturn(false, "Tidak ada kadar $kdar didalam IDSPKGips $IDSPKGips", null, null);
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
                '#090cd9' 
                WHEN C.SW = '8K' THEN
                '#02ba1e' 
                WHEN C.SW = '16K' THEN
                '#ff1a1a' 
                WHEN C.SW = '17K' THEN
                '#e65507' 
                WHEN C.SW = '17K.' THEN
                '#d909cb' 
                WHEN C.SW = '20K' THEN
                '#ffcba4' 
                WHEN C.SW = '10K' THEN
                '#f5fc0f' 
                WHEN C.SW = '8K.' THEN
                '#ebb52d' 
                WHEN C.SW = '19K' THEN
                '#4908a3'
                            ELSE '#000'
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

        // dd($request);
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
   
        if($request->OOO == 1){
            $GenerateIDTM = FacadesDB::connection('erp')
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
                    WaxtreeTransfer 
                WHERE
                    SWYear = DATE_FORMAT( CURDATE(), '%y' ) 
                AND SWMonth = MONTH (
                CurDate())
            ");
        }else{
            $GenerateIDTM = FacadesDB::connection('erp')
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
                    WaxtreeTransfer  
                WHERE
                    SWYear = DATE_FORMAT( CURDATE(), '%y' ) +50
                AND SWMonth = MONTH (
                CurDate())
            ");
        }

        // dd($GenerateIDTM);
        $username = Auth::user()->name;
        FacadesDB::beginTransaction();


        $insert_WaxtreeTransfer = WaxtreeTransfer::create([
            'ID'=> $GenerateIDTM[0]->Counter1,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => $username,
            'Remarks'=> $request->Catatan,
            'TransDate' => $request->date,
            'Employee' => $request->Employee,
            'Active' => 'A',
            // PostDate => 
            'SWYear' => $GenerateIDTM[0]->tahun,
            'SWMonth' => $GenerateIDTM[0]->bulan,
            'SWOrdinal' => $GenerateIDTM[0]->ID,
        ]);
        // dd($insert_GipsOrder);
        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_WaxtreeTransferitem = WaxtreeTransferitem::create([
                'IDM' => $insert_WaxtreeTransfer->ID,
                'Ordinal' => $IT+1,
                'WaxTree' => $isi['IDWaxtree'],
            ]);
            // $update_materialrequest = waxtree::where('ID', $isi['IDWaxtree'])->update([
            //     'Active' => 'G',
            // ]);
        }
        // dd($insert_GipsOrderItem);

        $data_return = $this->SetReturn(true, "Save GipsOrder Sukses", ['ID'=>$insert_WaxtreeTransfer->ID], null);
        return response()->json($data_return, 200);
    }

    public function Search(Request $request){
       
        // dd($request->keyword);
        // Cek waxtree if exists
        $year = date('y');
        // dd($year);
        $Waxtreetransfer_Header = FacadesDB::connection('erp')
        ->select("SELECT W.*, E.SW, IF(W.Active = 'P', 'POSTED', '') as Posting, IF(LEFT(W.ID,2) = $year, 1, 0) as OOO, G.Carat, H.Description, C.IDM as SPKGips
        From WaxTreeTransfer W Join Employee E On W.Employee = E.ID 
				JOIN waxtreetransferitem F ON F.IDM = W.ID 
			JOIN Waxtree G ON G.ID = F.WaxTree
            JOIN ProductCarat H ON H.ID = G.Carat
            JOIN GipsorderItem C ON C.Waxtree = F.Waxtree	
        Where W.ID = $request->keyword
        GROUP BY W.ID");
// dd($Waxtreetransfer_Header);
    if (count($Waxtreetransfer_Header) == 0) {
        $data_return = $this->SetReturn(false, "ID SPKOGips tersebut Tidak Ditemukan di data Gips Order", null, null);
        return response()->json($data_return, 404);
    }
    
        $Waxtreetransfer_Header = $Waxtreetransfer_Header[0];
    //    dd($Gips_Header);
        // IDWaxInjectOrder
        $IDWaxtreetransfer = $Waxtreetransfer_Header->ID;

        $Waxtreetransfer_tabel = FacadesDB::connection('erp')
        ->select("SELECT
        T.IDM,
        T.Ordinal,
        T.Waxtree as IDWaxtree,
        W.TransDate TreeDate,
        Cast( R.SW AS CHAR ) Plate,
        X.Description TreeSize,
        Cast( C.Description AS CHAR ) Carat,
        C.ID OID,
        W.Weight,
        W.WeightStone,
        W.Qty,
        W.WorkOrder,
        IF(LEFT(W.WorkOrder,1) = 'O','#913030','#000') as WorkText,
        CASE
                WHEN C.SW = '6K' THEN
                '#090cd9' 
                WHEN C.SW = '8K' THEN
                '#02ba1e' 
                WHEN C.SW = '16K' THEN
                '#ff1a1a' 
                WHEN C.SW = '17K' THEN
                '#e65507' 
                WHEN C.SW = '17K.' THEN
                '#d909cb' 
                WHEN C.SW = '20K' THEN
                '#ffcba4' 
                WHEN C.SW = '10K' THEN
                '#f5fc0f' 
                WHEN C.SW = '8K.' THEN
                '#ebb52d' 
                WHEN C.SW = '19K' THEN
                '#4908a3'
                    ELSE '#808080'
            END HexColor
    FROM
        WaxTreeTransferItem T
        JOIN WaxTree W ON T.WaxTree = W.ID
        JOIN RubberPlate R ON W.SW = R.ID
        JOIN ShortText X ON W.TreeSize = X.ID
        JOIN ProductCarat C ON W.Carat = C.ID 
    WHERE
        T.IDM = $IDWaxtreetransfer 
    ORDER BY
        T.Ordinal
      ");
    // dd($Gips_tabel);
        $Waxtreetransfer_Header->items2 = $Waxtreetransfer_tabel;
        $data_return = $this->SetReturn(true, "Getting data GipsOrder and GipsOrderitem Success.data found", $Waxtreetransfer_Header, null);
        return response()->json($data_return, 200);
    }

    public function posting(Request $request){
        
    //    dd($request);
    $IDTMWaxTree = $request->IDTMWaxTree;
        // dd($IDTMWaxTree);

        if (is_null($IDTMWaxTree) or $IDTMWaxTree == "") {
            $data_return = $this->SetReturn(false, "IDTMwaxtree tidakboleh kosong", null, null);
            return response()->json($data_return, 400);
        }
        $findIDWaxtree = WaxtreeTransfer::where('ID',$IDTMWaxTree)->first();
        if (is_null($findIDWaxtree)) {
            $data_return = $this->SetReturn(false, "dataTM Tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }

        if ($findIDWaxtree->Active != 'A') {
            $data_return = $this->SetReturn(false, "ID TM $IDTMWaxTree ini Sudah Pernah di Posting", null, null);
            return response()->json($data_return, 400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        $getWeightin = FacadesDB::connection('erp')->select("SELECT
            A.IDM,
            C.Carat,
            SUM( C.WeightStoneCalc ) AS Cal
        FROM 
            waxtreetransferitem A
            JOIN gipsorderitem B ON A.WaxTree = B.WaxTree
            JOIN waxtree C ON A.WaxTree = C.ID
        WHERE 
            A.IDM = $IDTMWaxTree");
        // dd($getWeightin);

        $generateID = FacadesDB::connection('erp')->select("SELECT CASE WHEN MAX( ID ) IS NULL THEN '1' ELSE MAX( ID )+1 END AS ID
        FROM stocksc");

        $getweightsaldo = FacadesDB::connection('erp')->select("SELECT WeightSaldo From StockSC
        Where ID In ( Select Max(ID) ID From StockSC Where Carat = $request->Kadar)");

        $insert_StockSC = StockSC::create([
            'ID'=> $generateID[0]->ID,
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => $username,
            'Process'=> 'Transfer',
            'TransDate' => $request->date,
            'LinkID' => $IDTMWaxTree,
            'Carat' => $request->Kadar,
            'WeightIn' => $getWeightin[0]->Cal,
            'WeightOut' => 0,
            'WeightSaldo' => $getweightsaldo[0]->WeightSaldo,
        ]);

        $dateupdate = date('Y-m-d H:i:s');
        $updatewaxtreetransfer = "UPDATE WaxTreeTransfer SET Active = 'P' , PostDate = '$dateupdate' WHERE ID = $IDTMWaxTree";
        $updatewaxtreetransfersucces = FacadesDB::connection('erp')->update($updatewaxtreetransfer);

        if ($updatewaxtreetransfersucces > 0) {
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
        $IDTMWaxtree = $request->IDTMWaxTree;
        $Catatan = $request->Catatan;
        $Employee = $request->Employee;
        $date = $request->data;
       

        // Check if date null or blank
        if (is_null($request->date) or $request->date == "") {
            $data_return = [
                "success"=>false,
                "message"=>"Gagal Update data TM Pohon from tanggal kosong",
                "data"=>null,
                "error"=>[
                "date"=>"date Parameters can't be null or blank"
                ]
            ];
            return response()->json($data_return,400);
        }

        $username = Auth::user()->name;
        FacadesDB::beginTransaction();

        $update_TMwaxtree = WaxtreeTransfer::where('ID',$IDTMWaxtree)->update([
            'EntryDate' => date('Y-m-d H:i:s'),
            'UserName' => $username,
            'Remarks' => $request->Catatan,
            'Employee' => $request->Employee,
            'TransDate' => $request->date,
        ]);
        
        $deleteTMWaxtree = WaxtreeTransferitem::where('IDM',$IDTMWaxtree)->delete();

        $k = 0;
        foreach ($request->items as $IT => $isi) {
            $k++;
            $insert_TMWaxtreeitem = WaxtreeTransferitem::create([
                'IDM' => $IDTMWaxtree,
                'Ordinal' => $IT+1,
                'WaxTree' => $isi['IDWaxtree'],
            ]);
            
        }

        $data_return = $this->SetReturn(true, "Update data Transfer Pohon Sukses", ['ID'=>$IDTMWaxtree], null);
        return response()->json($data_return, 200);
    }

    public function cetak($IDTMwaxtree){
    //    dd($IDTMwaxtree);
        // dd($IDSPKOGips);
        
        $TMwaxtree_Headercetak = FacadesDB::connection('erp')
        ->select("SELECT W.*, E.SW
        From WaxTreeTransfer W Join Employee E On W.Employee = E.ID
        Where W.ID = $IDTMwaxtree");

    if (count($TMwaxtree_Headercetak) == 0) {
        $data_return = $this->SetReturn(false, "ID Transfer Pohon tersebut Tidak Ditemukan di data Waxtreetransfer", null, null);
        return response()->json($data_return, 404);
    }
    
        $TMwaxtree_Headercetak1 = $TMwaxtree_Headercetak[0];
  
        $IDTMwaxtree = $TMwaxtree_Headercetak1->ID;

        $TMwaxtree_tabelcetak = FacadesDB::connection('erp')
        ->select("SELECT
            T.*,
            W.TransDate TreeDate,
            Cast( R.SW AS CHAR ) Plate,
            X.Description TreeSize,
            Cast( C.Description AS CHAR ) Carat,
            C.ID OID,
            W.Weight,
            W.WeightStone,
            CASE
            WHEN C.SW = '6K' THEN
            '#090cd9' 
            WHEN C.SW = '8K' THEN
            '#02ba1e' 
            WHEN C.SW = '16K' THEN
            '#ff1a1a' 
            WHEN C.SW = '17K' THEN
            '#e65507' 
            WHEN C.SW = '17K.' THEN
            '#d909cb' 
            WHEN C.SW = '20K' THEN
            '#ffcba4' 
            WHEN C.SW = '10K' THEN
            '#f5fc0f' 
            WHEN C.SW = '8K.' THEN
            '#ebb52d' 
            WHEN C.SW = '19K' THEN
            '#4908a3'
            ELSE '#808080'
        END HexColor,
            W.Qty,
            W.WorkOrder 
        FROM
            WaxTreeTransferItem T
            JOIN WaxTree W ON T.WaxTree = W.ID
            JOIN RubberPlate R ON W.SW = R.ID
            JOIN ShortText X ON W.TreeSize = X.ID
            JOIN ProductCarat C ON W.Carat = C.ID 
        WHERE
            T.IDM = $IDTMwaxtree
        ORDER BY
            T.Ordinal
      ");

$date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
$datenow = $date->format("d/m/Y");
$timenow = $date->format("H:i");

$username = Auth::user()->name;
FacadesDB::beginTransaction();

return view('Produksi.Lilin.TransferPohonLilin.cetak',
compact('username','TMwaxtree_Headercetak',
'TMwaxtree_tabelcetak','date','datenow','timenow'));
    }
}
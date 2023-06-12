<?php

namespace App\Http\Controllers\Produksi\PPIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// lokal heri
// use App\Models\tes_laravel\waxtree;


// live
use App\Models\erp\waxtree;


// Public Function
use App\Http\Controllers\Public_Function_Controller;
use \DateTime;
use \DateTimeZone;

class PohonPriority2Controller extends Controller{
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

        $datenow = date('Y-m-d');

        $TebelPriority = FacadesDB::connection('erp')
        ->select("SELECT
        X.ID as IDWaxtree,
        X.Priority,
        X.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        REPLACE(X.WorkOrder, ',' , '<br>') as WorkOrder,
        REPLACE(X.Product,';','<br>') as Product,
    CASE
            
            WHEN PC.SW = '6K' THEN
            '#090cd9' 
            WHEN PC.SW = '8K' THEN
            '#02ba1e' 
            WHEN PC.SW = '16K' THEN
            '#ff1a1a' 
            WHEN PC.SW = '17K' THEN
            '#e65507' 
            WHEN PC.SW = '17K.' THEN
            '#d909cb' 
            WHEN PC.SW = '20K' THEN
            '#ffcba4' 
            WHEN PC.SW = '10K' THEN
            '#f5fc0f' 
            WHEN PC.SW = '8K.' THEN
            '#ebb52d' 
            WHEN PC.SW = '19K' THEN
            '#4908a3' 
            WHEN PC.SW IS NULL THEN
            '#000' 
        END HexColor,
    CASE
            
            WHEN PC.Description IS NOT NULL THEN
            PC.Description ELSE '? ? ?' 
        END Kadar,
        X.Qty,
        SubString( WorkOrder, Locate( '(', WorkOrder ) + 1, Locate( ')', WorkOrder ) - Locate( '(', WorkOrder ) - 1 ) Model,
        IFNULL (X.TotalPoles, 0) TotalPoles,
        IFNULL (X.TotalPatri, 0) TotalPatri,
        IFNULL (X.TotalPUK, 0 ) TotalPUK,
        IFNULL ( X.WeightFG, 0 ) WeightFG,
        CASE WHEN LEFT (X.WorkOrder,1) = 'O' THEN 'bg-primary' ELSE 'bg-light' END WorkColor,
		CASE WHEN LEFT (X.WorkOrder,1) = 'O' THEN 'Tomato' ELSE 'Gray' END WorkText,
        IF(X.Priority = 'R', 'Checked', '') as Checked,
        IF(X.Priority = 'R', 'table-primary', '') as cssterpilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPoles, 0), 0) as TotalPolespilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPatri, 0), 0) as TotalPatripilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPUK, 0 ), 0) as TotalPUKpilih,
				IF(X.Priority = 'R', IFNULL ( X.WeightFG, 0 ), 0) as WeightFGpilih,
				IF(X.Priority = 'R', 1 , 0) as jumlahpohonpilih,
                CASE WHEN SubString( WorkOrder, Locate( '(', WorkOrder ) + 1, Locate( ')', WorkOrder ) - Locate( '(', WorkOrder ) - 1 ) IN ('ATKMNP','ATKAG05','ATKVR','ATKMVR','CBM1','CBM15','CBM2','CWS','CWS1','GOC15','GOC2','GPAC2','GPAC3','GPMA2','GPM2','GPM3','GPMD4','GPMD5','GRKVR','AGSMN','GRKB','AGS1','GSMN')
 THEN 'MediumSeaGreen' ELSE 'Gray' END infomodel
    FROM
        WaxTree X
        JOIN RubberPlate R ON X.SW = R.ID
        JOIN ProductCarat PC ON X.Carat = PC.ID 
    WHERE
        X.Active = 'A' 
        AND X.Weight IS NOT NULL 
        AND X.Carat <> 15 
        AND X.Priority != 'Y'
    ORDER BY
        X.TransDate,
        X.ID
        ");
     
        return view('Produksi.PPIC.PohonPriority2.index', compact('datenow','TebelPriority'));
    }

    public function Tabels(){
        // Getting WaxInjectOrder
        $datenow = FacadesDB::connection('erp')
        ->select("SELECT ID FROM Waxtree WHERE Active = 'A' LIMIT 1");

        $datenow = $datenow[0];
        
        $TebelPriority = FacadesDB::connection('erp')
        ->select("SELECT
        X.ID as IDWaxtree,
        X.Priority,
        X.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        REPLACE(X.WorkOrder, ',' , '<br>') as WorkOrder,
        REPLACE(X.Product,';','<br>') as Product,
    CASE
            
            WHEN PC.SW = '6K' THEN
            '#090cd9' 
            WHEN PC.SW = '8K' THEN
            '#02ba1e' 
            WHEN PC.SW = '16K' THEN
            '#ff1a1a' 
            WHEN PC.SW = '17K' THEN
            '#e65507' 
            WHEN PC.SW = '17K.' THEN
            '#d909cb' 
            WHEN PC.SW = '20K' THEN
            '#ffcba4' 
            WHEN PC.SW = '10K' THEN
            '#f5fc0f' 
            WHEN PC.SW = '8K.' THEN
            '#ebb52d' 
            WHEN PC.SW = '19K' THEN
            '#4908a3' 
            WHEN PC.SW IS NULL THEN
            '#000' 
        END HexColor,
    CASE
            
            WHEN PC.Description IS NOT NULL THEN
            PC.Description ELSE '? ? ?' 
        END Kadar,
        X.Qty,
        SubString( WorkOrder, Locate( '(', WorkOrder ) + 1, Locate( ')', WorkOrder ) - Locate( '(', WorkOrder ) - 1 ) Model,
        IFNULL (X.TotalPoles, 0) TotalPoles,
        IFNULL (X.TotalPatri, 0) TotalPatri,
        IFNULL (X.TotalPUK, 0 ) TotalPUK,
        IFNULL ( X.WeightFG, 0 ) WeightFG,
        CASE WHEN LEFT (X.WorkOrder,1) = 'O' THEN 'bg-primary' ELSE 'bg-light' END WorkColor,
		CASE WHEN LEFT (X.WorkOrder,1) = 'O' THEN 'Tomato' ELSE 'Gray' END WorkText,
        IF(X.Priority = 'R', 'Checked', '') as Checked,
        IF(X.Priority = 'R', 'table-primary', '') as cssterpilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPoles, 0), 0) as TotalPolespilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPatri, 0), 0) as TotalPatripilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPUK, 0 ), 0) as TotalPUKpilih,
				IF(X.Priority = 'R', IFNULL ( X.WeightFG, 0 ), 0) as WeightFGpilih,
				IF(X.Priority = 'R', 1 , 0) as jumlahpohonpilih,
                CASE WHEN SubString( WorkOrder, Locate( '(', WorkOrder ) + 1, Locate( ')', WorkOrder ) - Locate( '(', WorkOrder ) - 1 ) IN ('ATKMNP','ATKAG05','ATKVR','ATKMVR','CBM1','CBM15','CBM2','CWS','CWS1','GOC15','GOC2','GPAC2','GPAC3','GPMA2','GPM2','GPM3','GPMD4','GPMD5','GRKVR','AGSMN','GRKB','AGS1','GSMN')
 THEN 'MediumSeaGreen' ELSE 'Gray' END infomodel
    FROM
        WaxTree X
        JOIN RubberPlate R ON X.SW = R.ID
        JOIN ProductCarat PC ON X.Carat = PC.ID 
    WHERE
        X.Active = 'A' 
        AND X.Weight IS NOT NULL 
        AND X.Carat <> 15 
        AND X.Priority != 'Y'
    ORDER BY
        X.TransDate,
        X.ID
        ");
        if (count($TebelPriority) == 0) {
            $data_return = $this->SetReturn(false, "Getting data Failed", null, null);
            return response()->json($data_return, 404);
        }

        $datenow->tabel = $TebelPriority;
        $data_return = $this->SetReturn(true, "Getting data Success", $datenow, null);
        return response()->json($data_return, 200);
    }

    public function Tabels2(){
        // Getting WaxInjectOrder
        $datenow = FacadesDB::connection('erp')
        ->select("SELECT ID FROM Waxtree WHERE Active = 'A' LIMIT 1");

        $datenow = $datenow[0];
        
        $TebelPrioritytrue = FacadesDB::connection('erp')
        ->select("SELECT
        X.ID as IDWaxtree,
        X.Priority,
        X.TransDate,
        Cast( R.SW AS CHAR ) Plate,
        REPLACE(X.WorkOrder, ',' , '<br>') as WorkOrder,
        REPLACE(X.Product,';','<br>') as Product,
    CASE
            WHEN PC.SW = '6K' THEN
            '#090cd9' 
            WHEN PC.SW = '8K' THEN
            '#02ba1e' 
            WHEN PC.SW = '16K' THEN
            '#ff1a1a' 
            WHEN PC.SW = '17K' THEN
            '#e65507' 
            WHEN PC.SW = '17K.' THEN
            '#d909cb' 
            WHEN PC.SW = '20K' THEN
            '#ffcba4' 
            WHEN PC.SW = '10K' THEN
            '#f5fc0f' 
            WHEN PC.SW = '8K.' THEN
            '#ebb52d' 
            WHEN PC.SW = '19K' THEN
            '#4908a3' 
            WHEN PC.SW IS NULL THEN
            '#000' 
        END HexColor,
    CASE
            
            WHEN PC.Description IS NOT NULL THEN
            PC.Description ELSE '? ? ?' 
        END Kadar,
        X.Qty,
        SubString( WorkOrder, Locate( '(', WorkOrder ) + 1, Locate( ')', WorkOrder ) - Locate( '(', WorkOrder ) - 1 ) Model,
        IFNULL (X.TotalPoles, 0) TotalPoles,
        IFNULL (X.TotalPatri, 0) TotalPatri,
        IFNULL (X.TotalPUK, 0 ) TotalPUK,
        IFNULL ( X.WeightFG, 0 ) WeightFG,
        CASE WHEN LEFT (X.WorkOrder,1) = 'O' THEN 'bg-primary' ELSE 'bg-light' END WorkColor,
		CASE WHEN LEFT (X.WorkOrder,1) = 'O' THEN 'Tomato' ELSE 'Gray' END WorkText,
        IF(X.Priority = 'R', 'Checked', '') as Checked,
        IF(X.Priority = 'R', 'table-primary', '') as cssterpilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPoles, 0), 0) as TotalPolespilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPatri, 0), 0) as TotalPatripilih,
				IF(X.Priority = 'R', IFNULL (X.TotalPUK, 0 ), 0) as TotalPUKpilih,
				IF(X.Priority = 'R', IFNULL ( X.WeightFG, 0 ), 0) as WeightFGpilih,
				IF(X.Priority = 'R', 1 , 0) as jumlahpohonpilih,
                CASE WHEN SubString( WorkOrder, Locate( '(', WorkOrder ) + 1, Locate( ')', WorkOrder ) - Locate( '(', WorkOrder ) - 1 ) IN ('ATKMNP','ATKAG05','ATKVR','ATKMVR','CBM1','CBM15','CBM2','CWS','CWS1','GOC15','GOC2','GPAC2','GPAC3','GPMA2','GPM2','GPM3','GPMD4','GPMD5','GRKVR','AGSMN','GRKB','AGS1','GSMN')
 THEN 'MediumSeaGreen' ELSE 'Gray' END infomodel
    FROM
        WaxTree X
        JOIN RubberPlate R ON X.SW = R.ID
        JOIN ProductCarat PC ON X.Carat = PC.ID 
    WHERE
        X.Active = 'A' 
        AND X.Weight IS NOT NULL 
        AND X.Carat <> 15 
        AND X.Priority = 'Y'
    ORDER BY
        X.TransDate,
        X.ID
        ");
        if (count($TebelPrioritytrue) == 0) {
            $data_return = $this->SetReturn(false, "Getting data Failed", null, null);
            return response()->json($data_return, 404);
        }

        $datenow->tabel2 = $TebelPrioritytrue;
        $data_return = $this->SetReturn(true, "Getting data Success", $datenow, null);
        return response()->json($data_return, 200);
    }

    public function UbahjadiR($IDpohon){
        // dd($IDpohon);
        $update_waxtree = waxtree::where('ID',$IDpohon)->update([
            'Priority'	=> 'R',
        ]);
    }

    public function UbahjadiN($IDpohon){
        // dd($IDpohon);
        $update_waxtree = waxtree::where('ID',$IDpohon)->update([
            'Priority'	=> 'N',
        ]);
    }

    public function UbahjadiY($IDpohon){
        // dd($IDpohon);
        $update_waxtree = waxtree::where('ID',$IDpohon)->update([
            'Priority'	=> 'Y',
        ]);
    }

    public function Simpan(){

    //    dd($request->Priority);
        $countpohon = FacadesDB::connection('erp')
        ->select("SELECT COUNT(ID) as banyakpohon FROM Waxtree WHERE Priority = 'R'");

        $update_waxtree = waxtree::where('Priority','R')->update([
            'Priority'	=> 'Y',
        ]);
        
        if ($countpohon[0]->banyakpohon > 0) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                    'totalpohondiupdate' => $countpohon[0]->banyakpohon,
                ],
                201,
            );
        }
    }

    public function cetak($Priority){

        //    dd($Priority);

           // Get Header
           $Waxtreeitem = FacadesDB::connection('erp')
           ->select("SELECT

           A.ID,
           A.WorkOrder,
           D.Description Carat,
           C.SW
       FROM
           waxtree A
           JOIN Rubberplate C ON A.SW = C.ID
           JOIN ProductCarat D ON D.ID = A.Carat
       WHERE
           A.ID IN ($Priority)");
   
           $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
           $datenow = $date->format("d/m/Y");
           $timenow = $date->format("H:i");
   
           $username = Auth::user()->name;
           FacadesDB::beginTransaction();
   
           return view('Produksi.PPIC.PohonPriority2.cetak',compact('username','Waxtreeitem','date','datenow','timenow'));
           
        }

  
} 
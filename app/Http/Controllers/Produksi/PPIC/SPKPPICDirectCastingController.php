<?php

namespace App\Http\Controllers\Produksi\PPIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;
use App\Models\rndnew\productcarat;
use App\Models\rndnew\worklist3dpproduction;
use App\Models\rndnew\worklist3dpproductionitem;
use App\Models\erp\waxorder;
use App\Models\erp\waxorderitem;
use App\Models\rndnew\lastid;



// Import public function controller for logger
use App\Http\Controllers\Public_Function_Controller;

class SPKPPICDirectCastingController extends Controller
{
    /*public function __construct(Public_Function_Controller $Public_Function_Controller) {
        $this->Public_Function = $Public_Function_Controller;
    }
*/
    // Index function
    public function index() {
        $carats = FacadesDB::table('productcarat')
                ->select('ID', 'SKU', 'Alloy')
                ->where('Regular', '=', 'Y')
                ->get();
        return view("Produksi.PPIC.SPKPPICDirectCasting.index", compact('carats'));
    }

    public function GetListItemPPIC(Request $request){

        $html = '';

        $data = FacadesDB::connection('erp')
        ->table('workorder AS wo')
        ->join('workorderitem AS woi', function($join){
            $join->on("wo.ID","=","woi.IDM");
        })
        ->join('product AS p', function($join){
            $join->on("p.ID","=","woi.Product");
        })
        ->leftjoin('rndnew.drafter3d AS ddd', function($join){
            $join->on("p.LinkID","=","ddd.Product");
        })
        ->selectRaw("
           wo.ID as IDWO,
           wo.SW,
           woi.Ordinal,
           p.SW as Product,
           p.ID as IDProduct,
           p.LinkID as IDComponent,
           woi.Qty,
           woi.Remarks
        ")
        ->where("wo.SWUsed","=",$request->value)
        ->whereIn('wo.SWPurpose',array('CBC','CBD','OCBC','OCBD'))
        ->groupBy('woi.IDM')
        ->groupBy('woi.Ordinal')
        ->groupBy('p.VarSize')
        ->orderBy("woi.Ordinal","ASC")
        ->get();

        
        $no = 1;    
        foreach ($data as $key => $value) {
           $html .= '<tr>';
                $html .= '<td align="center"><input type="text" class="form-control fs-6 text-center" readonly style="background-color: white;" name="urut[]" value="'.$no++.'"></td>';
                $html .= '<td align="center">
                            <input type="text" class="form-control fs-6 text-center" readonly style="background-color: white;" name="wo[]" value="'.$value->SW.'">
                            <input type="hidden" name="idwo[]" value="'.$value->IDWO.'">
                            <input type="hidden" name="ordinal[]" value="'.$value->Ordinal.'">';
                $html .= '</td>';
                $html .= '<td align="center">
                            <input type="text" class="form-control fs-6 text-center" readonly style="background-color: white;" name="swprod[]" value="'.$value->Product.'">
                            <input type="hidden" name="idprod[]" value="'.$value->IDProduct.'">
                            <input type="hidden" name="idkompo[]" value="'.$value->IDComponent.'">';
                $html .= '</td>';
                $html .= '<td align="center"><input type="text" class="form-control fs-6 text-center" readonly style="background-color: white;" name="qty[]" value="'.$value->Qty.'"></td>';
                $html .= '<td align="center"><input type="text" class="form-control fs-6 text-center" readonly style="background-color: white;" name="ket[]" value="'.$value->Remarks.'"></td>';
            $html .= '</tr>';
        }
        // Setup Return Data
        $data_return = [
            "html" => $html
        ];
        return response()->json($data_return,200);
    }

    public function simpan(Request $request){
        $user = Auth::user();
        // Data for payrollinternship
        $UserName = $user->name;

        $datenow = date('Y-m-d');

        $idwo = $request->idwo;
        $ordinal = $request->ordinal;
        $baris = count($idwo);
        $idprod = $request->idprod;
        $qty= $request->qty;
        $idkompo = $request->idkompo;
        $tesWo = 'tes';

        $lastid = lastid::where("Module","WaxOrder")
        ->first();

        $IDWaxOrder = $lastid->Last;

        $inswaxorder = waxorder::create([
            "ID"=>$IDWaxOrder + 1,
            "UserName"=>$UserName,
            "WorkOrderStart"=>99999,
            "WorkOrderEnd"=>99999,
            "TransDate"=>$datenow
        ]);

        $updateLastID = lastid::where('Module','WaxOrder')->update([
            "Last"=>$IDWaxOrder + 1
        ]);

        for ($i=0; $i < count($idwo); $i++) { 
            $insWIP3DP = worklist3dpproduction::create([
                /*"Ordinal"=>$key+1,*/
                "Status"=>'SPK PPIC DC',
                "Description"=>'SPK PPIC DC',
                "Active"=>'A',
                "Qty"=>$qty[$i],
                "WorkOrder"=>$idwo[$i],
                "WorkOrderOrd"=>$ordinal[$i],
                "Notes"=>'Direct Casting',
                "TransDate"=>$datenow,
                "Product"=>$idprod[$i]
            ]);

            $getmaxIDwip3DP = FacadesDB::table('worklist3dpproduction')->max('ID');
            //dd($getmaxIDwip3DP );
            $insWIP3DPitem = worklist3dpproductionitem::create([
                "IDM"=> $getmaxIDwip3DP,
                "Ordinal"=>1,
                "Qty"=>$qty[$i],
                "WorkOrder"=>$idwo[$i],
                "Product"=>$idprod[$i]
            ]);

            $inswaxorderitem = waxorderitem::create([
                "IDM"=>$IDWaxOrder + 1,
                "Ordinal"=>$i +1 ,
                "WorkOrder"=>$idwo[$i],
                "WorkOrderOrd"=>$ordinal[$i],
                "Product"=>$idprod[$i],
                "Inject"=>0
            ]);

        }

        $data_return = [
            'status' => 'OK'
        ];
        return response()->json($data_return,200); 

    }
}


?>
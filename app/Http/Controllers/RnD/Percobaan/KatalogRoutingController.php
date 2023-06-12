<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

class KatalogRoutingController extends Controller{
    // Index function
    public function index() {
        return view('R&D.Percobaan.KatalogRoutingPCB.index');
    }

    public function GetListRouting(Request $request) {

        $html = '';

        $getWo = FacadesDB::select("SELECT ID, SW FROM workorder WHERE (ID = '".$request->value."' OR SWUsed = '".$request->value."' OR SW = '".$request->value."')");
        $idwo = $getWo[0]->ID;

        $listitem = FacadesDB::select("
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Cor' as Note, dz.SW VarSize, cci.`Status` FROM 
cutcast cc
JOIN cutcastitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 
UNION

SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Brush' as Note, dz.SW VarSize, cci.`Status` FROM 
brush cc
JOIN brushitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'BSP' as Note, dz.SW VarSize, cci.`Status` FROM 
bsp cc
JOIN bspitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Ass' as Note, dz.SW VarSize, cci.`Status` FROM 
assembling cc
JOIN assemblingitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Poles' as Note, dz.SW VarSize, cci.`Status` FROM 
poles cc
JOIN polesitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION 
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'PolesMesin' as Note, dz.SW VarSize, cci.`Status` FROM 
polesmesin cc
JOIN polesmesinitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION 
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Slep' as Note, dz.SW VarSize, cci.`Status` FROM 
slep cc
JOIN slepitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION 
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Marking' as Note, dz.SW VarSize, cci.`Status` FROM 
marking cc
JOIN markingitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'VarP' as Note, dz.SW VarSize, cci.`Status` FROM 
varp cc
JOIN varpitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Rep' as Note, dz.SW VarSize, cci.`Status` FROM 
reparasi cc
JOIN reparasiitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'RepSC' as Note, dz.SW VarSize, cci.`Status` FROM 
reparasisc cc
JOIN reparasiscitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Sepuh' as Note, dz.SW VarSize, cci.`Status` FROM 
sepuh cc
JOIN sepuhitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'Enamel' as Note, dz.SW VarSize, cci.`Status` FROM 
enamel cc
JOIN enamelitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

UNION 
SELECT cci.IDM, cci.Ordinal, cci.TreeID, cci.TreeOrd, cci.Weight, cci.WorkOrder, cci.Product , p.SerialNo, CONCAT(pt.SW,'-',p.SerialNo) as Model,cc.PostDate, wti.LinkOrd, 'QC' as Note, dz.SW VarSize, cci.`Status` FROM 
qc cc
JOIN qcitem cci ON cc.ID = cci.IDM AND cci.WorkOrder = ".$idwo."
JOIN waxtreeitem wti ON cci.TreeID = wti.IDM AND cci.TreeOrd = wti.Ordinal 
JOIN erp.product p ON cci.Product = p.ID
JOIN erp.product pt ON p.Model = pt.ID
JOIN designsize dz ON p.VarSize = dz.ID
WHERE cci.Weight > 0 

ORDER BY Product, TreeOrd, PostDate");
        $no = 0;
        $idwo = $getWo[0]->SW;;
        $totBarang = 0;

        foreach ($listitem as $value) {

            $bgcolor = "";

            if ($value->LinkOrd == '1') {
                $bgcolor = 'style="background-color:  #F0F8FF"';
            }
            elseif ($value->LinkOrd == '2') {
                $bgcolor = 'style="background-color: #F08080"';   
            }
            elseif ($value->LinkOrd == '3') {
                $bgcolor = 'style="background-color: #FFB6C1"';
            }

            $html .= '<tr>';
                $html .= '<td align="center" '.$bgcolor.' style="font-weight: bold;">'.$idwo.'</td>';
                $html .= '<td align="center" '.$bgcolor.'>'.$value->Model.' ( '.$value->VarSize.' )</td>';
                $html .= '<td align="center" '.$bgcolor.'>'.$value->SerialNo.'</td>';
                $html .= '<td align="center" '.$bgcolor.'>'.$value->Weight.'</td>';
                $html .= '<td align="center" '.$bgcolor.'>'.$value->Note.'</td>';
                $html .= '<td align="center" '.$bgcolor.'>'.$value->Status.'</td>';
            $html .= '</tr>';
        }

$html .= '</script>';

        $data_return = [
            "swwo" => $idwo,
            "html" => $html,
            "total" => $totBarang
        ];
        return response()->json($data_return,200);
    }
}
?>
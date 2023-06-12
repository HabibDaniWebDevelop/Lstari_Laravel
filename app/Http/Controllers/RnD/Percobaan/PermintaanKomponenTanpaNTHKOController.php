<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

use App\Models\erp\workschedule;
use App\Models\erp\workscheduleitem;

use App\Models\erp\workcompletion as erp_workcompletion;
use App\Models\erp\workcompletionitem as erp_workcompletionitem;

class PermintaanKomponenTanpaNTHKOController extends Controller{
    // Index function
    public function index() {
        return view('R&D.Percobaan.PermintaanKomponenTanpaNTHKO.index');
    }

    public function store(Request $request) {

        $jumlahBaris = count($request->idmn);

        $totalbarang = $request->totalbarang;

        $username = Auth::user()->name;  

        $getIDRPH = FacadesDB::connection('erp')->select("SELECT Last+1 as maxID FROM lastid WHERE Module = 'WorkSchedule'");
        $IDRPH = $getIDRPH[0]->maxID;

        $updLastIDRPH = lastid::where('Module','WorkSchedule')->update([
            "Last"=>$IDRPH
        ]);          

        /*$getIDNTHKO = FacadesDB::connection('erp')->select("SELECT Last+1 as maxID FROM lastid WHERE Module = 'WorkCompletion'");
        $IDNTHKO = $getIDNTHKO[0]->maxID;   

        $updLastIDNTHKO = lastid::where('Module','WorkCompletion')->update([
            "Last"=>$IDNTHKO
        ]);*/

        $insWorkSchedule = workschedule::create([
            'ID' => $IDRPH,
            'UserName' => $username,
            'Remarks' => 'TanpaNTHKO',
            'TransDate' => now(),
            'Location' => 56,
            'Qty' => $totalbarang,
            'Weight' => 0,
            'QtyPlan' => $totalbarang, 
            'WeightPlan' => 0,
            'Active' => 'P',
            'PostDate' =>  now(),
            'Operation' => 94
        ]);

        for ($i=0; $i < $jumlahBaris; $i++) { 
            $insWorkScheduleItem = workscheduleitem::create([
                'IDM' => $IDRPH,
                'Ordinal' => $i++,
                'LinkID' => $IDRPH,
                'LinkOrd' => $i,
                'Category' => 56,
                'Qty' => '0',
                'Weight' => '0'
            ]);
        }

        if ($insWorkSchedule) {
                return response()->json([
                    'success' => true,
                    'IDRPH' => $IDRPH,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }

    }

    public function GetListTanpaNTHKO(Request $request) {
        $html = '';
        $listitem = FacadesDB::select("SELECT
                woi.IDM, 
                p.SW,
                p.ID IDProduk,
                wo.SW SWWO,
                mm.ID IDMN,
                CASE 
                    WHEN mm.SKU IS NULL THEN mm.OldSW
                    ELSE mm.SKU
                END SKUMainan,
                CASE 
                    WHEN mm.OldId IS NULL THEN mm.SW
                    ELSE mm.OldSW
                END as SWMainan,
                SUM(pm.Qty * woi.Qty) as JumlahMainan
            FROM
                workorderitem woi 
                JOIN workorder wo ON woi.IDM = wo.ID
                JOIN productmn pm ON woi.Product = pm.IDM
                JOIN mastermainan mm ON pm.Mainan = mm.ID
                JOIN erp.product p ON woi.Product = p.ID
                LEFT JOIN (SELECT ID, LinkID FROM product WHERE TypeProcess = 26) pt ON mm.ID = pt.LinkID
            WHERE
                woi.IDM = ".$request->value." AND pm.Status = 'GT'
            GROUP BY mm.ID");
        $no = 0;
        $idwo = '';
        $totBarang = 0;

        foreach ($listitem as $value) {
        $idwo = $value->SWWO;
        $totBarang += $value->JumlahMainan;
            $html .= '<tr class="klik3" id="'.$no++.'" id2="'.$value->SW.'">';
                $html .= '<td align="center">
                            '.$no.'
                            <input type="hidden" name="idwo[]" id="idwo'.$no.'" style="width: 100%; box-sizing: border-box; text-align:center;" value="'.$value->IDM.'">
                        </td>';
                $html .= '<td>
                            <input type="text" style="width: 100%; box-sizing: border-box; text-align:center;" value="'.$value->SW.'">
                        </td>';
                $html .= '<td>
                            <input type="hidden" name="idmn[]" id="idmn'.$no.'" style="width: 100%; text-align: center;" value="'.$value->IDM.'"> 
                            <input type="hidden" name="idprod[]" id="idprod'.$no.'" style="width: 100%; text-align: center;" value="'.$value->IDProduk.'"> 
                            <input type="text" name="swmn[]" id="swmn'.$no.'" style="width: 100%; box-sizing: border-box; text-align:center;" value="'.$value->SWMainan.'">
                        </td>';
                $html .= '<td>
                            <input type="text" name="qty[]" id="qty'.$no.'" style="width: 100%; text-align: center; box-sizing: border-box;" value="'.$value->JumlahMainan.'"> 
                        </td>';
                /*$html .= '<td align="center">
                            <button type="button" onclick="hapusrow('.$no.')"><span class="tf-icons bx bx-trash"></span></button>
                        </td>';  */   
            $html .= '</tr>';
        }

        $html .= '<script>';

        $html .= "    $('.klik3').on('click', function(e) {
                            $('.klik3').css('background-color', 'white');

                            if ($('#menuklik').css('display') == 'block') {
                                $(' #menuklik ').hide();
                            } else {
                                var top = e.pageY + 15;
                                var left = e.pageX - 100;
                                var id = $(this).attr('id');
                                var id2 = $(this).attr('id2');
                                $('#judulklik').html(id);

                                $(this).css('background-color', '#f4f5f7');
                                $('#menuklik').css({
                                    display: 'block',
                                    top: top,
                                    left: left
                                });
                            }
                            return false;
                        });
                    $('body').on('click', function() {
                        if ($('#menuklik').css('display') == 'block') {
                            $(' #menuklik ').hide();
                        }
                        $('.klik3').css('background-color', 'white');
                    });

                    $('#menuklik a').on('click', function() {
                        $(this).parent().hide();
                    });";
        $html .= "function klikhapus(id) {
            $(\"#\" + id).remove();

            $(\"#tabel5 tr\").each((i, elem) => {
                Index = i + 1;
                if (Index < id) {
                    newIndex = i + 1;
                } else {
                    newIndex = i;
                }
                $('[data-index=\"' + Index + '1\"]').attr('value', newIndex);
                $('[data-index=\"' + Index + '1\"]').parent().parent().attr('id', newIndex);
                $('[data-index=\"' + Index + '1\"]').attr('data-index', newIndex + '1');
                $('[data-index=\"' + Index + '2\"]').attr('data-index', newIndex + '2');
                $('[data-index=\"' + Index + '3\"]').attr('data-index', newIndex + '3');
                $('[data-index=\"' + Index + '4\"]').attr('data-index', newIndex + '4');

                $(elem).find('.satuan').attr('id', \"satuan_\" + newIndex);
            })
        }";

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
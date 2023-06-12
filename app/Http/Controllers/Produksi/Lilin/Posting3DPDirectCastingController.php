<?php

namespace App\Http\Controllers\Produksi\Lilin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\erp\transferresindc;
use App\Models\erp\transferresindcitem;
use App\Models\erp\waxorderitem;

class Posting3DPDirectCastingController extends Controller
{
    public function index()
    {
        $carilists = FacadesDB::connection('erp')->select("SELECT ID, Active FROM `transferresindc` WHERE Active IN ('A', 'P') ORDER BY ID DESC LIMIT 20");
        //    dd('tess');
        return view('Produksi.Lilin.Posting3DPDirectCasting.index', compact('carilists'));
    }

    public function update(Request $request, $id)
    {
        $tglfull = date('Y-m-d h:i:s');

        $postingtransferresindc = transferresindc::find($id);
        $postingtransferresindc->Active = 'P';
        $postingtransferresindc->PostDate = $tglfull;
        $postingtransferresindc->save();

        $transferresindcs = FacadesDB::connection('erp')->select("SELECT Ti.*FROM transferresindc T INNER JOIN transferresindcitem Ti ON T.ID=ti.IDM WHERE t.ID='$id'");

        foreach ($transferresindcs as $key => $data) {

            $updatewaxorderitem = waxorderitem::where('WorkOrder', $data->WorkOrder)
            ->where('WorkOrderOrd', $data->WorkOrderOrd)
            ->update(['TransferResinDC' => $data->IDM, 'TransferResinDCOrd' => $data->Ordinal]);
        }
 
        // dd($transferresindcs);

        if ($postingtransferresindc) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Berhasil!!',
                    'message' => 'Berhasil!!',
                ],
                201,
            );
        }
    }
}
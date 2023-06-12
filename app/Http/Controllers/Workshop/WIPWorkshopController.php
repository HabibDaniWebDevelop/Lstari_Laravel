<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Models\erp\workorder;
// Prod
use App\Models\rndnew\wipworkshop;
use App\Models\rndnew\wipworkshopfg;
// Dev
// use App\Models\tes_laravel\wipworkshop;
// use App\Models\tes_laravel\wipworkshopfg;

class WIPWorkshopController extends Controller{
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
    // End Private Function

    public function Index(Request $request){
        $dataWIP = FacadesDB::select("
            SELECT
                A.ID AS IDWIP,
                A.ProgressStatus,
                B.ID,
                B.SW AS swProduct,
                B.TypeProcess,
                CASE 
                    WHEN B.TypeProcess = 25 THEN 'Komponen'
                    WHEN B.TypeProcess = 26 THEN 'Mainan'
                    WHEN B.TypeProcess = 27 THEN 'Kepala'
                END AS jenisPart,
                C.ImageOriginal AS imageProduct,
                C.Corel AS corelFile,
                D.Image AS imageProduct3D,
                D.File3DM AS file3DM 
            FROM
                wipworkshop A
                JOIN product B ON A.IDProduct = B.ID
                LEFT JOIN drafter2d C ON A.IDDrafter2D = C.ID
                LEFT JOIN drafter3d D ON A.IDDrafter3D = D.ID
        ");
        $request->session()->put('hostfoto', 'http://192.168.3.100:8383');
        return view('Workshop.WIPWorkshop.index', compact('dataWIP'));
    }
    
    public function PreviewFunction($idProduct){
        // $idProduct = 215141;
        // Get dimensidrafter3d
        $detailDesign = FacadesDB::select("
            SELECT
                A.SW,
                C.*
            FROM
                product A
                LEFT JOIN drafter3d B ON A.LinkID = B.Product AND ResultStatusID = 1
                LEFT JOIN dimensidrafter3d C ON B.ID = C.IDM
            WHERE
                A.ID = '$idProduct'
        ");
        $detailDesign = $detailDesign[0];
        $namaProduct = $detailDesign->SW;
        $htmlPreview = view('Workshop.WIPWorkshop.modalBody', compact('detailDesign'))->render();
        $data = [
            "namaProduct"=>$namaProduct,
            "dataHTML"=>$htmlPreview,
            "dataJSON"=>$detailDesign
        ];
        $data_return = $this->SetReturn(true, "Ok,", $data, null);
        return response()->json($data_return, 200);
    }

    public function VerifiedWIP($idWIP){
        // Check if idWIP exists
        $getWIP = wipworkshop::where("ID",$idWIP)->first();
        if (is_null($getWIP)) {
            $data_return = $this->SetReturn(false, "WIP Tidak ditemukan", null, null);
            return response()->json($data_return, 404);
        }

        // Check if this wip ProgressStatus is verification
        if ($getWIP['ProgressStatus'] != 0) {
            $data_return = $this->SetReturn(false, "WIP Ini bukan proses verifikasi", null, null);
            return response()->json($data_return, 400);
        } 

        // update ProgressStatus to Active (Siap SPKO)
        $updateWIP = wipworkshop::where('ID',$idWIP)->update([
            "ProgressStatus"=>1
        ]);

        $data_return = $this->SetReturn(true, "Verifikasi Success", $updateWIP, null);
        return response()->json($data_return, 200);
    }
}

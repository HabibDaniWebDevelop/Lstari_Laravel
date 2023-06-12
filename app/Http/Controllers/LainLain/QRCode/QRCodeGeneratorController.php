<?php

namespace App\Http\Controllers\LainLain\QRCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class QRCodeGeneratorController extends Controller
{
    public function index()
    {
        return view('Lain-lain.QRCode.QRCodeGenerator.index');
    }

    public function generate($no, $id)
    {
        // dd($no, $id);

        if($no == 1){
            return view('Lain-lain.QRCode.QRCodeGenerator.list', compact('id'));
        }
        else if ($no == 2) {

            $data = FacadesDB::connection('erp')->select(" SELECT
                        PD.ID,
                    CASE
                            
                            WHEN PD.SKU IS NULL THEN
                            PD.SW ELSE PD.SKU 
                        END AS SKU,
                        CONCAT( A.WorkAllocation, '-', A.Freq, '-', B.Ordinal ) string
                    FROM
                        workcompletion A
                        JOIN workcompletionitem B ON B.IDM = A.ID
                        JOIN product PD ON IFNULL( B.FG, B.Part ) = PD.ID 
                    WHERE
                        CONCAT( A.WorkAllocation, '-', A.Freq, '-', B.Ordinal ) = '$id' ");

            // dd($data);
            return view('Lain-lain.QRCode.QRCodeGenerator.list2', compact('id', 'data'));
        }
    }
}

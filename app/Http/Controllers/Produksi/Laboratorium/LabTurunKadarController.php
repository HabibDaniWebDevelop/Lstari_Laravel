<?php

namespace App\Http\Controllers\Produksi\Laboratorium;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\erp\cjepsi;

class LabTurunKadarController extends Controller{
     // START REUSABLE FUNCTION
     private function SetReturn($success,$message,$data,$error){
        $data_return = [
            "success"=>$success,
            "message"=>$message,
            "data"=>$data,
            "error"=>$error
        ];
        return $data_return;
    }

    private function GetNotaTukangLuar($nomorNota){
        $nota = $nomorNota;
        // Get Workcompletion and Lab
        $total_selisih_berat = 0;
        $data_workcompletion_lab = FacadesDB::connection('erp')->select("
            SELECT
                A.WorkAllocation AS SWSPKO,
                A.Freq,
                B.IDM AS IDNTHKO,
                B.Ordinal AS OrdinalNTHKO,
                A.TransDate AS TanggalNTHKO,
                A.PostDate AS TanggalPosting,
                substring_index(B.Note,' ',1) Tukang,
                C.ID AS IDProductFGNTHKO,
                C.SW AS NamaProductFGNTHKO,
                D.Description AS NamaProductNTHKO,
                E.SW AS NomorSPKNTHKO,
                F.SW AS CategoryProductFGNTHKO,
                B.Qty AS QtyNTHKO,
                B.Weight AS WeightNTHKO,
                G.TransDate AS TanggalLab,
                G.Carat AS HasilLab,
                G.CaratTolerance AS Toleransi, 
                G.Weight AS WeightLab,
                G.WeightDiff AS SelisihBerat,
                G.Remarks AS Catatan
            FROM
                workcompletion A
                JOIN workcompletionitem B ON A.ID = B.IDM
                JOIN product C ON B.FG = C.ID
                JOIN product D ON B.Product = D.ID
                JOIN workorder E ON B.WorkOrder = E.ID
                JOIN productcategory F ON C.Model = F.ProductID
                LEFT JOIN cjepsi G ON B.IDM = G.WorkCompletion AND B.Ordinal = G.WorkCompletionOrd
            WHERE
                A.WorkAllocation = '$nota' AND A.Active != 'C'
        ");
        
        $final_data = [];

        // Loop dat_workcompletion_lab and convert to array
        foreach($data_workcompletion_lab as $data){
            // convert data to array
            $data = (array) $data;

            // add total_selisih_berat from SelisihBerat in data
            $total_selisih_berat += $data['SelisihBerat'];

            // add data to final_data
            $final_data[] = $data;
        }

        // Get detail of nomor nota
        $detail = FacadesDB::connection('erp')->select("
            SELECT
                D.SW AS NamaTukang,
                C.SW AS Kadar,
                B.Description AS Proses
                
            FROM
                workallocation A 
                JOIN operation B ON A.Operation = B.ID
                JOIN productcarat C ON A.Carat = C.ID
                JOIN employee D ON A.Employee = D.ID
            WHERE
                A.SW = '$nota'
            LIMIT 1
        ");
        if (count($detail) < 1) {
            $detail = null;
        } else {
            $detail = (array) $detail[0];
        }

        $notaTukangLuar = $final_data;
        $html = view('Produksi.Laboratorium.LabTurunKadar.table',compact('notaTukangLuar','total_selisih_berat'))->render();

        $data_return = [
            "total_selisih_berat"=>$total_selisih_berat,
            "data"=>$final_data,
            "html"=>$html,
            "detail"=>$detail
        ];
        
        // return data_return
        return $data_return;
    }
    
    public function index(){
        return view("Produksi.Laboratorium.LabTurunKadar.index");
    }

    public function SearchNotaTukangLuar(Request $request){
        $nomorNota = $request->nomorNota;
        // check if nomorNota is null or is empty string
        if($nomorNota == null or $nomorNota == ""){
            // set data_return
            $data_return = $this->SetReturn(false,"Nomor Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }
        // run GetNotaTukangLuar
        $notaTukangLuar = $this->GetNotaTukangLuar($nomorNota);
        // set data_return
        $data_return = $this->SetReturn(true,"Sukses",$notaTukangLuar,null);
        // return data_return with 200
        return response()->json($data_return,200);
    }

    public function UpdateCjepsi(Request $request){
        // get idNTHKO from request
        $idNTHKO = $request->idNTHKO;
        // get ordinalNTHKO from request
        $ordinalNTHKO = $request->ordinalNTHKO;
        // get tanggal from request
        $tanggal = $request->tanggal;
        // get carat from request
        $carat = $request->carat;
        // get caratTolerance from request
        $caratTolerance = $request->caratTolerance;
        // get weightLab from request
        $weightLab = $request->weightLab;
        // get weightDiff from request
        $weightDiff = $request->weightDiff;
        // get remarks from request
        $remarks = $request->remarks;

        // check if idNTHKO is null or is empty string
        if($idNTHKO == null or $idNTHKO == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"ID Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }

        // check if ordinalNTHKO is null or is empty string
        if($ordinalNTHKO == null or $ordinalNTHKO == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"Ordinal Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }

        // check if tanggal is null or is empty string
        if($tanggal == null or $tanggal == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"Tanggal Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }

        // check if carat is null or is empty string
        if($carat == null or $carat == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"Kadar Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }

        // check if caratTolerance is null or is empty string
        if($caratTolerance == null or $caratTolerance == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"Toleransi Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }

        // check if weightLab is null or is empty string
        if($weightLab == null or $weightLab == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"Berat Lab Nota Tidak Boleh Kosong",null,null);
            // retuun data_return with 400
            return response()->json($data_return,400);
        }

        // check if weightDiff is null or is empty string
        if($weightDiff == null or $weightDiff == ""){
            // generate data_return
            $data_return = $this->SetReturn(false,"Selisih Berat Nota Tidak Boleh Kosong",null,null);
            // return data_return with 400
            return response()->json($data_return,400);
        }

        // check if cjepsi is exists in database
        $cek_cjepsi = cjepsi::where('WorkCompletion',$idNTHKO)->where('WorkCompletionOrd',$ordinalNTHKO)->first();
        if (!is_null($cek_cjepsi)) {
            // Because cjepsi is not null, then it will update cjepsi

            // Get WorkCompletion data
            $data_workcompletion = FacadesDB::connection('erp')->select("
                SELECT
                    B.WorkAllocation,
                    B.Freq,
                    A.IDM AS WorkCompletion,
                    A.Ordinal AS WorkCompletionOrd,
                    substring_index(A.Note,' ',1) EmployeeOut,
                    C.SW AS ModelNo,
                    A.WorkOrder
                FROM
                    workcompletionitem A
                    JOIN workcompletion B ON A.IDM = B.ID
                    JOIN product C ON A.FG = C.ID
                WHERE
                    A.IDM = '$idNTHKO' AND A.Ordinal = '$ordinalNTHKO'
            ");
            $data_workcompletion = (array) $data_workcompletion[0];
            // set data_workcompletion TransDate to tanggal
            $data_workcompletion['TransDate'] = $tanggal;
            // set data_workcompletion Carat to carat
            $data_workcompletion['Carat'] = $carat;
            // set data_workcompletion CaratTolerance to caratTolerance
            $data_workcompletion['CaratTolerance'] = $caratTolerance;
            // set data_workcompletion Weight to weightLab
            $data_workcompletion['Weight'] = $weightLab;
            // set data_workcompletion WeightDiff to weightDiff
            $data_workcompletion['WeightDiff'] = $weightDiff;
            // set data_workcompletion Remarks to remarks
            $data_workcompletion['Remarks'] = $remarks;
            // update cjepsi
            $update_cjepsi = cjepsi::where('WorkCompletion',$idNTHKO)->where('WorkCompletionOrd',$ordinalNTHKO)->update($data_workcompletion);
            // run GetNotaTukangLuar
            $notaTukangLuar = $this->GetNotaTukangLuar($idNTHKO);
            // set data_return
            $data_return = $this->SetReturn(true,"Update Lab Turun Kadar Berhasil",$notaTukangLuar,null);
            // return 200
            return response()->json($data_return,200);
        } else {
            // Because cjepsi is null, then it will create new cjepsi

            // Get WorkCompletion data
            $data_workcompletion = FacadesDB::connection('erp')->select("
                SELECT
                    B.WorkAllocation,
                    B.Freq,
                    A.IDM AS WorkCompletion,
                    A.Ordinal AS WorkCompletionOrd,
                    substring_index(A.Note,' ',1) EmployeeOut,
                    C.SW AS ModelNo,
                    A.WorkOrder
                FROM
                    workcompletionitem A
                    JOIN workcompletion B ON A.IDM = B.ID
                    JOIN product C ON A.FG = C.ID
                WHERE
                    A.IDM = '$idNTHKO' AND A.Ordinal = '$ordinalNTHKO'
            ");
            $data_workcompletion = (array) $data_workcompletion[0];

            // set data_workcompletion UserName from auth name
            $data_workcompletion['UserName'] = Auth::user()->name;
            // set data_workcompletion Status to 'A'
            $data_workcompletion['Status'] = 'A';
            // set data_workcompletion TransDate to tanggal
            $data_workcompletion['TransDate'] = $tanggal;
            // set data_workcompletion Carat to carat
            $data_workcompletion['Carat'] = $carat;
            // set data_workcompletion CaratTolerance to caratTolerance
            $data_workcompletion['CaratTolerance'] = $caratTolerance;
            // set data_workcompletion Weight to weightLab
            $data_workcompletion['Weight'] = $weightLab;
            // set data_workcompletion WeightDiff to weightDiff
            $data_workcompletion['WeightDiff'] = $weightDiff;
            // set data_workcompletion Remarks to remarks
            $data_workcompletion['Remarks'] = $remarks;
            // create new cjepsi
            $create_cjepsi = cjepsi::create($data_workcompletion);
            // run GetNotaTukangLuar
            $notaTukangLuar = $this->GetNotaTukangLuar($idNTHKO);
            // set data_return
            $data_return = $this->SetReturn(true,"Simpan Lab Turun Kadar Berhasil",$notaTukangLuar,null);
            // return 200
            return response()->json($data_return,200);
        }
    }

    public function CetakLabTurunKadar(Request $request){
        $nomorNota = $request->nomorNota;
        // check if nomorNota is null or is empty string
        if($nomorNota == null or $nomorNota == ""){
            // return abort 404
            return abort(404);
        }
        // run GetNotaTukangLuar
        $data_notatukangluar = $this->GetNotaTukangLuar($nomorNota);
        $notaTukangLuar = $data_notatukangluar['data'];
        $total_selisih_berat = $data_notatukangluar['total_selisih_berat'];
        $detail = $data_notatukangluar['detail'];
        $border=true;
        return view('Produksi.Laboratorium.LabTurunKadar.cetak',compact('notaTukangLuar','total_selisih_berat', 'detail', 'nomorNota', 'border'));
    }
}

<?php

namespace App\Http\Controllers\Penjualan\Informasi\MPC\FormOrderProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;



class FormOrderProduksiController extends Controller
{
    public function index(){
        return view('Penjualan.Informasi.MPC.FormOrderProduksi.index');
    }

    public function formOrder(){
        $data = FacadesDB::connection('erp')->select("
        SELECT  
            SW, CONCAT(DateStart, '|', DateEnd) AS Tanggal, SWOrdinal,
            CASE WHEN SWOrdinal = 1 THEN 'Januari'
            WHEN  SWOrdinal = 2 THEN 'Februari'
            WHEN  SWOrdinal = 3 THEN 'Maret'
            WHEN  SWOrdinal = 4 THEN 'April'
            WHEN  SWOrdinal = 5 THEN 'Mei'
            WHEN  SWOrdinal = 6 THEN 'Juni'
            WHEN  SWOrdinal = 7 THEN 'Juli'
            WHEN  SWOrdinal = 8 THEN 'Agustus'
            WHEN  SWOrdinal = 9 THEN 'September'
            WHEN  SWOrdinal = 10 THEN 'Oktober'
            WHEN  SWOrdinal = 11 THEN 'November'
            WHEN  SWOrdinal = 12 THEN 'Desember'
            ELSE 'Unknown'
            END AS Bulan
        FROM workperiod 
        WHERE SUBSTRING_INDEX(DateStart, '-', 1) = '".date('Y')."'  AND Type = 'P'
        ");

        $kate = FacadesDB::connection('erp')->select("
        SELECT
            ID,
            Description Kategori 
        FROM
            `shorttext` A 
        WHERE
            A.Type = 'Finish Good Sales' AND A.Active = 'Y'
        ORDER BY
            A.ID
        ");


        $kadar = FacadesDB::connection('erp')->select("
        SELECT ID, Description Kadar FROM `productcarat` A 
        WHERE A.Regular = 'Y' ORDER BY A.ID 
        ");
        //dd($data);
        $returnHTML =  view('Penjualan.Informasi.MPC.FormOrderProduksi.data', compact('data', 'kate', 'kadar'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    // public function setYear(Request $request){
    //     $thn = $request->thn;
    //    // dd($thn);
        
       
    //     $returnHTML = view('Penjualan.Informasi.FormOrderProduksi.databulan', compact('data'))->render();
    //     return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    // }

    public function gettingFormOrderProduksi(Request $request)
    {
        $bln = $request->bln;
        $kate = $request->kategori;
        $kadar = $request->kadar;
        $jenis = $request->jenis;
        $id1 = $request->id1;
        $id2 = $request->id2;
        $tgl1 = $request->tanggal1;
        $tgl2 = $request->tanggal2;
        
        $location = session('location');
        $exbln = explode('|', $bln);
        $bln1 = $exbln[0];
        $bln2 = $exbln[1];
        // $curYear = date('Y'); 
        if($jenis == 1){
            if($kate == 0 && $kadar == 0){
                $data = FacadesDB::connection('erp')->select(
                    "Select 
                    T.Product, 
                    T.Carat, 
                    P.SW, 
                    P.Description, 
                    C.Description Carat, 
                    CASE WHEN A.SuggestionWeight IS NULL THEN '0' ELSE A.SuggestionWeight END AS SuggestionWeight, 
                    T.Weight,
                    CASE WHEN (T.Weight - A.SuggestionWeight) IS NULL THEN '0' ELSE (T.Weight - A.SuggestionWeight)  END  AS Selisih1,
                    CASE WHEN B.SuggestionWaxTree IS NULL THEN '0' ELSE B.SuggestionWaxTree END AS SuggestionWaxTree, 
                    T.WaxTree,
                    CASE WHEN ( T.WaxTree - B.SuggestionWaxTree) IS NULL THEN '0' ELSE ( T.WaxTree - B.SuggestionWaxTree) END  AS Selisih2
                    From WorkSuggestionTarget T
                    Join Product P On T.Product = P.ID
                    Join ProductCarat C On T.Carat = C.ID
                    Left Join ( Select I.FinishGood, I.Carat, Sum(I.Weight) SuggestionWeight
                                From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                Where W.TransDate Between '".$bln1."' And '".$bln2."' 
                                Group By I.FinishGood, I.Carat ) A On T.Product = A.FinishGood And T.Carat = A.Carat
                    Left Join ( Select FinishGood, Carat, Count(IDM) SuggestionWaxTree From (
                                Select Distinct I.FinishGood, I.Carat,I.IDM
                                From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                Where W.TransDate Between '".$bln1."' And '".$bln2."'
                                ) A Group By FinishGood, Carat ) B On T.Product = B.FinishGood And T.Carat = B.Carat
                    Order By P.SW, C.Description
                    ");
            }elseif($kate != 0 && $kadar == 0){
                $data = FacadesDB::connection('erp')->select(
                    "Select 
                    T.Product, 
                    T.Carat, 
                    P.SW, 
                    P.Description, 
                    C.Description Carat, 
                    CASE WHEN A.SuggestionWeight IS NULL THEN '0' ELSE A.SuggestionWeight END AS SuggestionWeight, 
                    T.Weight,
                    CASE WHEN (T.Weight - A.SuggestionWeight) IS NULL THEN '0' ELSE (T.Weight - A.SuggestionWeight)  END  AS Selisih1,
                    CASE WHEN B.SuggestionWaxTree IS NULL THEN '0' ELSE B.SuggestionWaxTree END AS SuggestionWaxTree, 
                    T.WaxTree,
                    CASE WHEN ( T.WaxTree - B.SuggestionWaxTree) IS NULL THEN '0' ELSE ( T.WaxTree - B.SuggestionWaxTree) END  AS Selisih2
                    From WorkSuggestionTarget T
                    Join Product P On T.Product = P.ID
                    Join ProductCarat C On T.Carat = C.ID
                    LEFT JOIN shorttext SS ON SS.ID = P.Color
                    Left Join ( Select I.FinishGood, I.Carat, Sum(I.Weight) SuggestionWeight
                                From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                Where W.TransDate Between '".$bln1."' And '".$bln2."' 
                                Group By I.FinishGood, I.Carat ) A On T.Product = A.FinishGood And T.Carat = A.Carat
                    Left Join ( Select FinishGood, Carat, Count(IDM) SuggestionWaxTree From (
                                Select Distinct I.FinishGood, I.Carat,I.IDM
                                From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                Where W.TransDate Between '".$bln1."' And '".$bln2."'
                                ) A Group By FinishGood, Carat ) B On T.Product = B.FinishGood And T.Carat = B.Carat
                                WHERE SS.Description = '".$kate."'
                    Order By P.SW, C.Description
                    ");
                    //dd($data);
            }elseif($kate == 0 && $kadar != 0){
                    $data = FacadesDB::connection('erp')->select(
                        "Select 
                        T.Product, 
                        T.Carat, 
                        P.SW, 
                        P.Description, 
                        C.Description Carat, 
                        CASE WHEN A.SuggestionWeight IS NULL THEN '0' ELSE A.SuggestionWeight END AS SuggestionWeight, 
                        T.Weight,
                        CASE WHEN (T.Weight - A.SuggestionWeight) IS NULL THEN '0' ELSE (T.Weight - A.SuggestionWeight)  END  AS Selisih1,
                        CASE WHEN B.SuggestionWaxTree IS NULL THEN '0' ELSE B.SuggestionWaxTree END AS SuggestionWaxTree, 
                        T.WaxTree,
                        CASE WHEN ( T.WaxTree - B.SuggestionWaxTree) IS NULL THEN '0' ELSE ( T.WaxTree - B.SuggestionWaxTree) END  AS Selisih2
                        From WorkSuggestionTarget T
                        Join Product P On T.Product = P.ID
                        Join ProductCarat C On T.Carat = C.ID
                        Left Join ( Select I.FinishGood, I.Carat, Sum(I.Weight) SuggestionWeight
                                    From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                    Where W.TransDate Between '".$bln1."' And '".$bln2."' 
                                    Group By I.FinishGood, I.Carat ) A On T.Product = A.FinishGood And T.Carat = A.Carat
                        Left Join ( Select FinishGood, Carat, Count(IDM) SuggestionWaxTree From (
                                    Select Distinct I.FinishGood, I.Carat,I.IDM
                                    From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                    Where W.TransDate Between '".$bln1."' And '".$bln2."'
                                    ) A Group By FinishGood, Carat ) B On T.Product = B.FinishGood And T.Carat = B.Carat
                                    WHERE C.ID = ".$kadar."
                        Order By P.SW, C.Description
                        ");
            }elseif($kate != 0 && $kadar != 0){
                    $data = FacadesDB::connection('erp')->select(
                        "Select 
                        T.Product, 
                        T.Carat, 
                        P.SW, 
                        P.Description, 
                        C.Description Carat, 
                        CASE WHEN A.SuggestionWeight IS NULL THEN '0' ELSE A.SuggestionWeight END AS SuggestionWeight, 
                        T.Weight,
                        CASE WHEN (T.Weight - A.SuggestionWeight) IS NULL THEN '0' ELSE (T.Weight - A.SuggestionWeight)  END  AS Selisih1,
                        CASE WHEN B.SuggestionWaxTree IS NULL THEN '0' ELSE B.SuggestionWaxTree END AS SuggestionWaxTree, 
                        T.WaxTree,
                        CASE WHEN ( T.WaxTree - B.SuggestionWaxTree) IS NULL THEN '0' ELSE ( T.WaxTree - B.SuggestionWaxTree) END  AS Selisih2
                        From WorkSuggestionTarget T
                        Join Product P On T.Product = P.ID
                        Join ProductCarat C On T.Carat = C.ID
                        LEFT JOIN shorttext SS ON SS.ID = P.Color
                        Left Join ( Select I.FinishGood, I.Carat, Sum(I.Weight) SuggestionWeight
                                    From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                    Where W.TransDate Between '".$bln1."' And '".$bln2."' 
                                    Group By I.FinishGood, I.Carat ) A On T.Product = A.FinishGood And T.Carat = A.Carat
                        Left Join ( Select FinishGood, Carat, Count(IDM) SuggestionWaxTree From (
                                    Select Distinct I.FinishGood, I.Carat,I.IDM
                                    From WorkSuggestion W Join WorkSuggestionItem I On W.ID = I.IDM
                                    Where W.TransDate Between '".$bln1."' And '".$bln2."'
                                    ) A Group By FinishGood, Carat ) B On T.Product = B.FinishGood And T.Carat = B.Carat
                                    WHERE SS.Description = '".$kate."'
                                    AND C.ID = ".$kadar."
                        Order By P.SW, C.Description
                        ");
                        //dd($data);
            }
            //dd($data);
            $returnHTML = view('Penjualan.Informasi.MPC.FormOrderProduksi.formorder', compact('data'))->render();
            return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }elseif($jenis == 2){
            //dd($tgl1);
            if(!empty($id1) && !empty($id2) && !empty($tgl1) && !empty($tgl2)){
                $data = FacadesDB::connection('erp')->select("
                Select S.ID, S.TransDate, S.RequireDate, I.Ordinal, I.Customer, Cast(M.SW As Char) MSW, M.Description Model, I.Enamel, I.Slep, I.Marking, I.VarP,
                    ConCat(Year(S.TransDate) - 2000, ' - ', Date_Format(S.TransDate, '%m'), ' - ', MonthName(S.TransDate)) Month, S.Urgent, F.SW FinishGood,
                    X.SW Category, Cast(P.SW As Char) PSW, P.Description Product, Cast(R.Description As Char) Carat, S.Purpose, O.TransDate OrderDate,
                    If(S.Period = 'Jan', '01-Jan', If(S.Period = 'Feb', '01-Feb', If(S.Period = 'Mar', '03-Mar', If(S.Period = 'Apr', '04-Apr',
                    If(S.Period = 'May', '05-May', If(S.Period = 'Jun', '06-Jun', If(S.Period = 'Jul', '07-Jul', If(S.Period = 'Agt', '08-Agt',
                    If(S.Period = 'Sep', '09-Sep', If(S.Period = 'Okt', '10-Okt', If(S.Period = 'Nov', '11-Nov', If(S.Period = 'Des', '12-Des', '')))))))))))) Period,
                    I.Qty, ROUND(I.Weight, 2) Weight, I.Note, O.SW WorkOrder, O.SWPurpose, I.WaxNote, I.StoneNote, I.TotalStone, I.TotalInject, I.TotalPoles, I.TotalPatri,
                    I.TotalPUK, ROUND(I.WeightPcs, 2) WeightProduct, I.SpecialNote, I.FinishingNote, I.GTNote, I.KikirNote,
                            CASE WHEN M.SW IN (
                                'AKLC1',
                                'ARC1',
                                'ATCCX05',
                                'ATCCX1',
                                'ATCP05',
                                'ATCP1',
                                'CACP05',
                                'CACP1',
                                'CAPC05',
                                'CAPC1',
                                'GPAC2',
                                'GPAC3',
                                'GOC1',
                                'GOC15',
                                'GOC2',
                                'LTC05',
                                'LTC1',
                                'CCCX05',
                                'ARMNC1',
                                'GOCX1',
                                'GOCX15'
                            ) THEN (I.Qty*2) ELSE 0 END AS QtyEnm
                From WorkSuggestion S
                Join WorkSuggestionItem I On S.ID = I.IDM And I.Active <> 'C'
                Join Product P On I.Product = P.ID
                Join ProductCarat R On I.Carat = R.ID
                Left Join Product F On I.FinishGood = F.ID
                Left Join ShortText X On F.Color = X.ID
                Left Join Product M On P.Model = M.ID
                Left Join WorkOrderItem W On W.WorkSuggestion = I.IDM And W.WorkSuggestionOrd = I.Ordinal
                Left Join WorkOrder O On W.IDM = O.ID
                Where S.Active <> 'C'
                And S.ID Between ".$id1." And ".$id2." And S.TransDate Between '".$tgl1."' And '".$tgl2."'
                Order By S.ID, I.Ordinal");
                //dd($data);
            }elseif(!empty($id1) && !empty($id2) && empty($tgl1) && empty($tgl2)){
                $data = FacadesDB::connection('erp')->select("
                Select S.ID, S.TransDate, S.RequireDate, I.Ordinal, I.Customer, Cast(M.SW As Char) MSW, M.Description Model, I.Enamel, I.Slep, I.Marking, I.VarP,
                    ConCat(Year(S.TransDate) - 2000, ' - ', Date_Format(S.TransDate, '%m'), ' - ', MonthName(S.TransDate)) Month, S.Urgent, F.SW FinishGood,
                    X.SW Category, Cast(P.SW As Char) PSW, P.Description Product, Cast(R.Description As Char) Carat, S.Purpose, O.TransDate OrderDate,
                    If(S.Period = 'Jan', '01-Jan', If(S.Period = 'Feb', '01-Feb', If(S.Period = 'Mar', '03-Mar', If(S.Period = 'Apr', '04-Apr',
                    If(S.Period = 'May', '05-May', If(S.Period = 'Jun', '06-Jun', If(S.Period = 'Jul', '07-Jul', If(S.Period = 'Agt', '08-Agt',
                    If(S.Period = 'Sep', '09-Sep', If(S.Period = 'Okt', '10-Okt', If(S.Period = 'Nov', '11-Nov', If(S.Period = 'Des', '12-Des', '')))))))))))) Period,
                    I.Qty, ROUND(I.Weight, 2) Weight, I.Note, O.SW WorkOrder, O.SWPurpose, I.WaxNote, I.StoneNote, I.TotalStone, I.TotalInject, I.TotalPoles, I.TotalPatri,
                    I.TotalPUK, ROUND(I.WeightPcs, 2) WeightProduct, I.SpecialNote, I.FinishingNote, I.GTNote, I.KikirNote,
                            CASE WHEN M.SW IN (
                                'AKLC1',
                                'ARC1',
                                'ATCCX05',
                                'ATCCX1',
                                'ATCP05',
                                'ATCP1',
                                'CACP05',
                                'CACP1',
                                'CAPC05',
                                'CAPC1',
                                'GPAC2',
                                'GPAC3',
                                'GOC1',
                                'GOC15',
                                'GOC2',
                                'LTC05',
                                'LTC1',
                                'CCCX05',
                                'ARMNC1',
                                'GOCX1',
                                'GOCX15'
                            ) THEN (I.Qty*2) ELSE 0 END AS QtyEnm
                From WorkSuggestion S
                Join WorkSuggestionItem I On S.ID = I.IDM And I.Active <> 'C'
                Join Product P On I.Product = P.ID
                Join ProductCarat R On I.Carat = R.ID
                Left Join Product F On I.FinishGood = F.ID
                Left Join ShortText X On F.Color = X.ID
                Left Join Product M On P.Model = M.ID
                Left Join WorkOrderItem W On W.WorkSuggestion = I.IDM And W.WorkSuggestionOrd = I.Ordinal
                Left Join WorkOrder O On W.IDM = O.ID
                Where S.Active <> 'C'
                And S.ID Between ".$id1." And ".$id2." 
                Order By S.ID, I.Ordinal");
                //dd($data);
            }elseif(empty($id1) && empty($id2) && !empty($tgl1) && !empty($tgl2)){
                $data = FacadesDB::connection('erp')->select("
                Select S.ID, S.TransDate, S.RequireDate, I.Ordinal, I.Customer, Cast(M.SW As Char) MSW, M.Description Model, I.Enamel, I.Slep, I.Marking, I.VarP,
                    ConCat(Year(S.TransDate) - 2000, ' - ', Date_Format(S.TransDate, '%m'), ' - ', MonthName(S.TransDate)) Month, S.Urgent, F.SW FinishGood,
                    X.SW Category, Cast(P.SW As Char) PSW, P.Description Product, Cast(R.Description As Char) Carat, S.Purpose, O.TransDate OrderDate,
                    If(S.Period = 'Jan', '01-Jan', If(S.Period = 'Feb', '01-Feb', If(S.Period = 'Mar', '03-Mar', If(S.Period = 'Apr', '04-Apr',
                    If(S.Period = 'May', '05-May', If(S.Period = 'Jun', '06-Jun', If(S.Period = 'Jul', '07-Jul', If(S.Period = 'Agt', '08-Agt',
                    If(S.Period = 'Sep', '09-Sep', If(S.Period = 'Okt', '10-Okt', If(S.Period = 'Nov', '11-Nov', If(S.Period = 'Des', '12-Des', '')))))))))))) Period,
                    I.Qty, ROUND(I.Weight, 2) Weight, I.Note, O.SW WorkOrder, O.SWPurpose, I.WaxNote, I.StoneNote, I.TotalStone, I.TotalInject, I.TotalPoles, I.TotalPatri,
                    I.TotalPUK, ROUND(I.WeightPcs, 2) WeightProduct, I.SpecialNote, I.FinishingNote, I.GTNote, I.KikirNote,
                            CASE WHEN M.SW IN (
                                'AKLC1',
                                'ARC1',
                                'ATCCX05',
                                'ATCCX1',
                                'ATCP05',
                                'ATCP1',
                                'CACP05',
                                'CACP1',
                                'CAPC05',
                                'CAPC1',
                                'GPAC2',
                                'GPAC3',
                                'GOC1',
                                'GOC15',
                                'GOC2',
                                'LTC05',
                                'LTC1',
                                'CCCX05',
                                'ARMNC1',
                                'GOCX1',
                                'GOCX15'
                            ) THEN (I.Qty*2) ELSE 0 END AS QtyEnm
                From WorkSuggestion S
                Join WorkSuggestionItem I On S.ID = I.IDM And I.Active <> 'C'
                Join Product P On I.Product = P.ID
                Join ProductCarat R On I.Carat = R.ID
                Left Join Product F On I.FinishGood = F.ID
                Left Join ShortText X On F.Color = X.ID
                Left Join Product M On P.Model = M.ID
                Left Join WorkOrderItem W On W.WorkSuggestion = I.IDM And W.WorkSuggestionOrd = I.Ordinal
                Left Join WorkOrder O On W.IDM = O.ID
                Where S.Active <> 'C'
                And S.TransDate Between '".$tgl1."' And '".$tgl2."'
                Order By S.ID, I.Ordinal");
               //dd($data);
            }elseif(empty($id1) && empty($id2) && empty($tgl1) && empty($tgl2)){
                $data = FacadesDB::connection('erp')->select("
                Select S.ID, S.TransDate, S.RequireDate, I.Ordinal, I.Customer, Cast(M.SW As Char) MSW, M.Description Model, I.Enamel, I.Slep, I.Marking, I.VarP,
                    ConCat(Year(S.TransDate) - 2000, ' - ', Date_Format(S.TransDate, '%m'), ' - ', MonthName(S.TransDate)) Month, S.Urgent, F.SW FinishGood,
                    X.SW Category, Cast(P.SW As Char) PSW, P.Description Product, Cast(R.Description As Char) Carat, S.Purpose, O.TransDate OrderDate,
                    If(S.Period = 'Jan', '01-Jan', If(S.Period = 'Feb', '01-Feb', If(S.Period = 'Mar', '03-Mar', If(S.Period = 'Apr', '04-Apr',
                    If(S.Period = 'May', '05-May', If(S.Period = 'Jun', '06-Jun', If(S.Period = 'Jul', '07-Jul', If(S.Period = 'Agt', '08-Agt',
                    If(S.Period = 'Sep', '09-Sep', If(S.Period = 'Okt', '10-Okt', If(S.Period = 'Nov', '11-Nov', If(S.Period = 'Des', '12-Des', '')))))))))))) Period,
                    I.Qty, ROUND(I.Weight, 2) Weight, I.Note, O.SW WorkOrder, O.SWPurpose, I.WaxNote, I.StoneNote, I.TotalStone, I.TotalInject, I.TotalPoles, I.TotalPatri,
                    I.TotalPUK, ROUND(I.WeightPcs, 2) WeightProduct, I.SpecialNote, I.FinishingNote, I.GTNote, I.KikirNote,
                    CASE WHEN M.SW IN (
                        'AKLC1',
                        'ARC1',
                        'ATCCX05',
                        'ATCCX1',
                        'ATCP05',
                        'ATCP1',
                        'CACP05',
                        'CACP1',
                        'CAPC05',
                        'CAPC1',
                        'GPAC2',
                        'GPAC3',
                        'GOC1',
                        'GOC15',
                        'GOC2',
                        'LTC05',
                        'LTC1',
                        'CCCX05',
                        'ARMNC1',
                        'GOCX1',
                        'GOCX15'
                    ) THEN (I.Qty*2) ELSE 0 END AS QtyEnm
                From WorkSuggestion S
                Join WorkSuggestionItem I On S.ID = I.IDM And I.Active <> 'C'
                Join Product P On I.Product = P.ID
                Join ProductCarat R On I.Carat = R.ID
                Left Join Product F On I.FinishGood = F.ID
                Left Join ShortText X On F.Color = X.ID
                Left Join Product M On P.Model = M.ID
                Left Join WorkOrderItem W On W.WorkSuggestion = I.IDM And W.WorkSuggestionOrd = I.Ordinal
                Left Join WorkOrder O On W.IDM = O.ID
                Where S.Active <> 'C' And S.TransDate Between '".$bln1."' And '".$bln2."'
                Order By S.ID, I.Ordinal");
                //dd($data);
            }

            // $data2 = [
            //     'Jumlah' => 0,
            //     'JumlahEnm' => 0,
            //     'Berat' => 0,
            //     'BrtPcs' => 0,
            //     'Batu' => 0,
            //     'Inject' => 0,
            //     'Poles' => 0,
            //     'Patri' => 0,
            //     'PUK' => 0
               
            //     ];

            // foreach ($data as $key => $value) {
            //         $data2['Jumlah'] +=$value->Qty;
            //         $data2['JumlahEnm'] +=$value->QtyEnm;
            //         $data2['Berat'] +=$value->Weight;
            //         $data2['BrtPcs'] +=$value->WeightProduct;
            //         $data2['Batu'] +=$value->TotalStone;
            //         $data2['Inject'] +=$value->TotalInject;
            //         $data2['Poles'] +=$value->TotalPoles;
            //         $data2['Patri'] +=$value->TotalPatri;
            //         $data2['PUK'] +=$value->TotalPUK;
                  
            //     //die(print_r($TotalGrand));
            // }

            // return response()->json(["tampil"=>$data],200); 
            //dd($data);
            $returnHTML = view('Penjualan.Informasi.MPC.FormOrderProduksi.formtargetpermintaan')->render();
            return response()->json(array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'tampil'=> $data ) );
            
        }

    }
}
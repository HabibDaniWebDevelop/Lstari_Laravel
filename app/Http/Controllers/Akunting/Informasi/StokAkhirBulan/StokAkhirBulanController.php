<?php

namespace App\Http\Controllers\Akunting\Informasi\StokAkhirBulan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;



class StokAkhirBulanController extends Controller
{

    public function index(){
        return view('Akunting.Informasi.StokAkhirBulan.index');
    }

    public function formOpname(){
        $returnHTML =  view('Akunting.Informasi.StokAkhirBulan.dataopname')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function dailystock(){
        $returnHTML =  view('Akunting.Informasi.StokAkhirBulan.data')->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    public function setYear(Request $request){
        $thn = $request->thn;
        $data = FacadesDB::connection('erp')->select("
        SELECT SW, DateEnd AS Tanggal,
                CONCAT(CASE WHEN SWOrdinal = 1 THEN 'Januari'
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
                END, ' | ', DateEnd) AS Bulan
        FROM workperiod 
        WHERE SUBSTRING_INDEX(DateEnd, '-', 1) = '".$thn."' AND Type = 'P'
        ");
       
        $returnHTML = view('Akunting.Informasi.StokAkhirBulan.databulan', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }

    // public function setYear2(Request $request){
    //     $thn = $request->thn;
    //     $data = FacadesDB::connection('erp')->select("
    //     SELECT SW, SWOrdinal AS Tanggal,
    //     CASE WHEN SWOrdinal = 1 THEN 'Januari'
    //      WHEN  SWOrdinal = 2 THEN 'Februari'
    //      WHEN  SWOrdinal = 3 THEN 'Maret'
    //      WHEN  SWOrdinal = 4 THEN 'April'
    //      WHEN  SWOrdinal = 5 THEN 'Mei'
    //      WHEN  SWOrdinal = 6 THEN 'Juni'
    //      WHEN  SWOrdinal = 7 THEN 'Juli'
    //      WHEN  SWOrdinal = 8 THEN 'Agustus'
    //      WHEN  SWOrdinal = 9 THEN 'September'
    //      WHEN  SWOrdinal = 10 THEN 'Oktober'
    //      WHEN  SWOrdinal = 11 THEN 'November'
    //      WHEN  SWOrdinal = 12 THEN 'Desember'
    //      END AS Bulan
    //     FROM workperiod  
    //       WHERE SUBSTRING_INDEX(DateEnd, '-', 1) = ".$thn." AND Type = 'P'
    //     ");
       
    //     $returnHTML = view('Akunting.Informasi.StokAkhirBulan.data', compact('data'))->render();
    //     return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    // }
    

    // public function getworkperiod(){
    //     $data = FacadesDB::connection('erp')->select("
    //     SELECT SW, DateEnd AS Tanggal,
    //             CASE WHEN SWOrdinal = 1 THEN 'Januari'
    //             WHEN  SWOrdinal = 2 THEN 'Februari'
    //             WHEN  SWOrdinal = 3 THEN 'Maret'
    //             WHEN  SWOrdinal = 4 THEN 'April'
    //             WHEN  SWOrdinal = 5 THEN 'Mei'
    //             WHEN  SWOrdinal = 6 THEN 'Juni'
    //             WHEN  SWOrdinal = 7 THEN 'Juli'
    //             WHEN  SWOrdinal = 8 THEN 'Agustus'
    //             WHEN  SWOrdinal = 9 THEN 'September'
    //             WHEN  SWOrdinal = 10 THEN 'Oktober'
    //             WHEN  SWOrdinal = 11 THEN 'November'
    //             WHEN  SWOrdinal = 12 THEN 'Desember'
    //             END AS Bulan
    //     FROM workperiod 
    //     WHERE SWYear = 22 AND Type = 'P'
    //     ");
    //     // $data_return = [
    //     //     "data"=>$data
            
    //     // ];
    //     return view("Akunting.Informasi.StokAkhirBulan.data",compact('data'));
    // }



    public function gettingStokAkhirBulan(Request $request)
    {
        $bln = $request->bln;
        $location = session('location');
        $exbln = explode('-', $bln);
        $indexbln = $exbln[0];
        $curYear = date('Y'); 
        if($indexbln == $curYear){
            $db = 'erp';
        }else{
            $db = 'erp22';
        }
       
        $data = FacadesDB::connection('erp')->select("SELECT 
        DD.Description KADAR,
        ROUND(SUM( IF( AA.Location = 3, WeightStock, 0) ) ,2)AS Stockist, 
        ROUND(SUM( IF( AA.Location = 11, WeightStock, 0) ),2) AS StockLama, 
        ROUND(SUM( IF( AA.Location = 13, WeightStock, 0) ),2) AS ReturKembali,
        ROUND(SUM( IF( AA.Location = 14, WeightStock, 0) ),2) AS ReturKembaliSalesman,
        ROUND(SUM( IF( AA.Location = 16, WeightStock, 0) ),2) AS DianA,
        ROUND(SUM( IF( AA.Location = 27, WeightStock, 0) ),2) AS ReturMarketing,
        ROUND(SUM( IF( AA.Location = 30, WeightStock, 0) ),2) AS Juniar,
        ROUND(SUM( IF( AA.Location = 32, WeightStock, 0) ),2) AS ReparasiCustomer,
        ROUND(SUM( IF( AA.Location = 34, WeightStock, 0) ),2) AS PembayaranPenjualan,
        ROUND(SUM( IF( AA.Location = 36, WeightStock, 0) ),2) AS Tri,
        ROUND(SUM( IF( AA.Location = 39, WeightStock, 0) ),2) AS BarangContoh,
        ROUND(SUM( IF( AA.Location = 42, WeightStock, 0) ),2) AS Peminjaman,
        ROUND(SUM( IF( AA.Location = 61, WeightStock, 0) ),2) AS Konsinyasi,
        ROUND(SUM( IF( AA.Location = 62, WeightStock, 0) ),2) AS Pengiriman,
        ROUND(SUM( IF( AA.Location = 72, WeightStock, 0) ),2) AS Simon,
        ROUND(SUM( IF( AA.Location = 74, WeightStock, 0) ),2) AS WebPortal,
        ROUND(SUM( IF( AA.Location = 76, WeightStock, 0) ),2) AS Online,
        ROUND(SUM( IF( AA.Location = 81, WeightStock, 0) ),2) AS Agung,
        ROUND(SUM( IF( AA.Location = 84, WeightStock, 0) ),2) AS Aldy,
        ROUND(SUM( IF( AA.Location = 85, WeightStock, 0) ),2) AS SADigital,
        ROUND(SUM( IF( AA.Location = 88, WeightStock, 0) ),2) AS Pameran,
        ROUND(SUM( IF( AA.Location = 89, WeightStock, 0) ),2) AS ReparasiCustomerSalesman,
        ROUND(SUM( IF( AA.Location = 91, WeightStock, 0) ),2) AS Abimanyu,
        SUM( WeightStock ) AS totalsamping
    
    
    FROM
        dailystock AA
        JOIN dailystockitem BB ON BB.IDM = AA.ID
        JOIN location CC ON CC.ID = AA.Location
        LEFT JOIN productcarat DD ON DD.ID = BB.Carat
    WHERE AA.TransDate = '".$bln."' AND AA.Module IN ('S', 'C') AND AA.Active = 'P' AND AA.StartStock = 'Y' AND BB.Product <> 13540 AND DD.ID NOT IN (15,17)
    GROUP BY DD.ID
    ORDER BY DD.ID
    ");

    $dataother = FacadesDB::connection('erp')->select("SELECT 
    DD.Description KADAR,
    ROUND(SUM( IF( AA.Location = 3, WeightStock, 0) ) ,2)AS Stockist, 
    ROUND(SUM( IF( AA.Location = 11, WeightStock, 0) ),2) AS StockLama, 
    ROUND(SUM( IF( AA.Location = 13, WeightStock, 0) ),2) AS ReturKembali,
    ROUND(SUM( IF( AA.Location = 14, WeightStock, 0) ),2) AS ReturKembaliSalesman,
    ROUND(SUM( IF( AA.Location = 16, WeightStock, 0) ),2) AS DianA,
    ROUND(SUM( IF( AA.Location = 27, WeightStock, 0) ),2) AS ReturMarketing,
    ROUND(SUM( IF( AA.Location = 30, WeightStock, 0) ),2) AS Juniar,
    ROUND(SUM( IF( AA.Location = 32, WeightStock, 0) ),2) AS ReparasiCustomer,
    ROUND(SUM( IF( AA.Location = 34, WeightStock, 0) ),2) AS PembayaranPenjualan,
    ROUND(SUM( IF( AA.Location = 36, WeightStock, 0) ),2) AS Tri,
    ROUND(SUM( IF( AA.Location = 39, WeightStock, 0) ),2) AS BarangContoh,
    ROUND(SUM( IF( AA.Location = 42, WeightStock, 0) ),2) AS Peminjaman,
    ROUND(SUM( IF( AA.Location = 61, WeightStock, 0) ),2) AS Konsinyasi,
    ROUND(SUM( IF( AA.Location = 62, WeightStock, 0) ),2) AS Pengiriman,
    ROUND(SUM( IF( AA.Location = 72, WeightStock, 0) ),2) AS Simon,
    ROUND(SUM( IF( AA.Location = 74, WeightStock, 0) ),2) AS WebPortal,
    ROUND(SUM( IF( AA.Location = 76, WeightStock, 0) ),2) AS Online,
    ROUND(SUM( IF( AA.Location = 81, WeightStock, 0) ),2) AS Agung,
    ROUND(SUM( IF( AA.Location = 84, WeightStock, 0) ),2) AS Aldy,
    ROUND(SUM( IF( AA.Location = 85, WeightStock, 0) ),2) AS SADigital,
    ROUND(SUM( IF( AA.Location = 88, WeightStock, 0) ),2) AS Pameran,
    ROUND(SUM( IF( AA.Location = 89, WeightStock, 0) ),2) AS ReparasiCustomerSalesman,
    ROUND(SUM( IF( AA.Location = 91, WeightStock, 0) ),2) AS Abimanyu,
    SUM( WeightStock ) AS totalsamping


FROM
    dailystock AA
    JOIN dailystockitem BB ON BB.IDM = AA.ID
    JOIN location CC ON CC.ID = AA.Location
    LEFT JOIN productcarat DD ON DD.ID = BB.Carat
WHERE AA.TransDate = '".$bln."' AND AA.Module IN ('S', 'C') AND AA.Active = 'P' AND AA.StartStock = 'Y' AND BB.Product <> 13540 AND DD.ID IN (15,17)
GROUP BY DD.ID
ORDER BY DD.ID
");


$datarongsok = FacadesDB::connection('erp')->select("SELECT 
DD.Description KADAR,
ROUND(SUM( IF( AA.Location = 3, WeightStock, 0) ) ,2)AS Stockist, 
ROUND(SUM( IF( AA.Location = 11, WeightStock, 0) ),2) AS StockLama, 
ROUND(SUM( IF( AA.Location = 13, WeightStock, 0) ),2) AS ReturKembali,
ROUND(SUM( IF( AA.Location = 14, WeightStock, 0) ),2) AS ReturKembaliSalesman,
ROUND(SUM( IF( AA.Location = 16, WeightStock, 0) ),2) AS DianA,
ROUND(SUM( IF( AA.Location = 27, WeightStock, 0) ),2) AS ReturMarketing,
ROUND(SUM( IF( AA.Location = 30, WeightStock, 0) ),2) AS Juniar,
ROUND(SUM( IF( AA.Location = 32, WeightStock, 0) ),2) AS ReparasiCustomer,
ROUND(SUM( IF( AA.Location = 34, WeightStock, 0) ),2) AS PembayaranPenjualan,
ROUND(SUM( IF( AA.Location = 36, WeightStock, 0) ),2) AS Tri,
ROUND(SUM( IF( AA.Location = 39, WeightStock, 0) ),2) AS BarangContoh,
ROUND(SUM( IF( AA.Location = 42, WeightStock, 0) ),2) AS Peminjaman,
ROUND(SUM( IF( AA.Location = 61, WeightStock, 0) ),2) AS Konsinyasi,
ROUND(SUM( IF( AA.Location = 62, WeightStock, 0) ),2) AS Pengiriman,
ROUND(SUM( IF( AA.Location = 72, WeightStock, 0) ),2) AS Simon,
ROUND(SUM( IF( AA.Location = 74, WeightStock, 0) ),2) AS WebPortal,
ROUND(SUM( IF( AA.Location = 76, WeightStock, 0) ),2) AS Online,
ROUND(SUM( IF( AA.Location = 81, WeightStock, 0) ),2) AS Agung,
ROUND(SUM( IF( AA.Location = 84, WeightStock, 0) ),2) AS Aldy,
ROUND(SUM( IF( AA.Location = 85, WeightStock, 0) ),2) AS SADigital,
ROUND(SUM( IF( AA.Location = 88, WeightStock, 0) ),2) AS Pameran,
ROUND(SUM( IF( AA.Location = 89, WeightStock, 0) ),2) AS ReparasiCustomerSalesman,
ROUND(SUM( IF( AA.Location = 91, WeightStock, 0) ),2) AS Abimanyu,
SUM( WeightStock ) AS totalsamping


FROM
dailystock AA
JOIN dailystockitem BB ON BB.IDM = AA.ID
JOIN location CC ON CC.ID = AA.Location
LEFT JOIN productcarat DD ON DD.ID = BB.Carat
WHERE AA.TransDate = '".$bln."' AND AA.Module IN ('S', 'C') AND AA.Active = 'P' AND AA.StartStock = 'Y' AND BB.Product = 13540 AND DD.ID NOT IN (15,17)
GROUP BY DD.ID
ORDER BY DD.ID
");

$dataotherrongsok = FacadesDB::connection('erp')->select("SELECT 
DD.Description KADAR,
ROUND(SUM( IF( AA.Location = 3, WeightStock, 0) ) ,2)AS Stockist, 
ROUND(SUM( IF( AA.Location = 11, WeightStock, 0) ),2) AS StockLama, 
ROUND(SUM( IF( AA.Location = 13, WeightStock, 0) ),2) AS ReturKembali,
ROUND(SUM( IF( AA.Location = 14, WeightStock, 0) ),2) AS ReturKembaliSalesman,
ROUND(SUM( IF( AA.Location = 16, WeightStock, 0) ),2) AS DianA,
ROUND(SUM( IF( AA.Location = 27, WeightStock, 0) ),2) AS ReturMarketing,
ROUND(SUM( IF( AA.Location = 30, WeightStock, 0) ),2) AS Juniar,
ROUND(SUM( IF( AA.Location = 32, WeightStock, 0) ),2) AS ReparasiCustomer,
ROUND(SUM( IF( AA.Location = 34, WeightStock, 0) ),2) AS PembayaranPenjualan,
ROUND(SUM( IF( AA.Location = 36, WeightStock, 0) ),2) AS Tri,
ROUND(SUM( IF( AA.Location = 39, WeightStock, 0) ),2) AS BarangContoh,
ROUND(SUM( IF( AA.Location = 42, WeightStock, 0) ),2) AS Peminjaman,
ROUND(SUM( IF( AA.Location = 61, WeightStock, 0) ),2) AS Konsinyasi,
ROUND(SUM( IF( AA.Location = 62, WeightStock, 0) ),2) AS Pengiriman,
ROUND(SUM( IF( AA.Location = 72, WeightStock, 0) ),2) AS Simon,
ROUND(SUM( IF( AA.Location = 74, WeightStock, 0) ),2) AS WebPortal,
ROUND(SUM( IF( AA.Location = 76, WeightStock, 0) ),2) AS Online,
ROUND(SUM( IF( AA.Location = 81, WeightStock, 0) ),2) AS Agung,
ROUND(SUM( IF( AA.Location = 84, WeightStock, 0) ),2) AS Aldy,
ROUND(SUM( IF( AA.Location = 85, WeightStock, 0) ),2) AS SADigital,
ROUND(SUM( IF( AA.Location = 88, WeightStock, 0) ),2) AS Pameran,
ROUND(SUM( IF( AA.Location = 89, WeightStock, 0) ),2) AS ReparasiCustomerSalesman,
ROUND(SUM( IF( AA.Location = 91, WeightStock, 0) ),2) AS Abimanyu,
SUM( WeightStock ) AS totalsamping


FROM
dailystock AA
JOIN dailystockitem BB ON BB.IDM = AA.ID
JOIN location CC ON CC.ID = AA.Location
LEFT JOIN productcarat DD ON DD.ID = BB.Carat
WHERE AA.TransDate = '".$bln."' AND AA.Module IN ('S', 'C') AND AA.Active = 'P' AND AA.StartStock = 'Y' AND BB.Product = 13540 AND DD.ID IN (15,17)
GROUP BY DD.ID
ORDER BY DD.ID
");


$data2 = [
'TotalStockist' => 0,
'TotalStockLama' => 0,
'TotalReturKembali' => 0,
'TotalReturKembaliSalesman' => 0,
'TotalDianA' => 0,
'TotalReturMarketing' => 0,
'TotalJuniar' => 0,
'TotalReparasiCustomer' => 0,
'TotalPembayaranPenjualan' => 0,
'TotalTri' => 0,
'TotalBarangContoh' => 0,
'TotalPeminjaman' => 0,
'TotalKonsinyasi' => 0,
'TotalPengiriman' => 0,
'TotalSimon' => 0,
'TotalWebPortal' => 0,
'TotalOnline' => 0,
'TotalAgung' => 0,
'TotalAldy' => 0,
'TotalSADigital' => 0,
'TotalPameran' => 0,
'TotalReparasiCustomerSalesman' => 0,
'TotalAbimanyu' => 0,
'TotalGrand' => 0
];

    foreach ($data as $key => $value) {
         $data2['TotalStockist'] +=$value->Stockist;
         $data2['TotalStockLama'] +=$value->StockLama;
         $data2['TotalReturKembali'] +=$value->ReturKembali;
         $data2['TotalReturKembaliSalesman'] +=$value->ReturKembaliSalesman;
         $data2['TotalDianA'] +=$value->DianA;
         $data2['TotalReturMarketing'] +=$value->ReturMarketing;
         $data2['TotalJuniar'] +=$value->Juniar;
         $data2['TotalReparasiCustomer'] +=$value->ReparasiCustomer;
         $data2['TotalPembayaranPenjualan'] +=$value->PembayaranPenjualan;
         $data2['TotalTri'] +=$value->Tri;
         $data2['TotalBarangContoh'] +=$value->BarangContoh;
         $data2['TotalPeminjaman'] +=$value->Peminjaman;
         $data2['TotalKonsinyasi'] +=$value->Konsinyasi;
         $data2['TotalPengiriman'] +=$value->Pengiriman;
         $data2['TotalSimon'] +=$value->Simon;
         $data2['TotalWebPortal'] +=$value->WebPortal;
         $data2['TotalOnline'] +=$value->Online;
         $data2['TotalAgung'] +=$value->Agung;
         $data2['TotalAldy'] +=$value->Aldy;
         $data2['TotalSADigital'] +=$value->SADigital;
         $data2['TotalPameran'] +=$value->Pameran;
         $data2['TotalReparasiCustomerSalesman'] +=$value->ReparasiCustomerSalesman;
         $data2['TotalAbimanyu'] +=$value->Abimanyu;
         $data2['TotalGrand'] +=$value->totalsamping;
        //die(print_r($TotalGrand));
    }


    $data2rongsok = [
        'TotalStockist' => 0,
        'TotalStockLama' => 0,
        'TotalReturKembali' => 0,
        'TotalReturKembaliSalesman' => 0,
        'TotalDianA' => 0,
        'TotalReturMarketing' => 0,
        'TotalJuniar' => 0,
        'TotalReparasiCustomer' => 0,
        'TotalPembayaranPenjualan' => 0,
        'TotalTri' => 0,
        'TotalBarangContoh' => 0,
        'TotalPeminjaman' => 0,
        'TotalKonsinyasi' => 0,
        'TotalPengiriman' => 0,
        'TotalSimon' => 0,
        'TotalWebPortal' => 0,
        'TotalOnline' => 0,
        'TotalAgung' => 0,
        'TotalAldy' => 0,
        'TotalSADigital' => 0,
        'TotalPameran' => 0,
        'TotalReparasiCustomerSalesman' => 0,
        'TotalAbimanyu' => 0,
        'TotalGrand' => 0
        ];
        
            foreach ($datarongsok as $key => $value) {
                 $data2rongsok['TotalStockist'] +=$value->Stockist;
                 $data2rongsok['TotalStockLama'] +=$value->StockLama;
                 $data2rongsok['TotalReturKembali'] +=$value->ReturKembali;
                 $data2rongsok['TotalReturKembaliSalesman'] +=$value->ReturKembaliSalesman;
                 $data2rongsok['TotalDianA'] +=$value->DianA;
                 $data2rongsok['TotalReturMarketing'] +=$value->ReturMarketing;
                 $data2rongsok['TotalJuniar'] +=$value->Juniar;
                 $data2rongsok['TotalReparasiCustomer'] +=$value->ReparasiCustomer;
                 $data2rongsok['TotalPembayaranPenjualan'] +=$value->PembayaranPenjualan;
                 $data2rongsok['TotalTri'] +=$value->Tri;
                 $data2rongsok['TotalBarangContoh'] +=$value->BarangContoh;
                 $data2rongsok['TotalPeminjaman'] +=$value->Peminjaman;
                 $data2rongsok['TotalKonsinyasi'] +=$value->Konsinyasi;
                 $data2rongsok['TotalPengiriman'] +=$value->Pengiriman;
                 $data2rongsok['TotalSimon'] +=$value->Simon;
                 $data2rongsok['TotalWebPortal'] +=$value->WebPortal;
                 $data2rongsok['TotalOnline'] +=$value->Online;
                 $data2rongsok['TotalAgung'] +=$value->Agung;
                 $data2rongsok['TotalAldy'] +=$value->Aldy;
                 $data2rongsok['TotalSADigital'] +=$value->SADigital;
                 $data2rongsok['TotalPameran'] +=$value->Pameran;
                 $data2rongsok['TotalReparasiCustomerSalesman'] +=$value->ReparasiCustomerSalesman;
                 $data2rongsok['TotalAbimanyu'] +=$value->Abimanyu;
                 $data2rongsok['TotalGrand'] +=$value->totalsamping;
                //die(print_r($TotalGrand));
            }

    //die(print_r($data2));

        $returnHTML = view('Akunting.Informasi.StokAkhirBulan.stok', compact('data', 'data2', 'dataother', 'datarongsok', 'data2rongsok', 'dataotherrongsok'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }


    public function gettingStokAkhirBulanOpname(Request $request)
    {
        $thn = $request->thn;
        $bln = $request->bln;
        $location = session('location');
        $curYear = date('Y'); 
        if($thn == $curYear){
            $db = 'erp';
        }else{
            $db = 'erp22';
        }
        $data = FacadesDB::connection('erp')->select("SELECT  
        DD.Description KADAR,
        ROUND(SUM( IF( AA.Location =3, BB.WeightStock, 0) ),2) AS Stockist,
        ROUND(SUM( IF( AA.Location =4, BB.WeightStock, 0) ),2) AS Kikir,
        ROUND(SUM( IF( AA.Location =7, BB.WeightStock, 0) ),2) AS Cor,
        ROUND(SUM( IF( AA.Location =10, BB.WeightStock, 0) ),2) AS QC,
        ROUND(SUM( IF( AA.Location =11, BB.WeightStock, 0) ),2) AS StockLama,
        ROUND(SUM( IF( AA.Location =12, BB.WeightStock, 0) ),2) AS Poles,
        ROUND(SUM( IF( AA.Location =13, BB.WeightStock, 0) ),2) AS ReturKembali,
        ROUND(SUM( IF( AA.Location =14, BB.WeightStock, 0) ),2) AS ReturKembaliSalesman,
        ROUND(SUM( IF( AA.Location =15, BB.WeightStock, 0) ),2) AS PasangBatu,
        ROUND(SUM( IF( AA.Location =17, BB.WeightStock, 0) ),2) AS GilingTarik,
        ROUND(SUM( IF( AA.Location =21, BB.WeightStock, 0) ),2) AS Batu,
        ROUND(SUM( IF( AA.Location =22, BB.WeightStock, 0) ),2) AS CampurBahan,
        ROUND(SUM( IF( AA.Location =27, BB.WeightStock, 0) ),2) AS ReturMarketing,
        ROUND(SUM( IF( AA.Location =28, BB.WeightStock, 0) ),2) AS MalPerak,
        ROUND(SUM( IF( AA.Location =32, BB.WeightStock, 0) ),2) AS ReparasiCustomer,
        ROUND(SUM( IF( AA.Location =39, BB.WeightStock, 0) ),2) AS BarangContoh,
        ROUND(SUM( IF( AA.Location =42, BB.WeightStock, 0) ),2) AS Peminjaman,
        ROUND(SUM( IF( AA.Location =47, BB.WeightStock, 0) ),2) AS Enamel,
        ROUND(SUM( IF( AA.Location =48, BB.WeightStock, 0) ),2) AS Bombing,
        ROUND(SUM( IF( AA.Location =49, BB.WeightStock, 0) ),2) AS Slep,
        ROUND(SUM( IF( AA.Location =50, BB.WeightStock, 0) ),2) AS Sepuh,
        ROUND(SUM( IF( AA.Location =51, BB.WeightStock, 0) ),2) AS Lilin,
        ROUND(SUM( IF( AA.Location =52, BB.WeightStock, 0) ),2) AS Brush,
        ROUND(SUM( IF( AA.Location =53, BB.WeightStock, 0) ),2) AS Reparasi,
        ROUND(SUM( IF( AA.Location =56, BB.WeightStock, 0) ),2) AS PCB,
        ROUND(SUM( IF( AA.Location =73, BB.WeightStock, 0) ),2) AS TukangLuar,
        ROUND(SUM( IF( AA.Location =74, BB.WeightStock, 0) ),2) AS WebPortal,
        ROUND(SUM( IF( AA.Location =83, BB.WeightStock, 0) ),2) AS Rantai,
        ROUND(SUM( IF( AA.Location =85, BB.WeightStock, 0) ),2) AS SADigital,
        ROUND(SUM( IF( AA.Location =89, BB.WeightStock, 0) ),2) AS ReparasiCustomerSalesman,
        SUM( BB.WeightStock ) AS totalsamping
       
       
       
       FROM stockopname AA 
       JOIN stockopnameitem BB ON BB.IDM = AA.ID
       JOIN location CC ON CC.ID = AA.Location
       JOIN productcarat DD ON DD.ID = BB.Carat
       WHERE AA.Active = 'P' AND Year(AA.TransDate) = '".$thn."' And Month(AA.TransDate) = '".$bln."'
       GROUP BY DD.ID
       ORDER BY DD.ID");

       $data2 = [
        'Stockist' => 0,
        'Kikir' => 0,
        'Cor' => 0,
        'QC' => 0,
        'StockLama' => 0,
        'Poles' => 0,
        'ReturKembali' => 0,
        'ReturKembaliSalesman' => 0,
        'PasangBatu' => 0,
        'GilingTarik' => 0,
        'Batu' => 0,
        'CampurBahan' => 0,
        'ReturMarketing' => 0,
        'MalPerak' => 0,
        'ReparasiCustomer' => 0,
        'BarangContoh' => 0,
        'Peminjaman' => 0,
        'Enamel' => 0,
        'Bombing' => 0,
        'Slep' => 0,
        'Sepuh' => 0,
        'Lilin' => 0,
        'Brush' => 0,
        'Reparasi' => 0,
        'PCB' => 0,
        'TukangLuar' => 0,
        'WebPortal' => 0,
        'Rantai' => 0,
        'SADigital' => 0,
        'ReparasiCustomerSalesman' => 0,
        'TotalGrand' => 0
        ];
        
            foreach ($data as $key => $value) {
                $data2['Stockist'] +=$value->Stockist;
                $data2['Kikir'] +=$value->Kikir;
                $data2['Cor'] +=$value->Cor;
                $data2['QC'] +=$value->QC;
                $data2['StockLama'] +=$value->StockLama;
                $data2['Poles'] +=$value->Poles;
                $data2['ReturKembali'] +=$value->ReturKembali;
                $data2['ReturKembaliSalesman'] +=$value->ReturKembaliSalesman;
                $data2['PasangBatu'] +=$value->PasangBatu;
                $data2['GilingTarik'] +=$value->GilingTarik;
                $data2['Batu'] +=$value->Batu;
                $data2['CampurBahan'] +=$value->CampurBahan;
                $data2['ReturMarketing'] +=$value->ReturMarketing;
                $data2['MalPerak'] +=$value->MalPerak;
                $data2['ReparasiCustomer'] +=$value->ReparasiCustomer;
                $data2['BarangContoh'] +=$value->BarangContoh;
                $data2['Peminjaman'] +=$value->Peminjaman;
                $data2['Enamel'] +=$value->Enamel;
                $data2['Bombing'] +=$value->Bombing;
                $data2['Slep'] +=$value->Slep;
                $data2['Sepuh'] +=$value->Sepuh;
                $data2['Lilin'] +=$value->Lilin;
                $data2['Brush'] +=$value->Brush;
                $data2['Reparasi'] +=$value->Reparasi;
                $data2['PCB'] +=$value->PCB;
                $data2['TukangLuar'] +=$value->TukangLuar;
                $data2['WebPortal'] +=$value->WebPortal;
                $data2['Rantai'] +=$value->Rantai;
                $data2['SADigital'] +=$value->SADigital;
                $data2['ReparasiCustomerSalesman'] +=$value->ReparasiCustomerSalesman;
                $data2['TotalGrand'] +=$value->totalsamping;
                //die(print_r($TotalGrand));
            }

        $returnHTML = view('Akunting.Informasi.StokAkhirBulan.stokopname', compact('data', 'data2'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }


}



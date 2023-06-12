<?php

namespace App\Http\Controllers\Produksi\JadwalKerjaHarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as FacadesDB;
use \DateTime;
use \DateTimeZone;

class RPHProduksiController extends Controller
{
    public function index(){
        $location = session('location');

        if($location == NULL){
            $location = 4;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");
        $tahun = $date->format("Y");

        $query = "SELECT ID 
                    FROM workschedule 
                    WHERE LOCATION = $location AND Active <> 'C' AND EntryDate > '$tahun-01-01'
                    ORDER BY ID DESC, TransDate DESC
                    LIMIT 100
                    ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        $query2 = "SELECT ID, Description, CASE WHEN Product IS NULL THEN '00' ELSE Product END AS Product
                    FROM Operation 
                    WHERE Location = $location AND Active='Y'
                    ORDER BY Description ASC
                    ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        return view('Produksi.JadwalKerjaHarian.RPHProduksi.index', compact('data','rowcount','data2'));
    }

    public function daftarList(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 4;
        }

        $idmnya1 = $request->idmnya1;
        $proses = $request->proses;
        $prosesAll = explode(",", $proses);
        $prosesProduct = $prosesAll[0];
        $prosesID = $prosesAll[1];

        if(!empty($idmnya1)){
            $idmnyaOK = 1;
        }else{
            $idmnyaOK = 0;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $tahun = $date->format("y");
        $year = $date->format("Y");

        // AND WCI.Product = $prosesProduct
        $queryEnamel = "SELECT CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2), '-', IF(TMI.Level3 IS NULL, 'NULL', TMI.Level3), '-', IF(TMI.Level4 IS NULL, 'NULL', TMI.Level4)) kirimkan,
                            CONCAT(TMI.WorkAllocation,'-',TMI.LinkFreq,'-',TMI.LinkOrd) WS, L.Description Dari, OP.Description OPDescription, 
                            TM.TransDate TransDate, TMI.IDM TMno, TMI.Ordinal, P.Color, TMI.Carat, TMI.Qty, FORMAT(TMI.Weight,2) AS Weight, TMI.EnmSurface, TMI.EnmStep, TMI.EnmColor, WO.SW WOSW, P.SW SubCategory, 
                            PC.Description Kadar, ST.Description Kategori, TMI.Note, @rownum := @rownum + 1 AS ID, ST1.Description EnmSurfaceST, ST2.Description EnmStepST, ST3.Description EnmColorST,
                            IF(P.SW IN ('AKLC','AKLC1','AKLC1M','AKLC1T','ARC1','ARC1M','ARC1T','ARMNC','ARMNCM','ARMNCT','ASK','ASKB','ATCC1','ATCC1M','ATCC1T','ATCCX05','ATCCX1','ATCP05','ATCP05M',
                            'ATCP05T','ATCP1','ATCP1M','ATCP1T','GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), TMI.Qty*2, TMI.Qty) AS Pcs
                        FROM TransferRMItem TMI
                            JOIN TransferRM TM ON TM.ID=TMI.IDM
                            LEFT JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            LEFT JOIN Product P ON P.ID=WO.Product
                            LEFT JOIN ProductCarat PC ON TMI.Carat = PC.ID
                            LEFT JOIN ShortText ST ON ST.ID = P.Color
                            JOIN WorkCompletion WC ON WC.WorkAllocation=TMI.WorkAllocation AND WC.Freq=TMI.LinkFreq
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID AND WCI.Ordinal=TMI.LinkOrd
                            LEFT JOIN WorkAllocationItem WAI ON WCI.IDM = WAI.PrevProcess AND WCI.Ordinal = WAI.PrevOrd 
                            LEFT JOIN Location L ON WC.Location = L.ID
                            LEFT JOIN Operation OP ON OP.ID=TMI.Operation
                            LEFT JOIN ShortText ST1 ON ST1.ID=TMI.Level2
                            LEFT JOIN ShortText ST2 ON ST2.ID=TMI.Level3
                            LEFT JOIN ShortText ST3 ON ST3.ID=TMI.Level4
                            CROSS JOIN (SELECT @rownum := 0) r    
                        WHERE 
                            TM.ToLoc=$location
                            AND TM.Active = 'P' 
                            AND TM.TransDate >= '$year-01-01'
                            AND TMI.WorkSchedule IS NULL
                            AND WAI.IDM IS NULL 
                            AND (WCI.Qty <> 0 OR WCI.Weight <> 0 OR WCI.RepairQty <> 0 OR WCI.RepairWeight <> 0)
                        ORDER BY TM.TransDate DESC 
                        ";
             
        $queryOther = "SELECT CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2)) kirimkan,
                            CONCAT(TMI.WorkAllocation,'-',TMI.LinkFreq,'-',TMI.LinkOrd) WS, L.Description Dari, ST1.Description OperationProcess, ST2.Description Level2Process,
                            TM.TransDate TransDate, TMI.IDM TMno, TMI.Ordinal, P.Color, TMI.Carat, TMI.Qty, FORMAT(TMI.Weight,2) Weight, TMI.Operation OperationData, TMI.Level2 Level2Data, WO.SW WOSW, P.SW SubCategory, 
                            PC.Description Kadar, ST.Description Kategori, TMI.Note, @rownum := @rownum + 1 AS ID,
                            IF(P.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%', TMI.Qty*2, TMI.Qty) AS Pcs
                        FROM TransferRMItem TMI
                            JOIN TransferRM TM ON TM.ID=TMI.IDM
                            LEFT JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            LEFT JOIN Product P ON P.ID=WO.Product
                            LEFT JOIN ProductCarat PC ON TMI.Carat = PC.ID
                            LEFT JOIN ShortText ST ON ST.ID = P.Color
                            JOIN WorkCompletion WC ON WC.WorkAllocation=TMI.WorkAllocation AND WC.Freq=TMI.LinkFreq
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID AND WCI.Ordinal=TMI.LinkOrd
                            LEFT JOIN WorkAllocationItem WAI ON WCI.IDM = WAI.PrevProcess AND WCI.Ordinal = WAI.PrevOrd 
                            LEFT JOIN Location L ON WC.Location = L.ID
                            LEFT JOIN Operation ST1 ON ST1.ID=TMI.Operation
                            LEFT JOIN ShortText ST2 ON ST2.ID=TMI.Level2
                            CROSS JOIN (SELECT @rownum := 0) r    
                        WHERE 
                            TM.ToLoc=$location
                            AND TM.Active = 'P' 
                            AND TM.TransDate >= '$year-01-01'
                            AND TMI.WorkSchedule IS NULL
                            AND WAI.IDM IS NULL 
                            AND (WCI.Qty <> 0 OR WCI.Weight <> 0 OR WCI.RepairQty <> 0 OR WCI.RepairWeight <> 0)
                            -- GROUP BY TMI.WorkAllocation, TMI.LinkFreq, TMI.LinkOrd
                        ";

        if($location == 10){
            $queryOther .= "AND WCI.Product = $prosesProduct ";
        }

        $queryOther .= "ORDER BY TM.TransDate DESC";
        
        // dd($queryOther);

        if($location == 47){
            $query = $queryEnamel;
        }else{
            $query = $queryOther;
        }

        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        foreach($data as $datas){
            $rows[] = $datas; 
        }
   
        $data = (!empty($rows) ? $rows : '');
        return response()->json( array('success' => true, 'datalist' => $data, 'update' => $idmnyaOK, 'location' => $location) );

    }

    public function lihat(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 4;
        }

        $idubah = $request->dropdownValue;

        $IDitem1 = "SELECT ID, Remarks, Active, TransDate, Divisi FROM workschedule WHERE ID = $idubah ";
        $dataItem = FacadesDB::connection('erp')->select($IDitem1);

        foreach ($dataItem as $dataItems){}

        $queryEnamel = "SELECT
                            CONCAT( TMI.WorkAllocation, '-', TMI.LinkFreq, '-', TMI.LinkOrd ) WS,
                            TM.TransDate,
                            CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2), '-', IF(TMI.Level3 IS NULL, 'NULL', TMI.Level3), '-', IF(TMI.Level4 IS NULL, 'NULL', TMI.Level4)) kirimkan,
                            P.Description WPW,
                            P.SW PSS,
                            PA.SW Psiap,
                            PC.Description Kadar,
                            TMI.Qty,
                            FORMAT(TMI.Weight,2) Weight,
                            WO.SW WSW,
                            L.Description Lnama,
                            ST.Description Kategori,
                            TMI.Note,
                            P.Color,
                            TMI.Carat,
                            WS.ID idmcuk,
                            CASE WHEN TM.ID IS NULL THEN '-' ELSE TM.ID END AS TMno,  
                            DATE_FORMAT( WS.TransDate, '%d %M %Y' ) TransDate1,
                            @rownum := @rownum + 1 as ID,
                            WSI.EnmSurface,
                            WSI.EnmStep,
                            WSI.EnmColor,
                            OP.Description OPDescription,
                            IF(P.SW IN ('AKLC','AKLC1','AKLC1M','AKLC1T','ARC1','ARC1M','ARC1T','ARMNC','ARMNCM','ARMNCT','ASK','ASKB','ATCC1','ATCC1M','ATCC1T','ATCCX05','ATCCX1','ATCP05','ATCP05M',
                            'ATCP05T','ATCP1','ATCP1M','ATCP1T','GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), TMI.Qty*2, TMI.Qty) AS Pcs    
                        FROM
                            WorkCompletion WC
                            JOIN Location L ON WC.Location = L.ID
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID
                            JOIN TransferRMItem TMI ON WC.WorkAllocation = TMI.WorkAllocation AND WC.Freq = TMI.LinkFreq AND WCI.Ordinal = TMI.LinkOrd
                            JOIN TransferRM TM ON TM.ID = TMI.IDM 
                            JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            JOIN Product P ON P.ID=WO.Product
                            JOIN Product PA ON TMI.Product = PA.ID 
                            JOIN ProductCarat PC ON PC.ID=TMI.Carat
                            JOIN WorkScheduleItem WSI ON WSI.LinkID = TMI.IDM
                            JOIN WorkSchedule WS ON WS.ID = WSI.IDM AND WSI.LinkOrd = TMI.Ordinal
                            LEFT JOIN ShortText ST ON ST.ID=P.Color
                            Left Join Operation OP ON OP.ID=WSI.Operation
                            cross join (select @rownum := 0) r
                        WHERE
                            WS.ID = $idubah AND WS.Active != 'C' ";
             
        $queryOther = "SELECT
                            CONCAT( TMI.WorkAllocation, '-', TMI.LinkFreq, '-', TMI.LinkOrd ) WS,
                            TM.TransDate,
                            CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2)) kirimkan,
                            P.Description WPW,
                            P.SW PSS,
                            PA.SW Psiap,
                            PC.Description Kadar,
                            TMI.Qty,
                            FORMAT(TMI.Weight,2) Weight,
                            WO.SW WSW,
                            L.Description Lnama,
                            ST.Description Kategori,
                            TMI.Note,
                            P.Color,
                            TMI.Carat,
                            WS.ID idmcuk,
                            CASE WHEN TM.ID IS NULL THEN '-' ELSE TM.ID END AS TMno,  
                            DATE_FORMAT( WS.TransDate, '%d %M %Y' ) TransDate1,
                            @rownum := @rownum + 1 as ID,
                            ST1.Description OperationProcess,
                            ST2.Description Level2Process,
                            IF(P.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%', TMI.Qty*2, TMI.Qty) AS Pcs    
                        FROM
                            WorkCompletion WC
                            JOIN Location L ON WC.Location = L.ID
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID
                            JOIN TransferRMItem TMI ON WC.WorkAllocation = TMI.WorkAllocation AND WC.Freq = TMI.LinkFreq AND WCI.Ordinal = TMI.LinkOrd
                            JOIN TransferRM TM ON TM.ID = TMI.IDM 
                            JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            JOIN Product P ON P.ID=WO.Product
                            JOIN Product PA ON TMI.Product = PA.ID 
                            JOIN ProductCarat PC ON PC.ID=TMI.Carat
                            JOIN WorkScheduleItem WSI ON WSI.LinkID = TMI.IDM
                            JOIN WorkSchedule WS ON WS.ID = WSI.IDM AND WSI.LinkOrd = TMI.Ordinal
                            LEFT JOIN ShortText ST ON ST.ID=P.Color
                            Left Join Operation ST1 ON ST1.ID=WSI.Operation
                            Left Join ShortText ST2 ON ST2.ID=WSI.Level2
                            cross join (select @rownum := 0) r
                        WHERE
                            WS.ID = $idubah AND WS.Active != 'C' ";
                        
        if($location == 47){
            $query = $queryEnamel;
        }else{
            $query = $queryOther;
        }

        $data = FacadesDB::connection('erp')->select($query);

        if(count($data) == 0){
            $datajson = array('success' => false);
        }else{
            foreach($data as $datas){
                $rows[] = $datas; 
            }

            $data = (!empty($rows) ? $rows : '');
            $datajson = array(
                'success' => true, 
                'datalist' => $rows, 
                'update' => 'update',
                'active' => $dataItems->Active,
                'catatan' => $dataItems->Remarks,
                'ID' => $dataItems->ID,
                'tglRPH' => $dataItems->TransDate,
                'location' => $location,
                'proses' => $dataItems->Divisi
            );
        }
        return response()->json($datajson);
    }

    public function cekSPK(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 4;
        }

        $idm = $request->pilih;
        $data = explode(",",$idm);
        $jmlItem = count($data);
        
        // dd($jmlItem);

        $arrChar = array();
        foreach ($data as $key) {
            $tahu = explode("-", $key);
            $idmpas = $tahu[0];
            $ordpas = $tahu[1];

            $queryCek = "SELECT A.WorkOrder, B.SW, IF(LEFT(B.SWPurpose,1)='O',0,1) CharSPK
                            FROM transferrmitem A
                            JOIN workorder B ON A.WorkOrder = B.ID
                            WHERE A.IDM=$idmpas AND A.Ordinal=$ordpas
                        ";
            $dataCek = FacadesDB::connection('erp')->select($queryCek);
            foreach($dataCek as $datasCek){}

            array_push($arrChar,$datasCek->CharSPK);
        }

        $jmlItem2 = array_sum($arrChar);

        if($jmlItem2 == 0 || $jmlItem2 == $jmlItem){
            if($jmlItem2 == 0){ // SPK 'O'
                $cekspk = 0;
            }else{              // SPK Non 'O'
                $cekspk = 1;
            }
            $datajson = array('status' => 'Sukses', 'cekspk' => $cekspk);
        }else{
            $datajson = array('status' => 'Gagal');
        }

        return response()->json($datajson);
    }


    public function simpan(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 4;
        }
  
        $idm = $request->pilih;
        $proses = $request->proses;
        $catatan = $request->catatan;
        $tgl = $request->tglRPH;
        $totalqty = 0;
        $totalberat = 0;
        $totalqtyplan = 0;
        $totalberatplan = 0;  

        $prosesAll = explode(",", $proses);
        $prosesProduct = $prosesAll[0];
        $prosesID = $prosesAll[1];

        // get last id
        $queryLastID = "SELECT LAST+1 AS ID FROM lastid WHERE Module = 'WorkSchedule' ";
        $getLastID = FacadesDB::connection('erp')->select($queryLastID);

        foreach($getLastID as $getLastIDs){}
        $lastID = $getLastIDs->ID;
    
        // update last id
        $queryUpdateLastID = "UPDATE lastid SET LAST = $lastID WHERE Module = 'WorkSchedule'";
        $updateLastID = FacadesDB::connection('erp')->update($queryUpdateLastID);

        // insert workschedule
        $queryInsertRPH = "INSERT INTO workschedule (ID, UserName, Remarks, TransDate, Active, Location, Qty, Weight, QtyPlan, WeightPlan, Divisi, Operation)
                    VALUES ($lastID, '$UserEntry', ".((!empty($catatan)) ? "'$catatan'" : 'NULL').", '$tgl', 'A', $location, 0, 0, 0, 0, '$proses', $prosesID)";
        $insertRPH = FacadesDB::connection('erp')->update($queryInsertRPH);
     
        // get incoming data
        $data = explode(",",$idm);
        
        // insert workscheduleitem
        if($location == 47){
            $no = 1;
            foreach ($data as $key) 
            {
                $tahu = explode("-", $key);
                $idmpas = $tahu[0];
                $ordpas = $tahu[1];
                $qty = $tahu[2];
                $color = $tahu[3];
                $carat = $tahu[4];  
                $berat = $tahu[5]; 
                // $operation = $tahu[6]; 
                // $level2 = $tahu[7]; 
                // $level3 = $tahu[8]; 
                // $level4 = $tahu[9]; 
                $operation = ((isset($tahu[6])) ? $tahu[6] : 'NULL');
                $level2 = ((isset($tahu[7])) ? $tahu[7] : 'NULL');
                $level3 = ((isset($tahu[8])) ? $tahu[8] : 'NULL');
                $level4 = ((isset($tahu[9])) ? $tahu[9] : 'NULL');
           
                $totalqty += $qty;
                $totalberat += $berat;                     
                $queryInsertWSItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3, Level4) 
                                        VALUES ($lastID, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, $operation, $level2, $level3, $level4)";
                $insertWSItem = FacadesDB::connection('erp')->insert($queryInsertWSItem);
                
                $queryUpdateTMItem = "UPDATE transferrmitem SET WorkSchedule = $lastID, WorkScheduleItem = $no WHERE IDM = $idmpas AND Ordinal = $ordpas ";
                $updateTMItem = FacadesDB::connection('erp')->update($queryUpdateTMItem);
      
                $no++;               
            }  

        }else{
            $no = 1;
            foreach ($data as $key) 
            {
                $tahu = explode("-", $key);
                $idmpas = $tahu[0];
                $ordpas = $tahu[1];
                $qty = $tahu[2];
                $color = $tahu[3];
                $carat = $tahu[4];  
                $berat = $tahu[5]; 
                // $operation = $tahu[6]; 
                // $level2 = $tahu[7]; 
                $operation = ((isset($tahu[6])) ? $tahu[6] : 'NULL');
                $level2 = ((isset($tahu[7])) ? $tahu[7] : 'NULL');

                $totalqty += $qty;
                $totalberat += $berat;                     
                $queryInsertWSItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2) 
                                        VALUES ($lastID, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, $operation, $level2)";
                $insertWSItem = FacadesDB::connection('erp')->insert($queryInsertWSItem);
                
                $queryUpdateTMItem = "UPDATE transferrmitem SET WorkSchedule = $lastID, WorkScheduleItem = $no WHERE IDM = $idmpas AND Ordinal = $ordpas ";
                $updateTMItem = FacadesDB::connection('erp')->update($queryUpdateTMItem);

                $no++;               
            }  
        }

        // get totqty and totweight
        $queryGetTot = "SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM workscheduleitem WHERE IDM = $lastID";
        $getTot = FacadesDB::connection('erp')->select($queryGetTot);
        foreach($getTot as $getsTot){}

        // update workschedule
        $queryUpdateWS = "UPDATE workschedule SET Qty = $getsTot->Qty, Weight = $getsTot->Weight WHERE ID = $lastID";
        $updateWS = FacadesDB::connection('erp')->update($queryUpdateWS);

        // get divisi workschedule
        $queryDivisi = "SELECT * FROM workschedule WHERE ID = $lastID ";
        $divisi = FacadesDB::connection('erp')->select($queryDivisi);
        foreach($divisi as $divisis){}

        if($insertRPH == TRUE && $insertWSItem == TRUE && $updateWS == TRUE){
            $datajson = array(
                'status' => 'success',
                'update' => 'update',
                'idmnya' => $lastID,
                'proses' => $divisis->Divisi
            );
        }else{
            $datajson = array('status' => 'failed');
        }

        return response()->json($datajson);
    }

    public function posting(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 4;
        }

        $idubah = $request->idmnya1;

        $queryUpdate = "UPDATE workschedule SET Active = 'P', PostDate = '".date('Y-m-d H:i:s')."' WHERE ID = $idubah ";
        $dataUpdate = FacadesDB::connection('erp')->update($queryUpdate);  

        $queryEnamel = "SELECT
                            CONCAT( TMI.WorkAllocation, '-', TMI.LinkFreq, '-', TMI.LinkOrd ) WS,
                            TM.TransDate,
                            CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2), '-', IF(TMI.Level3 IS NULL, 'NULL', TMI.Level3), '-', IF(TMI.Level4 IS NULL, 'NULL', TMI.Level4)) kirimkan,
                            P.Description WPW,
                            P.SW PSS,
                            PA.SW Psiap,
                            PC.Description Kadar,
                            TMI.Qty,
                            TMI.Weight,
                            WO.SW WSW,
                            L.Description Lnama,
                            ST.Description Kategori,
                            TMI.Note,
                            P.Color,
                            TMI.Carat,
                            WS.ID idmcuk,
                            CASE WHEN TM.ID IS NULL THEN '-' ELSE TM.ID END AS TMno,  
                            DATE_FORMAT( WS.TransDate, '%d %M %Y' ) TransDate1,
                            @rownum := @rownum + 1 as ID,
                            WSI.EnmSurface,
                            WSI.EnmStep,
                            WSI.EnmColor,
                            OP.Description OPDescription,
                            IF(P.SW IN ('AKLC','AKLC1','AKLC1M','AKLC1T','ARC1','ARC1M','ARC1T','ARMNC','ARMNCM','ARMNCT','ASK','ASKB','ATCC1','ATCC1M','ATCC1T','ATCCX05','ATCCX1','ATCP05','ATCP05M',
                            'ATCP05T','ATCP1','ATCP1M','ATCP1T','GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), TMI.Qty*2, TMI.Qty) AS Pcs    
                        FROM
                            WorkCompletion WC
                            JOIN Location L ON WC.Location = L.ID
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID
                            JOIN TransferRMItem TMI ON WC.WorkAllocation = TMI.WorkAllocation AND WC.Freq = TMI.LinkFreq AND WCI.Ordinal = TMI.LinkOrd
                            JOIN TransferRM TM ON TM.ID = TMI.IDM 
                            JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            JOIN Product P ON P.ID=WO.Product
                            JOIN Product PA ON TMI.Product = PA.ID 
                            JOIN ProductCarat PC ON PC.ID=TMI.Carat
                            JOIN WorkScheduleItem WSI ON WSI.LinkID = TMI.IDM
                            JOIN WorkSchedule WS ON WS.ID = WSI.IDM AND WSI.LinkOrd = TMI.Ordinal
                            JOIN ShortText ST ON ST.ID=P.Color
                            Left Join Operation OP ON OP.ID=WSI.Operation
                            cross join (select @rownum := 0) r
                        WHERE
                            WS.ID = $idubah ";

        $queryOther = "SELECT
                            CONCAT( TMI.WorkAllocation, '-', TMI.LinkFreq, '-', TMI.LinkOrd ) WS,
                            TM.TransDate,
                            CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2)) kirimkan,
                            P.Description WPW,
                            P.SW PSS,
                            PA.SW Psiap,
                            PC.Description Kadar,
                            TMI.Qty,
                            TMI.Weight,
                            WO.SW WSW,
                            L.Description Lnama,
                            ST.Description Kategori,
                            TMI.Note,
                            P.Color,
                            TMI.Carat,
                            WS.ID idmcuk,
                            CASE WHEN TM.ID IS NULL THEN '-' ELSE TM.ID END AS TMno,  
                            DATE_FORMAT( WS.TransDate, '%d %M %Y' ) TransDate1,
                            @rownum := @rownum + 1 as ID,
                            ST1.Description OperationProcess,
                            ST2.Description Level2Process,
                            IF(P.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%', TMI.Qty*2, TMI.Qty) AS Pcs    
                        FROM
                            WorkCompletion WC
                            JOIN Location L ON WC.Location = L.ID
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID
                            JOIN TransferRMItem TMI ON WC.WorkAllocation = TMI.WorkAllocation AND WC.Freq = TMI.LinkFreq AND WCI.Ordinal = TMI.LinkOrd
                            JOIN TransferRM TM ON TM.ID = TMI.IDM 
                            JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            JOIN Product P ON P.ID=WO.Product
                            JOIN Product PA ON TMI.Product = PA.ID 
                            JOIN ProductCarat PC ON PC.ID=TMI.Carat
                            JOIN WorkScheduleItem WSI ON WSI.LinkID = TMI.IDM
                            JOIN WorkSchedule WS ON WS.ID = WSI.IDM AND WSI.LinkOrd = TMI.Ordinal
                            JOIN ShortText ST ON ST.ID=P.Color
                            Left Join Operation ST1 ON ST1.ID=WSI.Operation
                            Left Join ShortText ST2 ON ST2.ID=WSI.Level2
                            cross join (select @rownum := 0) r
                        WHERE
                            WS.ID = $idubah ";
                        
                    if($location == 47){
                        $query = $queryEnamel;
                    }else{
                        $query = $queryOther;
                    }

        $data = FacadesDB::connection('erp')->select($query);

        foreach($data as $datas){
            $rows[] = $datas; 
        }
   
        $data = (!empty($rows) ? $rows : '');
        return response()->json( array('success' => true, 'datalist' => $data, 'active' => 'P') );
    }

    public function update(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 4;
        }

        $idubah = $request->idmnya1;
        $idm = $request->pilih;
        $proses = $request->proses;
        $catatan = $request->catatan;
        $tgl = $request->tglRPH;
        $totalqty = 0;
        $totalberat = 0;
        $totalqtyplan = 0;
        $totalberatplan = 0;

        $prosesAll = explode(",", $proses);
        $prosesProduct = $prosesAll[0];
        $prosesID = $prosesAll[1];

        // set null transferrmitem
        $queryTM = "SELECT LinkID, LinkOrd FROM workscheduleitem WHERE IDM = $idubah ";
        $tmSetNull = FacadesDB::connection('erp')->select($queryTM);

        foreach($tmSetNull as $row){
            $rows[] = (array) $row;
        }
        
        foreach($rows as $keyy){
            $idmpas = $keyy['LinkID'];
            $ordpas = $keyy['LinkOrd'];      

            $queryUpdateTMNull = "UPDATE transferrmitem SET WorkSchedule = NULL, WorkScheduleItem = NULL WHERE IDM = $idmpas AND Ordinal = $ordpas ";
            $updateTMNull = FacadesDB::connection('erp')->select($queryUpdateTMNull);      
        }

        // delete rph item
        $queryDeleteWSItem = "DELETE FROM workscheduleitem WHERE IDM = $idubah ";
        $deleteWSItem = FacadesDB::connection('erp')->delete($queryDeleteWSItem);  
     
        $dataIDM = explode(",",$idm);
     
        // insert rph item
        if($location == 47){
            $no = 1;
            foreach ($dataIDM as $key) 
            { 
                $tahu = explode("-", $key);
                $idmpas = $tahu[0];
                $ordpas = $tahu[1];
                $qty = $tahu[2];
                $color = $tahu[3];
                $carat = $tahu[4];  
                $berat = $tahu[5]; 
                // $operation = $tahu[6]; 
                // $level2 = $tahu[7]; 
                // $level3 = $tahu[8]; 
                // $level4 = $tahu[9]; 
                $operation = ((isset($tahu[6])) ? $tahu[6] : 'NULL');
                $level2 = ((isset($tahu[7])) ? $tahu[7] : 'NULL');
                $level3 = ((isset($tahu[8])) ? $tahu[8] : 'NULL');
                $level4 = ((isset($tahu[9])) ? $tahu[9] : 'NULL');

                $totalqty += $qty;
                $totalberat += $berat;     
                
                // insert rph item
                $queryInsertWSItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3, Level4) 
                                        VALUES ($idubah, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, $operation, $level2, $level3, $level4)";
                $insertWSItem = FacadesDB::connection('erp')->insert($queryInsertWSItem);

                // update tm item
                $queryUpdateTMItem = "UPDATE transferrmitem SET WorkSchedule = $idubah, WorkScheduleItem = $no WHERE IDM = $idmpas AND Ordinal = $ordpas ";
                $updateTMItem = FacadesDB::connection('erp')->update($queryUpdateTMItem);

                $no++;                  
            } 

        }else{
            $no = 1;
            foreach ($dataIDM as $key) 
            { 
                $tahu = explode("-", $key);
                $idmpas = $tahu[0];
                $ordpas = $tahu[1];
                $qty = $tahu[2];
                $color = $tahu[3];
                $carat = $tahu[4];  
                $berat = $tahu[5]; 
                // $operation = $tahu[6]; 
                // $level2 = $tahu[7]; 
                $operation = ((isset($tahu[6])) ? $tahu[6] : 'NULL');
                $level2 = ((isset($tahu[7])) ? $tahu[7] : 'NULL');

                $totalqty += $qty;
                $totalberat += $berat;   
                
                // insert rph item
                $queryInsertWSItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2) 
                                        VALUES ($idubah, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, $operation, $level2)";               
                $insertWSItem = FacadesDB::connection('erp')->insert($queryInsertWSItem);
        
                // update tm item
                $queryUpdateTMItem = "UPDATE transferrmitem SET WorkSchedule = $idubah, WorkScheduleItem = $no WHERE IDM = $idmpas AND Ordinal = $ordpas ";
                $updateTMItem = FacadesDB::connection('erp')->update($queryUpdateTMItem);

                $no++;                  
            } 

        }

        // get totqty and totweight
        $queryGetTot = "SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM workscheduleitem WHERE IDM = $idubah";
        $getTot = FacadesDB::connection('erp')->select($queryGetTot);
        foreach($getTot as $getsTot){}

        // update workschedule
        $queryUpdateWS = "UPDATE workschedule SET 
                            Qty = $getsTot->Qty, 
                            Weight = $getsTot->Weight,
                            TransDate = '$tgl',
                            Divisi = '$proses',
                            Operation = $prosesID,
                            Remarks = ".((!empty($catatan)) ? "'$catatan'" : 'NULL')."
                        WHERE ID = $idubah";
        $updateWS = FacadesDB::connection('erp')->update($queryUpdateWS);

        // get divisi workschedule
        $queryDivisi = "SELECT * FROM workschedule WHERE ID = $idubah ";
        $divisi = FacadesDB::connection('erp')->select($queryDivisi);
        foreach($divisi as $divisis){}

        if($insertWSItem == TRUE){
            $datajson = array(
                'status' => 'success',
                'update' => 'update',
                'idmnya' => $idubah,
                'proses' => $divisis->Divisi
            );
        }else{
            $datajson = array('status' => 'failed');
        }

        return response()->json($datajson);
    }

    public function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
       
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    } 

    public function update1(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 4;
        }

        $idubah = $request->idmnya1;
        $idm = $request->pilih;
        $proses = $request->proses;
        $catatan = $request->catatan;
        $tgl = $request->tglRPH;
        $totalqty = 0;
        $totalberat = 0;
        $totalqtyplan = 0;
        $totalberatplan = 0;
        $cekspk = $request->cekspk;

        $prosesAll = explode(",", $proses);
        $prosesProduct = $prosesAll[0];
        $prosesID = $prosesAll[1];

        $dataIDM = explode(",",$idm);

        if($location == 47){
            foreach ($dataIDM as $key) 
            {
                $tahu = explode("-", $key);
                $idmpas = $tahu[0];
                $ordpas = $tahu[1];
                $qty = $tahu[2];
                $color = $tahu[3];
                $carat = $tahu[4];  
                $berat = $tahu[5]; 
                // $operation = $tahu[6]; 
                // $level2 = $tahu[7]; 
                // $level3 = $tahu[8]; 
                // $level4 = $tahu[9]; 
                $operation = ((isset($tahu[6])) ? $tahu[6] : 'NULL');
                $level2 = ((isset($tahu[7])) ? $tahu[7] : 'NULL');
                $level3 = ((isset($tahu[8])) ? $tahu[8] : 'NULL');
                $level4 = ((isset($tahu[9])) ? $tahu[9] : 'NULL');
 
                $totalqty += $qty;
                $totalberat += $berat;                  
    
                $data_array[] = array(
                            'ID' => $idmpas.'-'.$ordpas,
                            'LinkID' => $idmpas,
                            'LinkOrd' => $ordpas,
                            'Category' => $color,
                            'Carat' => $carat,
                            'Qty' => $qty,
                            'Weight' => $berat,
                            // 'Operation' => $operation,  
                            // 'Level2' => $level2,
                            // 'Level3' => $level3, 
                            // 'Level4' => $level4   
                            'Operation' => ((isset($operation)) ? $operation : 'NULL'),
                            'Level2' => ((isset($level2)) ? $level2 : 'NULL'),
                            'Level3' => ((isset($level3)) ? $level3 : 'NULL'),
                            'Level4' => ((isset($level4)) ? $level4 : 'NULL')   
                              
                );                      
            }

            $IDitem1 = "SELECT CONCAT(LinkID,'-',LinkOrd) AS ID, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3, Level4 FROM workscheduleitem WHERE IDM = $idubah ";
            $data = FacadesDB::connection('erp')->select($IDitem1);

        }else{
            foreach ($dataIDM as $key) 
            {
                $tahu = explode("-", $key);
                $idmpas = $tahu[0];
                $ordpas = $tahu[1];
                $qty = $tahu[2];
                $color = $tahu[3];
                $carat = $tahu[4];  
                $berat = $tahu[5]; 
                // $operation = $tahu[6]; 
                // $level2 = $tahu[7];
                $operation = ((isset($tahu[6])) ? $tahu[6] : 'NULL');
                $level2 = ((isset($tahu[7])) ? $tahu[7] : 'NULL');
 
                $totalqty += $qty;
                $totalberat += $berat;                  
    
                $data_array[] = array(
                            'ID' => $idmpas.'-'.$ordpas,
                            'LinkID' => $idmpas,
                            'LinkOrd' => $ordpas,
                            'Category' => $color,
                            'Carat' => $carat,
                            'Qty' => $qty,
                            'Weight' => $berat,
                            // 'Operation' => $operation,  
                            // 'Level2' => $level2  
                            'Operation' => ((isset($operation)) ? $operation : 'NULL'),
                            'Level2' => ((isset($level2)) ? $level2 : 'NULL')                                
                );               
            }

            $IDitem1 = "SELECT CONCAT(LinkID,'-',LinkOrd) AS ID, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2 FROM workscheduleitem WHERE IDM = $idubah ";
            $data = FacadesDB::connection('erp')->select($IDitem1);

        }

        // Cek O/NON-O
        $arrChar = array();
        foreach($data as $datas){
            $queryCek = "SELECT A.WorkOrder, B.SW, IF(LEFT(B.SWPurpose,1)='O',0,1) CharSPK
                        FROM transferrmitem A
                        JOIN workorder B ON A.WorkOrder = B.ID
                        WHERE A.IDM=$datas->LinkID AND A.Ordinal=$datas->LinkOrd
                        ";
            $dataCek = FacadesDB::connection('erp')->select($queryCek);
            foreach($dataCek as $datasCek){}
            array_push($arrChar,$datasCek->CharSPK);
        }

        $jmlItem2 = array_sum($arrChar);
        if($jmlItem2 == 0){ // SPK 'O'
            $cekspk2 = 0;
        }else{              // SPK Non 'O'
            $cekspk2 = 1;
        }

        if ($cekspk != $cekspk2) {
            $datajson = [
                "success"=>false,
                "message"=>"Update Failed, No SPK Ada yang Campur Antara 'O' dan Non 'O'",
                "data"=>null,
                "error"=>[
                    "CekSPK"=>"No SPK Ada yang Campur Antara 'O' dan Non 'O' "
                ]
            ];
            return response()->json($datajson,400);
        }



        foreach($data as $datas){
            $rows[] = (array) $datas; 
        }    
    
        if(empty($rows)){
            $rphlist = $data_array;
        }else{
            $rphlist = array_merge($data_array,$rows);        
        }

        // function unique_multidim_array($array, $key) {
        //     $temp_array = array();
        //     $i = 0;
        //     $key_array = array();
           
        //     foreach($array as $val) {
        //         if (!in_array($val[$key], $key_array)) {
        //             $key_array[$i] = $val[$key];
        //             $temp_array[$i] = $val;
        //         }
        //         $i++;
        //     }
        //     return $temp_array;
        // } 
        $details = $this->unique_multidim_array($rphlist,'ID');

        // set null transferrmitem
        $queryTM = "SELECT LinkID, LinkOrd FROM workscheduleitem WHERE IDM = $idubah ";
        $tmSetNull = FacadesDB::connection('erp')->select($queryTM);

        foreach($tmSetNull as $row){
            $rowsx[] = (array) $row;
        }

        foreach($rowsx as $keyy){
            $idmpas = $keyy['LinkID'];
            $ordpas = $keyy['LinkOrd'];      

            $queryUpdateTMNull = "UPDATE transferrmitem SET WorkSchedule = NULL, WorkScheduleItem = NULL WHERE IDM = $idmpas AND Ordinal = $ordpas ";	
            $updateTMNull = FacadesDB::connection('erp')->select($queryUpdateTMNull);      
        }

        // delete rph item
        $queryDeleteWSItem = "DELETE FROM workscheduleitem WHERE IDM = $idubah ";
        $deleteWSItem = FacadesDB::connection('erp')->delete($queryDeleteWSItem);  
     
        // insert rph item
        if($location == 47){
            $no = 1;
            
            foreach ($details as $key) 
            { 
                $idmpas = $key['LinkID'];
                $ordpas = $key['LinkOrd'];
                $qty = $key['Qty'];
                $color = $key['Category'];
                $carat = $key['Carat'];
                $berat = $key['Weight']; 
                $operation = $key['Operation'];
                $level2 = $key['Level2'];
                $level3 = $key['Level3'];
                $level4 = $key['Level4'];
                // $operation = ((isset($key['Operation'])) ? $key['Operation'] : 'NULL');
                // $level2 = ((isset($key['Level2'])) ? $key['Level2'] : 'NULL');
                // $level3 = ((isset($key['Level3'])) ? $key['Level3'] : 'NULL');
                // $level4 = ((isset($key['Level4'])) ? $key['Level4'] : 'NULL');

                $totalqty += $qty;
                $totalberat += $berat;     
                
                // insert rph item
                $queryInsertWSItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3, Level4) 
                                        VALUES ($idubah, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, '$operation', '$level2', '$level3', '$level4')";
                                        // dd($queryInsertWSItem);
                $insertWSItem = FacadesDB::connection('erp')->insert($queryInsertWSItem);

                // update tm item
                $queryUpdateTMItem = "UPDATE transferrmitem SET WorkSchedule = $idubah, WorkScheduleItem = $no WHERE IDM = $idmpas AND Ordinal = $ordpas ";
                $updateTMItem = FacadesDB::connection('erp')->update($queryUpdateTMItem);

                $no++;    
            } 

        }else{
            $no = 1;
            foreach ($details as $key) 
            { 
                $idmpas = $key['LinkID'];
                $ordpas = $key['LinkOrd'];
                $qty = $key['Qty'];
                $color = $key['Category'];
                $carat = $key['Carat'];
                $berat = $key['Weight']; 
                $operation = $key['Operation'];
                $level2 = $key['Level2'];
                // $operation = ((isset($key['Operation'])) ? $key['Operation'] : 'NULL');
                // $level2 = ((isset($key['Level2'])) ? $key['Level2'] : 'NULL');

                $totalqty += $qty;
                $totalberat += $berat;   
                
                // insert rph item
                $queryInsertWSItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2) 
                                        VALUES ($idubah, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, '$operation', '$level2')";
                $insertWSItem = FacadesDB::connection('erp')->insert($queryInsertWSItem);
        
                // update tm item
                $queryUpdateTMItem = "UPDATE transferrmitem SET WorkSchedule = $idubah, WorkScheduleItem = $no WHERE IDM = $idmpas AND Ordinal = $ordpas ";
                $updateTMItem = FacadesDB::connection('erp')->update($queryUpdateTMItem);

                $no++;                  
            } 

        }

        // get totqty and totweight
        $queryGetTot = "SELECT SUM(Qty) Qty, SUM(Weight) Weight FROM workscheduleitem WHERE IDM = $idubah";
        $getTot = FacadesDB::connection('erp')->select($queryGetTot);
        foreach($getTot as $getsTot){}

        // update workschedule
        $queryUpdateWS = "UPDATE workschedule SET 
                            Qty = $getsTot->Qty, 
                            Weight = $getsTot->Weight,
                            TransDate = '$tgl',
                            Divisi = '$proses',
                            Operation = $prosesID,
                            Remarks = ".((!empty($catatan)) ? "'$catatan'" : 'NULL')."
                            WHERE ID = $idubah";
        $updateWS = FacadesDB::connection('erp')->update($queryUpdateWS);

        // get divisi workschedule
        $queryDivisi = "SELECT * FROM workschedule WHERE ID = $idubah ";
        $divisi = FacadesDB::connection('erp')->select($queryDivisi);
        foreach($divisi as $divisis){}

        if($insertWSItem == TRUE){
            $datajson = array(
                'status' => 'success',
                'update' => 'update1',
                'idmnya' => $idubah,
                'proses' => $divisis->Divisi
            );
        }else{
            $datajson = array('status' => 'failed');
        }

        return response()->json($datajson);  
    }

    public function group_by($key, $data){
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
            $result[$val[$key]][] = $val;
            } else {
            $result[""][] = $val;
            }
        }
        return $result;
    }

    public function cetak(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 4;
        }

        $idrph = $request->idrph;

        // function group_by($key, $data){
        //     $result = array();
        //     foreach ($data as $val) {
        //         if (array_key_exists($key, $val)) {
        //         $result[$val[$key]][] = $val;
        //         } else {
        //         $result[""][] = $val;
        //         }
        //     }
        //     return $result;
        // }

        $query1 = "SELECT Description FROM department WHERE Location = $location ";
        $data1 = FacadesDB::connection('erp')->select($query1);

        $query2 = "SELECT A.ID, DATE_FORMAT( A.TransDate, '%d-%m-%Y' ) TransDate1, B.Description OperationName, C.Description LocationName
                    FROM workschedule A 
                    JOIN operation B ON A.Operation=B.ID 
                    JOIN department C ON A.Location=C.Location
                    WHERE A.ID = $idrph ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $queryEnamel = "SELECT
                            CONCAT( TMI.WorkAllocation, '-', TMI.LinkFreq, '-', TMI.LinkOrd ) WS,
                            TM.TransDate,
                            CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2), '-', IF(TMI.Level3 IS NULL, 'NULL', TMI.Level3), '-', IF(TMI.Level4 IS NULL, 'NULL', TMI.Level4)) kirimkan,
                            P.Description WPW,
                            P.SW PSS,
                            PA.SW Psiap,
                            PC.Description Kadar,
                            TMI.Qty,
                            TMI.Weight,
                            WO.SW WSW,
                            L.Description Lnama,
                            ST.Description Kategori,
                            TMI.Note,
                            P.Color,
                            TMI.Carat,
                            WS.ID idmcuk,
                            CASE WHEN TM.ID IS NULL THEN '-' ELSE TM.ID END AS TMno,  
                            DATE_FORMAT( WS.TransDate, '%d-%m-%Y' ) TransDate1,
                            @rownum := @rownum + 1 as ID,
                            WSI.EnmSurface,
                            WSI.EnmStep,
                            WSI.EnmColor,
                            OP.Description OPDescription,
                            IF(P.SW IN ('AKLC','AKLC1','AKLC1M','AKLC1T','ARC1','ARC1M','ARC1T','ARMNC','ARMNCM','ARMNCT','ASK','ASKB','ATCC1','ATCC1M','ATCC1T','ATCCX05','ATCCX1','ATCP05','ATCP05M',
                            'ATCP05T','ATCP1','ATCP1M','ATCP1T','GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), TMI.Qty*2, TMI.Qty) AS Pcs    
                        FROM
                            WorkCompletion WC
                            JOIN Location L ON WC.Location = L.ID
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID
                            JOIN TransferRMItem TMI ON WC.WorkAllocation = TMI.WorkAllocation AND WC.Freq = TMI.LinkFreq AND WCI.Ordinal = TMI.LinkOrd
                            JOIN TransferRM TM ON TM.ID = TMI.IDM 
                            JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            JOIN Product P ON P.ID=WO.Product
                            JOIN Product PA ON TMI.Product = PA.ID 
                            JOIN ProductCarat PC ON PC.ID=TMI.Carat
                            JOIN WorkScheduleItem WSI ON WSI.LinkID = TMI.IDM
                            JOIN WorkSchedule WS ON WS.ID = WSI.IDM AND WSI.LinkOrd = TMI.Ordinal
                            LEFT JOIN ShortText ST ON ST.ID=P.Color
                            Left Join Operation OP ON OP.ID=WSI.Operation
                            cross join (select @rownum := 0) r
                        WHERE
                            WS.ID = $idrph
                        ORDER BY PC.Description ";
             
        $queryOther = "SELECT
                            CONCAT( TMI.WorkAllocation, '-', TMI.LinkFreq, '-', TMI.LinkOrd ) WS,
                            TM.TransDate,
                            CONCAT(TMI.IDM, '-', TMI.Ordinal, '-', TMI.Qty, '-', COALESCE(P.Color,'0'), '-', TMI.Carat, '-', TMI.Weight, '-', IF(TMI.Operation IS NULL, 'NULL', TMI.Operation), '-', IF(TMI.Level2 IS NULL, 'NULL', TMI.Level2)) kirimkan,
                            P.Description WPW,
                            P.SW PSS,
                            PA.SW Psiap,
                            PC.Description Kadar,
                            TMI.Qty,
                            TMI.Weight,
                            WO.SW WSW,
                            L.Description Lnama,
                            ST.Description Kategori,
                            TMI.Note,
                            P.Color,
                            TMI.Carat,
                            WS.ID idmcuk,
                            CASE WHEN TM.ID IS NULL THEN '-' ELSE TM.ID END AS TMno,  
                            DATE_FORMAT( WS.TransDate, '%d-%m-%Y' ) TransDate1,
                            @rownum := @rownum + 1 as ID,
                            ST1.Description OperationProcess,
                            ST2.Description Level2Process,
                            IF(P.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%', TMI.Qty*2, TMI.Qty) AS Pcs    
                        FROM
                            WorkCompletion WC
                            JOIN Location L ON WC.Location = L.ID
                            JOIN WorkCompletionItem WCI ON WCI.IDM=WC.ID
                            JOIN TransferRMItem TMI ON WC.WorkAllocation = TMI.WorkAllocation AND WC.Freq = TMI.LinkFreq AND WCI.Ordinal = TMI.LinkOrd
                            JOIN TransferRM TM ON TM.ID = TMI.IDM 
                            JOIN WorkOrder WO ON WO.ID=TMI.WorkOrder
                            JOIN Product P ON P.ID=WO.Product
                            JOIN Product PA ON TMI.Product = PA.ID 
                            JOIN ProductCarat PC ON PC.ID=TMI.Carat
                            JOIN WorkScheduleItem WSI ON WSI.LinkID = TMI.IDM
                            JOIN WorkSchedule WS ON WS.ID = WSI.IDM AND WSI.LinkOrd = TMI.Ordinal
                            LEFT JOIN ShortText ST ON ST.ID=P.Color
                            Left Join Operation ST1 ON ST1.ID=WSI.Operation
                            Left Join ShortText ST2 ON ST2.ID=WSI.Level2
                            cross join (select @rownum := 0) r
                        WHERE
                            WS.ID = $idrph 
                        ORDER BY PC.Description ";
                        
        if($location == 47){
            $query3 = $queryEnamel;
        }else{
            $query3 = $queryOther;
        }

        $data3 = FacadesDB::connection('erp')->select($query3);

        foreach($data3 as $datas3){
            $rows[] = (array) $datas3;
        }  

        $byGroup = $this->group_by("Carat", $rows);

        return view('Produksi.JadwalKerjaHarian.RPHProduksi.cetak', compact('data1','data2','data3','byGroup'));
    }
}

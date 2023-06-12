<?php

namespace App\Http\Controllers\Produksi\JadwalKerjaHarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as FacadesDB;
use \DateTime;
use \DateTimeZone;

class PermintaanKomponenController extends Controller
{
    public function index(){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');
        
        if($location == NULL){
            $location = 4;
        }

        $query = "SELECT * FROM componentrequest WHERE LOCATION = $location AND Status IN (0,1) ORDER BY ID DESC LIMIT 100 ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.JadwalKerjaHarian.PermintaanKomponen.index', compact('data','rowcount','location'));
    }

    public function simpan(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        // dd($request);
        $prod = $request->prod;
        $idm = $request->idm;
        $ordinal = $request->ordinal;
        $qtypcs = $request->qtypcs;
        $brtpcs = $request->brtpcs;
        $caratpcs = $request->caratpcs;
        $jml = $request->jml;
        $brt = $request->brt;
        $tgl_masuk = $request->tgl_masuk;
        $catatan = $request->catatan;
        $wkorder = $request->wkorder;
        $acc = $request->acc;
        $qtyorder = $request->qtyorder;

        // SW Generator CR
        $queryEnvelope = "SELECT CONCAT('PKCB','',DATE_FORMAT(CURDATE(), '%y'),'',LPad(Month(CurDate()),2, '0'),'',LPad(Count(ID) + 1, 5, '0')) Counter
                            From componentrequest
                            Where Year(EntryDate) = Year(CurDate()) And Month(EntryDate) = Month(CurDate())" ;
        $envelope = FacadesDB::connection('erp')->select($queryEnvelope);

        foreach ($envelope as $envelopes){}   
        $counterSW = $envelopes->Counter;
        
        // Insert ComponentRequest RAW SQL Queries
        $querySimpan = "INSERT INTO componentrequest (UserName, EntryDate, Status, Location, Remarks, TotalQty, TotalWeight, TransDate, SW, ProcessStatus)
                        VALUES ('$UserEntry', now(), '0', $location,  ".((!empty($catatan)) ? "'$catatan'" : 'NULL').", $jml, $brt, '$tgl_masuk', '$counterSW', '0')";
        $simpan = FacadesDB::connection('erp')->insert($querySimpan);

        // Get LastInsertID When Using RAW SQL Queries
        $lastInsertedID = FacadesDB::connection('erp')->table('componentrequest')->select('ID')->latest('ID')->first();
        foreach ($lastInsertedID as $lastInsertedIDs){}
        $lastIDComponentRequest = $lastInsertedIDs;

        // // Insert ComponentRequest Query Builder
        // $simpan = FacadesDB::table('componentrequest')->insert([
        //             'EntryDate' => now(),
        //             'UserName' => $UserEntry,
        //             'Remarks' => $catatan,
        //             'SW' => $counterSW,
        //             'TransDate' => $tgl_masuk,
        //             'Location' => $location,
        //             'TotalQty' => $jml,
        //             'TotalWeight' => $brt,
        //             'Status' => 0,
        //             'ProcessStatus' => 0
        //         ]);    

        // // Get LastInsertID When Using Query Builder
        // $lastInsert = FacadesDB::getPdo()->lastInsertId();

        
        // Get LastID WorkCompletion
        $queryLastID = "SELECT LAST+1 AS ID FROM lastid WHERE Module = 'WorkCompletion' ";
        $dataLastID = FacadesDB::connection('erp')->select($queryLastID);

        foreach($dataLastID as $dataLastIDs){}
        $lastID = $dataLastIDs->ID;
    
        $queryUpdateLastID = "UPDATE lastid SET LAST = $lastID WHERE Module = 'WorkCompletion'";
        $dataUpdateLastID = FacadesDB::connection('erp')->update($queryUpdateLastID);



        if($location == 4){

            // Insert ComponentRequestItem
            $no = 1;
            for ($i=0; $i <count($prod) ; $i++) //Looping Sebanyak Jumlah Komponen Unique, Semua NTHKO di RPH
            { 
                if($qtyorder[$i] <> NULL){
                    // Insert ComponentRequestItem
                    $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
                    VALUES ($lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $prod[$i], $qtyorder[$i], '0')";
                    $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);

                    // Insert WorkCompletionItem
                    $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
                                    VALUES ($lastID, $no, $prod[$i], $caratpcs[$i], $qtyorder[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
                    $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);

                    $no++;

                }else{
                    // Insert ComponentRequestItem
                    $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
                                        VALUES ($lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $prod[$i], $qtypcs[$i], '0')";
                    $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);

                    // Insert WorkCompletionItem
                    $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
                                        VALUES ($lastID, $no, $prod[$i], $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
                    $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);

                    $no++;

                }  
            }

        }else{

            // Insert ComponentRequestItem
            $no = 1;
            for ($i=0; $i < count($prod) ; $i++) 
            { 
                if($acc == 'accbaru') {

                    // Insert ComponentRequestItem
                    $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
                                        VALUES ($lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $prod[$i], $qtypcs[$i], '0')";
                    $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);

                    // Insert WorkCompletionItem
                    $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
                                        VALUES ($lastID, $no, $prod[$i], $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
                    $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);
                    
                }else{

                    if($caratpcs[$i] == 1){
                        $prodlama = 182835;
                    }else if($caratpcs[$i] == 3){
                        $prodlama = 182836;
                    }else if($caratpcs[$i] == 4){
                        $prodlama = 182837;
                    }else if($caratpcs[$i] == 5){
                        $prodlama = 182838;
                    }else if($caratpcs[$i] == 6){
                        $prodlama = 182839;
                    }else if($caratpcs[$i] == 7){
                        $prodlama = 182840;
                    }else if($caratpcs[$i] == 12){
                        $prodlama = 182841;
                    }else if($caratpcs[$i] == 13){
                        $prodlama = 182842;
                    }else if($caratpcs[$i] == 14){
                        $prodlama = 182843;
                    }

                    // Insert ComponentRequestItem
                    $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
                                        VALUES ($lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $prodlama, $qtypcs[$i], '0')";
                    $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);

                    // Insert WorkCompletionItem
                    $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
                                        VALUES ($lastID, $no, $prodlama, $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
                    $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);
                }

                $no++;
            }

        }

        
        // SW Generator WC
        $querySWNTHKO = "SELECT CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'00',LPad(CASE WHEN Max( RIGHT ( WorkAllocation, 4 )) IS NULL THEN '6001' ELSE MAX( RIGHT ( WorkAllocation, 4 ) )+1 END, 4, '0')) Counter1
                        FROM
                            WorkCompletion 
                        WHERE
                            Operation = 140
                            AND LEFT ( WorkAllocation, 2 ) = DATE_FORMAT(CURDATE(), '%y') 
                            AND SubStr( WorkAllocation, 3, 2 ) = MONTH(CurDate())" ;
        $swNTHKO = FacadesDB::connection('erp')->select($querySWNTHKO);

        foreach($swNTHKO as $swNTHKOs){}
        $lastSWNTHKO = $swNTHKOs->Counter1;
        
        // Insert WorkCompletion
        $queryInsertWC = "INSERT INTO workcompletion (ID, EntryDate, UserName, TransDate, Employee, WorkAllocation, Freq, Location, Operation, Qty, Weight, Active) 
                            VALUES ($lastID, now(), '$UserEntry', '".date('Y-m-d')."', $iduser, '$lastSWNTHKO', '1', '22', '140', '".$jml."', '0', 'R' )" ;
        $insertWC = FacadesDB::connection('erp')->insert($queryInsertWC);


        if($insertCRI === TRUE){
            $datajson = array('status' => 'sukses', 'id' => $lastIDComponentRequest, 'location' => $location);
        }else{
            $datajson = array('status' => 'sukses');
        }

        return response()->json($datajson);

    }

    public function simpanTest(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        // dd($request);
        $prod = $request->prod;
        $idm = $request->idm;
        $ordinal = $request->ordinal;
        $qtypcs = $request->qtypcs;
        $brtpcs = $request->brtpcs;
        $caratpcs = $request->caratpcs;
        $jml = $request->jml;
        $brt = $request->brt;
        $tgl_masuk = $request->tgl_masuk;
        $catatan = $request->catatan;
        $wkorder = $request->wkorder;
        $acc = $request->acc;
        $qtyorder = $request->qtyorder;

        // SW Generator CR
        $queryEnvelope = "SELECT CONCAT('PKCB','',DATE_FORMAT(CURDATE(), '%y'),'',LPad(Month(CurDate()),2, '0'),'',LPad(Count(ID) + 1, 5, '0')) Counter
                            From componentrequest
                            Where Year(EntryDate) = Year(CurDate()) And Month(EntryDate) = Month(CurDate())" ;
        $envelope = FacadesDB::connection('erp')->select($queryEnvelope);

        foreach ($envelope as $envelopes){}   
        $counterSW = $envelopes->Counter;
        
        // // Insert ComponentRequest RAW SQL Queries
        // $querySimpan = "INSERT INTO componentrequest (UserName, EntryDate, Status, Location, Remarks, TotalQty, TotalWeight, TransDate, SW, ProcessStatus)
        //                 VALUES ('$UserEntry', now(), '0', $location,  ".((!empty($catatan)) ? "'$catatan'" : 'NULL').", $jml, $brt, '$tgl_masuk', '$counterSW', '0')";
        // $simpan = FacadesDB::connection('erp')->insert($querySimpan);

        // // Get LastInsertID When Using RAW SQL Queries
        // $lastInsertedID = FacadesDB::connection('erp')->table('componentrequest')->select('ID')->latest('ID')->first();
        // foreach ($lastInsertedID as $lastInsertedIDs){}
        // $lastIDComponentRequest = $lastInsertedIDs;

        // // // Insert ComponentRequest Query Builder
        // // $simpan = FacadesDB::table('componentrequest')->insert([
        // //             'EntryDate' => now(),
        // //             'UserName' => $UserEntry,
        // //             'Remarks' => $catatan,
        // //             'SW' => $counterSW,
        // //             'TransDate' => $tgl_masuk,
        // //             'Location' => $location,
        // //             'TotalQty' => $jml,
        // //             'TotalWeight' => $brt,
        // //             'Status' => 0,
        // //             'ProcessStatus' => 0
        // //         ]);    

        // // // Get LastInsertID When Using Query Builder
        // // $lastInsert = FacadesDB::getPdo()->lastInsertId();

        
        // // Get LastID WorkCompletion
        // $queryLastID = "SELECT LAST+1 AS ID FROM lastid WHERE Module = 'WorkCompletion' ";
        // $dataLastID = FacadesDB::connection('erp')->select($queryLastID);

        // foreach($dataLastID as $dataLastIDs){}
        // $lastID = $dataLastIDs->ID;
    
        // $queryUpdateLastID = "UPDATE lastid SET LAST = $lastID WHERE Module = 'WorkCompletion'";
        // $dataUpdateLastID = FacadesDB::connection('erp')->update($queryUpdateLastID);



        if($location == 4){

            // Insert ComponentRequestItem
            $no = 1;
            for ($i=0; $i <count($prod) ; $i++) //Looping Sebanyak Jumlah Komponen Unique, Semua NTHKO di RPH
            { 
        
                if($qtyorder[$i] <> NULL){
                    // Insert ComponentRequestItem
                    $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
                                    VALUES (1, $no, $idm[$i], $ordinal[$i], $prod[$i], $qtyorder[$i], '0')";
                    // $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);
                    dd($queryInsertCRI);

                    // Insert WorkCompletionItem
                    // $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
                    //                     VALUES ($lastID, $no, $prod[$i], $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
                    // $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);

                    // $no++;

                }else{
                    // Insert ComponentRequestItem
                    $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
                                    VALUES (1, $no, $idm[$i], $ordinal[$i], $prod[$i], $qtypcs[$i], '0')";
                    // $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);
                    dd($queryInsertCRI);

                    // Insert WorkCompletionItem
                    // $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
                    //                     VALUES ($lastID, $no, $prod[$i], $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
                    // $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);

                    // $no++;
                }

        
            }

        }
        // else{

        //     // Insert ComponentRequestItem
        //     $no = 1;
        //     for ($i=0; $i < count($prod) ; $i++) 
        //     { 
        //         if($acc == 'accbaru') {

        //             // Insert ComponentRequestItem
        //             $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
        //                                 VALUES ($lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $prod[$i], $qtypcs[$i], '0')";
        //             $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);

        //             // Insert WorkCompletionItem
        //             $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
        //                                 VALUES ($lastID, $no, $prod[$i], $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
        //             $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);
                    
        //         }else{

        //             if($caratpcs[$i] == 1){
        //                 $prodlama = 182835;
        //             }else if($caratpcs[$i] == 3){
        //                 $prodlama = 182836;
        //             }else if($caratpcs[$i] == 4){
        //                 $prodlama = 182837;
        //             }else if($caratpcs[$i] == 5){
        //                 $prodlama = 182838;
        //             }else if($caratpcs[$i] == 6){
        //                 $prodlama = 182839;
        //             }else if($caratpcs[$i] == 7){
        //                 $prodlama = 182840;
        //             }else if($caratpcs[$i] == 12){
        //                 $prodlama = 182841;
        //             }else if($caratpcs[$i] == 13){
        //                 $prodlama = 182842;
        //             }else if($caratpcs[$i] == 14){
        //                 $prodlama = 182843;
        //             }

        //             // Insert ComponentRequestItem
        //             $queryInsertCRI = "INSERT INTO componentrequestitem (IDM, Ordinal, LinkID, LinkOrd, Product, Qty, Weight) 
        //                                 VALUES ($lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $prodlama, $qtypcs[$i], '0')";
        //             $insertCRI = FacadesDB::connection('erp')->insert($queryInsertCRI);

        //             // Insert WorkCompletionItem
        //             $queryInsertWCI = "INSERT INTO workcompletionitem (IDM, Ordinal, Product, Carat, Qty, Weight, RepairQty, RepairWeight, ScrapQty, ScrapWeight, LinkID, LinkOrd, WorkSchedule, WorkScheduleOrd, WorkOrder) 
        //                                 VALUES ($lastID, $no, $prodlama, $caratpcs[$i], $qtypcs[$i], '0', '0', '0', '0', '0', $lastIDComponentRequest, $no, $idm[$i], $ordinal[$i], $wkorder[$i])";
        //             $insertWCI = FacadesDB::connection('erp')->insert($queryInsertWCI);
        //         }

        //         $no++;
        //     }

        // }

        
        // // SW Generator WC
        // $querySWNTHKO = "SELECT CONCAT(DATE_FORMAT(CURDATE(), '%y'),'',LPad(MONTH(CurDate()), 2, '0' ),'00',LPad(CASE WHEN Max( RIGHT ( WorkAllocation, 4 )) IS NULL THEN '6001' ELSE MAX( RIGHT ( WorkAllocation, 4 ) )+1 END, 4, '0')) Counter1
        //                 FROM
        //                     WorkCompletion 
        //                 WHERE
        //                     Operation = 140
        //                     AND LEFT ( WorkAllocation, 2 ) = DATE_FORMAT(CURDATE(), '%y') 
        //                     AND SubStr( WorkAllocation, 3, 2 ) = MONTH(CurDate())" ;
        // $swNTHKO = FacadesDB::connection('erp')->select($querySWNTHKO);

        // foreach($swNTHKO as $swNTHKOs){}
        // $lastSWNTHKO = $swNTHKOs->Counter1;
        
        // // Insert WorkCompletion
        // $queryInsertWC = "INSERT INTO workcompletion (ID, EntryDate, UserName, TransDate, Employee, WorkAllocation, Freq, Location, Operation, Qty, Weight, Active) 
        //                     VALUES ($lastID, now(), '$UserEntry', '".date('Y-m-d')."', $iduser, '$lastSWNTHKO', '1', '22', '140', '".$jml."', '0', 'R' )" ;
        // $insertWC = FacadesDB::connection('erp')->insert($queryInsertWC);


        // if($insertCRI === TRUE){
        //     $datajson = array('status' => 'sukses', 'id' => $lastIDComponentRequest, 'location' => $location);
        // }else{
        //     $datajson = array('status' => 'sukses');
        // }

        // return response()->json($datajson);

    }

    public function cekWSComponent(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $idcr = $request->id;

        // Get RPH ID
        $query = "SELECT A.ID, C.LinkID
                    FROM
                        componentrequest A
                        LEFT JOIN componentrequestitem C ON A.ID=C.IDM
                    WHERE
                        A.ID = $idcr
                    LIMIT 1 ";
        $data = FacadesDB::connection('erp')->select($query);

        foreach($data as $datas){}
        $linkID = $datas->LinkID;

        // Check WSComponent
        $query2 = "SELECT IDM FROM workschedulecomponent WHERE IDM=$linkID LIMIT 1 ";
        $data2 = FacadesDB::connection('erp')->select($query2);
        $rowcount = count($data2);

        $datajson = array('rowcount' => $rowcount, 'CRid' => $idcr, 'location' => $location);

        return response()->json($datajson);
    }

    public function lihat(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $idcr = $request->id;

        $query = "SELECT A.LinkID, B.SW from componentrequestitem A JOIN componentrequest B ON A.IDM = B.ID WHERE A.IDM = $idcr LIMIT 1 ";
        $data = FacadesDB::connection('erp')->select($query);

        foreach($data as $datas){}
        $linkID = $datas->LinkID;
  
        $query2 = "SELECT 
                        CONCAT(F.WorkAllocation,'-',F.LinkFreq,'-',F.LinkOrd) as NTKHO, G.SW FG, E.SW Komponen, D.Description Kadar, B.Qty, B.Weight, I.Description Kategori
                    FROM componentrequest A
                        JOIN componentrequestitem B ON A.ID=B.IDM
                        JOIN workscheduleitem C ON B.LinkID=C.IDM AND B.LinkOrd=C.Ordinal
                        JOIN productcarat D ON C.Carat=D.ID
                        JOIN product E ON B.Product=E.ID
                        LEFT JOIN transferrmitem F ON F.IDM = C.LinkID AND F.Ordinal = C.LinkOrd
                        LEFT JOIN product G ON F.FG=G.ID
                        LEFT JOIN product H ON H.ID = G.Model
                        LEFT JOIN shorttext I ON I.ID = H.Color
                    WHERE B.LinkID = $linkID ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $query3 = "SELECT 
                        E.SW, SUM(B.Qty) as jml, FORMAT(SUM(B.Weight), 2) as brt, D.Description as kadar 
                    FROM componentrequest A
                        JOIN componentrequestitem B ON A.ID=B.IDM
                        JOIN workscheduleitem C ON B.LinkID=C.IDM AND B.LinkOrd=C.Ordinal
                        JOIN productcarat D ON C.Carat=D.ID
                        JOIN product E ON B.Product=E.ID
                    WHERE B.LinkID = $linkID
                    GROUP BY B.Product, C.Carat
                    ";
        $data3 = FacadesDB::connection('erp')->select($query3);

        $returnHTML = view('Produksi.JadwalKerjaHarian.PermintaanKomponen.lihat', compact('data2','data3'))->render();
        return response()->json( array('html' => $returnHTML) );

    }

    public function cetak(Request $request){ //OK
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $idcr = $request->id;

        $query = "SELECT A.LinkID, B.SW from componentrequestitem A JOIN componentrequest B ON A.IDM = B.ID WHERE A.IDM = $idcr LIMIT 1 ";
        $data = FacadesDB::connection('erp')->select($query);

        foreach($data as $datas){}
        $linkID = $datas->LinkID;
        $CRSW = $datas->SW;

        $query2 = "SELECT 
                        CONCAT(F.WorkAllocation,'-',F.LinkFreq,'-',F.LinkOrd) as NTKHO, G.SW FG, E.SW Komponen, D.Description Kadar, B.Qty, B.Weight, I.Description Kategori
                    FROM componentrequest A
                        JOIN componentrequestitem B ON A.ID=B.IDM
                        JOIN workscheduleitem C ON B.LinkID=C.IDM AND B.LinkOrd=C.Ordinal
                        JOIN productcarat D ON C.Carat=D.ID
                        JOIN product E ON B.Product=E.ID
                        LEFT JOIN transferrmitem F ON F.IDM = C.LinkID AND F.Ordinal = C.LinkOrd
                        LEFT JOIN product G ON F.FG=G.ID
                        LEFT JOIN product H ON H.ID = G.Model
                        LEFT JOIN shorttext I ON I.ID = H.Color
                    WHERE B.LinkID = $linkID ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $query3 = "SELECT 
                        E.SW, SUM(B.Qty) as jml, FORMAT(SUM(B.Weight), 2) as brt, D.Description as kadar 
                    FROM componentrequest A
                        JOIN componentrequestitem B ON A.ID=B.IDM
                        JOIN workscheduleitem C ON B.LinkID=C.IDM AND B.LinkOrd=C.Ordinal
                        JOIN productcarat D ON C.Carat=D.ID
                        JOIN product E ON B.Product=E.ID
                    WHERE B.LinkID = $linkID
                    GROUP BY B.Product, C.Carat
                    ";
        $data3 = FacadesDB::connection('erp')->select($query3);


        return view('Produksi.JadwalKerjaHarian.PermintaanKomponen.cetak', compact('data2','data3','CRSW'));


    }

    public function periksaSepuh(Request $request){ //OK

        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $rph = $request->rph;

        $query = "SELECT
                    B.*,
                    (B.Qty * D.Qty) as Qtycin,
                    X.Description,
                    X.SW,
                    CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                    N.Description as Kateg,
                    CASE 
                        WHEN B.Carat = 1 THEN 'UGGW-10001-06K' 
                        WHEN B.Carat = 3 THEN 'UGGW-10001-08K'
                        WHEN B.Carat = 4 THEN 'UGGW-10001-16K'
                        WHEN B.Carat = 5 THEN 'UGGW-10001-17K'
                        WHEN B.Carat = 6 THEN 'UGGW-10001-17K.'
                        WHEN B.Carat = 7 THEN 'UGGW-10001-20K'
                        WHEN B.Carat = 12 THEN 'UGGW-10001-10K'
                        WHEN B.Carat = 13 THEN 'UGGW-10001-08K.'
                        WHEN B.Carat = 14 THEN 'UGGW-10001-19K'
                        ELSE 'UGGW-10001-06K'
                    END AS kom,
                    B.IDM,
                    B.Ordinal,
                    P.ID as verid,
                    PC.Description as kadar,
                    C.WorkOrder,
                    X.ID IDFG,
                    CASE WHEN X.VarCarat IS NULL THEN 'acclama' ELSE 'accbaru' END AS ACC,
                    CASE 
                        WHEN B.Carat = 1 THEN 182835 
                        WHEN B.Carat = 3 THEN 182836
                        WHEN B.Carat = 4 THEN 182837
                        WHEN B.Carat = 5 THEN 182838
                        WHEN B.Carat = 6 THEN 182839
                        WHEN B.Carat = 7 THEN 182840
                        WHEN B.Carat = 12 THEN 182841
                        WHEN B.Carat = 13 THEN 182842
                        WHEN B.Carat = 14 THEN 182843
                        ELSE 182835
                    END AS kompid
                FROM
                    workschedule A
                    JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                    JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                    JOIN productaccessories D ON IFNULL(C.FG,C.Part) = D.IDM
                    JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                    JOIN product X ON X.ID = D.IDM
                    JOIN product M ON M.ID = X.Model
                    JOIN shorttext N ON N.ID = M.Color
                    JOIN productcarat PC ON PC.ID = B.Carat
                    LEFT JOIN producttrans PX ON PX.Product = P.ID AND PC.ID = PX.Carat AND PX.Location = 22
                WHERE A.ID = $rph 
                AND P.Description like '%Ulir%'
                UNION
                SELECT
                    B.*,
                    (B.Qty * D.Qty) as Qtycin,
                    X.Description,
                    X.SW,
                    CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                    N.Description as Kateg,
                    CASE 
                        WHEN B.Carat = 1 THEN 'UGGW-10001-06K' 
                        WHEN B.Carat = 3 THEN 'UGGW-10001-08K'
                        WHEN B.Carat = 4 THEN 'UGGW-10001-16K'
                        WHEN B.Carat = 5 THEN 'UGGW-10001-17K'
                        WHEN B.Carat = 6 THEN 'UGGW-10001-17K.'
                        WHEN B.Carat = 7 THEN 'UGGW-10001-20K'
                        WHEN B.Carat = 12 THEN 'UGGW-10001-10K'
                        WHEN B.Carat = 13 THEN 'UGGW-10001-08K.'
                        WHEN B.Carat = 14 THEN 'UGGW-10001-19K'
                        ELSE 'UGGW-10001-06K'
                    END AS kom,
                    B.IDM,
                    B.Ordinal,
                    P.ID as verid,
                    PC.Description as kadar,
                    C.WorkOrder,
                    X.ID IDFG,
                    CASE WHEN X.VarCarat IS NULL THEN 'acclama' ELSE 'accbaru' END AS ACC,
                    CASE 
                        WHEN B.Carat = 1 THEN 182835 
                        WHEN B.Carat = 3 THEN 182836
                        WHEN B.Carat = 4 THEN 182837
                        WHEN B.Carat = 5 THEN 182838
                        WHEN B.Carat = 6 THEN 182839
                        WHEN B.Carat = 7 THEN 182840
                        WHEN B.Carat = 12 THEN 182841
                        WHEN B.Carat = 13 THEN 182842
                        WHEN B.Carat = 14 THEN 182843
                        ELSE 182835
                    END AS kompid
                FROM
                    workschedule A
                    JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                    JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                    JOIN productaccessories D ON IFNULL(C.FG,C.Part) = D.IDM
                    JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                    JOIN product X ON X.ID = D.IDM
                    JOIN product M ON M.ID = X.Model
                    JOIN shorttext N ON N.ID = M.Color
                    JOIN productcarat PC ON PC.ID = B.Carat
                    LEFT JOIN producttrans PX ON PX.Product = P.Color AND PC.ID = PX.Carat AND PX.Location = 22
                WHERE A.ID = $rph 
                AND P.Description like '%Ulir%' ";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT SW, jml, brt, kadar, Carat, kompid FROM (
                    SELECT
                        CASE 
                            WHEN B.Carat = 1 THEN 'UGGW-10001-06K' 
                            WHEN B.Carat = 3 THEN 'UGGW-10001-08K'
                            WHEN B.Carat = 4 THEN 'UGGW-10001-16K'
                            WHEN B.Carat = 5 THEN 'UGGW-10001-17K'
                            WHEN B.Carat = 6 THEN 'UGGW-10001-17K.'
                            WHEN B.Carat = 7 THEN 'UGGW-10001-20K'
                            WHEN B.Carat = 12 THEN 'UGGW-10001-10K'
                            WHEN B.Carat = 13 THEN 'UGGW-10001-08K.'
                            WHEN B.Carat = 14 THEN 'UGGW-10001-19K'
                            ELSE 'UGGW-10001-06K'
                        END AS SW,
                        SUM(B.Qty * D.Qty) as jml,
                        FORMAT(SUM(B.Weight), 2) as brt,
                        PC.Description as kadar, B.Carat,
                        CASE 
                            WHEN B.Carat = 1 THEN 182835 
                            WHEN B.Carat = 3 THEN 182836
                            WHEN B.Carat = 4 THEN 182837
                            WHEN B.Carat = 5 THEN 182838
                            WHEN B.Carat = 6 THEN 182839
                            WHEN B.Carat = 7 THEN 182840
                            WHEN B.Carat = 12 THEN 182841
                            WHEN B.Carat = 13 THEN 182842
                            WHEN B.Carat = 14 THEN 182843
                            ELSE 182835
                        END AS kompid
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN productaccessories D ON IFNULL(C.FG,C.Part) = D.IDM
                        JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                        JOIN product X ON X.ID = D.IDM
                        JOIN product M ON M.ID = X.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P.ID AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    AND P.Description like '%Ulir%'
                    GROUP BY SW, B.Carat
                    UNION
                    SELECT
                        CASE 
                            WHEN B.Carat = 1 THEN 'UGGW-10001-06K' 
                            WHEN B.Carat = 3 THEN 'UGGW-10001-08K'
                            WHEN B.Carat = 4 THEN 'UGGW-10001-16K'
                            WHEN B.Carat = 5 THEN 'UGGW-10001-17K'
                            WHEN B.Carat = 6 THEN 'UGGW-10001-17K.'
                            WHEN B.Carat = 7 THEN 'UGGW-10001-20K'
                            WHEN B.Carat = 12 THEN 'UGGW-10001-10K'
                            WHEN B.Carat = 13 THEN 'UGGW-10001-08K.'
                            WHEN B.Carat = 14 THEN 'UGGW-10001-19K'
                            ELSE 'UGGW-10001-06K'
                        END AS SW,
                        SUM(B.Qty * D.Qty) as jml,
                        FORMAT(SUM(B.Weight), 2) as brt,
                        PC.Description as kadar, B.Carat,
                        CASE 
                            WHEN B.Carat = 1 THEN 182835 
                            WHEN B.Carat = 3 THEN 182836
                            WHEN B.Carat = 4 THEN 182837
                            WHEN B.Carat = 5 THEN 182838
                            WHEN B.Carat = 6 THEN 182839
                            WHEN B.Carat = 7 THEN 182840
                            WHEN B.Carat = 12 THEN 182841
                            WHEN B.Carat = 13 THEN 182842
                            WHEN B.Carat = 14 THEN 182843
                            ELSE 182835
                        END AS kompid
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN productaccessories D ON IFNULL(C.FG,C.Part) = D.IDM
                        JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                        JOIN product X ON X.ID = D.IDM
                        JOIN product M ON M.ID = X.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P.Color AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    AND P.Description like '%Ulir%'
                    GROUP BY SW, B.Carat
                    ) AS SummaryKomp
                    ORDER BY Carat ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $query3 = "SELECT A.Active FROM workschedule A WHERE A.ID = $rph ";
        $data3 = FacadesDB::connection('erp')->select($query3);
        foreach($data3 as $datas3){}
        $status = $datas3->Active;

        $query4 = "SELECT COUNT(*) AS rowcount FROM componentrequestitem A WHERE A.LinkID = $rph ";
        $data4 = FacadesDB::connection('erp')->select($query4);
        foreach($data4 as $datas4){}
        $rowcount = $datas4->rowcount;

        $returnHTML = view('Produksi.JadwalKerjaHarian.PermintaanKomponen.show', compact('data','data2','location'))->render();
        return response()->json( array('html' => $returnHTML, 'status' => $status, 'rowcount' => $rowcount) );
        
    }

    public function periksaSepuhPCB(Request $request){

        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $rph = $request->rph;

        $query = "SELECT 
                    WSI.*,
                    (WSI.QTY * 2) as Qtycin,
                    CASE 
                        WHEN WSI.Carat = 1 THEN 182835 
                        WHEN WSI.Carat = 3 THEN 182836
                        WHEN WSI.Carat = 4 THEN 182837
                        WHEN WSI.Carat = 5 THEN 182838
                        WHEN WSI.Carat = 6 THEN 182839
                        WHEN WSI.Carat = 7 THEN 182840
                        WHEN WSI.Carat = 12 THEN 182841
                        WHEN WSI.Carat = 13 THEN 182842
                        WHEN WSI.Carat = 14 THEN 182843
                        ELSE 182835
                    END AS verid,
                    CONCAT(TMI.WorkAllocation,'-',TMI.LinkFreq,'-',TMI.LinkOrd) as NTKHO,
                    PC.Description as kadar,
                    TMI.WorkOrder, TMI.FG as IDFG,
                    CASE 
                        WHEN WSI.Carat = 1 THEN 'UGGW-10001-06K' 
                        WHEN WSI.Carat = 3 THEN 'UGGW-10001-08K'
                        WHEN WSI.Carat = 4 THEN 'UGGW-10001-16K'
                        WHEN WSI.Carat = 5 THEN 'UGGW-10001-17K'
                        WHEN WSI.Carat = 6 THEN 'UGGW-10001-17K.'
                        WHEN WSI.Carat = 7 THEN 'UGGW-10001-20K'
                        WHEN WSI.Carat = 12 THEN 'UGGW-10001-10K'
                        WHEN WSI.Carat = 13 THEN 'UGGW-10001-08K.'
                        WHEN WSI.Carat = 14 THEN 'UGGW-10001-19K'
                        ELSE 'UGGW-10001-06K'
                    END AS kom,
                    P.SW, P.ProdGroup, ST.Description Kateg,
                    CASE WHEN WSI.Carat IS NOT NULL THEN 'acclama' END AS ACC
                FROM 
                    WORKSCHEDULE WS
                    JOIN WORKSCHEDULEITEM WSI ON WS.ID=WSI.IDM
                    JOIN TRANSFERRMITEM TMI ON WSI.LINKID=TMI.IDM AND WSI.LINKORD=TMI.ORDINAL
                    JOIN TRANSFERRM TM ON TMI.IDM=TM.ID
                    JOIN WORKORDER WO ON TMI.WORKORDER=WO.ID
                    JOIN productcarat PC ON PC.ID = WSI.Carat
                    JOIN product P ON TMI.FG=P.ID
                    JOIN shorttext ST ON ST.ID=P.ProdGroup 
                WHERE
                    TM.TOLOC=50
                    AND TM.FROMLOC=56
                    AND WO.SWPURPOSE='PCB'
                    AND P.ProdGroup IN (67,6,163)
                    AND P.Description LIKE '%Giwang%'
                    AND WS.ID = $rph ";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT 
                        WSI.*,
                        SUM(WSI.QTY * 2) as jml,
                        FORMAT(SUM(WSI.Weight), 2) as brt,
                        CASE 
                            WHEN WSI.Carat = 1 THEN 182835 
                            WHEN WSI.Carat = 3 THEN 182836
                            WHEN WSI.Carat = 4 THEN 182837
                            WHEN WSI.Carat = 5 THEN 182838
                            WHEN WSI.Carat = 6 THEN 182839
                            WHEN WSI.Carat = 7 THEN 182840
                            WHEN WSI.Carat = 12 THEN 182841
                            WHEN WSI.Carat = 13 THEN 182842
                            WHEN WSI.Carat = 14 THEN 182843
                            ELSE 182835
                        END AS verid,
                        CONCAT(TMI.WorkAllocation,'-',TMI.LinkFreq,'-',TMI.LinkOrd) as NTKHO,
                        PC.Description as kadar,
                        TMI.WorkOrder,
                        CASE 
                            WHEN WSI.Carat = 1 THEN 'UGGW-10001-06K' 
                            WHEN WSI.Carat = 3 THEN 'UGGW-10001-08K'
                            WHEN WSI.Carat = 4 THEN 'UGGW-10001-16K'
                            WHEN WSI.Carat = 5 THEN 'UGGW-10001-17K'
                            WHEN WSI.Carat = 6 THEN 'UGGW-10001-17K.'
                            WHEN WSI.Carat = 7 THEN 'UGGW-10001-20K'
                            WHEN WSI.Carat = 12 THEN 'UGGW-10001-10K'
                            WHEN WSI.Carat = 13 THEN 'UGGW-10001-08K.'
                            WHEN WSI.Carat = 14 THEN 'UGGW-10001-19K'
                            ELSE 'UGGW-10001-06K'
                        END AS kom,
                        P.SW, P.ProdGroup, ST.Description Kateg,
                        CASE WHEN WSI.Carat IS NOT NULL THEN 'acclama' END AS ACC
                    FROM 
                        WORKSCHEDULE WS
                        JOIN WORKSCHEDULEITEM WSI ON WS.ID=WSI.IDM
                        JOIN TRANSFERRMITEM TMI ON WSI.LINKID=TMI.IDM AND WSI.LINKORD=TMI.ORDINAL
                        JOIN TRANSFERRM TM ON TMI.IDM=TM.ID
                        JOIN WORKORDER WO ON TMI.WORKORDER=WO.ID
                        JOIN productcarat PC ON PC.ID = WSI.Carat
                        JOIN product P ON TMI.FG=P.ID
                        JOIN shorttext ST ON ST.ID=P.ProdGroup 
                    WHERE
                        TM.TOLOC=50
                        AND TM.FROMLOC=56
                        AND WO.SWPURPOSE='PCB'
                        AND P.ProdGroup IN (67,6,163)
                        AND P.Description LIKE '%Giwang%'
                        AND WS.ID = $rph 
                    GROUP BY verid, WSI.Carat ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $query3 = "SELECT A.Active FROM workschedule A WHERE A.ID = $rph ";
        $data3 = FacadesDB::connection('erp')->select($query3);
        foreach($data3 as $datas3){}
        $status = $datas3->Active;

        $query4 = "SELECT COUNT(*) AS rowcount FROM componentrequestitem A WHERE A.LinkID = $rph ";
        $data4 = FacadesDB::connection('erp')->select($query4);
        foreach($data4 as $datas4){}
        $rowcount = $datas4->rowcount;

        $returnHTML = view('Produksi.JadwalKerjaHarian.PermintaanKomponen.show', compact('data','data2','location'))->render();
        return response()->json( array('html' => $returnHTML, 'status' => $status, 'rowcount' => $rowcount) );

    }

    public function periksaKikir(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $rph = $request->rph;

        $arrOrdinal = array();
        $arrCarat = array();
        $arrQty = array();
        $arrQtyFix = array();
        $arrPID = array();

        // Data Komponen yg Diminta Bu Minuk Untuk Bisa Di-Stok di Area Assembling
        $modelKomp = array(44,77,5628,5627,83);
        $queryCek = "SELECT 
                        WSI.IDM, WSI.Ordinal, TMI.WorkOrder, WSI.Carat, 4 AS QtyTambahan, P.ID PID
                    FROM 
                        workschedule WS
                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                        JOIN transferrmitem TMI ON TMI.IDM = WSI.LinkID AND TMI.Ordinal = WSI.LinkOrd
                        JOIN rndnew.productaccessories_v2 PA ON IFNULL(TMI.FG,TMI.Part) = PA.IDM AND PA.Carat=WSI.Carat
                        JOIN product P ON P.ID = PA.Product AND P.ProdGroup = 150 AND P.Model IN (44,77,5628,5627,83)
                    WHERE 
                        WS.ID = $rph
                        AND WSI.Qty > 0
                    GROUP BY 
                        P.ID, TMI.WorkOrder
                    ORDER BY WSI.Carat ";
        $dataCek = FacadesDB::connection('erp')->select($queryCek);
        $rowcount = count($dataCek);

        // Loop QtyTambahan
        if($rowcount > 0){

            foreach($dataCek as $datasCek){
                array_push($arrOrdinal, $datasCek->Ordinal); 
                array_push($arrCarat, $datasCek->Carat); 
                array_push($arrQty, $datasCek->QtyTambahan);
            }

            $jmlqty = array_sum($arrQty);
        
            $queryCek2 = "SELECT SUM(QtyTambahan) QtyTambahan, PID
                            FROM (
                                    SELECT 
                                        WSI.IDM, WSI.Ordinal, TMI.WorkOrder, WSI.Carat, 4 AS QtyTambahan, P.ID PID
                                    FROM 
                                        workschedule WS
                                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                                        JOIN transferrmitem TMI ON TMI.IDM = WSI.LinkID AND TMI.Ordinal = WSI.LinkOrd
                                        JOIN rndnew.productaccessories_v2 PA ON IFNULL(TMI.FG,TMI.Part) = PA.IDM
                                        JOIN product P ON P.ID = PA.Product AND P.ProdGroup = 150 AND P.Model IN (44,77,5628,5627,83)
                                    WHERE 
                                        WS.ID = $rph
                                        AND WSI.Qty > 0
                                    GROUP BY 
                                        P.ID, TMI.WorkOrder
                                    ORDER BY WSI.Carat
                                ) Results
                            GROUP BY PID
                            ORDER BY PID ";
            $dataCek2 = FacadesDB::connection('erp')->select($queryCek2);

            foreach($dataCek2 as $datasCek2){
                array_push($arrQtyFix, $datasCek2->QtyTambahan); 
                array_push($arrPID, $datasCek2->PID); 
            }
        
        }else{
            $jmlqty = 0;
        }

        // Loop LinkID
        $arrWO = array();
        $queryCek3 = "SELECT WSI.LinkID, TMI.WorkOrder 
                    FROM
                        workschedule WS
                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                        JOIN transferrmitem TMI ON TMI.IDM = WSI.LinkID AND TMI.Ordinal = WSI.LinkOrd
                    WHERE 
                        WS.ID = $rph
                        AND WSI.Qty > 0
                    GROUP BY TMI.WorkOrder ";
        $dataCek3 = FacadesDB::connection('erp')->select($queryCek3);

        foreach($dataCek3 as $datasCek3){
            array_push($arrWO, $datasCek3->LinkID); 
        }

        $query = "SELECT
                    B.*,
                    (IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as Qtycin,
                    X.Description,
                    X.SW,
                    CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                    N.Description as Kateg,
                    P.SW kom,
                    B.IDM,
                    B.Ordinal,
                    P.ID as verid,
                    PC.Description as kadar,
                    C.WorkOrder, B.IDM BIDM, B.Ordinal BOrdinal, C.FG CFG
                FROM
                    workschedule A
                    JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                    JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                    JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,C.Part) = D.IDM 
                    JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                    AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                    JOIN product X ON X.ID = D.IDM
                    JOIN product M ON M.ID = X.Model
                    JOIN shorttext N ON N.ID = M.Color
                    JOIN productcarat PC ON PC.ID = B.Carat
                    LEFT JOIN producttrans PX ON PX.Product = P.ID AND PC.ID = PX.Carat AND PX.Location = 22
                WHERE A.ID = $rph 
                UNION
                SELECT
                    B.*,
                    (IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as Qtycin,
                    X.Description,
                    X.SW,
                    CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                    N.Description as Kateg,
                    P.SW kom,
                    B.IDM,
                    B.Ordinal,
                    P.ID as verid,
                    PC.Description as kadar,
                    C.WorkOrder, B.IDM BIDM, B.Ordinal BOrdinal, C.FG CFG
                FROM
                    workschedule A
                    JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                    JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                    JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,C.Part) = D.IDM
                    JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                    AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                    JOIN product X ON X.ID = D.IDM
                    JOIN product M ON M.ID = X.Model
                    JOIN shorttext N ON N.ID = M.Color
                    JOIN productcarat PC ON PC.ID = B.Carat
                    LEFT JOIN producttrans PX ON PX.Product = P.Color AND PC.ID = PX.Carat AND PX.Location = 22
                WHERE A.ID = $rph 
                ORDER BY BIDM, BOrdinal, CFG, verid ";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT SW, jml, brt, kadar, Carat, Model, PID FROM (
                    SELECT
                        P.SW,
                        SUM(IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as jml,
                        FORMAT(SUM(B.Weight), 2) as brt,
                        PC.Description as kadar, B.Carat, P.Model, P.ID PID
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,C.Part) = D.IDM
                        JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                        AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                        JOIN product X ON X.ID = D.IDM
                        JOIN product M ON M.ID = X.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P.ID AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    GROUP BY P.SW, B.Carat
                    UNION
                    SELECT
                        P.SW,
                        SUM(IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as jml,
                        FORMAT(SUM(B.Weight), 2) as brt,
                        PC.Description as kadar, B.Carat, P.Model, P.ID PID
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,C.Part) = D.IDM
                        JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                        AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                        JOIN product X ON X.ID = D.IDM
                        JOIN product M ON M.ID = X.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P.Color AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    GROUP BY P.SW, B.Carat
                ) AS SummaryKomp
                ORDER BY Carat ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $query3 = "SELECT A.Active FROM workschedule A WHERE A.ID = $rph ";
        $data3 = FacadesDB::connection('erp')->select($query3);
        foreach($data3 as $datas3){}
        $status = $datas3->Active;

        $query4 = "SELECT COUNT(*) AS rowcount FROM componentrequestitem A WHERE A.LinkID = $rph ";
        $data4 = FacadesDB::connection('erp')->select($query4);
        foreach($data4 as $datas4){}
        $rowcount = $datas4->rowcount;

        $returnHTML = view('Produksi.JadwalKerjaHarian.PermintaanKomponen.show', compact('data','data2','arrOrdinal','arrPID','arrWO','arrQtyFix','location','jmlqty'))->render();
        return response()->json( array('html' => $returnHTML, 'status' => $status, 'rowcount' => $rowcount) );

    }

    public function periksaKikirDC(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        $rph = $request->rph;

        $arrOrdinal = array();
        $arrCarat = array();
        $arrQty = array();
        $arrQtyFix = array();
        $arrPID = array();

        // Data Komponen yg Diminta Bu Minuk Untuk Bisa Di-Stok di Area Assembling
        $modelKomp = array(44,77,5628,5627,83);
        $queryCek = "SELECT 
                        WSI.IDM, WSI.Ordinal, TMI.WorkOrder, WSI.Carat, 4 AS QtyTambahan, P.ID PID
                    FROM 
                        workschedule WS
                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                        JOIN transferrmitem TMI ON TMI.IDM = WSI.LinkID AND TMI.Ordinal = WSI.LinkOrd
                        JOIN rndnew.productaccessories_v2 PA ON IFNULL(TMI.FG,TMI.Part) = PA.IDM AND PA.Carat=WSI.Carat
                        JOIN product P ON P.ID = PA.Product AND P.ProdGroup = 150 AND P.Model IN (44,77,5628,5627,83)
                    WHERE 
                        WS.ID = $rph
                        AND WSI.Qty > 0
                    GROUP BY 
                        P.ID, TMI.WorkOrder
                    ORDER BY WSI.Carat ";
        $dataCek = FacadesDB::connection('erp')->select($queryCek);
        $rowcount = count($dataCek);

        // Loop QtyTambahan
        if($rowcount > 0){

            foreach($dataCek as $datasCek){
                array_push($arrOrdinal, $datasCek->Ordinal); 
                array_push($arrCarat, $datasCek->Carat); 
                array_push($arrQty, $datasCek->QtyTambahan);
            }

            $jmlqty = array_sum($arrQty);
        
            $queryCek2 = "SELECT SUM(QtyTambahan) QtyTambahan, PID
                            FROM (
                                    SELECT 
                                        WSI.IDM, WSI.Ordinal, TMI.WorkOrder, WSI.Carat, 4 AS QtyTambahan, P.ID PID
                                    FROM 
                                        workschedule WS
                                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                                        JOIN transferrmitem TMI ON TMI.IDM = WSI.LinkID AND TMI.Ordinal = WSI.LinkOrd
                                        JOIN rndnew.productaccessories_v2 PA ON IFNULL(TMI.FG,TMI.Part) = PA.IDM
                                        JOIN product P ON P.ID = PA.Product AND P.ProdGroup = 150 AND P.Model IN (44,77,5628,5627,83)
                                    WHERE 
                                        WS.ID = $rph
                                        AND WSI.Qty > 0
                                    GROUP BY 
                                        P.ID, TMI.WorkOrder
                                    ORDER BY WSI.Carat
                                ) Results
                            GROUP BY PID
                            ORDER BY PID ";
            $dataCek2 = FacadesDB::connection('erp')->select($queryCek2);

            foreach($dataCek2 as $datasCek2){
                array_push($arrQtyFix, $datasCek2->QtyTambahan); 
                array_push($arrPID, $datasCek2->PID); 
            }
        
        }else{
            $jmlqty = 0;
        }

        // Loop LinkID
        $arrWO = array();
        $queryCek3 = "SELECT WSI.LinkID, TMI.WorkOrder 
                    FROM
                        workschedule WS
                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                        JOIN transferrmitem TMI ON TMI.IDM = WSI.LinkID AND TMI.Ordinal = WSI.LinkOrd
                    WHERE 
                        WS.ID = $rph
                        AND WSI.Qty > 0
                    GROUP BY TMI.WorkOrder ";
        $dataCek3 = FacadesDB::connection('erp')->select($queryCek3);

        foreach($dataCek3 as $datasCek3){
            array_push($arrWO, $datasCek3->LinkID); 
        }

        $query = "SELECT
                        B.*,
                        (IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as Qtycin,
                        X.Description,
                        X.SW,
                        CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                        N.Description as Kateg,
                        P.SW kom,
                        B.IDM,
                        B.Ordinal,
                        P.ID as verid,
                        PC.Description as kadar,
                        C.WorkOrder, B.IDM BIDM, B.Ordinal BOrdinal, C.FG CFG
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN workorderitem WOI ON WOI.IDM=C.WorkOrder
                        JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,WOI.Product) = D.IDM 
                        JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                        AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                        JOIN product X ON X.ID = D.IDM
                        JOIN product M ON M.ID = X.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P.ID AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    UNION
                    SELECT
                        B.*,
                        (IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as Qtycin,
                        X.Description,
                        X.SW,
                        CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                        N.Description as Kateg,
                        P.SW kom,
                        B.IDM,
                        B.Ordinal,
                        P.ID as verid,
                        PC.Description as kadar,
                        C.WorkOrder, B.IDM BIDM, B.Ordinal BOrdinal, C.FG CFG
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN workorderitem WOI ON WOI.IDM=C.WorkOrder
                        JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,WOI.Product) = D.IDM
                        JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                        AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                        JOIN product X ON X.ID = D.IDM
                        JOIN product M ON M.ID = X.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P.Color AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    UNION
                    SELECT 
                        B.*,
                        (IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * PPT.Qty) as Qtycin,
                        X.Description,
                        X.SW,
                        CONCAT(C.WorkAllocation,'-',C.LinkFreq,'-',C.LinkOrd) as NTKHO,
                        N.Description as Kateg,
                        P1.SW kom,
                        B.IDM,
                        B.Ordinal,
                        P1.ID as verid,
                        PC.Description as kadar,
                        C.WorkOrder, B.IDM BIDM, B.Ordinal BOrdinal, C.FG CFG
                    FROM
                        workschedule A
                        JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                        JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                        JOIN workorderitem WOI ON WOI.IDM=C.WorkOrder
                        JOIN productpart PPT ON IFNULL(C.FG,WOI.Product)=PPT.IDM
                        JOIN product P1 on PPT.Product=P1.ID AND P1.ProdGroup=150 AND P1.TypeProcess=27
                        JOIN product X ON X.ID = P1.ID
                        JOIN product M ON M.ID = P1.Model
                        JOIN shorttext N ON N.ID = M.Color
                        JOIN productcarat PC ON PC.ID = B.Carat
                        LEFT JOIN producttrans PX ON PX.Product = P1.ID AND PC.ID = PX.Carat AND PX.Location = 22
                    WHERE A.ID = $rph 
                    ORDER BY BIDM, BOrdinal, CFG, verid";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT SW, jml, brt, kadar, Carat, Model, PID FROM (
                        SELECT
                            P.SW,
                            SUM(IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as jml,
                            FORMAT(SUM(B.Weight), 2) as brt,
                            PC.Description as kadar, B.Carat, P.Model, P.ID PID
                        FROM
                            workschedule A
                            JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                            JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                            JOIN workorderitem WOI ON WOI.IDM=C.WorkOrder
                            JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,WOI.Product) = D.IDM
                            JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                            AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                            JOIN product X ON X.ID = D.IDM
                            JOIN product M ON M.ID = X.Model
                            JOIN shorttext N ON N.ID = M.Color
                            JOIN productcarat PC ON PC.ID = B.Carat
                            LEFT JOIN producttrans PX ON PX.Product = P.ID AND PC.ID = PX.Carat AND PX.Location = 22
                        WHERE A.ID = $rph 
                        GROUP BY P.SW, B.Carat
                        UNION
                        SELECT
                            P.SW,
                            SUM(IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * D.Qty) as jml,
                            FORMAT(SUM(B.Weight), 2) as brt,
                            PC.Description as kadar, B.Carat, P.Model, P.ID PID
                        FROM
                            workschedule A
                            JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                            JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                            JOIN workorderitem WOI ON WOI.IDM=C.WorkOrder
                            JOIN rndnew.productaccessories_v2 D ON IFNULL(C.FG,WOI.Product) = D.IDM
                            JOIN product P ON P.ID = D.Product AND P.ProdGroup = 150
                            AND IF(P.Model NOT IN (10205,1938,1939), B.Carat=D.Carat, 0=D.Carat)
                            JOIN product X ON X.ID = D.IDM
                            JOIN product M ON M.ID = X.Model
                            JOIN shorttext N ON N.ID = M.Color
                            JOIN productcarat PC ON PC.ID = B.Carat
                            LEFT JOIN producttrans PX ON PX.Product = P.Color AND PC.ID = PX.Carat AND PX.Location = 22
                        WHERE A.ID = $rph 
                        GROUP BY P.SW, B.Carat
                        UNION
                        SELECT
                            P1.SW,
                            SUM(IF(B.QtyOrder IS NULL,B.Qty,B.QtyOrder) * PPT.Qty) as jml,
                            FORMAT(SUM(B.Weight), 2) as brt,
                            PC.Description as kadar, B.Carat, P1.Model, P1.ID PID
                        FROM
                            workschedule A
                            JOIN workscheduleitem B ON A.ID = B.IDM And B.Qty > 0
                            JOIN transferrmitem C ON C.IDM = B.LinkID AND C.Ordinal = B.LinkOrd
                            JOIN workorderitem WOI ON WOI.IDM=C.WorkOrder
                            JOIN productpart PPT ON IFNULL(C.FG,WOI.Product)=PPT.IDM
                            JOIN product P1 on PPT.Product=P1.ID AND P1.ProdGroup=150 AND P1.TypeProcess=27
                            JOIN product X ON X.ID = P1.ID
                            JOIN product M ON M.ID = P1.Model
                            JOIN shorttext N ON N.ID = M.Color
                            JOIN productcarat PC ON PC.ID = B.Carat
                            LEFT JOIN producttrans PX ON PX.Product = P1.ID AND PC.ID = PX.Carat AND PX.Location = 22
                        WHERE A.ID = $rph 
                        GROUP BY P1.SW, B.Carat
                    ) AS SummaryKomp
                    ORDER BY Carat";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $query3 = "SELECT A.Active FROM workschedule A WHERE A.ID = $rph ";
        $data3 = FacadesDB::connection('erp')->select($query3);
        foreach($data3 as $datas3){}
        $status = $datas3->Active;

        $query4 = "SELECT COUNT(*) AS rowcount FROM componentrequestitem A WHERE A.LinkID = $rph ";
        $data4 = FacadesDB::connection('erp')->select($query4);
        foreach($data4 as $datas4){}
        $rowcount = $datas4->rowcount;

        $returnHTML = view('Produksi.JadwalKerjaHarian.PermintaanKomponen.show', compact('data','data2','arrOrdinal','arrPID','arrWO','arrQtyFix','location','jmlqty'))->render();
        return response()->json( array('html' => $returnHTML, 'status' => $status, 'rowcount' => $rowcount) );

    }

    public function stokCB(){
        $location = session('location');
        $UserEntry = session('UserEntry');
        $iduser = session('iduser');

        if($location == NULL){
            $location = 4;
        }

        
        $querySepuh = "SELECT 
                        C.SW Kode, C.Description Nama, B.Description Kadar, A.Qty Jumlah, A.Weight Berat
                    FROM 
                        producttrans A
                        LEFT JOIN productcarat B ON A.Carat=B.ID
                        LEFT JOIN product C ON A.Product=C.ID
                    WHERE 
                        A.Location=22 AND C.ProdGroup=150 AND (C.FileCost IS NULL OR C.FileCost=0) AND C.UserName NOT IN ('Niko','Dhora') AND C.SW LIKE 'UGGW%'
                    ORDER BY C.SW
                    ";

        $queryKikir = "SELECT 
                        C.SW Kode, C.Description Nama, B.Description Kadar, A.Qty Jumlah, A.Weight Berat
                    FROM 
                        producttrans A
                        LEFT JOIN productcarat B ON A.Carat=B.ID
                        LEFT JOIN product C ON A.Product=C.ID
                    WHERE 
                        A.Location=22 AND C.ProdGroup=150 AND (C.FileCost IS NULL OR C.FileCost=0) AND C.UserName NOT IN ('Niko','Dhora') AND C.SW NOT LIKE 'UGGW%'
                    ORDER BY C.SW
                    ";

        if($location == 50){
            $query = $querySepuh;
        }else{
            $query = $queryKikir;
        }

        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.JadwalKerjaHarian.PermintaanKomponen.stokcb', compact('data'))->render();
        return response()->json( array('html' => $returnHTML) );

    }

}

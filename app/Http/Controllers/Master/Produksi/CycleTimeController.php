<?php

namespace App\Http\Controllers\Master\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use \DateTime;
use \DateTimeZone;


class CycleTimeController extends Controller
{
    public function index(){

        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        if($UserEntry == 'NuriC'){
            $location = 12;
        }

        // reset auto increment
        // $query = "ALTER TABLE rndnew.appmastercycletime AUTO_INCREMENT=0";

        $query = "SELECT * FROM rndnew.appoperation WHERE Location=$location ";
        $data = FacadesDB::connection('erp')->select($query);

        return view('Master.Produksi.CycleTime.index', compact('data'));
        
    }

    public function simpan(Request $request){

        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $tgl = $date->format('Y-m-d');
        $tahun = $date->format("y");
        $bulan = $date->format("m");

        $operation = $request->operation;
        $pilih = $request->pilih;

        $data = explode(",",$pilih);
 
        
        foreach ($data as $key) 
        {
            $super = explode("-", $key);
            $cycletime = $super[0];
            $subkategori = $super[1];
            // $model = $super[2];
            // $operator = $super[3];
                    
            // $query = "INSERT INTO rndnew.appmastercycletime (EntryDate, UserName, ValidDate, SubCategory, Product, CycleTime, Operation, Employee, Active, Location) 
            //             VALUES (now(), '$UserEntry', '$tgl', $subkategori, $model, $cycletime, $operation, $operator, 1)";

            $query = "INSERT INTO rndnew.appmastercycletime (EntryDate, UserName, ValidDate, SubCategory, CycleTime, Operation, Active, Location) 
                        VALUES (now(), '$UserEntry', '$tgl', $subkategori, $cycletime, $operation, 1, $location)";
            // dd($query);
            $insert = FacadesDB::connection('erp')->insert($query);      
        }  

        foreach ($data as $key){
            $super = explode("-", $key);
            $subkategori = $super[1];

            $queryUpdate = "UPDATE rndnew.appmastercycletime  
                            SET
                                EntryDate = now(),
                                Active = 0
                            WHERE 
                                SubCategory = $subkategori 
                                AND Location = $location
                                AND Active = 1
                                AND Operation = $operation
                                AND ValidDate < '$tgl'
                                ";
            $update = FacadesDB::connection('erp')->update($queryUpdate);

        }

        if($insert == TRUE && $update == TRUE){
            $goto = array('status' => 'success');
        }else{
            $goto = array('status' => 'failed');
        }

        return response()->json($goto);

    }


    public function lihat(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        if($UserEntry == 'NuriC'){
            $location = 12;
        }
     
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $operation =  $request->operation;

        $batasBawahCincin = 10;
        $batasAtasCincin = 80;
        $batasBawahLiontin = 10;
        $batasAtasLiontin = 70;
        $batasBawahGelang = 10;
        $batasAtasGelang = 170;
        $batasBawahAnting = 10;
        $batasAtasAnting = 30;
      
        $query = "SELECT KATEGORI, SUBKATEGORI, SUM(AVGTIME) SumAvg, COUNT(SUBKATEGORI) SumSPKO, FORMAT( (SUM(AVGTIME) / COUNT(SUBKATEGORI)), 2) MasterCycleTime, 
                    CEILING(MAX(AVGTIME+0)) MAX, CEILING(MIN(AVGTIME+0)) MIN, CEILING(SUM(AVGTIME) / COUNT(SUBKATEGORI)) MasterCycleTimeRound, 
                    CONCAT(CEILING(SUM(AVGTIME) / COUNT(SUBKATEGORI)),'-',IDSUBKATEGORI) AS kirimkan, @rownum := @rownum + 1 as ID FROM (
                    SELECT * FROM (
                        SELECT 
                                GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                                WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                                IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                                SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                                FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                                FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, P1.MODEL IDSUBKATEGORI, P1.SERIALNO MODELFG
                        FROM WORKALLOCATION WA
                                JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                                JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                                JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                                JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                                LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                                LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                                LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                                LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                        WHERE WL.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                        AND ST.DESCRIPTION = 'Cincin'
                        AND WL.OPERATION = '$operation'
                        GROUP BY WL.SPKO
                    ) Result
                    WHERE AVGTIME BETWEEN $batasBawahCincin AND $batasAtasCincin
                    UNION
                    SELECT * FROM (
                        SELECT 
                                GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                                WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                                IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                                SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                                FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                                FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, P1.MODEL IDSUBKATEGORI, P1.SERIALNO MODELFG
                        FROM WORKALLOCATION WA
                                JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                                JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                                JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                                JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                                LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                                LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                                LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                                LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                        WHERE WL.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                        AND ST.DESCRIPTION = 'Liontin'
                        AND WL.OPERATION = '$operation'
                        GROUP BY WL.SPKO
                    ) Result
                    WHERE AVGTIME BETWEEN $batasBawahLiontin AND $batasAtasLiontin
                    UNION
                    SELECT * FROM (
                        SELECT 
                                GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                                WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                                IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                                SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                                FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                                FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, P1.MODEL IDSUBKATEGORI, P1.SERIALNO MODELFG
                        FROM WORKALLOCATION WA
                                JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                                JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                                JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                                JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                                LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                                LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                                LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                                LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                        WHERE WL.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                        AND ST.DESCRIPTION = 'Gelang'
                        AND WL.OPERATION = '$operation'
                        GROUP BY WL.SPKO
                    ) Result
                    WHERE AVGTIME BETWEEN $batasBawahGelang AND $batasAtasGelang
                    UNION
                    SELECT * FROM (
                        SELECT 
                                GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                                WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                                IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                                SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                                FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                                FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, P1.MODEL IDSUBKATEGORI, P1.SERIALNO MODELFG
                        FROM WORKALLOCATION WA
                                JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                                JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                                JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                                JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                                LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                                LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                                LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                                LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                        WHERE WL.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                        AND ST.DESCRIPTION = 'Anting'
                        AND WL.OPERATION = '$operation'
                        GROUP BY WL.SPKO
                    ) Result
                    WHERE AVGTIME BETWEEN $batasBawahAnting AND $batasAtasAnting
                ) Results
                CROSS JOIN (SELECT @rownum := 0) r  
                WHERE SUBKATEGORI IS NOT NULL
                GROUP BY SUBKATEGORI
                ORDER BY SUBKATEGORI ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ['tampil'=>$data] ); 
    }

    public function lihatDataMaster(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 12;
        }

        $query = "SELECT 
                            A.*, IF(A.Active=0,'No','Yes') StatusActive, B.Name OperationName, C.SW SubCategoryName, D.Description CategoryName, E.Description LocationName
                        FROM 
                            rndnew.appmastercycletime A
                            LEFT JOIN rndnew.appoperation B ON A.Operation=B.ID
                            LEFT JOIN product C ON A.SubCategory=C.ID
                            LEFT JOIN shorttext D ON C.ProdGroup=D.ID
                            LEFT JOIN location E ON A.Location=E.ID
                        WHERE 
                            A.Location = $location
                        ORDER BY A.ID ";
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Master.Produksi.CycleTime.datamaster', compact('data'))->render();
        return response()->json( array('html' => $returnHTML) );

    }


  
}

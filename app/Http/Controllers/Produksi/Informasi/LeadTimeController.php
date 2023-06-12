<?php

namespace App\Http\Controllers\Produksi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as FacadesDB;
use \DateTime;
use \DateTimeZone;

class LeadTimeController extends Controller
{
    public function index(){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        if($iddept == NULL || $iddept == 12 || $iddept == 13){
            $iddept = 26;
        }

        // LIST AREA
        $queryArea = "SELECT * FROM LOCATION WHERE SCHEDULING='Y' AND TYPE='L' ";
        $dataArea = FacadesDB::connection('erp')->select($queryArea);

        // LIST KADAR 
        $query2 = "SELECT
                        ID, SW, Description
                    FROM
                        ProductCarat A
                    WHERE
                        A.Regular = 'Y'
                    ORDER BY A.Description
                    ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        // LIST OPERATION 
        $query3 = "SELECT 
                        A.*
                    FROM 
                        Operation A
                    WHERE 
                        A.Location = $location
                        AND A.Active = 'Y'
                    ORDER BY 
                        A.Description
                    ";
        $data3 = FacadesDB::connection('erp')->select($query3);

        // LIST OPERATOR 
        $query4 = "SELECT 
                        ID, SW, Description
                    FROM 
                        Employee A
                    WHERE 
                        A.Department = $iddept
                        AND A.Active = 'Y'
                        AND A.`Rank` = 'Operator' 
                    ORDER BY 
                        A.Description ASC
                    ";
        $data4 = FacadesDB::connection('erp')->select($query4);

        // LIST KATEGORI
        $query5 = "SELECT ID, Description FROM SHORTTEXT WHERE ACTIVE='Y' AND TYPE='FINISH GOOD GROUP' ";
        $data5 = FacadesDB::connection('erp')->select($query5);

        // LIST SUBKATEGORI
        $query6 = "SELECT ID, SW FROM PRODUCT WHERE PRODGROUP IN (SELECT ID FROM SHORTTEXT WHERE ACTIVE='Y' AND TYPE='FINISH GOOD GROUP') AND MODEL IS NULL ";
        $data6 = FacadesDB::connection('erp')->select($query6);

        return view('Produksi.Informasi.LeadTime.index', compact('data2','data3','data4','data5','data6','dataArea'));
    }



    public function cetakAll(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $jenis =  $request->jenis;
        $jeniscetak =  $request->jeniscetak;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $operator =  $request->operator;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $kategori =  $request->kategori;
        $subkategori =  $request->subkategori;

        $query = "";
        $data = FacadesDB::connection('erp')->select($query);

        if($jeniscetak == 1){
            return view('Produksi.Informasi.LeadTime.cetakAll', compact('data','tglstart','tglend','jenis'));
        }else{
            return view('Produksi.Informasi.LeadTime.cetakAllSummary', compact('data','tglstart','tglend','jenis'));
        }
        
    }

    public function report1(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $year = $date->format("y");

        $query = "SELECT 
                        MONTH(WA.TRANSDATE) BULAN, ST.DESCRIPTION KATEGORI, 
                        IF(P.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%', SUM(WAI.QTY*2), SUM(WAI.QTY)) PCS, 
                        SUM(WAI.WEIGHT) WEIGHT, ST.DESCRIPTION KATEGORI, DATE_FORMAT(WA.TRANSDATE, '%b') BULAN, DATE_FORMAT(WA.TRANSDATE, '%Y') TAHUN
                    FROM
                        WORKALLOCATION WA
                        JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                        LEFT JOIN PRODUCT P ON WAI.FG=P.ID
                        LEFT JOIN PRODUCT P2 ON P.MODEL=P2.ID
                        LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                    WHERE 
                        WA.LOCATION=$location AND WA.ACTIVE IN ('A','P','S') AND WA.SWYEAR=$year AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                    GROUP BY MONTH(WA.TRANSDATE), ST.DESCRIPTION ";
                    // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] ); 
    }

    public function report2(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $year = $date->format("y");

        $query = "SELECT TAHUN, BULAN, EMPLOYEE, EMPLOYEENAME, SUM(TARGETQTY) TARGETQTY, SUM(COMPLETIONQTY) COMPLETIONQTY, ( SUM(COMPLETIONQTY)/SUM(TARGETQTY)*100 ) PERSEN FROM (
                    SELECT DATE_FORMAT(A.ALLOCATIONDATE, '%Y') TAHUN, DATE_FORMAT(A.ALLOCATIONDATE, '%b') BULAN, DATE_FORMAT(A.ALLOCATIONDATE, '%m') MONTHSORT, E.DESCRIPTION EMPLOYEENAME, A.* 
                    FROM 
                        WORKALLOCATIONRESULT A 
                        LEFT JOIN EMPLOYEE E ON A.EMPLOYEE=E.ID
                        LEFT JOIN WORKALLOCATION WA ON A.SW=WA.SW AND WA.FREQ=1
                    WHERE A.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                    ) RESULTS
                GROUP BY TAHUN, BULAN, EMPLOYEE
                ORDER BY TAHUN, MONTHSORT, EMPLOYEE ASC ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] ); 
    }

    public function report3(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $year = $date->format("y");

        $query = "SELECT TAHUN, BULAN, EMPLOYEE, EMPLOYEENAME, SUM(TARGETQTY) TARGETQTY, SUM(COMPLETIONQTY) COMPLETIONQTY, ( SUM(COMPLETIONQTY)/SUM(TARGETQTY)*100 ) PERSEN FROM (
                    SELECT DATE_FORMAT(A.ALLOCATIONDATE, '%Y') TAHUN, DATE_FORMAT(A.ALLOCATIONDATE, '%b') BULAN, DATE_FORMAT(A.ALLOCATIONDATE, '%m') MONTHSORT, E.DESCRIPTION EMPLOYEENAME, A.* 
                    FROM 
                        WORKALLOCATIONRESULT A 
                        LEFT JOIN EMPLOYEE E ON A.EMPLOYEE=E.ID
                        LEFT JOIN WORKALLOCATION WA ON A.SW=WA.SW AND WA.FREQ=1
                    WHERE A.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                    ) RESULTS
                GROUP BY TAHUN, BULAN, EMPLOYEE
                ORDER BY TAHUN, MONTHSORT, EMPLOYEE ASC ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] ); 
    }

    public function report4(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $year = $date->format("y");

        $query = "SELECT YEAR, MONTH, DATE, MONTHSORT, EMPLOYEE, EMPLOYEENAME, SUM(TARGETQTY) TARGETQTY, SUM(COMPLETIONQTY) COMPLETIONQTY, ( SUM(COMPLETIONQTY)/SUM(TARGETQTY)*100 ) PERSEN FROM (
                    SELECT DATE_FORMAT(A.ALLOCATIONDATE, '%Y') YEAR, DATE_FORMAT(A.ALLOCATIONDATE, '%m') MONTHSORT, DATE_FORMAT(A.ALLOCATIONDATE, '%b') MONTH, DATE_FORMAT(A.ALLOCATIONDATE, '%d') DATE, 
                    E.DESCRIPTION EMPLOYEENAME, A.* 
                    FROM 
                        WORKALLOCATIONRESULT A 
                        JOIN WORKALLOCATION WA ON A.SW=WA.SW
                        LEFT JOIN EMPLOYEE E ON A.EMPLOYEE=E.ID
                    WHERE A.LOCATION=$location AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                    ) RESULTS
                GROUP BY YEAR, MONTH, DATE, EMPLOYEE
                ORDER BY YEAR, MONTHSORT, EMPLOYEENAME, DATE ASC ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] ); 
    }

    public function report5(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $operation =  $request->operation;

        $query = "SELECT 
                        GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                        WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                        IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                        SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                        FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                        FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                        DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, WL.WAKTUMULAI, WL.WAKTUSELESAI, AO.NAME OPERATIONAPP
                    FROM WORKALLOCATION WA
                        JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                        JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                        JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                        JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                        LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                        LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                        LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                        LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                        LEFT JOIN RNDNEW.APPOPERATION AO ON WL.OPERATION=AO.ID
                    WHERE WL.LOCATION=$location
                    AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND WL.OPERATION = '$operation'
                    GROUP BY WL.SPKO 
                    ORDER BY WL.SPKO ";
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] );
    }

    public function report6(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $operation =  $request->operation;
      
        // GET MASTER VALUE
        $query = "SELECT SUBKATEGORI, SUM(AVGTIME) SumAvg, COUNT(SUBKATEGORI) SumSPKO, FORMAT( (SUM(AVGTIME) / COUNT(SUBKATEGORI)), 2) MasterCycleTime, CEILING(MAX(AVGTIME+0)) MAX, CEILING(MIN(AVGTIME+0)) MIN, CEILING(SUM(AVGTIME) / COUNT(SUBKATEGORI)) MasterCycleTimeRound FROM (
                    SELECT 
                        GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                        WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                        IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                        SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                        FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                        FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                        DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH
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
                    AND WL.OPERATION = '$operation'
                    GROUP BY WL.SPKO 
                    ORDER BY WL.SPKO
                    ) Results
                WHERE SUBKATEGORI IS NOT NULL
                GROUP BY SUBKATEGORI
                ORDER BY SUBKATEGORI ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        $arrSubKategori = array();
        $arrLeadTime = array();

        foreach ($data as $datas){
            array_push($arrSubKategori, $datas->SUBKATEGORI);
            array_push($arrLeadTime, $datas->MasterCycleTimeRound);
        }

        return response()->json( ['tampil'=>$data, 'arrSubKategori' => $arrSubKategori, 'arrLeadTime' => $arrLeadTime] ); 
    }

    public function report7(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $operation =  $request->operation;

        // $batasBawahCincin = 20;
        // $batasAtasCincin = 80;
        // $batasBawahLiontin = 15;
        // $batasAtasLiontin = 70;
        // $batasBawahGelang = 30;
        // $batasAtasGelang = 170;
        // $batasBawahAnting = 10;
        // $batasAtasAnting = 30;

        $batasBawahCincin = 10;
        $batasAtasCincin = 80;
        $batasBawahLiontin = 10;
        $batasAtasLiontin = 70;
        $batasBawahGelang = 10;
        $batasAtasGelang = 170;
        $batasBawahAnting = 10;
        $batasAtasAnting = 30;

        $query = "SELECT * FROM (
                    SELECT 
                            GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                            WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                            IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                            SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                            FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                            FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                            DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, WL.WAKTUMULAI, WL.WAKTUSELESAI, AO.NAME OPERATIONAPP
                    FROM WORKALLOCATION WA
                            JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                            JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                            JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                            JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                            LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                            LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                            LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                            LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                            LEFT JOIN RNDNEW.APPOPERATION AO ON WL.OPERATION=AO.ID
                    WHERE WL.LOCATION=$location
                    AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND ST.DESCRIPTION = 'Cincin'
                    AND WL.OPERATION = '$operation'
                    GROUP BY WL.SPKO 
                ) RESULT
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
                            DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, WL.WAKTUMULAI, WL.WAKTUSELESAI, AO.NAME OPERATIONAPP
                    FROM WORKALLOCATION WA
                            JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                            JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                            JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                            JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                            LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                            LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                            LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                            LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                            LEFT JOIN RNDNEW.APPOPERATION AO ON WL.OPERATION=AO.ID
                    WHERE WL.LOCATION=$location
                    AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND ST.DESCRIPTION = 'Liontin'
                    AND WL.OPERATION = '$operation'
                    GROUP BY WL.SPKO 
                ) RESULT
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
                            DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, WL.WAKTUMULAI, WL.WAKTUSELESAI, AO.NAME OPERATIONAPP
                    FROM WORKALLOCATION WA
                            JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                            JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                            JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                            JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                            LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                            LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                            LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                            LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                            LEFT JOIN RNDNEW.APPOPERATION AO ON WL.OPERATION=AO.ID
                    WHERE WL.LOCATION=$location
                    AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND ST.DESCRIPTION = 'Gelang'
                    AND WL.OPERATION = '$operation'
                    GROUP BY WL.SPKO 
                ) RESULT
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
                            DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH, WL.WAKTUMULAI, WL.WAKTUSELESAI, AO.NAME OPERATIONAPP
                    FROM WORKALLOCATION WA
                            JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                            JOIN RNDNEW.APPWORKLOG WL ON WL.SW=WA.SW AND WL.FREQ=WA.FREQ AND WL.ORDINAL=WAI.ORDINAL
                            JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                            JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                            LEFT JOIN PRODUCT P1 ON WAI.FG=P1.ID
                            LEFT JOIN PRODUCT P2 ON P1.MODEL=P2.ID
                            LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                            LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                            LEFT JOIN RNDNEW.APPOPERATION AO ON WL.OPERATION=AO.ID
                    WHERE WL.LOCATION=$location
                    AND WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND ST.DESCRIPTION = 'Anting'
                    AND WL.OPERATION = '$operation'
                    GROUP BY WL.SPKO 
                ) RESULT
                WHERE AVGTIME BETWEEN $batasBawahAnting AND $batasAtasAnting
                ";
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] );
    }

    public function report8(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
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
      
        $query = "SELECT KATEGORI, SUBKATEGORI, SUM(AVGTIME) SumAvg, COUNT(SUBKATEGORI) SumSPKO, FORMAT( (SUM(AVGTIME) / COUNT(SUBKATEGORI)), 2) MasterCycleTime, CEILING(MAX(AVGTIME+0)) MAX, CEILING(MIN(AVGTIME+0)) MIN, CEILING(SUM(AVGTIME) / COUNT(SUBKATEGORI)) MasterCycleTimeRound FROM (
                    SELECT * FROM (
                        SELECT 
                                GROUP_CONCAT(WL.WORKLOGID SEPARATOR ',') WORKLOGID, WA.ID IDSPKO, WL.SPKO SPKO, WA.TRANSDATE TGLSPKO, WA.CARAT CARATID, PC.DESCRIPTION CARAT, OP.DESCRIPTION OPERATION, 
                                WA.EMPLOYEE EMPLOYEEID, EM.DESCRIPTION EMPLOYEE, P1.SW FG, P2.SW SUBKATEGORI, ST.DESCRIPTION KATEGORI, WAI.QTY QTYSPKO,
                                IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0)) PCSSPKO,
                                SUM(WL.TOTALSECONDS) TOTALSECONDS, 
                                FORMAT( (SUM(WL.TOTALSECONDS) / IF(P1.Description LIKE '%Anting%' OR P1.Description LIKE '%Giwang%', IF(WAI.QTY IS NOT NULL, WAI.QTY*2, 0), IF(WAI.QTY IS NOT NULL, WAI.QTY, 0))),2 ) AVGTIME, 
                                FORMAT((SUM(WL.TOTALSECONDS)/26100)*100,2) WORKHOURPERCENT,
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH
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
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH
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
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH
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
                                DATE_FORMAT(WA.TRANSDATE, '%Y') YEAR, DATE_FORMAT(WA.TRANSDATE, '%b') MONTH
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
                WHERE SUBKATEGORI IS NOT NULL
                GROUP BY SUBKATEGORI
                ORDER BY SUBKATEGORI ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        $arrSubKategori = array();
        $arrLeadTime = array();

        foreach ($data as $datas){
            array_push($arrSubKategori, $datas->SUBKATEGORI);
            array_push($arrLeadTime, $datas->MasterCycleTimeRound);
        }

        return response()->json( ['tampil'=>$data, 'arrSubKategori' => $arrSubKategori, 'arrLeadTime' => $arrLeadTime] ); 
    }

    public function report100(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $year = $date->format("y");

        $query = "SELECT * FROM ( ";
        $query .= "SELECT 
                    E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, 
                    WO.SW WOSW, WC.TransDate TglNTHKO,
                    IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)) QtySPKO, (SELECT SUM(WEIGHT) FROM WORKALLOCATION WHERE SW=WA.SW) WeightSPKO, 
                    IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0))) GoodQtyNTHKO, 
                    IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0))) NoGoodQtyNTHKORep, 
                    IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0))) NoGoodQtyNTHKOSS, 
                    FORMAT(SUM(IF(WCI.WEIGHT IS NOT NULL, WCI.WEIGHT, 0)),2) GoodWeightNTHKO, 
                    FORMAT(SUM(IF(WCI.REPAIRWEIGHT IS NOT NULL, WCI.REPAIRWEIGHT, 0)),2) NoGoodWeightNTHKORep, 
                    FORMAT(SUM(IF(WCI.SCRAPWEIGHT IS NOT NULL, WCI.SCRAPWEIGHT, 0)),2) NoGoodWeightNTHKOSS, 
                    F.SW FDescription, ST.Description Kategori, 
                    WA.SW WASW, E.Description EDescription, PC.Description PCDescription, ST.Description STDescription, F.SW FSW, OP.Description OPDescription
                FROM WORKALLOCATION WA 
                    JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                    LEFT JOIN WORKCOMPLETIONITEM WCI ON WAI.IDM=WCI.LINKID AND WAI.ORDINAL=WCI.LINKORD
                    LEFT JOIN WORKCOMPLETION WC ON WCI.IDM=WC.ID AND WC.ACTIVE in ('A','P','S')
                    LEFT JOIN EMPLOYEE E ON WA.EMPLOYEE=E.ID
                    LEFT JOIN PRODUCT PP ON WAI.FG=PP.ID
                    LEFT JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                    LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                    LEFT JOIN WORKORDER WO ON WAI.WORKORDER=WO.ID
                    LEFT JOIN PRODUCT F ON WO.PRODUCT = F.ID
                    LEFT JOIN SHORTTEXT ST ON F.PRODGROUP=ST.ID
                WHERE 
                    WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND WA.ACTIVE IN ('A','P','S')
                    AND WA.LOCATION = $location
                    AND WA.Freq=1
                    AND WO.SWPURPOSE <> 'PCB'
                GROUP BY WA.SW
                ";
        $query .= " UNION ";
        $query .= "SELECT 
                        E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, 
                        WO.SW WOSW, WC.TransDate TglNTHKO,
                        IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)) QtySPKO, (SELECT SUM(WEIGHT) FROM WORKALLOCATION WHERE SW=WA.SW) WeightSPKO, 
                        IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0))) GoodQtyNTHKO, 
                        IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0))) NoGoodQtyNTHKORep, 
                        IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0))) NoGoodQtyNTHKOSS, 
                        FORMAT(SUM(IF(WCI.WEIGHT IS NOT NULL, WCI.WEIGHT, 0)),2) GoodWeightNTHKO, 
                        FORMAT(SUM(IF(WCI.REPAIRWEIGHT IS NOT NULL, WCI.REPAIRWEIGHT, 0)),2) NoGoodWeightNTHKORep, 
                        FORMAT(SUM(IF(WCI.SCRAPWEIGHT IS NOT NULL, WCI.SCRAPWEIGHT, 0)),2) NoGoodWeightNTHKOSS, 
                        F.SW FDescription, ST.Description Kategori,
                        WA.SW WASW, E.Description EDescription, PC.Description PCDescription, ST.Description STDescription, F.SW FSW, OP.Description OPDescription
                    FROM WORKALLOCATION WA 
                        LEFT JOIN WORKCOMPLETION WC ON WA.SW=WC.WORKALLOCATION AND WC.ACTIVE in ('A','P','S')
                        LEFT JOIN WORKCOMPLETIONITEM WCI ON WC.ID=WCI.IDM
                        LEFT JOIN EMPLOYEE E ON WA.EMPLOYEE=E.ID
                        LEFT JOIN PRODUCT PP ON WCI.FG=PP.ID
                        LEFT JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
                        LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
                        LEFT JOIN WORKORDER WO ON WCI.WORKORDER=WO.ID
                        LEFT JOIN PRODUCT F ON WO.PRODUCT = F.ID
                        LEFT JOIN SHORTTEXT ST ON F.PRODGROUP=ST.ID
                    WHERE 
                        WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                        AND WA.ACTIVE IN ('A','P','S')
                        AND WA.LOCATION = $location
                        AND WA.Freq=1
                        AND WO.SWPURPOSE = 'PCB'
                    GROUP BY WA.SW
                    ";
        $query .= ") Results ";
        $query .= "ORDER BY WASW";
        dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] ); 
    }

    public function reportChart(){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 12;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $year = $date->format("y");

        $query = "SELECT 
                        MONTH(WA.TRANSDATE) BULAN, ST.DESCRIPTION KATEGORI, 
                        IF(P.Description LIKE '%Anting%' OR P.Description LIKE '%Giwang%', SUM(WAI.QTY*2), SUM(WAI.QTY)) PCS, 
                        SUM(WAI.WEIGHT) WEIGHT, ST.DESCRIPTION KATEGORI, DATE_FORMAT(WA.TRANSDATE, '%b') BULAN, DATE_FORMAT(WA.TRANSDATE, '%Y') TAHUN
                    FROM
                        WORKALLOCATION WA
                        JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                        LEFT JOIN PRODUCT P ON WAI.FG=P.ID
                        LEFT JOIN PRODUCT P2 ON P.MODEL=P2.ID
                        LEFT JOIN SHORTTEXT ST ON P2.PRODGROUP=ST.ID
                    WHERE 
                        WA.LOCATION=$location AND WA.ACTIVE IN ('A','P','S') AND WA.SWYEAR=$year
                    GROUP BY MONTH(WA.TRANSDATE), ST.DESCRIPTION ";
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( ["tampil"=>$data] ); 
    }
}

<?php

namespace App\Http\Controllers\Produksi\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as FacadesDB;
use \DateTime;
use \DateTimeZone;

class JadwalKerjaHarianController extends Controller
{
    public function index(){
        $location = session('location');
        $iddept = session('iddept');

        // LOKASI
        // Enamel = 47
        // Kikir = 4

        // DEPARTMENT
        // Enamel = 34
        // Kikir = 7

        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        // LIST AREA
        $queryArea = "SELECT * FROM LOCATION WHERE SCHEDULING='Y' AND TYPE='L' ";
        $dataArea = FacadesDB::connection('erp')->select($queryArea);

        // LIST RPH
        $query = "SELECT 
                        A.* 
                    FROM 
                        WORKSCHEDULE A 
                    WHERE 
                        A.LOCATION = $location
                        AND A.ACTIVE = 'P' 
                    ORDER BY 
                        A.TRANSDATE DESC, A.ID DESC 
                    LIMIT 100
                    ";
        $data = FacadesDB::connection('erp')->select($query);

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

        return view('Produksi.Informasi.JadwalKerjaHarian.index', compact('data','data2','data3','data4','data5','data6','dataArea'));
    }

    // function tampilArea(Request $request){

    //     $data =  $request->area;
    //     dd($data);

    //     // LIST RPH
    //     $query = "SELECT 
    //                     A.* 
    //                 FROM 
    //                     WORKSCHEDULE A 
    //                 WHERE 
    //                     A.LOCATION = $location
    //                     AND A.ACTIVE = 'P' 
    //                 ORDER BY 
    //                     A.TRANSDATE DESC, A.ID DESC 
    //                 LIMIT 100
    //                 ";
    //     $data = FacadesDB::connection('erp')->select($query);

    //     // LIST KADAR 
    //     $query2 = "SELECT
    //                     ID, SW, Description
    //                 FROM
    //                     ProductCarat A
    //                 WHERE
    //                     A.Regular = 'Y'
    //                 ORDER BY A.Description
    //                 ";
    //     $data2 = FacadesDB::connection('erp')->select($query2);

    //     // LIST OPERATION 
    //     $query3 = "SELECT 
    //                     A.*
    //                 FROM 
    //                     Operation A
    //                 WHERE 
    //                     A.Location = $location
    //                     AND A.Active = 'Y'
    //                 ORDER BY 
    //                     A.Description
    //                 ";
    //     $data3 = FacadesDB::connection('erp')->select($query3);

    //     // LIST OPERATOR 
    //     $query4 = "SELECT 
    //                     ID, SW, Description
    //                 FROM 
    //                     Employee A
    //                 WHERE 
    //                     A.Department = $iddept
    //                     AND A.Active = 'Y'
    //                     AND A.`Rank` = 'Operator' 
    //                 ORDER BY 
    //                     A.Description ASC
    //                 ";
    //     $data4 = FacadesDB::connection('erp')->select($query4);
    //     // dd($statuslocation);
    //     return view('Produksi.Informasi.JadwalKerjaHarian.data', compact('data','data2','data3','data4'));
    // }

    function reportPerRPH(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $rph =  $request->rph;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $jenis =  $request->jenis;


        $query = "SELECT 
                    WS.ID, PC.Description CaratName, TMI.IDM TM_ID, TMI.ORDINAL TM_ORDINAL, DATE_FORMAT(WS.TransDate,'%d-%m-%y') TransDate, WSI.Qty, WSI.Weight, OP.Description OperationName, WAI.IDM WAI_IDM, CONCAT(TMI.WorkAllocation,'-',TMI.LinkFreq,'-',TMI.LinkOrd) AS NTHKO_BEFORE,
                    WA.Employee, EM.Description EmpName, WSI.IDM WS_ID, WSI.ORDINAL WS_ORDINAL, WAI.IDM WA_ID, WAI.ORDINAL WA_ORDINAL, WCI.IDM WC_ID, WCI.ORDINAL WC_ORDINAL,
                    WSI.QTY WS_QTY, WSI.WEIGHT WS_WEIGHT, WAI.QTY WA_QTY, WAI.WEIGHT WA_WEIGHT, WCI.QTY WC_QTY, WCI.WEIGHT WC_WEIGHT, 
                    (WCI.REPAIRQTY+WCI.SCRAPQTY) WC_QTY_NG, (WCI.REPAIRWEIGHT+WCI.SCRAPWEIGHT) WC_WEIGHT_NG,
                    DATE_FORMAT(WA.TransDate,'%d-%m-%y') SPKO_TransDate, DATE_FORMAT(WC.TransDate,'%d-%m-%y') NTHKO_TransDate, WA.SW NoSPKO, P.SW PSW, WAI.IDM WAIIDM,
                    IF(P.ProdGroup=6 OR P.Description LIKE 'Giwang%' OR P.Description LIKE 'Anting%', WAI.QTY*2, WAI.QTY) PCS
                FROM WORKCOMPLETION WCBEFORE 
                    JOIN WORKCOMPLETIONITEM WCIBEFORE ON WCBEFORE.ID=WCIBEFORE.IDM
                    JOIN TRANSFERRMITEM TMI ON WCBEFORE.WORKALLOCATION=TMI.WORKALLOCATION AND WCBEFORE.FREQ=TMI.LINKFREQ AND WCIBEFORE.ORDINAL=TMI.LINKORD 
                    JOIN WORKSCHEDULEITEM WSI ON TMI.IDM=WSI.LINKID AND TMI.ORDINAL=WSI.LINKORD
                    JOIN WORKSCHEDULE WS ON WSI.IDM=WS.ID
                    LEFT JOIN WORKALLOCATIONITEM WAI ON WCIBEFORE.IDM=WAI.PREVPROCESS AND WCIBEFORE.ORDINAL=WAI.PREVORD
                    LEFT JOIN WORKALLOCATION WA ON WAI.IDM=WA.ID
                    -- LEFT JOIN WORKCOMPLETION WC ON WA.SW=WC.WORKALLOCATION
                    LEFT JOIN WORKCOMPLETIONITEM WCI ON WAI.IDM=WCI.LINKID AND WAI.ORDINAL=WCI.LINKORD
                    LEFT JOIN WORKCOMPLETION WC ON WCI.IDM=WC.ID
                    LEFT JOIN PRODUCTCARAT PC ON PC.ID=WSI.CARAT
                    LEFT JOIN OPERATION OP ON WSI.OPERATION = OP.ID
                    LEFT JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                    LEFT JOIN WORKORDER WO ON TMI.WORKORDER=WO.ID
                    LEFT JOIN PRODUCT P ON WO.PRODUCT=P.ID
                WHERE WS.ID=$rph ";

                if($kadar == NULL || $kadar == "" || $kadar == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WSI.CARAT = $kadar ";
                }
                if($operation == NULL || $operation == "" || $operation == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WSI.OPERATION = $operation ";
                }
                if($operator == NULL || $operator == "" || $operator == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WA.EMPLOYEE = $operator ";
                }
                $query .= "ORDER BY WS.ID, TMI.WorkAllocation, TMI.LinkFreq, TMI.LinkOrd"; 
    
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerRPH', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'jumlah' => $rowcount, 'jenis' => $jenis) );
    }

    function reportPerTgl(Request $request){
        $location = session('location');
        $iddept = session('iddept');
        
        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $rph =  $request->rph;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $jenis =  $request->jenis;

        $query = "SELECT 
                    WS.ID, PC.Description CaratName, TMI.IDM TM_ID, TMI.ORDINAL TM_ORDINAL, DATE_FORMAT(WS.TransDate,'%d-%m-%y') WS_TransDate, WSI.Qty, WSI.Weight, OP.Description OperationName, WAI.IDM WAI_IDM, CONCAT(TMI.WorkAllocation,'-',TMI.LinkFreq,'-',TMI.LinkOrd) AS NTHKO_BEFORE,
                    WA.Employee, EM.Description EmpName, WSI.IDM WS_ID, WSI.ORDINAL WS_ORDINAL, WAI.IDM WA_ID, WAI.ORDINAL WA_ORDINAL, WCI.IDM WC_ID, WCI.ORDINAL WC_ORDINAL,
                    WSI.QTY WS_QTY, WSI.WEIGHT WS_WEIGHT, WAI.QTY WA_QTY, WAI.WEIGHT WA_WEIGHT, WCI.QTY WC_QTY, WCI.WEIGHT WC_WEIGHT, 
                    (WCI.REPAIRQTY+WCI.SCRAPQTY) WC_QTY_NG, (WCI.REPAIRWEIGHT+WCI.SCRAPWEIGHT) WC_WEIGHT_NG,
                    WA.TransDate SPKO_TransDate, WC.TransDate NTHKO_TransDate, WA.SW NoSPKO, P.SW PSW, WAI.IDM WAIIDM,
                    IF(P.ProdGroup=6 OR P.Description LIKE 'Giwang%' OR P.Description LIKE 'Anting%', WAI.QTY*2, WAI.QTY) PCS, WA.SW WA_SW, WC.ID WC_ID,
                    DATE_FORMAT(WA.TransDate,'%d-%m-%y') WA_TransDate, DATE_FORMAT(WC.TransDate,'%d-%m-%y') WC_TransDate
                FROM WORKCOMPLETION WCBEFORE 
                    JOIN WORKCOMPLETIONITEM WCIBEFORE ON WCBEFORE.ID=WCIBEFORE.IDM
                    JOIN TRANSFERRMITEM TMI ON WCBEFORE.WORKALLOCATION=TMI.WORKALLOCATION AND WCBEFORE.FREQ=TMI.LINKFREQ AND WCIBEFORE.ORDINAL=TMI.LINKORD
                    JOIN WORKSCHEDULEITEM WSI ON TMI.IDM=WSI.LINKID AND TMI.ORDINAL=WSI.LINKORD
                    JOIN WORKSCHEDULE WS ON WSI.IDM=WS.ID
                    LEFT JOIN WORKALLOCATIONITEM WAI ON WCIBEFORE.IDM=WAI.PREVPROCESS AND WCIBEFORE.ORDINAL=WAI.PREVORD
                    LEFT JOIN WORKALLOCATION WA ON WAI.IDM=WA.ID
                    -- LEFT JOIN WORKCOMPLETION WC ON WA.SW=WC.WORKALLOCATION
                    LEFT JOIN WORKCOMPLETIONITEM WCI ON WAI.IDM=WCI.LINKID AND WAI.ORDINAL=WCI.LINKORD
                    LEFT JOIN WORKCOMPLETION WC ON WCI.IDM=WC.ID
                    LEFT JOIN PRODUCTCARAT PC ON PC.ID=WSI.CARAT
                    LEFT JOIN OPERATION OP ON WSI.OPERATION = OP.ID
                    LEFT JOIN EMPLOYEE EM ON WA.EMPLOYEE=EM.ID
                    LEFT JOIN WORKORDER WO ON TMI.WORKORDER=WO.ID
                    LEFT JOIN PRODUCT P ON WO.PRODUCT=P.ID
                WHERE 
                    WS.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
                    AND WS.ACTIVE IN ('P','A')
                    AND WS.LOCATION = $location ";
                // dd($query);
                if($kadar == NULL || $kadar == "" || $kadar == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WSI.CARAT = $kadar ";
                }
                if($operation == NULL || $operation == "" || $operation == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WSI.OPERATION = $operation ";
                }
                if($operator == NULL || $operator == "" || $operator == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WA.EMPLOYEE = $operator ";
                }
                $query .= "ORDER BY WS.ID, TMI.WorkAllocation, TMI.LinkFreq, TMI.LinkOrd"; 
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerTgl', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'jenis' => $jenis) );
    }

    function reportPerTglPercent(Request $request){
        $location = session('location');
        $iddept = session('iddept');
        
        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $rph =  $request->rph;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $jenis =  $request->jenis;

        $query = "SELECT 
                        WS.ID, WS.TransDate, 
                        COUNT(B.IDM) JmlRPH, 
                        COUNT(E.IDM) JmlSPKO, 
                        COUNT(G.ID) JmlNTHKO,
                        ( FORMAT( (COUNT(E.IDM)/COUNT(B.IDM)) * 100, 2 ) ) PercentSPKO, 
                        ( FORMAT( (COUNT(G.ID)/COUNT(E.IDM)) * 100, 2 ) ) PercentNTHKO
                        -- SUM(IF(F.TransDate=WS.TransDate AND F.Active IN ('P','S'), 1, 0)) JmlSPKO,
			            -- SUM(IF(G.TransDate=WS.TransDate AND G.Active IN ('P','S'), 1, 0)) JmlNTHKO,
                        -- ( FORMAT( ( SUM( IF(F.TransDate=WS.TransDate AND F.Active IN ('P','S'), 1, 0) ) / COUNT(WS.ID) ) * 100, 2 ) ) PercentSPKO,
			            -- ( FORMAT( ( SUM( IF(G.TransDate=WS.TransDate AND G.Active IN ('P','S'), 1, 0) ) / SUM( IF(F.TransDate=WS.TransDate AND F.Active IN ('P','S'), 1, 0) ) ) * 100, 2 ) ) PercentNTHKO
                    FROM 
                        transferrmitem A
                        JOIN workscheduleitem B ON A.IDM=B.LinkID AND A.Ordinal=B.LinkOrd
                        JOIN workschedule WS ON B.IDM=WS.ID
                        JOIN WORKCOMPLETION C ON A.WORKALLOCATION=C.WORKALLOCATION AND A.LINKFREQ=C.FREQ
                        JOIN WORKCOMPLETIONITEM D ON C.ID=D.IDM AND A.LINKORD=D.ORDINAL
                        LEFT JOIN workallocationitem E ON D.IDM=E.PrevProcess AND D.Ordinal=E.PrevOrd
                        LEFT JOIN workallocation F ON E.IDM=F.ID
                        LEFT JOIN workcompletion G ON F.SW=G.WorkAllocation AND F.Freq=G.Freq
                		-- LEFT JOIN workcompletionitem H ON E.IDM=H.LinkID AND E.Ordinal=H.LinkOrd
                    WHERE 
                        WS.Location = $location
                        AND WS.TransDate BETWEEN '$tglstart' AND '$tglend'
                        AND WS.Active IN ('P','A')
                ";
                if($kadar == NULL || $kadar == "" || $kadar == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND B.CARAT = $kadar ";
                }
                if($operation == NULL || $operation == "" || $operation == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND WSI.OPERATION = $operation ";
                }
                if($operator == NULL || $operator == "" || $operator == 'All'){
                    $query .= "";
                }else{
                    $query .= "AND F.EMPLOYEE = $operator ";
                }
                $query .= "GROUP BY WS.ID";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerTglPercent', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'jenis' => $jenis) );
    }

    function reportSPKO(Request $request){
        $location = session('location');
        $iddept = session('iddept');
        
        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $rph =  $request->rph;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $jenis =  $request->jenis;

        $query = "SELECT
                    F.Description Emp, A.SW, A.TransDate, E.SW FGName, C.Description Kadar, D.Description Operation, B.Qty, E.Description EDescription,
                    IF(E.ProdGroup=6 OR E.Description LIKE 'Giwang%' OR E.Description LIKE 'Anting%', B.Qty*2, B.Qty) Pcs, B.Weight, B.FG 
                FROM
                    WORKALLOCATION A
                    JOIN WORKALLOCATIONITEM B ON A.ID=B.IDM
                    JOIN PRODUCTCARAT C ON B.CARAT=C.ID
                    JOIN OPERATION D ON A.OPERATION=D.ID
                    LEFT JOIN PRODUCT E ON B.FG=E.ID
                    JOIN EMPLOYEE F ON A.EMPLOYEE=F.ID
                WHERE
                    A.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                    AND A.LOCATION = $location
                ";
                if($operator != ""){
                    $query .= "AND A.EMPLOYEE = $operator ";
                }
                if($kadar != ""){
                    $query .= "AND B.CARAT = $kadar ";
                }
                if($operation != ""){
                    $query .= "AND A.OPERATION = $operation ";
                }
                $query .= "ORDER BY F.DESCRIPTION";
        $data = FacadesDB::connection('erp')->select($query);

        $query2 = "SELECT
                    SUM(B.Qty) Qty, SUM(B.Weight) Weight, IF(E.ProdGroup=6 OR E.Description LIKE 'Giwang%' OR E.Description LIKE 'Anting%', B.Qty*2, B.Qty) Pcs
                FROM
                    WORKALLOCATION A
                    JOIN WORKALLOCATIONITEM B ON A.ID=B.IDM
                    JOIN PRODUCTCARAT C ON B.CARAT=C.ID
                    JOIN OPERATION D ON A.OPERATION=D.ID
                    LEFT JOIN PRODUCT E ON B.FG=E.ID
                    JOIN EMPLOYEE F ON A.EMPLOYEE=F.ID
                WHERE
                    A.TRANSDATE BETWEEN '$tglstart' AND '$tglend'
                    AND A.LOCATION = $location
                ";
                if($operator != ""){
                    $query .= "AND A.EMPLOYEE = $operator ";
                }
                if($kadar != ""){
                    $query .= "AND B.CARAT = $kadar ";
                }
                if($operation != ""){
                    $query .= "AND A.OPERATION = $operation ";
                }
                $query .= "GROUP BY A.Employee
                            ORDER BY F.DESCRIPTION";
        $data2 = FacadesDB::connection('erp')->select($query2);

        foreach($data2 as $datas2){
            $rows[] = (array) $datas2; 
        }   

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportSPKO', compact('data','data2'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'sum' => $rows, 'jenis' => $jenis) );
    }

    function reportSPKO2(Request $request){

        $location = session('location');
        
        if($location == NULL){
            $location = 10;
        }

        $rph =  $request->rph;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $jenis =  $request->jenis;

        // BERAPA JUMLAH DAN BERAT SPKO YG DIBUAT, PER TGL DAN PER OPERATOR
        $query = "SELECT 
                    WA.EMPLOYEE EID, E.DESCRIPTION ENAME, WA.TRANSDATE, SUM(WAI.QTY) QTY, FORMAT(SUM(WA.WEIGHT),2) WEIGHT
                FROM 
                    WORKALLOCATION WA
                    JOIN EMPLOYEE E ON WA.EMPLOYEE=E.ID
                    JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
                WHERE 
                    WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' AND WA.LOCATION=$location ";
                if($operator != ""){
                    $query .= "AND WA.EMPLOYEE = $operator ";
                }
                if($kadar != ""){
                    $query .= "AND WAI.CARAT = $kadar ";
                }
                if($operation != ""){
                    $query .= "AND WA.OPERATION = $operation ";
                }
        $query .= "GROUP BY WA.EMPLOYEE, WA.TRANSDATE
                    ORDER BY WA.EMPLOYEE";
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportSPKO2', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'jenis' => $jenis) );
    }

    function reportNTHKO2(Request $request){

        $location = session('location');
        
        if($location == NULL){
            $location = 10;
        }

        $rph =  $request->rph;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $jenis =  $request->jenis;
        
        // BERAPA JUMLAH NTHKO YG TELAH DISELESAIKAN, PER TGL DAN PER OPERATOR
        $query ="SELECT 
                    WC.EMPLOYEE EID, E.DESCRIPTION ENAME, WC.TRANSDATE, SUM(WCI.QTY+WCI.REPAIRQTY+SCRAPQTY) QTY, FORMAT(SUM(WC.WEIGHT+REPAIRQTY+SCRAPQTY),2) WEIGHT
                    -- (SELECT SUM(QTY) FROM WORKCOMPLETION WHERE EMPLOYEE=WC.EMPLOYEE AND TRANSDATE BETWEEN '23-01-07' AND '23-01-09' ) TOTALQTY,
                    -- (SELECT FORMAT(SUM(WEIGHT),2) FROM WORKCOMPLETION WHERE EMPLOYEE=WC.EMPLOYEE AND TRANSDATE BETWEEN '23-01-07' AND '23-01-09' ) TOTALWEIGHT
                FROM 
                    WORKCOMPLETION WC
                    JOIN EMPLOYEE E ON WC.EMPLOYEE=E.ID
                    JOIN WORKCOMPLETIONITEM WCI ON WC.ID=WCI.IDM
                WHERE 
                    WC.TRANSDATE BETWEEN '$tglstart' AND '$tglend' AND WC.LOCATION=$location ";
                if($operator != ""){
                    $query .= "AND WC.EMPLOYEE = $operator ";
                }
                if($kadar != ""){
                    $query .= "AND WCI.CARAT = $kadar ";
                }
                if($operation != ""){
                    $query .= "AND WC.OPERATION = $operation ";
                }
        $query .= "GROUP BY WC.EMPLOYEE, WC.TRANSDATE
                    ORDER BY WC.EMPLOYEE";
        $data = FacadesDB::connection('erp')->select($query);

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportNTHKO2', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'jenis' => $jenis) );
    }


    // REPORT All
    function reportAll(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $jenis =  $request->jenis;
        $tglstart =  $request->tglstart;
        $tglend =  $request->tglend;
        $rph =  $request->rph;
        $operator =  $request->operator;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $kategori =  $request->kategori;
        $subkategori =  $request->subkategori;
        
        // QUERY QTY
        // $query = "SELECT 
        //             E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, WC.TransDate TglNTHKO,
        //             WO.SW WOSW, WA.TargetQty QtySPKO, WA.Weight WeightSPKO, SUM(WCI.QTY) GoodQtyNTHKO, SUM(WCI.REPAIRQTY+WCI.SCRAPQTY) NoGoodQtyNTHKO, 
        //             FORMAT(SUM(WCI.WEIGHT),2) GoodWeightNTHKO, FORMAT(SUM(WCI.REPAIRWEIGHT+WCI.SCRAPWEIGHT),2) NoGoodWeightNTHKO, F.SW FDescription
        //         FROM WORKALLOCATION WA 
        //             JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
        //             LEFT JOIN WORKCOMPLETIONITEM WCI ON WAI.IDM=WCI.LINKID AND WAI.ORDINAL=WCI.LINKORD
        //             LEFT JOIN WORKCOMPLETION WC ON WCI.IDM=WC.ID
        //             LEFT JOIN EMPLOYEE E ON WA.EMPLOYEE=E.ID
        //             LEFT JOIN PRODUCT PP ON WAI.FG=PP.ID
        //             LEFT JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
        //             LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
        //             LEFT JOIN WORKORDER WO ON WAI.WORKORDER=WO.ID
        //             LEFT JOIN PRODUCT F ON WO.PRODUCT = F.ID
        //         WHERE 
        //             WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
        //             AND WA.ACTIVE IN ('A','P','S')
        //             AND WA.LOCATION = $location
        //         ";

        // QUERY PCS
        // $query = "SELECT 
        //             E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, WC.TransDate TglNTHKO, WO.SW WOSW, 
        //             IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', WA.TargetQty*2, WA.TargetQty) QtySPKO, WA.Weight WeightSPKO, 
        //             IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(WCI.QTY*2), SUM(WCI.QTY)) GoodQtyNTHKO, 
        //             IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(WCI.REPAIRQTY*2), SUM(WCI.REPAIRQTY)) NoGoodQtyNTHKORep, 
        //             IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(WCI.SCRAPQTY*2), SUM(WCI.SCRAPQTY)) NoGoodQtyNTHKOSS, 
        //             FORMAT(SUM(WCI.WEIGHT),2) GoodWeightNTHKO, 
        //             FORMAT(SUM(WCI.REPAIRWEIGHT),2) NoGoodWeightNTHKORep, 
        //             FORMAT(SUM(WCI.SCRAPWEIGHT),2) NoGoodWeightNTHKOSS, 
        //             F.SW FDescription, ST.Description Kategori
        //         FROM WORKALLOCATION WA 
        //             JOIN WORKALLOCATIONITEM WAI ON WA.ID=WAI.IDM
        //             LEFT JOIN WORKCOMPLETIONITEM WCI ON WAI.IDM=WCI.LINKID AND WAI.ORDINAL=WCI.LINKORD
        //             LEFT JOIN WORKCOMPLETION WC ON WCI.IDM=WC.ID
        //             LEFT JOIN EMPLOYEE E ON WA.EMPLOYEE=E.ID
        //             LEFT JOIN PRODUCT PP ON WAI.FG=PP.ID
        //             LEFT JOIN PRODUCTCARAT PC ON WA.CARAT=PC.ID
        //             LEFT JOIN OPERATION OP ON WA.OPERATION=OP.ID
        //             LEFT JOIN WORKORDER WO ON WAI.WORKORDER=WO.ID
        //             LEFT JOIN PRODUCT F ON WO.PRODUCT = F.ID
        //             LEFT JOIN SHORTTEXT ST ON F.PRODGROUP=ST.ID
        //         WHERE 
        //             WA.TRANSDATE BETWEEN '$tglstart' AND '$tglend' 
        //             AND WA.ACTIVE IN ('A','P','S')
        //             AND WA.LOCATION = $location
        //             ";

        $query = "SELECT * FROM ( ";
        // QUERY PCS FIX - // Query WO.SWPURPOSE<>PCB
        $query .= "SELECT 
                    E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, 
                    -- IF(WC.TransDate IS NOT NULL, WC.TransDate, (SELECT COALESCE(TransDate) FROM WORKCOMPLETION WHERE WORKALLOCATION=WA.SW)) TglNTHKO, 
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
                    ";
                
                if($kadar != ""){
                    $query .= "AND WAI.CARAT = $kadar ";
                }
                if($operation != ""){
                    $query .= "AND WA.OPERATION = $operation ";
                }
                if($operator != ""){
                    $query .= "AND WA.EMPLOYEE = $operator ";
                }
                if($kategori != ""){
                    $query .= "AND F.ProdGroup = $kategori ";
                }
                if($subkategori != ""){
                    $query .= "AND F.ID = $subkategori ";
                }

                $query .= "GROUP BY WA.SW";

                // if($jenis == 7){
                //     $query .= "GROUP BY WA.SW
                //                 ORDER BY E.Description, WA.SW";
                // }else if($jenis == 8){
                //     $query .= "GROUP BY WA.SW
                //                 ORDER BY PC.Description, WA.SW";
                // }else if($jenis == 9){
                //     $query .= "GROUP BY WA.SW
                //                 ORDER BY ST.Description, WA.SW";
                // }else if($jenis == 10){
                //     $query .= "GROUP BY WA.SW
                //                 ORDER BY F.SW, WA.SW";
                // }else if($jenis == 11){
                //     $query .= "GROUP BY WA.SW
                //                 ORDER BY OP.Description, WA.SW";
                // }
    
        $query .= " UNION ";

        // Query WO.SWPURPOSE=PCB
        $query .= "SELECT 
                        E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, 
                        -- IF(WC.TransDate IS NOT NULL, WC.TransDate, (SELECT COALESCE(TransDate) FROM WORKCOMPLETION WHERE WORKALLOCATION=WA.SW)) TglNTHKO, 
                        WO.SW WOSW, WC.TransDate TglNTHKO,
                        IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)) QtySPKO, 
                        (SELECT SUM(WEIGHT) FROM WORKALLOCATION WHERE SW=WA.SW) WeightSPKO, 
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
                        ";
                    
                    if($kadar != ""){
                        $query .= "AND WCI.CARAT = $kadar ";
                    }
                    if($operation != ""){
                        $query .= "AND WA.OPERATION = $operation ";
                    }
                    if($operator != ""){
                        $query .= "AND WA.EMPLOYEE = $operator ";
                    }
                    if($kategori != ""){
                        $query .= "AND F.ProdGroup = $kategori ";
                    }
                    if($subkategori != ""){
                        $query .= "AND F.ID = $subkategori ";
                    }

                    $query .= "GROUP BY WA.SW";

        $query .= ") Results ";

                    if($jenis == 7){
                        $query .= "ORDER BY EDescription, WASW";
                    }else if($jenis == 8){
                        $query .= "ORDER BY PCDescription, WASW";
                    }else if($jenis == 9){
                        $query .= "ORDER BY STDescription, WASW";
                    }else if($jenis == 10){
                        $query .= "ORDER BY FSW, WASW";
                    }else if($jenis == 11){
                        $query .= "ORDER BY OPDescription, WASW";
                    }else if($jenis == 12){
                        $query .= "ORDER BY EDescription, WASW";
                    }
        // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        // if($jenis == 7){
        //     $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerOperator', compact('data'))->render();
        // }else if($jenis == 8){
        //     $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerKadar', compact('data'))->render();
        // }else if($jenis == 9){
        //     $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerKategori', compact('data'))->render();
        // }else if($jenis == 10){
        //     $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerSubKategori', compact('data'))->render();
        // }else if($jenis == 11){
        //     $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerOperation', compact('data'))->render();
        // }

        $returnHTML = view('Produksi.Informasi.JadwalKerjaHarian.reportPerOperator', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'jenis' => $jenis) );
    }

    // REPORT PER KADAR




    // CETAK

    function cetakPerRPH(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $rph = $request->rph;
        $query = "SELECT 
                    WS.ID, WS.TransDate, WSI.Qty, WSI.Weight, F.Description, E.IDM EIDM, CONCAT(B.WorkAllocation,'-',B.LinkFreq,'-',B.LinkOrd) NTHKO
                FROM 
                    WORKSCHEDULE WS
                    JOIN WORKSCHEDULEITEM WSI ON WS.ID=WSI.IDM
                    JOIN TRANSFERRMITEM B ON WSI.LINKID=B.IDM AND WSI.LINKORD=B.ORDINAL
                    JOIN WORKCOMPLETION C ON C.WORKALLOCATION=B.WORKALLOCATION AND C.FREQ=B.LINKFREQ
                    JOIN WORKCOMPLETIONITEM D ON D.IDM=C.ID AND D.ORDINAL=B.LINKORD
                    LEFT JOIN WORKALLOCATIONITEM E ON D.IDM=E.PREVPROCESS AND D.ORDINAL=E.PREVORD
                    JOIN OPERATION F ON WSI.OPERATION = F.ID
                WHERE 
                    WS.ID = $rph
                    AND WS.ACTIVE IN ('P','A')
                ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return view('Produksi.Informasi.JadwalKerjaHarian.cetakPerRPH', compact('data'));
    }

    function cetakPerTgl(Request $request){
        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 10;
        }
        if($iddept == 12 || $iddept == 13){
            $iddept = 34;
        }

        $tglstart = $request->tglstart;
        $tglend = $request->tglend;
        $kadar =  $request->kadar;
        $operation =  $request->operation;
        $operator =  $request->operator;

        $query = "SELECT 
                    WS.ID, WS.TransDate, COUNT(WS.ID) SumRPH, COUNT(E.IDM) SumSPKO, COUNT(G.ID) SumNTHKO,
                    ( FORMAT( (COUNT(E.IDM)/COUNT(WS.ID)) * 100, 2 ) ) PercentSPKO, ( FORMAT( (COUNT(G.ID)/COUNT(E.IDM)) * 100, 2 ) ) PercentNTHKO
                FROM 
                    WORKSCHEDULE WS
                    JOIN WORKSCHEDULEITEM WSI ON WS.ID=WSI.IDM
                    JOIN TRANSFERRMITEM B ON WSI.LINKID=B.IDM AND WSI.LINKORD=B.ORDINAL
                    JOIN WORKCOMPLETION C ON C.WORKALLOCATION=B.WORKALLOCATION AND C.FREQ=B.LINKFREQ
                    JOIN WORKCOMPLETIONITEM D ON D.IDM=C.ID AND D.ORDINAL=B.LINKORD
                    LEFT JOIN WORKALLOCATIONITEM E ON D.IDM=E.PREVPROCESS AND D.ORDINAL=E.PREVORD
                    LEFT JOIN WORKALLOCATION F ON E.IDM=F.ID
                    LEFT JOIN WORKCOMPLETION G ON F.SW=G.WORKALLOCATION AND F.FREQ=G.FREQ
                    LEFT JOIN WORKCOMPLETIONITEM H ON G.ID=H.IDM AND E.ORDINAL=H.ORDINAL
                WHERE 
                    WS.Location = $location
                    AND WS.TransDate BETWEEN '$tglstart' AND '$tglend'
                    AND WS.ACTIVE IN ('P','A')
                GROUP BY WS.ID
                ";
                // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        return view('Produksi.Informasi.JadwalKerjaHarian.cetakPerTgl', compact('data','tglstart','tglend'));

    }


    function cetakAll(Request $request){

        $location = session('location');
        $iddept = session('iddept');

        if($location == NULL){
            $location = 10;
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

        // dd($request->tglstart);


        $query = "SELECT * FROM ( ";
        $query .= "SELECT 
                    E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, WO.SW WOSW,
                    -- IF(WC.TransDate IS NOT NULL, WC.TransDate, (SELECT COALESCE(TransDate) FROM WORKCOMPLETION WHERE WORKALLOCATION=WA.SW)) TglNTHKO,
                    WC.TransDate TglNTHKO,
                    CASE 
                        WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW))
                        ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW))
                    END QtySPKO,
                    CASE 
                        WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0)))
                        ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0)))
                    END GoodQtyNTHKO,
                    CASE 
                        WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0)))
                        ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0)))
                    END NoGoodQtyNTHKORep,
                    CASE 
                        WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0)))
                        ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0)))
                    END NoGoodQtyNTHKOSS,
                    (SELECT SUM(WEIGHT) FROM WORKALLOCATION WHERE SW=WA.SW) WeightSPKO,
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
                    ";
                
                if($kadar != ""){
                    $query .= "AND WAI.CARAT = $kadar ";
                }
                if($operation != ""){
                    $query .= "AND WA.OPERATION = $operation ";
                }
                if($operator != ""){
                    $query .= "AND WA.EMPLOYEE = $operator ";
                }
                if($kategori != ""){
                    $query .= "AND F.ProdGroup = $kategori ";
                }
                if($subkategori != ""){
                    $query .= "AND F.ID = $subkategori ";
                }

                $query .= "GROUP BY WA.SW";
    
        $query .= " UNION ";

        $query .= "SELECT 
                        E.Description Operator, WA.SW NoSPKO, WA.TransDate TglSPKO, PP.SW FinishGood, WA.Carat, PC.Description CaratName, OP.Description OperationName, WO.SW WOSW, 
                        -- IF(WC.TransDate IS NOT NULL, WC.TransDate, (SELECT COALESCE(TransDate) FROM WORKCOMPLETION WHERE WORKALLOCATION=WA.SW)) TglNTHKO,
                        WC.TransDate TglNTHKO,
                        -- IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)) QtySPKO,
                        -- IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0))) GoodQtyNTHKO, 
                        -- IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0))) NoGoodQtyNTHKORep, 
                        -- IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0))) NoGoodQtyNTHKOSS, 
                        CASE 
                            WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW))
                            ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW)*2, (SELECT SUM(TARGETQTY) FROM WORKALLOCATION WHERE SW=WA.SW))
                        END QtySPKO,
                        CASE 
                            WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0)))
                            ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY*2, 0)), SUM(IF(WCI.QTY IS NOT NULL, WCI.QTY, 0)))
                        END GoodQtyNTHKO,
                        CASE 
                            WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0)))
                            ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY*2, 0)), SUM(IF(WCI.REPAIRQTY IS NOT NULL, WCI.REPAIRQTY, 0)))
                        END NoGoodQtyNTHKORep,
                        CASE 
                            WHEN WA.Location=47 THEN IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%' OR PP.SW IN ('GOC1','GOC1M','GOC1T','GOC15','GOC15M','GOC15T','GOC2','GOC2M','GOC2T','GOCX1','GOCX15'), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0)))
                            ELSE IF(PP.Description LIKE '%Anting%' OR PP.Description LIKE '%Giwang%', SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY*2, 0)), SUM(IF(WCI.SCRAPQTY IS NOT NULL, WCI.SCRAPQTY, 0)))
                        END NoGoodQtyNTHKOSS,
                        (SELECT SUM(WEIGHT) FROM WORKALLOCATION WHERE SW=WA.SW) WeightSPKO,
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
                        ";
                    
                    if($kadar != ""){
                        $query .= "AND WAI.CARAT = $kadar ";
                    }
                    if($operation != ""){
                        $query .= "AND WA.OPERATION = $operation ";
                    }
                    if($operator != ""){
                        $query .= "AND WA.EMPLOYEE = $operator ";
                    }
                    if($kategori != ""){
                        $query .= "AND F.ProdGroup = $kategori ";
                    }
                    if($subkategori != ""){
                        $query .= "AND F.ID = $subkategori ";
                    }

                    $query .= "GROUP BY WA.SW";

        $query .= ") Results ";

                    if($jenis == 7){
                        $query .= "ORDER BY EDescription, WASW";
                    }else if($jenis == 8){
                        $query .= "ORDER BY PCDescription, WASW";
                    }else if($jenis == 9){
                        $query .= "ORDER BY STDescription, WASW";
                    }else if($jenis == 10){
                        $query .= "ORDER BY FSW, WASW";
                    }else if($jenis == 11){
                        $query .= "ORDER BY OPDescription, WASW";
                    }
                    // dd($query);
        $data = FacadesDB::connection('erp')->select($query);

        foreach($data as $datas){
            $rows[] = (array) $datas;
        }  

        // return view('Produksi.Informasi.JadwalKerjaHarian.cetakAll', compact('data','tglstart','tglend','rows','jenis'));

        if($jeniscetak == 1){
            return view('Produksi.Informasi.JadwalKerjaHarian.cetakAll', compact('data','tglstart','tglend','rows','jenis'));
        }else{
            return view('Produksi.Informasi.JadwalKerjaHarian.cetakAllSummary', compact('data','tglstart','tglend','rows','jenis'));
        }
        
    }
}

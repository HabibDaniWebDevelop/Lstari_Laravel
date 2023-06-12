<?php

namespace App\Http\Controllers\Produksi\JadwalKerjaHarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as FacadesDB;
use \DateTime;
use \DateTimeZone;

class RPHLilinController extends Controller
{
    public function index(){
        $location = session('location');

        if($location == NULL){
            $location = 51;
        }

        $query = "SELECT ID 
                    FROM workschedule 
                    WHERE LOCATION = $location AND Active <> 'C'
                    ORDER BY ID DESC, TransDate DESC
                    LIMIT 100
                    ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        $query2 = "SELECT ID, Description
                    FROM Operation 
                    WHERE Location = $location AND Active='Y'
                    ORDER BY Description ASC
                    ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        return view('Produksi.JadwalKerjaHarian.RPHLilin.index', compact('data','rowcount','data2'));
    }

    public function daftarList(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 51;
        }

        $idmnya1 = $request->idmnya1;
        $jenis = $request->jenis;

        if(!empty($idmnya1)){
            $idmnyaOK = 1;
        }else{
            $idmnyaOK = 0;
        }

        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $tahun = $date->format("y");
        $year = $date->format("Y");

        $queryPPIC = "SELECT DISTINCT GROUP_CONCAT(CONCAT(O.ID,'-',I.Ordinal,'-',IF(M.Color IS NULL, 0, X.ID),'-',I.Qty,'-',IF(O.TotalWeight IS NULL, 0, ROUND(O.TotalWeight, 2)),'-',C.ID,'-',J.IDM,'-',J.Ordinal,'-',IF(P.Description LIKE '%DC%', 210, 200)) SEPARATOR ',') IDM,
                    CONCAT(O.SW) SW,
                    O.TransDate,
                    X.Description Category,
                    X.ID Kategori,
                    M.SW Model,
                    C.SW Karat,
                    I.Ordinal,
                    GROUP_CONCAT(P.SW SEPARATOR ', ') Product,
                    SUM(I.Qty) as Qty,
                    Q.ID IDMs,
                    Q.TransDate,
                    IF(P.Description LIKE '%DC%', 210, 200) Status,
                    @rownum := @rownum + 1 as ID  
                FROM
                    WorkOrder O
                    JOIN Product M ON O.Product = M.ID
                    LEFT JOIN ShortText X ON M.Color = X.ID
                    JOIN ProductCarat C ON O.Carat = C.ID
                    JOIN WorkOrderItem I ON O.ID = I.IDM
                    JOIN Product P ON I.Product = P.ID
                    JOIN WaxOrderItem J ON I.IDM = J.WorkOrder AND I.Ordinal = J.WorkOrderOrd
                    JOIN WaxOrder Q ON J.IDM = Q.ID
                    LEFT JOIN WaxInjectOrderItem K ON J.IDM = K.WaxOrder AND J.Ordinal = K.WaxOrderOrd 
                    cross join (select @rownum := 0) r
                WHERE
                    O.SWYear IN ($tahun,$tahun+50) 
                    AND O.Active = 'A' 
                    AND (O.ID,I.Ordinal) NOT IN ( SELECT A.LinkID, A.LinkOrd FROM workscheduleitem A JOIN workschedule B ON A.IDM = B.ID AND B.Location = 51 WHERE B.Active <> 'C')  
                    AND K.IDM IS NULL AND O.TransDate > '$year-01-01' 
                    -- AND P.Description LIKE '%DC%'
                GROUP BY O.SWUsed
                ";

        $queryDC = "SELECT CONCAT(B.IDM,'-',B.Ordinal,'-',IF(F.Color IS NULL, 0, J.ID),'-',B.Qty,'-',IF(E.TotalWeight IS NULL, 0, ROUND(E.TotalWeight, 2)),'-',G.ID) IDM,
                    A.ID DCID, A.TransDate, A.Employee, C.Description EmpName, B.Qty, B.LinkID, B.LinkOrd, B.WorkOrder, B.WorkOrderOrd,
                    E.SW SW, F.SW Product, G.SW Karat, I.SW Model, J.SW Category, @rownum := @rownum + 1 as ID 
                FROM transferresindc A
                    JOIN transferresindcitem B ON A.ID=B.IDM
                    JOIN employee C ON A.Employee=C.ID
                    JOIN workorderitem D ON B.WorkOrder=D.IDM AND B.WorkOrderOrd=D.Ordinal
                    JOIN workorder E ON D.IDM=E.ID
                    JOIN product F ON B.Product=F.ID
                    JOIN productcarat G ON E.Carat=G.ID
                    JOIN RNDNEW.resindirectcastingcompletionitem H ON B.LinkID=H.IDM AND B.LinkOrd=H.Ordinal
                    JOIN RNDNEW.componentheader I ON F.Model=I.ID AND F.TypeProcess=25
                    -- JOIN shorttext J ON F.ProdGroup=J.ID
                    LEFT JOIN ShortText J ON F.Color = J.ID
                    cross join (select @rownum := 0) r
                WHERE 
                    E.SWYear IN ($tahun,$tahun+50)
                    AND (B.IDM, B.Ordinal) NOT IN (SELECT A.LinkID, A.LinkOrd FROM workscheduleitem A JOIN workschedule B ON A.IDM = B.ID AND B.Location = 51 WHERE B.Active <> 'C') 
                    AND E.TransDate > '$year-01-01'
                ";

        if($jenis == 'PPIC'){
            $query = $queryPPIC;
        }else if($jenis == 'DC'){
            $query = $queryDC;
        }
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        foreach($data as $datas){
            $rows[] = $datas; 
        }
   
        $data = (!empty($rows) ? $rows : '');
        return response()->json( array('success' => true, 'datalist' => $data, 'update' => $idmnyaOK) );

    }

    // public function daftarListDC(Request $request){
    //     $location = session('location');

    //     if($location == NULL){
    //         $location = 51;
    //     }

    //     $idmnya1 = $request->idmnya1;

    //     if(!empty($idmnya1)){
    //         $idmnyaOK = 1;
    //     }else{
    //         $idmnyaOK = 0;
    //     }

    //     $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
    //     $tahun = $date->format("y");
    //     $year = $date->format("Y");

    //     $query = "SELECT CONCAT(B.IDM,'-',B.Ordinal,'-',IF(F.Color IS NULL, 0, J.ID),'-',B.Qty,'-',IF(E.TotalWeight IS NULL, 0, ROUND(E.TotalWeight, 2)),'-',G.ID) IDM,
    //                 A.ID DCID, A.TransDate, A.Employee, C.Description EmpName, B.Qty, B.LinkID, B.LinkOrd, B.WorkOrder, B.WorkOrderOrd,
    //                 E.SW SW, F.SW Product, G.SW Karat, I.SW Model, J.SW Category, @rownum := @rownum + 1 as ID 
    //             FROM transferresindc A
    //                 JOIN transferresindcitem B ON A.ID=B.IDM
    //                 JOIN employee C ON A.Employee=C.ID
    //                 JOIN workorderitem D ON B.WorkOrder=D.IDM AND B.WorkOrderOrd=D.Ordinal
    //                 JOIN workorder E ON D.IDM=E.ID
    //                 JOIN product F ON B.Product=F.ID
    //                 JOIN productcarat G ON E.Carat=G.ID
    //                 JOIN RNDNEW.resindirectcastingcompletionitem H ON B.LinkID=H.IDM AND B.LinkOrd=H.Ordinal
    //                 JOIN RNDNEW.componentheader I ON F.Model=I.ID AND F.TypeProcess=25
    //                 -- JOIN shorttext J ON F.ProdGroup=J.ID
    //                 LEFT JOIN ShortText J ON F.Color = J.ID
    //                 cross join (select @rownum := 0) r
    //             WHERE 
    //                 A.Active='A'
    //                 AND E.SWYear IN ($tahun,$tahun+50) 
    //                 AND (B.IDM, B.Ordinal) NOT IN (SELECT A.LinkID, A.LinkOrd FROM workscheduleitem A JOIN workschedule B ON A.IDM = B.ID AND B.Location = 51 WHERE B.Active <> 'C') 
    //                 AND E.TransDate > '$year-01-01'
    //             ";
    //             // dd($query);
    //     $data = FacadesDB::connection('erp')->select($query);
    //     $rowcount = count($data);

    //     foreach($data as $datas){
    //         $rows[] = $datas; 
    //     }
   
    //     $data = (!empty($rows) ? $rows : '');
    //     return response()->json( array('success' => true, 'datalist' => $data, 'update' => $idmnyaOK) );

    // }

    public function lihat(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 51;
        }

        $idubah = $request->dropdownValue;
        $jenis = $request->jenis;

        $IDitem1 = "SELECT ID, Remarks, Active, TransDate FROM workschedule WHERE ID = $idubah ";
        $dataItem = FacadesDB::connection('erp')->select($IDitem1);

        foreach ($dataItem as $dataItems){}

        $queryPPIC = "SELECT DISTINCT GROUP_CONCAT(CONCAT(O.ID,'-',I.Ordinal,'-',IF(M.Color IS NULL, 0, X.ID),'-',I.Qty,'-',IF(O.TotalWeight IS NULL, 0, ROUND(O.TotalWeight, 2)),'-',C.ID,'-',J.IDM,'-',J.Ordinal,'-',IF(P.Description LIKE '%DC%', 210, 200)) SEPARATOR ',') AS IDM,
                    CONCAT(O.SW) SW,
                    O.TransDate,
                    Q.ID as IDMs,
                    X.Description Category,
                    X.ID Kategori,
                    M.SW Model,
                    C.SW Karat,
                    I.Ordinal,
                    GROUP_CONCAT(P.SW SEPARATOR ', ') Product,
                    SUM(I.Qty) as Qty,
                    @rownum := @rownum + 1 as ID  
                FROM
                    workscheduleitem WP 
                    JOIN WorkOrderItem I ON WP.LinkID = I.IDM AND WP.LinkOrd = I.Ordinal    
                    JOIN WorkOrder O ON O.ID = I.IDM   
                    JOIN WaxOrderItem J ON I.IDM = J.WorkOrder AND I.Ordinal = J.WorkOrderOrd
                    JOIN WaxOrder Q ON J.IDM = Q.ID                                                             
                    JOIN Product M ON O.Product = M.ID
                    JOIN ShortText X ON M.Color = X.ID
                    JOIN ProductCarat C ON O.Carat = C.ID
                    JOIN Product P ON I.Product = P.ID
                    JOIN workschedule WS ON WS.ID = WP.IDM 
                    LEFT JOIN RNDNEW.ComponentHeader CH ON P.Model=CH.ID
                    cross join (select @rownum := 0) r
                WHERE WS.ID = $idubah
                GROUP BY O.SWUsed";

        $queryDC = "SELECT CONCAT(B.IDM,'-',B.Ordinal,'-',IF(F.Color IS NULL, 0, J.ID),'-',B.Qty,'-',IF(E.TotalWeight IS NULL, 0, ROUND(E.TotalWeight, 2)),'-',G.ID) IDM,
                    A.ID DCID, A.TransDate, A.Employee, C.Description EmpName, B.Qty, B.LinkID, B.LinkOrd, B.WorkOrder, B.WorkOrderOrd,
                    E.SW SW, F.SW Product, G.SW Karat, I.SW Model, J.SW Category, @rownum := @rownum + 1 as ID 
                FROM workschedule WS
                    JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                    JOIN transferresindcitem B ON WSI.LinkID=B.IDM AND WSI.LinkOrd=B.Ordinal
                    JOIN transferresindc A ON A.ID=B.IDM
                    JOIN employee C ON A.Employee=C.ID
                    JOIN workorderitem D ON B.WorkOrder=D.IDM AND B.WorkOrderOrd=D.Ordinal
                    JOIN workorder E ON D.IDM=E.ID
                    JOIN product F ON B.Product=F.ID
                    JOIN productcarat G ON E.Carat=G.ID
                    JOIN RNDNEW.resindirectcastingcompletionitem H ON B.LinkID=H.IDM AND B.LinkOrd=H.Ordinal
                    JOIN RNDNEW.componentheader I ON F.Model=I.ID AND F.TypeProcess=25
                    -- JOIN shorttext J ON F.ProdGroup=J.ID
                    LEFT JOIN ShortText J ON F.Color = J.ID
                    cross join (select @rownum := 0) r
                WHERE 
                    WS.ID = $idubah
                ";


        if($jenis == 'PPIC'){
            $query = $queryPPIC;
        }else if($jenis == 'DC'){
            $query = $queryDC;
        }
        $data = FacadesDB::connection('erp')->select($query);

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
            'tglRPH' => $dataItems->TransDate
        );
        return response()->json($datajson);

    }

    // public function lihatDC(Request $request){
    //     $location = session('location');

    //     if($location == NULL){
    //         $location = 51;
    //     }

    //     $idubah = $request->dropdownValue;

    //     $IDitem1 = "SELECT ID, Remarks, Active, TransDate FROM workschedule WHERE ID = $idubah ";
    //     $dataItem = FacadesDB::connection('erp')->select($IDitem1);

    //     foreach ($dataItem as $dataItems){}

    //     $query = "SELECT CONCAT(B.IDM,'-',B.Ordinal,'-',IF(F.Color IS NULL, 0, J.ID),'-',B.Qty,'-',IF(E.TotalWeight IS NULL, 0, ROUND(E.TotalWeight, 2)),'-',G.ID) IDM,
    //                     A.ID DCID, A.TransDate, A.Employee, C.Description EmpName, B.Qty, B.LinkID, B.LinkOrd, B.WorkOrder, B.WorkOrderOrd,
    //                     E.SW SW, F.SW Product, G.SW Karat, I.SW Model, J.SW Category, @rownum := @rownum + 1 as ID 
    //                 FROM workschedule WS
    //                     JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
    //                     JOIN transferresindcitem B ON WSI.LinkID=B.IDM AND WSI.LinkOrd=B.Ordinal
    //                     JOIN transferresindc A ON A.ID=B.IDM
    //                     JOIN employee C ON A.Employee=C.ID
    //                     JOIN workorderitem D ON B.WorkOrder=D.IDM AND B.WorkOrderOrd=D.Ordinal
    //                     JOIN workorder E ON D.IDM=E.ID
    //                     JOIN product F ON B.Product=F.ID
    //                     JOIN productcarat G ON E.Carat=G.ID
    //                     JOIN RNDNEW.resindirectcastingcompletionitem H ON B.LinkID=H.IDM AND B.LinkOrd=H.Ordinal
    //                     JOIN RNDNEW.componentheader I ON F.Model=I.ID AND F.TypeProcess=25
    //                     -- JOIN shorttext J ON F.ProdGroup=J.ID
    //                     LEFT JOIN ShortText J ON F.Color = J.ID
    //                     cross join (select @rownum := 0) r
    //                 WHERE 
    //                     WS.ID = $idubah
    //                 ";
    //     $data = FacadesDB::connection('erp')->select($query);

    //     foreach($data as $datas){
    //         $rows[] = $datas; 
    //     }

    //     $data = (!empty($rows) ? $rows : '');
    //     $datajson = array(
    //         'success' => true, 
    //         'datalist' => $rows, 
    //         'update' => 'update',
    //         'active' => $dataItems->Active,
    //         'catatan' => $dataItems->Remarks,
    //         'ID' => $dataItems->ID,
    //         'tgl' => $dataItems->TransDate
    //     );
    //     return response()->json($datajson);

    // }

    public function cekSPK(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 51;
        }

        $idm = $request->pilih;
        $data = explode(",",$idm);
        $jmlItem = count($data);

        $arrChar = array();
        foreach ($data as $key) {
            $tahu = explode("-", $key);
            $idmpas = $tahu[0];

            $queryCek = "SELECT A.SW, IF(LEFT(A.SWPurpose,1)='O',0,1) CharSPK FROM WORKORDER A WHERE A.ID=$idmpas";
            $dataCek = FacadesDB::connection('erp')->select($queryCek);
            foreach($dataCek as $datasCek){}

            array_push($arrChar,$datasCek->CharSPK);
        }

        // dd($arrChar);

        $jmlItem2 = array_sum($arrChar);

        if($jmlItem2 == 0 || $jmlItem2 == $jmlItem){
            $datajson = array('status' => 'Sukses');
        }else{
            $datajson = array('status' => 'Gagal');
        }

        return response()->json($datajson);
    }

    public function simpan(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 51;
        }

        $idm = $request->pilih;
        $catatan = $request->catatan;
        $tgl = $request->tglRPH;
        $totalqty = 0;
        $totalberat = 0;
        $totalqtyplan = 0;
        $totalberatplan = 0;  

        // Get last id
        $query = "SELECT LAST+1 AS ID FROM lastid WHERE Module = 'WorkSchedule' ";
        $data = FacadesDB::connection('erp')->select($query);

        foreach($data as $datas){}
        $lastID = $datas->ID;
    
        $update = "UPDATE lastid SET LAST = $lastID WHERE Module = 'WorkSchedule'";
        $execUpdate = FacadesDB::connection('erp')->update($update);


        $insertRPH = "INSERT INTO workschedule (ID, UserName, Remarks, TransDate, Active, Location, Qty, Weight, QtyPlan, WeightPlan)
                    VALUES ($lastID, '$UserEntry', ".((!empty($catatan)) ? "'$catatan'" : 'NULL').", '$tgl', 'A', $location, 0, 0, 0, 0)";
                    // dd($insertRPH);
        $goInsertRPH = FacadesDB::connection('erp')->insert($insertRPH); 

        $dataIDM = explode(",",$idm);
        $nos = 1;
        foreach ($dataIDM as $key) 
        {
            $tahu = explode("-", $key);
            $idmpas = $tahu[0];
            $ordpas = $tahu[1];
            $color = $tahu[2];
            $qty = $tahu[3];
            $carat = $tahu[5];     
            $idmwax = $tahu[6];  
            $ordwax = $tahu[7];    
            $statusdc = $tahu[8];        
            $totalqty += $qty;       

            $insertRPHItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3) 
                            VALUES ($lastID, $nos, $idmpas, $ordpas, $color, $carat, $qty, 0, $statusdc, $idmwax, $ordwax)";
                            // dd($insertRPHItem);
            $goInsertRPHItem = FacadesDB::connection('erp')->insert($insertRPHItem);         
            $nos++;                  
        }  

        $updateQty = "UPDATE workschedule SET Qty = $totalqty WHERE ID = $lastID ";
        $execUpdateQty = FacadesDB::connection('erp')->update($updateQty);


        if($goInsertRPH == TRUE && $goInsertRPHItem == TRUE && $execUpdateQty == TRUE){
            $status = "OK";
        }else{
            $status = "Gagal";
        }

        return response()->json( array('success' => true, 'status' => $status, 'update' => 'update', 'idmnya' => $lastID) );
    }

    public function posting(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 51;
        }

        $idubah = $request->idmnya1;
        $jenis = $request->jenis;

        $queryUpdate = "UPDATE workschedule SET Active = 'P', PostDate = '".date('Y-m-d H:i:s')."' WHERE ID = $idubah ";
        $execUpdate = FacadesDB::connection('erp')->update($queryUpdate);  

        $queryPPIC = "SELECT DISTINCT GROUP_CONCAT(CONCAT(O.ID,'-',I.Ordinal,'-',IF(M.Color IS NULL, 0, X.ID),'-',I.Qty,'-',IF(O.TotalWeight IS NULL, 0, ROUND(O.TotalWeight, 2)),'-',C.ID,'-',J.IDM,'-',J.Ordinal,'-',IF(P.Description LIKE '%DC%', 210, 200)) SEPARATOR ',') AS IDM,
                CONCAT(O.SW) SW,
                O.TransDate,
                Q.ID as IDMs,
                X.Description Category,
                X.ID Kategori,
                M.SW Model,
                C.SW Karat,
                I.Ordinal,
                GROUP_CONCAT(P.SW SEPARATOR ', ') Product,
                SUM(I.Qty) as Qty,
                @rownum := @rownum + 1 as ID  
            FROM
                workscheduleitem WP 
                JOIN WorkOrderItem I ON WP.LinkID = I.IDM AND WP.LinkOrd = I.Ordinal    
                JOIN WorkOrder O ON O.ID = I.IDM   
                JOIN WaxOrderItem J ON I.IDM = J.WorkOrder AND I.Ordinal = J.WorkOrderOrd
                JOIN WaxOrder Q ON J.IDM = Q.ID                                                             
                JOIN Product M ON O.Product = M.ID
                JOIN ShortText X ON M.Color = X.ID
                JOIN ProductCarat C ON O.Carat = C.ID
                JOIN Product P ON I.Product = P.ID
                JOIN workschedule WS ON WS.ID = WP.IDM 
                LEFT JOIN RNDNEW.ComponentHeader CH ON P.Model=CH.ID
                cross join (select @rownum := 0) r
            WHERE WS.ID = $idubah
            GROUP BY O.SWUsed";

        $queryDC = "SELECT CONCAT(B.IDM,'-',B.Ordinal,'-',IF(F.Color IS NULL, 0, J.ID),'-',B.Qty,'-',IF(E.TotalWeight IS NULL, 0, ROUND(E.TotalWeight, 2)),'-',G.ID) IDM,
                A.ID DCID, A.TransDate, A.Employee, C.Description EmpName, B.Qty, B.LinkID, B.LinkOrd, B.WorkOrder, B.WorkOrderOrd,
                E.SW SW, F.SW Product, G.SW Karat, I.SW Model, J.SW Category, @rownum := @rownum + 1 as ID 
            FROM workschedule WS
                JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                JOIN transferresindcitem B ON WSI.LinkID=B.IDM AND WSI.LinkOrd=B.Ordinal
                JOIN transferresindc A ON A.ID=B.IDM
                JOIN employee C ON A.Employee=C.ID
                JOIN workorderitem D ON B.WorkOrder=D.IDM AND B.WorkOrderOrd=D.Ordinal
                JOIN workorder E ON D.IDM=E.ID
                JOIN product F ON B.Product=F.ID
                JOIN productcarat G ON E.Carat=G.ID
                JOIN RNDNEW.resindirectcastingcompletionitem H ON B.LinkID=H.IDM AND B.LinkOrd=H.Ordinal
                JOIN RNDNEW.componentheader I ON F.Model=I.ID AND F.TypeProcess=25
                -- JOIN shorttext J ON F.ProdGroup=J.ID
                LEFT JOIN ShortText J ON F.Color = J.ID
                cross join (select @rownum := 0) r
            WHERE 
                WS.ID = $idubah
            ";

        if($jenis == 'PPIC'){
            $query = $queryPPIC;
        }else if($jenis == 'DC'){
            $query = $queryDC;
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
            $location = 51;
        }

        $idubah = $request->idmnya1;
        $idm = $request->pilih;
        $catatan = $request->catatan;
        $tgl = $request->tglRPH;
        $totalqty = 0;
        $totalberat = 0;
        $totalqtyplan = 0;
        $totalberatplan = 0;

        $sqlhapus = "DELETE FROM workscheduleitem WHERE IDM = $idubah ";
        $proseshapus = FacadesDB::connection('erp')->delete($sqlhapus);  
     
        $dataIDM = explode(",",$idm);
        // dd($dataIDM);
        $nos = 1;
        foreach ($dataIDM as $key) 
        {
            $tahu = explode("-", $key);
            $idmpas = $tahu[0];
            $ordpas = $tahu[1];
            $color = $tahu[2];
            $qty = $tahu[3];
            $carat = $tahu[5];     
            $idmwax = $tahu[6];  
            $ordwax = $tahu[7];    
            $statusdc = $tahu[8];     
            $totalqty += $qty;       

            $insertRPHItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3) 
                            VALUES ($idubah, $nos, $idmpas, $ordpas, $color, $carat, $qty, 0, $statusdc, $idmwax, $ordwax)";
            $goInsertRPHItem = FacadesDB::connection('erp')->insert($insertRPHItem);         
            $nos++;                  
        }  

        $updateQty = "UPDATE workschedule SET Qty = $totalqty, TransDate = '$tgl', Remarks = ".((!empty($catatan)) ? "'$catatan'" : 'NULL')." WHERE ID = $idubah ";
        $execUpdateQty = FacadesDB::connection('erp')->update($updateQty);

        return response()->json( array('success' => true, 'update' => 'update', 'idmnya' => $idubah) );
    }

    public function update1(Request $request){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 51;
        }

        $idubah = $request->idmnya1;
        $idm = $request->pilih;
        $catatan = $request->catatan;
        $tgl = $request->tglRPH;
        $totalqty = 0;
        $totalberat = 0;
        $totalqtyplan = 0;
        $totalberatplan = 0;
        $spqty = 0;
        $sqpweight = 0;


        $dataIDM = explode(",",$idm);
        foreach ($dataIDM as $key) 
        {
            $tahu = explode("-", $key);
            $idmpas = $tahu[0];
            $ordpas = $tahu[1];
            $color = $tahu[2];
            $qty = $tahu[3];
            $carat = $tahu[5];  
            $idmwax = $tahu[6];  
            $ordwax = $tahu[7];
            $statusdc = $tahu[8];
            $totalqty += $qty;                

            $data_array[] = array(
                        'ID' => $idmpas.'-'.$ordpas,
                        'LinkID' => $idmpas,
                        'LinkOrd' => $ordpas,
                        'Category' => $color,
                        'Carat' => $carat,
                        'Qty' => $qty,
                        'Weight' => '0',
                        'Operation' => $statusdc,  
                        'Level2' => $idmwax,
                        'Level3' => $ordwax,                                         
            );                      
        }
    
        $IDitem1 = "SELECT CONCAT(LinkID,'-',LinkOrd) AS ID, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3 FROM workscheduleitem WHERE IDM = $idubah ";
        $data = FacadesDB::connection('erp')->select($IDitem1);

        foreach($data as $datas){
            $rows[] = (array) $datas; 
        }    
    
        if(empty($rows)){
            $rphlist = $data_array;
        }else{
            $rphlist = array_merge($data_array,$rows);        
        }

        function unique_multidim_array($array, $key) {
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
        $details = unique_multidim_array($rphlist,'ID');	


        $sqlhapus = "DELETE FROM workscheduleitem WHERE IDM = $idubah ";
        $proseshapus = FacadesDB::connection('erp')->delete($sqlhapus);

        $no = 1;
        foreach ($details as $key) 
        {
            $idmpas = $key['LinkID'];
            $ordpas = $key['LinkOrd'];
            $qty = $key['Qty'];
            $color = $key['Category'];
            $carat = $key['Carat'];
            $berat = $key['Weight']; 
            $statusdc = $key['Operation'];
            $idmwax = $key['Level2'];
            $ordwax = $key['Level3']; 
            $totalqty += $qty;
            $totalberat += $berat;  

            $insertRPHItem = "INSERT INTO workscheduleitem (IDM, Ordinal, LinkID, LinkOrd, Category, Carat, Qty, Weight, Operation, Level2, Level3) 
                            VALUES ($idubah, $no, $idmpas, $ordpas, $color, $carat, $qty, $berat, $statusdc, $idmwax, $ordwax)";
            $goInsertRPHItem = FacadesDB::connection('erp')->insert($insertRPHItem);         
            $no++;                  
        }

        $updateItem = "UPDATE workschedule SET 
                        Qty = $totalqty, 
                        Weight = $totalberat, 
                        TransDate = '$tgl',
                        Remarks = ".((!empty($catatan)) ? "'$catatan'" : 'NULL')." 
                        WHERE ID = $idubah ";
        $execUpdateItem = FacadesDB::connection('erp')->update($updateItem);      

        return response()->json( array('success' => true, 'update' => 'update1', 'idmnya' => $idubah) );
    }

    public function cetak(Request $request){
        $location = session('location');

        if($location == NULL){
            $location = 51;
        }

        $idrph = $request->idrph;
        $jenis = $request->jenis;

        function group_by($key, $data){
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

        $query1 = "SELECT Description FROM department WHERE Location = $location ";
        $data1 = FacadesDB::connection('erp')->select($query1);

        $query2 = "SELECT A.ID, DATE_FORMAT( A.TransDate, '%d %M %Y' ) TransDate1 FROM workschedule A WHERE A.ID = $idrph ";
        $data2 = FacadesDB::connection('erp')->select($query2);

        $queryPPIC = "SELECT
                        O.SWUsed,
                        CONCAT(O.SW) SW,
                        DATE_FORMAT(O.TransDate, '%d-%m-%Y') as TransDate,
                        Q.ID as IDMs,
                        X.Description Category,
                        M.SW Model,
                        C.SW Carat,
                        GROUP_CONCAT(P.Description SEPARATOR '<br> ') as Product,
                        GROUP_CONCAT(I.Qty SEPARATOR '<br> ') as Qty,
                        COUNT(O.SWUsed) as total,
                        SUM(I.Qty) as grandtot
                    FROM
                        WorkOrder O
                        JOIN Product M ON O.Product = M.ID
                        JOIN ShortText X ON M.Color = X.ID
                        JOIN ProductCarat C ON O.Carat = C.ID
                        JOIN WorkOrderItem I ON O.ID = I.IDM
                        JOIN WaxOrderItem J ON I.IDM = J.WorkOrder AND I.Ordinal = J.WorkOrderOrd
                        JOIN WaxOrder Q ON J.IDM = Q.ID                     
                        JOIN Product P ON I.Product = P.ID
                        JOIN workscheduleitem B ON I.IDM = B.LinkID AND B.LinkOrd = I.Ordinal
                        JOIN workschedule A ON A.ID = B.IDM
                        LEFT JOIN RNDNEW.ComponentHeader CH ON P.Model=CH.ID
                    WHERE A.ID = $idrph
                    GROUP BY O.SWUsed
                    ";
        
        $queryDC = "SELECT 
                        E.SWUsed,
                        CONCAT(E.SW) SW,
                        DATE_FORMAT(E.TransDate, '%d-%m-%Y') as TransDate,
                        WAX.ID as IDMs,
                        J.Description Category,
                        K.SW Model,
                        G.SW Carat,
                        GROUP_CONCAT(F.Description SEPARATOR '<br> ') as Product,
                        GROUP_CONCAT(B.Qty SEPARATOR '<br> ') as Qty,
                        COUNT(E.SWUsed) as total,
                        SUM(B.Qty) as grandtot
                    FROM workschedule WS
                        JOIN workscheduleitem WSI ON WS.ID=WSI.IDM
                        JOIN transferresindcitem B ON WSI.LinkID=B.IDM AND WSI.LinkOrd=B.Ordinal
                        JOIN transferresindc A ON A.ID=B.IDM
                        JOIN employee C ON A.Employee=C.ID
                        JOIN workorderitem D ON B.WorkOrder=D.IDM AND B.WorkOrderOrd=D.Ordinal
                        JOIN workorder E ON D.IDM=E.ID
                        JOIN product F ON B.Product=F.ID
                        JOIN productcarat G ON E.Carat=G.ID
                        JOIN RNDNEW.resindirectcastingcompletionitem H ON B.LinkID=H.IDM AND B.LinkOrd=H.Ordinal
                        JOIN RNDNEW.componentheader I ON F.Model=I.ID AND F.TypeProcess=25
                        LEFT JOIN ShortText J ON F.Color = J.ID
                        JOIN product K ON F.Model=K.ID 
                        JOIN WaxOrderItem WAXI ON D.IDM = WAXI.WorkOrder AND D.Ordinal = WAXI.WorkOrderOrd
                        JOIN WaxOrder WAX ON WAXI.IDM = WAX.ID     
                    WHERE 
                        WS.ID = $idrph
                    GROUP BY E.SWUsed
                    ";

        if($jenis == 'PPIC'){
            $query3 = $queryPPIC;
        }else if($jenis == 'DC'){
            $query3 = $queryDC;
        }
        $data3 = FacadesDB::connection('erp')->select($query3);

        foreach($data3 as $datas3){
            $rows[] = (array) $datas3;
        }  

        $byGroup = group_by("Carat", $rows);

        return view('Produksi.JadwalKerjaHarian.RPHLilin.cetak', compact('data1','data2','data3','byGroup'));
    }
}

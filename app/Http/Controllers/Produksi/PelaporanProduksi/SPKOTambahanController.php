<?php

namespace App\Http\Controllers\Produksi\PelaporanProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Http\Controllers\Public_Function_Controller;

use \DateTime;
use \DateTimeZone;
use Barryvdh\DomPDF\Facade\Pdf;

// Models
// use App\Models\erp\lastid;
// use App\Models\erp\workallocation;
// use App\Models\erp\workallocationitem;
// use App\Models\erp\workallocationresult;

// use App\Models\tes_laravel\lastid;
// use App\Models\tes_laravel\workallocation;
// use App\Models\tes_laravel\workallocationitem;
// use App\Models\tes_laravel\workallocationresult;

class SPKOTambahanController extends Controller
{
    // Setup Public Function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index(){ //OK
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        // Tampil SPKO List
        $query = "SELECT 
                    WA.ID, WA.SW, CONCAT(WA.SW,'-',WA.FREQ) SWFREQ
                FROM
                    workallocation WA
                WHERE
                    WA.Location = $location
                    AND WA.Active <> 'C' 
                ORDER BY WA.ID DESC";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.SPKOTambahan.index', compact('data','rowcount'));
    }

    public function lihat(Request $request){ //OK
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $swspko = $request->id;
        $swfreq = explode("-", $swspko);
        $sw = $swfreq[0];
        $freq = $swfreq[1];

        // Tampil Proses
        $queryProses= "SELECT * FROM operation WHERE Location = $location AND Active='Y' Order By Description ";
        $proses = FacadesDB::connection('erp')->select($queryProses);

        // Get Time Now
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Tampil RPH
        $queryRPH = "SELECT ID, DATE_FORMAT(TransDate,'%d-%m-%y') TransDate FROM WorkSchedule WHERE location=$location AND Active='P' ORDER BY ID DESC";
        $dataRPH = FacadesDB::connection('erp')->select($queryRPH);

        // Tampil SPKO Header
        $queryHeader = "SELECT 
                            W.*, Cast(E.SW As Char) ESW, Cast(G.WorkGroup As Char) EGroup, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                            O.Description ODescription, C.Description CSW, Cast(IfNull(G.WorkGroup, E.SW) As Char) EmployeeGroup, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA
                        From 
                            WorkAllocation W
                            Join Employee E On W.Employee = E.ID
                            Join Location L On W.Location = L.ID
                            Join Operation O On W.Operation = O.ID
                            Left Join ProductCarat C On W.Carat = C.ID
                            Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On W.WorkGroup = G.IDM
                        Where W.SW = $sw And W.Freq = $freq ";
        $header = FacadesDB::connection('erp')->select($queryHeader);
        $rowcountHeader = count($header);

        // Set Data Header
        foreach ($header as $headers){}
        $activeSPKO = $headers->Active;
        $idWA = $headers->ID;
        $transdateSPKO = $headers->TransDate;
        $locationSPKO = $headers->Location;

        // Khusus Enamel - Cek Proses
        $enmOpExclude = array(48,89);
        if(in_array($headers->Operation, $enmOpExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        // Cek Stok Harian
        // $querySH = "SELECT ID FROM Location WHERE ID = $locationSPKO AND StockDate = (SELECT MAX(TransDate) TransDate FROM WorkDate WHERE TransDate < '$transdateSPKO' AND Holiday = 'N' )";
        // $stokHarian = FacadesDB::connection('erp')->select($querySH);
        // $rowcountSH = count($stokHarian);
        // if($rowcountSH > 0){
        //     $status_SH = 'Y';
        // }else{
        //     $status_SH = 'N';
        // }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

        // Tampil SPKO Item
        $queryItem= "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.SW
                    From WorkAllocationItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                        JOIN WorkAllocation WA ON A.IDM=WA.ID
                    Where A.IDM = $idWA
                    Order By A.Ordinal ";
        $item = FacadesDB::connection('erp')->select($queryItem);

        $returnHTML = view('Produksi.PelaporanProduksi.SPKOTambahan.lihat', compact('location','datenow','status_SH','header','rowcountHeader','item','dataRPH'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'active' => $activeSPKO, 'excludepic' => $excludepic) );
    }

    public function baru(Request $request){ //OK
        $location = session('location');
        $username = session('UserEntry');
        $iduser = session('iduser');
        $iddept = session('iddept');
        $leveluser = session('LevelUser');

        if($location == NULL){
            $location = 47;
        }

        $swspko = $request->swspko;

        // Get Time Now
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Tampil SPKO Header
        $queryHeader = "SELECT 
                            W.*, Cast(E.SW As Char) ESW, Cast(G.WorkGroup As Char) EGroup, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                            O.Description ODescription, C.Description CSW, Cast(IfNull(G.WorkGroup, E.SW) As Char) EmployeeGroup, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA,
                            O.Product ProsesProduct
                        From 
                            WorkAllocation W
                            Join Employee E On W.Employee = E.ID
                            Join Location L On W.Location = L.ID
                            Join Operation O On W.Operation = O.ID
                            Left Join ProductCarat C On W.Carat = C.ID
                            Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On W.WorkGroup = G.IDM
                        Where W.SW = $swspko And W.Freq = 1";
        $header = FacadesDB::connection('erp')->select($queryHeader);
        $rowcountHeader = count($header);

        foreach($header as $headers){};
        $locationSPKO = $headers->Location;
        $transdateSPKO = $headers->TransDate;

        // MaxFreq
        $maxFreq = FacadesDB::connection('erp')->select("SELECT Max(Freq) MaxFreq FROM workallocation WHERE SW=$swspko");
        foreach($maxFreq as $maxFreqs){};
        $dataMaxFreq = $maxFreqs->MaxFreq;

        // Check Stok Harian
        $cekStokHarian = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

        // Tampil Bagian
        $queryBagian = "SELECT ID, Description FROM Location WHERE ID = $location ";
        $bagian = FacadesDB::connection('erp')->select($queryBagian);

        // Tampil Kadar
        $queryKadar = "SELECT ID, SW, Description FROM productcarat WHERE Regular='Y' ORDER BY Description ";
        $kadar = FacadesDB::connection('erp')->select($queryKadar);

        // Tampil Proses
        $queryProses= "SELECT * FROM operation WHERE Location = $location AND Active='Y' Order By Description ";
        $proses = FacadesDB::connection('erp')->select($queryProses);

         // Tampil RPH
         $queryRPH = "SELECT ID, DATE_FORMAT(TransDate,'%d-%m-%y') TransDate FROM WorkSchedule WHERE location=$location AND Active='P' ORDER BY ID DESC";
         $rph = FacadesDB::connection('erp')->select($queryRPH);
 
        $returnHTML = view('Produksi.PelaporanProduksi.SPKOTambahan.baruView', compact('location','header','cekStokHarian','dataMaxFreq'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK') );
    }

    public function ubah(Request $request){ //OK
        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $swspko = $request->id;
        $swfreq = explode("-", $swspko);
        $sw = $swfreq[0];
        $freq = $swfreq[1];
        
        // Get Time Now
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("Y-m-d");

        // Tampil RPH
        $queryRPH = "SELECT ID, DATE_FORMAT(TransDate,'%d-%m-%y') TransDate FROM WorkSchedule WHERE location=$location AND Active='P' ORDER BY ID DESC";
        $rph = FacadesDB::connection('erp')->select($queryRPH);

        // List Carat
        $queryKadar = "SELECT ID, SW, Description FROM productcarat WHERE Regular='Y' ORDER BY Description ";
        $kadar = FacadesDB::connection('erp')->select($queryKadar);

        // List Proses
        $queryProses = "SELECT * FROM operation WHERE Location = $location AND Active='Y' Order By Description ";
        $proses = FacadesDB::connection('erp')->select($queryProses);

        // Tampil SPKO Header
        $queryHeader = "SELECT 
                            W.*, Cast(E.SW As Char) ESW, Cast(G.WorkGroup As Char) EGroup, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                            O.Description ODescription, C.Description CSW, Cast(IfNull(G.WorkGroup, E.SW) As Char) EmployeeGroup, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA,
                            O.Product ProsesProduct
                        From 
                            WorkAllocation W
                            Join Employee E On W.Employee = E.ID
                            Join Location L On W.Location = L.ID
                            Join Operation O On W.Operation = O.ID
                            Left Join ProductCarat C On W.Carat = C.ID
                            Left Join ( Select I.IDM, Group_Concat(E.SW Order By E.ID Separator ', ') WorkGroup From WorkGroupItem I Join Employee E On I.Employee = E.ID Group By I.IDM ) G On W.WorkGroup = G.IDM
                        Where W.SW = $sw And W.Freq = $freq ";
        $header = FacadesDB::connection('erp')->select($queryHeader);
        $rowcountHeader = count($header);

        foreach ($header as $headers){}
        $activeSPKO = $headers->Active;
        $idWA = $headers->ID;
        $transdateSPKO = $headers->TransDate;
        $locationSPKO = $headers->Location;

        $enmOpExclude = array(48,89);
        if(in_array($headers->Operation, $enmOpExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        // Cek Stok Harian Manual
        // $querySH = "SELECT ID FROM Location WHERE ID = $locationSPKO AND StockDate = (SELECT MAX(TransDate) TransDate FROM WorkDate WHERE TransDate < '$transdateSPKO' AND Holiday = 'N' )";
        // $stokHarian = FacadesDB::connection('erp')->select($querySH);
        // $rowcountSH = count($stokHarian);
        // if($rowcountSH > 0){
        //     $status_SH = 'Y';
        // }else{
        //     $status_SH = 'N';
        // }

        // Cek Stok Harian (Public Function)
        $status_SH = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

        // Tampil SPKO Item
        $queryItem = "SELECT A.*,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.SW, O.ID OID
                    From WorkAllocationItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                        JOIN WorkAllocation WA ON A.IDM=WA.ID
                    Where A.IDM = $idWA
                    Order By A.Ordinal ";
        $item = FacadesDB::connection('erp')->select($queryItem);

        $returnHTML = view('Produksi.PelaporanProduksi.SPKOTambahan.ubahView', compact('location','datenow','status_SH','header','rowcountHeader','item','kadar','proses','rph'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK', 'active' => $activeSPKO, 'excludepic' => $excludepic) );
    }

    public function cariKaryawan(Request $request){ //OK
        $location = session('location');

        if($location == NULL){
            $location = 47;
        }

        $proses = $request->proses;
        $id = $request->id;
        
        // Get Data Employee
        $query = "SELECT ID, SW FROM Employee WHERE ID = $id ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount == 0){
            $jsondata = array('success' => false);
        }else{
            foreach ($data as $datas){}
            $idkary = $datas->ID;
            $swkary = $datas->SW;

            $jsondata = array('success' => true, 'idkary' => $idkary, 'swkary' => $swkary);
        }
        return response()->json($jsondata);
    }

    public function cariWorkgroup(Request $request){ //OK
        $location = session('location');
        $id = $request->id;
        $empid = $request->empid;

        if($location == NULL){
            $location = 47;
        }

        $wgArr = array();
        $wgArrID = array();

        if($id == ''){
            $goto = array('status' => 'setnull');
        }else{
            // Get Data WorkGroup
            $query = "SELECT A.ID, B.Employee, E.SW ESW
                    FROM workgroup A
                    JOIN workgroupitem B ON B.IDM=A.ID
                    JOIN employee E ON E.ID=B.Employee
                    WHERE 
                    A.Department = $location
                    AND A.ID = $id ";
            $data = FacadesDB::connection('erp')->select($query);
            $rowcount = count($data);

            if($rowcount == 0){
                $goto = array('status' => 'notfound');
            }else{
                foreach ($data as $datas){
                    $wgArr[] = $datas->ESW; 
                    $wgArrID[] = $datas->Employee;
                }
            
                if(in_array($empid, $wgArrID)){
                    $idwg = $id;
                    $swwg = implode(", ", $wgArr);
                    $goto = array('idwg' => $idwg, 'swwg' => $swwg, 'status' => 'sukses');
                }else{
                    $goto = array('status' => 'notsame');
                }
            }
        }
        return response()->json($goto);
    }

    public function simpan(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $idspko = $request->idspko;
        $swspko = $request->swspko;
        $freqspko = $request->freqspko;
        $stone = 0;
        $karyawan = $request->karyawan;
        $karyawanid = $request->karyawanid;
        $tgl = $request->tgl;
        $kadar = $request->kadar;
        $proses = $request->proses;
        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        $workgroup = $request->workgroupid;
        $targetqtyWA = $request->qtyspko;
        $weightWA = $request->weightspko;
        $keperluan = $request->keperluan;

        $WorkAllocation = $request->WorkAllocation;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $Carat = $request->Carat;
        $WorkOrder = $request->WorkOrder;
        $Note = $request->Note;
        $BarcodeNote = $request->BarcodeNote;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $PID = $request->PID;
        $PrevProcess = $request->PrevProcess;
        $PrevOrd = $request->PrevOrd;
        $FG = $request->FG;
        $Part = $request->Part;
        $RID = $request->RID;
        $OID = $request->OID;
        $OOrd = $request->OOrd;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $BatchNo = $request->BatchNo;
        
        $jmlArr = count($WorkAllocation);
        
        // Khusus Enamel - Cek Proses
        $enamelExclude = array(48,89);
        if(in_array($proses, $enamelExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        if($location == 47){
            if($proses == 161 || $proses == 166){

                $querywg = "SELECT * FROM workgroupitem
                            WHERE IDM IN (647)
                            AND Employee = $karyawanid ";
                $sqlwg = FacadesDB::connection('erp')->select($querywg);
                $rowcount = count($sqlwg);
        
                if($rowcount == 0){
                    $statuswg = 'Kosong';
                }else{
                    $statuswg = 'Ada';
                }

            }else if($proses == 48){
        
                $querywg = "SELECT * FROM workgroupitem
                            WHERE IDM IN (594)
                            AND Employee = $karyawanid
                            ";
                $sqlwg = FacadesDB::connection('erp')->select($querywg);
                $rowcount = count($sqlwg);
        
                if($rowcount == 0){
                    $statuswg = 'Kosong';
                }else{
                    $statuswg = 'Ada';
                }
                
            }else{
                $statuswg = 'Kosong';
            }
        
        }else{
            $statuswg = 'Ada';
        }

        // LAST ID - (WorkAllocation)
        $query = "SELECT LAST+1 AS ID FROM lastid Where Module = 'WorkAllocation' ";
        $data = FacadesDB::connection('erp')->select($query);
        foreach ($data as $datas){}

        $query2 = "UPDATE lastid SET LAST = $datas->ID WHERE Module = 'WorkAllocation' ";
        $data2 = FacadesDB::connection('erp')->update($query2);

        FacadesDB::connection('erp')->beginTransaction();
        try {

            // GET FREQ SPKO
            $queryfreq = "SELECT * FROM workallocation WHERE SW=$swspko ";
            $datafreq = FacadesDB::connection('erp')->select($queryfreq);
            $numfreq = count($datafreq)+1;
            $swfreqspko = $swspko.'-'.$numfreq;

            // INSERT WORKALLOCATION
            if($keperluan == 1){ //TAMBAH

                $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
                $queryWA = "INSERT INTO workallocation 
                            (ID, EntryDate, UserName, SW, Freq, TransDate, Purpose, Carat, Location, Operation, Employee, TargetQty, Weight, Active, 
                            WorkGroup, Stone, Outsource, Remarks) 
                            VALUES 
                            ($datas->ID, now(), '$username', $swspko, $numfreq, '$tgl', 'Tambah', $kadar, $location, $prosesID, $karyawanid, $targetqtyWA, $weightWA, 'A', 
                            $WorkGroupOK, $stone, 'N', 'Laravel') ";
                            // dd($queryWA);
                $dataWA = FacadesDB::connection('erp')->insert($queryWA);
            
            }else if($keperluan == 2){ //KURANG

                $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
                $queryWA = "INSERT INTO workallocation 
                            (ID, EntryDate, UserName, SW, Freq, TransDate, Purpose, Carat, Location, Operation, Employee, TargetQty, Weight, Active, 
                            WorkGroup, Stone, Outsource, Remarks) 
                            VALUES 
                            ($datas->ID, now(), '$username', $swspko, $numfreq, '$tgl', 'Kurang', $kadar, $location, $prosesID, $karyawanid, -$targetqtyWA, -$weightWA, 'A', 
                            $WorkGroupOK, $stone, 'N', 'Laravel') ";
                $dataWA = FacadesDB::connection('erp')->insert($queryWA);
            
            }
            // dd($queryWA);

            // INSERT WORKALLOCATIONITEM
            $no = 1;
            for ($i = 0; $i < $jmlArr; $i++) {

                $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
                $WorkAllocationOK = ((isset($WorkAllocation[$i])) ? $WorkAllocation[$i] : 'NULL');
                $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
                $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
                $CaratOK = ((isset($Carat[$i])) ? $Carat[$i] : 'NULL');
                $WorkOrderOK = ((isset($WorkOrder[$i])) ? $WorkOrder[$i] : 'NULL');
                $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".$BarcodeNote[$i]."'" : 'NULL');
                $NoteOK = ((isset($Note[$i])) ? "'".$Note[$i]."'" : 'NULL');
                $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
                $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
                $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
                $PrevProcessOK = ((isset($PrevProcess[$i])) ? $PrevProcess[$i] : 'NULL');
                $PrevOrdOK = ((isset($PrevOrd[$i])) ? $PrevOrd[$i] : 'NULL');
                $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
                $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
                $RIDOK = ((isset($RID[$i])) ? $RID[$i] : 'NULL');
                $OIDOK = ((isset($OID[$i])) ? $OID[$i] : 'NULL');
                $OOrdOK = ((isset($OOrd[$i])) ? $OOrd[$i] : 'NULL');
                $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
                $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
                $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');
            
                $queryWAI = "INSERT INTO workallocationitem 
                            (IDM, Ordinal, Product, Carat, Qty, Weight, WorkOrder, WorkOrderOrd, Note, BarcodeNote,
                            PrevProcess, PrevOrd, PrevType, TreeID, TreeOrd, Part, FG, StoneLoss, QtyLossStone, WaxOrder, BatchNo, WorkSchedule, WorkScheduleOrd) 
                            VALUES 
                            ($datas->ID, $no, $PIDOK, $RIDOK, $QtyOK, $WeightOK, $OIDOK, $OOrdOK, $NoteOK, $BarcodeNoteOK,
                            $PrevProcessOK, $PrevOrdOK, NULL, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, $StoneLossOK, $QtyLossStoneOK, NULL, $BatchNoOK, NULL, NULL)";
                $dataWAI = FacadesDB::connection('erp')->insert($queryWAI);

                $no++;
            }

            // if($dataWA == TRUE && $dataWAI == TRUE){
            //     $data = array('status' => 'Sukses', 'idspko' => $datas->ID, 'swspko' => $swfreqspko, 'excludepic' => $excludepic, 'statuswg' => $statuswg);		
            // }else{
            //     $data = array('status' => 'Gagal');		
            // }

            // return response()->json($data);

            FacadesDB::connection('erp')->commit();
            $json_return = array(
                'success' => true,
                'status' => 'Sukses',
                'idspko' => $datas->ID,
                'swspko' => $swfreqspko,
                'excludepic' => $excludepic,
                'statuswg' => $statuswg,
                'message' => 'Simpan Berhasil !'
            );
            return response()->json($json_return,200);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'message' => 'Simpan Error !'
            );
            return response()->json($json_return,500);
        }
    }

    public function update(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $idspko = $request->idspko;
        $swspko = $request->swspko;
        $freqspko = $request->freqspko;
        $stone = 0;
        $karyawan = $request->karyawan;
        $karyawanid = $request->karyawanid;
        $tgl = $request->tgl;
        $kadar = $request->kadar;
        $proses = $request->proses;
        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        $workgroup = $request->workgroupid;
        $targetqtyWA = $request->qtyspko;
        $weightWA = $request->weightspko;
        $keperluan = $request->keperluan;

        $WorkAllocation = $request->WorkAllocation;
        $Qty = $request->Qty;
        $Weight = $request->Weight;
        $Carat = $request->Carat;
        $WorkOrder = $request->WorkOrder;
        $Note = $request->Note;
        $BarcodeNote = $request->BarcodeNote;
        $StoneLoss = $request->StoneLoss;
        $QtyLossStone = $request->QtyLossStone;
        $PID = $request->PID;
        $PrevProcess = $request->PrevProcess;
        $PrevOrd = $request->PrevOrd;
        $FG = $request->FG;
        $Part = $request->Part;
        $RID = $request->RID;
        $OID = $request->OID;
        $OOrd = $request->OOrd;
        $TreeID = $request->TreeID;
        $TreeOrd = $request->TreeOrd;
        $BatchNo = $request->BatchNo;

        $jmlArr = count($WorkAllocation);

        $swfreqspko = $swspko.'-'.$freqspko;
        
        // Khusus Enamel - Cek Proses
        $enamelExclude = array(48,89);
        if(in_array($proses, $enamelExclude)){
            $excludepic = 1;
        }else{
            $excludepic = 0;
        }

        if($location == 47){
            if($proses == 161 || $proses == 166){
        
                $querywg = "SELECT * FROM workgroupitem
                            WHERE IDM IN (647)
                            AND Employee = $karyawanid
                            ";
                $sqlwg = FacadesDB::connection('erp')->select($querywg);
                $rowcount = count($sqlwg);
        
                if($rowcount == 0){
                    $statuswg = 'Kosong';
                }else{
                    $statuswg = 'Ada';
                }
            }else if($proses == 48){
        
                $querywg = "SELECT * FROM workgroupitem
                            WHERE IDM IN (594)
                            AND Employee = $karyawanid
                            ";
                $sqlwg = FacadesDB::connection('erp')->select($querywg);
                $rowcount = count($sqlwg);
        
                if($rowcount == 0){
                    $statuswg = 'Kosong';
                }else{
                    $statuswg = 'Ada';
                }
                
            }else{
                $statuswg = 'Kosong';
            }
        
        }else{
            $statuswg = 'Ada';
        }


        FacadesDB::connection('erp')->beginTransaction();
        try {

            // UPDATE WORKALLOCATION
            if($keperluan == 1){
                
                $WorkGroupOK = ((!empty($workgroup)) ? $workgroup : "NULL");
                $queryWA = "UPDATE workallocation 
                            SET
                                UserName = '$username',
                                TransDate = '$tgl',
                                Carat = $kadar,
                                Operation = $prosesID,
                                Employee = $karyawanid,
                                WorkGroup = $WorkGroupOK,
                                TargetQty = $targetqtyWA,
                                Weight = $weightWA,
                                Remarks = 'Update Laravel'
                            WHERE 
                                ID = $idspko
                            ";
                $dataWA = FacadesDB::connection('erp')->update($queryWA);
            
            }else if($keperluan == 2){

                $WorkGroupOK = ((isset($workgroup)) ? $workgroup : 'NULL');
                $queryWA = "UPDATE workallocation 
                            SET
                                UserName = '$username',
                                TransDate = '$tgl',
                                Carat = $kadar,
                                Operation = $prosesID,
                                Employee = $karyawanid,
                                WorkGroup = $WorkGroupOK,
                                TargetQty = ".-$targetqtyWA.",
                                Weight = ".-$weightWA.",
                                Remarks = 'Update Laravel'
                            WHERE 
                                ID = $idspko
                            ";
                $dataWA = FacadesDB::connection('erp')->update($queryWA);
            
            }

            // UPDATE WORKALLOCATIONITEM
            $deleteWAI = "DELETE FROM workallocationitem WHERE IDM = $idspko ";
            $sqldeleteWAI = FacadesDB::connection('erp')->delete($deleteWAI);

            $no = 1;
            for ($i = 0; $i < $jmlArr; $i++) {

                $PIDOK = ((isset($PID[$i])) ? $PID[$i] : 'NULL');
                $RIDOK = ((isset($RID[$i])) ? $RID[$i] : 'NULL');
                $QtyOK = ((isset($Qty[$i])) ? $Qty[$i] : 0);
                $WeightOK = ((isset($Weight[$i])) ? $Weight[$i] : 0);
                $OIDOK = ((isset($OID[$i])) ? $OID[$i] : 'NULL');
                $OOrdOK = ((isset($OOrd[$i])) ? $OOrd[$i] : 'NULL');
                $NoteOK = ((isset($Note[$i])) ? "'".$Note[$i]."'" : 'NULL');
                $BarcodeNoteOK = ((isset($BarcodeNote[$i])) ? "'".$BarcodeNote[$i]."'" : 'NULL');
                $PrevProcessOK = ((isset($PrevProcess[$i])) ? $PrevProcess[$i] : 'NULL');
                $PrevOrdOK = ((isset($PrevOrd[$i])) ? $PrevOrd[$i] : 'NULL');
                $TreeIDOK = ((isset($TreeID[$i])) ? $TreeID[$i] : 'NULL');
                $TreeOrdOK = ((isset($TreeOrd[$i])) ? $TreeOrd[$i] : 'NULL');
                $PartOK = ((isset($Part[$i])) ? $Part[$i] : 'NULL');
                $FGOK = ((isset($FG[$i])) ? $FG[$i] : 'NULL');
                $StoneLossOK = ((isset($StoneLoss[$i])) ? $StoneLoss[$i] : 0);
                $QtyLossStoneOK = ((isset($QtyLossStone[$i])) ? $QtyLossStone[$i] : 0);
                $BatchNoOK = ((isset($BatchNo[$i])) ? "'".$BatchNo[$i]."'" : 'NULL');

                $queryWAI = "INSERT INTO workallocationitem 
                            (IDM, Ordinal, Product,	Carat, Qty,	Weight,	WorkOrder, WorkOrderOrd, Note, BarcodeNote, PrevProcess, PrevOrd, PrevType, TreeID, TreeOrd, Part, FG, 
                            StoneLoss, QtyLossStone, WaxOrder, BatchNo, WorkSchedule, WorkScheduleOrd) 
                            VALUES 
                            ($idspko, $no, $PIDOK, $RIDOK, $QtyOK, $WeightOK, $OIDOK, $OOrdOK, $NoteOK, $BarcodeNoteOK, $PrevProcessOK, $PrevOrdOK, NULL, $TreeIDOK, $TreeOrdOK, $PartOK, $FGOK, 
                            $StoneLossOK, $QtyLossStoneOK, NULL, $BatchNoOK, NULL, NULL) ";
                $dataWAI = FacadesDB::connection('erp')->insert($queryWAI);

                $no++;
            }

            FacadesDB::connection('erp')->commit();
                $json_return = array(
                    'status' => 'Sukses',
                    'idspko' => $idspko,
                    'swspko' => $swfreqspko,
                    'excludepic' => $excludepic,
                    'statuswg' => $statuswg
                );
            return response()->json($json_return,200);
    
            // if($dataWAI == TRUE ){
            //     $data = array('status' => 'Sukses', 'idspko' => $idspko, 'swspko' => $swfreqspko, 'excludepic' => $excludepic, 'statuswg' => $statuswg);		
            // }else{
            //     $data = array('status' => 'Gagal');		
            // }

            // return response()->json($data);

        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            $json_return = array(
                'message' => 'Update Error !'
            );
            return response()->json($json_return,500);
        }
    }

    public function posting(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->idspko;

        $querystatus = "SELECT * FROM workallocation WHERE ID=$id ";
        $data = FacadesDB::connection('erp')->select($querystatus);

        foreach ($data as $datas){}
        $statuscek = $datas->Active;
        $swspko = $datas->SW;
        $freqspko = $datas->Freq;
        $transdateSPKO = $datas->TransDate;
        $locationSPKO = $datas->Location;
        $purpose = $datas->Purpose;

        $swfreqspko = $swspko.'-'.$freqspko;

        // Cek Stok Harian (Public Function)
        $cekStokHarian = $this->Public_Function->CekStokHarianERP($locationSPKO, $transdateSPKO); //Return True or False

        if($statuscek == 'P'){
            $json_return = array('status' => 'sdhposting');
            return response()->json($json_return,200);

        }else if($statuscek == 'S'){
            $json_return = array('status' => 'sdhsusut');
            return response()->json($json_return,200);

        }else if($statuscek == 'C'){
            $json_return = array('status' => 'sdhbatal');
            return response()->json($json_return,200);

        }else if ($statuscek == 'A' && $cekStokHarian == true){

            FacadesDB::connection('erp')->beginTransaction();
            try {

                if($purpose == 'Tambah'){

                    $postikan = FacadesDB::connection('erp')->select("SELECT WAI.*, WA.SW, WA.Location FROM workallocationitem WAI JOIN workallocation WA ON WA.ID=WAI.IDM WHERE WAI.IDM = $id ORDER BY Ordinal ASC");     

                    foreach ($postikan as $janda) {

                        $status = "C";                      // For credit (SPKO)
                        $tableitem = "workallocationitem";  // Tabel item
                        $userName = Auth::user()->name;     // User who post this transaction
                        $location = $janda->Location;       // Location SPKO
                        $product = $janda->Product;         // Ini nanti looping dari workallocationitem
                        $carat = $janda->Carat;             // Ini nanti looping dari workallocationitem
                        $Process = 'Production';            // Default
                        $cause = 'Usage';                   // todo: Usage (Stok Berkurang) (Untuk SPKO)
                        $SW = $janda->SW;                   // Ini nanti dapat dari tabel workallocationitem
                        $idSPKO = $janda->IDM;              // Ini nanti dapat dari tabel workallocationitem karena looping
                        $ordinal = $janda->Ordinal;         // Ini nanti dapat dari tabel workallocationitem karena looping
                        $workorder = $janda->WorkOrder;     // Ini nanti dapat dari tabel workallocationitem
        
                        $postingFunction = $this->Public_Function->PostingERP($status, $tableitem, $userName, $location, $product, $carat, $Process, $cause, $SW, $idSPKO, $ordinal, $workorder);    
                    }

                }else if($purpose == 'Kurang'){

                    $postikan = FacadesDB::connection('erp')->select("SELECT WAI.*, WA.SW, WA.Location FROM workallocationitem WAI JOIN workallocation WA ON WA.ID=WAI.IDM WHERE WAI.IDM = $id ORDER BY Ordinal ASC");     

                    foreach ($postikan as $janda) {
                
                        $status = "D";                      // For credit (SPKO)
                        $tableitem = "workallocationitem";  // Tabel item
                        $userName = Auth::user()->name;     // User who post this transaction
                        $location = $janda->Location;       // Location SPKO
                        $product = $janda->Product;         // Ini nanti looping dari workallocationitem
                        $carat = $janda->Carat;             // Ini nanti looping dari workallocationitem
                        $Process = 'Production';            // Default
                        $cause = 'Usage';                   // todo: Usage (Stok Berkurang) (Untuk SPKO)
                        $SW = $janda->SW;                   // Ini nanti dapat dari tabel workallocationitem
                        $idSPKO = $janda->IDM;              // Ini nanti dapat dari tabel workallocationitem karena looping
                        $ordinal = $janda->Ordinal;         // Ini nanti dapat dari tabel workallocationitem karena looping
                        $workorder = $janda->WorkOrder;     // Ini nanti dapat dari tabel workallocationitem
        
                        $postingFunction = $this->Public_Function->PostingERP($status, $tableitem, $userName, $location, $product, $carat, $Process, $cause, $SW, $idSPKO, $ordinal, $workorder);    
                    }
                }

                if ($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']) {

                    // UPDATE STATUS
                    $statusaktif = "UPDATE workallocation SET Active='P', PostDate=now(), Remarks='Posting Laravel' Where ID=$id ";
                    $aktif = FacadesDB::connection('erp')->update($statusaktif);

                    // UPDATE WORKALLOCATIONRESULT
                    $dataWAR = FacadesDB::connection('erp')->select("SELECT ID FROM workallocationresult WHERE SW=$swspko ");
                    $rowcek = count($dataWAR);
                    foreach ($dataWAR as $super){}

                    if($rowcek > 0){

                        $queryInfoWAR = "SELECT SUM(TargetQty) TargetQty, FORMAT(SUM(Weight),2) Weight, MAX(Freq) AlloFreq FROM workallocation WHERE SW=$swspko " ;
                        $infoWAR = FacadesDB::connection('erp')->select($queryInfoWAR);
                        foreach ($infoWAR as $super2){}

                        $queryUpdateWAR = "UPDATE workallocationresult SET TargetQty = $super2->TargetQty, Weight = $super2->Weight, AllocationFreq = $freqspko WHERE SW = $swspko ";
                        $updateWAR = FacadesDB::connection('erp')->update($queryUpdateWAR);
                    }
                }

                FacadesDB::connection('erp')->commit();
                $json_return = array(
                    'status' => 'sukses',
                    'swspko' => $swfreqspko
                );
                return response()->json($json_return,200);

                // if($postingFunction['validasi'] && $postingFunction['insertstok'] && $postingFunction['update_ptrns']){
                //     $data = array('status' => 'sukses', 'swspko' => $swfreqspko);	
                // }else{
                //     $data = array('status' => 'gagal');	
                // }


            } catch (Exception $e) {
                FacadesDB::connection('erp')->rollBack();
                $json_return = array(
                    'status' => 'Failed',
                    'message' => 'Posting Error !'
                );
                return response()->json($json_return,500);
            }

        }else{
            // $data = array('status' => 'belumstokharian');	
            $json_return = array('status' => 'belumstokharian');
            return response()->json($json_return,200);
        }

        // return response()->json($data);
    }

    public function barcodeUnit(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;
        $kadar = $request->carat;
        $wa = explode("-", $id);

        if(count($wa) <> 3){
            $goto = array('status' => 'NotFound');
        }else{

        $wasw = $wa[0];
        $wafreq = $wa[1];
        $waord = $wa[2];

        $queryCek = "SELECT IDM FROM transferrmitem WHERE WorkAllocation=$wasw AND LinkFreq=$wafreq AND LinkOrd=$waord AND WorkSchedule IS NOT NULL";
        $dataCek = FacadesDB::connection('erp')->select($queryCek);
        $rowcount = count($dataCek);

        // Get IDM and Ordinal NTHKO
        $queryDuplicate = "SELECT B.IDM, B.Ordinal FROM 
                            WorkCompletion A 
                            JOIN WorkCompletionItem B ON A.ID=B.IDM
                            WHERE A.WorkAllocation=$wasw AND A.Freq=$wafreq AND B.Ordinal=$waord
                            AND A.Active IN ('S')
                            ";
        $dataDuplicate= FacadesDB::connection('erp')->select($queryDuplicate);
        $rowcountDuplicate = count($dataDuplicate);

        // Cek SPKO Duplicate
        if($rowcountDuplicate > 0){

            foreach ($dataDuplicate as $datasDuplicate){}
            $IdmNthko = $datasDuplicate->IDM;
            $OrdNthko = $datasDuplicate->Ordinal;

            $queryCekDuplicate = "SELECT IDM FROM workallocationitem WAI
                                    JOIN workallocation WA ON WAI.IDM=WA.ID
                                    WHERE WAI.PrevProcess=$IdmNthko AND WAI.PrevOrd=$OrdNthko AND WA.Location=$location AND WA.Active IN ('S')";
            $dataCekDuplicate= FacadesDB::connection('erp')->select($queryCekDuplicate);
            $rowNumDuplicate = count($dataCekDuplicate);

        }else{
            $rowNumDuplicate = 0;
        }

        $query2 = "SELECT C.ID, C.WorkAllocation, C.Freq, P.Description PDescription, R.SW RSW, I.Ordinal, Cast(F.SW As Char) FinishGood, Cast(O.SW As Char) WorkOrder, I.WorkOrderOrd,
                        I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight, I.StoneLoss, I.QtyLossStone, P.Description Product,  T.Description Carat,
                        O.SW OSW, F.SW FDescription, T.SW TSW, P.UseCarat, P.SW PSW, I.BatchNo, O.SWPurpose,
                        If(F.SW = 'RPRS', G.SW, If(I.FG Is Null, H.SW, If(I.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) ProductDetail,
                        If(F.SW = 'RPRS', G.Photo, If(I.FG Is Null, H.Photo, G.Photo)) ProductPhoto,
                        G.ID GID, P.ID PID, R.ID RID, O.ID OID, O.TotalQty, I.Note, I.BarcodeNote, I.LinkID,
                        I.LinkOrd, I.TreeID, I.TreeOrd, X.SW RubberPlate, I.Part, I.FG, I.IDM PrevProcess, I.Ordinal PrevOrd, 
                        CONCAT(C.workallocation,'-',C.Freq,'-',I.Ordinal) NTHKO, TMI.WorkSchedule RPH
                    From WorkCompletion C
                        Join WorkCompletionItem I On C.ID = I.IDM And I.Carat = $kadar And If(C.Location <> 7, (((I.Qty + I.RepairQty) <> 0) Or ((I.Weight + I.RepairWeight) <> 0)), I.Product <> 255)
                        Join Product P On I.Product = P.ID
                        Join ProductCarat R On I.Carat = R.ID
                        Join WorkOrder O On I.WorkOrder = O.ID
                        Join Product F On O.Product = F.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join WaxTree W On I.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On I.FG = G.ID
                        Left Join Product H On I.Part = H.ID
                        JOIN transferrmitem TMI ON TMI.WorkAllocation=C.WorkAllocation AND TMI.LinkFreq=C.Freq AND TMI.LinkOrd=I.Ordinal
                    Where 
                        C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And I.Ordinal = $waord
                    ";

        $query = "SELECT C.ID, C.WorkAllocation, C.Freq, P.Description PDescription, R.SW RSW, I.Ordinal, Cast(F.SW As Char) FinishGood, Cast(O.SW As Char) WorkOrder, I.WorkOrderOrd, 
                    I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight, I.StoneLoss, I.QtyLossStone, P.Description Product,  T.Description Carat,
                    O.SW OSW, F.SW FDescription, T.SW TSW, P.UseCarat, P.SW PSW, I.BatchNo, O.SWPurpose,
                    If(F.SW = 'RPRS', G.SW, If(I.FG Is Null, H.SW, If(I.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) ProductDetail,
                    If(F.SW = 'RPRS', G.Photo, If(I.FG Is Null, H.Photo, G.Photo)) ProductPhoto,
                    G.ID GID, P.ID PID, R.ID RID, O.ID OID, O.TotalQty, I.Note, I.BarcodeNote, I.LinkID,
                    I.LinkOrd, I.TreeID, I.TreeOrd, X.SW RubberPlate, I.Part, I.FG, I.IDM PrevProcess, I.Ordinal PrevOrd
                From WorkCompletion C
                    Join WorkCompletionItem I On C.ID = I.IDM And I.Carat = $kadar And If(C.Location <> 7, (((I.Qty + I.RepairQty) <> 0) Or ((I.Weight + I.RepairWeight) <> 0)), I.Product <> 255)
                    Join Product P On I.Product = P.ID
                    Join ProductCarat R On I.Carat = R.ID
                    Join WorkOrder O On I.WorkOrder = O.ID
                    Join Product F On O.Product = F.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join WaxTree W On I.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On I.FG = G.ID
                    Left Join Product H On I.Part = H.ID
                Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = $wasw And C.Freq = $wafreq And I.Ordinal = $waord ";
 
        // Filter Duplicate NTHKO Saat Membuat SPKO
        if($rowNumDuplicate > 0){
            $goto = array('status' => 'Duplicate');

        }else{

            if($rowcount == 0){
                $hasil = FacadesDB::connection('erp')->select($query);
            }else{
                $hasil = FacadesDB::connection('erp')->select($query2);
            }
            
            $row = count($hasil);
            foreach ($hasil as $hasils){}

            if($row == 0){
                $goto = array('status' => 'Kosong');	
            }else{

                $WorkAllocation = ((isset($hasils->WorkAllocation)) ? $hasils->WorkAllocation : '');
                $Freq = ((isset($hasils->Freq)) ? $hasils->Freq : '');
                $Ordinal = ((isset($hasils->Ordinal)) ? $hasils->Ordinal : '');
                $WorkOrder = ((isset($hasils->WorkOrder)) ? $hasils->WorkOrder : '');
                $FinishGood = ((isset($hasils->FinishGood)) ? $hasils->FinishGood : '');
                $Carat = ((isset($hasils->Carat)) ? $hasils->Carat : '');
                $TotalQty = ((isset($hasils->TotalQty)) ? $hasils->TotalQty : '');
                $Product = ((isset($hasils->Product)) ? $hasils->Product : '');
                $Qty = ((isset($hasils->Qty)) ? $hasils->Qty : '');
                $Weight = ((isset($hasils->Weight)) ? $hasils->Weight : '');
                $StoneLoss = ((isset($hasils->StoneLoss)) ? $hasils->StoneLoss : '');
                $QtyLossStone = ((isset($hasils->QtyLossStone)) ? $hasils->QtyLossStone : '');
                $BarcodeNote = ((isset($hasils->BarcodeNote)) ? $hasils->BarcodeNote : '');
                $Note = ((isset($hasils->Note)) ? $hasils->Note : '');
                $RubberPlate = ((isset($hasils->RubberPlate)) ? $hasils->RubberPlate : '');
                $ProductDetail = ((isset($hasils->ProductDetail)) ? $hasils->ProductDetail : '');
                $TreeID = ((isset($hasils->TreeID)) ? $hasils->TreeID : '');
                $TreeOrd = ((isset($hasils->TreeOrd)) ? $hasils->TreeOrd : '');
                $BatchNo = ((isset($hasils->BatchNo)) ? $hasils->BatchNo : '');
                $ProductPhoto = ((isset($hasils->ProductPhoto)) ? $hasils->ProductPhoto : '');
                $PID = ((isset($hasils->PID)) ? $hasils->PID : '');
                $PrevProcess = ((isset($hasils->PrevProcess)) ? $hasils->PrevProcess : '');
                $PrevOrd = ((isset($hasils->PrevOrd)) ? $hasils->PrevOrd : '');
                $FG = ((isset($hasils->FG)) ? $hasils->FG : '');
                $Part = ((isset($hasils->Part)) ? $hasils->Part : '');
                $RID = ((isset($hasils->RID)) ? $hasils->RID : '');
                $OID = ((isset($hasils->OID)) ? $hasils->OID : '');
                $OOrd = ((isset($hasils->WorkOrderOrd)) ? $hasils->WorkOrderOrd : '');
            
                $goto = array('WorkAllocation' => $WorkAllocation, 'Freq' => $Freq, 'Ordinal' => $Ordinal, 'WorkOrder' => $WorkOrder, 'FinishGood' => $FinishGood, 'Carat' => $Carat, 'TotalQty' => $TotalQty,
                        'Product' => $Product, 'Qty' => $Qty, 'Weight' => $Weight, 'StoneLoss' => $StoneLoss, 'QtyLossStone' => $QtyLossStone, 'BarcodeNote' => $BarcodeNote, 'Note' => $Note, 
                        'RubberPlate' => $RubberPlate, 'ProductDetail' => $ProductDetail, 'TreeID' => $TreeID, 'TreeOrd' => $TreeOrd, 'BatchNo' => $BatchNo, 'ProductPhoto' => $ProductPhoto,
                        'PID' => $PID, 'PrevProcess' => $PrevProcess, 'PrevOrd' => $PrevOrd, 'FG' => $FG, 'Part' => $Part, 'RID' => $RID, 'OID' => $OID, 'OOrd' => $OOrd, 'CaratID' => $kadar,
                        'status' => 'Sukses');
            }
        }
        }
        return response()->json($goto);
    }

    public function barcodeAll(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;
        $kadar = $request->carat;
        $wa = explode("-", $id);
        $proses = $request->proses;

        $prosesAll = explode(",", $proses);
        $prosesID = $prosesAll[0];
        $prosesProduct = $prosesAll[1];

        if(count($wa) <> 2 ){
            $goto = array('status' => 'NotFound');
        }else{
            $wasw = $wa[0];
            $wafreq = $wa[1];

            $query = "SELECT C.ID, C.WorkAllocation, C.Freq, P.Description PDescription, R.SW RSW, I.Ordinal, Cast(F.SW As Char) FinishGood, Cast(O.SW As Char) WorkOrder, I.WorkOrderOrd,
                            I.Qty + I.RepairQty Qty, I.Weight + I.RepairWeight Weight, I.StoneLoss, I.QtyLossStone, P.Description Product,  T.Description Carat,
                            O.SW OSW, F.SW FDescription, T.SW TSW, P.UseCarat, P.SW PSW, I.BatchNo, O.SWPurpose,
                            If(F.SW = 'RPRS', G.SW, If(I.FG Is Null, H.SW, If(I.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) ProductDetail,
                            If(F.SW = 'RPRS', G.Photo, If(I.FG Is Null, H.Photo, G.Photo)) ProductPhoto,
                            G.ID GID, P.ID PID, R.ID RID, O.ID OID, O.TotalQty, I.Note, I.BarcodeNote, I.LinkID,
                            I.LinkOrd, I.TreeID, I.TreeOrd, X.SW RubberPlate, I.Part, I.FG, I.IDM PrevProcess, I.Ordinal PrevOrd
                        From WorkCompletion C
                            Join WorkCompletionItem I On C.ID = I.IDM And I.Carat = ".$kadar." And If(C.Location <> 7, (((I.Qty + I.RepairQty) <> 0) Or ((I.Weight + I.RepairWeight) <> 0)), I.Product <> 255)
                            Join Product P On I.Product = P.ID
                            Join ProductCarat R On I.Carat = R.ID
                            Join WorkOrder O On I.WorkOrder = O.ID
                            Join Product F On O.Product = F.ID
                            Left Join ProductCarat T On O.Carat = T.ID
                            Left Join WaxTree W On I.TreeID = W.ID
                            Left Join RubberPlate X On W.SW = X.ID
                            Left Join Product G On I.FG = G.ID
                            Left Join Product H On I.Part = H.ID
                        Where C.Active In ('R', 'P', 'S') And C.WorkAllocation = ".$wasw." And C.Freq = ".$wafreq." AND P.ID = ".$prosesProduct." ";
            $hasil = FacadesDB::connection('erp')->select($query);
            $rowcountHasil = count($hasil);

            // $dataSomething = [];
            // foreach ($hasil as $key => $value) {
            //     $dataSomething[] = $value->WorkAllocation;
            // }
            // dd($dataSomething);
            
            if($rowcountHasil == 0){
                $goto = array('status' => 'NotFound');
            }else{
                $arrWorkAllocation = array();
                $arrFreq = array();
                $arrOrdinal = array();
                $arrWorkOrder = array();
                $arrFinishGood = array();
                $arrCarat = array();
                $arrTotalQty = array();
                $arrProduct = array();
                $arrQty= array();
                $arrWeight = array();
                $arrStoneLoss = array();
                $arrQtyLossStone = array();
                $arrBarcodeNote = array();
                $arrNote = array();
                $arrRubberPlate = array();
                $arrProductDetail = array();
                $arrTreeID = array();
                $arrTreeOrd = array();
                $arrBatchNo = array();
                $arrProductPhoto = array();
                $arrPID = array();
                $arrPrevProcess = array();
                $arrPrevOrd = array();
                $arrFG = array();
                $arrPart = array();
                $arrRID = array();
                $arrOID = array();
                $arrOOrd = array();
                
                foreach ($hasil as $hasils){

                    $WorkAllocation = ((isset($hasils->WorkAllocation)) ? $hasils->WorkAllocation : '');
                    $Freq = ((isset($hasils->Freq)) ? $hasils->Freq : '');
                    $Ordinal = ((isset($hasils->Ordinal)) ? $hasils->Ordinal : '');
                    $WorkOrder = ((isset($hasils->WorkOrder)) ? $hasils->WorkOrder : '');
                    $FinishGood = ((isset($hasils->FinishGood)) ? $hasils->FinishGood : '');
                    $Carat = ((isset($hasils->Carat)) ? $hasils->Carat : '');
                    $TotalQty = ((isset($hasils->TotalQty)) ? $hasils->TotalQty : '');
                    $Product = ((isset($hasils->Product)) ? $hasils->Product : '');
                    $Qty = ((isset($hasils->Qty)) ? $hasils->Qty : '');
                    $Weight = ((isset($hasils->Weight)) ? $hasils->Weight : '');
                    $StoneLoss = ((isset($hasils->StoneLoss)) ? $hasils->StoneLoss : '');
                    $QtyLossStone = ((isset($hasils->QtyLossStone)) ? $hasils->QtyLossStone : '');
                    $BarcodeNote = ((isset($hasils->BarcodeNote)) ? $hasils->BarcodeNote : '');
                    $Note = ((isset($hasils->Note)) ? $hasils->Note : '');
                    $RubberPlate = ((isset($hasils->RubberPlate)) ? $hasils->RubberPlate : '');
                    $ProductDetail = ((isset($hasils->ProductDetail)) ? $hasils->ProductDetail : '');
                    $TreeID = ((isset($hasils->TreeID)) ? $hasils->TreeID : '');
                    $TreeOrd = ((isset($hasils->TreeOrd)) ? $hasils->TreeOrd : '');
                    $BatchNo = ((isset($hasils->BatchNo)) ? $hasils->BatchNo : '');
                    $ProductPhoto = ((isset($hasils->ProductPhoto)) ? $hasils->ProductPhoto : '');
                    $PID = ((isset($hasils->PID)) ? $hasils->PID : '');
                    $PrevProcess = ((isset($hasils->PrevProcess)) ? $hasils->PrevProcess : '');
                    $PrevOrd = ((isset($hasils->PrevOrd)) ? $hasils->PrevOrd : '');
                    $FG = ((isset($hasils->FG)) ? $hasils->FG : '');
                    $Part = ((isset($hasils->Part)) ? $hasils->Part : '');
                    $RID = ((isset($hasils->RID)) ? $hasils->RID : '');
                    $OID = ((isset($hasils->OID)) ? $hasils->OID : '');
                    $OOrd = ((isset($hasils->OOrd)) ? $hasils->OOrd : '');
                    
                    $arrWorkAllocation[] = $WorkAllocation;
                    $arrFreq[] = $Freq;
                    $arrOrdinal[] = $Ordinal;
                    $arrWorkOrder[] = $WorkOrder;
                    $arrFinishGood[] = $FinishGood;
                    $arrCarat[] = $Carat;
                    $arrTotalQty[] = $TotalQty;
                    $arrProduct[] = $Product;
                    $arrQty[] = $Qty;
                    $arrWeight[] = $Weight;
                    $arrStoneLoss[] = $StoneLoss;
                    $arrQtyLossStone[] = $QtyLossStone;
                    $arrBarcodeNote[] = $BarcodeNote;
                    $arrNote[] = $Note;
                    $arrRubberPlate[] = $RubberPlate;
                    $arrProductDetail[] = $ProductDetail;
                    $arrTreeID[] = $TreeID;
                    $arrTreeOrd[] = $TreeOrd;
                    $arrBatchNo[] = $BatchNo;
                    $arrProductPhoto[] = $ProductPhoto;
                    $arrPID[] = $PID;
                    $arrPrevProcess[] = $PrevProcess;
                    $arrPrevOrd[] = $PrevOrd;
                    $arrFG[] = $FG;
                    $arrPart[] = $Part;
                    $arrRID[] = $RID;
                    $arrOID[] = $OID;  
                    $arrOOrd[] = $OOrd;                           
                }
                // dd($arrWorkAllocation);
            
                $goto = array('WorkAllocation' => $arrWorkAllocation, 'Freq' => $arrFreq, 'Ordinal' => $arrOrdinal, 'WorkOrder' => $arrWorkOrder,
                            'FinishGood' => $arrFinishGood, 'Carat' => $arrCarat, 'TotalQty' => $arrTotalQty, 'Product' => $arrProduct,
                            'Qty' => $arrQty, 'Weight' => $arrWeight, 'StoneLoss' => $arrStoneLoss, 'QtyLossStone' => $arrQtyLossStone,
                            'BarcodeNote' => $arrBarcodeNote, 'Note' => $arrNote, 'RubberPlate' => $arrRubberPlate, 'ProductDetail' => $arrProductDetail,
                            'TreeID' => $arrTreeID, 'TreeOrd' => $arrTreeOrd, 'BatchNo' => $arrBatchNo, 'ProductPhoto' => $arrProductPhoto, 
                            'PID' => $arrPID, 'PrevProcess' => $arrPrevProcess, 'PrevOrd' => $arrPrevOrd, 'FG' => $arrFG, 'Part' => $arrPart, 
                            'RID' => $arrRID, 'OID' => $arrOID, 'OOrd' => $arrOOrd, 'baris' => $rowcountHasil, 'CaratID' => $kadar, 'baris' => $rowcountHasil, 'status' => 'sukses'
                            );
            }
        }
        return response()->json($goto);
    }

    public function cetakGambar(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;
        $sw = $request->sw;

        if($location == 12){
            $pembagi = 4;
        }else{
            $pembagi = 3;
        }

        $query = "SELECT 
                    W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                    O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                    0 AS WeightOK
                From 
                    WorkAllocation W
                    Join Employee E On W.Employee = E.ID
                    Join Location L On W.Location = L.ID
                    Join Operation O On W.Operation = O.ID
                    Left Join ProductCarat C On W.Carat = C.ID
                Where W.SW = $sw And W.Freq = 1 ";
        $data = FacadesDB::connection('erp')->select($query);

        foreach ($data as $datas) {}
        $date1 = date("d/m/Y", strtotime($datas->TransDate));

        $query2 = "SELECT A.*,
				O.SW OSW, F.SW FDescription, If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, G.SW GSW
			From WorkAllocationItem A
				Join Product P On A.Product = P.ID
				Left Join ProductCarat C On A.Carat = C.ID
				Left Join WorkOrder O On A.WorkOrder = O.ID
				Left Join ProductCarat T On O.Carat = T.ID
				Left Join Product F On O.Product = F.ID
				Left Join WorkCompletion Z On A.PrevProcess = Z.ID
				Left Join WaxTree W On A.TreeID = W.ID
				Left Join RubberPlate X On W.SW = X.ID
				Left Join Product G On A.FG = G.ID
				Left Join Product H On A.Part = H.ID
			Where A.IDM = $id
			Order By A.Ordinal";
        $data2 = FacadesDB::connection('erp')->select($query2);

        return view('Produksi.PelaporanProduksi.SPKOTambahan.cetakgambar', compact('location','data','data2','pembagi','date1'));
    }

    public function cetak(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;
        $sw = $request->sw;

        // Query Header
        $query = "SELECT 
                    W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                    O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                    CASE
                        WHEN (SIGN(WAR.Shrink) = 1) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) = -1) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) = 0) THEN FORMAT((WAR.CompletionWeight + WAR.Shrink),2)
                        WHEN (SIGN(WAR.Shrink) IS NULL) THEN FORMAT(WAR.CompletionWeight,2)
                    END AS WeightOK, GROUP_CONCAT(EE.SW SEPARATOR ', ') WorkBy
                From 
                    WorkAllocation W
                    Join Employee E On W.Employee = E.ID
                    Join Location L On W.Location = L.ID
                    Join Operation O On W.Operation = O.ID
                    Left Join ProductCarat C On W.Carat = C.ID
                    LEFT JOIN workallocationresult WAR ON WAR.SW=W.SW
                    LEFT JOIN workgroupitem WGI ON W.WorkGroup=WGI.IDM
                    LEFT JOIN employee EE ON WGI.Employee=EE.ID
                Where W.SW = $sw And W.Freq = 1
                ";

        $query2 = "SELECT 
                        W.*, Cast(E.SW As Char) ESW, L.Description LDescription, L.Department, Concat(C.SKU, If(C.Model = 'P', 'P', '-')) SKU,
                        O.Description ODescription, C.Description CSW, ConCat('*', W.SW, '*') Barcode, FORMAT(W.TargetQty,2) TargetQtyWA, W.Weight WWeight,
                        0 AS WeightOK, GROUP_CONCAT(EE.SW SEPARATOR ', ') WorkBy
                    From 
                        WorkAllocation W
                        Join Employee E On W.Employee = E.ID
                        Join Location L On W.Location = L.ID
                        Join Operation O On W.Operation = O.ID
                        Left Join ProductCarat C On W.Carat = C.ID
                        LEFT JOIN workgroupitem WGI ON W.WorkGroup=WGI.IDM
                        LEFT JOIN employee EE ON WGI.Employee=EE.ID
                    Where W.SW = $sw And W.Freq = 1
                    ";

        $querycek = "SELECT ID FROM workallocationresult WHERE SW = $sw ";
        $datacek = FacadesDB::connection('erp')->select($querycek);
        $rowcek = count($datacek);

        if($rowcek > 0) {
            $data = FacadesDB::connection('erp')->select($query);
        }else{
            $data = FacadesDB::connection('erp')->select($query2);
        }
    
        foreach ($data as $datas){}
        $date1 = date("d/m/Y", strtotime($datas->TransDate));
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $datenow = $date->format("d/m/Y");
        $timenow = $date->format("H:i");
        $sisa = $datas->WWeight - $datas->WeightOK;

        // QueryItem
        $queryItem = "SELECT A.*, WA.Purpose,
                        P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                        T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                        Concat(A.TreeID, '-', A.TreeOrd) Tree,
                        If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                        If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.TransDate WADate, W.TransDate PohonDate, Z.WorkAllocation, Z.ID IDD, 
                        CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, CONCAT(P.SW, ' ', C.Description) BarangName, O.RequireDate,
                        CONCAT(Z.WorkAllocation,'-',Z.Freq,'-',WCI.Ordinal) NTHKOBefore, TMI.WorkSchedule IDRPH, WS.TransDate TglRPH, WOI.Remarks NoteMarketing, (A.StoneLoss+A.QtyLossStone) JmlBatu
                    From WorkAllocationItem A
                        Join Product P On A.Product = P.ID
                        Left Join ProductCarat C On A.Carat = C.ID
                        Left Join WorkOrder O On A.WorkOrder = O.ID
                        Left Join ProductCarat T On O.Carat = T.ID
                        Left Join Product F On O.Product = F.ID
                        Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                        Left Join WaxTree W On A.TreeID = W.ID
                        Left Join RubberPlate X On W.SW = X.ID
                        Left Join Product G On A.FG = G.ID
                        Left Join Product H On A.Part = H.ID
                        Left Join WorkAllocation WA ON WA.ID=A.IDM
                        Left Join Employee EM ON EM.ID=WA.Employee
                        LEFT JOIN workcompletionitem WCI ON WCI.IDM=Z.ID AND WCI.Ordinal=A.PrevOrd
                        LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG
                        LEFT JOIN transferrmitem TMI ON TMI.WorkAllocation=Z.WorkAllocation AND TMI.LinkFreq=Z.Freq AND TMI.LinkOrd=WCI.Ordinal
                        LEFT JOIN workschedule WS ON WS.ID=TMI.WorkSchedule
                    Where A.IDM = $id
                    Order By A.Ordinal";
        $dataItem = FacadesDB::connection('erp')->select($queryItem);

        $queryTotal = "SELECT SUM(A.TargetQty) qtySPKO, SUM(A.Weight) weightSPKO, IF(SUM(B.Qty) IS NULL,0,SUM(B.Qty)) qtyNTHKO, IF(SUM(B.Weight) IS NULL,0,SUM(B.Weight)) weightNTHKO
                        FROM workallocation A
                        LEFT JOIN workcompletion B ON A.SW=B.WorkAllocation
                        WHERE A.SW=$sw";
        $dataTotal = FacadesDB::connection('erp')->select($queryTotal);

        return view('Produksi.PelaporanProduksi.SPKOTambahan.cetak', compact('location','data','dataItem','date','date1','datenow','timenow','sisa','username','sw','dataTotal'));
    }

    public function cetakBarcode(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;
        $sw = $request->sw;

        $query = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                    Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.TransDate WADate, W.TransDate PohonDate, Z.workallocation, Z.ID IDD, 
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, WOI.Remarks NoteMarketing
                From WorkAllocationItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    Left Join WorkAllocation WA ON WA.ID=A.IDM
                    Left Join Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG
                Where A.IDM = $id
                Order By A.Ordinal
                ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        return view('Produksi.PelaporanProduksi.SPKOTambahan.cetakbarcode', compact('location','data','username','sw','rowcount'));
    }

    public function cetakBarcodeDirect(Request $request){ //OK

        $location = session('location');
        $username = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $id = $request->id;
        $sw = $request->sw;

        $query = "SELECT A.*,
                    P.Description PDescription, P.SW PSW, C.Description CSW, O.SW OSW, P.UseCarat, F.SW FDescription,
                    T.Description FCarat, O.TotalQty QtyOrder, P.ProdGroup, X.SW RubberPlate, Z.WorkAllocation LinkSW, Z.Freq LinkFreq,
                    Concat(A.TreeID, '-', A.TreeOrd) Tree,
                    If(F.SW = 'RPRS', G.SW, If(A.FG Is Null, H.SW, If(A.Part Is Null, G.SW, If((H.Model = G.Model) And (H.SerialNo = G.SerialNo), G.SW, Concat(H.SW, ' - ', G.SW))))) GDescription,
                    If(F.SW = 'RPRS', G.Photo, If(A.FG Is Null, H.Photo, G.Photo)) GPhoto, WA.TransDate WADate, W.TransDate PohonDate, Z.workallocation, Z.ID IDD, 
                    CONCAT(WA.SW,'-',WA.Freq,'-',A.Ordinal) NoSPKO, G.SW GSW, EM.SW EMSW, WOI.Remarks NoteMarketing
                From WorkAllocationItem A
                    Join Product P On A.Product = P.ID
                    Left Join ProductCarat C On A.Carat = C.ID
                    Left Join WorkOrder O On A.WorkOrder = O.ID
                    Left Join ProductCarat T On O.Carat = T.ID
                    Left Join Product F On O.Product = F.ID
                    Left Join WorkCompletion Z On A.PrevProcess = Z.ID
                    Left Join WaxTree W On A.TreeID = W.ID
                    Left Join RubberPlate X On W.SW = X.ID
                    Left Join Product G On A.FG = G.ID
                    Left Join Product H On A.Part = H.ID
                    Left Join WorkAllocation WA ON WA.ID=A.IDM
                    Left Join Employee EM ON EM.ID=WA.Employee
                    LEFT JOIN workorderitem WOI ON WOI.IDM=A.WorkOrder AND WOI.Product=A.FG
                Where A.IDM = $id
                Order By A.Ordinal
                ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        $returnHTML = view('Produksi.PelaporanProduksi.SPKOTambahan.cetakbarcodedirect', compact('data','rowcount'));

        $pdf = Pdf::loadHtml($returnHTML);
        $customPaper = array(0, 0, 210, 835);
        $pdf->setPaper($customPaper, 'portrait');

        $hasilpdf = $pdf->output();             
        file_put_contents(('C:/LestariERP/ProduksiPDF/'.$id.'.pdf'), $hasilpdf);

        return response()->json( array('success' => true, 'id' => $id) );
    }

    public function listspko(){
        $location = session('location');
        $UserEntry = session('UserEntry');

        if($location == NULL){
            $location = 47;
        }

        $query = "SELECT WA.ID, WA.SW FROM workallocation WA WHERE WA.Location = $location ORDER BY WA.ID DESC LIMIT 100";
        $data = FacadesDB::connection('erp')->select($query);

        return response()->json( array('listspko' => $data) );
    }

    public function cariSPK(Request $request){ //OK
        $sw = $request->sw;
        $carat = $request->carat;

        if($sw == 'Stock' || $sw == 'stock' || $sw == 'STOCK'){
            $sw = 'Stock';
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW = '$sw' AND A.ACTIVE='A' ";
        }else if($sw == 'OStock' || $sw == 'ostock' || $sw == 'OSTOCK'){
            $sw = 'OStock';
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW = '$sw' AND A.ACTIVE='A' ";
        }else{
            $query = "SELECT 
                        A.*, B.SW ProductName, C.DESCRIPTION CaratName
                    FROM WORKORDER A 
                        JOIN PRODUCT B ON A.PRODUCT=B.ID
                        LEFT JOIN PRODUCTCARAT C ON A.CARAT=C.ID
                    WHERE A.SW LIKE '%$sw%' AND A.ACTIVE='A' AND A.CARAT=$carat ";
        }
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){

            foreach ($data as $datas){}
            $NoSPK = $datas->SW;
            $WorkOrder = $datas->ID;
            $TotalQty = $datas->TotalQty;
            $ProductName = $datas->ProductName;

            $queryCarat = "SELECT * FROM productcarat WHERE ID=$carat ";
            $data2 = FacadesDB::connection('erp')->select($queryCarat);
            foreach ($data2 as $datas2){}
            $CaratID = $datas2->ID;
            $CaratName = $datas2->Description;

            $dataReturn = array('NoSPK' => $NoSPK, 'WorkOrder' => $WorkOrder, 'TotalQty' => $TotalQty, 'ProductName' => $ProductName, 'Carat' => $CaratID, 'Kadar' => $CaratName, 'rowcount' => $rowcount);

        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }

    public function cariProduct(Request $request){ //OK
        $sw = $request->sw;
        $carat = $request->carat;
    
        $dataCarat = FacadesDB::connection('erp')->select("SELECT * FROM productcarat WHERE ID=$carat");
        foreach ($dataCarat as $datasCarat){}
        $caratID = $datasCarat->ID;
        $caratName = $datasCarat->Description;
     
        $query = "SELECT * FROM PRODUCT WHERE TYPE IN ('S','R','F') AND USECARAT IN ('Y','N') AND STOCKUNIT<>0 AND SW = '$sw' ";
        $data = FacadesDB::connection('erp')->select($query);
        $rowcount = count($data);

        if($rowcount > 0){
            foreach ($data as $datas){}
            
            $Product = $datas->ID;
            $ProductLabel = $datas->Description;
            $UseCarat = $datas->UseCarat;

            $dataReturn = array('rowcount' => $rowcount, 'Product' => $Product, 'ProductLabel' => $ProductLabel, 'UseCarat' => $UseCarat, 'caratID' => $caratID, 'caratName' => $caratName);
        }else{
            $dataReturn = array('rowcount' => $rowcount);
        }

        return response()->json($dataReturn);
    }
 
}
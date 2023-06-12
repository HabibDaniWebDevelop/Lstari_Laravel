<?php

namespace App\Http\Controllers\Inventori\MaterialRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Public_Function_Controller;

// Models
use App\Models\erp\materialrequest;
use App\Models\erp\materialrequestitem;
use App\Models\erp\materialrequestrecap;
use App\Models\erp\lastid;

class MRBahanPembantuController extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    // START REUSABLE FUNCTION
    private function SetReturn($success, $message, $data, $error)
    {
        $data_return = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ];
        return $data_return;
    }

    private function GetEmployee($keyword)
    {
        $employee = FacadesDB::connection('erp')
            ->table('Employee AS E')
            ->join('Department AS D', function ($join) {
                $join->on('E.Department', '=', 'D.ID');
            })
            ->selectRaw(
                "
            E.ID,
            E.Description NAME,
            D.Description Bagian,
            E.Department,
            E.WorkRole,
            E.Rank
        ",
            )
            ->where('E.SW', '=', "$keyword")
            ->orWhere('E.ID', '=', '' . $keyword)
            ->orderBy('E.Department', 'ASC')
            ->get();
        return $employee;
    }
    // END REUSABLE FUNCTION

    public function Index(Request $request)
    {
        $data = rtrim($request->id, ',');
        $id_array = explode(',', $data);
        $loc = $request->loc;
        
        // dd($loc);

        $tablename = 'materialrequest';
        $UserID = session('iduser');
        $Module = '191';
        $ListUserHistory = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);
        // dd($ListUserHistory);

        foreach ($id_array as $key => $id) {
            $transfer[] = $id;
            $lupping = $key + 1;
        }
        if ($data != '') {
            $transfercek = $lupping;
        } else {
            $transfercek = '0';
        }

        // if($loc != null){
        //     $fillloc = "AND Location = '$loc'";
        // } else{$fillloc ="";}
        $fillloc = "";

        // Get Employee
        $employee = $this->GetEmployee(Auth::user()->name);
        // $employee = $this->GetEmployee('Niko');
        $employee = $employee[0];
        $idDepartment = $employee->Department;
        $datenow = date('Y-m-d');

        // Get barang stock
        $barangStock = FacadesDB::connection('erp')->select("
            SELECT
                P.ID,
                P.SW,
                P.Description,
                CONCAT(ROUND(P.Stock, 2),' ',U.SW) Unit,
                U.ID UID,
                0 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchaseDepartment D
                JOIN ProductPurchase P ON D.ID = P.ID
                AND P.Active = 'Y'
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                D.Department = '$idDepartment' UNION
            SELECT
                P.ID,
                P.SW,
                P.Description,
                CONCAT(ROUND(P.Stock, 2),' ',U.SW) Unit,
                U.ID UID,
                1 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchase P
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                P.Active = 'Y'
                $fillloc
                AND P.ID NOT IN ( SELECT ID FROM ProductPurchaseDepartment WHERE Department = '$idDepartment' )
            ORDER BY
                Priority,
                Description
        ");

        $location = $request->session()->get('location');
        // $location = '22';
        // dd($location);

        // cheking koneksi erp next
        $url = 'http://erpnext.lestarigold.co.id';

        // Check server connection
        $server_headers = @get_headers($url);
        if ($server_headers === false) {
            // Server is down or not reachable
            echo " <h1 style='color:red;'> Server erp next is down or not reachable. <br> Mohon hubungi Tim IT </h1> ";
            exit();
        }

        // Check URL validity
        $url_headers = @get_headers($url);
        if ($url_headers === false) {
            // URL is invalid or not reachable
            echo ' <h1> URL is invalid or not reachable </h1> ';
            exit();
        }

        $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
        //ambil data dari erp next
        $response1 = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('http://erpnext.lestarigold.co.id/api/resource/Item?fields=["name","idproduct","item_name"]&filters=[["is_stock_item","=","0"],["item_group_parent","=","Pembelian"]]&order_by=name&limit=0');

        $response = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('erpnext.lestarigold.co.id/api/resource/Warehouse?fields=["name","id_warehouse"]&filters=[["parent_warehouse","=","Inventory - LMS"]]&order_by=name&limit=100');

        // $UserID = 139;
        $response2 = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('erpnext.lestarigold.co.id/api/resource/Employee?filters=[["id_employee","=","'. $UserID.'"]]');

        if ($response->successful()) {
            $tujuan = $response->json();
            $tujuan = $tujuan['data'];

            $nonstok = $response1->json();
            $nonstok = $nonstok['data'];

            $iderpn = $response2->json(); 
            $iderpn = $iderpn['data'][0]['name'];

            // dd($nonstok);
            session()->put('tujuan', $tujuan);
            session()->put('iderpn', $iderpn);
        } else {
            $status = $response->status();
            $message = $response->json()['message'];
            // Handle the error here
            dd("Error: $status - $message");
        }

        // dd($tujuan);

        return view('Inventori.MaterialRequest.BahanPembantu.index', compact('datenow', 'employee', 'transfer', 'transfercek', 'barangStock', 'tujuan', 'location', 'ListUserHistory', 'loc', 'nonstok'));
    }

    public function cari(Request $request)
    {
        $id = $request->id;

        //masukan ke user history
        $UserID = $request->session()->get('iduser');
        $Module = '191';
        $ID_Field = $id;
        $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $ID_Field);

        // dd($request->id);

        $header = FacadesDB::connection('erp')->select(" SELECT
                mr.Employee idEmployee,
                e.Description `nama`,
                mr.Department,
                d.Description Bagian,
                mr.TransDate,
                mr.Remarks,
                mr.Active,
                d.Location
            FROM
                `materialrequest` AS mr
                INNER JOIN employee AS e ON mr.Employee = e.ID
                INNER JOIN department AS d ON mr.Department = d.ID
            WHERE
                mr.ID = '$id'
        ");
        $items = FacadesDB::connection('erp')->select(" SELECT
                        m.*, p.SW, p.Location
                    FROM
                        `materialrequestitem` as m
                        LEFT JOIN productpurchase p ON p.ID = m.Product
                    WHERE
                        m.IDM = '$id'
            ");

        // $header[0]->Department = '29';

        // dd($items);
        // Get barang stock
        $barangStock = FacadesDB::connection('erp')->select(
            "
            SELECT
                P.ID,
                P.SW,
                P.Description,
                CONCAT(ROUND(P.Stock, 2),' ',U.SW) Unit,
                U.ID UID,
                0 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchaseDepartment D
                JOIN ProductPurchase P ON D.ID = P.ID
                AND P.Active = 'Y'
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                D.Department = '" .
                $header[0]->Department .
                "' UNION
            SELECT
                P.ID,
                P.SW,
                P.Description,
                CONCAT(ROUND(P.Stock, 2),' ',U.SW) Unit,
                U.ID UID,
                1 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchase P
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
            P.Active = 'Y'
                AND P.ID NOT IN ( SELECT ID FROM ProductPurchaseDepartment WHERE Department = '" .
                $header[0]->Department .
                "' )
            ORDER BY
                Priority,
                Description
        ",
        );

        $satuan = FacadesDB::connection('erp')->select("
            SELECT
                ID,
                SW
            FROM
                UNIT
            WHERE
                ID > 0
                AND TYPE IN ( 'W', 'V', 'U', 'H' ) 
            ORDER BY SW
        ");

        $proses = FacadesDB::connection('erp')->select(
            "
            SELECT
                ID,
                Description
            FROM
                OperationUsage
            WHERE
                ID > 0
                AND Department = '" .
                $header[0]->Department .
                "'
        ",
        );

        $location = $header[0]->Location;
        // $location = '26';
        // dd($location);

        $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
        //ambil data dari erp next
        $response1 = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('http://erpnext.lestarigold.co.id/api/resource/Item?fields=["name","idproduct","item_name"]&filters=[["is_stock_item","=","0"],["item_group_parent","=","Pembelian"]]&order_by=name&limit=0');

        $nonstok = $response1->json();
        $nonstok = $nonstok['data'];
        // $response = Http::withHeaders([
        //     'Authorization' => 'token ' . $token,
        //     'Accept' => 'application/json',
        // ])->get('erpnext.lestarigold.co.id/api/resource/Material%20Request/' . $header[0]->Remarks);

        // if ($response->successful()) {
        //     $tujuan = $response->json();
        //     $tujuan = $tujuan['data']['set_warehouse'];
        // } else {
        //     $tujuan = '';
        // }

        return view('Inventori.MaterialRequest.BahanPembantu.show', compact('barangStock', 'location', 'header', 'items', 'satuan', 'proses','nonstok'))->render();
    }
    public function cetak(Request $request)
    {
        abort(503);
        $id = $request->id;
        dd($id, $request);
    }

    public function LockGudang(Request $request)
    {
        $tujuan = session()->get('tujuan');
        $iddept = session('iddept');
        $id = '';
        foreach ($tujuan as $item) {
            if ($item['name'] == $request->id) {
                $id = $item['id_warehouse'];
                break;
            }
        }

        // Get barang stock
        $barangStock = FacadesDB::connection('erp')->select("
            SELECT
                P.ID,
                P.SW,
                P.Description,
                CONCAT(ROUND(P.Stock, 2),' ',U.SW) Unit,
                U.ID UID,
                0 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchaseDepartment D
                JOIN ProductPurchase P ON D.ID = P.ID
                AND P.Active = 'Y'
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                D.Department = '$iddept' UNION
            SELECT
                P.ID,
                P.SW,
                P.Description,
                CONCAT(ROUND(P.Stock, 2),' ',U.SW) Unit,
                U.ID UID,
                1 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchase P
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                P.Active = 'Y'
                AND Location = '$id'
                AND P.ID NOT IN ( SELECT ID FROM ProductPurchaseDepartment WHERE Department = '$iddept' )
            ORDER BY
                Priority,
                Description
        ");

        return view('Inventori.MaterialRequest.BahanPembantu.LockGudang', compact('barangStock'));
    }

    public function generateNewRow(Request $request)
    {
        $index = $request->index;
        // GetEmployee
        $username = Auth::user()->name;
        // $username = 'Sugiono';

        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];
        $idDepartment = $employee->Department;
        // $idDepartment = '29';

        if ($username == 'Niko' || $username == 'Adji' || $username == 'Sugiono' || $username == 'Supratno' || $username == 'Ferri') {
            $filter = '';
        } else{
            $filter = "AND Department = '$idDepartment'";
        }

        // Membaca file JSON
        // $jsonData = file_get_contents(public_path('assets\temp\mrbarang.json'));
        // $barangStock = json_decode($jsonData);

        $satuan = FacadesDB::connection('erp')->select("
            SELECT
                ID,
                SW
            FROM
                UNIT
            WHERE
                ID > 0
                AND TYPE IN ( 'W', 'V', 'U', 'H' ) 
            ORDER BY SW
        ");

        $proses = FacadesDB::connection('erp')->select("
            SELECT
                ID,
                Description
            FROM
                OperationUsage
            WHERE
                ID > 0
                $filter
                Order By Description
        ");

        // dd($index,$barangStock,$satuan,$proses);

        $rowHTML = view('Inventori.MaterialRequest.BahanPembantu.rowtable', compact('index', 'satuan', 'proses'))->render();
        $data_return = $this->SetReturn('success', 'Generate Row Success', $rowHTML, null);
        return response()->json($data_return, 200);
    }

    public function getBarang(Request $request)
    {
        $idBarang = $request->idBarang;
        if (is_null($idBarang) or $idBarang == '') {
            $data_return = $this->SetReturn(false, "idBarang can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        // GetEmployee for idDepartment
        $employee = $this->GetEmployee(Auth::user()->name);
        $employee = $employee[0];
        $idDepartment = $employee->Department;

        // Get Barang
        $barang = FacadesDB::connection('erp')->select("
            SELECT
                P.ID,
                P.SW,
                P.Description,
                P.Stock,
                U.SW Unit,
                U.ID UID,
                0 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchaseDepartment D
                JOIN ProductPurchase P ON D.ID = P.ID
                AND P.Active = 'Y'
                AND ( P.ID = '$idBarang' OR P.SW = '$idBarang' OR P.Description = '$idBarang' )
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                D.Department = '$idDepartment' UNION
            SELECT
                P.ID,
                P.SW,
                P.Description,
                P.Stock,
                U.SW Unit,
                U.ID UID,
                1 Priority,
                X.Description ProdGroup,
                L.Description Location
            FROM
                ProductPurchase P
                JOIN ShortText X ON P.ProdGroup = X.ID
                LEFT JOIN Unit U ON P.Unit = U.ID
                LEFT JOIN Location L ON P.Location = L.ID
            WHERE
                ( P.ID = '$idBarang' OR P.SW = '$idBarang' OR P.Description = '$idBarang' )
                AND P.Active = 'Y'
                AND P.ID NOT IN ( SELECT ID FROM ProductPurchaseDepartment WHERE Department = '$idDepartment' )
            ORDER BY
                Priority,
                Description
        ");

        // dd($barang);

        $data_return = $this->SetReturn(true, 'Success', $barang, null);
        return response()->json($data_return, 200);
    }

    public function saveMRBahanPembantu(Request $request)
    {
        // dd($request);
        $idEmployee = $request->idEmployee;
        $idDepartment = $request->idDepartment;
        $tanggal = $request->tanggal;
        $catatan = $request->catatan;
        $items = $request->items;
        // $GudangT = $request->GudangT;

        // Check idEmployee, idDepartment, tanggal, and items value is required. so that can't be null. and items must be array because that contains MR items
        if (is_null($idEmployee) or $idEmployee == '') {
            $data_return = $this->SetReturn(false, "idEmployee can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        if (is_null($idDepartment) or $idDepartment == '') {
            $data_return = $this->SetReturn(false, "idDepartment can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        if (is_null($tanggal) or $tanggal == '') {
            $data_return = $this->SetReturn(false, "tanggal can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false, "items can't be null and must array", null, null);
            return response()->json($data_return, 400);
        }

        // Loop items for check key and value is correct or not.
        foreach ($items as $key => $value) {
            // Check item key
            if (array_key_exists('barang', $value) and array_key_exists('jenisBarang', $value) and array_key_exists('jumlah', $value) and array_key_exists('kategori', $value) and array_key_exists('keperluan', $value) and array_key_exists('keterangan', $value) and array_key_exists('proses', $value) and array_key_exists('ulang', $value) and array_key_exists('unit', $value)) {
                // Check required item key value
                if (is_null($value['barang']) or $value['barang'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. Barang in items cant be null or blank", null, ['items' => ['barang' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['jenisBarang']) or $value['jenisBarang'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. jenisBarang in items cant be null or blank", null, ['items' => ['jenisBarang' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (!in_array($value['jenisBarang'], [1, 2])) {
                    $data_return = $this->SetReturn(false, "Can't save. jenisBarang in items Must Be 1(Barang Stock) or 2(Barang Non Stock)", null, ['items' => ['jenisBarang' => 'Must Be 1 or 2']]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['jumlah']) or $value['jumlah'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. jumlah in items cant be null or blank", null, ['items' => ['jumlah' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['unit']) or $value['unit'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. unit in items cant be null or blank", null, ['items' => ['unit' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['keperluan']) or $value['keperluan'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. keperluan in items cant be null or blank", null, ['items' => ['keperluan' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['kategori']) or $value['kategori'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. kategori in items cant be null or blank", null, ['items' => ['kategori' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['ulang']) or $value['ulang'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. ulang in items cant be null or blank", null, ['items' => ['ulang' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                // All checking items success
            }
        }

        // dd($items);

        // All Checking Success
        // Get lastid
        $lastid = lastid::where('module', 'MaterialRequest')->first();
        $lastid = $lastid->Last + 1;
        $update_lastid = lastid::where('Module', 'MaterialRequest')->update([
            'Last' => $lastid,
        ]);

        // Insert to materialrequest
        $insertMaterialRequest = materialrequest::create([
            'ID' => $lastid,
            'UserName' => Auth::user()->name,
            'Remarks' => $catatan,
            'TransDate' => now(),
            'Department' => $idDepartment,
            'Employee' => $idEmployee,
            'Purpose' => 'M',
            'Active' => 'A',
        ]);

        // dd($items);

        // Loop items to insert materialrequestitem

        $cek1 = 0;
        $cek2 = 0;
        $items_send = [];
        $items_send2 = [];
        foreach ($items as $key => $value) {
            if ($value['proses'] == null) {
                $value['proses'] = $idDepartment;
            }
            if ($value['jenisBarang'] == 1) {
                $insertMaterialRequestItem = materialrequestitem::create([
                    'IDM' => $lastid,
                    'Ordinal' => $key + 1,
                    'Product' => $value['barang'],
                    'ProductNote' => null,
                    'Qty' => $value['jumlah'],
                    'QtyBuy' => 0,
                    'Purpose' => $value['keperluan'],
                    'Note' => $value['keterangan'],
                    'Department' => $value['proses'],
                    'ProductType' => null,
                    'Purchase' => 'N',
                    'WorkshopOrder' => null,
                    'WorkshopItem' => null,
                    'Category' => $value['kategori'],
                    'ReBuy' => $value['ulang'],
                    'QtyUse' => $value['jumlah'],
                    'unit' => $value['unit'],
                ]);

                $items_send[] = [
                    'item_code' => $value['sw'],
                    'qty' => $value['jumlah'],
                    'uom' => $value['uom'],
                    'conversion_factor' => 1,
                    'keterangan'=> $value['keterangan'],
                ];

                $cek1 = 1;

            } else {
                $insertMaterialRequestItem = materialrequestitem::create([
                    'IDM' => $lastid,
                    'Ordinal' => $key + 1,
                    'Product' => null,
                    'ProductNote' => $value['barang'],
                    'Qty' => $value['jumlah'],
                    'QtyBuy' => $value['jumlah'],
                    'Purpose' => $value['keperluan'],
                    'Note' => $value['keterangan'],
                    'Department' => $value['proses'],
                    'ProductType' => null,
                    'Purchase' => 'N',
                    'WorkshopOrder' => null,
                    'WorkshopItem' => null,
                    'Category' => $value['kategori'],
                    'ReBuy' => $value['ulang'],
                    'QtyUse' => $value['jumlah'],
                    'unit' => $value['unit'],
                    'ProductNonStock' => $value['kodeNonStock'],
                ]);

                $items_send2[] = [
                    'item_code' => $value['kodeNonStock'],
                    'qty' => $value['jumlah'],
                    'description' => $value['barang'],
                    'uom' => $value['uom'],
                    'conversion_factor' => 1,
                    'keterangan'=> $value['keterangan'],
                ];
                $cek2 = 1;
                
            }
        }

        // dd($$request);
        // dd($insertMaterialRequestItem);

        // Get materialrequestitem and calculate it for insert to materialrequestrecap (barang non stok yang akan masuk ke tabel tersebut)
        $getMRItem = FacadesDB::connection('erp')->select("
            SELECT
                Product,
                IDM,
                Ordinal,
                ProductNote,
                Sum( Qty ) Qty
            FROM
                materialrequestitem
            WHERE
                IDM = '$lastid'
                AND ProductNote IS NOT NULL
            GROUP BY
                Product,
                ProductNote
        ");

        // loop and insert it to materialrequestrecap
        // foreach ($getMRItem as $key => $value) {
        //     $insertMaterialRecap = materialrequestrecap::create([
        //         'IDM' => $lastid,
        //         'Ordinal' => $key + 1,
        //         'Product' => $value->Product,
        //         'ProductNote' => $value->ProductNote,
        //         'Qty' => $value->Qty,
        //         'QtyBuy' => $value->Qty,
        //     ]);
        // }

        // dd($getMRItem);

        $insertProductPurchaseDepartment = FacadesDB::connection('erp')->insert(" INSERT INTO ProductPurchaseDepartment (ID, Department)
                SELECT DISTINCT Product, Department
                FROM MaterialRequestItem
                WHERE Product IS NOT NULL
                AND Department <> 0
                AND IDM = '{$lastid}'
                AND (Product, Department) NOT IN (
                    SELECT ID AS Product, Department
                    FROM ProductPurchaseDepartment
                )
            ");

        $insertProductPurchaseDepartment = FacadesDB::insert(" INSERT INTO ProductPurchaseDepartment (ID, Department)
                SELECT DISTINCT Product, Department
                FROM erp.MaterialRequestItem
                WHERE Product IS NOT NULL
                AND Department <> 0
                AND IDM = '{$lastid}'
                AND (Product, Department) NOT IN (
                    SELECT ID AS Product, Department
                    FROM ProductPurchaseDepartment
                )
            ");

        //masukan ke user history
        $UserID = session('iduser');
        $iderpn = session('iderpn');
        $Module = '191';
        $ID_Field = $lastid;
        $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $ID_Field);

        $tgl = date('Y-m-d');
        $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
        $Remark = '';

        // dd($cek1, $cek2);
        if($cek1 == 1){

            $data = [
                'naming_series' => 'MAT-MR-.YYYY.-',
                'material_request_type' => 'Material Issue',
                "employee_id" => $iderpn,
                'transaction_date' => $tgl,
                'schedule_date' => $tanggal,
                'company' => 'Lestari Mulia Sentosa',
                // 'set_warehouse' => $GudangT,
                'items' => $items_send,
            ];

            //dd($data);
            //die(print_r($data));

            $response = Http::withHeaders([
                'Authorization' => 'token ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('erpnext.lestarigold.co.id/api/resource/Material Request', $data);
            //dd($response);

            if ($response->successful()) {
                $responerpnext = $response->json();
                $Remarks = $responerpnext['data']['name'];
                // $Remark = $Remark.', '.$Remarks;
                $Remark = $Remarks;

                } else {
                    // jika permintaan gagal
                    // $responerpnext = $response->json();
                    // dd($responerpnext);
                    $data_return = $this->SetReturn(false, 'Terjadi Masalah Pada ERP NEXT', ['ID' => $lastid], null);
                    return response()->json($data_return, 200);
                }
            }
        if ($cek2 == 1){

            $data2 = [
                'naming_series' => 'MAT-MR-.YYYY.-',
                'material_request_type' => 'Purchase',
                "employee_id" => $iderpn,
                'transaction_date' => $tgl,
                'schedule_date' => $tanggal,
                'company' => 'Lestari Mulia Sentosa',
                // 'set_warehouse' => $GudangT,
                'items' => $items_send2,
            ];

            $response2 = Http::withHeaders([
                'Authorization' => 'token ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('erpnext.lestarigold.co.id/api/resource/Material Request', $data2);

            if ($response2->successful()) {
                $responerpnext2 = $response2->json();
                $Remarks2 = $responerpnext2['data']['name'];

                //$Remark = $Remark.', '.$Remarks2;
                $Remark = $Remarks2;

            } else {
                // jika permintaan gagal

                $responerpnext = $response2->json();
                    dd($responerpnext);
                $data_return = $this->SetReturn(false, 'Terjadi Masalah Pada ERP NEXT2', ['ID' => $lastid], null);
                return response()->json($data_return, 200);
            }

        }

        $update_workshoporderitem = materialrequest::where('ID', $lastid)
        // jika permintaan berhasil
        ->update([
            'Remarks' => $Remark,
        ]);

        // dd($response);
        // dd($insertProductPurchaseDepartment);

        // COMPLETE. Return success
        $data_return = $this->SetReturn(true, 'Create MR Bahan Pembantu Success', ['ID' => $lastid], null);
        return response()->json($data_return, 200);
    }

    public function update(Request $request)
    {
        // dd($request);
        $lastid = $request->id;
        $idEmployee = $request->idEmployee;
        $idDepartment = $request->idDepartment;
        $tanggal = $request->tanggal;
        $catatan = $request->catatan;
        $items = $request->items;
        // $GudangT = $request->GudangT;

        if (is_null($tanggal) or $tanggal == '') {
            $data_return = $this->SetReturn(false, "tanggal can't be null or blank", null, null);
            return response()->json($data_return, 400);
        }

        if (is_null($items) or !is_array($items)) {
            $data_return = $this->SetReturn(false, "items can't be null and must array", null, null);
            return response()->json($data_return, 400);
        }

        // Loop items for check key and value is correct or not.
        foreach ($items as $key => $value) {
            // Check item key
            if (array_key_exists('barang', $value) and array_key_exists('jenisBarang', $value) and array_key_exists('jumlah', $value) and array_key_exists('kategori', $value) and array_key_exists('keperluan', $value) and array_key_exists('keterangan', $value) and array_key_exists('proses', $value) and array_key_exists('ulang', $value) and array_key_exists('unit', $value)) {
                // Check required item key value
                if (is_null($value['barang']) or $value['barang'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. Barang in items cant be null or blank", null, ['items' => ['barang' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['jenisBarang']) or $value['jenisBarang'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. jenisBarang in items cant be null or blank", null, ['items' => ['jenisBarang' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (!in_array($value['jenisBarang'], [1, 2])) {
                    $data_return = $this->SetReturn(false, "Can't save. jenisBarang in items Must Be 1(Barang Stock) or 2(Barang Non Stock)", null, ['items' => ['jenisBarang' => 'Must Be 1 or 2']]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['jumlah']) or $value['jumlah'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. jumlah in items cant be null or blank", null, ['items' => ['jumlah' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['unit']) or $value['unit'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. unit in items cant be null or blank", null, ['items' => ['unit' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['keperluan']) or $value['keperluan'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. keperluan in items cant be null or blank", null, ['items' => ['keperluan' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['kategori']) or $value['kategori'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. kategori in items cant be null or blank", null, ['items' => ['kategori' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                if (is_null($value['ulang']) or $value['ulang'] == '') {
                    $data_return = $this->SetReturn(false, "Can't save. ulang in items cant be null or blank", null, ['items' => ['ulang' => "Can't be null or blank. Required"]]);
                    return response()->json($data_return, 400);
                }
                // All checking items success
            }
        }

        // update to materialrequest
        $update_materialrequest = materialrequest::where('ID', $lastid)->update([
            'TransDate' => $tanggal,
        ]);

        //menghapus materialrequestitem
        $deletematerialrequestitem = materialrequestitem::where('IDM', $lastid)->delete();

        // Loop items to insert materialrequestitem
        $items_send = [];
        foreach ($items as $key => $value) {
            if ($value['proses'] == null) {
                $value['proses'] = $idDepartment;
            }

            if ($value['jenisBarang'] == 1) {
                $insertMaterialRequestItem = materialrequestitem::create([
                    'IDM' => $lastid,
                    'Ordinal' => $key + 1,
                    'Product' => $value['barang'],
                    'ProductNote' => null,
                    'Qty' => $value['jumlah'],
                    'QtyBuy' => 0,
                    'Purpose' => $value['keperluan'],
                    'Note' => $value['keterangan'],
                    'Department' => $value['proses'],
                    'ProductType' => null,
                    'Purchase' => 'N',
                    'WorkshopOrder' => null,
                    'WorkshopItem' => null,
                    'Category' => $value['kategori'],
                    'ReBuy' => $value['ulang'],
                    'QtyUse' => $value['jumlah'],
                    'unit' => $value['unit'],
                ]);

                //mengisi item untuk erpnext
                $items_send[] = [
                    'item_code' => $value['sw'],
                    'qty' => $value['jumlah'],
                    'uom' => $value['uom'],
                    'conversion_factor' => 1,
                ];
            } else {
                $insertMaterialRequestItem = materialrequestitem::create([
                    'IDM' => $lastid,
                    'Ordinal' => $key + 1,
                    'Product' => null,
                    'ProductNote' => $value['barang'],
                    'Qty' => $value['jumlah'],
                    'QtyBuy' => $value['jumlah'],
                    'Purpose' => $value['keperluan'],
                    'Note' => $value['keterangan'],
                    'Department' => $value['proses'],
                    'ProductType' => null,
                    'Purchase' => 'N',
                    'WorkshopOrder' => null,
                    'WorkshopItem' => null,
                    'Category' => $value['kategori'],
                    'ReBuy' => $value['ulang'],
                    'QtyUse' => $value['jumlah'],
                    'unit' => $value['unit'],
                ]);
            }

        }

        // dd($$request);

        // Get materialrequestitem and calculate it for insert to materialrequestrecap (barang non stok yang akan masuk ke tabel tersebut)
        $getMRItem = FacadesDB::connection('erp')->select("
            SELECT
                Product,
                IDM,
                Ordinal,
                ProductNote,
                Sum( Qty ) Qty
            FROM
                materialrequestitem
            WHERE
                IDM = '$lastid'
                AND ProductNote IS NOT NULL
            GROUP BY
                Product,
                ProductNote
        ");

        //menghapus materialrequestrecap
        // $deletematerialrequestrecap = materialrequestrecap::where('IDM', $lastid)->delete();

        // // loop and insert it to materialrequestrecap
        // foreach ($getMRItem as $key => $value) {
        //     $insertMaterialRecap = materialrequestrecap::create([
        //         'IDM' => $lastid,
        //         'Ordinal' => $key + 1,
        //         'Product' => $value->Product,
        //         'ProductNote' => $value->ProductNote,
        //         'Qty' => $value->Qty,
        //         'QtyBuy' => $value->Qty,
        //     ]);
        // }

        // dd($getMRItem);

        $insertProductPurchaseDepartment = FacadesDB::connection('erp')->insert(" INSERT INTO ProductPurchaseDepartment (ID, Department)
                SELECT DISTINCT Product, Department
                FROM MaterialRequestItem
                WHERE Product IS NOT NULL
                AND Department <> 0
                AND IDM = '{$lastid}'
                AND (Product, Department) NOT IN (
                    SELECT ID AS Product, Department
                    FROM ProductPurchaseDepartment
                )
            ");

        $insertProductPurchaseDepartment = FacadesDB::insert(" INSERT INTO ProductPurchaseDepartment (ID, Department)
                SELECT DISTINCT Product, Department
                FROM erp.MaterialRequestItem
                WHERE Product IS NOT NULL
                AND Department <> 0
                AND IDM = '{$lastid}'
                AND (Product, Department) NOT IN (
                    SELECT ID AS Product, Department
                    FROM ProductPurchaseDepartment
                )
            ");

        //masukan ke user history
        $UserID = $request->session()->get('iduser');
        $Module = '191';
        $ID_Field = $lastid;
        $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $ID_Field);

        $data = [
            'naming_series' => 'MAT-MR-.YYYY.-',
            'material_request_type' => 'Material Issue',
            'schedule_date' => $tanggal,
            'company' => 'Lestari Mulia Sentosa',
            'doctype' => 'Material Request',
            'docstatus' => 0,
            // 'set_warehouse' => $GudangT,
            'items' => $items_send,
        ];

        // dd($data);

        $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
        $response = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->put('erpnext.lestarigold.co.id/api/resource/Material Request/' . $catatan, $data);

        if ($response->successful()) {
            $responerpnext = $response->json();
            $Remarks = $responerpnext['data']['name'];

            $update_workshoporderitem = materialrequest::where('ID', $lastid)
                // jika permintaan berhasil
                ->update([
                    'Remarks' => $Remarks,
                ]);
        } else {
            // jika permintaan gagal
            $data_return = $this->SetReturn(false, 'Terjadi Masalah Pada ERP NEXT', ['ID' => $lastid], null);
            return response()->json($data_return, 200);
        }

        // dd($response);

        // dd($insertProductPurchaseDepartment);

        // COMPLETE. Return success
        $data_return = $this->SetReturn(true, 'Update MR Bahan Pembantu Success', ['ID' => $lastid], null);
        return response()->json($data_return, 200);
    }
}

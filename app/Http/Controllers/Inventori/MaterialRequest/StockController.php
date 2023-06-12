<?php

namespace App\Http\Controllers\Inventori\MaterialRequest;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Http\Controllers\Public_Function_Controller;

// Models
use App\Models\erp\lastid;
use App\Models\erp\materialrequest;
use App\Models\erp\materialrequestitem;
// use App\Models\tes_laravel\lastid;
// use App\Models\tes_laravel\materialrequest;
// use App\Models\tes_laravel\materialrequestitem;


class StockController extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function Index(Request $request)
    {
        $iddept = session('iddept');

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

        //menyimpan List User History
        $tablename = 'materialrequest';
        $UserID = session('iduser');
        $Module = '192';
        $ListUserHistory = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);

        $satuan = FacadesDB::connection('erp')->select("
                    SELECT
                        ID,
                        SW
                    FROM
                        UNIT
                    WHERE
                        ID > 0
                        AND TYPE IN ( 'W', 'V', 'U', 'H' )
                ");

        $barangStock = FacadesDB::connection('erp')->select(
            " SELECT
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
                        AND P.ID NOT IN ( SELECT ID FROM ProductPurchaseDepartment WHERE Department = '$iddept' )
                    ORDER BY
                        Priority,
                        Description
                ",
        );

        $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
        $response = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('erpnext.lestarigold.co.id/api/resource/Warehouse?fields=["name","id_warehouse"]&filters=[["parent_warehouse","=","Inventory - LMS"]]&order_by=name&limit=100');

        // $UserID = 1854;
        $response2 = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('erpnext.lestarigold.co.id/api/resource/Employee?filters=[["id_employee","=","' . $UserID . '"]]');

        if ($response->successful()) {
            $tujuan = $response->json();
            $tujuan = $tujuan['data'];
            $iderpn = $response2->json();
            $iderpn = $iderpn['data'][0]['name'];
            // dd($iderpn);
            session()->put('tujuan', $tujuan);
            session()->put('iderpn', $iderpn);
        } else {
            $status = $response->status();
            $message = $response->json()['message'];
            // Handle the error here
            dd("Error: $status - $message");
        }

        return view('Inventori.MaterialRequest.Stock.index', compact('ListUserHistory', 'satuan', 'barangStock'));
    }

    public function show(Request $request)
    {
        if ($request->menu == 'lihat') {
            $id = $request->id;
            $menu = $request->menu;
            $iddept = session('iddept');
            //masukan ke user history
            $UserID = $request->session()->get('iduser');
            $Module = '192';
            $ID_Field = $id;
            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $ID_Field);

            $header = FacadesDB::connection('erp')->select("SELECT
                        A.*,
                        E.Description NAME,
                        G.Description NAMEDEP
                    FROM
                        materialrequest A
                        JOIN Employee E ON A.Employee = E.ID
                        JOIN Department G ON G.ID = A.Department
                    WHERE
                        A.ID =  '$id'
                ");

            $items = FacadesDB::connection('erp')->select("SELECT
                        I.*,
                        CONCAT( p.ID, ' - ', P.Description ) PDescription,
                        U.SW uom,
                        p.SW,
                        P.Location
                    FROM
                        MaterialRequestItem I
                        LEFT JOIN ProductPurchase P ON I.Product = P.ID
                        LEFT JOIN Unit U ON I.Unit = U.ID 
                    WHERE
                        I.IDM = '$id'
                    ORDER BY
                        I.Ordinal
                    ");

            $tujuans = session()->get('tujuan');
            foreach ($tujuans as $item) {
                $tujuan[$item['id_warehouse']] =  $item['name'];
            }

            foreach ($items as $Location) {
                $Locations[] = $tujuan[$Location->Location];
            }

            // dd($Locations);

            $satuan = FacadesDB::connection('erp')->select("
                    SELECT
                        ID,
                        SW
                    FROM
                        UNIT
                    WHERE
                        ID > 0
                        AND TYPE IN ( 'W', 'V', 'U' )
                ");

            $barangStock = FacadesDB::connection('erp')->select(
                " SELECT
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

            return view('Inventori.MaterialRequest.Stock.show', compact('menu', 'header', 'items', 'satuan', 'barangStock','Locations'))->render();
        } elseif ($request->menu == 'baru') {
            $tgl = date('Y-m-d');
            $menu = $request->menu;
            $iddept = session('iddept');
            $header[0] = (object) [
                'ID' => '',
                'Active' => '',
                'TransDate' => $tgl,
                'Remarks' => '',
                'Department' => $iddept,
            ];

            return view('Inventori.MaterialRequest.Stock.show', compact('menu', 'header'))->render();
        } elseif ($request->menu == 'detailbarang') {
            $id = $request->id;
            $barang = FacadesDB::connection('erp')->select("SELECT
                    P.ID,
                    P.SW,
                    P.Description,
                    P.Stock,
                    U.SW Unit,
                    U.ID UID,
                    0 Priority,
                    X.Description ProdGroup,
                    L.Description Location,
	                p.Location Lcode
                FROM
                    ProductPurchase P
                    JOIN ShortText X ON P.ProdGroup = X.ID
                    LEFT JOIN Unit U ON P.Unit = U.ID
                    LEFT JOIN Location L ON P.Location = L.ID
                WHERE
                    P.Active = 'Y'
                    AND P.ID = '$id'
            ");

            $Lcode = $barang[0]->Lcode;

            $tujuans = session()->get('tujuan');
            foreach ($tujuans as $item) {
                $tujuan[$item['id_warehouse']] =  $item['name'];
            }

            return response(
                [
                    'success' => true,
                    'barang' => $barang[0],
                    'location' => $tujuan[$Lcode],
                ],
                200,
            );
        }
    }

    public function store(Request $request)
    {
        // dd($request);

        $id = $request->hasilid;
        $tgl_masuk = $request->tgl_masuk;
        $catatan = $request->catatan;

        $iduser = session('iduser');
        $iddept = session('iddept');

        //dapatkan last id
        $GetLastID = $this->Public_Function->GetLastIDERP('MaterialRequest');
        //dd($GetLastID);
        FacadesDB::connection('erp')->beginTransaction();
        try {
            //update last id
            $update_lastid = lastid::where('Module', 'MaterialRequest')->update([
                'Last' => $GetLastID['ID'],
            ]);

            // masukan ke user history
            $UserID = $iduser;
            $Module = '192';
            $ID_Field = $GetLastID['ID'];
            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $ID_Field);

            // Insert to materialrequest
            $insertMaterialRequest = materialrequest::create([
                'ID' => $GetLastID['ID'],
                'UserName' => Auth::user()->name,
                'Remarks' => $catatan,
                'TransDate' => now(),
                'Department' => $iddept,
                'Employee' => $iduser,
                'Purpose' => 'M',
                'Active' => 'A',
            ]);

            // dd($insertMaterialRequest);
            foreach ($request->barang as $key => $value) {
                // echo "$key =  $value <br>";
                $insertMaterialRequestItem[] = materialrequestitem::create([
                    'IDM' => $GetLastID['ID'],
                    'Ordinal' => $key + 1,
                    'Product' => !empty($value) ? $value : null,
                    'ProductNote' => null,
                    'Qty' => $request->jumlah[$key],
                    'QtyBuy' => $request->jumlah[$key],
                    'Purpose' => 'Rutin',
                    'Note' => $request->keterangan[$key],
                    'Department' => '0',
                    'ProductType' => null,
                    'Purchase' => 'Y',
                    'WorkshopOrder' => null,
                    'WorkshopItem' => null,
                    'Category' => null,
                    'Unit' => $request->unit[$key],
                    'ReBuy' => null,
                ]);
                //dd($insertMaterialRequestItem);

                $items_send[] = [
                    'item_code' => $request->sw[$key],
                    'qty' => $request->jumlah[$key],
                    'uom' => $request->uom[$key],
                    'conversion_factor' => 1,
                ];

                // $items_send[] = [
                //     's_warehouse' => $request->location[$key],
                //     'item_code' => $request->sw[$key],
                //     'qty' => $request->jumlah[$key],
                //     'uom' => $request->uom[$key],
                //     'allow_zero_valuation_rate' => 1,
                // ];
            }

            $iderpn = session('iderpn');

            // $data = [
            //     "naming_series" => "MAT-STE-.YYYY.-",
            //     "stock_entry_type" => "Material Issue",
            //     "purpose " => "Material Issue",
            //     "company " => "Lestari Mulia Sentosa",
            //     "doctype " => "Stock Entry",
            //     "employee_id" => $iderpn,
            //     'items' => $items_send,
            // ];

             $data = [
                'naming_series' => 'MAT-MR-.YYYY.-',
                'material_request_type' => 'Purchase',
                "employee_id" => $iderpn,
                'transaction_date' => $tgl_masuk,
                'schedule_date' => $tgl_masuk,
                'company' => 'Lestari Mulia Sentosa',
                // 'set_warehouse' => $GudangT,
                'items' => $items_send,
            ];

            // dd($data);

            $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
            // $response = Http::withHeaders([
            //     'Authorization' => 'token ' . $token,
            //     'Accept' => 'application/json',
            //     'Content-Type' => 'application/json',
            // ])->post('erpnext.lestarigold.co.id/api/resource/Stock Entry', $data);

             $response = Http::withHeaders([
                'Authorization' => 'token ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('erpnext.lestarigold.co.id/api/resource/Material Request', $data);

            $responerpnext = $response->json();      

            if ($response->successful()) {
                FacadesDB::connection('erp')->commit();
                $responerpnext = $response->json();
                $Remarks = $responerpnext['data']['name'];

                $update_workshoporderitem = materialrequest::where('ID', $GetLastID['ID'])
                    // jika permintaan berhasil
                    ->update([
                        'Remarks' => $Remarks,
                    ]);
            } else {
                FacadesDB::connection('erp')->rollBack();
                // jika permintaan gagal
                return response(
                    [
                        'success' => false,
                        'message' => 'Terjadi Masalah Pada ERP NEXT',
                        'error' => $responerpnext['_server_messages']
                    ],
                    400,
                );
            }

            return response(
                [
                    'success' => true,
                    'id' => $GetLastID['ID'],
                    'message' => 'Simpan Berhasil !!!',
                ],
                200,
            );
        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'message' => 'Gagal',
                ],
                500,
            );
        }
    }

    public function update(Request $request)
    {
        // dd($request->location[0]);
        $id = $request->hasilid;
        $tgl_masuk = $request->tgl_masuk;
        $catatan = $request->catatan;
        $iduser = session('iduser');

        FacadesDB::connection('erp')->beginTransaction();
        try {

            // masukan ke user history
            $UserID = $iduser;
            $Module = '192';
            $ID_Field = $id;
            $UpdateUserHistory = $this->Public_Function->UpdateUserHistoryERP($UserID, $Module, $ID_Field);

            $update_materialrequest = materialrequest::where('ID', $id)->update([
                'TransDate' => $tgl_masuk,
            ]);

            //menghapus materialrequestitem
            $deletematerialrequestitem = materialrequestitem::where('IDM', $id)->delete();

            $i = 0;
            foreach ($request->barang as $key => $value) {
                // echo "$key =  $value <br>";
                $i++;
                $insertMaterialRequestItem[] = materialrequestitem::create([
                    'IDM' => $id,
                    'Ordinal' => $i,
                    'Product' => !empty($value) ? $value : null,
                    'ProductNote' => null,
                    'Qty' => $request->jumlah[$key],
                    'QtyBuy' => $request->jumlah[$key],
                    'Purpose' => 'Rutin',
                    'Note' => $request->keterangan[$key],
                    'Department' => '0',
                    'ProductType' => null,
                    'Purchase' => 'Y',
                    'WorkshopOrder' => null,
                    'WorkshopItem' => null,
                    'Category' => null,
                    'Unit' => $request->unit[$key],
                    'ReBuy' => null,
                ]);

                // $items_send[] = [
                //     's_warehouse' => $request->location[$key],
                //     'item_code' => $request->sw[$key],
                //     'qty' => $request->jumlah[$key],
                //     'uom' => $request->uom[$key],
                //     'allow_zero_valuation_rate' => 1,
                // ];

                $items_send[] = [
                    'item_code' => $request->sw[$key],
                    'qty' => $request->jumlah[$key],
                    'uom' => $request->uom[$key],
                    'conversion_factor' => 1,
                ];
            }

            // dd($items_send);

            $iderpn = session('iderpn');

            // $data = [
            //     "naming_series" => "MAT-STE-.YYYY.-",
            //     "stock_entry_type" => "Material Issue",
            //     "purpose " => "Material Issue",
            //     "company " => "Lestari Mulia Sentosa",
            //     "doctype " => "Stock Entry",
            //     "employee_id" => $iderpn,
            //     'items' => $items_send,
            // ];

            $data = [
                'naming_series' => 'MAT-MR-.YYYY.-',
                'material_request_type' => 'Purchase',
                "employee_id" => $iderpn,
                'transaction_date' => $tgl_masuk,
                'schedule_date' => $tgl_masuk,
                'company' => 'Lestari Mulia Sentosa',
                // 'set_warehouse' => $GudangT,
                'items' => $items_send,
            ];

            $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
            // $response = Http::withHeaders([
            //     'Authorization' => 'token ' . $token,
            //     'Accept' => 'application/json',
            //     'Content-Type' => 'application/json',
            // ])->put('erpnext.lestarigold.co.id/api/resource/Stock Entry/'. $catatan, $data);

            $response = Http::withHeaders([
                'Authorization' => 'token ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->put('erpnext.lestarigold.co.id/api/resource/Material Request', $data);

            $responerpnext = $response->json();
            
            if ($response->successful()) {
                FacadesDB::connection('erp')->commit();
                $responerpnext = $response->json();
                $Remarks = $responerpnext['data']['name'];

            } else {
                FacadesDB::connection('erp')->rollBack();
                // jika permintaan gagal
                return response(
                    [
                        'success' => false,
                        'message' => 'Terjadi Masalah Pada ERP NEXT',
                        'error' => $responerpnext['_server_messages']
                    ],
                    400,
                );
            }

            return response(
                [
                    'success' => true,
                    'id' => $id,
                    'message' => 'Simpan Berhasil !!!',
                ],
                200,
            );
        } catch (Exception $e) {
            FacadesDB::connection('erp')->rollBack();
            return response(
                [
                    'message' => 'Gagal',
                ],
                500,
            );
        }
    }
}

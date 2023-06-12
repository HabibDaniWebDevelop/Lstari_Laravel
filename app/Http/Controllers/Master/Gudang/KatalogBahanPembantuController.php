<?php

namespace App\Http\Controllers\Master\Gudang;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\rndnew\productpurchase;
use App\Models\rndnew\productpurchasedepartment;
use PhpParser\Node\Expr\AssignOp\Concat;

class KatalogBahanPembantuController extends Controller
{
    public function index(Request $request)
    {
        $deptid = session('deptid');
        DB::statement("UPDATE rndnew.productpurchase rnd,
            ( SELECT erp.ID, erp.Active FROM erp.productpurchase erp ) erp
            SET rnd.Active = erp.Active
            WHERE
                erp.ID = rnd.ID
                AND erp.Active != rnd.Active
            ");

        DB::statement("INSERT INTO rndnew.productpurchase (
                ID,
                EntryDate,
                UserName,
                Remarks,
                SW,
                Description,
                ProdGroup,
                Stock,
                Active,
                MinStock,
                Price,
                Unit,
                NameOriginal,
                Location,
                Shelf,
                Rack,
                Bin,
                AccountCode
            ) SELECT
            erp.*
            FROM
                erp.productpurchase erp
                LEFT JOIN rndnew.productpurchase rnd ON erp.ID = rnd.ID
            WHERE
                rnd.ID IS NULL
        ");
        // dd($request);
        // dd($request->session()->all());
        return view('Master.Gudang.KatalogBahanPembantu.index');
    }

    public function menu($menu)
    {
        $iddept = session('iddept');
        // $iddept = 48;
        $searchs = FacadesDB::select("SELECT A.ID,A.SW,A.Description FROM `productpurchase` A WHERE A.Active='Y' GROUP BY A.ID ORDER BY Description");
        $departments = FacadesDB::select("SELECT
                D.ID,
                D.Description
            FROM
                productpurchasedepartment AS P
                INNER JOIN department AS D ON D.ID = P.Department
                INNER JOIN productpurchase C ON C.ID = P.ID
                WHERE C.Active = 'Y' AND D.Type = 'S'
            GROUP BY
                P.Department
            ORDER BY
                D.Description, d.ID");

        $Location = FacadesDB::connection('dev')->select('SELECT ID, Description From Location Where HigherRank = 63 Order By Description');

        if ($menu == 'btn-solid') {
            $type = 'Chemical Solid';
        } elseif ($menu == 'btn-liquid') {
            $type = 'Chemical Liquid';
        } elseif ($menu == 'btn-media') {
            $type = 'Media';
        } elseif ($menu == 'btn-tools') {
            $type = 'Tools';
        }
        // DB::enableQueryLog();
        $datas = FacadesDB::table('productpurchase AS A')
            ->leftJoin('erp.productpurchase AS p', 'p.ID', '=', 'A.ID')
            ->leftJoin('erp.location AS ll', 'll.ID', '=', 'p.Location')
            ->leftJoin('productpurchasesupplier AS C', 'C.ID', '=', 'A.ID')
            ->leftJoin('supplierother AS D', 'D.ID', '=', 'C.Supplier')
            ->leftJoin('shorttext AS B', 'B.ID', '=', 'A.ProdGroup')
            ->leftJoin('productpurchasedepartment AS L', 'L.ID', '=', 'A.ID')
            ->leftJoin('department AS DD', 'DD.ID', '=', 'L.Department')
            ->leftJoin('productpurchasetrans AS PT', 'PT.Product', '=', 'A.ID')
            ->leftJoin('unit AS E', 'E.ID', '=', 'A.Unit')
            ->selectRaw(
            "
                    a.ID,
                    a.Description,
                    a.Image1,
                    ll.Description Location,
                    ll.ID LocationID,
                    CONCAT( '/DiskD/Warehouse/', '', A.Image1 ) foto,
                CASE
                        
                        WHEN D.Description IS NULL THEN
                        '-' ELSE D.Description
                    END AS Supplier,
                    D.SW SWSupp,
                    B.Description KAT,
                CASE
                        
                        WHEN A.Brand IS NULL THEN
                        '-' ELSE A.Brand
                    END AS Brand,
                CASE
                        
                        WHEN A.MaterialFunction IS NULL THEN
                        '-' ELSE A.MaterialFunction
                    END AS MaterialFunction,
                    DD.Description Loc,
                    DD.SW LocSW,
                CASE
                        
                        WHEN PT.Qty IS NULL THEN
                        '0' ELSE PT.Qty
                    END AS Qty,
                    E.SW satuan,
                    A.Type
                    ",
            )
            ->where('a.active', '=', 'Y')
            ->where('a.Type', '=', $type)
            // ->when($iddept != '48', function ($q) use ($iddept) {
            //     return $q->where(function ($query) use ($iddept) {
            //         $query->where('DD.ID', '=', $iddept)->orWhereNull('DD.ID');
            //     });
            // })
            ->groupBy('a.ID')
            ->orderBy('Description', 'asc')
            ->orderBy('Type', 'asc')
            ->Paginate(12);
        // dd(DB::getQueryLog());
        // dd($datas, $iddept);
        return view('Master.Gudang.KatalogBahanPembantu.data2', compact('menu', 'searchs', 'departments', 'datas', 'type', 'iddept', 'Location'));
    }

    function pagination(Request $request)
    {
        $iddept = session('iddept');
        // $iddept = 48;
        $menu = $request->menu;
        $cari = $request->cari;
        $department = $request->department;
        $param1 = '=';

        if ($menu == 'btn-solid') {
            $type = 'Chemical Solid';
        } elseif ($menu == 'btn-liquid') {
            $type = 'Chemical Liquid';
        } elseif ($menu == 'btn-media') {
            $type = 'Media';
        } elseif ($menu == 'btn-tools') {
            $type = 'Tools';
        } elseif ($cari != '') {
            $type = $cari;
            $param1 = '<>';
        } elseif ($department != '') {
            $type = $department;
            $param1 = '<>';
        } else {
            $type = 'All Category';
            $param1 = '<>';
        }

        if ($request->Location) {
            $Location = $request->Location;
        } else {
            $Location = '0';
        }
        // dd($Location);

        // dd($request);

        if ($request->ajax()) {
            // DB::enableQueryLog();

            $datas = FacadesDB::table('productpurchase AS A')
                ->leftJoin('erp.productpurchase AS p', 'p.ID', '=', 'A.ID')
                ->leftJoin('erp.location AS ll', 'll.ID', '=', 'p.Location')
                ->leftJoin('productpurchasesupplier AS C', 'C.ID', '=', 'A.ID')
                ->leftJoin('supplierother AS D', 'D.ID', '=', 'C.Supplier')
                ->leftJoin('shorttext AS B', 'B.ID', '=', 'A.ProdGroup')
                ->leftJoin('productpurchasedepartment AS L', 'L.ID', '=', 'A.ID')
                ->leftJoin('department AS DD', 'DD.ID', '=', 'L.Department')
                ->leftJoin('productpurchasetrans AS PT', 'PT.Product', '=', 'A.ID')
                ->leftJoin('unit AS E', 'E.ID', '=', 'A.Unit')
                ->selectRaw(
                "
                    a.ID,
                    a.Description,
                    a.Image1,
                    ll.Description Location,
                    ll.ID LocationID,
                    CONCAT( '/DiskD/Warehouse/', '', A.Image1 ) foto,
                CASE
                        
                        WHEN D.Description IS NULL THEN
                        '-' ELSE D.Description
                    END AS Supplier,
                    D.SW SWSupp,
                    B.Description KAT,
                CASE
                        
                        WHEN A.Brand IS NULL THEN
                        '-' ELSE A.Brand
                    END AS Brand,
                CASE
                        
                        WHEN A.MaterialFunction IS NULL THEN
                        '-' ELSE A.MaterialFunction
                    END AS MaterialFunction,
                    DD.Description Loc,
                    DD.SW LocSW,
                CASE
                        
                        WHEN PT.Qty IS NULL THEN
                        '0' ELSE PT.Qty
                    END AS Qty,
                    E.SW satuan,
                    A.Type
                    ",
                )
                ->where('a.active', '=', 'Y')
                // ->where('a.Type', $param1, $type)
                ->when($param1 != '<>', function ($q) use ($param1, $type) {
                    return $q->where('a.Type', $param1, $type);
                })
                ->when($cari != '', function ($q) use ($cari) {
                    return $q->where('a.SW', '=', $cari);
                })
                ->when($department != '', function ($q) use ($department) {
                    return $q->where('L.Department', '=', $department);
                })
                ->when($Location != '0', function ($q) use ($Location) {
                    return $q->where('ll.ID', '=', $Location);
                })
                // ->when($iddept != '48', function ($q) use ($iddept) {
                //     return $q->where(function ($query) use ($iddept) {
                //         $query->where('DD.ID', '=', $iddept)->orWhereNull('DD.ID');
                //     });
                // })
                ->groupBy('a.ID')
                ->orderBy('Description', 'asc')
                ->orderBy('a.Type', 'asc')
                // ->offset(10)
                ->Paginate(12);
            // dd($datas);
            // dd(DB::getQueryLog());
            return view('Master.Gudang.KatalogBahanPembantu.pagination_data', compact('menu', 'datas', 'type', 'iddept'))->render();
        }
    }

    public function lihat($id)
    {
        // dd($id);

        $datas = FacadesDB::select("SELECT
                    A.*,
                    CONCAT( '/DiskD/BahanPembantu/', '', A.Image1 ) foto1,
                CASE
                        
                        WHEN A.Image2 IS NULL THEN
                        '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image2 )
                    END AS foto2,
                CASE
                        
                        WHEN A.Image3 IS NULL THEN
                        '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image3 )
                    END AS foto3,
                CASE
                        
                        WHEN A.Image4 IS NULL THEN
                        '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image4 )
                    END AS foto4,
                CASE
                        
                        WHEN A.Image5 IS NULL THEN
                        '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image5 )
                    END AS foto5,
                CASE
                        
                        WHEN A.Image6 IS NULL THEN
                        '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image6 )
                    END AS foto6,
                CASE
                        
                        WHEN A.TechnicalImage IS NULL THEN
                        '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.TechnicalImage )
                    END AS foto7,
                CASE
                        
                        WHEN A.TDS IS NULL THEN
                        '-' ELSE CONCAT( '/DiskD/BahanPembantuDocument/', '', A.TDS )
                    END AS TDS,
                CASE
                        
                        WHEN A.MSDS IS NULL THEN
                        '-' ELSE CONCAT( '/DiskD/BahanPembantuDocument/', '', A.MSDS )
                    END AS MSDS,
                    B.Description KAT,
                    E.Description EE
                FROM
                    `productpurchase` A
                    LEFT JOIN shorttext B ON B.ID = A.ProdGroup
                    JOIN unit E ON E.ID = A.Unit
                WHERE
                    A.Active = 'Y'
                    AND A.ID = '$id'");

        $Suppliers = FacadesDB::select("SELECT
                D.SW SWSupp,
                D.Description Supplier
            FROM
                productpurchasesupplier A
                LEFT JOIN supplierother D ON D.ID = A.Supplier
            WHERE
                A.ID = '$id'");

        $areas = FacadesDB::select("SELECT
                    D.SW, D.Description
                FROM
                    `productpurchase` A
                    LEFT JOIN productpurchasedepartment C ON C.ID = A.ID
                    LEFT JOIN department D ON D.ID = C.Department
                WHERE
                    A.Active = 'Y'
                    AND A.ID = '$id'");

        // dd($areas);

        return view('Master.Gudang.KatalogBahanPembantu.lihat', compact('datas', 'Suppliers', 'areas'))->render();
    }

    function carifilter(Request $request)
    {
        // dd($request);
        if ($request->Location) {
            $clok = "AND A.Location = '$request->Location' ";
        } else {
            $clok = '';
        }
        $lokasi =  $request->Location;

        $searchs = FacadesDB::select("SELECT A.ID,A.SW,A.Description FROM `productpurchase` A WHERE A.Active='Y' $clok GROUP BY A.ID ORDER BY Description");
        // dd($request);

        return view('Master.Gudang.KatalogBahanPembantu.cari_filter', compact('searchs'));
    }

    public function ubah($id)
    {
        $datas = FacadesDB::select("SELECT
                A.*,
                CONCAT( '/DiskD/BahanPembantu/', '', A.Image1 ) foto1,
            CASE
                    
                    WHEN A.Image2 IS NULL THEN
                    '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image2 )
                END AS foto2,
            CASE
                    
                    WHEN A.Image3 IS NULL THEN
                    '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image3 )
                END AS foto3,
            CASE
                    
                    WHEN A.Image4 IS NULL THEN
                    '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image4 )
                END AS foto4,
            CASE
                    
                    WHEN A.Image5 IS NULL THEN
                    '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image5 )
                END AS foto5,
            CASE
                    
                    WHEN A.Image6 IS NULL THEN
                    '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.Image6 )
                END AS foto6,
            CASE
                    
                    WHEN A.TechnicalImage IS NULL THEN
                    '' ELSE CONCAT( '/DiskD/BahanPembantu/', '', A.TechnicalImage )
                END AS foto7,
                B.Description KAT,
                E.Description EE
            FROM
                `productpurchase` A
                LEFT JOIN shorttext B ON B.ID = A.ProdGroup
                JOIN unit E ON E.ID = A.Unit
            WHERE
                A.Active = 'Y'
                AND A.ID = '$id'");

        $departments = FacadesDB::select("SELECT
                D.ID,
                D.Description,
            CASE
                    
                    WHEN P.ID <> '' THEN
                    'selected' ELSE ''
                END AS PID
            FROM
                department d
                LEFT JOIN productpurchasedepartment AS P ON D.ID = P.Department
                AND P.ID = '$id'
            WHERE
                D.Type = 'S'
            ORDER BY
                PID DESC,
                Description");
        // dd($datas);
        return view('Master.Gudang.KatalogBahanPembantu.ubah', compact('datas', 'departments'))->render();
    }

    public function gambar($id)
    {
        $datas = FacadesDB::select("SELECT
            ID,
            Image1,
            Image2,
            Image3,
            Image4,
            Image5,
            Image6,
            TechnicalImage
        FROM
            `productpurchase`
            WHERE ID = '$id'");

        // dd($datas);
        return view('Master.Gudang.KatalogBahanPembantu.gambar', compact('datas'))->render();
    }

    public function edit(Request $request)
    {
        //simpan dokumen ke server
        if ($request->TDS != 'undefined') {
            $TDSName = $request->idnee . '-TDS.' . $request->TDS->extension();
            // $request->TDS->storeAs('BahanPembantuDocument', $TDSName, 'rnd');
            $request->TDS->storeAs('RND DATA/BahanPembantuDocument', $TDSName, 'Server_F');
        } else {
            $TDSName = null;
        }

        if ($request->MSDS != 'undefined') {
            $MSDSName = $request->idnee . '-MSD.' . $request->MSDS->extension();
            // $request->MSDS->storeAs('BahanPembantuDocument', $MSDSName, 'rnd');
            $request->MSDS->storeAs('RND DATA/BahanPembantuDocument', $MSDSName, 'Server_F');
        } else {
            $MSDSName = null;
        }

        $departments = explode(',', $request->department2);

        $link = productpurchase::find($request->idnee);
        $link->Brand = $request->Brand;
        $link->Type = $request->Type;
        $link->Remarks = $request->Remarks;
        $link->MaterialFunction = $request->MaterialFunction;
        if ($TDSName != null) {
            $link->TDS = $TDSName;
        }
        if ($MSDSName != null) {
            $link->MSDS = $MSDSName;
        }
        $link->save();

        $deleted = DB::table('productpurchasedepartment')
            ->where('ID', '=', $request->idnee)
            ->delete();

        $deleted2 = DB::connection('erp')
            ->table('productpurchasedepartment')
            ->where('ID', '=', $request->idnee)
            ->delete();

        foreach ($departments as $department) {
            $insert = DB::table('productpurchasedepartment')->insert([
                'ID' => $request->idnee,
                'Department' => $department,
            ]);

            $insert2 = DB::connection('erp')
                ->table('productpurchasedepartment')
                ->insert([
                    'ID' => $request->idnee,
                    'Department' => $department,
                ]);
        }

        if ($link) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Register Berhasil!!',
                    'message' => 'Register Berhasil!!',
                ],
                201,
            );
        }
    }

    public function uploadgambar(Request $request)
    {
        if ($request->hasFile('Image1')) {
            $file = request()->file('Image1');
            $imageName = request()->idnee . '-1.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path1 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path1 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path1) {
                $link = productpurchase::find($request->idnee);
                $link->Image1 = $imageName;
                $link->save();
            }
        }

        if ($request->hasFile('Image2')) {
            $file = request()->file('Image2');
            $imageName = request()->idnee . '-2.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path2 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path2 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path2) {
                $link = productpurchase::find($request->idnee);
                $link->Image2 = $imageName;
                $link->save();
            }
        }

        if ($request->hasFile('Image3')) {
            $file = request()->file('Image3');
            $imageName = request()->idnee . '-3.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path3 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path3 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path3) {
                $link = productpurchase::find($request->idnee);
                $link->Image3 = $imageName;
                $link->save();
            }
        }

        if ($request->hasFile('Image4')) {
            $file = request()->file('Image4');
            $imageName = request()->idnee . '-4.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path4 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path4 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path4) {
                $link = productpurchase::find($request->idnee);
                $link->Image4 = $imageName;
                $link->save();
            }
        }

        if ($request->hasFile('Image5')) {
            $file = request()->file('Image5');
            $imageName = request()->idnee . '-5.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path5 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path5 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path5) {
                $link = productpurchase::find($request->idnee);
                $link->Image5 = $imageName;
                $link->save();
            }
        }

        if ($request->hasFile('Image6')) {
            $file = request()->file('Image6');
            $imageName = request()->idnee . '-6.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path6 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path6 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path6) {
                $link = productpurchase::find($request->idnee);
                $link->Image6 = $imageName;
                $link->save();
            }
        }

        if ($request->hasFile('TechnicalImage')) {
            $file = request()->file('TechnicalImage');
            $imageName = request()->idnee . '-7.' . $file->extension();
            $img = Image::make($file);
            $img->orientate();
            $img->resize(1280, 1280, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resource = $img->stream()->detach();
            // $path7 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
            $path7 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

            if ($path7) {
                $link = productpurchase::find($request->idnee);
                $link->TechnicalImage = $imageName;
                $link->save();
            }
        }

        return response()->json(
            [
                'success' => true,
                'title' => 'Register Berhasil!!',
                'message' => 'Register Berhasil!!',
            ],
            201,
        );
    }

    public function mobile(Request $request)
    {
        if (isset($request->cari)) {
            $datas = FacadesDB::select("SELECT
                    ID,
                    SW,
                    Description,
                    Remarks,
                    Type,
                    Image1,
                    Image2,
                    Image3,
                    Image4,
                    Image5,
                    Image6,
                    TechnicalImage
                FROM
                    productpurchase
                WHERE
                    SW = '$request->cari'
            ");

            $departments = FacadesDB::select(
                "SELECT
                D.ID,
                D.Description,
            CASE
                    
                    WHEN P.ID <> '' THEN
                    'selected' ELSE ''
                END AS PID
            FROM
                department d
                LEFT JOIN productpurchasedepartment AS P ON D.ID = P.Department
                AND P.ID = '" .
                    $datas[0]->ID .
                    "'
            WHERE
                D.Type = 'S'
            ORDER BY
                PID DESC,
                Description",
            );

            // dd($productpurchase, $departments);

            if ($datas) {
                return view('Master.Gudang.KatalogBahanPembantu.mobiledata', compact('datas', 'departments'))->render();
            } else {
                return 'Data Tidak Di tekmukan';
            }

            return;
        }
        // dd($request);
        $searchs = FacadesDB::select("SELECT A.SW,A.Description FROM `productpurchase` A WHERE A.Active='Y' GROUP BY A.ID ORDER BY Description");
        return view('Master.Gudang.KatalogBahanPembantu.mobile', compact('searchs'))->render();
    }

    public function mobile_store(Request $request)
    {
        for ($i = 1; $i <= 7; $i++) {
            $cek = 'C_Image' . $i;
            $cek2 = 'Image' . $i;

            if ($request->$cek != null) {
                // echo $i ." = ". $request->$cek."<br>";
                $image_parts = explode(';base64,', $request->$cek);
                $image_type_aux = explode('image/', $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);
                $fileName = $request->idnee . '-' . $i . '.' . $image_type;

                // $simpan1 = Storage::disk('rnd')->put('BahanPembantu/' . $fileName, $image_base64);
                $simpan1 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $fileName, $image_base64);

                if ($simpan1) {
                    if ($i != 7) {
                        $lokasi = $cek2;
                    } else {
                        $lokasi = 'TechnicalImage';
                    }

                    $link = productpurchase::find($request->idnee);
                    $link->$lokasi = $fileName;
                    $link->save();
                }
            }

            if ($request->hasFile($cek2)) {
                $file = request()->file($cek2);
                $imageName = request()->idnee . '-' . $i . '.' . $file->extension();
                $img = Image::make($file);
                $img->orientate();
                $img->resize(1280, 1280, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $resource = $img->stream()->detach();
                // $simpan2 = Storage::disk('rnd')->put('BahanPembantu/' . $imageName, $resource);
                $simpan2 = Storage::disk('Server_F')->put('RND DATA/BahanPembantu/' . $imageName, $resource);

                if ($simpan2) {
                    if ($i != 7) {
                        $lokasi = $cek2;
                    } else {
                        $lokasi = 'TechnicalImage';
                    }

                    $link = productpurchase::find($request->idnee);
                    $link->$lokasi = $imageName;
                    $link->save();
                }
            }
        }

        // dd($request);

        $link = productpurchase::find($request->idnee);
        $link->Remarks = $request->Remarks;
        $link->Type = $request->Type;
        $link->save();

        $deleted = DB::table('productpurchasedepartment')
            ->where('ID', '=', $request->idnee)
            ->delete();

        $departments = explode(',', $request->department2);
        foreach ($departments as $department) {
            $insert = DB::table('productpurchasedepartment')->insert([
                'ID' => $request->idnee,
                'Department' => $department,
            ]);
        }

        return response()->json(
            [
                'success' => true,
            ],
            201,
        );
    }
}

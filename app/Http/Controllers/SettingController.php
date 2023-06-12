<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\master_level;
use Illuminate\Http\Request;
use App\Models\master_module;
use App\Models\master_module_QA;
use App\Models\rndnew\master_module_list_laravel as master_module_list;
use App\Models\erp\product;
use App\Models\messaging\todolist;
use App\Models\messaging\announcement;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Http\Controllers\Public_Function_Controller;

class SettingController extends Controller
{
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }

    public function index()
    {
        return view('Setting.setting', ['title' => 'setting']);
    }

    public function About()
    {
        return view('Setting.About', ['title' => 'setting']);
    }

    public function Account()
    {
        return view('Setting.Account', ['title' => 'setting']);
    }

    public function gantipswd()
    {
        return view('Setting.gantipswd', ['title' => 'setting']);
    }

    public function gantipswdspn(Request $request, $id)
    {
        $passwordbaru = $request->passwordbaru;
        $tglfull = date('Y-m-d h:i:s');

        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $link = user::find($id);
            $link->Password = Hash::make($passwordbaru);
            $link->Remarks = 'Password di Perbarui Oleh: ' . $request->name;
            $link->save();

            if ($link) {
                return response()->json([
                    'success' => true,
                    'message' => 'Register Berhasil!!',
                ]);
            }
        } else {
            return response()->json([
                'success' => true,
                'message' => 'pswsalah',
            ]);
        }
    }

    // !!------------------------------------ todolist ------------------------------------
    public function todolist()
    {
        $username = Auth::user()->name;
        // $username = 'niko';

        $data1 = FacadesDB::connection('erp')->select("SELECT e.SW,e.Rank FROM `employee` E WHERE SW='$username'");
        if ($data1['0']->Rank == 'Direktur') {
            $datas = FacadesDB::connection('messaging')
                ->table('todolist')
                ->selectRaw('*')
                ->orderBy('created_at', 'desc')
                ->Paginate(100);
        } else {
            $datas = FacadesDB::connection('messaging')
                ->table('todolist')
                ->selectRaw('*')
                ->where('todocreator', '=', "$username")
                ->orwhere('name', '=', "$username")
                ->orderBy('created_at', 'desc')
                ->Paginate(100);
        }
        // dd($data);

        return view('Setting.todolist', compact('datas'));
    }

    public function todolist_filter($id)
    {
        $username = Auth::user()->name;
        // $username = 'niko';
        $filter = "(name != '')";
        //meng uraikan input form
        $id2 = preg_split('/&/', $id);
        for ($i = 0; $i < count($id2); $i++) {
            $id3 = preg_split('/=/', $id2[$i]);
            // $ids[$id3[0]] = $id3[1];
            if ($id3[1] != '') {
                $filter .= " AND ($id3[0] LIKE '%$id3[1]%')";
            }
        }

        $data1 = FacadesDB::connection('erp')->select("SELECT e.SW,e.Rank FROM `employee` E WHERE SW='$username'");
        if ($data1['0']->Rank == 'Direktur') {
            $lihat = " todocreator != '' ";
        } else {
            $lihat = " todocreator = '$username' OR `name` = '$username' ";
        }

        if ($filter == "(name != '')") {
            $kosong = '0';
            return Response::json($kosong);
        } else {
            $datas = FacadesDB::connection('messaging')->select("SELECT
                    *
                FROM
                    `todolist`
                WHERE
                    ($lihat)
                    AND ($filter)
                ORDER BY
                    created_at DESC
                    LIMIT 200");
            // dd($datas);
            return view('Setting.todolistfilter', compact('datas'));
        }
    }

    public function todolist_name()
    {
        $username = Auth::user()->name;
        // $username = 'niko';
        $data1 = FacadesDB::connection('erp')->select("SELECT
                e.SW,
                e.Rank,
                e.Department,
                D.HigherRank AS HID,
                D.Responsibility RID,
                F.SW AS Diskripsi
            FROM
                employee e
                JOIN Department D ON E.Department = D.ID
                JOIN Department F ON F.ID = D.HigherRank 
            WHERE
                e.SW = '$username'");

        $kabag = "'Frita', 'Shita', 'Tetti', 'Kharies', 'Titis', 'ispri', 'Aditya', 'Novandre', 'Evi Puji', 'Arton', 'Octo'";
        $kabagary = ['Frita', 'Shita', 'Tetti', 'Kharies', 'Titis', 'ispri', 'Aditya', 'Novandre', 'Evi Puji', 'Arton', 'Octo'];

        // dd($data1);

        if ($data1 != null) {
            if ($data1[0]->Rank == 'Manager') {
                $cari1 = "WHERE H.ID = '" . $data1[0]->HID . "' OR e.Rank = 'Manager' OR e.Rank = 'Supervisor' OR D.ID = '12' OR E.SW IN ($kabag) ";
            } elseif ($data1[0]->Rank == 'Supervisor') {
                $cari1 = "WHERE R.ID = '" . $data1[0]->RID . "' OR e.Rank = 'Manager' OR e.Rank = 'Supervisor' OR D.ID = '12' OR E.SW IN ($kabag) ";
            } elseif (in_array($username, $kabagary)) {
                $cari1 = "WHERE E.Department = '" . $data1[0]->Department . "' OR e.Rank = 'Manager' OR e.Rank = 'Supervisor' OR D.ID = '12' OR E.SW IN ($kabag) ";
            } elseif ($data1[0]->Rank == 'Direktur') {
                $cari1 = '';
            } else {
                $cari1 = "WHERE U.NAME = '" . $username . "'";
            }
        } else {
            $cari1 = "WHERE U.NAME = '" . $username . "'";
        }

        $data2 = FacadesDB::connection('erp')->select("SELECT
            U.NAME,
            CASE
                WHEN e.Rank = 'Direktur' THEN '1' 
                WHEN e.Rank = 'Manager' THEN '2' 
                WHEN e.Rank = 'Supervisor' THEN '3' 
                WHEN U.NAME IN ($kabag)  THEN '4'
                WHEN H.SW = '" . $data1[0]->Diskripsi . "' THEN '5' 
                ELSE '6' 
            END AS ordinal,
            CASE   
                WHEN e.Rank = 'Direktur' THEN e.Rank 
                WHEN e.Rank = 'Manager' THEN e.Rank
                WHEN e.Rank = 'Supervisor' THEN e.Rank
                WHEN U.NAME IN ($kabag)  THEN 'Kepala Bagian'
                WHEN H.SW = '" . $data1[0]->Diskripsi . "' THEN D.SW 
                ELSE D.SW
            END AS jenis 
        FROM
            RnDNew.Users U
            LEFT JOIN Employee E ON U.NAME = E.SW AND u.`status` = 'A'
            JOIN Department D ON E.Department = D.ID
            JOIN Department H ON D.HigherRank = H.ID
            LEFT JOIN Department R ON D.Responsibility = R.ID
        $cari1
        ORDER BY Ordinal, jenis, E.Rank, u.`name`
        ");

        // dd($data2);
        return view('Setting.todolistname', compact('data2'));
    }

    public function todolist_tambah(Request $request)
    {
        $username = Auth::user()->name;
        $name = $request->name;

        $data1 = FacadesDB::connection('erp')->select("SELECT Department FROM employee WHERE SW = '$name' ");
        if ($data1 != null) {
            $department = $data1[0]->Department;
        } else {
            $department = '';
        }

        $tododate = $request->tododate;
        $status = $request->status;
        $todo = $request->todo;
        $remarks = $request->remarks;
        $TargetDate = $request->TargetDate;
        $Priority = $request->Priority;
        // dd($name, $status, $todo, $remarks);
        $link = todolist::create([
            'name' => $name,
            'department' => $department,
            'todo' => $todo,
            'remarks' => $remarks,
            'todocreator' => $username,
            'tododate' => $tododate,
            'TargetDate' => $TargetDate,
            'status' => $status,
            'Priority' => $Priority,
            'updatestatus' => null,
        ]);
        return Response::json($link);
    }

    public function todolist_update($id)
    {
        $datas = todolist::find($id);
        // dd($datas);
        return Response::json($datas);
    }

    public function todolist_edit(Request $request, $id)
    {
        $tgl = date('Y-m-d');
        $status = $request->status;
        $link = todolist::find($id);
        $link->status = $status;
        $link->updatestatus = $tgl;
        $link->save();

        if ($status == 'P') {
            $badge = 'badge bg-warning';
        }
        if ($status == 'T') {
            $badge = 'badge bg-danger';
        }
        if ($status == 'S') {
            $badge = 'badge bg-success';
        }
        if ($link) {
            return response()->json(
                [
                    'success' => true,
                    'tgl' => $tgl,
                    'status' => $status,
                    'badge' => $badge,
                ],
                201,
            );
        }
    }

    public function todolist_edit2(Request $request, $id)
    {
        // dd($request, $id); 
        $link = todolist::find($id);
        $link->todo = $request->todo;
        $link->name = $request->name;
        $link->remarks = $request->remarks;
        $link->tododate = $request->tododate;
        $link->TargetDate = $request->TargetDate;
        $link->Priority = $request->Priority;
        $link->save();
        if ($link) {
            return response()->json(
                [
                    'success' => true,
                ],
                201,
            );
        }
    }

    // !!------------------------------------ Pengumuman ------------------------------------
    public function Pengumuman()
    {
        $username = Auth::user()->name;
        // $username = 'niko';
        $datas = FacadesDB::connection('messaging')
            ->table('announcement AS a')
            ->leftJoin('erp.department AS d', function ($join) {
                $join->on('a.AnnounceTo', '=', 'd.id');
            })
            ->selectRaw('A.*, D.SW ')
            ->where('a.UserName', '=', $username)
            ->orderBy('TransDate', 'desc')
            ->Paginate(20);

        // dd($datas);

        return view('Setting.Pengumuman', compact('datas'));
    }

    public function Pengumuman_filter($id)
    {
        $username = Auth::user()->name;
        // $username = 'niko';
        $filter = "(A.UserName  != '')";
        //meng uraikan input form
        $id2 = preg_split('/&/', $id);
        for ($i = 0; $i < count($id2); $i++) {
            $id3 = preg_split('/=/', $id2[$i]);
            // $ids[$id3[0]] = $id3[1];
            if ($id3[1] != '') {
                if ($id3[0] == 'AnnounceTo') {
                    $filter .= " AND (D.SW LIKE '%$id3[1]%')";
                } else {
                    $filter .= " AND (A.$id3[0] LIKE '%$id3[1]%')";
                }
            }
        }
        // dd($filter);
        if ($filter == "(A.UserName  != '')") {
            $kosong = '0';
            return Response::json($kosong);
        } else {
            $datas = FacadesDB::connection('messaging')->select("SELECT
                    A.*,
                    D.SW
                FROM
                    `announcement` A
                INNER JOIN department D ON a.AnnounceTo = d.id
                WHERE $filter
                ORDER BY
                    A.created_at DESC
                    LIMIT 200");
            // dd($datas);
            return view('Setting.Pengumumanfilter', compact('datas'));
        }
    }

    public function Pengumuman_announceto()
    {
        $username = Auth::user()->name;
        $data1 = FacadesDB::connection('messaging')->select("SELECT id, SW FROM `department` WHERE Type = 'D' ORDER BY SW ");
        // dd($data1);
        return view('Setting.PengumumanAnnounceto', compact('data1'));
    }

    public function Pengumuman_tambah(Request $request)
    {
        // dd($request);
        // $username = Auth::user()->name;
        $UserName = $request->UserName;
        $TransDate = $request->TransDate;
        $AnnounceTo = $request->AnnounceTo;
        $ValidToDate = $request->ValidToDate;
        $Note = $request->Note;
        // dd($name, $status, $todo, $remarks);
        $link = announcement::create([
            'UserName' => $UserName,
            'TransDate' => $TransDate,
            'AnnounceTo' => $AnnounceTo,
            'ValidToDate' => $ValidToDate,
            'Note' => $Note,
        ]);
        return Response::json($link);
    }

    // !!------------------------------------ User ------------------------------------
    public function user(Request $request)
    {
        $settings = user::from('users as a')
            ->LeftJoin('erp.employee as b', 'b.SW', '=', 'a.name')
            ->LeftJoin('master_level_laravel as c', 'c.Id_Level', '=', 'a.level')
            ->select('a.id', 'a.name', 'a.level', 'a.status', 'b.Description', 'c.Nama_level')
            ->get();

        return view('Setting.user', compact('settings'));
    }

    public function UserCreat(Request $request)
    {
        $name = $request->Name;
        $level = $request->level;
        $status = $request->status;
        $username = Auth::user()->name;
        $password = strtolower($name);
        // dd($name, '|', $level,'|', $password, $status);
        $link = User::create([
            'name' => $name,
            'level' => $level,
            'status' => $status,
            'Remarks' => 'Di buat Oleh: ' . $username,
            'password' => Hash::make($password),
        ]);
        return Response::json($link);
    }

    public function UserEdit($link_id)
    {
        $link = user::find($link_id);
        // $super=['name'=> $link->name, 'id'=> $link->id, 'su'=> 'baru'];
        // dd($super);
        return Response::json($link);
    }

    public function UserUpdate(Request $request, $link_user)
    {
        $username = Auth::user()->name;
        // dd($request);
        $link = user::find($link_user);
        $link->Name = $request->Name;
        $link->level = $request->level;
        $link->status = $request->status;
        $link->Remarks = 'Di Perbarui Oleh: ' . $username;
        $link->save();

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

    public function UserUpdatePSW(Request $request)
    {
        // dd($request->id);
        $username = Auth::user()->name;
        $password = strtolower($request->Name);

        // dd($request);
        $link = user::find($request->id);
        $link->Password = Hash::make($password);
        $link->Remarks = 'Password di Reset Oleh: ' . $username;
        $link->save();

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

    // !!------------------------------------ menuLevel ------------------------------------
    public function menuLevel(Request $request)
    {
        $cek = $request->input('cek');

        $data = FacadesDB::select(" SELECT
	A.Nama_level,
	A.`Status`,
	A.Id_Level,
	( SELECT GROUP_CONCAT( DISTINCT ( `name` ) SEPARATOR ', ' ) AS a FROM users WHERE `level` = A.Id_Level AND `status` = 'A' ) USER,
	( SELECT GROUP_CONCAT(DISTINCT (c.`Name`) SEPARATOR ', ') AS a FROM `master_module_list_laravel` b INNER JOIN master_module_laravel c ON b.ID_Modul=c.ID_Modul LEFT JOIN master_module_laravel d ON c.ID_Modul=d.Parent WHERE `Level`=A.Id_Level AND d.ID_Modul IS NULL ORDER BY c.`Name`	) akses 
FROM
	`master_level_laravel` AS A
        ");

        return view('Setting.menuLevel', compact('data', 'cek'));
    }

    public function menuLevelEdit($link_id)
    {
        $link = master_level::find($link_id);
        // $super=['name'=> $link->name, 'id'=> $link->id, 'su'=> 'baru'];
        // dd($super);
        return Response::json($link);
    }

    public function menuLevelCreat(Request $request)
    {
        $link = master_level::create([
            'Nama_level' => $request->input('Name'),
            'Status' => 'A',
        ]);
        // // dd($sub);
        return Response::json($link);
    }

    public function menuLevelUpdate(Request $request, $link_user)
    {
        $link = master_level::find($link_user);
        $link->Nama_level = $request->Name;
        $link->save();

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

    // !!------------------------------------ List Menu ------------------------------------
    public function ListMenu(Request $request)
    {
        $cek = $request->input('cek');
        $settings = FacadesDB::select(" SELECT
            a.ordinal AS no1,
            a.NAME AS menul1,
            b.ordinal AS no2,
            b.NAME AS menul2,
            c.ordinal AS no3,
            c.NAME AS menul3,
            d.ordinal AS no4,
            d.NAME AS menul4,
        CASE
                
                WHEN d.ID_Modul IS NOT NULL THEN
                d.Icon
                WHEN c.ID_Modul IS NOT NULL THEN
                c.Icon
                WHEN b.ID_Modul IS NOT NULL THEN
                b.Icon ELSE a.Icon
            END AS Icon,
        CASE
                
                WHEN d.ID_Modul IS NOT NULL THEN
                d.made_by
                WHEN c.ID_Modul IS NOT NULL THEN
                c.made_by
                WHEN b.ID_Modul IS NOT NULL THEN
                b.made_by ELSE a.made_by
            END AS made_by,
        CASE
                
                WHEN d.Patch IS NOT NULL THEN
                d.Patch
                WHEN c.Patch IS NOT NULL THEN
                c.Patch
                WHEN b.Patch IS NOT NULL THEN
                b.Patch ELSE a.Patch
            END AS Patch,
        CASE
                
                WHEN d.ID_Modul IS NOT NULL THEN
                d.ID_Modul
                WHEN c.ID_Modul IS NOT NULL THEN
                c.ID_Modul
                WHEN b.ID_Modul IS NOT NULL THEN
                b.ID_Modul ELSE a.ID_Modul
            END AS nomodul,
        CASE
                
                WHEN d.`Status` IS NOT NULL THEN
                d.`Status`
                WHEN c.`Status` IS NOT NULL THEN
                c.`Status`
                WHEN b.`Status` IS NOT NULL THEN
                b.`Status` ELSE a.`Status`
            END AS STATUS,
            ( SELECT GROUP_CONCAT( DISTINCT (( SELECT `Nama_level` FROM master_level_laravel WHERE Id_Level = `Level` )) SEPARATOR ', ' ) FROM master_module_list_laravel WHERE ID_Modul = nomodul GROUP BY `ID_Modul` ) LEVEL
        FROM
            master_module_laravel AS a
            LEFT JOIN master_module_laravel AS b ON a.id_modul = b.parent
            LEFT JOIN master_module_laravel AS c ON b.id_modul = c.parent
            LEFT JOIN master_module_laravel AS d ON c.id_modul = d.parent
        WHERE
            a.parent = 0
        ORDER BY
            no1 ASC,
            no2 ASC,
            no3 ASC,
            no4 ASC
        ");

        $maxid = master_module::max('ID_Modul') + 1;
        // dd($settings);
        return view('Setting.ListMenu', compact('settings', 'cek', 'maxid'));
    }

    public function ListMenuTambah()
    {
        return view('Setting.ListMenuTambah');
    }

    public function ListMenuEdit($link_id)
    {
        $link = master_module::find($link_id);
        $paren = FacadesDB::select(
            " SELECT
            a.ID_Modul AS id,
            CONCAT(
            CASE
                WHEN c.`Name` IS NOT NULL THEN
                CONCAT(c.`Name`, '/') ELSE ''
            END,
            CASE
                WHEN b.`Name` IS NOT NULL THEN
                CONCAT(b.`Name`, '/') ELSE ''
            END,
            CASE
                WHEN a.`Name` IS NOT NULL THEN
                CONCAT(a.`Name`, '/') ELSE ''
            END) as Name
        FROM
            master_module_laravel a
            LEFT JOIN master_module_laravel AS b ON b.id_modul = a.parent
            LEFT JOIN master_module_laravel AS c ON c.id_modul = b.parent
        WHERE
            a.ID_Modul = '" .
                $link['Parent'] .
                "'
        ",
        );
        $Parent = $paren[0]->Name;

        $Levels = FacadesDB::select(
            " SELECT
                Id_Level,
                Nama_level
            FROM
                master_level_laravel
            WHERE
                `Status` = 'A'
                AND Id_Level <> '1'
                AND Id_Level <> '2'
                ORDER BY Nama_level
            ",
        );
        $Parent = $paren[0]->Name;
        return view('Setting.ListMenuEdit', compact('link', 'Parent', 'Levels'));
    }

    public function ListMenuCreat(Request $request)
    {
        $link = master_module::create($request->all());
        return Response::json($link);
    }

    public function ListMenuUpdate(Request $request, $link_id)
    {
        if ($request->Ordinallama == null || $request->Ordinallama > $request->Ordinal) {
            $Ordinal = $request->Ordinal - 0.5;
        } else {
            $Ordinal = $request->Ordinal + 0.5;
        }

        // dd($request);
        $link = master_module::find($link_id);
        $link->Name = $request->Name;
        $link->Ordinal = $Ordinal;
        $link->Parent = $request->Parent;
        $link->Patch = $request->Patch;
        $link->Status = $request->Status;
        $link->Icon = $request->Icon;
        $link->made_by = $request->made_by;
        $link->save();

        $i = 0;
        $datas = FacadesDB::select(" SELECT ID_Modul, Ordinal FROM master_module_laravel WHERE Parent = '$request->Parent' ORDER BY Ordinal");
        foreach ($datas as $data) {
            $i++;
            $link = master_module::find($data->ID_Modul);
            $link->Ordinal = $i;
            $link->save();
        }

        return Response::json($link);
    }

    public function ListMenuordinal($id)
    {
        $datas = FacadesDB::select(" SELECT Ordinal, `Name` FROM master_module_laravel WHERE Parent = '$id' ORDER BY Ordinal ");
        return view('Setting.ListMenuordinal', compact('datas'));
    }

    public function ListMenuAkses($id)
    {
        $datas = FacadesDB::select(" SELECT B.Id_Level,B.Nama_level, A.ID_Modul_List FROM master_module_list_laravel A INNER JOIN master_level_laravel B ON A.`Level`=B.Id_Level WHERE A.ID_Modul='$id' ORDER BY Nama_level ");
        $Levels = FacadesDB::select("SELECT Id_Level,Nama_level FROM master_level_laravel WHERE `Status`='A' AND Id_Level<> '1' AND Id_Level<> '2' ORDER BY Nama_level");
        // dd($datas);
        return view('Setting.ListMenuAkses', compact('datas', 'Levels'));
    }

    public function ListMenuAksesCreat(Request $request)
    {
        $sub = FacadesDB::select(
            "
        SELECT
            A.ID_Modul AS id1,
            A.`Name` AS name1,
            B.ID_Modul AS id2,
            B.`Name` AS name2,
            C.ID_Modul AS id3,
            C.`Name` AS name3
        FROM
            master_module_laravel AS A
            LEFT JOIN
            master_module_laravel AS B
            ON
                A.Parent = B.ID_Modul
            LEFT JOIN
            master_module_laravel AS C
            ON
                B.Parent = C.ID_Modul
        WHERE
            A.ID_Modul = '" .
                $request->Parent .
                "'
        ",
        );

        foreach ($sub as $subs) {
            if ($subs->id1 != null && $request->aksesbaru != '') {
                $link2 = master_module_list::firstOrCreate([
                    'ID_Modul' => $subs->id1,
                    'Level' => $request->aksesbaru,
                ]);
                $link2->save();
                // dd($link2);
            }
            if ($subs->id2 != null && $request->aksesbaru != '') {
                $link2 = master_module_list::firstOrCreate([
                    'ID_Modul' => $subs->id2,
                    'Level' => $request->aksesbaru,
                ]);
                $link2->save();
                // dd($link2);
            }
            if ($subs->id3 != null && $request->aksesbaru != '') {
                $link2 = master_module_list::firstOrCreate([
                    'ID_Modul' => $subs->id3,
                    'Level' => $request->aksesbaru,
                ]);
                $link2->save();
            }
        }

        if ($request->aksesbaru != '') {
            $link3 = master_module_list::firstOrCreate([
                'ID_Modul' => $request->id,
                'Level' => $request->aksesbaru,
            ]);
            $link3->save();
        }

        return Response::json($link3);
    }

    public function ListMenuAksesUpdate(Request $request, $id)
    {
        for ($i = 1; $i <= $request->jumlah; $i++) {
            $isi = 'isi_' . $i;
            $id = 'id_' . $i;
            $link = master_module_list::find($request->$id);
            $link->Level = $request->$isi;
            $link->save();
        }
        $deleted = master_module_list::where('Level', null)->delete();
        return Response::json($deleted);
    }

    public function ListMenuDelet($link_id)
    {
        // dd($link_id);
        $link = master_module::destroy($link_id);
        return Response::json($link);
    }

    // !!------------------------------------ MenuQA ------------------------------------
    public function MenuQA(Request $request)
    {
        $MenuQA = FacadesDB::select("SELECT
                a.NAME AS menul1,
                b.NAME AS menul2,
                c.NAME AS menul3,
                d.NAME AS menul4,
            CASE
                    
                    WHEN d.ID_Modul IS NOT NULL THEN
                    d.ID_Modul
                    WHEN c.ID_Modul IS NOT NULL THEN
                    c.ID_Modul
                    WHEN b.ID_Modul IS NOT NULL THEN
                    b.ID_Modul ELSE a.ID_Modul
                END AS ID_Modul,
            CASE
  
                    WHEN d.ID_Modul IS NOT NULL THEN
                    d.`Name`
                    WHEN c.ID_Modul IS NOT NULL THEN
                    c.`Name`
                    WHEN b.ID_Modul IS NOT NULL THEN
                    b.`Name` ELSE a.`Name`
                END AS menu,
            CASE
                    
                    WHEN d.ID_Modul IS NOT NULL THEN
                    d.Icon
                    WHEN c.ID_Modul IS NOT NULL THEN
                    c.Icon
                    WHEN b.ID_Modul IS NOT NULL THEN
                    b.Icon ELSE a.Icon
                END AS Icon,
                z.ID_QA,
                z.Ordinal as Ordinal,
                Z.ID_User,
                y.`name`
            FROM
                master_module_laravel AS a
                LEFT JOIN master_module_laravel AS b ON a.id_modul = b.parent
                LEFT JOIN master_module_laravel AS c ON b.id_modul = c.parent
                LEFT JOIN master_module_laravel AS d ON c.id_modul = d.parent
                INNER JOIN master_quick_access AS Z ON z.ID_Modul =
            CASE
                    
                    WHEN d.ID_Modul IS NOT NULL THEN
                    d.ID_Modul
                    WHEN c.ID_Modul IS NOT NULL THEN
                    c.ID_Modul
                    WHEN b.ID_Modul IS NOT NULL THEN
                    b.ID_Modul ELSE a.ID_Modul
                    
                END INNER JOIN users AS y ON Y.id = Z.ID_User
            WHERE
                a.parent = 0
            ORDER BY
                `Name`,
                Ordinal ");
        // dd($MenuQA);

        return view('Setting.MenuQA', compact('MenuQA'));
    }

    public function MenuQACreat(Request $request)
    {
        // dd($request);
        // $link = user::create($request->all());
        $Name = $request->input('Name');
        $Menu = $request->input('Menu');
        $Ordinal = $request->input('Ordinal');

        $link = master_module_QA::create([
            'ID_User' => $Name,
            'ID_Modul' => $Menu,
            'Ordinal' => $Ordinal,
        ]);
        // dd($sub);
        return Response::json($link);
    }

    public function MenuQAEdit($link_id)
    {
        $link1 = FacadesDB::select(" SELECT
            A.*, b.Patch
        FROM
            master_quick_access A
            INNER JOIN master_module_laravel b ON A.ID_Modul = B.ID_Modul
        WHERE
            A.ID_QA = '$link_id'
        ");
        return Response::json($link1['0']);
    }

    public function MenuQUpdate(Request $request, $link_id)
    {
        if ($request->Ordinallama == null || $request->Ordinallama > $request->Ordinal) {
            $Ordinal = $request->Ordinal - 0.5;
        } else {
            $Ordinal = $request->Ordinal + 0.5;
        }

        $link = master_module_QA::find($link_id);
        $link->ID_User = $request->Name;
        $link->ID_Modul = $request->Menu;
        $link->Ordinal = $Ordinal;
        $link->save();

        $i = 0;
        $datas = FacadesDB::select(" SELECT A.ID_Modul, A.Ordinal, A.ID_QA FROM master_quick_access AS A WHERE A.ID_User='$request->Name' ORDER BY A.Ordinal ");
        foreach ($datas as $data) {
            $i++;
            $link = master_module_QA::find($data->ID_QA);
            $link->Ordinal = $i;
            $link->save();
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

    public function MenuQADelet($id)
    {
        // dd($id);
        $deleted = master_module_QA::find($id);
        $deleted->delete();
        return Response::json($deleted);
    }

    public function MenuQAordinal($id)
    {
        // dd($id);
        $datas = FacadesDB::select(" SELECT
                A.ID_Modul,
                A.Ordinal,
                B.Patch
            FROM
                master_quick_access AS A
                INNER JOIN master_module_laravel AS B ON A.ID_Modul = B.ID_Modul
            WHERE
                A.ID_User = '$id'
            ORDER BY
                A.Ordinal ");
        // dd($datas);
        return view('Setting.menuQAOrdinal', compact('datas'));
    }

    // !!------------------------------------ Extra ------------------------------------
    public function autouser(Request $request)
    {
        $data = User::select('name as value', 'id')
            ->where('status', 'A')
            ->where('name', 'LIKE', '%' . $request->get('search') . '%')
            ->limit(20)
            ->get();

        return response()->json($data);
    }

    public function autousererp(Request $request)
    {
        $search = $request->get('search');
        $data = FacadesDB::connection('erp')->select("
        SELECT
        SW AS `value`, id
        FROM
            `employee`
        WHERE
            Active = 'Y'
            AND employee.SW LIKE '%$search%'
        ORDER BY SW LIMIT 20
        ");

        return response()->json($data);
    }

    public function automodul(Request $request)
    {
        $search = $request->get('search');
        $data = FacadesDB::select("SELECT
            ID_Modul as id,
            Patch as value
        FROM
            master_module_laravel AS wh
        WHERE
            wh.Patch IS NOT NULL
            AND `Status` != 'N'
            AND Patch LIKE '%$search%'
            LIMIT 20
        ");

        return response()->json($data);
    }

    public function autoparent(Request $request)
    {
        // dd($request->search);
        $data = FacadesDB::select("SELECT
            a.ID_Modul AS id,
            CONCAT(
            CASE
                WHEN c.`Name` IS NOT NULL THEN
                CONCAT(c.`Name`, '/') ELSE ''
            END,
            CASE
                WHEN b.`Name` IS NOT NULL THEN
                CONCAT(b.`Name`, '/') ELSE ''
            END,
            CASE
                WHEN a.`Name` IS NOT NULL THEN
                CONCAT(a.`Name`, '/') ELSE ''
            END) as value
        FROM
            master_module_laravel a
            LEFT JOIN master_module_laravel AS b ON b.id_modul = a.parent
            LEFT JOIN master_module_laravel AS c ON c.id_modul = b.parent
        WHERE
            ( a.Patch = '' OR a.Patch IS NULL )
            AND (a.`Name` LIKE '%$request->search%' OR b.`Name` LIKE '%$request->search%' OR c.`Name` LIKE '%$request->search%')
        ");

        return response()->json($data);
    }

    public function searchSKU(Request $request)
    {
        // dd($request);
        $search = $request->search;
        $data = FacadesDB::select(" SELECT
                SKU,
                SW,
                IF(SKU IS NULL, SW, SKU) AS value
            FROM
                product 
            WHERE
                SKU LIKE '$search%' 
                OR SW LIKE '$search%' 
                LIMIT 20
        ");

        // $data = [];
        // foreach ($SKUs as $SKU) {
        //     $data[] = [
        //         'id' => $SKU->SKU,
        //         'name' => $SKU->SW,
        //     ];
        // }

        // dd($data);

        return response()->json($data);
    }

    // !!------------------------------------ sampel ------------------------------------
    public function demotabel()
    {
        $query = product::select('ID', 'EntryDate', 'SW', 'Description', 'SKU')
            ->orderByDesc('ID')
            ->limit(100)
            ->Paginate(30);

        // dd($query);

        return view('Setting.tabel', compact('query'));
    }
    public function forms_basic()
    {
        $tablename = 'workallocation';
        $UserID = '327';
        $Module = '166';
        $carilists = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);

        return view('Setting.forms_basic', compact('carilists'));
    }

    public function forms_basicLihat($no, $id)
    {
        // dd($no, $id);
        $datas = FacadesDB::select('SELECT id, SW, Description, CAST( EntryDate AS DATE) EntryDate, Photo FROM `product` ORDER BY ID DESC LIMIT 5 ');
        // dd($datas);

        if ($no == '1') {
            return view('Setting.forms_BasicLihat', compact('datas'));
        }
        if ($no == '2') {
            return view('Setting.forms_BasicEdit', compact('no'));
        }
        if ($no == '3') {
            return view('Setting.forms_BasicEdit', compact('no', 'datas'));
        }
    }

    public function forms_basicupdate(Request $request, $id)
    {
        dd($request);

        return view('Setting.forms_basic', compact('carilists'));
    }
}
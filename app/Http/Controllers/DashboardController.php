<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB as FacadesDB;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $username = Auth::user()->name;
        // $username = 'niko';
        $pengumuman = FacadesDB::connection('erp')->select("SELECT e.SW,e.Rank FROM erp.employee e WHERE e.SW='$username'");

        if($pengumuman[0]->Rank == 'Direktur' || $pengumuman[0]->Rank == 'Manager' || $pengumuman[0]->Rank == 'Supervisor'){
            // dd($pengumuman[0]->Rank);
            $pengumuman ='1';
        }else{
            $pengumuman = '0';
        }
        
        return view('index', compact('pengumuman'), ['title' => 'home']);
    }

    public function todomenu(Request $request)
    {
        $username = Auth::user()->name;
        $todo1 = FacadesDB::connection('messaging')->select("SELECT * FROM `todolist` WHERE `Name` = '$username' AND `status` = '$request->id' ORDER BY created_at DESC, id DESC LIMIT 20");
        return view('setting.todomenu', compact('todo1'));
    }
 
    public function Pengumumanmenumenu(Request $request)
    {
        // dd($request->id);
        $tgl = date('Y-m-d');
        if($request->id =='P'){
            $filter = ">=";
        }else{$filter = "<";}
        $username = Auth::user()->name;
        $pengumumans = FacadesDB::connection('messaging')->select("SELECT
            e.Department , d.HigherRank, a.Note, TransDate, a.ValidToDate, a.ID, a.UserName
        FROM
            employee e
            INNER JOIN department d ON e.Department = d.ID 
            INNER JOIN announcement a ON d.HigherRank = a.AnnounceTo OR a.AnnounceTo = 0 
        WHERE e.SW = '$username' AND a.ValidToDate $filter '$tgl' 
        ORDER BY created_at DESC, id DESC LIMIT 20");
        return view('setting.pengumumanmenu', compact('pengumumans'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}

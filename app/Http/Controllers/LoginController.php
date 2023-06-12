<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $prev = url()->previous();
        return view('login', compact('prev'));
    }

    public function check_login(Request $request)
    {
        // dd($request->password == "");

        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $userlevel = Auth::user()->level;
            $username = Auth::user()->name;
            $status = Auth::user()->status;

            $LevelUser = FacadesDB::table('master_level_laravel')
                ->select('nama_level')
                ->where('ID_level', '=', $userlevel)
                ->get();

            foreach ($LevelUser as $level) { 
            }
            if (isset($level)) {
                $res = $level->nama_level;
            } else {
                $res = '0';
            }

            $nameuser = FacadesDB::table('employee')
                ->select('ID')
                ->where('SW', '=', $username)
                ->get();

            foreach ($nameuser as $nameusers) {
            }
            if (isset($nameusers)) {
                $userid = $nameusers->ID;
            } else {
                $userid = '0';
            }

            $location = FacadesDB::connection('erp')
                ->table('employee')
                ->join('department', 'employee.department', '=', 'department.id')
                ->select('department.Location', 'department.ID','employee.SW', 'employee.Description')
                ->where('employee.SW', '=', $username)
                ->get();
            
            foreach ($location as $locations) {
            }
            if (isset($locations)) {
                $lokasi = $locations->Location;
                $empSW = $locations->SW;
                $deptid = $locations->ID;
                $fullname = $locations->Description;
            } else {
                $lokasi = '0';
                $deptid = '0';
            }

            $request->session()->put('LevelUser', $res);
            $request->session()->put('iduser', $userid);
            $request->session()->put('location', $lokasi);
            $request->session()->put('iddept', $deptid);
            $request->session()->put('UserEntry', $empSW);
            $request->session()->put('fullname', $fullname);
            $request->session()->put('hostfoto', 'http://192.168.3.100:8383');

            if ($request->prev && !str_contains($request->prev, "gantipswd")) {
                $prev = $request->prev;
            } else {
                $prev = '/';
            }

            // dd($request->name, $request->password);

            if ($status == "N") {
                return response()->json([
                    'success' => true,
                    'message' => 'NonActive',
                ]);
            } 
            else if (strtolower($request->name) == strtolower($request->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'resetpassword',
                ]);
            }  
            else {
                return response()->json([
                    'success' => true,
                    'message' => $prev,
                ]); 
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal!',
            ]);
        }
    }
}


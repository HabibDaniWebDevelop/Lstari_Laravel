<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\messaging\sossight;
use App\Models\rndnew\notificationlaravel;


class MessagingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function Messaging_list(Request $request)
    { 
        $username=Auth::user()->name;
        $data = FacadesDB::connection('messaging')
        ->select("
        SELECT ID,Sender AS Dari,Description,Date,`Status`,NoteReplay, 1 AS Respon FROM `sossight` WHERE ToUser='$username' AND (`Status`!='S' AND `Status`!='C') UNION
        SELECT ID,ToUser AS Dari,Description,Date,`Status`,NoteReplay, 0 AS Respon FROM `sossight` WHERE sender='$username' AND Active='1' ORDER BY ID DESC
        ");

        $data2 = FacadesDB::connection('messaging')
        ->select(" SELECT ID, 1 AS Respon  FROM `sossight` WHERE ToUser='$username' AND (`Status`!='S' AND `Status`!='C') ");
        $count = count($data2);
        
        return view('setting.Messaging_list', compact('data','count'));
    }

    public function Messaging_count()
    {
        $username=Auth::user()->name;
        $data = FacadesDB::connection('messaging')
        ->select("
        SELECT ID, 1 AS Respon  FROM `sossight` WHERE ToUser='$username' AND (`Status`!='S' AND `Status`!='C')
        ");

        $count = count($data);
        // dd($count);
        return view('setting.Messaging_count', compact('count'));
    }

    public function Messaging_write($id)
    {
        // dd($id);
        $data_array = explode("&", $id);  
        if($data_array[0] != '0'){
            $sossight = FacadesDB::connection('messaging')->select("SELECT Description FROM `sossight` WHERE ID = '".$data_array[0]."' ");
            $Description = $sossight[0]->Description;
        }
        else{
            $Description = '';
        }
            
        $datas = FacadesDB::select("SELECT `name` FROM users WHERE `status` = 'A' ORDER BY `name` ");
        // dd($Description);
        
        return view('setting.Messaging_write', compact('id','datas','Description')); 
    }

    public function Messaging_read($id)
    {
        $link = sossight::find($id);
        $link->Active = '0';
        $link->save();
        // dd($link);

        return Response::json($link);
    }

    public function Messaging_readall()
    {
        $username=Auth::user()->name;
        $link = sossight::where('Sender', $username)
        ->where('Active', '1')
        ->Where(function($query) {
            $query->where('Status', 'S')
                  ->orwhere('Status', 'C');
        })
        ->update(['Active' => '0']);
        // dd($link);
        return Response::json($link);
    }

    public function messagingCreat(Request $request)
    {
        $tglfull = date('Y-m-d h:i:s');
        $tgl = date('Y-m-d');

        $cekemployee = FacadesDB::select("SELECT `name` FROM users WHERE `name` = '$request->pilihh' AND `status` = 'A' ");
        
        if(count($cekemployee) == 0){
            return response()->json([
                'success' => false,
                'message' => 'Nama Tidak Terdaftar!!',
            ], 400);
        }

        $link = sossight::create([
            'Sender'        => $request->name,
            'Description'   => $request->pesan,
            'Date'      => $tglfull,
            'DateSend'  => $tgl,
            'ToUser'    => $request->pilihh,
            'Status'    => 'Q',
            'Active'    => '1'
        ]);

        if($link){
            return response()->json([
                'success' => true,
                'message' => 'Simpan Berhasil!!',
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Simpan Gagal!!',
            ], 400);
        }

    }

    public function messagingUpdate(Request $request, $id)
    {

        if($request->pilihh == '0'){
            $link = sossight::find($id);
            $link->NoteReplay = $request->pesan;
            $link->Status = 'P';
            $link->save();
        }else{
            $link = sossight::find($id);
            $link->Status = $request->pilihh;
            $link->save();
        }
        
        // dd($link);
        return Response::json($link);
    }

    public function Notif_count()
    {
        $username=Auth::user()->name;
        $data = FacadesDB::select("
        SELECT ID FROM notificationlaravel WHERE UserName='$username' AND Active='1' ORDER BY ID DESC
        ");

        $count = count($data);
        // dd($count);
        return view('setting.Notification_count', compact('count'));
    }

    public function Notif_list(Request $request)
    {
        $username=Auth::user()->name;
        $data = FacadesDB::select("
        SELECT * FROM notificationlaravel WHERE UserName='$username' AND Active='1' ORDER BY ID DESC
        ");

        // dd($data);
        $count = count($data);
        return view('setting.Notification_list', compact('data','count'));
    }

    public function Notif_read($id)
    {
        $link = notificationlaravel::find($id);
        $link->Active = '0';
        $link->save();
        // dd($link);
        return Response::json($link);
    }

    public function Notif_readall()
    {
        $username=Auth::user()->name;
        $link = notificationlaravel::where('UserName', $username)
        ->where('Active', '1')
        ->Where(function($query) {
            $query->where('Status', 'S')
                  ->orwhere('Status', 'C');
        })
        ->update(['Active' => '0']);
        // dd($link);
        return Response::json($link);
    }

}
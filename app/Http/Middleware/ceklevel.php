<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ceklevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$leves)
    {
        // // setting lama
        // if ((in_array($request->user()->level, $leves)) and (in_array($request->user()->status, ['A']))){
        //     return $next($request);
        // }

        // return redirect('/');

        // Cek apakah pengguna telah login
        if (!$request->user()) {
            return redirect('/login');
        }

        // Ambil id level pengguna dari tabel users
        $userLevel = $request->user()->level;

        // Cek apakah level pengguna termasuk dalam daftar level yang diizinkan dan memiliki status 'A'
        $level = DB::table('master_level_laravel')
            ->where('Id_Level', $userLevel)
            ->where('Status', 'A')
            ->first();
        if (!$level || !in_array($level->Id_Level, $leves)) {
            return redirect('/');
        }

        // Cek apakah pengguna memiliki akses ke modul yang diizinkan
        $module = DB::table('master_module_laravel AS A')
            ->join('master_module_list_laravel AS B', 'A.ID_Modul', '=', 'B.ID_Modul')
            ->where('B.Level', $userLevel)
            ->where('A.Status', 'A')
            ->first();

        if (!$module || !in_array($module->Level, $leves)) {
            return $userLevel == '1' ? $next($request) : redirect('/');
        }

        // Jika pengguna memenuhi kriteria, maka izinkan akses ke halaman yang dimaksud
        return $next($request);
    }
}

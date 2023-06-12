<?php
 
namespace App\View\Composers;
 
use Illuminate\View\View;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

class MenuQuickAccess
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */

    public function compose(View $view)
    {
        $userid=Auth::user()->id;
        $QA = FacadesDB::select(" SELECT
                B.Icon,
                B.`Name`,
                B.Patch,
                A.Ordinal 
            FROM
                `master_quick_access` A
                LEFT JOIN master_module_laravel B ON A.ID_Modul = B.ID_Modul 
            WHERE
                A.ID_User = '$userid' 
                AND B.`Status` = 'A'
            ORDER BY
                A.Ordinal
            ");

            // dd($categories);
        $view->with('menus',$QA);
    }
}
<?php
 
namespace App\View\Composers;
 
use Illuminate\View\View;
use App\Models\menu_list;
use Illuminate\Support\Facades\Auth;


class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */

    public function compose(View $view)
    {
        $level=Auth::user()->level;
        //  {{ dd($tes); }} 

        if($level=='1') {
            $categories=menu_list::from( 'master_module_laravel as a' )
            ->select('a.Icon','a.Name','a.ID_Modul')
            ->where('a.Parent', 0) 
            ->where('a.Status', '!=', 'N')
            ->orderBy('a.Ordinal','ASC')
            ->get();
            // dd($categories);
        $view->with('menu_categoris',$categories);
        }

        else if($level=='2') {
            $categories=menu_list::from( 'master_module_laravel as a' )
            ->select('a.Icon','a.Name','a.ID_Modul')
            ->where('a.Parent', 0) 
            ->where('a.Status', 'A')
            ->orderBy('a.Ordinal','ASC')
            ->get();
            // dd($categories);
        $view->with('menu_categoris',$categories);
        }

        else{

            $categories=menu_list::from( 'master_module_laravel as a' )
            ->Join('master_module_list_laravel as b', 'b.ID_Modul', '=', 'a.ID_Modul')
            ->select('a.Icon','a.Name','a.ID_Modul')
            ->where(function($query) use ($level) {
                $query->where('a.Parent', 0)
                      ->where('b.Level', $level);
            })
            ->orWhere(function($query) {
                $query->where('a.Parent', 0)
                    ->where('b.LEVEL', 1);
            })
            ->where('a.Status', 'A')
            
            ->orderBy('a.Ordinal','ASC')
            ->get();
            
            // dd($categories);
        $view->with('menu_categoris',$categories);
        }


    }
}
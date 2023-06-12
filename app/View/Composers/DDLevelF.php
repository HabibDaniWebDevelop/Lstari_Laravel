<?php
 
namespace App\View\Composers;
 
use Illuminate\View\View;
use App\Models\master_level;

class DDLevelF
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */

    public function compose(View $view)
    {

        $levels = master_level::select('Id_Level','Nama_level')
        ->where('Status', 'A')
        ->orderBy('Nama_level')
        ->get();
            // dd($categories);
        $view->with('levels',$levels);
    }
}
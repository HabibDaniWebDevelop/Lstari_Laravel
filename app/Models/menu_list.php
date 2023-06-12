<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Facade\Ignition\Tabs\Tab;

class menu_list extends Model
{
    use HasFactory;
    protected $table = "master_module_laravel";
    protected $primaryKey = 'ID_Modul';

    public function childs()
    {

        $level = Auth::user()->level;
        //  {{ dd($tes); }} 

        if ($level == '1') {

            return $this->hasMany(menu_list::class, 'Parent')
                ->where("Status", "!=", "N")
                ->orderBy('Ordinal', 'ASC');
        } else if ($level == '2') {

            return $this->hasMany(menu_list::class, 'Parent')
                ->where("Status", "=", "A")
                ->orderBy('Ordinal', 'ASC');
        } else {

            return $this->hasMany(menu_list::class, 'Parent')
                ->from('master_module_laravel')
                ->Join('master_module_list_laravel as b', function($join) use($level) {
                    $join->on('b.ID_Modul', '=', 'master_module_laravel.ID_Modul')
                        ->where(function($query) use($level) {
                            $query->where('b.Level', $level)
                                ->orWhere('b.Level', '1');
                        });
                })
                ->select('Icon', 'Name', 'Patch', 'master_module_laravel.ID_Modul')
                ->where('master_module_laravel.Status', 'A')
                ->orderBy('Ordinal', 'ASC');

            // dd($this); 
        }
    }
}

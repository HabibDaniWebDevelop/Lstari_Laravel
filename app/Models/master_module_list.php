<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_module_list extends Model
{
    use HasFactory;
     /**
    * fillable
    *
    * @var array
    */ 

    protected $table = 'master_module_list_laravel';
    protected $guarded =['ID_Modul_List'];
    protected $primaryKey = 'ID_Modul_List';
}

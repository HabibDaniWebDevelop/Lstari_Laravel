<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_module extends Model
{
    use HasFactory;
     /**
    * fillable
    *
    * @var array
    */ 

    protected $table = 'master_module_laravel';
    protected $guarded =['ID_Modul'];
    protected $primaryKey = 'ID_Modul';
}

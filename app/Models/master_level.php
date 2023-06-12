<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_level extends Model
{
    use HasFactory;
     /**
    * fillable
    *
    * @var array
    */ 

    protected $table = 'master_level_laravel';
    protected $guarded =['Id_Level'];
    protected $primaryKey = 'Id_Level';
}

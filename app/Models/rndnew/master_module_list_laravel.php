<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_module_list_laravel extends Model
{
    use HasFactory;
    protected $table = 'master_module_list_laravel';
    protected $guarded = ['ID_Modul_List'];
    protected $primaryKey = 'ID_Modul_List';
}

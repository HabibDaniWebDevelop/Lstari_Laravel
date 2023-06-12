<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_module_QA extends Model
{
    use HasFactory;
     /**
    * fillable
    *
    * @var array
    */ 

    protected $table = 'master_quick_access';
    protected $guarded =['ID_QA'];
    protected $primaryKey = 'ID_QA';
}

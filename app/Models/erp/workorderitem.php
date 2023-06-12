<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workorderitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workorderitem';
    protected $guarded = [];
    // protected $primaryKey = 'ID';
    public $timestamps = false;
}

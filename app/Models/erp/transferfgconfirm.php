<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferfgconfirm extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'transferfgconfirm';
    protected $guarded = [];
    public $timestamps = false;
}
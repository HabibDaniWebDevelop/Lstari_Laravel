<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferfgitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'transferfgitem';
    protected $guarded = [];
    public $timestamps = false;
}
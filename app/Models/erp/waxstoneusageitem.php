<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxstoneusageitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxstoneusageitem';
    protected $guarded = [];
    public $timestamps = false;
}
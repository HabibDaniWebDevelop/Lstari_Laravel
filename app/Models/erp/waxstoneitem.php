<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxstoneitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxstoneitem';
    protected $guarded = [];
    public $timestamps = false;
}
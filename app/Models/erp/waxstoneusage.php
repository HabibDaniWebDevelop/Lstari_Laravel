<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxstoneusage extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxstoneusage';
    protected $guarded = [];
    public $timestamps = false;
}
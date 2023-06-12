<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxstone extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxstone';
    protected $guarded = [];
    public $timestamps = false;
}
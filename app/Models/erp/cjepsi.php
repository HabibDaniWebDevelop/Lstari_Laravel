<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cjepsi extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'cjepsi';
    protected $guarded = [];
    public $timestamps = false;
}

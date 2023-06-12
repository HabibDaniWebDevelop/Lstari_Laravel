<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workdate extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workdate';
    protected $guarded = [];
    public $timestamps = false;
}

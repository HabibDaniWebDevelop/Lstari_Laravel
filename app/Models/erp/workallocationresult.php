<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workallocationresult extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workallocationresult';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workallocationitem extends Model
{
    use HasFactory;
    // protected $connection = 'erp';
    protected $table = 'workallocationitem';
    protected $guarded = [];
    public $timestamps = false;
}
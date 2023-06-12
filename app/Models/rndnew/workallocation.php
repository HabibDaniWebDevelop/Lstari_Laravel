<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workallocation extends Model
{
    use HasFactory;
    // protected $connection = 'erp';
    protected $table = 'workallocation';
    protected $guarded = [];
    public $timestamps = false;
}
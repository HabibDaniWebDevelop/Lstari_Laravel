<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appworkpercent extends Model
{
    use HasFactory;
    // protected $connection = ''; // not use protected connection because default connection is rndnew
    protected $table = 'appworkpercent';
    protected $guarded = [];
    public $timestamps = false;
}
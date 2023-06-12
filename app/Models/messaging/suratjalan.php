<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratjalan extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'suratjalan';
    protected $guarded = [];  
    public $timestamps = false;
}

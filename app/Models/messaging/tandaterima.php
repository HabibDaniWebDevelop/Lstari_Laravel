<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tandaterima extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'tandaterima';
    protected $guarded = [];  
    public $timestamps = false;
}

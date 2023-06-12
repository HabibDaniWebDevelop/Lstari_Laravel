<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tandaterimaitem extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'tandaterimaitem';
    protected $guarded = [];  
    public $timestamps = false;
}

<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengantarpengembalian extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'pengantarpengembalian';
    protected $guarded = [];
    public $timestamps = false;
}

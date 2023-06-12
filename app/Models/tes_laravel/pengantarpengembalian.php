<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengantarpengembalian extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'pengantarpengembalian';
    protected $guarded = [];
    public $timestamps = false;
}

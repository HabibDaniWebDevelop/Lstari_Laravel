<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengantarpengembalianitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'pengantarpengembalianitem';
    protected $guarded = [];
    public $timestamps = false;
}

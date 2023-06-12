<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grafis extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'grafis';
    protected $guarded = [];
    public $timestamps = false;
}

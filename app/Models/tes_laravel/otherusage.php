<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otherusage extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'otherusage';
    protected $guarded = [];
    public $timestamps = false;
}

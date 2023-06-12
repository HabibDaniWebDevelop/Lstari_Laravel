<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxstone extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'waxstone';
    protected $guarded = [];
    public $timestamps = false;
}
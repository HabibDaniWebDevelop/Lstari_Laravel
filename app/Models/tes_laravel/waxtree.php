<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxtree extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'waxtree';
    protected $guarded = [];
    public $timestamps = false;
}
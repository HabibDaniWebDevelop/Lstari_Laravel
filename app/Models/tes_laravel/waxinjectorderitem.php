<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxinjectorderitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    // protected $connection = 'dev';
    protected $table = 'waxinjectorderitem';
    protected $guarded = [];
    public $timestamps = false;
}
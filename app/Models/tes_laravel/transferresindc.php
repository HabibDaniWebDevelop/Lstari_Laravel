<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferresindc extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'transferresindc';
    protected $guarded = [];
    public $timestamps = false;
}
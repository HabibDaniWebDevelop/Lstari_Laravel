<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worklist3dpproduction extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'worklist3dpproduction';
    protected $guarded = [];
    public $timestamps = false;
}
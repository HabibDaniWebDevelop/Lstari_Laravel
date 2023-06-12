<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worklist3dpproductionitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'worklist3dpproductionitem';
    protected $guarded = [];
    public $timestamps = false;
}
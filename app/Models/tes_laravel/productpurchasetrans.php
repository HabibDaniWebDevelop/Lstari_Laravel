<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productpurchasetrans extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'productpurchasetrans';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

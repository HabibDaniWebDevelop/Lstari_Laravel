<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cjepsi extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'cjepsi';
    protected $guarded = [];
    public $timestamps = false;
}

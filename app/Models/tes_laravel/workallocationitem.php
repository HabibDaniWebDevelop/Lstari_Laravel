<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workallocationitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'workallocationitem';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workdate extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'workdate';
    protected $guarded = [];
    public $timestamps = false;
}

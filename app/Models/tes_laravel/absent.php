<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absent extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'absent';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cast extends Model
{
    use HasFactory;
    // protected $connection = 'erp';
    protected $table = 'cast';
    protected $guarded = [];
    public $timestamps = false;
}
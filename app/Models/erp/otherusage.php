<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otherusage extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'otherusage';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class castitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'castitem';
    protected $guarded = [];
    public $timestamps = false;
}
<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxtree extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxtree';
    protected $guarded = [];
    public $timestamps = false;
}

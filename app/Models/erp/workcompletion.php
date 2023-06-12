<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workcompletion extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workcompletion';
    protected $guarded = [];
    public $timestamps = false;
}

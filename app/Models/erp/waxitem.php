<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxitem';
    protected $guarded = [];
    public $timestamps = false;
}
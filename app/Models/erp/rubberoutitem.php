<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberoutitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberoutitem';
    protected $guarded = [];
    public $timestamps = false;
}
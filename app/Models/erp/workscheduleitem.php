<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workscheduleitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workscheduleitem';
    protected $guarded = [];
    public $timestamps = false;
}
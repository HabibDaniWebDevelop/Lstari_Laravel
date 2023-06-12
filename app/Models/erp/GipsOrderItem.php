<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GipsOrderItem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'GipsOrderItem';
    protected $guarded = [];
    public $timestamps = false;
}
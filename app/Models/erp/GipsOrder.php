<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GipsOrder extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'gipsorder';
    protected $guarded = [];
    public $timestamps = false;
}
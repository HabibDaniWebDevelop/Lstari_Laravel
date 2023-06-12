<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSC extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'stocksc';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
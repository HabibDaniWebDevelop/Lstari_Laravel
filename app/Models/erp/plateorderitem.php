<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plateorderitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'plateorderitem';
    protected $guarded = [];
    public $timestamps = false;
}
<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class additionalfood extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'additionalfood';
    protected $guarded = [];
    public $timestamps = false;
}

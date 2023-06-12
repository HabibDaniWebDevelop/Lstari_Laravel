<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plateorder extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'plateorder';
    protected $guarded = [];
    public $timestamps = false;
}
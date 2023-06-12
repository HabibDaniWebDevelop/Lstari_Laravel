<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberplate extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberplate';
    protected $guarded = [];
    public $timestamps = false;
}

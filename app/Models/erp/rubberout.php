<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberout extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberout';
    protected $guarded = [];
    public $timestamps = false;
}
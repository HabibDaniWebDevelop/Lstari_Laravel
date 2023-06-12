<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberregistration extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberregistration';
    protected $guarded = [];
    public $timestamps = false;
}
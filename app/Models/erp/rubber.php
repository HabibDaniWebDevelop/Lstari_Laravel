<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubber extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubber';
    protected $guarded = [];
    public $timestamps = false;
}
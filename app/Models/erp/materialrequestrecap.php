<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialrequestrecap extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'materialrequestrecap';
    protected $guarded = [];
    public $timestamps = false;
}

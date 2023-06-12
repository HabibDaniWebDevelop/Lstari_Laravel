<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialrequest extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'materialrequest';
    protected $guarded = [];
    public $timestamps = false;
}

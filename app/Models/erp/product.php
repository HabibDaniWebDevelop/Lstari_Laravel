<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'product';
    protected $guarded = ['ID'];
}

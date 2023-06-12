<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productsize extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'productsize';
    protected $guarded = ['ID'];
}

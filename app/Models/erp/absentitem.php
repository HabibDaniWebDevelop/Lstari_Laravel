<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absentitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'absentitem';
    protected $guarded = [];
    public $timestamps = false;
}

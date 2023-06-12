<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lateexceptionitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'lateexceptionitem';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lateexception extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'lateexception';
    protected $guarded = [];
    public $timestamps = false;
}

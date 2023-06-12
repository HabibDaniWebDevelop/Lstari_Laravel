<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workcompletionitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workcompletionitem';
    protected $guarded = [];
    public $timestamps = false;
}

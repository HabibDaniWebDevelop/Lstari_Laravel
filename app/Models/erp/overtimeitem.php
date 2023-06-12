<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overtimeitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'overtimeitem';
    protected $guarded = [];
    public $timestamps = false;
}

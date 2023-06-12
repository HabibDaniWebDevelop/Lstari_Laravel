<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workhour extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workhour';
    protected $guarded = [];
    public $timestamps = false;
}

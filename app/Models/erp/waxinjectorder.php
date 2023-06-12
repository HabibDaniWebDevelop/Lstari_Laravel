<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxinjectorder extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxinjectorder';
    protected $guarded = [];
    public $timestamps = false;
}
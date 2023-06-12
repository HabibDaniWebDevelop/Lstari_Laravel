<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxclean extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxclean';
    protected $guarded = [];
    public $timestamps = false;
}
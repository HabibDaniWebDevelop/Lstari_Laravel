<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waxtreetransfer extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxtreetransfer';
    protected $guarded = [];
    public $timestamps = false;
}
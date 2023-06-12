<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class beritaacara extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'beritaacara';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferrmitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'transferrmitem';
    protected $guarded = [];
    public $timestamps = false;
}
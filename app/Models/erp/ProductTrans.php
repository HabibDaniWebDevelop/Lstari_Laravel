<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTrans extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'ProductTrans';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

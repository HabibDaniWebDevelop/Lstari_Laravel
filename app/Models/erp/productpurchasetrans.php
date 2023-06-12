<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productpurchasetrans extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'productpurchasetrans';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

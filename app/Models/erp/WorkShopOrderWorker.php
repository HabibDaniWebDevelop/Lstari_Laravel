<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopOrderWorker extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'WorkShopOrderWorker';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

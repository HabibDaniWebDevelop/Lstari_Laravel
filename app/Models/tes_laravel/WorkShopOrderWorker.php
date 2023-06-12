<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopOrderWorker extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'WorkShopOrderWorker';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

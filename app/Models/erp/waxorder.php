<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxorder extends Model{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxorder';
    protected $guarded = ['ID'];
    protected $fillable = ['ID', 'UserName', 'TransDate', 'WorkOrderStart', 'WorkOrderEnd'];
    public $timestamps = false;
}
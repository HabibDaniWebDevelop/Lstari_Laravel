<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxinejctorderitem extends Model{
    use HasFactory;
    protected $table = 'waxinejctorderitem';
    protected $guarded = ['ID'];
    protected $fillable = ['ID', 'UserName', 'TransDate', 'WorkOrderStart', 'WorkOrderEnd'];
    public $timestamps = false;
}
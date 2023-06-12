<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxinejctorder extends Model{
    use HasFactory;
    protected $table = 'waxinejctorder';
    protected $guarded = ['IDM'];
    protected $fillable = ['IDM',"Ordinal", 'WorkOrder', 'WorkOrderOrd' , 'Product', 'Inject'];
    public $timestamps = false;
}
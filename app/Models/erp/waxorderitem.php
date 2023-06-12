<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxorderitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxorderitem';
    protected $guarded = ['IDM'];
    protected $fillable = ['IDM',"Ordinal", 'WorkOrder', 'WorkOrderOrd' , 'Product', 'Inject'];
    public $timestamps = false;
}

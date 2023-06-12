<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class erp_product extends Model
{
    use HasFactory;
    /**
    * fillable
    *
    * @var array
    */ 
    // protected $fillable = [
    //     'ID', 'UserName', 'LinkID', 'Active'
    // ];
    protected $connection = 'erp';
    protected $table = 'product';
    protected $guarded =['ID'];
}
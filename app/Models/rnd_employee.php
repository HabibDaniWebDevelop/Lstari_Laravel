<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rnd_employee extends Model
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
    protected $connection = 'local';
    protected $table = 'employee';
    protected $guarded =['id'];
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dev_ProductTrans extends Model
{
    protected $connection = 'dev';
    protected $table = 'ProductTrans';
    protected $guarded =['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

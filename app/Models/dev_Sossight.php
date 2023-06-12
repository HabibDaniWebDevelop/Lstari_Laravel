<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dev_Sossight extends Model
{
    protected $connection = 'dev';
    protected $table = 'sossight';
    protected $guarded =['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

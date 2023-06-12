<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class messaging_Sossight extends Model
{
    protected $connection = 'messaging';
    protected $table = 'sossight';
    protected $guarded =['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
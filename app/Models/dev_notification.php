<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dev_notification extends Model
{
    protected $connection = 'dev';
    protected $table = 'notification';
    protected $guarded =['ID'];
    protected $primaryKey = 'ID';
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rnd_notification extends Model
{
    protected $connection = 'local';
    protected $table = 'notificationlaravel';
    protected $guarded =['ID'];
    protected $primaryKey = 'ID';
}

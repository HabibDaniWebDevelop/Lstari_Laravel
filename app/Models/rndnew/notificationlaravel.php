<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notificationlaravel extends Model
{
    use HasFactory;
    protected $table = 'notificationlaravel';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

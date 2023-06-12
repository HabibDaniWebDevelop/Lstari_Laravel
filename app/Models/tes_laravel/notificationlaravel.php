<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notificationlaravel extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'notificationlaravel';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

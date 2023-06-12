<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suratjalanitem extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'suratjalanitem';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lateexception extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'lateexception';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lateexceptionitem extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'lateexceptionitem';
    protected $guarded = [];
    public $timestamps = false;
}

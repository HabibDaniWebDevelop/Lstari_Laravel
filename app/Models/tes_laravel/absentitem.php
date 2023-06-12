<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absentitem extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'absentitem';
    protected $guarded = [];
    public $timestamps = false;
}

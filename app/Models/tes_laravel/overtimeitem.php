<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overtimeitem extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'overtimeitem';
    protected $guarded = [];
    public $timestamps = false;
}

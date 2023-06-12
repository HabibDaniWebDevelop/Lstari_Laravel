<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkclock extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'checkclock';
    protected $guarded = [];
    public $timestamps = false;
}

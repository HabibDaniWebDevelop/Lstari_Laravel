<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clockmanual extends Model
{
    use HasFactory;
    protected $connection = 'arik';
    protected $table = 'clockmanual';
    protected $guarded = [];
    public $timestamps = false;
}

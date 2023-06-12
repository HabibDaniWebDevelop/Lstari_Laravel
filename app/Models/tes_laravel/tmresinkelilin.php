<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmresinkelilin extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'tmresinkelilin';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

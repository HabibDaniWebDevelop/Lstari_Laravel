<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmresinkelilinitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'tmresinkelilinitem';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

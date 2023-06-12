<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberregistrationitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'rubberregistrationitem';
    protected $guarded = [];
    public $timestamps = false;
}
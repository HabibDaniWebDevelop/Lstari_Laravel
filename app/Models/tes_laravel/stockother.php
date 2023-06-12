<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockother extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'stockother';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transferrm extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'transferrm';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
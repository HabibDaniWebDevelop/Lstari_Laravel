<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workshoporder extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'workshoporder';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

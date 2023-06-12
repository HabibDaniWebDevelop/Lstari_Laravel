<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxtreetransferitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'waxtreetransferitem';
    protected $guarded = [];
    public $timestamps = false;
}
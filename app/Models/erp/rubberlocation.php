<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberlocation extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberlocation';
    protected $guarded = [];
    public $timestamps = false;
}
<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberlocationusage extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberlocationusage';
    protected $guarded = [];
    public $timestamps = false;
}
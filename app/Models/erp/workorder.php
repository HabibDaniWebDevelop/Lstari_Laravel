<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workorder extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workorder';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

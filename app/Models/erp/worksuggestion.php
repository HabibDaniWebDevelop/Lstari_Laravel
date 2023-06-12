<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worksuggestion extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'worksuggestion';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

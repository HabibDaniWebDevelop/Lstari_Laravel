<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worksuggestion extends Model
{
    use HasFactory;

    protected $table = 'worksuggestion';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
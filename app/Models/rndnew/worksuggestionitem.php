<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class worksuggestionitem extends Model
{
    use HasFactory;

    protected $table = 'worksuggestionitem';
    protected $guarded = [];
    // protected $primaryKey = 'ID';
    public $timestamps = false;
}
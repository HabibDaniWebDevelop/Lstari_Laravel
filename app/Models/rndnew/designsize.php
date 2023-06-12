<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class designsize extends Model
{
    use HasFactory;
    protected $table = 'designsize';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
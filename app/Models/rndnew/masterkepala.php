<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterkepala extends Model{
    use HasFactory;
    protected $table = 'masterkepala';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}
<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model{
    use HasFactory;
    protected $table = 'product';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}
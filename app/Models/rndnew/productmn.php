<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productmn extends Model{
    use HasFactory;
    protected $table = 'productmn';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

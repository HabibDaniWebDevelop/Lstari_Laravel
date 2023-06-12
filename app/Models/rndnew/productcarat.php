<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productcarat extends Model{
    use HasFactory;
    protected $table = 'productcarat';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

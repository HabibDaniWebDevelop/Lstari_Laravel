<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productcomponent extends Model{
    use HasFactory;
    protected $table = 'productcomponent';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

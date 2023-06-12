<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mastercomponent extends Model{
    use HasFactory;
    protected $table = 'mastercomponent';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

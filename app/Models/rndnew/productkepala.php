<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productkepala extends Model{
    use HasFactory;
    protected $table = 'productkepala';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

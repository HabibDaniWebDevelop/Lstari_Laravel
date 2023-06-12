<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workshopallocationitem extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'workshopallocationitem';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accesscontroltemp extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'accesscontroltemp';
    protected $guarded = [];
    public $timestamps = false;
}

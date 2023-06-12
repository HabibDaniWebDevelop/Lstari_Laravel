<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userhistoryweb extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'userhistoryweb';
    protected $guarded = [];
    // protected $primaryKey = 'ID';
    public $timestamps = false;
}

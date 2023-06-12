<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class componentorder extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'componentorder';
    protected $guarded = [];
    // protected $primaryKey = 'ID';
    public $timestamps = false;
}

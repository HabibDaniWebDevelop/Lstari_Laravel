<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkclock extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'checkclock';
    protected $guarded = [];
    public $timestamps = false;
}

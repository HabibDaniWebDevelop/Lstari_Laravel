<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxtreetransferitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxtreetransferitem';
    protected $guarded = [];
    public $timestamps = false;
}
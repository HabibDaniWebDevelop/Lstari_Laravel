<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waxtreeitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'waxtreeitem';
    protected $guarded = [];
    public $timestamps = false;
}

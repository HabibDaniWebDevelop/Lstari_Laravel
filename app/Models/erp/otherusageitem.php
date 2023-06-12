<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otherusageitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'otherusageitem';
    protected $guarded = [];
    public $timestamps = false;
}

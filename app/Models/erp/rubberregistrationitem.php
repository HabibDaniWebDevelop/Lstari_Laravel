<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rubberregistrationitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'rubberregistrationitem';
    protected $guarded = [];
    public $timestamps = false;
}
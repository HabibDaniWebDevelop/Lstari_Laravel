<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengantarpengembalianitem extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'pengantarpengembalianitem';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recipient extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'reciepent';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

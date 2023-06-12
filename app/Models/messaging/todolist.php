<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class todolist extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'todolist';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
}

<?php

namespace App\Models\messaging;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class announcement extends Model
{
    use HasFactory;
    protected $connection = 'messaging';
    protected $table = 'announcement';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
}

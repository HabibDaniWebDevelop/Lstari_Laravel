<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overtime extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'overtime';
    protected $guarded = [];
    public $timestamps = false;
}

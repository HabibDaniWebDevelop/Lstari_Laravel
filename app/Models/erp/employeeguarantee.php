<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeeguarantee extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'employeeguarantee';
    protected $guarded = [];
    public $timestamps = false;
}

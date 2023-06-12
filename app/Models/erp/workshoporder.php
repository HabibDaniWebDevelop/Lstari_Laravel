<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workshoporder extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'workshoporder';
    protected $guarded = [];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

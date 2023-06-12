<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payrollinternship extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'payrollinternship';
    protected $guarded = ['ID'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
}

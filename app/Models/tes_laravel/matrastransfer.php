<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrastransfer extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'matrastransfer';
    protected $guarded = [];
    public $timestamps = false;

    public function Items(){
        return $this->hasMany(matrastransferitem::class, 'IDMatrasTransfer', 'ID');
    }
}

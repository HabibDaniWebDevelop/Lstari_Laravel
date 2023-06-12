<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenismatras extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'jenismatras';
    protected $guarded = [];
    public $timestamps = false;

    public function Items(){
        return $this->hasMany(jenismatrasitem::class, 'IDJenisMatras', 'ID');
    }
}

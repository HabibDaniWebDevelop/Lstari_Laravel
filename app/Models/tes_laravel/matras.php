<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matras extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'matras';
    protected $guarded = [];
    public $timestamps = false;

    public function Items(){
        return $this->hasMany(matrasitem::class, 'IDMatras', 'ID');
    }

    public function Materials(){
        return $this->hasMany(materialmatras::class, 'IDMatras', 'ID');
    }
}

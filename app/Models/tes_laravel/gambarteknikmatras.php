<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gambarteknikmatras extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'gambarteknikmatras';
    protected $guarded = [];
    public $timestamps = false;

    public function Items(){
        return $this->hasMany(gambarteknikmatrasitem::class, 'IDGambarTeknikMatras', 'ID');
    }

    public function Matras(){
        return $this->hasOne(matras::class, 'IDGambarTeknikMatras', 'ID');
    }
}

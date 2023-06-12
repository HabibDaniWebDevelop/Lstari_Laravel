<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrasitem extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'matrasitem';
    protected $guarded = [];
    public $timestamps = false;

    public function knive(){
        return $this->hasMany(knives::class, 'IDMatrasItem', 'ID');
    }

    public function Product(){
        return $this->belongsTo(product::class, 'IDProduct', 'ID');
    }

    public function Matras(){
        return $this->belongsTo(matras::class, 'IDMatras', 'ID');
    }
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialmatras extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'materialmatras';
    protected $guarded = [];
    public $timestamps = false;
    
    public function rawMaterial(){
        return $this->belongsTo(rawmaterialworkshop::class, 'IDRawMaterialWorkshop', 'ID');
    }
}

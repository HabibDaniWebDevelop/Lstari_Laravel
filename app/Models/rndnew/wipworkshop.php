<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wipworkshop extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'wipworkshop';
    protected $guarded = [];
    public $timestamps = false;

    public function Product(){
        return $this->belongsTo(product::class, 'IDProduct', 'ID');
    }
}

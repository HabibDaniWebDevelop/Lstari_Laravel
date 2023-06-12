<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gambarteknikmatrasitem extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'gambarteknikmatrasitem';
    protected $guarded = [];
    public $timestamps = false;

    public function GambarTeknik(){
        return $this->belongsTo(gambarteknikmatras::class, 'IDGambarTeknikMatras', 'ID');
    }

    public function wipWorkshop(){
        return $this->belongsTo(wipworkshop::class, 'IDWIPWorkshop', 'ID');
    }
}

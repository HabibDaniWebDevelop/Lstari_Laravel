<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mastergambarteknik extends Model
{
    use HasFactory;
    protected $table = 'mastergambarteknik';
    protected $guarded = [];
    public $timestamps = false;

    public function GambarTeknik(){
        return $this->hasMany(gambarteknikmatras::class, 'IDMasterGambarTeknik', 'ID');
    }
}

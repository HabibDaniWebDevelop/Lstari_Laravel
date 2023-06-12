<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrasallocationitem extends Model
{
    use HasFactory;
    protected $table = 'matrasallocationitem';
    protected $guarded = [];
    public $timestamps = false;

    public function MatrasAllocation(){
        return $this->belongsTo(matrasallocation::class, 'IDMatrasAllocation', 'ID');
    }

    public function Matras(){
        return $this->belongsTo(matras::class, 'IDMatras', 'ID');
    }

    public function GambarTeknikMatras(){
        return $this->belongsTo(gambarteknikmatras::class, 'IDGambarTeknikMatras', 'ID');
    }
}

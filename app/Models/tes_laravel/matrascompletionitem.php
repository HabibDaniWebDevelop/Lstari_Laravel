<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrascompletionitem extends Model
{
    use HasFactory;
    protected $table = 'matrascompletionitem';
    protected $guarded = [];
    public $timestamps = false;

    public function MatrasCompletion(){
        return $this->belongsTo(matrascompletion::class, 'IDMatrasCompletion', 'ID');
    }

    public function Matras(){
        return $this->belongsTo(matras::class, 'IDMatras', 'ID');
    }

    public function GambarTeknikMatras(){
        return $this->belongsTo(gambarteknikmatras::class, 'IDGambarTeknikMatras', 'ID');
    }

    public function MatrasAllocationItem(){
        return $this->belongsTo(matrasallocationitem::class, 'IDMatrasAllocationItem', 'ID');
    }
}

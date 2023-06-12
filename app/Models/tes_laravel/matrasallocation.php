<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrasallocation extends Model
{
    use HasFactory;
    protected $table = 'matrasallocation';
    protected $guarded = [];
    public $timestamps = false;
    
    public function MasterGambarTeknik(){
        return $this->belongsTo(mastergambarteknik::class, 'IDMasterGambarTeknik', 'ID');
    }

    public function MatrasAllocationItems(){
        return $this->hasMany(matrasallocationitem::class, 'IDMatrasAllocation', 'ID');
    }
}

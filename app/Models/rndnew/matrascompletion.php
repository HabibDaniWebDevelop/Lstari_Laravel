<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrascompletion extends Model
{
    use HasFactory;
    protected $table = 'matrascompletion';
    protected $guarded = [];
    public $timestamps = false;

    public function MatrasAllocation(){
        return $this->belongsTo(matrasallocation::class, 'IDMatrasAllocation', 'ID');
    }

    public function MatrasCompletionItems(){
        return $this->hasMany(matrascompletionitem::class, 'IDMatrasCompletion', 'ID');
    }
}

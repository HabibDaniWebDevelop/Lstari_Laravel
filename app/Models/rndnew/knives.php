<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class knives extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'knives';
    protected $guarded = [];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(matrasitem::class, 'IDMatrasItem', 'ID');
    }
}

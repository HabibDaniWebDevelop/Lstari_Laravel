<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matrastransferitem extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'matrastransferitem';
    protected $guarded = [];
    public $timestamps = false;

    public function MatrasTransfer(){
        return $this->belongsTo(matrastransfer::class, 'IDMatrasTransfer', 'ID');
    }

    public function Matras(){
        return $this->belongsTo(matras::class, 'IDMatras', 'ID');
    }
}

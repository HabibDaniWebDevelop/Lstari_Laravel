<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workshopallocation extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'workshopallocation';
    protected $guarded = [];
    public $timestamps = false;

    public function jenisMatrasItem(){
        return $this->belongsTo(jenismatrasitem::class, 'MatrasTypeID', 'ID');
    }
}

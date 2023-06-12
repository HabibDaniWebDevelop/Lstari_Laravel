<?php

namespace App\Models\rndnew;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workshopcompletion extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'workshopcompletion';
    protected $guarded = [];
    public $timestamps = false;

    public function spkoMatras(){
        return $this->hasOne(workshopallocation::class, 'ID', 'IDWorkshopAllocation');
    }
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenismatrasitem extends Model
{
    use HasFactory;
    // protected $connection = 'arik';
    protected $table = 'jenismatrasitem';
    protected $guarded = [];
    public $timestamps = false;

    public function jenisMatras(){
        return $this->belongsTo(jenismatras::class, 'IDJenisMatras', 'ID');
    }
}

<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laboratoriumxray extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'laboratoriumxray';
    protected $guarded = [];
    public $timestamps = false;

    public function LabTransactionItem(){
        return $this->hasMany(laboratoriumxrayitem::class, 'IDM', 'ID');
    }

    public function LabResultItem(){
        return $this->hasMany(laboratoriumxrayresult::class, 'IDM', 'ID');
    }

    public function Employee(){
        return $this->belongsTo(employee::class, 'EmployeeID', 'ID');
    }
}

<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laboratoriumxray extends Model
{
    use HasFactory;
    protected $connection = 'erp';
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

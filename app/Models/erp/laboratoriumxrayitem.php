<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laboratoriumxrayitem extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'laboratoriumxrayitem';
    protected $guarded = [];
    public $timestamps = false;
    
    public function LabTransaction(){
        return $this->belongsTo(laboratoriumxray::class, 'IDM ', 'ID');
    }
}

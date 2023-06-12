<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laboratoriumxrayitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'laboratoriumxrayitem';
    protected $guarded = [];
    public $timestamps = false;
    
    public function LabTransaction(){
        return $this->belongsTo(laboratoriumxray::class, 'IDM ', 'ID');
    }
}

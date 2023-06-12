<?php

namespace App\Models\tes_laravel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payrollinternshipitem extends Model
{
    use HasFactory;
    protected $connection = 'dev';
    protected $table = 'payrollinternshipitem';
    protected $fillable = ['IDM',"Ordinal","Employee","TotKerja","Nominal"];
    public $timestamps = false;
}

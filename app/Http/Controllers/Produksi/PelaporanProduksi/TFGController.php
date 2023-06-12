<?php

namespace App\Http\Controllers\Produksi\PelaporanProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TFGController extends Controller
{
    // Setup Public Function
    protected $Public_Function;
    public function __construct(Public_Function_Controller $Public_Function_Controller)
    {
        $this->Public_Function = $Public_Function_Controller;
    }
}

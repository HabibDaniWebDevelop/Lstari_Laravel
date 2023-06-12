<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;
use App\Models\rndnew\productcarat;


// Import public function controller for logger
use App\Http\Controllers\Public_Function_Controller;

class SPKPercobaanController extends Controller
{
    public function __construct(Public_Function_Controller $Public_Function_Controller) {
        $this->Public_Function = $Public_Function_Controller;
    }

    // Index function
    public function index() {
        //$datenow = date('Y-m-d');

        $carats = FacadesDB::table('productcarat')
                ->select('ID', 'SKU', 'Alloy')
                ->where('Regular', '=', 'Y')
                ->get();
        return view("R&D.Percobaan.SPKPercobaan.index", compact('carats'));
    }

    public function cekKaret(){
        
    }
}
?>
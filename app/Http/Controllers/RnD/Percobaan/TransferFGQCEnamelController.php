<?php

namespace App\Http\Controllers\RnD\Percobaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

// Models
use App\Models\rndnew\enamel;
use App\Models\rndnew\product as product_rndnew;
use App\Models\erp\product;
use App\Models\erp\lastid;
use App\Models\erp\transferfg;
use App\Models\erp\transferfgitem;
use App\Models\erp\transferrm;
use App\Models\erp\producttrans;
use App\Models\erp\stock;


use App\Models\erp\designstp;
use App\Models\erp\designstpitem;

use App\Models\rndnew\productsize;
use App\Models\rndnew\productchild;
use App\Models\rndnew\productaccessories;
use App\Models\rndnew\productstone;
use App\Models\rndnew\productpart;
use App\Models\rndnew\productkepala;
use App\Models\rndnew\productmn;
use App\Models\rndnew\productcomponent;


use App\Models\tes_laravel\transferfg as transferfg_test;
use App\Models\tes_laravel\transferfgitem as transferfgitem_test;


class TransferFGQCEnamelController extends Controller{


}

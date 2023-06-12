<?php

namespace App\Http\Controllers\RnD\DivisiLilin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class RegristrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FacadesDB::connection('dev');
            // ->table('rubber AS a')
            // ->join('product AS p', function($join){
            //     $join->on("p.ID","=","a.product");
            // })
            // ->select("
            //         p.SW,
            //         p.Description  "
            // );
            // dd($data);
        
        return view('R&D.DivisiLilin.RegristrasiMulKaret.index', compact('data'));
    }

    public function CariBarkode()
    {
        $data = FacadesDB::connection('dev')->select(
            "SELECT
                P.SW,
                P.Description 
             FROM
                rubber A
                JOIN product P ON P.ID = A.Product"
            );
        $result= FacadesDB::connection('dev');

                                                                                                                                                                                                                                                        return ($data);
    }

    public function search()
    {
        $id = $request->id;
        $data = FacadesDB::connection('dev')
        ->table("rubberregistration AS U")
            ->selectRaw('
                U.ID
                FROM rubberregistration U ORDER BY U.ID DESC"
        ')
            ->where("U.rubberregistration")
            ->Where(function ($query) use ($id) {
                $query
                    ->where('A.rubberregistration', 'LIKE', '%' . $id . '%');
            })
            ->orderBy("U.ID", "desc");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = FacadesDB::connection('dev');
        return view('R&D.DivisiLilin.RegristrasiMulKaret.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function list()
    {
        $tablename  = 'workallocation';
        $UserID     = '327';
        $Module     = '166';
        $carilists = $this->Public_Function->ListUserHistoryERP($tablename, $UserID, $Module);

        // dd($carilists);

        return view('R&D.DivisiLilin.RegristrasiMulKaret.data', compact('carilists'));
    }
}

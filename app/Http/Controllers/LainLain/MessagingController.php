<?php

namespace App\Http\Controllers\LainLain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class MessagingController extends Controller
{
    public function index(Request $request)
    {
        $UserEntry = session('UserEntry');
        // $UserEntry = 'Niko';
        // dd($Niko);

        $data0 = FacadesDB::connection('erp')->select("SELECT
                e.SW,
                e.Rank,
                e.Department,
                D.HigherRank AS HID,
                D.Responsibility RID,
                F.SW AS Diskripsi
            FROM
                employee e
                JOIN Department D ON E.Department = D.ID
                JOIN Department F ON F.ID = D.HigherRank 
            WHERE
                e.SW = '$UserEntry'");

        if ($data0 != null) {
            if ($data0[0]->Rank == 'Direktur' || $UserEntry =='Niko') {
                $cari1 = '';
            } elseif ($data0[0]->Rank == 'Manager') {
                $cari1 = "WHERE H.ID = '" . $data0[0]->HID . "' ";
            } elseif ($data0[0]->Rank == 'Supervisor') {
                $cari1 = "WHERE R.ID = '" . $data0[0]->RID . "' ";
            } else {
                $cari1 = "WHERE U.NAME = '" . $UserEntry . "'";
            }
        } else {
            $cari1 = "WHERE U.NAME = '" . $UserEntry . "'";
        }

        $listnama = FacadesDB::connection('erp')->select("SELECT
                U.NAME,
            CASE
                    
                    WHEN e.Rank = 'Direktur' THEN
                    '1' 
                    WHEN e.Rank = 'Manager' THEN
                    '2' 
                    WHEN e.Rank = 'Supervisor' THEN
                    '3' ELSE '6' 
                END AS ordinal,
            CASE
                    
                    WHEN e.Rank = 'Direktur' THEN
                    e.Rank 
                    WHEN e.Rank = 'Manager' THEN
                    e.Rank 
                    WHEN e.Rank = 'Supervisor' THEN
                    e.Rank ELSE D.SW 
                END AS jenis 
            FROM
                RnDNew.Users U
                LEFT JOIN Employee E ON U.NAME = E.SW 
                AND u.`status` = 'A'
                JOIN Department D ON E.Department = D.ID
                JOIN Department H ON D.HigherRank = H.ID
                LEFT JOIN Department R ON D.Responsibility = R.ID 
            $cari1
            ORDER BY
                Ordinal,
                jenis,
                E.Rank,
                u.`name`
        ");
 
        // dd($data2);


        return view('Lain-Lain.Messaging.index', compact('UserEntry','listnama'));
    }

    public function show(Request $request)
    {
        $UserEntry = $request->UserEntry;

        $data = FacadesDB::connection('messaging')->select("SELECT
                    *,
                CASE
                        
                        WHEN `Status` = 'Q' THEN
                        1 
                        WHEN `Status` = 'P' THEN
                        2 ELSE 3 
                    END AS urut 
                FROM
                    `sossight` 
                WHERE
                    Sender = '$UserEntry'
                    OR ToUser = '$UserEntry'
                ORDER BY
                    urut ASC,
                    ID DESC 
        ");
        return response()->json(["data" => $data], 200);
    }

}
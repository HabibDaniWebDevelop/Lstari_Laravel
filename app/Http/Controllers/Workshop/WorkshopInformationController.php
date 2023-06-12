<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

class WorkshopInformationController extends Controller
{
    public function index()
    {
        return view('Workshop.WorkshopInformation.index');
    }

    public function Lihat($tgl1, $tgl2, $cari)
    {
        $username = Auth::user()->name;

        if ($username == 'Suryo') {
            $filter = 'AND O.Purpose = "Laser"';
        } 
		else {
			$cekdep = FacadesDB::connection('erp')->select("SELECT
            CASE
                WHEN
                    Department IN ( 12, 58, 13 ) THEN
                        'IT'
                        WHEN Department IN ( 9, 10 ) THEN
                        'Workshop'
                        WHEN Department IN ( 43 ) THEN
                        'Maintenance'
                        WHEN Department IN ( 46 ) THEN
                        'Laser'
                        WHEN Department IN ( 41, 37 ) THEN
                        'HRGA' ELSE NULL
                END AS DATA
                FROM
                    employee
            WHERE
                SW = '$username'");
			$DATA = $cekdep[0]->DATA;
			
            $filter = "AND O.Purpose = '$DATA'";
        }		

        if ($cari == '1') {
            $datas = FacadesDB::connection('erp')->select("SELECT
                O.ID,
                O.SW CODE,
                I.Ordinal LinkOrd,
                W.Ordinal,
                O.TransDate,
                E.Description Employee,
                D.Description Department,
                I.Product,
                I.Description,
                I.Qty,
                I.Type,
                I.Category,
                O.Purpose,
                DATE_FORMAT(I.DateNeeded,'%d/%m/%y') AS DateNeeded,
                DATE_FORMAT(W.DateStart,'%d/%m/%y') AS DateStart,
                X.Description Worked,
                X.ID Workerid,
                W.ToDo,
            IF
                (
                    I.Category = 'Biasa',
                    1,
                IF
                ( I.Category = 'Penting', 2, 3 )) Priority
            FROM
                WorkShopOrder O
                JOIN WorkShopOrderItem I ON O.ID = I.IDM
                AND I.STATUS IN ( 'A', 'P' )
                JOIN Employee E ON O.Employee = E.ID
                JOIN Department D ON O.Department = D.ID
                LEFT JOIN WorkShopOrderWorker W ON O.ID = W.IDM
                AND I.Ordinal = W.LinkOrd
                AND W.STATUS = 'A'
                LEFT JOIN Employee X ON W.Employee = X.ID
            WHERE
                O.STATUS <> 'C' AND O.TransDate BETWEEN '$tgl1' AND '$tgl2' $filter
                ORDER BY
                W.DateEnd ASC");
        } 
        elseif ($cari == '2') {
            $datas = FacadesDB::connection('erp')->select("SELECT
						O.ID,
						O.Purpose,
						O.SW CODE,
						I.Ordinal LinkOrd,
						W.Ordinal,
						O.TransDate,
						E.Description Employee,
						D.Description Department,
						I.Product,
						I.Description,
						I.Qty,
						I.Type,
						I.Category,
						I.DateNeeded,
						W.DateStart,
						X.Description Worked,
						X.ID Workerid,
						W.ToDo,
					IF
						(
							I.Category = 'Biasa',
							1,
						IF
						( I.Category = 'Penting', 2, 3 )) Priority 
					FROM
						WorkShopOrder O
						JOIN WorkShopOrderItem I ON O.ID = I.IDM 
						AND I.STATUS IN ( 'A', 'P' )
						JOIN Employee E ON O.Employee = E.ID
						JOIN Department D ON O.Department = D.ID
						LEFT JOIN WorkShopOrderWorker W ON O.ID = W.IDM 
						AND I.Ordinal = W.LinkOrd 
						AND W.STATUS = 'A'
						LEFT JOIN Employee X ON W.Employee = X.ID 
					WHERE
						O.STATUS <> 'C'  AND W.STATUS = 'A' AND O.TransDate BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' $filter
						ORDER BY
						W.DateEnd ASC");
        } 
        elseif ($cari == '3') {
            $datas = FacadesDB::connection('erp')->select("SELECT
							O.ID,
							O.SW CODE,
							O.Purpose,
							I.Ordinal LinkOrd,
							W.Ordinal,
							O.TransDate,
							E.Description Employee,
							D.Description Department,
							I.Product,
							I.Description,
							I.Qty,
							I.Type,
							I.Category,
							I.DateNeeded,
							W.DateEnd as DateStart,
							X.Description Worked,
							X.ID Workerid,
							W.ToDo,
						IF
							(
								I.Category = 'Biasa',
								1,
							IF
							( I.Category = 'Penting', 2, 3 )) Priority 
						FROM
							WorkShopOrder O
							JOIN WorkShopOrderItem I ON O.ID = I.IDM 
							JOIN Employee E ON O.Employee = E.ID
							JOIN Department D ON O.Department = D.ID
							LEFT JOIN WorkShopOrderWorker W ON O.ID = W.IDM 
							AND I.Ordinal = W.LinkOrd 
							AND W.STATUS = 'P'
							LEFT JOIN Employee X ON W.Employee = X.ID 
						WHERE
							O.STATUS <> 'C'  AND I.STATUS = 'T' AND W.DateEnd BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' $filter
						ORDER BY
						W.DateEnd ASC
                    ");
        } elseif ($cari == '4') {
            $datas = FacadesDB::connection('erp')->select("SELECT
							O.ID,
							O.SW CODE,
							O.Purpose,
							I.Ordinal LinkOrd,
							W.Ordinal,
							O.TransDate,
							E.Description Employee,
							D.Description Department,
							I.Product,
							I.Description,
							I.Qty,
							I.Type,
							I.Category,
							I.DateNeeded,
							W.DateStart,
							X.Description Worked,
							X.ID Workerid,
							W.ToDo,
						IF
							(
								I.Category = 'Biasa',
								1,
							IF
							( I.Category = 'Penting', 2, 3 )) Priority 
						FROM
							WorkShopOrder O
							JOIN WorkShopOrderItem I ON O.ID = I.IDM 
							AND I.STATUS IN ( 'A', 'P' )
							JOIN Employee E ON O.Employee = E.ID
							JOIN Department D ON O.Department = D.ID
							LEFT JOIN WorkShopOrderWorker W ON O.ID = W.IDM 
							AND I.Ordinal = W.LinkOrd 
							AND W.STATUS = 'A'
							LEFT JOIN Employee X ON W.Employee = X.ID 
						WHERE
							O.STATUS <> 'C'  AND W.STATUS = 'A' AND O.TransDate BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' $filter
						ORDER BY
						W.DateEnd ASC
                    ");
        } elseif ($cari == '5') {
            $datas = FacadesDB::connection('erp')->select(" SELECT
						O.ID,
						O.SW CODE,
						O.Purpose,
						I.Ordinal LinkOrd,
						W.Ordinal,
						O.TransDate,
						E.Description Employee,
						D.Description Department,
						I.Product,
						I.Description,
						I.Qty,
						I.Type,
						I.Category,
						I.DateNeeded,
						W.DateStart,
						X.Description Worked,
						X.ID Workerid,
						W.ToDo,
					IF
						(
							I.Category = 'Biasa',
							1,
						IF
						( I.Category = 'Penting', 2, 3 )) Priority 
					FROM
						WorkShopOrder O
						JOIN WorkShopOrderItem I ON O.ID = I.IDM 
						JOIN Employee E ON O.Employee = E.ID
						JOIN Department D ON O.Department = D.ID
						LEFT JOIN WorkShopOrderWorker W ON O.ID = W.IDM 
						AND I.Ordinal = W.LinkOrd 
						LEFT JOIN Employee X ON W.Employee = X.ID 
					WHERE
						O.STATUS <> 'C' AND O.TransDate BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' $filter
					ORDER BY
						ID DESC");
        }

        // dd($datas);
        return view('Workshop.WorkshopInformation.show', compact('datas', 'cari'));
    }
}

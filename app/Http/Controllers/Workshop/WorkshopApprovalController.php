<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Auth;

use App\Models\erp\WorkShopOrderWorker;
use App\Models\erp\workshoporderitem;
use App\Models\erp\workshoporder;

class WorkshopApprovalController extends Controller
{
    public function index()
    {
        return view('Workshop.WorkshopApproval.index');
    }

    public function show(Request $request, $no)
    {
        // dd($no, $request);
        $username = Auth::user()->name;

        if ($no == '1') {
            if ($request->dari != null) {
                $filter = "AND O.SW BETWEEN '$request->dari' AND '$request->hingga'";
            } else {
                $filter = '';
            }

            $cekdepartemen = FacadesDB::connection('erp')->select("SELECT
            CASE
                WHEN
                    Department IN ( 12, 58, 13 ) THEN
                        'IT'
                        WHEN Department IN ( 9, 10 ) THEN
                        'Workshop'
                        WHEN Department IN ( 43 ) THEN
                        'Maintenance'
                        WHEN Department IN ( 46, 72 ) THEN
                        'Laser'
                        WHEN Department IN ( 41, 37 ) THEN
                        'HRGA' ELSE NULL
                END AS DATA
                FROM
                    employee
            WHERE
                SW = '$username'");

            $depname = $cekdepartemen[0]->DATA;
            // $depname = 'Workshop';

            $datas = FacadesDB::connection('erp')->select("SELECT
                    O.ID,
                    O.SW CODE,
                    I.Ordinal LinkOrd,
                    W.Ordinal,
                    DATE_FORMAT( O.TransDate, '%d/%m/%y' ) AS TransDate,
                    LEFT(E.Description, 20) Employee,
                    D.Description Department,
                    I.Product,
                    I.Description,
                    I.Qty,
                    CONCAT( I.Type, '/', I.Category ) AS Type,
                    DATE_FORMAT( I.DateNeeded, '%d/%m/%y' ) AS DateNeeded,
                    DATE_FORMAT( W.DateStart, '%d/%m/%y' ) AS DateStart,
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
                    O.STATUS <> 'C'
                    AND O.Purpose = '$depname'
                    $filter
                ORDER BY
                    O.SW DESC,
                    Priority,
                    DateNeeded,
                    ID,
                    LinkOrd
                    ");

            // dd($datas);
            return view('Workshop.WorkshopApproval.show', compact('datas'));
        } elseif ($no == '2') {
            $id = $request->id;
            $ordinal = $request->ordinal;
            // dd($request);

            $heads = FacadesDB::connection('erp')->select("SELECT
                    O.ID,
                    O.SW CODE,
                    I.Ordinal AS Ord,
                    I.ID SW,
                    DATE_FORMAT( O.TransDate, '%Y-%m-%d' ) AS TransDate,
                    E.Description Employee,
                    D.Description Department,
                    D.ID AS dptid,
                    O.STATUS,
                    O.Remarks,
                    I.Product,
                    I.Description,
                    I.Qty,
                    I.Type,
                    I.Category,
                    I.DateNeeded,
                    I.ConfirmDate
                FROM
                    WorkShopOrder O
                    JOIN Employee E ON O.Employee = E.ID
                    JOIN Department D ON O.Department = D.ID
                    JOIN WorkShopOrderItem I ON O.ID = I.IDM
                    AND I.Ordinal = '$ordinal'
                    AND I.STATUS <> 'C'
                WHERE
                    O.ID = '$id'
                    AND O.STATUS <> 'C'");

            $getdep = FacadesDB::connection('erp')->select("SELECT
                    CASE
                        WHEN
                            Department IN ( 12, 58, 13 ) THEN
                                '12, 58, 13'
                                WHEN Department IN ( 9, 10 ) THEN
                                '9,10'
                                WHEN Department IN ( 43 ) THEN
                                '43'
                                WHEN Department IN ( 46 ) THEN
                                '46'
                                WHEN Department IN ( 41, 37 ) THEN
                                '41,37'
                                WHEN Department IN ( 72 ) THEN
                                '72' ELSE NULL
                        END AS DATA
                        FROM
                            employee
                        WHERE
                            SW = '$username'");

            $depname = $getdep[0]->DATA;
            // $depname = '9,10';

            $getkar = FacadesDB::connection('erp')->select("SELECT
                        ID,
                        SW,
                        Description
                    FROM
                        Employee
                    WHERE
                        ( Department IN ($depname) AND Active = 'Y' AND WorkRole <> 'Outsourcing' )
                        OR ID IN ( 144, 155, 174, 183 )
                    ORDER BY
                        Department DESC,
                        Description");

            $Worker = FacadesDB::connection('erp')->select("SELECT
                        I.*,
                        B.Description AS Nama
                    FROM
                        WorkShopOrderWorker AS I
                        INNER JOIN employee AS B ON I.Employee = B.ID
                        INNER JOIN workshoporderitem AS J ON J.IDM = I.IDM
                        AND J.Ordinal = I.LinkOrd
                    WHERE
                    I.IDM = '$id'
                    AND J.Ordinal = '$ordinal' ");

            // dd($heads, $getdep, $getkar, $Worker);

            // $datas ='123';

            return view('Workshop.WorkshopApproval.edit', compact('heads', 'getdep', 'getkar', 'Worker', 'id'))->render();
        }

        //hapus
        elseif ($no == '3') {
            // dd($request);
            $id = $request->id;
            $ordinal = $request->ordinal;

            $deleteWorkShopOrderWorker = WorkShopOrderWorker::where('IDM', $id)
                ->where('Ordinal', $ordinal)
                ->limit(1)
                ->delete();

            return response()->json([
                'success' => true,
                'id' => $id,
            ]);
        }

        //Simpan
        elseif ($no == '4') {
            
            $iduser = $request->session()->get('iduser');
            $IDM = $request->id_nama;
            $ordwoi = $request->no_urut1;
            
            $tgl_selesai = $request->tgl_selesai;
            $Kegiatan1 = $request->kegiatan1;
            $hasil = $request->hasil;

            $karyawan_input = $request->karyawan_input;
            if($karyawan_input != null){

                $idm = $request->id_nama;
                $linkord = $request->no_urut1;
                $employee = $request->karyawan_input;
                $dtatus = 'A';
                $datestart = $request->tgl_mulai;
                $dateend = $request->tgl_target;
                $todo = $request->pekerjaan;

                $MAXWorkShopOrderWorker = FacadesDB::connection('erp')->select("SELECT MAX(Ordinal) AS ID FROM WorkShopOrderWorker where IDM ='$idm' and LinkOrd ='$linkord';");

                if ($MAXWorkShopOrderWorker[0]->ID === null) {
                    $ordinal = '1';
                    $insert_WorkShopOrderWorker = WorkShopOrderWorker::create([
                        'IDM' => $idm,
                        'Ordinal' => $ordinal,
                        'Employee' => $employee,
                        'Status' => $dtatus,
                        'DateStart' => $datestart,
                        'DateEnd' => $dateend,
                        'LinkOrd' => $linkord,
                        'ToDo' => $todo,
                    ]);
                } else {
                    $ordinal = $MAXWorkShopOrderWorker[0]->ID + 1;
                    $insert_WorkShopOrderWorker = WorkShopOrderWorker::create([
                        'IDM' => $idm,
                        'Ordinal' => $ordinal,
                        'Employee' => $employee,
                        'Status' => $dtatus,
                        'DateStart' => $datestart,
                        'DateEnd' => $dateend,
                        'LinkOrd' => $linkord,
                        'ToDo' => $todo,
                    ]);
                }

                $update_workshoporderitem = workshoporderitem::where('IDM', $idm)
                ->where('Ordinal', $linkord)
                ->update([
                    'ConfirmBy' => $iduser,
                    'ConfirmDate' => now(),
                ]);

                $pekerjaan = $request->pekerjaan;
                $tgl_mulai = $request->tgl_mulai;
                $tgl_target = $request->tgl_target;

                $Update_WorkShopOrderWorker[] = WorkShopOrderWorker::where('IDM', $idm)
                    ->where('Ordinal', $insert_WorkShopOrderWorker->Ordinal)
                    ->update([
                        'Status' => 'P',
                        'DateEnd' => $tgl_selesai,
                        'DateTarget' => $tgl_target,
                        'Result' => $hasil,
                        'Remark' => ($pekerjaan != null ? $pekerjaan : $Kegiatan1),
                    ]);

                $WorkedDate = $tgl_mulai;

            }
            else{
                $ordinal = $request->Ordinal;
                $mulai = $request->mulai;
                $target = $request->target;
                $Kegiatan = $request->Kegiatan;

                foreach ($ordinal as $key => $value) {

                    $Update_WorkShopOrderWorker[] = WorkShopOrderWorker::where('IDM', $IDM)
                        ->where('Ordinal', $value)
                        ->update([
                            'Status' => 'P',
                            'DateEnd' => $tgl_selesai,
                            'DateTarget' => $target[$value],
                            'Result' => $hasil,
                            'Remark' => ($Kegiatan[$value] != null ? $Kegiatan[$value] : $Kegiatan1),
                        ]);

                    if ($key == '0') {
                        $WorkedDate = $mulai[$value];
                    }
                }
            }

            $count1 = FacadesDB::connection('erp')->select("SELECT Count(*) as a FROM workshoporderitem WHERE Status = 'A' AND IDM = '$IDM'");

            if ($count1[0]->a >= 0){
                $Status = 'T'; 
            } else{
                $Status = 'A';
            }

            $Update_WorkShopOrderItem = WorkShopOrderItem::where('IDM', $IDM)
                ->where('Ordinal', $ordwoi)
                ->update([
                    'Status' => $Status,
                    'WorkedDate' => $WorkedDate,
                    'ConfirmBy' => $iduser,
                    'ConfirmDate' => now(),
                ]);

            $Update_workshoporder = workshoporder::where('ID', $IDM)
            ->update([
                'Status' => $Status,
            ]);

            // dd($Status);

            return response()->json([
                'success' => true,
                // 'id' => $id,
            ]);
        }

        //ubah
        elseif ($no == '5') {
            // dd($request);
            $IDM = $request->id_nama;
            $Ordinal = $request->Ordinal;
            $Kegiatan = $request->Kegiatan;
            $karyawan_input = $request->karyawan_input;

            foreach ($Ordinal as $key => $value) {

                // echo $value;

                if (isset($karyawan_input[$value])) {
                    $employee = $karyawan_input[$value];
                    $Update_WorkShopOrderWorker[] = WorkShopOrderWorker::where('IDM', $IDM)
                    ->where('Ordinal', $value)
                    ->update([
                        'Remark' => $Kegiatan[$value],
                        'Employee' => $employee,
                    ]);
                } else {
                    $Update_WorkShopOrderWorker[] = WorkShopOrderWorker::where('IDM', $IDM)
                        ->where('Ordinal', $value)
                        ->update([
                            'Remark' => $Kegiatan[$value],
                        ]);
                }
            }

            // dd($Update_WorkShopOrderWorker);

            return response()->json([
                'success' => true,
                // 'id' => $id,
            ]);
        }
    }

    public function tambah(Request $request)
    {
        $iduser = $request->session()->get('iduser');
        $idm = $request->id_nama;
        $linkord = $request->no_urut1;
        $employee = $request->karyawan_input;
        $dtatus = 'A';
        $datestart = $request->tgl_mulai;
        $dateend = $request->tgl_target;
        $todo = $request->pekerjaan;

        $MAXWorkShopOrderWorker = FacadesDB::connection('erp')->select("SELECT MAX(Ordinal) AS ID FROM WorkShopOrderWorker where IDM ='$idm' and LinkOrd ='$linkord';");

        if ($MAXWorkShopOrderWorker[0]->ID === null) {
            $ordinal = '1';
            $insert_WorkShopOrderWorker = WorkShopOrderWorker::create([
                'IDM' => $idm,
                'Ordinal' => $ordinal,
                'Employee' => $employee,
                'Status' => $dtatus,
                'DateStart' => $datestart,
                'DateEnd' => $dateend,
                'LinkOrd' => $linkord,
                'ToDo' => $todo,
            ]);
        } else {
            $ordinal = $MAXWorkShopOrderWorker[0]->ID + 1;
            $insert_WorkShopOrderWorker = WorkShopOrderWorker::create([
                'IDM' => $idm,
                'Ordinal' => $ordinal,
                'Employee' => $employee,
                'Status' => $dtatus,
                'DateStart' => $datestart,
                'DateEnd' => $dateend,
                'LinkOrd' => $linkord,
                'ToDo' => $todo,
            ]);
        }

        $update_workshoporderitem = workshoporderitem::where('IDM', $idm)
            ->where('Ordinal', $linkord)
            ->update([
                'ConfirmBy' => $iduser,
                'ConfirmDate' => now(),
            ]);

        return response()->json([
            'success' => true,
            'id' => $idm,
            'ordinal' => $linkord,
        ]);
    }
}

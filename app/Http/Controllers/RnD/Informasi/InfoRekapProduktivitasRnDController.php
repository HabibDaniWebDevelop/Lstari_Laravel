<?php

namespace App\Http\Controllers\RnD\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;



class InfoRekapProduktivitasRnDController extends Controller
{

    public function index(){
        return view('R&D.Informasi.InfoProduktivitas.index');
    }

    public function getBulan(){
        $data = FacadesDB::connection('erp')->select("
        SELECT  
            SW, SWOrdinal,
            CASE WHEN SWOrdinal = 1 THEN 'Januari'
            WHEN  SWOrdinal = 2 THEN 'Februari'
            WHEN  SWOrdinal = 3 THEN 'Maret'
            WHEN  SWOrdinal = 4 THEN 'April'
            WHEN  SWOrdinal = 5 THEN 'Mei'
            WHEN  SWOrdinal = 6 THEN 'Juni'
            WHEN  SWOrdinal = 7 THEN 'Juli'
            WHEN  SWOrdinal = 8 THEN 'Agustus'
            WHEN  SWOrdinal = 9 THEN 'September'
            WHEN  SWOrdinal = 10 THEN 'Oktober'
            WHEN  SWOrdinal = 11 THEN 'November'
            WHEN  SWOrdinal = 12 THEN 'Desember'
            ELSE 'Unknown'
            END AS Bulan
        FROM workperiod 
        WHERE SUBSTRING_INDEX(DateStart, '-', 1) = '".date('Y')."'  AND Type = 'P'
        ");

        $returnHTML =  view('R&D.Informasi.InfoProduktivitas.data', compact('data'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
    }


    public function getInfoProduktivitas(Request $request){
        // $bln = $request->bln;
        $jenis = $request->jenis;
        $area = $request->area;
        $thn =  $request->thn;
        //dd($thn);
        if($area == 2 && $jenis == 1){
        $data1 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 
            
            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");
                // dd($data1);
            $data2 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 2 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 2 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 2 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 2 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 
            
            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");


            $data3 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 3 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 3 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 3 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee  
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 3 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data4 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 4 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 4 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 4 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 4 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data5 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 5 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 5 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 5 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 5 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data6 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 6 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 6 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 6 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 6 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data7 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 7 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 7 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 7 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 7 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data8 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 8 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 8 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 8 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 8 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");


            $data9 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 9 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 9 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 9 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 9 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data10 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 10 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 10 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 10 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 10 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data11 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 11 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 11 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 11 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 11 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $data12 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 12 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 12 AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 12 AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 12 AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");

            $returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1','data2', 'data3', 'data4', 'data5', 'data6', 'data7', 'data8', 'data9', 'data10', 'data11', 'data12'))->render();
            return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) ); 
        }

        if($area == 2 && $jenis == 2){
            $data = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN X2.ModelPcsBaru IS NULL THEN '-' ELSE X2.ModelPcsBaru END AS ModelBaru,
			CASE WHEN X4.ModelPcsRevisi IS NULL THEN '-' ELSE X4.ModelPcsRevisi END AS ModelRevisi,
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = ".$bln." AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1, GROUP_CONCAT(D.SW SEPARATOR ' , ') ModelPcsBaru 
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    JOIN drafter2d C ON C.ID = A.LinkID 
									JOIN erp.product D ON D.ID = C.erp.product 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = ".$bln." AND YEAR(A.EndDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN erp.product D ON D.ID = C.erp.product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = ".$bln." AND YEAR(A.EndDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2, GROUP_CONCAT(D.SW SEPARATOR ' , ') ModelPcsRevisi
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        JOIN drafter2d C ON C.ID = A.LinkID 
                        JOIN erp.product D ON D.ID = C.erp.product 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = ".$bln." AND YEAR(A.EndDate) = ".$thn."
                        GROUP BY B.ID 

            )X4 ON X4.ID = X.ID
            WHERE X.Department = 14 AND X.Active='Y'
            ORDER BY X.Description");
            $returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data'))->render();
            return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) ); 
        }


        if($area == 5 && $jenis == 1){
            $data1 = FacadesDB::select("SELECT 
            X.ID, 
            X.Description, 
            CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
            CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
            CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
            CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
            CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
            CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
            FROM erp.employee X 
            LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM erp.rubber A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN erp.product D ON D.ID = A.Product
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 1 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM erp.rubber A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 1 AND YEAR(A.TransDate) = ".$thn."
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            LEFT JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM erp.rubber A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN erp.product D ON D.ID = A.Product
                                        WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 1 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            LEFT JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM erp.rubber A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 1 AND YEAR(A.TransDate) = ".$thn."
                        GROUP BY B.ID 
            
            )X4 ON X4.ID = X.ID
            WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
            ORDER BY X.Description");
                    // dd($data1);
                $data2 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 2 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 2 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 2 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 2 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
    
                $data3 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 3 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 3 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 3 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 3 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data4 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 4 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 4 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 4 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 4 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data5 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 5 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 5 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 5 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 5 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data6 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 6 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 6 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 6 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 6 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data7 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 7 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 7 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 7 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 7 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data8 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 8 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 8 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 8 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 8 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
    
                $data9 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 9 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 9 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 9 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 9 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data10 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 10 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 10 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 10 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 10 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data11 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 11 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 11 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 11 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 11 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $data12 = FacadesDB::select("SELECT 
                X.ID, 
                X.Description, 
                CASE WHEN X1.COUNTModel1 IS NULL THEN '0' ELSE X1.COUNTModel1 END AS COUNTModel1, 
                CASE WHEN X2.COUNTPcs1 IS NULL THEN '0' ELSE X2.COUNTPcs1 END AS COUNTPcs1, 
                CASE WHEN X3.COUNTModel2 IS NULL THEN '0' ELSE X3.COUNTModel2 END AS COUNTModel2, 
                CASE WHEN X4.COUNTPcs2 IS NULL THEN '0' ELSE X4.COUNTPcs2 END AS COUNTPcs2, 
                CASE WHEN (X1.COUNTModel1+X3.COUNTModel2) IS NULL THEN '0' ELSE (X1.COUNTModel1+X3.COUNTModel2) END AS totalModel, 
                CASE WHEN (X2.COUNTPcs1+X4.COUNTPcs2) IS NULL THEN '0' ELSE (X2.COUNTPcs1+X4.COUNTPcs2) END AS totalPcs
                FROM erp.employee X 
                LEFT JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 12 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X1 ON X1.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                        FROM erp.rubber A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.Status IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 12 AND YEAR(A.TransDate) = ".$thn."
                                        GROUP BY B.ID 
                ) X2 ON X2.ID = X.ID 
                LEFT JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM erp.rubber A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN erp.product D ON D.ID = A.Product
                                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 12 AND YEAR(A.TransDate) = ".$thn."
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X3 ON X3.ID = X.ID 
                LEFT JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2
                            FROM erp.rubber A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.Status NOT IN (8, 'Baru') AND A.UnUsedDate IS NULL  AND MONTH(A.TransDate) = 12 AND YEAR(A.TransDate) = ".$thn."
                            GROUP BY B.ID 
                
                )X4 ON X4.ID = X.ID
                WHERE X.Department = 31 AND X.Active='Y' AND X.`Rank` = 'Operator'
                ORDER BY X.Description");
    
                $returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1','data2', 'data3', 'data4', 'data5', 'data6', 'data7', 'data8', 'data9', 'data10', 'data11', 'data12'))->render();
                return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) ); 
            }
    }

}



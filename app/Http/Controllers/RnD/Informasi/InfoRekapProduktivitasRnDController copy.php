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
        $bln = $request->bln;
        $jenis = $request->jenis;
        $area = $request->area;

        //dd($area);
        //dd($bln);
        

        $SelectB2 = ",X5.COUNTModel1Bulan2, 
        X6.COUNTPcs1Bulan2, 
        X7.COUNTModel2Bulan2, 
        X8.COUNTPcs2Bulan2, 
        (X5.COUNTModel1Bulan2 + X7.COUNTModel2Bulan2) totalModelBulan2, 
        (X6.COUNTPcs1Bulan2 + X8.COUNTPcs2Bulan2) totalPcsaBulan2";

       
    
        $SelectB3 = ",X9.COUNTModel1Bulan3, 
        X10.COUNTPcs1Bulan3, 
        X11.COUNTModel2Bulan3, 
        X12.COUNTPcs2Bulan3, 
        (X9.COUNTModel1Bulan3 + X11.COUNTModel2Bulan3) totalModelBulan3, 
        (X10.COUNTPcs1Bulan3 + X12.COUNTPcs2Bulan3) totalPcsaBulan3";

        $SelectB4 = ",X13.COUNTModel1Bulan4, 
        X14.COUNTPcs1Bulan4, 
        X15.COUNTModel2Bulan4, 
        X16.COUNTPcs2Bulan4, 
        (X13.COUNTModel1Bulan4 + X15.COUNTModel2Bulan4) totalModelBulan4, 
        (X14.COUNTPcs1Bulan4 + X16.COUNTPcs2Bulan4) totalPcsaBulan4";

        $SelectB5 = ",X17.COUNTModel1Bulan5, 
        X18.COUNTPcs1Bulan5, 
        X19.COUNTModel2Bulan5, 
        X20.COUNTPcs2Bulan5, 
        (X17.COUNTModel1Bulan5 + X19.COUNTModel2Bulan5) totalModelBulan5, 
        (X18.COUNTPcs1Bulan5 + X20.COUNTPcs2Bulan5) totalPcsaBulan5";

        $SelectB6 = ",X21.COUNTModel1Bulan6, 
        X22.COUNTPcs1Bulan6, 
        X23.COUNTModel2Bulan6, 
        X24.COUNTPcs2Bulan6, 
        (X21.COUNTModel1Bulan6 + X23.COUNTModel2Bulan6) totalModelBulan6, 
        (X22.COUNTPcs1Bulan6 + X24.COUNTPcs2Bulan6) totalPcsaBulan6";

        $SelectB7 = ",X25.COUNTModel1Bulan7, 
        X26.COUNTPcs1Bulan7, 
        X27.COUNTModel2Bulan7, 
        X28.COUNTPcs2Bulan7, 
        (X25.COUNTModel1Bulan7 + X27.COUNTModel2Bulan7) totalModelBulan7, 
        (X26.COUNTPcs1Bulan7 + X28.COUNTPcs2Bulan7) totalPcsaBulan7";

        $SelectB8 = ",X29.COUNTModel1Bulan8, 
        X30.COUNTPcs1Bulan8, 
        X31.COUNTModel2Bulan8, 
        X32.COUNTPcs2Bulan8, 
        (X29.COUNTModel1Bulan8 + X31.COUNTModel2Bulan8) totalModelBulan8, 
        (X30.COUNTPcs1Bulan8 + X32.COUNTPcs2Bulan8) totalPcsaBulan8";
    
        $SelectB9 = ",X33.COUNTModel1Bulan9, 
        X34.COUNTPcs1Bulan9, 
        X35.COUNTModel2Bulan9, 
        X36.COUNTPcs2Bulan9, 
        (X33.COUNTModel1Bulan9 + X35.COUNTModel2Bulan9) totalModelBulan9, 
        (X34.COUNTPcs1Bulan9 + X36.COUNTPcs2Bulan9) totalPcsaBulan9";

        $SelectB10 = ",X37.COUNTModel1Bulan10, 
        X38.COUNTPcs1Bulan10, 
        X39.COUNTModel2Bulan10, 
        X40.COUNTPcs2Bulan10, 
        (X37.COUNTModel1Bulan10 + X39.COUNTModel2Bulan10) totalModelBulan10, 
        (X38.COUNTPcs1Bulan10 + X40.COUNTPcs2Bulan10) totalPcsaBulan10";

        $SelectB11 = ",X41.COUNTModel1Bulan11, 
        X42.COUNTPcs1Bulan11, 
        X43.COUNTModel2Bulan11, 
        X44.COUNTPcs2Bulan11, 
        (X41.COUNTModel1Bulan11 + X43.COUNTModel2Bulan11) totalModelBulan11, 
        (X42.COUNTPcs1Bulan11 + X44.COUNTPcs2Bulan11) totalPcsaBulan11";

        $SelectB12 = ",X45.COUNTModel1Bulan12, 
        X46.COUNTPcs1Bulan12, 
        X47.COUNTModel2Bulan12, 
        X48.COUNTPcs2Bulan12, 
        (X45.COUNTModel1Bulan12 + X47.COUNTModel2Bulan12) totalModelBulan12, 
        (X46.COUNTPcs1Bulan12 + X48.COUNTPcs2Bulan12) totalPcsaBulan12";
    
        $Bulan2 ="JOIN (
            SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan2
            FROM 
            employee EE 
                JOIN (
                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    JOIN drafter2d C ON C.ID = A.LinkID 
                                    JOIN product D ON D.ID = C.Product 
                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 2
                            GROUP BY D.Model, D.Serialno
                ) M ON M.ID = EE.ID 
            GROUP BY EE.ID 
            ) X5 ON X5.ID = X.ID 
            JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan2
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 2
                                    GROUP BY B.ID 
            ) X6 ON X6.ID = X.ID 
            JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN product D ON D.ID = C.Product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 2
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X7 ON X7.ID = X.ID 
            JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 2
                        GROUP BY B.ID 
            )X8 ON X8.ID = X.ID";

            $Bulan3 ="JOIN (
                SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan3
                FROM 
                employee EE 
                    JOIN (
                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                    FROM drafter3d A
                                        JOIN employee B ON B.ID = A.Employee 
                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                        JOIN product D ON D.ID = C.Product 
                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 3
                                GROUP BY D.Model, D.Serialno
                    ) M ON M.ID = EE.ID 
                GROUP BY EE.ID 
                ) X9 ON X9.ID = X.ID 
                JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan3
                                        FROM drafter3d A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 3
                                        GROUP BY B.ID 
                ) X10 ON X10.ID = X.ID 
                JOIN (
                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan3
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                FROM drafter3d A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN drafter2d C ON C.ID = A.LinkID 
                                                    JOIN product D ON D.ID = C.Product 
                                            WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 3
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                ) X11 ON X11.ID = X.ID 
                JOIN (
                            SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan3
                            FROM drafter3d A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 3
                            GROUP BY B.ID 
                )X12 ON X12.ID = X.ID";

                $Bulan4 ="JOIN (
                    SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan4
                    FROM 
                    employee EE 
                        JOIN (
                                    SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                        FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            JOIN drafter2d C ON C.ID = A.LinkID 
                                            JOIN product D ON D.ID = C.Product 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 4
                                    GROUP BY D.Model, D.Serialno
                        ) M ON M.ID = EE.ID 
                    GROUP BY EE.ID 
                    ) X13 ON X13.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan4
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 4
                                            GROUP BY B.ID 
                    ) X14 ON X14.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan4
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 4
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X15 ON X15.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan4
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 4
                                GROUP BY B.ID 
                    )X16 ON X16.ID = X.ID";

                    $Bulan5 ="JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan5
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN product D ON D.ID = C.Product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 5
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
                        ) X17 ON X17.ID = X.ID 
                        JOIN (
                                    SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan5
                                                FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 5
                                                GROUP BY B.ID 
                        ) X18 ON X18.ID = X.ID 
                        JOIN (
                                SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan5
                                    FROM 
                                    employee EE 
                                        JOIN (
                                                    SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                        FROM drafter3d A
                                                            JOIN employee B ON B.ID = A.Employee 
                                                            JOIN drafter2d C ON C.ID = A.LinkID 
                                                            JOIN product D ON D.ID = C.Product 
                                                    WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 5
                                                    GROUP BY D.Model, D.Serialno
                                        ) M ON M.ID = EE.ID 
                                    GROUP BY EE.ID 
                        ) X19 ON X19.ID = X.ID 
                        JOIN (
                                    SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan5
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 5
                                    GROUP BY B.ID 
                        )X20 ON X20.ID = X.ID";

                        $Bulan6 ="JOIN (
                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan6
                            FROM 
                            employee EE 
                                JOIN (
                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                FROM drafter3d A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    JOIN drafter2d C ON C.ID = A.LinkID 
                                                    JOIN product D ON D.ID = C.Product 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 6
                                            GROUP BY D.Model, D.Serialno
                                ) M ON M.ID = EE.ID 
                            GROUP BY EE.ID 
                            ) X21 ON X21.ID = X.ID 
                            JOIN (
                                        SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan6
                                                    FROM drafter3d A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 6
                                                    GROUP BY B.ID 
                            ) X22 ON X22.ID = X.ID 
                            JOIN (
                                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan6
                                        FROM 
                                        employee EE 
                                            JOIN (
                                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                            FROM drafter3d A
                                                                JOIN employee B ON B.ID = A.Employee 
                                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                                JOIN product D ON D.ID = C.Product 
                                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 6
                                                        GROUP BY D.Model, D.Serialno
                                            ) M ON M.ID = EE.ID 
                                        GROUP BY EE.ID 
                            ) X23 ON X23.ID = X.ID 
                            JOIN (
                                        SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan6
                                        FROM drafter3d A
                                        JOIN employee B ON B.ID = A.Employee 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 6
                                        GROUP BY B.ID 
                            )X24 ON X24.ID = X.ID";

                            $Bulan7 ="JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan7
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 7
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                                ) X25 ON X25.ID = X.ID 
                                JOIN (
                                            SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan7
                                                        FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 7
                                                        GROUP BY B.ID 
                                ) X26 ON X26.ID = X.ID 
                                JOIN (
                                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan7
                                            FROM 
                                            employee EE 
                                                JOIN (
                                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                                FROM drafter3d A
                                                                    JOIN employee B ON B.ID = A.Employee 
                                                                    JOIN drafter2d C ON C.ID = A.LinkID 
                                                                    JOIN product D ON D.ID = C.Product 
                                                            WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 7
                                                            GROUP BY D.Model, D.Serialno
                                                ) M ON M.ID = EE.ID 
                                            GROUP BY EE.ID 
                                ) X27 ON X27.ID = X.ID 
                                JOIN (
                                            SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan7
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 7
                                            GROUP BY B.ID 
                                )X28 ON X28.ID = X.ID";

                                $Bulan8 ="JOIN (
                                    SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan8
                                    FROM 
                                    employee EE 
                                        JOIN (
                                                    SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                        FROM drafter3d A
                                                            JOIN employee B ON B.ID = A.Employee 
                                                            JOIN drafter2d C ON C.ID = A.LinkID 
                                                            JOIN product D ON D.ID = C.Product 
                                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 8
                                                    GROUP BY D.Model, D.Serialno
                                        ) M ON M.ID = EE.ID 
                                    GROUP BY EE.ID 
                                    ) X29 ON X29.ID = X.ID 
                                    JOIN (
                                                SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan8
                                                            FROM drafter3d A
                                                            JOIN employee B ON B.ID = A.Employee 
                                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 8
                                                            GROUP BY B.ID 
                                    ) X30 ON X30.ID = X.ID 
                                    JOIN (
                                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan8
                                                FROM 
                                                employee EE 
                                                    JOIN (
                                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                                    FROM drafter3d A
                                                                        JOIN employee B ON B.ID = A.Employee 
                                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                                        JOIN product D ON D.ID = C.Product 
                                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 8
                                                                GROUP BY D.Model, D.Serialno
                                                    ) M ON M.ID = EE.ID 
                                                GROUP BY EE.ID 
                                    ) X31 ON X31.ID = X.ID 
                                    JOIN (
                                                SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan8
                                                FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 8
                                                GROUP BY B.ID 
                                    )X32 ON X32.ID = X.ID";

                                    $Bulan9 ="JOIN (
                                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan9
                                        FROM 
                                        employee EE 
                                            JOIN (
                                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                            FROM drafter3d A
                                                                JOIN employee B ON B.ID = A.Employee 
                                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                                JOIN product D ON D.ID = C.Product 
                                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 9
                                                        GROUP BY D.Model, D.Serialno
                                            ) M ON M.ID = EE.ID 
                                        GROUP BY EE.ID 
                                        ) X33 ON X33.ID = X.ID 
                                        JOIN (
                                                    SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan9
                                                                FROM drafter3d A
                                                                JOIN employee B ON B.ID = A.Employee 
                                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 9
                                                                GROUP BY B.ID 
                                        ) X34 ON X34.ID = X.ID 
                                        JOIN (
                                                SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan9
                                                    FROM 
                                                    employee EE 
                                                        JOIN (
                                                                    SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                                        FROM drafter3d A
                                                                            JOIN employee B ON B.ID = A.Employee 
                                                                            JOIN drafter2d C ON C.ID = A.LinkID 
                                                                            JOIN product D ON D.ID = C.Product 
                                                                    WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 9
                                                                    GROUP BY D.Model, D.Serialno
                                                        ) M ON M.ID = EE.ID 
                                                    GROUP BY EE.ID 
                                        ) X35 ON X35.ID = X.ID 
                                        JOIN (
                                                    SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan9
                                                    FROM drafter3d A
                                                    JOIN employee B ON B.ID = A.Employee 
                                                    WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 9
                                                    GROUP BY B.ID 
                                        )X36 ON X36.ID = X.ID";

                                        $Bulan10 ="JOIN (
                                            SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan10
                                            FROM 
                                            employee EE 
                                                JOIN (
                                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                                FROM drafter3d A
                                                                    JOIN employee B ON B.ID = A.Employee 
                                                                    JOIN drafter2d C ON C.ID = A.LinkID 
                                                                    JOIN product D ON D.ID = C.Product 
                                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 10
                                                            GROUP BY D.Model, D.Serialno
                                                ) M ON M.ID = EE.ID 
                                            GROUP BY EE.ID 
                                            ) X37 ON X37.ID = X.ID 
                                            JOIN (
                                                        SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan10
                                                                    FROM drafter3d A
                                                                    JOIN employee B ON B.ID = A.Employee 
                                                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 10
                                                                    GROUP BY B.ID 
                                            ) X38 ON X38.ID = X.ID 
                                            JOIN (
                                                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan10
                                                        FROM 
                                                        employee EE 
                                                            JOIN (
                                                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                                            FROM drafter3d A
                                                                                JOIN employee B ON B.ID = A.Employee 
                                                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                                                JOIN product D ON D.ID = C.Product 
                                                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 10
                                                                        GROUP BY D.Model, D.Serialno
                                                            ) M ON M.ID = EE.ID 
                                                        GROUP BY EE.ID 
                                            ) X39 ON X39.ID = X.ID 
                                            JOIN (
                                                        SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan10
                                                        FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 10
                                                        GROUP BY B.ID 
                                            )X40 ON X40.ID = X.ID";

                                            $Bulan11 ="JOIN (
                                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan11
                                                FROM 
                                                employee EE 
                                                    JOIN (
                                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                                    FROM drafter3d A
                                                                        JOIN employee B ON B.ID = A.Employee 
                                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                                        JOIN product D ON D.ID = C.Product 
                                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 11
                                                                GROUP BY D.Model, D.Serialno
                                                    ) M ON M.ID = EE.ID 
                                                GROUP BY EE.ID 
                                                ) X41 ON X41.ID = X.ID 
                                                JOIN (
                                                            SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan11
                                                                        FROM drafter3d A
                                                                        JOIN employee B ON B.ID = A.Employee 
                                                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 11
                                                                        GROUP BY B.ID 
                                                ) X42 ON X42.ID = X.ID 
                                                JOIN (
                                                        SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan11
                                                            FROM 
                                                            employee EE 
                                                                JOIN (
                                                                            SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                                                FROM drafter3d A
                                                                                    JOIN employee B ON B.ID = A.Employee 
                                                                                    JOIN drafter2d C ON C.ID = A.LinkID 
                                                                                    JOIN product D ON D.ID = C.Product 
                                                                            WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 11
                                                                            GROUP BY D.Model, D.Serialno
                                                                ) M ON M.ID = EE.ID 
                                                            GROUP BY EE.ID 
                                                ) X43 ON X43.ID = X.ID 
                                                JOIN (
                                                            SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan11
                                                            FROM drafter3d A
                                                            JOIN employee B ON B.ID = A.Employee 
                                                            WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 11
                                                            GROUP BY B.ID 
                                                )X44 ON X44.ID = X.ID";

                                                $Bulan12 ="JOIN (
                                                    SELECT EE.ID, COUNT(M.Model1) COUNTModel1Bulan12
                                                    FROM 
                                                    employee EE 
                                                        JOIN (
                                                                    SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                                        FROM drafter3d A
                                                                            JOIN employee B ON B.ID = A.Employee 
                                                                            JOIN drafter2d C ON C.ID = A.LinkID 
                                                                            JOIN product D ON D.ID = C.Product 
                                                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 12
                                                                    GROUP BY D.Model, D.Serialno
                                                        ) M ON M.ID = EE.ID 
                                                    GROUP BY EE.ID 
                                                    ) X45 ON X45.ID = X.ID 
                                                    JOIN (
                                                                SELECT B.ID, COUNT(A.ID) COUNTPcs1Bulan12
                                                                            FROM drafter3d A
                                                                            JOIN employee B ON B.ID = A.Employee 
                                                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 12
                                                                            GROUP BY B.ID 
                                                    ) X46 ON X46.ID = X.ID 
                                                    JOIN (
                                                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2Bulan12
                                                                FROM 
                                                                employee EE 
                                                                    JOIN (
                                                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                                                    FROM drafter3d A
                                                                                        JOIN employee B ON B.ID = A.Employee 
                                                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                                                        JOIN product D ON D.ID = C.Product 
                                                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 12
                                                                                GROUP BY D.Model, D.Serialno
                                                                    ) M ON M.ID = EE.ID 
                                                                GROUP BY EE.ID 
                                                    ) X47 ON X47.ID = X.ID 
                                                    JOIN (
                                                                SELECT B.ID, COUNT(A.ID) COUNTPcs2Bulan12
                                                                FROM drafter3d A
                                                                JOIN employee B ON B.ID = A.Employee 
                                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 12
                                                                GROUP BY B.ID 
                                                    )X48 ON X48.ID = X.ID";

if($area == 2 && $jenis == 1 && $bln == 1){
    $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
    X1.COUNTModel1, 
    X2.COUNTPcs1, 
    X3.COUNTModel2, 
    X4.COUNTPcs2, 
    (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
    (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa

    FROM employee X 
    JOIN (
                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                FROM 
                employee EE 
                    JOIN (
                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                    FROM drafter3d A
                                        JOIN employee B ON B.ID = A.Employee 
                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                        JOIN product D ON D.ID = C.Product 
                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                GROUP BY D.Model, D.Serialno
                    ) M ON M.ID = EE.ID 
                GROUP BY EE.ID 
    ) X1 ON X1.ID = X.ID 
    JOIN (
                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                            FROM drafter3d A
                            JOIN employee B ON B.ID = A.Employee 
                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                            GROUP BY B.ID 
    ) X2 ON X2.ID = X.ID 
    JOIN (
            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                FROM 
                employee EE 
                    JOIN (
                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                    FROM drafter3d A
                                        JOIN employee B ON B.ID = A.Employee 
                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                        JOIN product D ON D.ID = C.Product 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY D.Model, D.Serialno
                    ) M ON M.ID = EE.ID 
                GROUP BY EE.ID 
    ) X3 ON X3.ID = X.ID 
    JOIN (
                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                FROM drafter3d A
                JOIN employee B ON B.ID = A.Employee 
                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                GROUP BY B.ID 
    
    )X4 ON X4.ID = X.ID
    
    ORDER BY X.Description");

        $returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
        return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
}

if($area == 2 && $jenis == 1 && $bln == 2){
    $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
    X1.COUNTModel1, 
    X2.COUNTPcs1, 
    X3.COUNTModel2, 
    X4.COUNTPcs2, 
    (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
    (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
    $SelectB2
    FROM employee X 
            JOIN (
                        SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN product D ON D.ID = C.Product 
                                        WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X1 ON X1.ID = X.ID 
            JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                    FROM drafter3d A
                                    JOIN employee B ON B.ID = A.Employee 
                                    WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                    GROUP BY B.ID 
            ) X2 ON X2.ID = X.ID 
            JOIN (
                    SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                        FROM 
                        employee EE 
                            JOIN (
                                        SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                            FROM drafter3d A
                                                JOIN employee B ON B.ID = A.Employee 
                                                JOIN drafter2d C ON C.ID = A.LinkID 
                                                JOIN product D ON D.ID = C.Product 
                                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                        GROUP BY D.Model, D.Serialno
                            ) M ON M.ID = EE.ID 
                        GROUP BY EE.ID 
            ) X3 ON X3.ID = X.ID 
            JOIN (
                        SELECT B.ID, COUNT(A.ID) COUNTPcs2
                        FROM drafter3d A
                        JOIN employee B ON B.ID = A.Employee 
                        WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                        GROUP BY B.ID 
            
            )X4 ON X4.ID = X.ID
            $Bulan2
            ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
}
else if($area == 2 && $jenis == 1 && $bln == 3){
            //print_r('Helloo');
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    ORDER BY X.Description");
            die(print_r($data1));
$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 4){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 5){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 6){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 7){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            $SelectB7
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    $Bulan7
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 8){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            $SelectB7
            $SelectB8
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    $Bulan7
                    $Bulan8
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 9){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            $SelectB7
            $SelectB8
            $SelectB9
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    $Bulan7
                    $Bulan8
                    $Bulan9
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 10){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            $SelectB7
            $SelectB8
            $SelectB9
            $SelectB10
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    $Bulan7
                    $Bulan8
                    $Bulan9
                    $Bulan10
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 11){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            $SelectB7
            $SelectB8
            $SelectB9
            $SelectB10
            $SelectB11
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    $Bulan7
                    $Bulan8
                    $Bulan9
                    $Bulan10
                    $Bulan11
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }
        else if($area == 2 && $jenis == 1 && $bln == 12){
            $data1 = FacadesDB::select("SELECT X.ID, X.Description, 
            X1.COUNTModel1, 
            X2.COUNTPcs1, 
            X3.COUNTModel2, 
            X4.COUNTPcs2, 
            (X1.COUNTModel1+X3.COUNTModel2) totalModel, 
            (X2.COUNTPcs1+X4.COUNTPcs2) totalPcsa
            $SelectB2
            $SelectB3
            $SelectB4
            $SelectB5
            $SelectB6
            $SelectB7
            $SelectB8
            $SelectB9
            $SelectB10
            $SelectB11
            $SelectB12
            FROM employee X 
                    JOIN (
                                SELECT EE.ID, COUNT(M.Model1) COUNTModel1
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model1
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X1 ON X1.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs1
                                            FROM drafter3d A
                                            JOIN employee B ON B.ID = A.Employee 
                                            WHERE A.ProcessingID = 8 AND MONTH(A.EndDate) = 1
                                            GROUP BY B.ID 
                    ) X2 ON X2.ID = X.ID 
                    JOIN (
                            SELECT EE.ID, COUNT(M.Model2) COUNTModel2
                                FROM 
                                employee EE 
                                    JOIN (
                                                SELECT B.ID, COUNT(CONCAT(SUBSTRING_INDEX(D.SW,'-',1),'-', D.SerialNo)) Model2
                                                    FROM drafter3d A
                                                        JOIN employee B ON B.ID = A.Employee 
                                                        JOIN drafter2d C ON C.ID = A.LinkID 
                                                        JOIN product D ON D.ID = C.Product 
                                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                                GROUP BY D.Model, D.Serialno
                                    ) M ON M.ID = EE.ID 
                                GROUP BY EE.ID 
                    ) X3 ON X3.ID = X.ID 
                    JOIN (
                                SELECT B.ID, COUNT(A.ID) COUNTPcs2
                                FROM drafter3d A
                                JOIN employee B ON B.ID = A.Employee 
                                WHERE A.ProcessingID <> 8 AND MONTH(A.EndDate) = 1
                                GROUP BY B.ID 
                    
                    )X4 ON X4.ID = X.ID
                    $Bulan2
                    $Bulan3
                    $Bulan4
                    $Bulan5
                    $Bulan6
                    $Bulan7
                    $Bulan8
                    $Bulan9
                    $Bulan10
                    $Bulan11
                    $Bulan12
                    ORDER BY X.Description");

$returnHTML = view('R&D.Informasi.InfoProduktivitas.produktivitas', compact('data1'))->render();
return response()->json( array('success' => true, 'html' => $returnHTML, 'status' => 'OK' ) );
        }else{
            
        }

    }



}



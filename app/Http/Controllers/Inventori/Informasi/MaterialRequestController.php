<?php

namespace App\Http\Controllers\Inventori\Informasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class MaterialRequestController extends Controller
{
    public function Index(Request $request)
    {
        // dd($request);
        $iddept = session('iddept');
        $UserEntry = session('UserEntry');

        //Buat Sw List
        $names = array('Niko', 'Aditya', 'EndangS', 'Ika M', 'kharies','Ahmad H');
        if (in_array($UserEntry, $names)) {
            // SW Yang Bisa Membuat Transfer Bahan Pembantu
            $Akses = 1; 
        } else {
            // SW Yang Bisa Meng Posting
            $Akses = 2;
        }

        $Department = FacadesDB::connection('erp')->select("SELECT ID, Description From Department Where Type = 'S' Order By Description");

        return view('Inventori.Informasi.MaterialRequest.Index', compact('Department','iddept','Akses'));
    }

    public function show(Request $request)
    {
        // dd($request);
        $no = $request->id;
        $depart= $request->depart;

        if($no == '1'){
            if($depart !=''){
                $filter = "AND R.Department LIKE '$depart'";
            }
            else{
                $filter = '';
            }

            $datas = FacadesDB::connection('erp')->select("SELECT
                    R.ID,
                    R.TransDate,
                    R.Department,
                    D.Description Department,
                    E.Description Employee,
                    I.Ordinal,
                    P.Description Product,
                    I.Product cekbarang,
                    I.ProductNote,
                    I.Qty,
                    I.QtyBuy,
                    T.SW Unit,
                    U.Description ForUse,
                    I.Purpose,
                    I.Note,
                    I.Purchase,
                    R.Active,
                    R.UserName,
                    ConCat(
                        Date_Format( R.TransDate, '%m' ),
                        ' - ',
                    MonthName( R.TransDate )) MONTH 
                FROM
                    MaterialRequest R
                    JOIN MaterialRequestItem I ON R.ID = I.IDM
                    JOIN Department D ON R.Department = D.ID
                    JOIN Employee E ON R.Employee = E.ID
                    JOIN OperationUsage U ON I.Department = U.ID
                    JOIN Unit T ON I.Unit = T.ID
                    LEFT JOIN ProductPurchase P ON I.Product = P.ID 
                WHERE
                    R.TransDate BETWEEN '$request->tgl1'
                    AND '$request->tgl2'
                    AND R.Purpose = 'M' 
                    AND R.Active <> 'C'
                    $filter
                ORDER BY
                    R.ID DESC,
                    I.Ordinal
            ");
        }

        else if($no == '2'){

            if($depart !=''){
                $filter = "And R.Department LIKE '$depart'";
            }
            else{
                $filter = '';
            }

            $datas = FacadesDB::connection('erp')->select("SELECT
                    R.ID,
                    R.TransDate,
                    D.Description Department,
                    E.Description Employee,
                    W.SW Unit,
                    I.Ordinal,
                    P.Description Product,
                    I.ProductNote,
                    I.Qty,
                    U.Description ForUse,
                    I.Purpose,
                    I.Note,
                    Q.QtyPurchase,
                    I.Qty - IfNull( Q.QtyPurchase, 0 ) QtyOutstanding,
                    ConCat(
                        Date_Format( R.TransDate, '%m' ),
                        ' - ',
                    MonthName( R.TransDate )) MONTH 
                FROM
                    MaterialRequest R
                    JOIN MaterialRequestItem I ON R.ID = I.IDM
                    JOIN Department D ON R.Department = D.ID
                    JOIN Employee E ON R.Employee = E.ID
                    JOIN OperationUsage U ON I.Department = U.ID
                    JOIN Unit W ON I.Unit = W.ID
                    LEFT JOIN ProductPurchase P ON I.Product = P.ID
                    LEFT JOIN PurchaseRequestItem Z ON I.IDM = Z.LinkID 
                    AND I.Ordinal = Z.LinkOrd
                    LEFT JOIN (
                    SELECT
                        I.RequestID,
                        I.RequestOrd,
                        Sum( Qty ) QtyPurchase 
                    FROM
                        PurchaseInvoiceOther P
                        JOIN PurchaseInvoiceOtherItem I ON P.ID = I.IDM 
                    WHERE
                        P.Active <> 'P' 
                    GROUP BY
                        I.RequestID,
                        I.RequestOrd 
                    ) Q ON Z.IDM = Q.RequestID 
                    AND Z.Ordinal = Q.RequestOrd 
                WHERE
                    R.TransDate BETWEEN '$request->tgl1'
                    AND '$request->tgl2' 
                    AND R.Purpose = 'M' 
                    $filter
                    AND R.Active <> 'C' 
                    AND I.Purchase = 'Y' 
                    AND (
                    I.Qty - IfNull( Q.QtyPurchase, 0 )) > 0 
                ORDER BY
                    R.ID DESC,
                    I.Ordinal
            ");
        }

        else if($no == '3'){

            	if($depart == 2 || $depart == 3)
                {
                    $dep = '66';
                }
                else if($depart == 12 || $depart == 13 || $depart == 58)
                {
                    $dep = '67';
                }
                else if($depart == 9 || $depart == 10)
                {
                    $dep = '68';
                }
                else if($depart == 48)
                {
                    $dep = '69';
                }
                else if($depart == 35)
                {
                    $dep = '65';
                }
                else
                {
                    $dep = '64';
                }

                if($depart !=''){
                    $filter = "AND R.Department LIKE '$depart'";
                }
                else{
                    $filter = '';
                }

            $datas = FacadesDB::connection('erp')->select("SELECT
                        R.ID,
                        R.TransDate,
                        D.Description Department,
                        E.Description Employee,
                        I.Ordinal,
                        P.Description Product,
                        I.Qty,
                        Z.PrepareQty,
                        U.Description ForUse,
                        I.Purpose,
                        I.Note,
                        I.Purchase,
                        W.SW Unit,
                        O.UsageQty,
                        I.Qty - IfNull( Z.PrepareQty, 0 ) - IfNull( O.UsageQty, 0 ) QtyLeft,
                        ConCat(
                            Date_Format( R.TransDate, '%m' ),
                            ' - ',
                        MonthName( R.TransDate )) MONTH 
                    FROM
                        MaterialRequest R
                        JOIN MaterialRequestItem I ON R.ID = I.IDM 
                        AND I.Qty <> 0
                        JOIN Department D ON R.Department = D.ID
                        JOIN Employee E ON R.Employee = E.ID
                        JOIN OperationUsage U ON I.Department = U.ID
                        JOIN ProductPurchase P ON I.Product = P.ID 
                        AND IfNull( P.Location, 64 ) = $dep
                        JOIN Unit W ON I.Unit = W.ID
                        LEFT JOIN (
                        SELECT
                            I.LinkID,
                            I.LinkOrd,
                            Sum( I.Qty ) PrepareQty 
                        FROM
                            OtherUsage O
                            JOIN OtherUsageItem I ON O.ID = I.IDM 
                        WHERE
                            O.Active = 'A' 
                        GROUP BY
                            I.LinkID,
                            I.LinkOrd 
                        ) Z ON I.IDM = Z.LinkID 
                        AND I.Ordinal = Z.LinkOrd
                        LEFT JOIN (
                        SELECT
                            I.LinkID,
                            I.LinkOrd,
                            Sum( I.Qty ) UsageQty 
                        FROM
                            OtherUsage O
                            JOIN OtherUsageItem I ON O.ID = I.IDM 
                        WHERE
                            O.Active = 'P' 
                        GROUP BY
                            I.LinkID,
                            I.LinkOrd 
                        ) O ON I.IDM = O.LinkID 
                        AND I.Ordinal = O.LinkOrd 
                    WHERE
                        R.Purpose = 'M' 
                        AND R.Active <> 'C' 
                        AND (
                        I.Qty - IfNull( O.UsageQty, 0 )) <> 0 
                        AND U.Description <> 'Stock'
                        $filter
                        AND	R.TransDate BETWEEN '$request->tgl1'
                        AND '$request->tgl2'
                    ORDER BY
                        R.ID DESC,
                        I.Ordinal
            ");
        }

        else if($no == '4'){
            $datas = FacadesDB::connection('erp')->select("SELECT
                    P.ID,
                    P.SW,
                    P.Description,
                    X.Description ProdGroup,
                    P.MinStock,
                    P.Stock,
                    U.SW Unit 
                FROM
                    ProductPurchase P
                    JOIN ShortText X ON P.ProdGroup = X.ID
                    JOIN Unit U ON P.Unit = U.ID 
                WHERE
                    IfNull( P.MinStock, 0 ) > IfNull( P.Stock, 0 ) 
                ORDER BY
                    ProdGroup,
                    Description
            ");
        }

        else if($no == '5'){

            if($depart !=''){
                $filter = "AND M.Department LIKE '$depart'";
            }
            else{
                $filter = '';
            }

            $datas = FacadesDB::connection('erp')->select("SELECT
                    M.ID,
                    M.TransDate,
                    P.SW,
                    P.Description Product,
                    I.Qty,
                    E.SW Employee,
                    I.LinkID,
                    I.LinkOrd,
                    D.Description Department,
                    O.Description Operation,
                    I.Note,
                    M.Active,
                    X.Description ProdGroup,
                    U.SW Unit,
                    ConCat(
                        Date_Format( M.TransDate, '%m' ),
                        ' - ',
                    MonthName( M.TransDate )) MONTH 
                FROM
                    OtherUsage M
                    JOIN OtherUsageItem I ON M.ID = I.IDM
                    JOIN ProductPurchase P ON I.Product = P.ID
                    JOIN Employee E ON I.Employee = E.ID
                    JOIN Department D ON E.Department = D.ID
                    JOIN Unit U ON P.Unit = U.ID
                    LEFT JOIN OperationUsage O ON I.Operation = O.ID
                    LEFT JOIN ShortText X ON P.ProdGroup = X.ID 
                WHERE
                    M.TransDate BETWEEN '$request->tgl1' 
                    AND '$request->tgl2' 
                    AND M.Active <> 'C' 
                    $filter
                ORDER BY
                    M.ID DESC,
                    I.Ordinal
            ");
        }

        else if($no == '6'){

            if($depart !=''){
                $filter = "AND M.Department LIKE '$depart' ";
            }
            else{
                $filter = '';
            }

            $datas = FacadesDB::connection('erp')->select("SELECT
                        M.ID,
                        M.TransDate,
                        D.Description Department,
                        Q.ProductNote,
                        I.Qty,
                        O.Description Operation,
                        I.Note,
                        M.Active,
                        I.LinkID,
                        I.LinkOrd,
                        U.SW Unit,
                        Q.Note MRNote,
                        ConCat(
                            Date_Format( M.TransDate, '%m' ),
                            ' - ',
                        MonthName( M.TransDate )) MONTH 
                    FROM
                        NonStockUsage M
                        JOIN Department D ON M.Department = D.ID
                        JOIN NonStockUsageItem I ON M.ID = I.IDM
                        JOIN MaterialRequestItem Q ON I.LinkID = Q.IDM 
                        AND I.LinkOrd = Q.Ordinal
                        JOIN OperationUsage O ON Q.Department = O.ID
                        JOIN Unit U ON Q.Unit = U.ID 
                    WHERE
                        M.TransDate BETWEEN '$request->tgl1' 
                        AND '$request->tgl2' 
                        AND M.Active <> 'C' 
                        $filter
                    ORDER BY
                        M.ID DESC,
                        I.Ordinal
            ");
        }

        else if($no == '7'){
            $datas = FacadesDB::connection('erp')->select("SELECT
                        T.ID,
                        I.Ordinal,
                        T.TransDate,
                        F.Description FromLocation,
                        L.Description ToLocation,
                        T.Active,
                        P.Description Product,
                        I.Qty,
                        U.SW Unit,
                        I.LinkID,
                        I.LinkOrd 
                    FROM 
                        OtherTransfer T
                        JOIN OtherTransferItem I ON T.ID = I.IDM
                        JOIN ProductPurchase P ON I.Product = P.ID
                        JOIN Location F ON T.FromLoc = F.ID
                        JOIN Location L ON T.ToLoc = L.ID
                        JOIN Unit U ON P.Unit = U.ID 
                    WHERE
                        T.TransDate BETWEEN '$request->tgl1' 
                        AND '$request->tgl2' 
                        AND T.Active <> 'C' 
                    ORDER BY
                        T.ID,
                        I.Ordinal
            ");
        }

    return view('Inventori.Informasi.MaterialRequest.show', compact('no', 'datas'))->render();

    }


}

<?php

namespace App\Http\Controllers\IT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\tes_laravel\data_cpu;
use App\Models\tes_laravel\hardware;
use Illuminate\Support\Facades\DB as FacadesDB;

class DataPCController extends Controller
{
    public function DataPC()
    {
        $data = FacadesDB::connection('dev')
        ->table("data_cpu AS a")
        ->leftJoin("hardware AS c", function ($join) {
            $join->on("a.storage1", "=", "c.id");
        })
        ->leftJoin("hardware AS d", function ($join) {
            $join->on("a.storage2", "=", "d.id");
        })
        ->leftJoin("hardware AS e", function ($join) {
            $join->on("a.printer1", "=", "e.id");
        })
        ->leftJoin("hardware AS f", function ($join) {
            $join->on("a.printer2", "=", "f.id");
        })
        ->leftJoin("hardware AS g", function ($join) {
            $join->on("a.mouse", "=", "g.id");
        })
        ->leftJoin("hardware AS h", function ($join) {
            $join->on("a.keyboard", "=", "h.id");
        })
        ->leftJoin("hardware AS i", function ($join) {
            $join->on("a.vga", "=", "i.id");
        })
        ->leftJoin("hardware AS j", function ($join) {
            $join->on("a.monitor", "=", "j.id");
        })
        ->leftJoin("hardware AS k", function ($join) {
            $join->on("a.memory1", "=", "k.id");
        })
        ->leftJoin("hardware AS l", function ($join) {
            $join->on("a.scanner", "=", "l.id");
        })
        ->leftJoin("hardware AS m", function ($join) {
            $join->on("a.ups", "=", "m.id");
        })
        ->leftJoin("hardware AS n", function ($join) {
            $join->on("a.weightscale", "=", "n.id");
        })
        ->leftJoin("hardware AS o", function ($join) {
            $join->on("a.memory2", "=", "o.id");
        })
        ->leftJoin("employee AS p", function ($join) {
            $join->on("a.employee", "=", "p.id");
        })
        ->leftJoin("hardware AS q", function ($join) {
            $join->on("a.barcodescanner", "=", "q.id");
        })
        ->leftJoin("hardware AS r", function ($join) {
            $join->on("a.mainboard", "=", "r.id");
        })
        ->leftJoin("hardware AS s", function ($join) {
            $join->on("a.processor", "=", "s.id");
        })
        ->selectRaw('
            A.ID,
                A.Type,
                A.SW,
                A.ComputerName,
                A.IPAddress,
                A.MACAddress,
            CASE
                    
                    WHEN A.Series IS NULL THEN
                    "-" ELSE A.Series
                END AS Series,
            CASE
                    
                    WHEN A.Supplier IS NULL THEN
                    "-" ELSE A.Supplier
                END AS Supplier,
                A.STATUS,
            CASE
                    
                    WHEN A.PurchaseDate IS NULL THEN
                    "-" ELSE A.PurchaseDate
                END AS PurchaseDate,
                A.OperatingSystem,
                A.Domain,
                A.Antivirus,
                A.Note,
                A.EntryDate,
                CONCAT( C.SW, " - ", C.Brand, " ", C.SubType, " ", C.Var2 ) AS Storage1,
            CASE
                    
                    WHEN Storage2 IS NULL THEN
                    "-" ELSE CONCAT( D.SW, " - ", D.Brand, " ", D.SubType, " ", D.Var2 )
                END AS Storage2,
            CASE
                    
                    WHEN Printer1 IS NULL THEN
                    "-" ELSE CONCAT( E.SW, " - ", E.Brand, " ", E.Series, " ", E.SerialNo )
                END AS Printer1,
            CASE
                    
                    WHEN Printer2 IS NULL THEN
                    "-" ELSE CONCAT( F.SW, " - ", F.Brand, " ", F.Series, " ", F.SerialNo )
                END AS Printer2,
            CASE
                    
                    WHEN Mouse IS NULL THEN
                    "-" ELSE CONCAT( G.SW, " - ", G.Brand, " ", G.Series, " ", G.Var4 )
                END AS Mouse,
            CASE
                    
                    WHEN Keyboard IS NULL THEN
                    "-" ELSE CONCAT( H.SW, " - ", H.Brand, " ", H.Series, " ", H.Var4 )
                END AS Keyboard,
                CONCAT( I.SW, " - ", I.Brand, " ", I.Series, " ", I.SubType ) AS VGA,
            CASE
                    
                    WHEN Monitor IS NULL THEN
                    "-" ELSE CONCAT( J.SW, " - ", J.Brand, " ", J.Series, " ", J.Var5 )
                END AS Monitor,
                CONCAT( K.SW, " - ", K.Brand, " ", K.SubType, " ", K.Var2 ) AS Memory1,
            CASE
                    
                    WHEN Scanner IS NULL THEN
                    "-" ELSE CONCAT( L.SW, " - ", L.Brand, " ", L.Series, " ", L.SerialNo )
                END AS Scanner,
            CASE
                    
                    WHEN UPS IS NULL THEN
                    "-" ELSE CONCAT( M.SW, " - ", M.Brand, " ", M.Series, " ", M.SerialNo )
                END AS UPS,
            CASE
                    
                    WHEN WeightScale IS NULL THEN
                    "-" ELSE CONCAT( N.SW, " - ", N.Brand, " ", N.Series, " ", N.SerialNo )
                END AS WeightScale,
            CASE
                    
                    WHEN Memory2 IS NULL THEN
                    "-" ELSE CONCAT( O.SW, " - ", O.Brand, " ", O.SubType, " ", O.Var2 )
                END AS Memory2,
            CASE
                    
                    WHEN Description IS NULL THEN
                    "-" ELSE Description
                END AS Employee,
            CASE
                    
                    WHEN BarcodeScanner IS NULL THEN
                    "-" ELSE CONCAT( Q.SW, " - ", Q.Brand, " ", Q.Series, " ", Q.SerialNo )
                END AS BarcodeScanner,
                CONCAT( R.SW, " - ", R.Brand, " ", R.Series, " ", R.Var6 ) AS Mainboard,
                CONCAT( S.SW, " - ", S.Brand, " ", S.Series, " ", S.Var10 ) AS Processor
        ')
        ->where("a.active", "=", 1)
        ->orderBy("id", "desc")
        // ->get();
        ->Paginate(25);

        return view('IT.DataPC.index', compact('data'));
    }

    public function search(Request $request)
    {
        $id = $request->id;
        $data = FacadesDB::connection('dev')
            ->table("data_cpu AS a")
            ->leftJoin("hardware AS c", function ($join) {
                $join->on("a.storage1", "=", "c.id");
            })
            ->leftJoin("hardware AS d", function ($join) {
                $join->on("a.storage2", "=", "d.id");
            })
            ->leftJoin("hardware AS e", function ($join) {
                $join->on("a.printer1", "=", "e.id");
            })
            ->leftJoin("hardware AS f", function ($join) {
                $join->on("a.printer2", "=", "f.id");
            })
            ->leftJoin("hardware AS g", function ($join) {
                $join->on("a.mouse", "=", "g.id");
            })
            ->leftJoin("hardware AS h", function ($join) {
                $join->on("a.keyboard", "=", "h.id");
            })
            ->leftJoin("hardware AS i", function ($join) {
                $join->on("a.vga", "=", "i.id");
            })
            ->leftJoin("hardware AS j", function ($join) {
                $join->on("a.monitor", "=", "j.id");
            })
            ->leftJoin("hardware AS k", function ($join) {
                $join->on("a.memory1", "=", "k.id");
            })
            ->leftJoin("hardware AS l", function ($join) {
                $join->on("a.scanner", "=", "l.id");
            })
            ->leftJoin("hardware AS m", function ($join) {
                $join->on("a.ups", "=", "m.id");
            })
            ->leftJoin("hardware AS n", function ($join) {
                $join->on("a.weightscale", "=", "n.id");
            })
            ->leftJoin("hardware AS o", function ($join) {
                $join->on("a.memory2", "=", "o.id");
            })
            ->leftJoin("employee AS p", function ($join) {
                $join->on("a.employee", "=", "p.id");
            })
            ->leftJoin("hardware AS q", function ($join) {
                $join->on("a.barcodescanner", "=", "q.id");
            })
            ->leftJoin("hardware AS r", function ($join) {
                $join->on("a.mainboard", "=", "r.id");
            })
            ->leftJoin("hardware AS s", function ($join) {
                $join->on("a.processor", "=", "s.id");
            })
            ->selectRaw('
            A.ID,
                A.Type,
                A.SW,
                A.ComputerName,
                A.IPAddress,
                A.MACAddress,
            CASE
                    
                    WHEN A.Series IS NULL THEN
                    "-" ELSE A.Series
                END AS Series,
            CASE
                    
                    WHEN A.Supplier IS NULL THEN
                    "-" ELSE A.Supplier
                END AS Supplier,
                A.STATUS,
            CASE
                    
                    WHEN A.PurchaseDate IS NULL THEN
                    "-" ELSE A.PurchaseDate
                END AS PurchaseDate,
                A.OperatingSystem,
                A.Domain,
                A.Antivirus,
                A.Note,
                A.EntryDate,
                CONCAT( C.SW, " - ", C.Brand, " ", C.SubType, " ", C.Var2 ) AS Storage1,
            CASE
                    
                    WHEN Storage2 IS NULL THEN
                    "-" ELSE CONCAT( D.SW, " - ", D.Brand, " ", D.SubType, " ", D.Var2 )
                END AS Storage2,
            CASE
                    
                    WHEN Printer1 IS NULL THEN
                    "-" ELSE CONCAT( E.SW, " - ", E.Brand, " ", E.Series, " ", E.SerialNo )
                END AS Printer1,
            CASE
                    
                    WHEN Printer2 IS NULL THEN
                    "-" ELSE CONCAT( F.SW, " - ", F.Brand, " ", F.Series, " ", F.SerialNo )
                END AS Printer2,
            CASE
                    
                    WHEN Mouse IS NULL THEN
                    "-" ELSE CONCAT( G.SW, " - ", G.Brand, " ", G.Series, " ", G.Var4 )
                END AS Mouse,
            CASE
                    
                    WHEN Keyboard IS NULL THEN
                    "-" ELSE CONCAT( H.SW, " - ", H.Brand, " ", H.Series, " ", H.Var4 )
                END AS Keyboard,
                CONCAT( I.SW, " - ", I.Brand, " ", I.Series, " ", I.SubType ) AS VGA,
            CASE
                    
                    WHEN Monitor IS NULL THEN
                    "-" ELSE CONCAT( J.SW, " - ", J.Brand, " ", J.Series, " ", J.Var5 )
                END AS Monitor,
                CONCAT( K.SW, " - ", K.Brand, " ", K.SubType, " ", K.Var2 ) AS Memory1,
            CASE
                    
                    WHEN Scanner IS NULL THEN
                    "-" ELSE CONCAT( L.SW, " - ", L.Brand, " ", L.Series, " ", L.SerialNo )
                END AS Scanner,
            CASE
                    
                    WHEN UPS IS NULL THEN
                    "-" ELSE CONCAT( M.SW, " - ", M.Brand, " ", M.Series, " ", M.SerialNo )
                END AS UPS,
            CASE
                    
                    WHEN WeightScale IS NULL THEN
                    "-" ELSE CONCAT( N.SW, " - ", N.Brand, " ", N.Series, " ", N.SerialNo )
                END AS WeightScale,
            CASE
                    
                    WHEN Memory2 IS NULL THEN
                    "-" ELSE CONCAT( O.SW, " - ", O.Brand, " ", O.SubType, " ", O.Var2 )
                END AS Memory2,
            CASE
                    
                    WHEN Description IS NULL THEN
                    "-" ELSE Description
                END AS Employee,
            CASE
                    
                    WHEN BarcodeScanner IS NULL THEN
                    "-" ELSE CONCAT( Q.SW, " - ", Q.Brand, " ", Q.Series, " ", Q.SerialNo )
                END AS BarcodeScanner,
                CONCAT( R.SW, " - ", R.Brand, " ", R.Series, " ", R.Var6 ) AS Mainboard,
                CONCAT( S.SW, " - ", S.Brand, " ", S.Series, " ", S.Var10 ) AS Processor
        ')
            ->where("a.active", "=", 1)
            ->Where(function ($query) use ($id) {
                $query
                    ->where('A.SW', 'LIKE', '%' . $id . '%')
                    ->orwhere('A.ComputerName', 'LIKE',  '%' . $id . '%')
                    ->orwhere('A.Type', 'LIKE',  '%' . $id . '%')
                    ->orwhere('A.Note', 'LIKE',  '%' . $id . '%');
            })
            ->orderBy("id", "desc")
            // ->get();
            ->Paginate(50);

        return view('IT.DataPC.index', compact('data'));
    }

    public function DataPCcetak(Request $request)
    {
        $id = $request->id;
        $data = FacadesDB::connection('dev')->select("
            SELECT
                A.ID,
                A.Type,
                A.ComputerName,
                A.IPAddress,
                A.MACAddress,
                A.Series,
                A.Supplier,
                A.STATUS,
                A.PurchaseDate,
                A.OperatingSystem,
                A.Domain,
                A.Antivirus,
                A.Note,
                A.EntryDate,
                CONCAT( C.Brand, ' ', C.SubType, ' ', C.Var2 ) AS Storage1,
                CONCAT( D.Brand, ' ', D.SubType, ' ', D.Var2 ) AS Storage2,
                CONCAT( E.Brand, ' ', E.Series, ' ', E.SerialNo ) AS Printer1,
                CONCAT( F.Brand, ' ', F.Series, ' ', F.SerialNo ) AS Printer2,
                CONCAT( G.Brand, ' ', G.Series, ' ', G.Var4 ) AS Mouse,
                CONCAT( H.Brand, ' ', H.Series, ' ', H.Var4 ) AS Keyboard,
                CONCAT( I.Brand, ' ', I.Series, ' ', I.SubType ) AS VGA,
                CONCAT( J.Brand, ' ', J.SubType, ' ', J.Var5 ) AS Monitor,
                CONCAT( K.Brand, ' ', K.SubType, ' ', K.Var2 ) AS Memory1,
                CONCAT( L.Brand, ' ', L.Series, ' ', L.SerialNo ) AS Scanner,
                CONCAT( M.Brand, ' ', M.Series, ' ', M.SerialNo ) AS UPS,
                CONCAT( N.Brand, ' ', N.Series, ' ', N.SerialNo ) AS WeightScale,
                CONCAT( O.Brand, ' ', O.SubType, ' ', O.Var2 ) AS Memory2,
                P.Description AS Employee,
                CONCAT( Q.Brand, ' ', Q.Series, ' ', Q.SerialNo ) AS BarcodeScanner,
                CONCAT( R.Brand, ' ', R.Series, ' ', R.Var6 ) AS Mainboard,
                CONCAT( S.Brand, ' ', S.Series, ' ', S.Var10 ) AS Processor
            FROM
                data_cpu A
                LEFT JOIN hardware C ON A.Storage1 = C.ID
                LEFT JOIN hardware D ON A.Storage2 = D.ID
                LEFT JOIN hardware E ON A.Printer1 = E.ID
                LEFT JOIN hardware F ON A.Printer2 = F.ID
                LEFT JOIN hardware G ON A.Mouse = G.ID
                LEFT JOIN hardware H ON A.Keyboard = H.ID
                LEFT JOIN hardware I ON A.VGA = I.ID
                LEFT JOIN hardware J ON A.Monitor = J.ID
                LEFT JOIN hardware K ON A.Memory1 = K.ID
                LEFT JOIN hardware L ON A.Scanner = L.ID
                LEFT JOIN hardware M ON A.UPS = M.ID
                LEFT JOIN hardware N ON A.WeightScale = N.ID
                LEFT JOIN hardware O ON A.Memory2 = O.ID
                LEFT JOIN employee P ON A.Employee = P.ID
                LEFT JOIN hardware Q ON A.BarcodeScanner = Q.ID
                LEFT JOIN hardware R ON A.Mainboard = R.ID
                LEFT JOIN hardware S ON A.Processor = S.ID
            WHERE
                A.ID = $id
        ");
        // dd($data);
        return view('IT.DataPC.cetak', compact('data', 'id'));
    }

    public function DataPCInfo(Request $request, $id)
    {
        $data1 = FacadesDB::connection('dev')->select("
            SELECT  SW FROM data_cpu A WHERE ID = $id
        ");

        foreach ($data1 as $data1s) {
        }

        $data2 = FacadesDB::connection('dev')->select("
            SELECT
                A.ID,
                A.Type,
                A.SW,
                A.ComputerName,
                A.IPAddress,
                A.MACAddress,
            CASE
                    
                    WHEN A.Series IS NULL THEN
                    '-' ELSE A.Series
                END AS Series,
            CASE
                    
                    WHEN A.Supplier IS NULL THEN
                    '-' ELSE A.Supplier
                END AS Supplier,
                A.STATUS,
            CASE
                    
                    WHEN A.PurchaseDate IS NULL THEN
                    '-' ELSE A.PurchaseDate
                END AS PurchaseDate,
                A.OperatingSystem,
                A.Domain,
                A.Antivirus,
                A.Note,
                A.EntryDate ED,
                CONCAT( C.SW, ' - ', C.Brand, ' ', C.SubType, ' ', C.Var2 ) AS Storage1,
            CASE
                    
                    WHEN Storage2 IS NULL THEN
                    '-' ELSE CONCAT( D.SW, ' - ', D.Brand, ' ', D.SubType, ' ', D.Var2 )
                END AS Storage2,
            CASE
                    
                    WHEN Printer1 IS NULL THEN
                    '-' ELSE CONCAT( E.SW, ' - ', E.Brand, ' ', E.Series, ' ', E.SerialNo )
                END AS Printer1,
            CASE
                    
                    WHEN Printer2 IS NULL THEN
                    '-' ELSE CONCAT( F.SW, ' - ', F.Brand, ' ', F.Series, ' ', F.SerialNo )
                END AS Printer2,
            CASE
                    
                    WHEN Mouse IS NULL THEN
                    '-' ELSE CONCAT( G.SW, ' - ', G.Brand, ' ', G.Series, ' ', G.Var4 )
                END AS Mouse,
            CASE
                    
                    WHEN Keyboard IS NULL THEN
                    '-' ELSE CONCAT( H.SW, ' - ', H.Brand, ' ', H.Series, ' ', H.Var4 )
                END AS Keyboard,
                CONCAT( I.SW, ' - ', I.Brand, ' ', I.Series, ' ', I.SubType ) AS VGA,
            CASE
                    
                    WHEN Monitor IS NULL THEN
                    '-' ELSE CONCAT( J.SW, ' - ', J.Brand, ' ', J.Series, ' ', J.Var5 )
                END AS Monitor,
                CONCAT( K.SW, ' - ', K.Brand, ' ', K.SubType, ' ', K.Var2 ) AS Memory1,
            CASE
                    
                    WHEN Scanner IS NULL THEN
                    '-' ELSE CONCAT( L.SW, ' - ', L.Brand, ' ', L.Series, ' ', L.SerialNo )
                END AS Scanner,
            CASE
                    
                    WHEN UPS IS NULL THEN
                    '-' ELSE CONCAT( M.SW, ' - ', M.Brand, ' ', M.Series, ' ', M.SerialNo )
                END AS UPS,
            CASE
                    
                    WHEN WeightScale IS NULL THEN
                    '-' ELSE CONCAT( N.SW, ' - ', N.Brand, ' ', N.Series, ' ', N.SerialNo )
                END AS WeightScale,
            CASE
                    
                    WHEN Memory2 IS NULL THEN
                    '-' ELSE CONCAT( O.SW, ' - ', O.Brand, ' ', O.SubType, ' ', O.Var2 )
                END AS Memory2,
            CASE
                    
                    WHEN Description IS NULL THEN
                    '-' ELSE Description
                END AS Employee,
            CASE
                    
                    WHEN BarcodeScanner IS NULL THEN
                    '-' ELSE CONCAT( Q.SW, ' - ', Q.Brand, ' ', Q.Series, ' ', Q.SerialNo )
                END AS BarcodeScanner,
                CONCAT( R.SW, ' - ', R.Brand, ' ', R.Series, ' ', R.Var6 ) AS Mainboard,
                CONCAT( S.SW, ' - ', S.Brand, ' ', S.Series, ' ', S.Var10 ) AS Processor
            FROM
                data_cpu A
                LEFT JOIN hardware C ON A.Storage1 = C.ID
                LEFT JOIN hardware D ON A.Storage2 = D.ID
                LEFT JOIN hardware E ON A.Printer1 = E.ID
                LEFT JOIN hardware F ON A.Printer2 = F.ID
                LEFT JOIN hardware G ON A.Mouse = G.ID
                LEFT JOIN hardware H ON A.Keyboard = H.ID
                LEFT JOIN hardware I ON A.VGA = I.ID
                LEFT JOIN hardware J ON A.Monitor = J.ID
                LEFT JOIN hardware K ON A.Memory1 = K.ID
                LEFT JOIN hardware L ON A.Scanner = L.ID
                LEFT JOIN hardware M ON A.UPS = M.ID
                LEFT JOIN hardware N ON A.WeightScale = N.ID
                LEFT JOIN hardware O ON A.Memory2 = O.ID
                LEFT JOIN employee P ON A.Employee = P.ID
                LEFT JOIN hardware Q ON A.BarcodeScanner = Q.ID
                LEFT JOIN hardware R ON A.Mainboard = R.ID
                LEFT JOIN hardware S ON A.Processor = S.ID
            WHERE
                A.SW = '$data1s->SW'
        ");
        // dd($data2);
        return view('IT.DataPC.info', compact('data2'));
    }

    public function DataPCedit($id)
    {
        $data1 = FacadesDB::connection('dev')->select("
            SELECT
                A.ID,
                B.ID AS b,
                C.ID AS c,
                D.ID AS d,
                E.ID AS e,
                F.ID AS f,
                G.ID AS g,
                H.ID AS h,
                I.ID AS i,
                J.ID AS j,
                K.ID AS k,
                L.ID AS l,
                M.ID AS m,
                N.ID AS n,
                O.ID AS o,
                P.ID AS p,
                Q.ID AS q,
                R.ID AS r,
                S.ID AS s,
                A.Type,
                A.SW,
                A.ComputerName,
                A.IPAddress,
                A.MACAddress,
                A.Series,
                A.Supplier,
                A.STATUS,
                A.PurchaseDate,
                A.OperatingSystem,
                A.Domain,
                A.Antivirus,
                A.Note,
                CONCAT( B.SW, ' - ', B.Brand, ' ', B.Series, ' ', B.Var6 ) AS Mainboard,
                CONCAT( C.SW, ' - ', C.Brand, ' ', C.Series, ' ', C.Var10 ) AS Processor,
                CONCAT( D.SW, ' - ', D.Brand, ' ', D.SubType, ' ', D.Var2 ) AS Storage1,
                CONCAT( E.SW, ' - ', E.Brand, ' ', E.SubType, ' ', E.Var2 ) AS Storage2,
                CONCAT( F.SW, ' - ', F.Brand, ' ', F.Series, ' ', F.SerialNo ) AS Printer1,
                CONCAT( G.SW, ' - ', G.Brand, ' ', G.Series, ' ', G.SerialNo ) AS Printer2,
                CONCAT( H.SW, ' - ', H.Brand, ' ', H.Series, ' ', H.Var4 ) AS Mouse,
                CONCAT( I.SW, ' - ', I.Brand, ' ', I.Series, ' ', I.Var4 ) AS Keyboard,
                CONCAT( J.SW, ' - ', J.Brand, ' ', J.Series, ' ', J.SubType ) AS VGA,
                CONCAT( K.SW, ' - ', K.Brand, ' ', K.SubType, ' ', K.Var5 ) AS Monitor,
                CONCAT( L.SW, ' - ', L.Brand, ' ', L.SubType, ' ', L.Var2 ) AS Memory1,
                CONCAT( M.SW, ' - ', M.Brand, ' ', M.SubType, ' ', M.Var2 ) AS Memory2,
                CONCAT( N.SW, ' - ', N.Brand, ' ', N.Series, ' ', N.SerialNo ) AS Scanner,
                CONCAT( O.SW, ' - ', O.Brand, ' ', O.Series, ' ', O.SerialNo ) AS BarcodeScanner,
                CONCAT( P.SW, ' - ', P.Brand, ' ', P.Series, ' ', P.SerialNo ) AS UPS,
                CONCAT( Q.SW, ' - ', Q.Brand, ' ', Q.Series, ' ', Q.SerialNo ) AS WeightScale,
                R.Description AS Employee,
                S.Description AS Department
            FROM
                data_cpu A
                LEFT JOIN hardware B ON A.Mainboard = B.ID
                LEFT JOIN hardware C ON A.Processor = C.ID
                LEFT JOIN hardware D ON A.Storage1 = D.ID
                LEFT JOIN hardware E ON A.Storage2 = E.ID
                LEFT JOIN hardware F ON A.Printer1 = F.ID
                LEFT JOIN hardware G ON A.Printer2 = G.ID
                LEFT JOIN hardware H ON A.Mouse = H.ID
                LEFT JOIN hardware I ON A.Keyboard = I.ID
                LEFT JOIN hardware J ON A.VGA = J.ID
                LEFT JOIN hardware K ON A.Monitor = K.ID
                LEFT JOIN hardware L ON A.Memory1 = L.ID
                LEFT JOIN hardware M ON A.Memory2 = M.ID
                LEFT JOIN hardware N ON A.Scanner = N.ID
                LEFT JOIN hardware O ON A.BarcodeScanner = O.ID
                LEFT JOIN hardware P ON A.UPS = P.ID
                LEFT JOIN hardware Q ON A.WeightScale = Q.ID
                LEFT JOIN employee R ON A.Employee = R.ID
                LEFT JOIN department S ON S.ID = R.Department
            WHERE
                A.ID = $id
        ");
        
        $Mainboard = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ',Var6) AS Deskripsi FROM hardware WHERE Type = 'Mainboard'
        ");

        $Processor = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ',Var10) AS Deskripsi FROM hardware WHERE Type = 'Processor'
        ");

        $Memory = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', Var2) AS Deskripsi FROM hardware WHERE Type = 'Memory'
        ");

        $Storage = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', Var2) AS Deskripsi FROM hardware WHERE Type = 'Storage'
        ");

        $VGA = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SubType) AS Deskripsi FROM hardware WHERE Type = 'VGA'
        ");

        $Monitor = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', Var5) AS Deskripsi FROM hardware WHERE Type = 'Monitor'
        ");

        $Keyboard = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', Var4) AS Deskripsi FROM hardware WHERE Type = 'Keyboard'
        ");

        $Mouse = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', Var4) AS Deskripsi FROM hardware WHERE Type = 'Mouse'
        ");

        $Printer = FacadesDB::connection('dev')->select("
        SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'Printer'
        ");

        $WeightScale = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'WeightScale'
        ");

        $UPS = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'UPS'
        ");

        $Scanner = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'Scanner'
        ");

        $BarcodeScanner = FacadesDB::connection('dev')->select("
        SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'BarcodeScanner'
        ");

        $department = FacadesDB::connection('dev')->select("
            SELECT ID, Description FROM department WHERE Type = 'S' ORDER BY Description ASC
        ");

        // dd($Mainboard);
        return view('IT.DataPC.edit', compact('data1', 'Mainboard', 'Processor', 'Memory', 'Storage', 'VGA', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'WeightScale', 'UPS', 'Scanner', 'BarcodeScanner', 'department'));
    }

    public function DataPCTambah()
    {

        $Mainboard = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ',Var6) AS Deskripsi FROM hardware WHERE Type = 'Mainboard' AND Status='Belum dipakai'
        ");

        $Processor = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ',Var10) AS Deskripsi FROM hardware WHERE Type = 'Processor' AND Status='Belum dipakai'
        ");

        $Memory = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', Var2) AS Deskripsi FROM hardware WHERE Type = 'Memory' AND Status='Belum dipakai'
        ");

        $Storage = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', Var2) AS Deskripsi FROM hardware WHERE Type = 'Storage' AND Status='Belum dipakai'
        ");

        $VGA = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SubType) AS Deskripsi FROM hardware WHERE Type = 'VGA' AND Status='Belum dipakai'
        ");

        $Monitor = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', Var5) AS Deskripsi FROM hardware WHERE Type = 'Monitor' AND Status='Belum dipakai'
        ");
        
        $Keyboard = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', Var4) AS Deskripsi FROM hardware WHERE Type = 'Keyboard' AND Status='Belum dipakai'
        ");

        $Mouse = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', Var4) AS Deskripsi FROM hardware WHERE Type = 'Mouse' AND Status='Belum dipakai'
        ");

        $Printer = FacadesDB::connection('dev')->select("
        SELECT ID, CONCAT(SW, ' - ',Brand, ' ', SubType, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'Printer' AND Status='Belum dipakai'
        ");

        $WeightScale = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'WeightScale' AND Status='Belum dipakai'
        ");

        $UPS = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'UPS' AND Status='Belum dipakai'
        ");

        $Scanner = FacadesDB::connection('dev')->select("
            SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'Scanner' AND Status='Belum dipakai'
        ");

        $BarcodeScanner = FacadesDB::connection('dev')->select("
        SELECT ID, CONCAT(SW, ' - ',Brand, ' ', Series, ' ', SerialNo) AS Deskripsi FROM hardware WHERE Type = 'BarcodeScanner' AND Status='Belum dipakai'
        ");

        $department = FacadesDB::connection('dev')->select("
            SELECT ID, Description FROM department WHERE Type = 'S' ORDER BY Description ASC
        ");

        return view('IT.DataPC.tambah', compact('Mainboard', 'Processor', 'Memory', 'Storage', 'VGA', 'Monitor', 'Keyboard', 'Mouse', 'Printer', 'WeightScale', 'UPS', 'Scanner', 'BarcodeScanner', 'department'));
    }

    public function DataPCkar($id)
    {
        $data1 = FacadesDB::connection('erp')->select("
            SELECT ID, Description FROM employee WHERE Department = '$id' AND Active <> 'N' ORDER BY Description
        ");

        //    dd($data1);
        return view('IT.DataPC.employee', compact('data1'));
    }

    public function DataPCCreat(Request $request)
    {
        $tglfull = date('Y-m-d h:i:s');
        $tgl = date('Y-m-d');
        $tahun =  date("y");
        $bulan =  date("m");
        $Active = '1';
        $Freq = '1';
        $Kode = 'CPU';

        $data1 = FacadesDB::connection('dev')->select("
            SELECT
                CONCAT(
                    '$Kode',
                    '$tahun',
                    '$bulan',
                LPAD( COUNT( A.ID )+ 1, 3, '0' )) SW 
            FROM
                data_cpu A
                LEFT JOIN data_cpu B ON A.Active = B.ID 
            WHERE
                A.Active = $Active 
                AND B.ID = $Active 
                AND A.YearAdd = $tahun 
                AND A.MonthAdd = $bulan
        ");

        foreach ($data1 as $data1s) {
        }

        $insert_data_cpu = data_cpu::create([
            'Active' => $Active, 'Type' => $request->Type, 'SW' => $data1s->SW, 'ComputerName' => $request->ComputerName, 'IPAddress' => $request->IPAddress,
            'MACAddress' => $request->MACAddress, 'Mainboard' => $request->Mainboard, 'Processor' => $request->Processor, 'Memory1' => $request->Memory1, 'Memory2' => $request->Memory2,
            'Storage1' => $request->Storage1, 'Storage2' => $request->Storage2, 'Monitor' => $request->Monitor, 'VGA' => $request->VGA, 'Mouse' => $request->Mouse,
            'Keyboard' => $request->Keyboard, 'Printer1' => $request->Printer1, 'Printer2' => $request->Printer2, 'WeightScale' => $request->WeightScale, 'Scanner' => $request->Scanner,
            'BarcodeScanner' => $request->BarcodeScanner, 'UPS' => $request->UPS, 'Domain' => $request->Domain, 'OperatingSystem' => $request->OperatingSystem, 'Series' => $request->Series,
            'Antivirus' => $request->Antivirus, 'Employee' => $request->Employee, 'Supplier' => $request->Supplier, 'Status' => $request->Status, 'Note' => $request->Note,
            'PurchaseDate'  => $request->PurchaseDate, 'YearAdd' => $tahun, 'MonthAdd' => $bulan, 'Freq' => $Freq
        ]);

        // dd($data1);

        if ($request->Printer1 <> null) {
            $update1 = hardware::find($request->Printer1);
            $update1->CPU = $insert_data_cpu->ID;
            $update1->Status = 'Terpakai';
            $update1->save();
        }

        if ($request->Printer2 <> null) {
            $update2 = hardware::find($request->Printer2);
            $update2->CPU = $insert_data_cpu->ID;
            $update2->Status = 'Terpakai';
            $update2->save();
        }

        if ($request->Memory1 <> null) {
            $update3 = hardware::find($request->Memory1);
            $update3->CPU = $insert_data_cpu->ID;
            $update3->Status = 'Terpakai';
            $update3->save();
        }

        if ($request->Memory2 <> null) {
            $update4 = hardware::find($request->Memory2);
            $update4->CPU = $insert_data_cpu->ID;
            $update4->Status = 'Terpakai';
            $update4->save();
        }

        if ($request->Storage1 <> null) {
            $update5 = hardware::find($request->Storage1);
            $update5->CPU = $insert_data_cpu->ID;
            $update5->Status = 'Terpakai';
            $update5->save();
        }

        if ($request->Storage2 <> null) {
            $update6 = hardware::find($request->Storage2);
            $update6->CPU = $insert_data_cpu->ID;
            $update6->Status = 'Terpakai';
            $update6->save();
        }

        if ($request->Processor <> null) {
            $update7 = hardware::find($request->Processor);
            $update7->CPU = $insert_data_cpu->ID;
            $update7->Status = 'Terpakai';
            $update7->save();
        }

        if ($request->Mainboard <> null) {
            $update8 = hardware::find($request->Mainboard);
            $update8->CPU = $insert_data_cpu->ID;
            $update8->Status = 'Terpakai';
            $update8->save();
        }

        if ($request->Mouse <> null) {
            $update9 = hardware::find($request->Mouse);
            $update9->CPU = $insert_data_cpu->ID;
            $update9->Status = 'Terpakai';
            $update9->save();
        }

        if ($request->Keyboard <> null) {
            $update10 = hardware::find($request->Keyboard);
            $update10->CPU = $insert_data_cpu->ID;
            $update10->Status = 'Terpakai';
            $update10->save();
        }

        if ($request->WeightScale <> null) {
            $update11 = hardware::find($request->WeightScale);
            $update11->CPU = $insert_data_cpu->ID;
            $update11->Status = 'Terpakai';
            $update11->save();
        }

        if ($request->Scanner <> null) {
            $update12 = hardware::find($request->Scanner);
            $update12->CPU = $insert_data_cpu->ID;
            $update12->Status = 'Terpakai';
            $update12->save();
        }

        if ($request->BarcodeScanner <> null) {
            $update13 = hardware::find($request->BarcodeScanner);
            $update13->CPU = $insert_data_cpu->ID;
            $update13->Status = 'Terpakai';
            $update13->save();
        }

        if ($request->UPS <> null) {
            $update14 = hardware::find($request->UPS);
            $update14->CPU = $insert_data_cpu->ID;
            $update14->Status = 'Terpakai';
            $update14->save();
        }

        if ($request->VGA <> null) {
            $update15 = hardware::find($request->VGA);
            $update15->CPU = $insert_data_cpu->ID;
            $update15->Status = 'Terpakai';
            $update15->save();
        }

        if ($request->Monitor <> null) {
            $update16 = hardware::find($request->Monitor);
            $update16->CPU = $insert_data_cpu->ID;
            $update16->Status = 'Terpakai';
            $update16->save();
        }

        // dd($data1);

        if ($insert_data_cpu) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Berhasil!!',
                    'message' => 'Berhasil!!',
                ],
                201,
            );
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal!'
            ], 401);
        }
    }

    public function DataPCUpdate(Request $request, $id)
    {
        
        $update_hardware = hardware::where('CPU', $id)->update([
            'Status' => 'Belum Terpakai',
            'CPU' => null,
        ]);

        $data1 = FacadesDB::connection('dev')->select("
            SELECT MAX(Freq)+1 AS FQ FROM data_cpu WHERE SW='$request->sw'
        ");
        foreach ($data1 as $data1s) {
        }

        $tglfull = date('Y-m-d h:i:s');
        $tgl = date('Y-m-d');
        $tahun =  date("y");
        $bulan =  date("m");

        $insert_data_cpu = data_cpu::create([
            'Active' => '1', 'Type' => $request->Type, 'SW' => $request->sw, 'ComputerName' => $request->ComputerName, 'IPAddress' => $request->IPAddress,
            'MACAddress' => $request->MACAddress, 'Mainboard' => $request->Mainboard, 'Processor' => $request->Processor, 'Memory1' => $request->Memory1, 'Memory2' => $request->Memory2,
            'Storage1' => $request->Storage1, 'Storage2' => $request->Storage2, 'Monitor' => $request->Monitor, 'VGA' => $request->VGA, 'Mouse' => $request->Mouse,
            'Keyboard' => $request->Keyboard, 'Printer1' => $request->Printer1, 'Printer2' => $request->Printer2, 'WeightScale' => $request->WeightScale, 'Scanner' => $request->Scanner,
            'BarcodeScanner' => $request->BarcodeScanner, 'UPS' => $request->UPS, 'Domain' => $request->Domain, 'OperatingSystem' => $request->OperatingSystem, 'Series' => $request->Series,
            'Antivirus' => $request->Antivirus, 'Employee' => $request->Employee, 'Supplier' => $request->Supplier, 'Status' => $request->Status, 'Note' => $request->Note,
            'PurchaseDate'  => $request->PurchaseDate, 'YearAdd' => $tahun, 'MonthAdd' => $bulan, 'Freq' => $data1s->FQ, 'TransDate' => $tglfull
        ]);

        $update_data_cpu = data_cpu::find($id);
        $update_data_cpu->Active = '0';
        $update_data_cpu->save();

        if ($request->Printer1 <> null) {
            $update1 = hardware::find($request->Printer1);
            $update1->CPU = $insert_data_cpu->ID;
            $update1->Status = 'Terpakai';
            $update1->save();
        }

        if ($request->Printer2 <> null) {
            $update2 = hardware::find($request->Printer2);
            $update2->CPU = $insert_data_cpu->ID;
            $update2->Status = 'Terpakai';
            $update2->save();
        }

        if ($request->Memory1 <> null) {
            $update3 = hardware::find($request->Memory1);
            $update3->CPU = $insert_data_cpu->ID;
            $update3->Status = 'Terpakai';
            $update3->save();
        }

        if ($request->Memory2 <> null) {
            $update4 = hardware::find($request->Memory2);
            $update4->CPU = $insert_data_cpu->ID;
            $update4->Status = 'Terpakai';
            $update4->save();
        }

        if ($request->Storage1 <> null) {
            $update5 = hardware::find($request->Storage1);
            $update5->CPU = $insert_data_cpu->ID;
            $update5->Status = 'Terpakai';
            $update5->save();
        }

        if ($request->Storage2 <> null) {
            $update6 = hardware::find($request->Storage2);
            $update6->CPU = $insert_data_cpu->ID;
            $update6->Status = 'Terpakai';
            $update6->save();
        }

        if ($request->Processor <> null) {
            $update7 = hardware::find($request->Processor);
            $update7->CPU = $insert_data_cpu->ID;
            $update7->Status = 'Terpakai';
            $update7->save();
        }

        if ($request->Mainboard <> null) {
            $update8 = hardware::find($request->Mainboard);
            $update8->CPU = $insert_data_cpu->ID;
            $update8->Status = 'Terpakai';
            $update8->save();
        }

        if ($request->Mouse <> null) {
            $update9 = hardware::find($request->Mouse);
            $update9->CPU = $insert_data_cpu->ID;
            $update9->Status = 'Terpakai';
            $update9->save();
        }

        if ($request->Keyboard <> null) {
            $update10 = hardware::find($request->Keyboard);
            $update10->CPU = $insert_data_cpu->ID;
            $update10->Status = 'Terpakai';
            $update10->save();
        }

        if ($request->WeightScale <> null) {
            $update11 = hardware::find($request->WeightScale);
            $update11->CPU = $insert_data_cpu->ID;
            $update11->Status = 'Terpakai';
            $update11->save();
        }

        if ($request->Scanner <> null) {
            $update12 = hardware::find($request->Scanner);
            $update12->CPU = $insert_data_cpu->ID;
            $update12->Status = 'Terpakai';
            $update12->save();
        }

        if ($request->BarcodeScanner <> null) {
            $update13 = hardware::find($request->BarcodeScanner);
            $update13->CPU = $insert_data_cpu->ID;
            $update13->Status = 'Terpakai';
            $update13->save();
        }

        if ($request->UPS <> null) {
            $update14 = hardware::find($request->UPS);
            $update14->CPU = $insert_data_cpu->ID;
            $update14->Status = 'Terpakai';
            $update14->save();
        }

        if ($request->VGA <> null) {
            $update15 = hardware::find($request->VGA);
            $update15->CPU = $insert_data_cpu->ID;
            $update15->Status = 'Terpakai';
            $update15->save();
        }

        if ($request->Monitor <> null) {
            $update16 = hardware::find($request->Monitor);
            $update16->CPU = $insert_data_cpu->ID;
            $update16->Status = 'Terpakai';
            $update16->save();
        }

        if ($update_hardware) {
            return response()->json(
                [
                    'success' => true,
                    'title' => 'Berhasil!!',
                    'message' => 'Berhasil!!',
                ],
                201,
            );
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal!'
            ], 401);
        }
    }
}
<?php

namespace App\Http\Controllers;

use QrCode;
use App\Models\user;
use Milon\Barcode\DNS2D;
use Milon\Barcode\DNS1D;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB as FacadesDB;

use Illuminate\Support\Facades\Http;

class tesfungsi extends Controller
{

    public function tes(Request $request)
    {

        $token = '4d81c116fcbc84b:e4cc0447fbf1d27';
        $response = Http::withHeaders([
            'Authorization' => 'token ' . $token,
            'Accept' => 'application/json',
        ])->get('erp.lestarigold.co.id/api/resource/Warehouse?fields=["name","id_warehouse"]&filters=[["parent_warehouse","=","Gudang Produksi - LMS"]]&order_by=name&limit=100');
        $data = $response->json();
        // dd($data);

        return view('setting.tes');

        dd($request);
    }
    public function tes3()
    {

        $users = User::all();
        $jsonData = json_encode($users);
        File::put(public_path('assets\temp\users.json'), $jsonData);
        // Storage::put('users.json', $jsonData);

        dd($users);
        // Menggunakan koneksi database dengan nama 'dev'
        //     $mango = DB::connection('dev');

        //     // Drop stored procedure jika sudah ada
        //     $mango->unprepared('DROP PROCEDURE IF EXISTS looping_procedure');

        //     // Membuat stored procedure
        //     $mango->unprepared('CREATE PROCEDURE looping_procedure()
        // BEGIN
        //     DECLARE i INT DEFAULT 1;
        //     DECLARE max_value INT DEFAULT 10;

        //     DROP TABLE IF EXISTS results;
        //     CREATE TEMPORARY TABLE results (
        //         id INT AUTO_INCREMENT PRIMARY KEY,
        //         result VARCHAR(255)
        //     );

        //     WHILE i <= max_value DO
        //         IF i % 2 = 0 THEN
        //             INSERT INTO results (result) VALUES (CONCAT(i, " is even"));
        //         ELSE
        //             INSERT INTO results (result) VALUES (CONCAT(i, " is odd"));
        //         END IF;

        //         SET i = i + 1;
        //     END WHILE;

        //     SELECT * FROM results;
        // END');

        //     // Menjalankan stored procedure dan mendapatkan hasilnya
        //     $results = $mango->select('CALL looping_procedure()');

        //     // Menampilkan hasil dari stored procedure
        //     foreach ($results as $result) {
        //         echo $result->result . '<br>';
        //     }

        // Set the value of @number variable
        DB::select('SET @number = 5');

        // Execute the IF statement query
        $result = DB::select('SELECT IF(@number > 0, CONCAT(@number, " is a positive number"), IF(@number < 0, CONCAT(@number, " is a negative number"), CONCAT(@number, " is zero"))) AS result');

        // Output the result
        // dd($result);

        // Execute the looping_procedure stored procedure
        $result = DB::unprepared('
    DROP PROCEDURE IF EXISTS looping_procedure;
    CREATE PROCEDURE looping_procedure()
    BEGIN
         DECLARE i INT DEFAULT 1;
            DECLARE max_value INT DEFAULT 10;

            DROP TABLE IF EXISTS results;
            CREATE TEMPORARY TABLE results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                result VARCHAR(255)
            );

            WHILE i <= max_value DO
                IF i % 2 = 0 THEN
                    INSERT INTO results (result) VALUES (CONCAT(i, " is even"));
                ELSE
                    INSERT INTO results (result) VALUES (CONCAT(i, " is odd"));
                END IF;

                SET i = i + 1;
            END WHILE;

            SELECT * FROM results;
    END
');

        DB::select('CALL looping_procedure()');

        // Execute the jamik stored procedure
        DB::unprepared('
    DROP PROCEDURE IF EXISTS jamik;
    CREATE PROCEDURE jamik()
    BEGIN
        DECLARE i INT DEFAULT 1;
        DECLARE max_value INT DEFAULT 10;
        
        WHILE i <= max_value DO
            IF i % 2 = 0 THEN
                SELECT CONCAT(i, " is even") AS result;
            ELSE
                SELECT CONCAT(i, " is odd") AS result;
            END IF;
            
            SET i = i + 1;
        END WHILE;
    END
');

        DB::select('CALL jamik()');

        dd($result);
        dd('huhu');
// ambil hasil query
// $results = DB::connection('dev')->select('SELECT result FROM information_schema.processlist WHERE COMMAND="Query" AND INFO LIKE "%CALL looping_procedure%"');
// dd($results);

            // dd($mango);

        return view('setting.tes');
    }

    // public function tes2()
    // {
    //     session(['tema' => '1']);
    //     return view('setting.tes2', ['title' => 'home']);
    // }

    public function tes2(Request $request)
    {
        $id = '253';


        return view('setting.tes2', compact('id'));
    }

    public function tes23()
    {
        DB::enableQueryLog();
        $user = user::from('users as a')
            ->LeftJoin('employee as b', 'b.SW', '=', 'a.name')
            ->LeftJoin('master_level_laravel as c', 'c.Id_Level', '=', 'a.level')
            ->select('a.id', 'a.name', 'a.level', 'a.status', 'b.Description', 'c.Nama_level')
            ->get();

        dd(DB::getQueryLog());
        dd($user);

        // dd(session()->all());
    }

    //membaca file txt
    public function tes22()
    {
        // Open File
        $filename = storage_path('temp/test5.txt');
        $open = File::get($filename);

        // Turn file from string to array
        $splited = preg_split('/\n|\r\n?/', $open);
        // dd($splited);

        // Array container
        $newdata = [];

        $head = '';
        $body[] = 0;
        $a = 0;
        $b = 0;
        for ($i = 0; $i < count($splited); $i++) {
            if ($i > 3) {
                if ($splited[$i] != '') {
                    array_push($newdata, $splited[$i]);
                    if ($i == 4) {
                        $head = preg_replace('/[^A-Za-z0-9\-]/', ' ', $splited[$i]);
                        $head1 = preg_split('/\s+/', $head);
                        array_pop($head1);
                        array_shift($head1);
                    } elseif ($i > 4) {
                        $a++;
                        $bodys = preg_replace('/%/', '', $splited[$i]);
                        $body0[$a] = preg_split('/\s\s+/', $bodys);

                        if ($body0[$a][1] == 'Au') {
                            $head2 = $body0[$a];
                            $b++;
                            $a = 0;
                        }
                        if ($b == 0) {
                            $body1[$a] = $body0[$a];
                            array_pop($body1[$a]);
                            array_shift($body1[$a]);
                        } elseif ($b == 1 and $a != 0 and $a <= 7) {
                            $body2[$a] = $body0[$a];
                        }
                    }
                }
            }
        }

        dd($head1, $body1, $head2, $body2);
    }

    public function proses1(Request $request)
    {
        

        // if ($request->gambar[1] != 'undefined') {

        //     $fileName = 'tes.jpg';
        //     $request->gambar[1]->storeAs('tess', $fileName, 'UploadGrafis');
        // } else {

        // }

        dd($request);
    }
}

<?php
echo "
php artisan make:controller Setting/BlogController
php artisan make:model Setting/Blog
php artisan route:pretty
php artisan log:clear
php artisan optimize:clear

";

// cek variabel tersedia
if (isset($a)) {
    echo "Variable 'a' is set.<br>";
}
//cek sesion
dd($request->session()->all());

//dapatkan nilai dasi sesion
$request->session()->get('iduser');
$iddept = session('iddept');

//memotong string
substr($request->$sku, 0, -2);

//replace
str_replace('world', 'Peter', 'Hello world!');

//Zero Padding atau Angka Nol di depan
sprintf('%03d', $bilangan);

//memecah string
explode(',', $string);

//mengases file dari depan
$_SERVER['DOCUMENT_ROOT'] . '/Assets/koneksi.php';

//if dalam list
$myArray = array('apple', 'banana', 'orange');
$fruit = 'banana';

if (in_array($fruit, $myArray)) {
    echo $fruit . ' is in the array.';
} else {
    echo $fruit . ' is not in the array.';
}

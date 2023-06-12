<?php

use GuzzleHttp\Middleware;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public_Function_Controller;
use App\Http\Controllers\publick_function_sampel;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\tesfungsi;

use App\Http\Controllers\Master\Produksi\CycleTimeController;

use App\Http\Controllers\Absensi\Absensi\CheckClockManualController;
use App\Http\Controllers\Absensi\Absensi\AbsensiLemburKerjaController;
use App\Http\Controllers\Absensi\Absensi\AbsensiTambahanUangMakanController;
use App\Http\Controllers\Absensi\Absensi\DispensasiKeterlambatanController;
use App\Http\Controllers\Absensi\Absensi\PilihanLemburKerjaController;
use App\Http\Controllers\Absensi\Absensi\PilihanManualCheckclockController;
use App\Http\Controllers\Absensi\Absensi\AbsensiIjinKerjaController;
use App\Http\Controllers\Absensi\Absensi\AbsensiBeritaAcaraController;
use App\Http\Controllers\Absensi\Absensi\DownloadFaceAbsenController;
use App\Http\Controllers\Absensi\Absensi\DownloadFingerAbsenController;
use App\Http\Controllers\Absensi\Gaji\GajiMagangController;
use App\Http\Controllers\Absensi\Informasi\JamKerjaController;
use App\Http\Controllers\Absensi\Informasi\PenilaianKehadiranController;
use App\Http\Controllers\Absensi\Informasi\BeritaAcaraController;
use App\Http\Controllers\Absensi\Informasi\CheckClockController;
use App\Http\Controllers\Absensi\Informasi\IjinKerjaController;
use App\Http\Controllers\Absensi\Informasi\LemburKerjaController;
use App\Http\Controllers\Absensi\Informasi\AbsensiTidakLengkapController;
use App\Http\Controllers\Absensi\Informasi\AbsensiBulananController;
use App\Http\Controllers\Absensi\Informasi\KoreksiAbsensiController;
use App\Http\Controllers\Absensi\Informasi\UpahPSBController;
use App\Http\Controllers\Absensi\Informasi\TambahanUangMakanController;
use App\Http\Controllers\Absensi\Informasi\InformasiJaminanKaryawanController;
use App\Http\Controllers\Absensi\PenilaianAbsensi\PenilaianAbsensiController;
use App\Http\Controllers\Absensi\JaminanKaryawan\JaminanKaryawanController;
use App\Http\Controllers\Absensi\Informasi\InformasiFaceAbsentController;

use App\Http\Controllers\Produksi\PPIC\SPKPPICDirectCastingController;
use App\Http\Controllers\Produksi\PelaporanProduksi\NTHKOController;
use App\Http\Controllers\Produksi\PelaporanProduksi\SPKOController;
use App\Http\Controllers\Produksi\PelaporanProduksi\SPKOTambahanController;
use App\Http\Controllers\Produksi\PelaporanProduksi\TMController;
use App\Http\Controllers\Produksi\PelaporanProduksi\TFGController;
use App\Http\Controllers\Produksi\PelaporanProduksi\SusutanController;
use App\Http\Controllers\Produksi\LainLain\StatusInfoController;
use App\Http\Controllers\Produksi\Informasi\JadwalKerjaHarianController;
use App\Http\Controllers\Produksi\Informasi\LeadTimeController;
use App\Http\Controllers\Produksi\Informasi\RoutingProduksiController;
use App\Http\Controllers\Produksi\JadwalKerjaHarian\RPHProduksiController;
use App\Http\Controllers\Produksi\JadwalKerjaHarian\RPHLilinController;
use App\Http\Controllers\Produksi\JadwalKerjaHarian\PermintaanKomponenController;

use App\Http\Controllers\Produksi\PelaporanProduksi\ComponentOrderController;

use App\Http\Controllers\Produksi\Lilin\NTHKOPohonanController;
use App\Http\Controllers\Produksi\Lilin\PostingKaretPcbController;
use App\Http\Controllers\Produksi\Laboratorium\LaboratoriumXrayController;
use App\Http\Controllers\Produksi\Laboratorium\LabTurunKadarController;

use App\Http\Controllers\LainLain\Korespondensi\SuratJalanController;
use App\Http\Controllers\LainLain\Korespondensi\SuratPengantarController;
use App\Http\Controllers\LainLain\Korespondensi\TandaTerimaController;

use App\Http\Controllers\Akunting\Informasi\StokAkhirBulan\StokAkhirBulanController;


use App\Http\Controllers\RnD\Percobaan\TMKaretPCBKeLilinController;
use App\Http\Controllers\RnD\Percobaan\TMKaretQcPCBKeLilinController;
// module Grafis
use App\Http\Controllers\RnD\Percobaan\PostingTMPCBController;
use App\Http\Controllers\RnD\Percobaan\WipGrafisController;
use App\Http\Controllers\RnD\Grafis\SPKOGrafisController;
use App\Http\Controllers\RnD\Informasi\InformasiWIPGrafisController;
use App\Http\Controllers\RnD\Informasi\InformasiProdukController;

use App\Http\Controllers\RnD\Percobaan\TransferFGKonfirmasiController;
use App\Http\Controllers\RnD\Percobaan\TransferFGQCController;
use App\Http\Controllers\RnD\Percobaan\TransferFGQCEnamelController;

use App\Http\Controllers\Penjualan\Informasi\MPC\FormOrderProduksi\FormOrderProduksiController;
use App\Http\Controllers\Penjualan\MPC\FormOrderProduksiDCController;

// Info RnD
use App\Http\Controllers\RnD\Informasi\InfoRekapProduktivitasRnDController;
use App\Http\Controllers\RnD\Informasi\InformasiTMKaretPCBController;

//Permintaan Komponen PCB
use App\Http\Controllers\RnD\Percobaan\PermintaanKomponenTanpaNTHKOController;
use App\Http\Controllers\RnD\Percobaan\KatalogRoutingController;

// WORKSHOP MATRAS
use App\Http\Controllers\Workshop\VerifikasiWorkshopController;
use App\Http\Controllers\Workshop\WIPWorkshopController;
use App\Http\Controllers\Workshop\TMMatrasWorkshopController;
use App\Http\Controllers\Workshop\GambarTeknikWorkshopController;
// SPKO Workshop
use App\Http\Controllers\Workshop\SPKO\SPKOMatrasWorkshopController;
use App\Http\Controllers\Workshop\SPKO\SPKOPercobaanKuninganWorkshopController;
// NTHKO Workshop
use App\Http\Controllers\Workshop\NTHKO\NTHKOMatrasWorkshopController;
// API Worklog
use App\Http\Controllers\API\WorkLog\WorklogApiController;

//CampurBahan
use App\Http\Controllers\Produksi\Informasi\InfoKebutuhanKomponenController;
use App\Http\Controllers\Produksi\CampurBahan\OrderCorCampurBahanController;
use App\Http\Controllers\Produksi\CampurBahan\OrderGTCampurBahanController;


// Tukang Luar
use App\Http\Controllers\Produksi\TukangLuar\ProduksiTukangLuarController;

//!!  ------------------------ Tanpa middleware ------------------------ !!
// login
Route::get('/login', 'LoginController@index') ->name('login') ->middleware('guest');
Route::post('/login', 'LoginController@check_login')->name('login.check_login');
Route::get('/logout', 'DashboardController@logout')->name('dashboard.logout');
Route::get('/welcome', function () { return view('welcome'); });
Route::get('timbangan', function () { return view('Setting.publick_function.timbangan'); })->name('timbangan');
Route::get('tes2', [tesfungsi::class, 'tes2']);

//!!  ------------------------    Dashboard    ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard.index');
    Route::get('About',[SettingController::class, 'About'])->name('setting.About');
    Route::get('Account', [SettingController::class, 'Account'])->name('setting.Account');

    Route::get('/todomenu/{id?}', 'DashboardController@todomenu');
    Route::get('/todolist', [SettingController::class, 'todolist'])->name('setting.todolist');
    Route::get('/todolist/filter/{id?}', [SettingController::class, 'todolist_filter']);
    Route::get('/todolist/name', [SettingController::class, 'todolist_name']);
    Route::get('/todolist/update/{id?}', [SettingController::class, 'todolist_update']);
    Route::post('/todolist/tambah', [SettingController::class, 'todolist_tambah']);
    Route::put('/todolist/edit/{id?}', [SettingController::class, 'todolist_edit']);
    Route::put('/todolist/edit2/{id?}', [SettingController::class, 'todolist_edit2']);

    Route::get('/Pengumumanmenu/{id?}', 'DashboardController@Pengumumanmenumenu');
    Route::get('/Pengumuman', [SettingController::class, 'Pengumuman']);
    Route::get('/Pengumuman/announceto', [SettingController::class, 'Pengumuman_announceto']);
    Route::post('/pengumuman/tambah', [SettingController::class, 'Pengumuman_tambah']);
    Route::get('/pengumuman/filter/{id?}', [SettingController::class, 'pengumuman_filter']);
    
    Route::get('gantipswd', [SettingController::class, 'gantipswd'])->name('setting.gantipswd');
    Route::put('/gantipswd/{id?}', [SettingController::class, 'gantipswdspn'])->name('user.gantipswdspn'); 

    Route::post('/messaging', [MessagingController::class, 'messagingCreat'])->name('messaging.creat');
    Route::put('/messaging/{id?}', [MessagingController::class, 'messagingUpdate'])->name('messaging.update');
    Route::put('/messaging/read/{id?}', [MessagingController::class, 'Messaging_read'])->name('messaging.read');
    Route::post('/messaging/readall', [MessagingController::class, 'Messaging_readall'])->name('messaging.readall');
    Route::get('Messaging_list', [MessagingController::class, 'Messaging_list'])->name('Messaging_list');
    Route::get('Messaging_count', [MessagingController::class, 'Messaging_count'])->name('Messaging_count');
    Route::get('Messaging_write/{id?}', [MessagingController::class, 'Messaging_write'])->name('Messaging_write');

    Route::get('/Notification_count', [MessagingController::class, 'Notif_count'])->name('Notification.count');
    Route::get('/Notification_list', [MessagingController::class, 'Notif_list'])->name('Notification.list');
    Route::put('/Notification/NTread/{id?}', [MessagingController::class, 'Notif_read'])->name('Notification.read');
    Route::post('/Notification/NTreadall', [MessagingController::class, 'Notif_readall'])->name('Notification.readall');
});

//!  ------------------------     Settings     ------------------------ !!
Route::group(['middleware' => ['auth', 'ceklevel:1']], function () {
    Route::get('Setting', [SettingController::class, 'index'])->name('setting');

    Route::get('user', [SettingController::class, 'user'])->name('setting.user');
    Route::post('/user', [SettingController::class, 'userCreat'])->name('user.creat');
    Route::get('/user/{link_user?}', [SettingController::class, 'UserEdit'])->name('user.edit');
    Route::put('/user/{link_user?}', [SettingController::class, 'UserUpdate'])->name('user.update');
    Route::post('/UserUpdatePSW', [SettingController::class, 'UserUpdatePSW'])->name('user.Userpsw');

    Route::get('menuLevel', [SettingController::class, 'menuLevel'])->name('setting.menuLevel');
    Route::post('/menuLevel', [SettingController::class, 'menuLevelCreat'])->name('menuLevel.creat');
    Route::get('/menuLevel/{link_user?}', [SettingController::class, 'menuLevelEdit'])->name('menuLevel.edit');
    Route::put('/menuLevel/{link_user?}', [SettingController::class, 'menuLevelUpdate'])->name('menuLevel.update');

    Route::get('ListMenu', [SettingController::class, 'ListMenu'])->name('setting.ListMenu');
    Route::get('/ListMenu/tambah', [SettingController::class, 'ListMenuTambah'])->name('ListMenu.Tambah');
    Route::post('/links', [SettingController::class, 'ListMenuCreat'])->name('ListMenu.creat');
    Route::get('/links/{link_id?}', [SettingController::class, 'ListMenuEdit'])->name('ListMenu.edit');
    Route::put('/links/{link_id?}', [SettingController::class, 'ListMenuUpdate'])->name('ListMenu.update');
    Route::delete('/links/{link_id?}', [SettingController::class, 'ListMenuDelet'])->name('ListMenu.delet');
    Route::get('/ListMenu/ordinal/{id?}', [SettingController::class, 'ListMenuordinal']);
    Route::get('/ListMenu/Akses/{id?}', [SettingController::class, 'ListMenuAkses']);
    Route::post('/ListMenu/Akses', [SettingController::class, 'ListMenuAksesCreat']);
    Route::put('/ListMenu/Akses/{id?}', [SettingController::class, 'ListMenuAksesUpdate']);

    Route::get('MenuQA', [SettingController::class, 'MenuQA'])->name('setting.MenuQA');
    Route::post('/MenuQA', [SettingController::class, 'MenuQACreat'])->name('MenuQA.creat');
    Route::get('/MenuQA/{link_MenuQA?}', [SettingController::class, 'MenuQAEdit'])->name('MenuQA.edit');
    Route::put('/MenuQA/{link_MenuQA?}', [SettingController::class, 'MenuQUpdate'])->name('MenuQA.update');
    Route::delete('/MenuQA/{id?}', [SettingController::class, 'MenuQADelet']);
    Route::get('/MenuQA/ordinal/{id?}', [SettingController::class, 'MenuQAordinal']);

    //searching data
    Route::get('autouser', [SettingController::class, 'autouser'])->name('autouser');
    Route::get('automodul', [SettingController::class, 'automodul'])->name('automodul');
    Route::get('autousererp', [SettingController::class, 'autousererp'])->name('autousererp');
    Route::get('autoparent', [SettingController::class, 'autoparent'])->name('autoparent');
    Route::get('/searchSKU', [SettingController::class, 'searchSKU'])->name('searchSKU');

    Route::get('/tabel', [SettingController::class, 'demotabel']);
    Route::get('/forms_basic', [SettingController::class, 'forms_basic']);
    Route::get('/forms_basic/isi/{no?}/{id?}', [SettingController::class, 'forms_basicLihat']);
    Route::put('/forms_basic/{id?}', [SettingController::class, 'forms_basicupdate']);
    Route::get('sampel', function () {return view('setting.sampel'); })->name('sampel');
    Route::get('/forms_layouts', function () {return view('setting.forms_layouts'); });
    Route::get('/Input_Group', function () { return view('setting.Input_Group'); });
    Route::get('/buttons', function () { return view('setting.buttons'); });
    Route::get('/modals', function () { return view('setting.modals'); });
    Route::get('/Publick_Function', function () { return view('setting.Publick_Function'); });
    Route::get('/tes', [tesfungsi::class, 'tes'] );
    Route::post('/tes', [tesfungsi::class, 'proses1']);
    
});

//!  ------------------------ Publick Function ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
    // Route::get('timbangan', function () { return view('setting.publick_function.timbangan'); })->name('timbangan');
    Route::get('tesposting',  function () {return view('setting.publick_function.TesPosting'); });
    Route::post('tesposting', [publick_function_sampel::class,'tesposting']);
    Route::get('tespostingnew', function () {return view('setting.publick_function.TesPostingNew'); });
    Route::post('tespostingnew', [publick_function_sampel::class, 'tespostingnew']);
    Route::get('TespostingTM', [publick_function_sampel::class, 'TespostingTMLihat']);
    Route::post('TespostingTM', [publick_function_sampel::class, 'TespostingTM']);
    Route::get('tesGetLastID', [publick_function_sampel::class,'tesGetLastID']);
    Route::get('TesListUserHistory', [publick_function_sampel::class,'TesListUserHistory']);
    Route::get('TesUpdateUserHistory', [publick_function_sampel::class,'TesUpdateUserHistory']);
    Route::get('TesViewSelection', [publick_function_sampel::class, 'TesViewSelection']);
    Route::get('ViewSelection', [Public_Function_Controller::class, 'ViewSelection']);
    Route::get('TesCekStokHarian', [publick_function_sampel::class, 'TesCekStokHarian']);
    Route::get('TesCekStokHarian2', [publick_function_sampel::class, 'TesCekStokHarian2']);
    Route::get('TesSetStatustransaction', [publick_function_sampel::class, 'TesSetStatustransaction']);
});

//!  ------------------------      Master      ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //todo  ------------------      Gudang  Katalog Bahan Pembantu     ------------------------ !!
    Route::controller('Master\Gudang\KatalogBahanPembantuController'::class)->prefix('/Master/Gudang/KatalogBahanPembantu')->group(function () {
        Route::get('/', 'Index');
        Route::get('/menu/{menu?}', 'menu');
        Route::get('/pagination', 'pagination');
        Route::get('/carifilter', 'carifilter');
        Route::get('/lihat/{id?}', 'lihat');
        Route::get('/ubah/{id?}', 'ubah');
        Route::get('/gambar/{id?}', 'gambar');
        Route::post('/edit', 'edit');
        Route::post('/uploadgambar', 'uploadgambar');
        Route::get('/mobile', 'mobile');
        Route::post('/mobile', 'mobile_store');
    });

    //  ------------------      CycleTime     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,35,42']], function () {
        Route::get('/Master/Produksi/CycleTime', [CycleTimeController::class, 'index']);
        Route::post('/Master/Produksi/CycleTime/lihat', [CycleTimeController::class, 'lihat']);
        Route::post('/Master/Produksi/CycleTime/simpan', [CycleTimeController::class, 'simpan']);
        Route::get('/Master/Produksi/CycleTime/lihatDataMaster', [CycleTimeController::class, 'lihatDataMaster']);
    });


});

//!  ------------------------     Penjualan    ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
    //todo ------------------------------ Informasi -------------------------------- !!
        //!  ------------------------ Informasi ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,30,31, 32']], function () {
            Route::get('/Penjualan/Informasi/MPC/FormOrderProduksi', [FormOrderProduksiController::class, 'index']);
            Route::get('/Penjualan/Informasi/MPC/FormOrderProduksi/gettingFormOrderProduksi', [FormOrderProduksiController::class, 'gettingFormOrderProduksi']);
            // Route::get('/Akunting/Informasi/StokAkhirBulan/gettingStokAkhirBulanOpname', [StokAkhirBulanController::class, 'gettingStokAkhirBulanOpname']);
            //Route::get('/Penjualan/Informasi/FormOrderProduksi/setYear', [FormOrderProduksiController::class, 'setYear']);
            Route::get('/Penjualan/Informasi/MPC/FormOrderProduksi/formOrder', [FormOrderProduksiController::class, 'formOrder']);
            // Route::get('/Akunting/Informasi/StokAkhirBulan/formDaily', [StokAkhirBulanController::class, 'dailystock']);
        
        });

        //!  ------------------------ MPC ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2']], function () {
            Route::resource('/Penjualan/MPC/FormOrderProduksi', 'Penjualan\MPC\FormOrderProduksiDCController'::class);
            //Route::post('/RnD/Informasi/InformasiProduk/GetListProdukKomponen', [InformasiProdukController::class, 'GetListProdukKomponen']);
            Route::post('/Penjualan/MPC/FormOrderProduksi/listProduk', [FormOrderProduksiDCController::class, 'listProduk']);
            Route::post('/Penjualan/MPC/FormOrderProduksi/simpanws', [FormOrderProduksiDCController::class, 'simpanws']);
            Route::post('/Penjualan/MPC/FormOrderProduksi/cekProduk', [FormOrderProduksiDCController::class, 'cekProduk']);
            //Route::get('/R&D/Percobaan/SPKPercobaan', [SPKPercobaanController::class, 'index']);
        });
});

//!  ------------------------    Pembelian     ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
});

//!  ------------------------     Produksi     ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
    //todo ------------------------------ Campur Bahan -------------------------------- !!

    //?  ------------------------   Campur Bahan Informasi Kebutuhan Komponen  ------------------------ !!
    // Route::group(['middleware' => ['ceklevel:1,2,61,48,69']], function () {
    //     Route::get('/Produksi/CampurBahan/InfoKebutuhanKomponen', [InfoKebutuhanKomponenController::class, 'index']);
    //     Route::get('/Produksi/CampurBahan/InfoKebutuhanKomponen/getSPKProduksi', [InfoKebutuhanKomponenController::class, 'getSPKProduksi']);
    //     Route::get('/Produksi/CampurBahan/InfoKebutuhanKomponen/getFilter', [InfoKebutuhanKomponenController::class, 'getFilter']);
    //     Route::get('/Produksi/CampurBahan/InfoKebutuhanKomponen/getSPKRouting', [InfoKebutuhanKomponenController::class, 'getSPKRouting']);
    // });

    Route::group(['middleware' => ['ceklevel:1,2,61,48,69,44']], function () {
        Route::get('/Produksi/Informasi/CampurBahan', [InfoKebutuhanKomponenController::class, 'index']);
        Route::get('/Produksi/Informasi/CampurBahan/getSPKProduksi', [InfoKebutuhanKomponenController::class, 'getSPKProduksi']);
        Route::get('/Produksi/Informasi/CampurBahan/getFilter', [InfoKebutuhanKomponenController::class, 'getFilter']);
        Route::get('/Produksi/Informasi/CampurBahan/getSPKRouting', [InfoKebutuhanKomponenController::class, 'getSPKRouting']);
    });

    //?  ------------------------   Campur Bahan Permintaan Cor  ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,61']], function () {
        Route::get('/Produksi/CampurBahan/PermintaanCor', [OrderCorCampurBahanController::class, 'index']);
        Route::get('/Produksi/CampurBahan/PermintaanCor/getFilter', [OrderCorCampurBahanController::class, 'getFilter']);
        Route::get('/Produksi/CampurBahan/PermintaanCor/getProduk', [OrderCorCampurBahanController::class, 'getProduk']);
        // Route::get('/Produksi/CampurBahan/PermintaanCor/getFilter', [OrderCorCampurBahanController::class, 'getFilter']);
        // Route::get('/Produksi/CampurBahan/PermintaanCor/getSPKRouting', [OrderCorCampurBahanController::class, 'getSPKRouting']);
    });

     //?  ------------------------   Campur Bahan Permintaan GT  ------------------------ !!
     Route::group(['middleware' => ['ceklevel:1,2,61']], function () {
        Route::get('/Produksi/CampurBahan/PermintaanGT', [OrderGTCampurBahanController::class, 'index']);
        Route::get('/Produksi/CampurBahan/PermintaanGT/getFilter', [OrderGTCampurBahanController::class, 'getFilter']);
        Route::get('/Produksi/CampurBahan/PermintaanGT/getProduk', [OrderGTCampurBahanController::class, 'getProduk']);
        Route::get('/Produksi/CampurBahan/PermintaanGT/getSPK', [OrderGTCampurBahanController::class, 'getSPK']);
        Route::get('/Produksi/CampurBahan/PermintaanGT/getMaterial', [OrderGTCampurBahanController::class, 'getMaterial']);
        Route::post('/Produksi/CampurBahan/PermintaanGT/saveOrder', [OrderGTCampurBahanController::class, 'saveOrder']);
        Route::get('/Produksi/CampurBahan/PermintaanGT/scanMaterial', [OrderGTCampurBahanController::class, 'scanMaterial']);
        // Route::get('/Produksi/CampurBahan/PermintaanCor/getSPKRouting', [OrderCorCampurBahanController::class, 'getSPKRouting']);
    });





    //todo ------------------------------ PPIC -------------------------------- !!
    Route::group(['middleware' => ['ceklevel:1,2,27']], function () {

        //Route::get('/Produksi/PPIC/SPKPPICDirectCasting', 'Produksi\PPIC\SPKPPICDirectCastingController'::class);
        Route::get('/Produksi/PPIC/SPKPPICDirectCasting', [SPKPPICDirectCastingController::class, 'index']);
        Route::post('/Produksi/PPIC/SPKPPICDirectCasting/GetListItemPPIC', [SPKPPICDirectCastingController::class, 'GetListItemPPIC']);
        Route::post('/Produksi/PPIC/SPKPPICDirectCasting/simpan', [SPKPPICDirectCastingController::class, 'simpan']);
 
        //Route::get('/R&D/Percobaan/SPKPercobaan', [SPKPercobaanController::class, 'index']);
    });

     //!  ------------------------    PPIC Pohon Priority   ------------------------ !!
     Route::middleware(['ceklevel:1,2,14,27'])->controller('Produksi\PPIC\PohonPriorityController'::class)->prefix('/Produksi/PPIC/PohonPriority')->group(function () {
        Route::get('/', 'index');
        Route::put('/UbahjadiR/{IDpohon?}', 'UbahjadiR');
        Route::put('/UbahjadiN/{IDpohon?}', 'UbahjadiN');
        Route::put('/UbahjadiY/{IDpohon?}', 'UbahjadiY');
        Route::put('/Simpan', 'Simpan');
        Route::get('/Tabels','Tabels');
        Route::get('/Tabels2','Tabels2');
        Route::get('/cetak/{Priority?}/_blank', 'cetak');
    });

      //!  ------------------------    PPIC Pohon Priority   ------------------------ !!
      Route::middleware(['ceklevel:1,2'])->controller('Produksi\PPIC\PohonPriority2Controller'::class)->prefix('/Produksi/PPIC/PohonPriority2')->group(function () {
        Route::get('/', 'index');
        Route::put('/UbahjadiR/{IDpohon?}', 'UbahjadiR');
        Route::put('/UbahjadiN/{IDpohon?}', 'UbahjadiN');
        Route::put('/UbahjadiY/{IDpohon?}', 'UbahjadiY');
        Route::put('/Simpan', 'Simpan');
        Route::get('/Tabels','Tabels');
        Route::get('/Tabels2','Tabels2');
        Route::get('/cetak/{Priority?}/_blank', 'cetak');
    });


    //todo ------------------------------ Informasi -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   Jadwal Kerja Harian   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,26,34,35,36,37,38,39,53,54,55,48,69']], function () {
            Route::get('/Produksi/Informasi/JadwalKerjaHarian', [JadwalKerjaHarianController::class, 'index']);
            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportPerRPH', [JadwalKerjaHarianController::class, 'reportPerRPH']);
            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportPerTgl', [JadwalKerjaHarianController::class, 'reportPerTgl']);
            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportPerTglPercent', [JadwalKerjaHarianController::class, 'reportPerTglPercent']);
            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportSPKO', [JadwalKerjaHarianController::class, 'reportSPKO']);
            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportSPKO2', [JadwalKerjaHarianController::class, 'reportSPKO2']);
            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportNTHKO2', [JadwalKerjaHarianController::class, 'reportNTHKO2']);
            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakPerRPH', [JadwalKerjaHarianController::class, 'cetakPerRPH']);
            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakPerTgl', [JadwalKerjaHarianController::class, 'cetakPerTgl']);

            Route::post('/Produksi/Informasi/JadwalKerjaHarian/reportAll', [JadwalKerjaHarianController::class, 'reportAll']);
            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakAll', [JadwalKerjaHarianController::class, 'cetakAll']);

            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakPerKadar', [JadwalKerjaHarianController::class, 'cetakPerKadar']);
            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakPerKategori', [JadwalKerjaHarianController::class, 'cetakPerKategori']);
            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakPerSubKategori', [JadwalKerjaHarianController::class, 'cetakPerSubKategori']);
            Route::get('/Produksi/Informasi/JadwalKerjaHarian/cetakPerOperation', [JadwalKerjaHarianController::class, 'cetakPerOperation']);
        });

        //?  ------------------------   LeadTime   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,26,34,35,36,37,38,39,42']], function () {
            Route::get('/Produksi/Informasi/LeadTime', [LeadTimeController::class, 'index']);
            Route::post('/Produksi/Informasi/LeadTime/report1', [LeadTimeController::class, 'report1']);
            Route::post('/Produksi/Informasi/LeadTime/report2', [LeadTimeController::class, 'report2']);
            Route::post('/Produksi/Informasi/LeadTime/report3', [LeadTimeController::class, 'report3']);
            Route::post('/Produksi/Informasi/LeadTime/report4', [LeadTimeController::class, 'report4']);
            Route::post('/Produksi/Informasi/LeadTime/report5', [LeadTimeController::class, 'report5']);
            Route::post('/Produksi/Informasi/LeadTime/report6', [LeadTimeController::class, 'report6']);
            Route::post('/Produksi/Informasi/LeadTime/report7', [LeadTimeController::class, 'report7']);
            Route::post('/Produksi/Informasi/LeadTime/report8', [LeadTimeController::class, 'report8']);
            Route::post('/Produksi/Informasi/LeadTime/reportChart', [LeadTimeController::class, 'reportChart']);
            // Route::post('/Produksi/Informasi/LeadTime/reportAll', [LeadTimeController::class, 'reportAll']);
            // Route::get('/Produksi/Informasi/LeadTime/cetakAll', [LeadTimeController::class, 'cetakAll']);
        });

        //?  ------------------------   Routing Produksi   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,13,44']], function () {
            Route::get('/Produksi/Informasi/RoutingProduksi', [RoutingProduksiController::class, 'index']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getSPKProduksi', [RoutingProduksiController::class, 'getSPKProduksi']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getFilter', [RoutingProduksiController::class, 'getFilter']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getSPKRouting', [RoutingProduksiController::class, 'getSPKRouting']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getSPKO', [RoutingProduksiController::class, 'getSPKO']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getNTHKO', [RoutingProduksiController::class, 'getNTHKO']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getTRM', [RoutingProduksiController::class, 'getTRM']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getTFG', [RoutingProduksiController::class, 'getTFG']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getSPKOdetails', [RoutingProduksiController::class, 'getSPKOdetails']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getNTHKOdetails', [RoutingProduksiController::class, 'getNTHKOdetails']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getTRMdetails', [RoutingProduksiController::class, 'getTRMdetails']);
            Route::get('/Produksi/Informasi/RoutingProduksi/getTFGdetails', [RoutingProduksiController::class, 'getTFGdetails']);
        });
    });
    
    //todo ------------------------------ Lain-Lain -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   StatusInfo   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,26,34,35,36,37,38,39,53,54,55']], function () {
            Route::get('/Produksi/Lain-Lain/StatusInfo/cekSession', [StatusInfoController::class, 'cekSession']);
            Route::get('/Produksi/Lain-Lain/StatusInfo', [StatusInfoController::class, 'index']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/tmItem', [StatusInfoController::class, 'tmItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/spkoItem', [StatusInfoController::class, 'spkoItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/kodeItem', [StatusInfoController::class, 'kodeItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/tfStockItem', [StatusInfoController::class, 'tfStockItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/tfFGItem', [StatusInfoController::class, 'tfFGItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/tfFGPersiapanItem', [StatusInfoController::class, 'tfFGPersiapanItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/operationItem', [StatusInfoController::class, 'operationItem']);
            Route::get('/Produksi/Lain-Lain/StatusInfo/Show/{ID}/{Jenis}', [StatusInfoController::class, 'showInfo']);
        });
    });

    //todo ------------------------------ JadwalKerjaHarian -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   RPH Produksi   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,26,34,35,36,37,38,39,53,54,55,48,69,49,58']], function () {
            Route::get('/Produksi/JadwalKerjaHarian/RPHProduksi', [RPHProduksiController::class, 'index']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/daftarList', [RPHProduksiController::class, 'daftarList']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/lihat', [RPHProduksiController::class, 'lihat']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/simpan', [RPHProduksiController::class, 'simpan']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/update', [RPHProduksiController::class, 'update']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/update1', [RPHProduksiController::class, 'update1']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/posting', [RPHProduksiController::class, 'posting']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/cekWSComponent', [RPHProduksiController::class, 'cekWSComponent']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHProduksi/cekSPK', [RPHProduksiController::class, 'cekSPK']);
            Route::get('/Produksi/JadwalKerjaHarian/RPHProduksi/cetak', [RPHProduksiController::class, 'cetak']);
        });

        //?  ------------------------   RPH Lilin   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,26']], function () {
            Route::get('/Produksi/JadwalKerjaHarian/RPHLilin', [RPHLilinController::class, 'index']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/daftarList', [RPHLilinController::class, 'daftarList']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/lihat', [RPHLilinController::class, 'lihat']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/simpan', [RPHLilinController::class, 'simpan']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/update', [RPHLilinController::class, 'update']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/update1', [RPHLilinController::class, 'update1']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/posting', [RPHLilinController::class, 'posting']);
            Route::post('/Produksi/JadwalKerjaHarian/RPHLilin/cekSPK', [RPHLilinController::class, 'cekSPK']);
            Route::get('/Produksi/JadwalKerjaHarian/RPHLilin/cetak', [RPHLilinController::class, 'cetak']);
        });

        //?  ------------------------   Permintaan Komponen   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,36,39']], function () {
            Route::get('/Produksi/JadwalKerjaHarian/PermintaanKomponen', [PermintaanKomponenController::class, 'index']);
            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/lihat', [PermintaanKomponenController::class, 'lihat']);
            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/simpan', [PermintaanKomponenController::class, 'simpan']);
            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaSepuh', [PermintaanKomponenController::class, 'periksaSepuh']);
            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaSepuhPCB', [PermintaanKomponenController::class, 'periksaSepuhPCB']);
            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaKikir', [PermintaanKomponenController::class, 'periksaKikir']);
            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaKikirDC', [PermintaanKomponenController::class, 'periksaKikirDC']);
            Route::get('/Produksi/JadwalKerjaHarian/PermintaanKomponen/stokCB', [PermintaanKomponenController::class, 'stokCB']);
            Route::get('/Produksi/JadwalKerjaHarian/PermintaanKomponen/cetak', [PermintaanKomponenController::class, 'cetak']);

            Route::post('/Produksi/JadwalKerjaHarian/PermintaanKomponen/simpanTest', [PermintaanKomponenController::class, 'simpanTest']);
        });
    });

    //todo ------------------------------ PelaporanProduksi -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   NTHKO   ------------------------ !!
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/Produksi/PelaporanProduksi/NTHKO', [NTHKOController::class, 'index']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/lihat', [NTHKOController::class, 'lihat']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/lihatNext', [NTHKOController::class, 'lihatNext']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/baru', [NTHKOController::class, 'baru']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/simpan', [NTHKOController::class, 'simpan']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/ubah', [NTHKOController::class, 'ubah']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/update', [NTHKOController::class, 'update']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/posting', [NTHKOController::class, 'posting']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/barcodeUnit', [NTHKOController::class, 'barcodeUnit']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/cariSPK', [NTHKOController::class, 'cariSPK']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/cariProduct', [NTHKOController::class, 'cariProduct']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/cekProduct', [NTHKOController::class, 'cekProduct']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/detailSPK/{swspko}', [NTHKOController::class, 'detailSPK']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/detailSPKO/{swnthko}/{freqnthko?}', [NTHKOController::class, 'detailSPKO']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/detailNTHKO/{idnthko}', [NTHKOController::class, 'detailNTHKO']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/nthkoList', [NTHKOController::class, 'nthkoList']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/cetak', [NTHKOController::class, 'cetak']);
            Route::get('/Produksi/PelaporanProduksi/NTHKO/cetakBarcode', [NTHKOController::class, 'cetakBarcode']);
            Route::post('/Produksi/PelaporanProduksi/NTHKO/cetakBarcodeDirect', [NTHKOController::class, 'cetakBarcodeDirect']);

            Route::get('/Produksi/PelaporanProduksi/NTHKO/testKoneksi', [NTHKOController::class, 'testKoneksi']);
        });

        //?  ------------------------   SPKO   ------------------------ !!
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/Produksi/PelaporanProduksi/SPKO', [SPKOController::class, 'index']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/lihat', [SPKOController::class, 'lihat']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/baru', [SPKOController::class, 'baru']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/simpan', [SPKOController::class, 'simpan']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/ubah', [SPKOController::class, 'ubah']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/update', [SPKOController::class, 'update']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/posting', [SPKOController::class, 'posting']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/barcodeUnit', [SPKOController::class, 'barcodeUnit']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/barcodeAll', [SPKOController::class, 'barcodeAll']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cariKaryawan', [SPKOController::class, 'cariKaryawan']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cariWorkgroup', [SPKOController::class, 'cariWorkgroup']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cariSPK', [SPKOController::class, 'cariSPK']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cariProduct', [SPKOController::class, 'cariProduct']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cekSPK', [SPKOController::class, 'cekSPK']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/apiApp', [SPKOController::class, 'apiApp']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/insertWorkPercent', [SPKOController::class, 'insertWorkPercent']);
            Route::get('/Produksi/PelaporanProduksi/SPKO/lihatPersenKerja', [SPKOController::class, 'lihatPersenKerja']);
            Route::get('/Produksi/PelaporanProduksi/SPKO/cetak', [SPKOController::class, 'cetak']);
            Route::get('/Produksi/PelaporanProduksi/SPKO/cetakBarcode', [SPKOController::class, 'cetakBarcode']);
            Route::get('/Produksi/PelaporanProduksi/SPKO/cetakGambar', [SPKOController::class, 'cetakGambar']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cetakBarcodeDirect', [SPKOController::class, 'cetakBarcodeDirect']);

            Route::get('/Produksi/PelaporanProduksi/SPKO/apiAppTest/{idspko}/{username}', [SPKOController::class, 'apiAppTest']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/lihatPersenKerjaAjax', [SPKOController::class, 'lihatPersenKerja']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/simpanWorkPercent', [SPKOController::class, 'simpanWorkPercent']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/simpanTest', [SPKOController::class, 'simpanTest']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/updateTest', [SPKOController::class, 'updateTest']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/cekSPKTest', [SPKOController::class, 'cekSPKTest']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/test3', [SPKOController::class, 'test3']);
            Route::get('/Produksi/PelaporanProduksi/SPKO/cetak2', [SPKOController::class, 'cetak2']);
            Route::post('/Produksi/PelaporanProduksi/SPKO/testInsertWorkPercent', [SPKOController::class, 'testInsertWorkPercent']);
        });

        //?  ------------------------   SPKO Tambahan  ------------------------ !!
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/Produksi/PelaporanProduksi/SPKOTambahan', [SPKOTambahanController::class, 'index']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/lihat', [SPKOTambahanController::class, 'lihat']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/baru', [SPKOTambahanController::class, 'baru']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/simpan', [SPKOTambahanController::class, 'simpan']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/ubah', [SPKOTambahanController::class, 'ubah']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/update', [SPKOTambahanController::class, 'update']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/posting', [SPKOTambahanController::class, 'posting']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/barcodeUnit', [SPKOTambahanController::class, 'barcodeUnit']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/barcodeAll', [SPKOTambahanController::class, 'barcodeAll']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/cariKaryawan', [SPKOTambahanController::class, 'cariKaryawan']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/cariWorkgroup', [SPKOTambahanController::class, 'cariWorkgroup']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/cariSPK', [SPKOTambahanController::class, 'cariSPK']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/cariProduct', [SPKOTambahanController::class, 'cariProduct']);
            Route::get('/Produksi/PelaporanProduksi/SPKOTambahan/cetak', [SPKOTambahanController::class, 'cetak']);
            Route::get('/Produksi/PelaporanProduksi/SPKOTambahan/cetakBarcode', [SPKOTambahanController::class, 'cetakBarcode']);
            Route::get('/Produksi/PelaporanProduksi/SPKOTambahan/cetakGambar', [SPKOTambahanController::class, 'cetakGambar']);
            Route::post('/Produksi/PelaporanProduksi/SPKOTambahan/cetakBarcodeDirect', [SPKOTambahanController::class, 'cetakBarcodeDirect']);
        });

         //?  ------------------------   Transfer Material  ------------------------ !!
         Route::group(['middleware' => ['auth']], function () { //
            Route::get('/Produksi/PelaporanProduksi/TransferMaterial', [TMController::class, 'index']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/lihat', [TMController::class, 'lihat']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/baru', [TMController::class, 'baru']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/simpan', [TMController::class, 'simpan']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/ubah', [TMController::class, 'ubah']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/update', [TMController::class, 'update']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/posting', [TMController::class, 'posting']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/updateOperation', [TMController::class, 'updateOperation']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/barcodeUnit', [TMController::class, 'barcodeUnit']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/barcodeKomponen', [TMController::class, 'barcodeKomponen']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/cariKaryawan', [TMController::class, 'cariKaryawan']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/cekSPK', [TMController::class, 'cekSPK']);
            Route::get('/Produksi/PelaporanProduksi/TransferMaterial/cetak', [TMController::class, 'cetak']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/cariSPK', [TMController::class, 'cariSPK']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/cariProduct', [TMController::class, 'cariProduct']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/cariKadar', [TMController::class, 'cariKadar']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/cariSPKO', [TMController::class, 'cariSPKO']);
            
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/postingTest', [TMController::class, 'postingTest']);
            Route::post('/Produksi/PelaporanProduksi/TransferMaterial/simpanTest', [TMController::class, 'simpanTest']);
        });

        //?  ------------------------   Susutan  ------------------------ !!
        Route::group(['middleware' => ['auth']], function () { //
                Route::get('/Produksi/PelaporanProduksi/Susutan', [SusutanController::class, 'index']);
                Route::post('/Produksi/PelaporanProduksi/Susutan/lihat', [SusutanController::class, 'lihat']);
                Route::post('/Produksi/PelaporanProduksi/Susutan/baru', [SusutanController::class, 'baru']);
                Route::post('/Produksi/PelaporanProduksi/Susutan/simpan', [SusutanController::class, 'simpan']);
                Route::get('/Produksi/PelaporanProduksi/Susutan/cetak', [SusutanController::class, 'cetak']);  

                Route::post('/Produksi/PelaporanProduksi/Susutan/simpanTest', [SusutanController::class, 'simpanTest']);
        });

         //?  ------------------------   Order Komponen Produksi  ------------------------ !!
        Route::group(['middleware' => ['auth']], function () { 
            Route::get('/Produksi/PelaporanProduksi/OrderKomponen/', [ComponentOrderController::class, 'index']);
            Route::get('/Produksi/PelaporanProduksi/OrderKomponen/getFilter', [ComponentOrderController::class, 'getFilter']);
            Route::get('/Produksi/PelaporanProduksi/OrderKomponen/getKomponen', [ComponentOrderController::class, 'getKomponen']);
            Route::post('/Produksi/PelaporanProduksi/OrderKomponen/saveOrder', [ComponentOrderController::class, 'saveOrder']);
            Route::get('/Produksi/PelaporanProduksi/OrderKomponen/cetak', [ComponentOrderController::class, 'cetak']);
            Route::post('/Produksi/PelaporanProduksi/OrderKomponen/lihat', [ComponentOrderController::class, 'lihat']);

        });

        //!  ------------------------     Tukang Luar    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,38']], function () {
            Route::get('Produksi/TukangLuar',[ProduksiTukangLuarController::class, 'Index']);
            Route::get('Produksi/TukangLuar/search',[ProduksiTukangLuarController::class, 'GetTukangLuar']);
            Route::get('Produksi/TukangLuar/cetak',[ProduksiTukangLuarController::class, 'CetakTukangLuar']);
        });
    });

    //todo ------------------------------ Lilin -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {
        
         //? ------------------------------ SPK Inject Lilin -------------------------------- !!
        //  Route::middleware(['ceklevel:1,2,24,25,26,29,45'])->controller('Produksi\Lilin\SPKInjectLilin2Controller'::class)->prefix('/Produksi/Lilin/SPKInjectLilin2')->group(function () {
        //     Route::get('/', 'index');
        //     Route::get('/search/{ID}', 'search');
        //     Route::get('/form', 'form');
        //     Route::get('/Operator/{IdOperator}', 'inputoperator');
        //     Route::get('/Piring/{Labelpiring}', 'inputpiring');
        //     Route::get('/Pohon/{stickpohonval}', 'isipohon');
        //     Route::get('/TambahData/{items}/{kdr}/{rph}', 'tambahdata');
        //     Route::get('/TambahKaret/{items}/{kdr}/{rph}','tambahkaret');
        //     Route::get('/TambahKomponenDirect/{items}/{kdr}/{rph}','tambahkomponendirect');
        //     Route::get('/printcomponendirect/{items}/{kdr}/{rph}','printcomponendirect');
        //     Route::post('/save','save');
        //     Route::get('/show/{IDWaxInject}', 'show');
        //     Route::get('/edit/{IDWaxInject}', 'edit');
        //     Route::post('/jumlahinject','jumlahinject');
        //     Route::get('/edit2','edit2');
        //     Route::get('/klicklagi/{IDwaxinjectorder}', 'Klickdaftarproductlagi');
        //     Route::get('/ProdukList/{kdr}/{rph}', 'product');
        //     Route::get('/PrintBarkode/{IDWaxInject}/_blank', 'printbarkode');
        //     Route::get('/PrintBarkodetes/{IDWaxInject}/_blank', 'printbarkodetes');
        //     Route::get('/PrintSPK/{IDWaxInject}/_blank', 'printspk');
        //     Route::get('/PrintSPK2/{IDWaxInject}/_blank', 'printspk2');
        //     Route::post('/SPK3DP','simpanspk3dp');
        //     Route::get('/PrintSPK3Dp/{IDSPK3Dp}/_blank','printspk3dp');                
        //     Route::get('/TabelItem/{IDWaxInject}', 'tabelitem');
        //     Route::get('/TabelKaretDipilih/{IDWaxInject}', 'tabelkaretdipilih');
        //     Route::get('/TabelBatu/{IDWaxInject}', 'tabelbatu');
        //     Route::get('/TabelBatuLama/{IDWaxInject}', 'tabelbatulama');
        //     Route::get('/TabelTotalBatu/{IDWaxInject}', 'tabeltotalbatu');
        //     Route::get('/TabelKaretPilihan/{IDWaxInject}', 'tabelkaretpilihan');
        //     Route::post('/TambahItem', 'formdata');
        //     Route::post('/cariSWItemProduct','cariSWItemProduct');
        //     Route::get('/lihat/{idkaret}', 'lihat');
        // });

         //? ------------------------------ SPK Inject Lilin -------------------------------- !!
         Route::middleware(['ceklevel:1,2,24,25,26,29,45'])->controller('Produksi\Lilin\SPKInjectLilinController'::class)->prefix('/Produksi/Lilin/SPKInjectLilin')->group(function () {
            Route::get('/', 'index');
            Route::get('/Operator/{IdOperator}', 'inputoperator'); //get id OPerator
            Route::get('/Piring/{Labelpiring}', 'inputpiring'); //get id label karet Active
            Route::get('/Pohon/{stickpohonval}', 'isipohon');  // get id stick pohon 
            Route::get('/TambahData/{SPKPPICs}/{kdr}/{rph}', 'tambahdata'); //generate item setelah memilih spk PPIC
            Route::get('/TambahDataKaret/{items}','tambahdatakaret'); //generate karet setelah memilih spk PPIC
            Route::get('/TambahKaret/{items}/{kdr}/{rph}','tambahkaret');
            Route::get('/TambahKomponenDirect/{items}/{kdr}/{rph}','tambahkomponendirect');//
            Route::get('/printcomponendirect/{items}/{kdr}/{rph}','printcomponendirect');//
            Route::post('/save','save'); //
            Route::get('/show/{IDWaxInject}', 'show');//
            Route::Put('/Update','Update');//
            Route::get('/ProdukList/{kdr}/{rph}', 'product');
            Route::get('/PrintBarkode/{IDWaxInject}/_blank', 'printbarkode');
            Route::get('/PrintBarkodetes/{IDWaxInject}/_blank', 'printbarkodetes');
            Route::get('/PrintSPK/{IDWaxInject}/_blank', 'printspk');
            Route::get('/PrintSPK2/{IDWaxInject}/_blank', 'printspk2');
            Route::post('/SPK3DP','simpanspk3dp');
            Route::get('/PrintSPK3Dp/{IDSPK3Dp}/_blank','printspk3dp');    
            Route::post('/TambahItem', 'formdata');
            Route::post('/cariSWItemProduct','cariSWItemProduct');
            Route::get('/lihat/{idkaret}', 'lihat');
        });

        //? ------------------------------ SPK Pohonan ----------------------------------------------------------------------------------------------------------------------------------------- !!
        Route::middleware(['ceklevel:1,2,24,26'])->controller('Produksi\Lilin\SPKPohonanController'::class)->prefix('/Produksi/Lilin/SPKPohonan')->group(function () {
            Route::get('/','index');
            Route::get('/form', 'form');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::get('/Piring/{Labelpiring}', 'inputpiring');
            Route::get('/cetak/{idspkpohon?}/{carat?}/{idtm?}', 'cetak');
            Route::get('/printplate/{idspkpohon?}/_blank','printplate');
            Route::get('/add/{carat?}/{idtm?}','ListTabel');
            Route::POST('/savespkpohonan','savespkpohonan');
            Route::get('/cari/{IDcari}','search');
            Route::get('/show/{carat?}/{idtm?}','tes');
        });

         //? ------------------------------ SPK PohonanTestFULLDC ----------------------------------------------------------------------------------------------------------------------------------------- !!
        Route::middleware(['ceklevel:1,2,24,26'])->controller('Produksi\Lilin\SPKPohonanTestFULLDCController'::class)->prefix('/Produksi/Lilin/SPKPohonanTestFULLDC')->group(function () {
            Route::get('/','index');
            Route::get('/form', 'form');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::get('/Piring/{Labelpiring}', 'inputpiring');
            Route::get('/cetak/{idspkpohon?}/{carat?}/{idtm?}', 'cetak');
            Route::get('/printplate/{idspkpohon?}/_blank','printplate');
            Route::get('/add/{carat?}/{idtm?}','ListTabel');
            Route::post('/Save','Save');
            Route::get('/cari/{IDcari}','search');
            Route::get('/show/{carat?}/{idtm?}','tes');

            Route::get('/ProdukList/{kdr}/{rph}', 'produklist');
            Route::get('/itemproductDC/{items}/{kdr}/{rph}', 'itemproductDC');
            Route::get('/dapattm3dp/{IdTmResinSudahDiposting}','dapattm3dp');
            Route::post('/Requesti','Requesti');
            Route::get('/PrintSPK3Dp/{IDSPK3Dp}/_blank','printspk3dp');                
            Route::get('/tambahdataSPK/{workorder}/{kdrspk}/{rphspk}','tambahdataSPK');
        });

         //?  ------------------------   Order Tambahan  ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,26'])->controller('Produksi\Lilin\OrderTambahanLilinController'::class)->prefix('/Produksi/Lilin/OrderTambahanLilin')->group(function () {
            // -------------------------------- Order + SPKO Inject -----------------------------------!!
            Route::get('/', 'index');
            Route::get('/formSPKPohonan','formSPKPohonan');
            Route::get('/formSPKO','formSPKO');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::get('/Piring/{Labelpiring}', 'inputpiring');
            Route::get('/ProdukList/{SWWorkOrder}', 'ProdukList');
            Route::get('/ItemSPKO/{XIOrdinal}/{SWWorkOrder}', 'ItemSPKO');
            Route::get('/TambahKomponenDirect/{items}/{kdr}/{rph}','TambahKomponenDirect');
        
            Route::get('/dapattm3dp/{IdTmResinSudahDiposting}','dapattm3dp');
            Route::get('/tambahdataSPKPohonan/{workorder}/{kdrspk}/{rphspk}','tambahdataSPKPohonan');
            Route::post('/SPK3DP','simpanspk3dp');
            Route::post('/save','save');
            Route::get('/show/{IDWaxInject}', 'show');
            Route::get('/PrintBarkode/{IDWaxInject}/_blank', 'printbarkode');
            Route::get('/PrintSPKO/{IDWaxInject}/_blank', 'printspk');
            Route::get('/PrintBarkode/{IDWaxInject}/_blank', 'printbarkode');
            // ------------------------------- Order + SPK Pohonan -----------------------------------!!
            Route::get('/ProdukListPohonan/{SWWorkOrderDC}','produklistpohon');
            Route::get('/ItemSPKPohonan/{XIOrdinal}/{SWWorkOrder}','ItemSPKPohonan');
            Route::put('/update/{id?}', 'update');
        });

        //? ------------------------------ Informasi Lemari karet -------------------------------- !!
        Route::middleware(['ceklevel:1,2,26,29,96'])->controller('Produksi\Lilin\InfoLemariKaretController'::class)->prefix('/Produksi/Lilin/InfoLemariKaret')->group(function () {
            Route::get('/','index');
            Route::get('/Lemari/{lemari}/{laci}','lemari');
            Route::get('/Laci/{id?}','laci');
            Route::get('/Print/{lemari}/_blank','Print');
        });

        //?  ------------------------   posting 3DP DirectCasting  ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26'])->controller('Produksi\Lilin\Posting3DPDirectCastingController'::class)->prefix('/Produksi/Lilin/Posting3DPDirectCasting')->group(function () {
            Route::get('/', 'index');
            Route::put('/update/{id?}', 'update');
        });

         //!  ------------------------     NTHKO Inject Lilin   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26,29,45,96'])->controller('Produksi\Lilin\NTHKOInjectLilinController'::class)->prefix('/Produksi/Lilin/NTHKOInjectLilin')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::put('/Update','UpdateNTHKO');
            Route::get('/Piring','GetPiring');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/cetak','PrintNTHKOInject');
            Route::get('/Search', 'Search');
            Route::post('/cariSWItemProduct','cariSWItemProduct');
        });

        //!  ------------------------    Lilin Cetak   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26,45,29,96'])->controller('Produksi\Lilin\LilinCetakController'::class)->prefix('/Produksi/Lilin/LilinCetak')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::put('/Update','Update');
            Route::get('/Pohon','GetPohon');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/Cetak','Print');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
        });

        //!  ------------------------    Lilin Bersih   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26,96'])->controller('Produksi\Lilin\LilinBersihController'::class)->prefix('/Produksi/Lilin/LilinBersih')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::put('/Update','Update');
            Route::get('/Pohon','GetPohon');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/Cetak','Print');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
        });

         //!  ------------------------    Lilin Stone Cast   ------------------------ !!
         Route::middleware(['ceklevel:1,2,24,25,26,96'])->controller('Produksi\Lilin\LilinStoneCastController'::class)->prefix('/Produksi/Lilin/LilinStoneCast')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::put('/Update','Update');
            Route::get('/Pohon','GetPohon');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/Cetak','Print');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
        });


        //!  ------------------------    Kebutuhan Batu Lilin   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26'])->controller('Produksi\Lilin\KebutuhanBatuLilinController'::class)->prefix('/Produksi/Lilin/KebutuhanBatuLilin')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::put('/Update','Update');
            Route::get('/Piring','GetPiring');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/cetak','PrintNTHKOInject');
            Route::get('/Search', 'Search');
            Route::post('/cariSWItemProduct','cariSWItemProduct');
        });

        //!  ------------------------     NTHKO Pohonan    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,24,25,26,96']], function () {
            Route::get('/Produksi/Lilin/NTHKOPohonDirectCasting', [NTHKOPohonanController::class, 'Index']);
            Route::post('/Produksi/Lilin/NTHKOPohonDirectCasting', [NTHKOPohonanController::class, 'SimpanNTHKOPohonan']);
            Route::put('/Produksi/Lilin/NTHKOPohonDirectCasting', [NTHKOPohonanController::class, 'UpdateNTHKO']);
            Route::get('/Produksi/Lilin/NTHKOPohonDirectCasting/Pohon', [NTHKOPohonanController::class, 'GetPohon']);
            Route::get('/Produksi/Lilin/NTHKOPohonDirectCasting/Items/{IDWaxInjectOrder}', [NTHKOPohonanController::class, 'getWaxInjectOrder']);
            Route::get('/Produksi/Lilin/NTHKOPohonDirectCasting/cetak', [NTHKOPohonanController::class, 'CetakNTHKOPohonan']);
            Route::get('/Produksi/Lilin/NTHKOPohonDirectCasting/Search', [NTHKOPohonanController::class, 'SearchNTHKOPohonan']);
        });

        //!  ------------------------     Posting Karet PCB    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,29']], function () {
            Route::get('/Produksi/Lilin/PostingKaretdariPCB', [PostingKaretPcbController::class, 'Index']);
            Route::get('/Produksi/Lilin/PostingKaretdariPCB/search', [PostingKaretPcbController::class, 'SearchTMKaretLilin']);
            Route::post('/Produksi/Lilin/PostingKaretdariPCB/posting', [PostingKaretPcbController::class, 'PostingTMKaretLilin']);
        });

         //!  ------------------------     Lilin Penggunaan Batu   ------------------------ !!
         Route::middleware(['ceklevel:1,2,24,25,26,29,45'])->controller('Produksi\Lilin\PenggunaanBatuController'::class)->prefix('/Produksi/Lilin/PenggunaanBatu')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::put('/Update','Update');
            Route::get('/Operator/{IdOperator}', 'inputoperator');
            Route::get('/Pohon','GetPohon');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/cetak','PrintSPKOBatu');
            Route::get('/Search', 'Search');
            Route::post('/posting','tesposting');
            Route::POST('/cariSW','SWBatu');
            Route::get('/jumlahberatbatutabel','BeratBatu');
            Route::post('/cariWorkorder','cariWorkOrder');
            Route::get('/cariStone/{idston}','cariBatu');
        });

        //!  ------------------------     Lilin Penggunaan Karet   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26,45,29,96'])->controller('Produksi\Lilin\PenggunaanKaretController'::class)->prefix('/Produksi/Lilin/PenggunaanKaret')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::put('/Update','Update');
            Route::get('/Pohon','GetPohon');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/Cetak','Print');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
        });

          //!  ------------------------     Lilin Pengembalian Karet   ------------------------ !!
          Route::middleware(['ceklevel:1,2,24,25,26,45,29,96'])->controller('Produksi\Lilin\PengembaliankaretController'::class)->prefix('/Produksi/Lilin/PengembalianKaret')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::put('/Update','Update');
            // Route::get('/Cetak', 'Cetak');
            Route::get('/Cetak/_blank', 'Cetak');
            Route::get('/Items/{IDKarettrigger}','gettriggrttabel');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
        });

 //!  ------------------------     Lilin Registrasi Karet   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26,45,29,96'])->controller('Produksi\Lilin\RegistrasiKaretController'::class)->prefix('/Produksi/Lilin/RegistrasiKaret')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::get('/pilihlokasi/{lokasi}','pilihlokasi');
            Route::get('/Cetak','Print');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
            Route::get('/Lemari/{lemari}/{laci}','lemari');
        });

        //!  ------------------------     Lilin Unregistrasi Karet   ------------------------ !!
        Route::middleware(['ceklevel:1,2,24,25,26,45,29,96'])->controller('Produksi\Lilin\UnRegistrasiKaretController'::class)->prefix('/Produksi/Lilin/UnRegistrasiKaret')->group(function () {
            Route::get('/', 'index');
            Route::post('/Simpan', 'Simpan');
            Route::put('/Update','Update');
            Route::get('/Pohon','GetPohon');
            Route::get('/Items/{IDWaxInjectOrder}','getWaxInjectOrder');
            Route::get('/Cetak','Print');
            Route::get('/cariID','IDKaret');
            Route::get('/Search', 'Search');
        });

        //!  ------------------------     Lilin Transfer Pohon   ------------------------ !!
        Route::middleware(['ceklevel:1,2,69,25'])->controller('Produksi\Lilin\TransferPohonLilinController'::class)->prefix('/Produksi/Lilin/TransferPohonLilin')->group(function () {
            Route::get('/', 'index');
            Route::get('/DafatarPohon','DafatarPohon');
            Route::post('/Simpan', 'Simpan');
            Route::post('/PilihPohon/{Pilihpohon?}/{WorkOrderOO}', 'PilihPohon');
            Route::put('/Update','Update');
            Route::get('/Search', 'Search');
            Route::post('/posting','posting'); 
            Route::get('/cetak/{IDTM}/_blank', 'cetak');
        });
    });
    
    //todo ------------------------------ Gips Lebur Cor -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {
    
    //!  ------------------------   SPK Gips   ------------------------ !!
        Route::middleware(['ceklevel:1,2,69,25'])->controller('Produksi\GipsLeburCor\SPKOGipsController'::class)->prefix('/Produksi/GipsLeburCor/SPKOGips')->group(function () {
            Route::get('/', 'index');
            Route::get('/DafatarPohon/{WorkOrderOO}','DafatarPohon');
            Route::get('/DafatarPohonPerak/{WorkOrderOO}','DafatarPohonPerak');
            Route::post('/Simpan', 'Simpan');
            Route::post('/PilihPohon/{Pilihpohon?}/{WorkOrderOO}', 'PilihPohon');
            Route::put('/Update','Update');
            Route::get('/Search', 'Search');
            Route::post('/posting','posting'); 
            Route::get('/cetak/{IDSPKOGips}/_blank', 'cetak');
            Route::get('/cetakrekap/{IDSPKOGips}/_blank', 'cetakrekap');
        });
        
    });

      //!  ------------------------   SPKO Plate Cor   ------------------------ !!
      Route::middleware(['ceklevel:1,2,69,25,85'])->controller('Produksi\GipsLeburCor\SPKOPlateCorController'::class)->prefix('/Produksi/GipsLeburCor/SPKOPlateCor')->group(function () {
        Route::get('/', 'index');
        Route::get('/ListPosted','ListPosted');
        Route::get('/GetIDTMPohon','GetIDTMPohon');
        Route::get('/getNTHKO','getNTHKO');
        Route::post('/Simpan', 'Simpan');
        Route::get('/isiSPKOplatecor', 'isiSPKOplatecor');
        
        Route::put('/Update','Update');
        Route::get('/Search', 'Search');
        Route::post('/posting','posting'); 
        Route::get('/cetak/{IDSPKOPlateCor}/_blank', 'cetak');
        Route::get('/cetakrekap/{IDSPKOPlateCor}/_blank', 'cetakrekap');
    });

    //todo  ------------------------     Laboratorium     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {
        //?  ------------------------     Laboratorium Xray    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,14']], function () {
            Route::get('Produksi/Laboratorium/LaboratoriumXray', [LaboratoriumXrayController::class, 'Index']);
            Route::post('Produksi/Laboratorium/LaboratoriumXray', [LaboratoriumXrayController::class, 'SaveLabTest']);
            Route::put('Produksi/Laboratorium/LaboratoriumXray',[LaboratoriumXrayController::class, 'UpdateLabTest']);
            Route::get('Produksi/Laboratorium/LaboratoriumXray/cari/{keyword}', [LaboratoriumXrayController::class, 'SearchLabTest']);
            Route::get('Produksi/Laboratorium/LaboratoriumXray/cetak/{keyword}', [LaboratoriumXrayController::class, 'CetakLabTest']);
            Route::post('Produksi/Laboratorium/LaboratoriumXray/check', [LaboratoriumXrayController::class, 'CheckLabTest']);
        });
        
        //!  ------------------------     Laboratorium Turun Kadar    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,14']], function () {
            Route::get('Produksi/Laboratorium/LabTurunKadar',[LabTurunKadarController::class, 'Index']);
            Route::put('Produksi/Laboratorium/LabTurunKadar',[LabTurunKadarController::class, 'UpdateCjepsi']);
            Route::get('Produksi/Laboratorium/LabTurunKadar/search',[LabTurunKadarController::class, 'SearchNotaTukangLuar']);
            Route::get('Produksi/Laboratorium/LabTurunKadar/cetak',[LabTurunKadarController::class, 'CetakLabTurunKadar']);
        });
    });

    
});

//!  ------------------------     Inventori     ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //todo  ------------------------     Material Request     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   Bahan Pembantu   ------------------------ !!
        Route::controller('Inventori\MaterialRequest\MRBahanPembantuController'::class)->prefix('/Inventori/MaterialRequest/BahanPembantu')->group(function () {
            Route::get('/', 'Index');
            Route::post('/', 'saveMRBahanPembantu');
            Route::post('/update', 'update');
            Route::get('/generateRow', 'generateNewRow');
            Route::get('/LockGudang', 'LockGudang');
            Route::get('/getBarang', 'getBarang');
            Route::get('/cari', 'cari');
            Route::get('/cetak', 'cetak');
        });

        //?  ------------------------   Bahan Pembantu Stock  ------------------------ !!
        Route::middleware(['ceklevel:1,2,22,69'])->controller('Inventori\MaterialRequest\StockController'::class)->prefix('/Inventori/MaterialRequest/Stock')->group(function () {
            Route::get('/', 'Index');
            Route::get('/show', 'show');
            Route::post('/', 'store');
            Route::put('/', 'update');
        });

        //?  ------------------------   Koreksi MR  ------------------------ !!
        Route::middleware(['ceklevel:1,2,22,69'])->controller('Inventori\MaterialRequest\KoreksiMRController'::class)->prefix('/Inventori/MaterialRequest/KoreksiMR')->group(function () {
            Route::get('/', 'Index');
            Route::get('/show', 'show');
            Route::post('/', 'store');
            Route::put('/', 'update');
        });
    });

    //todo  ------------------------     Transfer     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   Bahan Pembantu   ------------------------ !!
        Route::controller('Inventori\Transfer\BahanPembantuController'::class)->prefix('/Inventori/Transfer/BahanPembantu')->group(function () {
            Route::get('/', 'Index');
            Route::get('/show', 'show');
            Route::post('/', 'store');
            Route::put('/', 'update');
            Route::post('/Posting', 'Posting');
            Route::get('/cetak', 'cetak');
        });
    });

    //todo  ------------------------     Informasi     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   Informasi Material Request   ------------------------ !!
        Route::controller('Inventori\Informasi\MaterialRequestController'::class)->prefix('/Inventori/Informasi/MaterialRequest')->group(function () {
            Route::get('/', 'Index');
            Route::get('/show', 'show');
        });

        //?  ------------------------   Informasi Bahan Pembantu   ------------------------ !!
        Route::middleware(['ceklevel:1,2,22'])->controller('Inventori\Informasi\InformasiBahanPembanuController'::class)->prefix('/Inventori/Informasi/InformasiBahanPembanu')->group(function () {
            Route::get('/', 'Index');
            Route::get('/show', 'show');
        });
    });
});

//!  ------------------------     Keuangan     ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
});

//!  ------------------------     Akunting     ------------------------ !!
Route::group(['middleware' => ['auth']], function () {
    //todo ------------------------------ Informasi -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {
        //?  ------------------------   StatusInfo   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,13']], function () {
            Route::get('/Akunting/Informasi/StokAkhirBulan', [StokAkhirBulanController::class, 'index']);
            Route::get('/Akunting/Informasi/StokAkhirBulan/gettingStokAkhirBulan', [StokAkhirBulanController::class, 'gettingStokAkhirBulan']);
            Route::get('/Akunting/Informasi/StokAkhirBulan/gettingStokAkhirBulanOpname', [StokAkhirBulanController::class, 'gettingStokAkhirBulanOpname']);
            Route::get('/Akunting/Informasi/StokAkhirBulan/setYear', [StokAkhirBulanController::class, 'setYear']);
            Route::get('/Akunting/Informasi/StokAkhirBulan/formOpname', [StokAkhirBulanController::class, 'formOpname']);
            Route::get('/Akunting/Informasi/StokAkhirBulan/formDaily', [StokAkhirBulanController::class, 'dailystock']);
            // Route::get('/Akunting/Informasi/StokAkhirBulan/formDaily', [SettingController::class, 'dailystock']);
        });
    });
});

//!  ------------------------     Absensi      ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //todo  ------------------------     Absensi     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {
        //?  ------------------------   CheckClock Manual   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/CheckClockManual', [CheckClockManualController::class, 'index']);
            Route::post('/Absensi/Absensi/CheckClockManual', [CheckClockManualController::class, 'SaveCheckClockManual']);
            Route::put('/Absensi/Absensi/CheckClockManual', [CheckClockManualController::class, 'UpdateCheckClockManual']);
            Route::get('/Absensi/Absensi/CheckClockManual/employee', [CheckClockManualController::class, 'SearchEmployee']);
            Route::get('/Absensi/Absensi/CheckClockManual/search', [CheckClockManualController::class, 'SearchCheckClockManual']);
        });

        //?  ------------------------   Lembur Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/LemburKerja', [AbsensiLemburKerjaController::class, 'Index']);
            Route::post('/Absensi/Absensi/LemburKerja', [AbsensiLemburKerjaController::class, 'SimpanLemburKerja']);
            Route::put('/Absensi/Absensi/LemburKerja', [AbsensiLemburKerjaController::class, 'UpdateLemburKerja']);
            Route::get('/Absensi/Absensi/LemburKerja/CheckDate', [AbsensiLemburKerjaController::class, 'CheckDate']);
            Route::get('/Absensi/Absensi/LemburKerja/employee', [AbsensiLemburKerjaController::class, 'SearchEmployee']);
            Route::get('/Absensi/Absensi/LemburKerja/search', [AbsensiLemburKerjaController::class, 'SearchLemburKerja']);
        });

        //?  ------------------------   Tambahan Uang Makan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/TambahanUangMakan', [AbsensiTambahanUangMakanController::class, 'Index']);
            Route::post('/Absensi/Absensi/TambahanUangMakan', [AbsensiTambahanUangMakanController::class, 'SimpanTambahanUangMakan']);
            Route::put('/Absensi/Absensi/TambahanUangMakan', [AbsensiTambahanUangMakanController::class, 'UpdateTambahanUangMakan']);
            Route::get('/Absensi/Absensi/TambahanUangMakan/daftarkaryawan', [AbsensiTambahanUangMakanController::class, 'GetDaftarKaryawan']);
            Route::get('/Absensi/Absensi/TambahanUangMakan/search', [AbsensiTambahanUangMakanController::class, 'SearchTambahanUangMakan']);
            Route::get('/Absensi/Absensi/TambahanUangMakan/posting', [AbsensiTambahanUangMakanController::class, 'PostingTambahanUangMakan']);
        });

        //?  ------------------------   Dispensasi Keterlambatan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/DispensasiKeterlambatan', [DispensasiKeterlambatanController::class, 'Index']);
            Route::post('/Absensi/Absensi/DispensasiKeterlambatan', [DispensasiKeterlambatanController::class, 'SaveDispensasiKeterlambatan']);
            Route::put('/Absensi/Absensi/DispensasiKeterlambatan', [DispensasiKeterlambatanController::class, 'UpdateDispensasiKeterlambatan']);
            Route::get('/Absensi/Absensi/DispensasiKeterlambatan/daftarkaryawan', [DispensasiKeterlambatanController::class, 'GetDaftarKaryawan']);
            Route::get('/Absensi/Absensi/DispensasiKeterlambatan/Search', [DispensasiKeterlambatanController::class, 'SearchDispensasiKeterlambatan']);
            Route::get('/Absensi/Absensi/DispensasiKeterlambatan/Posting', [DispensasiKeterlambatanController::class, 'PostingDispensasiKeterlambatan']);
        });

        //?  ------------------------   Pilihan Lembur Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/PilihanLemburKerja', [PilihanLemburKerjaController::class, 'Index']);
            Route::get('/Absensi/Absensi/PilihanLemburKerja/Cari', [PilihanLemburKerjaController::class, 'GetPilihanLemburKerja']);
        });

        //?  ------------------------   Pilihan Manual Checkclock   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/PilihanManualCheckclock', [PilihanManualCheckclockController::class, 'Index']);
            Route::get('/Absensi/Absensi/PilihanManualCheckclock/Cari', [PilihanManualCheckclockController::class, 'GetPilihanManualCheckclock']);
            Route::get('/Absensi/Informasi/Checkclock/Detail', [CheckClockController::class, 'DetailTidakFaceAbsen']);
        });

        //?  ------------------------   Ijin Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/IjinKerja', [AbsensiIjinKerjaController::class, 'Index']);
            Route::post('/Absensi/Absensi/IjinKerja', [AbsensiIjinKerjaController::class, 'SaveIjinKerja']);
            Route::put('/Absensi/Absensi/IjinKerja', [AbsensiIjinKerjaController::class, 'UpdateIjinKerja']);
            Route::get('/Absensi/Absensi/IjinKerja/Posting', [AbsensiIjinKerjaController::class, 'PostingIjinKerja']);
            Route::get('/Absensi/Absensi/IjinKerja/Search', [AbsensiIjinKerjaController::class, 'SearchIjinKerja']);
            Route::get('/Absensi/Absensi/IjinKerja/employee', [AbsensiIjinKerjaController::class, 'SearchEmployee']);
            Route::get('/Absensi/Absensi/IjinKerja/gettanggal', [AbsensiIjinKerjaController::class, 'GetTanggal']);
            Route::get('/Absensi/Absensi/IjinKerja/getkurang', [AbsensiIjinKerjaController::class, 'GetKurang']);
        });

        //?  ------------------------   Berita Acara   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/BeritaAcara', [AbsensiBeritaAcaraController::class, 'Index']);
            Route::post('/Absensi/Absensi/BeritaAcara', [AbsensiBeritaAcaraController::class, 'SaveBeritaAcara']);
            Route::put('/Absensi/Absensi/BeritaAcara', [AbsensiBeritaAcaraController::class, 'UpdateBeritaAcara']);
            Route::get('/Absensi/Absensi/BeritaAcara/employee', [AbsensiBeritaAcaraController::class, 'SearchEmployee']);
            Route::get('/Absensi/Absensi/BeritaAcara/search', [AbsensiBeritaAcaraController::class, 'SearchBeritaAcara']);
        });

        //?  ------------------------   Download Face Absen   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/DownloadFaceAbsen', [DownloadFaceAbsenController::class, 'Index']);
            Route::post('/Absensi/Absensi/DownloadFaceAbsen', [DownloadFaceAbsenController::class, 'SaveFaceAbsentTransaction']);
            Route::post('/Absensi/Absensi/DownloadFaceAbsen/check', [DownloadFaceAbsenController::class, 'GetAbsentTransaction']);
            Route::post('/Absensi/Absensi/DownloadFaceAbsen/posting', [DownloadFaceAbsenController::class, 'PostingFaceAbsent']);
        });

        //?  ------------------------   Download Finger Absen   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Absensi/DownloadFingerAbsen', [DownloadFingerAbsenController::class, 'Index']);
            Route::post('/Absensi/Absensi/DownloadFingerAbsen', [DownloadFingerAbsenController::class, 'SaveAbsent']);
            Route::post('/Absensi/Absensi/DownloadFingerAbsen/check', [DownloadFingerAbsenController::class, 'KlikCheck']);
            Route::post('/Absensi/Absensi/DownloadFingerAbsen/posting', [DownloadFingerAbsenController::class, 'PostingFingerAbsent']);
        });

    });

    //todo  ------------------------   Penilaian Absensi   ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
        Route::get('/Absensi/PenilaianAbsensi', [PenilaianAbsensiController::class, 'Index']);
        Route::get('/Absensi/PenilaianAbsensi/search', [PenilaianAbsensiController::class, 'SearchPenilaianAbsensi']);
    });

    //todo ------------------------------ Gaji -------------------------------- !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   Gaji Magang   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Gaji/Magang', [GajiMagangController::class, 'index']);
            Route::post('/Absensi/Gaji/Magang/getemployeeandsallary', [GajiMagangController::class, 'GetEmployeeAndSallary']);
            Route::get('/Absensi/Gaji/Magang/getsallary', [GajiMagangController::class, 'GetSallary']);
            Route::post('/Absensi/Gaji/Magang/savepayroll', [GajiMagangController::class, 'SavePayroll']);
            Route::get('/Absensi/Gaji/Magang/searchpayroll', [GajiMagangController::class, 'SearchPayroll']);
            Route::get('/Absensi/Gaji/Magang/cetak', [GajiMagangController::class, 'CetakPayroll']);
            Route::put('/Absensi/Gaji/Magang/ubahpayroll', [GajiMagangController::class, 'UbahPayroll']);
        });
    });

    //todo  ------------------------   Jaminan Karyawan   ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
        Route::get('/Absensi/JaminanKaryawan', [JaminanKaryawanController::class, 'Index']);
        Route::post('/Absensi/JaminanKaryawan', [JaminanKaryawanController::class, 'SimpanJaminanKaryawan']);
        Route::put('/Absensi/JaminanKaryawan', [JaminanKaryawanController::class, 'UbahJaminanKaryawan']);
        Route::get('/Absensi/JaminanKaryawan/search', [JaminanKaryawanController::class, 'SearchJaminanKaryawan']);
        Route::get('/Absensi/JaminanKaryawan/employee', [JaminanKaryawanController::class, 'SearchEmployee']);
        Route::get('/Absensi/JaminanKaryawan/cetak/{id}', [JaminanKaryawanController::class, 'CetakJaminanKaryawan']);
    });

    //!  ------------------------     Informasi     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {
        //?  ------------------------   Jam Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15,66,11,26,27,30,34,35,36,37,38,39,44,51,53,54,55,58,50']], function () {
            Route::get('/Absensi/Informasi/JamKerja', [JamKerjaController::class, 'Index']);
            Route::get('/Absensi/Informasi/JamKerja/Cari', [JamKerjaController::class, 'GetJamKerja']);
        });
        //?  ------------------------   Penilaian Kehadiran   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/PenilaianKehadiran', [PenilaianKehadiranController::class, 'Index']);
            Route::get('/Absensi/Informasi/PenilaianKehadiran/Cari', [PenilaianKehadiranController::class, 'GetPenilaian']);
        });
        //?  ------------------------   Berita Acara   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/BeritaAcara', [BeritaAcaraController::class, 'Index']);
            Route::get('/Absensi/Informasi/BeritaAcara/Cari', [BeritaAcaraController::class, 'GetBeritaAcara']);
        });
        //?  ------------------------   Check Clock   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/Checkclock', [CheckClockController::class, 'Index']);
            Route::get('/Absensi/Informasi/Checkclock/Cari', [CheckClockController::class, 'GetCheclClock']);
        });
        //?  ------------------------   Ijin Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/IjinKerja', [IjinKerjaController::class, 'Index']);
            Route::get('/Absensi/Informasi/IjinKerja/Cari', [IjinKerjaController::class, 'GetIjinKerja']);
        });
        //?  ------------------------   Lembur Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/LemburKerja', [LemburKerjaController::class, 'Index']);
            Route::get('/Absensi/Informasi/LemburKerja/Cari', [LemburKerjaController::class, 'GetLemburKerja']);
        });
        //?  ------------------------   Lembur Kerja   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/AbsensiTidakLengkap', [AbsensiTidakLengkapController::class, 'Index']);
            Route::get('/Absensi/Informasi/AbsensiTidakLengkap/Cari', [AbsensiTidakLengkapController::class, 'GetAbsensiTidakLengkap']);
        });
        //?  ------------------------   Absensi Bulanan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/AbsensiBulanan', [AbsensiBulananController::class, 'Index']);
            Route::get('/Absensi/Informasi/AbsensiBulanan/Cari', [AbsensiBulananController::class, 'GetAbsensiBulanan']);
        });
        //?  ------------------------   Koreksi Absensi   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/KoreksiAbsensi', [KoreksiAbsensiController::class, 'Index']);
            Route::get('/Absensi/Informasi/KoreksiAbsensi/Cari', [KoreksiAbsensiController::class, 'GetKoreksiAbsensi']);
        });
        //?  ------------------------   UpahPSB   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/UpahPSB', [UpahPSBController::class, 'Index']);
            Route::get('/Absensi/Informasi/UpahPSB/Cari', [UpahPSBController::class, 'GetUpahPSB']);
        });
        //?  ------------------------   Tambahan Uang Makan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/TambahanUangMakan', [TambahanUangMakanController::class, 'Index']);
            Route::get('/Absensi/Informasi/TambahanUangMakan/Cari', [TambahanUangMakanController::class, 'GetTambahanUangMakan']);
        });
        //?  ------------------------   Jaminan Karyawan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/JaminanKaryawan', [InformasiJaminanKaryawanController::class, 'Index']);
            Route::get('/Absensi/Informasi/JaminanKaryawan/Proses/{ID}', [InformasiJaminanKaryawanController::class, 'ProcessEmployeeGuarantee']);
        });
        //?  ------------------------   Informasi Face Absent   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,10,15']], function () {
            Route::get('/Absensi/Informasi/InformasiFaceAbsent', [InformasiFaceAbsentController::class, 'Index']);
        });
    });
});

//!  ------------------------       RnD        ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //todo  ------------------------      3D Printing Direct Casting      ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {      

         //?  ------------------------   SPKO 3DP Direct Casting   ------------------------ !!
        Route::middleware(['ceklevel:1,2,23'])->controller('RnD\TigaDPrintingDirectCasting\SPKO3DPDirectCastingController'::class)->prefix('/R&D/3DPrintingDirectCasting/SPKO3DPDirectCasting')->group(function () {
            Route::get('/', 'index');
            Route::get('/show/{no?}/{id?}', 'getDirectCastingRequest');
            Route::get('/see/{id?}', 'see');
            Route::post('/saveAllocation', 'saveAllocation');
            Route::get('/cetak', 'cetak');
            Route::get('/download{file}', 'download');
        });

        //?  ------------------------   NTHKO 3DP Direct Casting   ------------------------ !!
        Route::middleware(['ceklevel:1,2,23'])->controller('RnD\TigaDPrintingDirectCasting\NTHKO3DPDirectCastingController'::class)->prefix('/R&D/3DPrintingDirectCasting/NTHKO3DPDirectCasting')->group(function () {
            Route::get('/', 'index');
            Route::post('/saveCompletion', 'saveCompletion');
            Route::get('/show/{no?}/{id?}', 'Lihat');
            Route::get('/see/{no?}/{id?}', 'Lihat');
            Route::get('/cetak', 'cetak');
            Route::post('/speky{id?}', 'speky');
        });

        //?  ------------------------   tm hasil resin ke lilin   ------------------------ !!
        Route::middleware(['ceklevel:1,2,23,24'])->controller('RnD\TigaDPrintingDirectCasting\TMResinkeLilinController'::class)->prefix('/R&D/3DPrintingDirectCasting/TMResinkeLilin')->group(function () {
            Route::get('/', 'index');
            Route::get('/show/{no?}/{id?}', 'Lihat');
            Route::post('/store', 'store');
            Route::get('/cetak', 'cetak');
        });

    });

    //todo  ------------------------      Percobaan      ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {  

        //?  ------------------------   SPK Percobaan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2']], function () {
            Route::resource('/R&D/Percobaan/SPKPercobaan', 'RnD\Percobaan\SPKPercobaanController'::class);
            //Route::get('/R&D/Percobaan/SPKPercobaan', [SPKPercobaanController::class, 'index']);
        });

        //?  ------------------------   Katalog Routing   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,44']], function () {
            Route::resource('/R&D/Percobaan/KatalogRoutingPCB', 'RnD\Percobaan\KatalogRoutingController'::class);
            Route::post('/RnD/Percobaan/KatalogRouting/GetListRouting', [KatalogRoutingController::class, 'GetListRouting']);
            //Route::post('/store', 'store');
            /*Route::post('/RnD/Percobaan/PermintaanKomponenTanpaNTHKO/simpan', [PermintaanKomponenTanpaNTHKOController::class, 'simpan']);*/
 
        });


        //?  ------------------------   Permintaan Tanpa NTHKO   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2']], function () {
            Route::resource('/R&D/Percobaan/PermintaanKomponenTanpaNTHKO', 'RnD\Percobaan\PermintaanKomponenTanpaNTHKOController'::class);
            Route::post('/RnD/Percobaan/PermintaanKomponenTanpaNTHKO/GetListTanpaNTHKO', [PermintaanKomponenTanpaNTHKOController::class, 'GetListTanpaNTHKO']);
            Route::post('/RnD/Percobaan/PermintaanKomponenTanpaNTHKO/store', [PermintaanKomponenTanpaNTHKOController::class, 'store']);
            //Route::post('/store', 'store');
            /*Route::post('/RnD/Percobaan/PermintaanKomponenTanpaNTHKO/simpan', [PermintaanKomponenTanpaNTHKOController::class, 'simpan']);*/
 
        });

        //?  ------------------------   SPK Percobaan Tanpa Karet  ------------------------ !!
        Route::middleware(['ceklevel:1,2,3'])->controller('RnD\Percobaan\SPKPercobaanTanpaKaretController'::class)->prefix('/R&D/Percobaan/SPKPercobaanTanpaKaret')->group(function () {
            Route::get('/', 'index');
            Route::get('/show/{no?}/{id?}', 'show');
            Route::post('/store', 'store');
            Route::get('/cetak', 'cetak');
        });

        //?  ------------------------     TM Karet PCB ke Lilin    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,3,28']], function () {
            Route::get('/R&D/Percobaan/TMKaretPCBKeLilin',[TMKaretPCBKeLilinController::class, 'Index']);
            Route::post('/R&D/Percobaan/TMKaretPCBKeLilin',[TMKaretPCBKeLilinController::class, 'SaveTMKaretPCBKeLilin']);
            Route::put('/R&D/Percobaan/TMKaretPCBKeLilin',[TMKaretPCBKeLilinController::class, 'UpdateTMKaretLilin']);
            Route::get('/R&D/Percobaan/TMKaretPCBKeLilin/search',[TMKaretPCBKeLilinController::class, 'SearchTMKaretLilin']);
            Route::post('/R&D/Percobaan/TMKaretPCBKeLilin/items',[TMKaretPCBKeLilinController::class, 'GetRubber']);
            Route::get('/R&D/Percobaan/TMKaretPCBKeLilin/cetak',[TMKaretPCBKeLilinController::class, 'CetakTMKaretLilin']);
        });

        //?  ------------------------     TM Karet QC ke Lilin    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,3,28']], function () {
            Route::get('/R&D/Percobaan/TMKaretQcPCBKeLilin',[TMKaretQcPCBKeLilinController::class, 'Index']);
            Route::post('/R&D/Percobaan/TMKaretQcPCBKeLilin',[TMKaretQcPCBKeLilinController::class, 'SaveTMKaretLilin2']);
            Route::put('/R&D/Percobaan/TMKaretQcPCBKeLilin',[TMKaretQcPCBKeLilinController::class, 'UpdateTMKaretLilin2']);
            Route::get('/R&D/Percobaan/TMKaretQcPCBKeLilin/search',[TMKaretQcPCBKeLilinController::class, 'SearchTMKaretLilin']);
            Route::post('/R&D/Percobaan/TMKaretQcPCBKeLilin/items',[TMKaretQcPCBKeLilinController::class, 'GetWorkAllocation4']);
            Route::get('/R&D/Percobaan/TMKaretQcPCBKeLilin/cetak',[TMKaretQcPCBKeLilinController::class, 'CetakTMKaretLilin']);
            Route::get('/R&D/Percobaan/TMKaretQcPCBKeLilin/informasi',[TMKaretQcPCBKeLilinController::class, 'Information']);
        });

        //?  ------------------------     Posting TM PCB    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,33,44']], function () {
            Route::get('/R&D/Percobaan/PostingTMPCB',[PostingTMPCBController::class, 'Index']);
            Route::get('/R&D/Percobaan/PostingTMPCB/getTM',[PostingTMPCBController::class, 'GetTM']);
            Route::post('/R&D/Percobaan/PostingTMPCB/posting',[PostingTMPCBController::class, 'PostingTM']);
            Route::get('/R&D/Percobaan/PostingTMPCB/cetak',[PostingTMPCBController::class, 'CetakWIPGrafis']);
        });

        //?  ------------------------     WIP Grafis    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,28,9,41,44']], function () {
            Route::get('/R&D/Percobaan/WIPGrafis',[WipGrafisController::class, 'Index']);
            Route::post('/R&D/Percobaan/WIPGrafis',[WipGrafisController::class, 'SaveWIPGrafis']);
            Route::get('/R&D/Percobaan/WIPGrafis/getNTHKO',[WipGrafisController::class, 'GetNTHKOVarp']);
            Route::get('/R&D/Percobaan/WIPGrafis/search',[WipGrafisController::class, 'SearchWIPGrafis']);
            Route::get('/R&D/Percobaan/WIPGrafis/cetak',[WipGrafisController::class, 'CetakWIPGrafis']);
        });

        //?  ------------------------     Transfer FG Konfirmasi    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2']], function () {
            Route::get('/R&D/Percobaan/TransferFGKonfirmasi',[TransferFGKonfirmasiController::class, 'index']);
            Route::post('/R&D/Percobaan/TransferFGKonfirmasi/lihat',[TransferFGKonfirmasiController::class, 'lihat']);
            Route::post('/R&D/Percobaan/TransferFGKonfirmasi/getItemTM',[TransferFGKonfirmasiController::class, 'getItemTM']);
            Route::post('/R&D/Percobaan/TransferFGKonfirmasi/simpan',[TransferFGKonfirmasiController::class, 'simpan']);
            Route::post('/R&D/Percobaan/TransferFGKonfirmasi/posting',[TransferFGKonfirmasiController::class, 'posting']);
            Route::get('/R&D/Percobaan/TransferFGKonfirmasi/cetak',[TransferFGKonfirmasiController::class, 'cetak']);
        });

        //?  ------------------------     Transfer FG QC    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2']], function () {
            Route::get('/R&D/Percobaan/TransferFGQC',[TransferFGQCController::class, 'Index']);
            Route::post('/R&D/Percobaan/TransferFGQC/lihat',[TransferFGQCController::class, 'lihat']);
            Route::post('/R&D/Percobaan/TransferFGQC/simpan',[TransferFGQCController::class, 'simpan']);
            Route::post('/R&D/Percobaan/TransferFGQC/posting',[TransferFGQCController::class, 'posting']);
            Route::get('/R&D/Percobaan/TransferFGQC/cetak',[TransferFGQCController::class, 'cetak']);
        });

        //?  ------------------------     Transfer FG QC Enamel    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2']], function () {
            Route::get('/R&D/Percobaan/TransferFGQCEnamel',[TransferFGQCEnamelController::class, 'Index']);
            Route::post('/R&D/Percobaan/TransferFGQCEnamel/lihat',[TransferFGQCEnamelController::class, 'lihat']);
            Route::post('/R&D/Percobaan/TransferFGQCEnamel/simpan',[TransferFGQCEnamelController::class, 'simpan']);
            Route::post('/R&D/Percobaan/TransferFGQCEnamel/posting',[TransferFGQCEnamelController::class, 'posting']);
            Route::get('/R&D/Percobaan/TransferFGQCEnamel/cetak',[TransferFGQCEnamelController::class, 'cetak']);
        });
    });

    //todo  ------------------------      Grafis      ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   NTHKO Grafis lama   ------------------------ !!
        // Route::middleware(['ceklevel:1,2,33,44'])->controller('RnD\Grafis\NTHKOGrafis'::class)->prefix('/R&D/Grafis/NTHKOGrafis')->group(function () {
        //     Route::get('/', 'index');
        //     Route::get('/show/{no?}/{id?}', 'show');
        //     Route::post('/store', 'store');
        //     Route::post('/posting', 'posting');
        //     Route::post('/upload', 'upload');
        //     Route::get('/cetak', 'cetak');
        // });

        //?  ------------------------   NTHKO Grafis   ------------------------ !!
        Route::middleware(['ceklevel:1,2,33,44'])->controller('RnD\Grafis\NTHKOGrafis'::class)->prefix('/R&D/Grafis/NTHKOGrafis')->group(function () {
            Route::get('/', 'index');
            Route::get('/show/{no?}/{id?}', 'show');
            Route::post('/store', 'store');
            Route::put('/update', 'update');
            Route::post('/posting', 'posting');
            Route::post('/upload', 'upload');
            Route::get('/cetak', 'cetak');
        });

        //?  ------------------------     SPKO Grafis    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,33,44']], function () {
            Route::get('/R&D/Grafis/SPKOGrafis',[SPKOGrafisController::class, 'Index']);
            Route::post('/R&D/Grafis/SPKOGrafis',[SPKOGrafisController::class, 'SaveSPKO']);
            Route::post('/R&D/Grafis/SPKOGrafis/posting',[SPKOGrafisController::class, 'PostingSPKO']);
            Route::get('/R&D/Grafis/SPKOGrafis/getWIP',[SPKOGrafisController::class, 'GetWIP']);
            Route::get('/R&D/Grafis/SPKOGrafis/cari',[SPKOGrafisController::class, 'FindSPKO']);
            Route::get('/R&D/Grafis/SPKOGrafis/cetak',[SPKOGrafisController::class, 'CetakSPKO']);
        });

        //?  ------------------------   Upload Foto   ------------------------ !!
        Route::middleware(['ceklevel:1,2,33'])->controller('RnD\Grafis\UploadFoto'::class)->prefix('/R&D/Grafis/UploadFoto')->group(function () {
            Route::get('/', 'index');
            Route::get('/show/{no?}/{id?}', 'show');
            Route::post('/store', 'store');
            Route::get('/search', 'search');
        });
    });

    //todo  ------------------------      Informasi      ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {      
        //?  ------------------------   Informasi BoM   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,3']], function () {
            Route::resource('/R&D/Informasi/InformasiProduk', 'RnD\Informasi\InformasiProdukController'::class);
        });
        Route::post('/RnD/Informasi/InformasiProduk/GetListProdukKomponen', [InformasiProdukController::class, 'GetListProdukKomponen']);
        Route::post('/RnD/Informasi/InformasiProduk/GetListProdukMainan', [InformasiProdukController::class, 'GetListProdukMainan']);
        Route::post('/RnD/Informasi/InformasiProduk/GetListProdukKepala', [InformasiProdukController::class, 'GetListProdukKepala']);
        //Route::post('/RnD/Informasi/InformasiProduk/GetListProdukMainan', [InformasiProdukController::class, 'GetListProdukMainan']);
        Route::get('/produk', function () {return view('R&D.Informasi.InformasiProduk.produk'); });
        Route::get('/kepala', function () {return view('R&D.Informasi.InformasiProduk.kepala'); });
        Route::get('/mainan', function () {return view('R&D.Informasi.InformasiProduk.mainan'); });
        Route::get('/component', function () {return view('R&D.Informasi.InformasiProduk.component'); });



        //?  ------------------------   Informasi Produktivitas Operator   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,33']], function () {
            Route::get('/R&D/Informasi/InfoProduktivitas',[InfoRekapProduktivitasRnDController::class, 'Index']);
            Route::get('/R&D/Informasi/InfoProduktivitas/getBulan', [InfoRekapProduktivitasRnDController::class, 'getBulan']);
            Route::get('/R&D/Informasi/InfoProduktivitas/getInfoProduktivitas', [InfoRekapProduktivitasRnDController::class, 'getInfoProduktivitas']);
            
        });


        //?  ------------------------   Checking Form Order   ------------------------ !!
        
        Route::middleware(['ceklevel:1,2,3'])->controller('RnD\Informasi\CheckFormOrderController'::class)->prefix('/R&D/Informasi/CheckFormOrder')->group(function () {
             Route::get('/', 'index');
        });

        //!  ------------------------    Informasi TM Karet PCB ke Lilin    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,3,28,29']], function () {
            Route::get('/R&D/Informasi/InformasiTMKaretPCB',[InformasiTMKaretPCBController::class, 'Index']);
        });

        //?  ------------------------     Informasi WIP Grafis    ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,33,41,44']], function () {
            Route::get('/R&D/Informasi/InformasiWIPGrafis',[InformasiWIPGrafisController::class, 'Index']);
            Route::get('/R&D/Informasi/InformasiWIPGrafis/wipdatasource',[InformasiWIPGrafisController::class, 'GetWIPGrafis']);
            Route::get('/R&D/Informasi/InformasiWIPGrafis/productwipdatasource',[InformasiWIPGrafisController::class, 'GetProductWIPGrafis']);
        });

    });
    
});

//!  ------------------------     Workshop     ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //?  ------------------------   SPK Workshop   ------------------------ !!
    Route::controller('Workshop\SPKWorkshopController'::class)->prefix('/Workshop/SPKWorkshop')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{no?}/{id?}', 'Lihat');
        Route::post('/store', 'store');
        Route::put('/update/{id?}', 'update');
        Route::get('/cetak', 'cetak');
    });
    
    //?  ------------------------   Workshop Approval   ------------------------ !!
    Route::controller('Workshop\WorkshopApprovalController'::class)->prefix('/Workshop/WorkshopApproval')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{no?}', 'show');
        Route::get('/tambah', 'tambah');
    });

    //?  ------------------------   Workshop Information   ------------------------ !!
    Route::controller('Workshop\WorkshopInformationController'::class)->prefix('/Workshop/WorkshopInformation')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{tgl1?}/{tgl2?}/{cari?}', 'Lihat');
    });

    //!  ------------------------     Verifikasi Workshop     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,47']], function () {
        Route::get('/Workshop/VerifikasiWorkshop', [VerifikasiWorkshopController::class, 'Index']);
        Route::post('/Workshop/VerifikasiWorkshop', [VerifikasiWorkshopController::class, 'SavetoWIP']);
        Route::get('/Workshop/VerifikasiWorkshop/getSPK', [VerifikasiWorkshopController::class, 'FindSPKPCB']);
    });

    //!  ------------------------     WIP Workshop     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,47']], function () {
        Route::get('/Workshop/WIPWorkshop', [WIPWorkshopController::class, 'Index']);
        Route::get('/Workshop/WIPWorkshop/preview/{idProduct}', [WIPWorkshopController::class, 'PreviewFunction']);
        Route::get('/Workshop/WIPWorkshop/verified/{idProduct}', [WIPWorkshopController::class, 'VerifiedWIP']);
    });

    //!  ------------------------     TM Matras Workshop     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,47']], function () {
        Route::get('/Workshop/TMMatras', [TMMatrasWorkshopController::class, 'Index']);
        Route::post('/Workshop/TMMatras', [TMMatrasWorkshopController::class, 'SaveTMMatras']);
        Route::put('/Workshop/TMMatras', [TMMatrasWorkshopController::class, 'UpdateTMMatras']);
        Route::get('/Workshop/TMMatras/matras', [TMMatrasWorkshopController::class, 'GetMatras']);
        Route::get('/Workshop/TMMatras/search', [TMMatrasWorkshopController::class, 'SearchTMMatras']);
        Route::get('/Workshop/TMMatras/cetak', [TMMatrasWorkshopController::class, 'CetakTMMatras']);
    });

    //!  ------------------------     Gambar Teknik Workshop     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,47']], function () {
        Route::get('/Workshop/GambarTeknik', [GambarTeknikWorkshopController::class, 'Index']);
        Route::post('/Workshop/GambarTeknik', [GambarTeknikWorkshopController::class, 'SaveGambarTeknik']);
        Route::post('/Workshop/GambarTeknik/update', [GambarTeknikWorkshopController::class, 'UpdateGambarTeknik']);
        Route::get('/Workshop/GambarTeknik/posting', [GambarTeknikWorkshopController::class, 'PostingGambarTeknik']);
        Route::get('/Workshop/GambarTeknik/generate-form', [GambarTeknikWorkshopController::class, 'GenerateForm']);
        Route::get('/Workshop/GambarTeknik/search', [GambarTeknikWorkshopController::class, 'SearchGambarTeknik']);
        Route::get('/Workshop/GambarTeknik/cetak', [GambarTeknikWorkshopController::class, 'CetakGambarTeknik']);
    });

    //!  ------------------------     SPKO Matras Workshop     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,47']], function () {
        Route::get('/Workshop/SPKO/Matras', [SPKOMatrasWorkshopController::class, 'Index']);
        Route::post('/Workshop/SPKO/Matras', [SPKOMatrasWorkshopController::class, 'SaveSPKOMatrasWorkshop']);
        Route::put('/Workshop/SPKO/Matras', [SPKOMatrasWorkshopController::class, 'UpdateSPKOMatrasWorkshop']);
        Route::get('/Workshop/SPKO/Matras/posting', [SPKOMatrasWorkshopController::class, 'PostingSPKOMatrasWorkshop']);
        Route::get('/Workshop/SPKO/Matras/gambarteknik', [SPKOMatrasWorkshopController::class, 'getGambarTeknik']);
        Route::get('/Workshop/SPKO/Matras/search', [SPKOMatrasWorkshopController::class, 'FindSPKO']);
        Route::get('/Workshop/SPKO/Matras/cetak', [SPKOMatrasWorkshopController::class, 'CetakSPKO']);
    });

    //!  ------------------------     NTHKO Matras Workshop     ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2,47']], function () {
        Route::get('/Workshop/NTHKO/Matras', [NTHKOMatrasWorkshopController::class, 'Index']);
        Route::post('/Workshop/NTHKO/Matras', [NTHKOMatrasWorkshopController::class, 'saveNTHKOWorkshop']);
        Route::put('/Workshop/NTHKO/Matras', [NTHKOMatrasWorkshopController::class, 'updateNTHKOMatrasWorkshop']);
        Route::get('/Workshop/NTHKO/Matras/getSPKO', [NTHKOMatrasWorkshopController::class, 'GetSPKOWorkshop']);
        Route::get('/Workshop/NTHKO/Matras/search', [NTHKOMatrasWorkshopController::class, 'SearchNTHKOMatrasWorkshop']);
        Route::get('/Workshop/NTHKO/Matras/cetak', [NTHKOMatrasWorkshopController::class, 'CetakNTHKOMatrasWorkshop']);
    });
});

//!  ------------------------        IT        ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //?  ------------------------   Data PC   ------------------------ !!
    Route::middleware(['ceklevel:1,2'])->controller('IT\DataPCController'::class)->prefix('/IT/DataPC')->group(function () {
        Route::get('/', 'DataPC');
        Route::get('/cetak', 'DataPCcetak');
        Route::get('/info/{id?}', 'DataPCInfo');
        Route::get('/edit/{id?}', 'DataPCedit');
        Route::get('/tambah', 'DataPCTambah');
        Route::get('/kar2/{id?}', 'DataPCkar');
        Route::post('/tambah', 'DataPCCreat');
        Route::put('/edit/{id?}', 'DataPCUpdate');
        Route::get('/search', 'search');
    });

    //?  ------------------------   Data Printer   ------------------------ !!
    Route::middleware(['ceklevel:1,2'])->controller('IT\DataPrinterController'::class)->prefix('/IT/MasterDataHardware/DataPrinter')->group(function () {
        Route::resource('/', 'IT\DataPrinterController'::class);
        Route::get('/search', 'search');
        Route::get('/cetak', 'cetak');
    });

    //?  ------------------------   Data Storage   ------------------------ !!
    Route::middleware(['ceklevel:1,2'])->controller('IT\DataStorageController'::class)->prefix('/IT/MasterDataHardware/DataStorage')->group(function () {
        Route::resource('/', 'IT\DataStorageController'::class);
        Route::get('/search', 'search');
        Route::get('/cetak', 'cetak');
    });
    //?  ------------------------   Data Monitor   ------------------------ !!
    Route::middleware(['ceklevel:1,2'])->controller('IT\DataMonitorController'::class)->prefix('/IT/MasterDataHardware/DataMonitor')->group(function () {
        Route::resource('/', 'IT\DataMonitorController'::class);
        Route::get('/search', 'search');
        Route::get('/cetak', 'cetak');
    });
    //?  ------------------------   Data Memory   ------------------------ !!
    Route::middleware(['ceklevel:1,2'])->controller('IT\DataMemoryController'::class)->prefix('/IT/MasterDataHardware/DataMemory')->group(function () {
        Route::resource('/', 'IT\DataMemoryController'::class);
        Route::get('/search', 'search');
        Route::get('/cetak', 'cetak');
    });
    //?  ------------------------   Data VGA   ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2']], function () {
        Route::get('/IT/MasterDataHardware/DataVGA/search', 'IT\DataVGAController@search');
        Route::get('/IT/MasterDataHardware/DataVGA/cetak', 'IT\DataVGAController@cetak');
        Route::resource('/IT/MasterDataHardware/DataVGA', 'IT\DataVGAController'::class);
    });
    //?  ------------------------   Data UPS   ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2']], function () {
        Route::get('/IT/MasterDataHardware/DataUPS/search', 'IT\DataUPSController@search');
        Route::get('/IT/MasterDataHardware/DataUPS/cetak', 'IT\DataUPSController@cetak');
        Route::resource('/IT/MasterDataHardware/DataUPS', 'IT\DataUPSController'::class);
    });
    //?  ------------------------   Data Mouse   ------------------------ !!
    Route::group(['middleware' => ['ceklevel:1,2']], function () {
        Route::get('/IT/MasterDataHardware/DataMouse/search', 'IT\DataMouseController@search');
        Route::get('/IT/MasterDataHardware/DataMouse/cetak', 'IT\DataMouseController@cetak');
        Route::resource('/IT/MasterDataHardware/DataMouse', 'IT\DataMouseController'::class);
    });
});

//!  ------------------------     Lain-lain    ------------------------ !!
Route::group(['middleware' => ['auth']], function () {

    //?  ------------------------     Korespondensi     ------------------------ !!
    Route::group(['middleware' => ['auth']], function () {

        //?  ------------------------   Surat Jalan   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,11,12,40,4']], function () {
            Route::get('/Lain-lain/Korespondensi/SuratJalan', [SuratJalanController::class, 'Index']);
            Route::post('/Lain-lain/Korespondensi/SuratJalan', [SuratJalanController::class, 'CreateSuratJalan']);
            Route::put('/Lain-lain/Korespondensi/SuratJalan', [SuratJalanController::class, 'UpdateSuratJalan']);
            Route::get('/Lain-lain/Korespondensi/SuratJalan/cetak/{SW}', [SuratJalanController::class, 'Cetak']);
            Route::get('/Lain-lain/Korespondensi/SuratJalan/cari/{SW}', [SuratJalanController::class, 'CariSuratJalan']);
            Route::get('/Lain-lain/Korespondensi/SuratJalan/recipient', [SuratJalanController::class, 'FindRecipient']);
            Route::get('/Lain-lain/Korespondensi/SuratJalan/informasi', [SuratJalanController::class, 'SuratJalanInformation']);
            Route::get('/Lain-lain/Korespondensi/SuratJalan/resource-informasi', [SuratJalanController::class, 'SuratJalanInformationResource']);
            Route::get('/Lain-lain/Korespondensi/SuratJalan/listrecipient', [SuratJalanController::class, 'ListRecipientInformation']);
            Route::put('/Lain-lain/Korespondensi/SuratJalan/listrecipient', [SuratJalanController::class, 'UpdateRecipient']);
            Route::delete('/Lain-lain/Korespondensi/SuratJalan/listrecipient', [SuratJalanController::class, 'RemoveRecipient']);
        });

        //?  ------------------------   Surat Pengantar   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,11,12,4']], function () {
            Route::get('/Lain-lain/Korespondensi/SuratPengantar', [SuratPengantarController::class, 'Index']);
            Route::post('/Lain-lain/Korespondensi/SuratPengantar', [SuratPengantarController::class, 'CreateSuratPengantar']);
            Route::put('/Lain-lain/Korespondensi/SuratPengantar', [SuratPengantarController::class, 'UpdateSuratPengantar']);
            Route::get('/Lain-lain/Korespondensi/SuratPengantar/cari/{SW}', [SuratPengantarController::class, 'CariSuratPengantar']);
            Route::get('/Lain-lain/Korespondensi/SuratPengantar/cetak/{SW}', [SuratPengantarController::class, 'Cetak']);
            Route::get('/Lain-lain/Korespondensi/SuratPengantar/recipient', [SuratPengantarController::class, 'FindRecipient']);
            Route::get('/Lain-lain/Korespondensi/SuratPengantar/informasi', [SuratPengantarController::class, 'informasi']);
            Route::get('/Lain-lain/Korespondensi/SuratPengantar/resource-informasi', [SuratPengantarController::class, 'SuratPengantarInformationResource']);
        });

        //?  ------------------------   Surat Tanda Terima   ------------------------ !!
        Route::group(['middleware' => ['ceklevel:1,2,11,12,40,4,56']], function () {
            Route::get('/Lain-lain/Korespondensi/TandaTerima', [TandaTerimaController::class, 'Index']);
            Route::post('/Lain-lain/Korespondensi/TandaTerima', [TandaTerimaController::class, 'CreateTandaTerima']);
            Route::put('/Lain-lain/Korespondensi/TandaTerima', [TandaTerimaController::class, 'UpdateTandaTerima']);
            Route::get('/Lain-lain/Korespondensi/TandaTerima/cari/{SW}', [TandaTerimaController::class, 'CariTandaTerima']);
            Route::get('/Lain-lain/Korespondensi/TandaTerima/cetak/{SW}', [TandaTerimaController::class, 'Cetak']);
            Route::get('/Lain-lain/Korespondensi/TandaTerima/informasi', [TandaTerimaController::class, 'informasi']);
            Route::get('/Lain-lain/Korespondensi/TandaTerima/resource-informasi', [TandaTerimaController::class, 'TandaTerimaInformationResource']);
        });
    });

     //?  ------------------------   Messaging   ------------------------ !!
    Route::controller('LainLain\MessagingController'::class)->prefix('/Lain-lain/Messaging')->group(function () {
        Route::get('/', 'index');
        Route::get('/show', 'show');
    });
});

//!! ------------------------   Tanpa middleware 2      ------------------------ !!

//todo  ------------------      Master Item Catalog     ------------------------ !!
Route::controller('Master\Item\CatalogController'::class)->prefix('/Master/Item/Katalog')->group(function () {
    Route::get('/', 'Index');
    Route::get('/cekspk', 'CekSPK');
    Route::get('/spk/{SWSPK}','GetSPK');
    Route::get('/spk/detail/{SWProduct}', 'DetailItemSPK');
    Route::get('/cekmodel', 'CekNoModel');
    Route::get('/model/cetak', 'CetakNoModel');
    Route::get('/model/{idCategory}/{fromNumber}/{toNumber}', 'GetNoModel');
    Route::get('/model/detail/{SWProduct}', 'DetailItemModel');
    Route::get('/model/cetakselectedmodel', 'PilihCetakModel');
    Route::post('/model/cetakselectedmodel', 'CetakSelectedModel');
    Route::get('/cektukangluar', 'CekTukangLuar');
    Route::get('/cektukangluarbyspk', 'CekTukangLuarBySPK');
    Route::get('/tukangluar/{idCategory}/{fromNumber}/{toNumber}', 'GetTukangLuar');
    Route::get('/tukangluarbyspk/{noSPK}', 'GetTukangLuarBySPK');
    Route::get('/tukangluar/detail/{SWProduct}', 'DetailItemTukangLuar');
    Route::get('/ceklilin', 'CekLilin');
    Route::get('/lilin/{idKaret}', 'GetLilin');
    Route::get('/marketing', 'NewMarketingCatalog');
    Route::get('/marketing/cetak', 'NewMarketingCatalogPrint');
});

//todo  ------------------      Lain - Lain QRCode     ------------------------ !!
Route::controller('LainLain\QRCode\QRCodeGeneratorController'::class)->prefix('/Lain-lain/QRCode/QRCodeGenerator')->group(function () {
    Route::get('/', 'Index');
    Route::get('/generate/{no?}/{id?}', 'generate');
});

Route::controller('LainLain\QRCode\LabelBarcodePCBController'::class)->prefix('/Lain-lain/QRCode/LabelBarcodePCB')->group(function () {
    Route::get('/', 'Index');
    Route::get('/generate/{no?}/{id?}', 'generate');
});

Route::controller('LainLain\QRCode\QRTMBarangJadiController'::class)->prefix('/Lain-lain/QRCode/QRTMBarangJadi')->group(function () {
    Route::get('/', 'Index');
    Route::get('/generate/{no?}/{id?}', 'generate');
    Route::post('/generate', 'generate2');
});

//!  ------------------------     API Worklog     ------------------------ !!
Route::controller(WorklogApiController::class)->group(function () {
    Route::get('/api/v1/employee-statistics', [WorklogApiController::class, 'getEmployeeStatistics']);
});
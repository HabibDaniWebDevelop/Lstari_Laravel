<div class="card col-lg-12 p-4" style="height:calc(100vh - 295px);">

    <div class="table-responsive">
        <table class="table table-inverse table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr class="text-center">
                    <th width='3%'> NO </th>
                    <th width='230'> demo </th>
                    <th> lokasi projek </th>
                    <th width='50%'> Description </th>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/tespostingnew')">
                            Posting NEW
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesPostingNew</td>
                    <td>Digunakan untuk melakukan posting <b>SPKO, NTHKO, TM</b> secara looping</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/tesposting')">
                            Posting
                        </button></td>
                    <td>Controllers: publick_function_sampel :: tesposting</td>
                    <td>Digunakan untuk melakukan posting <b>SPKO, NTHKO, TM</b> sekali kirim</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TespostingTM')">
                            Posting TM
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TespostingTM</td>
                    <td>Digunakan untuk melakukan posting <b>TM</b> mengurangi stock di <b>FromLoc</b> dan menambah stock di <b>ToLoc</b> sekaligus</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TesCekStokHarian')">
                            Cek Stok Harian 1 lokasi
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesCekStokHarian</td>
                    <td>Digunakan untuk mengecek stok harian sudah sesuai berdasar <b>Location</b> area sudah benar</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TesCekStokHarian2')">
                            Cek Stok Harian 2 lokasi
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesCekStokHarian2</td>
                    <td>Digunakan untuk mengecek stok harian sudah sesuai berdasar <b>FromLoc dan ToLoc</b> area sudah benar </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TesSetStatustransaction')">
                            Set Status transaction
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesSetStatustransaction</td>
                    <td>Digunakan setelah menjalankan posting <b>SPKO, NTHKO, TM</b> untuk meng update status trasnsaksi dari A ke P dan update tanggal posting </td>
                </tr>
                <tr>
                    <td>7</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TesSetStatustransaction')">
                            Check Previous Posting
                        </button></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/timbangan')">
                            Timbangan
                        </button></td>
                    <td>view: setting / publick_function / timbangan</td>
                    <td>Digunakan untuk menarik nilai input hardware timbangan <br> Jangan lupa setting di chrome: <b>chrome://flags/</b> terus masukan <b>http://192.168.1.100:8282</b> di kolom <b>Insecure origins treated as secure</b></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/tesGetLastID')">
                            Get LastID
                        </button></td>
                    <td>Controllers: publick_function_sampel :: tesGetLastID</td>
                    <td>Digunakan untuk mendapatkan last id tabel</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TesListUserHistory')">
                            List User History
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesListUserHistory</td>
                    <td>Digunaklan untuk mendapatkan data 10 trasnsaksi terahir user di modul yang di guanakan</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100"
                            onclick="window.open('/TesUpdateUserHistory')">
                            Update User History
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesUpdateUserHistory</td>
                    <td>Digunaklan untuk menambah/update data last id ketika selesai menyimpan atau melihat data oleh user</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td><button type="button" class="btn btn-primary m-1 w-100" onclick="window.open('/TesViewSelection')">
                            View Selection
                        </button></td>
                    <td>Controllers: publick_function_sampel :: TesViewSelection</td>
                    <td>Digunaklan untuk menfilter data lebih detai dengan parameter yang sudah di masukan oleh user<br> klik <b><i class="bx bx-search"></i></b> di input search</td>
                </tr>


                {{-- @foreach ($datas as $data)
            <tr class="baris" id="2">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $data->SW }}</td>
                <td>{{ $data->Description }}</td>
                <td>{{ $data->EntryDate }}</td>
                <td> </td>
            </tr>
            @endforeach --}}

            </tbody>

        </table>

    </div>

</div>


{{-- <div class="card-body">
        <div class="row gy-3">
            <!-- Default Modal -->
            <div class="col-12">
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/tesposting')">
                    Posting (SPKO, NTHKO, TM)
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/TespostingTM')">
                    Posting TM (TM Keluar Masuk)
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/TesCekStokHarian')">
                    Cek Stok Harian
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/timbangan')">
                    Timbangan
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/tesGetLastID')">
                    Get LastID
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/TesListUserHistory')">
                    List User History
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/TesUpdateUserHistory')">
                    Update User History
                </button>
    
                <button type="button" class="btn btn-primary m-1" onclick="window.open('/TesViewSelection')">
                    View Selection
                </button>
    
            </div>
    
        </div>
    </div> --}}

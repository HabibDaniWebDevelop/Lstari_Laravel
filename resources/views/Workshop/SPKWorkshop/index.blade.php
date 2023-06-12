<?php $title = 'Surat Perintah Kerja Mekanikal / Elektronikal / IT'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">Surat Perintah Kerja</li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Workshop.SPKWorkshop.data')

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>

@endsection

@section('script')

    <script>
        //patch lokasi modul
        var patch = '/Workshop/SPKWorkshop/';

        function ChangeCari(id) {
            $('#btn-menu .btn').prop('disabled', true);
            $('#Cetak1').prop('disabled', false);
            $('#Batal1').prop('disabled', false);
            $('#Ubah1').prop('disabled', false);
            $("#tampil").removeClass('d-none');

            if (id == '0') {
                id = $('#cari').val();
            }
            $.get(patch + 'show/1/' + id, function(data) {
                $("#tampil").html(data);
                $('#Cetak1').val(id);
            });
        }

        function Klik_Baru1() {
            $('#btn-menu .btn').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#cari').prop('readonly', true);
            $("#tampil").removeClass('d-none');
            $('#Simpan1').val('baru');

            $.get(patch + 'show/2/0', function(data) {
                $("#tampil").html(data);
            });
        }

        function Klik_Ubah1() {
            $('#btn-menu .btn').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#cari').prop('readonly', true);
            $("#tampil").removeClass('d-none');
            $('#Simpan1').val('ubah');

            id = $('#cari').val();
            $.get(patch + 'show/3/' + id, function(data) {
                $("#tampil").html(data);
                $('#Cetak1').val(id);
            });
        }

        function Klik_Cetak1() {
            let id = $('#hasilid').val();
            window.open(patch + 'cetak?id=' + id, '_blank');
        }

        function Klik_Batal1() {
            location.reload();
        }

        //produk
        function getdatacari(event, row) {
            char = event.which || event.keyCode;
            if (char == 9 || char == 13) {
                var id = $('[data-index="' + row + '2"]').val();
                var idbagian = $('#idbagian').val();


                if (id != '') {

                    $.get('/Workshop/SPKWorkshop/show/4/' + id + ',' + idbagian, function(data) {

                        if (data.success) {
                            $('[data-index="' + row + '3"]').val(data.Dsc);
                            $('[data-index="' + row + '4"]').focus();
                        } else {

                            Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'Data tidak ditemukan !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('[data-index="' + row + '2"]').val('');
                            $('[data-index="' + row + '2"]').focus();
                            return;
                        }
                    });
                }

            }
        }

        //getkaryawan
        function getkary() {

            var id = $('#karyawan').val();

            if (id == '') {
                id = 'xxxxx';
            }
            $.get(patch + 'show/5/' + id, function(data) {

                if (data.success) {
                    $("#karid0").html("( " + data.karid + " )");
                    $('#karid').val(data.karid);
                    $('#karyawan').val(data.nama);
                    $('#karyawan0').val(data.nama);
                    $('#bagian').val(data.jabatan);
                    $('#idbagian').val(data.idbgn);
                    $('#username').val(data.username);
                } else {

                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Data tidak ditemukan !',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // dd
                    var reset = $('#karyawan0').val();
                    $('#karyawan').val(reset);
                    $('#karyawan').select();
                }
            });
        }

        //getgettgl
        function gettgl(row) {
            var kategori = $('[data-index="' + row + '6"]').val();

            $.get('/Workshop/SPKWorkshop/show/6/' + kategori, function(data) {
                $('[data-index="' + row + '7"]').val(data.tgl);
                $('[data-index="' + row + '8"]').focus();
            });
        }

        // // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

        function add(id) {
            let rowCount = $('#tabel1 tr').length;

            // cheking / validasi data sebelum tambah baru
            var barang = $('[data-index="' + id + '3"]').val();
            var jumlah = $('[data-index="' + id + '4"]').val();
            var tgl_butuh = $('[data-index="' + id + '7"]').val();
            console.log(barang, jumlah, tgl_butuh);

            var chek = '';
            barang === '' ? chek += "barang tidak boleh kosong. " : '';
            jumlah === '' ? chek += "jumlah tidak boleh kosong. " : '';
            tgl_butuh === '' ? chek += "Kategori tidak boleh kosong." : '';
            
            if (chek !== '') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: chek,
                    showConfirmButton: false,
                    timer: 1800
                });

                return;
            }

            // Setup table row
            let trStart = '<tr class="baris" id="' + rowCount + '">';
            let cell1 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly value="' +
                rowCount + '" data-index="' + rowCount + '1"> </td>';
            let cell2 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="id_inv[]" value="" data-index="' +
                rowCount + '2" onkeydown="getdatacari(event, ' + rowCount + ')"> </td>';
            let cell3 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="barang[]" data-index="' +
                rowCount + '3"> </td>';
            let cell4 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="jumlah[]" value="" data-index="' +
                rowCount + '4"> </td>';
            let cell5 =
                '<td class="m-0 p-0"> <select class="form-select form-select-sm fs-6 w-100" name="tipe[]" data-index="' +
                rowCount +
                '5" onkeydown="handler(event)"> <option value="Service" >Service</option> <option value="Rusak" >Rusak</option> <option value="Baru" >Baru</option> </select> </td>';
            let cell6 =
                '<td class="m-0 p-0"> <select class="form-select form-select-sm fs-6 w-100" name="kategori[]" data-index="' +
                rowCount +
                '6" onkeydown="handler(event)" onchange="gettgl(' +
                rowCount +
                ')"> <option value="">Pilih Kategori</option> <option value="Biasa">Biasa</option> <option value="Penting">Penting</option> <option value="Darurat">Darurat</option> </select> </td>';
            let cell7 =
                '<td class="m-0 p-0"> <input type="date" class="form-control form-control-sm fs-6 w-100" name="tgl_butuh[]" value="" data-index="' +
                rowCount + '7"> </td>';
            let cell8 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="deskripsi[]" value="" data-index="' +
                rowCount + '8"> </td>';
            let cell9 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="id_bagian_inv[]" value="" data-index="' +
                rowCount + '9" posisi-index="akhir"> </td> <input type="hidden" name="id[]" value="" >';
            let trEnd = '</tr>';
            let finalItem = "";
            let rowitem = finalItem.concat(trStart, cell1, cell2, cell3, cell4, cell5, cell6, cell7, cell8, cell9, trEnd);
            $("#tabel1 > tbody").append(rowitem);

            $posisi = "#tabel1 #" + rowCount + " input";
            $($posisi).on('contextmenu', function(e) {
                rightclik(this, e);
            });

            $($posisi).keydown(function(e) {
                var id = $(this).parent().parent().attr('id');
                // alert(id);
                tambahbaris(id);
            });

        }

        //digunakan ketika select option tidak bisa berpindah focuss
        function handler(event) {
            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));

            if (event.keyCode === 39) {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 37) {
                $('[data-index="' + (index - 1).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 13) {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
            }
        }

        function klikhapus(id) {
            // var id = $(this).attr('id');
            $("#" + id).remove();

            $("#tabel1 tr").each((i, elem) => {
                Index = i + 1;
                if (Index < id) {
                    newIndex = i + 1;
                } else {
                    newIndex = i;
                }
                // alert(Index +' '+ newIndex)
                $('[data-index="' + Index + '1"]').attr('value', newIndex);
                $('[data-index="' + Index + '1"]').parent().parent().attr('id', newIndex);
                $('[data-index="' + Index + '1"]').attr('data-index', newIndex + '1');
                $('[data-index="' + Index + '2"]').attr('data-index', newIndex + '2');
                $('[data-index="' + Index + '3"]').attr('data-index', newIndex + '3');
                $('[data-index="' + Index + '4"]').attr('data-index', newIndex + '4');
                $('[data-index="' + Index + '5"]').attr('data-index', newIndex + '5');

                $(elem).find('.satuan').attr('id', "satuan_" + newIndex);

            })
        }

        //proses simpan
        function Klik_Simpan1() {
            action = $('#Simpan1').val();
            var modul = $("#Keperluan").val();

            // cheking / validasi data sebelum tambah baru
            let rowCount = $('#tabel1 tbody tr').length;
            let barang = $('[data-index="' + rowCount + '3"]').val();
            let jumlah = $('[data-index="' + rowCount + '4"]').val();
            let tipe = $('[data-index="' + rowCount + '5"]').val();
            let kategori = $('[data-index="' + rowCount + '6"]').val();
            let tgl_butuh = $('[data-index="' + rowCount + '7"]').val();
            console.log(barang, jumlah, tgl_butuh, tipe, kategori);

            var chek = '';
            modul === null ? chek += " Keperluan tidak boleh kosong. " : '';
            barang === '' ? chek += " barang tidak boleh kosong. " : '';
            jumlah === '' ? chek += " jumlah tidak boleh kosong. " : '';
            tgl_butuh === '' ? chek += " tanggal butuh tidak boleh kosong. " : '';
            tipe === '' ? chek += " tipe tidak boleh kosong. " : '';
            
            if (chek !== '') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: chek,
                    showConfirmButton: false,
                    timer: 1800
                });

                return;
            }

            //simpan tambah baru
            if (action === "baru") {
                var formData = $('#form1').serializeArray();
                console.log(formData);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var type = "POST";
                var ajaxurl = patch + 'store';

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(data) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Tambah Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        })

                        $('#form1').trigger("reset");
                        ChangeCari(data.id);

                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss Error !',
                            text: data.responseJSON.message
                        })
                        console.log('Error:', data);
                    }
                });
            }

            //simpan ubah data
            else if (action === "ubah") {
                var formData = $('#form1').serializeArray();
                console.log(formData);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var type = "PUT";
                var ajaxurl = patch + 'update/' + id;

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(data) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Tambah Berhasil!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#form1').trigger("reset");
                                ChangeCari(data.id);
                            }
                        });

                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss Error !',
                            text: 'Transaksi Gagal Tersimpan !'
                        })
                        console.log('Error:', data);
                    }
                });


            }
        }
    </script>

@endsection

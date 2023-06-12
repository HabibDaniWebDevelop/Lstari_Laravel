<?php
$title = 'SPK Percobaan Tanpa Karet';
$menu = '1';
?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">{{ $title }} </li>
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

                @include('R&D.Percobaan.SPKPercobaanTanpaKaret.data')

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik2" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik2"></div>
        <a class="dropdown-item" id="klikcetak"><span class="tf-icons bx bx-printer"></span>&nbsp;
            Cetak</a>
    </div>

@endsection

@section('script')

    <script>
        function ChangeCari(id) {
            $('#btn-menu .btn').prop('disabled', true);
            // $('#Cetak1').prop('disabled', false);
            $('#Batal1').prop('disabled', false);
            $('#Ubah1').prop('disabled', false);
            $("#tampil").removeClass('d-none');

            if (id == '0') {
                id = $('#cari').val();
            }
            $.get('/R&D/Percobaan/SPKPercobaanTanpaKaret/show/1/' + id, function(data) {
                $("#tampil").html(data);
                // $('#Cetak1').val(id);
            });
        }

        //paging
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            function fetch_data(page) {
                $.ajax({
                    url: "/R&D/Percobaan/SPKPercobaanTanpaKaret/show/1/0?page=" + page,
                    success: function(data) {
                        $("#tampil").html(data);
                    }
                });
            }
        });

        function Klik_Baru1() {
            $('#btn-menu .btn').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#cari').prop('readonly', true);
            $("#tampil").removeClass('d-none');

            $.get('/R&D/Percobaan/SPKPercobaanTanpaKaret/show/2/0', function(data) {
                $("#tampil").html(data);
            });
        }

        function Klik_Lihat1() {
            $.get('/R&D/Percobaan/SPKPercobaanTanpaKaret/show/1/0', function(data) {
                $("#tampil").removeClass('d-none');
                $("#tampil").html(data);
            });
        }

        function getproduct(event, row) {

            char = event.which || event.keyCode;
            if (char == 9 || char == 13) {

                var item = [];
                $('.form-check-input:checked').each(function(i, e) {
                    var id = $(this).val();
                    let dataitems = {
                        id: id,
                    }
                    item.push(dataitems);
                });

                if (item == '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Kadar Harus di isi !'
                    });
                } else {

                    var id = $('[data-index="' + row + '3"]').val();
                    // alert(id);

                    $.get('/R&D/Percobaan/SPKPercobaanTanpaKaret/show/4/' + id, function(data) {

                        if (data.success) {
                            $('[data-index="' + row + '2"]').val(data.Kategori);
                            $('[data-index="' + row + '3"]').val(data.SWProduk);
                            $('[data-index="' + row + '4"]').val('3');
                            $('[data-index="' + row + '5"]').val('1');
                            $('[data-index="' + row + '7"]').val(data.idprod);
                            $('[data-index="' + row + '6"]').val(data.kepala);
                            $('[data-index="' + row + '8"]').val(data.component);
                            $('[data-index="' + row + '9"]').val(data.mainan);
                            $('[data-index="' + row + '5"]').focus();
                            gettotal();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'Data tidak ditemukan !'
                            });
                        }
                    });

                }
            }
        }

        function gettotal() {
            // alert('totoal');
            totoal = 0;
            let rowCount = $('#tabel1 tbody tr').length;
            for (let i = 1; i <= rowCount; i++) {
                totoal = parseFloat(totoal) + parseFloat($('[data-index="' + i + '5"]').val());
            }
            $('#totalQty').val(totoal);
        }

        function Klik_Cetak1(id) {
            // alert('cetak'+id);
            // id = $('#Cetak1').val();
            window.open('/R&D/Percobaan/SPKPercobaanTanpaKaret/cetak?id=' + id, '_blank');
        }

        function Klik_Batal1() {
            location.reload();
        }

        // // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

        function add() {
            let rowCount = $('#tabel1 tbody tr').length;
            rowCount += 1;

            // Setup table row
            let trStart = '<tr class="baris" id="' + rowCount + '">';
            let cell1 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly value="' +
                rowCount + '" data-index="' + rowCount + '1"> </td>';
            let cell2 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index="' +
                rowCount + '2"> </td>';
            let cell3 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="SWProduk[]" data-index="' +
                rowCount + '3" onkeydown="getproduct(event, ' + rowCount +
                ')"> <input type="hidden" name="idprod[]" data-index="' +
                rowCount + '7"> </td>';
            let cell4 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="Qty[]" value="" data-index="' +
                rowCount + '4"> </td>';
            let cell5 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="Berat[]" value="" data-index="' +
                rowCount + '5" onchange="gettotal()" posisi-index="akhir"> </td>';
            let cell6 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index="' +
                rowCount + '6" > </td> ';
            let cell7 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index = "' +
            rowCount + '8"> </td> ';
            let cell8 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index = "' +
            rowCount + '9"> </td> ';
            let trEnd = '</tr>';
            let finalItem = "";
            let rowitem = finalItem.concat(trStart, cell1, cell2, cell3, cell4, cell5, cell6, cell7, cell8, trEnd);
            $("#tabel1 > tbody").append(rowitem);

            $posisi = "#tabel1 #" + rowCount + " input";
            $($posisi).on('contextmenu', function(e) {
                rightclik(this, e);
            });

            $($posisi).keydown(function(e) {
                var id = $(this).parent().parent().attr('id');

                tambahbaris(id);
            });

        }

        function klikhapus(id) {
            
            $("#" + id).remove();
            let rowCount = $('#tabel1 tbody tr').length;

            for (let i = 1; i <= rowCount + 1; i++) {

                if (i > id) {
                    newIndex = i - 1;
                } else {
                    newIndex = i;
                }

                console.log(i, newIndex);
                // alert(Index +' '+ newIndex)
                $('[data-index="' + i + '1"]').attr('value', newIndex);
                $('[data-index="' + i + '1"]').parent().parent().attr('id', newIndex);
                $('[data-index="' + i + '1"]').attr('data-index', newIndex + '1');
                $('[data-index="' + i + '2"]').attr('data-index', newIndex + '2');
                $('[data-index="' + i + '3"]').attr('data-index', newIndex + '3');
                $('[data-index="' + i + '4"]').attr('data-index', newIndex + '4');
                $('[data-index="' + i + '5"]').attr('data-index', newIndex + '5');
                $('[data-index="' + i + '6"]').attr('data-index', newIndex + '6');
                $('[data-index="' + i + '7"]').attr('data-index', newIndex + '7');
                $('[data-index="' + i + '8"]').attr('data-index', newIndex + '8');
                $('[data-index="' + i + '9"]').attr('data-index', newIndex + '9');
            }

        }

        function Klik_Simpan1() {

            var formData = $('#form1').serializeArray();
            var item = [];
            $('.form-check-input:checked').each(function(i, e) {
                var id = $(this).val();
                var sku = $(this).attr("data-sku");
                formData.push({
                    name: "item[]",
                    value: sku
                });
                item.push(id);
            });

            console.log(formData);

            idprod = '';
            formData.forEach(element => {
                if (element['name'] == 'idprod[]') {
                    idprod = idprod + element['value']
                }
            });

            //cheking apakah data sudah lengkap
            if (item == '' || idprod == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Kadar dan SKU Harus di isi !'
                });
                return; //to stop execution here..use return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var type = "POST";
            var ajaxurl = '/R&D/Percobaan/SPKPercobaanTanpaKaret/store';
            // alert(formData);

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Tambah Berhasil!',
                        text: 'Silahkan di cek Kembali'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form1').trigger("reset");
                            location.reload();
                        }
                    });

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: 'Transaksi Gagal Tersimpan !',
                        confirmButtonColor: "#913030"
                    })
                    console.log('Error:', data);
                }
            });
        }
    </script>

@endsection

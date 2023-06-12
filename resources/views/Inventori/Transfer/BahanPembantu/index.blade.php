<?php $title = 'Transfer Bahan Pembantu'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Inventori </li>
        <li class="breadcrumb-item">Transfer </li>
        <li class="breadcrumb-item active">BahanPembantu</li>
    </ol>
@endsection

@section('css')

    <style>
        #scrollToTopBtn {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .table-hover tbody tr:hover input.form-control {
            background-color: #f9fafb;
            border-color: #ccc;
            color: #697a8d;
        }
    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="min-height:calc(100vh - 242px);">

                @include('Inventori.Transfer.BahanPembantu.data')

            </div>
        </div>
    </div>

    {{-- ! tombol untuk mepali ke bagian atas halaman --}}
    <button id="scrollToTopBtn" class="btn btn-dark rounded-circle m-0 p-0"
        style="display: none; position: fixed; bottom: 10px; right: 5px; "><i class="fas fa-chevron-up"></i></button>

    {{-- ! baris baru untuk di clone --}}
    <table id="tambahbaris" class="d-none">
        <tr class="klik1">
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="urut[]" readonly value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="idm[]" readonly value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="ord[]" readonly value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="tgl[]" readonly value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="barang[]" value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="jumlah[]" value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="unit[]" value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="proses[]" value="">
            </td>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="keterangan[]" value=""> </td>
            <td class="m-0 p-0 bg-light">
                <div class="form-check d-flex justify-content-center">
                    <input type="checkbox" class="form-check-input" value="Y" checked="checked" name="pilih[]">
                </div>
            </td>
        </tr>
    </table>

    {{-- ! menu klik kanan --}}
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <input type="hidden" id="idklik">
        <a class="dropdown-item" id="klikhapus" onclick="klikhapus()"><span class="tf-icons bx bx-trash"></span>&nbsp;
            Hapus</a>
    </div>

@endsection

@section('script')

    <script>
        //patch lokasi modul
        var patch = '/Inventori/Transfer/BahanPembantu/';

        // jQuery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            //auto schrol up
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('#scrollToTopBtn').fadeIn();
                } else {
                    $('#scrollToTopBtn').fadeOut();
                }
            });

            //auto schrol up
            $('#scrollToTopBtn').click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
                return false;
            });

            //cek Akses Menu
            let Akses = $('#Akses').val();
            if(Akses == '2'){
                $("#btn_baru").addClass("d-none");
                $("#btn_ubah").addClass("d-none");
                $("#btn_simpan").addClass("d-none");
            }

        });

        //menjalankan get_form
        $(document).on('change', '#tipe', function() {
            let bagian = $('#bagian').val();
            let gudang = $('#gudang').val();
            let tipe = $('#tipe').val();

            if (!bagian || !gudang || !tipe) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Data Ada Yang Kosong / Belum Diisi !',
                    showConfirmButton: false,
                    timer: 1200
                });
                $('#tipe').val('');
                return;
            }

            let datas = {
                "no": '3',
                "idm": bagian,
                "ord": gudang,
            }

            $.ajax({
                url: patch + 'show',
                data: datas,
                beforeSend: function() {
                    $(".preloader").show();
                },
                success: function(data) {
                    // console.log(data);
                    $("#bagian").prop("disabled", true);
                    $("#tipe").prop("disabled", true);
                    $("#tabel1").html(data);
                },
                complete: function() {
                    $(".preloader").fadeOut();
                }
            });

            if (tipe == "I") {
                $('#InOut').val('Keluar');
            } else if (tipe == "R") {
                $('#InOut').val('Terima');
            }
        });

        //tombol arah di tabel
        $(document).on('keydown', '#tabel1 input', function(e) {

            if (e.which === 9 || e.which === 13 || e.which === 39 || e.which === 37 || e.which === 38 || e.which ===
                40) {

                var row = $(this).closest('tr').index();
                var col = $(this).closest('td').index();
                var lastRow = $('#tabel1 tbody tr:last-child').index();
                // console.log('Input is at row ' + row + ' and column ' + col);

                //tab, enter, kanan
                if (e.which === 9 || e.which === 13 || e.which === 39) {

                    // setting mentok
                    if (row == lastRow && col == 8) {
                        // newrow();
                        // return;
                    } else if (col == 8) {
                        row++;
                        col = 3;
                    }

                    e.preventDefault();
                    $('#tabel1 tbody tr').eq(row).find('td').eq(col + 1).find('input').focus();
                }

                //kiri
                else if (e.which === 37) {

                    // setting mentok
                    if (col == 4) {
                        row--;
                        col = 9;
                    }

                    e.preventDefault();
                    $('#tabel1 tbody tr').eq(row).find('td').eq(col - 1).find('input').focus();
                }

                //atas
                else if (e.which === 38) {
                    e.preventDefault();
                    $('#tabel1 tbody tr').eq(row - 1).find('td').eq(col).find('input').focus();
                }

                //bawah
                else if (e.which === 40) {

                    if (row == lastRow) {
                        // newrow();
                        // return;
                    }
                    e.preventDefault();
                    $('#tabel1 tbody tr').eq(row + 1).find('td').eq(col).find('input').focus();
                }
            }
        });

        //tambah baris
        function newrow() {
            // console.log('baris baru');
            //peng clone nan
            var clone = $("#tambahbaris tr").clone();
            //menambah baris
            $("#tabel1 tbody").append(clone);
            //memberi nomer urut dan fokus di baris berikutnya
            var lastRow = $('#tabel1 tbody tr:last-child').index();
            $("#tabel1 tr:last td:first input").val(lastRow + 1);
            $("#tabel1 tbody tr:last td:nth-child(5) input").focus();
        }

        //hapus baris
        function klikhapus() {
            var id = parseInt($("#idklik").val()) + 1;
            $('#tabel1 tr:eq(' + id + ')').remove();

            $("#tabel1 tr").each((i, elem) => {
                $('#tabel1 tr:eq(' + i + ') td:eq(0) input').val(i);
            })

        }

        function ChangeCari() {

            let cari = $('#cari').val();
            if (cari == '') {
                return;
            }

            let datas = {
                "no": '1',
                "id": cari,
            }

            $.ajax({
                url: patch + 'show',
                data: datas,
                beforeSend: function() {
                    // $(".preloader").show();
                },
                success: function(data) {
                    $('#btn-menu .btn').prop('disabled', true);
                    $('#btn_baru').prop('disabled', false);
                    $('#btn_ubah').prop('disabled', false);
                    $('#btn_batal').prop('disabled', false);
                    $('#btn_cetak').prop('disabled', false);
                    $('#btn_Posting').prop('disabled', false);

                    $("#tampil").html(data);
                    $('#gudang').prop('disabled', true);
                    // console.log($('#gudang').val());
                    let tipe = $('#tipe').val();
                    if (tipe == "I") {
                        $('#InOut').val('Keluar');
                    } else if (tipe == "R") {
                        $('#InOut').val('Terima');
                    }

                    //cek Akses Menu
                    let Akses = $('#Akses').val();
                    let Active = $('#Active').val();

                    if (Active == 'A' && Akses == '1') {
                        $("#btn_baru").removeClass("d-none");
                        $('#btn_ubah').removeClass('d-none');
                        $('#btn_simpan').removeClass('d-none');
                        $('#btn_Posting').removeClass('d-none');
                    }
                    else if (Active == 'A' && Akses == '2') {
                        $("#btn_baru").addClass("d-none");
                        $("#btn_ubah").addClass("d-none");
                        $("#btn_simpan").addClass("d-none");
                        $('#btn_Posting').removeClass('d-none');
                    }
                    else if (Active == 'P' && Akses == '1') {
                        $("#btn_baru").removeClass("d-none");
                        $('#btn_ubah').addClass('d-none');
                        $('#btn_simpan').addClass('d-none');
                        $('#btn_Posting').addClass('d-none');
                    }
                    else {
                        $("#btn_baru").addClass("d-none");
                        $('#btn_ubah').addClass('d-none');
                        $('#btn_simpan').addClass('d-none');
                        $('#btn_Posting').addClass('d-none');
                    } 
                },
                complete: function() {
                    // $(".preloader").fadeOut();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'error!!',
                        text: 'Kode Salah Atau Tidak Di temukan!!',
                    })
                    console.log(xhr.responseJSON.message);
                }
            });
        }

        function KlikUbah() {

            $('#btn-menu .btn').prop('disabled', true);
            $('#btn_simpan').prop('disabled', false);
            $('#btn_batal').prop('disabled', false);

            $('#cari').prop('disabled', true);
            $('#btn_simpan').val('ubah');

            // $('#bagian').removeAttr('disabled');
            // $('#tipe').removeAttr('disabled');
            $('#catatan').removeAttr('readonly');

            // $('input[name*=barang]').removeAttr('readonly');
            // $('input[name*=jumlah]').removeAttr('readonly');
            // $('input[name*=unit]').removeAttr('readonly');
            // $('input[name*=proses]').removeAttr('readonly');
            $('input[name*=keterangan]').removeAttr('readonly');
            $('input[name*=pilih]').removeAttr('disabled');

        }

        function klikBatal() {
            window.location.reload();
        }

        function KlikBaru() {

            $('#btn-menu .btn').prop('disabled', true);
            $('#btn_simpan').prop('disabled', false);
            $('#btn_batal').prop('disabled', false);

            $('#cari').prop('disabled', true);
            $('#btn_simpan').val('baru');

            let datas = {
                "no": '2',
                "id": '0',
            }

            $.ajax({
                url: patch + 'show',
                data: datas,
                success: function(data) {
                    // console.log(data);
                    $("#tampil").html(data);
                    newrow();
                    $('#bagian').removeAttr('disabled');
                    $('#tipe').removeAttr('disabled');
                    // $('#gudang').removeAttr('disabled');
                    $('#catatan').removeAttr('readonly');

                },
            });
        }

        function KlikSimpan() {

            var formData = $('#form1').serializeArray();

            let bagian = $('#bagian').val();
            let tipe = $('#tipe').val();
            let gudang = $('#gudang').val();
            let simpan = $('#btn_simpan').val();

            console.log(simpan);

            formData.push({
                name: 'bagian',
                value: bagian
            });
            formData.push({
                name: 'tipe',
                value: tipe
            });
            formData.push({
                name: 'gudang',
                value: gudang
            });

            if (simpan == 'baru') {
                method = "POST";
            } else if (simpan == 'ubah') {
                method = "PUT";
            }

            $.ajax({
                url: patch,
                method: method,
                data: formData,
                beforeSend: function() {
                    $(".preloader").show();
                },
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1200
                    });
                    $('#cari').val(data.id);
                    ChangeCari();

                },
                complete: function() {
                    $(".preloader").fadeOut();
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

        function KlikPosting() {

            var formData = $('#form1').serializeArray();
            let gudang = $('#gudang').val();

            formData.push({
                name: 'gudang',
                value: gudang
            });

            $.ajax({
                url: patch + "Posting",
                method: "POST",
                data: formData,
                beforeSend: function() {
                    $(".preloader").show();
                },
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1200
                    });
                    $('#cari').val(data.id);
                    ChangeCari();

                },
                complete: function() {
                    $(".preloader").fadeOut();
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

        function klikCetak() {
            let id = $('#cari').val();
            window.open(patch + 'cetak?id=' + id, '_blank');
        }
    </script>

@endsection

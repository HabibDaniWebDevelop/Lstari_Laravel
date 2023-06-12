<?php $title = 'Koreksi Material Request'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Inventori </li>
        <li class="breadcrumb-item">Material Request </li>
        <li class="breadcrumb-item active">Koreksi MR </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="min-height:calc(100vh - 242px);">
                @include('Inventori.MaterialRequest.KoreksiMR.data')
            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <input type="hidden" id="idklik">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="removeRow" onclick="klikhapus()"><span class="tf-icons bx bx-trash"></span>&nbsp;
            Hapus</a>
    </div>

    {{-- ! baris baru untuk di clone --}}
    <table id="tambahbaris" class="d-none">
        <tr class='klik' id='Row_'>
            <td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                    name="no[]" readonly value="">
            </td>
            <td class="m-0 p-0">
                <select class="form-control form-control-sm fs-6 my-select" name="barang[]" id="barang"
                    data-live-search="true">
                    <option value="0">-- Silahkan Pilih --</option>
                    @foreach ($barangStock as $item)
                        <option value="{{ $item->ID }}">{{ $item->ID }} - {{ $item->Description }}</option>
                    @endforeach
                </select>
            </td>
            <td class="m-0 p-0"><input type="number" class="form-control form-control-sm fs-6 w-100 border-0 text-center"
                    name="jumlah[]" value="">
            </td>
            <td class="m-0 p-0">
                <select name="unit[]" class="form-select form-select-sm fs-6 w-100 border-0 text-center">
                    @foreach ($satuan as $item)
                        <option value="{{ $item->ID }}">{{ $item->SW }}</option>
                    @endforeach
                </select>
            </td>

            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="keterangan[]"
                    value="">
            </td>
            <td class="m-0 p-0 d-none">
                <input type="text" name="location[]">
            </td>
            <td class="m-0 p-0 d-none">
                <input type="text" name="sw[]">
            </td>
            <td class="m-0 p-0 d-none">
                <input type="text" name="uom[]">
            </td>
        </tr>
    </table>
@endsection

@section('script')
    {{-- Bootstrap Select --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>

    <script>
        //patch lokasi modul
        var patch = '/Inventori/MaterialRequest/KoreksiMR/';

        // buat token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

        });

        //tombol arah di tabel
        $(document).on('keydown', '#tabel1 input, #tabel1 select', function(e) {
            if (e.which === 9 || e.which === 13 || e.which === 39 || e.which === 37 || e.which === 38 || e.which ===
                40) {

                var row = $(this).closest('tr').index();
                var col = $(this).closest('td').index();
                var lastRow = $('#tabel1 tbody tr:last-child').index();
                console.log('Input is at row ' + row + ' and column ' + col);

                //tab, enter, kanan
                if (e.which === 9 || e.which === 13 || e.which === 39) {
                    e.preventDefault();
                    if (col == 4) {
                        if (row == lastRow) {
                            newRow();
                        }
                        $('#tabel1 tbody tr').eq(row + 1).find('td').eq('2').find('input, select').focus();
                        return;
                    }

                    $('#tabel1 tbody tr').eq(row).find('td').eq(col + 1).find('input, select').focus();
                }

                //kiri
                else if (e.which === 37) {
                    e.preventDefault();
                    // setting mentok
                    if (col == 2) {
                        row--;
                        col = 5;
                    }

                    $('#tabel1 tbody tr').eq(row).find('td').eq(col - 1).find('input, select').focus();
                }

                //atas
                else if (e.which === 38) {
                    e.preventDefault();
                    $('#tabel1 tbody tr').eq(row - 1).find('td').eq(col).find('input, select').focus();
                }

                //bawah
                else if (e.which === 40) {

                    console.log();
                    e.preventDefault();
                    if (row == lastRow && col != 1) {
                        newRow();
                        $('#tabel1 tbody tr').eq(row + 1).find('td').eq('2').find('input, select').focus();
                        return;
                    }
                    $('#tabel1 tbody tr').eq(row + 1).find('td').eq(col).find('input, select').focus();
                }
            }
        });


        // $('.form-check-input').oninput(function(){
        //     var row = $(this).closest('tr').index();
        //     var value = $('.form-check-input').val();
        //     if (value === 'Y') {
        //         $('#ispurchase'+row).prop('checked', true);
        //     } else {
        //         $('#ispurchase'+row).prop('checked', false);
        //     }
        // });

        // var rowIndex = $("#myTable tr").filter(function() {
        //         return $(this).text().trim() === searchText;
        //     }).index();

        // mencari detail barang
        $(document).on("change", "select[name='barang[]']", function() {
            var barang = $(this).val();
            var index = $(this).closest('tr').index();

            $('[name="barang[]"]').selectpicker('toggle');

            console.log(index);

            let datas = {
                menu: 'detailbarang',
                id: barang,
            }

            $.ajax({
                url: patch + 'show',
                type: 'GET',
                data: datas,
                success: function(data) {
                    // console.log('tesss' + data.barang.Description);

                    $('#tabel1 tbody tr').eq(index).find('td').eq('2').find('input, select').val('1')
                        .change();
                    $('#tabel1 tbody tr').eq(index).find('td').eq('3').find('input, select').val(data
                        .barang.UID).change();
                    $('#tabel1 tbody tr').eq(index).find('td').eq('5').find('input, select').val(data
                    .location).change();
                    $('#tabel1 tbody tr').eq(index).find('td').eq('6').find('input, select').val(data.barang
                    .SW).change();
                    $('#tabel1 tbody tr').eq(index).find('td').eq('7').find('input, select').val(data.barang
                    .Unit).change();

                    $('#tabel1 tbody tr').eq(index).find('td').eq('2').find('input, select').focus().select();
                    $('#tabel1 tbody tr').eq(index).find('td').eq('3').find('input, select').prop('disabled', true);

                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Barang Tidak Di temukan...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            });
        });


        //pencarian data
        var cekdoblecari = true;
        $('#cari').on('keydown change', function(e) {

            console.log('Event: ' + e.type + ', Key code: ' + e.keyCode);

            if ((e.keyCode === 13 || e.type === 'change') && $(this).val() != '' && cekdoblecari) {
                var value = $(this).val();
                cekdoblecari = false;

                let datas = {
                    menu: 'lihat',
                    id: value,
                }

                $.ajax({
                    url: patch + 'show',
                    type: 'GET',
                    data: datas,
                    success: function(data) {
                        $("#tampil").html(data);
                        $('#btn_cetak').val(value);
                        $('#btn_ubah').val(value);
                        cekdoblecari = true;

                        if ($('#Active').val() == 'P' || $('#Active').val() == 'C') {
                            //$('#btn_ubah').addClass('d-none');
                            //$('#btn_simpan').addClass('d-none');
                            // $('#btn_Posting').addClass('d-none');

                        } else {
                            $('#btn_ubah').removeClass('d-none');
                            $('#btn_simpan').removeClass('d-none');
                            // $('#btn_Posting').removeClass('d-none');
                        }
                     

                        $('#btn-menu .btn').prop('disabled', true);
                        $('#btn_ubah').prop('disabled', false);
                        $('#btn_batal').prop('disabled', false);
                        $('.form-check-input').prop('disabled', false);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message
                        })
                        cekdoblecari = true;
                        return;
                    }
                });
            }

        });

        //memunculkan  menuklik
        $(document).on('contextmenu', '#tabel1 tbody tr', function(e) {
            e.preventDefault();
            var index = $(this).closest('tr').index();
            $('#idklik').val(index);
            var top = e.pageY + 15;
            var left = e.pageX - 100;
            $("#judulklik").html(index + 1);

            $(this).css('background-color', '#f4f5f7');
            $("#menuklik").css({
                display: "block",
                top: top,
                left: left
            });

        });

        // menyembunyikan menuklik
        $(document).on('click', function() {
            $(" #menuklik ").hide();
        });



        // Function for klik baru
        function KlikBaru() {

            let datas = {
                menu: 'baru'
            }

            $.ajax({
                url: patch + 'show',
                type: 'GET',
                data: datas,
                success: function(data) {

                    // Empty row in tbody
                    $("#tabel1 tbody").empty()
                    $("#tampil").html(data);
                    $('#btn_simpan').val('baru');

                    //menambah baris
                    var clone = $("#tambahbaris tr").clone();
                    $("#tabel1 tbody").append(clone);
                    var lastRow = $('#tabel1 tbody tr:last-child').index();
                    $("#tabel1 tr:last td:first input").val(lastRow + 1);

                    $('.my-select').selectpicker();

                    $('#btn-menu .btn').prop('disabled', true);
                    $('#btn_simpan').prop('disabled', false);
                    $('#btn_batal').prop('disabled', false);

                    $('#tgl_masuk').prop('readonly', false)
                    $('#catatan').prop('readonly', false);
                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message
                    })
                    return;
                }
            });

        }

        // fungsi untuk batal dan reload halaman
        function klikBatal() {
            history.replaceState({}, document.title, window.location.pathname);
            location.reload();
        }

        //fungsi untuk ubah data yang sudah ada
        function KlikUbah() {
            // console.log('klik ubah');
            $('#btn_simpan').val('ubah');
            $('#btn-menu .btn').prop('disabled', true);
            $('#btn_simpan').prop('disabled', false);
            $('#btn_batal').prop('disabled', false);

           // $('#tgl_masuk').prop('readonly', false)
           // $('#catatan').prop('readonly', false)
            //$('select[name*=barang]').prop('disabled', false);
            //$('input[name*=jumlah]').prop('readonly', false)
            //$('input[name*=keterangan]').prop('readonly', false)
            //$('.my-select').selectpicker();
        }

        // fungsi untuk cetak
        function klikCetak() {
            // Return print page
            let cari = $('#btn_cetak').val()
            // Make request
            window.open('/Inventori/MaterialRequest/BahanPembantu/cetak?id=' + cari, '_blank');
        }

        // fungsi untuk tambah baris
        function newRow() {

            $('.my-select').selectpicker('destroy')
            //menambah baris
            var clone = $("#tambahbaris tr").clone();
            $("#tabel1 tbody").append(clone);
            var lastRow = $('#tabel1 tbody tr:last-child').index();
            $("#tabel1 tr:last td:first input").val(lastRow + 1);

            $('.my-select').selectpicker()
        }

        //hapus baris
        function klikhapus() {
            var id = parseInt($("#idklik").val());
            $('#tabel1 tbody tr:eq(' + id + ')').remove();

            $("#tabel1 tr").each((i, elem) => {
                $('#tabel1 tr:eq(' + i + ') td:eq(0) input').val(i);
            })

        }

        //Simpan ke database
        function KlikSimpan() {

            // cek apa ada input yang kososng
            var emptyInputs = $('#tabel1 [name="barang[]"]').filter(function() {
                return $(this).val() == 0;
            });

            var emptyInputs2 = $('#tabel1 [name="jumlah[]"]').filter(function() {
                return $(this).val() == '' || $(this).val() == 0;
            });

            if (emptyInputs.length || emptyInputs2.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Ada Barang yang kosong atau jumlah tidak boleh 0 tolong di isi atau di hapus yang kosong",
                })
                return;
            }

            let action = $('#btn_simpan').val();
            let hasilid = $('#hasilid').val();

            $('select[name*=unit]').prop('disabled', false);
            var formData = $('#form1').serializeArray();
            $('select[name*=unit]').prop('disabled', true);


            if (action == 'baru') {
                method = "POST";
            } else if (action == 'ubah') {
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
                        title: 'Success',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1200
                    });
                    $('#cari').val(data.id).change();
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
    </script>
@endsection

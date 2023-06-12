<?php $title = 'Data Storage'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">IT </li>
        <li class="breadcrumb-item active">DataStorage </li>
    </ol>
@endsection

@section('css')

    <style>


    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                @include('IT.MasterDataHardware.DataStorage.data')
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true
        });

        // -------------------- klik di tabel --------------------
        $(".klik").on('click', function(e) {
            // $('.klik').css('background-color', 'white');
            var id = $(this).attr('id');
            if ($(this).hasClass('table-secondary')) {
                $(this).removeClass('table-secondary');
                $('#cek_' + id).attr('checked', false);
            } else {
                $(this).addClass('table-secondary');
                $('#cek_' + id).attr('checked', true);
            }
            return false;
        });

        function kliktambah(id) {

            var formData = $('#form1').serialize();
            alert(formData);


            // $("#jodulmodal1").html('Form Tambah Data Printer');
            // $('#modalformat').attr('class', 'modal-dialog modal-lg');
            // $("#simpan1").removeClass('d-none');
            // $('#simpan1').val('Tambah');

            // $.get('/IT/MasterDataHardware/DataStorage/create/', function(data) {
            //     $("#modal1").html(data);
            //     $('#modalinfo').modal('show');
            // });
        }

        function klikedit(id) {
            $("#jodulmodal1").html('Form Edit Data PC');
            $('#modalformat').attr('class', 'modal-dialog modal-lg');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Edit');

            $.get(`/IT/MasterDataHardware/DataPrinter/${id}/edit`, function(data) {
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        }

        function klikcetak(id) {
            window.open('/IT/MasterDataHardware/DataPrinter/cetak?id=' + id, '_blank');
        }

        function klikCari() {
            if (event.keyCode === 13) {
                var id = $('#cari').val();
                window.location.replace('/IT/MasterDataHardware/DataStorage/search?id=' + id);
            }
        }

        // klik simpan pada modal untuk tambah data dan edit data
        function KlikSimpan1() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id = $('#ID').val();
            var operation = $('#simpan1').val();
            var formData = $('#formmodal1').serialize();

            alert(formData);
        }
    </script>

@endsection

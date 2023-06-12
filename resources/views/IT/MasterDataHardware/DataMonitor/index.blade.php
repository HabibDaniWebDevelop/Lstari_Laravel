<?php $title = 'Data Monitor'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">IT </li>
        <li class="breadcrumb-item active">DataMonitor </li>
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

                @include('IT.MasterDataHardware.DataMonitor.data')

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
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true
        });

        function kliktambah(id) {
            $("#jodulmodal1").html('Form Tambah Data Printer');
            $('#modalformat').attr('class', 'modal-dialog modal-lg');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Tambah');

            $.get('DataPrinter/create/', function(data) {
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        }

        function klikedit(id) {
            $("#jodulmodal1").html('Form Edit Data PC');
            $('#modalformat').attr('class', 'modal-dialog modal-lg');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Edit');

            $.get(`DataPrinter/${id}/edit`, function(data) {
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        }

        function klikcetak(id) {
            window.open('DataPrinter/cetak?id=' + id, '_blank');
        }

        function klikCari() {
            if (event.keyCode === 13) {
                var id = $('#cari').val();
                window.location.replace('/IT/MasterDataHardware/DataMonitor/search?id=' + id);
            }
        }
    </script>

@endsection

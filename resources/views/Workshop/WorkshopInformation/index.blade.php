<?php $title = 'Informasi Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">WorkshopInformation</li>
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

                @include('Workshop.WorkshopInformation.data')

            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>

        function Klik_Cari1() {

            var tgl_awal = $('#tgl_mulai').val();
            var tgl_akhir = $('#tgl_akhir').val();
            var jenis = $('#jenis').val();
            data = {
                tgl_awal: tgl_awal,
                tgl_akhir: tgl_akhir,
                jenis: jenis
            };

            // alert(jenis);

            if (tgl_awal == '' && tgl_akhir == '' || jenis == null) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Informasi..!',
                    text: 'Transaksi Gagal Tersimpan !',
                    text: "Ada Data Yang Belum Diisi/Dipilih."
                })
            } else {
                $("#tampil").removeClass('d-none');
                $.get('/Workshop/WorkshopInformation/show/' + tgl_awal + '/' + tgl_akhir + '/' + jenis, function(data) {
                    $("#tampil").html(data);
                });
            }

        }
    </script>

@endsection

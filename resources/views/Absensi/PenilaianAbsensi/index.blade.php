<?php $title = 'Data Penilaian Absensi'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Penilaian Absensi </li>
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

                @include('Absensi.PenilaianAbsensi.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function SearchPenilaianAbsensi() {
            let tanggal_awal = $('#tanggal_awal').val()
            let tanggal_akhir = $('#tanggal_akhir').val()
            let department = $('#department').val()
            if (tanggal_awal == "" || tanggal_akhir == "" || department == "") {
                return;
            }
            window.open('/Absensi/PenilaianAbsensi/search?tgl_awal='+tanggal_awal+'&tgl_akhir='+tanggal_akhir+'&department='+department, '_blank');
        }
    </script>
@endsection
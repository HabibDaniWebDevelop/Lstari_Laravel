<?php $title = 'Informasi Face Absent'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Informasi Face Absent </li>
    </ol>
@endsection

@section('css')
    
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Absensi.Informasi.InformasiFaceAbsent.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#tableSudahTerdaftar').DataTable({});
        $('#tableBelumTerdaftar').DataTable({});
    </script>
@endsection
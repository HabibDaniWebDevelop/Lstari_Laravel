<?php $title = 'Percobaan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">SPK Percobaan </li>
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
                @include('R&D.Percobaan.SPKPercobaan.data')
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik">
        <a class="dropdown-item" onclick="klikedit2()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" onclick="klikcetak2()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
        <a class="dropdown-item" onclick="klikinfo2()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a>
    </div>
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')

    <script>        
    </script>

@endsection

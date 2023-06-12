<?php $title = 'Informasi BoM'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Bill of Material </li>
        <li class="breadcrumb-item active">BoM</li>
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
                @include('R&D.Informasi.InformasiProduk.data')
            </div>
        </div>
    </div>
@endsection

@section('script')

@include('layouts.backend-Theme-3.DataTabelButton')


@endsection
<?php $title = 'Lab Turun Kadar'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Laboratorium </li>
        <li class="breadcrumb-item active">Lab Turun Kadar </li>
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
                @include('Produksi.Laboratorium.LabTurunKadar.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
{{-- sheetjs --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/sheetjs/xlsx.full.min.js') !!}"></script>
{{-- This File JS --}}
<script src="{!! asset('scripts/LabTurunKadar/index.js') !!}"></script>
@endsection
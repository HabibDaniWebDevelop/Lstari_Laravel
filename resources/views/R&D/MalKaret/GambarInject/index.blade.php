<?php $title = 'SPKO 3DP Direct Casting'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R & D </li>
    <li class="breadcrumb-item">Mal Karet</li>
    <li class="breadcrumb-item active">Upload Gambar Inject Lilin</li>
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
            @include('R&D.MalKaret.GambarInject.data')
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript" src="instascan.min.js"></script>
@endsection
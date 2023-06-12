<?php $title = 'Permintaan Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R & D </li>
    <li class="breadcrumb-item">Difisi Lilin </li>
    <li class="breadcrumb-item active">Permintaan Karet</li>
</ol>
@endsection

@section('container')
    <div class="row">
         <div class="col-md-12">
             <div class="card mb-4">

                @include('R&D.DivisiLilin.PermintaanKaret.data')
                
            </div>
        </div>
    </div>
    
   
@endsection
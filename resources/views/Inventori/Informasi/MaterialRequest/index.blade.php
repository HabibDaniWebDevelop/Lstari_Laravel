<?php $title = 'Informasi Permintaan Barang'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Invebtory </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">MaterialRequest</li>
    </ol>
@endsection

@section('css')

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0" style="min-height:calc(100vh - 242px);">
                @include('Inventori.Informasi.MaterialRequest.data')

            </div>
        </div>
    </div>
@endsection

@section('script')
  

    <script>
        function Klik_btn(btnIndex) {
            $(".tombol-group button").removeClass("btn-primary").addClass("btn-secondary");
            $("#btn" + btnIndex).removeClass("btn-secondary").addClass("btn-primary");
            // alert("Tombol " + $("#btn" + btnIndex).text() + " diklik!");

            var tgl_mulai = $('#tgl_mulai').val();
            var tgl_akhir = $('#tgl_akhir').val();   
            var depart = $('#Department').val();   

            var data = {tgl1 : tgl_mulai, tgl2 : tgl_akhir, depart: depart, id: btnIndex};
            // $.get('/Inventori/Informasi/MaterialRequest/show/',data, function(data) {
            //     $("#tampil").html(data);
            // });

            $.ajax({
                url: '/Inventori/Informasi/MaterialRequest/show/',
                type: 'GET',
                data: data,
                beforeSend: function() {
                    $(".preloader").show();
                },
                success: function(data) {
                    $("#tampil").html(data);
                },
                complete: function() {
                    $(".preloader").fadeOut();
                },
            });
        }
     
    </script>


@endsection

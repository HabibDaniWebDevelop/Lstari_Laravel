<?php $title = 'WIP Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">WIP Workshop </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    {{-- Lightbox.js --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/css/lightbox.min.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Workshop.WIPWorkshop.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
{{-- Lightbox.js --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/js/lightbox.min.js') !!}"></script>

    <script>
        // Data Table Settings
        $('#tabel1').DataTable({
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
        });

        function previewFunction(idProduct) {
            $.ajax({
                type: "GET",
                url: "/Workshop/WIPWorkshop/preview/"+idProduct,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    console.log(data);
                    
                    // Set modalBody
                    $('#judulModal').html(data.data.namaProduct)
                    $('#modalBody').empty();
                    $('#modalBody').html(data.data.dataHTML)
                    $('#modalinfo').modal('show');
                    return
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return
                }
            })
        }

        function verifiedWIP(idWIP) {
            $.ajax({
                type: "GET",
                url: "/Workshop/WIPWorkshop/verified/"+idWIP,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $("#verification_"+idWIP).remove()
                    Swal.fire({
                        icon: 'success',
                        title: 'Verified',
                        text: 'Gambar Berhasil Terverifikasi',
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    }).then(function () {
                        window.location.reload()
                    })
                    return
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return
                }
            })
        }

    </script>
@endsection
<?php $title = 'Verifikasi Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">Verifikasi Workshop </li>
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

                @include('Workshop.VerifikasiWorkshop.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
{{-- Lightbox.js --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/js/lightbox.min.js') !!}"></script>

    <script>
        localStorage.clear();
        // Data Table Settings
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true
        });

        // Function for search surat jalan
        function cariSPKPCB() {
            // Get no spk from spkPCB
            let spkPCB = $('#spkPCB').val()
            // Check if value is blank
            if (spkPCB === ""){
                // return aleart when it blank
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "spkPCB can't be blank",
                })
                return;
            }

            let data = {spkPCB:spkPCB}

            // Make request
            $.ajax({
                type: "GET",
                url: "/Workshop/VerifikasiWorkshop/getSPK",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    let item = {data:data.data.return, swspk:spkPCB}
                    localStorage.clear();
                    localStorage.setItem("dataItem", JSON.stringify(item));
                    console.log(data.data.return);
                    // Set Hidden idLemburKerja value
                    
                    // Destroy datatable;
                    $("#tabel1").dataTable().fnDestroy()

                    // Set TableItems
                    $('#spkItems').empty();
                    $('#spkItems').html(data.data.returnHTML)

                    $('#tabel1').DataTable({
                        paging: false,
                        searching: false,
                        ordering: false,
                        info: false,
                    });
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

        function simpanWIP() {
            // Get items from localstorage
            let dataitems = JSON.parse(localStorage.dataItem)

            data = {swSPK:dataitems.swspk, items:dataitems.data}

            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Hit Backend
            $.ajax({
                type: "POST",
                url: "/Workshop/VerifikasiWorkshop",
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: "Data Berhasil Tersimpan.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    
                    return;
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            })
        }
    </script>
@endsection
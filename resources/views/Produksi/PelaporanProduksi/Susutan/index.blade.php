<?php $title = 'Susutan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Pelaporan Produksi </li>
        <li class="breadcrumb-item active">Susutan</li>
    </ol>
@endsection

@section('css')
    <style>
        .container-fluid{
            padding: 0px !important;
            padding-left: 10px !important;
            padding-right: 15px !important;
        }
        
    </style>
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('Produksi.PelaporanProduksi.Susutan.data')
            </div>
        </div>
    </div>
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')

    <script>

            function openModal() {
                $(".preloader").fadeIn(300);
            }

            function closeModal() {
                $(".preloader").fadeOut(300);
            }

            function klikBatal() {
                location.reload();
            }

            function klikClear() {
                document.getElementById("idcari").value = '';
                document.getElementById("idcari").focus();
            }

            function klikLihat() {

                var id = $('#idcari').val();
                var data = {id: id};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/Susutan/lihat',
                    beforeSend: function() {
                        openModal();
                    },
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {
                        $("#tampil").html(data.html);
                        var table = $('#tampiltabel').DataTable({
                            ordering: false,
                            paging: false,
                            pageLength: 100,
                            searching: false,
                            lengthChange: false,
                            scrollX: true,
                            scroller: true,
                        });
                        document.getElementById("btncetak").disabled = false;
                        document.getElementById("btnsimpan").disabled = true;
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })
                        return;
                    }
                });

            }

            function klikLihatNext(id) {

                var id = id;
                var data = {id: id};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/Susutan/lihat',
                    beforeSend: function() {
                        openModal();
                    },
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {
                        $("#tampil").html(data.html);
                        var table = $('#tampiltabel').DataTable({
                            ordering: false,
                            paging: false,
                            pageLength: 100,
                            searching: false,
                            lengthChange: false,
                            scrollX: true,
                            scroller: true,
                        });
                        document.getElementById("btnlihat").disabled = false;
                        document.getElementById("btncetak").disabled = false;
                        document.getElementById("btnsimpan").disabled = true;
                        $("#idcari").val(id);
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })
                        return; 
                    }
                });

            }

            function klikBaru() {

                if ($("#inputspko").css('display') == 'block') {
                    $("#inputspko").hide();
                } else {
                    document.getElementById('inputspko').style.display = 'block';
                    document.getElementById("inputspko").value = '';
                    document.getElementById("inputspko").focus();
                }

            }

            function baru(spko) {

                var explode = spko.split("-");
                var id = explode[0];
                var data = {id: id};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/Susutan/baru',
                    beforeSend: function() {
                        openModal();
                    },
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {

                        $("#tampil").html(data.html);
                        var table = $('#tampiltabel').DataTable({
                            ordering: false,
                            paging: false,
                            pageLength: 100,
                            searching: false,
                            lengthChange: false,
                            scrollCollapse: true,
                            scrollX: true,
                        });
                        document.getElementById('inputspko').style.display = 'none';
                        document.getElementById("btnsimpan").disabled = false;
                        document.getElementById("btnlihat").disabled = true;
                        document.getElementById("btncetak").disabled = true;
                        
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        if(xhr.responseJSON.jenisFunction == 'baru'){
                            document.getElementById("inputspko").value = '';
                            document.getElementById("inputspko").focus();
                        }

                        let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })
                        return; 
                    }
                });
            }

            function simpan() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/Susutan/simpan',
                    beforeSend: function() {
                        openModal();
                    },
                    data: $('#tampilform').serialize(),
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {
                        document.getElementById("btnsimpan").disabled = true;
                        klikLihatNext(data.id);
                        console.log(data.id);
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })
                        return; 
                    }
                });
                
            }

            function simpanTest() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/Susutan/simpanTest',
                    beforeSend: function() {
                        openModal();
                    },
                    data: $('#tampilform').serialize(),
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {
                        console.log(data);
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })
                        return; 
                    }
                });

            }

            function klikCetak() {
                var id = $("#nospko").val();

                var dataUrl = `/Produksi/PelaporanProduksi/Susutan/cetak?id=${id}`;
                window.open(dataUrl, '_blank');
            }

    </script>
    
@endsection
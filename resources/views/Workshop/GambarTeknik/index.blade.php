<?php $title = 'Gambar Teknik Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">Gambar Teknik Workshop </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Workshop.GambarTeknik.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- Bootstrap Select --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
    <script>
        localStorage.clear();

        function KlikBaru() {
            // Getting idGambarTeknik from hidden input
            let idGambarTeknik = $('#idGambarTeknik').val()
            if (idGambarTeknik != "") {
                // If idGambarTeknik have value. It will reload th page
                window.location.reload()
                return;
            }
            // Disable button "Baru, Ubah, and Posting"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_posting").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',true)
            $("#btn_batal").prop('disabled',false)
            // Disabled Cetak
            $("#btn_cetak").prop('disabled',true)
            // Enable input
            $("#jenisMatras").prop('disabled',false)
            $("#ukuranMatras").prop('disabled',false)
            $("#jumlahMatras").prop('disabled',false)
            $("#jumlahItemMatras").prop('disabled',false)
            $("#pakaiPisau").prop('disabled',false)
            return
        }

        function klikBatal() {
            window.location.reload()
        }

        function GenerateForm() {
            let jenisMatras = $('#jenisMatras').val()
            let jumlahMatras = $('#jumlahMatras').val()
            let jumlahItemMatras = $('#jumlahItemMatras').val()
            
            // Check if jumlah matras is still blank
            if (jumlahMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "jumlah Matras Harus di isi.",
                })
                return;
            }

            // Check if jumlah item matras is still blank
            if (jumlahItemMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "jumlah item Matras Harus di isi.",
                })
                return;
            }

            // Ok cheecckkk..
            let data = {
                jenisMatras:jenisMatras,
                jumlahMatras:jumlahMatras,
                jumlahItemMatras:jumlahItemMatras
            }
            
            // Ajax Request
            $.ajax({
                url: '/Workshop/GambarTeknik/generate-form',
                type: 'get',
                data:data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    localStorage.clear();
                    localStorage.setItem("rawMaterial", JSON.stringify(data.data.rawMaterial));
                    $('#GeneratedForm').empty()
                    $('#GeneratedForm').append(data.data.layout)
                    
                    $("#btn_simpan").prop('disabled',false)
                    $("#jenisMatras").prop('disabled',true)
                    $("#jumlahMatras").prop('disabled',true)
                    $("#jumlahItemMatras").prop('disabled',true)
                    $('#generateForm').prop('disabled',true)
                    return
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
            });
        }

        function KlikCari() {
            let idMasterGambarTeknik = $('#cari').val()
            $.ajax({
                url: '/Workshop/GambarTeknik/search',
                type: 'get',
                data:{idGambarTeknik:idMasterGambarTeknik},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    localStorage.clear();
                    localStorage.setItem("rawMaterial", JSON.stringify(data.data.rawMaterial));

                    // Set id
                    $('#idGambarTeknik').val(data.data.MasterGambarTeknik.ID)

                    // Switch jenisMatras
                    $('#jenisMatras').val(data.data.MasterGambarTeknik.JenisMatras).change()
                    $('#ukuranMatras').val('0').change()
                    $('#jumlahMatras').val(data.data.MasterGambarTeknik.JumlahMatras)
                    $('#jumlahItemMatras').val(data.data.MasterGambarTeknik.JumlahItemMatras)
                    $('#pakaiPisau').val('0').change()

                    $('#GeneratedForm').empty()
                    $('#GeneratedForm').append(data.data.layout)

                    $('#generateForm').prop('disabled',true)

                    if (data.data.MasterGambarTeknik.Active == 'P') {
                        $("#btn_baru").prop('disabled',false)
                        $("#btn_ubah").prop('disabled',true)
                        $("#btn_posting").prop('disabled',true)
                        $("#btn_simpan").prop('disabled',true)
                        $("#btn_batal").prop('disabled',true)
                        $("#btn_cetak").prop('disabled',false)
                        $('#postingStatus').val(data.data.MasterGambarTeknik.Active)
                        $('#action').val('ubah')
                    } else {
                        $("#btn_baru").prop('disabled',false)
                        $("#btn_ubah").prop('disabled',false)
                        $("#btn_posting").prop('disabled',false)
                        $("#btn_simpan").prop('disabled',true)
                        $("#btn_batal").prop('disabled',true)
                        $("#btn_cetak").prop('disabled',true)
                        $('#postingStatus').val(data.data.MasterGambarTeknik.Active)
                        $('#action').val('ubah')
                    }
                    return
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
            });
        }

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan') {
                saveGambarTeknik()
                return
            } else if(action == 'ubah'){
                UpdateGambarTeknik()
                return
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Invalid Action",
                })
                return;
            }
        }

        function KlikUbah() {
            // Disable button "Baru, Ubah, and Posting"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_posting").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Disabled Cetak
            $("#btn_cetak").prop('disabled',true)

            // Enable Input
            $('.product').prop('disabled',false)
            $('.tipeMatras').prop('disabled',false)
            $('.fotoGambarTeknik').prop('disabled',false)
            $('.namaBahan').prop('disabled',false)
            $('.qtyBahan').prop('disabled',false)
            $('.btn_add_row').prop('disabled',false)
            $('.btn_remove').prop('disabled',false)
        }

        function KlikPosting() {
            let idGambarTeknik = $('#idGambarTeknik').val()
            if (idGambarTeknik == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Gambar Teknik Masih Belum Terpilih",
                })
                return;
            }

            let postingStatus = $('#postingStatus').val()
            if (postingStatus != 'A') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Gambar Teknik Sudah Diposting",
                })
                return;
            }

            let data = {idGambarTeknik:idGambarTeknik}

            $.ajax({
                url: '/Workshop/GambarTeknik/posting',
                type: 'get',
                data:data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#postingStatus').val('P')

                    
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',true)
                    $("#btn_posting").prop('disabled',true)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    $("#btn_cetak").prop('disabled',false)
                
                    return
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
            });

        }

        function klikCetak() {
            let idGambarTeknik = $('#idGambarTeknik').val()
            if (idGambarTeknik == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Gambar Teknik Belum Terpilih",
                })
                return;
            }
            window.open('/Workshop/GambarTeknik/cetak?idGambarTeknik='+idGambarTeknik, '_blank');
            return
        }

    </script>

@endsection
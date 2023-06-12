<?php $title = 'NTHKO Matras Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">NTHKO Matras Workshop </li>
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

                @include('Workshop.NTHKO.Matras.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function KlikBaru() {
            // Getting idNTHKOWorkshop from hidden input
            let idNTHKOWorkshop = $('#idNTHKOWorkshop').val()
            if (idNTHKOWorkshop != "") {
                // If idNTHKOWorkshop have value. It will reload th page
                window.location.reload()
                return;
            }
            // Disable button "Baru  & Ubah"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Disabled Cetak
            $("#btn_cetak").prop('disabled',true)
            // Enable input
            $("#idSPKOMatras").prop('disabled',false)
            $("#hasil").prop('disabled',false)
            return
            
        }

        function klikBatal() {
            window.location.reload();
        }

        function KlikUbah() {
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Disabled Cetak
            $("#btn_cetak").prop('disabled',true)
            // Enable input
            $("#hasil").prop('disabled',false)
        }

        function SearchSPKO() {
            let idSPKOMatras = $('#idSPKOMatras').val()
            if (idSPKOMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idSPKOMatras tidak boleh kosong",
                })
                return;
            }

            $.ajax({
                url: '/Workshop/NTHKO/Matras/getSPKO',
                type: 'GET',
                data:{idSPKOMatras:idSPKOMatras},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Input
                    $("#employee").val(data.data.matrasAllocation.Employee).change();
                    
                    // Set Items
                    $('#tabelItems tbody > tr').remove();
                    $("#tabelItems > tbody").append(data.data.itemsHTML);

                },
                error: function(xhr){
                    $('#idSPKO').val("")
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
            // Get action
            let action = $('#action').val()
            if (action == "simpan") {
                saveNTHKO()
                return
            } else if (action == "ubah") {
                updateNTHKO()
                return
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action Invalid",
                })
                return;
            }
        }

        function saveNTHKO() {
            let idSPKOMatras = $('#idSPKOMatras').val()
            let hasilNTHKO = $('#hasil').val()

            let data = {
                idSPKOMatras:idSPKOMatras,
                hasilNTHKO:hasilNTHKO
            }
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/Workshop/NTHKO/Matras',
                type: 'POST',
                data:data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hiddem Input
                    $('#idNTHKOWorkshop').val(data.data.id)
                    $('#action').val('ubah')

                    // Disable button "Baru  & Ubah"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Enable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#idSPKOMatras").prop('disabled',true)
                    $("#hasil").prop('disabled',true)
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

        function updateNTHKO() {
            let idNTHKOWorkshop = $('#idNTHKOWorkshop').val()
            let hasilNTHKO = $('#hasil').val()

            // Check if idNTHKOWorkshop is already set
            if (idNTHKOWorkshop == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "NTHKO Matras Masih Belum Terpilih",
                })
                return;
            }
            if (!["GOOD", "NO GOOD"].includes(hasilNTHKO)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Invalid Hasil NTHKO Matras",
                })
                return;
            }

            let data = {
                idNTHKOWorkshop:idNTHKOWorkshop,
                hasilNTHKO:hasilNTHKO
            }

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/Workshop/NTHKO/Matras',
                type: 'PUT',
                data:data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Disable button "Baru  & Ubah"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Enable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#idSPKO").prop('disabled',true)
                    $("#hasil").prop('disabled',true)
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
            let keyword = $('#cari').val()
            if (keyword == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Kolom Cari Tidak Boleh Kosong",
                })
                return;
            }

            $.ajax({
                url: '/Workshop/NTHKO/Matras/search',
                type: 'get',
                data:{idNTHKOMatras:keyword},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // // Set Hiddem Input
                    $('#idNTHKOWorkshop').val(data.data.matrasCompletion.ID)
                    $('#action').val('ubah')

                    $('#idSPKOMatras').val(data.data.matrasCompletion.IDMatrasAllocation)
                    $('#employee').val(data.data.matrasCompletion.Employee).change()
                    $('#tanggalNow').val(data.data.TransDate)
                    
                    // Set Items
                    $('#tabelItems tbody > tr').remove();
                    $("#tabelItems > tbody").append(data.data.itemsHTML);



                    // Disable button "Baru  & Ubah"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Enable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#idSPKOMatras").prop('disabled',true)
                    $("#hasil").prop('disabled',true)
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
            let idNTHKOMatras = $('#idNTHKOWorkshop').val()
            if (idNTHKOMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "NTHKO belum terpilih",
                })
                return;
            }
            window.open('/Workshop/NTHKO/Matras/cetak?idNTHKOMatras='+idNTHKOMatras, '_blank');
            return
        }

    </script>
@endsection
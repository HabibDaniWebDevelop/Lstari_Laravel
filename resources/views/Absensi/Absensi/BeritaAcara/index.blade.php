<?php $title = 'Berita Acara'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Berita Acara </li>
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

                @include('Absensi.Absensi.Beritaacara.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Function for klik baru
        function KlikBaru() {
            // Getting idBeritaAcara from hidden input
            let idBeritaAcara = $('#idBeritaAcara').val()
            // Check if idBeritaAcara have value
            if (idBeritaAcara != "") {
                // If idBeritaAcara have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Set Button
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            
            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggal").prop('disabled',false)
            $("#keperluan").prop('disabled',false)
            $("#jenis").prop('disabled',false)
            $("#keterangan").prop('disabled',false)
            $("#solusi").prop('disabled',false)
        }

        function klikBatal() {
            window.location.reload()
        }

        function KlikSimpan () {
            let action = $('#action').val()
            if (action == "simpan") {
                SimpanBeritaAcara()
            }else if (action == "ubah"){
                UbahBeritaAcara()
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action Invalid",
                })
                return;
            }
        }

        function SimpanBeritaAcara() {
            let idEmployee = $('#idEmployee').text()
            let tanggal = $('#tanggal').val()
            let keperluan = $('#keperluan').val()
            let jenis = $('#jenis').val()
            let keterangan = $('#keterangan').val()
            let solusi = $('#solusi').val()
            
            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return
            }
            
            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Tidak Boleh Kosong",
                })
                return
            }

            if (keperluan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "keperluan Tidak Boleh Kosong",
                })
                return
            }
            
            if (jenis == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "jenis Tidak Boleh Kosong",
                })
                return
            }

            if (keterangan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "keterangan Tidak Boleh Kosong",
                })
                return
            }

            if (solusi == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "solusi Tidak Boleh Kosong",
                })
                return
            }

            data = {idEmployee:idEmployee, tanggal:tanggal, keperluan:keperluan, jenis:jenis, keterangan:keterangan, solusi:solusi}
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/BeritaAcara",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: "Data Berhasil Tersimpan.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    $('#idBeritaAcara').val(data.data.id)
                    $('#cari').val(data.data.id)
                    $('#action').val("ubah")

                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Set Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal").prop('disabled',true)
                    $("#keperluan").prop('disabled',true)
                    $("#jenis").prop('disabled',true)
                    $("#keterangan").prop('disabled',true)
                    $("#solusi").prop('disabled',true)
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

        function UbahBeritaAcara() {
            let idBeritaAcara = $('#idBeritaAcara').val()
            let idEmployee = $('#idEmployee').text()
            let tanggal = $('#tanggal').val()
            let keperluan = $('#keperluan').val()
            let jenis = $('#jenis').val()
            let keterangan = $('#keterangan').val()
            let solusi = $('#solusi').val()
            
            if (idBeritaAcara == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idBeritaAcara Belum Terpilih",
                })
                return
            }
            
            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return
            }
            
            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Tidak Boleh Kosong",
                })
                return
            }

            if (keperluan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "keperluan Tidak Boleh Kosong",
                })
                return
            }
            
            if (jenis == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "jenis Tidak Boleh Kosong",
                })
                return
            }

            if (keterangan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "keterangan Tidak Boleh Kosong",
                })
                return
            }

            if (solusi == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "solusi Tidak Boleh Kosong",
                })
                return
            }

            data = {idBeritaAcara:idBeritaAcara, idEmployee:idEmployee, tanggal:tanggal, keperluan:keperluan, jenis:jenis, keterangan:keterangan, solusi:solusi}
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: "/Absensi/Absensi/BeritaAcara",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terupdate!',
                        text: "Data Berhasil Terupdate.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Set Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal").prop('disabled',true)
                    $("#keperluan").prop('disabled',true)
                    $("#jenis").prop('disabled',true)
                    $("#keterangan").prop('disabled',true)
                    $("#solusi").prop('disabled',true)
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

        function KlikUbah () {
            // Set Button
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            
            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggal").prop('disabled',false)
            $("#keperluan").prop('disabled',false)
            $("#jenis").prop('disabled',false)
            $("#keterangan").prop('disabled',false)
            $("#solusi").prop('disabled',false)
        }

        function KlikCari() {
            let cari = $('#cari').val()
            if (cari == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Kolom Cari Tidak Boleh Kosong",
                })
                return;
            }
            let data = {keyword:cari}

            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/BeritaAcara/search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#employee').val(data.data.NAME)
                    $('#idEmployee').text(data.data.ID)
                    $('#tanggal').val(data.data.TransDate)
                    $('#bagian').val(data.data.Bagian)
                    $('#status').val(data.data.WorkRole)
                    $('#keterangan').val(data.data.Note)
                    $('#solusi').val(data.data.Solution)
                    $("#keperluan").val(data.data.Purpose).change();
                    $("#jenis").val(data.data.Type).change();

                    $('#idBeritaAcara').val(data.data.IDM)
                    $('#action').val('ubah')

                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Set Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal").prop('disabled',true)
                    $("#keperluan").prop('disabled',true)
                    $("#jenis").prop('disabled',true)
                    $("#keterangan").prop('disabled',true)
                    $("#solusi").prop('disabled',true)
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

        function searchKaryawan() {
            let employee = $('#employee').val()
            let data = {employee:employee}
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/BeritaAcara/employee",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#employee').val(data.data.NAME)
                    $('#idEmployee').text(data.data.ID)
                    $('#bagian').val(data.data.Bagian)
                    $('#status').val(data.data.WorkRole)
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    $('#employee').val("")
                    $('#idEmployee').text("")
                    $('#bagian').val("")
                    $('#status').val("")
                    return;
                }
            })
        }

    </script>
    

@endsection
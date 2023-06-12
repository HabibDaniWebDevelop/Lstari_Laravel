<?php $title = 'Jaminan Karyawan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Jaminan Karyawan </li>
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

                @include('Absensi.JaminanKaryawan.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Function for klik baru
        function KlikBaru() {
            // Getting JaminanKaryawanID from hidden input
            let JaminanKaryawanID = $('#JaminanKaryawanID').val()
            // Check if JaminanKaryawanID have value
            if (JaminanKaryawanID != "") {
                // If JaminanKaryawanID have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Disable button "Cetak"
            $("#btn_cetak").prop('disabled',true)
            // Disable button "Ubah"
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggal_diterima").prop('disabled',false)
            $("#jaminan").prop('disabled',false)
            $("#employee").prop('disabled',false)
            $("#nomor_sk").prop('disabled',false)
            $("#keterangan").prop('disabled',false)
        }

        function klikBatal() {
            window.location.reload()
        }

        function KlikSimpan () {
            let action = $('#action').val()
            if (action == "simpan") {
                SimpanJaminanKaryawan()
            }else if (action == "ubah"){
                UbahJaminanKaryawan()
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action Invalid",
                })
                return;
            }
        }

        function SimpanJaminanKaryawan() {
            let idEmployee = $('#idEmployee').text()
            let tanggal_diterima = $('#tanggal_diterima').val()
            let jaminan = $('#jaminan').val()
            let nomor_sk = $('#nomor_sk').val()
            let keterangan = $('#keterangan').val()

            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return
            }
            
            if (tanggal_diterima == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Terima Tidak Boleh Kosong",
                })
                return
            }

            if (jaminan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jaminan Belum Terpilih",
                })
                return
            }

            if (nomor_sk == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Nomor/SK Tidak Boleh Kosong",
                })
                return
            }

            if (keterangan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Keterangan Tidak Boleh Kosong",
                })
                return
            }

            data = {idEmployee:idEmployee, tanggal_diterima:tanggal_diterima, jaminan:jaminan, nomor_sk:nomor_sk, keterangan:keterangan}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/Absensi/JaminanKaryawan",
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
                    $('#JaminanKaryawanID').val(data.data.id)
                    $('#cari').val(data.data.id)
                    $('#action').val("ubah")

                    // Enable button "Baru"
                    $("#btn_baru").prop('disabled',false)
                    // Enable button "Cetak"
                    $("#btn_cetak").prop('disabled',false)
                    // Enable button "Ubah"
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    // Disable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal_diterima").prop('disabled',true)
                    $("#jaminan").prop('disabled',true)
                    $("#employee").prop('disabled',true)
                    $("#nomor_sk").prop('disabled',true)
                    $("#keterangan").prop('disabled',true)
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

        function UbahJaminanKaryawan() {
            let idJaminanKaryawan = $('#JaminanKaryawanID').val()
            let idEmployee = $('#idEmployee').text()
            let tanggal_diterima = $('#tanggal_diterima').val()
            let jaminan = $('#jaminan').val()
            let nomor_sk = $('#nomor_sk').val()
            let keterangan = $('#keterangan').val()

            if (idJaminanKaryawan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jaminan Karyawan Belum Terpilih",
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
            
            if (tanggal_diterima == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Terima Tidak Boleh Kosong",
                })
                return
            }

            if (jaminan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jaminan Belum Terpilih",
                })
                return
            }

            if (nomor_sk == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Nomor/SK Tidak Boleh Kosong",
                })
                return
            }

            if (keterangan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Keterangan Tidak Boleh Kosong",
                })
                return
            }

            data = {idJaminanKaryawan:idJaminanKaryawan ,idEmployee:idEmployee, tanggal_diterima:tanggal_diterima, jaminan:jaminan, nomor_sk:nomor_sk, keterangan:keterangan}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: "/Absensi/JaminanKaryawan",
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
                        title: 'Updated!',
                        text: "Data Berhasil diubah.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });

                    // Enable button "Baru"
                    $("#btn_baru").prop('disabled',false)
                    // Enable button "Cetak"
                    $("#btn_cetak").prop('disabled',false)
                    // Enable button "Ubah"
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    // Disable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal_diterima").prop('disabled',true)
                    $("#jaminan").prop('disabled',true)
                    $("#employee").prop('disabled',true)
                    $("#nomor_sk").prop('disabled',true)
                    $("#keterangan").prop('disabled',true)
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
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Disable button "Cetak"
            $("#btn_cetak").prop('disabled',true)
            // Disable button "Ubah"
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)

            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggal_diterima").prop('disabled',false)
            $("#jaminan").prop('disabled',false)
            $("#employee").prop('disabled',false)
            $("#nomor_sk").prop('disabled',false)
            $("#keterangan").prop('disabled',false)
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
                url: "/Absensi/JaminanKaryawan/search",
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
                    $('#tanggal_diterima').val(data.data.TransDate)
                    $('#nomor_sk').val(data.data.SW)
                    $('#keterangan').val(data.data.Remarks)
                    $("#jaminan").val(data.data.Type).change();

                    $('#JaminanKaryawanID').val(data.data.ID)
                    $('#action').val('ubah')


                    // Enable button "Baru"
                    $("#btn_baru").prop('disabled',false)
                    // Enable button "Cetak"
                    $("#btn_cetak").prop('disabled',false)
                    // Enable button "Ubah"
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
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
                url: "/Absensi/JaminanKaryawan/employee",
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
                    return;
                }
            })
        }

        function klikCetak() {
            $('#JaminanKaryawanID').val()
            window.open('/Absensi/JaminanKaryawan/cetak/'+$('#JaminanKaryawanID').val(), '_blank');
        }

    </script>
    

@endsection
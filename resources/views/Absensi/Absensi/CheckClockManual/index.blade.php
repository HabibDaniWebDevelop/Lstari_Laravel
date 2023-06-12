<?php $title = 'CheckClock Manual'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">CheckClock Manual </li>
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

                @include('Absensi.Absensi.CheckClockManual.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
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
        // Function for klik baru
        function KlikBaru() {
            // Getting CheckClockManualID from hidden input
            let CheckClockManualID = $('#CheckClockManualID').val()
            // Check if CheckClockManualID have value
            if (CheckClockManualID != "") {
                // If CheckClockManualID have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Disable button "Ubah"
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggal").prop('disabled',false)
            $("#jam_clock").prop('disabled',false)
            $("#masuk").prop('disabled',false)
            $("#catatan").prop('disabled',false)
        }

        function klikBatal() {
            window.location.reload()
        }

        function KlikSimpan () {
            let action = $('#action').val()
            if (action == "simpan") {
                SimpanCheckClockManual()
            }else if (action == "ubah"){
                UbahCheckClockManual()
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action Invalid",
                })
                return;
            }
        }

        function SimpanCheckClockManual() {
            let idEmployee = $('#idEmployee').text()
            let tanggal = $('#tanggal').val()
            let jam = $('#jam_clock').val()
            let catatan = $('#catatan').val()
            let masuk = $("#masuk").is(':checked') ? "M" : "K"
            
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

            if (jam == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jam Tidak Boleh Kosong",
                })
                return
            }

            data = {idEmployee:idEmployee, tanggal:tanggal, jam:jam, masuk:masuk, catatan:catatan}
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/CheckClockManual",
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
                    $('#CheckClockManualID').val(data.data.id)
                    $('#cari').val(data.data.id)
                    $("#tabel1  tbody").empty()
                    let urut  = "<td>"+data.data.Urut+"</td>"
                    let transdate  = "<td>"+data.data.TransDate+"</td>"
                    let transtime = "<td>"+data.data.TransTime+"</td>"
                    let employee = "<td class='text-center'>"+data.data.Employee+"</td>"
                    let divisi = "<td>"+data.data.Divisi+"</td>"
                    let finalItem = ""
                    let rowitem = finalItem.concat("<tr>", urut, transdate, transtime, employee, divisi, "</tr>")
                    $("#tabel1 > tbody").append(rowitem);

                    $('#action').val("ubah")

                    // Enable button "Baru"
                    $("#btn_baru").prop('disabled',false)
                    // Enable button "Ubah"
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    // Disable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal").prop('disabled',true)
                    $("#jam_clock").prop('disabled',true)
                    $("#masuk").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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

        function UbahCheckClockManual() {
            let idCheckClockManual = $('#CheckClockManualID').val()
            let idEmployee = $('#idEmployee').text()
            let tanggal = $('#tanggal').val()
            let jam = $('#jam_clock').val()
            let catatan = $('#catatan').val()
            let masuk = $("#masuk").is(':checked') ? "M" : "K"

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

            if (jam == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jam Tidak Boleh Kosong",
                })
                return
            }

            data = {idCheckClockManual:idCheckClockManual , idEmployee:idEmployee, tanggal:tanggal, jam:jam, masuk:masuk, catatan:catatan}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: "/Absensi/Absensi/CheckClockManual",
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

                    $("#tabel1  tbody").empty()
                    let urut  = "<td>"+data.data.Urut+"</td>"
                    let transdate  = "<td>"+data.data.TransDate+"</td>"
                    let transtime = "<td>"+data.data.TransTime+"</td>"
                    let employee = "<td class='text-center'>"+data.data.Employee+"</td>"
                    let divisi = "<td>"+data.data.Divisi+"</td>"
                    let finalItem = ""
                    let rowitem = finalItem.concat("<tr>", urut, transdate, transtime, employee, divisi, "</tr>")
                    $("#tabel1 > tbody").append(rowitem);

                    // Enable button "Baru"
                    $("#btn_baru").prop('disabled',false)
                    // Enable button "Ubah"
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    // Disable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggal").prop('disabled',true)
                    $("#jam_clock").prop('disabled',true)
                    $("#masuk").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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
            $("#tanggal").prop('disabled',false)
            $("#jam_clock").prop('disabled',false)
            $("#masuk").prop('disabled',false)
            $("#catatan").prop('disabled',false)
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
                url: "/Absensi/Absensi/CheckClockManual/search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#employee').val(data.data.EmployeeName)
                    $('#idEmployee').text(data.data.idEmployee)
                    $('#tanggal').val(data.data.TransDate)
                    $('#jam_clock').val(data.data.TransTime)
                    if (data.data.Masuk == "M") {
                        $("#masuk").prop('checked',true)   
                    }else {
                        $("#masuk").prop('checked',false)   
                    }
                    $('#catatan').val(data.data.Catatan)

                    $('#CheckClockManualID').val(data.data.CheckClockManualID)
                    $('#action').val('ubah')

                    $("#tabel1  tbody").empty()
                    let urut  = "<td>"+data.data.Urut+"</td>"
                    let transdate  = "<td>"+data.data.TransDate+"</td>"
                    let transtime = "<td>"+data.data.TransTime+"</td>"
                    let employee = "<td class='text-center'>"+data.data.EmployeeName+"</td>"
                    let divisi = "<td>"+data.data.Divisi+"</td>"
                    let finalItem = ""
                    let rowitem = finalItem.concat("<tr>", urut, transdate, transtime, employee, divisi, "</tr>")
                    $("#tabel1 > tbody").append(rowitem);


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
                url: "/Absensi/Absensi/CheckClockManual/employee",
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

    </script>
    

@endsection
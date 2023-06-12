<?php $title = 'Face Absent'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Face Absent </li>
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

                @include('Absensi.Absensi.FaceAbsent.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#tabelTransaction').DataTable({
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: true,
            responsive: true,
            fixedColumns: true,
        });

        function KlikBaru() {
            // Getting haveFaceAbsent from hidden input
            let haveFaceAbsent = $('#haveFaceAbsent').val()
            // Check if haveFaceAbsent have value
            if (haveFaceAbsent != "") {
                // If haveFaceAbsent have value. It will reload th page
                window.location.reload()
                return;
            }
            // Button Settings
            $("#btn_baru").prop('disabled',true)
            $("#btn_simpan").prop('disabled',true)
            $("#btn_batal").prop('disabled',false)
            $("#btn_posting").prop('disabled',true)
            
            // Input Settings
            $("#tanggalAwal").prop('disabled',false)
            $("#tanggalAkhir").prop('disabled',false)
            $('#Cari').prop('disabled',false)
        }

        function klikBatal() {
            window.location.reload()
            return;
        }

        function klikCari() {
            let startDate = $('#tanggalAwal').val()
            let endDate = $('#tanggalAkhir').val()

            if (startDate == "" || endDate == "") {
                return;
            }

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url:'/Absensi/Absensi/FaceAbsensi/check',
                data: {startDate: startDate, endDate: endDate},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    // Destroy datatable;
                    $("#tabelTransaction").dataTable().fnDestroy()
                    $('#tabelTransaction tbody > tr').remove();
                    
                    // Insert item to table
                    data.data.forEach(function (value, i) {
                        let number = i+1;
                        let trStart = "<tr id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let karyawan = '<td>'+value["EmployeeName"]+'</td>'
                        let bagian = '<td>'+value["Bagian"]+'</td>'
                        let tanggal = '<td>'+value["TransDate"]+'</td>'
                        let waktu = '<td>'+value["TransTime"]+'</td>'
                        let status = '<td>'+value["Status"]+'</td>'
                        let machine = '<td>'+value["Machine"]+'</td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, karyawan, bagian, tanggal, waktu, status, machine, trEnd)
                        $("#tabelTransaction > tbody").append(rowitem);
                    })

                    $('#tabelTransaction').DataTable({
                        scrollY: '50vh',
                        scrollCollapse: true,
                        paging: false,
                        lengthChange: false,
                        autoWidth: true,
                        responsive: true,
                        fixedColumns: true,
                    });

                    $("#btn_simpan").prop('disabled',false)
                },
                error:function(xhr){
                    // Invalid Jenis
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            }); 
        }

        function KlikSimpan() {
            let startDate = $('#tanggalAwal').val()
            let endDate = $('#tanggalAkhir').val()

            if (startDate == "" || endDate == "") {
                return;
            }

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/FaceAbsensi",
                data: {startDate: startDate, endDate: endDate},
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set haveFaceAbsent
                    $('#haveFaceAbsent').val(1);

                    // Button Settings
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    $("#btn_posting").prop('disabled',false)
                    
                    // Input Settings
                    $("#tanggalAwal").prop('disabled',true)
                    $("#tanggalAkhir").prop('disabled',true)
                    $("#Cari").prop('disabled',true)
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

        function KlikPosting() {
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/FaceAbsensi/posting",
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set posting status
                    $('#postingStatus').val('P')

                    // Button Settings
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    $("#btn_posting").prop('disabled',true)
                    
                    // Input Settings
                    $("#tanggalAwal").prop('disabled',true)
                    $("#tanggalAkhir").prop('disabled',true)
                    $("#Cari").prop('disabled',true)
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Yaayy...',
                    //     text: "Success",
                    // })
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
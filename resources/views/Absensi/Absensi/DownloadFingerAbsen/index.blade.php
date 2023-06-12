<?php $title = 'Download Finger Absen'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Download Finger Absen </li>
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

                @include('Absensi.Absensi.DownloadFingerAbsen.data')
                
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
            // Getting haveFingerAbsent from hidden input
            let haveFingerAbsent = $('#haveFingerAbsent').val()
            // Check if haveFingerAbsent have value
            if (haveFingerAbsent != "") {
                // If haveFingerAbsent have value. It will reload th page
                window.location.reload()
                return;
            }
            // Button Settings
            $("#btn_baru").prop('disabled',true)
            $("#btn_simpan").prop('disabled',true)
            $("#btn_batal").prop('disabled',false)
            $("#btn_posting").prop('disabled',true)
            
            // Input Settings
            $("#file").prop('disabled',false)
            $('#TestResult').prop('disabled',false)
            $('#btn_check').prop('disabled',false)
        }

        function klikBatal() {
            window.location.reload()
            return;
        }

        function klikCheck() {
            let data = new FormData()
            if ($('#file').get(0).files.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "File Not Found",
                })
                return;
            }else{
                // Check with File
                let file = $('#file').prop('files')[0]
                data.append('file', file)
                data.append('inputType', 'file')
            }
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/DownloadFingerAbsen/check",
                data:data,
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
                    
                    $("#file").prop('disabled',true)
                    $("#btn_check").prop('disabled',true)
                    $("#btn_simpan").prop('disabled',false)
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

        function KlikSimpan() {
            let data = new FormData()
            if ($('#file').get(0).files.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "File Not Found",
                })
                return;
            }else{
                // Check with File
                let file = $('#file').prop('files')[0]
                data.append('file', file)
                data.append('inputType', 'file')
            }
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/DownloadFingerAbsen",
                data:data,
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
                    // Set haveFingerAbsent
                    $('#haveFingerAbsent').val(1);

                    // Button Settings
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    $("#btn_posting").prop('disabled',false)
                    
                    // Input Settings
                    $("#file").prop('disabled',true)
                    $('#btn_check').prop('disabled',true)
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

        function KlikPosting() {
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/DownloadFingerAbsen/posting",
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
                    $("#file").prop('disabled',true)
                    $('#btn_check').prop('disabled',true)
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
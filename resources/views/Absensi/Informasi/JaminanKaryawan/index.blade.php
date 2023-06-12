<?php $title = 'Data Jaminan Karyawan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
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

                @include('Absensi.Informasi.JaminanKaryawan.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.backend-Theme-3.DataTabelButton')
    <script>
        var table = $('#TabelJaminanKaryawan').DataTable({
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            orderCellsTop: true,
            fixedHeader: true,
            buttons: [{
                extend: 'print',
                split: ['excel', 'pdf'],
            }]
        });
        table.buttons().container().appendTo('#TabelJaminanKaryawan_wrapper .col-md-6:eq(0)');
    
        function ProcessEmployeeGuarantee(ID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, process it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type:'GET',
                        url:'/Absensi/Informasi/JaminanKaryawan/Proses/'+ID,
                        dataType: "json",
                        beforeSend: function () {
                            $(".preloader").show();  
                        },
                        complete: function () {
                            $(".preloader").fadeOut(); 
                        },
                        success:function(data){
                            Swal.fire(
                                'Processed!',
                                'Your file has been processed.',
                                'success'
                            ).then((result2)=>{
                                if (result2.isConfirmed) {
                                    window.location.reload();
                                }
                            })
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
                    })
                }
            })
        }
    </script>
@endsection
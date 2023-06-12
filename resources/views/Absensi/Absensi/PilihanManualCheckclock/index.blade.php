<?php $title = 'Pilihan Manual Checkclock'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Pilihan Manual Checkclock </li>
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

                @include('Absensi.Absensi.PilihanManualCheckclock.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.backend-Theme-3.DataTabelButton')
    <script>
        var table = $('#TabelPilihanManualCheckclock').DataTable({
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
        table.buttons().container().appendTo('#TabelPilihanManualCheckclock_wrapper .col-md-6:eq(0)');
    
        function GetPilihanManualCheckclock() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Absensi/PilihanManualCheckclock/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    // console.log(data.data);
                    // return
                    $("#TabelPilihanManualCheckclock").dataTable().fnDestroy()
                    $('#TabelPilihanManualCheckclock tbody > tr').remove();
                    data.data.forEach(element => {
                        let tanggal = "<td>"+element['TransDate']+"</td>"
                        let hari = "<td>"+element['DAY']+"</td>"
                        let jamMasuk = "<td>"+element['WorkIn']+"</td>"
                        let jamKeluar = "<td>"+element['WorkOut']+"</td>"
                        let karyawan = "<td>"+element['Employee']+"</td>"
                        let bagian = "<td>"+element['Department']+"</td>"
                        let status = "<td>"+element['WorkRole']+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>",tanggal, hari, jamMasuk, jamKeluar, karyawan, bagian, status,"</tr>")
                        $("#TabelPilihanManualCheckclock > tbody").append(rowitem);
                    });
                    var table = $('#TabelPilihanManualCheckclock').DataTable({
                        scrollY: '500px',
                        scrollCollapse: true,
                        paging: true,
                        orderCellsTop: true,
                        fixedHeader: true,
                        buttons: [{
                            extend: 'print',
                            split: ['excel', 'pdf'],
                        }],
                    });
                    table.buttons().container().appendTo('#TabelPilihanManualCheckclock_wrapper .col-md-6:eq(0)');

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
    </script>
@endsection
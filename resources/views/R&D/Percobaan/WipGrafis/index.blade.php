<?php $title = 'Wip Grafis'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R & D </li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">Wip Grafis </li>
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

                @include('R&D.Percobaan.WipGrafis.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#tabel1').DataTable({
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            lengthChange: true,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: true,
            responsive: true,
            fixedColumns: true,
        });

        function KlikBaru() {
            let idNTHKO = $('#idNTHKO').val()
            if (idNTHKO != "") {
                // If idNTHKO have value. It will reload th page
                window.location.reload()
                return;
            }
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            $("#btn_cetak").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable input
            $("#noNTHKO").prop('disabled',false)
        }

        function klikBatal(){
            window.location.reload()
        }
        
        function getNTHKO() {
            let noNTHKO = $('#noNTHKO').val()
            if (noNTHKO == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "noNTHKO Tidak Boleh Kosong",
                })
                return;
            }
            let data = {noNTHKO:noNTHKO}

            $.ajax({
                type: "GET",
                url: "/R&D/Percobaan/WIPGrafis/getNTHKO",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // return
                    // Destroy datatable;
                    $("#tabel1").dataTable().fnDestroy()
                    $('#tabel1 tbody > tr').remove();

                    // Set TableItems
                    $('#TableItems').empty();
                    $('#TableItems').html(data.data.resultHTML)

                    $('#tabel1').DataTable({
                        scrollY: '50vh',
                        scrollCollapse: true,
                        paging: false,
                        lengthChange: true,
                        searching: false,
                        ordering: false,
                        info: false,
                        autoWidth: true,
                        responsive: true,
                        fixedColumns: true,
                    });
                    
                    // Set Input
                    $('#tanggalNTHKO').val(data.data.result.TransDate)
                    $('#totalJumlah').val(data.data.result.Qty)
                    $('#totalBerat').val(data.data.result.Weight)
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
            let noNTHKO = $('#noNTHKO').val()
            if (noNTHKO == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "NTHKO Belum terpilih",
                })
                return;
            }

            let data = {noNTHKO:noNTHKO}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/R&D/Percobaan/WIPGrafis",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Setting Buttons
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_cetak").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Setting input
                    $("#noNTHKO").prop('disabled',true)
                    $('#idNTHKO').val(data.data.result.ID)
                    $('#cari').val(noNTHKO)

                    
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

        function KlikCari() {
            let keyword = $('#cari').val()
            if (cari == "") {
                return
            }

            let data = {noNTHKO:keyword}

            $.ajax({
                type: "GET",
                url: "/R&D/Percobaan/WIPGrafis/search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // return
                    // Destroy datatable;
                    $("#tabel1").dataTable().fnDestroy()
                    $('#tabel1 tbody > tr').remove();

                    // Set TableItems
                    $('#TableItems').empty();
                    $('#TableItems').html(data.data.resultHTML)

                    $('#tabel1').DataTable({
                        scrollY: '50vh',
                        scrollCollapse: true,
                        paging: false,
                        lengthChange: true,
                        searching: false,
                        ordering: false,
                        info: false,
                        autoWidth: true,
                        responsive: true,
                        fixedColumns: true,
                    });

                    // Enable button "Baru"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_cetak").prop('disabled',false)
                    // Enable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Set Input
                    $('#tanggalNTHKO').val(data.data.result.TransDate)
                    $('#totalJumlah').val(data.data.result.Qty)
                    $('#totalBerat').val(data.data.result.Weight)
                    $('#idNTHKO').val(data.data.result.ID)
                    $("#noNTHKO").val(keyword)
                    $("#noNTHKO").prop('disabled',true)
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

        function klikCetak() {
            let keyword = $('#cari').val()
            if (keyword == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Kolom Cari Tidak boleh Kosong",
                })
                return;
            }
            window.open('/R&D/Percobaan/WIPGrafis/cetak?workAllocation='+keyword, '_blank');
            return
        }
    </script>
    

@endsection
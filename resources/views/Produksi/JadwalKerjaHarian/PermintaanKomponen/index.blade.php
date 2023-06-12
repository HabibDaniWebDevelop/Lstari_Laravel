<?php $title = 'Permintaan Komponen'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Jadwal Kerja Harian </li>
        <li class="breadcrumb-item active">Permintaan Komponen </li>
    </ol>
@endsection

@section('css')

    <style>
        .dx-datagrid-headers{
            background-color: #D7F5FC;
            text-align: center!important; 
        }
        .dx-datagrid{  
            font-style: verdana; 
            font-size: 10px; 
        }  
        .dx-datagrid-action.dx-cell-focus-disabled {
            text-align: center !important;
            vertical-align: middle !important;
        }   
        .dx-data-row td.cls {  
            text-align: center!important;  
        }   
        .dx-data-row td.lss {  
            text-align: right!important;  
        }  
        tbody{
            color: black;
        }
    </style>

    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Produksi.JadwalKerjaHarian.PermintaanKomponen.data')

            </div>
        </div>
    </div>
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')

    <script src="{!! asset('assets/almas/sum().js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>

        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }

        function klikClear(){
            document.getElementById("idcari").value = '';
            document.getElementById("idcari").focus();
        }

        function klikBatal(){
            location.reload();
        }

        function refresh_sum_qty(id) {
            var baris = id-1;
            var table = $('#tampilitem').DataTable();

            // console.log(baris);

            var qtyinput = $(table.cell(baris, 6).node()).find('input').val();
            document.getElementById("qtyorder2"+id).value = qtyinput;

            table.cell(baris, 7).data(qtyinput);
            var qtydata = table.column(7).data().sum();

            // console.log(qtydata);

            document.getElementById("jmlOrderLabel").innerHTML = String(qtydata);
            document.getElementById("jmlOrder").value = qtydata;

        }

        function klikBaru() { //OK
            var wik = document.getElementById("idmnya1").value;
            var idlocation = document.getElementById("idlocation").value;
            if (wik != '') {
                location.reload(true);
            } else {
                document.getElementById("btnsimpan").disabled = false;
                document.getElementById("btnbatal").disabled = false;
                document.getElementById("btnbaru").disabled = true;
                document.getElementById("rph").disabled = false;
                document.getElementById("tgl_masuk").disabled = false;
                document.getElementById("catatan").disabled = false;
                document.getElementById("rph").focus();
                if(idlocation == 50){
                    document.getElementById("btnformsepuh").disabled = false;
                    document.getElementById("btnformpcb").disabled = false;
                }else{
                    document.getElementById("btnformkikir").disabled = false;
                    document.getElementById("btnformdc").disabled = false;
                }
            }
        }

        function klikFormSepuh(){ //OK
            var rph = $("#rph").val();

            data = {rph: rph};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaSepuh',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if (data.status == 'P' && data.rowcount <= 0) { // && data.rowcount <= 0
                        $("#tampil").html(data.html);

                        var table = $('#tampilitem').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false
                        });
                        var table = $('#tampiltotal').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false
                        });
                        document.getElementById("btnsimpan").disabled = false;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Info!',
                            text: "RPH Tidak Bisa Diproses/Sudah Pernah Dibuatkan/RPH Belum Di Posting.",
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikFormPCB(){ //OK
            var rph = $("#rph").val();

            data = {rph: rph};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaSepuhPCB',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if (data.status == 'P' && data.rowcount <= 0) { // && data.rowcount <= 0
                        $("#tampil").html(data.html);

                        var table = $('#tampilitem').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false
                        });
                        var table = $('#tampiltotal').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false
                        });
                        document.getElementById("btnsimpan").disabled = false;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Info!',
                            text: "RPH Tidak Bisa Diproses/Sudah Pernah Dibuatkan/RPH Belum Di Posting.",
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikFormKikir(){ //OK
            var rph = $("#rph").val();

            data = {rph: rph};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaKikir',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if (data.status == 'P') { // && data.rowcount <= 0
                        $("#tampil").html(data.html);

                        var table = $('#tampilitem').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false,
                            "scrollX": true,
                            "scroller": true
                        });
                        var table2 = $('#tampiltotal').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false,
                            "scrollX": true,
                            "scroller": true
                        });
                        document.getElementById("btnsimpan").disabled = false;
                        table.columns(7).visible(false);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Info!',
                            text: "RPH Tidak Bisa Diproses/Sudah Pernah Dibuatkan/RPH Belum Di Posting.",
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikFormDC(){ //OK
            var rph = $("#rph").val();

            data = {rph: rph};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/periksaKikirDC',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if (data.status == 'P' && data.rowcount <= 0) { // && data.rowcount <= 0
                        $("#tampil").html(data.html);

                        var table = $('#tampilitem').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false,
                            "scrollX": true,
                            "scroller": true
                        });
                        var table2 = $('#tampiltotal').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false,
                            "scrollX": true,
                            "scroller": true
                        });
                        document.getElementById("btnsimpan").disabled = false;
                        table.columns(7).visible(false);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Info!',
                            text: "RPH Tidak Bisa Diproses/Sudah Pernah Dibuatkan/RPH Belum Di Posting.",
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikSimpan() { //OK
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/simpan',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan, #dataitem, #datatotal, #dataitem2").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if (data.status == 'sukses') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: "Data Berhasil Disimpan",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        $("#update").val(data.update);
                        $("#idmnya1").val(data.id);
                        $("#idmnya").html(data.id);
                        $("#idcari").val(data.id);
                        document.getElementById("btncetak").disabled = false;
                        if(data.location == 50){
                            document.getElementById("btnformsepuh").disabled = true;
                            document.getElementById("btnformpcb").disabled = true;
                        }else{
                            document.getElementById("btnformkikir").disabled = true;
                            document.getElementById("btnformdc").disabled = true;
                        }
                        document.getElementById("btnsimpan").disabled = true;
                        document.getElementById("btnbaru").disabled = false;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Info',
                            text: "Data Tidak Berhasil Di Proses.",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikSimpanTest() { //OK
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/simpanTest',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan, #dataitem, #datatotal, #dataitem2").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if (data.status == 'sukses') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: "Data Berhasil Disimpan",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        $("#update").val(data.update);
                        $("#idmnya1").val(data.id);
                        $("#idmnya").html(data.id);
                        $("#idcari").val(data.id);
                        document.getElementById("btncetak").disabled = false;
                        if(data.location == 50){
                            document.getElementById("btnformsepuh").disabled = true;
                            document.getElementById("btnformpcb").disabled = true;
                        }else{
                            document.getElementById("btnformkikir").disabled = true;
                            document.getElementById("btnformdc").disabled = true;
                        }
                        document.getElementById("btnsimpan").disabled = true;
                        document.getElementById("btnbaru").disabled = false;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Info',
                            text: "Data Tidak Berhasil Di Proses.",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikCetakAll() { //OK
            var id = $("#idcari").val();

            if (idcari == "") {
                alert("ID Pencarian Kosong");
            } else {
                var dataUrl = `/Produksi/JadwalKerjaHarian/PermintaanKomponen/cetak?id=${id}`;
                window.open(dataUrl, '_blank');
            }
        }
        
        function klikLihatAll() { //OK
            var idcari = $("#idcari").val();

            if (idcari == "") {
                alert("ID Pencarian Kosong");
            } else {

                data = {id: idcari};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/JadwalKerjaHarian/PermintaanKomponen/lihat',
                    beforeSend: function() {
                        openModal();
                    },
                    data: data,
                    dataType: "json",
                    type: 'POST',
                    success: function(data) {
                        document.getElementById("btnbaru").disabled = false;
                        document.getElementById("btncetak").disabled = false;
                        $("#tampil").html(data.html);
                        $("#idmnya1").val(idcari);
                        $("#idmnya").html(idcari);
                        $("#idcari").val(idcari);
                        var table = $('#tampilitem').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false
                        });
                        var table = $('#tampiltotal').DataTable({
                            "paging": false,
                            "ordering": true,
                            "info": false,
                            "searching": false,
                            "scrollY": "450px",
                            "scrollCollapse": true,
                            "paging": false
                        });
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr, thrownError, ajaxOptions) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Invalid Request",
                        })
                        return;
                    }
                });

            }
        }

        function stokCB(){ //OK

            $.get('/Produksi/JadwalKerjaHarian/PermintaanKomponen/stokCB', function(data) {
                $("#contentBody").html(data.html);
                $('#myModal').modal('show');
                var table = $('#tampilstokCB').DataTable({
                    ordering: true,
                    paging: true,
                    pageLength: 10,
                    searching: true,
                    lengthChange: true,
                    scrollCollapse: true,
                });
            });

        }

    </script>

@endsection

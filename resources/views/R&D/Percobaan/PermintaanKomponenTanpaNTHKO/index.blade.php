<?php $title = 'Percobaan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">Permintaan Komponen Tanpa NTHKO</li>
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
            @include('R&D.Percobaan.PermintaanKomponenTanpaNTHKO.data')
            <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
                <div class="text-center fw-bold mb-2" id="judulklik"></div>
                <a class="dropdown-item" id="removeRow" ><span class="">-</span>&nbsp; Hapus</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@include('layouts.backend-Theme-3.DataTabelButton')

<script>
    $('#tabel5').DataTable({
        "paging": true,
        "lengthChange": false,
        "pageLength": 11,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": false,
    });


    function getListItem(value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        data = {value: value};
        $.ajax({
            url: "/RnD/Percobaan/PermintaanKomponenTanpaNTHKO/GetListTanpaNTHKO",
            type: "POST",
            data: data,
            dataType: "json",
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {

                document.getElementById("btn_simpan").disabled = false;
                document.getElementById("btn_ubah").disabled = false;
                document.getElementById("btn_batal").disabled = false;

                $("#tabel5 tbody").html(data.html);

                $("#idwo").val(data.swwo);
                $("#totalbarang").val(data.total);
            }
        });
    }

    function simpanrequest() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var formData = $('#listitem').serializeArray();

        $.ajax({
            url: "/RnD/Percobaan/PermintaanKomponenTanpaNTHKO/store",
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {

            }
        });

    }
</script>

@endsection

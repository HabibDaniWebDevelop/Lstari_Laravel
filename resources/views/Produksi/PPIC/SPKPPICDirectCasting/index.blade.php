<?php $title = 'PPIC'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">PPIC </li>
    <li class="breadcrumb-item active">SPK PPIC Direct Casting </li>
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
            @include('Produksi.PPIC.SPKPPICDirectCasting.data')
        </div>
    </div>
</div>
@endsection

@section('script')
@include('layouts.backend-Theme-3.DataTabelButton')
<script>
function getListItem(value) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    data = {        value: value    };
    $.ajax({
        url: "/Produksi/PPIC/SPKPPICDirectCasting/GetListItemPPIC",
        type: "POST",
        data: data,
        dataType: "json",
        success: function(data) {
            document.getElementById("btn_simpan").disabled = false;
            document.getElementById("btn_ubah").disabled = false;
            document.getElementById("btn_batal").disabled = false;
            $("#tampungtabel tbody").append(data.html);

            $("#idwo").val('');
        }
    });
}

function KlikSimpan() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: "/Produksi/PPIC/SPKPPICDirectCasting/simpan",
        type: "POST",
        data: $("#listitem").serialize(),
        dataType: "json",
        success: function(data) {
            document.getElementById("btn_simpan").disabled = true;
            document.getElementById("btn_ubah").disabled = true;
            document.getElementById("btn_batal").disabled = true;

            if (data.status == 'OK') {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "SPK PPIC Direct Casting Berhasil di-buat!",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }
        }
    });
}

function klikBatal() {
    window.location.reload();
}
</script>
@endsection
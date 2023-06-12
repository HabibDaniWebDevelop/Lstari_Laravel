<?php $title = 'Informasi Karet Keluar & Kembali'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R & D </li>
    <li class="breadcrumb-item">Divisi Lilin </li>
    <li class="breadcrumb-item active">Karet Keluar & Kembali</li>
</ol>
@endsection

@section('container')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-body"> -->
            @include('R&D.DivisiLilin.KaretKeluardanKembali.data')
            <div id="tabellaci" class="col-md-12">

            </div>
            <!-- </div> -->

        </div>
        @include('Setting.publick_function.ViewSelectionModal')
    </div>
</div>

@endsection

@section('script')
@include('layouts.backend-Theme-3.DataTabelButton')
<script>

var table = $('#tabel1').DataTable({
    "paging": false,
    "lengthChange": false,
    // "pageLength": 9,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,
    buttons: [{
        extend: 'print',
        split: ['excel', 'pdf'],
    }]
});
table.buttons().container().appendTo('#tabel1_wrapper .col-md-6:eq(0)');

</script>
@extends('layouts.backend-Theme-3.XtraScript')
@endsection
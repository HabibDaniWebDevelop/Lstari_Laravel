<?php $title = 'QR Code Generator'; ?>
<?php 
    if (Auth::check()) {
        $app = "app";
    } else {
        $app = "app2";
    }
?>
@extends('layouts.backend-Theme-3.'.$app)
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-lain </li>
        <li class="breadcrumb-item active">QRCode</li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Lain-lain.QRCode.QRCodeGenerator.data')

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>

@endsection

@section('script')

    <script>
        function Klik_Batal1() {
            location.reload();
        }

        //getkaryawan
        function Klik_Generate() {

            var id = $('#generate').val();
            var NTHKO = $('#NTHKO').val();

            if (id != '' && NTHKO == '') {
                window.open('/Lain-lain/QRCode/QRCodeGenerator/generate/1/' + id, '_blank');
            } else if (NTHKO != '' && id == '') {
                window.open('/Lain-lain/QRCode/QRCodeGenerator/generate/2/' + NTHKO, '_blank');
            } else if (id == '' && NTHKO == '') {
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'input file tidak boleh kosong !',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan !',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }

    </script>
@endsection

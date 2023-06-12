<?php $title = 'SPKO'; ?>
@extends('layouts.backend-Theme-3.app2')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Pelaporan Produksi </li>
        <li class="breadcrumb-item active">SPKO </li>
    </ol>
@endsection

@section('css')
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.css') !!}">
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .container-fluid{
            padding: 0px !important;
            padding-left: 10px !important;
            padding-right: 5px !important;
        }
    </style>

    <script>
        var element = document.getElementById("id-element"); // ganti "id-element" dengan ID elemen yang ingin dihapus kelasnya
        element.classList.remove("container-p-y");

        // $(document).ready(function() {
        // $("body").removeClass("container-p-y"); // ganti "id-element" dengan ID elemen yang ingin dihapus kelasnya
        // });
        // $("body").removeClass("container-p-y");
    </script>

@endsection

@section('container')

    <div class="demo-inline-spacing" id="btn-menu">

        <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                class="fas fa-times-circle"></span>&nbsp; Batal</button>

        <button type="button" class="btn btn-warning" id="Simpan1" onclick="Klik_Simpan1()">
            <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

        <button type="button" class="btn btn-dark me-4" id="Posting1" disabled onclick="Klik_Posting1()">
            <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>

        {{-- <button type="button" class="btn btn-info" id="Cetak1" value="" disabled onclick="Klik_Cetak1()">
            <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button> --}}

        <button type="button" class="btn btn-primary" id="Generate" value="" disabled onclick="Klik_Generate()">
            <span class="fas fa-qrcode"></span>&nbsp; Generate</button>



        <div class="d-flex float-end">

            <div class="position-absolute d-none" id="postinglogo" style="right: 300px; top: 10px; ">
                <img src="{!! asset('assets/images/posting.jpg') !!}" style="width: 250px; object-fit: cover; object-position: top;">
            </div>

            <div class="input-group input-group-merge" style="width: 200px;">
                <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                <input type="search" class="form-control" list="carilist" autofocus id='cari'
                    onchange="ChangeCari('0')" placeholder="search...">
            </div>
            <datalist class="text-warning" id="carilist">
                {{-- @foreach ($carilists as $carilist)
                <option value="{{ $carilist->SW }}"> {{ $carilist->ID }}
                </option>
                @endforeach --}}
            </datalist>

        </div>
        <hr class="m-0" />

    </div>



<div class="container mt-4">
    <div class="mb-3">{!! DNS2D::getBarcodeHTML('4445645656', 'QRCODE') !!}</div>
    <div class="mb-3" >{!! DNS1D::getBarcodeHTML('4445645656', 'C128', 2, 50, 'red') !!}</div>
    <div class="mb-3">{!! DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T') !!}</div>
    <div class="mb-3">{!! DNS1D::getBarcodeHTML('4445645656', 'CODABAR') !!}</div>
    <div class="mb-3">{!! DNS1D::getBarcodeHTML('4445645656', 'KIX') !!}</div>
    <div class="mb-3">{!! DNS1D::getBarcodeHTML('4445645656', 'RMS4CC') !!}</div>
    <div class="mb-3">{!! DNS1D::getBarcodeHTML('4445645656', 'UPCA') !!}</div>
</div>

    <form id="tampilform2">
        <input type="file" accept="image/jpeg" id="g1" name="gambar1" class="dropify" data-height="209px" multiple
            data-default-file="" data-id="1" />
        <input type="file" accept="image/jpeg" id="g2" name="gambar2" class="dropify" data-height="209px" multiple
            data-default-file="" data-id="2" />
        <input type="file" accept="image/jpeg" id="g3" name="gambar3" class="dropify" data-height="209px" multiple
            data-default-file="" data-id="3" />

    </form>

    <img src="{!! asset('assets/images/no-photos.jpg') !!}" style="max-width: 500px; max-height: 200px;"
                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">

@endsection

@section('script')

    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.js') !!}"></script>

    <script>
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });

        function Klik_Simpan1() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // var formData = $('#tampilform2').serializeArray();
            data = new FormData();

            // let a = $('#g1').val();
            // let b = $('#g2').val();
            // let c = $('#g3').val();

            $.each($('#g1').prop('files'), function(i, field) {

                let a = $('#g1').prop('files')[i]
                data.append('gambar1[' + i + ']', a);

            });

            // let a = $('#g1').prop('files')[0]
            // let b = $('#g1').prop('files')[1]
            // let c = $('#g1').prop('files')[2]


            // data.append('gambar1', a);
            // data.append('gambar2', b);
            // data.append('gambar3', c);

            console.log(data);
            $.ajax({
                type: "POST",
                url: '/tes',
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {


                },
                error: function(data) {

                }
            });



        }
    </script>

@endsection

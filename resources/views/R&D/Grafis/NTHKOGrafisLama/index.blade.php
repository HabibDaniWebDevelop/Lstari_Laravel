<?php
$title = 'NTHKOGrafis';
$menu = '1';
?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">NTHKO Grafis</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R&D / Grafis </li>
        <li class="breadcrumb-item active">{{ $title }} </li>
    </ol>
@endsection

@section('css')
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.css') !!}">

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('R&D.Grafis.NTHKOGrafis.data')

            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.js') !!}"></script>
    @include('layouts.backend-Theme-3.timbangan')

    <script>
        //patch lokasi modul
        var patch = '/R&D/Grafis/NTHKOGrafis/';

        $(document).ajaxStart(function() {
            $(".preloader").show();
        });
        $(document).ajaxComplete(function() {
            $(".preloader").hide();
        });

        console.log({{ session()->get('iduser') }})

        function ChangeCari(id) {
            $('#btn-menu .btn').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#conscale').prop('disabled', false);
            $('#Generate').prop('disabled', false);
            $("#tampil").removeClass('d-none');

            if (id == '0') {
                id = $('#cari').val();
                console.log(id);
            }
            $.get(patch + 'show/1/' + id, function(data) {
                $("#tampil").html(data);
                $('#Cetak1').val(id);

                // console.log(data);

                cekpos = $('#postingstatus').val();
                if (cekpos == "P") {
                    $("#postinglogo").removeClass('d-none');
                    $('#btn-menu .btn').prop('disabled', true);
                    $('#Batal1').prop('disabled', false);
                } else if (cekpos == "A") {
                    $("#postinglogo").addClass('d-none');
                    $('#Simpan1').prop('disabled', true);
                    $('#Posting1').prop('disabled', false);
                }
                $('#Cetak1').prop('disabled', false);
                $('#upload').prop('disabled', false);
            });
        }

        function ChangeCari2(id) {
            console.log(id);
            $('#' + id).focus();
        }

        function hasilgrafis() {
            // console.log(id);
            $('#cari2').val('');
            // $('#cari2').focus();
        }

        function Klik_Batal1() {
            location.reload();
        }

        function Klik_Generate() {
            id = $('#cari').val();
            window.open(patch + 'show/2/' + id, '_blank');
        }

        function Klik_Cetak1() {
            id = $('#cari').val();
            window.open(patch + 'show/3/' + id, '_blank');
        }

        function Klik_Simpan1() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#form1').serializeArray();
            count = $('#count').val();

            for (let index = 1; index <= count; index++) {
                let nama = $('#gambar' + index).attr('data-id');
                formData.push({
                    name: 'nama[]',
                    value: nama
                });
            }

            var type = "POST";
            var ajaxurl = patch + 'store';

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Simpan Berhasil!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#Simpan1').prop('disabled', true);
                            $('#Posting1').prop('disabled', false);
                        }
                    });
                    console.log('success:', data);
                    id = $('#cari').val();
                    ChangeCari(id);

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message
                    })
                    console.log('Error:', data);
                }
            });
        }

        function Klik_Posting1() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#form1').serializeArray();
            data = new FormData();
            count = $('#count').val();


            // idworkallocation = $('#idworkallocation').val();
            // data.append('idworkallocation', idworkallocation);

            //meng uraikan serializeArray
            $.each(formData, function(i, field) {
                data.append(field.name, field.value);
                console.log(field.name, field.value);
            });

            for (let index = 1; index <= count; index++) {
                let nama = $('#gambar' + index).attr('data-id');
                data.append('nama[]', nama);
                let sku = $('#SKU' + index).val();
                data.append(nama + 'SKU', sku);

                $.each($('#gambar' + index).prop('files'), function(i, field) {
                    let a = $('#gambar' + index).prop('files')[i];
                    data.append(nama + '[' + i + ']', a);
                    // console.log(index,i);
                });

            }

            var type = "POST";
            var ajaxurl = patch + 'posting'; // alert(formData); //
            // console.log(formData);
            $.ajax({
                type: type,
                url: ajaxurl,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Posting Berhasil!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#Posting1').prop('disabled', true);
                            id = $('#cari').val();
                            ChangeCari(id);
                        }
                    });
                    console.log('success:', data);

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message
                    })
                    console.log('Error:', data);
                }
            });
        }

        function Klik_Upload() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#form1').serializeArray();
            data = new FormData();
            count = $('#count').val();


            //meng uraikan serializeArray
            $.each(formData, function(i, field) {
                data.append(field.name, field.value);
                console.log(field.name, field.value);
            });

            for (let index = 1; index <= count; index++) {
                let nama = $('#gambar' + index).attr('data-id');
                data.append('nama[]',
                    nama);
                let sku = $('#SKU' + index).val();
                data.append(nama + 'SKU', sku);
                $.each($('#gambar' + index).prop('files'),
                    function(i, field) {
                        let a = $('#gambar' + index).prop('files')[i];
                        data.append(nama + '[' + i + ']', a); //
                        console.log(index, i);
                    });
            }
            var type = "POST";
            var ajaxurl = patch + 'upload';
            console.log(formData);
            $.ajax({
                type: type,
                url: ajaxurl,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Posting Berhasil!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#Posting1').prop('disabled', true);
                            id = $('#cari').val();
                            ChangeCari(id);
                        }
                    });
                    console.log('success:', data);

                }
            });

        }
    </script>

@endsection

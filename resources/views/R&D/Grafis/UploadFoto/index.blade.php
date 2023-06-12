<?php $title = 'Upload Foto Grafis'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">RnD </li>
        <li class="breadcrumb-item">Grafis </li>
        <li class="breadcrumb-item active">UploadFoto </li>
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

                @include('R&D.Grafis.UploadFoto.data')

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.js') !!}"></script>

    <script>
        //patch lokasi modul
        var patch = '/R&D/Grafis/UploadFoto/';

        $('#tabel1').DataTable({
            "paging": false,
            // "pageLength": 13,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true
        });

        function klikCari() {
            if (event.keyCode === 13) {
                var id = $('#cari').val();

                console.log(id);
                window.location.replace(patch + 'search?id=' + id);
            }
        }

        function Klik_Batal1() {
            window.location.reload()
            return;
        }

        $(document).ajaxStart(function() {
            $(".preloader").show();
        });

        $(document).ajaxComplete(function() {
            $(".preloader").hide();
        });

        // -------------------- klik di tabel --------------------
        $(".klik").on('click', function(e) {
            $('.klik').css('background-color', 'white');

            var id = $(this).attr('id');
            klikedit(id);
            return false; //blocks default Webbrowser right click menu

        });

        function klikedit(id) {
            $("#jodulmodal1").html('Form Edit Data PC');
            $('#modalformat').attr('class', 'modal-dialog modal-lg');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Edit');

            console.log(id);
            $.get(patch + 'show/1/' + id, function(data) {
                $("#tampil").html(data);
            });
        }

        function Klik_Simpan1(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            data = new FormData();
            count = $('#urut').val();

            var formData = $('#form1').serializeArray();
            //meng uraikan serializeArray
            $.each(formData, function(i, field) {
                data.append(field.name, field.value);
                console.log(field.name, field.value);
            });


            for (let index = 1; index <= count; index++) {

                $.each($('#loc' + index).prop('files'), function(i, field) {
                    let a = $('#loc' + index).prop('files')[i];
                    data.append('loc[' + index + '][' + i + ']', a);
                    // console.log(index,i);
                });

                var inputFile1 = $('#g\\[' + index + '\\]\\[1\\]')[0].files[0];
                data.append('g[' + index + '][1]', inputFile1);

                var inputFile2 = $('#g\\[' + index + '\\]\\[2\\]')[0].files[0];
                data.append('g[' + index + '][2]', inputFile2);

                var inputFile1 = $('#g\\[' + index + '\\]\\[3\\]')[0].files[0];
                data.append('g[' + index + '][3]', inputFile1);

                var inputFile2 = $('#g\\[' + index + '\\]\\[4\\]')[0].files[0];
                data.append('g[' + index + '][4]', inputFile2);

                var inputFile1 = $('#g\\[' + index + '\\]\\[5\\]')[0].files[0];
                data.append('g[' + index + '][5]', inputFile1);

                var inputFile2 = $('#g\\[' + index + '\\]\\[6\\]')[0].files[0];
                data.append('g[' + index + '][6]', inputFile2);

                var inputFile1 = $('#g\\[' + index + '\\]\\[7\\]')[0].files[0];
                data.append('g[' + index + '][7]', inputFile1);
            }

            var type = "POST";
            var ajaxurl = patch + 'store'; // alert(formData); //
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
                        text: 'Simpan Berhasil!',
                        showConfirmButton: false,
                        timer: 1200
                    });
                    console.log('success:', data);

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message,
                        // showConfirmButton: false,
                        // timer: 2400
                    })
                    console.log('Error:', data);
                }
            });

        }
    </script>

@endsection

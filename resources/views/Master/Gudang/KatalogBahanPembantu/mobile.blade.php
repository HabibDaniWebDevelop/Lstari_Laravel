<?php $title = 'Upload Foto Material'; ?>
@extends('layouts.backend-Theme-3.app2')
@section('title', '$title')

@section('Dashboard')
    <h5 class="mb-4 mt-4">{{ $title }}</h5>

@endsection

@section('css')
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.css') !!}">
    <style>

    </style>

@endsection


@section('container')

    <div class="row">
        <div class="col-md-12">
            <div id="menu1">

                <div class="row d-flex justify-content-center mb-4">
                    <div class="col-12">
                        <div class="input-group">
                            <label class="input-group-text btn-primary">Material</label>
                            <select class="form-control selectpicker my-select" id="cari" name="cari"
                                data-live-search="true" data-style="border">
                                <option selected="">Choose...</option>
                                @foreach ($searchs as $search)
                                    <option value="{{ $search->SW }}">{{ $search->Description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.js') !!}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
        integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script>
        $(document).ready(function() {
            $('.my-select').selectpicker();
        });

        $('select[name=cari]').change(function() {
            var cari = $('#cari').val();

            $.get("/Master/Gudang/KatalogBahanPembantu/mobile", {
                cari: cari
            }, function(data) {
                $("#menu1").html(data);
            });

        });

        function Klik_Batal1() {
            location.reload();
        }

        // klik simpan pada modal untuk upload gambar
        function KlikSimpan2() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id = $('#ID').val();
            var Type = $('#Type').val();
            var Remarks = $('#Remarks').val();
            var department2 = $('#department2').val();

            let Image1 = $('#Image1').prop('files')[0]
            let Image2 = $('#Image2').prop('files')[0]
            let Image3 = $('#Image3').prop('files')[0]
            let Image4 = $('#Image4').prop('files')[0]
            let Image5 = $('#Image5').prop('files')[0]
            let Image6 = $('#Image6').prop('files')[0]
            let TechnicalImage = $('#TechnicalImage').prop('files')[0]

            let cek1 = $("#gambar_k1").val();
            let cek2 = $("#gambar_k2").val();
            let cek3 = $("#gambar_k3").val();
            let cek4 = $("#gambar_k4").val();
            let cek5 = $("#gambar_k5").val();
            let cek6 = $("#gambar_k6").val();
            let cek7 = $("#gambar_k7").val();

            data = new FormData()
            data.append('idnee', id)
            data.append('Type', Type)
            data.append('Remarks', Remarks)
            data.append('department2', department2)
            data.append('Image1', Image1)
            data.append('Image2', Image2)
            data.append('Image3', Image3)
            data.append('Image4', Image4)
            data.append('Image5', Image5)
            data.append('Image6', Image6)
            data.append('Image7', TechnicalImage)

            data.append('C_Image1', cek1)
            data.append('C_Image2', cek2)
            data.append('C_Image3', cek3)
            data.append('C_Image4', cek4)
            data.append('C_Image5', cek5)
            data.append('C_Image6', cek6)
            data.append('C_Image7', cek7)

            // let formData = new FormData('#formmodal1');
            var type = "post";
            var ajaxurl = '/Master/Gudang/KatalogBahanPembantu/mobile';

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
                        title: 'Success',
                        text: 'Simpan Berhasil!',
                        showConfirmButton: false,
                        timer: 1000
                    }).then((result) => {
                        window.location.href = "/Master/Gudang/KatalogBahanPembantu/mobile";
                    });

                    // $('#modalinfo').modal('hide');
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.responseJSON.message,
                        showConfirmButton: false,
                        timer: 2400
                    })
                    console.log('Error:', data);
                }
            });

        };
    </script>
@endsection

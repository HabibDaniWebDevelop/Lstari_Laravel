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

        // $(document).ajaxStart(function() {
        //     $(".preloader").show();
        // });
        // $(document).ajaxComplete(function() {
        //     $(".preloader").hide();
        // });

        // console.log({{ session()->get('iduser') }})

        function KlikUbah() {
            $('#action').val('ubah')
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#conscale').prop('disabled', false);
            $('#Posting1').prop('disabled', true);
            $('#btn_ubah').prop('disabled', true);
        }

        function ChangeCari(id) {
            $('#btn-menu .btn').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#conscale').prop('disabled', false);
            $('#Generate').prop('disabled', false);
            $('#Posting1').prop('disabled', true);
            $("#tampil").removeClass('d-none');

            if (id == '0') {
                id = $('#cari').val() + ",1";
                console.log(id);
            } else if (id == '1') {
                id = $('#cari2').val() + ",2";
            } else {
                id = id + ",1";
                $('#Posting1').prop('disabled', true);
            }
            $.get(patch + 'show/1/' + id, function(data) {
                    $("#tampil").html(data);
                    $('#Cetak1').val(id);

                    cekpos = $('#postingstatus').val();
                    if (cekpos == "P") {
                        $("#postinglogo").removeClass('d-none');
                        $('#btn-menu .btn').prop('disabled', true);
                        $('#Batal1').prop('disabled', false);
                        $('#btn_ubah').prop('disabled', true);
                    } else if (cekpos == "A") {
                        $("#postinglogo").addClass('d-none');
                        $('#Simpan1').prop('disabled', true);
                        $('#Posting1').prop('disabled', false);
                        $('#btn_ubah').prop('disabled', false);
                    }
                    $('#Cetak1').prop('disabled', false);
                    $('#upload').prop('disabled', false);
                })
                .fail(function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: xhr.responseText,
                        showConfirmButton: false,
                        timer: 1200
                    })
                    console.log('Error:', xhr.responseText);
                    $('#cari').val('');
                    $('#cari2').val('');
                });
        }

        function ChangeCari2(id) {
            console.log(id);
            $('#' + id).focus();
        }

        function Klik_Batal1() {
            location.reload();
        }

        function Klik_Cetak1() {
            id = $('#idworkallocation').val();
            window.open(patch + 'show/3/' + id, '_blank');
        }

        function Klik_Simpan1() {
            let action = $('#action').val()
            if (action == 'simpan') {
                SimpanNTHKO()
                return
            } else if (action == 'ubah'){
                UbahNTHKO()
                return
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Upss Error !',
                    text: "Invalid Action",
                })
                return
            }
        }

        function UbahNTHKO() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // get nomorNTHKO
            let nomorNTHKO = $('#cari2').val()
            // get next_process 
            let next_process = $('.next-process')
            // get weight_item
            let weight_item = $('.weight-item')

            // check length next_process and weight_item must be the same
            if (next_process.length != weight_item.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Upss Error !',
                    text: "Panjang Next Process dan Panjang Berat Item Tidak Sama",
                })
                return
            }

            // check if nomorNTHKO is null or blank
            if (nomorNTHKO == "" || nomorNTHKO == null){
                Swal.fire({
                    icon: 'error',
                    title: 'Upss Error !',
                    text: "NTHKO belum terpilih.",
                })
                return
            }


            let list_next_process = []
            let list_weight_item = []

            // loop by length of next_process and weight_item
            for (let index = 0; index < next_process.length; index++) {
                // add next_process to list
                list_next_process.push($(next_process[index]).val())
                // add weight_item to list
                list_weight_item.push($(weight_item[index]).val())
            }

            json_data = {
                nomorNTHKO:nomorNTHKO,
                next_product:list_next_process,
                berat_items:list_weight_item
            }

            let ajaxurl = patch + 'update';
            $.ajax({
                type: 'PUT',
                url: ajaxurl,
                data: json_data,
                dataType: 'json',
                success: function(data) {
                    $('#Simpan1').prop('disabled', true);
                    $('#Posting1').prop('disabled', false);
                    // set action to update
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Update Berhasil!',
                        showConfirmButton: false,
                        timer: 1200
                    })
                    console.log('success:', data);
                    id = $('#cari2').val();
                    ChangeCari(id);
                    return
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1200
                    })
                    console.log('Error:', data);
                    return
                }
            });

        }

        function SimpanNTHKO() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#form1').serializeArray();
            var type = "POST";
            var ajaxurl = patch + 'store';

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    $('#Simpan1').prop('disabled', true);
                    $('#Posting1').prop('disabled', false);
                    // set action to update
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Simpan Berhasil!',
                        showConfirmButton: false,
                        timer: 1200
                    })
                    console.log('success:', data);
                    id = $('#cari2').val();
                    ChangeCari(id);

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1200
                    })
                    console.log('Error:', data);
                }
            });
        }

        function Klik_Posting1() {
            $('#Posting1').prop('disabled', true);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#form1').serializeArray();

            var type = "POST";
            var ajaxurl = patch + 'posting'; // alert(formData); //
            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Posting Berhasil!',
                        showConfirmButton: false,
                        timer: 1200
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#Posting1').prop('disabled', true);
                            id = $('#cari2').val();
                            ChangeCari(id);
                        }
                    });
                    console.log('success:', data);

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1200
                    })
                    console.log('Error:', data);
                }
            });
        }

        function calculateWeight() {
            let berat_qc = 0
            let berat_cor = 0
            let berat_rep = 0
            let berat_sc = 0
            let berat_varp = 0
            let berat_sepuh = 0

            // get weight of each item with class 'next-process'
            let items = $('.next-process')
            for (let index = 0; index < items.length; index++) {
                let ord = index+1
                let berat = $('#'+ord).val()
                let nextProcess = $(items[index]).val()

                // case if nextProcess if 753 then it will weight to berat_qc
                if (nextProcess == 753){
                    berat_qc+= parseFloat(berat)
                }

                // case if nextProcess if 256 then it will weight to berat_cor
                if (nextProcess == 256){
                    berat_cor+= parseFloat(berat)
                }

                // case if nextProcess if 254 then it will weight to berat_rep
                if (nextProcess == 254){
                    berat_rep+= parseFloat(berat)
                }

                // case if nextProcess if 98 then it will weight to berat_sc
                if (nextProcess == 98){
                    berat_sc+= parseFloat(berat)
                }

                // case if nextProcess if 2234 then it will weight to berat varp
                if (nextProcess == 2234){
                    berat_varp+= parseFloat(berat)
                }

                // case if nextProcess if 260 then it will weight to berat sepuh
                if (nextProcess == 260){
                    berat_sepuh+= parseFloat(berat)
                }
            }

            // set berat
            $('#berat_qc').val(berat_qc.toFixed(2))
            $('#berat_cor').val(berat_cor.toFixed(2))
            $('#berat_rep').val(berat_rep.toFixed(2))
            $('#berat_sc').val(berat_sc.toFixed(2))
            $('#berat_varp').val(berat_varp.toFixed(2))
            $('#berat_sepuh').val(berat_sepuh.toFixed(2))
        }
    </script>

@endsection

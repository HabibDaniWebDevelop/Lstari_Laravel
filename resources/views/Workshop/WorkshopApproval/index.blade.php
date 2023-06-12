<?php $title = 'Monitoring and Approval SPK Mekanikal / Elektronikal / IT / Laser'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">WorkshopApproval</li>
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

                @include('Workshop.WorkshopApproval.data')

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik"
        style="display:none; position: absolute; z-index: 9999;">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikedit"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>

@endsection

@section('script')

    <script>
        //patch lokasi modul
        var patch = '/Workshop/WorkshopApproval/';

        $(document).ready(function() {
            Klik_Cari1();
        });

        function Klik_Cari1() {

            let dari = $('#dari').val();
            let hingga = $('#hingga').val();

            let datas = {
                "dari": dari,
                "hingga": hingga,
            }

            // alert(dari);
            $("#tampil").removeClass('d-none');
            $.get(patch + 'show/1', datas, function(data) {
                $("#tampil").html(data);
            });
        }

        $(window).ready(function() {
            let time = 1800
            setInterval(function() {
                time--;
                $('#time').html(time);
                if (time === 0) {
                    location.reload()
                }
            }, 1000);
        });

        function refrsh_page() {
            location.reload(true);
        }

        function lihat(id, id2) {

            $("#jodulmodal1").html('Detail Kerja WorkShop');
            $('#modalformat').attr('class', 'modal-dialog modal-xl');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Tambah');

            console.log(id, id2);
            let datas = {
                "id": id,
                "ordinal": id2,
            }

            $.get(patch + 'show/2', datas, function(data) {
                // console.log(data);
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        };

        function Tambah() {

            let cek = $('#karyawan_input').val();
            let formData = $('#tambah').serialize();
            console.log(cek, formData);

            if (cek === null) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Karyawan harus di pilih !',
                    showConfirmButton: false,
                    timer: 1200
                })
                return;
            }

            $.ajax({
                url: patch + 'tambah',
                type: 'GET',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Tambah Berhasil!',
                        showConfirmButton: false,
                        timer: 1200
                    });
                    console.log('success:', data);

                    lihat(data.id, data.ordinal);

                    $("#wikwik").html(data);
                    $("#karyawan_input").val('');
                    $("#pekerjaan").val('');
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upss Error !',
                        text: data.responseJSON.message,
                        showConfirmButton: false,
                        timer: 2400
                    })
                    console.log('Error:', data);
                }
            })
            return false;
        }

        function klikhapus(id, id2) {

            let datas = {
                "id": id,
                "ordinal": id2,
            }

            let no_urut = $("#no_urut").val();

            $.get(patch + 'show/3', datas, function(data) {

                if (data.success === true) {
                    console.log(data);
                    lihat(data.id, no_urut);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Gagal Hapus !',
                        showConfirmButton: false,
                        timer: 1200
                    })
                }
            });
        }

        function ubah() {

            let formData = $('#form2').serialize();
            $.get(patch + 'show/5', formData, function(data) {

                if (data.success === true) {} else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Gagal Ubah !',
                        showConfirmButton: false,
                        timer: 1200
                    })
                }
            });
        }

        function klikedit(id, id2) {
            $('#karyawan_input' + id2).prop('disabled', false);
        }

        function KlikSimpan1() {
            console.log('simpan');

            var tgl_selesai = $("#tgl_selesai").val();
            var hasil = $("#hasil").val();
            var mulai = $('#mulai').val();
            var karyawan_input = $('#karyawan_input').val();


            console.log('simpan', tgl_selesai, hasil, mulai, karyawan_input);

            if (tgl_selesai == '' || hasil == null || (karyawan_input == null && mulai == undefined)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Ada Data Yang Belum Diisi !',
                    showConfirmButton: false,
                    timer: 1200
                })
                return;
            } else {
                var formData = $('#tambah, #form2').serialize();
                $.get(patch + 'show/4', formData, function(data) {

                    if (data.success === true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: 'Simpan Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        console.log(data);
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Gagal Hapus !',
                            showConfirmButton: false,
                            timer: 1200
                        })
                    }
                });
            }

        }
    </script>

@endsection

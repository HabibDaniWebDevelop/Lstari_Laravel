<?php $title = 'Data UPS'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">IT </li>
        <li class="breadcrumb-item active">DataUPS </li>
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

                @include('IT.MasterDataHardware.DataUPS.data')

            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" onclick="klikedit2()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" onclick="klikcetak2()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
        <!-- <a class="dropdown-item" onclick="klikinfo2()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a> -->
    </div>
@endsection

@section('script')

    <script>
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true
        });

// ----------------------- Klik Tabel -------------------------
$(".klik").on('click', function(e) {
            $('.klik').css('background-color', 'white');

            if ($("#menuklik").css('display') == 'block') {
                $(" #menuklik ").hide();
            } else {
                var top = e.pageY + 15;
                var left = e.pageX - 100;
                window.idfiell = $(this).attr('id');
                var id2 = $(this).attr('id2');
                $("#judulklik").html(id2);

                $(this).css('background-color', '#f4f5f7');
                $("#menuklik").css({
                    display: "block",
                    top: top,
                    left: left
                });
            }
            return false; //blocks default Webbrowser right click menu

        });

    $("body").on("click", function() {
        if ($("#menuklik").css('display') == 'block') {
            $(" #menuklik ").hide();
        }
        $('.klik').css('background-color', 'white');
    });

    $("#menuklik a").on("click", function() {
        $(this).parent().hide();
    });

    function klikedit2() {
        klikedit(window.idfiell);
    }

    function klikcetak2() {
        klikcetak(window.idfiell);
    }

    // ----------------------------------------------------------

        function kliktambah(id) {
            $("#jodulmodal1").html('Form Tambah Data UPS');
            $('#modalformat').attr('class', 'modal-dialog modal-lg');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Tambah');

            $.get('DataUPS/create/', function(data) {
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        }

        function klikcetak(id) {
            window.open('/IT/MasterDataHardware/DataUPS/cetak?id=' + id, '_blank');
        }

        function klikedit(id) {
            $("#jodulmodal1").html('Form Edit Data UPS');
            $('#modalformat').attr('class', 'modal-dialog modal-lg');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Edit');

            $.get(`/IT/MasterDataHardware/DataUPS/${id}/edit`, function(data) {
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        }

        function klikCari() {
            if (event.keyCode === 13) {
                var id = $('#cari').val();
                window.location.replace('/IT/MasterDataHardware/DataUPS/search?id=' + id);
            }
        }
    </script>

@endsection

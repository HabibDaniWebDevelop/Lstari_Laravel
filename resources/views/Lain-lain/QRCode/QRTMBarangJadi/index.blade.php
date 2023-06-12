<?php $title = 'Label Barcode Barang Jadi'; ?>
<?php
if (Auth::check()) {
    $app = 'app';
} else {
    $app = 'app2';
}
?>
@extends('layouts.backend-Theme-3.' . $app)

@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-lain </li>
        <li class="breadcrumb-item active">QRTMBarangJadi</li>
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

                @include('Lain-Lain.QRCode.QRTMBarangJadi.data')

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
        //barcode
        var patch = '/Lain-lain/QRCode/QRTMBarangJadi/';

        function Klik_generate() {
            var formData = $('#form1').serialize();
            // console.log(formData);
            var id = $('#inputsku').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: patch + 'generate',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    // alert(data.datas[1][1]);
                    // console.log(data.datas[1]);

                    var item = [];
                    for (var i = 1; i <= 50; i++) {

                        if (data.datas[i] !== undefined) {
                            // console.log(data.datas[i]);
                            item.push(data.datas[i][1] + '&');
                            item.push(data.datas[i][2] + '&');
                            item.push(data.datas[i][3] + '&');
                            item.push(data.datas[i][4] + '&');
                            item.push(data.datas[i][5] + '&');
                            item.push(data.datas[i][6] + '&');
                        } else {
                            break;
                        }

                    }
                    
                    console.log(item);
                    window.open(patch +'generate/2/' + item + ',', '_blank');
                },
            });
        }

        //cari data nthko
        function cari_data() {
            $('#Batal1').prop('disabled', false);
            $('#generate').prop('disabled', false);
            var id = $('#idnthko').val();
            $.get(patch + 'generate/1/' + id, function(data) {
                $("#tampil").html(data);
            });
        }

        function Klik_Batal1() {
            location.reload();
        }


        //digunakan ketika select option tidak bisa berpindah focuss
        function handler(event) {

            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));

            if (event.keyCode === 39) {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 37) {
                $('[data-index="' + (index - 1).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 13) {
                add(index);
                $('[data-index="' + (index + 6).toString() + '"]').focus();

            }
        }
    </script>
@endsection

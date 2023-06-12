<?php
$title = 'Form Order Produksi';
$menu = '1';
?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">MPC </li>
        <li class="breadcrumb-item active">{{ $title }} </li>
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

                @include('Penjualan.MPC.FormOrderProduksi.data')

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik2" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik2"></div>
        <a class="dropdown-item" id="klikcetak"><span class="tf-icons bx bx-printer"></span>&nbsp;
            Cetak</a>
    </div>

@endsection

@section('script')

    <script>
        function Klik_Baru1() {
            /*$('#btn-menu .btn').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#cari').prop('readonly', true);
            $("#tampil").removeClass('d-none');
            $('#Simpan1').val('baru');

            $.get(patch + 'show/2/0', function(data) {
                $("#tampil").html(data);
            });*/
        }

        function Klik_Simpan1() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = $('#itemworksuggestion').serializeArray();
            $.ajax({ 
                    type: 'POST',
                    url: '/Penjualan/MPC/FormOrderProduksi/simpanws',
                    dataType: 'json',
                    data: formData,                    
                    beforeSend: function () {
                        $(".preloader").show(); 
                    },
                    complete: function () {
                        $(".preloader").fadeOut(); 
                    },
                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: "Form Order Berhasil di-buat!",
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    },
                    error: function(data) {

                    }
                });

        }

        function tambahKeranjang(idprod, varstone, varenamel,varslep, varmarking, kadar, subka, serialno) {

            $('#Simpan1').prop('disabled', false);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            table = document.getElementById("cart").getElementsByTagName('tbody')[0];
            rowCount = table.rows.length+1;
            row = table.insertRow();
            row.id = 'myRow'+rowCount;

            cell0 = row.insertCell(0);
            cell1 = row.insertCell(1);
            cell2 = row.insertCell(2);

            cell0.innerHTML = '<b style="text-align: center;">'+rowCount+'</b>'+
                                '<input type="hidden" id="idprod'+rowCount+'" name="idprod[]" value="'+idprod+'-'+varstone+'-'+varenamel+'-'+varslep+'-'+varmarking+'-'+kadar+'">';
            cell1.innerHTML = '<b style="text-align: center;">'+subka+'-'+serialno+'</b>';
            cell2.innerHTML = '<input type="number" style="text-align: center;" class="form-control" placeholder="Jumlah Order" value="1" id="qty'+rowCount+'" name="qty[]">';

            /*data = {idprod: idprod, kadar: kadar, varstone: varstone, varenamel: varenamel, varslep: varslep, varmarking: varmarking};

            $.ajax({
                type: 'POST',
                url: '/Penjualan/MPC/FormOrderProduksi/cekProduk',
                dataType: 'json',
                data: data, 
                beforeSend: function () {
                    $(".preloader").show(); 
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                }, 
                success: function(data) {
                    
                },
                error: function(data) {

                }
            });*/
        }

        function getModel() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            kadar = $('#kadar').find(":selected").val();
            idsubka = $('#subka').val();
            noawal = $('#noawal').val();
            noakhir = $('#noakhir').val();

            if (kadar === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Harap Pilih Kadar Terlebih Dahulu",
                })
                return;
            }

            else{
                formData = {idsubka: idsubka, noawal: noawal, noakhir: noakhir, kadar: kadar};

                $.ajax({
                    type: 'POST',
                    url: '/Penjualan/MPC/FormOrderProduksi/listProduk',
                    dataType: 'json',
                    data: formData,
                    beforeSend: function () {
                        $(".preloader").show(); 
                    },
                    complete: function () {
                        $(".preloader").fadeOut(); 
                    },
                    success: function(data) {
                        //alert(data.html);
                        //console.log(data);
                        $("#list").html(data.html);
                    },
                    error: function(data) {

                    }
                });
            }
        }
    </script>

@endsection
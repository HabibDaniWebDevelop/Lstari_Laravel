<?php $title = 'Order Komponen'; ?>
<?php $tab='1'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Pelaporan Produksi </li>
        <li class="breadcrumb-item active">Order Komponen </li>
    </ol>
@endsection

@section('css')
     <style>
       .container-p-y:not([class^="pt-"]):not([class*=" pt-"]) {
            padding-top: 10px !important;
        }

        .layout-horizontal .bg-menu-theme .menu-inner > .menu-item {
            margin: 0rem 0 !important;
        }

        @media (min-width: 850px) {
            h2,
                .h2 {
                    font-size: 25px;
                }
        }

        .card-body{
            padding:10px;
        }

        .breadcrumb {
            margin-bottom: 5px !important;
        }

        .table-sm > :not(caption) > * > * {
            padding: 0px !important;
        }

        .mb-4 {
            margin-bottom: 5px !important;
        }

        .demo-inline-spacing > * {
            margin: 5px !important;
        }

    </style>
     {{-- DevExtreme --}}
     <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
     <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">
@endsection

@section('container')
<div class="row mb-4">
    <div class="col-md-12">
      <ul class="nav nav-pills flex-column flex-md-row mb-3" >
        <li class="nav-item">
        <a class="nav-link btn-tab1 {{ ($tab === "1") ? 'active':' ' }}" id="idtab1" data-bs-toggle="tab" href="javascript:void(0);" style="display:none;"><i class="fas fa-chart-bar"></i> Info Form Order Produksi</a>
        </li>
      </ul> 
        <div class="row" id="listkomponen"></div>
    </div>
</div>

@endsection

@section('script')
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.buttons.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/jszip.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/pdfmake.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/vfs_fonts.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.html5.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.print.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.fixedColumns.min.js') !!}"></script>

{{-- DevExtreme --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>



<script>
        $(document).ready(function(){
            var token = $("meta[name='csrf-token']").attr("content");

            $('.nav-link').click(function(){
            var tab = $(this).attr('id');

            if(tab == "idtab1"){show1();}
            else if(tab == "idtab2"){show2();}
            });

            show1();
        });

        function show1(){
            $.ajax({
                    url: '/Produksi/PelaporanProduksi/OrderKomponen/getFilter',            
                    dataType : 'json',
                    type : 'GET',
                    success: function(data)
                    {
                        //console.log(data.html);
                        $("#listkomponen").html(data.html);
                        document.getElementById("listkomponen").style.display = "block";
                        //document.getElementById("stockopname").style.display = "none";
                    },
                });
        }

        function getKomponen(){
            var komponen = $("#komponen").val();
            data = {komponen:komponen};
            $.ajax({
                    url: '/Produksi/PelaporanProduksi/OrderKomponen/getKomponen',            
                    dataType : 'json',
                    type : 'GET',
                    data:data,
                    success: function(data)
                    {
                        //console.log(data.html);
                        $("#tampil").html(data.html);
                        document.getElementById("datas").style.display = "block";
                        // document.getElementById("stockopname").style.display = "none";
                    },
                });
        }

        function Klik_Baru1(){
            $('#Baru1').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', false);
            $('#Cetak1').prop('disabled', true);
            $('#employee').prop('disabled', false);
            $('#tgl').prop('disabled', false);
            $('#sw').prop('disabled', false);
            $('#note').prop('disabled', false);
            $('#jenis').prop('disabled', false);
            $('#komponen').prop('disabled', false);
        }

        function Klik_Batal1() {
            location.reload();
        }

        function Klik_Simpan1() {
            var checkboxes = document.querySelectorAll('input[type=checkbox]');
            checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                console.log("Checkbox " + checkbox.name + " dicentang!");

                var detail = {
                    employee: $("#employee option:selected").val(),
                    tgl: $('#tgl').val(),
                    note: $('#note').val(),
                    komponen: $('#komponen').val()
                }
                var item = [];
                $('.form-check-input:checked').each(function(i, e) {
                    var id = $(this).val();
                    var product = $(this).attr("data-product");
                    var qty = $(this).attr("data-qty");
                    var kadar = $(this).attr("data-kadar");
                    var sw = $(this).attr("data-sw");

                    let dataitems = {
                        id: id,
                        product: product,
                        qty: qty,
                        kadar: kadar,
                        sw: sw
                    }
                    item.push(dataitems);
                });
                var data = {
                    detail: detail,
                    item: item
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var type = "POST";
                var ajaxurl = '/Produksi/PelaporanProduksi/OrderKomponen/saveOrder';
                // alert(formData);
                console.log(data);
                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Transaksi Berhasil Disimpan',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // $('#form1').trigger("reset");
                                // $("#tampil").html('');
                                $('#Batal1').prop('disabled', true);
                                $('#Simpan1').prop('disabled', true);
                                $('#Cetak1').prop('disabled', false);
                                $('#Baru1').prop('disabled', false);
                                $('#sw').val(data.id)
                                $('#cari').val(data.id)
                            }
                        });

                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss Error !',
                            text: 'Transaksi Gagal Tersimpan !',
                            confirmButtonColor: "#913030"
                        })
                        console.log('Error:', data);
                    }
                });
            } else {
                // Swal.fire({
                //             icon: 'warning',
                //             title: 'Warning !',
                //             text: 'Harap Pilih Komponen Yang Akan Diorder !',
                //             confirmButtonColor: "#913030",
                //             timer: 900,
                //         })
                console.log("Checkbox " + checkbox.name + " tidak dicentang!");
                }
            });
        }

        function Klik_Cetak1() {
            id = $('#cari').val();
            window.open('/Produksi/PelaporanProduksi/OrderKomponen/cetak?id=' + id, '_blank');
        }

        function ChangeCari() {
                $('#Cetak1').prop('disabled', false);
                $('#Batal1').prop('disabled', false);
                $('#Simpan1').prop('disabled', true);
                $('#Baru1').prop('disabled', false);
                $("#tampil").removeClass('d-none');
                var id = $('#cari').val();
                var data = {id: id};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/OrderKomponen/lihat',
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {
                        $("#tampil").html(data.html);
                        $('#Cetak1').val(id);
                        document.getElementById("datas").style.display = "block";
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Invalid Request",
                        })
                        return;
                    }
                });
        }

</script>
@endsection
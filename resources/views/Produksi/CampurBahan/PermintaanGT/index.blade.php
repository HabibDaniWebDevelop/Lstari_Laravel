<?php $title = 'Permintaan GT'; ?>
<?php $tab='1'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Campur Bahan</li></li>
        <li class="breadcrumb-item active">Permintaan GT </li>
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
                    url: '/Produksi/CampurBahan/PermintaanGT/getFilter',            
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

        // function pilihjenis(){
        //     var jenis = $("#jenis").val();
        //     data = {jenis:jenis};
        //     $.ajax({
        //             url: '/Produksi/CampurBahan/PermintaanGT/getJenis',            
        //             dataType : 'json',
        //             type : 'GET',
        //             data:data,
        //             success: function(data)
        //             {
        //                 $("#listprod").html(data.html);
        //             },
        //         });
        // }

        function getProduk(){
            var komponen = $("#produk").val();
            data = {komponen:komponen};
            $.ajax({
                url: '/Produksi/CampurBahan/PermintaanGT/getSPK',            
                dataType : 'json',
                type : 'GET',
                data:data,
                success: function(data)
                {
                    $("#tampil").html(data.html);
                    document.getElementById("datas").style.display = "block";
                    var table = $('#tabwo').DataTable({
                        "paging": false,
                        "ordering": true,
                        "info": false,
                        "searching": true,
                        "autoWidth": true,
                        "responsive": true
                    });
                }
            });
        }

        function getspkpatri(){
            var spk = $("#inputspkpatri").val();
            data = {spk:spk};
            $.ajax({
                url: '/Produksi/CampurBahan/PermintaanGT/getSPK',            
                dataType : 'json',
                type : 'GET',
                data:data,
                success: function(data)
                {
                    $("#tampil").html(data.html);
                    document.getElementById("tabkom").style.display = "block";
                    $('#inputmaterial').prop('disabled', false);
                    $('#inputmaterial').focus();
                },
            });
        }

        function scanmaterial(no){
            var idwox = $("#idwo"+no).val();
            var idc = $("#idkadar"+no).val();
            var idw = $("#swo"+no).val();
            var wo = $("#swworkorder"+no).val();
            $.ajax({
                url: '/Produksi/CampurBahan/PermintaanGT/scanMaterial',            
                dataType : 'json',
                type : 'GET',
                success: function(data)
                {
                    $("#material").html(data.html);
                    document.getElementById("material").style.display = "block";
                    $("#inputmaterial").focus();
                    $("#workorder").val(idwox);
                    $("#idcarat").val(idc);
                    $("#labelspk").val(idw);
                    $("#ketwo").val(wo);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
            //alert(idwox);
        }

        function getmaterial(){
            var material = $("#inputmaterial").val();
            var idkadar = $("#idcarat").val();
            var labelwo = $("#labelspk").val();
            //alert(idkadar);
            data = {material:material, idkadar:idkadar, labelwo:labelwo};
            $.ajax({
                url: '/Produksi/CampurBahan/PermintaanGT/getMaterial',            
                dataType : 'json',
                type : 'GET',
                data:data,
                success: function(data)
                {
                    if(data.status === 'OK'){
                        //alert(data.id);
                        $("#id").val(data.id);
                        $("#kadar").val(data.carat);
                        $("#product").val(data.idproduk);
                        $("#weight").val(data.berat);
                        $("#qty").val(data.qty);
                        $("#ordinal").val(data.ordinal);
                        $("#spk").val(data.wo);
                        $("#kode").val(data.produk);
                        $("#deskripsi").val(data.deskripsi);
                        $("#deskadar").val(data.kadar);
                        $("#berat").val(data.berat);
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Upss ... !',
                            text: "Kadar NTHKO Tidak Sesuai Dengan SPK Patri, Harap Scan NTHKO Yang Sesuai.",
                            confirmButtonColor: false
                        })
                        $("#inputmaterial").val('');
                        $("#inputmaterial").focus();
                    }
                   
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Upss ... !',
                        text: "Kadar NTHKO Tidak Sesuai Dengan SPK Patri, Harap Scan NTHKO Yang Sesuai.",
                        confirmButtonColor: "#913030"
                    })
                    console.log('Error:', data);
                }
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
            $('#labelwo').prop('disabled', false);
            $('#produk').prop('disabled', false);
        }

        function Klik_Batal1() {
            location.reload();
        }

        function Klik_Simpan1() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/Produksi/CampurBahan/PermintaanGT/saveOrder',
                data: $('#form1,#from2,#form3').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'Sukses') {
                        $("#idcetak").val(data.id);
                        $("#cari").val(data.id);
                        $("#sw").val(data.id);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Simpan Gagal",
                            timer: 1500,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        //document.getElementById("Simpan1").disabled = true;
                    }
                },
                error: function(xhr) {
                    //document.getElementById("Simpan1").disabled = true;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function Klik_Cetak1() {
            id = $('#cari').val();
            window.open('/Produksi/CampurBahan/PermintaanGT/cetak?id=' + id, '_blank');
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
                    url: '/Produksi/CampurBahan/PermintaanGT/lihat',
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
<?php 
$title = 'NTHKO 3DP Direct Casting'; 
$menu = '1';
?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">3D Printing Direct Casting </li>
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

                @include('R&D.3DPrintingDirectCasting.NTHKO3DPDirectCasting.data')

            </div>
        </div>
    </div>
@endsection

@section('script')

    {{-- @include('layouts.backend-Theme-3.DataTabelButton') --}}

    <script>
        function Klik_Baru1() {
            $('#Baru1').prop('disabled', true);
            $('#Batal1').prop('disabled', false);
            $('#envelope2').prop('disabled', false);
            $('#tanggal2').prop('disabled', false);
           
        }

        function seeenve(){
            var id = $("#envelope2").val();
            $.get('/R&D/3DPrintingDirectCasting/NTHKO3DPDirectCasting/show/2/' + id, function(data) {
                $("#tampil").html(data);
                $('#Cetak1').val(id);
                $("#tampil").removeClass('d-none');
                $("#tutup").removeClass('d-none');
                $('#Simpan1').prop('disabled', false);
                $('#Cetak1').prop('disabled', false);
            });
        }

        // function speky(){
        //     var id = $('#mesin1').val();
        //     var data = {id:id};
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     var type = "POST";
        //     var ajaxurl = '/RnD/TigaDPrintingDirectCasting/NTHKO3DPDirectCasting/speky';
        //     $.ajax({
        //         type: type,
        //         url: ajaxurl,
        //         data: data,
        //         dataType: 'json',
        //         success: function(data) {
        //             $('#material1').val(data.material);
        //             $('#dias').val(data.luas);
        //             $('#thickness1').val(data.ketebalan);
        //         },
        //         error: function(data) {}
        //     });
        // }

        // function proses() {
        //     alert('proses');
        // }

        function ChangeCari(id) {
            $('#Cetak1').prop('disabled', false);
            $('#Batal1').prop('disabled', false);
            $('#Simpan1').prop('disabled', true);
            $('#Baru1').prop('disabled', false);
           
            if (id == '0') {
                id = $('#cari').val();
            }
            $.get('/R&D/3DPrintingDirectCasting/NTHKO3DPDirectCasting/see/1/' + id, function(data) {
                $("#tampil").html(data);
                $('#Cetak1').val(id);
            });
        }

        function Klik_Cetak1() {
            id = $('#Cetak1').val();
            window.open('/R&D/3DPrintingDirectCasting/NTHKO3DPDirectCasting/cetak?id=' + id, '_blank');
        }

        function Klik_Batal1() {
            location.reload();
        }

        function Klik_Simpan1() {
            var detail = {
                tanggal2 : $('#tanggal2').val(),
                employee: $("#employee option:selected").val(),
                envelope2: $('#envelope2').val(),
                mesin1: $('#mesin1').val(),
                material1: $('#material1').val(),
                dias: $('#dias').val(),
                tabug: $('#tabug').val(),
                trak: $('#trak').val(),
                lamp: $('#lamp').val(),
                log1: $('#log1').val(),
                thickness1: $('#thickness1').val(),
                brigness1: $('#brigness1').val(),
                catatan2: $('#catatan2').val()
            }


            let qtygoods = $('.Qtygood1');
            let qtynogoods = $('.Qtynogood1');
            let idms = $('.Idm1');
            let ords = $('.Ord1');
            let skus = $('.Sku1');
            let stls = $('.Stl1');
            let worklists = $('.Worklist1');
            var products = document.getElementsByClassName("Product1");
            var x = products.lenght;
            //alert(x);
            var item = [];
            for (let i = 0; i < products.lenght; i++) {
                var qtygood = $('#qtygood'+i).val();
                var qtynogood = $('#qtynogood'+i).val();
                var idm = $('#idm'+i).val();
                var ord = $('#ord'+i).val();
                var product = $('#product'+i).val();
                var sku = $('#sku'+i).val();
                var stl = $('#stl'+i).val();
                var worklist = $('#worklist'+i).val();

                let dataitems = {
                    idm: idm,
                    ord: ord,
                    product: product,
                    qtygood: qtygood,
                    qtynogood: qtynogood,
                    sku: sku,
                    stl: stl,
                    worklist : worklist
                }
                
                item.push(dataitems);
            };
            //console.log(dataitems);
            //console.log(item);
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
            var ajaxurl = '/R&D/3DPrintingDirectCasting/NTHKO3DPDirectCasting/saveCompletion';
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
                        title: 'Tambah Berhasil!',
                        text: 'Silahkan di cek Kembali'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // $('#form1').trigger("reset");
                            // $("#tampil").html('');
                            $('#Batal1').prop('disabled', true);
                            $('#Simpan1').prop('disabled', true);
                            $('#Cetak1').prop('disabled', false);
                            $('#Baru1').prop('disabled', false);
                            $('#cari').val(data.id)
                        }
                    });

                },
                error: function(data) {
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Upss Error !',
                    //     text: 'Transaksi Gagal Tersimpan !',
                    //     confirmButtonColor: "#913030"
                    // })

                    Swal.fire({
                        icon: 'success',
                        title: 'Data Berhasil Disimapan !',
                        // text: 'Transaksi Gagal Tersimpan !',
                        confirmButtonColor: "#913030"
                    })
                    console.log('Error:', data);
                }
            });
        }

    </script>

@endsection

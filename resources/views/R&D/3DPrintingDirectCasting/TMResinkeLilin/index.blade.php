<?php 
$title = 'TM Resin ke Lilin (Direct Casting)'; 
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

            @include('R&D.3DPrintingDirectCasting.TMResinkeLilin.data')

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
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $("#tampil").removeClass('d-none');

    $.get('/R&D/3DPrintingDirectCasting/TMResinkeLilin/show/2/0', function(data) {
        $("#tampil").html(data);
    });
}

function ChangeCari(id) {
    $('#Cetak1').prop('disabled', false);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);

    $("#tampil").removeClass('d-none');

    if (id == '0') {
        id = $('#cari').val();
    }
    $.get('/R&D/3DPrintingDirectCasting/TMResinkeLilin/show/1/' + id, function(data) {
        $("#tampil").html(data);
        $('#Cetak1').val(id);

        cekpos = $('#postingstatus').val();
        if (cekpos == "P") {
            $("#postinglogo").removeClass('d-none');
        } else {
            $("#postinglogo").addClass('d-none');
        }
    });
}

function Klik_Cetak1() {
    id = $('#Cetak1').val();
    window.open('/R&D/3DPrintingDirectCasting/TMResinkeLilin/cetak?id=' + id, '_blank');
}

function Klik_Batal1() {
    location.reload();
}

function Klik_Simpan1() {

    var detail = {
        employe: $("#employe option:selected").val(),
        tgl: $('#tanggal').val(),
        note: $('#note').val()
    }

    var item = [];
    $('.form-check-input:checked').each(function(i, e) {
        var id = $(this).val();
        var product = $(this).attr("data-product");
        var qty = $(this).attr("data-qty");
        var wo = $(this).attr("data-wo");
        var woi = $(this).attr("data-woi");
        var idm = $(this).attr("data-nthko");
        var ord = $(this).attr("data-nthkoitem");

        let dataitems = {
            id: id,
            product: product,
            qty: qty,
            wo: wo,
            woi: woi,
            idm: idm,
            ord: ord

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
    var ajaxurl = '/R&D/3DPrintingDirectCasting/TMResinkeLilin/store';
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
                    $('#form1').trigger("reset");
                    ChangeCari(data.idm);
                    $('#Simpan1').prop('disabled', true);
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
}
</script>

@endsection
<?php
$title = 'Posting Resin ke Lilin (Direct Casting)';
$menu = '2';
?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
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
function ChangeCari(id) {

    if (id == '0') {
        id = $('#cari').val();
    }
    $.get('/R&D/3DPrintingDirectCasting/TMResinkeLilin/show/1/' + id, function(data) {
        $("#tampil").removeClass('d-none');
        $("#tampil").html(data);
        $('#Cetak1').val(id);

        if ($('#postingstatus').val() == "A") {
            $('#Posting1').prop('disabled', false);
            $("#postinglogo").addClass('d-none');
        } else {
            $('#Posting1').prop('disabled', true);
            $("#postinglogo").removeClass('d-none');
        }
    });
}

function Klik_Cetak1() {
    id = $('#Cetak1').val();
    window.open('/R&D/3DPrintingDirectCasting/TMResinkeLilin/cetak?id=' + id, '_blank');
}

function Klik_Posting1() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    id = $('#idm').val();

    var type = "PUT";
    var ajaxurl = '/Produksi/Lilin/Posting3DPDirectCasting/update/' + id;
    // alert(formData);

    $.ajax({
        type: type,
        url: ajaxurl,
        data: '',
        dataType: 'json',
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'Posting Berhasil!',
                text: 'Silahkan di cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#Posting1').prop('disabled', true);
                }
            });

        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Tersimpan!',
                text: 'Terjadi Kesalahan!',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });

}
</script>

@endsection
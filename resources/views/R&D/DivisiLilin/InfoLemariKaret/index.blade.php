<?php $title = 'Informasi Lokasi Penyimpanan Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R & D </li>
    <li class="breadcrumb-item">Divisi Lilin </li>
    <li class="breadcrumb-item active">Informasi Lokasi Penyimpanan Karet</li>
</ol>
@endsection

@section('container')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                @include('R&D.DivisiLilin.InfoLemariKaret.data')
                <div id="tabellaci" class="col-md-12">

                </div>
            </div>

        </div>
        @include('Setting.publick_function.ViewSelectionModal')
    </div>
</div>

@endsection

@section('script')

<script>
// $("#tabellemari").html();
// $('#tabellemari').show();

// function PilihLemari() {
//     var id = $('#lemari').val();

//     $.get('/R&D/DivisiLilin/InfoLemariKaret/lemari' + id, function(data) {
//         $("#laci").html(data);
//     });
//     // alert('tes');
// }


function ClickLaci() {
    // $('#tampilkanlemari').html();
    let idlemari = $('#lemari').val() //Ambil value lemari
    let idlaci = $('#laci').val() //Ambil value laci

    // let data = {
    //     idlemari: idlemari,
    //     idlaci: idlaci
    // }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // $.get("/R&D/DivisiLilin/InfoLemariKaret/Lemari/" + idlemari + "/" + idlaci, function(data) {
    //     $("#tabellaci").html(data);
    // })

    $.ajax({
        type: "GET",
        url: "/R&D/DivisiLilin/InfoLemariKaret/Lemari/" + idlemari + "/" + idlaci,
        // data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {
            $("#tabellaci").html(data.html);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },

    })
}
</script>

@endsection
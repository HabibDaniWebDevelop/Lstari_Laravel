<?php $title = 'Surat Perintah Kerja Operator Pohon (Direct Casting)'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">SPKO Pohon (Direct Casting)</li>
</ol>
@endsection

@section('container')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-body"> -->
            <div id="tabellaci" class="col-md-12">
                @include('Produksi.Lilin.SPKPohonan.data')
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>

@endsection

@section('script')
@include('layouts.backend-Theme-3.DataTabelButton')
<script>
function Click_Tambah1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Cetakbarkode').prop('disabled', true);
    $("#tampil1").removeClass('form');
    $("#karetdipilih").hide();
    $.get('/Produksi/Lilin/SPKPohonan/form/', function(data) {
        $("#tampil1").html(data);
    });

}

function kadardanrph() {

    $("#tampil").removeClass('d-none');

    var carat = $('#kadar').val();
    var idtm = $('#idtm').val();

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/SPKPohonan/add/' + carat + '/' + idtm,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tampil").html(data.html);
            $("#totalQty").val(data.totalQty);

            jumlahqty();
        }
    });
}


function ChangeCari() {
    IDcari = $('#cari').val();
    $('#Cetak1').prop('disabled', false);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);
    $("#tampil1").removeClass('form');
    $("#tampil").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKPohonan/cari/' + IDcari, function(data) {
        $("#tampil1").html(data);
        $("#tampil").html(data);
    });

}

function Klik_Batal1() {
    location.reload();
}

function closed() {
    $('#Baru1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', false);
    $("#tampil1").removeClass('form');
}

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#Employee').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/SPKPohonan/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
    }
}

function isipiring() { // input form label piring untuk trigger form id piring
    LabelPiring = $('#Plate').val();

    if (LabelPiring !== '') {
        $.get('/Produksi/Lilin/SPKPohonan/Piring/' + LabelPiring, function(data) {
            $('#IdPiring').val(data.IdPir);
        });
    }
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------- simpan
function Klik_Simpan1() {
    // var emp = $('#Employee').val();
    // alert(emp);

    var detail = {
        Employee: $('#Employee').val(),
        tgl: $('#tgl').val(),
        WorkGroup: $('#WorkGroup').val(),
        BoxNo: $('#BoxNo').val(),
        StickPohon: $('#StickPohon').val(),
        Plate: $('#IdPiring').val(),
        note: $('#note').val(),
        kadar: $('#kadar').val(),
        idtm: $('#idtm').val(),
        totalQty: $('#totalQty').val()
    }
    // pindah ke funtion listproduct !!!!!!
    let WorkOrders = $('.WorkOrder');
    let Products = $('.Product');
    let Qtys = $('.Qty');
    let Kadars = $('.Kadar');
    let WorkOrderOrds = $('.WorkOrderOrd');
    let WaxOrders = $('.WaxOrder');
    let WaxOrderOrds = $('.WaxOrderOrd');
    let Tfdcs = $('.Tfdc');
    let Tfdcors = $('.Tfdcor');
    let idworklists = $('.idworklist');
    let Scs = $('.Sc');

    var itemss = [];
    for (let i = 0; i < WorkOrders.length; i++) {
        var WorkOrder = $(WorkOrders[i]).val()
        var product = $(Products[i]).val()
        var Qty = $(Qtys[i]).val()
        var carat = $(Kadars[i]).val()
        var WorkOrderOrd = $(WorkOrderOrds[i]).val()
        var WaxOrder = $(WaxOrders[i]).val()
        var WaxOrderOrd = $(WaxOrderOrds[i]).val()
        var Tfdc = $(Tfdcs[i]).val()
        var Tfdcor = $(Tfdcors[i]).val()
        var idworklist = $(idworklists[i]).val()
        var Sc = $(Scs[i]).val()

        // var worklistord = $(this).attr("data-worklistord");
        let dataitemskokmacet = {
            product: product,
            Qty: Qty,
            carat: carat,
            WorkOrder: WorkOrder,
            WorkOrderOrd: WorkOrderOrd,
            WaxOrder: WaxOrder,
            WaxOrderOrd: WaxOrderOrd,
            Tfdc: Tfdc,
            Tfdcor: Tfdcor,
            idworklist: idworklist,
            Sc: Sc
            // worklistord: worklistord
        }
        itemss.push(dataitemskokmacet);


        console.log(itemss);
    };
    var data = {
        detail: detail,
        itemss: itemss
    }
    console.log(data)

    $("#tampil1").removeClass('form');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log(data);
    $.ajax({
        type: "POST",
        url: '/Produksi/Lilin/SPKPohonan/savespkpohonan',
        data: data,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'Tambah Berhasil!',
                text: 'Silahkan di cek Kembali',
                timer: 800,
                showCancelButton: false,
                showConfirmButton: false
            });

            $('#Batal1').prop('disabled', true);
            $('#Simpan1').prop('disabled', true);
            $('#Cetak1').prop('disabled', false);
            $('#Baru1').prop('disabled', false);
            $('#Employee').prop('disabled', true);
            $('#tgl').prop('disabled', true);
            $('#sw').prop('disabled', true);
            $('#WorkGroup').prop('disabled', true);
            $('#BoxNo').prop('disabled', true);
            $('#Plate').prop('disabled', true);
            $('#StickPohon').prop('disabled', true);
            $('#note').prop('disabled', true);
            $('#note').prop('disabled', true);
            $('#kadar').prop('disabled', true);
            $('#idtm').prop('disabled', true);

            // $("#tampil1").html(data);
            $('#cari').val(data.ID2)
            $('#IDSPKpohonan').val(data.ID2);

            ChangeCari();

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

// --------------------------------------------------------------------------------------------------------------------------------------------------------------- cetak
function jumlahqty(qty, id) {
    console.log(qty);
    var Qtys = $('#tabel1').find('.Qty')

    var total = 0;
    for (let i = 0; i < Qtys.length; i++) {
        var aa = parseInt($(Qtys[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#FormTotalQty').val(total);
}

function Klik_Cetak1() {
    idspkpohon = $('#IDSPKpohonan').val();
    carat = $('#Caratshow').val();
    idtm = $('#idtmshow').val();

    // alert(carat);
    // alert(idtm);

    if (idspkpohon !== '') {
        window.open('/Produksi/Lilin/SPKPohonan/cetak/' + idspkpohon + '/' + carat + '/' + idtm, '_blank');
    }
}

function printbarkode() {

    idspkpohon = $('#IDSPKpohonan').val();

    if (idspkpohon !== '') {
        window.open('/Produksi/Lilin/SPKPohonan/printplate/' + idspkpohon + '/_blank');
    }

}
</script>
{{-- @extends('layouts.backend-Theme-3.XtraScript') --}}
@endsection
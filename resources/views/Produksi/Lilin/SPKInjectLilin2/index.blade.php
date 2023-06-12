<?php $title = 'Surat Perintah Kerja Operator Inject Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('css')

{{-- Lightbox.js --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/js/lightbox.min.js') !!}"></script>
{{-- Bootstrap Select --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
{{-- Fancybox --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/fancybox/fancybox.umd.min.js') !!}"></script>

<style>
#CarouselSPK .swiper-slide img {
    object-fit: contain;
    object-position: center;
    overflow: hidden;
    max-width: 80%;
    max-height: 40vh;
}

#CarouselModel .swiper-slide img {
    object-fit: contain;
    object-position: center;
    overflow: hidden;
    max-width: 80%;
    height: 35vh;
}

#listGroupModel,
#listGroupTukangLuar {
    height: 20vh;
}

#myCard {
    min-height: calc(100vh - 200px);
}

a[data-fancybox] img {
    cursor: zoom-in;
}
</style>

@endsection

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">SPKO Inject Lilin</li>
</ol>
@endsection

@section('container')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-body"> -->

            <div id="tabellaci" class="col-md-12">
                @include('Produksi.Lilin.SPKInjectLilin.data')
            </div>
            <!-- </div> -->
        </div>
        @include('Setting.publick_function.ViewSelectionModal')
    </div>
</div>

@endsection

@section('script')

{{-- Lightbox.js --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/js/lightbox.min.js') !!}"></script>
{{-- Bootstrap Select --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
{{-- Fancybox --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/fancybox/fancybox.umd.min.js') !!}"></script>

@include('layouts.backend-Theme-3.DataTabelButton')
<script>
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

var table = $('#tabel1').DataTable({
    "paging": false,
    "lengthChange": false,
    // "pageLength": 9,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,

});
var printService = new WebSocketPrinter();

function printPDF(data) { //OK
    printService.submit({
        'type': 'BARCODE',
        'url': 'http://192.168.3.100/produksi/ProduksiPDF/' + data + '.pdf'
    });

}

function Klik_Baru1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', true);
    $('#Cetakbarkode').prop('disabled', true);
    $("#tampil1").removeClass('form');
    $("#karetdipilih").hide();
    $.get('/Produksi/Lilin/SPKInjectLilin/form/', function(data) {
        $("#tampil1").html(data);
    });

}

function item() {
    $("#tampil2").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelItem/', function(data) {
        $("#tampil2").html(data);
    });
}

// ------------------------------------------------------------------------------------------------- form trigger operator dan piringan
function getkaryawan() {
    $("#karyawan").keydown(function(event) {
        if (event.keyCode === 13) {
            $("#id_of_button").click();
        }
    });
}

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#IdOperator').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/SPKInjectLilin/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
    }
    $("#IdOperator").keydown(function(event) {
        if (event.keyCode === 13) {
            $("#kadar1").click();
        }
    });
}

function isipiring() { // input form label piring untuk trigger form id piring
    LabelPiring = $('#LabelPiring').val();

    if (LabelPiring !== '') {
        $.get('/Produksi/Lilin/SPKInjectLilin/Piring/' + LabelPiring, function(data) {
            $('#IdPiring').val(data.IdPir);
        });
    }
}

function ChangeCari() {
    let IDWaxInject = $('#cari').val(); //Ambil value form cari

    $('#idwaxinjectorder').val(IDWaxInject);
    $('#action').val('update');
    $('#Baru1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', false);
    $('#Ubah1').prop('disabled', false);
    $("#tampil1").removeClass('form');
    $("#tampil2").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/show/' + IDWaxInject, function(data) {
        $("#tampil1").html(data);
        item();
    });
}

function Klik_Ubah1() {
    // Set action to update
    $('#action').val('update');
    //Ambil value form cari
    let IDWaxInject = $('#cari').val();

    data = {
        IDWaxInject: IDWaxInject
    };

    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', false);
    $('#Ubah1').prop('disabled', true);

    // $.get('/Produksi/Lilin/SPKInjectLilin/edit/' + IDWaxInject, function(data) {
    //     $("#tampil1").html(data);
    //     $('#IdOperator').prop('disabled', false);
    // });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "get",
        url: "/Produksi/Lilin/SPKInjectLilin/edit2",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            var tes = data.employee;
            console.log(tes)
            // $("#tabel1 tbody").empty()
            //set idopertaor

            $('#IdOperator').val(data.employee).change();

            //set label piring
            $('#LabelPiring').val(data.labelpiring)
            $('#Idpiring').val(data.rubberplate)

            // Set TotalQTY
            $('#FormTotalQty').val(data.qtytotal)

            // set kelompok dan group
            $('#kelompok').val(data.workgroup)
            $('#kotak').val(data.boxno)

            // Set stik Pohon
            $('#stickpohon').val(data.treestick)

            // Set Catatan
            $('#catatan').val(data.remarks)
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
            })
            // Set idWaxInjectOrder to blank
            return;
        }
    })

    // totalQty = $('#totalQty').val();

    // alert(items);

    console.log(items);
    $('#FormTotalQty').val(totalQty);
    $('#Simpan1').prop('disabled', false);
    $("#showtabel").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TambahData/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#showtabel").html(data);
        $('#modalproduk').modal('hide');
        $('#spkppicdipilih').val(items);
    });
}

// ---------------------------- vvvvvvvvvvvvvvvvvvvvvvvvvv tabel show vvvvvvvvvvvvvvvvvvvvvvvvvvvvv ----------------------------------------

function add(id, workorder) {
    console.log(workorder);
    console.log(id);
    let wrk = workorder;
    let nok = $('#tabel6 tr').length;
    let no = $('#tabel1 tr').length;
    // console.log(no);
    let trStart = "<tr class='baris' id='tr" + no + "'>"
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value=" + no + "></td>"
    let WorkOrder =
        "<td><input type='text' style='color: #FFF; font-weight: bold;' name='workorder[]' class='WorkOrder form-control form-control-sm fs-6 w-10 text-center bg-dark noSPK' onchange='getSWItemProduct(" +
        no + "," + nok + ")'  id='WorkOrder" +
        no + "' value='" + wrk + "'></td>"
    let Description =
        "<td style='text-align: center'><div class='row'><div class='col-9 p-0'><input type='text' style='color: #FFF; max-width: 100%; font-weight: bold;' class='form-control form-control-sm w-10 fs-6 text-center bg-primary Product' id='Product_" +
        no + "' onchange='getSWProdnodata(this.value," + no +
        ")' value=''></div>" +
        "<div class='col-3 p-0'><button class='btn btn-warning btn-sm' type='button' onclick='nyusahin(" + no + "," +
        workorder +
        ")' id='nyusahin" + no +
        "'><i class='fa fa-plus'></i></button></div></div>" +
        "<div class='row'><span class='badge text-dark bg-light' id='DescriptionProd" + no +
        "'></span></div>" +
        "</td>"
    let Qty =
        '<td class="m-0 p-0"><input style="text-align: center" type="number" class="Qty form-control form-control-lg fs-6 w-20" name="Qty[]"id="Qty' +
        no + '" onkeyup="jumlahqty(this.value, ' + no + ')" value=""></td>'
    let Inject =
        '<td class="m-0 p-0"><input style="text-align: center" type="text" name="Inject[]" class="Inject form-control form-control-lg fs-6 w-100" id="Inject' +
        no + '" style = "font-size:20px;" value =""></td>'
    let Tok =
        '<td class="m-0 p-0"><input style="text-align: center" type="text" class="Tok form-control form-control-lg fs-6 w-100" name="Tok[]" id="Tok' +
        no + '" value=""> </td>'
    let SC =
        '<td class="m-0 p-0"><input style="text-align: center" type="text" class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]" id="Sc' +
        no + '" style="font-weight: bold !important;" value="" placeholder = "Harap diisi" ></td>'
    let Waxorder =
        '<td style="text-align: center"><span style="text-align: center" id="waxorder_' + no +
        '"></span><input type="hidden" name="waxorder[]" class="waxorder form-control form-control-sm fs-6 w-100 " id="waxorder' +
        no + '" value=""></td>'
    let Waxorderord =
        '<td style="text-align: center"><span style="text-align: center" id="waxorderord_' + no +
        '"></span><input type="hidden" class = "waxorderord form-control form-control-sm fs-6 w-100" name = "waxorderord[]" id = "waxorderord' +
        no + '" value = ""></td>'
    let action =
        "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" + no + ")' id='add_" + no +
        "'><i class = 'fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick ='remove(" +
        no + ")' id='remove_" + no + "'><i class='fa fa-minus'></i></button></td>"
    let inputhiddden =
        '<td hidden><input type="hidden" class="Rph form-control form-control-sm fs-6 w-100" name="Rph[]" id="Rph' +
        no + '" value=""></td>' +
        '<td hidden><input type="hidden" class="Ordinal form-control form-control-sm fs-6 w-100" name="Ordinal[]" id="Ordinal' +
        no + '" value=""></td>' +
        '<td hidden><input type="hidden" class="IDWorkOrder form-control form-control-sm fs-6 w-100" name="IDWorkOrder[]" id="IDWorkOrder' +
        no + '" value=""></td>' +
        '<td hidden><input type="hidden" class="purposeitem form-control form-control-sm fs-6 w-100"name="purposeitem[]" id="purposeitem' +
        no + '" value="T"></td>'
    let trEnd = "</tr>"
    let finalItem = ""
    let rowitem = finalItem.concat(trStart, urut, WorkOrder, Description, Qty, Inject, Tok, SC, Waxorder, Waxorderord,
        action,
        inputhiddden, trEnd)
    // console.log(rowitem);
    $("#tabel1 > tbody").append(rowitem);
    no += 1;

    $posisi = "#tabel1 #" + no + " input";
    $($posisi).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        alert(id);
        tambahbaris(id);
    });
    jumlahqty();
    // console.log(id);

    console.log(nok);

    let startkaret =
        "<tr class='klik3' id='" + nok + "'>"
    let buatcheck =
        '<td><input class="form-check-input karet" type="checkbox" name="id[]" id="cek_' + nok +
        '" value = "" data-lokasi = "" disabled ></td>'
    let idkaret =
        '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nok + '"></span>'
    let product =
        '<td> <span class="badge bg-primary" style="font-size:14px;" id="productkaret_' + nok + '"></span></td>'
    let Pcs =
        '<td><span id="Pcs_' + nok + '" value=""></span></td>'
    let kadar =
        '<td><span class="badge" style="font-size:14px; background-color: #444" id="kadarkaret_' + nok +
        '"></span></td>'
    let size =
        '<td><span id="size_' + nok + '" value=""></span></td>'
    let waxusage =
        '<td><span id="waxusage_' + nok + '" value=""></span></td>'
    let transdatekaret =
        '<td><span id="transdatekaret_' + nok + '" value=""></span></td>'
    let status =
        '<td><span id="status_' + nok + '" value=""></span></td>'
    let stonecast =
        '<td><span id="stonecast_' + nok + '" value=""></span></td>'
    let lokasi =
        '<td><span id="lokasi_' + nok + '" value=""></span></td>'
    let active =
        '<td><span id="active_' + nok + '" value=""></span></td>'
    let lihat =
        '<td> <button type="button" class="btn btn-info" id="lihat_' + nok +
        '" value="" onclick="lihat(this.value)"> <span class="fas fa-camera"></span></button></td>'
    let waxin =
        "<td><button type='button' class='btn btn-danger btn-sm' onclick ='removekaret(" + nok + ")' id='remove_" +
        nok +
        "'><i class='fa fa-minus'></i></button></td>"
    let trEndkaret = "</tr>"
    let finalItemkrat = ""
    let rowitemkaret = finalItem.concat(startkaret, buatcheck, idkaret, product, Pcs, kadar, size, waxusage,
        transdatekaret, status, stonecast, lokasi, active, lihat, trEndkaret)
    // console.log(rowitemkaret);
    $("#tabel6 > tbody").append(rowitemkaret);
    no += 1;

    $posisi6 = "#tabel6 #" + no + " input";
    $($posisi6).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi6).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        alert(id);
        tambahbaris(id);
    });
    $(".klik3").on('click', function(e) {

        var id = $(this).attr('id');
        console.log(id);
        if ($(this).hasClass('table-primary')) {
            $(this).removeClass('table-primary');
            $('#cek_' + id).attr('checked', false);
            console.log(id);
        } else {
            $(this).addClass('table-primary');
            $('#cek_' + id).attr('checked', true);
        }
        return false;
    });

}



function nyusahin(id, workorder) {
    console.log(workorder);
    console.log(id);
    let wrk = workorder;
    let nok = $('#tabel6 tr').length;
    let no = $('#tabel1 tr').length;
    // console.log(no);
    let trStart = "<tr class='baris' id='tr" + no + "'>"
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value=" + no + "></td>"
    let WorkOrder =
        "<td><input type='text' style='color: #FFF; font-weight: bold;' name='workorder[]' class='WorkOrder form-control form-control-sm fs-6 w-10 text-center bg-dark noSPK' onchange='getSWItemProduct(" +
        no + "," + nok + ")'  id='WorkOrder" +
        no + "' value='" + wrk + "'></td>"
    let Description =
        "<td>" +
        "<div class='input-group input-group-merge' id='kad'>" +
        "<select class='form-control selectpicker' data-style='border' name='productCategory' id='productCategory' data-live-search='true'>" +
        "@foreach ($ProdSW as $PSW)" +
        "<option value='{{$PSW->ID}}'>{{$PSW->SW}}</option>" +
        "@endforeach" +
        "</select>" +
        "</div>" +
        "<button class='btn btn-warning btn-sm' type='button' onclick='nyusahin(" + workorder + "," + no +
        ")' id='nyusahin" + no +
        "'><i class='fa fa-plus'></i></button>" +
        "</td>"
    let Qty =
        '<td class="m-0 p-0"><input style="text-align: center" type="number" class="Qty form-control form-control-lg fs-6 w-20" name="Qty[]"id="Qty' +
        no + '" onkeyup="jumlahqty(this.value, ' + no + ')" value=""></td>'
    let Inject =
        '<td class="m-0 p-0"><input style="text-align: center" type="text" name="Inject[]" class="Inject form-control form-control-lg fs-6 w-100" id="Inject' +
        no + '" style = "font-size:20px;" value =""></td>'
    let Tok =
        '<td class="m-0 p-0"><input style="text-align: center" type="text" class="Tok form-control form-control-lg fs-6 w-100" name="Tok[]" id="Tok' +
        no + '" value=""> </td>'
    let SC =
        '<td class="m-0 p-0"><input style="text-align: center" type="text" class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]" id="Sc' +
        no + '" style="font-weight: bold !important;" value="" placeholder = "Harap diisi" ></td>'
    let Waxorder =
        '<td style="text-align: center"><span style="text-align: center" id="waxorder_' + no +
        '"></span><input type="hidden" name="waxorder[]" class="waxorder form-control form-control-sm fs-6 w-100 " id="waxorder' +
        no + '" value=""></td>'
    let Waxorderord =
        '<td style="text-align: center"><span style="text-align: center" id="waxorderord_' + no +
        '"></span><input type="hidden" class = "waxorderord form-control form-control-sm fs-6 w-100" name = "waxorderord[]" id = "waxorderord' +
        no + '" value = ""></td>'
    let action =
        "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" + no + ")' id='add_" + no +
        "'><i class = 'fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick ='remove(" +
        no + ")' id='remove_" + no + "'><i class='fa fa-minus'></i></button></td>"
    let inputhiddden =
        '<td hidden><input type="hidden" class="Rph form-control form-control-sm fs-6 w-100" name="Rph[]" id="Rph' +
        no + '" value=""></td>' +
        '<td hidden><input type="hidden" class="Ordinal form-control form-control-sm fs-6 w-100" name="Ordinal[]" id="Ordinal' +
        no + '" value=""></td>' +
        '<td hidden><input type="hidden" class="IDWorkOrder form-control form-control-sm fs-6 w-100" name="IDWorkOrder[]" id="IDWorkOrder' +
        no + '" value=""></td>' +
        '<td hidden><input type="hidden" class="purposeitem form-control form-control-sm fs-6 w-100"name="purposeitem[]" id="purposeitem' +
        no + '" value="T"></td>'
    let trEnd = "</tr>"
    let finalItem = ""
    let rowitem = finalItem.concat(trStart, urut, WorkOrder, Description, Qty, Inject, Tok, SC, Waxorder, Waxorderord,
        action,
        inputhiddden, trEnd)
    // console.log(rowitem);
    $("#tabel1 > tbody").append(rowitem);
    no += 1;

    $posisi = "#tabel1 #" + no + " input";
    $($posisi).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        alert(id);
        tambahbaris(id);
    });

    // console.log(id);

    console.log(nok);

    let startkaret =
        "<tr class='klik3' id='" + nok + "'>"
    let buatcheck =
        '<td><input class="form-check-input karet" type="checkbox" name="id[]" id="cek_' + nok +
        '" value = "" data-lokasi = "" disabled ></td>'
    let idkaret =
        '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nok + '"></span>'
    let product =
        '<td> <span class="badge bg-primary" style="font-size:14px;" id="productkaret_' + nok + '"></span></td>'
    let Pcs =
        '<td><span id="Pcs_' + nok + '" value=""></span></td>'
    let kadar =
        '<td><span class="badge" style="font-size:14px; background-color: #444" id="kadarkaret_' + nok +
        '"></span></td>'
    let size =
        '<td><span id="size_' + nok + '" value=""></span></td>'
    let waxusage =
        '<td><span id="waxusage_' + nok + '" value=""></span></td>'
    let transdatekaret =
        '<td><span id="transdatekaret_' + nok + '" value=""></span></td>'
    let status =
        '<td><span id="status_' + nok + '" value=""></span></td>'
    let stonecast =
        '<td><span id="stonecast_' + nok + '" value=""></span></td>'
    let lokasi =
        '<td><span id="lokasi_' + nok + '" value=""></span></td>'
    let active =
        '<td><span id="active_' + nok + '" value=""></span></td>'
    let lihat =
        '<td> <button type="button" class="btn btn-info" id="lihat_' + nok +
        '" value="" onclick="lihat(this.value)"> <span class="fas fa-camera"></span></button></td>'
    let waxin =
        "<td><button type='button' class='btn btn-danger btn-sm' onclick ='removekaret(" + nok + ")' id='remove_" +
        nok +
        "'><i class='fa fa-minus'></i></button></td>"
    let trEndkaret = "</tr>"
    let finalItemkrat = ""
    let rowitemkaret = finalItem.concat(startkaret, buatcheck, idkaret, product, Pcs, kadar, size, waxusage,
        transdatekaret, status, stonecast, lokasi, active, lihat, trEndkaret)
    // console.log(rowitemkaret);
    $("#tabel6 > tbody").append(rowitemkaret);
    no += 1;

    $posisi6 = "#tabel6 #" + no + " input";
    $($posisi6).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi6).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        alert(id);
        tambahbaris(id);
    });
    $(".klik3").on('click', function(e) {

        var id = $(this).attr('id');
        console.log(id);
        if ($(this).hasClass('table-primary')) {
            $(this).removeClass('table-primary');
            $('#cek_' + id).attr('checked', false);
            console.log(id);
        } else {
            $(this).addClass('table-primary');
            $('#cek_' + id).attr('checked', true);
        }
        return false;
    });

}

function remove(id) {
    document.getElementById("tabel1").deleteRow(id);
    jumlahqty();
}

function removekaret(nok) {
    document.getElementById("tabel6").deleteRow(nok);
}

function lihat(idka) {
    // console.log(idkaret);
    var idkaret = idka

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/SPKInjectLilin/lihat/' + idkaret,
        // dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#modal3").html(data);
            // $('#modalproduk').modal('show');
            $("#modalfotokaret").modal('show');
            // $('#lilinview').html(data)
        },
        error: function(xhr, textStatus, errorThrown) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "ID karet tidak ditemukan",
                confirmButtonColor: "#913030"
            })
            return;
        }
    })
}

function getSWProdnodata(Prod) {
    console.log(Prod)
}

function getswused(id, swused) {
    var id = id;
    var swused = swused;

    $.get('/Produksi/Lilin/SPKInjectLilin/getswused/' + swused, function(data) {});

}

function getSWItemProduct(id, nok) {

    var id = id;
    var nok = nok;
    let work = String($('#WorkOrder' + id).val());
    let Product = String($('#Product_' + id).val());
    totalQty = $('#totalQty').val();
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    // alert(items);
    console.log(items);
    $('#FormTotalQty').val(totalQty);
    $('#Simpan1').prop('disabled', false);
    $("#showtabel").removeClass('d-none');

    console.log(id);
    console.log(work);
    data = {
        id: id,
        work: work,
        Product: Product
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/Produksi/Lilin/SPKInjectLilin/cariSWItemProduct',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {

            if (data.rowcount > 0) {
                $('#workOrder_' + id).val(data.WorkOrder)
                $('#Product_' + id).val(data.Product)
                $('#Qty' + id).val(data.Qty)
                $('#Inject' + id).val(data.Inject)
                $('#waxorder_' + id).text(data.WaxOrder)
                $('#waxorder' + id).val(data.WaxOrder)
                $('#waxorderord_' + id).text(data.WaxOrderOrd)
                $('#waxorderord' + id).val(data.WaxOrderOrd)
                $('#Rph' + id).val(data.Rph)
                $('#Ordinal' + id).val(data.Ordinal)
                $('#IDWorkOrder' + id).val(data.IDWorkOrder)

                $('#cek_' + nok).val(data.idkaret)
                $('#lihat_' + nok).val(data.idkaret)
                $('#idkaret_' + nok).text(data.idkaret)
                $('#productkaret_' + nok).text(data.productkaret)
                $('#Pcs_' + nok).text(data.pcs)
                $('#kadarkaret_' + nok).text(data.kadarkaret)
                $('#size_' + nok).text(data.size)
                $('#waxusage_' + nok).text(data.waxusage)
                $('#transdatekaret_' + nok).text(data.transdatekaret)
                $('#status_' + nok).text(data.status)
                $('#stonecast_' + nok).text(data.stonecast)
                $('#lokasi_' + nok).text(data.lokasi)
                $('#active_' + nok).text(data.active)
                // $('#waxin_' + nok).text(data.waxin)

            } else {
                $('#workOrder_' + id).val('')
                $('#Product_' + id).val('')
                $('#Qty' + id).val('')
                $('#Inject' + id).val('')
                $('#waxorder' + id).val('')
                $('#waxorderord' + id).val('')
                $('#Rph' + id).val('')
                $('#Ordinal' + id).val('')
                $('#IDWorkOrder' + id).val('')
            }
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
            })
            return;
        }
    });
}

function item() {
    let IDWaxInject = $('#cari').val();

    $("#tampilitem").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelItem/' + IDWaxInject, function(data) {
        $("#tab").hide();
        $("#tampilitem").html(data);
        $("#tampilitem").show();
    });
}

function karetdipilih() {
    let IDWaxInject = $('#cari').val();

    $("#tampilitem").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelKaretDipilih/' + IDWaxInject, function(data) {
        $("#tab").hide();
        $("#tampilitem").html(data);
        $("#tampilitem").show();

    });
}

function batu() {
    let IDWaxInject = $('#cari').val();

    $("#tampilitem").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelBatu/' + IDWaxInject, function(data) {
        $("#tab").hide();
        $("#tampilitem").html(data);
        $("#tampilitem").show();

    });
}

function batulama() {
    let IDWaxInject = $('#cari').val();

    $("#tampilitem").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelBatuLama/' + IDWaxInject, function(data) {
        $("#tab").hide();
        $("#tampilitem").html(data);
        $("#tampilitem").show();
    });
}

function totalbatu() {
    let IDWaxInject = $('#cari').val();

    $("#tampilitem").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelTotalBatu/' + IDWaxInject, function(data) {
        $("#tab").hide();
        $("#tampilitem").html(data);
        $("#tampilitem").show();
    });
}

function karetpilihan() {
    let IDWaxInject = $('#cari').val();

    $("#tampilitem").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TabelKaretPilihan/' + IDWaxInject, function(data) {
        $("#tampilitem").html(data);
        $("#tampilitem").show();
    });
}

// -----------------------------------------------------------------  end tabel -----------------------------------------------------------------------------

function Klik_Batal1() {
    location.reload();
}

function Prosesdataedit() {
    totalQty = $('#totalQty').val();
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    alert(kdr + "," + rph);

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    // alert(items);
    console.log(items);
    $('#FormTotalQty').val(totalQty);
    $('#Simpan1').prop('disabled', false);
    $("#showtabel").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TambahDataedit/' + items + '/' + kdr + '/' + rph, function(
        data) {
        $("#showtabel").html(data);
        $('#modalproduk').modal('hide');
    });

}

function Klickdaftarproductlagi() {
    IDwaxinjectorder = $('#cari').val();
    alert(IDwaxinjectorder);

    $.get('/Produksi/Lilin/SPKInjectLilin/klicklagi/' + IDwaxinjectorder, function(data) {

    })
}

function Klickdaftarproduct() {
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    if (kdr !== '' && rph !== '') {
        $.ajax({
            type: "GET",
            url: '/Produksi/Lilin/SPKInjectLilin/ProdukList/' + kdr + '/' + rph,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").fadeOut();
            },
            success: function(data) {
                $("#modal1").html(data);
                $('#modalproduk').modal('show');
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'semua SPK sudah anda proses',
                    timer: 1000,
                    showCancelButton: false,
                    showConfirmButton: false
                })
                console.log('Error:', data);
            }
        });

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Harap Isi Form',
            text: 'Kadar dan RPH terlebih dahulu',
        })
        return
    }
}

function Prosesdata() { // -------------------------------------------------------------- ambil value ID dari checkbox modal 
    totalQty = $('#totalQty').val();
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    // alert(items);
    console.log(items);
    $('#FormTotalQty').val(totalQty);
    $('#Simpan1').prop('disabled', false);
    $("#showtabel").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TambahData/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#showtabel").html(data);
        $('#modalproduk').modal('hide');
        $('#spkppicdipilih').val(items);
    });

}


//--------------------------------------------------------------------------------show tabel berdasarkan value dari chekbox modal
function itemdipilih() {
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    $("#tampilkaret").hide('d-none');
    $.get('/Produksi/Lilin/SPKInjectLilin/TambahData/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#tampilitem").html(data);
        $("#tampilitem").show('d-none');
        $('#idwaxinejctordersudah').val(items);
    });

}

function karetpilihandipilih() {
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    // alert(items);
    $("#tampilitem").hide('d-none');

    $.get('/Produksi/Lilin/SPKInjectLilin/TambahKaret/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#tampilkaret").html(data);
        $("#tampilkaret").show('d-none2');
    });
}

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

function OrderitemDc() {
    let kdr = $('#kadar').val(); //Ambil value kadar
    let rph = $('#rphlilin').val(); //Ambil value rph

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    console.log(items);
    // if (items !== '') {
    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/SPKInjectLilin/TambahKomponenDirect/' + items + '/' + kdr + '/' +
            rph,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#modal2").html(data);
            $('#modalKomponenDirect').modal('show')
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Ada Item Driect casting yang bisa di Order!',
                timer: 1000,
                showCancelButton: false,
                showConfirmButton: false
            })
            console.log('Error:', data);
        }
    });
}

function printSPK3DP() {

    let IDSPK3Dp = $('#hiddenid3dp').val();
    // alert(IDSPK3Dp);

    if (IDSPK3Dp !== '') {
        window.open('/Produksi/Lilin/SPKInjectLilin/PrintSPK3Dp/' + IDSPK3Dp + '/_blank');
    }
}

function SPKkomponen3DP() {

    var itemdcs = [];
    $('.itemdc:checked').each(function(i, e) {
        let id = $(this).val();
        var Rph = $(this).attr("data-Rph");
        var Spk = $(this).attr("data-SPK");
        var Product = $(this).attr("data-product");
        var Qty = $(this).attr("data-Qty");
        var Pasang = $(this).attr("data-Pasang");
        var WaxOrder = $(this).attr("data-waxorder");
        var WaxOrderOrd = $(this).attr("data-waxorderord");
        var Workorder = $(this).attr("data-workorder");
        var IDm = $(this).attr("data-IDM");
        var OrdinalWOI = $(this).attr("data-ordinalWOI");
        var QtySatuPohon = $(this).attr("data-QtySatuPohon");
        // var worklistord = $(this).attr("data-worklistord");
        let dataitems = {
            id: id,
            Rph: Rph,
            Spk: Spk,
            Product: Product,
            Qty: Qty,
            WaxOrder: WaxOrder,
            WaxOrderOrd: WaxOrderOrd,
            Workorder: Workorder,
            Pasang: Pasang,
            IDm: IDm,
            OrdinalWOI: OrdinalWOI,
            QtySatuPohon: QtySatuPohon
            // worklistord: worklistord
        }
        itemdcs.push(dataitems);
    });
    alert(itemdcs);
    console.log(itemdcs);

    var datal = {
        itemdcs: itemdcs
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: '/Produksi/Lilin/SPKInjectLilin/SPK3DP',
        data: datal,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'SPK3DP Berhasil Dibuat!',
                text: 'Silahkan di cetak'
            }).then((result) => {
                if (result.isConfirmed) {

                    $('#hiddenid3dp').val(data.ID3Dp);

                    printSPK3DP();
                }
            });
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Upss Error !',
                text: 'Gagal Membuat SPK !',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });
}

function Prosespilihkaret() {
    var rubbers = [];
    $('.karet:checked').each(function(i, e) {
        let idRubber = $(this).val();
        var lokasi = $(this).attr("data-lokasi");

        let datakaret = {
            idRubber: idRubber,
            lokasi: lokasi
        }
        rubbers.push(datakaret);
    });
    console.log(rubbers);
}

function Klik_Simpan1() {

    //value form
    var detail = {
        idspk: $('#IDSPKINJECT').val(),
        Operator: $('#IdOperator').val(),
        Kadar: $('#kadar').val(),
        Piring: $('#IdPiring').val(),
        Date: $('#date').val(),
        TotalQty: $('#FormTotalQty').val(),
        Kelompok: $('#kelompok').val(),
        Kotak: $('#kotak').val(),
        RphLilin: $('#rphlilin').val(),
        Stickpohon: $('#stickpohon').val(),
        Catatan: $('#catatan').val()
    }

    //value checkbox
    var idcheck = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        idcheck.push(id);
    });
    // alert(idcheck);

    //value data product
    let WorkOrders = $('.WorkOrder');
    let Products = $('.Product');
    let Qtys = $('.Qty');
    let Injects = $('.Inject');
    let Toks = $('.Tok');
    // let Rubbercarats = $('.Rc');
    let Scs = $('.Sc');
    let waxorders = $('.waxorder');
    let waxorderords = $('.waxorderord');
    let Rphs = $('.Rph');
    let Ordinals = $('.Ordinal');
    let IDWorkOrders = $('.IDWorkOrder');
    let purposeitems = $('.purposeitem');
    // let productparts = $('.productpart')

    var items = [];
    for (let i = 0; i < WorkOrders.length; i++) {
        var WorkOrder = $(WorkOrders[i]).val()
        var Product = $(Products[i]).val()
        var Qty = $(Qtys[i]).val()
        var Inject = $(Injects[i]).val()
        var Tok = $(Toks[i]).val()
        // var Rubbercarat = $(Rubbercarats[i]).val()
        var Sc = $(Scs[i]).val()
        var waxorder = $(waxorders[i]).val()
        var waxorderord = $(waxorderords[i]).val()
        var Rph = $(Rphs[i]).val()
        var Ordinal = $(Ordinals[i]).val()
        var IDWorkOrder = $(IDWorkOrders[i]).val()
        var purposese = $(purposeitems[i]).val()
        // var productpart = $(productparts[i]).val()

        let dataitems = {
            WorkOrder: WorkOrder,
            Product: Product,
            Qty: Qty,
            Inject: Inject,
            Tok: Tok,
            // Rubbercarat: Rubbercarat,
            Sc: Sc,
            waxorder: waxorder,
            waxorderord: waxorderord,
            Rph: Rph,
            Ordinal: Ordinal,
            IDWorkOrder: IDWorkOrder,
            purposese: purposese
            // productpart: productpart
        }
        items.push(dataitems);
    }

    // alert(items);

    //value idkaret
    var rubbers = [];
    $('.karet:checked').each(function(i, e) {
        let idRubber = $(this).val();
        var lokasi = $(this).attr("data-lokasi");

        let datakaret = {
            idRubber: idRubber,
            lokasi: lokasi
        }
        rubbers.push(datakaret);
    });
    //alert(rubbers);

    var datas = {
        detail: detail,
        items: items,
        rubbers: rubbers
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // /*Operator !== '' && Piring !== '' && date !== '' &&*/ TotalQty !== ''
    /*&& Kelompok !== '' && Kotak !== '' &&
           stickpohon !== ''*/

    // if (detail - > ['Operator'] !== '') {
    $.ajax({
        type: "POST",
        url: '/Produksi/Lilin/SPKInjectLilin/save',
        data: datas,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 800,
                showCancelButton: false,
                showConfirmButton: false
            });
            $('#IDSPKINJECT').val(data.ID2);
            $('#cari').val(data.ID2);

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

function printbarkode() {

    let IDWaxInject = $('#cari').val();
    let IDWaxInjectform = $('#IDSPKINJECT').val();

    if (IDWaxInject !== '' || IDWaxInjectform !== '') {
        window.open('/Produksi/Lilin/SPKInjectLilin/PrintBarkode/' + IDWaxInject + '/_blank');
        onsole.log(id);
        printPDF(id);
    }
}

function printbarkodetes() {

    let IDWaxInject = $('#cari').val();
    let IDWaxInjectform = $('#IDSPKINJECT').val();

    // data = {
    //     IDWaxInject: IDWaxInject,
    //     IDWaxInjectform: IDWaxInjectform
    // };

    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    // $.ajax({
    //     url: '/Produksi/Lilin/SPKInjectLilin/PrintBarkodetes/',
    //     beforeSend: function() {
    //         openModal();
    //     },
    //     data: data,
    //     dataType: 'json',
    //     type: 'POST',
    //     success: function(data) {
    //         printPDF(data.id);
    //     },
    //     complete: function() {
    //         closeModal();
    //     },
    // });

    if (IDWaxInject !== '' || IDWaxInjectform !== '') {
        window.open('/Produksi/Lilin/SPKInjectLilin/PrintSPK2/' + IDWaxInject + '/_blank');
    }

}

function Klik_Cetak1() {
    let IDWaxInject = $('#cari').val();
    let IDWaxInjectform = $('#IDSPKINJECT').val();

    if (IDWaxInject !== '' || IDWaxInjectform !== '') {
        window.open('/Produksi/Lilin/SPKInjectLilin/PrintSPK/' + IDWaxInject + '/_blank');
    }
}

function Klik_Cetak2() {
    let Idspk3dp = $('#cari').val();
    if (Idspk3dp !== '') {
        window.open('/Produksi/Lilin/SPKInjectLilin/PrintSPK3Dp/' + Idspk3dp + '/_blank');
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Upss Error !',
            text: 'Masukkan ID SPK 3dp di Kolom Pencarian !',
            confirmButtonColor: "#913030"
        })
    }
}


table.buttons().container().appendTo('#tabel1_wrapper .col-md-6:eq(0)');
</script>

@endsection
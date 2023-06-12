<?php $title = 'Surat Perintah Kerja Operator Inject Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('css')

{{-- Lightbox.js --}}
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/css/lightbox.min.css') !!}">
{{-- Bootstrap Select --}}
<link rel="stylesheet"
    href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
{{-- Fancybox --}}
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/fancybox/fancybox.min.css') !!}">

<style>
#listGroupModel,
#listGroupTukangLuar {
    height: 20vh;
}
</style>

@endsection

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
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
    "searching": false,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,
});
var table = $('#tabel2').DataTable({
    "paging": false,
    "lengthChange": false,
    // "pageLength": 9,
    "searching": false,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,
});
var table = $('#tabel3').DataTable({
    "paging": false,
    "lengthChange": false,
    // "pageLength": 9,
    "searching": false,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,
});
var table = $('#tabel5').DataTable({
    "paging": false,
    "lengthChange": false,
    // "pageLength": 9,
    "searching": false,
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

    // let today = new Date().toLocaleDateString()
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', true);
    $('#Ubah1').prop('disabled', true);

    // $("#karetdipilih").hide();

    $('#IDSPKINJECT').prop('readonly', true);
    $('#IDSPKINJECT').val('');
    $('#Cetakbarkode').prop('disabled', true);
    $('#date').prop('readonly', false);

    // $('#date').prop('disabled', false);
    $('#FormTotalQty').prop('readonly', true);
    $('#FormTotalQty').val('');
    // $('#FormTotalQty').prop('disabled', true);

    $('#IdOperator').prop('readonly', false);
    $('#IdOperator').val('');
    // $('#IdOperator').prop('disabled', false);
    $('#kelompok').prop('readonly', false);
    $('#kelompok').val('');
    // $('#kelompok').prop('disabled', false);
    $('#kotak').prop('readonly', false);
    $('#kotak').val('');

    $('#kadar').prop('disabled', false);
    $('#kadar').val('');
    $('#rphlilin').prop('readonly', false);
    $('#rphlilin').val('');
    $('#daftarpro').prop('disabled', false);
    $('#daftarpro').val('');

    $('#LabelPiring').prop('readonly', false);
    $('#LabelPiring').val('');
    // $('#IdPiring').prop('hidden', false);
    $('#stickpohon').prop('disabled', false);
    $('#stickpohon').val('');
    $('#stickpohonshow').prop('hidden', true);
    $('#stickpohonshow').val('');

    $('#catatan').prop('readonly', false);
    $('#catatan').val('');

    $('#show').prop('hidden', true);

    $('#from').empty();
    //set idspko
    $('#IDSPKINJECT').prop('disabled', false);
    $('#date').prop('disabled', false);
    $('#FormTotalQty').prop('disabled', false);
    $('#IdOperator').prop('disabled', false);
    // $('#NamaOperator').prop('disabled', false);
    $('#kelompok').prop('disabled', false);
    $('#kotak').prop('disabled', false);
    $('#kadar').prop('hidden', false);
    $('#rphlilin').prop('disabled', false);
    $('#LabelPiring').prop('disabled', false);
    // $('#IdPiring').prop('disabled', false);
    $('#stickpohon').prop('hidden', false);
    $('#catatan').prop('disabled', false);

    $('#daftarpro').prop('hidden', false);
    $('#kadarshow').prop('hidden', true);
    $('#stickpohonshow').prop('hidden', true);

    $('#cari').val('');

    // set show tabel
    $('#tampil1').prop('hidden', true);
    // $('#Action').prop('hidden', false); // action tambah kurang pada tabel
    $('#action').val('simpan'); // hidden untuk update
    // set list tabel
    $('#li2').prop('hidden', true);
    $('#li3').prop('hidden', true);
    $('#li4').prop('hidden', true);
    $('#li6').prop('hidden', false);
    //hide modal
    $('#modalproduk').modal('hide');
    // Set item table
    // kososngkan tabel terlebih dahulu
    $("#tabel1 tbody").empty();
    $("#tabel2 tbody").empty();
    $("#tabel3 tbody").empty();
    $("#tabel4 tbody").empty();
    $("#tabel5 tbody").empty();
    $("#tabel6 tbody").empty();

    $('#kadar').focus();
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
        // $('#LabelPiring').focus();
    }
    $("#IdOperator").keydown(function(event) {
        if (event.keyCode === 13) {
            $("#kadar1").click();
        }
    });
}

function klikkadar() {
    $('#rphlilin').focus();
}

function isipiring() { // input form label piring untuk trigger form id piring
    LabelPiring = $('#LabelPiring').val();

    if (LabelPiring !== '') {
        $.get('/Produksi/Lilin/SPKInjectLilin/Piring/' + LabelPiring, function(data) {
            $('#IdPiring').val(data.IdPir);
        });
        // $('#kelompok').focus();
    }
}

function kelompok() {
    // $('#kotak').focus();
}

function kotak() {
    $('#stickpohon').focus();
}

function stick() {
    $('#catatan').focus();
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "get",
        url: "/Produksi/Lilin/SPKInjectLilin/show/" + IDWaxInject,
        // data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            console.log(data.data.Qty);
            //set from header
            $('#from').empty()
            //set idspko
            $('#IDSPKINJECT').prop('disabled', true)
            $('#date').prop('disabled', true)
            $('#FormTotalQty').prop('disabled', true)
            $('#IdOperator').prop('disabled', true)
            $('#NamaOperator').prop('disabled', true)
            $('#kelompok').prop('disabled', true)
            $('#kotak').prop('disabled', true)
            $('#kadar').prop('hidden', true)
            $('#rphlilin').prop('disabled', true)
            $('#LabelPiring').prop('disabled', true)
            $('#IdPiring').prop('disabled', true)
            $('#stickpohon').prop('hidden', true)
            $('#catatan').prop('disabled', true)

            $('#Cetakbarkode').prop('disabled', false);

            $('#daftarpro').prop('hidden', true)
            $('#show').prop('hidden', false)
            $('#kadarshow').prop('hidden', false)
            $('#stickpohonshow').prop('hidden', false)

            $('#IDSPKINJECT').val(data.data.IDspko)
            $('#date').val(data.data.TransDate)
            $('#FormTotalQty').val(data.data.Qty)
            $('#IdOperator').val(data.data.IDOperator).change();
            $('#NamaOperator').val(data.data.employee)
            $('#kelompok').val(data.data.Kelompok)
            $('#kotak').val(data.data.Kotak)
            $('#kadar').val(data.data.IDKadar).select();
            $('#kadarshow').val(data.data.Kadar)
            $('#idkadar').text(data.data.IDKadar)
            $('#rphlilin').val(data.data.RPH)
            $('#LabelPiring').val(data.data.LabelPiring)
            $('#IdPiring').val(data.data.IDPiring)
            $('#stickpohon').val(data.data.IDstick).select();
            $('#stickpohonshow').val(data.data.StickPohon)
            $('#catatan').val(data.data.Catatan)
            $('#user').val(data.data.UserName)
            $('#entrydate').val(data.data.EntryDate)

            // set show tabel
            $('#tampil1').prop('hidden', false);
            $('#Action').prop('hidden', true);
            // set list tabel
            $('#li2').prop('hidden', false);
            $('#li3').prop('hidden', false);
            $('#li4').prop('hidden', false);
            $('#li6').prop('hidden', true);
            //hide modal
            $('#modalproduk').modal('hide');
            // Set item table
            // kososngkan tabel terlebih dahulu
            $("#tabel1 tbody").empty()
            $("#tabel2 tbody").empty()
            $("#tabel3 tbody").empty()
            $("#tabel4 tbody").empty()
            $("#tabel5 tbody").empty()
            $("#tabel6 tbody").empty()

            let kdr = $('#kadar').val();
            console.log(kdr);

            let no = 1
            data.data.Kpn.forEach(function(value, i) {
                let trStart = "<tr class='baris' id='tr_" + no + "' style='text-align: center'>"
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                // let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    '<td><span class="badge bg-dark" style="font-size:14px;">' + value.WorkOrder +
                    '</span>' +
                    '<input type="hidden" name="workorder[]" class="WorkOrder form-control form-control-sm fs-6 w-100"id="WorkOrder_' +
                    no + '" value="' + value.IDWorkOrder + '"></td > '
                let Komponen =
                    '<td><span class="badge bg-primary" style="font-size:14px;">' + value
                    .SWProduct + '</span> <br>' +
                    '<span class="' + value.dcinfo + '">' + value.DescriptionProduct + '</span>' +
                    '<input type="hidden" name="Product[]" class="IDProduct form-control form-control-sm fs-6 w-100 " id="Product_' +
                    no + '" value="' + value.IDprod + '">' +
                    '</td>'
                let Qty =
                    ' <td class="m-0 p-0">' +
                    '<input readonly  style="text-align: center" type="number" class="Qty form-control form-control-lg fs-6 w-20" name="Qty[]" id = "Qty_' +
                    no + '" value = "' + value.Qty + '" onkeyup="jumlahqty(this.value, ' + no +
                    ')" > ' +
                    '</td>'
                let Inject =
                    '<td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="text" name="Inject[]" class="Inject form-control form-control-lg fs-6 w-100" id="Inject_' +
                    no + '" style="font-size:20px;" value="' + value.Inject + '">' +
                    '</td>'
                let Tok =
                    '<td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="text" class="Tok form-control form-control-lg fs-6 w-100" name="Tok[]" id="Tok_' +
                    no + '" value="' + value.Tok + '">' +
                    '</td>'
                let KebutuhanBatu =
                    '<td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="text" class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]" id="Sc_' +
                    no +
                    '" style="font-weight: bold !important;" value="' + value.StoneCast +
                    '" placeholder="Harap diisi">' +
                    '</td>'
                let WaxOrder =
                    '<td>' + value.WaxOrder + '  ' + value.WaxOrderOrd +
                    '<input type="hidden" name="waxorder[]" class="WaxOrder form-control form-control-sm fs-6 w-100 " id = "WaxOrder_' +
                    no + '" value = "' + value.WaxOrder + '">' +
                    '</td>'
                let WaxOrderOrd =
                    '<td>' + value.StoneNote +
                    '<input type="hidden" class = "WaxOrderOrd form-control form-control-sm fs-6 w-100" name = "waxorderord[]" id = "WaxOrderOrd_' +
                    no + '" value = "' + value.WaxOrderOrd + '">' +
                    '</td>'
                let hidden_Rph_RphOrd_IDWorkOrder =
                    '<td hidden><input type="hidden" class="Rph form-control form-control-sm fs-6 w-100" name="Rph[]" id="Rph_' +
                    no + '" value="' + value.Rph + '">' +
                    '</td>' +
                    '<td hidden>' +
                    '<input type="hidden" class="RphOrdinal form-control form-control-sm fs-6 w-100" name="Ordinal[]" id="RphOrdinal_' +
                    no + '" value="' + value.RphOrdinal + '">' +
                    '</td>' +
                    '<td hidden>' +
                    '< input type ="hidden" class ="IDIDWorkOrder form-control form-control-sm fs-6 w-100" name = "IDWorkOrder[]" id ="IDWorkOrder_' +
                    no + '" value = "' + value.IDWorkOrder + '" >' +
                    '</td>' +
                    '<td hidden><input type="hidden" class="purposeitem form-control form-control-sm fs-6 w-100"name="purposeitem[]" id="purposeitem' +
                    no + '" value="A"></td>' +
                    '<td hidden><input type="hidden" class="FilterO form-control form-control-sm fs-6 w-100"name="FilterO[]" id="FilterO' +
                    no + '" value=""></td>'
                let Action =
                    "<td align='center' class='tambahkurang'><button " + value.cek +
                    " class='btn btn-info btn-sm' type='button' onclick='add(\"" +
                    value.WorkOrder + "\"," + no + "," + kdr + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' " +
                    value.cek + " class='btn btn-danger btn-sm' onclick='remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, WorkOrder, Komponen, Qty, Inject, Tok,
                    KebutuhanBatu, WaxOrder, WaxOrderOrd, hidden_Rph_RphOrd_IDWorkOrder, Action,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            let nokp = 1
            data.data.krtdpl.forEach(function(value, i) {
                let startkaretp =
                    "<tr class='klik99' id='tr_" + nokp + "' style='text-align: center'>"
                let idkaretp =
                    '<td>' + nokp + '</td>' +
                    '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nokp +
                    '">' + value.IDKaret + '</span>'
                let Komponen_dalam_karetp =
                    '<td> <span class="badge bg-primary" style="font-size:14px;" id="productkaret_' +
                    nokp + '">' + value.Product + '</span></td>'
                let Pcsp =
                    '<td><span id="Pcs_' + nokp + '" value="">' + value.Pcs + '</span></td>'
                let kadarp =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="kadarkaret_' +
                    nokp +
                    '">' + value.Kadar + '</span></td>'
                let sizep =
                    '<td><span id="size_' + nokp + '" value="">' + value.Size + '</span></td>'
                let waxusagep =
                    '<td><span id="waxusage_' + nokp + '" value="">' + value.WaxUsage +
                    '</span></td>'
                let transdatekaretp =
                    '<td><span id="transdatekaret_' + nokp + '" value="">' + value.TransDate +
                    '</span></td>'
                let statusp =
                    '<td><span id="status_' + nokp + '" value="">' + value.STATUS + '</span></td>'
                let stonecastp =
                    '<td><span id="stonecast_' + nokp + '" value="">' + value.StoneCast +
                    '</span></td>'
                let lokasip =
                    '<td><span id="lokasi_' + nokp + '" value="">' + value.lokasi + '</span></td>'
                let lihatp =
                    '<td> <button type="button" class="btn btn-info" id="lihat_' + nokp +
                    '" value="" onclick="lihat(this.value)"> <span class="fas fa-camera"></span></button></td>'
                let trEndkaretp = "</tr>"
                let finalItemkaretp = ""
                let rowitemkaretpilihan = finalItemkaretp.concat(startkaretp, idkaretp,
                    Komponen_dalam_karetp, Pcsp, kadarp, sizep, waxusagep,
                    transdatekaretp, statusp, stonecastp, lokasip, lihatp, trEndkaretp)
                $("#tabel2 > tbody").append(rowitemkaretpilihan);
                nokp += 1;
            });

            let nob = 1
            data.data.bt.forEach(function(value, i) {
                let startbatu =
                    "<tr class='baris3' id='tr_" + nob + "' style='text-align: center'>"
                let nomer =
                    '<td>' + nob + '</td>'
                let SPKppic =
                    '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nob +
                    '">' + value.SWWorkOrder + '</span>'
                let Barang_Jadib =
                    '<td> <span class="badge bg-primary" style="font-size:14px;" id="productjadi_' +
                    nob + '">' + value.ProductJadi + '</span></td>'
                let Injectb =
                    '<td><span id="Inject_' + nob + '" value="">' + value.Inject + '</span></td>'
                let JenisBatub =
                    '<td><span id="JenisBatu_' + nob + '" value="">' + value.JenisBatu +
                    '</span></td>'
                let Pesanb =
                    '<td><span id="Pesan_' + nob + '" value="">' + value.Kebutuhan + '</span></td>'
                let at =
                    '<td><span id="EachQty_' + nob + '" value="">' + value.EachQty +
                    '</span></td>'
                let totalb =
                    '<td><span id="Total_' + nob + '" value="">' + value.Total +
                    '</span></td>'
                let keteranganbatu =
                    '<td><span id="StoneNote_' + nob + '" value="">' + value.StoneNote +
                    '</span></td>'
                let trEndbatu = "</tr>"
                let finalbatu = ""
                let rowitembatu = finalbatu.concat(startbatu, nomer, SPKppic, Barang_Jadib, Injectb,
                    JenisBatub, Pesanb, at, totalb, keteranganbatu, trEndbatu)
                $("#tabel3 > tbody").append(rowitembatu);
                nob += 1;
            });

            let notb = 1
            data.data.tbt.forEach(function(value, i) {
                let starttotalbatu =
                    "<tr class='baris5' id='" + notb + "' style='text-align: center'>"
                let nomertb =
                    '<td>' + notb + '</td>'
                let JenisBatutb =
                    '<td><span id="JenisBatu_' + notb + '" value="">' + value.Stone +
                    '</span></td>'
                let totaltb =
                    '<td><span id="Total_' + notb + '" value="">' + value.TotalBatu +
                    '</span></td>'
                let trEndbatutb = "</tr>"
                let finalbatutb = ""
                let rowitembatutb = finalbatutb.concat(starttotalbatu, nomertb,
                    JenisBatutb, totaltb, trEndbatutb)
                $("#tabel5 > tbody").append(rowitembatutb);
                notb += 1;
            });

            let nok = 1
            data.data.krt.forEach(function(value, i) {
                let startkaret =
                    "<tr class='klik7 " + value
                    .cssdipilih + "' id='" + nok + "' style='text-align: center'>"
                let buatcheck =
                    '<td class="nomerchekpilkaret">' + nok + '</td>' +
                    '<td hidden class="checkpilkaret"><input class="form-check-input karet" type="checkbox" name="id[]" id="Karet_' +
                    nok +
                    '" value = "' + value.IDKaret + '" data-lokasi = "' + value.lokasi +
                    '" disabled  ' + value.Dipilih + ' ></td>'
                let idkaret =
                    '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nok +
                    '">' + value.IDKaret + '</span>'
                let Komponen_dalam_karet =
                    '<td> <span class="badge bg-primary" style="font-size:14px;" id="productkaret_' +
                    nok + '">' + value.Product + '</span></td>'
                let Pcs =
                    '<td><span id="Pcs_' + nok + '" value="">' + value.Pcs + '</span></td>'
                let kadar =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="kadarkaret_' +
                    nok +
                    '">' + value.Kadar + '</span></td>'
                let size =
                    '<td><span id="size_' + nok + '" value="">' + value.Size + '</span></td>'
                let waxusage =
                    '<td><span id="waxusage_' + nok + '" value="">' + value.WaxUsage +
                    '</span></td>'
                let transdatekaret =
                    '<td><span id="transdatekaret_' + nok + '" value="">' + value.TransDate +
                    '</span></td>'
                let status =
                    '<td><span id="status_' + nok + '" value="">' + value.STATUSk + '</span></td>'
                let stonecast =
                    '<td><span id="stonecast_' + nok + '" value="">' + value.StoneCast +
                    '</span></td>'
                let lokasi =
                    '<td><span id="lokasi_' + nok + '" value="">' + value.lokasi + '</span></td>'
                let active =
                    '<td><span id="active_' + nok + '" value="">' + value.Active + '</span></td>'
                let lihat =
                    '<td> <button type="button" class="btn btn-info" id="lihat_' + nok +
                    '" value="" onclick="lihat(this.value)"> <span class="fas fa-camera"></span></button></td>'
                let trEndkaret = "</tr>"
                let finalItemkaret = ""
                let rowitemkaret = finalItemkaret.concat(startkaret, buatcheck, idkaret,
                    Komponen_dalam_karet, Pcs, kadar, size, waxusage,
                    transdatekaret, status, stonecast, lokasi, active, lihat, trEndkaret)
                // console.log(rowitemkaret);
                $("#tabel6 > tbody").append(rowitemkaret);
                nok += 1
            });

            $('.tambahkurang').prop('hidden', true)
        },

        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
            })
            // Set idWaxInjectOrder to blank
            $("#idWaxInjectOrder").val("")
            return;
        }
    })
}

function Klik_Ubah1() {
    $('#action').val('update')

    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Ubah1').prop('disabled', true);

    $('#IDSPKINJECT').prop('disabled', false)
    $('#date').prop('disabled', false)
    $('#FormTotalQty').prop('disabled', false)
    $('#IdOperator').prop('disabled', false)
    $('#kelompok').prop('disabled', false)
    $('#kotak').prop('disabled', false)
    $('#rphlilin').prop('disabled', false)
    $('#LabelPiring').prop('disabled', false)
    $('#catatan').prop('disabled', false)
    $('#daftarpro').prop('hidden', true)

    $('#IDSPKINJECT').prop('readonly', true);
    $('#Cetakbarkode').prop('disabled', true);
    $('#date').prop('readonly', false);
    $('#FormTotalQty').prop('readonly', true);
    $('#IdOperator').prop('readonly', false);
    $('#kelompok').prop('readonly', false);
    $('#kotak').prop('readonly', false);
    $('#kadar').prop('disabled', false);
    $('#rphlilin').prop('readonly', true);
    $('#LabelPiring').prop('readonly', false);
    $('#stickpohon').prop('disabled', false);
    $('#stickpohonshow').prop('hidden', false);
    $('#catatan').prop('readonly', false);


    // set list tabel
    $('#li2').prop('hidden', false);
    $('#li3').prop('hidden', false);
    $('#li4').prop('hidden', false);
    $('#li6').prop('hidden', true);

    $('.Qty').prop('readonly', false)
    $('.Tok').prop('readonly', false)
    $('.Sc').prop('readonly', false)
    $('#Action').prop('hidden', false);
    $('.tambahkurang').prop('hidden', false)

    $('.checkpilkaret').prop('hidden', false)
    $('.nomerchekpilkaret').prop('hidden', true)


    $(".klik7").on('click', function(e) {
        // $('.klik').css('background-color', 'white');
        var id = $(this).attr('id');
        if ($(this).hasClass('table-primary')) {
            $(this).removeClass('table-primary');
            $('#Karet_' + id).attr('checked', false);
            console.log(id);
        } else {
            $(this).addClass('table-primary');
            $('#Karet_' + id).attr('checked', true);
        }
        return false;
    });
}
// ---------------------------- vvvvvvvvvvvvvvvvvvvvvvvvvv tabel show vvvvvvvvvvvvvvvvvvvvvvvvvvvvv ----------------------------------------

function add(workroder, urutan, kdr) {
    console.log(urutan + ',' + kdr);

    var workorder = workorder;
    var kdr = kdr;
    let nok = $('#tabel6 tr').length;
    let no = $('#tabel1 tr').length;
    // console.log(no);
    let trStart = "<tr class='baris' id='tr" + no + "' style='text-align: center'>"
    // let kadar_val = value.Kadar == null ? '' : value.Kadar
    // let remarks_val = value.Remarks == null ? '' : value.Remarks
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value=" + no + "></td>"
    let WorkOrder =
        "<td><input type='text' style='color: #FFF; font-weight: bold;' name='workorder[]' class='WorkOrder form-control form-control-sm fs-6 w-10 text-center bg-dark noSPK' onchange='getSWItemProduct(this.value," +
        no + "," + nok + "," + kdr + ")'  id='WorkOrder" +
        no + "' value=''></td>"
    // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
    let trEnd = "</tr>"
    let finalItem = ""
    let rowitem = finalItem.concat(trStart, urut, WorkOrder,
        trEnd)
    $("#tabel1 > tbody").append(rowitem);
    no += 1;


    $posisi = "#tabel1 #" + no + " input";
    $($posisi).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        // alert(id);
        tambahbaris(id);
    });

    // console.log(id);
}

function remove(id) {
    // document.getElementById("tabel1").deleteRow(id);
    $("#tabel1").find("#tr_" + id).remove()
    jumlahqty();
}

function removekaret(nok) {
    $("#tabel1").find("#tr_" + nok).remove()
    // document.getElementById("tabel6").deleteRow(nok);
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

function getSWProdnodata(SwProd) {
    console.log(Swprod)
}

function getSWItemProduct(workorder, urutitem, urutkaret, kadar) {

    var workorder = workorder;
    var urutitem = urutitem;
    var urutkaret = urutkaret;
    var kadar = kadar;

    console.log(workorder + ',' + urutitem + ',' + urutkaret + ',' + kadar);

    // let work = String($('#WorkOrder' + id).val());
    // let Product = String($('#Product_' + id).val());
    // totalQty = $('#totalQty').val();
    // let kdr = $('#kadar').val(); //Ambil value kdr
    // let rph = $('#rphlilin').val(); //Ambil value rph

    data = {
        workorder: workorder,
        urutitem: urutitem,
        urutkaret: urutkaret,
        kadar: kadar
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
            let no = urutitem
            document.getElementById("tabel1").deleteRow(no);

            data.data.datakomponenadd.forEach(function(value, i) {
                let trStart = "<tr class='baris' id='tr_" + no + "' style='text-align: center'>"
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                // let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    '<td><span class="badge bg-dark" style="font-size:14px;">' + value.WorkOrder +
                    '</span>' +
                    '<input type="hidden" name="workorder[]" class="WorkOrder form-control form-control-sm fs-6 w-100"id="WorkOrder_' +
                    no + '" value="' + value.IDWorkOrder + '"></td > '
                let Komponen =
                    '<td><span class="badge bg-primary" style="font-size:14px;">' + value
                    .SWProduct + '</span> <br>' +
                    '<span class="' + value.dcinfo + '">' + value.DescriptionProduct + '</span>' +
                    '<input type="hidden" name="Product[]" class="IDProduct form-control form-control-sm fs-6 w-100 " id="Product_' +
                    no + '" value="' + value.IDprod + '">' +
                    '</td>'
                let Qty =
                    ' <td class="m-0 p-0">' +
                    '<input style="text-align: center" type="number" class="Qty form-control form-control-lg fs-6 w-20" name="Qty[]" id = "Qty_' +
                    no + '" value = "' + value.Qty + '" onkeyup="jumlahqty(this.value, ' + no +
                    ')" > ' +
                    '</td>'
                let Inject =
                    '<td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="text" name="Inject[]" class="Inject form-control form-control-lg fs-6 w-100" id="Inject_' +
                    no + '" style="font-size:20px;" value="' + value.Inject + '">' +
                    '</td>'
                let Tok =
                    '<td class="m-0 p-0">' +
                    '<input style="text-align: center" type="text" class="Tok form-control form-control-lg fs-6 w-100" name="Tok[]" id="Tok_' +
                    no + '" value="">' +
                    '</td>'
                let KebutuhanBatu =
                    '<td class="m-0 p-0">' +
                    '<input style="text-align: center" type="text" class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]" id="Sc_' +
                    no +
                    '" style="font-weight: bold !important;" value="" placeholder="Harap diisi">' +
                    '</td>'
                let WaxOrder =
                    '<td>' + value.waxorder +
                    '<input type="hidden" name="waxorder[]" class="WaxOrder form-control form-control-sm fs-6 w-100 " id="WaxOrder_' +
                    no + '" value = "' + value.waxorder + '">' +
                    '</td>'
                let WaxOrderOrd =
                    '<td>' + value.waxorderord +
                    '<input type="hidden" class = "WaxOrderOrd form-control form-control-sm fs-6 w-100" name = "waxorderord[]" id = "WaxOrderOrd_' +
                    no + '" value = "' + value.waxorderord + '">' +
                    '</td>'
                let hidden_Rph_RphOrd_IDWorkOrder =
                    '<td hidden><input type="hidden" class="Rph form-control form-control-sm fs-6 w-100" name="Rph[]" id="Rph_' +
                    no + '" value="' + value.Rph + '">' +
                    '</td>' +
                    '<td hidden>' +
                    '<input type="hidden" class="RphOrdinal form-control form-control-sm fs-6 w-100" name="Ordinal[]" id="RphOrdinal_' +
                    no + '" value="' + value.RphOrdinal + '">' +
                    '</td>' +
                    '<td hidden>' +
                    '< input type="hidden" class="IDIDWorkOrder form-control form-control-sm fs-6 w-100" name = "IDWorkOrder[]" id ="IDWorkOrder_' +
                    no + '" value = "' + value.IDWorkOrder + '" >' +
                    '</td>' +
                    '<td hidden><input type="hidden" class="purposeitem form-control form-control-sm fs-6 w-100"name="purposeitem[]" id="purposeitem' +
                    no + '" value="T"></td>'
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(\"" +
                    value.WorkOrder + "\"," + no + "," + kadar + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick='remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, WorkOrder, Komponen, Qty, Inject, Tok,
                    KebutuhanBatu, WaxOrder, WaxOrderOrd, hidden_Rph_RphOrd_IDWorkOrder, Action,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            let nok = urutkaret
            data.data.datakaretkomponenadd.forEach(function(value, i) {
                let startkaret =
                    "<tr class='klik8' id='" + nok + "' style='text-align: center'>"
                let buatcheck =
                    '<td><input class="form-check-input karet" type="checkbox" name="id[]" id="Karett_' +
                    nok +
                    '" value = "' + value.IDKaret + '" data-lokasi = "' + value.lokasi +
                    '" disabled ></td>'
                let idkaret =
                    '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nok +
                    '">' + value.IDKaret + '</span>'
                let Komponen_dalam_karet =
                    '<td> <span class="badge bg-primary" style="font-size:14px;" id="productkaret_' +
                    nok + '">' + value.Product + '</span></td>'
                let Pcs =
                    '<td><span id="Pcs_' + nok + '" value="">' + value.Pcs + '</span></td>'
                let kadar =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="kadarkaret_' +
                    nok +
                    '">' + value.Kadar + '</span></td>'
                let size =
                    '<td><span id="size_' + nok + '" value="">' + value.Size + '</span></td>'
                let waxusage =
                    '<td><span id="waxusage_' + nok + '" value="">' + value.WaxUsage +
                    '</span></td>'
                let transdatekaret =
                    '<td><span id="transdatekaret_' + nok + '" value="">' + value.TransDate +
                    '</span></td>'
                let status =
                    '<td><span id="status_' + nok + '" value="">' + value.STATUS + '</span></td>'
                let stonecast =
                    '<td><span id="stonecast_' + nok + '" value="">' + value.StoneCast +
                    '</span></td>'
                let lokasi =
                    '<td><span id="lokasi_' + nok + '" value="">' + value.lokasi + '</span></td>'
                let active =
                    '<td><span id="active_' + nok + '" value="">' + value.Active + '</span></td>'
                let lihat =
                    '<td> <button type="button" class="btn btn-info" id="lihat_' + nok +
                    '" value="" onclick="lihat(this.value)"> <span class="fas fa-camera"></span></button></td>'
                let trEndkaret = "</tr>"
                let finalItemkaret = ""
                let rowitemkaret = finalItemkaret.concat(startkaret, buatcheck, idkaret,
                    Komponen_dalam_karet, Pcs, kadar, size, waxusage,
                    transdatekaret, status, stonecast, lokasi, active, lihat, trEndkaret)
                // console.log(rowitemkaret);
                $("#tabel6 > tbody").append(rowitemkaret);
                nok += 1;
            });
            $(".klik8").on('click', function(e) {
                // $('.klik').css('background-color', 'white');
                var id = $(this).attr('id');
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');
                    $('#Karett_' + id).attr('checked', false);
                    console.log(id);
                } else {
                    $(this).addClass('table-primary');
                    $('#Karett_' + id).attr('checked', true);
                }
                return false;
            });

            jumlahqty();
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

function Klik_Batal1() {
    location.reload();
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

    var SPKPPICs = [];
    $('.SPKPPIC:checked').each(function(i, e) {
        let id = $(this).val();

        SPKPPICs.push(id);
    });

    if (kdr == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "anda belum memilih kadar",
        })
        return;
    }

    // Check idEmployee
    if (rph == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "anda belum memilih rph",
        })
        return;
    }

    if (totalQty == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "anda belum memilih spk PPIC mana yang akan dikerjakan",
        })
        return;
    }

    // let data = {
    //     kdr: kdr,
    //     rph: rph,
    //     items: items
    // }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "get",
        url: "/Produksi/Lilin/SPKInjectLilin/TambahData/" + SPKPPICs + "/" + kdr + "/" + rph,
        // data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // set show tabel
            $('#tampil1').prop('hidden', false);
            //hide modal
            $('#modalproduk').modal('hide');
            $('#checkSWO').val(data.data.SWO);
            // Set item table
            $("#tabel1 tbody").empty() // kososngkan tabel terlebih dahulu
            let no = 1
            data.data.datakomponen.forEach(function(value, i) {
                let trStart = "<tr class='baris' id='tr_" + no + "' style='text-align: center'>"
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                // let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    '<td><span class="badge bg-dark" style="font-size:14px;">' + value.WorkOrder +
                    '</span>' +
                    '<input type="hidden" name="workorder[]" class="WorkOrder form-control form-control-sm fs-6 w-100"id="WorkOrder_' +
                    no + '" value="' + value.IDWorkOrder + '"></td > ' +
                    '<input type="hidden" name="SWWorkorder[]" class="SWWorkOrder" id="SWWorkOrder_' +
                    no + '" value="' + value.WorkOrder + '"></td > '
                let Komponen =
                    '<td><span class="badge bg-primary" style="font-size:14px;">' + value
                    .SWProduct + '</span> <br>' +
                    '<span class="' + value.dcinfo + '">' + value.DescriptionProduct + '</span>' +
                    '<input type="hidden" name="Product[]" class="IDProduct form-control form-control-sm fs-6 w-100 " id="Product_' +
                    no + '" value="' + value.IDprod + '">' +
                    '</td>'
                let Qty =
                    ' <td class="m-0 p-0">' +
                    '<input style="text-align: center" type="number" class="Qty form-control form-control-lg fs-6 w-20" name="Qty[]" id = "Qty_' +
                    no + '" value = "' + value.Qty + '" onkeyup="jumlahqty(this.value, ' + no +
                    ')" > ' +
                    '</td>'
                let Inject =
                    '<td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="text" name="Inject[]" class="Inject form-control form-control-lg fs-6 w-100" id="Inject_' +
                    no + '" style="font-size:20px;" value="' + value.Inject + '">' +
                    '</td>'
                let Tok =
                    '<td class="m-0 p-0">' +
                    '<input style="text-align: center" type="text" class="Tok form-control form-control-lg fs-6 w-100" name="Tok[]" id="Tok_' +
                    no + '" value="">' +
                    '</td>'
                let KebutuhanBatu =
                    '<td class="m-0 p-0">' +
                    '<input style="text-align: center" type="text" class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]" id="Sc_' +
                    no +
                    '" style="font-weight: bold !important;" value="" placeholder="Harap diisi">' +
                    '</td>'
                let WaxOrder =
                    '<td>' + value.waxorder + '  ' + value.waxorderord +
                    '<input type="hidden" name="waxorder[]" class="WaxOrder form-control form-control-sm fs-6 w-100 " id = "Waxorder_' +
                    no + '" value = "' + value.waxorder + '">' +
                    '</td>'
                let WaxOrderOrd =
                    '<td>' + value.StoneNote +
                    '<input type="hidden" class = "WaxOrderOrd form-control form-control-sm fs-6 w-100" name = "waxorderord[]" id = "WaxOrderOrd_' +
                    no + '" value = "' + value.waxorderord + '">' +
                    '</td>'
                let hidden_Rph_RphOrd_IDWorkOrder =
                    '<td hidden><input type="hidden" class="Rph form-control form-control-sm fs-6 w-100" name="Rph[]" id="Rph_' +
                    no + '" value="' + value.Rph + '">' +
                    '</td>' +
                    '<td hidden>' +
                    '<input type="hidden" class="RphOrdinal form-control form-control-sm fs-6 w-100" name="Ordinal[]" id="RphOrdinal_' +
                    no + '" value="' + value.RphOrdinal + '">' +
                    '</td>' +
                    '<td hidden>' +
                    '< input type = "hidden" class = "IDIDWorkOrder form-control form-control-sm fs-6 w-100" name = "IDWorkOrder[]" id ="IDWorkOrder_' +
                    no + '" value = "' + value.IDWorkOrder + '" >' +
                    '</td>' +
                    '<td hidden><input type="hidden" class="purposeitem form-control form-control-sm fs-6 w-100"name="purposeitem[]" id="purposeitem' +
                    no + '" value="A"></td>' +
                    '<td hidden><input type="hidden" class="FilterO form-control form-control-sm fs-6 w-100"name="FilterO[]" id="FilterO' +
                    no + '" value="' + value.cek + '"></td>'
                let Action =
                    "<td align='center'><button " + value.cek +
                    " class='btn btn-info btn-sm tambah' type='button' onclick='add(\"" +
                    value.WorkOrder + "\"," + no + "," + kdr + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm kurang' onclick='remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, WorkOrder, Komponen, Qty, Inject, Tok,
                    KebutuhanBatu, WaxOrder, WaxOrderOrd, hidden_Rph_RphOrd_IDWorkOrder, Action,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            $('#Action').prop('hidden', false);
            $("#tabel6 tbody").empty()
            let nok = 1
            data.data.datakaretkomponen.forEach(function(value, i) {
                let startkaret =
                    "<tr class='klik7' id='" + nok + "' style='text-align: center'>"
                let buatcheck =
                    '<td><input class="form-check-input karet" type="checkbox" name="id[]" id="Karet_' +
                    nok +
                    '" value = "' + value.IDKaret + '" data-lokasi = "' + value.lokasi +
                    '" disabled ></td>'
                let idkaret =
                    '<td> <span class="badge bg-dark" style="font-size:14px;" id="idkaret_' + nok +
                    '">' + value.IDKaret + '</span>'
                let Komponen_dalam_karet =
                    '<td> <span class="badge bg-primary" style="font-size:14px;" id="productkaret_' +
                    nok + '">' + value.Product + '</span></td>'
                let Pcs =
                    '<td><span id="Pcs_' + nok + '" value="">' + value.Pcs + '</span></td>'
                let kadar =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="kadarkaret_' +
                    nok +
                    '">' + value.Kadar + '</span></td>'
                let size =
                    '<td><span id="size_' + nok + '" value="">' + value.Size + '</span></td>'
                let waxusage =
                    '<td><span id="waxusage_' + nok + '" value="">' + value.WaxUsage +
                    '</span></td>'
                let transdatekaret =
                    '<td><span id="transdatekaret_' + nok + '" value="">' + value.TransDate +
                    '</span></td>'
                let status =
                    '<td><span id="status_' + nok + '" value="">' + value.STATUS + '</span></td>'
                let stonecast =
                    '<td><span id="stonecast_' + nok + '" value="">' + value.StoneCast +
                    '</span></td>'
                let lokasi =
                    '<td><span id="lokasi_' + nok + '" value="">' + value.lokasi + '</span></td>'
                let active =
                    '<td><span id="active_' + nok + '" value="">' + value.Active + '</span></td>'
                let lihat =
                    '<td> <button type="button" class="btn btn-info" id="lihat_' + nok +
                    '" value="" onclick="lihat(this.value)"> <span class="fas fa-camera"></span></button></td>'
                let trEndkaret = "</tr>"
                let finalItemkaret = ""
                let rowitemkaret = finalItemkaret.concat(startkaret, buatcheck, idkaret,
                    Komponen_dalam_karet, Pcs, kadar, size, waxusage,
                    transdatekaret, status, stonecast, lokasi, active, lihat, trEndkaret)
                // console.log(rowitemkaret);
                $("#tabel6 > tbody").append(rowitemkaret);
                nok += 1
            });

            $(".klik7").on('click', function(e) {
                // $('.klik').css('background-color', 'white');
                var id = $(this).attr('id');
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');
                    $('#Karet_' + id).attr('checked', false);
                    console.log(id);
                } else {
                    $(this).addClass('table-primary');
                    $('#Karet_' + id).attr('checked', true);
                }
                return false;
            });

            jumlahqty();
            $('#Simpan1').prop('disabled', false);
            $('#IdOperator').focus();

            // if ($('.FilterO').val() == 0) {
            //     $('.tambah').prop('disbled', true);
            //     $('.kurang').prop('disbled', true);
            // } else($('.FilterO').val() == 1) {
            //     $('.tambah').prop('disbled', false);
            //     $('.kurang').prop('disbled', false);
            // }
        },

        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
            })
            // Set idWaxInjectOrder to blank
            $("#idWaxInjectOrder").val("")
            return;
        }
    })

}


function jumlahqty(qty) {
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
    var detail = {
        RphLilin: $('#rphlilin').val()
    }

    var itemdcs = [];
    $('.itemdc:checked').each(function(i, e) {
        let id = $(this).val();
        var Rph = $(this).attr("data-Rph");
        var Spk = $(this).attr("data-SPK");
        var Product = $(this).attr("data-product");
        var Qty = $(this).attr("data-Qty");
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
            IDm: IDm,
            OrdinalWOI: OrdinalWOI,
            QtySatuPohon: QtySatuPohon
            // worklistord: worklistord
        }
        itemdcs.push(dataitems);
    });
    // alert(itemdcs);
    console.log(itemdcs);

    var datal = {
        detail: detail,
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


function Klik_Simpan1() {
    // Get Action let
    action = $('#action').val()
    // rubah value action
    if (action == 'simpan') {
        Simpan()
    } else {
        Ubah()
    }
}

function Simpan() {

    //value form
    var detail = {
        idspk: $('#IDSPKINJECT').val(),
        Date: $('#date').val(),
        TotalQty: $('#FormTotalQty').val(),

        Operator: $('#IdOperator').val(),
        Kelompok: $('#kelompok').val(),
        Kotak: $('#kotak').val(),

        Kadar: $('#kadar').val(),
        RphLilin: $('#rphlilin').val(),

        Piring: $('#IdPiring').val(),
        Stickpohon: $('#stickpohon').val(),

        Catatan: $('#catatan').val(),

        checkSWO: $('#checkSWO').val()
    }

    //value checkbox
    var SPKPPICs = [];
    $('.SPKPPIC:checked').each(function(i, e) {
        let id = $(this).val();

        SPKPPICs.push(id);
    });
    // alert(idcheck);

    //value data product
    let WorkOrders = $('.WorkOrder');
    let IDProducts = $('.IDProduct');
    let Qtys = $('.Qty');
    let Injects = $('.Inject');
    let Toks = $('.Tok');
    // let Rubbercarats = $('.Rc');
    let Scs = $('.Sc');
    let WaxOrders = $('.WaxOrder');
    let WaxOrderOrds = $('.WaxOrderOrd');
    let Rphs = $('.Rph');
    let RphOrdinals = $('.RphOrdinal');
    let purposeitems = $('.purposeitem');
    let IDWorkOrders = $('.IDIDWorkOrder');
    // let productparts = $('.productpart')

    var items = [];
    for (let i = 0; i < WorkOrders.length; i++) {
        var WorkOrder = $(WorkOrders[i]).val()
        var IDProduct = $(IDProducts[i]).val()
        var Qty = $(Qtys[i]).val()
        var Inject = $(Injects[i]).val()
        var Tok = $(Toks[i]).val()
        // var Rubbercarat = $(Rubbercarats[i]).val()
        var Sc = $(Scs[i]).val()
        var WaxOrder = $(WaxOrders[i]).val()
        var WaxOrderOrd = $(WaxOrderOrds[i]).val()
        var Rph = $(Rphs[i]).val()
        var RphOrdinal = $(RphOrdinals[i]).val()
        var purposeitem = $(purposeitems[i]).val()
        var IDWorkOrder = $(IDWorkOrders[i]).val()
        // var productpart = $(productparts[i]).val()

        let dataitems = {
            WorkOrder: WorkOrder,
            IDProduct: IDProduct,
            Qty: Qty,
            Inject: Inject,
            Tok: Tok,
            // Rubbercarat: Rubbercarat,
            Sc: Sc,
            WaxOrder: WaxOrder,
            WaxOrderOrd: WaxOrderOrd,
            Rph: Rph,
            RphOrdinal: RphOrdinal,
            purposeitem: purposeitem,
            IDWorkOrder: IDWorkOrder
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
            $('#idwaxinjectorder').val(data.ID2);
            $('#action').val('update');
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

function Ubah() {
    //value form
    var detail = {
        idspko: $('#IDSPKINJECT').val(),
        Date: $('#date').val(),
        TotalQty: $('#FormTotalQty').val(),

        Operator: $('#IdOperator').val(),
        Kelompok: $('#kelompok').val(),
        Kotak: $('#kotak').val(),

        Kadar: $('#kadar').val(),
        RphLilin: $('#rphlilin').val(),

        Piring: $('#IdPiring').val(),
        Stickpohon: $('#stickpohon').val(),

        Catatan: $('#catatan').val()
    }

    //value data product
    let WorkOrders = $('.WorkOrder');
    let IDProducts = $('.IDProduct');
    let Qtys = $('.Qty');
    let Injects = $('.Inject');
    let Toks = $('.Tok');
    // let Rubbercarats = $('.Rc');
    let Scs = $('.Sc');
    let WaxOrders = $('.WaxOrder');
    let WaxOrderOrds = $('.WaxOrderOrd');
    let Rphs = $('.Rph');
    let RphOrdinals = $('.RphOrdinal');
    let purposeitems = $('.purposeitem');
    let IDWorkOrders = $('.IDIDWorkOrder');

    var items = [];
    for (let i = 0; i < WorkOrders.length; i++) {
        var WorkOrder = $(WorkOrders[i]).val()
        var IDProduct = $(IDProducts[i]).val()
        var Qty = $(Qtys[i]).val()
        var Inject = $(Injects[i]).val()
        var Tok = $(Toks[i]).val()
        // var Rubbercarat = $(Rubbercarats[i]).val()
        var Sc = $(Scs[i]).val()
        var WaxOrder = $(WaxOrders[i]).val()
        var WaxOrderOrd = $(WaxOrderOrds[i]).val()
        var Rph = $(Rphs[i]).val()
        var RphOrdinal = $(RphOrdinals[i]).val()
        var purposeitem = $(purposeitems[i]).val()
        var IDWorkOrder = $(IDWorkOrders[i]).val()
        // var productpart = $(productparts[i]).val()

        let dataitems = {
            WorkOrder: WorkOrder,
            IDProduct: IDProduct,
            Qty: Qty,
            Inject: Inject,
            Tok: Tok,
            // Rubbercarat: Rubbercarat,
            Sc: Sc,
            WaxOrder: WaxOrder,
            WaxOrderOrd: WaxOrderOrd,
            Rph: Rph,
            RphOrdinal: RphOrdinal,
            purposeitem: purposeitem,
            IDWorkOrder: IDWorkOrder
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


    $.ajax({
        type: "PUT",
        url: '/Produksi/Lilin/SPKInjectLilin/Update',
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
                title: 'TerUpdate!',
                text: "Data Berhasil DiUpdate",
                timer: 800,
                showCancelButton: false,
                showConfirmButton: false
            });
            $('#IDSPKINJECT').val(data.ID2);
            $('#cari').val(data.ID2);
            $('#idwaxinjectorder').val(data.ID2);
            $('#action').val('update');
            ChangeCari();
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Upss Error !',
                text: 'Transaksi Gagal TerUpdate !',
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
<?php $title = 'SPKO PLAT COR'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Gips Lebur Cor </li>
    <li class="breadcrumb-item active">SPKO Plat Cor</li>
</ol>
@endsection

@section('css')

<style>
#beratBatu:enabled {
    background: #FCF3CF;
}

#beratBatu:disabled {
    background: #eceef1;
}

#tabel1 th,
td {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: 0px;
    margin-left: 0px;
}

#tabel3 th,
td {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: 0px;
    margin-left: 0px;
}
</style>

@endsection

@section('container')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            @include('Produksi.GipsLeburCor.SPKOPlateCor.data')
            @include('Produksi.GipsLeburCor.SPKOPlateCor.Modal')
            @include('setting.publick_function.ViewSelectionModal')
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Timbangan Script --}}
@include('layouts.backend-Theme-3.timbangan')
{{-- This Page Script --}}
<script>
// Data Table Settings
$(document).on('keypress', 'input,textarea,select', function(e) {
    if (e.which == 13) {

        var posisi = parseFloat($(this).attr('tabindex')) + 1;
        $('[tabindex="' + posisi + '"]').focus();
        console.log(posisi)
        if (posisi != '3') {
            e.preventDefault();
        }
    }
});

$('#tabel1').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": true,
});
$('#tabel2').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": true,
});
$('#tabel3').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": true,
});

function KlikBaru() {
    $('#IDwax').val('')
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_edit").prop('disabled', true)
    $("#btn_batal").prop('disabled', false)
    $("#btn_info").prop('disabled', false)
    $("#btn_simpan").prop('disabled', true)
    // $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"

    // Enable input
    $("#showtambah").prop('hidden', false)
    $("#hiddentambah").prop('hidden', true)

    $("#tabel1 tbody").empty();
    $("#tabel1").prop('hidden', false);
    $("#tabel2").prop('hidden', true);
    $("#tabel3").prop('hidden', true);

    $("#show").prop('hidden', true)
    $("#infoTM").prop('hidden', false)
    $("#infoTM2").prop('hidden', false)

    $("#IDSPKOPlateCor").val('')
    $("#Catatan").val('')
    $("#IDTMPohon").focus()
    $('#action').val('simpan')

    $("#infoposting").text('')


}

function klikBatal() {
    $(".preloader").show();
    window.location.reload()
}

function ListSPKO() {
    console.log(1);
    $("#tabel2").prop('hidden', false);
    $("#tabel3").prop('hidden', true);
}

function ListPosted() {

    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/ListPosted",
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $("#tabel3 tbody").empty();
            $('#tabel3').DataTable().destroy();

        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel3 tbody").empty();
            $("#tabel2").prop('hidden', true);
            $("#tabel3").prop('hidden', false);

            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 " + value.style + " px-0 mx-0' id='" + no + "'>"
                let Urutan =
                    '<td class="px-0 mx-0">' + no + '</td>'
                let SWworkallocation = '<td class="px-0 mx-0"><span id="swworkallocation_' + no +
                    '" value="" class="badge bg-dark" style="font-size:14px;">' + value.spkocor +
                    '</span><input hidden class="workallocationID3" id="workallocationID3_' + no +
                    '" value="' +
                    value.workallocationID + '"></td>'
                let tanggal =
                    '<td><span class="tanggal">' + value.tgl + '</span></td>'
                let kadar =
                    '<td class="px-0 mx-0"><span class="badge" style="font-size:14px; background-color: ' +
                    value
                    .HexColor + '">' + value.Kadar +
                    '</span></td>'
                let TotalWeight =
                    '<td class="px-0 mx-0"><span class="Weight3" id="Weight3_' +
                    no +
                    '">' + value.totalWeight.toFixed(2) + '</span></td>'
                let Action =
                    '<td><button class="btn btn-info btn-sm add" type="button" onclick="lihatisiSPKOPlatcor(' +
                    value.workallocationID + ',' + no +
                    ')"><i class="fas fa-list"></i>&nbsp;&nbsp;Lihat</button ></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, Urutan, SWworkallocation,
                    tanggal, kadar, TotalWeight, Action,
                    trEnd)
                $("#tabel3 > tbody").append(rowitem);
                no += 1;
            });
            JumlahPohon()
            jumlahberat()

            $('#tabel3').DataTable({
                "paging": false,
                "lengthChange": true,
                // "pageLength": 9,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "fixedColumns": true,
            });
            // $("#modal1").html(data);
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
                showCancelButton: false,
                showConfirmButton: false
            })
            return;
        }
    })
}

function lihatisiSPKOPlatcor(workallocationID, no) {
    console.log(workallocationID + "," + no);

    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/isiSPKOplatecor?keyword=" + workallocationID,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $("#tabel4 tbody").empty();
            $('#tabel4').DataTable().destroy();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel4 tbody").empty();
            $("#UserNameAdminSPKOPlateCor").text(data.data.UserName)
            $("#TanggaPembuatanSPKOPlateCor").text(data.data.EntryDate)
            $("#SWSPKOPlateCor").text(data.data.SW)

            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 " + value.style + " px-0 mx-0' id='" + no + "'>"
                let Urutan =
                    '<td class="px-0 mx-0">' + no + '</td>'
                let IDpohon = '<td class="px-0 mx-0"><span id="IDP_' + no +
                    '" value="" class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor + '">' + value.IDPohon +
                    '</span><input hidden class="IDpohon" id="IDPohon_' + no + '" value="' +
                    value.IDPohon + '"></td>'
                let IsiPohon =
                    '<td><span class="isipohon">' + value.Product + '</span></td>'
                let kadar =
                    '<td class="px-0 mx-0"><span class="badge" style="font-size:14px; background-color: ' +
                    value
                    .HexColor + '">' + value.Kadar +
                    '</span></td>'
                let Weight =
                    '<td class="px-0 mx-0"><span class="Weight3" id="Weight3_' +
                    no +
                    '">' + value.Weight.toFixed(2) +
                    '</span></td>'
                let Product =
                    '<td><span class="' + value.batucorinfo + ' jenis" style="font-size:14px;">' +
                    value
                    .Description +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' +
                    no +
                    '">1</span></td>'
                let BatchNo =
                    '<td>' + value.BatchNo + '</td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, Urutan, IDpohon,
                    IsiPohon, kadar, Weight, Product, BatchNo,
                    trEnd)
                $("#tabel4 > tbody").append(rowitem);
                no += 1;
            });
            JumlahPohonisi()

            $('#tabel4').DataTable({
                "paging": false,
                "lengthChange": true,
                // "pageLength": 9,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "fixedColumns": true,
            });
            // $("#modal1").html(data);
            $('#modallihatSPKOPlatecor').modal('show');
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
                showCancelButton: false,
                showConfirmButton: false
            })
            return;
        }
    })

}

function GetDataIDTMPohon() {

    let IDTMpohon = $('#IDTMPohon').val();
    console.log(IDTMpohon);

    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/GetIDTMPohon?keyword=" + IDTMpohon,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').DataTable().destroy();
            $("#tabel1").prop('hidden', false);
            $("#tabel1 tbody").empty();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#btn_baru").prop('disabled', false)
            $('#showgetTM').prop('hidden', false)
            $("#UserNameAdminTM").text(' TM : ' + data.data.UserName)
            $("#TanggaPembuatanTM").text(data.data.EntryDate)
            $('#O').val(data.data.OO)
            // $("#btn_simpan").prop('disabled', false)

            // Set item table
            let no = 1;
            let tab = 5;
            let tab2 = 6;
            let tab3 = 7;
            data.data.items.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 " + value.style + " px-0 mx-0' id='tr_" + value.NoPohon + "'>"
                let Urutan =
                    '<td class="px-0 mx-0"><span class="urutaninput" id="urutaninput_' +
                    no +
                    '">' + no + '</span></td>'
                let IDpohon = '<td class="px-0 mx-0"><span id="IDP_' + no +
                    '" value="" class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor + '">' + value.IDPohon +
                    '</span><input hidden class="IDpohoninput" id="IDPohoninput_' + no +
                    '" value="' +
                    value.IDPohon + '"></td>'
                let NoPohon =
                    '<td class="px-0 mx-0"><span class="badge bg-dark NOPohon" style="font-size:14px;" id="NoPohon_' +
                    no + '" value="">' + value.NoPohon +
                    '</span></td>'
                let WeightNeed =
                    '<td class="px-0 mx-0"><span class="WeightNeedinput" id="WeightNeedinput_' +
                    no +
                    '">' + value.GoldNeed.toFixed(2) + '</span></td>'
                let SPKPPIC =
                    '<td><span id="workorder_' + no + '" value="">' + value.SPKPPIC +
                    '</span></td>'
                let Sisa =
                    '<td><span class="sisainput" id="sisainput_' + no + '" value="">0</td>'
                let Product =
                    '<td><span id="Product_' + no + '" value="">' + value.grupProduct +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' +
                    no +
                    '">1</span>' +
                    '<input hidden class="JenisProductinput" id="JenisProductinput_' + no +
                    '" value="195237">' +
                    '<input hidden class="IdKadarinput" id="IdKadarinput_' + no +
                    '" value="' + value.idKadar + '">' +
                    '<input hidden class="IdSPKPPICinput" id="IdSPKPPICinput_' + no +
                    '" value="' + value.WorkOrder + '">' +
                    '<input hidden class="SWSPKPPICinput" id="SWSPKPPICinput_' + no +
                    '" value="' + value.SPKPPIC + '">' +
                    '<input hidden class="SWSPKPPICLengkapinput" id="SWSPKPPICLengkapinput_' + no +
                    '" value="' + value.grupOrderWax + '">' +
                    '<input hidden class="Weighbutuhinput" id="Weighbutuhinput_' + no +
                    '" value="' + value.GoldNeed.toFixed(2) + '">' +
                    '<input hidden class="GroupProductinput" id="GroupProductinput_' + no +
                    '" value="' + value.grupProduct + '">' +
                    '<input hidden class="Qtyinput" id="Qtyinput_' + no +
                    '" value="' + value.Qty + '">' +
                    '<input hidden class="WeightWaxinput" id="WeightWaxinput_' + no +
                    '" value="' + value.WeightWax + '">' +
                    '<input class="WeightStoneinput" id="WeightStoneinput_' + no +
                    '" value="' + value.WeightStone + '"></td>'
                let NTHKO =
                    '<td><input class="form-control form-control-sm fs-6 w-100 text-center NTHKOinput" tabindex="' +
                    tab + '" id="NTHKOinput_' +
                    no + '" onchange="getNTHKO(this.value,' + no + ',' + value.IDPohon +
                    ')" value="">' +
                    '<input hidden class="WorkcompletionIDinput" id="WorkcompletionIDinput_' + no +
                    '" value="">' +
                    '<input hidden class="BatchNoinput" id="BatchNoinput_' + no + '" value="">' +
                    '<input hidden class="WcompliFreqinput" id="WcompliFreqinput_' + no +
                    '" value="">' +
                    '<input hidden class="WcompliOrdinput" id="WcompliOrdinput_' + no +
                    '" value="">' +
                    '</td>'
                let WeightGold =
                    "<td>" + "<div class='input-group' width='70%'>" +
                    "<input type='text' tabindex='" + tab2 +
                    "' readonly class='form-control form-control-sm fs-6 text-center WeightGoldinput' id='WeightGoldinput_" +
                    no + "' onkeydown='jumlahberatnimbang(" + no + ")' value='0'>" +
                    "</td>"
                let Remarks =
                    "<td> <input readonly  type='text' tabindex='" + tab3 +
                    "' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
                    no + "' value=''></td></td>"
                let Action =
                    "<td align='center'><button class='btn btn-warning btn-sm addplat p-1 m-1' type='button' onclick='addplat(" +
                    value.IDPohon + ",\"" + value.NoPohon + "\"," + no + ",\"" + value.SPKPPIC +
                    "\",\"" + value.HexColor + "\"," + value.GoldNeed.toFixed(2) + "," + value
                    .idKadar + "," + value.WorkOrder + ",\"" + value.grupOrderWax + "\",\"" + value
                    .grupProduct + "\"," + value.Qty + "," + value.WeightWax + "," + tab3 +
                    ")' id='addplat_" + no +
                    "'>PLAT</button>" +
                    "<button class='btn btn-secondary btn-sm addr p-1 m-1' type='button' onclick='add(" +
                    value.IDPohon + ",\"" + value.NoPohon + "\"," + no + ",\"" + value.SPKPPIC +
                    "\",\"" + value.HexColor + "\")' id='add_" +
                    no +
                    "'>BATU</button></td>"
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, Urutan, IDpohon,
                    NoPohon, WeightNeed, SPKPPIC, Sisa, Product, NTHKO, WeightGold, Remarks,
                    Action,
                    trEnd)

                $("#tabel1 > tbody").append(rowitem);
                no += 1;
                tab += 3;
                tab2 += 3;
                tab3 += 3;
            });

            JumlahPohon()
            jumlahberat()
            jumlahberatnimbang()

            $("#tanggal").focus()
            $('#tabel1').DataTable({
                "paging": false,
                "lengthChange": false,
                // "pageLength": 9,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": true,
                "fixedColumns": false,
            });

            $("#btn_simpan").prop('disabled', false)
            $(".Note").prop('readonly', false)

            $(".WeightGoldinput").change(function() {
                // alert("The text has been changed.");
            });
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
                showCancelButton: false,
                showConfirmButton: false
            })
            return;
        }
    })
}



function add(IDPohonadd, NoPohonadd, noadd, PPIC, Color) {
    let no = $('#tabel1 tr').length;
    // console.log(IDPohonadd + ',' + NoPohonadd + ',' + noadd + ',' + PPIC + ',' + Color + ',' + no)

    let start =
        "<tr class='kl px-0 mx-0 table-secondary' id='tr_" + no + "'>"
    let Urutan =
        '<td class="px-0 mx-0"><span class="urutaninput" id="urutaninput_' +
        no +
        '"></span></td>'
    let IDpohon = '<td class="px-0 mx-0"><span id="IDP_' + no +
        '" value="" class="badge" style="font-size:14px; background-color: ' + Color + '">' + IDPohonadd +
        '</span><input hidden class="IDpohoninput" id="IDPohoninput_' + no +
        '" value="' +
        IDPohonadd + '"></td>'
    let NoPohon =
        '<td class="px-0 mx-0"><span class="badge bg-dark NOPohon" style="font-size:14px;" id="NoPohon_' +
        no + '" value="">' + NoPohonadd +
        '</span></td>'
    let WeightNeed =
        '<td class="px-0 mx-0"><span class="WeightNeedinput" id="WeightNeedinput_' +
        no +
        '"></span></td>'
    let SPKPPIC =
        '<td><span id="workorder_' + no + '" value="">' + PPIC +
        '</span></td>'
    let Sisa =
        '<td><span class="sisainput" id="sisainput_' + no + '" value="">0</td>'
    let Product =
        '<td><span id="Product_' + no + '" value=""></span><span hidden class="Jumlahdata" id="Jumlahdata_' + no +
        '" value="' +
        no +
        '">1</span>' +
        '<input hidden class="JenisProductinput" id="JenisProductinput_' + no +
        '" value="93">' +
        '<input hidden class="IdKadarinput" id="IdKadarinput_' + no +
        '" value="">' +
        '<input hidden class="IdSPKPPICinput" id="IdSPKPPICinput_' + no +
        '" value="">' +
        '<input hidden class="SWSPKPPICinput" id="SWSPKPPICinput_' + no +
        '" value="">' +
        '<input hidden class="SWSPKPPICLengkapinput" id="SWSPKPPICLengkapinput_' + no +
        '" value="">' +
        '<input hidden class="Weighbutuhinput" id="Weighbutuhinput_' + no +
        '" value="">' +
        '<input hidden class="GroupProductinput" id="GroupProductinput_' + no +
        '" value="">' +
        '<input hidden class="Qtyinput" id="Qtyinput_' + no +
        '" value="">' +
        '<input hidden class="WeightWaxinput" id="WeightWaxinput_' + no +
        '" value=""></td>'
    let NTHKO =
        '<td><input disabled class="form-control form-control-sm fs-6 w-100 text-center NTHKOinput" tabindex="5" id="NTHKOinput_' +
        no + '" onchange="getNTHKO(this.value,' + no + ',' + IDPohonadd +
        ')" value="">' +
        '<input hidden class="WorkcompletionIDinput" id="WorkcompletionIDinput_' + no +
        '" value="">' +
        '<input hidden class="BatchNoinput" id="BatchNoinput_' + no + '" value="">' +
        '<input hidden class="WcompliFreqinput" id="WcompliFreqinput_' + no +
        '" value="">' +
        '<input hidden class="WcompliOrdinput" id="WcompliOrdinput_' + no +
        '" value="">' +
        '</td>'
    let WeightGold =
        "<td>" + "<div class='input-group' width='70%'>" +
        "<input type='text' tabindex='5' class='form-control form-control-sm fs-6 text-center WeightGoldinput' id='WeightGoldinput_" +
        no +
        "' onkeydown='jumlahberatnimbang(" + no + ")'>" +
        "</td>"
    let Remarks =
        "<td> <input type='text' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
        no + "' value=''></td></td>"
    let Action =
        "<td align='center'><button type='button' class='btn btn-danger btn-sm remove' onclick = 'remove(" +
        IDPohonadd + "," +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let trEnd = "</tr>"
    let final = ""
    let rowitem = final.concat(start, Urutan, IDpohon,
        NoPohon, WeightNeed, SPKPPIC, Sisa, Product, NTHKO, WeightGold, Remarks, Action,
        trEnd)
    // $("#tabel1 > tbody").append(rowitem).insertAfter($(this).parents().closest('tr'));;
    $("#tabel1").find(".klik3").eq(noadd - 1).after(rowitem);
}

function addplat(IDPohonadd, NoPohonadd, noadd, PPIC, Color, GoldNeedadd, idkadaradd, idworkorderadd, grupOrderWaxadd,
    grupProductadd, Qtyadd, WeightWaxadd, Ordindex) {
    let no = Ordindex;
    let no1 = no + 1;
    let no2 = no + 2;
    let no3 = no + 3;
    console.log(IDPohonadd + ',' + NoPohonadd + ',' + noadd + ',' + PPIC + ',' + Color + ',' + GoldNeedadd + ',' +
        idkadaradd + ',' + idworkorderadd + ',' + grupOrderWaxadd + ',' + grupProductadd + ',' + Qtyadd + ',' +
        WeightWaxadd + ',' + no + ',' + no1 + ',' + no2 + ',' + no3);

    let start =
        "<tr class='kl px-0 mx-0 table-warning' id='tr_" + no + "'>"
    let Urutan =
        '<td class="px-0 mx-0"><span class="urutaninput" id="urutaninput_' +
        no +
        '"></span></td>'
    let IDpohon = '<td class="px-0 mx-0"><span id="IDP_' + no +
        '" value="" class="badge" style="font-size:14px; background-color: ' + Color + '">' + IDPohonadd +
        '</span><input hidden class="IDpohoninput" id="IDPohoninput_' + no +
        '" value="' +
        IDPohonadd + '"></td>'
    let NoPohon =
        '<td class="px-0 mx-0"><span class="badge bg-dark NOPohon" style="font-size:14px;" id="NoPohon_' +
        no + '" value="">' + NoPohonadd +
        '</span></td>'
    let WeightNeed =
        '<td class="px-0 mx-0"><span class="WeightNeedinput" id="WeightNeedinput_' +
        no +
        '">' + GoldNeedadd + '</span></td>'
    let SPKPPIC =
        '<td><span id="workorder_' + no + '" value="">' + PPIC +
        '</span></td>'
    let Sisa =
        '<td><span class="sisainput" id="sisainput_' + no + '" value="">0</td>'
    let Product =
        '<td><span id="Product_' + no + '" value="">' + grupProductadd +
        '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no +
        '" value="' +
        no +
        '">1</span>' +
        '<input hidden class="JenisProductinput" id="JenisProductinput_' + no +
        '" value="195237">' +
        '<input hidden class="IdKadarinput" id="IdKadarinput_' + no +
        '" value="' + idkadaradd + '">' +
        '<input hidden class="IdSPKPPICinput" id="IdSPKPPICinput_' + no +
        '" value="' + idworkorderadd + '">' +
        '<input hidden class="SWSPKPPICinput" id="SWSPKPPICinput_' + no +
        '" value="' + PPIC + '">' +
        '<input hidden class="SWSPKPPICLengkapinput" id="SWSPKPPICLengkapinput_' + no +
        '" value="' + grupOrderWaxadd + '">' +
        '<input hidden class="Weighbutuhinput" id="Weighbutuhinput_' + no +
        '" value="' + GoldNeedadd + '">' +
        '<input hidden class="GroupProductinput" id="GroupProductinput_' + no +
        '" value="' + grupProductadd + '">' +
        '<input hidden class="Qtyinput" id="Qtyinput_' + no +
        '" value="' + Qtyadd + '">' +
        '<input hidden class="WeightWaxinput" id="WeightWaxinput_' + no +
        '" value="' + WeightWaxadd + '"></td>'
    let NTHKO =
        '<td><input class="form-control form-control-sm fs-6 w-100 text-center NTHKOinput" tabindex="' + no1 +
        '" id="NTHKOinput_' +
        no + '" onchange="getNTHKO(this.value,' + no + ',' + IDPohonadd +
        ')" value="">' +
        '<input hidden class="WorkcompletionIDinput" id="WorkcompletionIDinput_' + no +
        '" value="">' +
        '<input hidden class="BatchNoinput" id="BatchNoinput_' + no + '" value="">' +
        '<input hidden class="WcompliFreqinput" id="WcompliFreqinput_' + no +
        '" value="">' +
        '<input hidden class="WcompliOrdinput" id="WcompliOrdinput_' + no +
        '" value="">' +
        '</td>'
    let WeightGold =
        "<td>" + "<div class='input-group' width='70%'>" +
        "<input type='text' tabindex='" + no2 +
        "' class='form-control form-control-sm fs-6 text-center WeightGoldinput' id='WeightGoldinput_" +
        no + "' onkeydown='jumlahberatnimbang(" + no + ")'>" + "</td>"
    let Remarks =
        "<td> <input type='text' tabindex='" + no3 +
        "' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
        no + "' value=''></td></td>"
    let Action =
        "<td align='center'><button type='button' class='btn btn-danger btn-sm remove' onclick = 'remove(" +
        IDPohonadd + "," +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let trEnd = "</tr>"
    let final = ""
    let rowitem = final.concat(start, Urutan, IDpohon,
        NoPohon, WeightNeed, SPKPPIC, Sisa, Product, NTHKO, WeightGold, Remarks, Action,
        trEnd)
    // $("#tabel1 > tbody").append(rowitem).insertAfter($(this).parents().closest('tr'));;
    $("#tabel1").find(".klik3").eq(noadd - 1).after(rowitem);
}

function remove(pohon, id) {
    console.log(id)
    $("#tabel1").find("#tr_" + id).remove()
    // document.getElementById("tabel1").deleteRow(id);
}
// $("#WeightGoldinput_").change(function(){
//   alert("The text has been changed.");
// });

function getNTHKO(value, no, IDPohon) {
    console.log(value + ',' + no);
    var pisah = value.split('-');
    var workallocation = pisah[0]; // dari tabel workcompletion kolom workallocation
    var Freq = pisah[1]; // freq tabel workcompletion
    var ordinal = pisah[2]; // ordinal workcompletionitem

    console.log(workallocation + ',' + Freq + ',' + ordinal);

    var data = {
        workallocation: workallocation,
        Freq: Freq,
        ordinal: ordinal,
        IDPohon: IDPohon
    }

    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/getNTHKO",
        data: data,
        dataType: 'json',
        beforeSend: function() {},
        complete: function() {},
        success: function(data) {
            if (data.rowcount > 0) {
                $('#WorkcompletionIDinput_' + no).val(data.WorkcompletionID)
                $('#WcompliFreqinput_' + no).val(data.WorkcompletionFreq)
                $('#WcompliOrdinput_' + no).val(data.WcompliOrd)
                // $('#Weight_' + no).text(data.Weight)
                $('#BatchNoinput_' + no).val(data.BatchNo)
                $('#WeightGoldinput_' + no).prop('readonly', false)
                $("#tomboltimbangan_" + no).prop('readonly', false)
                // $("#WeightGoldinput_" + no).focus();
                console.log(data.WorkcompletionID + ',' + data.WcompliOrd + ',' + data.BatchNo)
            } else {
                $('#WorkcompletionIDinput_' + no).val('')
                $('#WcompliFreqinput_' + no).val('')
                $('#WcompliOrdinput_' + no).val('')
                // $('#Weight_' + no).text('')
                $('#BatchNoinput_' + no).val('')
            }
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
                showCancelButton: false,
                showConfirmButton: false
            })
            return;
        }
    })
}


function pilihan() {
    var Cuaks = $('#tabel1').find('.Checked')

    var total = 0;
    for (let i = 0; i < Cuaks.length; i++) {
        var aa = parseInt($(Cuaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#Pilihan').text(total);
}

function jumlahberat() {
    var Cuaks = $('#tabel1').find('.WeightNeedinput')

    var total = 0;
    for (let i = 0; i < Cuaks.length; i++) {
        var aa = parseFloat($(Cuaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totalberatkebutuhan').text(total.toFixed(2));
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function jumlahberatnimbang(no) {
    kliktimbang("WeightGoldinput_" + no)
    await sendSerialLine();
    let value = $("#WeightGoldinput_" + no).val()
    let Cuaks = $('#tabel1').find('#WeightNeedinput_' + no)
    let aa = parseFloat($(Cuaks).text())
    let hitungsisa = aa - value
    console.log(hitungsisa)
    $('#sisainput_' + no).text(hitungsisa.toFixed(2));

    let nilaiberat = $('#tabel1').find('.WeightGoldinput')

    var total = 0;
    for (let i = 0; i < nilaiberat.length; i++) {
        var bb = parseFloat($(nilaiberat[i]).val())
        total = total + bb
    }
    console.log('totalberat:' + total);
    $('#totalberathasilnimbang').text(total.toFixed(2));
}


function JumlahPohon() {
    var Cuaks = $('#tabel1').find('.Jumlahdata')

    var total = 0;
    for (let i = 0; i < Cuaks.length; i++) {
        var aa = parseInt($(Cuaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#TotalPohon').text(total);
}

function JumlahPohonisi() {
    var Cuaks = $('#tabel4').find('.Jumlahdata')

    var total = 0;
    for (let i = 0; i < Cuaks.length; i++) {
        var aa = parseInt($(Cuaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#TotalPohonisi').text(total);
}


// function jumlahcari() {
//     var Cuaks = $('#tabel2').find('.Jumlahdata')
//     var Cuakslagi = $('#tabel2').find('.JumlahBerat')

//     var total = 0;
//     for (let i = 0; i < Cuaks.length; i++) {
//         var aa = parseInt($(Cuaks[i]).val())

//         total = total + aa
//     }

//     var total2 = 0;
//     for (let i = 0; i < Cuakslagi.length; i++) {
//         var bb = parseFloat($(Cuakslagi[i]).text())

//         total2 = total2 + bb
//     }

//     console.log(total);
//     $('#TotalPohon').text(total);
//     $('#Pilihan').text(total);
//     $('#TotalBerat').text(total2.toFixed(2));

// }


function KlikSimpan() {
    // Get Action let
    action = $('#action').val()
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)

    // rubah value action
    if (action == 'simpan') {
        Simpan()
    } else {
        Ubah()
    }
}

function Simpan() {

    // insert workallocation
    // insert workallocationitem
    // insert cast
    // insert castitem

    // IDSPKOPlateCor
    var IDTMPohon = $('#IDTMPohon').val()
    // Get tanggal
    var date = $('#tanggal').val()
    var employee = $('#idEmployee').val()
    // GET CATATAN
    var Catatan = $('#Catatan').val()
    var OOO = $('#O').val()
    var totaltimbangan = $('#totalberathasilnimbang').text()

    // Get item
    let IDWaxtrees = $('.IDpohoninput')
    let urutaninputs = $('.urutaninput')
    let jenisproduks = $('.JenisProductinput')
    let idkadars = $('.IdKadarinput')
    let idspkppics = $('.IdSPKPPICinput')
    let swspkppics = $('.SWSPKPPICinput')
    let swspkppiclengkaps = $('.SWSPKPPICLengkapinput')
    let weightneeds = $('.Weighbutuhinput')
    let groupproduks = $('.GroupProductinput')
    let qtys = $('.Qtyinput')
    let weightwaxs = $('.WeightWaxinput')
    let BatchNos = $('.BatchNoinput')
    let WorkcompletionIDinputs = $('.WorkcompletionIDinput')
    let WcompliFreqinputs = $('.WcompliFreqinput')
    let wcompletionords = $('.WcompliOrdinput')
    let weightinputs = $('.WeightGoldinput')


    // Check tanggal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }
    // check klom employee
    if (employee == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Operator tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDWaxtrees.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tidak ID Waxtree yang diproses .",
        })
        return;
    }
    if (weightinputs.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "anda belum memasukkan berat emas sama sekali",
        })

    }

    //!  ------------------------    Check Items Waxtree if have blank value     ------------------------ !!
    let cekIDWaxtrees = false
    IDWaxtrees.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat id pohon yangh error yang kosong.",
            })
            cekIDWaxtrees = true
            return false;
        }
    })
    if (cekIDWaxtrees == true) {
        return false;
    }

    // let cekwcompletionords = false
    // wcompletionords.map(function() {
    //     if (this.value === '') {
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Oops...',
    //             text: "Terdapat id pohon yang belum ditimbang.",
    //             showDenyButton: true,
    //             confirmButtonText: 'Yes',
    //             denyButtonText: 'No',
    //         })
    //         wcompletionords = true
    //         return false;
    //     }
    // })
    // if (cekwcompletionords == true) {
    //     return false;
    // }
    // if (cekweightinput == true) {
    //     return true;
    // }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDWaxtrees.length; index++) {
        var IDWaxtree = $(IDWaxtrees[index]).val()
        var urutaninput = $(urutaninputs[index]).text()
        var jenisproduk = $(jenisproduks[index]).val()
        var idkadar = $(idkadars[index]).val()
        var idspkppic = $(idspkppics[index]).val()
        var swspkppic = $(swspkppics[index]).val()
        var swspkppiclengkap = $(swspkppiclengkaps[index]).val()
        var weightneed = $(weightneeds[index]).val()
        var groupproduk = $(groupproduks[index]).val()
        var qty = $(qtys[index]).val()
        var weightwax = $(weightwaxs[index]).val()
        var BatchNo = $(BatchNos[index]).val()
        var WorkcompletionIDinput = $(WorkcompletionIDinputs[index]).val()
        var wcompletionord = $(wcompletionords[index]).val()
        var weightinput = $(weightinputs[index]).val()

        let dataitems = {
            IDWaxtree: IDWaxtree,
            urutaninput: urutaninput,
            jenisproduk: jenisproduk,
            idkadar: idkadar,
            idspkppic: idspkppic,
            swspkppic: swspkppic,
            swspkppiclengkap: swspkppiclengkap,
            weightneed: weightneed,
            groupproduk: groupproduk,
            qty: qty,
            weightwax: weightwax,
            BatchNo: BatchNo,
            WorkcompletionIDinput: WorkcompletionIDinput,
            wcompletionord: wcompletionord,
            weightinput: weightinput
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        IDTMPohon: IDTMPohon,
        totaltimbangan: totaltimbangan,
        OOO: OOO,
        date: date,
        employee: employee,
        Catatan: Catatan,
        items: items
    }

    // Setup CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Hit Backend
    $.ajax({
        type: "POST",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/Simpan",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            // alert(data.data.ID)
            $('#cari').val(data.data.ID)
            $('#IDSPKOPlateCor').val(data.data.ID)
            // Set action to update
            $('#action').val('update')
            $("#Posting1").prop('disabled', false)
            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 500,
                showCancelButton: false,
                showConfirmButton: false
            });
            // pindah function serach
            Search();
            return;
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
    })
}


function KlikEdit() {
    // Set action to update
    var data = $('#TotalPohon');

    $('#action').val('update')
    // Set Button 
    $("#btn_baru").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Disable input 
    $("#tanggal").prop('readonly', false)
    $("#Catatan").prop('disabled', false)
    $(".WeightGoldinput").prop('readonly', false)
    $(".tomboltimbangan").prop('disabled', false)
    $(".add").prop('disabled', false)
    $(".NTHKOinput").prop('readonly', false)
}

function Ubah() {

    // update workallocation
    // insert workallocationitem
    // update cast
    // insert castitem

    // swworkallocation
    var SWworkallocation = $('#cari').val();
    // IDSPKOPlateCor
    var IDTMPohon = $('#IDTMPohon').val()
    // Get tanggal
    var date = $('#tanggal').val()
    var employee = $('#idEmployee').val()
    // GET CATATAN
    var Catatan = $('#Catatan').val()
    var OOO = $('#O').val()
    var totaltimbangan = $('#totalberathasilnimbang').text()

    // Get item
    let IDWaxtrees = $('.IDpohoninput')
    let urutaninputs = $('.urutaninput')
    let jenisproduks = $('.JenisProductinput')
    let idkadars = $('.IdKadarinput')
    let idspkppics = $('.IdSPKPPICinput')
    let swspkppics = $('.SWSPKPPICinput')
    let swspkppiclengkaps = $('.SWSPKPPICLengkapinput')
    let weightneeds = $('.Weighbutuhinput')
    let groupproduks = $('.GroupProductinput')
    let qtys = $('.Qtyinput')
    let weightwaxs = $('.WeightWaxinput')
    let BatchNos = $('.BatchNoinput')
    let WorkcompletionIDinputs = $('.WorkcompletionIDinput')
    let WcompliFreqinputs = $('.WcompliFreqinput')
    let wcompletionords = $('.WcompliOrdinput')
    let weightinputs = $('.WeightGoldinput')



    // Check tanggal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }
    // check klom employee
    if (employee == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Operator tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDWaxtrees.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tidak ID Waxtree yang diproses .",
        })
        return;
    }
    if (weightinputs.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "anda belum memasukkan berat emas sama sekali",
        })

    }

    //!  ------------------------    Check Items Waxtree if have blank value     ------------------------ !!
    let cekIDWaxtrees = false
    IDWaxtrees.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat id pohon yangh error yang kosong.",
            })
            cekIDWaxtrees = true
            return false;
        }
    })
    if (cekIDWaxtrees == true) {
        return false;
    }

    // let cekwcompletionords = false
    // wcompletionords.map(function() {
    //     if (this.value === '') {
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Oops...',
    //             text: "Terdapat id pohon yang belum ditimbang.",
    //             showDenyButton: true,
    //             confirmButtonText: 'Yes',
    //             denyButtonText: 'No',
    //         })
    //         wcompletionords = true
    //         return false;
    //     }
    // })
    // if (cekwcompletionords == true) {
    //     return false;
    // }
    // if (cekweightinput == true) {
    //     return true;
    // }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDWaxtrees.length; index++) {
        var IDWaxtree = $(IDWaxtrees[index]).val()
        var urutaninput = $(urutaninputs[index]).text()
        var jenisproduk = $(jenisproduks[index]).val()
        var idkadar = $(idkadars[index]).val()
        var idspkppic = $(idspkppics[index]).val()
        var swspkppic = $(swspkppics[index]).val()
        var swspkppiclengkap = $(swspkppiclengkaps[index]).val()
        var weightneed = $(weightneeds[index]).val()
        var groupproduk = $(groupproduks[index]).val()
        var qty = $(qtys[index]).val()
        var weightwax = $(weightwaxs[index]).val()
        var BatchNo = $(BatchNos[index]).val()
        var WorkcompletionIDinput = $(WorkcompletionIDinputs[index]).val()
        var wcompletionord = $(wcompletionords[index]).val()
        var weightinput = $(weightinputs[index]).val()

        let dataitems = {
            IDWaxtree: IDWaxtree,
            urutaninput: urutaninput,
            jenisproduk: jenisproduk,
            idkadar: idkadar,
            idspkppic: idspkppic,
            swspkppic: swspkppic,
            swspkppiclengkap: swspkppiclengkap,
            weightneed: weightneed,
            groupproduk: groupproduk,
            qty: qty,
            weightwax: weightwax,
            BatchNo: BatchNo,
            WorkcompletionIDinput: WorkcompletionIDinput,
            wcompletionord: wcompletionord,
            weightinput: weightinput
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        SWworkallocation: SWworkallocation,
        IDTMPohon: IDTMPohon,
        totaltimbangan: totaltimbangan,
        OOO: OOO,
        date: date,
        employee: employee,
        Catatan: Catatan,
        items: items
    }

    // Setup CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Hit Backend
    $.ajax({
        type: "PUT",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/Update",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            alert(data.data.ID)
            $('#cari').val(data.data.ID)
            $('#IDSPKOPlateCor').val(data.data.ID)
            // Set action to update
            $('#action').val('update')
            $("#Posting1").prop('disabled', false)
            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 500,
                showCancelButton: false,
                showConfirmButton: false
            });
            // pindah function serach
            Search();
            return;
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
    })
}

function Search() {

    $('#action').val('update')
    // disabel button "Baru, Ubah and except Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_edit").prop('disabled', false)
    // disable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', true)
    $("#btn_batal").prop('disabled', true)
    // disabled input
    $("#IDSPKOPlateCor").prop('readonly', true)
    $("#tanggal").prop('readonly', true)
    $('#Catatan').prop('disabled', true)


    // Get cari from input 
    let cari = $('#cari').val();

    if (cari == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. cari Cannot be null.",
        })
        return
    }
    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOPlateCor/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').prop('hidden', false);
            $('#tabel2').prop('hidden', false);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#showtambah").prop('hidden', false)
            $("#hiddentambah").prop('hidden', true)

            $("#tabel1 tbody").empty();
            $("#tabel1").prop('hidden', false);
            $("#tabel2").prop('hidden', true);
            $("#tabel3").prop('hidden', true);

            $("#show").prop('hidden', true)
            $("#infoTM").prop('hidden', false)
            $("#infoTM2").prop('hidden', false)

            $("#IDSPKOPlateCor").val('')
            $("#Catatan").val('')
            $("#tomboldaftarpohon").focus()
            $('#action').val('simpan')

            $("#infoposting").text('')

            $("#tabel1 tbody ").empty()
            $("#tabel2 tbody ").empty()
            // Set WaxInjectOrderID 
            $('#IDTMPohon').val(data.data.IDTMpohon)
            $('#tanggal').val(data.data.datetanggal)
            $('#Catatan').val(data.data.Remarks)
            // Set user admin batu
            $('#UserNameAdmin').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatan').text(data.data.EntryDate)
            // Set item table let 
            $('#postingstatus').val(data.data.Active)
            $("#infoposting").text(data.data.Posting)
            $('#O').val(data.data.OOO)
            $('#UserNameAdminTM').text(' GLC : ' + data.data.UserName)
            $('#TanggaPembuatanTM').text(data.data.EntryDate)
            let no = 1
            data.data.items2.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 " + value.style + " px-0 mx-0' id='tr_" + value
                    .IDpohonwork +
                    "'>"
                let Urutan =
                    '<td class="px-0 mx-0"><span class="urutaninput" id="urutaninput_' +
                    no +
                    '">' + no + '</span></td>'
                let IDpohon = '<td class="px-0 mx-0"><span id="IDP_' + no +
                    '" value="" class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor + '">' + value.IDpohonwork +
                    '</span><input hidden class="IDpohoninput" id="IDPohoninput_' + no +
                    '" value="' +
                    value.IDpohonwork + '"></td>'
                let NoPohon =
                    '<td class="px-0 mx-0"><span class="badge bg-dark NOPohon" style="font-size:14px;" id="NoPohon_' +
                    no + '" value="">' + value.NoPohon +
                    '</span></td>'
                let WeightNeed =
                    '<td class="px-0 mx-0"><span class="WeightNeedinput" id="WeightNeedinput_' +
                    no +
                    '">' + value.WeightNeed.toFixed(2) + '</span></td>'
                let SPKPPIC =
                    '<td><span id="workorder_' + no + '" value="">' + value.SPKPPIC +
                    '</span></td>'
                let Sisa =
                    '<td><span class="sisainput" id="sisainput_' + no + '" value="">0</td>'
                let Product =
                    '<td><span id="Product_' + no + '" value="">' + value.grupProduct +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no +
                    '" value="' +
                    no +
                    '">1</span>' +
                    '<input hidden class="JenisProductinput" id="JenisProductinput_' + no +
                    '" value="' + value.jenisproduct + '">' +
                    '<input hidden class="IdKadarinput" id="IdKadarinput_' + no +
                    '" value="' + value.caratitem + '">' +
                    '<input hidden class="IdSPKPPICinput" id="IdSPKPPICinput_' + no +
                    '" value="' + value.WorkOrder + '">' +
                    '<input hidden class="SWSPKPPICinput" id="SWSPKPPICinput_' + no +
                    '" value="' + value.SPKPPIC + '">' +
                    '<input hidden class="SWSPKPPICLengkapinput" id="SWSPKPPICLengkapinput_' +
                    no +
                    '" value="' + value.grupOrderWax + '">' +
                    '<input hidden class="Weighbutuhinput" id="Weighbutuhinput_' + no +
                    '" value="' + value.WeightNeed.toFixed(2) + '">' +
                    '<input hidden class="GroupProductinput" id="GroupProductinput_' + no +
                    '" value="' + value.grupProduct + '">' +
                    '<input hidden class="Qtyinput" id="Qtyinput_' + no +
                    '" value="' + value.QtyWax + '">' +
                    '<input hidden class="WeightWaxinput" id="WeightWaxinput_' + no +
                    '" value="' + value.WeightWax + '"></td>'
                let NTHKO =
                    '<td><input readonly class="form-control form-control-sm fs-6 w-100 text-center NTHKOinput" tabindex="5" id="NTHKOinput_' +
                    no + '" onchange="getNTHKO(this.value,' + no + ',' + value.IDpohonwork +
                    ')" value="' + value.nthko + '">' +
                    '<input hidden class="WorkcompletionIDinput" id="WorkcompletionIDinput_' +
                    no +
                    '" value="' + value.PrevProcess + '">' +
                    '<input hidden class="BatchNoinput" id="BatchNoinput_' + no + '" value="' +
                    value.BatchNo + '">' +
                    '<input hidden class="WcompliFreqinput" id="WcompliFreqinput_' + no +
                    '" value="' + value.Freq + '">' +
                    '<input hidden class="WcompliOrdinput" id="WcompliOrdinput_' + no +
                    '" value="' + value.PreVOrd + '">' +
                    '</td>'
                let WeightGold =
                    "<td>" + "<div class='input-group' width='70%'>" +
                    "<input readonly tabindex='5' type='text' class='form-control form-control-sm fs-6 text-center WeightGoldinput' id='WeightGoldinput_" +
                    no +
                    "' onkeydown='jumlahberatnimbang(this.value," + no + ")' value='" + value
                    .weightworkitem.toFixed(2) + "' onkeyup='kliktimbang(\"WeightGoldinput_" +
                    no + "\")'>" +
                    // "<button disabled tabindex='5' type='button' id='tomboltimbangan_" + no +
                    // "' class='btn btn-info btn-sm tomboltimbangan' onclick='kliktimbang(\"WeightGoldinput_" +
                    // no + "\")'><i class='fa fa-balance-scale'></i></button>" + 
                    "</td>"
                let Remarks =
                    "<td> <input readonly type='text' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
                    no + "' value=''></td></td>"
                let Action =
                    "<td align='center'><button disabled class='btn btn-info btn-sm add' type='button' onclick='add(" +
                    value.IDpohonwork + ",\"" + value.NoPohon + "\"," + no + ",\"" + value
                    .SPKPPIC +
                    "\",\"" + value.HexColor + "\")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button></td>"
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, Urutan, IDpohon,
                    NoPohon, WeightNeed, SPKPPIC, Sisa, Product, NTHKO, WeightGold, Remarks,
                    Action,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
                // jumlahberatnimbang(value.weightworkitem, no);
                no += 1;
            });

            let pos = $('#postingstatus').val();
            if (pos == 'P') {
                $("#Posting1").prop('disabled', true)
                $("#btn_edit").prop('disabled', true)
            } else {
                $("#Posting1").prop('disabled', false)
                $("#btn_edit").prop('disabled', false)
            }
            // jumlahcari()

            // show user admin dan tangal entry
            JumlahPohon();
            jumlahberat();
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
    })
}
</script>
@endsection
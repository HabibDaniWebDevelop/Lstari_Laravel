<?php $title = 'SPKO GIPS'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Gips Lebur Cor </li>
    <li class="breadcrumb-item active">SPKO Gips</li>
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
</style>

@endsection

@section('container')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            @include('Produksi.GipsLeburCor.SPKOGips.data')

        </div>
    </div>
</div>
@endsection

@section('script')

{{-- This Page Script --}}
<script>
// Data Table Settings
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

function KlikBaru() {
    $('#IDwax').val('')
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_edit").prop('disabled', true)
    $("#btn_batal").prop('disabled', false)
    $("#btn_simpan").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_cetakrekap").prop('disabled', true)
    // Enable Button "Batal dan Simpan"

    // Enable input
    $("#IDSPKOGips").prop('readonly', true)
    $("#tomboldaftarpohon").prop('disabled', false)
    $("#tomboldaftarpohon1").prop('disabled', false)
    $("#tanggal").prop('readonly', false)
    $("#Posting1").prop('disabled', true)
    $("#Catatan").prop('disabled', false)

    $("#tomboldaftarpohon").prop('hidden', false)
    $("#tomboldaftarpohon1").prop('hidden', false)
    $("#tomboldaftarpohon2").prop('hidden', true)

    $("#tabel1 tbody").empty();
    $("#tabel2 tbody").empty();
    $("#show2").prop('hidden', false)
    $("#show").prop('hidden', true)

    $("#IDSPKOGips").val('')
    $("#Catatan").val('')
    $("#tomboldaftarpohon").focus()
    $('#action').val('simpan')

    $("#infoposting").text('')



    Swal.fire({
            title: 'Kerjakan SPK Jenis O',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: 'No',
        })
        .then((result) => {
            if (result.isConfirmed) {
                $("#O").val('=')

            } else {
                $("#O").val('!=')

            }
        })
}

function klikBatal() {
    window.location.reload()
}

// function KlickDaftarPohonEmas() {
//     $("#pohon").val('emas');
//     KlickDaftarPohon()
// }

// function KlickDaftarPohonPerak() {
//     $("#pohon").val('perak');
//     KlickDaftarPohon()
// }

function KlickDaftarPohonEmas() {
    var WorkOrderOO = $("#O").val()

    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOGips/DafatarPohon/" + WorkOrderOO,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').DataTable().destroy();
            $("#tabel1").prop('hidden', false);
            $("#tabel2").prop('hidden', true);
            $("#tabel1 tbody").empty();
            $("#tabel2 tbody").empty();
            $("#show2").prop('hidden', false)
            $("#show").prop('hidden', true)
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#btn_baru").prop('disabled', false)
            $("#tomboldaftarpohon").prop('hidden', true)
            $("#tomboldaftarpohon1").prop('hidden', true)
            $("#tomboldaftarpohon2").prop('hidden', false)

            // Set Operator
            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 " + value.style + "' id='" + no + "'>"
                let PIlls =
                    '<td><div style="width: 5px;"><input class="form-check-input readonly Priority" type="checkbox" name="id[]" id="Priority_' +
                    no +
                    '" value ="' + value.IDWaxtree + '" disabled data-itung="1" data-FG="' + value
                    .WeightFG
                    .toFixed(2) + '" ' + value.Checked + '></div></td>'
                let Dates =
                    '<td><span id="Dates_' + no + '" value="">' + value.TransDate +
                    '</span><span hidden class="FGG ' + value
                    .Checked + '" id="FG_' + no + '">1</span><span hidden class="BBB ' + value
                    .status + '" id="BB_' + no + '">' + value.WeightFG.toFixed(2) + '</span></td>'
                let Plates =
                    '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate + '</span>'
                let Sizes =
                    '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                    '</span></td>'
                let Kadars =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="KadarPohon_' +
                    no +
                    '">' + value.Carat + '</span></td>'
                let BeratLilins =
                    '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(2) +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' +
                    no +
                    '">1</span></td>'
                let BeratBatus =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value.WeightStone.toFixed(
                        2) +
                    '</span></td>'
                let Qtys =
                    '<td><span id="Qty_' + no + '" value="">' + value.Qty +
                    '</span></td>'
                let NoSPKPPICs =
                    '<td><div class="SPK" style="font-size:15px; color:' + value
                    .WorkText +
                    '; font-weight: bold; width: 230px; word-break: break-all;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let BeratFG = '<td center><span class="JumlahBerat" center id="Berat_' + no +
                    '" value="" onkeyup="TotalDataBerat(' + value.WeightFG.toFixed(2) + ')">' +
                    value.WeightFG
                    .toFixed(2) +
                    '</span></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, PIlls, Dates,
                    Plates, Sizes, Kadars, BeratLilins, BeratBatus, Qtys, NoSPKPPICs, BeratFG,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            JumlahPohon()
            pilihan()
            jumlahberat()

            $("#tanggal").focus()
            $('#tabel1').DataTable({
                "paging": false,
                "lengthChange": true,
                // "pageLength": 9,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "fixedColumns": true,
            });

            $(".klik3").on('click', function(e) {
                var id = $(this).attr('id');
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#Pilihan').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung - parseInt(itung)
                    $('#Pilihan').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#TotalBerat').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg - parseFloat(fg)
                    $('#TotalBerat').text(calfg.toFixed(2))

                    //generate tampilan titakterpilih
                    $('#Priority_' + id).attr('checked', false);
                    console.log(id);
                } else {
                    $(this).addClass('table-primary');

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#Pilihan').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung + parseInt(itung)
                    $('#Pilihan').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#TotalBerat').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg + parseFloat(fg)
                    $('#TotalBerat').text(calfg.toFixed(2))

                    //generate tampilan terpilih
                    $('#Priority_' + id).attr('checked', true);
                }
                return false;
            });
            $("#btn_simpan").prop('disabled', true)

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

function KlickDaftarPohonPerak() {
    var WorkOrderOO = $("#O").val()

    $.ajax({
        type: "GET",
        url: "/Produksi/GipsLeburCor/SPKOGips/DafatarPohonPerak/" + WorkOrderOO,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').DataTable().destroy();
            $("#tabel1").prop('hidden', false);
            $("#tabel2").prop('hidden', true);
            $("#tabel1 tbody").empty();
            $("#tabel2 tbody").empty();
            $("#show2").prop('hidden', false)
            $("#show").prop('hidden', true)
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#btn_baru").prop('disabled', false)
            $("#tomboldaftarpohon").prop('hidden', true)
            $("#tomboldaftarpohon1").prop('hidden', true)
            $("#tomboldaftarpohon2").prop('hidden', false)

            // Set Operator
            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 " + value.style + "' id='" + no + "'>"
                let PIlls =
                    '<td><div style="width: 5px;"><input class="form-check-input readonly Priority" type="checkbox" name="id[]" id="Priority_' +
                    no +
                    '" value ="' + value.IDWaxtree + '" disabled data-itung="1" data-FG="' + value
                    .WeightFG
                    .toFixed(2) + '" ' + value.Checked + '></div></td>'
                let Dates =
                    '<td><span id="Dates_' + no + '" value="">' + value.TransDate +
                    '</span><span hidden class="FGG ' + value
                    .Checked + '" id="FG_' + no + '">1</span><span hidden class="BBB ' + value
                    .status + '" id="BB_' + no + '">' + value.WeightFG.toFixed(2) + '</span></td>'
                let Plates =
                    '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate + '</span>'
                let Sizes =
                    '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                    '</span></td>'
                let Kadars =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="KadarPohon_' +
                    no +
                    '">' + value.Carat + '</span></td>'
                let BeratLilins =
                    '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(2) +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' +
                    no +
                    '">1</span></td>'
                let BeratBatus =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value.WeightStone.toFixed(
                        2) +
                    '</span></td>'
                let Qtys =
                    '<td><span id="Qty_' + no + '" value="">' + value.Qty +
                    '</span></td>'
                let NoSPKPPICs =
                    '<td><div class="SPK" style="font-size:15px; color:' + value
                    .WorkText +
                    '; font-weight: bold; width: 230px; word-break: break-all;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let BeratFG = '<td center><span class="JumlahBerat" center id="Berat_' + no +
                    '" value="" onkeyup="TotalDataBerat(' + value.WeightFG.toFixed(2) + ')">' +
                    value.WeightFG
                    .toFixed(2) +
                    '</span></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, PIlls, Dates,
                    Plates, Sizes, Kadars, BeratLilins, BeratBatus, Qtys, NoSPKPPICs, BeratFG,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            JumlahPohon()
            pilihan()
            jumlahberat()

            $("#tanggal").focus()
            $('#tabel1').DataTable({
                "paging": false,
                "lengthChange": true,
                // "pageLength": 9,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "fixedColumns": true,
            });

            $(".klik3").on('click', function(e) {
                var id = $(this).attr('id');
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#Pilihan').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung - parseInt(itung)
                    $('#Pilihan').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#TotalBerat').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg - parseFloat(fg)
                    $('#TotalBerat').text(calfg.toFixed(2))

                    //generate tampilan titakterpilih
                    $('#Priority_' + id).attr('checked', false);
                    console.log(id);
                } else {
                    $(this).addClass('table-primary');

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#Pilihan').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung + parseInt(itung)
                    $('#Pilihan').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#TotalBerat').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg + parseFloat(fg)
                    $('#TotalBerat').text(calfg.toFixed(2))

                    //generate tampilan terpilih
                    $('#Priority_' + id).attr('checked', true);
                }
                return false;
            });
            $("#btn_simpan").prop('disabled', true)

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

function KlickPilihPohon() {
    var WorkOrderOO = $("#O").val()

    var Pilihpohon = [];
    $('.Priority:checked').each(function(i, e) {
        let idWaxtree = $(this).val();

        Pilihpohon.push(idWaxtree);
    });

    // alert(Pilihpohon);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/Produksi/GipsLeburCor/SPKOGips/PilihPohon/" + Pilihpohon + "/" + WorkOrderOO,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').prop('hidden', true);
            $('#tabel2').prop('hidden', false);
            $("#show2").prop('hidden', false)
            $("#show").prop('hidden', true)
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#btn_baru").prop('disabled', true)
            $("#tomboldaftarpohon").prop('hidden', false)
            $("#tomboldaftarpohon1").prop('hidden', false)
            $("#tomboldaftarpohon2").prop('hidden', true)
            $("#btn_simpan").prop('disabled', false)

            // Set Operator
            // Set item table
            let no = 1
            data.data.items2.forEach(function(value, i) {
                let start =
                    "<tr class='klik2' id='" + no + "'>"
                let uruts =
                    '<td><span id="urut_' + no + '" value="">' + no +
                    '</span></td>' +
                    '<td hidden><input class="IDWaxtreeDipilih" id="IDwaxtree' + no + '" value="' +
                    value
                    .IDWaxtree + '"></input></td>'
                let Dates =
                    '<td><span id="Dates_' + no + '" value="">' + value.TransDate +
                    '</span></td>'
                let Plates =
                    '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate + '</span></td>'
                let Sizes =
                    '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                    '</span></td>'
                let Kadars =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="KadarPohon_' +
                    no +
                    '">' + value.Carat + '</span></td>'
                let BeratLilins =
                    '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(2) +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' +
                    no +
                    '">1</span></td>'
                let BeratBatus =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value.WeightStone.toFixed(
                        2) +
                    '</span></td>'
                let Qtys =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value.Qty +
                    '</span></td>'
                let NoSPKPPICs =
                    '<td><div class="SPK" style="font-size:15px; color:' + value
                    .WorkText +
                    '; font-weight: bold; width: 230px; word-break: break-all;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let BeratFG = '<td center><span class="JumlahBerat" center id="Berat_' + no +
                    '" value="" onkeyup="TotalDataBerat(' + value.WeightFG.toFixed(2) + ')">' +
                    value.WeightFG
                    .toFixed(2) +
                    '</span></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, uruts, Dates,
                    Plates, Sizes, Kadars, BeratLilins, BeratBatus, Qtys, NoSPKPPICs, BeratFG,
                    trEnd)
                $("#tabel2 > tbody").append(rowitem);
                no += 1;
            });
            JumlahPohon()

            // pilihan()
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
            $("#tomboldaftarpohon").prop('hidden', false)
            $("#tomboldaftarpohon1").prop('hidden', false)
            $("#tomboldaftarpohon2").prop('hidden', true)
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
    var Cuaks = $('#tabel1').find('.Y')

    var total = 0;
    for (let i = 0; i < Cuaks.length; i++) {
        var aa = parseFloat($(Cuaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#TotalBerat').text(total.toFixed(2));
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

// function JumlahPohon1() {
//     var Cuaks = $('#tabel2').find('.Jumlahdata')

//     var total = 0;
//     for (let i = 0; i < Cuaks.length; i++) {
//         var aa = parseInt($(Cuaks[i]).text())

//         total = total + aa
//     }
//     console.log(total);
//     $('#TotalPohon').text(total);
// }

function jumlahcari() {
    var Cuaks = $('#tabel2').find('.Jumlahdata')
    var Cuakslagi = $('#tabel2').find('.JumlahBerat')

    var total = 0;
    for (let i = 0; i < Cuaks.length; i++) {
        var aa = parseInt($(Cuaks[i]).val())

        total = total + aa
    }

    var total2 = 0;
    for (let i = 0; i < Cuakslagi.length; i++) {
        var bb = parseFloat($(Cuakslagi[i]).text())

        total2 = total2 + bb
    }

    console.log(total);
    $('#TotalPohon').text(total);
    $('#Pilihan').text(total);
    $('#TotalBerat').text(total2.toFixed(2));
}


function KlikSimpan() {
    // Get Action let
    action = $('#action').val()
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_cetakrekap").prop('disabled', true)
    // rubah value action
    if (action == 'simpan') {
        Simpan()
    } else {
        Ubah()
    }
}

function Simpan() {

    // insert gisporder
    // insert gipsorderitem

    // IDSPKOGips
    let IDSPKOGips = $('#IDSPKOGips').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // GET CATATAN
    let Catatan = $('#Catatan').val()
    var OOO = $('#O').val()


    // Get item
    let IDWaxtrees = $('.IDWaxtreeDipilih')

    // Check tanggal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDWaxtrees.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tidak ID Waxtree yang dipilih .",
        })
        return;
    }

    //!  ------------------------    Check Items Waxtree if have blank value     ------------------------ !!
    let cekIDWaxtrees = false
    IDWaxtrees.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat id Waxtree yang kosong.",
            })
            cekIDWaxtrees = true
            return false;
        }
    })
    if (cekIDWaxtrees == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDWaxtrees.length; index++) {
        var IDWaxtree = $(IDWaxtrees[index]).val()

        let dataitems = {
            IDWaxtree: IDWaxtree
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        OOO: OOO,
        date: date,
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
        url: "/Produksi/GipsLeburCor/SPKOGips/Simpan",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            // Set idWaxTree
            $('#cari').val(data.data.ID)
            $('#IDSPKOGips').val(data.data.ID)
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

    $('#action').val('update')
    // Set Button 
    $("#btn_baru").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_cetakrekap").prop('disabled', true)
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Disable input 
    $("#tanggal").prop('readonly', false)
    $("#Catatan").prop('disabled', false)

    KlickDaftarPohonEmas()
}

function Ubah() {

    //get id gipsorder
    let IDSPKOGips = $('#IDSPKOGips').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // Get Operator
    // GET CATATAN
    let Catatan = $('#Catatan').val()
    var OOO = $('#O').val()

    // Get item
    let IDWaxtrees = $('.IDWaxtreeDipilih')

    // Check tanggal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDWaxtrees.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tidak ID Waxtree yang dipilih .",
        })
        return;
    }

    //!  ------------------------    Check Items Waxtree if have blank value     ------------------------ !!
    let cekIDWaxtrees = false
    IDWaxtrees.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat id Waxtree yang kosong.",
            })
            cekIDWaxtrees = true
            return false;
        }
    })
    if (cekIDWaxtrees == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDWaxtrees.length; index++) {
        var IDWaxtree = $(IDWaxtrees[index]).val()

        let dataitems = {
            IDWaxtree: IDWaxtree
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        IDSPKOGips: IDSPKOGips,
        OOO: OOO,
        date: date,
        Catatan: Catatan,
        items: items
    }
    console.log(data);
    // Setup CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // HitBackend 
    $.ajax({
        type: "PUT",
        url: "/Produksi/GipsLeburCor/SPKOGips/Update",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'Terupdate!',
                text: "Data Berhasil Terupdate.",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: false
            });

            $('#cari').val(data.data.ID)
            $('#IDSPKOGips').val(data.data.ID)
            // Set action to update 
            // Enablebutton "Baru, Ubah and Cetak"
            Search();
            return;
        },
        error: function(xhr) {
            // It will executed if response frombackend is error 
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
    $("#btn_cetak").prop('disabled', false)
    $("#btn_cetakrekap").prop('disabled', false)
    // disable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', true)
    $("#btn_batal").prop('disabled', true)
    // disabled input
    $("#IDSPKOGips").prop('readonly', true)
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
        url: "/Produksi/GipsLeburCor/SPKOGips/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').prop('hidden', true);
            $('#tabel2').prop('hidden', false);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody ").empty()
            $("#tabel2 tbody ").empty()
            // Set WaxInjectOrderID 
            $('#IDSPKOGips').val(data.data.ID)
            $('#tanggal').val(data.data.TransDate)
            $('#Catatan').val(data.data.Remarks)
            // Set user admin batu
            $('#UserNameAdmin').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatan').text(data.data.EntryDate)
            // Set item table let 
            $('#postingstatus').val(data.data.Active)
            $("#infoposting").text(data.data.Posting)
            $('#O').val(data.data.OOO)
            let no = 1
            data.data.items2.forEach(function(value, i) {
                let start =
                    "<tr class='klik2' id='" + no + "'>"
                let uruts =
                    '<td><span id="urut_' + no + '" value="">' + no +
                    '</span></td>' +
                    '<td hidden><input class="IDWaxtreeDipilih" id="IDwaxtree' + no + '" value="' +
                    value
                    .IDWaxtree + '"></input></td>'
                let Dates =
                    '<td><span id="Dates_' + no + '" value="">' + value.TreeDate +
                    '</span></td>'
                let Plates =
                    '<td><span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate +
                    '</span><br><span style="font-size:10px; vertical-align: text-top;">' + value
                    .WaxTree + '</span></td>'
                let Sizes =
                    '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                    '</span></td>'
                let Kadars =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="KadarPohon_' +
                    no +
                    '">' + value.Carat + '</span></td>'
                let BeratLilins =
                    '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(2) +
                    '</span><input hidden class="Jumlahdata" id="Jumlahdata_' + no +
                    '" value="1"></td>'
                let BeratBatus =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value
                    .WeightStone.toFixed(
                        2) +
                    '</span></td>'
                let Qtys =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value.Qty +
                    '</span></td>'
                let NoSPKPPICs =
                    '<td><div class="SPK" style="font-size:15px; color:' + value
                    .WorkText +
                    '; font-weight: bold;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let BeratFG = '<td center><span class="JumlahBerat" center id="Berat_' + no +
                    '" value="" onkeyup="TotalDataBerat(' + value.WeightFG.toFixed(2) + ')">' +
                    value.WeightFG
                    .toFixed(2) +
                    '</span></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, uruts, Dates,
                    Plates, Sizes, Kadars, BeratLilins, BeratBatus, Qtys, NoSPKPPICs, BeratFG,
                    trEnd)
                $("#tabel2 > tbody").append(rowitem);
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
            jumlahcari()

            // show user admin dan tangal entry
            $('#show').prop('hidden', false)

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

function Klik_Posting1() {
    $("#Posting1").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#infoposting").text('POSTED')
    let IDSPKOGips = $('#IDSPKOGips').val()
    $("#btn_cetak").prop('disabled', false)
    $("#btn_cetakrekap").prop('disabled', false)
    if (IDSPKOGips == null || IDSPKOGips == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "SPKOGips Belum Dibuat",
        })
        return;
    }
    let data = {
        IDSPKOGips: IDSPKOGips
    }
    // alert(IDSPKOGips);
    // Setup CSRF TOKEN
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/Produksi/GipsLeburCor/SPKOGips/posting",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // Set posting status
            $('#postingstatus').val('P')

            // Button Settings
            $("#btn_baru").prop('disabled', false)
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            $("#btn_posting").prop('disabled', true)
            $("#btn_cetak").prop('disabled', false)
            $("#btn_cetakrekap").prop('disabled', false)

            // Input Settings

            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil di posting...',
                text: "Success",
            })
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

function klikCetak() {
    // Get idWaxTree
    let IDSPKOGips = $('#cari').val()
    $("#btn_baru").prop('disabled', false)

    $("#btn_batal").prop('disabled', true)
    if (IDSPKOGips == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. isi id SPKOGips di kolom pencarian.",
        })
        return
    }
    // klikCetakrekap()
    window.open('/Produksi/GipsLeburCor/SPKOGips/cetak/' + IDSPKOGips + '/_blank');

}

function klikCetakRekap() {
    // Get idWaxTree
    let IDSPKOGips = $('#cari').val()
    $("#btn_baru").prop('disabled', false)

    $("#btn_batal").prop('disabled', true)
    if (IDSPKOGips == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. isi id SPKOGips di kolom pencarian.",
        })
        return
    }
    // window.open('/Produksi/GipsLeburCor/SPKOGips/cetak/' + IDSPKOGips + '/_blank');
    window.open('/Produksi/GipsLeburCor/SPKOGips/cetakrekap/' + IDSPKOGips + '/_blank');

}
</script>
@endsection
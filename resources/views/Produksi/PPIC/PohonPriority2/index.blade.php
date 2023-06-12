<?php $title = 'Pohon priority'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
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
        <div class="card mb-1">

            @include('Produksi.PPIC.PohonPriority2.data')

        </div>
    </div>
</div>
@endsection

@section('script')

{{-- This Page Script --}}
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
<script>
function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#idEmployee').val();

    if (IdOperator !== '') {
        $.get('/Produksi/PPIC/PohonPriority/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
    }
}

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


$('#tabel2').DataTable({
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

function KlikBaru() {
    $('#IDwax').val('')
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $('#action').val('simpan')
}

function klikBatal() {
    window.location.reload()
}

function totalsudahdiklick() {
    var Rusaks = $('#tabel1').find('.pohonpilih')
    var Rusaks2 = $('#tabel1').find('.patripilih')
    var Rusaks3 = $('#tabel1').find('.pukpilih')
    var Rusaks4 = $('#tabel1').find('.fgpilih')
    var Rusaks5 = $('#tabel1').find('.polespilih')
    var total = 0;
    var total2 = 0;
    var total3 = 0;
    var total4 = 0;
    var total5 = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    for (let i = 0; i < Rusaks2.length; i++) {
        var aa = parseInt($(Rusaks2[i]).text())

        total2 = total2 + aa
    }
    for (let i = 0; i < Rusaks3.length; i++) {

        var aa = parseInt($(Rusaks3[i]).text())

        total3 = total3 + aa
    }
    for (let i = 0; i < Rusaks4.length; i++) {
        var aa = parseFloat($(Rusaks4[i]).text())

        total4 = total4 + aa
    }
    for (let i = 0; i < Rusaks5.length; i++) {
        var aa = parseInt($(Rusaks5[i]).text())

        total5 = total5 + aa
    }

    $('#tpri').text(total);
    $('#tfg').text(total4.toFixed(2));
    $('#poles').text(total5);
    $('#patri').text(total2);
    $('#puk').text(total3);
}

function TotalData(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel1').find('.Jumlahdata')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldata').text(total);
}

function TotalDataPohon(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel1').find('.JumlahQty')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapohon').text(total);
}

function TotalDataPoles(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel1').find('.JumlahPoles')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapoles').text(total);
}

function TotalDataPatri(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel1').find('.JumlahPatri')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapatri').text(total);
}

function TotalDataPuk(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel1').find('.JumlahPuk')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapuk').text(total);
}

function TotalDataBerat(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel1').find('.JumlahBerat')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseFloat($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldataberat').text(total.toFixed(2));
}
//------------------------------------------------------------------------------------ 

function TotalData2(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel2').find('.Jumlahdata')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldata').text(total);
    $('#tpri').text(total);
}

function TotalDataPohon2(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel2').find('.JumlahQty')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapohon').text(total);
}

function TotalDataPoles2(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel2').find('.JumlahPoles')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapoles').text(total);
    $('#poles').text(total);

}

function TotalDataPatri2(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel2').find('.JumlahPatri')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapatri').text(total);
    $('#patri').text(total);
}

function TotalDataPuk2(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel2').find('.JumlahPuk')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldatapuk').text(total);
    $('#puk').text(total);
}

function TotalDataBerat2(Qty) {
    console.log(Qty);
    var Rusaks = $('#tabel2').find('.JumlahBerat')

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseFloat($(Rusaks[i]).text())

        total = total + aa
    }
    console.log(total);
    $('#totaldataberat').text(total.toFixed(2));
    $('#tfg').text(total.toFixed(2));
}

function tabelpilihanpohon() {

    $.ajax({
        type: "GET",
        url: "/Produksi/PPIC/PohonPriority/Tabels",
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').DataTable().destroy();
            $("#tabel1 tbody").empty();
            $("#tabel2").prop('hidden', true);
            $("#tabel1").prop('hidden', false);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            $("#tpri").text(0);
            $("#tfg").text(0);
            $("#poles").text(0);
            $("#patri").text(0);
            $("#puk").text(0);

            // Set item table
            let no = 1
            data.data.tabel.forEach(function(value, i) {
                let start =
                    "<tr class='klik3 m-0 p-0 " + value.cssterpilih + "' id='" + no + "'>"
                let PIlls =
                    '<td class="m-0 p-0"><div><input class="form-check-input Priority" type="checkbox" name="id[]" id="Priority_' +
                    no +
                    '" value ="' + value.IDWaxtree + '" disabled data-itung="1" data-FG="' + value
                    .WeightFG
                    .toFixed(2) + '" data-Poles="' + value.TotalPoles +
                    '" data-Patri="' + value.TotalPatri + '" data-Puk="' + value.TotalPUK +
                    '" ' + value.Checked + '></div></td>'
                let Dates =
                    '<td class="p-0 m-0"><span id="Dates_' + no + '" value="">' + value.TransDate +
                    '</span></td>'
                let Plates =
                    '<td class="p-0 m-0"> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate + '</span></td>'
                let Kadars =
                    '<td class="p-0 m-0"><span class="badge" style="font-size:14px; background-color: ' +
                    value
                    .HexColor +
                    '" id="kadarkaret_' +
                    no +
                    '">' + value.Kadar + '</span></td>'
                let NoSPKPPICs =
                    '<td><div class="badge SPK" style="line-height: 1.5; font-size:14px; background-color:' +
                    value
                    .WorkText +
                    '; color: #fff;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let Models =
                    '<td><span class="badge" id="Models_' + no +
                    '" value="" style="font-size:14px; background-color:' + value.infomodel +
                    '; color: #fff;">' + value
                    .Model +
                    '</span></td>'
                let Products =
                    '<td class="p-0 m-0"><span id="Products_' + no +
                    '" value="" style="font-size:15px; color: #000; font-weight: normal; word-break: break-all;">' +
                    value.Product +
                    '</span></td>'
                let dataqty =
                    '<td class="p-0 m-0"><span class="JumlahQty" id="Jumlah_' + no + '" value="' +
                    value.Qty +
                    '" >' + value.Qty + '</span>' +
                    '<span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' + no +
                    '" >1</span>' +
                    '<span hidden class="polespilih"  value="' + no +
                    '" >' + value.TotalPolespilih + '</span>' +
                    '<span hidden class="patripilih" value="' + no +
                    '" >' + value.TotalPatripilih + '</span>' +
                    '<span hidden class="pukpilih" value="' +
                    no +
                    '" >' + value.TotalPUKpilih + '</span>' +
                    '<span hidden class="fgpilih" value="' + no +
                    '" >' + value.WeightFGpilih + '</span>' +
                    '<span hidden class="pohonpilih" value="' + no +
                    '" >' + value.jumlahpohonpilih + '</span>' + '</td>' +
                    '<td class="p-0 m-0"><span class="JumlahPoles" id="Poles_' + no +
                    '" value="" onkeyup="TotalDataPoles(' + value
                    .TotalPoles + ')">' + value.TotalPoles +
                    '</span></td>' +
                    '<td class="p-0 m-0"><span class="JumlahPatri" center id="Patri_' + no +
                    '" value="">' + value
                    .TotalPatri +
                    '</span></td>' +
                    '<td class="p-0 m-0"><span  class="JumlahPuk" id="PUK_' + no +
                    '" value="">' + value.TotalPUK +
                    '</span></td>' +
                    '<td class="p-0 m-0"><span class="JumlahBerat" center id="Berat_' + no +
                    '" value="">' +
                    value.WeightFG
                    .toFixed(2) +
                    '</span></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, PIlls, Dates,
                    Plates, Kadars, NoSPKPPICs, Models, Products,
                    dataqty, trEnd)
                // console.log(rowitemkaret);
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
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
            totalsudahdiklick()
            TotalData();
            TotalDataPohon();
            TotalDataPoles();
            TotalDataPatri();
            TotalDataPuk();
            TotalDataBerat();



            $(".klik3").on('click', function(e) {
                var id = $(this).attr('id');
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');

                    let IDpohon = $('#Priority_' + id).val()
                    console.log(IDpohon + '-> jadi N');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/Produksi/PPIC/PohonPriority/UbahjadiN/' + IDpohon,
                    });

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#tpri').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung - parseInt(itung)
                    $('#tpri').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#tfg').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg - parseFloat(fg)
                    $('#tfg').text(calfg.toFixed(2))
                    //---------------------------------
                    let pol = parseInt($('#Priority_' + id).attr('data-Poles'))
                    let totalpol = parseInt($('#poles').text())
                    if (isNaN(totalpol)) {
                        totalpol = 0
                    }
                    let calpol = totalpol - parseInt(pol)
                    $('#poles').text(calpol)
                    //---------------------------------
                    let pat = parseInt($('#Priority_' + id).attr('data-Patri'))
                    let totalpat = parseInt($('#patri').text())
                    if (isNaN(totalpat)) {
                        totalpat = 0
                    }
                    let calpat = totalpat - parseInt(pat)
                    $('#patri').text(calpat)
                    //---------------------------------
                    let puk = parseInt($('#Priority_' + id).attr('data-Puk'))
                    let totalpuk = parseInt($('#puk').text())
                    if (isNaN(totalpuk)) {
                        totalpuk = 0
                    }
                    let calpuk = totalpuk - parseInt(puk)
                    $('#puk').text(calpuk)

                    //generate tampilan titakterpilih
                    $('#Priority_' + id).attr('checked', false);
                    // console.log(id);
                } else {
                    $(this).addClass('table-primary');

                    let IDpohon = $('#Priority_' + id).val()
                    console.log(IDpohon + '-> jadi R');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/Produksi/PPIC/PohonPriority/UbahjadiR/' + IDpohon,
                    });

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#tpri').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung + parseInt(itung)
                    $('#tpri').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#tfg').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg + parseFloat(fg)
                    $('#tfg').text(calfg.toFixed(2))
                    //---------------------------------
                    let pol = parseInt($('#Priority_' + id).attr('data-Poles'))
                    let totalpol = parseInt($('#poles').text())
                    if (isNaN(totalpol)) {
                        totalpol = 0
                    }
                    let calpol = totalpol + parseInt(pol)
                    $('#poles').text(calpol)
                    //---------------------------------
                    let pat = parseInt($('#Priority_' + id).attr('data-Patri'))
                    let totalpat = parseInt($('#patri').text())
                    if (isNaN(totalpat)) {
                        totalpat = 0
                    }
                    let calpat = totalpat + parseInt(pat)
                    $('#patri').text(calpat)
                    //---------------------------------
                    let puk = parseInt($('#Priority_' + id).attr('data-Puk'))
                    let totalpuk = parseInt($('#puk').text())
                    if (isNaN(totalpuk)) {
                        totalpuk = 0
                    }
                    let calpuk = totalpuk + parseInt(puk)
                    $('#puk').text(calpuk)

                    //generate tampilan terpilih
                    $('#Priority_' + id).attr('checked', true);
                }
                return false;
            });

            $("#btn_simpan").prop('disabled', false)
            $("#btn_batal").prop('disabled', false)
            $("#tabel1").css("padding", 0);
            $("#tabel1").css("margin", 0);

            // var filterData = [];
            // $('#tabel1').DataTable({
            //     orderCellsTop: true,
            //     stateSave: true,
            //     initComplete: function() {
            //         this.api()
            //             .columns([1, 2])
            //             .every(function(index) {
            //                 var column = this;
            //                 var select = $(
            //                         '<select class="form-control form-control--filter"><option value=""> -- Filter -- </option></select>'
            //                     )
            //                     .appendTo($('thead tr:eq(1) td:eq(' + this.index() + ')'))
            //                     .on('change', function() {
            //                         var val = $.fn.dataTable.util.escapeRegex(
            //                             $(this).val()
            //                         );
            //                         column
            //                             .search(val ? '^' + val + '$' : '', true, false)
            //                             .draw();
            //                     });

            //                 var option;
            //                 column.data().unique().sort().each(function(d, j) {
            //                     if (!d == '') {
            //                         option = $('<option value="' + d + '">' + d +
            //                             '</option>');
            //                         if (d == filterData[index]) {
            //                             option.prop('selected', true);
            //                         }
            //                         select.append(option);
            //                     }
            //                 });

            //             });
            //     },
            //     stateLoadParams: function(settings, data) {
            //         data.columns.forEach(function(col, i) {
            //             filterData.push(col.search.search);
            //         });
            //     }
            // });
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

function tabelpilihanpohondipilih() {

    $.ajax({
        type: "GET",
        url: "/Produksi/PPIC/PohonPriority/Tabels2",
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').DataTable().destroy();
            $('#tabel2').DataTable().destroy();
            $("#tabel2 tbody").empty();
            $("#tabel1 tbody").empty();
            $("#tabel2").prop('hidden', false);
            $("#tabel1").prop('hidden', true);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            $("#tpri").text(0);
            $("#tfg").text(0);
            $("#poles").text(0);
            $("#patri").text(0);
            $("#puk").text(0);
            // Set item table
            let no = 1
            data.data.tabel2.forEach(function(value, i) {
                let start =
                    "<tr class='klik4 m-0 p-0 table-primary' id='" + no + "'>"
                let PIlls =
                    '<td class="m-0 p-0"><div><input class="form-check-input Priority" type="checkbox" name="id[]" id="Priority2_' +
                    no +
                    '" value ="' + value.IDWaxtree + '" disabled checked data-itung="1" data-FG="' +
                    value
                    .WeightFG
                    .toFixed(2) + '" data-Poles="' + value.TotalPoles +
                    '" data-Patri="' + value.TotalPatri + '" data-Puk="' + value.TotalPUK +
                    '"></div></td>'
                let Dates =
                    '<td><span id="Dates_' + no + '" value="">' + value.TransDate +
                    '</span></td>'
                let Plates =
                    '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate + '</span>'
                let Kadars =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor + ';" id="kadarkaret_' +
                    no +
                    '">' + value.Kadar + '</span></td>'
                let NoSPKPPICs =
                    '<td><div class="badge SPK" style="line-height: 1.5; font-size:14px; background-color:' +
                    value
                    .WorkText +
                    '; color: #fff;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let Models =
                    '<td><span class="badge" id="Models_' + no +
                    '" value="" style="font-size:14px; background-color:' + value.infomodel +
                    '; color: #fff;">' + value
                    .Model +
                    '</span></td>'
                let Products =
                    '<td class="p-0 m-0"><span id="Products_' + no +
                    '" value="" style="font-size:15px; color: #000; font-weight: normal; word-break: break-all;">' +
                    value.Product +
                    '</span></td>'
                let dataqty =
                    '<td class="p-0 m-0"><span class="JumlahQty" id="Jumlah_' + no + '" value="' +
                    value.Qty +
                    '" >' + value.Qty + '</span>' +
                    '<span hidden class="Jumlahdata" id="Jumlahdata_' + no + '" value="' + no +
                    '" >1</span></td>' +
                    '<td class="p-0 m-0"><span class="JumlahPoles" id="Poles_' + no +
                    '" value="" onkeyup="TotalDataPoles(' + value
                    .TotalPoles + ')">' + value.TotalPoles +
                    '</span></td>' +
                    '<td class="p-0 m-0"><span class="JumlahPatri" center id="Patri_' + no +
                    '" value="">' + value
                    .TotalPatri +
                    '</span></td>' +
                    '<td class="p-0 m-0"><span  class="JumlahPuk" id="PUK_' + no +
                    '" value="">' + value.TotalPUK +
                    '</span></td>' +
                    '<td class="p-0 m-0"><span class="JumlahBerat" center id="Berat_' + no +
                    '" value="">' +
                    value.WeightFG
                    .toFixed(2) +
                    '</span></td>'
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, PIlls, Dates,
                    Plates, Kadars, NoSPKPPICs, Models, Products,
                    dataqty, trEnd)
                // console.log(rowitemkaret);
                $("#tabel2 > tbody").append(rowitem);
                no += 1;
            });
            $('#tabel2').DataTable({
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
            TotalData2();
            TotalDataPohon2();
            TotalDataPoles2();
            TotalDataPatri2();
            TotalDataPuk2();
            TotalDataBerat2();

            $(".klik4").on('click', function(e) {
                var id = $(this).attr('id');
                if ($(this).hasClass('table-primary')) {
                    $(this).removeClass('table-primary');

                    let IDpohon = $('#Priority2_' + id).val()
                    console.log(IDpohon + '-> jadi N');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/Produksi/PPIC/PohonPriority/UbahjadiN/' + IDpohon,
                    });

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority2_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#tpri').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung - parseInt(itung)
                    $('#tpri').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority2_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#tfg').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg - parseFloat(fg)
                    $('#tfg').text(calfg.toFixed(2))
                    //---------------------------------
                    let pol = parseInt($('#Priority2_' + id).attr('data-Poles'))
                    let totalpol = parseInt($('#poles').text())
                    if (isNaN(totalpol)) {
                        totalpol = 0
                    }
                    let calpol = totalpol - parseInt(pol)
                    $('#poles').text(calpol)
                    //---------------------------------
                    let pat = parseInt($('#Priority2_' + id).attr('data-Patri'))
                    let totalpat = parseInt($('#patri').text())
                    if (isNaN(totalpat)) {
                        totalpat = 0
                    }
                    let calpat = totalpat - parseInt(pat)
                    $('#patri').text(calpat)
                    //---------------------------------
                    let puk = parseInt($('#Priority2_' + id).attr('data-Puk'))
                    let totalpuk = parseInt($('#puk').text())
                    if (isNaN(totalpuk)) {
                        totalpuk = 0
                    }
                    let calpuk = totalpuk - parseInt(puk)
                    $('#puk').text(calpuk)

                    //generate tampilan titakterpilih
                    $('#Priority2_' + id).attr('checked', false);
                    console.log(id);
                } else {
                    $(this).addClass('table-primary');

                    let IDpohon = $('#Priority2_' + id).val()
                    console.log(IDpohon + '-> jadi Y');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "PUT",
                        url: '/Produksi/PPIC/PohonPriority/UbahjadiY/' + IDpohon,
                    });

                    // hitung jumlah data yang di klick
                    let itung = parseInt($('#Priority2_' + id).attr('data-itung'))
                    let totalItung = parseInt($('#tpri').text())
                    if (isNaN(totalItung)) {
                        totalItung = 0
                    }
                    let calItung = totalItung + parseInt(itung)
                    $('#tpri').text(calItung)
                    //---------------------------------
                    let fg = parseFloat($('#Priority2_' + id).attr('data-FG'))
                    let totalfg = parseFloat($('#tfg').text())
                    if (isNaN(totalfg)) {
                        totalfg = 0
                    }
                    let calfg = totalfg + parseFloat(fg)
                    $('#tfg').text(calfg.toFixed(2))
                    //---------------------------------
                    let pol = parseInt($('#Priority2_' + id).attr('data-Poles'))
                    let totalpol = parseInt($('#poles').text())
                    if (isNaN(totalpol)) {
                        totalpol = 0
                    }
                    let calpol = totalpol + parseInt(pol)
                    $('#poles').text(calpol)
                    //---------------------------------
                    let pat = parseInt($('#Priority2_' + id).attr('data-Patri'))
                    let totalpat = parseInt($('#patri').text())
                    if (isNaN(totalpat)) {
                        totalpat = 0
                    }
                    let calpat = totalpat + parseInt(pat)
                    $('#patri').text(calpat)
                    //---------------------------------
                    let puk = parseInt($('#Priority2_' + id).attr('data-Puk'))
                    let totalpuk = parseInt($('#puk').text())
                    if (isNaN(totalpuk)) {
                        totalpuk = 0
                    }
                    let calpuk = totalpuk + parseInt(puk)
                    $('#puk').text(calpuk)

                    //generate tampilan terpilih
                    $('#Priority2_' + id).attr('checked', true);
                }
                return false;
            });
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', false)
            $("#tabel2").css("padding", 0);
            $("#tabel2").css("margin", 0);
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
            // Set idWaxInjectOrder to blank
            $("#idWaxInjectOrder").val("")
            return;
        }
    })
}

function centangpohon() {

}


function KlikSimpan() {

    // update tabel waxtreeitem kolom priority

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "PUT",
        url: '/Produksi/PPIC/PohonPriority/Simpan',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function() {

            Swal.fire({
                icon: 'success',
                title: 'TerUpdate!',
                text: " Data Berhasil Disimpan",
                timer: 800,
                showCancelButton: false,
                showConfirmButton: false
            });
            // klikCetak()
            // tabelpilihanpohon()
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Upss Error !',
                text: 'Data Gagal Disimpan!',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });
}

function klikCetak() {

    var Priority = [];
    $('.Priority:checked').each(function(i, e) {
        let idWax = $(this).val();

        Priority.push(idWax);
    });
    tabelpilihanpohon()
    // alert(Priority);

    window.open('/Produksi/PPIC/PohonPriority/cetak/' + Priority + '/_blank');

}
</script>
@endsection
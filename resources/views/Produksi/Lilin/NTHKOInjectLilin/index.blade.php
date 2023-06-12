<?php $title = 'NTHKO Inject Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">NTHKO Inject Lilin </li>
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

            @include('Produksi.Lilin.NTHKOInjectLilin.data')

        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Timbangan Script --}}
@include('layouts.backend-Theme-3.timbangan')
{{-- This Page Script --}}
<script>
// Script Timbanganku
async function kliktimbang2(id) {
    if (event.keyCode === 13) {
        await sendSerialLine();
        document.getElementById("selscale").value = id;
        // Get value of beratPohonTotal
        let beratPohonTotal = $('#beratPohonTotal').val()
        // Get value of beratBatu
        let beratBatu = $('#beratBatu').val()
        // Get value of berat Pohon
        let BeratPiring = $('#BeratPiring').val()
        // calculate berat resin
        let beratResin = beratPohonTotal - beratBatu - BeratPiring
        // Format Float just 2 digit after coma
        beratResin = beratResin.toFixed(2)
        // set beratResin
        $('#beratResin').val(beratResin)
    }
}


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
    dom: 'Bfrtip',
    buttons: [{
        extend: 'print',
        split: ['excel', 'pdf', 'print', 'copy', 'csv'],
    }]
    // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
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
    dom: 'Bfrtip',
    buttons: [{
        extend: 'print',
        split: ['excel', 'pdf', 'print', 'copy', 'csv'],
    }]
    // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
});

function KlikBaru() {
    // let idWaxTree = $('#idWaxTree').val()
    // if (idWaxTree != "") {
    //     // If idWaxTree have value. It will reload th page
    //     window.location.reload()
    //     return;
    // }
    // Disable button "Baru and Cetak"
    $('#action').val('simpan')
    $("#btn_baru").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $("#tanggal").prop('disabled', false)
    $("#idWaxInjectOrder").prop('readonly', false)
    $("#idEmployee").prop('disabled', false)
    $("#NomorPiring").prop('disabled', false)
    $("#beratPohonTotal").prop('disabled', false)
    $("#beratBatu").prop('disabled', false)
    $("#Catatan").prop('disabled', false)

    $("#idWaxInjectOrder").val('')
    $("#NomorPiring").val('')
    $("#BeratPiring").val('')
    $("#beratPohonTotal").val('')
    $("#beratBatu").val('')
    $("#Catatan").val('')
    $("#totalQTY").val('')
    $("#beratResin").val('')
    $("#stickpohon").val('')
    $("#kadar").val('')
    $("#idkadar").text('')
    $("#IdPiring").text('')
    $("#idWaxInjectOrder").focus()
    $("#infoposting").text('')

    // $('#fromheader1').empty()
    // $('#fromheader2').empty()
    // $('#fromheader3').empty()
    // $('#fromheader4').empty()
    $("#show").empty()
    $("#tabel1 tbody").empty()
    $("#tabel2 tbody").empty()
}

function klikBatal() {
    window.location.reload()
}

function SearchWaxInjectOrder() {
    // Get idWaxInjectOrder from input
    let waxinjectorderid = $('#idWaxInjectOrder').val();
    if (waxinjectorderid == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. Waxinjectorder ID Cannot be null.",
        })
        return
    }

    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/NTHKOInjectLilin/Items/" + waxinjectorderid,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody").empty()
            // Set idKadar 
            $('#idKadar').text(data.data.idKadar)
            $('#kadar').val(data.data.Kadar)
            // Set TotalQTY
            $('#totalQTY').val(data.data.totalQty)
            // Set stik Pohon
            $('#stickpohon').val(data.data.stickpohon)
            $('#idstick').text(data.data.idstick)
            $('#stickid').val(data.data.idstick)
            // set piringan
            $('#IdPiring').text(data.data.IdPiring)
            $('#NomorPiring').val(data.data.NomorPiring)
            $('#BeratPiring').val(data.data.BeratPiring)
            // Set Berat Batu
            $('#beratBatu').val(data.data.tbb)
            $('#totalBatu').val(data.data.Qtyb)
            // Set Catatan
            $("#idbatu").text(data.data.IDpenggunaanbatu)
            $('#Catatan').val(data.data.Catatan)
            // set workgroub
            $('#WorkGroup').val(data.data.WorkGroup)
            // set purpose
            $('#purpose').val(data.data.Purpose)


            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let trStart = "<tr class='baris' id='tr" + no + "'>"
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                // let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    "<td hidden></span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center workOrder' id='workOrder_" +
                    no + "' readonly value=" + value.WorkOrder + "></td>"
                let SW =
                    "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm fs-6 w-10 text-center bg-dark noSPK' id='noSPK_" +
                    no + "' onkeyup='getSWItemProduct(" +
                    no + ")' value=" + value.SW + "></td>"
                let idBarang =
                    "<td hidden><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center idProduct' id='idProduct_" +
                    no + "' readonly value=" + value.Product +
                    "><input type='hidden' class='WorkOrderOrd' id='WorkOrderOrd" + no +
                    "' readonly value=" + value.WorkOrderOrd +
                    "><input type='hidden' class='ProductVar' id='ProductVar" + no +
                    "' readonly value=" + value.ProductVariation +
                    "></td>"
                let Barang =
                    "<td><input type='text' style='color: #FFF; font-weight: bold;'  tabindex='7' class='form-control form-control-sm w-10 fs-6 text-center bg-primary product' id='product_" +
                    no + "' onkeyup='getSWItemProduct(" +
                    no + ")' value='" + value.Barang +
                    "'><span class='badge text-dark bg-light' id='DescriptionProd" + no + "'>" +
                    value
                    .Description +
                    "</span></td>"
                let ProdukJadi =
                    "<td><span class='badge bg-light text-dark ProdukJadi' id='ProdukJadi_" + no +
                    "'>" + value.produkjadiaa +
                    "</td>"
                let Kadar =
                    "<td><span class='badge' style='font-size:14px; background-color:" + value
                    .HexColor + "' id='KadarItem" + no + "'>" + kadar_val +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar_' id='kadar_" +
                    no + "' readonly value=" + value.IDKadar + "></td>"
                let QTY =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
                    no + "'  onkeyup='jumlahqty(this.value)' value='" + value.Qty +
                    "' tabindex='7'></td>"
                let variations =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center variation' id='variation_" +
                    no + "' value=" + value.NoteVariation + "></td>"
                // let Remarks =
                //     "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center remarks' id='remarks_" +
                //     no + "' value=" + remarks_val + "></td>"
                let Action =
                    "<td align='center'><button class='add btn btn-info btn-sm' type='button' onclick='add(" +
                    no + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='remove btn btn-danger btn-sm' onclick='remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, WorkOrder, SW, idBarang, Barang,
                    ProdukJadi,
                    Kadar, QTY, variations, Action, trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            $("#tabel2 tbody").empty()

            let nob = 1
            data.data.batu.forEach(function(value, i) {
                let trStart = "<tr class='baris2' id='tr2" + nob + "'>"
                // no, idbatu, jenisbatu, ukuran, keperluan, pcs, berat, rata - rata
                let urut =
                    "<td>" + nob +
                    "</td>"
                let ID =
                    "<td><span class='badge bg-dark' style='font-size:14px;'>" + value.IDM +
                    "</span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center workOrderbatu' id='workOrderbatu_" +
                    nob + "' readonly value='" + value.IDM + "'></td>"
                let SW =
                    "<td><span class='badge bg-primary' style='font-size:14px;'>" + value.SW +
                    "</span><br>" + value.Description +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center noSPKbatu' id='noSPKbatu_" +
                    nob + "' readonly value=" + value.SW + "></td>"
                let size =
                    "<td>" + value.Size + "</td>"
                let Keperluan =
                    "<td>" + value.Purpose + "</td>"
                let pcs =
                    "<td>" + value.Qty + "</td>"
                let Berat =
                    "<td >" + value.Weight + "</td>"
                let ratarata =
                    "<td>" + value.Avg.toFixed(4) + "</td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, ID, SW, size, Keperluan, pcs, Berat,
                    ratarata, trEnd)
                $("#tabel2 > tbody").append(rowitem);
                nob += 1;
            });
            jumlahqty();
            $('.itemQty').prop('disabled', false);
            $('.variation').prop('disabled', false);
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

function getSWItemProduct(id) {

    var id = id;
    let switem = String($('#product_' + id).val());
    let work = String($('#noSPK_' + id).val());

    data = {
        id: id,
        switem: switem,
        work: work
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/Produksi/Lilin/NTHKOInjectLilin/cariSWItemProduct',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {
            console.log(data['SW'] + " " + data['Barang']);

            if (data.rowcount > 0) {
                $('#workOrder_' + id).val(data.WorkOrder)
                $('#noSPK_' + id).val(data.SW)
                $('#idProduct_' + id).val(data.Product)
                $('#product_' + id).val(data.Barang)
                $('#DescriptionProd' + id).text(data.Description)
                $('#KadarItem' + id).text(data.Kadar)
                $('#KadarItem' + id).css('background-color', data.HexColor)
                $('#kadar_' + id).val(data.IDKadar)
                $('#WorkOrderOrd' + id).val(data.WorkOrderOrd)
                $('#ProductVar' + id).val(data.ProductVariation)
                $('#beratbatu_' + id).val(data.BeratPerBatu)
                $('#variation_' + id).val(data.NoteVariation)
                $('#itemQty_' + id).val(data.Qty)

            } else {
                $('#workOrder_' + id).val('')

                $('#idProduct_' + id).val('')
                $('#WorkOrderOrd' + id).val('')
                $('#ProductVar' + id).val('')

                $('#DescriptionProd' + id).text('')
                $('#KadarItem' + id).text('')
                $('#KadarItem' + id).css('background-color', 'dark')
                $('#kadar_' + id).val('')
                $('#beratbatu_' + id).val('')
                $('#variation_' + id).val('')
                $('#itemQty_' + id).val('')
            }

        },
        complete: function() {
            $(".preloader").fadeOut();
        },
    });


}

function jumlahqty(qty) {
    console.log(qty);
    var Qtys = $('#tabel1').find('.itemQty')

    var total = 0;
    for (let i = 0; i < Qtys.length; i++) {
        var aa = parseInt($(Qtys[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#totalQTY').val(total);

}

function add(value, id) {

    let no = $('#tabel1 tr').length;
    let trStart = "<tr class='baris' id='tr" + no + "'>"

    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value=" + no + "></td>"
    let WorkOrder =
        "<td hidden></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center workOrder' id='workOrder_" +
        no + "' readonly value=''></td>"
    let SW =
        "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm fs-6 w-10 text-center bg-dark noSPK' onkeyup='getSWItemProduct(" +
        no + ")' id='noSPK_" +
        no + "' value=''></td>"
    let idBarang =
        "<td hidden><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center idProduct' id='idProduct_" +
        no + "' readonly value=''><input type='hidden' class='WorkOrderOrd' id='WorkOrderOrd" + no +
        "' readonly value=''><input type='hidden' class='ProductVar' id='ProductVar" + no +
        "' readonly value=''></td>"
    let Barang =
        "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm w-10 fs-6 text-center bg-primary product' id='product_" +
        no + "' onkeyup='getSWItemProduct(" +
        no + ")' value=''><span class='badge text-dark bg-light' id='DescriptionProd" + no + "'>" +
        "</span></td>"
    let ProdukJadi =
        "<td><span class='badge ProdukJadi' id='ProdukJadi_" + no + "'>" + value
        .produkjadiaa +
        "</td>"
    let Kadar =
        "<td><span class='badge' style='font-size:14px; background-color: dark' id='KadarItem" + no + "'>" +
        "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar_' id='kadar_" +
        no + "' readonly value=''></td>"
    let QTY =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
        no + "' onkeyup='jumlahqty(this.value)' value=''></td>"
    let variations =
        "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center variation' id='variation_" +
        no + "' value=''></td>"
    // let Remarks =
    //     "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center remarks' id='remarks_" +
    //     no + "' value=" + remarks_val + "></td>"
    let Action =
        "<td align='center'><button class='add btn btn-info btn-sm' type='button' onclick='add(" +
        no + ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='remove btn btn-danger btn-sm' onclick='remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
    let trEnd = "</tr>"
    let finalItem = ""
    let rowitem = finalItem.concat(trStart, urut, WorkOrder, SW, idBarang, Barang, ProdukJadi,
        Kadar, QTY, variations, Action, trEnd)
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
}

function remove(id) {
    document.getElementById("tabel1").deleteRow(id);
}

function searchPiring() {
    let NomorPiring = $('#NomorPiring').val();
    if (NomorPiring == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. Waxorder ID Cannot be null.",
        })
        return
    }

    let data = {
        keyword: NomorPiring
    }
    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/NTHKOInjectLilin/Piring",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // Set NomorPiring
            $('#NomorPiring').val(data.data.SW)
            // Set IdPiring
            $('#IdPiring').text(data.data.ID)
            // Set BeratPiring
            $('#BeratPiring').val(data.data.Weight)
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

// Calculate Berat Batu
function calculateBeratBatu() {
    // Get value of beratPohonTotal
    let beratPohonTotal = $('#beratPohonTotal').val()
    // Get value of beratBatu
    let beratBatu = $('#beratBatu').val()
    // Get value of berat Pohon
    let BeratPiring = $('#BeratPiring').val()
    // calculate berat resin
    let beratResin = beratPohonTotal - beratBatu - BeratPiring
    // Format Float just 2 digit after coma
    beratResin = beratResin.toFixed(2)
    // set beratResin
    $('#beratResin').val(beratResin)
}

function KlikSimpan() {
    // Get Action
    let action = $('#action').val()
    if (action == 'simpan') {
        SimpanNTHKO()
    } else {
        UbahNTHKO()
    }
}

function SimpanNTHKO() {
    // alert('simpan');

    // idWaxInjectOrder
    let idWaxInjectOrder = $('#idWaxInjectOrder').val()
    // Get idEmployee
    let idEmployee = $('#idEmployee').val()
    // get tanggal
    let tanggal = $('#tanggal').val()
    // get stick pohon
    let stickpohon = $('#stickpohon').val()
    let idstickpohon = $('#stickid').val()
    // get piring
    let piring = $('#IdPiring').text()
    // get beratpiring
    let beratpiring = $('#BeratPiring').val()
    // get total Qty
    let totalQty = $('#totalQTY').val()
    // get beratPohonTotal
    let beratPohonTotal = $('#beratPohonTotal').val()
    // get beratBatu
    let beratBatu = $('#beratBatu').val()
    //jumlah batu
    let banyakBatu = $('#totalBatu').val()
    // get resin 
    let beratResin = $('#beratResin').val()
    // get kadar
    let idKadar = $('#idKadar').text()
    let Kadar = $('#Kadar').val()
    // GET CATATAN
    let Catatan = $('#Catatan').val()
    // set workgroub
    let WorkGroup = $('#WorkGroup').val()
    // set purpose
    let purpose = $('#purpose').val()
    // items
    let workOrders = $('.workOrder')
    let noSPKs = $('.noSPK')
    let idProducts = $('.idProduct')
    let products = $('.product')
    let kadars = $('.kadar_')
    let itemQtys = $('.itemQty')
    let WorkOrderOrds = $('.WorkOrderOrd')
    let ProductVars = $('.ProductVar')
    // let NoteVars = $('.NoteVar')
    let variations = $('.variation')
    // let remarks = $('.remarks')
    console.log(' wxinject: ' + idWaxInjectOrder + ' id op: ' + idEmployee + ' tanggal: ' + tanggal + ' stick: ' +
        stickpohon + ' idstick: ' + idstickpohon + ' piring: ' +
        piring + ' beratpiring:  ' +
        beratpiring + ' totalQty: ' +
        totalQty + ' beratpohon: ' +
        beratPohonTotal + ' beratbatu: ' +
        beratBatu + ' beratresin: ' +
        beratResin + ' idkadar: ' +
        idKadar + ' kadar: ' +
        Kadar + ' catatan: ' +
        Catatan + ' ' +
        workOrders + ' ' + idProducts + ' ' + kadars + ' ' + itemQtys);
    // Check idWaxInjectOrder
    if (idWaxInjectOrder == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "idWaxInjectOrder Can't be blank",
        })
        return;
    }

    // Check idEmployee
    if (idEmployee == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "idEmployee Can't be blank",
        })
        return;
    }

    // Check beratBatu
    if (beratBatu == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "beratBatu Can't be blank",
        })
        return;
    }

    // Check beratPohonTotal
    if (beratPohonTotal == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "beratPohonTotal Can't be blank",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (workOrders.length === 0 || idProducts.length === 0 || kadars.length === 0 || itemQtys.length === 0 ||
        WorkOrderOrds
        .length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekWorkOrder = false
    workOrders.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "kolom SPK PPIC ada yang salah",
            })
            cekWorkOrder = true
            return false;
        }
    })
    if (cekWorkOrder == true) {
        return false;
    }

    //!  ------------------------    Check Items noSPK if have blank value     ------------------------ !!
    let cekidProducts = false
    idProducts.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "kolom item product ada yang salah",
            })
            cekidProducts = true
            return false;
        }
    })
    if (cekidProducts == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let cekkadars = false
    kadars.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "kolom kadar ada yang salah",
            })
            cekkadars = true
            return false;
        }
    })
    if (cekkadars == true) {
        return false;
    }

    //!  ------------------------    Check Items Qty if have blank value     ------------------------ !!
    let cekitemQty = false
    itemQtys.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "kolom item Qty ada yang kosong",
            })
            cekitemQty = true
            return false;
        }
    })
    if (cekitemQty == true) {
        return false;
    }

    //!  ------------------------    Check WorkOrderOrd if have blank value     ------------------------ !!
    let cekWorkOrderOrd = false
    WorkOrderOrds.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "kolom item Qty ada yang kosong",
            })
            cekWorkOrderOrd = true
            return false;
        }
    })
    if (cekWorkOrderOrd == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < workOrders.length; index++) {
        var workOrder = $(workOrders[index]).val()
        var noSPK = $(noSPKs[index]).val()
        var idProduct = $(idProducts[index]).val()
        var product = $(products[index]).val()
        var kadar = $(kadars[index]).val()
        var itemQty = $(itemQtys[index]).val()
        // var remark = $(remarks[index]).val()
        var WorkOrderOrd = $(WorkOrderOrds[index]).val()
        var ProductVar = $(ProductVars[index]).val()
        var variation1 = $(variations[index]).val()
        let dataitems = {
            workOrder: workOrder,
            noSPK: noSPK,
            idProduct: idProduct,
            kadar: kadar,
            product: product,
            itemQty: itemQty,
            // remark: remark,
            WorkOrderOrd: WorkOrderOrd,
            ProductVar: ProductVar,
            variation1: variation1
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        idWaxInjectOrder: idWaxInjectOrder,
        idEmployee: idEmployee,
        tanggal: tanggal,
        stickpohon: stickpohon,
        idstickpohon: idstickpohon,
        piring: piring,
        beratpiring: beratpiring,
        totalQty: totalQty,
        beratPohonTotal: beratPohonTotal,
        beratBatu: beratBatu,
        banyakBatu: banyakBatu,
        beratResin: beratResin,
        idKadar: idKadar,
        Catatan: Catatan,
        WorkGroup: WorkGroup,
        purpose: purpose,
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
        url: "/Produksi/Lilin/NTHKOInjectLilin/Simpan",
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
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: false
            });

            // Set idWaxTree
            $('#idWaxTree').val(data.data.ID)
            $('#cari').val(data.data.ID)

            // Set action to update
            $('#action').val('update')

            // Enable button "Baru, Ubah and Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)

            // Disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)

            // Disable input
            $("#tanggal").prop('disabled', true)
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#NomorPiring").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)
            $('#beratResin').prop('disabled', true)
            $("#Catatan").prop('disabled', true)

            $('.itemQty').prop('disabled', true)
            $('.variation').prop('disabled', true)

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
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)

    // Disable Inable input
    $("#tanggal").prop('disabled', true)
    $("#idWaxInjectOrder").prop('readonly', true)
    $("#idEmployee").prop('disabled', false)
    $("#NomorPiring").prop('disabled', false)
    $("#beratPohonTotal").prop('disabled', false)
    $("#beratBatu").prop('disabled', false)
    $("#Catatan").prop('disabled', false)
    $("#BeratPiring").prop('disabled', false)
    $("#beratResin").prop('disabled', false)
    $('.itemQty').prop('disabled', false)
    $('.variation').prop('disabled', false)
    $('#tambahkurang').prop('hidden', false)
    $('.add').prop('hidden', false)
    $('.remove').prop('hidden', false)
}

function SearchNTHKOPohonan() {
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
        url: "/Produksi/Lilin/NTHKOInjectLilin/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody").empty()
            // Set Hidden idWaxTree
            $('#idWaxTree').val(data.data.ID)
            // Set WaxInjectOrderID
            $('#idWaxInjectOrder').val(data.data.WaxOrder)
            // Set Employee
            $('#idEmployee').val(data.data.Employee).change();
            // Set Tanggal 
            $('#tanggal').val(data.data.TransDate)
            // Stick Pohon
            $('#stickpohon').val(data.data.stickpohon)
            $('#stickid').val(data.data.idstick)
            $('#idstick').text(data.data.idstick)
            // Set IdPiring
            $('#IdPiring').text(data.data.IdPiring)
            // Set NomorPiring
            $('#NomorPiring').val(data.data.namaPohon)
            // Set Berat Pohon
            $('#BeratPiring').val(data.data.BeratPiring)
            $("#BeratPiring").prop('disabled', true)
            // Set TotalQTY
            $('#totalQTY').val(data.data.Qty)
            // Set Berat Pohon Total
            $('#beratPohonTotal').val(data.data.WeightWax)
            // Set Berat Batu
            $('#beratBatu').val(data.data.WeightStone)
            // Set beratResin
            $('#beratResin').val(data.data.Weight)
            $('#beratResin').prop('disabled', true)
            // Set idKadar 
            $('#idKadar').text(data.data.idKadar)
            $('#kadar').val(data.data.Kadar)
            $('#purpose').val(data.data.Purpose)
            $('#Catatan').val(data.data.Remarks)
            $('#idbatu').text(data.data.idbatu)
            $("#showinformasi").prop('hidden', false)
            $("#username").text(data.data.UserName)
            $("#entry").text(data.data.EntryDate)

            $("#gips").text(data.data.GipsS)

            // Set item table
            let no = 1
            data.data.itemsl.forEach(function(value, i) {
                let trStart = "<tr class='baris' id='tr" + no + "'>"
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                // let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    "<td hidden></span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center workOrder' id='workOrder_" +
                    no + "' readonly value=" + value.WorkOrder + "></td>"
                let SW =
                    "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm fs-6 w-10 text-center bg-dark noSPK' id='noSPK_" +
                    no + "' onkeyup='getSWItemProduct(" +
                    no + ")' value=" + value.SW + "></td>"
                let idBarang =
                    "<td hidden><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center idProduct' id='idProduct_" +
                    no + "' readonly value=" + value.Product +
                    "><input type='hidden' class='WorkOrderOrd' id='WorkOrderOrd" + no +
                    "' readonly value=" + value.WorkOrderOrd +
                    "><input type='hidden' class='ProductVar' id='ProductVar" + no +
                    "' readonly value=" + value.ProductVariation +
                    "></td>"
                let Barang =
                    "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm w-10 fs-6 text-center bg-primary product' id='product_" +
                    no + "' onkeyup='getSWItemProduct(" +
                    no + ")' value='" + value.Barang +
                    "'><span class='badge text-dark bg-light' id='DescriptionProd" + no + "'>" +
                    value
                    .Description +
                    "</span></td>"
                let ProdukJadi =
                    "<td><span class='badge bg-light text-dark ProdukJadi' id='ProdukJadi_" + no +
                    "'>" + value
                    .produkjadiaa +
                    "</td>"
                let Kadar =
                    "<td><span class='badge' style='font-size:14px; background-color:" + value
                    .HexColor + "' id='KadarItem" + no + "'>" + kadar_val +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar_' id='kadar_" +
                    no + "' readonly value=" + value.IDKadar + "></td>"
                let QTY =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
                    no + "'  onkeyup='jumlahqty(this.value)' value='" + value.Qty + "'></td>"
                let variations =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center variation' id='variation_" +
                    no + "' value=" + value.NoteVariation + "></td>"
                // let Remarks =
                //     "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center remarks' id='remarks_" +
                //     no + "' value=" + remarks_val + "></td>"
                let Action =
                    "<td align='center'><button class='add btn btn-info btn-sm' type='button' onclick='add(" +
                    no + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='remove btn btn-danger btn-sm' onclick='remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, WorkOrder, SW, idBarang, Barang,
                    ProdukJadi, Kadar, QTY, variations, Action, trEnd)
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            $("#tabel2 tbody").empty()

            let nob = 1
            data.data.batusl.forEach(function(value, i) {
                let trStart = "<tr class='baris2' id='tr2" + nob + "'>"
                // no, idbatu, jenisbatu, ukuran, keperluan, pcs, berat, rata - rata
                let urut =
                    "<td>" + nob +
                    "</td>"
                let ID =
                    "<td><span class='badge bg-dark' style='font-size:14px;'>" + value.IDM +
                    "</span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center workOrderbatu' id='workOrderbatu_" +
                    nob + "' readonly value='" + value.IDM + "'></td>"
                let SW =
                    "<td><span class='badge bg-primary' style='font-size:14px;'>" + value.SW +
                    "</span><br>" + value.Description +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center noSPKbatu' id='noSPKbatu_" +
                    nob + "' readonly value=" + value.SW + "></td>"
                let size =
                    "<td>" + value.Size + "</td>"
                let Keperluan =
                    "<td>" + value.Purpose + "</td>"
                let pcs =
                    "<td>" + value.Qty + "</td>"
                let Berat =
                    "<td >" + value.Weight + "</td>"
                let ratarata =
                    "<td>" + value.Avg.toFixed(4) + "</td>"
                let trEnd = "</tr>"
                let finalItem = ""
                let rowitem = finalItem.concat(trStart, urut, ID, SW, size, Keperluan, pcs, Berat,
                    ratarata, trEnd)
                $("#tabel2 > tbody").append(rowitem);
                nob += 1;
            });

            // 
            // Disable input
            $("#tanggal").prop('disabled', true)
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#NomorPiring").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)
            $("#Catatan").prop('disabled', true)

            // Disable button "Baru and Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            $('.variation').prop('disabled', true)
            $('.itemQty').prop('disabled', true)

            $('#tambahkurang').prop('hidden', true)
            $('.add').prop('hidden', true)
            $('.remove').prop('hidden', true)


            var infoposting = $('#gips').text();
            console.log(infoposting)
            if (infoposting == "") {
                $("#btn_edit").prop('disabled', false)
            } else {
                $("#btn_edit").prop('disabled', true)
            }
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

function UbahNTHKO() {

    // alert('tes');
    let idWaxInjectOrder = $('#idWaxInjectOrder').val()
    // Get idNTHKO
    let idWaxTree = $('#idWaxTree').val()
    // Get idEmployee
    let idEmployee = $('#idEmployee').val()
    // get tanggal
    let tanggal = $('#tanggal').val()
    // get stick pohon
    let stickpohon = $('#stickpohon').val()
    let idstickpohon = $('#stickid').val()
    // get piring
    let piring = $('#IdPiring').text()
    // get beratpiring
    let beratpiring = $('#BeratPiring').val()
    // get total Qty
    let totalQty = $('#totalQTY').val()
    // get beratPohonTotal
    let beratPohonTotal = $('#beratPohonTotal').val()
    // get beratBatu
    let beratBatu = $('#beratBatu').val()
    //jumlah batu
    let banyakBatu = $('#totalBatu').val()
    // get resin 
    let beratResin = $('#beratResin').val()
    // get kadar
    let idKadar = $('#idKadar').text()
    let Kadar = $('#Kadar').val()
    // GET CATATAN
    let Catatan = $('#Catatan').val()
    // set purpose
    let purpose = $('#purpose').val()
    // items
    let workOrders = $('.workOrder')
    let noSPKs = $('.noSPK')
    let idProducts = $('.idProduct')
    let products = $('.product')
    let kadars = $('.kadar_')
    let itemQtys = $('.itemQty')
    let WorkOrderOrds = $('.WorkOrderOrd')
    let ProductVars = $('.ProductVar')
    // let NoteVars = $('.NoteVar')
    let variations = $('.variation')
    // Check idWaxTree
    console.log(' wxinject: ' + idWaxInjectOrder + 'waxtree' + idWaxTree + ' id op: ' + idEmployee + ' tanggal: ' +
        tanggal + ' stick: ' +
        stickpohon + ' idstick: ' + idstickpohon + ' piring: ' +
        piring + ' beratpiring:  ' +
        beratpiring + ' totalQty: ' +
        totalQty + ' beratpohon: ' +
        beratPohonTotal + ' beratbatu: ' +
        beratBatu + ' beratresin: ' +
        beratResin + ' idkadar: ' +
        idKadar + ' kadar: ' +
        Kadar + ' catatan: ' +
        Catatan + ' ' +
        workOrders + ' ' + idProducts + ' ' + kadars + ' ' + itemQtys);

    if (workOrders.length === 0 || idProducts.length === 0 || kadars.length === 0 || itemQtys.length === 0 ||
        WorkOrderOrds
        .length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
        })
        return;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < workOrders.length; index++) {
        var workOrder = $(workOrders[index]).val()
        var noSPK = $(noSPKs[index]).val()
        var idProduct = $(idProducts[index]).val()
        var product = $(products[index]).val()
        var kadar = $(kadars[index]).val()
        var itemQty = $(itemQtys[index]).val()
        // var remark = $(remarks[index]).val()
        var WorkOrderOrd = $(WorkOrderOrds[index]).val()
        var ProductVar = $(ProductVars[index]).val()
        var variation1 = $(variations[index]).val()
        let dataitems = {
            workOrder: workOrder,
            noSPK: noSPK,
            idProduct: idProduct,
            kadar: kadar,
            product: product,
            itemQty: itemQty,
            // remark: remark,
            WorkOrderOrd: WorkOrderOrd,
            ProductVar: ProductVar,
            variation1: variation1
        }
        items.push(dataitems)
    }
    // alert(items);

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        idWaxInjectOrder: idWaxInjectOrder,
        idWaxTree: idWaxTree,
        idEmployee: idEmployee,
        tanggal: tanggal,
        stickpohon: stickpohon,
        idstickpohon: idstickpohon,
        piring: piring,
        beratpiring: beratpiring,
        totalQty: totalQty,
        beratPohonTotal: beratPohonTotal,
        beratBatu: beratBatu,
        banyakBatu: banyakBatu,
        beratResin: beratResin,
        idKadar: idKadar,
        Catatan: Catatan,
        purpose: purpose,
        items: items
    }
    // Setup CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Hit Backend
    // alert(data);
    $.ajax({
        type: "PUT",
        url: "/Produksi/Lilin/NTHKOInjectLilin/Update",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // alert('tes6');
            Swal.fire({
                icon: 'success',
                title: 'Terupdate!',
                text: "Data Berhasil Terupdate.",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: false
            });
            $('.itemQty').prop('disabled', true)
            $('.variation').prop('disabled', true)
            // Set action to update
            $('#action').val('update')

            // Enable button "Baru, Ubah and Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)

            // Disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)

            // Disable input
            $("#tanggal").prop('disabled', true)
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#NomorPiring").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#BeratPiring").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)
            $("#Catatan").prop('disabled', true)
            $('#BeratPiring').prop('disabled', true)
            $('#beratResin').prop('disabled', true)

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
    let idWaxTree = $('#idWaxTree').val()
    if (idWaxTree == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. WaxTree Belum Terpilih.",
        })
        return
    }
    window.open("/Produksi/Lilin/NTHKOInjectLilin/cetak?idWaxTree=" + idWaxTree, '_blank');
}
</script>
@endsection
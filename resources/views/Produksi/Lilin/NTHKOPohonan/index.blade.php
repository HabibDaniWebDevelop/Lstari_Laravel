<?php $title = 'NTHKO Pohon Direct Casting'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">NTHKO Pohon Direct Casting </li>
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

            @include('Produksi.Lilin.NTHKOPohonan.data')

        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Timbangan Script --}}
@include('layouts.backend-Theme-3.timbangan')
{{-- This Page Script --}}
<script>
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Script Timbanganku
function kliktimbang2(id) {
    if (event.keyCode === 13) {
        sendSerialLine();
        document.getElementById("selscale").value = id;

        sleep(800).then(() => {
            // Get value of beratPohonTotal
            let beratPohonTotal = $('#beratPohonTotal').val()
            // Get value of beratBatu
            let beratBatu = $('#beratBatu').val()
            // Get value of berat Pohon
            let beratPohon = $('#beratPohon').val()
            // calculate berat resin
            let beratResin = beratPohonTotal - beratBatu - beratPohon
            // Format Float just 2 digit after coma
            beratResin = beratResin.toFixed(2)
            // set beratResin
            $('#beratResin').val(beratResin)
        })
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

function KlikBaru() {
    let idWaxTree = $('#idWaxTree').val()
    if (idWaxTree != "") {
        // If idWaxTree have value. It will reload th page
        window.location.reload()
        return;
    }
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $("#tanggal").prop('disabled', false)
    $("#idWaxInjectOrder").prop('disabled', false)
    $("#idEmployee").prop('disabled', false)
    $("#nomorPohon").prop('disabled', false)
    $("#beratPohonTotal").prop('disabled', false)
    $("#beratBatu").prop('disabled', false)
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
        url: "/Produksi/Lilin/NTHKOPohonDirectCasting/Items/" + waxinjectorderid,
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
            // Set Pohon
            $('#idPohon').text(data.data.idPohon)
            $('#nomorPohon').val(data.data.nomorPohon)
            $('#beratPohon').val(data.data.beratPohon)
            // Set Berat Batu
            $('#beratBatu').val(data.data.beratBatu)
            $('#catatanHeader').val(data.data.Remarks)

            // Set item table
            let no = 1
            data.data.item.forEach(function(value, i) {
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center workOrder' id='workOrder_" +
                    no + "' readonly value=" + value.WorkOrder + "></td>"
                let SW =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center noSPK' id='noSPK_" +
                    no + "' readonly value=" + value.SW + "></td>"
                let idBarang =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center idProduct' id='idProduct_" +
                    no + "' readonly value=" + value.Product + "></td>"
                let Barang =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center product' id='product_" +
                    no + "' readonly value=" + value.Barang + "></td>"
                let Kadar =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center kadar_' id='kadar_" +
                    no + "' readonly value=" + kadar_val + "></td>"
                let QTY =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
                    no + "' readonly value=" + value.Qty + "></td>"
                let Remarks = "<td>" + remarks_val + "</td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let finalItem = ""
                let rowitem = finalItem.concat("<tr>", urut, WorkOrder, SW, idBarang, Barang, Kadar,
                    QTY, Remarks, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
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

function SearchPohonan() {
    let nomorPohon = $('#nomorPohon').val();
    if (nomorPohon == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. Waxorder ID Cannot be null.",
        })
        return
    }

    let data = {
        keyword: nomorPohon
    }
    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/NTHKOPohonDirectCasting/Pohon",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // Set nomorPohon
            $('#nomorPohon').val(data.data.SW)
            // Set idPohon
            $('#idPohon').text(data.data.ID)
            // Set beratPohon
            $('#beratPohon').val(data.data.Weight)
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
    let beratPohon = $('#beratPohon').val()
    // calculate berat resin
    let beratResin = beratPohonTotal - beratBatu - beratPohon
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
    // idWaxInjectOrder
    let idWaxInjectOrder = $('#idWaxInjectOrder').val()
    // Get idEmployee
    let idEmployee = $('#idEmployee').val()
    // get beratPohonTotal
    let beratPohonTotal = $('#beratPohonTotal').val()
    // get beratBatu
    let beratBatu = $('#beratBatu').val()

    // items
    let workOrders = $('.workOrder')
    let noSPKs = $('.noSPK')
    let idProducts = $('.idProduct')
    let products = $('.product')
    let itemQtys = $('.itemQty')

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
    if (workOrders.length === 0 || noSPKs.length === 0 || idProducts.length === 0 || products.length === 0 || itemQtys
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
                text: "There still empty WorkOrder field. Please Fill it.",
            })
            cekWorkOrder = true
            return false;
        }
    })
    if (cekWorkOrder == true) {
        return false;
    }

    //!  ------------------------    Check Items noSPK if have blank value     ------------------------ !!
    let ceknoSPK = false
    noSPKs.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty noSPK field. Please Fill it.",
            })
            ceknoSPK = true
            return false;
        }
    })
    if (ceknoSPK == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let CekidProduct = false
    idProducts.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty idProduct field. Please Fill it.",
            })
            CekidProduct = true
            return false;
        }
    })
    if (CekidProduct == true) {
        return false;
    }

    //!  ------------------------    Check Items Product if have blank value     ------------------------ !!
    let cekProduct = false
    products.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty Product field. Please Fill it.",
            })
            cekProduct = true
            return false;
        }
    })
    if (cekProduct == true) {
        return false;
    }

    //!  ------------------------    Check Items itemQty if have blank value     ------------------------ !!
    let cekItemQty = false
    itemQtys.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty itemQty field. Please Fill it.",
            })
            cekItemQty = true
            return false;
        }
    })
    if (cekItemQty == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < workOrders.length; index++) {
        var workOrder = $(workOrders[index]).val()
        var noSPK = $(noSPKs[index]).val()
        var idProduct = $(idProducts[index]).val()
        var product = $(products[index]).val()
        var itemQty = $(itemQtys[index]).val()
        let dataitems = {
            workOrder: workOrder,
            noSPK: noSPK,
            idProduct: idProduct,
            product: product,
            itemQty: itemQty
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        idWaxInjectOrder: idWaxInjectOrder,
        idEmployee: idEmployee,
        beratPohonTotal: beratPohonTotal,
        beratBatu: beratBatu,
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
        url: "/Produksi/Lilin/NTHKOPohonDirectCasting",
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
            $("#idWaxInjectOrder").prop('disabled', true)
            $("#idEmployee").prop('disabled', true)
            $("#nomorPohon").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)

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

    // Disable input
    $("#tanggal").prop('disabled', true)
    $("#idWaxInjectOrder").prop('disabled', true)
    $("#idEmployee").prop('disabled', false)
    $("#nomorPohon").prop('disabled', true)
    $("#beratPohonTotal").prop('disabled', true)
    $("#beratBatu").prop('disabled', true)
}

function UbahNTHKO() {
    // Get idNTHKO
    let idWaxTree = $('#idWaxTree').val()
    // Get idEmployee
    let idEmployee = $('#idEmployee').val()

    // Check idWaxTree
    if (idWaxTree == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "WaxTree Belum Terpilih",
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

    let data = {
        idWaxTree: idWaxTree,
        idEmployee: idEmployee
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
        url: "/Produksi/Lilin/NTHKOPohonDirectCasting",
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
            $("#idWaxInjectOrder").prop('disabled', true)
            $("#idEmployee").prop('disabled', true)
            $("#nomorPohon").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)

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
        url: "/Produksi/Lilin/NTHKOPohonDirectCasting/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1  tbody").empty()
            // Set Hidden idWaxTree
            $('#idWaxTree').val(data.data.ID)
            // Set WaxInjectOrderID
            $('#idWaxInjectOrder').val(data.data.WaxOrder)
            // Set Employee
            $('#idEmployee').val(data.data.Employee).change();
            // Set Tanggal 
            $('#tanggal').val(data.data.TransDate)
            // Set idPohon
            $('#idPohon').text(data.data.idPohon)
            // Set nomorPohon
            $('#nomorPohon').val(data.data.namaPohon)
            // Set Berat Pohon
            $('#beratPohon').val(data.data.BeratPohon)
            // Set TotalQTY
            $('#totalQTY').val(data.data.Qty)
            // Set Berat Pohon Total
            $('#beratPohonTotal').val(data.data.WeightWax)
            // Set Berat Batu
            $('#beratBatu').val(data.data.WeightStone)
            // Set beratResin
            $('#beratResin').val(data.data.Weight)
            // Set idKadar 
            $('#idKadar').text(data.data.idKadar)
            $('#kadar').val(data.data.Kadar)
            // Set Remarks
            $('#catatanHeader').val(data.data.Remarks)

            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let kadar_val = value.Kadar == null ? '' : value.Kadar
                let remarks_val = value.Remarks == null ? '' : value.Remarks
                let urut =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value=" + no + "></td>"
                let WorkOrder =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center workOrder' id='workOrder_" +
                    no + "' readonly value=" + value.WorkOrder + "></td>"
                let SW =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center noSPK' id='noSPK_" +
                    no + "' readonly value=" + value.SW + "></td>"
                let idBarang =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center idProduct' id='idProduct_" +
                    no + "' readonly value=" + value.Product + "></td>"
                let Barang =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center product' id='product_" +
                    no + "' readonly value=" + value.Barang + "></td>"
                let Kadar =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center kadar_' id='kadar_" +
                    no + "' readonly value=" + kadar_val + "></td>"
                let QTY =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
                    no + "' readonly value=" + value.Qty + "></td>"
                let Remarks = "<td>" + remarks_val + "</td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let finalItem = ""
                let rowitem = finalItem.concat("<tr>", urut, WorkOrder, SW, idBarang, Barang, Kadar,
                    QTY, Remarks, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            // Disable input
            $("#tanggal").prop('disabled', true)
            $("#idWaxInjectOrder").prop('disabled', true)
            $("#idEmployee").prop('disabled', true)
            $("#nomorPohon").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)

            // Disable button "Baru and Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)

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
    window.open("/Produksi/Lilin/NTHKOPohonDirectCasting/cetak?idWaxTree=" + idWaxTree, '_blank');
}
</script>
@endsection
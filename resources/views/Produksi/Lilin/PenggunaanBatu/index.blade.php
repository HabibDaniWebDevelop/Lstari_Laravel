<?php $title = 'Lilin Penggunaan Batu'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Lilin Penggunaan Batu</li>
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

            @include('Produksi.Lilin.PenggunaanBatu.data')

        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Timbangan Script --}}
@include('layouts.backend-Theme-3.timbangan')
{{-- This Page Script --}}
<script src="{!! asset('assets/almas/sum().js') !!}"></script>
<script>
// Script Timbanganku
async function kliktimbang2(id) {
    if (event.keyCode === 13) {
        await sendSerialLine();
        document.getElementById("selscale").value = id;
        // Get value of beratPohonTotal
        let beratPohonTotal = $('#beratPohonTotal').val()
        // Get value of beratBatu
        let beratBatu = $('#TotalBerat').val()
        // Get value of berat Pohon
        let beratPohon = $('#beratPohon').val()
        // calculate berat resin
        let beratResin = beratPohonTotal - beratBatu - beratPohon
        // Format Float just 2 digit after coma
        beratResin = beratResin.toFixed(2)
        // set beratResin
        $('#beratResin').val(beratResin)
    }
}


function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#idEmployee').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/PenggunaanBatu/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
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
    let idWaxstoneusage = $('#idWaxstoneusage').val()

    // If idWaxstoneusage have value. It will reload th page

    $("#Keperluan").val('Tambah').change()
    $('#idWaxstoneusage').val('')
    $('#postingstatus').val('')
    $('#idWaxInjectOrder').val('')
    $('#action').val('simpan')
    $('#idEmployee').val('')
    $('#NamaOperator').val('')
    $('#TotalBerat').val('')
    $("#idWaxInjectOrder").focus();


    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_edit").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $("#idWaxInjectOrder").prop('readonly', false)
    $("#idWaxInjectOrder").prop('disabled', false)
    $("#idEmployee").prop('disabled', false)
    $("#tanggal").prop('disabled', false)
    $("#TotalBerat").prop('disabled', false)
    $("#TotalJumlah").prop('disabled', false)
    $("#Keperluan").prop('disabled', false)
    $("#Catatan").prop('disabled', false)
    $("#idopera").prop('disabled', false)
    // Disabled button posting
    $("#Posting1").prop('disabled', true)
    $("#infoposting").text('')

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
        url: "/Produksi/Lilin/PenggunaanBatu/Items/" + waxinjectorderid,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody").empty()
            // Set Total jumlah Batu
            $('#TotalJumlah').val(data.data.TotalAll)
            // alert(data.data.TotalAll);

            // Set item table
            let no = 1
            data.data.item.forEach(function(value, i) {

                let remarks_val = value.Remarks == null ? '' : value.Remarks
                let start = "<tr id='tr" + no + "'>"
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let WorkOrder =
                    "<td> <span class='badge bg-dark' style='font-size:14px;'>" + value.WorkOrder +
                    "</span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center WorkOrder' id='WorkOrder_" +
                    no + "' readonly value='" + value.IDWorkOrder + "'></td>"
                let idBarang =
                    "<td hidden><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IdProduct' id='IdProduct_" +
                    no + "' readonly value='" + value.IDProduct + "'></td>"
                let Barang =
                    "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm w-10 fs-6 text-center bg-primary product' id='product_" +
                    no + "' onkeyup='getSWBatu(this.value," +
                    no + ")' value='" + value.Stone +
                    "'><span class='badge text-dark bg-light' id='DescriptionProd" + no +
                    "'>" + value.Description +
                    "</span></td>"
                let QTY =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
                    no + "' onkeyup='jumlahqty(this.value)' value='" + value.Qty + "'></td>"
                let Berat =
                    "<td>" + "<div class='input-group' width='70%'>" +
                    "<input type='text' class='form-control form-control-sm fs-6 text-center beratbatu' id='beratbatu_" +
                    no +
                    "' onchange='jumlahberat(this.value)' value='0'>" +
                    "<button type='button' class='btn btn-info btn-sm' onclick='kliktimbang(\"beratbatu_" +
                    no + "\")'><i class='fa fa-balance-scale'></i></button>" + "</td>"
                let Remarks =
                    "<td> <input type='text' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
                    no + "' onchange='refresh_sum_weight(" +
                    no + ")' value='" + remarks_val + "'></td></td>"
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
                    value.IDWorkOrder + "," +
                    value.IDProduct + "," +
                    no + ",\"" + value.WorkOrder + "\") ' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, WorkOrder, idBarang, Barang,
                    QTY, Berat, Remarks, Action, "</tr>")

                $("#tabel1 > tbody").append(rowitem);
                no += 1;

                // let fotter = "<tr><td><b>" + sum_totalberat + "</b></td></tr>"
                // $("#tabel1 > tfoot").append(hitungberat);
                $("#idEmployee").focus();
            });
            jumlahberat()
            jumlahqty()
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
            })
            // Set idWaxInjectOrder to blank
            // $("#idWaxInjectOrder").val("")
            return;
        }
    })
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
    $('#TotalJumlah').val(total);

}

function jumlahberat(berat) {
    console.log(berat);
    var Qtys = $('#tabel1').find('.beratbatu')

    var total = 0;
    for (let i = 0; i < Qtys.length; i++) {
        var aa = parseFloat($(Qtys[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#TotalBerat').val(total);

}

function add(idworkorder, idstone, id, workorder) {
    console.log(idworkorder + ',' + idstone + ',' + id)
    idtambah = id + 1;
    idwork = idworkorder;
    idston = idstone;
    SWworkorder = workorder;


    $.get('/Produksi/Lilin/PenggunaanBatu/cariStone/' + idston, function(data) {
        $('#product_' + idtambah).val(data.swstone);
    });

    let no = $('#tabel1 tr').length;
    let start = "<tr id='tr" + no + "'>"
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value='" + no + "'></td>"
    let WorkOrder =
        "<td><span class='badge bg-dark' id='work_" + no + "' style='font-size:14px;'>" +
        workorder +
        "</span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center WorkOrder' id='WorkOrder_" +
        no + "' readonly value='" +
        idwork + "'></td>"
    let idBarang =
        "<td hidden><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IdProduct' id='IdProduct_" +
        no + "' readonly value='" + idston + "'></td>"
    let Barang =
        "<td><input type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm w-10 fs-6 text-center bg-primary product' id='product_" +
        no + "' onkeyup='getSWBatu(this.value," +
        no + ")' value=''><span class='badge text-dark bg-light' id='DescriptionProd" + no +
        "'>" +
        "</span></td>"
    let QTY =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
        no + "' onkeyup='jumlahqty(this.value)' value='0'></td>"
    let Berat =
        "<td>" + "<div class='input-group' width='70%'>" +
        "<input type='text' class='form-control form-control-sm fs-6 text-center beratbatu' id='beratbatu_" +
        no +
        "' onchange='jumlahberat(this.value)' value='0'>" +
        "<button type='button' class='btn btn-info btn-sm' onclick='kliktimbang(\"beratbatu_" +
        no + "\")'><i class='fa fa-balance-scale'></i></button>" + "</td>"
    let Remarks =
        "<td> <input type='text' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
        no + "' value=''></td></td>"
    let Action =
        "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
        idwork + "," +
        idston + "," +
        no + ",\"" + SWworkorder + "\")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
    let finalItem = ""
    let rowitem = finalItem.concat(start, urut, WorkOrder, idBarang, Barang,
        QTY, Berat, Remarks, Action, "</tr>")

    $("#tabel1 > tbody").append(rowitem);
    $("#product_" + no).focus();
    jumlahberat()
    jumlahqty()
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
    $("#tabel1").find("#tr" + id).remove()
    jumlahberat()
    jumlahqty()
}

function getSWBatu(value, id) {
    console.log(value, id);
    var value = value;
    var id = id;

    data = {
        value: value,
        id: id
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/Produksi/Lilin/PenggunaanBatu/cariSW',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {
            console.log(data['Stone']);

            if (data.rowcount > 0) {
                $('#IdProduct_' + id).val(data.IDProduct)
                $('#DescriptionProd' + id).text(data.Description)
                $('#Product' + id).val(data.Stone)

            } else {
                $('#IdProduct_' + id).val('')
                $('#DescriptionProd' + id).text('')
                $('#Product' + id).val('')
            }
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
    });

}

async function kliktimbang1(id) {
    if (event.keyCode === 13) {
        await sendSerialLine();
        document.getElementById("selscale").value = id;
        // Format Float just 2 digit after coma
        beratBatu = beratBatu.toFixed(2)
        // set beratResin
        $('.beratbatu').val(beratbatu)
    }
}

function KlikSimpan() {
    // Get Action
    let action = $('#action').val()
    // Disable button "Baru and Cetak"

    if (action == 'simpan') {
        SimpanSPKO()
    } else {
        Ubah()
    }
}

function SimpanSPKO() {
    // idWaxInjectOrder
    let idWaxInjectOrder = $('#idWaxInjectOrder').val()
    // Get idEmployee
    let idEmployee = $('#idEmployee').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // Get TotalBerat
    let TotalBerat = $('#TotalBerat').val()
    // Get Qty Total
    let TotalJumlah = $('#TotalJumlah').val()
    // Get Keperluan
    let Keperluan = $('#Keperluan').val()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let WorkOrders = $('.WorkOrder')
    let IdProducts = $('.IdProduct')
    let products = $('.product')
    let itemQtys = $('.itemQty')
    let beratbatus = $('.beratbatu')
    let Notes = $('.Note')

    // Check idWaxInjectOrder
    if (idWaxInjectOrder == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "idWaxInjectOrder tidak boleh kosong",
        })
        return;
    }

    // Check idEmployee
    if (idEmployee == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "idEmployee tidak boleh kosong",
        })
        return;
    }

    //Check beratBatu
    // if (TotalBerat == "") {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: "beratBatu tidak boleh kosong",
    //     })
    //     return;
    // }

    // Check beratPohonTotal
    if (TotalJumlah == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Total Jumlah tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (WorkOrders.length === 0 || IdProducts.length === 0 || products.length === 0 || itemQtys
        .length === 0 || beratbatus.lenghts === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekWorkOrder = false
    WorkOrders.map(function() {
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

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let CekIdProduct = false
    IdProducts.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty idProduct field. Please Fill it.",
            })
            CekIdProduct = true
            return false;
        }
    })
    if (CekIdProduct == true) {
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

    //!  ------------------------    Check Items BeratBatu if have blank value     ------------------------ !!
    let cekBeratBatu = false
    beratbatus.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty BeratBatu field. Please Fill it.",
            })
            cekBeratBatu = true
            return false;
        }
    })
    if (cekBeratBatu == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < WorkOrders.length; index++) {
        var WorkOrder = $(WorkOrders[index]).val()
        var IdProduct = $(IdProducts[index]).val()
        var product = $(products[index]).val()
        var itemQty = $(itemQtys[index]).val()
        var beratbatu = $(beratbatus[index]).val()
        var Note = $(Notes[index]).val()
        let dataitems = {
            WorkOrder: WorkOrder,
            beratbatu: beratbatu,
            IdProduct: IdProduct,
            product: product,
            itemQty: itemQty,
            Note: Note
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        idWaxInjectOrder: idWaxInjectOrder,
        idEmployee: idEmployee,
        date: date,
        TotalBerat: TotalBerat,
        TotalJumlah: TotalJumlah,
        Keperluan: Keperluan,
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
        url: "/Produksi/Lilin/PenggunaanBatu/Simpan",
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

            // Set action to update
            $('#action').val('update')
            // set idwaxstoneusage

            // disabel button "Baru, Ubah and except Cetak"
            $("#btn_baru").prop('disabled', true)
            $("#btn_edit").prop('disabled', true)
            $("#btn_cetak").prop('disabled', false)
            $("#conscale").prop('disabled', true)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            // disabled input
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#tanggal").prop('disabled', true)
            $("#TotalBerat").prop('disabled', true)
            $("#TotalJumlah").prop('disabled', true)
            $("#Keperluan").prop('disabled', true)
            $("#Catatan").prop('disabled', true)
            // Disabled button posting
            $("#Posting1").prop('disabled', false)

            $('#cari').val(data.data.IDtes)
            $('#TotalBerat').val(data.data.berattotaltes)
            $('#TotalJumlah').val(data.data.jumlahtotaltes)
            $('#idWaxstoneusage').val(data.data.IDtes)

            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 700,
                showCancelButton: false,
                showConfirmButton: false
            });

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



function Klik_Posting1() {
    $("#Posting1").prop('disabled', true)
    let waxstoneusage = $('#idWaxstoneusage').val();
    $("#btn_cetak").prop('disabled', false)
    if (waxstoneusage == null || waxstoneusage == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "SPKO Belum Terpilih",
        })
        return;
    }
    let data = {
        waxstoneusage: waxstoneusage
    }
    // alert(waxstoneusage);
    // Setup CSRF TOKEN
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/Produksi/Lilin/PenggunaanBatu/posting",
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
    $("#tanggal").prop('readonly', false)
    $("#idWaxInjectOrder").prop('readonly', true)
    $("#idEmployee").prop('disabled', false)
    $("#Keperluan").prop('disabled', false)
    $("#Catatan").prop('disabled', false)
    $("#beratBatu").prop('disabled', true)

    $(".product").prop('readonly', false)
    $(".itemQty").prop('readonly', false)
    $(".beratbatu").prop('readonly', false)
    $(".tomboltimbang").prop('disabled', false)
    $(".Note").prop('readonly', false)
    $(".plus").prop('hidden', false)
    $(".min").prop('hidden', false)
}

function Ubah() {
    // get idwaxtoneusage
    var idpenggunaanbatu = $('#cari').val()
    // idWaxInjectOrder
    let idWaxInjectOrder = $('#idWaxInjectOrder').val()
    // Get idEmployee
    let idEmployee = $('#idEmployee').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // Get TotalBerat
    let TotalBerat = $('#TotalBerat').val()
    // Get Qty Total
    let TotalJumlah = $('#TotalJumlah').val()
    // Get Keperluan
    let Keperluan = $('#Keperluan').val()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let WorkOrders = $('.WorkOrder')
    let IdProducts = $('.IdProduct')
    let products = $('.product')
    let itemQtys = $('.itemQty')
    let beratbatus = $('.beratbatu')
    let Notes = $('.Note')

    // Check idWaxInjectOrder
    if (idWaxInjectOrder == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "idWaxInjectOrder tidak boleh kosong",
        })
        return;
    }

    // Check idEmployee
    if (idEmployee == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "idEmployee tidak boleh kosong",
        })
        return;
    }

    //Check beratBatu
    // if (TotalBerat == "") {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: "beratBatu tidak boleh kosong",
    //     })
    //     return;
    // }

    // Check beratPohonTotal
    if (TotalJumlah == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Total Jumlah tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (WorkOrders.length === 0 || IdProducts.length === 0 || products.length === 0 || itemQtys
        .length === 0 || beratbatus.lenghts === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekWorkOrder = false
    WorkOrders.map(function() {
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

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let CekIdProduct = false
    IdProducts.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty idProduct field. Please Fill it.",
            })
            CekIdProduct = true
            return false;
        }
    })
    if (CekIdProduct == true) {
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

    //!  ------------------------    Check Items BeratBatu if have blank value     ------------------------ !!
    let cekBeratBatu = false
    beratbatus.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty BeratBatu field. Please Fill it.",
            })
            cekBeratBatu = true
            return false;
        }
    })
    if (cekBeratBatu == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < WorkOrders.length; index++) {
        var WorkOrder = $(WorkOrders[index]).val()
        var IdProduct = $(IdProducts[index]).val()
        var product = $(products[index]).val()
        var itemQty = $(itemQtys[index]).val()
        var beratbatu = $(beratbatus[index]).val()
        var Note = $(Notes[index]).val()
        let dataitems = {
            WorkOrder: WorkOrder,
            beratbatu: beratbatu,
            IdProduct: IdProduct,
            product: product,
            itemQty: itemQty,
            Note: Note
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        idpenggunaanbatu: idpenggunaanbatu,
        idWaxInjectOrder: idWaxInjectOrder,
        idEmployee: idEmployee,
        date: date,
        TotalBerat: TotalBerat,
        TotalJumlah: TotalJumlah,
        Keperluan: Keperluan,
        Catatan: Catatan,
        items: items
    }
    // Setup CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //hit backend
    $.ajax({
        type: "PUT",
        url: "/Produksi/Lilin/PenggunaanBatu/Update",
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
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#nomorPohon").prop('disabled', true)
            $("#beratPohonTotal").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)

            Searchspkkebutuhanbatu()
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

function Searchspkkebutuhanbatu() {
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
        url: "/Produksi/Lilin/PenggunaanBatu/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1  tbody").empty()
            // Set WaxInjectOrderID
            $('#idWaxInjectOrder').val(data.data.WaxOrder)
            // Set Employee
            $('#idEmployee').val(data.data.idEmployee)
            $('#NamaOperator').val(data.data.Employee).change();
            // Set Tanggal 
            $('#tanggal').val(data.data.TransDate)
            // Set total batu dan total berat
            $('#TotalJumlah').val(data.data.jumlahtotal)
            $('#TotalBerat').val(data.data.berattotal.toFixed(3))
            //set keperluan
            $('#Keperluan').val(data.data.Purpose)
            //set catatan
            $('#infoposting').text(data.data.Posting)
            $('#Catatan').val(data.data.Remarks)
            // Set user admin batu
            $('#UserNameAdminBatu').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatanSPKOBatu').text(data.data.EntryDate)
            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start = "<tr id='tr" + no + "'>"
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let WorkOrder =
                    "<td> <span class='badge bg-dark' style='font-size:14px;'>" + value
                    .SWWorkOrder +
                    "</span> <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center WorkOrder' id='WorkOrder_" +
                    no + "' readonly value='" + value.IDWorkOrder + "'></td>"
                let idBarang =
                    "<td hidden><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IdProduct' id='IdProduct_" +
                    no + "' readonly value='" + value.IdProduct + "'></td>"
                let Barang =
                    "<td><input readonly type='text' style='color: #FFF; font-weight: bold;' class='form-control form-control-sm w-10 fs-6 text-center bg-primary product' id='product_" +
                    no + "' onkeyup='getSWBatu(this.value," +
                    no + ")' value='" + value.Stone +
                    "'><span class='badge text-dark bg-light' id='DescriptionProd" + no +
                    "'>" + value.Description +
                    "</span></td>"
                let QTY =
                    "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center itemQty' id='itemQty_" +
                    no + "' onkeyup='jumlahqty(this.value)' value='" + value.Qty + "'></td>"
                let Berat =
                    "<td>" + "<div class='input-group' width='70%'>" +
                    "<input readonly type='text' class='form-control form-control-sm fs-6 text-center beratbatu' id='beratbatu_" +
                    no +
                    "' onchange='jumlahberat(this.value)' value='" + value.Weight + "'>" +
                    "<button type='button' disabled class='btn btn-info btn-sm tomboltimbang' onclick='kliktimbang(\"beratbatu_" +
                    no + "\")'><i class='fa fa-balance-scale'></i></button>" + "</td>"
                let Remarks =
                    "<td> <input readonly type='text' class='form-control form-control-sm fs-6 w-100 text-center Note' id='remarks_" +
                    no + "' onchange='refresh_sum_weight(" +
                    no + ")' value='" + value.Note + "'></td></td>"
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm plus' type='button' onclick='add(" +
                    value.IDWorkOrder + "," +
                    value.IdProduct + "," +
                    no + ",\"" + value.SWWorkOrder + "\") ' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm min' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                // let Remarks = "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center catatan' id='catatan_"+no+"' readonly value="+remarks_val+"></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, WorkOrder, idBarang, Barang,
                    QTY, Berat, Remarks, Action, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            // Disable input
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#nomorPohon").prop('disabled', true)

            // Disable button "Baru and Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)


            $("#tanggal").prop('readonly', true)
            $("#idWaxInjectOrder").prop('readonly', true)
            $("#idEmployee").prop('disabled', true)
            $("#Keperluan").prop('disabled', true)
            $("#Catatan").prop('disabled', true)
            $("#beratBatu").prop('disabled', true)

            $(".product").prop('readonly', true)
            $(".itemQty").prop('readonly', true)
            $(".beratbatu").prop('readonly', true)
            $(".tomboltimbang").prop('disabled', true)
            $(".Note").prop('readonly', true)

            $(".plus").prop('hidden', true)
            $(".min").prop('hidden', true)

            // $("#Posting1").prop('disabled', false)
            var infoposting = $('#infoposting').text();
            console.log(infoposting)
            if (infoposting == "") {
                $("#btn_edit").prop('disabled', false)
                $("#Posting1").prop('disabled', false)
            } else {
                $("#btn_edit").prop('disabled', true)
                $("#Posting1").prop('disabled', true)
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

function klikCetak() {
    // Get idWaxTree
    let idWaxstoneusage = $('#cari').val()
    if (idWaxstoneusage == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. isi id waxstone do pencarian.",
        })
        return
    }
    window.open("/Produksi/Lilin/PenggunaanBatu/cetak?idWaxstoneusage=" + idWaxstoneusage, '_blank');
}
</script>
@endsection
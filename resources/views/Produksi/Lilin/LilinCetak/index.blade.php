<?php $title = 'Lilin Cetak'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Lilin Cetak</li>
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

            @include('Produksi.Lilin.LilinCetak.data')

        </div>
    </div>
</div>
@endsection

@section('script')

{{-- This Page Script --}}
<script>
// Script Timbanganku

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#idEmployee').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/SPKInjectLilin/Operator/' + IdOperator, function(data) {
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
    $('#IDwax').val('')
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $("#IDSPKOInject").prop('readonly', false)
    $("#tanggal").prop('readonly', false)
    $("#Catatan").prop('disabled', false)
    $("#IdOperator").prop('disabled', false)
    $("#kelompok").prop('disabled', false)

    $("#IDSPKOInject").val('')
    $("#kelompok").val('')
    $("#Kadar").val('')
    $("#Catatan").val('')
    $("#IDSPKOInject").focus()
    $('#action').val('simpan')
}

function klikBatal() {
    window.location.reload()
}

function JumlahRusak(Rusak, no) {
    console.log(Rusak);
    var Rusaks = $('#tabel1').find('.Rusak')

    var a = parseInt($('#Rusak_' + no).val())
    var b = parseInt($('#Setor_' + no).val())
    c = a + b;
    $('#Cetak_' + no).val(c);
    $('#Inject_' + no).val(c);
    JumlahCetak()

    var total = 0;
    for (let i = 0; i < Rusaks.length; i++) {
        var aa = parseInt($(Rusaks[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#Jumlah_Rusak').text(total);
}

function JumlahSetor(Setor, no) {
    console.log(Setor);
    var no = no;
    var Setors = $('#tabel1').find('.Setor')

    var a = parseInt($('#Rusak_' + no).val())
    var b = parseInt($('#Setor_' + no).val())
    c = a + b;
    $('#Cetak_' + no).val(c);
    $('#Inject_' + no).val(c);
    JumlahCetak()

    var total = 0;
    for (let i = 0; i < Setors.length; i++) {
        var aa = parseInt($(Setors[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#Jumlah_Setor').text(total);
}

function JumlahCetak(Cetak, no) {
    console.log(Cetak);
    var Cetaks = $('#tabel1').find('.Cetak')

    var total = 0;
    for (let i = 0; i < Cetaks.length; i++) {
        var aa = parseInt($(Cetaks[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#Jumlah_Cetak').text(total);
}

function SearchWaxInjectOrder() {
    // Get IDSPKOInject from input
    let IDSPKOInject = $('#IDSPKOInject').val();
    if (IDSPKOInject == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. Waxinjectorder ID Cannot be null.",
        })
        return
    }

    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/LilinCetak/Items/" + IDSPKOInject,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody").empty()
            // Set Operator
            $('#Operator').val(data.data.Operator)
            $('#IDOperator').text(data.data.IDOperator)
            $('#idope').val(data.data.IDOperator)
            // Set Kadar
            $('#Kadar').val(data.data.Kadar)
            $('#IDKadar').text(data.data.IDKadar)
            $('#Kadar').css('background-color', data.data.HexColor)
            $('#idkad').val(data.data.IDKadar)
            $('#kelompok').val(data.data.WorkGroup)
            $('#IdOperator').val(data.data.Employee)
            isioperator();
            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start = "<tr id='tr" + no + "'>"
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let WorkOrders =
                    '<td><span class="badge bg-dark" style="font-size:14px;">' + value.WorkOrder +
                    '</span>' +
                    '<input type="hidden" name="workorder[]" class="IDWorkOrder form-control form-control-sm fs-6 w-100"id="IDWorkOrder_' +
                    no + '" value="' + value.IDWorkOrder + '"></td > '
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
                    "'>" + value.ItemProduct +
                    "</span> <br> <span class='badge text-dark bg-light' id='Description" + no +
                    "'>" + value.Description +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
                    no + "' readonly value='" + value.IDProd + "'></td>"
                let Kadar =
                    "<td><span class='badge' style='font-size:14px; background-color:" +
                    value.HexColor + "' id='KadarKar" + no +
                    "'>" + value.Kadar +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
                    no + "' readonly value='" + value.IDKadar + "'></td>"
                let IDkaret =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
                    no + ")' id='IDKaret_" +
                    no + "' value='" + value.IDKaret + "' tabindex='6'></td>"
                let Qty =
                    ' <td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="number" class="Qty form-control form-control-sm fs-6 w-20" name="Qty[]" id = "Qty' +
                    no + '" value = "' + value.Qty + '")" > ' +
                    '</td>'
                let setor =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Rusak' id='Rusak_" +
                    no +
                    "' value='0' onkeyup='JumlahRusak(this.value," + no + ")' tabindex='6'></td>" +
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Setor' id='Setor_" +
                    no + "' value='0' onkeyup='JumlahSetor(this.value," + no +
                    ")'tabindex='6'></td>" +
                    "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center Cetak' id='Cetak_" +
                    no + "' value='0' onkeyup='JumlahCetak(this.value," + no + ")'></td>" +
                    "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center Inject' id='Inject_" +
                    no + "' value='0' ></td>"
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm' type='button' tabindex='6' onclick='add(\"" +
                    value.WorkOrder + "\"," + value.IDWorkOrder + "," + no + "," + value.Qty +
                    ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' tabindex='6' class='btn btn-danger btn-sm' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, WorkOrders, Barang, Kadar, IDkaret, Qty,
                    setor,
                    Action, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            JumlahCetak();
            JumlahSetor();
            JumlahRusak();
            $("#tanggal").focus()
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

function getIDKaret(value, id) {

    var value = value;
    var id = id;

    console.log(value)
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
        type: 'GET',
        url: '/Produksi/Lilin/LilinCetak/cariID',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {
            console.log(data['Kadar']);

            if (data.rowcount > 0) {
                $('#ItemProd' + id).text(data.ItemProduct)
                $('#Description' + id).text(data.Description)
                $('#IDProduct_' + id).val(data.IDProd)
                // ubah kadar karet
                $('#KadarKar' + id).text(data.Kadar)
                $('#KadarKar' + id).css('background-color', data.HexColor)
                $('#kadar_' + id).val(data.IDKadar)
                $("#Rusak_" + id).focus();
            } else {
                $('#ItemProd' + id).text('')
                $('#Description' + id).text('')
                $('#IDProduct_' + id).val('')
                // ubah kadar karet
                $('#KadarKar' + id).text('')
                $('#KadarKar' + id).css('background-color', 'dark')
                $('#kadar_' + id).val('')

            }

        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Invalid Request",
                timer: 500,
                showCancelButton: false,
                showConfirmButton: false
            })
            return;
        }
    });
}

function add(WorkOrder, IDWorkOrder, id, Qty) {

    let no = $('#tabel1 tr').length;
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value='" + no + "'></td>"
    let WorkOrders =
        '<td><span class="badge bg-dark" style="font-size:14px;">' + WorkOrder +
        '</span>' +
        '<input type="hidden" name="workorder[]" class="IDWorkOrder form-control form-control-sm fs-6 w-100"id="IDWorkOrder_' +
        no + '" value="' + IDWorkOrder + '"></td > '
    let Barang =
        "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
        "'></span> <br> <span class='badge text-dark bg-light' id='Description" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
        no + "' readonly value=''></td>"
    let Kadar =
        "<td><span class='badge' style='font-size:14px; background-color:' id='KadarKar" + no +
        "'>" +
        "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
        no + "' readonly value=''></td>"
    let IDkaret =
        "<td><input type='number' tabindex='6' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
        no + ")' id='IDKaret_" +
        no + "' value=''></td>"
    let Qtys =
        ' <td class="m-0 p-0">' +
        '<input readonly style="text-align: center" type="number" class="Qty form-control form-control-sm fs-6 w-20" name="Qty[]" id = "Qty' +
        no + '" value = "' + Qty + '")" > ' +
        '</td>'
    let setor =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Rusak' id='Rusak_" +
        no +
        "' value='0' onkeyup='JumlahRusak(this.value," + no + ")' tabindex='6'></td>" +
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Setor' id='Setor_" +
        no + "' value='0' onkeyup='JumlahSetor(this.value," + no + ")' tabindex='6'></td>" +
        "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center Cetak' id='Cetak_" +
        no + "' value='0' onkeyup='JumlahCetak(this.value," + no + ")'></td>" +
        "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center Inject' id='Inject_" +
        no + "' value='0' ></td>"
    let Action =
        "<td align='center'><button class='btn btn-info btn-sm add' type='button' tabindex='6' onclick='add(\"" +
        WorkOrder + "\"," + IDWorkOrder + "," + no + "," + Qty + ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' tabindex='6' class='remove btn btn-danger btn-sm' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let finalItem = ""
    let rowitem = finalItem.concat("<tr>", urut, WorkOrders, Barang, Kadar, IDkaret, Qtys, setor,
        Action, "</tr>")
    $("#tabel1 > tbody").append(rowitem);
    $("#IDKaret_" + no).focus();

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

function KlikSimpan() {
    // Get Action let
    action = $('#action').val()
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_cetak").prop('disabled', true)
    // rubah value action
    if (action == 'simpan') {
        Simpan()
    } else {
        Ubah()
    }
}

function Simpan() {

    // insert wax
    // insert waxitem

    // IDSPKOInject
    let IDSPKOInject = $('#IDSPKOInject').val()
    // get kadar
    // let IDkadarspko = $('#IDKadar').text()
    let KadarSPK = $('#Kadar').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // Get Operator
    let IDOperator = $('#IdOperator').val()
    // Get Keperluan
    let kelompok = $('#kelompok').val()
    // Get jumlah rusak setor dan cetak
    let JumlahScrap = $('#Jumlah_Rusak').text()
    let JumlahCompletion = $('#Jumlah_Setor').text()
    let JumlahMold = $('#Jumlah_Cetak').text()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let IDWorkOrders = $('.IDWorkOrder')
    let IDProducts = $('.IDProduct')
    let IDKarets = $('.IDKaret')
    let Qtys = $('.Qty')
    let Molds = $('.Cetak')
    let Scraps = $('.Rusak')
    let Completions = $('.Setor')
    let Injects = $('.Inject')

    // alert(IDprods, KadarKarets, IDKarets, Locations);
    // Check idWaxInjectOrder
    if (IDSPKOInject == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "IDSPKOInject tidak boleh kosong",
        })
        return;
    }

    // Check idEmployee
    if (IDOperator == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Operator tidak boleh kosong",
        })
        return;
    }

    // Check ranggal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }
    // Check kelompok
    if (kelompok == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Kelompok tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDKarets.length === 0 || IDProducts.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "IDkaret didalam tabel ada yang kosong .",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekIDKarets = false
    IDKarets.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat id karet dalam tabel yang belum di isi.",
            })
            cekIDKarets = true
            return false;
        }
    })
    if (cekIDKarets == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let cekIDProducts = false
    IDProducts.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat IDkaret dalam tabel yang tidak memiliki Product.",
            })
            cekIDProducts = true
            return false;
        }
    })
    if (cekIDProducts == true) {
        return false;
    }


    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDKaret = $(IDKarets[index]).val()
        var IDProduct = $(IDProducts[index]).val()
        var IDWorkOrder = $(IDWorkOrders[index]).val()
        var Qty = $(Qtys[index]).val()
        var Mold = $(Molds[index]).val()
        var Scrap = $(Scraps[index]).val()
        var Completion = $(Completions[index]).val()
        var Inject = $(Injects[index]).val()
        let dataitems = {
            IDKaret: IDKaret,
            IDProduct: IDProduct,
            IDWorkOrder: IDWorkOrder,
            Qty: Qty,
            Mold: Mold,
            Scrap: Scrap,
            Completion: Completion,
            Inject: Inject
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        IDSPKOInject: IDSPKOInject,
        KadarSPK: KadarSPK,
        date: date,
        IDOperator: IDOperator,
        kelompok: kelompok,
        JumlahScrap: JumlahScrap,
        JumlahCompletion: JumlahCompletion,
        JumlahMold: JumlahMold,
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
    // Hit Backend
    $.ajax({
        type: "POST",
        url: "/Produksi/Lilin/LilinCetak/Simpan",
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

            // Set action to update
            $('#action').val('update')
            $('#IDwax').val(data.data.ID)
            // pindah function serach
            Search();

            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 500,
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

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#IdOperator').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/LilinCetak/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
    }
    $("#IdOperator").keydown(function(event) {
        if (event.keyCode === 13) {
            $("#kadar1").click();
        }
    });
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
    $("#Catatan").prop('disabled', false)
    $("#IdOperator").prop('disabled', false)
    $("#kelompok").prop('disabled', false)

    // set open edit tabel
    $('.IDWorkOrder').prop('readonly', false)
    $('.IDProduct').prop('readonly', false)
    $('.IDKaret').prop('readonly', false)
    $('.Rusak').prop('readonly', false)
    $('.Setor').prop('readonly', false)
    $('.Inject').prop('readonly', false)

    $('#tambahkurang').prop('hidden', false)
    $('.add').prop('hidden', false)
    $('.remove').prop('hidden', false)
}

function Ubah() {
    //get ID wax
    let IDwax = $('#IDwax').val()
    let IDSPKOInject = $('#IDSPKOInject').val()
    // get kadar
    // let IDkadarspko = $('#IDKadar').text()
    let KadarSPK = $('#Kadar').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // Get Operator
    let IDOperator = $('#IdOperator').val()
    // Get Keperluan
    let kelompok = $('#kelompok').val()
    // Get jumlah rusak setor dan cetak
    let JumlahScrap = $('#Jumlah_Rusak').text()
    let JumlahCompletion = $('#Jumlah_Setor').text()
    let JumlahMold = $('#Jumlah_Cetak').text()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let IDWorkOrders = $('.IDWorkOrder')
    let IDProducts = $('.IDProduct')
    let IDKarets = $('.IDKaret')
    let Qtys = $('.Qty')
    let Molds = $('.Cetak')
    let Scraps = $('.Rusak')
    let Completions = $('.Setor')
    let Injects = $('.Inject')

    // alert(IDprods, KadarKarets, IDKarets, Locations);
    // Check idWaxInjectOrder
    if (IDSPKOInject == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "IDSPKOInject tidak boleh kosong",
        })
        return;
    }

    // Check idEmployee
    if (IDOperator == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Operator tidak boleh kosong",
        })
        return;
    }

    // Check ranggal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }
    // Check kelompok
    if (kelompok == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Kelompok tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDKarets.length === 0 || IDProducts.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "IDkaret didalam tabel ada yang kosong .",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekIDKarets = false
    IDKarets.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat id karet dalam tabel yang belum di isi.",
            })
            cekIDKarets = true
            return false;
        }
    })
    if (cekIDKarets == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let cekIDProducts = false
    IDProducts.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat IDkaret dalam tabel yang tidak memiliki Product.",
            })
            cekIDProducts = true
            return false;
        }
    })
    if (cekIDProducts == true) {
        return false;
    }


    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDKaret = $(IDKarets[index]).val()
        var IDProduct = $(IDProducts[index]).val()
        var IDWorkOrder = $(IDWorkOrders[index]).val()
        var Qty = $(Qtys[index]).val()
        var Mold = $(Molds[index]).val()
        var Scrap = $(Scraps[index]).val()
        var Completion = $(Completions[index]).val()
        var Inject = $(Injects[index]).val()
        let dataitems = {
            IDKaret: IDKaret,
            IDProduct: IDProduct,
            IDWorkOrder: IDWorkOrder,
            Qty: Qty,
            Mold: Mold,
            Scrap: Scrap,
            Completion: Completion,
            Inject: Inject
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        IDwax: IDwax,
        IDSPKOInject: IDSPKOInject,
        KadarSPK: KadarSPK,
        date: date,
        IDOperator: IDOperator,
        kelompok: kelompok,
        JumlahScrap: JumlahScrap,
        JumlahCompletion: JumlahCompletion,
        JumlahMold: JumlahMold,
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
        url: "/Produksi/Lilin/LilinCetak/Update",
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
            $('#IDwax').val(data.data.ID)
            // Set action to update 
            $('#action').val('update')
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
    // disable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', true)
    $("#btn_batal").prop('disabled', true)
    // disabled input
    $("#IDSPKOInject").prop('readonly', true)
    $("#tanggal").prop('readonly', true)
    $("#Catatan").prop('disabled', true)
    $("#IdOperator").prop('disabled', true)
    $("#kelompok").prop('disabled', true)

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
        url: "/Produksi/Lilin/LilinCetak/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody ").empty()
            // Set WaxInjectOrderID 
            $('#IDwax').val(data.data.ID)
            $('#IDSPKOInject').val(data.data.spk)
            $('#Operator').val(data.data.Operator)
            $('#IDOperator').text(data.data.IDOperator)
            $('#idope').val(data.data.IDOperator)
            // Set Kadar
            $('#Kadar').val(data.data.Kadar)
            $('#IDKadar').text(data.data.IDKadar)
            $('#Kadar').css('background-color', data.data.HexColor)
            $('#idkad').val(data.data.IDKadar)

            $('#IdOperator').val(data.data.Employee)
            isioperator();

            $('#kelompok').val(data.data.WorkGroup)
            // set jumlah rusak setor dan cetak
            $('#Jumlah_Rusak').text(data.data.Rusak)
            $('#Jumlah_Setor').text(data.data.Setor)
            $('#Jumlah_Cetak').text(data.data.Cetak)
            //set catatan 
            $('#Catatan').val(data.data.Remarks)
            // Set user admin batu
            $('#UserNameAdmin').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatan').text(data.data.EntryDate)
            // Set item table let 
            no = 1
            data.data.items.forEach(function(value, i) {
                let start = "<tr id='tr" + no + "'>"
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let WorkOrders =
                    '<td><span class="badge bg-dark" style="font-size:14px;">' + value.WorkOrder +
                    '</span>' +
                    '<input type="hidden" name="workorder[]" class="IDWorkOrder form-control form-control-sm fs-6 w-100"id="IDWorkOrder_' +
                    no + '" value="' + value.IDWorkOrder + '"></td > '
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
                    "'>" + value.ItemProduct +
                    "</span> <br> <span class='badge text-dark bg-light' id='Description" + no +
                    "'>" + value.Description +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
                    no + "' readonly value='" + value.IDProd + "'></td>"
                let Kadar =
                    "<td><span class='badge' style='font-size:14px; background-color:" +
                    value.HexColor + "' id='KadarKar" + no +
                    "'>" + value.Kadar +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
                    no + "' readonly value='" + value.IDKadar + "'></td>"
                let IDkaret =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
                    no + ")' id='IDKaret_" +
                    no + "' value='" + value.IDKaret + "'></td>"
                let Qty =
                    ' <td class="m-0 p-0">' +
                    '<input readonly style="text-align: center" type="number" class="Qty form-control form-control-sm fs-6 w-20" name="Qty[]" id = "Qty' +
                    no + '" value = "' + value.Qty + '")" > ' +
                    '</td>'
                let setor =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Rusak' id='Rusak_" +
                    no +
                    "' value='" + value.Rusak + "' onkeyup='JumlahRusak(this.value," + no +
                    ")'></td>" +
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Setor' id='Setor_" +
                    no + "' value='" + value.Setor + "' onkeyup='JumlahSetor(this.value," + no +
                    ")'></td>" +
                    "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center Cetak' id='Cetak_" +
                    no + "' value='" + value.Cetak + "' onkeyup='JumlahCetak(this.value," + no +
                    ")'></td>" +
                    "<td><input type='number' readonly class='form-control form-control-sm fs-6 w-100 text-center Inject' id='Inject_" +
                    no + "' value='" + value.Inject + "' ></td>"
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm add' type='button' onclick='add(\"" +
                    value.WorkOrder + "\"," + value.IDWorkOrder + "," + no + "," + value.Qty +
                    ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm remove' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, WorkOrders, Barang, Kadar, IDkaret, Qty,
                    setor, Action,
                    "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            // show user admin dan tangal entry
            $('#show').prop('hidden', false)
            //set readonly tabel
            $('.IDWorkOrder').prop('readonly', true)
            $('.IDProduct').prop('readonly', true)
            $('.IDKaret').prop('readonly', true)
            $('.Qty').prop('readonly', true)
            $('.Cetak').prop('readonly', true)
            $('.Rusak').prop('readonly', true)
            $('.Setor').prop('readonly', true)
            $('.Inject').prop('readonly', true)

            $('#tambahkurang').prop('hidden', true)
            $('.add').prop('hidden', true)
            $('.remove').prop('hidden', true)

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
    let idRubberOut = $('#cari').val()
    $("#btn_baru").prop('disabled', false)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_batal").prop('disabled', true)
    if (idRubberOut == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. isi id waxstone do pencarian.",
        })
        return
    }
    $('#IDSPKOInject').val(idRubberOut)
    window.open("/Produksi/Lilin/LilinCetak/Cetak?idRubberOut=" + idRubberOut, '_blank');
}
</script>
@endsection
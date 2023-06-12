<?php $title = 'Lilin Bersih'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Lilin Bersih</li>
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

            @include('Produksi.Lilin.LilinBersih.data')

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
        $.get('/Produksi/Lilin/LilinBersih/Operator/' + IdOperator, function(data) {
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
    $('#IDWaxClean').val('')
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $("#IDSPKOInject").prop('readonly', false)
    $("#IDSPKOInject").prop('disabled', false)
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

function JumlahKomponen(Komponen, no) {
    // console.log(Komponen);
    var Komponens = $('#tabel1').find('.QtyKomponen')

    var total = 0;
    for (let i = 0; i < Komponens.length; i++) {
        var aa = parseInt($(Komponens[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#Jumlah_Komponen').text(total);
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
        url: "/Produksi/Lilin/LilinBersih/Items/" + IDSPKOInject,
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
            $("#IdOperator").focus()
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
                let productJadi =
                    "<td><span class='badge text-dark bg-light ProductJadi' style='font-size:14px;' id='ProductJadi_" +
                    no +
                    "'>" + value.Productjadi +
                    "</span></td>"
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" +
                    no +
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
                let QtyKomponen =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center QtyKomponen' id='QtyKomponen_" +
                    no + "' value='" + value.Qty + "' onkeyup='JumlahKomponen(this.value," + no +
                    ")'></td>"
                let Keterangan =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center Keterangan' id='Keterangan_" +
                    no + "' value=''></td>"
                let Action =
                    "<td align='center'><button disabled class='btn btn-info btn-sm add' type='button' onclick='add(\"" +
                    value.WorkOrder + "\"," + value.IDWorkOrder + "," + no + "," + value.Qty +
                    ",\"" + value.Productjadi + "\")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' disabled class='btn btn-danger btn-sm remove' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, WorkOrders, Barang, Kadar,
                    QtyKomponen, Keterangan, Action, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            JumlahKomponen();
            $("#tanggal").focus()
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
                timer: 500,
                showCancelButton: false,
                showConfirmButton: false
            })
            // Set idWaxInjectOrder to blank
            $("#idWaxInjectOrder").val("")
            return;
        }
    })
}



function addloc(WorkOrder, IDWorkOrder, id, Qty) {

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
        "'>" +
        "</span> <br> <span class='badge text-dark bg-light' id='Description" + no +
        "'>" +
        "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
        no + "' readonly value=''></td>"
    let Kadar =
        "<td><span class='badge' style='font-size:14px; background-color:" +
        value.HexColor + "' id='KadarKar" + no +
        "'>" +
        "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
        no + "' readonly value='" + "'></td>"
    let QtyKomponen =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center QtyKomponen' id='QtyKomponen_" +
        no + "' value='" + Qty + "' onkeyup='JumlahKomponen(this.value," + no +
        ")'></td>"
    let Batu =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center Batu' id='Batu_" +
        no + "' value='0' onkeyup='JumlahBatu(this.value," + no + ")'></td>"
    let Keterangan =
        "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center Keterangan' id='Keterangan_" +
        no + "' value=''></td>"
    let Action =
        "<td align='center'><button disabled class='btn btn-info btn-sm add' type='button' onclick='add(\"" +
        WorkOrder + "\"," + IDWorkOrder + "," + no + "," + Qty +
        ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' disabled class='btn btn-danger btn-sm remove' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let finalItem = ""
    let rowitem = finalItem.concat(start, urut, WorkOrders, Barang, Kadar, QtyKomponen,
        Batu, Keterangan, Action, "</tr>")
    $("#tabel1 > tbody").append(rowitem)
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
    // Get jumlah komponen dan batu
    let JumlahKomponen = $('#Jumlah_Komponen').text()
    let JumlahBatu = $('#Jumlah_Batu').text()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let IDWorkOrders = $('.IDWorkOrder')
    let IDProducts = $('.IDProduct')
    let Qtys = $('.QtyKomponen')
    let Keterangans = $('.Keterangan')

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
    if (Qtys.length === 0 || IDProducts.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Jumlah Batu dalam tabel ada yang kosong .",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekQtys = false
    Qtys.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Terdapat jumlah batu dalam tabel yang belum di isi.",
            })
            cekQtys = true
            return false;
        }
    })
    if (cekQtys == true) {
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
    for (let index = 0; index < IDProducts.length; index++) {
        var IDProduct = $(IDProducts[index]).val()
        var IDWorkOrder = $(IDWorkOrders[index]).val()
        var Qty = $(Qtys[index]).val()
        var Keterangan = $(Keterangans[index]).val()
        let dataitems = {
            IDProduct: IDProduct,
            IDWorkOrder: IDWorkOrder,
            Qty: Qty,
            Keterangan: Keterangan
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
        JumlahKomponen: JumlahKomponen,
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
        url: "/Produksi/Lilin/LilinBersih/Simpan",
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
            $('#IDWaxClean').val(data.data.ID)
            // disabled form header
            $('#show').prop('hidden', false)
            //set readonly tabel
            $('.IDWorkOrder').prop('readonly', true)
            $('.IDProduct').prop('readonly', true)
            $('.QtyKomponen').prop('readonly', true)
            $('.Keterangan').prop('readonly', true)

            $('#tambahkurang').prop('hidden', true)
            $('.add').prop('hidden', true)
            $('.remove').prop('hidden', true)


            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            // disabled input
            $("#IDSPKOInject").prop('disabled', true)
            $("#tanggal").prop('readonly', true)
            $("#Catatan").prop('disabled', true)
            $("#IdOperator").prop('disabled', true)
            $("#kelompok").prop('disabled', true)

            // pindah function serach


            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "Data Berhasil Tersimpan.",
                timer: 500,
                showCancelButton: false,
                showConfirmButton: false
            });

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

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#IdOperator').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/LilinBersih/Operator/' + IdOperator, function(data) {
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
    $('.QtyKomponen').prop('readonly', false)

    $('.Keterangan').prop('readonly', false)

    $('#tambahkurang').prop('hidden', false)
    $('.add').prop('hidden', false)
    $('.remove').prop('hidden', false)
}

function Ubah() {
    //get ID wax
    let IDwax = $('#IDWaxClean').val()
    console.log(IDwax);
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
    // Get jumlah komponen dan batu
    let JumlahKomponen = $('#Jumlah_Komponen').text()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let IDWorkOrders = $('.IDWorkOrder')
    let IDProducts = $('.IDProduct')
    let Qtys = $('.QtyKomponen')
    let Keterangans = $('.Keterangan')

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
    if (Qtys.length === 0 || IDProducts.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Jumlah Batu dalam tabel ada yang kosong .",
        })
        return;
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
    for (let index = 0; index < IDProducts.length; index++) {
        var IDProduct = $(IDProducts[index]).val()
        var IDWorkOrder = $(IDWorkOrders[index]).val()
        var Qty = $(Qtys[index]).val()

        var Keterangan = $(Keterangans[index]).val()
        let dataitems = {
            IDProduct: IDProduct,
            IDWorkOrder: IDWorkOrder,
            Qty: Qty,

            Keterangan: Keterangan
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
        JumlahKomponen: JumlahKomponen,
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
        url: "/Produksi/Lilin/LilinBersih/Update",
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
            $('#IDWaxClean').val(data.data.ID)
            $("#IdOperator").prop('disabled', true)
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
        url: "/Produksi/Lilin/LilinBersih/Search?keyword=" + cari,
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
            $('#IDWaxClean').val(data.data.ID)
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
            // set jumlah komponen

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
                let productJadi =
                    "<td><span class='badge text-dark bg-light ProductJadi' style='font-size:14px;' id='ProductJadi_" +
                    no +
                    "'>" + value.Productjadi +
                    "</span></td>"
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" +
                    no +
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
                let QtyKomponen =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center QtyKomponen' id='QtyKomponen_" +
                    no + "' value='" + value.Qty + "' onkeyup='JumlahKomponen(this.value," + no +
                    ")'></td>"
                let Keterangan =
                    "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center Keterangan' id='Keterangan_" +
                    no + "' value='" + value.Keterangan + "'> </td>"
                let Action =
                    "<td align='center'><button disabled class='btn btn-info btn-sm add' type='button' onclick='add(\"" +
                    value.WorkOrder + "\"," + value.IDWorkOrder + "," + no + "," + value.Qty +
                    ",\"" + value.Productjadi + "\")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' disabled class='btn btn-danger btn-sm remove' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, WorkOrders, Barang, Kadar,
                    QtyKomponen, Keterangan, Action, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });
            JumlahKomponen();
            // show user admin dan tangal entry
            $('#show').prop('hidden', false)
            //set readonly tabel
            $('.IDWorkOrder').prop('readonly', true)
            $('.IDProduct').prop('readonly', true)
            $('.QtyKomponen').prop('readonly', true)
            $('.Keterangan').prop('readonly', true)

            $('#tambahkurang').prop('hidden', true)
            $('.add').prop('hidden', true)
            $('.remove').prop('hidden', true)


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
<?php $title = 'Lilin Pengembalian Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Lilin Pengembalian Karet</li>
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

@media print {
    body * {
        visibility: hidden;
    }

    #tabel1 * {
        visibility: visible;
    }

    #tabel1 {
        position: absolute;
        left: 0;
        top: 0;
        page-break-before: always;
    }

    td {
        font-size: 11px;

    }

    @page {
        size: A4 portrait;
        margin-top: -27mm;
    }

}
</style>

@endsection

@section('container')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            @include('Produksi.Lilin.PengembalianKaret.data')

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

    // $("#tabel1 tbody").empty()
    // Disable button "Baru and Cetak"

    $("#btn_baru").prop('disabled', true)
    $("#btn_cetak").prop('disabled', false)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    $("#tabel1").prop('hidden', false)
    $('.IDKaret').prop('disabled', false)
    $('.Status').prop('disabled', false)
    $('.ilang').prop('hidden', false)
    $('.cetak').prop('hidden', true)
    getstatus();
}

function klikBatal() {
    window.location.reload()
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
}

function getstatus(value, id) {
    var values = value;
    if (values == 'N') {
        var status = 'Rusak';
    } else {
        var status = 'Bagus';
    }
    console.log(status)
    $('#ids_' + id).text(status)
}

function getIDKaret(value, id) {

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
        type: 'GET',
        url: '/Produksi/Lilin/PengembalianKaret/cariID',
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {
            console.log(data['IDMRubberOut']);

            if (data.rowcount > 0) {
                $('#ItemProd' + id).text(data.ItemProduct)
                $('#Description' + id).text(data.Description)
                $('#IDProduct_' + id).val(data.IDProd)
                // ubah form kadar karet
                $('#KadarKar' + id).text(data.Kadar)
                $('#KadarKar' + id).css('background-color', data.HexColor)
                $('#IDKadar_' + id).val(data.IDKadar)

                $('#idk_' + id).text(data.IDKaret)
                // ubah form  lokasi
                $('#Lok' + id).text(data.Lokasi)
                $('#Location_' + id).val(data.Lokasi)
                // ubah form idm dan ordinal
                $('#IDMRubberOut_' + id).val(data.IDMRubberOut)
                $('#OrdinalRubberOut_' + id).val(data.OrdinalRubberOut)
                getstatus();
            } else {
                $('#ItemProd' + id).text('')
                $('#Description' + id).text('')
                $('#IDProduct_' + id).val('')
                // ubah kadar karet
                $('#KadarKar' + id).text('')
                $('#KadarKar' + id).css('background-color', 'dark')
                $('#IDKadar_' + id).val('')
                // ubah lokasi
                $('#Lok' + id).text('')
                $('#Location_' + id).val('')
                // ubah form idm dan ordinal
                $('#IDMRubberOut_' + id).val('')
                $('#OrdinalRubberOut_' + id).val('')

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
            })
            return;
        }
    });

}

function add(id) {

    let no = $('#tabel1 tr').length;
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value='" + no + "'></td>"
    let IDkaret =
        "<td class='ilang'><input type='number' autofocus class='form-control form-control-sm fs-6 w-100 text-center IDKaret' autofocus onchange='getIDKaret(this.value," +
        no + ")' id='IDKaret_" +
        no + "' value=''></td>" +
        "<td hidden class='cetak' id='idk_" + no + "'></td>"
    let Barang =
        "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
        "'></span> <br> <span class='badge text-dark bg-light' id='Description" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
        no + "' readonly value=''></td>"
    let Kadar =
        "<td><span class='badge' style='font-size:14px; background-color: dark ' id='KadarKar" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDKadar' id='IDKadar_" +
        no + "' readonly value=''></td>"
    let Location =
        "<td><span class = 'badge text-dark bg-light' style='font-size:16px;' id='Lok" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center Location' id='Location_" +
        no + "' readonly value=''></td>"
    let Status =
        "<td class='ilang'><select name='Keperluan' id='Status1' class='form-select Status' tabindex='2' onchange='getstatus(this.value," +
        no + ")'> <option value='Y'>Bagus</option> <option value='N'>Rusak</option> </select></td>" +
        "<td hidden class='cetak' id='ids_" + no + "'></td>"
    let IDM =
        "<td hidden><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDMRubberOut' id='IDMRubberOut_" +
        no + "' value=''></td>"
    let Ordinal =
        "<td hidden><input type='number' class='form-control form-control-sm fs-6 w-100 text-center OrdinalRubberOut' id='OrdinalRubberOut_" +
        no + "' value=''></td>"
    let Action =
        "<td align='center' class='ilang'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
        no + ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let finalItem = ""
    let rowitem = finalItem.concat("<tr>", urut, IDkaret, Barang, Kadar, Location, Status, IDM, Ordinal,
        Action, "</tr>")
    $("#tabel1 > tbody").append(rowitem);
    $("#IDKaret_" + no).focus();

    $posisi = "#tabel1 #" + no + " input";
    $($posisi).on('contextmenu', function(e) {
        rightclik(this, e);
    });
    getstatus();
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

    // Get item
    let IDProds = $('.IDProduct')
    let KadarKarets = $('.IDKadar')
    let IDKarets = $('.IDKaret')
    let Locations = $('.Location')
    let Conditions = $('.Status')
    let IDMRubberOuts = $('.IDMRubberOut')
    let OrdinalRubberOuts = $('.OrdinalRubberOut')

    // console.log(IDMRubberOuts);

    console.log(IDProds + '' + KadarKarets + '' + IDKarets + '' + IDMRubberOuts + '' + OrdinalRubberOuts + '' +
        Locations + '' +
        Conditions);
    // Check idWaxInjectOrder
    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDProds.length === 0 || KadarKarets.length === 0 || IDKarets.length === 0 || Locations.length === 0 ||
        Conditions.length === 0 || IDMRubberOuts.length === 0 || OrdinalRubberOuts.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekIDProduct = false
    IDProds.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'Waduh mbak',
                title: 'Error....',
                text: "Ada Baris yang masih kosong harap check kembali sebelum di simpan.",
            })
            cekIDProduct = true
            return false;
        }
    })
    if (cekIDProduct == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let cekKadarKaret = false
    KadarKarets.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty idProduct field. Please Fill it.",
            })
            cekKadarKaret = true
            return false;
        }
    })
    if (cekKadarKaret == true) {
        return false;
    }

    //!  ------------------------    Check Items Product if have blank value     ------------------------ !!
    let cekIDKaret = false
    IDKarets.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty Product field. Please Fill it.",
            })
            cekIDKaret = true
            return false;
        }
    })
    if (cekIDKaret == true) {
        return false;
    }

    //!  ------------------------    Check Items itemQty if have blank value     ------------------------ !!
    let cekLocation = false
    Locations.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty itemQty field. Please Fill it.",
            })
            cekLocation = true
            return false;
        }
    })
    if (cekLocation == true) {
        return false;
    }

    //!  ------------------------    Check Items Status if have blank value     ------------------------ !!
    let cekConditions = false
    Conditions.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty Condition field. Please Fill it.",
            })
            cekConditions = true
            return false;
        }
    })
    if (cekConditions == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDProd = $(IDProds[index]).val()
        var KadarKaret = $(KadarKarets[index]).val()
        var IDKaret = $(IDKarets[index]).val()
        var Location = $(Locations[index]).val()
        var Condition = $(Conditions[index]).val()
        var IDMRubberOut = $(IDMRubberOuts[index]).val()
        var OrdinalRubberOut = $(OrdinalRubberOuts[index]).val()
        let dataitems = {
            IDProd: IDProd,
            KadarKaret: KadarKaret,
            IDKaret: IDKaret,
            Location: Location,
            Condition: Condition,
            IDMRubberOut: IDMRubberOut,
            OrdinalRubberOut: OrdinalRubberOut
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
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
        url: "/Produksi/Lilin/PengembalianKaret/Simpan",
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
            // $('#cari').val(data.data.RubberID)
            // $('#IDRubberout').val(data.data.ID)
            // Set action to update
            // $('#action').val('update')
            // disabel button "Baru, Ubah and except Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            // disabled input
            $('.IDKaret').prop('disabled', true)
            $('.Status').prop('disabled', true)
            $('.ilang').prop('hidden', true)



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

function KlikEdit() {
    // Set action to update
    // $('#action').val('update')
    // Set Button 
    $("#btn_baru").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Disable input 
    $('.IDKaret').prop('disabled', false)
    $('.Status').prop('disabled', false)
}

function UbahSPKO() {
    // GetidNTHKO
    let idWaxTree = $('#idWaxTree').val()
    // Get idEmployee 
    let idEmployee = $('#idEmployee').val()
    // CheckidWaxTree
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
    // CSRF Token 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token" ]').attr('content')
        }
    });
    // HitBackend 
    $.ajax({
        type: "PUT",
        url: "/Produksi/Lilin/PengembalianKaret",
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
            // Enablebutton "Baru, Ubah and Cetak"
            $("#btn_baru").prop('disabled', true)
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



function klikCetak() {


    let IDKarets = $('.IDKaret')
    $("#tabel1").css("position: fixed;");
    $('.ilang').prop('hidden', true)
    $('.cetak').prop('hidden', false)
    window.print('#tabel1')

    // let items = []
    // for (let index = 0; index < IDKarets.length; index++) {
    //     var IDKaret = $(IDKarets[index]).val()

    //     items.push(IDKaret)
    // }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    // window.open('/Produksi/Lilin/PengembalianKaret/Cetak/_blank');
}
</script>
@endsection
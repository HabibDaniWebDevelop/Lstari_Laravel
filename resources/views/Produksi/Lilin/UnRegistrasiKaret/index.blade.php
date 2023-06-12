<?php $title = 'UnRegistrasi Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">UnRegistrasi Karet</li>
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
    }

}
</style>

@endsection

@section('container')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            @include('Produksi.Lilin.UnRegistrasiKaret.data')

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
    dom: 'Bfrtip',
    buttons: [{
        extend: 'print',
        split: ['excel', 'pdf', 'print', 'copy', 'csv'],
    }]
    // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
});

function KlikBaru() {

    // Disable button "Baru and Cetak"
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', true)
    // $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    $("#tabel1").prop('hidden', false)
    $('.IDKaret').prop('disabled', false)
    $('.Status').prop('disabled', false)
    $('.ilang').prop('hidden', false)

    $("#IDKaret_1").focus();
}

function klikBatal() {
    window.location.reload()
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
        url: '/Produksi/Lilin/UnRegistrasiKaret/cariID',
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
                $('#product_' + id).val(data.IDProd)
                // ubah kadar karet
                $('#KadarKar' + id).text(data.Kadar)
                $('#KadarKar' + id).css('background-color', data.HexColor)
                $('#kadar_' + id).val(data.IDKadar)
                // ubah lokasi
                $('#Lok' + id).text(data.Lokasi)
                $('#Location_' + id).val(data.Lokasi)

            } else {
                $('#ItemProd' + id).text('')
                $('#Description' + id).text('')
                $('#product_' + id).val('')
                // ubah kadar karet
                $('#KadarKar' + id).text('KARET BELUM DIREGISTRASI')
                $('#KadarKar' + id).css('background-color', '#000')
                $('#kadar_' + id).val('')
                // ubah lokasi
                $('#Lok' + id).text('')
                $('#Location_' + id).val('')
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

function add(id) {

    let no = $('#tabel1 tr').length;
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value='" + no + "'></td>"
    let IDkaret =
        "<td><input type='number' autofocus class='form-control form-control-sm fs-6 w-100 text-center IDKaret' autofocus onchange='getIDKaret(this.value," +
        no + ")' id='IDKaret_" +
        no + "' value=''></td>"
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
        "<td><input type='text' class='form-control form-control-sm fs-6 w-100 text-center Keterangan' id='Keterangan_" +
        no + "' value=''></td>"
    let Action =
        "<td align='center' class='ilang'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
        no + ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let finalItem = ""
    let rowitem = finalItem.concat("<tr>", urut, IDkaret, Barang, Kadar, Location, Status, Action, "</tr>")
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

    // Get item
    let IDKarets = $('.IDKaret')
    let Locations = $('.Location')
    let Keterangans = $('.Keterangan')

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDKarets.length === 0 || Locations.length === 0 || Keterangans.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
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
                text: "There still empty ITEM field. Please Fill it.",
            })
            cekIDKarets = true
            return false;
        }
    })
    if (cekIDKarets == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    let cekLocations = false
    Locations.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty idProduct field. Please Fill it.",
            })
            cekLocations = true
            return false;
        }
    })
    if (cekLocations == true) {
        return false;
    }


    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDKaret = $(IDKarets[index]).val()
        var Location = $(Locations[index]).val()
        var Keterangan = $(Keterangans[index]).val()
        let dataitems = {
            IDKaret: IDKaret,
            Location: Location,
            Keterangan: Keterangan
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
        url: "/Produksi/Lilin/UnRegistrasiKaret/Simpan",
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
            // $('#cari').val(data.data.ID)
            // $('#IDRubberout').val(data.data.ID)
            // Set action to update
            // $('#action').val('update')
            // disabel button "Baru, Ubah and except Cetak"
            $("#btn_baru").prop('disabled', true)
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
                title: 'UnRegistrasi!',
                text: "Karet Berhasil Di UnReg.",
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

function klikCetak() {
    $('.IDKaret').prop('disabled', true)
    $('.Status').prop('disabled', true)
    $('.ilang').prop('hidden', true)
    $("#btn_baru").prop('disabled', false)

    window.print('#tabel1')

}
</script>
@endsection
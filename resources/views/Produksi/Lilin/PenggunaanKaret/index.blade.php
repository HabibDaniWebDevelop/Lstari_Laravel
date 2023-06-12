<?php $title = 'Lilin Penggunaan Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Lilin Penggunaan Karet</li>
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

            @include('Produksi.Lilin.PenggunaanKaret.data')

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

    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    // Enable Button "Batal dan Simpan"
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Enable input
    $("#IDSPKOInject").prop('disabled', false)
    $("#tanggal").prop('disabled', false)
    $("#Keperluan").prop('disabled', false)
    $("#Catatan").prop('disabled', false)
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

    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/PenggunaanKaret/Items/" + IDSPKOInject,
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
            // Set item table
            let no = 1
            data.data.items.forEach(function(value, i) {
                let start = "<tr id='tr" + no + "'>"
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
                    "'>" + value.ItemProduct +
                    "</span> <br> <span class='badge text-dark bg-light' id='Description" + no +
                    "'>" + value.Description +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center product' id='product_" +
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
                let Location =
                    "<td><span class = 'badge text-dark bg-light' style='font-size:16px;' id='Lok" +
                    no + "'>" +
                    value.Lokasi +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center Location' id='Location_" +
                    no + "' readonly value='" + value.Lokasi + "'></td>"
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
                    value.IDKaret + "," + no + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat(start, urut, Barang, Kadar, IDkaret, Location,
                    Action, "</tr>")
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
        url: '/Produksi/Lilin/PenggunaanKaret/cariID',
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
                $('#KadarKar' + id).text('')
                $('#KadarKar' + id).css('background-color', 'dark')
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

function add(Karet, id) {
    console.log(Karet + ',' + id)
    var Karet = Karet;

    let no = $('#tabel1 tr').length;
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value='" + no + "'></td>"
    let Barang =
        "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
        "'></span> <br> <span class='badge text-dark bg-light' id='Description" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center product' id='product_" +
        no + "' readonly value=''></td>"
    let Kadar =
        "<td><span class='badge' style='font-size:14px; background-color: dark ' id='KadarKar" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
        no + "' readonly value=''></td>"
    let IDkaret =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
        no + ")' id='IDKaret_" +
        no + "' value=''></td>"
    let Location =
        "<td><span class = 'badge text-dark bg-light' style='font-size:16px;' id='Lok" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center Location' id='Location_" +
        no + "' readonly value=''></td>"
    let Action =
        "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" + Karet + "," +
        no + ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let finalItem = ""
    let rowitem = finalItem.concat("<tr>", urut, Barang, Kadar, IDkaret, Location,
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

// function KlikSimpan() {
//     // Get Action let
//     action = $('#action').val()
//     // Disable button "Baru and Cetak"
//     $("#btn_baru").prop('disabled', false)
//     $("#btn_cetak").prop('disabled', true)
//     // rubah value action
//     if (action == 'simpan') {
//         SimpanSPKO()
//     } else {
//         UbahSPKO()
//     }
// }

function KlikSimpan() {
    // IDSPKOInject
    let IDSPKOInject = $('#IDSPKOInject').val()
    // Get Operator
    let Operator = $('#idope').val()
    // Get tanggal
    let date = $('#tanggal').val()
    // Get Keperluan
    let Keperluan = $('#Keperluan').val()
    // Get kadarspk
    let Kadar = $('#idkad').val()
    // GET CATATAN
    let Catatan = $('#Catatan').val()

    // Get item
    let IDprods = $('.product')
    let KadarKarets = $('.kadar')
    let IDKarets = $('.IDKaret')
    let Locations = $('.Location')

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
    if (Operator == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Operator tidak boleh kosong",
        })
        return;
    }

    // Check beratPohonTotal
    if (date == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Tanggal tidak boleh kosong",
        })
        return;
    }

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDprods.length === 0 || IDKarets.length === 0 || Locations.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Need One or More Data.",
        })
        return;
    }

    //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
    let cekIDProduct = false
    IDprods.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty ITEM field. Please Fill it.",
            })
            cekIDProduct = true
            return false;
        }
    })
    if (cekIDProduct == true) {
        return false;
    }

    //!  ------------------------    Check Items idProduct if have blank value     ------------------------ !!
    // let cekKadarKaret = false
    // KadarKarets.map(function() {
    //     if (this.value === '') {
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Oops...',
    //             text: "There still empty idProduct field. Please Fill it.",
    //         })
    //         cekKadarKaret = true
    //         return false;
    //     }
    // })
    // if (cekKadarKaret == true) {
    //     return false;
    // }

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

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDprod = $(IDprods[index]).val()
        var KadarKaret = $(KadarKarets[index]).val()
        var IDKaret = $(IDKarets[index]).val()
        var Location = $(Locations[index]).val()
        let dataitems = {
            IDprod: IDprod,
            KadarKaret: KadarKaret,
            IDKaret: IDKaret,
            Location: Location
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        IDSPKOInject: IDSPKOInject,
        Operator: Operator,
        date: date,
        Keperluan: Keperluan,
        kadar: Kadar,
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
        url: "/Produksi/Lilin/PenggunaanKaret/Simpan",
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
            // $('#IDRubberout').val(data.data.ID)
            // Set action to update
            // $('#action').val('update')
            // disabel button "Baru, Ubah and except Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', true)
            $("#btn_cetak").prop('disabled', false)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            // disabled input
            $("#IDSPKOInject").prop('disabled', true)
            $("#Operator").prop('disabled', true)
            $("#tanggal").prop('disabled', true)
            $("#Keperluan").prop('disabled', true)
            $("#Catatan").prop('disabled', true)

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
    $("#tanggal").prop('disabled', true)
    $("#idWaxInjectOrder").prop('disabled', true)
    $("#idEmployee").prop('disabled', false)
    $("#nomorPohon").prop('disabled', true)
    $("#beratPohonTotal").prop('disabled', true)
    $("#beratBatu").prop('disabled', true)
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
        url: "/Produksi/Lilin/PenggunaanKaret",
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

function SearchspkkebutuhanKaret() {
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
        url: "/Produksi/Lilin/PenggunaanKaret/Search?keyword=" + cari,
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
            $('#IDSPKOInject').val(data.data.IDSPKOInject)
            // Set Operator
            $('#Operator').val(data.data.Operator)
            $('#IDOperator').text(data.data.IDOperator)
            // Set Tanggal
            $('#tanggal').val(data.data.TransDate)
            //set keperluan 
            $('#Keperluan').val(data.data.Status)
            // Set Kadar
            $('#Kadar').val(data.data.Kadar)
            $('#IDKadar').text(data.data.IDKadar)
            $('#Kadar').css('background-color', data.data.HexColor)
            //set catatan 
            $('#Catatan').val(data.data.Remarks)
            // Set user admin batu
            $('#UserNameAdminKaret').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatanSPKOBatu').text(data.data.EntryDate)
            // Set item table let 
            no = 1
            data.data.items.forEach(function(value, i) {
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
                    "'>" + value.ItemProduct +
                    "</span> <br> <span class='badge text-dark bg-light' id='Description" + no +
                    "'>" + value.Description +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center product' id='product_" +
                    no + "' readonly value='" + value.IDProd + "'></td>"
                let Kadar =
                    "<td><span class='badge' style='font-size:14px; background-color:" +
                    value.HexColor + "' id='KadarKar" + no +
                    "'>" + value.Kadar +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
                    no + "' readonly value='" + value.IDKadar + "'></td>"
                let IDkaret =
                    "<td>" + value.IDKaret +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
                    no + ")' id='IDKaret_" +
                    no + "' value='" + value.IDKaret + "'></td>"
                let Location =
                    "<td><span class = 'badge text-dark bg-light' style='font-size:16px;' id='Lok" +
                    no + "'>" +
                    value.Lokasi +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center Location' id='Location_" +
                    no + "' readonly value='" + value.Lokasi + "'></td>"
                let Action =
                    "<td align='center'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
                    value.IDKaret + "," + no + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat("<tr>", urut, Barang, Kadar, IDkaret, Location,
                    Action, "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            // Disable input
            $("#tanggal").prop('disabled', true)
            $("#IDSPKOInject").prop('disabled', true)
            $('#Operator').prop('disabled', true)
            $('#IDOperator').prop('disabled', true)
            $('#Keperluan').prop('disabled', true)
            $('#Catatan').prop('disabled', true)

            // Disable button "Baru and Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)

            // $("#Posting1").prop('disabled', false)

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
    window.open("/Produksi/Lilin/PenggunaanKaret/Cetak?idRubberOut=" + idRubberOut, '_blank');
}
</script>
@endsection
<?php $title = 'Transfer Pohon Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">TM Pohon Lilin</li>
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

            @include('Produksi.Lilin.TransferPohonLilin.data')

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
    $('#IDTransferTree').val('')
    // Disable button "Baru and Cetak"
    $("#btn_baru").prop('disabled', false)
    $("#btn_edit").prop('disabled', true)
    $("#btn_batal").prop('disabled', false)
    $("#btn_simpan").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_cetakrekap").prop('disabled', true)
    // Enable Button "Batal dan Simpan"

    // Enable input
    $("#IDTMWaxTree").prop('readonly', true)
    $("#IDSPKGips").prop('readonly', false)
    $("#kadar").prop('disabled', false)
    $("#tomboldaftarpohon").prop('disabled', false)
    $("#tanggal").prop('readonly', false)
    $("#Posting1").prop('disabled', true)
    $("#Catatan").prop('disabled', false)

    $("#IDSPKGips").prop('disabled', false)
    $("#kadar").prop('disabled', false)

    $("#IDTMtree").val('')
    $("#IDSPKGips").val('')
    $("#Catatan").val('')
    $('#action').val('simpan')

    $("#infoposting").text('')
    $("#IDSPKGips").focus()
    $("#tabel1 tbody").empty();
    $('#TotalPohon').text(0)
    Swal.fire({
            title: 'Kerjakan SPK Jenis O',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: 'No',
        })
        .then((result) => {
            if (result.isConfirmed) {
                $("#O").val(1)
            } else {
                $("#O").val(0)
            }
        })

}

function klikBatal() {
    window.location.reload()
}

function KlickDaftarPohonEmas() {
    var WorkOrderOO = $("#O").val()
    var IDSPKGips = $("#IDSPKGips").val()
    var Kadar = $("#kadar").val()
    $('#idkadar').text(Kadar)

    if (WorkOrderOO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "anda belum memilih jenis SPK O atau bukan",
        })
        return;
    }

    if (IDSPKGips == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Harap Masukkan ID SPKGips Terlebih dahulu",
        })
        return;
    }

    if (Kadar == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Kadar Belum dipilih",
        })
        return;
    }

    data = {
        WorkOrderOO: WorkOrderOO,
        IDSPKGips: IDSPKGips,
        Kadar: Kadar
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/TransferPohonLilin/DafatarPohon",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $("#tabel1").prop('hidden', false);
            $("#tabel2").prop('hidden', true);
            $("#tabel2 tbody").empty();
            $("#show2").prop('hidden', false);
            $("#show").prop('hidden', true);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            $("#btn_baru").prop('disabled', false)
            $("#tomboldaftarpohon").prop('hidden', false)
            $("#idEmployee").text(data.data.ID)
            $("#Employee").val(data.data.UserName)

            // Set Operator
            // Set item table
            if ($('#TotalPohon').text() == 0) {
                let no = 1
                data.data.items.forEach(function(value, i) {
                    let start =
                        "<tr class='klik3' id='" + no + "'>"
                    let uruts =
                        '<td><span id="urut_' + no + '" value="">' + no +
                        '</span></td>' +
                        '<td hidden><input class="IDWaxtreeDipilih" id="IDwaxtree' + no +
                        '" value="' +
                        value
                        .IDWaxtree + '"></input></td>'
                    let Plates =
                        '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                        no +
                        '">' + value.Plate + '</span>'
                    let Qtys =
                        '<td><span id="Qty_' + no + '" value="">' + value.Qty +
                        '</span></td>'
                    let BeratLilins =
                        '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(
                            2) +
                        '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no +
                        '" value="' +
                        no +
                        '">1</span></td>'
                    let BeratBatus =
                        '<td><span id="BeratBatus_' + no + '" value="">' + value.WeightStone
                        .toFixed(
                            2) +
                        '</span></td>'
                    let Sizes =
                        '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                        '</span></td>'
                    let Dates =
                        '<td><span id="Dates_' + no + '" value="">' + value.TransDate +
                        '</span><span hidden class=" totalpohon" id="tp_' + no +
                        '">1</span></td>'
                    let NoSPKPPICs =
                        '<td><div class="SPK" style="font-size:15px; color:' + value
                        .WorkText +
                        '; font-weight: bold; width: 230px; word-break: break-all;" id="SPK_' +
                        no +
                        '">' + value.WorkOrder +
                        '</div></td>'
                    let Kadars =
                        '<td><span class="badge" style="font-size:14px; background-color: ' + value
                        .HexColor +
                        '" id="KadarPohon_' +
                        no +
                        '">' + value.Carat + '</span></td>'
                    let Action =
                        "<td align='center'><button type='button' class='remove btn btn-danger btn-sm' onclick='remove(" +
                        no + ")' id='remove_" + no +
                        "'><i class='fa fa-minus'></i></button></td>"
                    let trEnd = "</tr>"
                    let final = ""
                    let rowitem = final.concat(start, uruts, Plates, Qtys, BeratLilins, BeratBatus,
                        Sizes, Dates, NoSPKPPICs, Kadars, Action,
                        trEnd)
                    $("#tabel1 > tbody").append(rowitem);
                    no += 1;
                });
            } else {
                var aa = parseInt($('#TotalPohon').text())
                let no = aa + 1
                data.data.items.forEach(function(value, i) {
                    let start =
                        "<tr class='klik3' id='" + no + "'>"
                    let uruts =
                        '<td><span id="urut_' + no + '" value="">' + no +
                        '</span></td>' +
                        '<td hidden><input class="IDWaxtreeDipilih" id="IDwaxtree' + no +
                        '" value="' +
                        value
                        .IDWaxtree + '"></input></td>'
                    let Plates =
                        '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                        no +
                        '">' + value.Plate + '</span>'
                    let Qtys =
                        '<td><span id="Qty_' + no + '" value="">' + value.Qty +
                        '</span></td>'
                    let BeratLilins =
                        '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(
                            2) +
                        '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no +
                        '" value="' +
                        no +
                        '">1</span></td>'
                    let BeratBatus =
                        '<td><span id="BeratBatus_' + no + '" value="">' + value.WeightStone
                        .toFixed(
                            2) +
                        '</span></td>'
                    let Sizes =
                        '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                        '</span></td>'
                    let Dates =
                        '<td><span id="Dates_' + no + '" value="">' + value.TransDate +
                        '</span><span hidden class=" totalpohon" id="tp_' + no +
                        '">1</span></td>'
                    let NoSPKPPICs =
                        '<td><div class="SPK" style="font-size:15px; color:' + value
                        .WorkText +
                        '; font-weight: bold; width: 230px; word-break: break-all;" id="SPK_' +
                        no +
                        '">' + value.WorkOrder +
                        '</div></td>'
                    let Kadars =
                        '<td><span class="badge" style="font-size:14px; background-color: ' + value
                        .HexColor +
                        '" id="KadarPohon_' +
                        no +
                        '">' + value.Carat + '</span></td>'
                    let Action =
                        "<td align='center'><button type='button' class='remove btn btn-danger btn-sm' onclick='remove(" +
                        no + ")' id='remove_" + no +
                        "'><i class='fa fa-minus'></i></button></td>"
                    let trEnd = "</tr>"
                    let final = ""
                    let rowitem = final.concat(start, uruts, Plates, Qtys, BeratLilins, BeratBatus,
                        Sizes, Dates, NoSPKPPICs, Kadars, Action,
                        trEnd)
                    $("#tabel1 > tbody").append(rowitem);
                    no += 1;
                });
            }
            JumlahPohon();

            $("#btn_simpan").prop('disabled', false)
            $("#kadar").prop('disabled', true)

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

function remove(id) {
    document.getElementById("tabel1").deleteRow(id);
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

    // insert WaxtreeTransfer
    // insert WaxtreeTransferitem

    // IDTMTree
    let IDTMWaxTree = $('#IDTMWaxTree').val()
    // Get tanggal
    let date = $('#tanggal').val()
    var Employee = $('#idEmployee').text()
    let IDSPKGips = $('#IDSPKGips').val()
    let Kadar = $("#kadar").val()
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

    if (OOO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Jenis SPK Belum ditentukan",
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
        Employee: Employee,
        IDSPKGips: IDSPKGips,
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
        url: "/Produksi/Lilin/TransferPohonLilin/Simpan",
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
    $("#IDSPKGips").prop('readonly', false)
    $("#IDSPKGips").prop('disabled', false)
    $("#kadar").prop('disabled', true)
    $("#IDSPKGips").focus()
    $("#tomboldaftarpohon").prop('disabled', false)
    $('.remove').prop('disabled', false)

    // KlickDaftarPohonEmas()
}

function Ubah() {

    // IDTMTree
    let IDTMWaxTree = $('#IDTMWaxTree').val()
    // Get tanggal
    let date = $('#tanggal').val()
    var Employee = $('#idEmployee').text()
    let IDSPKGips = $('#IDSPKGips').val()
    let Kadar = $("#kadar").val()
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

    if (OOO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Jenis SPK Belum ditentukan",
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
        Employee: Employee,
        IDTMWaxTree: IDTMWaxTree,
        IDSPKGips: IDSPKGips,
        Catatan: Catatan,
        items: items

    }

    // Setup CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // HitBackend 
    $.ajax({
        type: "PUT",
        url: "/Produksi/Lilin/TransferPohonLilin/Update",
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
            $('#IDTMWaxTree').val(data.data.ID)
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
    $("#IDSPKGips").prop('disabled', true)
    $("#kadar").prop('disabled', true)
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
        url: "/Produksi/Lilin/TransferPohonLilin/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
            $('#tabel1').prop('hidden', false);
            $("#tabel1 tbody ").empty()
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // Set WaxInjectOrderID 
            $('#IDTMWaxTree').val(data.data.ID)
            $('#tanggal').val(data.data.TransDate)
            $('#Catatan').val(data.data.Remarks)
            $('#idEmployee').text(data.data.Employee)
            $('#Employee').val(data.data.SW)
            // Set user admin batu
            $('#UserNameAdmin').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatan').text(data.data.EntryDate)
            // Set item table let 
            $('#postingstatus').val(data.data.Active)
            console.log(data.data.Active);
            $("#infoposting").text(data.data.Posting)
            $("#kadar").val(data.data.Carat)
            $('#O').val(data.data.OOO)
            $('#IDSPKGips').val(data.data.SPKGips)
            let no = 1
            data.data.items2.forEach(function(value, i) {
                let start =
                    "<tr class='klik3' id='" + no + "'>"
                let uruts =
                    '<td><span id="urut_' + no + '" value="">' + no +
                    '</span></td>' +
                    '<td hidden><input class="IDWaxtreeDipilih" id="IDwaxtree' + no +
                    '" value="' +
                    value
                    .IDWaxtree + '"></input></td>'
                let Plates =
                    '<td> <span class="badge bg-dark Plates" style="font-size:14px;" id="Plates_' +
                    no +
                    '">' + value.Plate + '</span>'
                let Qtys =
                    '<td><span id="Qty_' + no + '" value="">' + value.Qty +
                    '</span></td>'
                let BeratLilins =
                    '<td><span id="BeratLilins_' + no + '" value="">' + value.Weight.toFixed(
                        2) +
                    '</span><span hidden class="Jumlahdata" id="Jumlahdata_' + no +
                    '" value="' +
                    no +
                    '">1</span></td>'
                let BeratBatus =
                    '<td><span id="BeratBatus_' + no + '" value="">' + value.WeightStone
                    .toFixed(
                        2) +
                    '</span></td>'
                let Sizes =
                    '<td><span id="Sizes_' + no + '" value="">' + value.TreeSize +
                    '</span></td>'
                let Dates =
                    '<td><span id="Dates_' + no + '" value="">' + value.TreeDate +
                    '</span><span hidden class=" totalpohon" id="tp_' + no +
                    '">1</span></td>'
                let NoSPKPPICs =
                    '<td><div class="SPK" style="font-size:15px; color:' + value
                    .WorkText +
                    '; font-weight: bold; width: 230px; word-break: break-all;" id="SPK_' +
                    no +
                    '">' + value.WorkOrder +
                    '</div></td>'
                let Kadars =
                    '<td><span class="badge" style="font-size:14px; background-color: ' + value
                    .HexColor +
                    '" id="KadarPohon_' +
                    no +
                    '">' + value.Carat + '</span></td>'
                let Action =
                    "<td align='center'><button type='button' class='remove btn btn-danger btn-sm' onclick='remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let trEnd = "</tr>"
                let final = ""
                let rowitem = final.concat(start, uruts, Plates, Qtys, BeratLilins, BeratBatus,
                    Sizes, Dates, NoSPKPPICs, Kadars, Action,
                    trEnd)
                $("#tabel1 > tbody").append(rowitem);
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
            JumlahPohon();

            // show user admin dan tangal entry
            $('#show').prop('hidden', false)
            $('.remove').prop('disabled', true)
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

    let IDTMWaxTree = $('#IDTMWaxTree').val()
    let date = $('#tanggal').val()
    let Kadar = $("#kadar").val()


    if (IDTMWaxTree == null || IDTMWaxTree == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "TM Belum Dibuat",
        })
        return;
    }
    let data = {
        IDTMWaxTree: IDTMWaxTree,
        date: date,
        Kadar: Kadar
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
        url: "/Produksi/Lilin/TransferPohonLilin/posting",
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
            // Input Settings

            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil di posting...',
                text: "Success",
            })

            Search()
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
    let IDTMwaxtree = $('#cari').val()
    console.log(IDTMwaxtree);
    $("#btn_baru").prop('disabled', false)
    // $("#btn_cetak").prop('disabled', true)
    $("#btn_batal").prop('disabled', true)
    if (IDTMwaxtree == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. isi id TMPohon di kolom pencarian.",
        })
        return
    }
    // klikCetakrekap()
    window.open('/Produksi/Lilin/TransferPohonLilin/cetak/' + IDTMwaxtree + '/_blank');
}
</script>
@endsection
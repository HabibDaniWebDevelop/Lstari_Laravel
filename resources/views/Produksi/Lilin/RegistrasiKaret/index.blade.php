<?php $title = 'Registrasi Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Registrasi Karet</li>
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

            @include('Produksi.Lilin.RegistrasiKaret.data')

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
        $.get('/Produksi/Lilin/Registrasi/Operator/' + IdOperator, function(data) {
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
    $("#tabel1").prop('hidden', false)
    $('.IDKaret').prop('disabled', false)
    $('.Status').prop('disabled', false)
    $('.ilang').prop('hidden', false)
    $('.pilihlemari').prop('disabled', true)
    $(".IDKaret").prop('readonly', false)
    $("#IDKaret_1").focus();
}

function klikBatal() {
    window.location.reload()
}


function add(Karet, id) {
    var Karet = Karet;

    let no = $('#tabel1 tr').length;
    let urut =
        "<td>" + no +
        "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
        no + "' readonly value='" + no + "'></td>"
    let IDkaret =
        "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
        no + ")' id='IDKaret_" +
        no + "' value=''></td>"
    let Barang =
        "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
        "'></span> <br> <span class='badge text-dark bg-light' id='Description" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
        no + "' readonly value=''></td>"
    let Kadar =
        "<td><span class='badge' style='font-size:14px; background-color: dark ' id='KadarKar" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
        no + "' readonly value=''></td>"
    let pilihlokasi =
        "<td><button type='button' class='btn btn-primary pilihlemari' id='pilihlemari_" + no +
        "' value='' onclick='pilihlemari(this.value," +
        no + ")'> <span class='tf-icons bx bx-plus-circle'></span> &nbsp; Lemari </button></td>"
    let Location =
        "<td><span class = 'badge text-dark bg-light' style='font-size:16px;' id='Lok" + no +
        "'></span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDLocation' id='IDLocation_" +
        no + "' readonly value=''></td>" +
        "<td hidden><input type='hidden' class='ML' id='ML_" + no + "' value=''></td>" +
        "<td hidden><input type='hidden' class='MC' id='MC_" + no + "' value=''></td>" +
        "<td hidden><input type='hidden' class='MK' id='MK_" + no + "' value=''></td>" +
        "<td hidden><input type='hidden' class='MB' id='MB_" + no + "' value=''></td>"
    let Action =
        "<td align='center' class='ilang'><button class='btn btn-info btn-sm' type='button' onclick='add(" + Karet +
        "," +
        no + ")' id='add_" + no +
        "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
        no + ")' id='remove_" + no +
        "'><i class='fa fa-minus'></i></button></td>"
    let finalItem = ""
    let rowitem = finalItem.concat("<tr>", urut, IDkaret, Barang, Kadar, pilihlokasi, Location, Action,
        "</tr>")
    $("#tabel1 > tbody").append(rowitem);
    $("#IDKaret_" + no).focus();
    $('.pilihlemari').prop('disabled', true);

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
        url: '/Produksi/Lilin/RegistrasiKaret/cariID',
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
                // ubah lokasi
                $('#Lok' + id).text(data.Lokasi)
                $('#IDLocation_' + id).val(data.Lokasi)
                $('.pilihlemari').prop('disabled', false)
                $('#pilihlemari_' + id).val(data.IDKaret)

            } else {
                $('#ItemProd' + id).text('')
                $('#Description' + id).text('')
                $('#IDProduct_' + id).val('')
                // ubah kadar karet
                $('#KadarKar' + id).text('')
                $('#KadarKar' + id).css('background-color', 'dark')
                $('#kadar_' + id).val('')
                // ubah lokasi
                $('#Lok' + id).text('')
                $('#IDLocation_' + id).val('')
                $('.pilihlemari').prop('disabled', true)

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



function ClickLaci() {
    // $('#tampilkanlemari').html();
    let lemari = $('#lemari').val() //Ambil value lemari
    let laci = $('#laci').val() //Ambil value laci

    console.log(lemari + ' ' + laci)
    if (lemari !== '' && laci !== '') {
        $.ajax({
            type: "GET",
            url: "/Produksi/Lilin/RegistrasiKaret/Lemari/" + lemari + "/" + laci,
            beforeSend: function() {
                $(".preloader").show();
            },
            success: function(data) {
                $("#modal1").html(data);
                // $('#modalemari').modal('show');
                // $("#daftarlaci").html(data.html);
            },
            complete: function() {
                $(".preloader").fadeOut();
            },
        })
    }
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
        Update()
    }
}

function pilihlemari(IDKaret, id) {
    var id = id;
    var IDKaret = IDKaret;
    console.log(IDKaret);
    $('#modalpilihlemari').modal('show');
    $('#baris').val(id);
    $('#bariskaret').val(IDKaret);


}

function PilihLokasi() {
    var SPKPPICs = [];
    $('.lokasi:checked').each(function(i, e) {
        let id = $(this).val();

        SPKPPICs.push(id);
    });

}

function SimpanLokasi() {
    var baris = $('#baris').val();
    console.log(baris);
    var bariskaret = $('#bariskaret').val();

    var lokasi = [];
    $('.lokasi:checked').each(function(i, e) {
        let id = $(this).val();

        lokasi.push(id);
    });
    console.log(lokasi);

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/RegistrasiKaret/pilihlokasi/' + lokasi,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            console.log(data.dapatlokasi);
            $('#modalpilihlemari').modal('hide');
            $('#Lok' + baris).text(data.dapatlokasi);
            $('#IDLocation_' + baris).val(data.IDLokasi);
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'lokasi yang anda pilih sudah terisi karet lain',
                timer: 1000,
                showCancelButton: false,
                showConfirmButton: false
            })
            console.log('Error:', data);
        }
    });
}

function Simpan() {

    // Get item
    let IDKarets = $('.IDKaret')
    let IDProducts = $('.IDProduct')
    let IDLocations = $('.IDLocation')

    // alert(IDprods, KadarKarets, IDKarets, Locations);

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDKarets.length === 0 || IDProducts.length === 0 || IDLocations.length === 0) {
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
                text: "There still empty IDKaret field. Please Fill it.",
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
                text: "There still empty IDProduct field. Please Fill it.",
            })
            cekIDProducts = true
            return false;
        }
    })
    if (cekIDProducts == true) {
        return false;
    }

    //!  ------------------------    Check Items Product if have blank value     ------------------------ !!
    let cekIDLocations = false
    IDLocations.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty IDLocation field. Please Fill it.",
            })
            cekIDLocations = true
            return false;
        }
    })
    if (cekIDLocations == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDKaret = $(IDKarets[index]).val()
        var IDProduct = $(IDProducts[index]).val()
        var IDLocation = $(IDLocations[index]).val()
        let dataitems = {
            IDKaret: IDKaret,
            IDProduct: IDProduct,
            IDLocation: IDLocation
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
        url: "/Produksi/Lilin/RegistrasiKaret/Simpan",
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
            $('#action').val('update')
            // disabel button "Baru, Ubah and except Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', true)
            $("#btn_cetak").prop('disabled', false)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            // disabled input
            $(".IDKaret").prop('readonly', true)
            $(".pilihlemari").prop('disabled', true)
            $(".ilang").prop('hidden', true)

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
    $('#action').val('update')
    // Set Button 
    $("#btn_baru").prop('disabled', true)
    $("#btn_edit").prop('disabled', true)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    // Disable input 
    $(".IDKaret").prop('readonly', false)
    $('.pilihlemari').prop('disabled', false);
    $(".ilang").prop('hidden', false)
}

function Update() {
    //GET IDregist
    var IDRegist = $('#cari').val();
    // Get item
    let IDKarets = $('.IDKaret')
    let IDProducts = $('.IDProduct')
    let IDLocations = $('.IDLocation')

    // alert(IDprods, KadarKarets, IDKarets, Locations);

    //!  ------------------------    Check if have items     ------------------------ !!
    if (IDKarets.length === 0 || IDProducts.length === 0 || IDLocations.length === 0) {
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
                text: "There still empty IDKaret field. Please Fill it.",
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
                text: "There still empty IDProduct field. Please Fill it.",
            })
            cekIDProducts = true
            return false;
        }
    })
    if (cekIDProducts == true) {
        return false;
    }

    //!  ------------------------    Check Items Product if have blank value     ------------------------ !!
    let cekIDLocations = false
    IDLocations.map(function() {
        if (this.value === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "There still empty IDLocation field. Please Fill it.",
            })
            cekIDLocations = true
            return false;
        }
    })
    if (cekIDLocations == true) {
        return false;
    }

    // Turn items to json format
    let items = []
    for (let index = 0; index < IDKarets.length; index++) {
        var IDKaret = $(IDKarets[index]).val()
        var IDProduct = $(IDProducts[index]).val()
        var IDLocation = $(IDLocations[index]).val()
        let dataitems = {
            IDKaret: IDKaret,
            IDProduct: IDProduct,
            IDLocation: IDLocation
        }
        items.push(dataitems)
    }

    //!  ------------------------    Send Request to Server     ------------------------ !!
    // Setup data for server
    let data = {
        IDRegist: IDRegist,
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
        type: "PUT",
        url: "/Produksi/Lilin/RegistrasiKaret/Update",
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
            $('#action').val('update')
            // disabel button "Baru, Ubah and except Cetak"
            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', true)
            $("#btn_cetak").prop('disabled', false)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            // disabled input
            $(".IDKaret").prop('readonly', true)
            $(".pilihlemari").prop('readonly', true)
            $(".ilang").prop('hidden', true)

            Swal.fire({
                icon: 'success',
                title: 'TerUpdate!',
                text: "Data Berhasil Di Update.",
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

function Search() {
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
        url: "/Produksi/Lilin/RegistrasiKaret/Search?keyword=" + cari,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tabel1 tbody ").empty()

            // Set user admin batu
            $('#UserNameAdmin').text(data.data.UserName)
            // set tanggal entry spko batu
            $('#TanggaPembuatan').text(data.data.EntryDate)
            // Set item table let 
            no = 1
            data.data.items.forEach(function(value, i) {
                console.log(value.ItemProduct);
                let urut =
                    "<td>" + no +
                    "<input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor' id='nomor_" +
                    no + "' readonly value='" + no + "'></td>"
                let IDkaret =
                    "<td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDKaret' onchange='getIDKaret(this.value," +
                    no + ")' id='IDKaret_" +
                    no + "' value='" + value.IDKaret + "'></td>"
                let Barang =
                    "<td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd" + no +
                    "'>" + value.ItemProduct +
                    "'</span> <br> <span class='badge text-dark bg-light' id='Description" + no +
                    "'>" + value.Description +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct' id='IDProduct_" +
                    no + "' readonly value='" + value.IDProd + "'></td>"
                let Kadar =
                    "<td><span class='badge' style='font-size:14px; background-color:" + value
                    .HexColor + " ' id='KadarKar" +
                    no +
                    "'>" + value.Kadar +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center kadar' id='kadar_" +
                    no + "' readonly value='" + value.IDKaret + "'></td>"
                let pilihlokasi =
                    "<td><button type='button' class='btn btn-primary pilihlemari' id='pilihlemari_" +
                    no +
                    "' value='" + value.IDKaret + "' onclick='pilihlemari(this.value," +
                    no +
                    ")'> <span class='tf-icons bx bx-plus-circle'></span> &nbsp; Lemari </button></td>"
                let Location =
                    "<td><span class = 'badge text-dark bg-light' style='font-size:16px;' id='Lok" +
                    no +
                    "'>" + value.Lokasi +
                    "</span><input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDLocation' id='IDLocation_" +
                    no + "' readonly value='" + value.RubberLocationID + "'></td>" +
                    "<td hidden><input type='hidden' class='ML' id='ML_" + no + "' value=''></td>" +
                    "<td hidden><input type='hidden' class='MC' id='MC_" + no + "' value=''></td>" +
                    "<td hidden><input type='hidden' class='MK' id='MK_" + no + "' value=''></td>" +
                    "<td hidden><input type='hidden' class='MB' id='MB_" + no + "' value=''></td>"
                let Action =
                    "<td align='center' class='ilang'><button class='btn btn-info btn-sm' type='button' onclick='add(" +
                    value.IDKaret +
                    "," +
                    no + ")' id='add_" + no +
                    "'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm' onclick = 'remove(" +
                    no + ")' id='remove_" + no +
                    "'><i class='fa fa-minus'></i></button></td>"
                let finalItem = ""
                let rowitem = finalItem.concat("<tr style='text-align: center'>", urut, IDkaret,
                    Barang, Kadar, pilihlokasi,
                    Location, Action,
                    "</tr>")
                $("#tabel1 > tbody").append(rowitem);
                no += 1;
            });

            // show user admin dan tangal entry
            $('#show').prop('hidden', false)
            $("#tabel1").prop('hidden', false)
            //set readonly tabel

            $("#btn_baru").prop('disabled', false)
            $("#btn_edit").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)
            // disable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)

            $(".IDKaret").prop('readonly', true)
            $('.pilihlemari').prop('disabled', true);
            $(".ilang").prop('hidden', true)


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
    let idRubberregistration = $('#cari').val()
    $("#btn_baru").prop('disabled', false)
    $("#btn_cetak").prop('disabled', true)
    $("#btn_batal").prop('disabled', true)
    if (idRubberregistration == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Failed. isi id registrasikaret di pencarian.",
        })
        return
    }
    window.open("/Produksi/Lilin/RegistrasiKaret/Cetak?idRubberregistration=" + idRubberregistration, '_blank');
}
</script>
@endsection
<?php $title = 'SPKO Grafis'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R&D </li>
    <li class="breadcrumb-item">Grafis </li>
    <li class="breadcrumb-item active">SPKO Grafis </li>
</ol>
@endsection

@section('css')

<style>

</style>

@endsection

@section('container')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            @include('R&D.Grafis.SPKOGrafis.data')

        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Timbangan Script --}}
@include('layouts.backend-Theme-3.timbangan')
<script>
function isNumeric(str) {
    if (typeof str != "string") return false // we only process strings!  
    return !isNaN(str) &&
        // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Script Timbanganku
async function kliktimbang2(id) {
    if (event.keyCode === 13) {

        await sendSerialLine();
        $('#selscale').val(id);
    }
}

function CalculateTotalWeight() {
    // Get class beratItem
    let beratItems = $('.beratItem')
    let totalBeratItem = 0;
    for (let index = 0; index < beratItems.length; index++) {
        let berat = $(beratItems[index]).val()
        if (isNumeric(berat)) {
            totalBeratItem += parseFloat(berat)
        }
    }
    $('#totalBerat').val(totalBeratItem);
    console.log(totalBeratItem);
}

function KlikBaru() {
    // Getting idWorkAllocation from hidden input
    let idWorkAllocation = $('#idWorkAllocation').val()
    // Check if idWorkAllocation have value
    if (idWorkAllocation != "") {
        // If idWorkAllocation have value. It will reload th page
        window.location.reload()
        return;
    }
    // Button Settings
    $("#btn_baru").prop('disabled', true)
    $("#btn_simpan").prop('disabled', false)
    $("#btn_batal").prop('disabled', false)
    $("#btn_posting").prop('disabled', true)

    // Input Settings
    $("#noNTHKO").prop('disabled', false)
    $('#employee').prop('disabled', false)
}

function klikBatal() {
    window.location.reload()
    return;
}

function getWIP() {
    let noNTHKO = $("#noNTHKO").val()
    if (noNTHKO == null || noNTHKO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "noNTHKO Tidak Boleh Kosong"
        })
        return;
    }
    let data = {
        noNTHKO: noNTHKO
    }


    $.ajax({
        type: "GET",
        url: "/R&D/Grafis/SPKOGrafis/getWIP",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $('#tabel1 tbody > tr').remove();

            // Set TableItems
            // $('#TableItems').empty();
            $('#TableItems').html(data.data.itemsHTML)

            $('#totalJumlah').val(data.data.totalJumlah)
            $('#totalBerat').val(data.data.totalBerat)

            return;
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            $("#noNTHKO").val("")
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
            })
            return;
        }
    })
}

function KlikSimpan() {
    let noNTHKO = $("#noNTHKO").val()
    let employee = $("#employee").val()
    let totalBerat = $('#totalBerat').val()

    let beratItems = $('.beratItem')
    let beratPerItem = [];
    for (let index = 0; index < beratItems.length; index++) {
        let berat = $(beratItems[index]).val()
        if (isNumeric(berat)) {
            beratPerItem.push(berat)
        }
    }
    if (noNTHKO == null || noNTHKO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "noNTHKO Tidak Boleh Kosong"
        })
        return;
    }

    if (employee == null || employee == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "employee Tidak Boleh Kosong"
        })
        return;
    }

    let data = {
        noNTHKO: noNTHKO,
        employee: employee,
        total_berat: totalBerat,
        beratItems: beratPerItem
    }

    // Setup CSRF TOKEN
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/R&D/Grafis/SPKOGrafis",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#btn_baru").prop('disabled', false)
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            $("#btn_posting").prop('disabled', false)
            $("#btn_cetak").prop('disabled', false)

            // Input Settings
            $("#noNTHKO").prop('disabled', true)
            $('#employee').prop('disabled', true)

            // Set Hidden Value
            $('#idWorkAllocation').val(data.data.WorkAllocation)
            $('#action').val("ubah")
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

function KlikPosting() {
    // Get SWWorkAllocatoin fron input hidden
    let workAllocation = $('#idWorkAllocation').val();
    if (workAllocation == null || workAllocation == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "SPKO Belum Terpilih",
        })
        return;
    }
    let data = {
        workAllocation: workAllocation
    }
    // Setup CSRF TOKEN
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/R&D/Grafis/SPKOGrafis/posting",
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
            $('#postingStatus').val('P')

            // Button Settings
            $("#btn_baru").prop('disabled', false)
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            $("#btn_posting").prop('disabled', true)
            $("#btn_cetak").prop('disabled', false)

            // Input Settings
            $("#noNTHKO").prop('disabled', true)
            $('#employee').prop('disabled', true)
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

function KlikCari() {
    let keyword = $('#cari').val()
    if (keyword == null || keyword == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Kolom Cari Tidak boleh Kosong",
        })
        return;
    }

    let data = {
        workAllocation: keyword
    }
    $.ajax({
        type: "GET",
        url: "/R&D/Grafis/SPKOGrafis/cari",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $('#tabel1 tbody > tr').remove()
            $('#TableItems').html(data.data.itemsHTML)

            $('#totalJumlah').val(data.data.totalJumlah)
            $('#totalBerat').val(data.data.totalBerat)
            $('#tanggalNow').val(data.data.TransDate)
            $('#employee').val(data.data.Employee).select()
            $('#noNTHKO').val(data.data.noNTHKO)

            $("#btn_baru").prop('disabled', false)
            $("#btn_simpan").prop('disabled', true)
            $("#btn_batal").prop('disabled', true)
            if (data.data.Active == 'P') {
                $('#postingStatus').val('P')
                $("#btn_posting").prop('disabled', true)
            } else {
                $('#postingStatus').val('A')
                $("#btn_posting").prop('disabled', false)
            }
            $("#btn_cetak").prop('disabled', false)

            // Input Settings
            $("#noNTHKO").prop('disabled', true)
            $('#employee').prop('disabled', true)

            // Set Hidden Value
            $('#idWorkAllocation').val(data.data.SW)
            $('#action').val("ubah")

            return;
        },
        error: function(xhr) {
            // It will executed if response from backend is error
            $("#cari").val("")
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
    let workAllocation = $('#idWorkAllocation').val()
    if (workAllocation == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Kolom Cari Tidak boleh Kosong",
        })
        return;
    }
    window.open('/R&D/Grafis/SPKOGrafis/cetak?workAllocation=' + workAllocation, '_blank');
    return
}
</script>


@endsection
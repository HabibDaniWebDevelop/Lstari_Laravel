<?php $title = 'Posting Karet dari PCB'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">Posting Karet dari PCB </li>
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

            @include('Produksi.Lilin.PostingKaretPcb.data')

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
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

function KlikCari() {
    let cari = $('#cari').val()
    if (cari == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Kolom Cari Tidak Boleh Kosong",
        })
        return;
    }
    let data = {
        keyword: cari
    }

    $.ajax({
        type: "GET",
        url: "/Produksi/Lilin/PostingKaretdariPCB/search",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // Destroy datatable;
            $("#tabel1").dataTable().fnDestroy()
            $('#tabel1 tbody > tr').remove();

            // Set TableItems
            $('#TableItems').empty();
            $('#TableItems').html(data.data.resultHTML)

            $('#tabel1').DataTable({
                scrollY: '50vh',
                scrollCollapse: true,
                paging: false,
                lengthChange: false,
                searching: false,
                ordering: false,
                info: false,
                autoWidth: true,
                responsive: true,
                fixedColumns: true,
            });

            // Set Button
            if (data.data.result.PostDate == null) {
                $("#btn_posting").prop('disabled', false)
                $("#postingStatus").val("Belum di Posting")
            } else {
                $("#btn_posting").prop('disabled', true)
                $("#postingStatus").val("Terposting")
            }

            // Set Input
            $('#idTMKaretKeLilin').val(data.data.result.ID)
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

function validateKaret() {
    let valueValidate = $('#validateKaret').val()
    if (valueValidate == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "ID Karet Scan Tidak boleh blank",
        })
        $('#validateKaret').val("")
        $('#validateKaret').focus()
        return;
    }

    // Searhing checkbox with value is equal to valueValidate
    let checkboxRubber = $('.checkingRubber[value="' + valueValidate + '"]')
    if (checkboxRubber.length == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "ID Karet " + valueValidate + " Tidak Ditemukan",
        })
        $('#validateKaret').val("")
        $('#validateKaret').focus()
        return;
    }
    $('.checkingRubber[value="' + valueValidate + '"]').prop('checked', true);
    $('#validateKaret').val("")
    $('#validateKaret').focus()
}

function KlikPosting() {
    let idTMKaretLilin = $('#idTMKaretKeLilin').val()
    if (idTMKaretLilin == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "TM Karet lilin Belum terpilih",
        })
        return;
    }

    // Check if all checkbox r checked
    let checkedCheckbox = $('.checkingRubber:checkbox:checked')
    let totalCheckbox = $('.checkingRubber')
    if (checkedCheckbox.length != totalCheckbox.length) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Masih ada Karet yang belum tercentang",
        })
        return;
    }

    let data = {
        idTMKaretLilin: idTMKaretLilin
    }

    // Setup CSRF TOKEN
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/Produksi/Lilin/PostingKaretdariPCB/posting",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            // Setting Buttons
            $("#btn_posting").prop('disabled', true)

            Swal.fire({
                icon: 'success',
                title: 'Yaayy...',
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
</script>


@endsection
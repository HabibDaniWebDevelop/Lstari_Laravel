<?php $title = 'Regristrasi Mul Karet'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R & D </li>
    <li class="breadcrumb-item">Difisi Lilin </li>
    <li class="breadcrumb-item active">Regristrasi Mul Karet</li>
</ol>
@endsection

@section('container')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">

            @include('R&D.DivisiLilin.RegristrasiMulKaret.data')

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
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "fixedColumns": true
});

function Klik_Baru1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $("#tampil").removeClass('d-none');
}


function Klik_Batal1() {
    location.reload();
}

function Klik_Simpan1() {
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', false);
    $('#Cetak1').prop('disabled', false);

    var formData = $('#form1').serialize();
    // alert(formData);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var type = "PUT";
    var ajaxurl = '/forms_basic/' + '11';

    $.ajax({
        type: type,
        url: ajaxurl,
        data: formData,
        dataType: 'json',
        success: function(data) {
            alert('tes_s');

        }
    });

}

// ----------------------- fungsi Tambah Baris dan pindah input -----------------------

$('.baris').keydown(function(e) {
    var id = $(this).attr('id');
    tambahbaris(id);
});

function tambahbaris(id) {
    var id = parseFloat(id);
    if (event.keyCode === 13 || event.keyCode === 9) {
        var $this = $(event.target);
        var index = parseFloat($this.attr('data-index'));
        var pos_index = $this.attr('posisi-index');
        // alert(index + ' | ' + id + ' | ' + pos_index);

        if (pos_index == 'akhir') {
            posisi = id + 1;
            var table = document.getElementById("tabel1");
            rowCount = table.rows.length;
            // add();
            if (posisi == rowCount) {
                add();
            }
            $('[data-index="' + (id + 1).toString() + '2"]').focus();
        } else {
            $('[data-index="' + (index + 1).toString() + '"]').focus();
        }
    }

    if (event.keyCode === 40) {
        var table = document.getElementById("tabel1");
        rowCount = table.rows.length - 1;
        // alert(rowCount + ' ' + id);
        if (id == rowCount) {
            add();
            $('[data-index="' + (id + 1).toString() + '2"]').focus();
        }
    }
}

// ----------------------- fungsi klik kanan sub menu -----------------------
$(".baris input").on('contextmenu', function(e) {
    rightclik(this, e);
});

$(document).bind("contextmenu", function(e) {
    return false;
});

$("body").on("click", function() {
    if ($("#menuklik").css('display') == 'block') {
        $(" #menuklik ").hide();
    }
});

$("#menuklik a").on("click", function() {
    $(this).parent().hide();
});

function rightclik(ids, event) {

    var id = $(ids).parent().parent().attr('id');
    var top = event.pageY + 15;
    var left = event.pageX - 100;

    $("#judulklik").html(id);
    $('#klikhapus').attr('onclick', 'klikhapus(' + id + ')');
    $("#menuklik").css({
        display: "block",
        top: top,
        left: left
    });

    return false;
}

function add() {
    let rowCount = $('#tabel1 tr').length;

    // Setup table row
    let trStart = '<tr class="baris" id="' + rowCount + '">';
    let cell1 =
        '<td class="m-0 p-0"> <input type="number" readonly class = "form-control form-control-sm fs-6 w-100 text-center" name = "no[]" id="no" value = "' +
        rowCount + '" data-index = "' + rowCount + '1" posisi-index = "awal" > </td>';
    let cell2 =
        '<td class="m-0 p-0"> <input type="number" class="form-control form-control-sm fs-6 w-100 text-center" name="karet" id="karet" value="" data-index="' +
        rowCount + '2"> </td>';
    let cell3 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="kodeproduk" id="kodeproduk" value="" data-index="' +
        rowCount + '3"> </td>';
    let cell4 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="deskripsi" id="deskripsi" value="" data-index="' +
        rowCount + '4"> </td>';
    let cell5 =
        '<td class="m-0 p-1"> <button type="button" class="btn btn-primary p-1 w-100" id="pilihlokasi" data-bs-toggle="modal" data-bs-target="#exLargeModal" > <span class="tf-icons bx bx-archive"> </span>&nbsp; </button> </td > ';
    let cell6 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="lokasilemari" id="lokasilemari" value="" data-index="' +
        rowCount + '5" posisi-index="akhir"> </td>';
    let trEnd = '</tr>';
    let finalItem = "";
    let rowitem = finalItem.concat(trStart, cell1, cell2, cell3, cell4, cell5, cell6, trEnd);
    $("#tabel1 > tbody").append(rowitem); //on enter + baris

    // on enter
    $posisi = "#tabel1 #" + rowCount + " input";
    $($posisi).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        // alert(id);
        tambahbaris(id);
    });

}

function klikhapus(id) {
    // var id = $(this).attr('id');
    $("#" + id).remove();

    $("#tabel1 tr").each((i, elem) => {
        Index = i + 1;
        if (Index < id) {
            newIndex = i + 1;
        } else {
            newIndex = i;
        }
        // alert(Index +' '+ newIndex)
        $('[data-index="' + Index + '1"]').attr('value', newIndex);
        $('[data-index="' + Index + '1"]').parent().parent().attr('id', newIndex);
        $('[data-index="' + Index + '1"]').parent().parent().attr('id', newIndex);
        $('[data-index="' + Index + '1"]').attr('data-index', newIndex + '1');
        $('[data-index="' + Index + '2"]').attr('data-index', newIndex + '2');
        $('[data-index="' + Index + '3"]').attr('data-index', newIndex + '3');
        $('[data-index="' + Index + '4"]').attr('data-index', newIndex + '4');
        $('[data-index="' + Index + '5"]').attr('data-index', newIndex + '5');

        $(elem).find('.satuan').attr('id', "satuan_" + newIndex);

    })
}

function OnchangeID() {
    let idkaret = $('#idkaret').val()

    var data = {
        idkaret: idkaret
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: "/R&D/DivisiLilin/RegristrasiMulKaret/Modal/" + idkaret,
        data: data,
        dataType: "json",
        success: function(data) {
            if (data.status === 'sukses') {
                $("#kodeproduk" + row).val(data.desk); // 
                $("#deskripsi" + row).val(data.kode);
                document.getElementById("pilihlokasi" + row).disabled = false;
            } else {
                //alert("Data Tidak Ditemukan/Kurang Spesifik");
                $("#idkaret" + row).val("");
                $("#idkaret" + row).focus();

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

function ClickLaci() {

    let idlemari = $('#lemari').val() //Ambil value lemari
    let idlaci = $('#laci').val() //Ambil value laci

    let data = {
        idlemari: idlemari,
        idlaci: idlaci
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: "/R&D/DivisiLilin/RegristrasiMulKaret/Modal/" + idlemari + "/" + idlaci,
        dataType: 'json',
        beforeSend: function() {
            $(".preloader").show();
        },
        success: function(data) {
            $("#TabelLaci").html(data.html);
        },
        complete: function() {
            $(".preloader").fadeOut();
        },

    })
}
</script>

@endsection
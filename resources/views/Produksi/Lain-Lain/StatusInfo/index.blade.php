<?php $title = 'Status Info'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lain-Lain </li>
    <li class="breadcrumb-item active">Status Info </li>
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

            @include('Produksi.Lain-Lain.StatusInfo.data')

        </div>
    </div>
</div>
{{-- 
        <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
            <div class="text-center fw-bold mb-2" id="judulklik"></div>
            <a class="dropdown-item" onclick="klikedit2()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
            <a class="dropdown-item" onclick="klikcetak2()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
            <a class="dropdown-item" onclick="klikinfo2()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a>
        </div> 
    --}}
@endsection

@section('script')

{{-- @include('layouts.backend-Theme-3.DataTabelButton') --}}

<script>
function openModal() {
    $(".preloader").fadeIn(300);
}

function closeModal() {
    $(".preloader").fadeOut(300);
}

function tampilData() {
    var jenis = $('#jenis').val();

    if (jenis == "") {
        alert("Isi Jenis Dulu");
    } else {
        showIndex(jenis);
    }
}

function showIndex(idjenis) {

    var id = idjenis;

    if (id == 1) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/tmItem';
    } else if (id == 2) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/spkoItem';
    } else if (id == 3) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/kodeItem';
    } else if (id == 4) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/tfStockItem';
    } else if (id == 5) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/tfFGItem';
    } else if (id == 6) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/tfFGPersiapanItem';
    } else if (id == 7) {
        var urlAjax = '/Produksi/Lain-Lain/StatusInfo/operationItem';
    } else {
        var urlAjax = 0;
    }

    if (urlAjax != 0) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: urlAjax,
            beforeSend: function() {
                openModal();
            },
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                // console.log(data.status);
                $("#tampil1").html(data.html);
                var table = $('#tabelShow').DataTable({
                    ordering: false,
                    paging: true,
                    pageLength: 10,
                    searching: true,
                    lengthChange: false,
                    scrollX: true,
                    scroller: true,
                });
            },
            complete: function() {
                closeModal();
            },
        });
    }
}




function klikDetail(id, jenis) {
    // var url = '/Produksi/Lain-Lain/StatusInfo/Show/'+id+'/'+jenis;
    // console.log(id, jenis, url);

    $("#judulmodal1").html('Form Status Info');
    $("#modalformat").attr('class', 'modal-dialog modal-lg');

    $.get('/Produksi/Lain-Lain/StatusInfo/Show/' + id + '/' + jenis, function(data) {
        // console.log(data.status);
        $("#modal1").html(data.html);
        var table = $('#tabelShow').DataTable({
            ordering: false,
            paging: true,
            pageLength: 10,
            searching: false,
            lengthChange: false,
            scrollX: false,
            scroller: false,
        });
        $('#modalinfo').modal('show');

        $('#modalinfo').on('hidden.bs.modal', function() {
            $('#tabelShow').dataTable().fnDestroy();
        });
    });
}



function klikCari() {
    if (event.keyCode === 13) {
        var id = $('#cari').val();
        window.location.replace('/IT/DataPC/search?id=' + id);
    }
}

// -------------------- klik di tabel --------------------
$(".klik").on('click', function(e) {
    $('.klik').css('background-color', 'white');

    if ($("#menuklik").css('display') == 'block') {
        $(" #menuklik ").hide();
    } else {
        var top = e.pageY + 15;
        var left = e.pageX - 100;
        window.idfiell = $(this).attr('id');
        var id2 = $(this).attr('id2');
        $("#judulklik").html(id2);

        $(this).css('background-color', '#f4f5f7');
        $("#menuklik").css({
            display: "block",
            top: top,
            left: left
        });
    }
    return false; //blocks default Webbrowser right click menu

});

$("body").on("click", function() {
    if ($("#menuklik").css('display') == 'block') {
        $(" #menuklik ").hide();
    }
    $('.klik').css('background-color', 'white');
});

$("#menuklik a").on("click", function() {
    $(this).parent().hide();
});

function klikedit2() {
    klikedit(window.idfiell);
}

function klikcetak2() {
    klikcetak(window.idfiell);
}

function klikinfo2() {
    klikinfo(window.idfiell);
}

// ----------------------------------------------------------

function kliktambah(id) {
    $("#jodulmodal1").html('Form Tambah Data PC');
    $('#modalformat').attr('class', 'modal-dialog modal-lg');
    $("#simpan1").removeClass('d-none');
    $('#simpan1').val('Tambah');

    $.get('/IT/DataPC/tambah/', function(data) {
        $("#modal1").html(data);
        $('#modalinfo').modal('show');
    });
}

function klikcetak(id) {
    window.open('/IT/DataPC/cetak?id=' + id, '_blank');
}

function klikinfo(id) {
    $("#jodulmodal1").html('History');
    $('#modalformat').attr('class', 'modal-dialog modal-fullscreen');
    $("#simpan1").addClass('d-none');

    $.get('/IT/DataPC/info/' + id, function(data) {
        $("#modal1").html(data);
        $('#modalinfo').modal('show');
    });
}

function klikedit(id) {
    $("#jodulmodal1").html('Form Edit Data PC');
    $('#modalformat').attr('class', 'modal-dialog modal-lg');
    $("#simpan1").removeClass('d-none');
    $('#simpan1').val('Edit');

    $.get('/IT/DataPC/edit/' + id, function(data) {
        $("#modal1").html(data);
        $('#modalinfo').modal('show');
    });
}

function pilihkar1() {
    var id = $('#Department').val();

    $.get('/IT/DataPC/kar2/' + id, function(data) {
        $("#optionsto1").html(data);
    });
}

function pilihkar2() {
    var id = $('#Department').val();

    $.get('/IT/DataPC/kar2/' + id, function(data) {
        $("#optionsto2").html(data);
    });
}

// klik simpan pada modal untuk tambah data dan edit data
function KlikSimpan1() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id = $('#ID').val();
    var operation = $('#simpan1').val();
    var formData = $('#formmodal1').serialize();
    // alert(id);

    if (operation == "Tambah") {
        var type = "POST";
        var ajaxurl = '/IT/DataPC/tambah/';
    }

    if (operation == "Edit") {
        var type = "PUT";
        var ajaxurl = '/IT/DataPC/edit/' + id;
    }

    $.ajax({
        type: type,
        url: ajaxurl,
        data: formData,
        dataType: 'json',
        success: function(data) {

            if (operation == "Tambah") {
                Swal.fire({
                    icon: 'success',
                    title: 'Tambah Berhasil!',
                    text: 'Silahkan di cek Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "/IT/DataPC";
                    }
                });
                // $('#modalinfo').modal('hide');

            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Edit Berhasil!',
                    text: 'Silahkan di cek Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "/IT/DataPC";
                    }
                });

                // $('#modalinfo').modal('hide');
            }
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Tersimpan!',
                text: 'Ada fiel yang wajib di isi!',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });

};
</script>

@endsection
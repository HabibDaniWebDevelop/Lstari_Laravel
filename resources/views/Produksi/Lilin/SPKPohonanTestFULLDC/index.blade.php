<?php $title = 'Surat Perintah Kerja Pohonan (Direct Casting) !!!!!!!!!!!!!! [TEST] !!!!!!!!!!!!!!!'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
@endsection

@section('container')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-body"> -->
            <div id="tabellaci" class="col-md-12">
                @include('Produksi.Lilin.SPKPohonanTestFULLDC.data')
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>

@endsection

@section('script')
@include('layouts.backend-Theme-3.DataTabelButton')
<script>
function Click_Tambah1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Cetakbarkode').prop('disabled', true);
    $("#tampil1").removeClass('form');
    $("#karetdipilih").hide();
    $.get('/Produksi/Lilin/SPKPohonanTestFULLDC/form/', function(data) {
        $("#tampil1").html(data);

        permintaan3dp()
    });
}

function kadardanrph() {

}

function ChangeCari() {
    IDcari = $('#cari').val();
    $('#Cetak1').prop('disabled', false);
    $('#Cetak2').prop('disabled', false);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);
    $("#tampil1").removeClass('form');
    $("#tampil").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKPohonanTestFULLDC/cari/' + IDcari, function(data) {
        $("#tampil1").html(data);

    });

}

function Klik_Batal1() {
    location.reload();
}

function closed() {
    $('#Baru1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', false);
    $("#tampil1").removeClass('form');
}

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#Employee').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/SPKInjectLilin/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
    }
}

function isipiring() { // input form label piring untuk trigger form id piring
    LabelPiring = $('#Plate').val();

    if (LabelPiring !== '') {
        $.get('/Produksi/Lilin/SPKInjectLilin/Piring/' + LabelPiring, function(data) {
            $('#IdPiring').val(data.IdPir);
        });
    }
}

function permintaan3dp() {
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('hidden', true);
    $('#permintaan3dp').prop('hidden', false);
    $('#permintaan3dp').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Cetak1').prop('hidden', true);
    $('#Cetak2').prop('hidden', false);
    $('#Baru1').prop('disabled', false);
    $('#showtabel2').removeClass('showtabel2');
}

function spk3dp() {
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('hidden', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('hidden', false);
    $('#Cetak2').prop('hidden', true);
    $('#permintaan3dp').prop('hidden', true);
    $('#permintaan3dp').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);
}

function prosesminta() {
    var DetailRequest = {
        kadar: $('#kadar').val(),
        rph: $('#rph').val(),
        totalQty: $('#totalQty').val()
    }

    let WorkOrders = $('.WorkOrder');
    let Products = $('.Product');
    let Qtys = $('.Qty');
    let waxorders = $('.waxorder');
    let waxorderords = $('.waxorderord');
    let Rphs = $('.Rph');
    let Ordinals = $('.Ordinal');
    let IDWorkOrders = $('.IDWorkOrder');
    let ID3ds = $('.ID3d');

    var ItemRequests = [];
    for (let i = 0; i < WorkOrders.length; i++) {
        var WorkOrder = $(WorkOrders[i]).val()
        var Product = $(Products[i]).val()
        var Qty = $(Qtys[i]).val()
        var waxorder = $(waxorders[i]).val()
        var waxorderord = $(waxorderords[i]).val()
        var Rph = $(Rphs[i]).val()
        var Ordinal = $(Ordinals[i]).val()
        var IDWorkOrder = $(IDWorkOrders[i]).val()
        var ID3d = $(ID3ds[i]).val()
        // var productpart = $(productparts[i]).val()

        let dataitems = {
            WorkOrder: WorkOrder,
            Product: Product,
            Qty: Qty,
            waxorder: waxorder,
            waxorderord: waxorderord,
            Rph: Rph,
            Ordinal: Ordinal,
            IDWorkOrder: IDWorkOrder,
            ID3d: ID3d
            // productpart: productpart
        }
        ItemRequests.push(dataitems);
    }

    var data = {
        DetailRequest: DetailRequest,
        ItemRequests: ItemRequests
    }

    $("#tampil1").removeClass('form');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var type = "POST";
    var ajaxurl = '/Produksi/Lilin/SPKPohonanTestFULLDC/Requesti';
    // alert(formData);
    console.log(data);
    $.ajax({
        type: type,
        url: ajaxurl,
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
                title: 'Tambah Berhasil!',
                text: 'Silahkan di cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    // $('#tampil1').trigger("reset");
                    $('#Batal1').prop('disabled', true);
                    $('#Simpan1').prop('disabled', true);
                    $('#Cetak2').prop('disabled', false);
                    $('#Baru1').prop('disabled', false);
                    $('#tgl').prop('disabled', true);
                    $('#note').prop('disabled', true);
                    $('#kadar').prop('disabled', true);
                    $('#Rph').prop('disabled', true);
                    $('#idspk3dp').prop('disabled', true);

                    // $("#tampil1").html(data);
                    // $('#cari').val(data.ID1)
                    $('#idspk3dp').val(data.ID1);

                    // ChangeCari();
                }
            });

        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Upss Error !',
                text: 'Transaksi Gagal Tersimpan !',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------- simpan
function Klik_Simpan1() {
    // var emp = $('#Employee').val();
    // alert(emp);

    var detail = {
        Employee: $('#Employee').val(),
        tgl: $('#tgl').val(),
        WorkGroup: $('#WorkGroup').val(),
        BoxNo: $('#BoxNo').val(),
        StickPohon: $('#StickPohon').val(),
        Plate: $('#IdPiring').val(),
        note: $('#note').val(),
        kadar: $('#Caratshow').val(),
        idtm: $('#idtmshow').val(),
        totalQty: $('#totalQty').val()
    }
    // pindah ke funtion listproduct !!!!!!



    var items = [];
    $('.form-check-input-itemspkpohon:checked').each(function(i, e) {
        var id = $(this).val();
        var product = $(this).attr("data-product");
        var Qty = $(this).attr("data-qty");
        var carat = $(this).attr("data-carat");
        var WorkOrder = $(this).attr("data-WorkOrder");
        var WorkOrderOrd = $(this).attr("data-WorkOrderOrd");
        var WaxOrder = $(this).attr("data-WaxOrder");
        var WaxOrderOrd = $(this).attr("data-WaxOrderOrd");
        var TransferResinDC = $(this).attr("data-TransferResinDC");
        var TransferResinDCOrd = $(this).attr("data-TransferResinDCOrd");
        // var worklistord = $(this).attr("data-worklistord");
        let dataitems = {
            id: id,
            product: product,
            Qty: Qty,
            carat: carat,
            WorkOrder: WorkOrder,
            WorkOrderOrd: WorkOrderOrd,
            WaxOrder: WaxOrder,
            WaxOrderOrd: WaxOrderOrd,
            TransferResinDC: TransferResinDC,
            TransferResinDCOrd: TransferResinDCOrd
            // worklistord: worklistord
        }
        items.push(dataitems);
    });
    var data = {
        detail: detail,
        items: items
    }

    $("#tampil1").removeClass('form');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var type = "POST";
    var ajaxurl = '/Produksi/Lilin/SPKPohonanTestFULLDC/Save';
    // alert(formData);
    console.log(data);
    $.ajax({
        type: type,
        url: ajaxurl,
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
                title: 'Tambah Berhasil!',
                text: 'Silahkan di cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    // $('#tampil1').trigger("reset");
                    $('#Batal1').prop('disabled', true);
                    $('#Simpan1').prop('disabled', true);
                    $('#Cetak1').prop('disabled', false);
                    $('#Baru1').prop('disabled', false);
                    $('#Employee').prop('disabled', true);
                    $('#tgl').prop('disabled', true);
                    $('#sw').prop('disabled', true);
                    $('#WorkGroup').prop('disabled', true);
                    $('#BoxNo').prop('disabled', true);
                    $('#Plate').prop('disabled', true);
                    $('#StickPohon').prop('disabled', true);
                    $('#note').prop('disabled', true);
                    $('#note').prop('disabled', true);
                    $('#kadar').prop('disabled', true);
                    $('#idtm').prop('disabled', true);

                    // $("#tampil1").html(data);
                    $('#IDSPKpohonan').val(data.ID2);
                    $('#cari').val(data.ID2);

                    ChangeCari();
                }
            });

        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Upss Error !',
                text: 'Transaksi Gagal Tersimpan !',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });
}

function KlickDaftarProduct() {
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph


    if (kdr !== '' && rph !== '') {
        $.ajax({
            type: "GET",
            url: '/Produksi/Lilin/SPKPohonanTestFULLDC/ProdukList/' + kdr + '/' + rph,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").fadeOut();
            },
            success: function(data) {
                $("#modal1").html(data);
                $('#modalproduk').modal('show');
            }
        });

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Harap Isi Form',
            text: 'Kadar dan RPH terlebih dahulu',
        })
        return
    }
}

function KlickDaftarKomponen() {
    $("#showtabel").removeClass('d-none');

    var carat = $('#kadar').val();
    var idtm = $('#idtm').val();

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/SPKPohonanTestFULLDC/add/' + carat + '/' + idtm,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#showtabel1").html(data);
        }
    });
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------- cetak

function Prosesdata() {
    totalQty = $('#totalQty1').val();
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    // alert(totalQty + ',' + kdr + ',' + rph);

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    // alert(items);
    console.log(items);
    $('#FormTotalQty').val(totalQty);
    $('#Simpan1').prop('disabled', false);
    $("#showtabel").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKPohonanTestFULLDC/itemproductDC/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#showtabel1").html(data);
        $('#modalproduk').modal('hide');
    });
}

function cetakformpermintaan() {

}

function Klik_Cetak1() {
    idspkpohon = $('#IDSPKpohonan').val();
    carat = $('#Caratshow').val();
    idtm = $('#idtmshow').val();
    console.log(idspkpohon + '/' + carat + '/' + idtm);
    // alert(idtm);

    if (idspkpohon !== '') {
        window.open('/Produksi/Lilin/SPKPohonanTestFULLDC/cetak/' + idspkpohon + '/' + carat + '/' + idtm, '_blank');
    }
}

function printbarkode() {

    idspkpohon = $('#IDSPKpohonan').val();

    if (idspkpohon !== '') {
        window.open('/Produksi/Lilin/SPKPohonanTestFULLDC/printplate/' + idspkpohon + '/_blank');
    }

}

function ChangeTM() {
    IdTmResinSudahDiposting = $('#IDtm3dp').val();
    // alert(IdTmResinSudahDiposting);

    if (IdTmResinSudahDiposting !== '') {
        $('#Baru1').prop('disabled', true);
        $('#Batal1').prop('disabled', false);
        $('#Simpan1').prop('disabled', false);
        $('#Cetak1').prop('disabled', true);
        $("#showtabel2").removeClass('showtabel2');

        $.ajax({
            type: "GET",
            url: '/Produksi/Lilin/SPKPohonanTestFULLDC/dapattm3dp/' + IdTmResinSudahDiposting,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").fadeOut();
            },
            success: function(data) {
                $("#showtabel2").html(data);
                $('#Employee').prop('disabled', false);
                $('#tgl').prop('disabled', false);
                $('#sw').prop('disabled', true);
                $('#WorkGroup').prop('disabled', false);
                $('#BoxNo').prop('disabled', false);
                $('#Plate').prop('disabled', false);
                $('#StickPohon').prop('disabled', false);
                $('#note').prop('disabled', false);
                $('#kadar2').prop('disabled', true);
                $('#rphlilin2').prop('disabled', true);

                tambahdataSPK()
            }
        });

    }
}

function tambahdataSPK() {
    kdrspk = $('#Caratshow').val();
    rphspk = $('#idtmshow').val();
    workorder = $('#workorder').val();

    console.log(kdrspk + '/' + rphspk + '/' + workorder);

    $('#Simpan1').prop('disabled', false);
    $("#showtabel3").removeClass('showtabel3');

    $.get('/Produksi/Lilin/SPKPohonanTestFULLDC/tambahdataSPK/' + workorder + '/' + kdrspk + '/' + rphspk, function(
        data) {
        $("#showtabel3").html(data);
    });
}


function dataspk() {
    totalQty = $('#totalQty').val();
    let kdr = $('#kadar').val(); //Ambil value kdr
    let rph = $('#rphlilin').val(); //Ambil value rph

    // alert(totalQty + ',' + kdr + ',' + rph);

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    // alert(items);
    console.log(items);
    $('#FormTotalQty').val(totalQty);
    $('#Simpan1').prop('disabled', false);
    $("#showtabel").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKPohonanTestFULLDC/itemproductDC/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#showtabel1").html(data);
        $('#modalproduk').modal('hide');
    });
}

function printSPK3DP() {
    // alert('request 3dp');

    let IDSPK3Dp = $('#idspk3dp').val();
    // con(IDSPK3Dp);

    if (IDSPK3Dp !== '') {
        window.open('/Produksi/Lilin/SPKInjectLilin/PrintSPK3Dp/' + IDSPK3Dp + '/_blank');
    }
}
</script>
{{-- @extends('layouts.backend-Theme-3.XtraScript') --}}
@endsection
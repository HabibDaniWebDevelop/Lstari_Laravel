<?php $title = 'Order Tambahan Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2 mb-1">
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
                @include('Produksi.Lilin.OrderTambahanLilin.data')
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>

@endsection

@section('script')
@include('layouts.backend-Theme-3.DataTabelButton')
<script>
function Tambah_SPKO() {
    $('#Baru1').prop('disabled', true);
    $('#Baru2').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Cetakbarkode').prop('disabled', true);
    $("#tampil1").removeClass('form');
    $("#karetdipilih").hide();
    $.get('/Produksi/Lilin/OrderTambahanLilin/formSPKO/', function(data) {
        $("#tampil1").html(data);

        spk3dp()
    });
}

function Tambah_SPKPohon() {
    $(".preloader").show();

    $('#Baru1').prop('disabled', true);
    $('#Baru2').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Cetakbarkode').prop('disabled', true);
    $("#tampil1").removeClass('form');
    $("#karetdipilih").hide();
    $.get('/Produksi/Lilin/OrderTambahanLilin/formSPKPohonan/', function(data) {
        $("#tampil1").html(data);
        permintaan3dp()
        $(".preloader").fadeOut();
    });
}

function printbarkode() {

    idspkpohon = $('#IDSPKpohonan').val();

    if (idspkpohon !== '') {
        window.open('/Produksi/Lilin/OrderTambahanLilin/printplate/' + idspkpohon + '/_blank');
    }
}

function isioperator() { // input form id operator untuk trigger form nama operator
    IdOperator = $('#IdOperator').val();

    if (IdOperator !== '') {
        $.get('/Produksi/Lilin/OrderTambahanLilin/Operator/' + IdOperator, function(data) {
            $('#NamaOperator').val(data.namaop);
        });
    }
}

function isipiring() { // input form label piring untuk trigger form id piring
    LabelPiring = $('#LabelPiring').val();

    if (LabelPiring !== '') {
        $.get('/Produksi/Lilin/OrderTambahanLilin/Piring/' + LabelPiring, function(data) {
            $('#IdPiring').val(data.IdPir);
        });
    }
}

function Klickdaftarproduct() {
    let SWWorkOrder = $('#SWWorkOrder').val(); //Ambil value rph

    if (SWWorkOrder !== '') {
        $.ajax({
            type: "GET",
            url: '/Produksi/Lilin/OrderTambahanLilin/ProdukList/' + SWWorkOrder,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").fadeOut();
            },
            success: function(data) {
                $("#modal1").html(data);
                $('#modalproduk').modal('show');
                $('#Kadar').val(data.carat);
                $('#rphlilin').val(data.id);
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'No. SPK Tidak ditemukan!',
                    text: 'Harap Check Kembali',
                    confirmButtonColor: "#913030"
                })
                console.log('Error:', data);
            }
        });

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Harap Isi Form',
            text: 'SPK ORDER terlebih dahulu',
        })
        return
    }
}

function Prosesdata() {
    totalQty = $('#totalQtyOrderItemInject').val();
    kadaritem = $('#kadaritem').val();
    rphitem = $('#rphitem').val();
    KadarID = $('#KadarID').val();
    let SWWorkOrder = $('#SWWorkOrder').val(); //Ambil value rph

    console.log(totalQty + ',' + SWWorkOrder + ',' + kadaritem + ',' + KadarID + ',' + rphitem);

    var XIOrdinal = [];
    $('.daftarproduk:checked').each(function(i, e) {
        let id = $(this).val();

        XIOrdinal.push(id);
    });
    // alert(items);
    console.log(XIOrdinal);
    $('#FormTotalQty').val(totalQty);
    $('#Kadar').val(kadaritem);
    $('#rphlilin').val(rphitem);
    $('#KadarId').val(KadarID);
    $('#Simpan1').prop('disabled', false);
    $("#showtabelitem").removeClass('d-none');
    $(".preloader").show();

    $.get('/Produksi/Lilin/OrderTambahanLilin/ItemSPKO/' + XIOrdinal + '/' + SWWorkOrder, function(data) {
        $("#showtabelitem").html(data);
        $('#modalproduk').modal('hide');
        $(".preloader").fadeOut();
    });
}

function ShowListItemDC() {
    let kdr = $('#kadar').val(); //Ambil value kadar
    let rph = $('#rphlilin').val(); //Ambil value rph

    var items = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        items.push(id);
    });
    console.log(items);
    // if (items !== '') {
    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/OrderTambahanLilin/TambahKomponenDirect/' + items + '/' + kdr + '/' + rph,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#modal2").html(data);
            $('#modalKomponenDirect').modal('show')
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Ada Item Driect casting !',
                text: 'Tidak Ada Komponene Direct Casting',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });

    // } else {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: 'Tidak ada Komponen yang menggunakan Direcasting',
    //     })
    //     return
    // }
}

function jumlahqty(qty) {
    console.log(qty);
    var Qtys = $('#tabel1').find('.Qty')

    var total = 0;
    for (let i = 0; i < Qtys.length; i++) {
        var aa = parseInt($(Qtys[i]).val())

        total = total + aa
    }
    console.log(total);
    $('#FormTotalQty').val(total);

}

function OrderKomponen3DP() {
    var detailformspko = {
        kadar: $('#kadar').val(),
        rph: $('#rph').val()
    }
    console.log(detailformspko);

    var itemdcs = [];
    $('.itemdrc:checked').each(function(i, e) {
        let id = $(this).val();
        var Rph = $(this).attr("data-Rph");
        var Spk = $(this).attr("data-SPK");
        var Product = $(this).attr("data-product");
        var Qty = $(this).attr("data-Qty");
        var WaxOrder = $(this).attr("data-waxorder");
        var WaxOrderOrd = $(this).attr("data-waxorderord");
        var IDm = $(this).attr("data-IDM");
        var OrdinalWOI = $(this).attr("data-ordinalWOI");
        var QtySatuPohon = $(this).attr("data-QtySatuPohon");
        // var worklistord = $(this).attr("data-worklistord");
        let dataitems = {
            id: id,
            Rph: Rph,
            Spk: Spk,
            Product: Product,
            Qty: Qty,
            WaxOrder: WaxOrder,
            WaxOrderOrd: WaxOrderOrd,
            IDm: IDm,
            OrdinalWOI: OrdinalWOI,
            QtySatuPohon: QtySatuPohon
            // worklistord: worklistord
        }
        itemdcs.push(dataitems);
    });
    // alert(itemdcs);
    console.log(itemdcs);

    var datal = {
        itemdcs: itemdcs
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: '/Produksi/Lilin/OrderTambahanLilin/SPK3DP',
        data: datal,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'SPK3DP Berhasil Dibuat!',
                text: 'Silahkan di cetak'
            }).then((result) => {
                if (result.isConfirmed) {

                    $('#hiddenid3dp').val(data.ID3Dp);

                    printSPK3DP();
                }
            });
        },
        error: function(data) {
            Swal.fire({
                icon: 'error',
                title: 'Upss Error !',
                text: 'Gagal Membuat SPK !',
                confirmButtonColor: "#913030"
            })
            console.log('Error:', data);
        }
    });
}

function Klik_Simpan1() {

    var chekpiring = $('#IdPiring').val();
    //value form
    var detail = {
        idspk: $('#IDSPKINJECT').val(),
        Operator: $('#IdOperator').val(),
        Kadar: $('#KadarID').val(),
        Piring: $('#IdPiring').val(),
        Date: $('#date').val(),
        TotalQty: $('#FormTotalQty').val(),
        Kelompok: $('#kelompok').val(),
        Kotak: $('#kotak').val(),
        RphLilin: $('#rphlilin').val(),
        Stickpohon: $('#stickpohon').val(),
        Catatan: $('#catatan').val()
    }

    if (chekpiring == "" || chekpiring == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Piring belum diisi",
        })
        return;
    }

    //value checkbox
    var idcheck = [];
    $('.form-check-input:checked').each(function(i, e) {
        let id = $(this).val();

        idcheck.push(id);
    });
    // alert(idcheck);

    //value data product
    let WorkOrders = $('.WorkOrder');
    let Products = $('.Product');
    let Qtys = $('.Qty');
    let Injects = $('.Inject');
    let Toks = $('.Tok');
    let Rubbercarats = $('.Rc');
    let Scs = $('.Sc');
    let waxorders = $('.waxorder');
    let waxorderords = $('.waxorderord');
    let Rphs = $('.Rph');
    let Ordinals = $('.Ordinal');
    let IDWorkOrders = $('.IDWorkOrder');
    // let productparts = $('.productpart')

    var items = [];
    for (let i = 0; i < WorkOrders.length; i++) {
        var WorkOrder = $(WorkOrders[i]).val()
        var Product = $(Products[i]).val()
        var Qty = $(Qtys[i]).val()
        var Inject = $(Injects[i]).val()
        var Tok = $(Toks[i]).val()
        var Rubbercarat = $(Rubbercarats[i]).val()
        var Sc = $(Scs[i]).val()
        var waxorder = $(waxorders[i]).val()
        var waxorderord = $(waxorderords[i]).val()
        var Rph = $(Rphs[i]).val()
        var Ordinal = $(Ordinals[i]).val()
        var IDWorkOrder = $(IDWorkOrders[i]).val()
        // var productpart = $(productparts[i]).val()

        let dataitems = {
            WorkOrder: WorkOrder,
            Product: Product,
            Qty: Qty,
            Inject: Inject,
            Tok: Tok,
            Rubbercarat: Rubbercarat,
            Sc: Sc,
            waxorder: waxorder,
            waxorderord: waxorderord,
            Rph: Rph,
            Ordinal: Ordinal,
            IDWorkOrder: IDWorkOrder
            // productpart: productpart
        }
        items.push(dataitems);
    }

    // alert(items);

    //value idkaret
    var rubbers = [];
    $('.pilihkaret:checked').each(function(i, e) {
        let idRubber = $(this).val();
        var lokasi = $(this).attr("data-lokasi");

        let datakaret = {
            idRubber: idRubber,
            lokasi: lokasi
        }
        rubbers.push(datakaret);
    });
    //alert(rubbers);

    var datas = {
        detail: detail,
        items: items,
        rubbers: rubbers
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // /*Operator !== '' && Piring !== '' && date !== '' &&*/ TotalQty !== ''
    /*&& Kelompok !== '' && Kotak !== '' &&
           stickpohon !== ''*/

    // if (detail - > ['Operator'] !== '') {
    $.ajax({
        type: "POST",
        url: '/Produksi/Lilin/OrderTambahanLilin/save',
        data: datas,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'Simpan Berhasil!',
                text: 'Silahkan di cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#IDSPKINJECT').val(data.ID2);
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

function ChangeCari() {
    let IDWaxInject = $('#cari').val(); //Ambil value form cari

    $('#Baru1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', false);
    $('#Ubah1').prop('disabled', false);
    $("#tampil1").removeClass('form');
    $("#tampil2").removeClass('d-none');

    $.get('/Produksi/Lilin/OrderTambahanLilin/show/' + IDWaxInject, function(data) {
        $("#tampil1").html(data);
        item();
    });

}

function Klik_Cetak1() {
    let IDWaxInject = $('#IDSPKINJECT').val();

    if (IDWaxInject !== '') {
        window.open('/Produksi/Lilin/OrderTambahanLilin/PrintSPKO/' + IDWaxInject + '/_blank');
    }
}

function printbarkode() {

    let IDWaxInject = $('#IDSPKINJECT').val();

    if (IDWaxInject !== '') {
        window.open('/Produksi/Lilin/OrderTambahanLilin/PrintBarkode/' + IDWaxInject + '/_blank');
        onsole.log(id);
        printPDF(id);
    }
}


function KlickDaftarProductPohonan() {
    let SWWorkOrderDC = $('#SWWorkOrderDC').val(); //Ambil value rph

    if (SWWorkOrderDC !== '') {
        $.ajax({
            type: "GET",
            url: '/Produksi/Lilin/OrderTambahanLilin/ProdukListPohonan/' + SWWorkOrderDC,
            beforeSend: function() {
                $(".preloader").show();
            },
            complete: function() {
                $(".preloader").fadeOut();
            },
            success: function(data) {
                $("#modal1").html(data);
                $('#modalproduk').modal('show');
                $('#Kadar').val(data.carat);
                $('#rphlilin').val(data.id);
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'No. SPK Tidak ditemukan!',
                    text: 'Harap Check Kembali',
                    confirmButtonColor: "#913030"
                })
                console.log('Error:', data);
            }
        });

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Harap Isi Form',
            text: 'SPK ORDER terlebih dahulu',
        })
        return
    }
}

function ProsesdataPohonan() {
    totalQty = $('#totalQtyOrderItemInject').val();
    kadaritem = $('#kadaritem').val();
    rphitem = $('#rphitem').val();
    KadarID = $('#KadarID').val();
    let SWWorkOrder = $('#SWWorkOrderDC').val(); //Ambil value rph

    console.log(totalQty + ',' + SWWorkOrder + ',' + kadaritem + ',' + KadarID + ',' + rphitem);

    var XIOrdinal = [];
    $('.daftarproduk:checked').each(function(i, e) {
        let id = $(this).val();

        XIOrdinal.push(id);
    });
    // alert(items);
    console.log(XIOrdinal);
    $('#FormTotalQty').val(totalQty);
    $('#KadarPohonan').val(kadaritem);
    $('#RphLilinPohonan').val(rphitem);
    $('#KadarIdPohonan').val(KadarID);
    $('#Simpan1').prop('disabled', false);
    $("#ShowTabelItemPohonan").removeClass('d-nonePohonan');

    $.get('/Produksi/Lilin/OrderTambahanLilin/ItemSPKPohonan/' + XIOrdinal + '/' + SWWorkOrder, function(data) {
        $("#ShowTabelItemPohonan").html(data);
        $('#modalproduk').modal('hide');
    });
}


//////////////////////////////////////////////////////////////////// DC ///////////////////////////////////////////////////////////////////////////////

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
            url: '/Produksi/Lilin/OrderTambahanLilin/dapattm3dp/' + IdTmResinSudahDiposting,
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
    kdrspkpohon = $('#CaratFormLengkapSPKPohon').val();
    rphspkpohon = $('#RPHFormLengkapSPKPohonan').val();
    workorder = $('#workorder').val();

    console.log(kdrspkpohon + '/' + rphspkpohon + '/' + workorder);

    $('#Simpan1').prop('disabled', false);
    $("#showtabelspkpohon").removeClass('showtabel3');

    $.get('/Produksi/Lilin/OrderTambahanLilin/tambahdataSPKPohonan/' + workorder + '/' + kdrspkpohon + '/' +
        rphspkpohon,
        function(
            data) {
            $("#showtabelspkpohon").html(data);
        });
}




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function kadardanrph() {

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

function permintaan3dp() {
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('hidden', true);
    $('#permintaan3dp').prop('hidden', false);
    $('#permintaan3dp').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#Cetak1').prop('hidden', true);
    $('#Cetak2').prop('hidden', false);

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
    var ajaxurl = '/Produksi/Lilin/OrderTambahanLilin/Requesti';
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

function remove(id) {
    document.getElementById("tabel1").deleteRow(id);
}

function KlickDaftarKomponen() {
    $("#showtabel").removeClass('d-none');

    var carat = $('#kadar').val();
    var idtm = $('#idtm').val();

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/OrderTambahanLilin/add/' + carat + '/' + idtm,
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



function cetakformpermintaan() {

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

    $.get('/Produksi/Lilin/OrderTambahanLilin/itemproductDC/' + items + '/' + kdr + '/' + rph, function(data) {
        $("#showtabel1").html(data);
        $('#modalproduk').modal('hide');
    });
}

function printSPK3DP() {
    // alert('request 3dp');

    let IDSPK3Dp = $('#idspk3dp').val();
    // con(IDSPK3Dp);

    if (IDSPK3Dp !== '') {
        window.open('/Produksi/Lilin/OrderTambahanLilin/PrintSPK3Dp/' + IDSPK3Dp + '/_blank');
    }
}
</script>
{{-- @extends('layouts.backend-Theme-3.XtraScript') --}}
@endsection
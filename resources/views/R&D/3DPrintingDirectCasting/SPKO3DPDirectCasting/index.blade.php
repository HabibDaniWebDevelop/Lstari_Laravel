<?php $title = 'SPKO 3DP Direct Casting'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">R & D </li>
    <li class="breadcrumb-item">3DP Direct Casting </li>
    <li class="breadcrumb-item active">SPKO 3DP Direct Casting </li>
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
            @include('R&D.3DPrintingDirectCasting.SPKO3DPDirectCasting.data')
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.buttons.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/jszip.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/pdfmake.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/vfs_fonts.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.html5.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.print.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.fixedColumns.min.js') !!}"></script>



<script>
// function openModal(){
//     $(".preloader").fadeIn(300);
// }
// function closeModal(){
//     $(".preloader").fadeOut(300);
// }
function Klik_Baru1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $('#employee').prop('disabled', false);
    $('#tgl').prop('disabled', false);
    $('#sw').prop('disabled', false);
    $('#note').prop('disabled', false);
    $("#tampil").removeClass('d-none');

    $.get('/R&D/3DPrintingDirectCasting/SPKO3DPDirectCasting/show/2/0', function(data) {
        $("#tampil").html(data);
        // var collapsedGroups = {};
        var table = $('#tampiltabel').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true,
            "autoWidth": true,
            "responsive": true,
            // rowGroup: {
            //     // Uses the 'row group' plugin
            //     dataSrc: 6,
            //     startRender: function(rows, group) {
            //         //console.log(group);
            //         var collapsed = !!collapsedGroups[group];
            //         rows.nodes().each(function(r) {
            //             r.style.display = collapsed ? '' : 'none';
            //         });
            //         // Add category name to the <tr>. NOTE: Hardcoded colspan
            //         return $('<tr/>')
            //             .append('<td colspan="8"><b>Produk : </b>' + group + ' (' + rows.count() +
            //                 ')</td>')
            //             .attr('data-name', group)
            //             .toggleClass('collapsed', collapsed);
            //     }
            // }
        });
        // $('#tampiltabel tbody').on('click', 'tr.dtrg-group', function() {
        //     //console.log('ikkk');
        //     var name = $(this).data('name');
        //     collapsedGroups[name] = !collapsedGroups[name];
        //     table.draw(false);
        // });
    });

}

// function Klik_Ubah1() {
//         $('#Baru1').prop('disabled', true);
//         $('#Ubah1').prop('disabled', true);
//         $('#Batal1').prop('disabled', false);
//         $('#Simpan1').prop('disabled', false);

//         var id = $('#cari').val();
//         $.get('/forms_basic/isi/3/' + id, function(data) {
//             $("#tampil").html(data);
//         });
//     }

function Klik_Batal1() {
    location.reload();
}

function Klik_Simpan1() {

    var detail = {
        employee: $("#employee option:selected").val(),
        tgl: $('#tgl').val(),
        note: $('#note').val()
    }

    var item = [];
    $('.form-check-input:checked').each(function(i, e) {
        var id = $(this).val();
        var product = $(this).attr("data-product");
        var qty = $(this).attr("data-qty");
        var id3d = $(this).attr("data-id3d");
        var worklistid = $(this).attr("data-worklistid");
        var worklistidord = $(this).attr("data-worklistidord");

        let dataitems = {
            id: id,
            product: product,
            qty: qty,
            id3d: id3d,
            worklistid: worklistid,
            worklistidord: worklistidord

        }
        item.push(dataitems);
    });
    var data = {
        detail: detail,
        item: item
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var type = "POST";
    var ajaxurl = '/R&D/3DPrintingDirectCasting/SPKO3DPDirectCasting/saveAllocation';
    // alert(formData);
    console.log(data);
    $.ajax({
        type: type,
        url: ajaxurl,
        data: data,
        dataType: 'json',
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'Tambah Berhasil!',
                text: 'Silahkan di cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    // $('#form1').trigger("reset");
                    // $("#tampil").html('');
                    $('#Batal1').prop('disabled', true);
                    $('#Simpan1').prop('disabled', true);
                    $('#Cetak1').prop('disabled', false);
                    $('#Baru1').prop('disabled', false);
                    $('#sw').val(data.sw)
                    $('#cari').val(data.id)
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

function ChangeCari(id) {
    $('#Cetak1').prop('disabled', false);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);
    $("#tampil").removeClass('d-none');
    if (id == '0') {
        id = $('#cari').val();
    }
    $.get('/R&D/3DPrintingDirectCasting/SPKO3DPDirectCasting/see/' + id, function(data) {
        $("#tampil").html(data);
        $('#Cetak1').val(id);
        var collapsedGroups = {};
        var table = $('#tampiltabel2').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true,
            "autoWidth": true,
            "responsive": true,
            rowGroup: {
                // Uses the 'row group' plugin
                dataSrc: 2,
                startRender: function(rows, group) {
                    //console.log(group);
                    var collapsed = !!collapsedGroups[group];
                    rows.nodes().each(function(r) {
                        r.style.display = collapsed ? '' : 'none';
                    });
                    // Add category name to the <tr>. NOTE: Hardcoded colspan
                    return $('<tr/>')
                        .append('<td colspan="8"><b>Produk : </b>' + group + ' (' + rows.count() +
                            ')</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
                }
            }
        });
        $('#tampiltabel2 tbody').on('click', 'tr.dtrg-group', function() {
            //console.log('ikkk');
            var name = $(this).data('name');
            collapsedGroups[name] = !collapsedGroups[name];
            table.draw(false);
        });
    });
}

function Klik_Cetak1() {
    id = $('#cari').val();
    window.open('/R&D/3DPrintingDirectCasting/SPKO3DPDirectCasting/cetak?id=' + id, '_blank');
}
</script>
@endsection
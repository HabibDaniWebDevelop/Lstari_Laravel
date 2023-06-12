<!-- <div class="col-xl-12 mb-3 pt-4">
    <form id="form1" action="/Produksi/Lilin/SPKPohonan/simpan" method="post">
        @csrf
        <div class="table-responsive text-nowrap px-1">
            <table class="table table-border table-hover table-sm" id="tabeldaftarproduk">
                <thead class="table-secondary sticky-top zindex-2" style="center">
                    <tr style="text-align: center">
                        <th>PILL</th>
                        <th>Kode Produk</th>
                        <th>Description</th>
                        <th>SKU</th>
                        <th>Kadar</th>
                        <th>SW</th>
                    </tr>
                </thead>
                <tfoot>
                    <hr>
                </tfoot>
                {{-- {{ dd($DaftarProduct); }} --}}
                <tbody>
                    @forelse ($datas as $data1)
                    <tr class="klik2" id="{{ $data1->IDtrans }}" style="text-align: center">
                        <td>
                            {{-- <input class="quiz_checkbox" type="checkbox" value="{{$Waxori->WorkOrder}}"
                            name="product[]"
                            checked="checked" /> --}}
                            <input class="form-check-input" type="checkbox" name="id[]" id="cek_{{ $data1->IDtrans }}"
                                value="{{$data1->IDtrans}}" />
                        </td>
                        <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data1->cm }}</span> </td>
                        <td>{{ $data1->mname }}</td>
                        <td>{{ $data1->skus }}</td>
                        <td>{{ $data1->kadar }}</td>
                        <td>{{ $data1->MM }}</td>
                    </tr>
                    @empty
                    <div class="alert alert-danger">
                        Data Blog belum Tersedia.
                    </div>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end px-4">
             <button type="submit" class="btn btn-primary mb-2">Simpan</button>
            <button type="button" class="btn btn-primary mb-2" onclick="getvalueform()">tes</button>
            <button type="button" class="btn btn-primary mb-2" onclick="Prosesdata()">tes1</button>
            <button type="button" class="btn btn-outline-secondary mb-2" onclick="closed()">
                Close
            </button> -->
</div>
</form>
</div>
<script>
$(".klik2").on('click', function(e) {
    // $('.klik').css('background-color', 'white');
    var id = $(this).attr('id');
    if ($(this).hasClass('table-secondary')) {
        $(this).removeClass('table-secondary');
        $('#cek_' + id).attr('checked', false);
    } else {
        $(this).addClass('table-secondary');
        $('#cek_' + id).attr('checked', true);
    }
    return false;
});
</script>























<!-- ---------------------------------------------------------------------------------------------------------------- INDEX --------------------------------------------------------------------------------------- -->
<?php $title = 'Surat Perintah Kerja Pohonan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
<h2 class="m-0">{{ $title }}</h2>
<ol class="breadcrumb sm-2">
    <li class="breadcrumb-item"><a href="/">Home </a></li>
    <li class="breadcrumb-item">Produksi </li>
    <li class="breadcrumb-item">Lilin </li>
    <li class="breadcrumb-item active">SPK Pohonan</li>
</ol>
@endsection

@section('container')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-body"> -->

            <div id="tabellaci" class="col-md-12">
                @include('Produksi.Lilin.SPKPohonan.data')
            </div>
            <!-- </div> -->

        </div>
        @include('Setting.publick_function.ViewSelectionModal')
    </div>
</div>

@endsection

@section('script')
@include('layouts.backend-Theme-3.DataTabelButton')
<script>
// ---------------------------------------------------------------------------------------------------------------- atribut listTabel
var table = $('#tabel1').DataTable({
    "paging": false,
    "lengthChange": false,
    // "pageLength": 9,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,

});
// ---------------------------------------------------------------------------------------------------------------- atribut tabel Item
var table = $('#tabel2').DataTable({
    "paging": true,
    "lengthChange": false,
    "pageLength": 9,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,
});


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

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/SPKPohonan/add/2/0',
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tampil").html(data);
        }
    });
}


// ---------------------------------------------- chek box example ------------------------------------------- get all value in materialized -----------------------------------------------------------------------------
function getvalueform() {
    var formData = $('#form1').serialize();
    alert(formData);

    $.ajax({
        type: "GET",
        url: '/Produksi/Lilin/SPKPohonan/Simpan' + formData,
        beforeSend: function() {
            $(".preloader").show();
        },
        complete: function() {
            $(".preloader").fadeOut();
        },
        success: function(data) {
            $("#tampil2").html(data);
        }
    });
}

// ---------------------------------------------- chek box example ------------------------------------------- get in Jquery ------------------------------------------------------------------------------
function Prosesdata() {

    var testval = [];
    $('.form-check-input:checked').each(function() {
        testval.push($(this).val());
    });
    alert(testval);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: '/Produksi/Lilin/SPKPohonan/Simpan',
        data: testval,
        // beforeSend: function() {
        //     $(".preloader").show();
        // },
        // complete: function() {
        //     $(".preloader").fadeOut();
        // },
        success: function(data) {
            // $("#tampil2").html(data);
            console.log(data);
        }
    });
}

function ChangeCari() {
    let IDMWaxOrderItem = $('#cari').val(); //Ambil value lemari

    $('#Baru1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Cetak1').prop('disabled', false);
    $("#tampil").removeClass('d-none');

    $.get('/Produksi/Lilin/SPKPohonan/TabelItem/' + IDMWaxOrderItem, function(data) {
        $("#tampil").html(data);
    });

}

let form = document.querySelector("form1");
form.addEventListener("submit", (event) => {
    let submitter = event.submitter;
    let handler = submitter.id;

    if (handler) {
        processOrder(form, handler);
    } else {
        showAlertMessage("An unknown or unaccepted payment type was selected. Please try again.", "OK");
    }
});



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

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------- simpan
function Klik_Simpan1() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var formData = $('#form1').serialize();
    alert(formData);
    var type = "POST";
    var ajaxurl = '/Produksi/Lilin/SPKPohonan/Save';
    // alert(formData);

    $.ajax({
        type: type,
        url: ajaxurl,
        data: formData,
        dataType: 'json',
        success: function(data) {

            Swal.fire({
                icon: 'success',
                title: 'Tambah Berhasil!',
                text: 'Silahkan di cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form1').trigger("reset");
                    $("#tampil").html('');
                    $('#Batal1').prop('disabled', true);
                    $('#Simpan1').prop('disabled', true);
                    $('#Cetak1').prop('disabled', false);
                    $('#Baru1').prop('disabled', false);
                }
            });

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
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------- cetak
function Klik_Cetak1() {
    let IDWaxInject = $('#cari').val();
    alert(IDWaxInject);

    if (IDWaxInject !== '') {
        window.open('/Produksi/Lilin/SPKPohonan/PrintSPK/' + IDWaxInject + '/_blank');
    }
}
</script>
@extends('layouts.backend-Theme-3.XtraScript')
@endsection -->
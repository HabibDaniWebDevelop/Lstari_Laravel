<?php $title = 'Label Barcode PCB'; ?>
<?php
if (Auth::check()) {
    $app = 'app';
} else {
    $app = 'app2';
}
?>
@extends('layouts.backend-Theme-3.' . $app)
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-lain </li>
        <li class="breadcrumb-item active">LabelBarcodePCB</li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Lain-Lain.QRCode.LabelBarcodePCB.data')

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>

@endsection

@section('script')

    <script>
        Klik_detail();

        function Klik_Batal1() {
            location.reload();
        }

        function Klik_detail() {
            $('#generate').val('detail');

            $.get('/Lain-lain/QRCode/LabelBarcodePCB/generate/1/0', function(data) {
                $("#tampil").html(data);
            });
        }

        function Klik_sku() {
            $('#generate').val('sku');
            var id = $('#generate').val();

            $.get('/Lain-lain/QRCode/LabelBarcodePCB/generate/2/0', function(data) {
                $("#tampil").html(data);
            });
        }

        //barcode
        function Klik_generate() {

            var menu = $('#generate').val();
            // alert(id);
            if (menu == 'sku') {
                var id = $('#inputsku').val();
                window.open('/Lain-lain/QRCode/LabelBarcodePCB/generate/3/' + id, '_blank');
            } else if (menu == 'detail') {
                var formData = $('#form1').serialize();
                console.log(formData);
                var id = $('#inputsku').val();
                window.open('/Lain-lain/QRCode/LabelBarcodePCB/generate/4/' + formData, '_blank');
            }

        }

        // // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

        function add(id) {
            let rowCount = $('#tabel1 tbody tr').length;
            rowCount += 1;

            // Setup table row
            let trStart = '<tr class="baris" id="' + rowCount + '">';
            let cell1 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly name="no[]" value="' +
                rowCount + '" data-index="' + rowCount + '1"> </td>';
            let cell2 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="Model[]" id="' +
                rowCount + '2" value="" data-index="' +
                rowCount + '2" onchange="getdatacari(event, ' + rowCount + ')" onkeyup="this.value = this.value.toUpperCase()"> </td>';
            let cell3 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="Kadar[]" data-index="' +
                rowCount + '3" onkeyup="this.value = this.value.toUpperCase()"> </td>';
            let cell4 =
                '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="Berat[]" value="" data-index="' +
                rowCount + '4"> </td>';
            let cell5 =
                '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100" name="Ring[]" value="" data-index="' +
                rowCount + '5"></td>';
            let cell6 =
                '<td class="m-0 p-0"> <select class="form-select form-select-sm fs-6 w-100" name="Bulan[]" data-index="' +
                rowCount +
                '6" onkeydown="handler(event)" posisi-index="akhir"><option value=""></option> <option value="Jan">Jan</option> <option value="Feb">Feb</option> <option value="Mar">Mar</option> <option value="Apr">Apr</option> <option value="May">May</option> <option value="Jun">Jun</option> <option value="Jul">Jul</option> <option value="Aug">Aug</option> <option value="Sep">Sep</option> <option value="Oct">Oct</option> <option value="Nov">Nov</option> <option value="Dec">Dec</option> <option value="13">13</option> </select> <input type="hidden" name="barcode[]" value="" data-index="' +
                rowCount + '7">';
            let trEnd = '</tr>';
            let finalItem = "";
            let rowitem = finalItem.concat(trStart, cell1, cell2, cell3, cell4, cell5, cell6, trEnd);
            $("#tabel1 > tbody").append(rowitem);

            $posisi = "#tabel1 #" + rowCount + " input";
            $($posisi).on('contextmenu', function(e) {
                rightclik(this, e);
            });

            $($posisi).keydown(function(e) {
                var id = $(this).parent().parent().attr('id');
                // alert(id);
                tambahbaris(id);
                fungsiautocom(id + '2');
            });

        }

        //produk
        function getdatacari(event, row) {

            var id = $('[data-index="' + row + '2"]').val();

            if (id != '') {

                $.get('/Lain-lain/QRCode/LabelBarcodePCB/generate/5/' + id, function(data) {

                    console.log(data);

                    if (data.success) {
                        $('[data-index="' + row + '7"]').val(data.barcode);
                        $('[data-index="' + row + '2"]').val(data.SW);
                        $('[data-index="' + row + '3"]').val(data.Carat);
                        $('[data-index="' + row + '4"]').val(data.berat);
                        $('[data-index="' + row + '5"]').val(data.ukuran);
                        $('[data-index="' + row + '6"]').val(data.bulan);
                        $('[data-index="' + row + '3"]').focus();
                    } else {

                        $('[data-index="' + row + '2"]').val('');
                        $('[data-index="' + row + '2"]').focus();
                        return;
                    }
                });
            }
        }

        //digunakan ketika select option tidak bisa berpindah focuss
        function handler(event) {

            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));

            if (event.keyCode === 39) {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 37) {
                $('[data-index="' + (index - 1).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 13) {
                add(index);
                $('[data-index="' + (index + 6).toString() + '"]').focus();

            }
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
                $('[data-index="' + Index + '1"]').attr('data-index', newIndex + '1');
                $('[data-index="' + Index + '2"]').attr('data-index', newIndex + '2');
                $('[data-index="' + Index + '3"]').attr('data-index', newIndex + '3');
                $('[data-index="' + Index + '4"]').attr('data-index', newIndex + '4');
                $('[data-index="' + Index + '5"]').attr('data-index', newIndex + '5');
                $('[data-index="' + Index + '6"]').attr('data-index', newIndex + '6');

                $(elem).find('.satuan').attr('id', "satuan_" + newIndex);

            })
        }
    </script>
@endsection

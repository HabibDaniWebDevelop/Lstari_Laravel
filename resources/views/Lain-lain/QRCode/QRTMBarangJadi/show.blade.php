@foreach ($datas as $data)
    @if ($loop->iteration == 1)
        <div class="row">
            <div class="col-md-6">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped table-sm">
                        <thead class="table-secondary sticky-top zindex-2">
                            <tr style="text-align: center;">
                                {{-- <th width='5%'>Urut</th> --}}
                                <th>cetak</th>
                                <th>Gambar</th>
                                <th>No Model</th>
                                <th>Kadar</th>
                                <th>Berat</th>
                                <th>Ring</th>
                                <th>Bulan STP</th>

                            </tr>
                        </thead>
                        <tbody>
                        @elseif ($loop->iteration == $rowcount + 1)
                            <div class="col-md-6">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-striped table-sm">
                                        <thead class="table-secondary sticky-top zindex-2">
                                            <tr style="text-align: center">
                                                {{-- <th width='5%'>Urut</th> --}}
                                                <th>cetak</th>
                                                <th>Gambar</th>
                                                <th>No Model</th>
                                                <th>Kadar</th>
                                                <th>Berat</th>
                                                <th>Ring</th>
                                                <th>Bulan STP</th>

                                            </tr>
                                        </thead>
                                        <tbody>
    @endif

    @php
        $get = substr($data->SW, 0, 10);
        
        if (preg_match('/-/i', $get)) {
            $str_arr = explode('-', $data->SW);
            $sw = $str_arr[0] . '.' . $str_arr[1];
        } else {
            $str_arr = explode('.', $data->SW);
            $sw = $str_arr[0] . '.' . $str_arr[1];
        }
        
        if ($data->SKU != '') {
            $barcode = $data->SKU;
        } else {
            $barcode = $data->SW;
        }

        if ($data->ukuran == '000') {
            $data->ukuran = '';
        }
        
    @endphp

    <tr class="baris" id="{{ $loop->iteration }}" height="92">

        <input type="hidden" name="SW[]" value="{{ $barcode }}" data-index="{{ $loop->iteration }}1">
        <td align="center">
            <input class="form-check-input" type="checkbox" autocomplete="off" name="id[]"
                id="cek_{{ $loop->iteration }}" value="{{ $loop->index }}" />
        </td>
        <td>
            <img src="{{ Session::get('hostfoto') }}/image/{{ $data->Photo }}.jpg" class="img-fluid"
                style="max-height: 80px; max-width: 120px;"
                onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
        </td>
        <td class="m-0
                p-0">
            <input type="text" class="form-control form-control-sm fs-6 w-100" name="Model[]"
                value="{{ $sw }}" data-index="{{ $loop->iteration }}2">
        </td>
        <td class="m-0 p-0">
            <input type="text" class="form-control form-control-sm fs-6 w-100" name="Kadar[]"
                value="{{ $data->Carat }}" data-index="{{ $loop->iteration }}3" onkeyup="this.value = this.value.toUpperCase()">
        </td>
        <td class="m-0 p-0">
            <input type="text" class="form-control form-control-sm fs-6 w-100" name="Berat[]"
                value="{{ $data->berat }}" data-index="{{ $loop->iteration }}4">
        </td>
        <td class="m-0 p-0">
            <input type="text" class="form-control form-control-sm fs-6 w-100" name="Ring[]"
                value="{{ $data->ukuran }}" data-index="{{ $loop->iteration }}5">
        </td>
        <td class="m-0 p-0">
            <select class="form-select form-select-sm fs-6 w-100" name="Bulan[]" data-index="{{ $loop->iteration }}6"
                posisi-index="akhir" onkeydown="handler(event)">
                <option value=""></option>
                <option value="Jan" {!! $data->bulan == 'Jan' ? 'selected' : '' !!}>Jan</option>
                <option value="Feb" {!! $data->bulan == 'Feb' ? 'selected' : '' !!}>Feb</option>
                <option value="Mar" {!! $data->bulan == 'Mar' ? 'selected' : '' !!}>Mar</option>
                <option value="Apr" {!! $data->bulan == 'Apr' ? 'selected' : '' !!}>Apr</option>
                <option value="May" {!! $data->bulan == 'May' ? 'selected' : '' !!}>May</option>
                <option value="Jun" {!! $data->bulan == 'Jun' ? 'selected' : '' !!}>Jun</option>
                <option value="Jul" {!! $data->bulan == 'Jul' ? 'selected' : '' !!}>Jul</option>
                <option value="Aug" {!! $data->bulan == 'Aug' ? 'selected' : '' !!}>Aug</option>
                <option value="Sep" {!! $data->bulan == 'Sep' ? 'selected' : '' !!}>Sep</option>
                <option value="Oct" {!! $data->bulan == 'Oct' ? 'selected' : '' !!}>Oct</option>
                <option value="Nov" {!! $data->bulan == 'Nov' ? 'selected' : '' !!}>Nov</option>
                <option value="Dec" {!! $data->bulan == 'Dec' ? 'selected' : '' !!}>Dec</option>
                <option value="13">13</option>
            </select>
        </td>
    </tr>

    @if ($loop->iteration == $rowcount)
        </tbody>
        </table>
        </div>
        </div>
    @endif
@endforeach

</tbody>
</table>
</div>
</div>

</div>
<br>
<p align="center"><b>!! Klik Gambar Untuk Memilih !! </b></p>

<script>
    // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

    $('.baris').keydown(function(e) {
        var id = $(this).attr('id');
        tambahbaris(id);
    });

    $(".baris .img-fluid").on('click', function(e) {
        var id = $(this).parent().parent().attr('id');

        if ($('#cek_' + id).prop("checked")) {
            $(this).parent().parent().removeClass('table-secondary');
            $('#cek_' + id).attr('checked', false);
        } else {
            // alert(id);
            $(this).parent().parent().addClass('table-secondary');
            $('#cek_' + id).attr('checked', true);
        }
        return false;
    });

    function tambahbaris(id) {
        var id = parseFloat(id);
        var $this = $(event.target);
        var index = parseFloat($this.attr('data-index'));
        var pos_index = $this.attr('posisi-index');

        //enter dan panah kanan
        if (event.keyCode === 13 || event.keyCode === 39) {
            // alert(index + ' | ' + id + ' | ' + pos_index);

            if (pos_index == 'akhir') {
                posisi = id + 1;
                var table = document.getElementById("tabel1");
                rowCount = table.rows.length;
                // add();
                if (posisi == rowCount) {
                    add(id);
                }
                $('[data-index="' + (id + 1).toString() + '2"]').focus();
            } else {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
            }
        }

        //panah kanan
        if (event.keyCode === 39) {
            event.preventDefault();
        }

        // panah atas
        if (event.keyCode === 38) {
            arah = index - 10;
            $('[data-index="' + (arah) + '"]').focus();
            event.preventDefault();
        }

        //panah bawah
        if (event.keyCode === 40) {

            rowCount = $('#tabel1 tbody tr').length;
            // console.log(table, rowCount);
            // alert(rowCount + ' ' + id);
            if (id == rowCount) {
                add(id);
                $('[data-index="' + (id + 1).toString() + '2"]').focus();
            } else {
                arah = index + 10;
                $('[data-index="' + (arah) + '"]').focus();
            }
            event.preventDefault();
        }

        // panah kiri
        if (event.keyCode === 37) {
            if (index % 10 == 2) {
                $('[data-index="' + (id - 1) + '9"]').focus();
            } else {
                $('[data-index="' + (index - 1) + '"]').focus();
            }
            event.preventDefault();
        }

        // panah ctrl + Delet
        if (event.ctrlKey && event.keyCode === 46) {
            klikhapus(id);
            $('[data-index="' + (index) + '"]').focus();
            // alert(id);
        }

        // event.preventDefault();
    }

    // ----------------------- fungsi klik kanan sub menu -----------------------
    $(".baris input").on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $("#tabel1").bind("contextmenu", function(e) {
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
</script>

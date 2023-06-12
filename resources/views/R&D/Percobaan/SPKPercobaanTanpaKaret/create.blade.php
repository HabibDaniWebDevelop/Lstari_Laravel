<div class="row my-4">

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">No. Form Kerja PCB</label>
            <input type="text" class="form-control" name="NoForm" readonly value="">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tgl_masuk" readonly value="{{ date('Y-m-d') }}">
        </div>
    </div>

</div>

<div class="text-nowrap w-px-500 mb-4">

    <table class="table table-bordered table-sm" id="tabel000">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th colspan="3">Pilih Kadar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                @if ($loop->iteration % 3 == '1')
                    <tr>
                @endif
                <th class="fs-6">
                    <input type="checkbox" class="form-check-input" name="idKadar[]" id="car_{{ $data->ID }}"
                        value="{{ $data->ID }}" data-sku='{{ $data->SKU }}' />
                    <label for="car_{{ $data->ID }}"> {{ $data->SKU }}{{ $data->Alloy }} </label>
                </th>
                @if ($loop->iteration % 3 == '0')
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

<div class="text-nowrap">

    <table class="table table-borderless table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th width='5%'>No</th>
                <th width='15%'>Kategori</th>
                <th width='15%'>SKU/Produk ID</th>
                <th width='5%'>Qty</th>
                <th width='8%'>Berat Bahan</th>
                <th width=''>kepala</th>
                <th width=''>component</th>
                <th width=''>mainan</th>
            </tr>
        </thead>

        <tbody>

            <tr class="baris" id="1">
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly
                        value="1" data-index="11">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly
                        value="" data-index="12">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="SWProduk[]" value="" data-index="13" onkeydown="getproduct(event, 1)">
                    <input type="hidden" name="idprod[]" data-index="17">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="Qty[]" value="" data-index="14">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="Berat[]" value="" data-index="15" posisi-index="akhir" onchange="gettotal()" >
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index="16">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index="18" >
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="" data-index="19" >
                </td>
            </tr>

        </tbody>

        <tfoot>
            <tr>
                <td class="m-0 p-0 text-end" colspan="4">
                    {{-- <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="Total :"> --}}
                    <b> Total :&nbsp;</b>
                </td>
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="totalQty" id="totalQty" readonly>
                </td>
            </tr>
        </tfoot>

    </table>
</div>

<script>
    // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

    $('.baris').keydown(function(e) {
        var id = $(this).attr('id');
        tambahbaris(id);
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
                posisi = id;
                rowCount = $('#tabel1 tbody tr').length;
                // alert(posisi, rowCount)
                if (posisi == rowCount) {
                    add();
                }
                $('[data-index="' + (id + 1).toString() + '3"]').focus();
            } else {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
            }
        }

        // panah atas
        if (event.keyCode === 38) {
            arah = index - 10;
            $('[data-index="' + (arah) + '"]').focus();
        }

        //panah bawah
        if (event.keyCode === 40) {
            rowCount = $('#tabel1 tbody tr').length;
            if (id == rowCount) {
                add();
                $('[data-index="' + (id + 1).toString() + '3"]').focus();
            } else {
                arah = index + 10;
                $('[data-index="' + (arah) + '"]').focus();
            }
        }

        // panah kiri
        if (event.keyCode === 37) {
            if (index % 10 == 2) {
                $('[data-index="' + (id - 1) + '6"]').focus();
            } else {
                $('[data-index="' + (index - 1) + '"]').focus();
            }
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
</script>

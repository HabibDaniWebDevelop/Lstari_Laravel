<table class="table table-borderless table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr style="text-align: center">
            <th width='6%'> No </th>
            <th> SW </th>
            <th width='30%'> Description </th>
            <th> TGL TRANS </th>
            <th width='30%'> Keterangan </th>
        </tr>
    </thead>
    <tbody>
        {{-- tambah --}}
        @if ($no == '2')
            <tr class="baris" id="1">
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly
                        value="1" data-index="11">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="id[]" value="" data-index="12">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="des[]" value="" data-index="13">
                </td>
                <td class="m-0 p-0"> <input type="date" class="form-control form-control-sm fs-6 w-100"
                        name="tgl[]" value="" data-index="14">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="ket[]" value="" data-index="15" posisi-index="akhir">
                </td>
                <input type="hidden" name="id[]" value="">
            </tr>

            {{-- edit --}}
        @elseif ($no == '3')
            @foreach ($datas as $data)
                <tr class="baris" id="{{ $loop->iteration }}">
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly
                            value="{{ $loop->iteration }}" data-index="{{ $loop->iteration }}1">
                    </td>
                    <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                            name="id[]" value="{{ $data->SW }}" data-index="{{ $loop->iteration }}2">
                    </td>
                    <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                            name="des[]" value="{{ $data->Description }}" data-index="{{ $loop->iteration }}3">
                    </td>
                    <td class="m-0 p-0"> <input type="date" class="form-control form-control-sm fs-6 w-100"
                            name="tgl[]" value="{{ $data->EntryDate }}" data-index="{{ $loop->iteration }}4">
                    </td>
                    <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                            name="ket[]" value="" data-index="{{ $loop->iteration }}5" posisi-index="akhir">
                    </td>
                    <input type="hidden" name="id[]" value="{{ $data->id }}">
                </tr>
            @endforeach
        @endif

    </tbody>

</table>

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

        // panah atas
        if (event.keyCode === 38) {
            arah = index - 10;
            $('[data-index="' + (arah) + '"]').focus();
            event.preventDefault();
        }

        //panah bawah
        if (event.keyCode === 40) {
            var table = document.getElementById("tabel1");
            rowCount = table.rows.length - 1;
            // alert(rowCount + ' ' + id);
            if (id == rowCount) {
                add();
                $('[data-index="' + (id + 1).toString() + '2"]').focus();
                event.preventDefault();
            } else {
                arah = index + 10;
                $('[data-index="' + (arah) + '"]').focus();
                event.preventDefault();
            }
        }

        // panah kiri
        if (event.keyCode === 37) {
            if (index % 10 == 2) {
                $('[data-index="' + (id - 1) + '5"]').focus();
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

<div class="table-responsive text-nowrap">
    <table class="table table-borderless table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th width='5%'>Urut</th>
                <th width='30%'>No Model</th>
                <th>Kadar</th>
                <th>Berat</th>
                <th>Ring</th>
                <th>Bulan STP</th>
            </tr>
        </thead>
        <tbody>

            <tr class="baris" id="1">
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" readonly value="1"
                        name="no[]" data-index="11">
                </td>
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" id="12" name="Model[]"
                        value="" data-index="12" onchange="getdatacari(event, 1)" onkeyup="this.value = this.value.toUpperCase()">
                </td>
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="Kadar[]" value=""
                        data-index="13" onkeyup="this.value = this.value.toUpperCase()">
                </td>
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="Berat[]" value=""
                        data-index="14">
                </td>
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="Ring[]" value=""
                        data-index="15">
                </td>
                <td class="m-0 p-0">
                    <select class="form-select form-select-sm fs-6 w-100" name="Bulan[]" data-index="16"
                        posisi-index="akhir" onkeydown="handler(event)">
                        <option value=""></option>
                        <option value="Jan">Jan</option>
                        <option value="Feb">Feb</option>
                        <option value="Mar">Mar</option>
                        <option value="Apr">Apr</option>
                        <option value="May">May</option>
                        <option value="Jun">Jun</option>
                        <option value="Jul">Jul</option>
                        <option value="Aug">Aug</option>
                        <option value="Sep">Sep</option>
                        <option value="Oct">Oct</option>
                        <option value="Nov">Nov</option>
                        <option value="Dec">Dec</option>
                        <option value="13">13</option>
                    </select>
                    <input type="hidden" name="barcode[]" value="" data-index="17">
                </td>
            </tr>

        </tbody>
    </table>
</div>

<script>
    // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

    $('.baris').keydown(function(e) {
        var id = $(this).attr('id');
        tambahbaris(id);
        fungsiautocom(id + '2');

    });


    function fungsiautocom(id) {
        id = parseFloat(id);
        $("#" + id).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '/searchSKU',
                    type: 'GET',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },

            select: function(event, ui) {

                // console.log(id);
                $('#' + id).val(ui.item.label);
                $('[data-index="' + (id + 1) + '"]').focus();

                console.log(ui.item);
                return false;
            },
            open: function() {
                $(this).autocomplete('widget').css('z-index', 1100);
                return false;
            }
        });
    }


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
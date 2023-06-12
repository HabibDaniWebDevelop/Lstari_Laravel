<div class="row my-4">

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">ID</label>
            <input type="text" class="form-control" name="id_ambil" readonly value="" >
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">No Order</label>
            <input type="text" class="form-control" name="id_ambil_sw" readonly value="">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" readonly
                value="{{ date('Y-m-d') }}">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">Karyawan</label> <label class="form-label" id="karid0" ></label>
            <input type="text" class="form-control" name="karyawan" id="karyawan" value="{{ $data[0]->nama }}"
                onClick="this.select();" onChange="getkary()">
            <input type="hidden" class="form-control" id="karyawan0" value="{{ $data[0]->nama }}">
            <input type="hidden" class="form-control" id="karid" name="karid" value="{{ $data[0]->karid }}">
            <input type="hidden" class="form-control" id="username" name="username" value="{{ $data[0]->username }}">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">Bagian</label>
            <input type="text" class="form-control" name="bagian" id="bagian" readonly value="{{ $data[0]->jabatan }}">
            <input type="hidden" name="idbagian" id="idbagian" value="{{ $data[0]->idbgn }}">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">Keperluan</label>
            <select class="form-select" name="Keperluan" id="Keperluan">
                <option selected disabled value="">Pilih</option>
                <option value="IT">IT</option>
                <option value="Workshop">Workshop</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Laser">Laser</option>
                <!-- <option value="Marketing">Marketing</option> -->
                <option value="HRGA">HRGA</option>
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" name="catatan" id="catatan" value="">
        </div>
    </div>

</div>

<div class="table-responsive text-nowrap">

    <table class="table table-borderless table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th width='5%'>Urut</th>
                <th width='10%'>ID Inventaris</th>
                <th width='15%'>Barang</th>
                <th width='5%'>Jumlah</th>
                <th width='8%'>Tipe</th>
                <th width='8%'>Kategori</th>
                <th>Tgl Butuh</th>
                <th width='25%'>Deskripsi</th>
                <th>Bagian Inventaris</th>
            </tr>
        </thead>
        <tbody>

            <tr class="baris" id="1">
                <td class="m-0 p-0">
                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly
                        value="1" data-index="11">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="id_inv[]" value="" data-index="12" autocomplete="off"
                        onkeypress="return /[0-9]/i.test(event.key)" onkeydown="getdatacari(event, 1)">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="barang[]" value="" data-index="13">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="jumlah[]" value="" data-index="14">
                </td>
                <td class="m-0 p-0">
                    <select class="form-select form-select-sm fs-6 w-100" name="tipe[]" data-index="15" >
                        <option value="Service">Service</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Baru">Baru</option>
                    </select>
                </td>
                <td class="m-0 p-0">
                    <select class="form-select form-select-sm fs-6 w-100" name="kategori[]" data-index="16" onchange="gettgl(1)">
                        <option value="">Pilih Kategori</option>
                        <option value="Biasa" >Biasa</option>
                        <option value="Penting">Penting</option>
                        <option value="Darurat">Darurat</option>
                    </select>
                </td>
                <td class="m-0 p-0"> <input type="date" class="form-control form-control-sm fs-6 w-100"
                        name="tgl_butuh[]" value="" data-index="17">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="deskripsi[]" value="" data-index="18">
                </td>
                <td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100"
                        name="id_bagian_inv[]" value="" data-index="19" posisi-index="akhir">
                </td>
                {{-- <input type="hidden" name="id[]" value="{{ $data->id }}"> --}}
            </tr>

        </tbody>
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
            var table = document.getElementById("tabel1");
            rowCount = table.rows.length - 1;
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

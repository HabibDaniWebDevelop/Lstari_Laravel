<button type="submit" style="display:none" id="hidden-submit">Submit</button>

<div class="row mb-2">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">No Order</label>
            <input type="text" name="no_order" class="form-control form-control-sm fs-6" value="{{ $heads[0]->CODE }}"
                disabled="true">
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">No Urut</label>
            <input type="text" name="no_urut" id='no_urut' class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Ord }}" disabled="true">
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">Tgl SPK</label>
            <input type="date" name="tgl_spk" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->TransDate }}" disabled="true">
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">Tgl Minta</label>
            <input type="date" name="tgl_minta" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->DateNeeded }}" disabled="true">
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">barang</label>
            <input type="text" name="barang" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Product }}" disabled="true">
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label class="form-label mb-0">deskripsi</label>
            <input type="text" name="deskripsi" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Description }}" disabled="true">
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">tipe</label>
            <input type="text" name="tipe" class="form-control form-control-sm fs-6" value="{{ $heads[0]->Type }}"
                disabled="true">
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">kategori</label>
            <input type="text" name="kategori" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Category }}" disabled="true">
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">jumlah</label>
            <input type="text" name="jumlah" class="form-control form-control-sm fs-6" value="{{ $heads[0]->Qty }}"
                disabled="true">
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">karyawan</label>
            <input type="text" name="karyawan" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Employee }}" disabled="true">
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label class="form-label mb-0">bagian</label>
            <input type="text" name="bagian" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Department }}" disabled="true">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label mb-0">catatan</label>
            <input type="text" name="catatan" class="form-control form-control-sm fs-6"
                value="{{ $heads[0]->Remarks }}" disabled="true">
        </div>
    </div>
</div>

<hr>
<form id="tambah">
    <input type="hidden" name="id_nama" id="id_nama" value="{{ $id }}">
    <input type="hidden" name="no_urut1" value="{{ $heads[0]->Ord }}">
    <div class="row mb-2">
        <div class="col-3">
            <div class="form-group">
                <label class="form-label mb-0">Karyawan</label>
                <select class="form-select form-select-sm fs-6" name="karyawan_input" id="karyawan_input" required>
                    <option value="" selected disabled>Silahkan Pilih</option>
                    @foreach ($getkar as $data)
                        <option value="{{ $data->ID }}">{{ $data->Description }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label class="form-label mb-0">Tgl Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control form-control-sm fs-6"
                    value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label class="form-label mb-0">Tgl Target</label>
                <input type="date" name="tgl_target" class="form-control form-control-sm fs-6"
                    value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="col-3">
            <br>
            <button class="btn btn-primary btn-sm" type="button" onclick="Tambah()">TAMBAH</button>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <div class="form-group">
                <label class="form-label mb-0">Pekerjaan</label>
                <textarea class="form-control form-control-sm fs-6" name="pekerjaan" id="pekerjaan" style="height: 45px"></textarea>
            </div>
        </div>
    </div>
</form>
<hr>

<form id="form2">
    <input type="hidden" name="id_nama" id="id_nama" value="{{ $id }}">
    <input type="hidden" name="no_urut1" value="{{ $heads[0]->Ord }}">
    <div class="row mb-2">
        <div class="col-3">
            <div class="form-group">
                <label class="form-label mb-0">Hasil</label>
                <select class="form-select form-select-sm fs-6" name="hasil" id="hasil" required>
                    <option value="0" disabled selected>Silahkan Pilih</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dialihkan">Dialihkan</option>
                    <option value="Ditunda">Ditunda</option>
                    <option value="Check">Check</option>
                </select>
            </div>
        </div>
        <div class="col-3">
        </div>
        <div class="col-3">
            <div class="form-group">
                <label class="form-label mb-0">Tgl Selesai</label>
                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control form-control-sm fs-6"
                    value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="form-group">
                <label class="form-label mb-0">Kegiatan</label>
                <textarea class="form-control form-control-sm fs-6" name="kegiatan1" id="kegiatan1" style="height: 45px"></textarea>
            </div>
        </div>
    </div>

    <table width="100%" class="table table-bordered table-sm">
        <thead class="table-secondary sticky-top zindex-2">
            <tr>
                <th width='200'>Petugas</th>
                <th>Tgl Mulai</th>
                <th>Tgl Target</th>
                <th>Note</th>
                <th width='80'>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Worker as $data)
                <tr class="klik2" id="{{ $data->IDM }}" id2="{{ $data->Ordinal }}">
                    <td class="m-0 p-0">
                        <input type="hidden" name="Ordinal[]" value="{{ $data->Ordinal }}">
                        <select class="form-select form-select-sm fs-6 w-100" name="karyawan_input[{{ $data->Ordinal }}]" id="karyawan_input{{ $data->Ordinal }}" disabled onchange="ubah()">
                            <option value="" disabled>Silahkan Pilih</option>
                            @foreach ($getkar as $data2)
                                <option value="{{ $data2->ID }}" <?php echo $data->Employee == $data2->ID ? 'selected' : ''; ?>>{{ $data2->Description }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="m-0 p-0">
                        <input type="date" name="mulai[{{ $data->Ordinal }}]" id="mulai"
                            class="form-control form-control-sm fs-6 w-100" readonly value="{{ $data->DateStart }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="date" name="target[{{ $data->Ordinal }}]" id="target"
                            class="form-control form-control-sm fs-6 w-100" readonly value="{{ $data->DateEnd }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" name="ToDo[{{ $data->Ordinal }}]" id="ToDo"
                            class="form-control form-control-sm fs-6 w-100" readonly value="{{ $data->ToDo }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" name="Status[{{ $data->Ordinal }}]" id="Status"
                            class="form-control form-control-sm fs-6 w-100" readonly value="{{ $data->Status }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" name="Kegiatan[{{ $data->Ordinal }}]" id="Kegiatan" onchange="ubah()"
                            class="form-control form-control-sm fs-6 w-100" value="{{ $data->Remark }}">
                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>

</form>

<script>
    // -------------------- klik di tabel --------------------
    $(".klik2").on('contextmenu', function(e) {
        let id = $(this).attr('id');
        let id2 = $(this).attr('id2');

        $('#klikedit').attr('onclick', 'klikedit(' + id + ',' + id2 + ')');
        $('#klikhapus').attr('onclick', 'klikhapus(' + id + ',' + id2 + ')');

        console.log(id, id2);
        // lihat(id, id2);

        let top = e.pageY + 15;
        let left = e.pageX - 100;
        $("#judulklik").html(id2);

        $(this).css('background-color', '#f4f5f7');
        $("#menuklik").css({
            display: "block",
            top: top,
            left: left
        });

        return false; //blocks default Webbrowser right click menu
    });

    $("body").on("click", function() {
        if ($("#menuklik").css('display') == 'block') {
            $(" #menuklik ").hide();
        }
        $('.klik').css('background-color', 'white');
    });
</script>

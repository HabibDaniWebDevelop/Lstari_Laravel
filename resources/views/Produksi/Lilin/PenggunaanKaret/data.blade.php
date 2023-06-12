<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="hidden" class="btn btn-primary me-4" disabled id="btn_edit" onclick="KlikEdit()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span
                    class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="IDRubberout" value="" type="number">
            <input type="hidden" id="postingstatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="SearchspkkebutuhanKaret()"
                        autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($historyRubberout as $item)
                    <option value="{{$item->LinkID}}">{{$item->LinkID}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-4 center">
            <label class="form-label" for="IDSPKOInject">No. SPKO Inject:</label>
            <input type="number" disabled class="form-control" id="IDSPKOInject" tabindex="1"
                onchange="SearchWaxInjectOrder()">
        </div>
        <div class="col-4 center">
            <label class="form-label" for="Operator">Operator : <span id="IDOperator"></span></label>
            <input type="text" disabled class="form-control" id="Operator">
            <input type="hidden" id="idope">
        </div>
        <div class="col-4">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" disabled class="form-control" id="tanggal" readonly value="{{$datenow}}">
        </div>
    </div>
    <div class="row">

        <div class="col-6">
            <label class="form-label" for="Keperluan">Keterangan Keluar : </label>
            <select name="Keperluan" disabled id="Keperluan" class="form-select" tabindex="2">
                <option value="O">Operator</option>
                <option value="P">Pinjam</option>
                <option value="R">Reparasi</option>
            </select>
        </div>
        <div class="col-2">
            <label class="form-label me-0 pe-0" for="Kadar">Kadar SPK Inject : <span id="IDKadar"></span></label>
            <input type="text" disabled class="form-control me-0 pe-0" id="Kadar"
                style="font-weight: bold; color: #FFF; text-align: center;">
            <input type="hidden" id="idkad">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="Catatan">Catatan</label>
            <input type="text" class="form-control" id="Catatan" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="UserNameAdminKaret"><span id="UserNameAdminKaret"></span></label>
        </div>
        <div class="col-6">
            <label class="form-label" for="TanggaPembuatanSPKOKaret"><span id="TanggaPembuatanSPKOKaret"></span></label>
        </div>
    </div>

    <hr>
    <div>
        <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <!-- <th width="15%">No.SPK PPIC</th> -->
                    <th width="20%">Barang</th>
                    <th width="6%">Kadar Karet</th>
                    <th width="8%">ID Karet</th>
                    <th width="10%">Lokasi</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).on('keypress', 'input,textarea,select', function(e) {
    if (e.which == 13) {

        var posisi = parseFloat($(this).attr('tabindex')) + 1;
        $('[tabindex="' + posisi + '"]').focus();

        if (posisi != '2') {
            e.preventDefault();
        }
    }
});
</script>
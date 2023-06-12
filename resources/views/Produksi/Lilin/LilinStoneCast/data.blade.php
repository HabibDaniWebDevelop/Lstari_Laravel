<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="hidden" class="btn btn-primary me-4" disabled id="btn_edit" onclick="KlikEdit()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" tabindex="7"
                onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

            <button hidden type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="IDwaxstone" value="" type="number">
            <input type="hidden" id="postingstatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="Search()" autofocus=""
                        id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($historyRubberout as $item)
                    <option value="{{$item->ID}}">{{$item->ID}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-4 center">
            <label class="form-label" for="IDSPKOInject">No. SPKO Inject:</label>
            <input type="number" readonly class="form-control" id="IDSPKOInject" tabindex="1"
                onchange="SearchWaxInjectOrder()">
        </div>
        <div class="col-4">
            <label class="form-label me-0 pe-0" for="Kadar">Kadar SPK Inject : <span id="IDKadar"></span></label>
            <input type="text" readonly class="form-control me-0 pe-0" id="Kadar"
                style="font-weight: bold; color: #FFF; text-align: center;">
            <input type="hidden" id="idkad">
        </div>
        <div class="col-4">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" class="form-control" id="tanggal" readonly value="{{$datenow}}" tabindex="2">
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="basic-icon-default-fullname">ID Operator</label>
            <div class="input-group input-group-merge">
                <input type="text" class="form-control" disabled id="IdOperator" placeholder="Masukkan ID "
                    onkeyup="isioperator()" value="" tabindex="3" />
            </div>
        </div>
        <div class="col-4">
            <label class="form-label" for="basic-icon-default-fullname">Nama Operator</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text" style="background-color: #F0F0F0;"><i
                        class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="NamaOperator" placeholder="nama operator"
                    style="font-weight: bold; color: #80271c;" readonly value="" />
            </div>
        </div>
        <div class="col-4">
            <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                        class="fas fa-grip-horizontal"></i></span>
                <input type="number" class="form-control" disabled id="kelompok"
                    style="font-weight: bold; color: #80271c;" placeholder="" min="1" max="16" tabindex="4" />
            </div>
        </div>
    </div>
    <br>
    <div class="row" center>
        <div class="col-6">

            <label class="form-label" style="text-align: center; font-weight: bold; color: #80271c;" for="Rusak">

                <h6 style="text-align: center; font-weight: bold; color: #80271c;">&nbsp;&nbsp;&nbsp;&nbsp; Jumlah
                    Komponen :
                    &nbsp;&nbsp;<span id="Jumlah_Komponen"></span></h6>
            </label>
        </div>
        <div class="col-6">
            <label class="form-label" style="text-align: center; font-weight: bold; color: #80271c;" for="Setor">

                <h6 style="text-align: center; font-weight: bold; color: #80271c;">&nbsp;&nbsp;&nbsp;&nbsp; Jumlah batu
                    :
                    &nbsp;&nbsp;<span id="Jumlah_Batu"></span></h6>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="Catatan">Catatan</label>
            <input type="text" class="form-control" id="Catatan" disabled tabindex="5">
        </div>
    </div>
    <br>
    <div class="row" id="show" hidden>
        <div class="col-6">
            <label style="text-align: center; font-weight: bold; color: #80271c;" class="form-label"
                for="UserNameAdmin">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Admin : <span
                    id="UserNameAdmin"></span></label>
        </div>
        <div class="col-6">
            <label style="text-align: center; font-weight: bold; color: #80271c;" class="form-label"
                for="TanggaPembuatan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal Dibuat : <span
                    id="TanggaPembuatan"></span></label>
        </div>
    </div>

    <hr>
    <div>
        <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th width="15%">No.SPK PPIC</th>
                    <th width="20%">Komponen</th>
                    <th width="10%">Kadar</th>
                    <th width="7%">Qty</th>
                    <th width="7%">Batu</th>
                    <th width="17%">Keterangan</th>
                    <th id="tambahkurang" width="7%">Action</th>
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
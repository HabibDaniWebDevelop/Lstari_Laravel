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
            <button type="button" class="btn btn-primary" id="conscale" onclick="connectSerial()">Connect
                Timbangan</button>
            <input type="hidden" id="idWaxstoneusage" value="" type="number">
            <input type="hidden" id="postingstatus" value="A">

            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}

            <span id="infoposting"
                style="font-weight: bold; color: Tomato; font-size: 30px; padding-left: 150px;"></span>

        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="Searchspkkebutuhanbatu()"
                        autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($historyWaxStoneUsage as $item)
                    <option value="{{$item->ID}}">{{$item->ID}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="idWaxInjectOrder">No. SPKO Inject:</label>
            <input type="number" readonly class="form-control" id="idWaxInjectOrder" tabindex="1"
                onchange="SearchWaxInjectOrder()">
        </div>

        <div class="col-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">ID
                            Operator</label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="idEmployee" placeholder="Masukkan ID "
                                onkeyup="isioperator()" value="" tabindex="1" />
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-fullname">Nama Operator</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                style="background-color: #F0F0F0;"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="NamaOperator" placeholder="nama operator"
                                style="font-weight: bold; color: #80271c;" disabled value="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" disabled class="form-control" id="tanggal" readonly value="{{$datenow}}">
        </div>
    </div>
    <div class="row">

        <div class="col-3">
            <label class="form-label" for="TotalBerat">Total Berat Batu:</label>
            <input type="text" disabled class="form-control" tabindex="4" readonly id="TotalBerat" value="">
        </div>
        <div class="col-3">
            <label class="form-label" for="TotalJumlah">Total Jumlah :</label>
            <input type="text" class="form-control" id="TotalJumlah" readonly>
        </div>
        <div class="col-3">
            <label class="form-label" for="Keperluan">Keperluan : </label>
            <select name="Keperluan" disabled id="Keperluan" class="form-select" tabindex="2">
                <option value="Tambah">Tambah</option>
                <option value="Kurang">Kurang</option>
            </select>
        </div>
        <div class="col-md-3">
            <div class="d-grid gap-2">
                <label class="form-label"> &nbsp;</label>
                <button type="button" class="btn btn-dark" id="Posting1" disabled onclick="Klik_Posting1()">
                    <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            </div>
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
            <label class="form-label" for="UserNameAdminBatu"><span id="UserNameAdminBatu"></span></label>
        </div>
        <div class="col-6">
            <label class="form-label" for="TanggaPembuatanSPKOBatu"><span id="TanggaPembuatanSPKOBatu"></span></label>
        </div>
    </div>

    <hr>
    <div>

        <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th width="15%">No.SPK PPIC</th>
                    <th width="10%">Barang</th>
                    <th width="6%">Pcs</th>
                    <th width="10%">Berat</th>
                    <th width="16%">Keterangan</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
            <tfoot>
                <tr>

                </tr>
            </tfoot>
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
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled id="btn_edit" onclick="KlikEdit()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span
                    class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <input type="hidden" id="idWaxTree" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="SearchNTHKOPohonan()"
                        autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($historyWaxTree as $item)
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
            <input type="text" disabled class="form-control" id="idWaxInjectOrder" tabindex="1"
                onchange="SearchWaxInjectOrder()">
        </div>
        <div class="col-4">
            <label class="form-label" for="Karyawan">Karyawan : </label>
            <select name="idEmployee" disabled id="idEmployee" class="form-select" tabindex="2">
                @foreach ($employee as $item)
                <option value="{{$item->ID}}">{{$item->Description}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" disabled class="form-control" id="tanggal" readonly value="{{$datenow}}">
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="stickpohon">Stick Pohon : <span id="idstick"></span></label>
            <input type="text" disabled class="form-control" readonly id="stickpohon" onchange="SearchPohonan()">
        </div>
        <div class="col-3">
            <label class="form-label" for="NomorPiring">Nomor Piringan : <span id="IdPiring"></span></label>
            <input type="text" disabled class="form-control" id="NomorPiring" onchange="searchPiring()" tabindex="3">
        </div>
        <div class="col-3">
            <label class="form-label" for="BeratPiring">Berat Piringan:</label>
            <input type="text" class="form-control" id="BeratPiring" readonly>
        </div>
        <div class="col-3">
            <label class="form-label" for="totalQTY">Total QTY :</label>
            <input type="text" class="form-control" id="totalQTY" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="beratPohonTotal">Berat Pohon Total :</label>
            <input type="text" disabled class="form-control" tabindex="4" id="beratPohonTotal"
                onkeydown="kliktimbang2('beratPohonTotal')">
        </div>
        <div class="col-3">
            <label class="form-label" for="beratBatu">Berat Batu :</label>
            <input type="text" disabled class="form-control" tabindex="5" onchange="calculateBeratBatu()"
                id="beratBatu">
        </div>
        <div class="col-3">
            <label class="form-label" for="beratResin">Berat Resin :</label>
            <input type="text" disabled class="form-control" id="beratResin">
        </div>
        <div class="col-3">
            <label class="form-label" for="kadar">Kadar : <span id="idKadar"></span></label>
            <input type="text" class="form-control" id="kadar" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="Catatan">Catatan</label>
            <input type="text" class="form-control" disabled id="Catatan">
            <input type="hidden" id="WorkGroup">
            <input type="hidden" id="purpose">
            <input type="hidden" id="totalBatu">
        </div>
    </div>
    <hr>

    <div class="col-6 card mx-2" border-style=" 1px black important!">
        <ul class="nav nav-pills btn-group" role="group">
            <li class="nav-item col-6">
                <button type="radio" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tabelItem"
                    aria-controls="tabelItem" aria-selected="true"> Item
                </button>
            </li>
            <li class="nav-item col-6">
                <button type="radio" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#Penggunaanbatu"
                    aria-controls="tabelKaret" aria-selected="false" style="border-style: 1px primary;"> Penggunaan batu
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        {{-- tab1 --}}
        <div class="tab-pane fade active show" id="tabelItem" role="tabpanel">
            <table class=" table table-border table-hover table-sm rounded-4" id="tabel1">
                <thead class="table-secondary sticky-top zindex-2 rounded-4">
                    <tr style="text-align: center">
                        <th width="6%"> NO </th>
                        <th width="10%">No.SPK PPIC</th>
                        <th width="20%">Barang</th>
                        <th width="8%">Kadar</th>
                        <th width="6%">Jumlah</th>
                        <th width="18%">Keterangan</th>
                        <th width="6%">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>

        {{-- Panel tab2 --}}
        <div class="tab-pane fade" id="Penggunaanbatu" role="tabpanel">
            <table class="table table-border table-hover table-sm rounded-4" id="tabel2">
                <thead class="table-secondary sticky-top zindex-2 rounded-4">
                    <tr style="text-align: center">
                        <th width="6%">No</th>
                        <th width="10%">ID Batu</th>
                        <th>Jenis Batu</th>
                        <th width="6%">Ukuran</th>
                        <th width="12%">Keperluan</th>
                        <th width="6%">Pcs</th>
                        <th width="8%">Berat</th>
                        <th width="6%">Rata-rata</th>
                    </tr>
                </thead>

                <tbody class="text-center">
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
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
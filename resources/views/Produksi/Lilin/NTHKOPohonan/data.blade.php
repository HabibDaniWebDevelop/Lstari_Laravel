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
            <button type="button" class="btn btn-info me-4" id="btn_cetak" onclick="klikCetak()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <button type="button" class="btn btn-primary" id="conscale" onclick="connectSerial()">Connect
                Timbangan</button>
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
            <label class="form-label" for="idWaxInjectOrder">No. SPKO :</label>
            <input type="text" disabled class="form-control" id="idWaxInjectOrder" onchange="SearchWaxInjectOrder()">
        </div>
        <div class="col-4">
            <label class="form-label" for="Karyawan">Karyawan : </label>
            <select name="idEmployee" disabled id="idEmployee" class="form-select">
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
        <div class="col-4">
            <label class="form-label" for="nomorPohon">Nomor Piringan : <span id="idPohon"></span></label>
            <input type="text" disabled class="form-control" readonly id="nomorPohon" onchange="SearchPohonan()">
        </div>
        <div class="col-4">
            <label class="form-label" for="beratPohon">Berat Piringan:</label>
            <input type="text" class="form-control" id="beratPohon" readonly>
        </div>
        <div class="col-4">
            <label class="form-label" for="totalQTY">Total QTY :</label>
            <input type="text" class="form-control" id="totalQTY" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="beratPohonTotal">Berat Pohon Total :</label>
            <input type="text" disabled class="form-control" id="beratPohonTotal" readonly
                onkeydown="kliktimbang('beratPohonTotal')" onkeyup="calculateBeratBatu()">
        </div>
        <div class="col-3">
            <label class="form-label" for="beratBatu">Berat Batu :</label>
            <input type="text" disabled class="form-control" onchange="calculateBeratBatu()" id="beratBatu">
        </div>
        <div class="col-3">
            <label class="form-label" for="beratResin">Berat Resin :</label>
            <input type="text" disabled class="form-control" readonly id="beratResin">
        </div>
        <div class="col-3">
            <label class="form-label" for="kadar">Kadar : <span id="idKadar"></span></label>
            <input type="text" class="form-control" id="kadar" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="catatan">Catatan :</label>
            <textarea class="form-control" rows="1" name="catatan" , id="catatanHeader" disabled=""></textarea>
        </div>
    </div>
    <hr>
    <div>
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th width="10%">WorkOrder</th>
                    <th width="10%">No SPK</th>
                    <th width="5%">ID Barang</th>
                    <th width="39%">Barang</th>
                    <th width="8%">Kadar</th>
                    <th width="6%">Jumlah</th>
                    <th width="18%">Keterangan</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
</div>
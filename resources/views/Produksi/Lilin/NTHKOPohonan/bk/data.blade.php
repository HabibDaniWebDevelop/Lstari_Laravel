<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary me-4" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idWaxTree" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="SearchNTHKOPohonan()" autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="idWaxInjectOrder">Wax Inject Order ID :</label>
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
            <input type="date" disabled class="form-control" id="tanggal" value="{{$datenow}}">
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="nomorPohon">Nomor Pohon : <span id="idPohon"></span></label>
            <input type="text" disabled class="form-control" id="nomorPohon" onchange="SearchPohonan()">
        </div>
        <div class="col-4">
            <label class="form-label" for="beratPohon">Berat Pohon:</label>
            <input type="text" class="form-control" id="beratPohon" readonly>
        </div>
        <div class="col-4">
            <label class="form-label" for="totalQTY">Total QTY :</label>
            <input type="text" class="form-control" id="totalQTY" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="beratPohonTotal">Berat Pohon Total :</label>
            <input type="text" disabled class="form-control" id="beratPohonTotal">
        </div>
        <div class="col-4">
            <label class="form-label" for="beratBatu">Berat Batu :</label>
            <input type="text" disabled class="form-control" id="beratBatu">
        </div>
        <div class="col-4">
            <label class="form-label" for="kadar">Kadar : <span id="idKadar"></span></label>
            <input type="text" class="form-control" id="kadar" readonly>
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
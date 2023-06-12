<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info me-4" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <a href="/Lain-lain/Korespondensi/TandaTerima/informasi" target="_BLANK" class="btn btn-info">Informasi</a>
            <input type="hidden" id="SWTandaTerima" value="" type="text">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
            <input type="hidden" id="removeAction" value="true" type="text"> {{-- Input checking for determine remove action will executed or not. --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($history as $item)
                    <option value="{{$item->SW}}">{{$item->SW}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="no_referensi">No.Referensi :</label>
            <input type="text" class="form-control" disabled="" id="no_referensi">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" disabled="" class="form-control" id="tanggal" value="{{$datenow}}">
        </div>
        <div class="col-6">
            <label class="form-label" for="fromuser">Diterima Dari :</label>
            <input type="text" class="form-control" disabled="" id="fromuser" name="fromuser" value="">
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div>
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th> QTY </th>
                    <th> Satuan </th>
                    <th>Nama Barang</th>
                    <th width="40%"> Keterangan </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
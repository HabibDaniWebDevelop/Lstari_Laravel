<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <input type="hidden" id="CheckClockManualID" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" onchange="KlikCari()" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="employee">Karyawan : <span id="idEmployee"></span></label>
            <input type="text" class="form-control" disabled value="" onchange="searchKaryawan()" id="employee"> 
        </div>
        <div class="col-2">
            <label class="form-label" for="jam_clock">Jam :</label>
            <input type="time" class="form-control" disabled id="jam_clock">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" class="form-control" disabled value="{{$datenow}}" id="tanggal">
        </div>
        <div class="col">
            <br>
            <input class="form-check-input" disabled type="checkbox" value="" id="masuk">
            <label class="form-label" for="masuk">Masuk</label>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="form-label" for="catatan">Catatan :</label>
            <textarea disabled class="form-control" name="catatan" id="catatan" rows="2"></textarea>
        </div>
    </div>
    <br>
    <hr>
    <table class="table table-sm table-striped" id="tabel1">
        <thead>
            <tr>
                <th width="3%">Urut</th>
                <th width="10%">Tanggal Transaksi</th>
                <th width="10%">Jam Transaksi</th>
                <th class="text-center">Nama Karyawan</th>
                <th width="10%">Divisi</th>
            </tr>
        </thead>
    </table>
</div>
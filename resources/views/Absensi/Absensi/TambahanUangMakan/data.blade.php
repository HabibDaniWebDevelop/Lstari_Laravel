<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-dark me-4" id="btn_posting" onclick="KlikPosting()" disabled=""><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <input type="hidden" id="idTambahanUangMakan" value="" type="text">
            <input type="hidden" id="postingStatus" value="A">
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
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <div class="input-group">
                <input type="date" disabled class="form-control" value="{{$datenow}}" id="tanggal">
                <button disabled onclick="GetDaftarKaryawan()" class="btn btn-primary" id="btn_daftarKaryawan">Daftar Karyawan</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div>
        <table class="table table-borderless table-striped table-sm" id="tabel1">
            <thead class="table-secondary text-center">
                <tr>
                    <th width="5%"> NO </th>
                    <th width="55%"> Karyawan </th>
                    <th width="10%">ID Karyawan</th>
                    <th width="15%"> Bagian </th>
                    <th width="15%"> Pulang </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
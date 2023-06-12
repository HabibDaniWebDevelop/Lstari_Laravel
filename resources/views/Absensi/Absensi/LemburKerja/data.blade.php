<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <input type="hidden" id="idLemburKerja" value="" type="text">
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
        <div class="col-6">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" disabled="" class="form-control" id="tanggal" onkeydown="if (event.key == 'Enter'){NextInput('jam_selesai',true, CheckDate)}">
        </div>
        <div class="col-3">
            <label class="form-label" for="jam_mulai">Jam :</label>
            <input type="time" class="form-control" disabled="" id="jam_mulai" name="jam_mulai" value="" step="1">
        </div>
        <div class="col-3">
            <label class="form-label" for="jam_selesai">Hingga :</label>
            <input type="time" class="form-control" disabled="" id="jam_selesai" name="jam_selesai" value="" step="1" onkeydown="if (event.key == 'Enter'){NextInput('catatan')}">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="catatan">Catatan :</label>
            <input type="text" class="form-control" disabled="" id="catatan">
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div>
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th width="4%"> NO </th>
                    <th width="30%"> Karyawan </th>
                    <th width="10%"> ID Karyawan </th>
                    <th width="10%"> Bagian </th>
                    <th width="10%"> Aktual Mulai </th>
                    <th width="10%"> Aktual Selesai </th>
                    <th width="10%"> Lama </th>
                    <th width="6%"> Tambah </th>
                    <th width="10%"> Bonus </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
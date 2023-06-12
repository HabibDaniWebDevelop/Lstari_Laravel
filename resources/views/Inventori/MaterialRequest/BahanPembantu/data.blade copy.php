<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled=""
                onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan"
                onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idMaterialRequest" value="" type="text">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
            <input type="hidden" id="removeAction" value="true" type="text"> {{-- Input checking for determine remove action will executed or not. --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" id="cari"
                        list="carilist">
                </div>
                <datalist id="carilist">
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="employee">karyawan : <span id="idEmployee">{{ $employee->ID }}</span></label>
            <input type="text" class="form-control" disabled="" value="{{ $employee->NAME }}" id="employee"
                name="department">
        </div>
        <div class="col-6">
            <label class="form-label" for="department">Department : <span
                    id="idDepartment">{{ $employee->Department }}</span></label>
            <input type="text" class="form-control" disabled="" id="department" name="department"
                value="{{ $employee->Bagian }}">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" disabled="" class="form-control" id="tanggal" value="{{ $datenow }}">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="tanggal">Catatan :</label>
            <textarea class="form-control" name="catatan" id="catatan" cols="1" rows="1"></textarea>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div>
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th> Barang Stock </th>
                    <th> Barang Non Stock </th>
                    <th> Jumlah </th>
                    <th> Unit </th>
                    <th> Proses </th>
                    <th> Keperluan </th>
                    <th> Kategori </th>
                    <th> Ulang </th>
                    <th> Keterangan </th>
                </tr>
            </thead>
            <tbody class="text-center">
               
            </tbody>
        </table>
    </div>
</div>

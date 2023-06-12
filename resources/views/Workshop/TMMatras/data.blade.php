<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info me-4" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idTMMatras" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="KlikCari()" autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <br>

    {{-- Input new form --}}
    <div class="row">
        <div class="col-12">
            <table class="table table-borderless table-sm" id="tabel1">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th width="5%">No.</th>
                        <th>ID Matras</th>
                        <th>Nama Matras</th>
                        <th>Jenis Matras</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>
    </div>
</div>
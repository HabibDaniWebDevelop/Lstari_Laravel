<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" disabled="" onclick="klikCetak()"> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idSPKOPercobaanKuningan" value="">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
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
        <div class="col-3">
            <label class="form-label" for="idMatras">ID Matras :</label>
            <input type="text" disabled onchange="searchMatras()" id="idMatras" class="form-control">
        </div>
        <div class="col-3">
            <label class="form-label" for="employee">Karyawan :</label>
            <select class="form-select" disabled name="employee" id="employee">
                @foreach ($employees as $item)
                <option value="{{$item->ID}}">{{$item->SW}} - {{$item->Description}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <label class="form-label" for="tanggalNow">Tanggal :</label>
            <input type="date" disabled="" value="{{$now}}" class="form-control" id="tanggalNow">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="catatan">Catatan :</label>
            <textarea class="form-control" disabled rows="1" name="catatan" id="catatan"></textarea>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <div>
                <table class="table table-borderless table-sm" id="tabel1">
                    <thead class="table-secondary">
                        <tr style="text-align: center">
                            <th> Matras </th>
                            <th> Produk Matras </th>
                            <th> Pisau </th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
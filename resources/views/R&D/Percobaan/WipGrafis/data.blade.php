<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary me-4" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" disabled="" onclick="klikCetak()"> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idNTHKO" value="" type="number">
            <input type="hidden" id="action" value="simpan"> 
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" onchange="KlikCari()" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($history as $item)
                        <option value="{{$item->WorkAllocation}}">{{$item->WorkAllocation}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="noNTHKO">No NTHKO :</label>
            <input type="text" disabled class="form-control" onchange="getNTHKO()" id="noNTHKO">
        </div>
        <div class="col-3">
            <label class="form-label" for="tanggalNTHKO">Tanggal :</label>
            <input type="date" class="form-control" disabled="" id="tanggalNTHKO">
        </div>
        <div class="col-3">
            <label class="form-label" for="totalJumlah">Total Jumlah :</label>
            <input type="text" disabled class="form-control" id="totalJumlah">
        </div>
        <div class="col-3">
            <label class="form-label" for="totalBerat">Total Berat :</label>
            <input type="text" disabled class="form-control" id="totalBerat">
        </div>
    </div>
    <hr>

    {{-- Input new form --}}
    <div id="TableItems">
        <table class="table table-borderless table-striped table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th> No. </th>
                    <th> No. SPK </th>
                    <th> Product </th>
                    <th> Kadar </th>
                    <th> Jumlah </th>
                    <th> Berat </th>
                    <th> Gambar </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
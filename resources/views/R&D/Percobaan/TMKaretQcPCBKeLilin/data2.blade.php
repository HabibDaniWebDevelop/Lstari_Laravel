<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info me-4" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <a class="btn btn-info" href="/R&D/Percobaan/TMKaretQcPCBKeLilin/informasi"><span class="tf-icons bx bx-info-circle"></span>&nbsp; Information</a>
            <input type="hidden" id="idTMKaretKeLilin" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="KlikCari()" autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($listhist as $item)
                        <option value="{{$item->IDM}}">{{$item->IDM}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <br>

    {{-- Input new form --}}
    <div class="row">
        <div class="col-2">
            <table class="table table-borderless table-sm" id="tabel1">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th width="10%">No.</th>
                        <th>NTHKO QC</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>
        <div class="col-10">
            <table class="table table-borderless table-striped table-sm" id="tabelitem">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th> No. NTHKO</th>
                        <th> Product </th>
                        <th> Bulan STP</th>
                        <th> Rubber Kepala </th>
                        <th> Nama Product Kepala </th>
                        <th> Rubber Mainan </th>
                        <th> Nama Product Mainan </th>
                        <th> Rubber Komponen </th>
                        <th> Nama Product Komponen </th>
                    </tr>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>
    </div>
</div>
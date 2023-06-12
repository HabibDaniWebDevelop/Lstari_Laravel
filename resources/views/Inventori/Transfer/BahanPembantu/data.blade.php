
<div class="card-body">
    <div class="row">
        <div class="col-9" id="btn-menu">
            <button type="button" class="btn btn-primary mb-2" id="btn_baru" onclick="KlikBaru()">
                <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary mb-2 me-3" id="btn_ubah" disabled onclick="KlikUbah()">
                <span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger mb-2" disabled id="btn_batal" onclick="klikBatal()">
                <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning mb-2" disabled id="btn_simpan" onclick="KlikSimpan()">
                <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-dark mb-2 me-3" id="btn_Posting" disabled onclick="KlikPosting()">
                <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <button type="button" class="btn btn-info mb-2" id="btn_cetak" disabled onclick="klikCetak()">
                <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                <input type="hidden" id="Akses" value="{{$Akses}}">
        </div>

        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="search" class="form-control" placeholder="Search..." autofocus="" id="cari"
                        list="carilist" onchange="ChangeCari()">
                </div>
                <datalist class="text-warning" id="carilist">
                    @foreach ($ListUserHistory as $carilist)
                    <option value="{{ $carilist->SW }}">
                        {{ $carilist->SW }}
                    </option>
                    @endforeach
                </datalist>
            </div>
        </div>

    </div>
    <hr class="mt-0">
    <form id="form1" autocomplete="off">
        <div id="tampil">

        </div>
    </form>
</div>

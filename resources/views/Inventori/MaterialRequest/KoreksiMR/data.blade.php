<div class="card-body">
    <div class="row">
        <div class="col-6" id="btn-menu">
            {{-- <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button> --}}
            <button type="button" class="btn btn-primary me-4" id="btn_ubah" disabled onclick="KlikUbah()"><span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled id="btn_simpan" onclick="KlikSimpan()"><span
                    class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <input type="hidden" id="ulangtambah" value="1">
        </div>
        <div class="col-6">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="search" class="form-control" placeholder="Search..." autofocus="" id="cari"
                        list="carilist">
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
    <hr>
    <form id="form1" autocomplete="off">
        <div id="tampil">

        </div>
    </form>
</div>

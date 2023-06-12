<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary me-4" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-dark me-4" id="btn_posting" onclick="KlikPosting()" disabled=""><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <button type="button" class="btn btn-info" id="btn_cetak" disabled="" onclick="klikCetak()"> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <button type="button" class="btn btn-primary" id="conscale" onclick="connectSerial()">Connect Timbangan</button>
            <input type="hidden" id="idWorkAllocation" value="">
            <input type="hidden" id="postingStatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
            <input type="hidden" id="selscale">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" onchange="KlikCari()" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($history as $item)
                        <option value="{{$item->workAllocation}}">{{$item->workAllocation}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="noNTHKO">No.NTHKO :</label>
            <input type="text" disabled="" onchange="getWIP()" class="form-control" id="noNTHKO">
        </div>
        <div class="col-3">
            <label class="form-label" for="employee">Karyawan :</label>
            <select class="form-select" disabled name="employee" id="employee">
                @foreach ($employees as $item)
                <option value="{{$item->ID}}">{{$item->SW}} - {{$item->Description}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="totalJumlah">Total Jumlah :</label>
            <input type="text" disabled="" class="form-control" id="totalJumlah">
        </div>
        <div class="col-4">
            <label class="form-label" for="totalBerat">Total Berat :</label>
            <input type="text" disabled="" style="background-color: #FCF3CF;" class="form-control" id="totalBerat">
        </div>
        <div class="col-4">
            <label class="form-label" for="tanggalNow">Tanggal :</label>
            <input type="text" disabled="" value="{{$now}}" class="form-control" id="tanggalNow">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <div id="TableItems">
            </div>
        </div>
    </div>
</div>
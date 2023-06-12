<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled id="btn_ubah" onclick="klikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="klikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="LabTransactionID" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="CariLabTest()" autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($labtrx as $item)
                    <option value="{{$item['id']}}">{{$item['WorkAllocation']}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <p class="text-danger">Note: Tolong pilih salah satu. Paste langsung ke "Test Result" atau upload file ketika ingin melakukan Check.</p>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="workallocation">No. NTHKO Lebur :</label>
            <input type="text" disabled class="form-control" id="workallocation">
        </div>
        <div class="col-12">
            <label class="form-label" for="TestResult">Test Result :</label>
            <textarea name="TestResult" disabled class="form-control" rows="10" id="TestResult"></textarea>
            <br>
        </div>
        <div class="col-12">
            <label class="form-label" for="file">File :</label>
            <input type="file" disabled class="form-control" id="file" accept="text/plain">
            <br>
            <button class="btn btn-outline-primary" disabled type="button" id="btn_check" onclick="klikCheck()">Check</button>
        </div>
    </div>
    <br>
    <hr>
    <div id="LabView">
    </div>
</div>
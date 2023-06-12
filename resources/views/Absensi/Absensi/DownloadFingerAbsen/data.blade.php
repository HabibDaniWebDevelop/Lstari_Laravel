<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary me-4" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-dark me-4" id="btn_posting" onclick="KlikPosting()" disabled=""><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <input type="hidden" id="haveFingerAbsent" value="" type="text">
            <input type="hidden" id="postingStatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="file">File :</label>
            <input type="file" disabled="" class="form-control" id="file" accept=".csv">
            <br>
            <button class="btn btn-outline-primary" disabled="" type="button" id="btn_check" onclick="klikCheck()">Check</button>
            <hr>
        </div>
        <div class="col-12">
            <table class="table table-sm table-striped" id="tabelTransaction">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Bagian</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Machine</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
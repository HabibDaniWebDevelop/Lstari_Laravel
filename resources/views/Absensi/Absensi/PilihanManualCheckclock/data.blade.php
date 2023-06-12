<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" onchange="GetPilihanManualCheckclock()" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            <div class="input-group">
                <input type="date" class="form-control" onchange="GetPilihanManualCheckclock()" id="tanggal_akhir">
                <button class="btn btn-primary" onclick="GetPilihanManualCheckclock()">Search</button>
            </div>
        </div>
    </div>
    <br>
    <div id="DataTableContainer">
        <table class="table table-striped" id="TabelPilihanManualCheckclock">
            <thead class="table-secondary">
                <tr>
                    <th width="10%" class="sorting_asc" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Tanggal: activate to sort column descending">Tanggal</th>
                    <th width="10%" class="sorting" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-label="Hari: activate to sort column ascending">Hari</th>
                    <th width="10%" class="sorting" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-label="Jam Masuk: activate to sort column ascending">Jam Masuk</th>
                    <th width="10%" class="sorting" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-label="Jam Pulang: activate to sort column ascending">Jam Pulang</th>
                    <th class="sorting" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-label="karyawan: activate to sort column ascending">karyawan</th>
                    <th width="10%" class="sorting" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-label="Bagian: activate to sort column ascending">Bagian</th>
                    <th width="10%" class="sorting" tabindex="0" aria-controls="tblitem" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" onchange="SearchBeritaAcara()" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            <div class="input-group">
                <input type="date" class="form-control" onchange="SearchBeritaAcara()" id="tanggal_akhir">
                <button class="btn btn-primary" onclick="SearchBeritaAcara()">Search</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="DataTableContainer">
        <table class="table table-striped" id="TabelBeritaAcara">
            <thead class="table-secondary">
                <tr>
                    <th width="2%">ID</th>
                    <th width="10%">Tanggal</th>
                    <th width="10%">Karyawan</th>
                    <th width="2%">Divisi</th>
                    <th width="2%">Status</th>
                    <th width="5%">Keperluan</th>
                    <th width="10">Jenis</th>
                    <th>Catatan</th>
                    <th>Solusi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
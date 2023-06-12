<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="JaminanKaryawanID" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
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
        <div class="col">
            <label class="form-label" for="employee">Karyawan : <span id="idEmployee"></span></label>
            <input type="text" class="form-control" disabled value="" onchange="searchKaryawan()" id="employee"> 
        </div>
        <div class="col">
            <label class="form-label" for="tanggal_diterima">Tanggal Diterima :</label>
            <input type="date" class="form-control" disabled value="{{$datenow}}" id="tanggal_diterima">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="form-label" for="jaminan">Jaminan :</label>
            <select class="form-select" id="jaminan" name="jaminan" disabled>
                <option value="496">Ijazah SD/MI</option>	
                <option value="497">Ijazah SMP/MTS</option>	
                <option value="498">Ijazah SMA/SMK/MA/SMU/STM/SMEA</option>	
                <option value="499">Ijazah D1</option>
                <option value="500">Ijazah D2</option>	
                <option value="501">Ijazah D3</option>	
                <option value="502">Ijazah S1</option>	
                <option value="503">Ijazah S2</option>	
                <option value="504">Ijazah S3</option>	
                <option value="505">Daftar/Transkrip Nilai</option>	
                <option value="506">Kartu Keluarga</option>	
                <option value="507">Kartu Tanda Penduduk</option>	
                <option value="508">Akte Kelahiran</option>
                <option value="509">Lain-Lain</option>
            </select>
        </div>
        <div class="col">
            <label class="form-label" for="nomor_sk">Nomor/SK :</label>
            <input type="text" class="form-control" disabled value="" id="nomor_sk"> 
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="form-label" for="keterangan">Keterangan :</label>
            <textarea disabled class="form-control" name="keterangan" id="keterangan" rows="4"></textarea>
        </div>
    </div>
    <br>
</div>
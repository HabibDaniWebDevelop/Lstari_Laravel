<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <input type="hidden" id="idBeritaAcara" value="" type="number">
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
        <div class="col-6">
            <label class="form-label" for="employee">Karyawan : <span id="idEmployee"></span></label>
            <input type="text" class="form-control" disabled value="" onchange="searchKaryawan()" id="employee"> 
        </div>
        <div class="col-6">
            <label class="form-label" for="bagian">Bagian :</label>
            <input type="text" class="form-control" readonly id="bagian">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" class="form-control" disabled value="{{$datenow}}" id="tanggal">
        </div>
        <div class="col-6">
            <label class="form-label" for="status">Status :</label>
            <input type="text" class="form-control" readonly value="" id="status">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="keperluan">Keperluan :</label>
            <select id="keperluan" disabled name="keperluan" class="form-select">
                <option value="269">Berita Acara</option>	
                <option value="268">Surat Peringatan 1</option>	
                <option value="267">Surat Peringatan 2</option>	
                <option value="266">Surat Peringatan 3</option>	
            </select>
        </div>
        <div class="col-6">
            <label class="form-label" for="jenis">Jenis :</label>
            <select id="jenis" disabled name="jenis" class="form-select">
                <option value="81">Kesalahan</option>	
                <option value="80">Tidak Lembur</option>	
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="keterangan">Keterangan :</label>
            <textarea disabled class="form-control" name="keterangan" id="keterangan" rows="2"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="solusi">Solusi :</label>
            <textarea disabled class="form-control" name="solusi" id="solusi" rows="2"></textarea>
        </div>
    </div>
</div>
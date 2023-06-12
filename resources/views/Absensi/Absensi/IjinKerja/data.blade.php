<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-dark me-4" id="btn_posting" onclick="KlikPosting()" disabled=""><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <input type="hidden" id="idIjinKerja" value="" type="text">
            <input type="hidden" id="postingStatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
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
        <div class="col-4">
            <label class="form-label" for="employee">Karyawan : <span id="idEmployee"></span> <span id="Rank"></span></label> <span id="Department"></span></label>
            <input type="text" class="form-control" disabled value="" onchange="searchKaryawan()" onkeydown="if (event.key == 'Enter'){NextInput('tanggalMulai')}" id="employee"> 
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggalMulai">Tanggal Mulai :</label>
            <input type="date" class="form-control" value="{{$datenow}}" onkeydown="if (event.key == 'Enter'){NextInput('waktuMulai')}" disabled id="tanggalMulai">
        </div>
        <div class="col-2">
            <label class="form-label" for="waktuMulai">Jam Mulai :</label>
            <input type="time" class="form-control" value="08:00:00" step="1" disabled id="waktuMulai" onkeydown="if (event.key == 'Enter'){NextInput('tanggalSelesai')}">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggalSelesai">Tanggal Selesai :</label>
            <input type="date" class="form-control" value="{{$datenow}}" disabled id="tanggalSelesai" onkeydown="if (event.key == 'Enter'){NextInput('waktuSelesai',true, get_tglakhir)}">
        </div>
        <div class="col-2">
            <label class="form-label" for="waktuSelesai">Jam Selesai :</label>
            <input type="time" class="form-control" value="16:30:00" step="1" disabled id="waktuSelesai" onkeydown="if (event.key == 'Enter'){NextInput('jenisIjin')}">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="jenis">Jenis Ijin :</label>
            <select name="jenisIjin" id="jenisIjin" class="form-select" onkeydown="if (event.key == 'Enter'){NextInput('catatan')}" disabled>
                @foreach ($jenisijin as $item)
                    <option value="{{$item->ID}}">{{$item->Description}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-1">
            <br>
            <input class="form-check-input" disabled type="checkbox" value="" id="pemberitahuan">
            <label class="form-label" for="pemberitahuan">Pemberitahuan</label>
        </div>
        <div class="col-1">
            <br>
            <input class="form-check-input" disabled type="checkbox" value="" id="ijinSebelumnya">
            <label class="form-label" for="ijinSebelumnya">Ijin Sebelumnya</label>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="form-label" for="catatan">Catatan :</label>
            <textarea disabled class="form-control" name="catatan" id="catatan" rows="2"></textarea>
        </div>
    </div>
    <br>
    <hr>
    <div class="row">
        <div class="col-6">
            <p><b>Transaksi</b></p>
            <table class="table table-sm table-striped" id="tabelTransaction">
                <thead>
                    <tr>
                        <th width="3%">Urut</th>
                        <th>Tanggal</th>
                        <th width="10%">Mulai</th>
                        <th width="10%">Akhir</th>
                        <th width="10%">Absent</th>
                        <th width="10%">Time 1</th>
                        <th width="10%">Time 2</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <p><b>History</b></p>
            <table class="table table-sm table-striped" id="tabelHistory">
                <thead>
                    <tr>
                        <th width="3%">ID</th>
                        <th>Ijin</th>
                        <th width="25%">Tanggal</th>
                        <th width="10%">Mulai</th>
                        <th width="10%">Akhir</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
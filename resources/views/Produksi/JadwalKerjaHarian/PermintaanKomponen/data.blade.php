<div class="card-body" style="zoom:90%">

    <div class="card-body">
        <div class="row alert alert-info">
            <div class="col-md-9">
                <span>
                    <button class="btn btn-warning btn-md" id="btnsimpan" disabled onclick="klikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button class="btn btn-danger btn-md" id="btnbatal" disabled onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button class="btn btn-primary btn-md" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru</button>
                    <button class="btn btn-primary btn-md" id="btnlihat" onclick="klikLihatAll()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    <button class="btn btn-info btn-md" id="btncetak" disabled onclick="klikCetakAll()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    <button class="btn btn-info btn-md" id="btnstokcb" onclick="stokCB()"><span class="tf-icons bx bx-dropbox"></span>&nbsp; Lihat Stok CB</button>
                    <button class="btn btn-dark btn-md" id="btnsimpantest" onclick="klikSimpanTest()">testIT</button>
                    <button class="btn btn-primary btn-md float-end" id="btnclear" onclick="klikClear()"><span class="tf-icons bx bx-search-alt-2"></span>&nbsp; Clear</button>
                </span>
            </div>
            <div class="col-md-3">
                <input class="form-control form-control-sm" list="datalistNTHKO" name="idcari" id="idcari" placeholder="Cari" onchange="klikLihatAll()">
                <input type="hidden" name="idlocation" id="idlocation" value="{{$location}}"> 
                <datalist id="datalistNTHKO">
                    @if($rowcount > 0)
                        @foreach($data as $datas)
                            <option value="{{ $datas->ID }}">{{ $datas->ID }}</option>
                        @endforeach
                    @endif
                </datalist>
            </div>
        </div>
        <form id="datasimpan">
            <div class="alert alert-info">
                <div class="row">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info btn-sm">ID <span class="badge badge-light" id="idmnya">0</span>
                    </div>
                    <div class="col-md-5">
                        <input type="number" id="rph" name="rph" disabled class="form-control" placeholder="Masukan ID RPH">
                    </div>
                    <div class="col-md-6" style="float: right">
                        @if($location == 50)
                            <button style="float: center" type="button" class="btn btn-primary btn-sm" id="btnformsepuh" onclick="klikFormSepuh()" disabled>Periksa</button>
                            <button style="float: center" type="button" class="btn btn-primary btn-sm" id="btnformpcb" onclick="klikFormPCB()" disabled>Periksa PCB</button>
                        @elseif($location == 4)
                            <button style="float: center" type="button" class="btn btn-primary btn-sm" id="btnformkikir" onclick="klikFormKikir()" disabled>Periksa</button>
                            <button style="float: center" type="button" class="btn btn-primary btn-sm" id="btnformdc" onclick="klikFormDC()" disabled>Periksa DC</button>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Tanggal</label>
                        <input type="date" name="tgl_masuk" placeholder="Tanggal" disabled id="tgl_masuk" class="form-control" value="<?php echo date('Y-m-d') ?>">
                        <input type="hidden" name="update" id="update" value="">
                        <input type="hidden" name="idmnya1" id="idmnya1" value="">
                        <input type="hidden" name="icis[]" id="icis" value="">
                        <input id="pilih" type="hidden" name="pilih">
                    </div>
                    <div class="col-md-6">
                        <label>Catatan</label>
                        <input type="text" name="catatan" disabled id="catatan" placeholder="Catatan" class="form-control">
                    </div>
                </div>
            </div>
        </form>
    </div>
  
    <hr style="height:1px; color: #000000;">
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div id="tampil"></div>
            </div>
        </div>
    </div>
  
</div>

@include('Produksi.JadwalKerjaHarian.PermintaanKomponen.modal')
  
  
  
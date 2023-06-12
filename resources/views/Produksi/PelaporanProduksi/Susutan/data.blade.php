<div class="card-body" style="zoom:90%;">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-10">
                <span>
                    <button class="btn btn-warning" id="btnsimpan" onclick="simpan()" disabled><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button class="btn btn-danger" id="btnbatal" onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button class="btn btn-primary" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru</button>
                    <button class="btn btn-primary" id="btnlihat" onclick="klikLihat()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    <button class="btn btn-info" id="btncetak" onclick="klikCetak()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    
                    @if($iddept == 12)
                    <button class="btn btn-dark" id="btntest" onclick="simpanTest()">Test</button>
                    @endif
                </span>
            </div>
            <div class="col-md-2">
                <input type="search" class="form-control" list="datalist" name="idcari" id="idcari" placeholder="Cari" onchange="klikLihat()">
                <datalist id="datalist">
                    @if($rowcount > 0)
                        @foreach ($data as $datas)
                            <option value="{{ $datas->Allocation }}">{{ $datas->Allocation }}</option>
                        @endforeach
                    @endif
                </datalist>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"> 
                <input class="form-control" type="text" id="inputspko" name="inputspko" style="display:none;" placeholder="Scan No SPKO" onchange="baru(this.value)">
            </div>
        </div>
    </div>
  
    <hr style="height:1px; color: #000000">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-12">
                <div id="tampil"></div>
            </div>
        </div>
    </div>
  
</div>

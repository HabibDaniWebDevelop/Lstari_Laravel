<div class="card-body" style="zoom:100%; padding: 10px; margin: 0">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-10">
                <span>
                    <button class="btn btn-warning btn-sm" id="btnsimpan" onclick="klikSimpan()" disabled><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button class="btn btn-danger btn-sm" id="btnbatal" onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button class="btn btn-primary btn-sm" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru</button>
                    <button class="btn btn-primary btn-sm" id="btnubah" onclick="klikUbah()" disabled><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
                    <button class="btn btn-primary btn-sm" id="btnlihat" onclick="klikLihat()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    <button class="btn btn-info btn-sm" id="btncetak" onclick="klikCetak()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    <button class="btn btn-info btn-sm" id="btncetakbarcode" onclick="klikCetakBarcode()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Barcode</button>
                    <button type="button" class="btn btn-primary btn-sm" id="conscale" onclick="connectSerial()">Timbangan</button>
                    {{-- <button class="btn btn-danger btn-sm" id="btntest" onclick="nthkoList2()">Test</button> --}}
                    {{-- <button type="button" class="btn btn-primary btn-sm float-end" onclick="klikClear()"><span class="tf-icons bx bx-search-alt-2"></span>&nbsp; Clear</button> --}}
                </span>
            </div>
            <div class="col-md-2" id="nthkoList">
                <input type="search" class="form-control" style="font-size: 13px; font-weight: bold; color: black;" list="datalistNTHKO" name="idcari" id="idcari" placeholder="Cari" onchange="klikLihat()" maxlength="12">
                <datalist id="datalistNTHKO">
                    @if($rowcount > 0)
                        @foreach ($data as $datas)
                            <option value="{{ $datas->NTHKO }}">{{ $datas->NTHKO }}</option>
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
  
    <hr class="my-2 mx-2" style="height:1px; color: #000000;">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-12">
                <div id="tampil"></div>
            </div>
        </div>
    </div>
  
</div>
  
@include('Produksi.PelaporanProduksi.NTHKO.modal')


  
  
  
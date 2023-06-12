<div class="card-body" style="zoom:100%; padding: 10px; margin: 0">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-10">
                <span>
                    <button class="btn btn-warning btn-sm" id="btnsimpan" onclick="klikSimpan()" disabled><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button></button>
                    <button class="btn btn-danger btn-sm" id="btnbatal" onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button></button>
                    <button class="btn btn-primary btn-sm" id="btnbaru" onclick="showInputSpko()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button></button>
                    <button class="btn btn-primary btn-sm" id="btnubah" onclick="klikUbah()" disabled><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button></button>
                    <button class="btn btn-primary btn-sm" id="btnlihat" onclick="klikLihat()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    <button class="btn btn-info btn-sm" id="btncetak" onclick="klikCetak()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button></button>
                    <button class="btn btn-info btn-sm" id="btncetakbarcode" onclick="klikCetakBarcode()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Barcode</button></button>
                    {{-- <button class="btn btn-info btn-sm" id="btncetakbarcodedirect" onclick="klikCetakBarcodeDirect()" ><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode Direct</button></button> --}}
                    <button class="btn btn-info btn-sm" id="btncetakgambar" onclick="klikCetakGambar()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Gambar</button></button>
                    <button type="button" class="btn btn-primary btn-sm" id="conscale" onclick="connectSerial()">Timbangan</button>
                    {{-- <button type="button" class="btn btn-primary btn-sm float-end" onclick="klikClear()"><span class="tf-icons bx bx-trash-alt"></span></button> --}}
                </span>
            </div>
            <div class="col-md-2">
                <input type="search" class="form-control" list="datalistSPKO" name="idcari" id="idcari" onchange="klikLihat()" placeholder="Cari SPKO">
                <datalist id="datalistSPKO">

                    @if($rowcount > 0)
                        @foreach ($data as $dataOK)
                            <option value={{$dataOK->SWFREQ}}>{{$dataOK->SWFREQ}}</option>
                        @endforeach
                    @endif
     
                </datalist>
            </div>
        </div>
        <div class="row" style="padding-top: 10px;">
            <div class="col-md-2"> 
                <input class="form-control" type="text" id="inputspko" name="inputspko" style="display:none;" placeholder="Scan No SPKO" onkeyup="klikBaru(this.value)">
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
  
@include('Produksi.PelaporanProduksi.SPKOTambahan.modal')
  
  
  
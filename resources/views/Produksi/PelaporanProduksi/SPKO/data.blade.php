<div class="card-body" style="zoom:100%; padding: 10px; margin: 0">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-10">
                <span>
                    {{-- <button class="btn btn-warning btn-sm" id="btnsimpan" onclick="klikSimpan()" disabled><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button> --}}
                    <button class="btn btn-warning btn-sm" id="btnsimpan" onclick="cekSPK()" disabled><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

                    <button class="btn btn-danger btn-sm" id="btnbatal" onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button class="btn btn-primary btn-sm" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    <button class="btn btn-primary btn-sm" id="btnubah" onclick="klikUbah()" disabled><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
                    <button class="btn btn-primary btn-sm" id="btnlihat" onclick="klikLihat()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    @if($location == 12 || $iddept == 26)
                        <button class="btn btn-info btn-sm" id="btncetak" onclick="klikCetak2()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    @else
                        <button class="btn btn-info btn-sm" id="btncetak" onclick="klikCetak()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    @endif
                   
                    <button class="btn btn-info btn-sm" id="btncetakbarcode" onclick="klikCetakBarcode()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Barcode</button>
                    {{-- <button class="btn btn-info btn-sm" id="btncetakbarcodedirect" onclick="klikCetakBarcodeDirect()" ><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode Direct</button> --}}
                    <button class="btn btn-info btn-sm" id="btncetakgambar" onclick="klikCetakGambar()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Gambar</button></button>
                    <button type="button" class="btn btn-primary btn-sm" id="conscale" onclick="connectSerial()">Timbangan</button>

                    {{-- ID Dept IT --}}
                    {{-- @if($iddept == 12 || $iddept == 26)  --}}
                    {{-- <button class="btn btn-danger btn-sm" id="btncetak" onclick="klikCetak2()">Test Cetak</button></button> --}}
                    <button class="btn btn-danger btn-sm" id="btncetak" onclick="klikPersenKerjaAjax()">WorkPersen</button></button>
                    {{-- <button class="btn btn-danger btn-sm" id="btncetak" onclick="insertWorkPercentTest()">Test SimpanPersen</button></button> --}}
                    {{-- @endif --}}

                    @if($iddept == 12)
                        <button class="btn btn-danger btn-sm" id="btntest" onclick="cekSPKTest()">Test SPK</button>
                        <button class="btn btn-danger btn-sm" id="btntest2" onclick="klikSimpanTest()">Test Simpan</button>
                        <button class="btn btn-danger btn-sm" id="btntest3" onclick="test3()">Test Escape String</button>
                        <button class="btn btn-danger btn-sm" id="btntest" onclick="testInsertWorkPercent()">testInsertWorkPercent</button>
                    @endif

                    {{-- <button type="button" class="btn btn-primary btn-sm float-end" onclick="klikClear()"><span class="tf-icons bx bx-trash-alt"></span></button> --}}
                </span>
            </div>
            <div class="col-md-2">
                <input type="search" class="form-control" style="font-size: 13px; font-weight: bold; color: black;" list="datalistSPKO" name="idcari" id="idcari" onchange="klikLihat()" placeholder="Cari SPKO">
                <datalist id="datalistSPKO">

                    @if($rowcount > 0)
                        @foreach ($data as $dataOK)
                            <option value={{$dataOK->SW}}>{{$dataOK->SW}}</option>
                        @endforeach
                    @endif
     
                </datalist>
            </div>
        </div>
    </div>
    {{-- class="my-2 mx-2 m-2" -> bawaan bootstrap pengganti css margin , class="py-2 px-2 p-2" -> bawaan bootstrap pengganti css padding , jumlah max = 5 --}}
    <hr class="my-2 mx-2" style="height:1px; color: #000000;">
    
    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-12">
                <div id="tampil"></div>
            </div>
        </div>
    </div>
  
</div>
  
@include('Produksi.PelaporanProduksi.SPKO.modal')
  
  
  
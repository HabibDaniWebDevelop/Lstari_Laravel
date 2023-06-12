<div class="card-body" style="zoom:100%; padding: 10px; margin: 0">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-10">
                <span>
                    <input type="hidden" id="jumlah" name="jumlah" value=""> 
                    <button class="btn btn-warning btn-sm" id="btnsimpan" onclick="cekSPK()" disabled><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button class="btn btn-danger btn-sm" id="btnbatal" onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button class="btn btn-primary btn-sm" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru</button>
                    <button class="btn btn-primary btn-sm" id="btnubah" onclick="klikUbah()" disabled><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
                    <button class="btn btn-primary btn-sm" id="btnlihat" onclick="klikLihat()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    <button class="btn btn-info btn-sm" id="btncetak" onclick="klikCetak()" disabled><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    <button type="button" class="btn btn-primary btn-sm" id="conscale" onclick="connectSerial()">Timbangan</button>

                    @if($iddept == 12)
                        <button class="btn btn-danger btn-sm" id="btntest" onclick="klikPostingTest()">Test Posting</button>
                        <button class="btn btn-warning btn-sm" id="btntest" onclick="cekSPKAll()">Simpan Test</button>
                        <button class="btn btn-primary btn-sm" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru</button>
                        {{-- <button class="btn btn-danger btn-sm" id="btntest" type="submit">Test Posting2</button> --}}
                    @endif

                    {{-- <button class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()" ><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button> --}}
                    <button class="btn btn-primary btn-sm float-end" onclick="klikClear()"><span class="tf-icons bx bx-search-alt-2"></span>&nbsp; Clear</button>
                </span>
            </div>
            <div class="col-md-2">
                <input type="search" class="form-control" list="datalistTM" name="idcari" id="idcari" placeholder="Cari" onchange="klikLihat()">
                <datalist id="datalistTM">
                    @if($rowcount > 0)
                        @foreach ($data as $datas)
                            <option value="{{ $datas->ID }}">{{ $datas->ID }}</option>
                        @endforeach
                    @endif
                </datalist>
            </div>
        </div>
    </div>
  
    <hr style="height:1px; color: #000000">

    <div class="card-body p-0 m-0">
        <div class="row">
            <div class="col-md-12">
                <div id="tampil">

                </div>
            </div>
        </div>
    </div>
  
</div>
  
@include('Produksi.PelaporanProduksi.TransferMaterial.modal')

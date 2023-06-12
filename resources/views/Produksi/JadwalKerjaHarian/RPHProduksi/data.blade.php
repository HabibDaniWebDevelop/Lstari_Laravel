<div class="card-body" style="zoom:100%">

    <div class="card-body">
        <div class="row alert alert-info">
            <div class="col-md-9">
                <span>
                    {{-- <button class="btn btn-warning btn-sm" id="btnsimpan" disabled onclick="klikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button> --}}
                    <button class="btn btn-warning btn-sm" id="btnsimpan" disabled onclick="cekSPK()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

                    <button class="btn btn-danger btn-sm" id="btnbatal" disabled onclick="klikBatal()"><span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button class="btn btn-primary btn-sm" id="btnbaru" onclick="klikBaru()"><span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru</button>
                    <button class="btn btn-primary btn-sm" id="btnubah" disabled onclick="klikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
                    <button class="btn btn-primary btn-sm" id="btnlihat" onclick="klikLihat()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                    <button class="btn btn-info btn-sm" id="btncetak" disabled onclick="klikCetak()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    {{-- <button class="btn btn-danger btn-sm" id="btntest" onclick="cekSPK()">Test IT</button> --}}
                    <button class="btn btn-primary btn-sm float-end" id="btnclear" onclick="klikClear()"><span class="tf-icons bx bx-search-alt-2"></span>&nbsp; Clear</button>
                </span>
            </div>
            <div class="col-md-3">
                <input class="form-control form-control-sm" list="datalistNTHKO" name="idcari" id="idcari" placeholder="Cari" onchange="klikLihat()">
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
        <div class="row alert alert-info">
            <div class="col-md-4">
                <span>
                    <button type="button" class="btn btn-info btn-sm">ID <span class="badge badge-light" id="idmnya">0</span></button>
                    <button type="button" class="btn btn-dark btn-sm" onclick="klikPosting()" id="btnposting" disabled><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>	
                    <button type="button" class="btn btn-dark btn-sm" onclick="klikForm()" id="btnform" disabled><span class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar List</button>
                </span>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <div class="col-md-12">
                        <input type="date" name="tglRPH" id="tglRPH" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" disabled>	
                        <input type="hidden" name="update" id="update" value="">	
                        <input type="hidden" name="idmnya1" id="idmnya1" value="">
                        <input type="hidden" name="pilih" id="pilih" value="">	
                        <input type="hidden" name="cekspk" id="cekspk" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group row">
                    <div class="col-md-12">
                        <select class="form-select form-select-sm" id="proses" name="proses" disabled>
                            <option value="">Proses</option>
                            @foreach($data2 as $datas2)
                            <option value="{{ $datas2->Product }},{{ $datas2->ID }}">{{ $datas2->Description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <input type="text" name="catatan" id="catatan" placeholder="Catatan" class="form-control form-control-sm" disabled>	
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
  

  
  
  
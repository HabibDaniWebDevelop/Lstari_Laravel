<div class="card-body">
                    
        <!-- 1st Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="row alert alert-info">
                    <div class="col-md-2">
                        <label class="col-form-label-sm">Jenis Laporan :</label>
                        <select class="form-select form-select-sm" name="jenis" id="jenis" onchange="showInput(this.value)">
                            <option value="">Pilih</option>
                            {{-- <option value="1">Per RPH</option>
                            <option value="2">Per Tgl</option>
                            <option value="3">Per Tgl Percentage</option>
                            <option value="4">SPKO</option>
                            <option value="5">Laporan Jml & Brt SPKO, Per Tgl Per Operator</option>
                            <option value="6">Laporan Jml & Brt NTHKO, Per Tgl Per Operator</option> --}}
                            <option value="7">Laporan Per Operator</option>
                            <option value="8">Laporan Per Kadar</option>
                            <option value="9">Laporan Per Kategori</option>
                            <option value="10">Laporan Per SubKategori</option>
                            <option value="11">Laporan Per Operation</option>
                            <option value="12">All</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Jenis Cetak :</label>
                        <select class="form-select form-select-sm" name="jeniscetak" id="jeniscetak">
                            <option value="1">Detail</option>
                            <option value="2">Summary</option>
                        </select>
                    </div>
                    {{-- @if($statuslocation == 0)
                    <div class="col-md-2">
                        <label class="control-label">Area :</label>
                        <select class="form-select" name="area" id="area">
                            <option value="">Pilih</option>
                            @foreach($dataArea as $datasArea)
                                <option value="{{ $datasArea->ID }},{{$datasArea->Department}}" >{{ $datasArea->Description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="control-label">Info :</label>
                        <button type="button" class="btn btn-primary btn-sm" onclick="klikArea()">Tampilkan</button>
                        <select class="form-select" name="area" id="area">
                            <option value="">Pilih</option>
                            @foreach($dataArea as $datasArea)
                                <option value="{{ $datasArea->ID }},{{$datasArea->Department}}" >{{ $datasArea->Description }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif --}}
                </div>  
            </div>
        </div>
        <div class="row" id="rowinput" style="display:none;">
            <div class="col-md-12">
                <div class="row alert alert-info">
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Tgl Start SPKO :</label>
                        <input type="date" class="form-control form-control-sm" name="tgl1" id="tgl1">
                    </div> 
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Tgl End SPKO :</label>
                        <input type="date" class="form-control form-control-sm" name="tgl2" id="tgl2">
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">RPH :</label>
                        <select class="form-select form-select-sm" name="rph" id="rph"> 
                            <option value="" >Pilih</option>
                            @foreach($data as $datas)
                                <option value="{{ $datas->ID }}" >{{ $datas->ID }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Operator :</label><br>
                        <select class="form-select form-select-sm" name="operator" id="operator">
                            <option value="" >All</option>
                            @foreach($data4 as $datas4)
                                <option value="{{ $datas4->ID }}" >{{ $datas4->Description }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Kadar :</label>
                        <select class="form-select form-select-sm " name="kadar" id="kadar"> 
                            <option value="" >All</option>
                            @foreach($data2 as $datas2)
                                <option value="{{ $datas2->ID }}" >{{ $datas2->Description }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Kategori :</label><br>
                        <select class="form-select form-select-sm" name="kategori" id="kategori">
                            <option value="" >All</option>
                            @foreach($data5 as $datas5)
                                <option value="{{ $datas5->ID }}" >{{ $datas5->Description }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">SubKategori :</label><br>
                        <select class="form-select form-select-sm myselect" style="width: 100%" name="subkategori" id="subkategori">
                            <option value="" >All</option>
                            @foreach($data6 as $datas6)
                                <option value="{{ $datas6->ID }}" >{{ $datas6->SW }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-1">
                        <label class="col-form-label-sm">Operation :</label>
                        <select class="form-select form-select-sm" name="operation" id="operation"> 
                            <option value="" >All</option>
                            @foreach($data3 as $datas3)
                                <option value="{{ $datas3->ID }}" >{{ $datas3->Description }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-4">
                        <label class="control-label" style="color: d1ecf1"></label><br>
                        <button type="button" class="btn btn-primary btn-sm" onclick="klikLaporanRPH()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
                        <button type="button" class="btn btn-info btn-sm" onclick="klikCetakLaporan()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2nd Row -->
        <div class="row">
            <div class="col-md-12">
                <div id="tampil"></div>
            </div>
        </div>
  
</div>
  
@include('Produksi.Informasi.JadwalKerjaHarian.modal')

  
  
  
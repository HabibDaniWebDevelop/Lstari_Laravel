{{-- DATATABLE --}}
{{-- <div class="card-body">
                    
    <!-- 1st Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="row alert alert-primary">
                <div class="col-md-2">
                    <label class="col-form-label-sm">Jenis Laporan :</label>
                    <select class="form-select form-select-sm" name="jenis" id="jenis" onchange="showInput(this.value)">
                        <option value="">Pilih</option>
                        <option value="1">Per Kadar</option>
                        <option value="2">Per Operation</option>
                        <option value="3">Per Operator</option>
                        <option value="4">Per Kategori</option>
                        <option value="5">Per SubKategori</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="col-form-label-sm">Jenis Cetak :</label>
                    <select class="form-select form-select-sm" name="jeniscetak" id="jeniscetak">
                        <option value="1">Detail</option>
                        <option value="2">Summary</option>
                    </select>
                </div>
            </div>  
        </div>
    </div>
    <div class="row" id="rowinput" style="display:none;">
        <div class="col-md-12">
            <div class="row alert alert-info">
                <div class="col-md-1">
                    <label class="col-form-label-sm">Tgl Start SPKO :</label>
                    <input type="date" class="form-control form-control-sm" name="tgl1" id="tgl1" value="@php date('Y-m-d') @endphp">
                </div> 
                <div class="col-md-1">
                    <label class="col-form-label-sm">Tgl End SPKO :</label>
                    <input type="date" class="form-control form-control-sm" name="tgl2" id="tgl2">
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
                    <label class="col-form-label-sm">Operation :</label>
                    <select class="form-select form-select-sm" name="operation" id="operation"> 
                        <option value="" >All</option>
                        @foreach($data3 as $datas3)
                            <option value="{{ $datas3->ID }}" >{{ $datas3->Description }}</option>
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
                <div class="col-md-4">
                    <label class="control-label" style="color: d1ecf1"></label><br>
                    <button type="button" class="btn btn-primary btn-sm" onclick="klikLaporan()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>
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

</div> --}}



{{-- DEV EXTREME --}}
<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label">Jenis Report :</label>
            <select class="form-select" name="jenisreport" id="jenisreport">
                <option value="">Pilih</option>
                <option value="1">Performance Output</option>
                <option value="2">Peringkat Produktivitas Operator</option>
                <option value="3">Performance Produktivitas Tiap Operator</option>
                <option value="4">Performance Produktivitas Tiap Operator Secara Harian</option>
                <option value="5">Data Cycle Time RAW</option>
                <option value="6">Master Cycle Time RAW</option>
                <option value="7">Data Cycle Time FILTER</option>
                <option value="8">Master Cycle Time FILTER</option>
            </select>
        </div>
        <div class="col-2">
            <label class="form-label">Operation :</label>
            <select class="form-select" name="operation" id="operation">
                <option value="">Pilih</option>
                <option value="1">Poles Manual</option>
                <option value="2">Ultrasonic + Kering Poles Manual</option>
                <option value="3">Pemilahan Hasil Proses Manual</option>
                <option value="4">QC Poles Manual</option>
               
            </select>
        </div>
        <div class="col-2">
            <label class="form-label" for="tglstart">Tanggal :</label>
            <input type="date" class="form-control" id="tglstart">
        </div>
        <div class="col-2">
            <label class="form-label" for="tglend">Hingga :</label>
            <div class="input-group">
                <input type="date" class="form-control" id="tglend">
                <button class="btn btn-primary" onclick="reportAll()">Search</button>
            </div>
        </div>
        {{-- <div class="col-2">
            <label class="form-label" style="color: white">Chart :</label><br>
            <button class="btn btn-primary" id="btnchart" onclick="tampilChart()" disabled>Tampil Chart</button>
        </div> --}}
    </div>
    <br>

    <div id="tampil" style="overflow-x:scroll; white-space: nowrap;"> 
    </div> 

    {{-- <hr style="height:1px; color: #000000; padding-top: 10px; padding-bottom: 10px; display: block;"> --}}

    <div class="row" style="padding-top: 30px">
        {{-- <div class="col-3" id="chart1"></div> --}}
        <div class="col-12" id="chart2"></div>
        {{-- <div class="col-4" id="chart3"></div> --}}
    </div>

    <div class="row">
        <div class="col-6" id="chartApp1"></div>
        <div class="col-6" id="chartApp2"></div>
    </div>
    
</div>




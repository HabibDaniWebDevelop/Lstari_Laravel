<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="demo-inline-spacing mb-4">
                    <button type="button" class="btn btn-primary " id="Baru1" onclick="Klik_Baru1()"> <span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    {{-- <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled onclick="Klik_Ubah1()">
                        <span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button> --}}
                    <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                            class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button type="button" class="btn btn-warning" id="Simpan1" disabled onclick="Klik_Simpan1()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    {{-- <button type="button" class="btn btn-dark me-4" id="Posting1" disabled onclick="Klik_Posting1()">
                        <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button> --}}
                    <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    <div class="float-end">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' list="carilist"
                                onchange="ChangeCari(0)" />
                        </div>
                        <datalist id="carilist">
                            @foreach ($carilists as $carilist)
                            <option value="{{ $carilist->ID }}">{{ $carilist->ID }}</option>
                            @endforeach
                        </datalist>
                        <input type="hidden" id="idcetak" value="">
                    </div>
                    <hr class="m-0" />
                </div>
                <form id="form1">
                    <div class="row" id="filters">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="sw">ID</label>
                                    <input class="form-control" id="sw" type="text" name="sw" value="" disabled="true">
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="tgl">Tanggal</label>
                                    <input class="form-control" id="tgl" type="date" name="tgl" value="{{ date('Y-m-d'); }}"
                                        disabled="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="tgl">Kepada</label>
                                    <select class="form-select" id="employee" name="employee" disabled="true">
                                        @foreach ($employees as $employee)
                                        <option value="{{ $employee->ID }}" selected>{{ $employee->Description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="jenis">Jenis</label>
                                    <select class="form-select" id='jenis' name='jenis' onchange="pilihjenis()" disabled="true">
                                        <option value="0">Pilih</option>
                                        <option value="GT" selected>GT</option>
                                        <option value="Cor">Cor</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="komponen">Komponen</label>
                                    <select class="form-select" id='komponen' name='komponen' onchange="getKomponen()" disabled="true">
                                        <option value="" selected>Pilih</option>
                                        <option value="Patri">Patri</option>
                                        {{-- @foreach($data as $dataOK)
                                        <option value="{{$dataOK->Tanggal}}" 
                                            @if($dataOK->SWOrdinal == date('m'))
                                                selected
                                            @else
                                                
                                            @endif
                                            >{{$dataOK->Bulan}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="id">Catatan</label>
                            <input class="form-control" id="note" type="text" name="note" value="" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row" id="datas" style="display:none;">
                            <div class="col-sm-12">
                                <div id="tampil"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datepicker/bootstrap-datepicker.min.js') !!}"></script>
<script>
    
</script>


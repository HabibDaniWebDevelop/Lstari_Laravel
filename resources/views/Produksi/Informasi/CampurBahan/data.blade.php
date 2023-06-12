<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-12 col-xl-12">
                    <div class="row" id="filters">
                        <div class="col-3">
                            <select class="form-select" id='jenis' name='jenis' onchange="setjenis()">
                                <option value="0">Pilih</option>
                                <option value="1" selected>Estimasi Kebutuhan Komponen Kode Baru</option>
                                <option value="3">Estimasi Kebutuhan Komponen Kode Lama</option>
                                <option value="2">Permintaan Komponen</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <input type="text" class="form-control" id="tahunnya" autocomplete="off" value="{{date('Y');}}">
                        </div>
                        <div class="col-1">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='bulannya' name='bulannya' disabled="true">
                                <option value="0" selected>Pilih Bulan</option>
                                @foreach($data as $dataOK)
                                <option value="{{$dataOK->SWOrdinal}}" 
                                    @if($dataOK->SWOrdinal == date('m'))
                                        selected
                                    @else
                                        
                                    @endif
                                    >{{$dataOK->Bulan}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-1"> --}}
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            {{-- <select class="form-select" id='kadar' name='kadar'>
                                <option value="0" selected>Pilih Kadar</option>
                                @foreach($kadar as $dataOK)
                                <option value="{{$dataOK->ID}}" >{{$dataOK->Kadar}}</option>
                                @endforeach
                                
                            </select>
                        </div> --}}
                        {{-- <div class="col-1">
                            <input type="number" class="form-control" id="id1" name="id1" placeholder="Dari ID" disabled="true">
                        </div>
                        <div class="col-1">
                            <input type="number" class="form-control" id="id2" name="id2" placeholder="Hingga ID" disabled="true">
                        </div>--}}
                        <div class="col-1">
                            <input type="date" class="form-control" id="tanggal1" name="tanggal1"  disabled="true">
                        </div>
                        <div class="col-1">
                            <input type="date" class="form-control" id="tanggal2" name="tanggal2"  disabled="true">
                        </div> 
                        <div class="col-1">
                            <select class="form-select" id='non' name='non'>
                                <option value="0" selected>Pilih Tipe SPK</option>
                                <option value="O">O</option>
                                <option value="Non O">Non O</option>
                            </select>
                        </div> 
                        <div class="col-1">
                            <button type="button" class="btn btn-primary" id="btn-tampilkan" onclick="getSPKProduksi()">Tampilkan</button>
                        </div>
                    
                    {{-- <div class="row" id="datas" style="display:none;">
                        <div class="col-sm-12">
                            <div id="tampil">
                            </div>
                        </div>
                    </div> --}}
                    <div class="row" id="datas" style="padding-right:0px;">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="tampil">
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding:0px;">
                                    <div id="tampil2" style="display:none; margin-top:15px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="idworkorder" value="">
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datepicker/bootstrap-datepicker.min.js') !!}"></script>
<script>
$("#tahunnya").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    autoclose:true
}); 
</script>


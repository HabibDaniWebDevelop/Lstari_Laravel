<div class="col-md-12">
    <div class="card mb-4">
        {{-- @include('Akunting.Informasi.StokAkhirBulan.data') --}}
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-12 col-xl-12">
                    <div class="row" id="filters">
                        <div class="col-2">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='area' name='area'>
                                <option value="0" selected>Pilih Area</option>
                                <option value="1" >2D</option>
                                <option value="2" >3D</option>
                                <option value="3" >3D Printing</option>
                                <option value="4" >Perak</option>
                                <option value="5" >Karet</option>
                            </select>
                        </div>
                        <div class="col-2">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='jenis' name='jenis' onchange="setpilihan()">
                                <option value="0" selected>Pilih Jenis Info</option>
                                <option value="1" >Produktivitas Tahunan</option>
                                <option value="2" >Produktivitas Bulanan</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <input type="text" class="form-control" id="tahunnya" autocomplete="off" value="{{date('Y');}}">
                        </div>
                        <div class="col-1">
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
                        <div class="col-1">
                            <button type="button" class="btn btn-primary" id="btn-tampilkan" onclick="gettingInfoProduktivitas()">Tampilkan</button>
                        </div>
                    <div class="row" id="datas" style="display:none;">
                        <div class="col-sm-12">
                            <div id="tampil"></div>
                        </div>
                    </div>
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


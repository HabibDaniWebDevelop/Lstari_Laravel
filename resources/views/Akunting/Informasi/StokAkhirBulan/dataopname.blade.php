<div class="col-md-12">
    <div class="card mb-4">
        {{-- @include('Akunting.Informasi.StokAkhirBulan.data') --}}
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="form-label">Tahun</label>
                                    <input type="text" class="form-control" id="tahunnya2" autocomplete="off">
                                    {{-- <select class="form-select" id='tahunnya' name='tahunnya' onclick="setyear()">
                                        <option value="0" selected>Pilih</option>
                                        <option value="21" >2021</option>
                                        <option value="22" >2022</option>
                                    </select> --}}
                                </div>
                                <div class="col-md-2">
                                    <div id="selectone2">
                                        <label class="form-label">Bulan</label>
                                        <select class="form-select" id="bulannya2">
                                            <option value="0" selected>Pilih</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" onclick="gettingStokAkhirBulanOpname()">Tampilkan</button>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="tampil2"></div>
                                </div>
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
    $("#tahunnya2").datepicker({
            onSelect: setyear,
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose:true
        });  
</script>
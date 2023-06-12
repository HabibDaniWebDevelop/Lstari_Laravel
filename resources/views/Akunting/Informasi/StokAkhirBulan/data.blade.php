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
                                    <input type="text" class="form-control" id="tahunnya" autocomplete="off">
                                    {{-- <select class="form-select" id='tahunnya' name='tahunnya' onclick="setyear()">
                                        <option value="0" selected>Pilih</option>
                                        <option value="21" >2021</option>
                                        <option value="22" >2022</option>
                                    </select> --}}
                                </div>
                                <div class="col-md-2">
                                    <div id="selectone">
                                        <label class="form-label">Bulan</label>
                                        <select class="form-select">
                                            <option value="0" selected>Pilih</option>
                                        </select>
                                    </div>
                                    <div id="options" style="display:none;"></div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" onclick="gettingStokAkhirBulan()">Tampilkan</button>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="tampil"></div>
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
     $("#tahunnya").datepicker({
            onSelect: setyear,
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose:true
    });  
    
    $('#tahunnya').on('changeDate', function (e) {
            setyear();
    });

    function setyear(){
        var thn = $("#tahunnya").val();
        var data = {thn:thn};
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
                url: '/Akunting/Informasi/StokAkhirBulan/setYear',            
                dataType : 'json',
                type : 'GET',
                data:data,
                success: function(data)
                {
                    //console.log(data.html);
                    $("#options").html(data.html);
                    document.getElementById("selectone").style.display = "none";
                    document.getElementById("options").style.display = "block";
                    
                },
                            
            });
    }
</script>


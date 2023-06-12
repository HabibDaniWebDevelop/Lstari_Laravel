<div class="col-md-12">
    <div class="card mb-4">
        {{-- @include('Akunting.Informasi.StokAkhirBulan.data') --}}
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-12 col-xl-12">
                    <div class="row" id="filters">
                        <div class="col-2">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='jenis' name='jenis' onchange="pilihjenis()">
                                <option value="0" selected>Jenis Info</option>
                                <option value="1" >Target Permintaan Produksi</option>
                                <option value="2" >Permintaan Produksi</option>
                            </select>
                        </div>
                        <div class="col-1">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='bulannya' name='bulannya' disabled="true">
                                <option value="0" selected>Pilih Bulan</option>
                                @foreach($data as $dataOK)
                                <option value="{{$dataOK->Tanggal}}" 
                                    @if($dataOK->SWOrdinal == date('m'))
                                        selected
                                    @else
                                    @endif
                                    >{{$dataOK->Bulan}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-1">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='kategori' name='kategori' disabled="true">
                                <option value="0" selected>Pilih Kategori</option>
                                @foreach($kate as $dataOK)
                                <option value="{{$dataOK->Kategori}}" >{{$dataOK->Kategori}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-1">
                            {{-- <input type="text" class="form-control" id="tahunnya" autocomplete="off"> --}}
                            <select class="form-select" id='kadar' name='kadar' disabled="true">
                                <option value="0" selected>Pilih Kadar</option>
                                @foreach($kadar as $dataOK)
                                <option value="{{$dataOK->ID}}" >{{$dataOK->Kadar}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <div class="col-1">
                            <input type="number" class="form-control" id="id1" name="id1" placeholder="Dari ID" disabled="true">
                        </div>
                        <div class="col-1">
                            <input type="number" class="form-control" id="id2" name="id2" placeholder="Hingga ID" disabled="true">
                        </div>
                        <div class="col-1">
                            <input type="date" class="form-control" id="tanggal1" name="tanggal1" disabled="true">
                        </div>
                        <div class="col-1">
                            <input type="date" class="form-control" id="tanggal2" name="tanggal2" disabled="true">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-primary" id="btn-tampilkan" onclick="gettingFormOrderProduksi()">Tampilkan</button>
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
    //  $("#tahunnya").datepicker({
    //         onSelect: setyear,
    //         format: "yyyy",
    //         viewMode: "years", 
    //         minViewMode: "years",
    //         autoclose:true
    // });  
    
    // $('#tahunnya').on('changeDate', function (e) {
    //         setyear();
    // });

    // function setyear(){
    //     var thn = $("#tahunnya").val();
    //     var data = {thn:thn};
    //     $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //     $.ajax({
    //             url: '/Penjualan/Informasi/FormOrderProduksi/setYear',            
    //             dataType : 'json',
    //             type : 'GET',
    //             data:data,
    //             success: function(data)
    //             {
    //                 //console.log(data.html);
    //                 $("#options").html(data.html);
    //                 document.getElementById("selectone").style.display = "none";
    //                 document.getElementById("options").style.display = "block";
                    
    //             },
                            
    //         });
    // }
</script>


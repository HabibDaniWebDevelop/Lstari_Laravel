{{-- Tab Gambar, Batu, Informasi --}}
<div class="row">
    <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12">
        <ul class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#gambar" aria-controls="gambar" aria-selected="true"> Gambar </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kebutuhanBatu" aria-controls="kebutuhanBatu" aria-selected="false"> Kebutuhan Batu </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#informasiProduk" aria-controls="informasiProduk" aria-selected="false"> Informasi Produk </button>
            </li>
        </ul>
    </div>
    <div class="col-xl-3 col-md-12 col-sm-12 col-xs-12">
        <div class="input-group input-group-merge float-end">
            <input type="text" class="form-control" placeholder="221002803" autofocus="" id="cari" value="{{$SWSPK}}" onchange="CariSPK()">
            <button class="btn btn-outline-primary" onclick="CariSPK()">Cari</button>
        </div>
    </div>
</div>
<hr class="mt-0">
{{-- End Tab Gambar, Batu, Informasi --}}
{{-- Keterangan --}}
<p>Keterangan : {{$spkNote}}</p>

{{-- Content Gambar, Batu, Informasi --}}
<div class="tab-content px-0 pt-1">
    {{-- Panel Gambar --}}
    <div class="tab-pane fade active show" id="gambar" role="tabpanel">
        <div class="row d-flex justify-content-center">
            <div class="col-xxl-3 col-xl-3 col-md-12 col-sm-12 col-xs-12">
                {{-- Items --}}
                <div class="list-group" id="listGroupSPK">
                    <div class="table-responsive">
                    @foreach ($data as $item)
                    <button type="button" onclick="DetailProductSPK({{$loop->iteration}},'{{$item->SW}}')" class="list-group-item list-group-item-action" id="spkItem_{{$loop->iteration}}">{{$item->SW}}</button>
                    @endforeach
                    </div>
                </div>
                {{-- End Item --}}
            </div>
            <div class="col-xxl-9 col-xl-9 col-md-12 col-sm-12 col-xs-12 mt-2" id="detailProductSPK">
                <h1>Please Select Item</h1>
            </div>
        </div>
    </div>
    {{-- End Panel Gambar --}}
    {{-- Panel Batu --}}
    <div class="tab-pane fade" id="kebutuhanBatu" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm datatable" id="tablekebutuhanbatu">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th> Produk </th>
                        <th> Batu </th>
                        <th> Kebutuhan </th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($stone as $item)
                        <tr>
                            <td>{{$item->SW}}</td>
                            <td>{{$item->Stone}}</td>
                            <td>{{$item->Total}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- End Panel Batu --}}
    {{-- Panel Informasi --}}
    <div class="tab-pane fade" id="informasiProduk" role="tabpanel">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered table-sm datatable" id="tableinfoproduk">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th> Produk </th>
                        <th> Qty </th>
                        <th> Kadar </th>
                        <th> Berat Perkiraan </th>
                        <th> UK. Potong </th>
                        <th> Logo </th>
                        <th> PSB </th>
                        <th> NOTE </th>
                        <th> Enamel </th>
                        <th> Slep </th>
                        <th> Marking </th>
                        <th> Ukuran </th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($infoProduct as $item)
                        <tr>
                            <td>{{$item->SW}}</td>
                            <td>{{$item->Qty}}</td>
                            <td>{{$item->Carat}}</td>
                            <td>{{$item->Weight}}</td>
                            <td>{{$item->BrtKunci}}</td>
                            <td>{{$item->Logo}}</td>
                            <td>{{$item->PSB}}</td>
                            <td>{{$item->Remarks}}</td>
                            <td>{{$item->Enamel}}</td>
                            <td>{{$item->Slep}}</td>
                            <td>{{$item->Marking}}</td>
                            <td>{{$item->UkRing}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- End Panel Informasi --}}
</div>
{{-- End Content Gambar, Batu, Informasi --}}

<script>
    $('#tablekebutuhanbatu, #tableinfoproduk').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true
    });

    var table = $('#tablekebutuhanbatu').DataTable();
    table.columns().iterator('column', function(ctx, idx) {
        $(table.column(idx).header()).append('<span class = "sort-icon" / > ');
    });
    var table = $('#tableinfoproduk').DataTable();
    table.columns().iterator('column', function(ctx, idx) {
        $(table.column(idx).header()).append('<span class = "sort-icon" / > ');
    });
</script>
<div class="table-responsive text-nowrap rounded-4" style="height: 70vh;">
    <form id="form1">
        <table class="table table-border table-hover table-sm rounded-4" id="tabel7">
            <div class="mb-3 ml-3">RPH LILIN : <span class="badge bg-dark"
                    style="font-size:16px;">{{ $tambahkomponendirect[0]->Rph }}</span></div>
            <thead class="table-secondary sticky-top zindex-2 rounded-4">
                <tr style="text-align: center">
                    <th>No</th>
                    <!-- <th>RPH Lilin</th> -->
                    <th>SPK Lilin</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Kaitan</th>
                    <th>Urut</th>
                </tr>
            </thead>
            <tfoot>

            </tfoot>
            {{-- {{ dd($DaftarProduct); }} --}}
            <tbody>
                @forelse ($tambahkomponendirect as $itemdirect)
                <tr id="{{ $loop->iteration }}" style="text-align: center">
                    <td hidden>
                        <input class="form-check-input-itemdrc" type="checkbox" name="id[]"
                            id="cek_{{ $loop->iteration }}" value="{{ $itemdirect->ID3d }}"
                            data-Rph="{{$itemdirect->Rph}}" data-SPK="{{ $itemdirect->SPK }}"
                            data-product="{{$itemdirect->IDprod}}" data-Qty="{{$itemdirect->Qty}}"
                            data-waxorder="{{$itemdirect->waxorder}}" data-waxorderord="{{ $itemdirect->waxorderord}}"
                            data-IDM="{{ $itemdirect->IDM }}" data-ordinalWOI="{{ $itemdirect->OrdinalWOI }}"
                            style="opacity:0; position:absolute; left:9999px;" checked disabled>
                    </td>
                    <td>{{ $loop->iteration }} </td>
                    <td hidden> <span class="badge bg-dark" style="font-size:14px;">{{ $itemdirect->Rph }}</span></td>
                    <td>{{ $itemdirect->WorkOrder }}</td>
                    <td> <span class="badge bg-primary" style="font-size:14px;">{{ $itemdirect->Product }}</span> <br>
                        {{ $itemdirect->Description }}</td>
                    <td>{{ $itemdirect->Qty }}</td>
                    <td>{{ $itemdirect->waxorder }}</td>
                    <td>{{ $itemdirect->waxorderord }}</td>
                </tr>
                @empty
                <div class="alert alert-danger" id="infoitemdirect">
                    Tidak Ada Komponen Direct Casting
                    <div></div>
                </div>
                @endforelse
            </tbody>
        </table>
        <input type="hidden" id="hiddenid3dp" value="">
    </form>
</div>
<div class="d-grid gap-2 d-md-flex justify-content-md-end px-4 mt-3">
    <button type="button" class="btn btn-primary mb-2 " onclick="SPKkomponen3DP()">Buat SPK 3DP</button>
    <button type="button" class="btn btn-outline-secondary mb-2" data-bs-dismiss="modal"> Close </button>
    <!-- <button type="button" onclick="printSPK3DP()">tes print</button> -->
</div>
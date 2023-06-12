<div class="row">
    <div class="col-md-8">
        <div class="mb-3 ml-3">RPH LILIN : <span class="badge bg-dark"
                style="font-size:16px;">{{ $tambahkomponendirect[0]->Rph }}</span></div>
    </div>
    <div class="col-md-3">
        <div class="input-group input-group-merge">
            <span id="basic-icon-default-fullname2" class="input-group-text" style="background-color: #F0F0F0;"> <span
                    class="mr-2">Total
                    Qty&nbsp;&nbsp;&nbsp;</span><i class="fas fa-equals"></i></span>
            <input class="form-control" style="text-align: center" id="totalQtyDC" type="number" name="totalQtyDC"
                value="" disabled="true">
            <!-- value berisi qty dari hasil penjumlahan total qty yang dipilih -->
        </div>
    </div>
</div>
<div class="table-responsive text-nowrap px-3" style="height: 45vh;">
    <form id="form1">
        <table class="table table-border table-hover table-sm" id="tabel7">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th>PILL</th>
                    <th>No</th>
                    <th>SPK Lilin</th>
                    <th>Product</th>
                    <th>Pcs</th>
                    <th>Pasang</th>
                    <th>Kaitan</th>
                    <th>Urut</th>
                </tr>
            </thead>
            <tfoot>

            </tfoot>
            {{-- {{ dd($DaftarProduct); }} --}}
            <tbody>
                @forelse ($tambahkomponendirect as $itemdirect)
                <tr class="klik4" id="{{ $loop->iteration }}" style="text-align: center">
                    <td>
                        <input class="form-check-input itemdc" type="checkbox" name="id[]"
                            id="tek_{{ $loop->iteration }}" value="{{ $itemdirect->ID3d }}"
                            data-Rph="{{ $itemdirect->Rph }}" data-SPK="{{ $itemdirect->SPK }}"
                            data-product="{{ $itemdirect->IDprod }}" data-qty="{{ $itemdirect->Qty }}"
                            data-Pasang="{{ $itemdirect->TQty }}" data-waxorder="{{ $itemdirect->waxorder }}"
                            data-waxorderord="{{ $itemdirect->waxorderord }}" data-IDM="{{ $itemdirect->IDM }}"
                            data-ordinalWOI="{{ $itemdirect->OrdinalWOI }}"
                            data-QtySatuPohon="{{ $itemdirect->QtySatuPohon }}"
                            data-workorder="{{ $itemdirect->WorkOrder }}" disabled>
                    </td>
                    <td>{{ $loop->iteration }} </td>
                    <td> <span class="badge bg-dark" style="font-size:14px;">{{ $itemdirect->WorkOrder }}</span></td>

                    <td> <span class="badge bg-primary" style="font-size:14px;">{{ $itemdirect->Product }}</span> <br>
                        {{ $itemdirect->Description }}</td>
                    <td>{{ $itemdirect->Qty }}</td>
                    <td>{{ $itemdirect->TQty }}</td>
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
    <button type="button" class="btn btn-primary mb-2 " onclick="SPKkomponen3DP()">Buat SPK ke 3DP</button>
    <button type="button" class="btn btn-outline-secondary mb-2" data-bs-dismiss="modal"> Close </button>
    <!-- <button type="button" onclick="printSPK3DP()">tes print</button> -->
</div>

<script>
$(".klik4").on('click', function(e) {
    // $('.klik').css('background-color', 'white');
    var id = $(this).attr('id');
    if ($(this).hasClass('table-primary')) {
        $(this).removeClass('table-primary');
        let itemQty = parseInt($('#tek_' + id).attr('data-qty'))
        let totalQty = parseInt($('#totalQtyDC').val())

        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty - parseInt(itemQty)
        $('#totalQtyDC').val(calculate)
        $('#tek_' + id).attr('checked', false);
    } else {
        $(this).addClass('table-primary');
        let itemQty = parseInt($('#tek_' + id).attr('data-qty'))
        let totalQty = parseInt($('#totalQtyDC').val())
        console.log(itemQty)
        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty + parseInt(itemQty)
        $('#totalQtyDC').val(calculate)
        $('#tek_' + id).attr('checked', true);
    }
    return false;
});
</script>
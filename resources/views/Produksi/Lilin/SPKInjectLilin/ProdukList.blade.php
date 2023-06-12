<div class="row">
    <div class="col-md-8 ms-3">
        <h5 class="modal-title" id="jodulmodal1">Daftar Produk</h5>
    </div>
    <div class="col-md-3 mb-3">
        <div class="input-group input-group-merge">
            <span id="basic-icon-default-fullname2" class="input-group-text" style="background-color: #F0F0F0;"> <span
                    class="mr-2">Total
                    Qty&nbsp;&nbsp;&nbsp;</span><i class="fas fa-equals"></i></span>
            <input class="form-control" style="text-align: center" id="totalQty" type="number" name="totalQty" value=""
                disabled="true">
            <!-- value berisi qty dari hasil penjumlahan total qty yang dipilih -->
        </div>
    </div>
</div>
<div class="col-xl-12 mb-3">
    <div class="table-responsive text-nowrap px-3" style="height: 60vh;">
        <form id="form1">
            <table class="table table-border table-hover table-sm" id="tabeldaftarproduk">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr style="text-align: center">
                        <th>PILL</th>
                        <!-- <th><input id="masterCheck" type="checkbox" /></th> -->

                        <th>No.SPK PPIC</th>
                        <th>Produk jadi</th>
                        <th width="20%">Qty</th>
                        <th>sisa</th>
                    </tr>
                </thead>
                <tfoot>

                </tfoot>
                {{-- {{ dd($DaftarProduct); }} --}}
                <tbody>
                    @forelse ($tabeltes as $DP)
                    <tr class="klik2" id="{{ $DP->IDWorkOrder }}" style="text-align: center">
                        <td>
                            <input class="form-check-input SPKPPIC" type="checkbox" name="id[]"
                                id="cek_{{ $DP->IDWorkOrder }}" value="{{ $DP->IDWorkOrder }}"
                                data-qty="{{ $DP->TQty }}" data-SWUsed="{{ $DP->SWUsed }}" disabled>
                        </td>

                        <td> <span class="badge bg-dark" style="font-size:14px;">{{ $DP->WorkOrder }}</span> </td>
                        <td> <span class="badge bg-primary" style="font-size:14px;">{{ $DP->Product }}</span> <br>
                            {{ $DP->Description }}
                        </td>
                        <td>{{ $DP->TQty }}</td>
                        <td>{{ $DP->Level4 }}</td>
                    </tr>
                    @empty
                    <div class="alert alert-danger">
                        Data Blog belum Tersedia.
                    </div>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end px-4 mt-3">
        <button type="button" class="btn btn-primary mb-2 " onclick="Prosesdata()">Proses</button>
        <button type="button" class="btn btn-outline-secondary mb-2" data-bs-dismiss="modal">
            Close
        </button>
    </div>
</div>

<script>
$(".klik2").on('click', function(e) {
    // $('.klik').css('background-color', 'white');
    var id = $(this).attr('id');
    if ($(this).hasClass('table-primary')) {
        $(this).removeClass('table-primary');
        let itemQty = parseInt($('#cek_' + id).attr('data-qty'))
        let totalQty = parseInt($('#totalQty').val())
        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty - parseInt(itemQty)
        $('#totalQty').val(calculate)
        $('#cek_' + id).attr('checked', false);
    } else {
        $(this).addClass('table-primary');
        let itemQty = parseInt($('#cek_' + id).attr('data-qty'))
        let totalQty = parseInt($('#totalQty').val())
        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty + parseInt(itemQty)
        $('#totalQty').val(calculate)
        $('#cek_' + id).attr('checked', true);
    }
    return false;
});
// $('.klik').css('background-color', 'white');
</script>
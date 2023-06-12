<div class="row">
    <div class="col-md-8 ms-3">
        <h5 class="modal-title" id="jodulmodal1">Daftar Produk</h5>
    </div>
    <div class="col-md-3 mb-3">
        <div class="input-group input-group-merge">
            <span id="basic-icon-default-fullname2" class="input-group-text" style="background-color: #F0F0F0;"> <span
                    class="mr-2">Total
                    Qty&nbsp;&nbsp;&nbsp;</span><i class="fas fa-equals"></i></span>
            <input class="form-control" style="text-align: center" id="totalQtyOrderItemInject" type="number"
                name="totalQty" value="" disabled="true">
            <!-- value berisi qty dari hasil penjumlahan total qty yang dipilih -->
        </div>
        <input type="hidden" id="rphitem" value="{{$tabeltes[0]->IDM}}">
        <input type="hidden" id="kadaritem" value="{{$tabeltes[0]->DesCarat}}">
        <input type="hidden" id="KadarID" value="{{$tabeltes[0]->Carat}}">
    </div>
</div>
<div class="col-xl-12 mb-3">
    <div class="table-responsive text-nowrap px-3" style="height: 30vh;">
        <form id="form1">
            <table class="table table-border table-hover table-sm" id="tabeldaftarproduk">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr style="text-align: center">
                        <th>PILL</th>
                        <!-- <th><input id="masterCheck" type="checkbox" /></th> -->
                        <th>Produk Jadi</th>
                        <th>Kadar</th>
                        <th>No.SPK</th>
                        <th>Item Produk</th>
                        <th width="20%">Qty</th>
                    </tr>
                </thead>
                <tfoot>

                </tfoot>
                {{-- {{ dd($DaftarProduct); }} --}}
                <tbody>
                    @forelse ($tabeltes as $DP)
                    <tr class="klik2" id="{{ $loop->iteration }}" style="text-align: center">
                        <td>
                            <input class="form-check-input daftarproduk" type="checkbox" name="id[]"
                                id="cek_{{ $loop->iteration }}" value="{{ $DP->waxorderord }}" data-qty="{{ $DP->Qty }}"
                                data-SWUsed="{{ $DP->SWUsed }}" data-Carat="{{ $DP->Carat }}" data-rph="{{ $DP->IDM }}"
                                disabled>
                        </td>
                        <td> <span class="badge bg-gray" style="font-size:14px;">{{ $DP->Product }}</span>
                            <br>
                            {{ $DP->Description }}
                        </td>
                        <td> {{ $DP->Carat }}</td>
                        <td> <span class="badge bg-dark" style="font-size:14px;">{{ $DP->WorkOrder }}</span> </td>
                        <td> <span class="badge bg-primary" style="font-size:14px;">{{ $DP->swPSJ }}</span>
                            <br>
                            {{ $DP->descriptionPSJ }}
                        </td>
                        <td>{{ $DP->Qty }}</td>
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
        <button type="button" class="btn btn-primary mb-2 " onclick="ProsesdataPohonan()">Proses</button>
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
        let totalQty = parseInt($('#totalQtyOrderItemInject').val())
        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty - parseInt(itemQty)
        $('#totalQtyOrderItemInject').val(calculate)
        $('#cek_' + id).attr('checked', false);
    } else {
        $(this).addClass('table-primary');
        let itemQty = parseInt($('#cek_' + id).attr('data-qty'))
        let totalQty = parseInt($('#totalQtyOrderItemInject').val())
        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty + parseInt(itemQty)
        $('#totalQtyOrderItemInject').val(calculate)
        $('#cek_' + id).attr('checked', true);
    }
    return false;
});
</script>
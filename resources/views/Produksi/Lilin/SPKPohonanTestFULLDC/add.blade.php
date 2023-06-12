<br>
<div class="table-responsive text-nowrap" style="height:calc(100vh - 550px);">
    <form id="form1">
        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2" style="center">
                <tr style="text-align: center">
                    <th>PILL</th>
                    <th>ID Produk</th>
                    <th>Kode Produk</th>
                    <th>Deskripsi</th>
                    <th>Kadar</th>
                    <th>Qty</th>

                </tr>
            </thead>
            <tfoot>

            </tfoot>
            {{-- {{ dd($DaftarProduct); }} --}}
            <tbody>
                @forelse ($datas as $data1)
                <tr class="klik2" id="{{ $loop->iteration }}" style="text-align: center">
                    <td><input type="checkbox" class="form-check-input" name="cekan[]" id="cek_{{ $loop->iteration }}"
                            value="{{$data1->ID }}" data-product="{{ $data1->Product }}" data-qty="{{ $data1->Qty }}"
                            data-carat="{{ $data1->Kadar }}" data-workorder="{{ $data1->WorkOrder }}"
                            data-WorOrderOrd="{{ $data1->WorkOrderOrd }}" data-WaxOrder="{{ $data1->WaxOrder }}"
                            data-WaxOrderOrd="{{ $data1->WaxOrderOrd }}" disabled /></td>
                    <td>{{ $data1->Product }}</td>
                    <td><span style="font-size: 14px" class="badge bg-dark">{{$data1->SW}}</span><br>{{$data1->SKU}}
                    </td>
                    <td>{{ $data1->Description}}</td>
                    <td>{{ $data1->Kadar}}</td>
                    <td>{{ $data1->Qty}}</td>
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

<div class="d-grid gap-2 d-md-flex justify-content-md-end px-4">

</div>

<script>
$(".klik2").on('click', function(e) {
    // $('.klik').css('background-color', 'white');
    var id = $(this).attr('id');
    if ($(this).hasClass('table-secondary')) {
        $(this).removeClass('table-secondary');
        let itemQty = parseInt($('#cek_' + id).attr('data-qty'))
        let totalQty = parseInt($('#totalQty').val())
        if (isNaN(totalQty)) {
            totalQty = 0
        }
        let calculate = totalQty - parseInt(itemQty)
        $('#totalQty').val(calculate)
        $('#cek_' + id).attr('checked', false);
    } else {
        $(this).addClass('table-secondary');
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
</script>
<div class="table-responsive text-nowrap" style="height:calc(100vh - 310px); margin-top:15px;">
    <table class="table table-border table-hover table-sm" id="tabelformorder" width="100%" border="0">
        <thead class="table-secondary sticky-top zindex-2">
            <t>
                <th width="5%" style="font-weight:bold; font-size:16px; text-align:center;">No.</th>
                <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Kode Produk</th>
                <th width="15%" style="font-weight:bold; font-size:16px; text-align:center;">Deskripsi</th>
                <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Kadar</th>
                <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Qty Order</th>
                <th width="5%" style="font-weight:bold; font-size:16px; text-align:center;">#</th>
            </t>
        </thead>
        <tbody>
            @forelse ($komponenlist as $dataOK)
                <tr class="klik" id="{{ $dataOK->ID }}">
                    <td width="5%" style="text-align:center;">{{ $loop->iteration }}.</td>
                    <td width="10%" style="text-align:center;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOK->SW }}</span></td>
                    <td width="15%" style="text-align:center; color:black;">{{ $dataOK->Description }}</td>
                    <td width="10%" style="text-align:center; color:black;">{{ $dataOK->Kadar }}</td>
                    <td width="10%" style="text-align:center;"><input class="form-control" type="number" id="qty{{ $loop->iteration }}" name="qty[]" value="1"></td>
                    <td width="5%" style="text-align:center;"><input type="checkbox" class="form-check-input" name="cekan[]" id="cek_{{ $dataOK->ID }}" value="{{ $dataOK->ID }}" data-kadar="{{$dataOK->IDKadar}}" data-product="{{ $dataOK->ID }}" data-sw="{{ $dataOK->SW }}" data-qty="1"></td>
                </tr>
            @empty
                <div class="alert alert-danger">
                    Data Belum Tersedia.
                </div>
            @endforelse
        </tbody>
    </table>
</div>
<script>
    $(".klik").on('click', function(e) {
        // $('.klik').css('background-color', 'white');
        var id = $(this).attr('id');
        if ($(this).hasClass('table-secondary')) {
            $(this).removeClass('table-secondary');
            $('#cek_' + id).attr('checked', false);
        } else {
            $(this).addClass('table-secondary');
            $('#cek_' + id).attr('checked', true);
        }
        return false;
    });
</script>
    
    
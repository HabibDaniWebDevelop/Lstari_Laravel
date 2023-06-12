<table id="tabel2" class="table table-border table-hover table-sm">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            <th width="3%">No.</th>
            <th width="10%">ID</th>
            <th>Nama Karyawan</th>
            <th width="25%">Tanggal</th>
            <th width="10%">Status</th>
            <th width="10%">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item->ID}}</td>
                <td>{{$item->Description}}</td>
                <td>{{$item->TransDate}}</td>
                <td>Masuk</td>
                <td class="currency">{{$item->nominal}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" align="right">Total: </td>
            <td class="currency">{{$totalSallary}}</td>
        </tr>
    </tbody>
</table>
<script>
    var items = $('.currency')
    items.each(function (i, item) {
        $(item).text(toRupiah($(item).text(), {floatingPoint: 0}))
    })
</script>
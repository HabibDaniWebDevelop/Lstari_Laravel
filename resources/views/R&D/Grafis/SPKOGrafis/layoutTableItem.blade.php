<table class="table table-bordered table-striped table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr class="text-center">
            <th width="5%"> No. </th>
            <th width="25%"> Product </th>
            <th width="10%"> Kadar </th>
            <th width="5%"> Jumlah </th>
            <th width="8%"> Berat </th>
            <th width="7">Perkiraan Pernah Dikerjakan</th>
            <th width="40%"> Gambar </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr class="text-center">
                <td>{{$loop->iteration}}</td>
                <td>{{$item->namaProduct}}</td>
                <td>{{$item->kadar}}</td>
                <td>{{$item->jumlah}}</td>
                <td><input class="form-control beratItem" id="beratItem_{{$loop->iteration}}" readonly type="text" value="{{$item->berat}}" style="background-color: #FCF3CF;" onchange="CalculateTotalWeight()" onkeydown="kliktimbang2('beratItem_{{$loop->iteration}}')"></td>
                <td>@if ($item->ProbablyDone) Iya @else Tidak @endif</td>
                <td><img src="{{Session::get('hostfoto')}}/image/{{$item->Photo}}.jpg" style="max-width: 500px; max-height: 200px;" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $("#tabel1").dataTable().fnDestroy()
    $('#tabel1').DataTable({
        scrollY: '40vh',
        scrollCollapse: true,
        paging: false,
        lengthChange: true,
        searching: false,
        ordering: false,
        info: false
    });
</script>
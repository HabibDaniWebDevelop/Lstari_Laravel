<table class="table table-bordered table-striped table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr class="text-center">
            <th> No. </th>
            <th> No. SPK </th>
            <th> Product </th>
            <th> Kadar </th>
            <th> Jumlah </th>
            <th> Berat </th>
            <th> Gambar </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($getTM->tmItem as $item)
            <tr class="text-center">
                <td>{{$loop->iteration}}</td>
                <td>{{$item->nomorSPK}}</td>
                <td>{{$item->namaProduct}}</td>
                <td>{{$item->kadar}}</td>
                <td>{{$item->jumlah}}</td>
                <td>{{$item->berat}}</td>
                <td><img src="{{Session::get('hostfoto')}}/image/{{$item->Photo}}.jpg" style="max-height: 200px;" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></td>
            </tr>
        @endforeach
    </tbody>
</table>
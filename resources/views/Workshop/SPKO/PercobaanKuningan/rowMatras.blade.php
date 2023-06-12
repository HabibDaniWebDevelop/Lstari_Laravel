<tr>
    <td style="vertical-align: top">
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <th>ID Matras</th>
                <th>Nama Matras</th>
                <th>Ukuran Matras</th>
            </thead>
            <tbody>
                <tr>
                    <td>{{$matras->ID}}</td>
                    <td>{{$matras->SW}}</td>
                    <td>{{$matras->UkuranMatras}}</td>
                </tr>
            </tbody>
        </table>
    </td>
    <td style="vertical-align: top">
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <th>No.</th>
                <th>product</th>
            </thead>
            <tbody>
                @foreach ($matras->Items as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->Product->SW}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </td>
    <td style="vertical-align: top">
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <th>ID Pisau</th>
                <th>Nama Pisau</th>
                <th>Bentuk</th>
            </thead>
            <tbody>
                @foreach ($matras->Items as $item)
                    @foreach ($item->knive as $knive)
                        <tr>
                            <td>{{$knive->ID}}</td>
                            <td>{{$knive->SW}}</td>
                            <td>{{$knive->Bentuk}}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </td>
</tr>
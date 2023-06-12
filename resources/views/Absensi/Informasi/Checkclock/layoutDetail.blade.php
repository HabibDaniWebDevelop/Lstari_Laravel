<table id="tabel2" class="table table-border table-hover table-sm">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            <th width="3%">No.</th>
            <th width="10%">ID</th>
            <th width="40%">Nama Karyawan</th>
            <th width="15%">Tanggal</th>
            <th width="15%">Jam</th>
            <th width="10%">Status</th>
            <th width="15%">Type</th>
            <th width="5%">Machine</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataChecklock as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item->ID}}</td>
                <td>{{$item->Description}}</td>
                <td>{{$item->TransDate}}</td>
                <td>{{$item->TransTime}}</td>
                <td>{{$item->Status}}</td>
                <td>
                    @if ($item->Type == 'F')
                        Absen Jari
                    @elseif ($item->Type == 'A')
                        Absen Wajah
                    @else
                        Absen Manual
                    @endif
                </td>
                <td>{{$item->Machine}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
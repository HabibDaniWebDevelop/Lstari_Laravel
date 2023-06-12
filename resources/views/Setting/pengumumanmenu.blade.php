
<table class="table table-border table-hover table-sm" id="tabel1">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            <th width='100'>Tanggal</th>
            <th>Oleh</th>
            <th>NOTE</th>
        </tr>
    </thead>
    <tbody>
 
        {{-- {{ dd($data) }} --}}
        @forelse ($pengumumans as $pengumuman)
        <tr id="{{ $pengumuman->ID }}">
            <td>{{ date('d-m-y', strtotime($pengumuman->TransDate)) }}</td>
            <td>{{ $pengumuman->UserName }}</td>
            <td>{{ $pengumuman->Note }}</td>
        </tr>
        @empty
        @endforelse

    </tbody>
</table>
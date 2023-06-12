<div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm rounded-4" id="tabel5">
        <thead class="table-secondary sticky-top zindex-2 rounded-4">
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Batu</th>
            </tr>
        </thead>
        <tfoot>

        </tfoot>
        {{-- {{ dd($query); }} --}}
        <tbody>
            @forelse ($TabelTotalBatu as $ttb)
            <tr id="{{ $ttb->Stone }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $ttb->Stone }}</span>
                </td>
                <td>{{ $ttb->Total }}</td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
            @endforelse
        </tbody>
    </table>
</div>
<div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm rounded-4" id="tabel3">
        <thead class="table-secondary sticky-top zindex-2 rounded-4">
            <tr>
                <th>No</th>
                <th>NO SPK</th>
                <th>Barang Jadi</th>
                <th>Inject</th>
                <th>Jenis Batu</th>
                <th>Pesan</th>
                <th>@</th>
                <th>Total</th>
                <th>Keterangan Batu</th>
            </tr>
        </thead>
        <tfoot>

        </tfoot>
        {{-- {{ dd($query); }} --}}
        <tbody>
            @forelse ($TabelBatu as $tb)
            <tr id="{{ $tb->IDM }}">
                <td>{{ $loop->iteration }}</td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tb->WorkOrder }}</span>
                </td>
                <td>{{ $tb->Product }}</td>
                <td>{{ $tb->Inject }}</td>
                <td>{{ $tb->Stone }}</td>
                <td>{{ $tb->Ordered }}</td>
                <td>{{ $tb->EachQty }}</td>
                <td>{{ $tb->Total }}</td>
                <td>{{ $tb->StoneNote }}</td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
            @endforelse
        </tbody>
    </table>
</div>
<div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm rounded-4" id="tabel4">
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
            @forelse ($TabelBatuLama as $tbl)
            <tr id="{{ $tbl->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tbl->WorkOrder }}</span>
                </td>
                <td>{{ $tbl->Product }}</td>
                <td>{{ $tbl->Inject }}</td>
                <td>{{ $tbl->Stone }}</td>
                <td>{{ $tbl->Ordered }}</td>
                <td>{{ $tbl->EachQty }}</td>
                <td>{{ $tbl->Total }}</td>
                <td>{{ $tbl->StoneNote }}</td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
            @endforelse
        </tbody>
    </table>
</div>
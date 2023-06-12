<div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2 rounded-4">
            <tr>
                <th>No</th>
                <th>NO SPK</th>
                <th>Produk</th>
                <th>Inject</th>
                <th>Qty</th>
                <th>Tok</th>
                <th>SC</th>
                <!-- <th>Tatakan</th> -->
                <th>Keterangan Batu</th>
                <th>Kaitan</th>
                <th>Urut</th>
            </tr>
        </thead>
        <tfoot>

        </tfoot>
        {{-- {{ dd($query); }} --}}
        <tbody>
            @forelse ($TabelItem as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $item->nospk }}</span>
                </td>
                <td>{{ $item->product }}</td>
                <td>{{ $item->Inject }}</td>
                <td>{{ $item->Qty }}</td>
                <td>{{ $item->Tok }}</td>
                <td>{{ $item->StoneCast }}</td>
                <!-- <td>{{ $item->Tatakan }}</td> -->
                <td>{{ $item->StoneNote }}</td>
                <td>{{ $item->WaxOrder }}</td>
                <td>{{ $item->WaxOrderOrd }}</td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
            @endforelse
        </tbody>
    </table>
</div>
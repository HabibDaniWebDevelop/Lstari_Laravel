<div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm rounded-4" id="tabel2">
        <thead class="table-secondary sticky-top zindex-2 rounded-4">
            <tr>
                <th>No</th>
                <th>ID Karet</th>
                <th>Model</th>
                <th>PCS</th>
                <th>Kadar</th>
                <th>Ukuran</th>
                <th>Digunakan</th>
                <th>Hasil OK</th>
                <th>Hasil SS</th>
                <th>Tanggal Buat</th>
                <th>Status</th>
                <th>Ukuran</th>
                <th>SC</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tfoot>

        </tfoot>
        {{-- {{ dd($query); }} --}}
        <tbody>
            @forelse ($TKaretDiPilih as $kdp)
            <tr id="{{ $kdp->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $kdp->Rubber }}</span>
                </td>
                <td>{{ $kdp->Product }}</td>
                <td>{{ $kdp->Pcs }}</td>
                <td><span class="badge" style="font-size:14px; background-color: {{$kdp->HexColor}}">{{$kdp->Kadar}}
                    </span>
                </td>
                <td>{{ $kdp->Size }}</td>

                <td>{{ $kdp->WaxUsage }}</td>
                <td>{{ $kdp->WaxCompletion }}</td>
                <td>{{ $kdp->WaxScrap }}</td>
                <td>{{ $kdp->TransDate }}</td>
                <td>{{ $kdp->STATUS }}</td>
                <td>{{ $kdp->StoneCast }}</td>
                <td>{{ $kdp->lokasi }}</td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
            @endforelse
        </tbody>
    </table>
</div>
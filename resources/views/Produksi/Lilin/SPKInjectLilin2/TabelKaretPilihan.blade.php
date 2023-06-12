<div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm rounded-4" id="tabel6">
        <thead class="table-secondary sticky-top zindex-2 rounded-4">
            <tr>
                <th>No</th>
                <th>ID Karet</th>
                <th>Model</th>
                <th>Pesan</th>
                <th>Pcs</th>
                <th>Kadar</th>
                <th>Ukuran</th>
                <th>Digunakan</th>
                <th>Hasil OK</th>
                <th>Hasil SS</th>
                <th>Tanggal buat</th>
                <th>Status</th>

                <th>SC</th>
                <th>Lokasi</th>
                <th>Activ</th>
                <th>SPKO Inject</th>
            </tr>
        </thead>
        <tfoot>

        </tfoot>

        <tbody>
            @forelse ($TabelKaretPilihan as $tkp)
            <tr id="{{ $tkp->ID }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tkp->ID }}</span>
                </td>
                <td>{{ $tkp->Product }}</td>
                <td>{{ $tkp->Qty }}</td>
                <td>{{ $tkp->Pcs }}</td>
                <td><span class="badge" style="font-size:14px; background-color: {{$tkp->HexColor}}">{{$tkp->Kadar}}
                    </span>
                </td>
                <td>{{ $tkp->Size }}</td>
                <td>{{ $tkp->WaxUsage }}</td>
                <td>{{ $tkp->WaxCompletion }}</td>
                <td>{{ $tkp->WaxScrap }}</td>
                <td>{{ $tkp->TransDate }}</td>
                <td>{{ $tkp->Status }}</td>

                <td>{{ $tkp->StoneCast }}</td>
                <td>{{ $tkp->lokasi }}</td>
                <td>{{ $tkp->Active }}</td>
                <td>{{ $tkp->WaxInjectOrder }}</td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
            @endforelse
        </tbody>
    </table>
</div>
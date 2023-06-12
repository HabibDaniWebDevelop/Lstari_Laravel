<table class="table table-border table-hover table-sm">
    <thead class="table-secondary sticky-top zindex-2">
        <tr style="text-align: center">
            <th>No.</th>
            <th>Tanggal</th>
            <th>Kode<br>Komputer</th>
            <th>Computer Name</th>
            <th>Type</th>
            <th>IP Address</th>
            <th>MAC Address</th>
            <th>Mainboard</th>
            <th>Processor</th>
            <th>Memory 1</th>
            <th>Memory 2</th>
            <th>Storage 1</th>
            <th>Storage 2</th>
            <th>Monitor</th>
            <th>VGA</th>
            <th>Keyboard</th>
            <th>Mouse</th>
            <th>Printer 1</th>
            <th>Printer 2</th>
            <th>WeightScale</th>
            <th>UPS</th>
            <th>Scanner</th>
            <th>Barcode Scanner</th>
            <th>Series</th>
            <th>Operating System</th>
            <th>Domain</th>
            <th>Antivirus</th>
            <th>User</th>
            <th>Status</th>
            <th>Note</th>
            <th>Supplier</th>
            <th>Purchase Date</th>
        </tr>
    </thead>
    <tbody>

        {{-- {{ dd($data2) }} --}}
        @forelse ($data2 as $data2s)
            <tr>
                <td>{{ $loop->iteration }} </td>
                <td>{{ $data2s->ED }}</td>
                <td style="text-align: center"><span class="badge bg-dark"
                        style="font-size:15px; font-weight:normal;">{{ $data2s->SW }}</span></td>
                <td>{{ $data2s->ComputerName }}</td>
                <td>{{ $data2s->Type }}</td>
                <td>{{ $data2s->IPAddress }}</td>
                <td>{{ $data2s->MACAddress }}</td>
                <td>{{ $data2s->Mainboard }}</td>
                <td>{{ $data2s->Processor }}</td>
                <td>{{ $data2s->Memory1 }}</td>
                <td>{{ $data2s->Memory2 }}</td>

                <td>{{ $data2s->Storage1 }}</td>
                <td>{{ $data2s->Storage2 }}</td>
                <td>{{ $data2s->Monitor }}</td>
                <td>{{ $data2s->VGA }}</td>
                <td>{{ $data2s->Keyboard }}</td>
                <td>{{ $data2s->Mouse }}</td>
                <td>{{ $data2s->Printer1 }}</td>
                <td>{{ $data2s->Printer2 }}</td>
                <td>{{ $data2s->WeightScale }}</td>
                <td>{{ $data2s->UPS }}</td>

                <td>{{ $data2s->Scanner }}</td>
                <td>{{ $data2s->BarcodeScanner }}</td>
                <td>{{ $data2s->Series }}</td>
                <td>{{ $data2s->OperatingSystem }}</td>
                <td>{{ $data2s->Domain }}</td>
                <td>{{ $data2s->Antivirus }}</td>
                <td>{{ $data2s->Employee }}</td>
                <td>{{ $data2s->STATUS }}</td>
                <td>{{ $data2s->Note }}</td>
                <td>{{ $data2s->Supplier }}</td>
                <td>{{ $data2s->PurchaseDate }}</td>

            </tr>
        @empty
            <div class="alert alert-danger">
                Data Blog belum Tersedia.
            </div>
        @endforelse

    </tbody>
</table>

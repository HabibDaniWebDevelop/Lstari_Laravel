<table class="table table-border table-hover table-sm">
    <thead class="table-secondary sticky-top zindex-2">
        <tr style="text-align: center">
            <th>No.</th>
            <th>Tanggal</th>
            <th>Kode<br>Komputer</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data2 as $data2s)
            <tr>
                <td>{{ $loop->iteration }} </td>
                <td>{{ $data2s->ED }}</td>
                <td>{{ $data2s->ED }}</td>
            </tr>
        @empty
            <div class="alert alert-danger">
                Data Belum Tersedia.
            </div>
        @endforelse

    </tbody>
</table>


<table class="table table-border table-hover table-sm" width="100%" id="tabelShow">
    <thead class="table-secondary sticky-top zindex-2">
        <tr style="text-align: center">
            <td style="font-weight: bold; color: black" width="10%">No</td>
            <td style="font-weight: bold; color: black" width="15%">ID</td>
            <td style="font-weight: bold; color: black" width="20%">Tgl Transaksi</td>
            <td style="font-weight: bold; color: black" width="20%">Divisi</td>
            <td style="font-weight: bold; color: black" width="15%">Total</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $dataOK)
            <?php
                $date1 = date("d/m/Y", strtotime($dataOK->TransDate));
            ?>
            <tr class="klikStatus" id="{{ $dataOK->ID }}" style="text-align: center">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $dataOK->ID }}</td>
                <td>{{ $date1 }}</td>
                <td>{{ $dataOK->EmpName }}</td>
                <td>{{ $dataOK->DeptName }}</td>
                <td>{{ number_format($dataOK->Total,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

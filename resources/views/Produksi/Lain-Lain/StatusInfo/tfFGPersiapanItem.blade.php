<table width="100%" border="1" class="table table-striped" id="tabelShow">
    <thead>
        <tr style="text-align: center">
            <td style="font-weight: bold; color: black" width="10%">No</td>
            <td style="font-weight: bold; color: black" width="15%">ID</td>
            <td style="font-weight: bold; color: black" width="20%">Tgl Transaksi</td>
            <td style="font-weight: bold; color: black" width="20%">Karyawan</td>
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

<table width="100%" border="1" class="table table-striped" id="tabelShow">
    <thead>
        <tr style="text-align: center">
            <td style="font-weight: bold; color: black" width="10%">No</td>
            <td style="font-weight: bold; color: black" width="15%">ID</td>
            <td style="font-weight: bold; color: black" width="15%">Tgl Transaksi</td>
            <td style="font-weight: bold; color: black" width="15%">Proses</td>
            <td style="font-weight: bold; color: black" width="15%">Operator</td>
            <td style="font-weight: bold; color: black" width="15%">Qty</td>
            <td style="font-weight: bold; color: black" width="15%">Brt</td>
        </tr>
    </thead>
    <tbody>
        
        @foreach ($data as $dataOK)
            <?php
                $date1 = date("d/m/Y", strtotime($dataOK->TransDate));
            ?>
            <tr style="text-align: center;" onclick=klikStatus({{$dataOK->ID}}, {{$jenis}})>
                <td>{{ $loop->iteration }} </td>
                <td>{{ $dataOK->ID }}</td>
                <td>{{ $date1 }}</td>
                <td>{{ $dataOK->Proses }}</td>
                <td>{{ $dataOK->OpName }}</td>
                <td>{{ number_format($dataOK->TargetQty,0) }}</td>
                <td>{{ number_format($dataOK->Weight,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



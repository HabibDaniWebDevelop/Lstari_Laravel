<table width="100%" border="1" class="table table-striped" id="tabelItem">
    <thead>
        <tr style="text-align: center">
            <td style="font-weight: bold; color: black" width="10%">No</td>
            <td style="font-weight: bold; color: black" width="15%">ID</td>
            <td style="font-weight: bold; color: black" width="15%">Tgl Transaksi</td>
            <td style="font-weight: bold; color: black" width="15%">Asal</td>
            <td style="font-weight: bold; color: black" width="15%">Tujuan</td>
            <td style="font-weight: bold; color: black" width="15%">Qty</td>
            <td style="font-weight: bold; color: black" width="15%">Brt</td>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $dataOK)

            @php        
                $date1 = date("d/m/Y", strtotime($dataOK->TransDate));
                $id = $dataOK->ID;
            @endphp
                
            <tr style="text-align: center;" onclick=klikDetail({{$id}})>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $dataOK->ID }}</td>
                <td>{{ $date1 }}</td>
                <td>{{ $dataOK->Asal }}</td>
                <td>{{ $dataOK->Tujuan }}</td>
                <td>{{ number_format($dataOK->TotalQty,0) }}</td>
                <td>{{ number_format($dataOK->TotalWeight,2) }}</td>
            </tr>

        @endforeach

    </tbody>
</table>
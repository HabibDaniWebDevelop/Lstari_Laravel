@if($jenis == 1)

   <table width="100%" border="1" class="table table-striped" id="tabelShow">
        <thead>
            <tr style="text-align: center">
                <td style="font-weight: bold; color: black" width="5%">No</td>
                <td style="font-weight: bold; color: black" width="15%">ID</td>
                <td style="font-weight: bold; color: black" width="20%">NTHKO</td>
                <td style="font-weight: bold; color: black" width="40%">Kadar</td>
                <td style="font-weight: bold; color: black" width="10%">Qty</td>
                <td style="font-weight: bold; color: black" width="10%">Brt</td>
            </tr>
        </thead>
        <tbody>

            @foreach ($data as $dataOK)
                 
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dataOK->IDM }}</td>
                    <td>{{ $dataOK->NTHKO }}</td>
                    <td>{{ $dataOK->Kadar }}</td>
                    <td>{{ number_format($dataOK->Qty,0) }}</td>
                    <td>{{ number_format($dataOK->Weight,2) }}</td>
                </tr>

            @endforeach

        </tbody>
    </table>
    
@elseif($jenis == 2)
@elseif($jenis == 3)
@elseif($jenis == 4)
@elseif($jenis == 5)
@elseif($jenis == 6)
@else
@endif





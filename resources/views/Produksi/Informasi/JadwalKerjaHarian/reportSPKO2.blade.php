<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="width:100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Operator</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Tgl Transaksi</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Qty</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Berat</th> 
            </tr>                     
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->ENAME}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TRANSDATE}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WEIGHT}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>


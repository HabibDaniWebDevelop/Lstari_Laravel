<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="width:100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Tgl RPH</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Jumlah RPH</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Jumlah SPKO</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Jumlah NTHKO</th>  
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">(SPKO/RPH)*100</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">(NTHKO/SPKO)*100</th>
            </tr>                     
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->ID}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->JmlRPH}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->JmlSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->JmlNTHKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->PercentSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->PercentNTHKO}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
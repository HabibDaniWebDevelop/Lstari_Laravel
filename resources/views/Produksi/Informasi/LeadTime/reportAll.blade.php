@php

@endphp

<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="table-layout:fixed">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">WorkLog</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">SPKO</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Tgl SPKO</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Kadar</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Operation</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Operator</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">FinishGood</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">SubKategori</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Kategori</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Qty SPKO</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Total Seconds</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Avg Time</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">WorkHour</th> 

            </tr>                     
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WORKLOGID}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->SPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TGLSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->CARAT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->OPERATION}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->EMPLOYEE}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->FG}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->SUBKATEGORI}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->KATEGORI}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->QTYSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TOTALSECONDS}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->AVGTIME}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WORKHOURPERCENT}}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
             
            </tr>
        </tfoot>
    </table>
</form>


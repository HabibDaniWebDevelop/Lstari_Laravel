@php
    foreach ($data2 as $datas2){
        $sumQty += $datas2->Qty;
        $sumPcs += $datas2->Pcs;
        $sumWeight += $datas2->Weight;
    }
@endphp
<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="width:100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Operator</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">SPKO</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">FG</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Kadar</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Operation</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Qty</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Pcs</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Berat</th> 
            </tr>                     
        </thead>
        <tbody>
            @php
                $sumQty = 0;
                $sumPcs = 0;
                $sumWeight = 0;
            @endphp
            @foreach ($data as $datas)
            {{-- @php
                $sumQty += $datas->Qty;
                $sumPcs += $datas->Pcs;
                $sumWeight += $datas->Weight;
            @endphp --}}
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Emp}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->SW}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->FGName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Kadar}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Operation}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Qty}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Pcs}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Weight}}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" align="right"><font color="#000000" weight="bold"><b>Total :</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>{{$sumQty}}</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>{{$sumPcs}}</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>{{$sumWeight}}</b></font></td>
            </tr>
        </tfoot>
    </table>
</form>


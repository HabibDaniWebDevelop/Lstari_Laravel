<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="width:100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Before</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">SPKO No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">SPKO Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Kadar</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Operation</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Operator</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH Qty</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH Brt</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">SPKO Qty</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">SPKO Brt</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Qty (Good)</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Brt (Good)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Qty (Not Good)</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Brt (Not Good)</th> 
            </tr>                     
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->NTHKO_BEFORE}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WS_ID}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WS_TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WA_SW}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WA_TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WC_ID}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WC_TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->CaratName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->OperationName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->EmpName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WS_QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WS_WEIGHT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WA_QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WA_WEIGHT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WC_QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WC_WEIGHT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WC_QTY_NG}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WC_WEIGHT_NG}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
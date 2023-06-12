<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="width:100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Before</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH ID</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">No SPKO</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">RPH Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">SPKO Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">NTHKO Tgl</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Jenis</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Kadar</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Operation</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Operator</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; width: 10% ">Pcs</th>
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
            @php
                $countRPH = 0;
                $countSPKO = 0;
            @endphp
            @foreach ($data as $datas)
            @php
                if($datas->ID != NULL){
                    $countRPH += 1;
                }
                if($datas->WAIIDM != NULL){
                    $countSPKO += 1;
                }
            @endphp
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->NTHKO_BEFORE}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WS_ID}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->NoSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->SPKO_TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->NTHKO_TransDate}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->PSW}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->CaratName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->OperationName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->EmpName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->PCS}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WS_QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WS_WEIGHT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WA_QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WA_WEIGHT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WC_QTY}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WC_WEIGHT}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WC_QTY_NG}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black">{{$datas->WC_WEIGHT_NG}}</td>
            </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <td colspan="16" align="right"><font color="#000000" weight="bold"><b>{{$datas->WC_WEIGHT}}</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>Jml RPH : {{$countRPH}}</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>Jml SPKO : {{$countSPKO}}</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>Persentase : {{ number_format(($countSPKO/$countRPH)*100,2) }}</b></font></td>
                <td align="center"><font color="#000000" weight="bold"><b>Jml RPH : {{$datas->JmlSPKODate}}</b></font></td>
            </tr>
        </tfoot> --}}
    </table>
</form>


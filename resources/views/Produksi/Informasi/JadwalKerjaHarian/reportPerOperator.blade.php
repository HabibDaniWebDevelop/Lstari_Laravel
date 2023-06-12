@php

@endphp

<form id="tampilform" >
    <table class="table text-nowrap" id="tampiltabel" style="table-layout:fixed">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">No</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Operator</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">No SPK</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">No SPKO</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Tgl SPKO</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Tgl NTHKO</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Kategori</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">SubKategori</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">FinishGood</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Kadar</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Operation</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Qty SPKO</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Brt SPKO</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Qty NTHKO (Good)</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Qty NTHKO (Rep)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Qty NTHKO (SS)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Brt NTHKO (Good)</th>
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Brt NTHKO (Rep)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Brt NTHKO (SS)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Persen (Good)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Persen (Rep)</th> 
                <th class="text-center" style="font-size: 11px; font-weight: bold; color: white; ">Persen (SS)</th> 
            </tr>                     
        </thead>
        <tbody>
            @php
                $sumQtyWA = 0;
                $sumWeightWA = 0;
                $sumQtyWC = 0;
                $sumWeightWC = 0;
                $sumQtyWCRep = 0;
                $sumWeightWCRep = 0;
                $sumQtyWCSS = 0;
                $sumWeightWCSS = 0;
            @endphp
            @foreach ($data as $datas)
            @php
                // $persen = number_format(($datas->GoodQtyNTHKO / $datas->QtySPKO)*100,2);
                // $persenRep = number_format(($datas->NoGoodQtyNTHKORep / $datas->QtySPKO)*100,2);
                // $persenSS = number_format(($datas->NoGoodQtyNTHKOSS / $datas->QtySPKO)*100,2);

                if($datas->GoodQtyNTHKO <> 0 && $datas->QtySPKO <> 0){
                    $persen = number_format(($datas->GoodQtyNTHKO / $datas->QtySPKO)*100,2);
                }else{
                    $persen = number_format(0,2);
                }
                if($datas->NoGoodQtyNTHKORep <> 0 && $datas->QtySPKO <> 0){
                    $persenRep = number_format(($datas->NoGoodQtyNTHKORep / $datas->QtySPKO)*100,2);
                }else{
                    $persenRep = number_format(0,2);
                }
                if($datas->NoGoodQtyNTHKOSS <> 0 && $datas->QtySPKO <> 0){
                        $persenSS = number_format(($datas->NoGoodQtyNTHKOSS / $datas->QtySPKO)*100,2);
                }else{
                        $persenSS = number_format(0,2);
                }

                $sumQtyWA += $datas->QtySPKO;
                $sumWeightWA += $datas->WeightSPKO;
                $sumQtyWC += $datas->GoodQtyNTHKO;
                $sumWeightWC += $datas->GoodWeightNTHKO;
                $sumQtyWCRep += $datas->NoGoodQtyNTHKORep;
                $sumWeightWCRep += $datas->NoGoodWeightNTHKORep;
                $sumQtyWCSS += $datas->NoGoodQtyNTHKOSS;
                $sumWeightWCSS += $datas->NoGoodWeightNTHKOSS;
            @endphp
            <tr>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Operator}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WOSW}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->NoSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TglSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TglNTHKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->Kategori}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->FDescription}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->FinishGood}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->CaratName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->OperationName}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->QtySPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->WeightSPKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->GoodQtyNTHKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->NoGoodQtyNTHKORep}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->NoGoodQtyNTHKOSS}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->GoodWeightNTHKO}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->NoGoodWeightNTHKORep}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->NoGoodWeightNTHKOSS}}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$persen}}%</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$persenRep}}%</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black" readonly>{{$persenSS}}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11" align="right" style="font-size: 18px; font-weight: bold; color: green">Total :</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumQtyWA}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumWeightWA}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumQtyWC}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumQtyWCRep}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumQtyWCSS}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumWeightWC}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumWeightWCRep}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green">{{$sumWeightWCSS}}</td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green"></td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green"></td>
                <td align="center" style="font-size: 16px; font-weight: bold; color: green"></td>
            </tr>
        </tfoot>
    </table>
</form>


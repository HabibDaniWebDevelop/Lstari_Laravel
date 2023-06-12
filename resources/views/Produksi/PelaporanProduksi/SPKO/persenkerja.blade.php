@php
    $no = 0;
    // $a = $data3[0]['employee_id']
    // foreach ($data3 as $datas3) {}
@endphp

<table width="100%" border="1" class="table table-hover text-nowrap" id="tampiltabelmodal">
    <thead>
        <tr bgcolor="black">
            <td align="center" style="font-weight: bold; color: white">No</td>
            <td align="center" style="font-weight: bold; color: white">Operation</td>
            <td align="center" style="font-weight: bold; color: white">Tanggal SPKO</td>
            {{-- <td align="center" style="font-weight: bold; color: white">ID Operator</td> --}}
            <td align="center" style="font-weight: bold; color: white">Operator</td>
            <td align="center" style="font-weight: bold; color: white">Do Ytd (%)</td>
            <td align="center" style="font-weight: bold; color: white">Done Ytd (%)</td>
            <td align="center" style="font-weight: bold; color: white">Remaining Ytd (%)</td>
            <td align="center" style="font-weight: bold; color: white">Do Today (%)</td>
            <td align="center" style="font-weight: bold; color: white">Done Today (%)</td>
            <td align="center" style="font-weight: bold; color: white">Remaining Today (%)</td>

        </tr>
    </thead>
    <tbody>
        
        @foreach ($data as $datas)
        @php 
            $workdate = date("d/m/y", strtotime($datas->WorkDate)); 
            foreach ($data2 as $datas2) {
                if($datas2->ID == $datas->AID && $datas2->Persen <> 0){
                    $sisaPercentYesterday = 100 - $datas2->Persen;
                    break;
                }else{
                    $sisaPercentYesterday = 0;
                }
            }
           
            // $sisaPercentToday = 100 - $sisaPercentYesterday - $datas->WorkPercent;
            // $sisaPercentToday = 100 - $datas->WorkPercent;

            if($datas->WorkPercent > 90){
                $color = 'blue';
            }elseif($datas->WorkPercent > 80 && $datas->WorkPercent <= 90){
                $color = 'green';
            }elseif($datas->WorkPercent > 70 && $datas->WorkPercent <= 80){
                $color = 'fuchsia';
            }elseif($datas->WorkPercent > 60 && $datas->WorkPercent <= 70){
                $color = 'orange';
            }else{
                $color = 'red';
            }
        @endphp

        @if($rowcount3 > 0)
            @foreach ($data3 as $datas3)
            @php
                if($datas->AID == $datas3->employee_id){
                    $persenSisa = $datas3->percent;
                    $sisaPercentToday = 100 - $datas->WorkPercent - $persenSisa;
                    if($sisaPercentToday >= 0){
                        $sisaPercentToday = $sisaPercentToday;
                    }else{
                        $sisaPercentToday = 0;
                    }
                    break;
                }else{
                    $persenSisa = 0;
                    $sisaPercentToday = 100 - $datas->WorkPercent - $persenSisa;
                    if($sisaPercentToday >= 0){
                        $sisaPercentToday = $sisaPercentToday;
                    }else{
                        $sisaPercentToday = 0;
                    }
                }
            @endphp
            @endforeach
        @else
            @php
                $persenSisa = 0;
                $sisaPercentToday = 100;
            @endphp
        @endif

        @foreach ($data4 as $datas4)
            @php
                if($datas->AID == $datas4->employee_id){
                    $persenSPKO = $datas4->percent;
                    break;
                }else{
                    $persenSPKO = 0;
                }
            @endphp
        @endforeach

        @foreach ($data6 as $datas6)
            @php
                if($datas->AID == $datas6->employee_id){
                    $persenDoneYesterday = $datas6->persentase_yesterday;
                    $persenDoneToday = $datas6->persentase_today;
                    break;
                }else{
                    $persenDoneYesterday = 0;
                    $persenDoneToday = 0;
                }
            @endphp
        @endforeach

        @php
            // $persenDone = $persenSPKO - $persenSisa;
        @endphp

        <tr style="background-color: {{$color}}">
            <td align="center" style="color: white; font-weight: bold">{{$loop->iteration}}</td>
            <td align="center" style="color: white; font-weight: bold">{{$datas->OperationName}}</td>
            <td align="center" style="color: white; font-weight: bold">{{$workdate}}</td>
            {{-- <td align="center" style="color: white; font-weight: bold">{{$datas->AID}}</td> --}}
            <td align="center" style="color: white; font-weight: bold">{{$datas->OperatorName}}</td>
            <td align="center" style="color: white; font-weight: bold">{{number_format($persenSPKO,2)}}</td>
            <td align="center" style="color: white; font-weight: bold">{{number_format($persenDoneYesterday,2)}}</td>
            <td align="center" style="color: white; font-weight: bold">{{number_format($persenSisa,2)}}</td>
            <td align="center" style="color: white; font-weight: bold">{{number_format($datas->WorkPercent,2)}}</td>
            <td align="center" style="color: white; font-weight: bold">{{number_format($persenDoneToday,2)}}</td>
            <td align="center" style="color: white; font-weight: bold">{{number_format($sisaPercentToday,2)}}</td>


        </tr>

        @endforeach
    </tbody>
    <tfoot>
        
    </tfoot>
</table>
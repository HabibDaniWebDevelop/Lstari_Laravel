<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Cetak</title>

    <style type="text/css" media="print">
        @media print {
    
            table {
                width: 100%;
                padding: 0px 5px 0px 5px;
                font-family: "Arial Narrow", Arial, sans-serif; 
            }
    
            td {
                font-size: 11px;
            }
    
            @page {
                size: portrait;
                margin: 0mm;
            }
        }
    
        #Header,
        #Footer {
            display: none !important;
        }
    
        .table{
            border:1px;
            padding:0px;
        }
    </style>
</head>
<body>
    @php    
        if($jenis == 7){
            $jenislaporan = 'Report Performance Per Operator';
            $grup = 'Operator';
            $grup2 = 'Operator';
        }else if($jenis == 8){
            $jenislaporan = 'Report Performance Per Kadar';
            $grup = 'CaratName';
            $grup2 = 'Kadar';
        }else if($jenis == 9){
            $jenislaporan = 'Report Performance Per Kategori';
            $grup = 'Kategori';
            $grup2 = 'Kategori';
        }else if($jenis == 10){
            $jenislaporan = 'Report Performance Per SubKategori';
            $grup = 'FDescription';
            $grup2 = 'SubKategori';
        }else if($jenis == 11){
            $jenislaporan = 'Report Performance Per Operation';
            $grup = 'OperationName';
            $grup2 = 'Operation';
        }
    @endphp
    <table width="100%">
        <tr>
            <td align="center" width="100%" style="font-size:15px;"><b>{{$jenislaporan}} : {{date('d/m/Y', strtotime($tglstart))}} - {{date('d/m/Y', strtotime($tglend))}}</b></td>
        </tr>
    </table>
    <table class="table" id="tampiltabel" style="width: 100%; border: 1px solid black;"> 
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 30%;">{{$jenislaporan}}</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Qty SPKO</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Brt SPKO</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Qty NTHKO (Good)</th> 
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Qty NTHKO (Rep)</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Qty NTHKO (SS)</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Brt NTHKO (Good)</th> 
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Brt NTHKO (Rep)</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Brt NTHKO (SS)</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Persen (Good)</th> 
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 7%;">Persen (Rep)</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223)">Persen (SS)</th>
            </tr>                     
        </thead>
        <tbody>
            @php
                $sumQtyWATotal = 0;
                $sumWeightWATotal = 0;
                $sumQtyWCTotal = 0;
                $sumWeightWCTotal = 0;
                $sumQtyWCRepTotal = 0;
                $sumWeightWCRepTotal = 0;
                $sumQtyWCSSTotal = 0;
                $sumWeightWCSSTotal = 0;

                function group_by($key, $data) {
                    $result = array();
                    foreach($data as $val) {
                        if(array_key_exists($key, $val)){
                            $result[$val[$key]][] = $val;
                        }else{
                            $result[""][] = $val;
                        }
                    }
                    return $result;
                }

                $byGroup = group_by("$grup", $rows);
            @endphp

        @foreach ($byGroup as $keyi => $dataku) 
        
            <tr bgcolor="yellow" style="black: white">
                <td style="border-bottom: 1px solid black; font-size : 11px; color: magenta;" colspan="12"><b>{{$grup2}} : {{$keyi}} ({{count($dataku)}})<b></td>
            </tr>

            @php
                $no = 1;
                $sumQtyWA = 0;
                $sumWeightWA = 0;
                $sumQtyWC = 0;
                $sumWeightWC = 0;
                $sumQtyWCRep = 0;
                $sumWeightWCRep = 0;
                $sumQtyWCSS = 0;
                $sumWeightWCSS = 0;
            @endphp

            @foreach ($dataku as $datas)
            @php
                $tglSPKO = date('d/m/y', strtotime($datas['TglSPKO']));
                $tglNTHKO = date('d/m/y', strtotime($datas['TglNTHKO'])); 

                // $persen = number_format(($datas['GoodQtyNTHKO'] / $datas['QtySPKO'])*100,2);  
                // $persenRep = number_format(($datas['NoGoodQtyNTHKORep'] / $datas['QtySPKO'])*100,2);  
                // $persenSS = number_format(($datas['NoGoodQtyNTHKOSS'] / $datas['QtySPKO'])*100,2);  

                if($datas['GoodQtyNTHKO'] <> 0 && $datas['QtySPKO'] <> 0){
                    $persen = number_format(($datas['GoodQtyNTHKO'] / $datas['QtySPKO'])*100,2);
                }else{
                    $persen = number_format(0,2);
                }
                if($datas['NoGoodQtyNTHKORep'] <> 0 && $datas['QtySPKO'] <> 0){
                    $persenRep = number_format(($datas['NoGoodQtyNTHKORep'] / $datas['QtySPKO'])*100,2);
                }else{
                    $persenRep = number_format(0,2);
                }
                if($datas['NoGoodQtyNTHKOSS']<> 0 && $datas['QtySPKO'] <> 0){
                        $persenSS = number_format(($datas['NoGoodQtyNTHKOSS'] / $datas['QtySPKO'])*100,2);
                }else{
                        $persenSS = number_format(0,2);
                }

                $sumQtyWA += $datas['QtySPKO'];
                $sumWeightWA += $datas['WeightSPKO'];
                $sumQtyWC += $datas['GoodQtyNTHKO'];
                $sumWeightWC += $datas['GoodWeightNTHKO'];
                $sumQtyWCRep += $datas['NoGoodQtyNTHKORep'];
                $sumWeightWCRep += $datas['NoGoodWeightNTHKORep'];
                $sumQtyWCSS += $datas['NoGoodQtyNTHKOSS'];
                $sumWeightWCSS += $datas['NoGoodWeightNTHKOSS'];
            @endphp
            {{-- <tr>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$no++}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['Operator']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['WOSW']}}</td> 
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['NoSPKO']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$tglSPKO}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$tglNTHKO}}</td> 
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['FDescription']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['FinishGood']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['CaratName']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['OperationName']}}</td> 
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['QtySPKO']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['WeightSPKO']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['GoodQtyNTHKO']}}</td> 
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['NoGoodQtyNTHKORep']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['NoGoodQtyNTHKOSS']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['GoodWeightNTHKO']}}</td> 
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['NoGoodWeightNTHKORep']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$datas['NoGoodWeightNTHKOSS']}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$persen}}</td> 
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: black)">{{$persenRep}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size: 12px; font-weight: bold; color: black">{{$persenSS}}</td>
            </tr> --}}
            @endforeach

            @php
                // dd($sumQtyWA);
                $sumQtyWATotal += $sumQtyWA;
                $sumWeightWATotal += $sumWeightWA;
                $sumQtyWCTotal += $sumQtyWC;
                $sumQtyWCRepTotal += $sumQtyWCRep;
                $sumQtyWCSSTotal += $sumQtyWCSS;
                $sumWeightWCTotal += $sumWeightWC;
                $sumWeightWCRepTotal += $sumWeightWCRep;
                $sumWeightWCSSTotal += $sumWeightWCSS;

                $persenTotal = number_format(($sumQtyWC / $sumQtyWA) * 100,2);
                $persenRepTotal = number_format(($sumQtyWCRep / $sumQtyWA) * 100,2);
                $persenSSTotal = number_format(($sumQtyWCSS / $sumQtyWA) * 100,2);
            @endphp

            @if($sumQtyWA == ($sumQtyWC + $sumQtyWCRep + $sumQtyWCSS))
                @php $colorstatus = 'blue'; @endphp
            @else
                @php $colorstatus = 'red'; @endphp
            @endif

            <tr bgcolor="#E9967A" style="black: white; font-weight: bold"><td style="border-bottom: 1px solid black; font-size : 11px; color: {{$colorstatus}};" colspan="1" align="left">Sub Total : </td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumQtyWA}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumWeightWA}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumQtyWC}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumQtyWCRep}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumQtyWCSS}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumWeightWC}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumWeightWCRep}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$sumWeightWCSS}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$persenTotal}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$persenRepTotal}}</td>
                <td style="border-bottom: 1px solid black; font-size : 12px; color: {{$colorstatus}};" align="center">{{$persenSSTotal}}</td>
                </td>
            </tr>

        @endforeach

        <tr bgcolor="#E9967A" style="black: white; font-weight: bold">
            <td style="font-size: 12px; color: green;" colspan="1" align="left">Grand Total : </td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumQtyWATotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumWeightWATotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumQtyWCTotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumQtyWCRepTotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumQtyWCSSTotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumWeightWCTotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumWeightWCRepTotal}}</td>
            <td style="font-size: 12px; color: green;" align="center">{{$sumWeightWCSSTotal}}</td>
            <td style="font-size: 12px; color: green;" align="center"></td>
            <td style="font-size: 12px; color: green;" align="center"></td>
            <td style="font-size: 12px; color: green;" align="center"></td>
        </tr>
        </tbody>
    </table>
	
</body>
</html>

<script>
    window.onload = function(){
        window.print();
        setTimeout(window.close, 0); 
    }
</script>

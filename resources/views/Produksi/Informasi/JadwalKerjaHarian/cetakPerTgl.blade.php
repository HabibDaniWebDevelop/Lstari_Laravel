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

    <table width="100%">
        <tr>
            <td align="center" width="100%" style="font-size:15px;"><b>Report Persentase Per Tgl {{$tglstart}} - {{$tglend}}</b></td>
        </tr>
    </table>
    <table class="table table-striped" id="tampiltabel" style="width: 100%; border: 1px solid black;">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">No</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">RPH</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">Tgl RPH</th> 
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">Jumlah RPH</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">Jumlah SPKO</th>
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">Jumlah NTHKO</th> 
                <th class="text-center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">(SPKO/RPH)*100</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 12px; font-weight: bold; color: rgb(88, 88, 223); width: 10% ">(NTHKO/SPKO)*100</th>
            </tr>                     
        </thead>
        <tbody>
            @foreach ($data as $datas)
            <tr>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$loop->iteration}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->ID}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->TransDate}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->SumRPH}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->SumSPKO}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->SumNTHKO}}</td>
                <td align="center" style="border-bottom: 1px solid black; border-right: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->PercentSPKO}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size: 13px; font-weight: bold; color: black" readonly>{{$datas->PercentNTHKO}}</td>
            </tr>
            @endforeach
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

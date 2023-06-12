@php
    foreach($data1 as $datas1){} 
    foreach($data2 as $datas2){} 

@endphp

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
                size: auto F4 portrait;
                margin: 1mm;
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
            <td align="center" width="100%">RENCANA PRODUKSI HARIAN - {{ $datas2->TransDate1 }}</td>
        </tr>
        <tr>
            <td align="center" width="100%"><b>{{ $datas2->LocationName }} / {{ $datas2->OperationName }}</b></td>
        </tr>   
        <tr>
            <td align="left" width="100%">ID RPH : {{ $datas2->ID }}</td>
        </tr>                   
    </table>
    <table width="100%">
        <thead>
            <tr bgcolor="#475c6c" style="color: black">             
                <th width="2%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">No</th>
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Kadar</th>     
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Kategori</th>                                   
                <th width="8%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Dari</th>                     
                <th width="12%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">NTHKO</th> 
                <th width="9%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">TM</th>
                <th width="8%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Tanggal</th>
                <th width="9%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">SPK</th>
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">SubKategori</th>
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Proses</th>                          
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Qty</th>
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Pcs</th>
                <th width="8%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Berat</th>                     
                <th width="12%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Note</th>    
                <th width="2%"></th>
            </tr>           
        </thead>
        <tbody>
            @php
                $jml1 = 0;
                $brt1 = 0;
                $pcs1 = 0;
            @endphp
            @foreach ($byGroup as $keyi => $dataku)
            <tr bgcolor="yellow" style="black: white">
                <td style="border-bottom: 1px solid black; font-size : 11px" colspan="15">
                    Kadar : {{$keyi}} <b>&nbsp;&nbsp;(Total Terpilih : {{ count($dataku) }})<b>
                </td>
            </tr>
            @php
                $no = 1;
                $jml = 0;
                $brt = 0;
                $pcs = 0;
            @endphp
            @foreach ($dataku as $data)
            @php 
                $jml += $data['Qty'];
                $pcs += $data['Pcs'];
                $brt += $data['Weight'];
            @endphp
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $no++ }}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Kadar'] }}</td>                            
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Kategori']}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Lnama'] }}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['WS'] }}</td>                                      
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['TMno'] }}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['TransDate1'] }}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['WSW'] }}</td>   
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['PSS'] }}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Psiap'] }}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Qty'] }}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Pcs'] }}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Weight'] }}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Note'] }}</td>   
                <td style="text-align: center; vertical-align: middle; border-bottom: 1px solid black; font-size : 11px"><input type="checkbox"></td>
            </tr>
            @endforeach
            <tr bgcolor="#E9967A" style="black: white; font-weight: bold">
                <td style="border-bottom: 1px solid black; font-size : 11px" colspan="10" align="right">Sub Total : </td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="center">{{ $jml }}</td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="center">{{ $pcs }}</td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="center">{{ $brt }}</td>
                <td></td>
            </tr>
            @php 
                $jml1 += $jml; 
                $brt1 += $brt;
                $pcs1 += $pcs;
            @endphp
            @endforeach
            <tr bgcolor="#E9967A" style="black: white; font-weight: bold">
                <td style="border-bottom: 1px solid black; font-size : 11px" colspan="10" align="right">Grand Total : </td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="center">{{ $jml1 }}</td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="center">{{ $pcs1 }}</td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="center">{{ $brt1 }}</td>
                <td></td>
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

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
            <td align="center" width="100%"><b>{{ $datas1->Description }}</b></td>
        </tr>   
        <tr>
            <td align="left" width="100%">ID RPH : {{ $datas2->ID }}</td>
        </tr>                   
    </table>
    <table width="100%">
        <thead>
            <tr bgcolor="#475c6c" style="color: black">
                <th width="2%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">No</th>
                <th width="10%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">SPK</th>                          
                <th width="10%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Tanggal</th>
                <th width="10%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Sub Kategori</th>   
                <th width="30%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Deskripsi</th>                                        
                <th width="10%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">SPK Lilin</th>
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Model</th>
                <th width="5%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Kadar</th>                      
                <th width="8%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Qty</th>
                <th width="8%" style="border-bottom: 1px solid black; font-size : 12px; font-weight: bold">Sub Qty</th>
                <th width="2%"></th>    
            </tr>           
        </thead>
        <tbody>
            @php
                $jml1 = 0;
                $brt1 = 0;
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
            @endphp
            @foreach ($dataku as $data)
            
            @php 
                $jml += $data['grandtot']; 
            @endphp
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $no++ }}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['SW'] }}</td>                            
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['TransDate']}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Model'] }}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{!! $data['Product'] !!}</td>                                      
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['IDMs'] }}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['total'] }}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['Carat'] }}</td>                         
                <td align="right" style="border-bottom: 1px solid black; font-size : 11px">{!! $data['Qty'] !!}</td>  
                <td align="right" style="border-bottom: 1px solid black; font-size : 11px">{{ $data['grandtot'] }}</td>  
                <td style="text-align: center; vertical-align: middle; border-bottom: 1px solid black; font-size : 11px"><input type="checkbox"></td>
            </tr>
            @endforeach
            <tr bgcolor="#E9967A" style="black: white; font-weight: bold">
                <td style="border-bottom: 1px solid black; font-size : 11px" colspan="9" align="right">Sub Total : </td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="right">{{ $jml }}</td>
                <td></td>
            </tr>
            @php $jml1 += $jml; @endphp
            @endforeach
            <tr bgcolor="#E9967A" style="black: white; font-weight: bold">
                <td style="border-bottom: 1px solid black; font-size : 11px" colspan="9" align="right">Grand Total : </td>
                <td style="border-bottom: 1px solid black; font-size : 11px" align="right">{{ $jml1 }}</td>
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

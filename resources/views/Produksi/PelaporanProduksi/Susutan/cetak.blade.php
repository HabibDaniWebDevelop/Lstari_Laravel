@php
    foreach ($data as $datas){}
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
    
            .table2 {
                padding-bottom:0px;
                font-family: "Arial Narrow", Arial, sans-serif; 
            }
    
            td {
                font-size: 11px;
            }

            table, th, tr, td, hr{
                padding:0px;
                margin:0px;
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

        .vl {
            border-left: 1px solid black;
            height: 220px;
            position: absolute;
            left: 50%;
            margin-left: -3px;
            top: 0;
        }

        hr{
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="vl"></div>

    <table width="100%" border="0">
        <tr>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
        </tr>    
        <tr>
            <td colspan="1"></td>
            <td colspan="11" align="left" width="50%" style="font-size:14px;"><b>Nota Susutan</b></td>
            <td colspan="1"></td>
            <td colspan="11"align="left" width="50%" style="font-size:14px;"><b>Nota Susutan</b></td>
        </tr>
    </table>
    <table width="100%" border="0">	
        <tr>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
        </tr>            
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="6">SPK Operator : <b>{{$datas->NoSPKO}}</b></td>
            <td colspan="5">Tgl Transaksi : <b>{{$datas->Tgl}}</b></td>
            <td colspan="1"></td>
            <td colspan="6">SPK Operator : <b>{{$datas->NoSPKO}}</b></td>
            <td colspan="5">Tgl Transaksi : <b>{{$datas->Tgl}}</b></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="6">Nama : <b>{{$datas->Pekerja}}</b></td>
            <td colspan="5">Kadar : <b>{{$datas->Kadar}}</b></td>
            <td colspan="1"></td>
            <td colspan="6">Nama : <b>{{$datas->Pekerja}}</b></td>
            <td colspan="5">Kadar : <b>{{$datas->Kadar}}</b></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="6">Bagian : <b>{{$datas->Bagian}}</b></td>
            <td colspan="5">Proses : <b>{{$datas->Proses}}</b></td>
            <td colspan="1"></td>
            <td colspan="6">Bagian : <b>{{$datas->Bagian}}</b></td>
            <td colspan="5">Proses : <b>{{$datas->Proses}}</b></td>
        </tr>
    </table>
    <hr>
    <table width="100%" border="0">	
        <tr>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
        </tr>            
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="3">Total SPKO : </td>
            <td colspan="2"><b>{{$datas->BrtSPKO}}</b></td>
            <td colspan="6"></td>
            <td colspan="1"></td>
            <td colspan="3">Total SPKO : </td>
            <td colspan="2"><b>{{$datas->BrtSPKO}}</b></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="3">Total NTHKO : </td>
            <td colspan="2"><b>{{$datas->BrtNTHKO}}</b></td>
            <td colspan="6"></td>
            <td colspan="1"></td>
            <td colspan="3">Total NTHKO : </td>
            <td colspan="2"><b>{{$datas->BrtNTHKO}}</b></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="3">Susutan : </td>
            <td colspan="2"><b>{{$datas->Susut}}</b></td>
            <td colspan="6"></td>
            <td colspan="1"></td>
            <td colspan="3">Susutan : </td>
            <td colspan="2"><b>{{$datas->Susut}}</b></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="3">Toleransi : </td>
            <td colspan="2"><b>{{$datas->Toleransi}}</b></td>
            <td colspan="6"></td>
            <td colspan="1"></td>
            <td colspan="3">Toleransi : </td>
            <td colspan="2"><b>{{$datas->Toleransi}}</b></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="1"></td>
            <td colspan="3">Selisih : </td>
            <td colspan="2"><b>{{$datas->Perbedaan}}</b></td>
            <td colspan="6"></td>
            <td colspan="1"></td>
            <td colspan="3">Selisih : </td>
            <td colspan="2"><b>{{$datas->Perbedaan}}</b></td>
        </tr>

    </table>

    <table width="100%" border="0">
        <tr>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
        </tr>    	            
        <tr style="font-size: 10px;">
            <td colspan="4" style="text-align: center;">Diberikan oleh</td>
            <td colspan="4" style="text-align: center;">Diterima oleh</td>
            <td colspan="4">Dicetak :</td>
            <td colspan="4" style="text-align: center;">Diberikan oleh</td>
            <td colspan="4" style="text-align: center;">Diterima oleh</td>
            <td colspan="4">Dicetak :</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="12">&nbsp;</td>
            <td colspan="12">&nbsp;</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="8">&nbsp;</td>
            <td colspan="4">{{$datenow}} {{$timenow}}</td>
            <td colspan="8">&nbsp;</td>
            <td colspan="4">{{$datenow}} {{$timenow}}</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="4" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="4" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="4">{{$username}}</td>
            <td colspan="4" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="4" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="4">{{$username}}</td>
        </tr>
    </table>

</body>
</html>
<script>
    window.onload = function(){
        window.print();
        setTimeout(window.close, 0); 
    }
</script>
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
    </style>
</head>
<body>
    <input type="hidden" id="idtm" name="idtm" value="{{ $datas->ID }}">

    <table width="100%">
        <tr>
            <td align="left" width="50%" style="font-size:14px;"><b>Transfer Material</b></td>
        </tr>
    </table>
    <table width="100%" border="0">	
        <tr hidden>
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
            <td colspan="8">No Transfer : <b>{{$datas->ID}}</b></td>
            <td colspan="4">Tanggal : <b>{{$date1}}</b></td>
            <td colspan="4">Dari Bagian : <b>{{$datas->DariBagian}}</b></td>
            <td colspan="4">Ke Bagian : <b>{{$datas->KeBagian}}</b></td>
            <td colspan="4">Penerima : <b>{{$datas->Penerima}}</b></td>
        </tr>
    </table>
    <hr>
    <table class="table2" width="100%" border="0">
        <thead class="thead-dark">
            <tr >
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
            </tr>
            <tr>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" rowspan="2">No</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >Produk</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >Jumlah</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >Berat</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >No SPK</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >Jenis</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >No NTHKO</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >No Pohon</th>   
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >Keterangan</th>   
            </tr>
        </thead>
        <tbody>
            @php
                $totQty = 0;
                $totWeight = 0;
                $no = 0;
            @endphp
            @foreach($dataItem as $key => $dataItems)
            <tr >
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
                <td width="8.33%"></td>
            </tr>
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$loop->iteration}}</td>
                @if($key == 0)
                    <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->Produk}} {{$dataItems->CSW}}</td>
                @else
                    @if($dataItems->Carat <> $dataItem[$key-1]->Carat)
                        <td align="center" style="border-bottom: 1px solid black; font-size: 10px; color: black" colspan="2" ><strong>{{$dataItems->Produk}} {{$dataItems->CSW}}</strong></td>
                    @else
                        <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->Produk}} {{$dataItems->CSW}}</td>
                    @endif
                @endif
                {{-- <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->Produk}} {{$dataItems->CSW}}</td> --}}
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->Qty}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->Weight}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->WOSW}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->PSW}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->NTHKO}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->NoPohon}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >{{$dataItems->Keterangan}}</td> 
            </tr>
            @php
                $totQty += $dataItems->Qty;
                $totWeight += $dataItems->Weight;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td align="center" colspan="3"><b>Total :</b></td>
                <td align="center" colspan="1"><b>{{$totQty}}</b></td>
                <td align="center" colspan="1"><b>{{number_format($totWeight,2)}}</b></td>
                <td align="center" colspan="7"></td>
            </tr>
        </tfoot>
    </table>
    <table width="100%" border="0">
        <tr>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
            <td width="8.33%"></td>
        </tr>    	            
        <tr style="font-size: 10px;">
            <td colspan="6"></td>
            <td colspan="2" style="text-align: center;">Diberikan oleh</td>
            <td colspan="2" style="text-align: center;">Diterima oleh</td>
            <td colspan="2" rowspan="4">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <div style="display: flex; justify-content: center; text-align: center;">{{$datas->ID}}</div>
            </td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="10">&nbsp;</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="10">&nbsp;</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="1"></td>
            <td colspan="1">Dicetak : </td>
            <td colspan="1">{{$datenow}}</td>
            <td colspan="1">{{$timenow}}</td>
            <td colspan="2">{{$username}}</td>
            <td colspan="2" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="2" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
        </tr>
    </table>
</table>
</body>
</html>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
<script>
    
    let idtm = document.getElementById('idtm').value;
    var qrcode = new QRCode("qrcode", {
        text: idtm,
        width: 60,
        height: 60,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    window.onload = function(){
        window.print();
        setTimeout(window.close, 0); 
    }
</script>
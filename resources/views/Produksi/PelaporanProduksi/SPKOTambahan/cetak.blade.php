@php
    foreach ($data as $datas){}
    foreach ($dataTotal as $datasTotal){}
    $totBrtSPKO = $datasTotal->weightSPKO;
    $totBrtNTHKO = $datasTotal->weightNTHKO;
    $sisaBrt = $totBrtSPKO - $totBrtNTHKO;
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
    <input type="hidden" id="swspko" name="swspko" value="{{ $sw }}">

    <table width="100%">
        <tr>
            <td align="left" width="50%" style="font-size:14px;"><b>Surat Perintah Kerja Operator [ {{$datas->Purpose}} ]</b></td>
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
            <td colspan="4">SPKO : <b>{{$datas->SW}}</b></td>
            <td colspan="3">Nama : <b>{{$datas->ESW}}</b></td>
            <td colspan="5">Tgl Transaksi : <b>{{$date1}}</b></td>
            <td colspan="4">Kadar : <b>{{$datas->CSW}}</b></td>
            <td colspan="4">Bagian : <b>{{$datas->LDescription}}</b></td>
            <td colspan="4">Proses : <b>{{$datas->ODescription}}</b></td>
        </tr>
       @if($datas->WorkGroup <> NULL)
         <tr style="font-size: 11px;">
            <td colspan="4">WorkGroup ID : <b>{{$datas->WorkGroup}}</b></td>
            <td colspan="20">WorkGroup Operator : <b>{{$datas->WorkBy}}</b></td>
        </tr>
        @endif
    </table>
    <hr>
    <table class="table2" width="100%" border="0" >
        <thead class="thead-dark">
            <tr hidden>
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
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" rowspan="2">No</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >SPK PPIC</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="1" >Tgl Butuh Mkt</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="1" >No RPH</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >No Batch</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >NTHKO Sblm</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="1" >No Pohon</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >Produk</th>   
            </tr>
            <tr>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="3" >Barang</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >Jenis</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >Jumlah</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >Berat</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="3" >Variasi</th>  
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >Keterangan</th>          
            </tr> 
        </thead>
        <tbody>
            @php
                $no = 1;
                $totQty = 0;
                $totWeight = 0;
                $totBatu = 0;
            @endphp
            @foreach($dataItem as $dataItems)
                @php
                    if($dataItems->RequireDate != NULL){
                        $datemkt = date("d/m/Y", strtotime($dataItems->RequireDate));
                    }else{
                        $datemkt = NULL;
                    }
                @endphp
            <tr hidden>
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
                <td align="center" style="border-bottom: 1px solid black; font-size: 10px; color: black" colspan="1" rowspan="2">{{$no}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->OSW}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="1" >{{$datemkt}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="1" >{{$dataItems->IDRPH}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->BatchNo}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->NTHKOBefore}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="1" >{{$dataItems->Tree}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->GSW}}</td>  
            </tr>
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="3" >{{$dataItems->BarangName}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->FDescription}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->Qty}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{number_format($dataItems->Weight,2)}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="3" >{{$dataItems->NoteMarketing}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->BarcodeNote}}</td>    
            </tr>
                @php
                    $totQty += $dataItems->Qty;
                    $totWeight += $dataItems->Weight;
                    $totBatu += $dataItems->JmlBatu;
                    $no++;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td align="center" colspan="4"></td>
                <td align="center" colspan="1">Total :</td>
                <td align="center" colspan="1">{{$totQty}}</td>
                <td align="center" colspan="1">{{number_format($totWeight,2)}}</td>
                <td align="center" colspan="5"></td>
            </tr>
        </tfoot>
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
            <td colspan="3" style="text-align: center;">Diberikan oleh</td>
            <td colspan="3" style="text-align: center;">Diterima oleh</td>
            <td colspan="8"></td>
            <td colspan="1">SPKO</td>
            <td colspan="1" style="text-align: center;">:</td>
            {{-- <td colspan="2">{{number_format($datas->WWeight,2)}}</td> --}}
            <td colspan="2">{{number_format($totBrtSPKO,2)}}</td>
            <td colspan="6" rowspan="4">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <div style="display: flex; justify-content: center; text-align: center;">{{$datas->Barcode}}</div>
            </td>

        
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="6"></td>
            <td colspan="8"></td>
            <td colspan="1">NTHKO</td>
            <td colspan="1" style="text-align: center;">:</td>
            {{-- <td colspan="2">{{number_format($datas->WeightOK,2)}}</td> --}}
            <td colspan="2">{{number_format($totBrtNTHKO,2)}}</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="3"></td>
            <td colspan="3"></td>
            <td colspan="1"></td>
            <td colspan="2">Dicetak : </td>
            <td colspan="2">{{$datenow}}</td>
            <td colspan="3">{{$timenow}}</td>
            <td colspan="1">Sisa</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">{{number_format($sisaBrt,2)}}</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="3" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="3" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="3"></td>
            <td colspan="2">{{$username}}</td>
            <td colspan="3"></td>
            <td colspan="1">Batu</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">{{number_format($totBatu,2)}}</td>
        </tr>
    </table>
</table>
</body>
</html>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
<script>
    
    let swspko = document.getElementById('swspko').value;
    var qrcode = new QRCode("qrcode", {
        text: swspko,
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
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
    <input type="hidden" id="swspko" name="swspko" value="{{ $sw }}">

    <table width="100%">
        <tr>
            <td align="left" width="50%" style="font-size:14px;"><b>Surat Perintah Kerja Operator</b></td>
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
    <table class="table2" width="100%" border="0">
        <thead class="thead-dark">
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
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" rowspan="2">No</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="4" >SPK PPIC</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >Tgl Butuh Mkt</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >No RPH</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="4" >No Batch</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="4" >NTHKO Sblm</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >No Pohon</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="1" >Produk</th>   
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >Pcs</th>  
                <th class="text-center" style="font-size: 9px; color: black" colspan="1" >CT</th>  
            </tr>
            <tr>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="6" >Barang</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >Jenis</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >Jumlah</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >Berat</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="6" >Variasi</th>  
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >Keterangan</th>       
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >Total Time</th>   
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >Persen</th>       
            </tr> 
        </thead>
        <tbody>
            @php
                $no = 1;
                $totQty = 0;
                $totWeight = 0;
                $totBatu = 0;
                $totTime = 0;
                $totPersen = 0;
            @endphp
            @foreach($dataItem as $dataItems)
                @php
                    // dd($dataItems);
                    if($dataItems->RequireDate != NULL){
                        $datemkt = date("d/m/Y", strtotime($dataItems->RequireDate));
                    }else{
                        $datemkt = NULL;
                    }
                @endphp
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
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" rowspan="2">{{$no}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="4" >{{$dataItems->OSW}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$datemkt}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->WorkSchedule}}</td> 
                @if($dataItems->BatchNo == 'null')
                    <td align="center" style="font-size: 11px; color: black" colspan="4" ></td> 
                @else
                    <td align="center" style="font-size: 11px; color: black" colspan="4" >{{$dataItems->BatchNo}}</td> 
                @endif
                <td align="center" style="font-size: 11px; color: black" colspan="4" >{{$dataItems->NTHKOBefore}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->Tree}}</td> 
                <td align="center" style="font-size: 9px; color: black" colspan="1" >{{$dataItems->GSW}}</td>  
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->Pcs}}</td>  
                <td align="center" style="font-size: 11px; color: black" colspan="1" >{{$dataItems->MasterCycleTime}}</td>  
            </tr>
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="6" >{{$dataItems->BarangName}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->FDescription}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->Qty}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{number_format($dataItems->Weight,2)}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="6" >{{$dataItems->NoteMarketing}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >{{$dataItems->BarcodeNote}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->TotalTime}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->Persen}}</td>  
            </tr>
                @php
                    $totQty += $dataItems->Qty;
                    $totWeight += $dataItems->Weight;
                    $totBatu += $dataItems->JmlBatu;
                    $totTime += $dataItems->TotalTime;
                    $totPersen += $dataItems->Persen;
                    $no++;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td align="center" style="font-weight: bold" colspan="8"></td>
                <td align="center" style="font-weight: bold" colspan="2">Total :</td>
                <td align="center" style="font-weight: bold" colspan="2">{{$totQty}}</td>
                <td align="center" style="font-weight: bold" colspan="2">{{number_format($totWeight,2)}}</td>
                <td align="center" style="font-weight: bold" colspan="7"></td>
                <td align="center" style="font-weight: bold" colspan="2">{{$totTime}}</td>
                <td align="center" style="font-weight: bold" colspan="1">{{$totPersen}}</td>
            </tr>
        </tfoot>
    </table>
    <table width="100%" border="0">
        <tr >
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
            <td colspan="2">{{number_format($datas->WWeight,2)}}</td>
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
            <td colspan="2">{{number_format($datas->WeightOK,2)}}</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="3"></td>
            <td colspan="3"></td>
            <td colspan="3">Dicetak : </td>
            <td colspan="2">{{$datenow}}</td>
            <td colspan="3">{{$timenow}}</td>
            <td colspan="1">Sisa</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">{{number_format($sisa,2)}}</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="3" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="3" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="3"></td>
            <td colspan="5">{{$username}}</td>
            <td colspan="1">Batu</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">0</td>
            {{-- <td colspan="2">{{number_format($totBatu,2)}}</td> --}}
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
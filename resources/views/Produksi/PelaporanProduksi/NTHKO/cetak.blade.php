@php
    foreach ($data as $datas){}
    foreach ($dataInfo as $datasInfo){}
    $tgltransaksi = date('d/m/y', strtotime($datas->TransDate));
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
    <input type="hidden" id="swspko" name="swspko" value="{{$datasInfo->SW}}">

    <table width="100%">
        <tr>
            <td align="left" width="50%" style="font-size:14px;"><b>Nota Terima Hasil Kerja Operator</b></td>
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
            <td colspan="6">No Setor : <b>{{$datas->Code}}</b></td>
            <td colspan="6">SPK Operator : <b>{{$datas->WorkAllocation}}</b></td>
            <td colspan="6">Kadar : <b>{{$datas->Carat}}</b></td>
            <td colspan="6">Tgl Transaksi : <b>{{$tgltransaksi}}</b></td> 
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="12" style="font-size: 10px;">Nama : <b>{{$datas->WorkBy}}</b></td>
            <td colspan="6">Bagian : <b>{{$datas->LDescription}}</b></td>
            <td colspan="6">Proses : <b>{{$datas->ODescription}}</b></td>
        </tr>
    </table>
    <hr>
    <table class="table2" width="100%" border="0" >
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
                <th class="text-center" style="font-size: 9px; color: black" colspan="3" >SPK PPIC</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >Tgl Butuh Mkt</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >No RPH</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="4" >No Batch</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="4" >NTHKO Sblm</th>
                <th class="text-center" style="font-size: 9px; color: black" colspan="2" >No Pohon</th> 
                <th class="text-center" style="font-size: 9px; color: black" colspan="5" >Produk</th>   
            </tr>
            <tr>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="5" >Barang</th>
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >Jenis</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >J.Setor</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="2" >B.Setor</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >J.OK</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >B.OK</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >J.Rep</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >B.Rep</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >J.SS</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="1" >B.SS</th> 
                <th class="text-center" style="border-bottom: 1px solid black; font-size: 9px; color: black" colspan="5" >Keterangan</th>          
            </tr> 
        <thead>
        <tbody>
            @php
                $no = 1;
                $totQtyComplete = 0;
                $totWeightComplete = 0;
                $totQtyNTHKO = 0;
                $totWeightNTHKO = 0;
                $totQtyNTHKORepair = 0;
                $totWeightNTHKORepair = 0;
                $totQtyNTHKOScrap = 0;
                $totWeightNTHKOScrap = 0;
                $totBatu = 0;
            @endphp
            @foreach($dataItem as $dataItems)
            {{-- ProductID: 1202 = Batu Sisa --}}
            {{-- @if($dataItems->Product <> 1202) --}}
                @php
                    if($dataItems->RequireDate != NULL){
                        $datemkt = date("d/m/y", strtotime($dataItems->RequireDate));
                    }else{
                        $datemkt = NULL;
                    }
                @endphp
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size: 10px; color: black" colspan="2" rowspan="2">{{$no}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="3" >{{$dataItems->OSW}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$datemkt}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->WorkSchedule}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="4" >{{$dataItems->BatchNo}}</td> 
                <td align="center" style="font-size: 11px; color: black" colspan="4" >{{$dataItems->NTHKOBefore}}</td>
                <td align="center" style="font-size: 11px; color: black" colspan="2" >{{$dataItems->TreeID}}</td>  
                <td align="center" style="font-size: 11px; color: black" colspan="5" >{{$dataItems->GSW}}</td>  
            </tr>
            <tr>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black; font-weight: bold" colspan="5" >{{$dataItems->BarangName}}</td>
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->FDescription}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{$dataItems->QtyComplete}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="2" >{{number_format($dataItems->WeightComplete,2)}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->Qty}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{number_format($dataItems->Weight,2)}}</td> 
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->RepairQty}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{number_format($dataItems->RepairWeight,2)}}</td>   
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{$dataItems->ScrapQty}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="1" >{{number_format($dataItems->ScrapWeight,2)}}</td>  
                <td align="center" style="border-bottom: 1px solid black; font-size: 11px; color: black" colspan="6" >{{$dataItems->Note}}</td>     
            </tr>
                @php
                    $totQtyComplete += $dataItems->QtyComplete;
                    $totWeightComplete += $dataItems->WeightComplete;
                    $totQtyNTHKO += $dataItems->Qty;
                    $totWeightNTHKO += $dataItems->Weight;
                    $totQtyNTHKORepair += $dataItems->RepairQty;
                    $totWeightNTHKORepair += $dataItems->RepairWeight;
                    $totQtyNTHKOScrap += $dataItems->ScrapQty;
                    $totWeightNTHKOScrap += $dataItems->ScrapWeight;
                    $totBatu += $dataItems->JmlBatu;
                    $no++;
                @endphp
            {{-- @endif --}}
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td align="center" colspan="7"></td>
                <td align="center" colspan="2">Total :</td>
                <td align="center" colspan="2">{{$totQtyComplete}}</td>
                <td align="center" colspan="2">{{number_format($totWeightComplete,2)}}</td>
                <td align="center" colspan="1">{{$totQtyNTHKO}}</td>
                <td align="center" colspan="1">{{number_format($totWeightNTHKO,2)}}</td>
                <td align="center" colspan="1">{{$totQtyNTHKORepair}}</td>
                <td align="center" colspan="1">{{number_format($totWeightNTHKORepair,2)}}</td>
                <td align="center" colspan="1">{{$totQtyNTHKOScrap}}</td>
                <td align="center" colspan="1">{{number_format($totWeightNTHKOScrap,2)}}</td>
                <td align="center" colspan="5"></td>
            </tr>
        </tfoot>
    </table>
    @php
        // $sisa = $datasInfo->Weight - $totWeightComplete;
        $sisa = $datasInfo->Weight - $datasInfo->CompletionWeight;
    @endphp
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
            <td colspan="1">Terima</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">{{number_format($datasInfo->Weight,2)}}</td>
            <td colspan="6" rowspan="4">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <div style="display: flex; justify-content: center; text-align: center;">*{{$datasInfo->SW}}*</div>
            </td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="6"></td>
            <td colspan="8"></td>
            <td colspan="1">Setor</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">{{number_format($totWeightComplete,2)}}</td>
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
            <td colspan="2">{{number_format($sisa,2)}}</td>
        </tr>
        <tr style="font-size: 10px;">
            <td colspan="3" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="3" style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="3"></td>
            <td colspan="2">{{$username}}</td>
            <td colspan="3"></td>
            <td colspan="1">Batu</td>
            <td colspan="1" style="text-align: center;">:</td>
            <td colspan="2">0</td>
            {{-- <td colspan="2">{{number_format($totBatu,2)}}</td> --}}
        </tr>
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
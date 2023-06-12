@php
    foreach ($data as $datas){}
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>
    <style type="text/css" media="print">
        @media print {
            #aa {page-break-before:always}
    
            table {
                width: 100%;
                padding: 0px 10px 0px 10px;
                font-family: Arial, sans-serif; 
            }
    
            body {
                zoom: 90%;
            }
    
            td {
                font-size: 12px;
            }
    
            @page {
                size: portrait;
                margin: 0mm;
            }
        }
    </style>
</head>
<body>
    <input type="hidden" id="rowcount" name="rowcount" value="{{ $rowcount }}">

    @foreach ($data as $datas)

    @php
        $date1 = date("d/m/Y", strtotime($datas->PohonDate));
        $date2 = date("d/m/Y", strtotime($datas->WADate));
    @endphp

    <input type="hidden" id="nospko{{$loop->iteration}}" value="{{$datas->NoSPKO}}">
    <table width="100%" border="0" style="font-family: Arial, sans-serif;" id="aa">
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
        <tr>
            <td colspan="4"><strong>{{$datas->Tree}}</strong></td>
            @if($datas->PohonDate == NULL)
                <td colspan="3"></td>
            @else
                <td colspan="3">{{$date1}}</td>    
            @endif 
            @if($datas->BatchNo == 'null')
                <td colspan="5" align="right"></td>	
            @else
                <td colspan="5" align="right">{{$datas->BatchNo}}</td>	
            @endif
            {{-- <td colspan="5" align="right">{{$datas->BatchNo}}</td>	 --}}
        </tr>
        <tr>
            <td colspan="12">{{$datas->GSW}}</td>    	
        </tr>
        <tr>
            <td colspan="12" style="font-size: 13px;"><strong>{{$datas->PSW}}</strong></td>
        </tr> 
        <tr>
            <td colspan="3" style="font-size: 13px;"><strong>SPKO</strong></td>
            <td colspan="3"><strong>{{$datas->CSW}}</strong></td>	
            <td colspan="3" align="right">{{$date2}}</td>	
            <td align="right" width="100%" colspan="3" rowspan="3">
                {{-- <div style="display: none; justify-content: center; text-align: center;" id="qrcode{{$loop->iteration}}"></div> --}}
                <div style="display: flex; justify-content: center; text-align: center;">{!! QrCode::size(60)->generate('{{$datas->NoSPKO}}') !!}</div>
                {{-- <img src="{!! asset('assets/temp/'.$datas->NoSPKO.'.svg') !!}" class="img-fluid" style="height: 60px; width: 60px;"> --}}
                {{-- <img src="{{ Session::get('hostfoto') }}/produksi/ProduksiPDF/{{$datas->NoSPKO}}.svg" class="img-fluid" style="height: 60px; width: 60px;"> --}}
            </td>
        </tr>
        <tr>
            <td colspan="2"><strong>{{$datas->Qty}}</strong></td>
            <td colspan="2"><strong>{{number_format($datas->Weight,2)}}</strong></td>	
            <td colspan="5" align="right"><strong>{{$datas->NoSPKO}}</strong></td>	
        </tr>
        <tr>
            <td colspan="4">{{$datas->OSW}}</td>
            <td colspan="2">{{$datas->FDescription}}</td>	
            <td colspan="3" align="right"><strong>{{$datas->EMSW}}</strong></td>	
        </tr>
        <tr>
            <td width="100%" colspan="12">{{$datas->NoteMarketing}}</td>      	
        </tr>
        <tr>
            <td width="100%" colspan="12">{{$datas->BarcodeNote}}</td>      	
        </tr>
        <tr>
        
        </tr>
    </table>
    <p id="demo"></p>

    @endforeach
</body>
</html>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
<script src="{!! asset('assets/almas/assets/websocket-printer.js') !!}"></script>
<script>
    
    // let rowcount = document.getElementById('rowcount').value;
    // var i = 1;
    // while (i <= rowcount) {
    //     var nospko = document.getElementById('nospko'+i).value;
    //     var qrcode = new QRCode("qrcode"+i, {
    //         text: nospko,
    //         width: 60,
    //         height: 60,
    //         colorDark: "#000000",
    //         colorLight: "#ffffff",
    //         correctLevel: QRCode.CorrectLevel.H
    //     });
    //     i++;
    // }

    window.onload = function(){
        window.print();
        setTimeout(window.close, 0); 
    }
</script>


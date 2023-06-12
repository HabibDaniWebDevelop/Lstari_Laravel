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
</head>
<body>
    <input type="hidden" id="rowcount" name="rowcount" value="{{ $rowcount }}">

    @foreach ($data as $datas)

    @php
        $date1 = date("d/m/Y", strtotime($datas->PohonDate));
        $date2 = date("d/m/Y", strtotime($datas->WADate));
        Storage::disk('produksi')->put('BarcodeProduksi/'.$datas->NoSPKO.'.svg', QrCode::generate($datas->NoSPKO));
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
            <td align="right" width="100%" colspan="3" rowspan="4" >
                {{-- <div style="display: none; justify-content: center; text-align: center;" id="qrcode{{$loop->iteration}}"></div> --}}
                {{-- <div style="display: flex; justify-content: center; text-align: center;">{!! QrCode::size(60)->generate('{{$datas->NoSPKO}}') !!}</div> --}}
                {{-- <img src="{!! asset('assets/temp/'.$datas->NoSPKO.'.svg') !!}" class="img-fluid" style="height: 60px; width: 60px;"> --}}
                <img src="{{ Session::get('hostfoto') }}/produksi/BarcodeProduksi/{{$datas->NoSPKO}}.svg" class="img-fluid" style="height: 60px; width: auto; margin-top: -20px">
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
            <td width="100%" colspan="9">{{$datas->NoteMarketing}}</td>      	
        </tr>
        <tr>
            <td width="100%" colspan="12">{{$datas->BarcodeNote}}</td>      	
        </tr>
        <tr>

        </tr>
    </table>

    <style type="text/css">

        table{
	        page-break-after:always;
        }

        body > table:last-of-type{page-break-after:auto}
    
        table {
            width: 100%;
            padding: 0px 5px 0px 5px;
            font-family: Arial, sans-serif; 
        }
    
        body {
            zoom: 100%;
        }
    
        td {
            font-size: 11px;
            padding-top: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            border-spacing: 0px;
        }
    
        @page {
            size: portrait;
            margin: 0mm;
        }
    
    </style>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

    @endforeach

</body>
</html>



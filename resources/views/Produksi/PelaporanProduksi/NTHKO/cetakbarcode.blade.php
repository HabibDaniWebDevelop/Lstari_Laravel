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
    @if($datas->Product <> 1202)
    @php
        $date1 = date("d/m/y", strtotime($datas->PohonDate));
        $date2 = date("d/m/y", strtotime($datas->NTHKODate));
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
            <td colspan="5" align="right">{{$datas->BatchNo}}</td>	
        </tr>
        <tr>
            <td colspan="12">{{$datas->FGName}}</td>    	
        </tr>
        <tr>
            <td colspan="12" style="font-size: 13px;"><strong>{{$datas->PSW}}</strong></td>
        </tr> 
        <tr>
            <td colspan="3" style="font-size: 13px;"><strong>NTHKO</strong></td>
            <td colspan="3"><strong>{{$datas->CSW}}</strong></td>	
            <td colspan="3" align="right">{{$date2}}</td>	
            <td align="right" width="100%" colspan="3" rowspan="3">
                <div style="display: flex; justify-content: center; text-align: center;">{!! QrCode::size(60)->generate($datas->NoSPKO) !!}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><strong>{{$datas->QtyComplete}}</strong></td>
            <td colspan="2"><strong>{{number_format($datas->WeightComplete,2)}}</strong></td>	
            <td colspan="5" align="right"><strong>{{$datas->NoSPKO}}</strong></td>	
        </tr>
        <tr>
            <td colspan="4">{{$datas->OSW}}</td>
            <td colspan="2">{{$datas->FDescription}}</td>	
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="12">{{$datas->Note}}</td>
        </tr>
    </table>
    @endif
    @endforeach
</body>
</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
<script>
    window.onload = function(){
        window.print();
        setTimeout(window.close, 0); 
    }
</script>

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
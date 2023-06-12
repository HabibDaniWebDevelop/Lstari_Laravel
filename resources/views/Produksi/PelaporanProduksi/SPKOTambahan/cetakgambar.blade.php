@php
    foreach ($data as $datas){}
@endphp

<table width="100%">
    <tr>
        <td align="left" width="50%" style="font-size:20px;"><b>SPKO - Daftar Gambar</b></td>
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
    <tr>
        <td colspan="5" style="font-size: 14px;">SPKO : <b>{{ $datas->SW }}</b></td>
        <td colspan="4" style="font-size: 14px;">Tgl : <b>{{ $date1 }}</b></td>
        <td colspan="4" style="font-size: 14px;">Bagian : <b>{{ $datas->LDescription }}</b></td>
        <td colspan="6" style="font-size: 14px;">Proses : <b>{{ $datas->ODescription }}</b></td>
        <td colspan="5" style="font-size: 14px;">Kadar : <b>{{ $datas->CSW}}</b></td>
    </tr>
</table>
<table class="table2" width="100%" border="0" >
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
    <tbody>
        @php $no = 0; @endphp
            <tr>
        @foreach ($data2 as $datas2)
        @php $img = $datas2->GPhoto . '.jpg'; @endphp

            @if(($no % $pembagi) <> 0)
                <td align="center" style="border: 1px solid black; font-size: 11px; color: black" colspan="8">
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
                        <tr>
                            <td colspan="6" align="left">{{ $datas2->OSW }}</td>
                            <td colspan="6" align="right">{{ $datas2->Qty }}</td>
                        </tr>
                        <tr>
                            <td colspan="12" align="center">{{ $datas2->GSW }}</td>
                        </tr>
                    </table>
                    <br>
                    <img src="http://192.168.3.100:8383/image2/{{$img}}" width="200px" height="200px">	
                </td>
            
            @elseif(($no % $pembagi) == 0)
            </tr>
            <tr>
                <td align="center" style="border: 1px solid black; font-size: 11px; color: black" colspan="8">
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
                        <tr>
                            <td colspan="6" align="left">{{ $datas2->OSW }}</td>
                            <td colspan="6" align="right">{{ $datas2->Qty }}</td>
                        </tr>
                        <tr>
                            <td colspan="12" align="center">{{ $datas2->GSW }}</td>
                        </tr>
                    </table>
                    <br>
                    <img src="http://192.168.3.100:8383/image2/{{$img}}" width="200px" height="200px">
                </td>
            @endif
            @php $no++; @endphp
        @endforeach
            </tr>
    </tbody>
</table>

<script>
	window.onload = function() {
		window.print();
		setTimeout(window.close, 0); 
	}
</script>
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

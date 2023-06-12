<style type="text/css" media="print">
    @media print {

        table {
            width: 100%;
            font-family: Arial, Helvetica, sans-serif;

        }

        body {
            zoom: 100%;

            /*or whatever percentage you need, play around with this number*/
        }

        td {
            font-size: 13px;
        }

        @page {
            size: portrait;
            margin: 0mm;
        }
    }


    b {
        font-size: 13px;
    }

    #Header,
    #Footer {
        display: none !important;
    }

    .table {
        border: 1px;
        padding: 0px;
    }

</style>

@if (isset($data[0]->SKU))
    <table width="100%" border="0" style="background-color : #F0FFF0">
        <tr>
            <td align="center" style="padding: 2px;" width="20%">{!!
                QrCode::style('round')->size(80)->margin(3)->generate($data[0]->SKU) !!}</td>
            <td align="center" width="80%"><b>{{ $data[0]->SKU }}<br>{{ $id }}<b></td>
        </tr>
    </table>
@else
    data Tidak ditemukan
@endif




<script>
    window.onload = function() {
		window.print();
		setTimeout(window.close, 0);
	}
</script>
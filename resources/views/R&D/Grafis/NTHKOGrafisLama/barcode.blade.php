<script>
    window.onload = function() {
        window.print();
        // setTimeout(window.close, 0); 
    }
</script>
<style type="text/css" media="print">
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        border: 1.5px solid black;
        font-size: 15px;
    }

    @media print {
        @page {
            size: 80mm 297mm;
            margin: 15px;
            /* padding: 6px; */
        }
    }

    #Header,
    #Footer {
        display: none !important;
    }
</style>

@foreach ($getitemwaxtree as $data)
    <div style='page-break-after: always;'>
        <table width="100%" border="1">
            <tr>
                <td align="center" colspan="2">{{ $data->IDM }}-{{ $data->Product }}-{{ $data->LinkOrd }}</td>
                <td rowspan="2">{!! QrCode::size(50)->margin(2)->generate( $data->IDM-$data->Product-$data->LinkOrd) !!}</td>
            </tr>

            <tr>
                <td align="center">
                    <input type="text" style="width:100%; border: 0 none; text-align: center;" readonly=""
                        value="">
                </td>
                <td align="center">
                    <input type="text" style="width:100%; border: 0 none; text-align: center;" readonly=""
                        value="">
                </td>
            </tr>
        </table>
    </div>
@endforeach

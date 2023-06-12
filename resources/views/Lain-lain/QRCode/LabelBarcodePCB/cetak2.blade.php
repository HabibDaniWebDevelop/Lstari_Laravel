<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak</title>
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.min.css') !!}">

    <style type="text/css">
        body {
            font-family: arial;
            font-size: 13px;
        }

        table {
            font-family: arial, sans-serif;
            width: 100%;
            margin: 0px;
            padding: 0px;
        }

        #tabel1 {
            border-collapse: collapse;
            table-layout: fixed;
            width: 7.1cm;
            position: relative;
            /* top: 13mm; */
            /* border: 1.5px solid black; */
        }

        #tabel1 td {
            /* border: 1px solid black; */
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            word-wrap: break-word;
            margin: 0px;
            padding: 0px;
        }

        #tabel2 td {
            /* border: 1px solid black; */
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            word-wrap: break-word;
            font-size: 9px;
            margin: 0px;
            padding: 0px;
        }

        @media print {
            @page {
                /* margin: 0mm 0mm 0mm 0mm; */
                margin: 1mm;
            }
        }

        #Header,
        #Footer {
            display: none !important;
        }
    </style>

</head>

<body>

    <?php $i = 0; ?>
    @foreach ($datas as $data)
        <?php $i = $i + 1; 
        
        if($datas[$i][6] ==''){
            $datas[$i][6] = $datas[$i][1];
        }
        
        ?>
        <table id="tabel1">
            <tr align="center" style="height: 40px; padding: 200px;">
                <td width="35%" style="vertical-align: top;">
                    <div id="qrcode1">{!! QrCode::size(40)->generate($datas[$i][6]) !!}</div>
                </td>
                <td></td>
                <td width="31%" style="word-wrap:break-word;" contenteditable='true'>
                    <table id="tabel2">
                        <tr>
                            <td colspan="2">{{ $datas[$i][1] }}</td>
                        </tr>
                        <tr>
                            <td>{{ $datas[$i][2] }}</td>
                            <td align="right">{{ $datas[$i][3] }}</td>
                        </tr>
                        <tr>
                            <td>{{ $datas[$i][4] }}</td>
                            <td align="right">{{ $datas[$i][5] }}</td>
                        </tr>
                    </table>
                </td>
                <td width="1%"></td>
            </tr>
        </table>
    @endforeach

</body>

</html>

<script>
    window.onload = function() {
        window.print();
        setTimeout(window.close, 0);
    }
</script>

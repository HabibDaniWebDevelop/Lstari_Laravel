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
        }

        #tabel1 {
            border-collapse: collapse;
            table-layout: fixed;
            width: 7cm;
            position: relative;
            /* top: 13mm; */
            /* border: 1.5px solid black; */
        }

        #tabel1 td {
            /* border: 1px solid black; */
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            word-wrap: break-word;
            font-size: 7.8px;
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
    <table id="tabel1">
        <tr align="center" style="height: 40px; padding: 200px;">
            <td width="35%" style="vertical-align: top;">
                <div id="qrcode1">{!! QrCode::size(40)->generate($id) !!}</div>
            </td>
            <td></td>
            <td width="32%" style="word-wrap:break-word;" contenteditable='true'> {{ $id }}</td>
        </tr>
    </table>

</body>

</html>

<script>
    window.onload = function() {
        window.print();
        setTimeout(window.close, 0);
    }
</script>
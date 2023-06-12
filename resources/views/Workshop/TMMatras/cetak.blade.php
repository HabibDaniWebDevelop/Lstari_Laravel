<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Form Serah Terima Workshop</title>
    <style>
        @page {
            size: <%= @size_card[0] %>cm  <%= @size_card[1] %>cm;
            margin: 5mm;
        } 

        html, body {
            height: 140mm;
            weight:95%;
            font-family: Arial, Helvetica, sans-serif;
            
        }

        table {
            border-collapse: collapse;
        }

        td{
            font-size:12px;
        }

        th{

            font-size:12px;
        }
        p{
            font-size:12px;
        }
    </style>
</head>
    <body>
        <h3 style="text-align: center;">Form Serah Terima Matras Workshop</h3>
        <br>
        <table width="100%" border="0">	            
            <tbody>
                <tr>
                    <td width="5%" style="vertical-align:text-top;">ID TM Matras</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$TMMatras->ID}}</td>
                    <td width="5%" rowspan="2">
                        <div id="qrcode"></div>
                    </td>
                </tr>
                <tr>
                    <td width="2%" style="vertical-align:text-top;">Tanggal</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$TMMatras->TransDate}}</td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <table width="100%" border="1" style="text-align: center;">
            <thead>
                <tr style="text-align: center">
                    <th> No.</th>
                    <th> ID Matras</th>
                    <th> Nama Matras </th>
                    <th> Jenis Matras </th>
                    <th> Tipe Matras</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($TMMatras->Items as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->Matras->ID}}</td>
                        <td>{{$item->Matras->SW}}</td>
                        <td>{{$item->Matras->JenisMatras}}</td>
                        <td>{{$item->Matras->TipeMatras}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <table width="50%" border="1" style="margin-left: auto; margin-right: auto;">
            <thead>
                <tr>
                    <th width="20%">Workshop</th>
                    <th width="20%">Admin GT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="height: 80px"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
<script>
    var qrcode = new QRCode("qrcode", {
        text: {{$TMMatras->ID}},
        width: 60,
        height: 60,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    window.onload = function() {
        window.print();
    }
</script>
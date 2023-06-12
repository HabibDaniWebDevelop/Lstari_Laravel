<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Form Serah Terima Karet PCB ke Lilin</title>
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
        <h3 style="text-align: center;">Form Serah Terima Karet PCB ke Lilin</h3>
        <br>
        <table width="100%" border="0">	            
            <tbody>
                <tr>
                    <td width="2%" style="vertical-align:text-top;">ID TM Karet</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$TMKaretLilin['ID']}}</td>
                    <td width="5%" rowspan="2">
                        <div id="qrcode"></div>
                    </td>
                </tr>
                <tr>
                    <td width="2%" style="vertical-align:text-top;">Tanggal</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$TMKaretLilin['TransDate']}}</td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <table width="100%" border="1">
            <thead>
                <tr style="text-align: center">
                    <th> No.</th>
                    <th> No NTHKO QC</th>
                    <th> Product </th>
                    <th> Bulan STP</th>
                    <th> Rubber Kepala </th>
                    <th> Nama Product Kepala </th>
                    <th> Rubber Mainan </th>
                    <th> Nama Product Mainan </th>
                    <th> Rubber Komponen </th>
                    <th> Nama Product Komponen </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($TMItems as $item)
                <tr style="text-align: center">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item['nthkoqc']}}</td>
                    <td>{{$item['Product']}}</td>
                    <td>{{$item['bulanSTP']}}</td>
                    <td>{{implode(", ",$item['rubberKepala'])}}</td>
                    <td>{{implode(", ",$item['namaProductKepala'])}}</td>
                    <td>{{implode(", ",$item['rubberMainan'])}}</td>
                    <td>{{implode(", ",$item['namaProductMainan'])}}</td>
                    <td>{{implode(", ",$item['rubberComponent'])}}</td>
                    <td>{{implode(", ",$item['namaProductComponent'])}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <span>Total Karet : {{$TotalRubber}}</span>
        <table width="50%" border="1" style="margin-left: auto; margin-right: auto;">
            <thead>
                <tr>
                    <th width="20%">PCB</th>
                    <th width="20%">Adm Karet Lilin</th>
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
        text: {{$TMKaretLilin['ID']}},
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
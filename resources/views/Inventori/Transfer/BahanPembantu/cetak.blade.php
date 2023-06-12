<?php

function tgl_indo($tanggal)
{
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];
    $pecahkan = explode('-', $tanggal);
    //die(print_r($tanggal));
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak</title>

    <style type="text/css">
        body {
            font-family: arial;
            font-size: 13px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
        }

        .tabel0 {
            border: 2px solid black;
            font-size: 15px;
            margin-bottom: 10px;
            width: 100%;
        }

        .tabel0 td {
            padding: 2px 5px;
        }

        #tabel1 {
            width: 100%;
        }

        #tabel2 {
            width: 350px;
        }

        #tabel1 td,
        #tabel1 th,
        #tabel2 td,
        #tabel2 th {
            border: 1px solid black;
            text-align: left;
            padding: 5px;
        }

        #tabel1 th,
        #tabel2 th {
            background-color: #ddd;
        }

        #tabel1 tr:nth-child(even),
        #tabel2 tr:nth-child(even) {
            background-color: #eee;
        }

        @page {
            size: auto A4 landscape;
            margin: 2mm;
        }

        #Header,
        #Footer {
            display: none !important;
        }

    </style>

</head>

<body>

    <table class="tabel0" >
        <tr>
            <td colspan="12" style="font-size:16px; padding: 8px; border: 2px solid black; background-color: #ddd;">
                <b>Transfer Bahan Pembantu</b> </td>
        </tr>
        <tr>
            <td width="120">No Order</td>
            <td width="1%">:</td>
            <td width="">{{ $header[0]->ID }}</td>
            <td width="120">Tanggal</td>
            <td width="1%">:</td>
            <td width="">{{ $header[0]->PAPA }}</td>
        </tr>
        <tr>
            <td>Dari Gudang</td>
            <td>:</td>
            <td>{{ $header[0]->LDescription }}</td>
            <td>Ke Bagian</td>
            <td>:</td>
            <td>{{ $header[0]->Description }}</td>
        </tr>

    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary zindex-2">
            <tr style="text-align: center">
                <th>No</th>
                <th>MR</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Proses</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($body as $data1)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data1->IDM }}</td>
                    <td>{{ $data1->PDescription }}</td>
                    <td>{{ $data1->Qty }}</td>
                    <td>{{ $data1->ODescription }}</td>
                    <td>{{ $data1->Note }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <br>

    <table width="100%" style="page-break-inside: avoid;">
        <tr>
            <td align="center" width="20%" style="font-size : 14px">Penerima</td>
            <td align="center" width="20%" style="font-size : 14px">Administrator</td>
        </tr>
        <tr>
            <td height="30"></td>
        </tr>
        <tr>
            <td align="center" style="font-size : 14px">(................)</td>
            <td align="center" style="font-size : 14px">(................)</td>
        </tr>
        <tr><td>Dicetak: {{date("d/m/y H:i")}} Oleh: {{ Auth::user()->name }}</td></tr>
    </table>

</body>


</html>

{{-- <script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script> --}}

<script>
    // let idtm = document.getElementById('id').value;
    // var qrcode = new QRCode("qrcode", {
    //     text: idtm,
    //     width: 48,
    //     height: 48,
    //     colorDark: "#000000",
    //     colorLight: "#ffffff",
    //     correctLevel: QRCode.CorrectLevel.H
    // });

    window.onload = function() {
        window.print();
    }
</script>

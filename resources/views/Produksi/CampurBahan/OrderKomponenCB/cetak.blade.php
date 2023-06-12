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
        width: 100%;
    }

    .tabel0 {
        border: 1.5px solid black;
        font-size: 15px;
        background-color: #F0FFF0;
        margin-bottom: 10px;
    }

    .tabel0 td {
        padding: 2px 10px;
    }

    #tabel1 td,
    #tabel1 th {
        border: 1px solid black;
        text-align: left;
        padding: 5px;
    }

    #tabel1 th {
        background-color: #ddd;
    }

    #tabel1 tr:nth-child(even) {
        background-color: #eee;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 0mm;
        }
    }
    #Header,
    #Footer {
        display: none !important;
    }
    </style>
</head>
<body>
    <table class="tabel0" style="background-color: #F0FFF0;">
        <tr>
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b>Order Komponen Patri</b>
            </td>
        </tr>
        <tr>
            <td width="120">ID Karyawan</td>
            <td width="2">:</td>
            <td>{{ $printdatas[0]->IDP }}</td>
            <td width="120">ID Order</td>
            <td width="2">:</td>
            <td width="200">{{ $printdatas[0]->IDOrder }}</td>
            <input type="hidden" id="idspk" value="{{ $printdatas[0]->SWUsed }}">
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $printdatas[0]->penerima }}</td>
            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($printdatas[0]->tgl); ?></td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{ $printdatas[0]->Remarks }}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th style="text-align:center;">No.</th>
                <th style="text-align:center;">No SPK</th>
                <th style="text-align:center;">Kode Produk</th>
                <th style="text-align:center;">Description</th>
                <th style="text-align:center;">Kadar</th>
                <th style="text-align:center;">Qty</th>
            </tr>
        </thead>
        <tbody>
            {{-- {{ dd($data) }} --}}
            @foreach ($printdatas as $data1)
            <tr>
                <td style="text-align:center;">{{ $loop->iteration }}</td>
                <td>{{ $data1->WO }}</td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data1->SW }}</span> </td>
                <td>{{ $data1->Produk }}</td>
                <td>{{ $data1->kadar }}</td>
                <td style="text-align:center;">{{ $data1->Qty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table style="text-align: center;">
        <tr style="font-size: 14px;">
            <td width="8%"></td>
            <td width="15%">Dibuat Oleh</td>
            <td> </td>
            <td width="15%">Diterima Oleh</td>
            <td width="8%"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="height: 50px; text-align: center;">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td> {{ $printdatas[0]->Employee }} </td>
            <td></td>
            <td> {{ $printdatas[0]->penerima }} </td>
            <td></td>
        </tr>
    </table>
</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

<script>
let idtm = document.getElementById('idspk').value;
var qrcode = new QRCode("qrcode", {
    text: idtm,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

window.onload = function() {
    window.print();
    setTimeout(window.close, 0);
}
</script>
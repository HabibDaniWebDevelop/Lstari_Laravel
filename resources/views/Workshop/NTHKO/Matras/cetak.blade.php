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
    <title>Cetak NTHKO</title>

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
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b>NTHKO Matras Baru </b>
            </td>
        </tr>
        <tr>
            <td width="120">ID Karyawan</td>
            <td width="2">:</td>
            <td>{{$matrasCompletion->Employee}}</td>
            <td width="120">ID NTHKO</td>
            <td width="2">:</td>
            <td width="200">{{$matrasCompletion->ID}}</td>
            <input type="hidden" id="idspko" value="{{$matrasCompletion->ID}}">
        </tr>

        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{$matrasCompletion->Karyawan}}</td>
            <td>No SPKO</td>
            <td>:</td>
            <td>{{$matrasCompletion->Allocation}}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($matrasCompletion->TransDate); ?></td>
            <td>Proses</td>
            <td>:</td>
            <td>Pembuatan Matras Baru</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Hasil</td>
            <td>:</td>
            <td>{{$matrasCompletion->Result}}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{$matrasCompletion->Remarks}}</td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th width=5%>No.</th>
                <th width=10%>ID Matras</th>
                <th>Matras</th>
                <th>Jenis Matras</th>
                <th>Tipe Matras</th>
                <th>Product</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matrasCompletion->matrasCompletionItems as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item->Matras->ID}}</td>
                <td>{{$item->Matras->SW}}</td>
                <td>{{$item->Matras->JenisMatras}}</td>
                <td>{{$item->Matras->TipeMatras}}</td>
                <td>
                    @foreach ($item->Matras->Items as $productMatras)
                        {{$productMatras->Product->SW}} <br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table style="text-align: center;">
        <tr style="font-size: 14px;">
            <td width="8%"></td>
            <td width="15%">Diserahkan Oleh</td>
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
            <td> {{$matrasCompletion->Admin}} </td>
            <td></td>
            <td> {{$matrasCompletion->Karyawan}} </td>
            <td></td>
        </tr>

    </table>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

<script>
let idtm = document.getElementById('idspko').value;
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
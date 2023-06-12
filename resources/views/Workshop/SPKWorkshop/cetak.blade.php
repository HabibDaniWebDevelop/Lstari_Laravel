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
                <b>Surat Perintah Kerja {{ $header[0]->Purpose }}</b> </td>
        </tr>
        <tr>
            <td width="70">No Order</td>
            <td width="1%">:</td>
            <td width="">{{ $header[0]->noor }}</td>
            <td width="5%">Tanggal</td>
            <td width="1%">:</td>
            <td width="">{{ $header[0]->inputtgl }}</td>
            <td width="5%">Bagian</td>
            <td width="1%">:</td>
            <td width="">{{ $header[0]->jabatan }}</td>
        </tr>
        <tr>
            <td>No SPK</td>
            <td>:</td>
            <td>{{ $header[0]->IDwk }}</td>
            <td>Karyawan</td>
            <td>:</td>
            <td colspan="4">{{ $header[0]->namakar }}</td>
            
        </tr>

    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Inv</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
                <th>Minta Tgl</th>
                <th>Jenis</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($body as $data1)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data1->VDescription }}</td>
                    <td>{{ $data1->Product }}</td>
                    <td>{{ $data1->Qty }}</td>
                    <td>{{ $data1->IDescription }}</td>
                    <td>{{ $data1->DateNeeded1 }}</td>
                    <td>{{ $data1->Type }}</td>
                    <td>{{ $data1->Category }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <br>

    <table width="100%" style="page-break-inside: avoid;">
        <tr>
            <td align="center" width="20%" style="font-size : 14px">Diorder</td>
            <td align="center" width="20%" style="font-size : 14px">Disetujui</td>
            <td align="center" width="20%" style="font-size : 14px">Diterima</td>

            <td align="center" width="20%" style="font-size : 14px">Hasil Diserahkan</td>
            <td align="center" width="20%" style="font-size : 14px">Hasil Diperiksa</td>
        </tr>
        <tr>
            <td height="30"></td>
        </tr>
        <tr>
            <td align="center" style="font-size : 14px">(................)</td>
            <td align="center" style="font-size : 14px">(................)</td>
            <td align="center" style="font-size : 14px">(................)</td>

            <td align="center" style="font-size : 14px">(................)</td>
            <td align="center" style="font-size : 14px">(................)</td>
        </tr>
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

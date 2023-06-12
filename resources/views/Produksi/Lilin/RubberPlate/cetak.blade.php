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
        font-size: 14px;
        background-color: #F0FFF0;
        margin-bottom: 10px;
    }

    .tabel0 td {
        padding: 2px 10px;
    }

    #tabel1 td,
    #tabel1 th {
        border: 1px solid black;

        padding: 5px;
    }

    #tabel1 th {
        background-color: #ddd;
    }

    #tabel1 tr:nth-child(even) {
        background-color: #eee;
    }

    #gambar {
        display: inline-block;
        width: 150px;
        height: 200px;
        margin: 2px;
        border: 1px solid black;
    }

    @media print {
        td {
            font-size: 11px;

        }

        @page {
            size: A4 portrait;
            margin: 7mm;
        }

        #tabel3 {
            page-break-before: always;
        }

        #itemproduk {
            width: 140px !important;
        }

        #gambar {
            display: inline-block;
            width: 142px;
            height: 200px;
            margin: 2px;
            border: 1px solid black;
        }
    }

    #colom {
        text-align: center;
    }

    #Header,
    #Footer {
        display: none !important;
    }

    b {
        font-weight: bold;
        font-size: 13px;
    }
    </style>
</head>

<body>
    <!-- =========================================================================================== tabel spk lilin ==============================================================================================-->

    <table class="tabel0" style="background-color: #F0FFF0;">
        <tr>
            <td colspan="5" style="font-size:16px; padding-bottom: 10px; ">From Cetak Registrasi Karet<b>
                </b>
            </td>
            <td>

            </td>
        </tr>
        <tr>
            <td width="350">ID Registrasi Karet</td>
            <td width="1">:</td>
            <td width="600"><b>{{ $RubberregistrationHeader->IDrubberregis }}</b></td>
            <input type="hidden" id="IDSPK" value=" {{ $RubberregistrationHeader->IDrubberregis }}">

            <td width="330"></td>
            <td width="1"></td>
            <td width="400"></td>
        </tr>
        <tr>
            <td width="200">Admin</td>
            <td width="1">:</td>
            <td width="800">{{ $RubberregistrationHeader->UserName }}</td>

            <td>Tanggal </td>
            <td>:</td>
            <td> {{ $RubberregistrationHeader->EntryDate }}</td>
        </tr>
        <tr>
            <td> </td>
        </tr>
    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
            style="border-bottom: 1px solid black; font-size : 12px; text-align:center;">
            <tr style="text-align: center !important">
                <th>No</th>
                <th>ID Karet</th>
                <th colspan="1">Produk Dalam Karet</th>
                <th>Lem</th>
                <th>Lac</th>
                <th>Kol</th>
                <th>Bar</th>
                <th>Kadar Karet</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody" style="border-bottom: 1px solid black; font-size : 12px; ">

            @foreach ($Rubberregistrationdata as $WaxData)
            <tr id="{{ $loop->iteration }}" style=" text-align: center !important">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $WaxData->CodeOp }}&nbsp;&nbsp;{{ $WaxData->ID }}&nbsp;&nbsp;{{ $WaxData->st }}</td>
                <td> {{ $WaxData->ItemProduct }} </td>
                <!-- <td>{{ $WaxData->Description }}</td> -->
                <td><b>{{ $WaxData->ML }}</b></td>
                <td><b>{{ $WaxData->MC }}</b></td>
                <td><b>{{ $WaxData->MK }}</b></td>
                <td><b>{{ $WaxData->MB }}</b></td>
                <td>{{ $WaxData->Kadar }}</td>
            </tr>
            @endforeach
        </tbody>
        <tr>

        </tr>
        <table>

            <tr>
                <td style="height: 50px; text-align: center;">
                </td>
                <td style="padding-left: 50px"> Diberikan Oleh</td>
                <td style="padding-left: 130px"> Diterimah Oleh</td>
                <td style="padding-left: 20px">
                    Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{$username}}
                </td>
            </tr>

            <tr>
                <td style="text-align: center; vertical-align: text-top; padding-left: 20px">
                </td>
                <td style="padding-left: 60px">( {{$username}} )</td>
                <td style="padding-left: 140px">(...........)</td>
                <td style="padding-left: 60px;"> </td>
            </tr>
        </table>

    </table>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

<script>
window.onload = function() {
    window.print();
}
</script>
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
        text-align: left;
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
            width: 70px !important;
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
            <td colspan="5" style="font-size:20px; padding-bottom: 10px; "><b>SPK Permintaan 3DP
                </b>
            </td>
            <td>
                <div id="colorcadar" class="colorcadar"
                    style="background-color: {{ $printspk3dp[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                    &nbsp;
                </div>
            </td>
        </tr>
        <tr>
            <td width="100">NO SPK</td>
            <td width="1">:</td>
            <td width="1000"><b>{{ $printspk3dp[0]->ID }}</b></td>
            <input type="hidden" id="IDSPK" value=" {{ $printspk3dp[0]->ID }}">

            <td width="70">Kadar</td>
            <td width="1">:</td>
            <td width="100"><b id="bold">{{$printspk3dp[0]->kadar }}</b></td>
        </tr>

        <tr>
            <td> </td>
        </tr>
    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
            style="border-bottom: 1px solid black; font-size : 12px; ">
            <tr style="text-align: center">
                <th>No</th>
                <th>SPK PPIC</th>
                <!-- <th Width="10%">Produk Jadi</th> -->
                <th>SKU</th>
                <th width="20%" id="itemproduk">Item Produk</th>
                <th>Pcs</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody" style="border-bottom: 1px solid black; font-size : 12px; ">
            <?php $sum_totalQty = 0 ?>
            @foreach ($printspk3dp as $itemdc)
            <tr id="{{ $itemdc->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $itemdc->SPKPPIC1 }} </td>
                <!-- <td style="word-wrap: break-word;">{{ $itemdc->ProdukJadi }} </td> -->
                <td> <span class="badge bg-dark" style="font-size:14px; word-wrap: break-word;">{{ $itemdc->SW }}</span>
                </td>
                <td>{{ $itemdc->descripproduct }}</td>
                <td>{{ $itemdc->Qty }}</td>
                <?php $sum_totalQty += $itemdc->Qty ?>
            </tr>
            @endforeach
        </tbody>
        <tr>
            <td colspan="4" style="text-align: center">Total</td>
            <td>{{ $sum_totalQty }}</td>
        </tr>
        <table>

            <tr>
                <td style="height: 50px; text-align: center;">
                    <div style="display: flex; justify-content: center; text-align: center; padding-top: 15px; padding-left: 20px"
                        id="qrcode"></div>
                </td>
                <td style="padding-left: 50px"> Diberikan Oleh</td>
                <td style="padding-left: 130px"> Diterimah Oleh</td>
                <td style="padding-left: 20px">
                    Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;Linda
                </td>
            </tr>

            <tr>
                <td style="text-align: center; vertical-align: text-top; padding-left: 20px">{{$printspk3dp[0]->ID }}
                </td>
                <td style="padding-left: 60px">( Linda )</td>
                <td style="padding-left: 140px">( Adrianus )</td>
                <td style="padding-left: 60px;"> </td>
            </tr>
        </table>

    </table>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

<script>
idspkinject = document.getElementById('IDSPK').value;
var qrcode = new QRCode("qrcode", {
    text: idspkinject,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});
window.onload = function() {
    window.print();
}
</script>
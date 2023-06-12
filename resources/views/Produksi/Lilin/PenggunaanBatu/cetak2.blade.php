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
            <td colspan="5" style="font-size:16px; padding-bottom: 10px; ">SPKO Kebutuhan Batu<b>
                </b>
            </td>
            <td>

            </td>
        </tr>
        <tr>
            <td width="200">NO SPKO Lilin</td>
            <td width="1">:</td>
            <td width="800"><b>{{ $idWaxInjectOrder }}</b></td>
            <input type="hidden" id="IDSPK" value=" {{ $IDSPKObatu }}">

            <td width="230">ID Penggunaan Batu</td>
            <td width="1">:</td>
            <td width="100"><b id="bold">{{ $IDSPKObatu }}</b> <span>- [{{ $WaxStoneHeader->Purpose }}] </span></td>
        </tr>
        <tr>
            <td width="200">Operator</td>
            <td width="1">:</td>
            <td width="800"><b>{{ $WaxStoneHeader->Employee }}</b>&nbsp;[{{ $WaxStoneHeader->idEmployee }}]</td>
            <input type="hidden" id="IDSPK" value=" {{ $IDSPKObatu }}">

            <td>Tanggal </td>
            <td>:</td>
            <td> {{ $WaxStoneHeader->EntryDate }}</td>
        </tr>
        <tr>
            <td width="200">Catatan</td>
            <td width="1">:</td>
            <td width="800"><b>{{ $WaxStoneHeader->Remarks }}</b></td>
            <input type="hidden" id="IDSPK" value=" {{ $IDSPKObatu }}">
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
                <th>SW Batu</th>
                <th width="30%" id="itemproduk">Deskripsi Batu</th>
                <th>Pcs</th>
                <th>Berat</th>
                <th>rata-rata</th>
                <th>Ketrangan</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody" style="border-bottom: 1px solid black; font-size : 12px; ">
            <?php $sum_totalPcs = 0 ?>
            <?php $sum_totalWeight = 0 ?>
            <?php $sum_totalratarata = 0 ?>
            @foreach ($WaxStoneTabel as $WaxData)
            <tr id="{{ $WaxData->IDSPKOBatu }}">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $WaxData->SWWorkOrder }} </td>
                <td> <span class="badge bg-dark"
                        style="font-size:14px; word-wrap: break-word;">{{ $WaxData->Stone }}</span>
                </td>
                <td>{{ $WaxData->Description }}</td>
                <td>{{ $WaxData->Qty }}</td>
                <td>{{ $WaxData->Weight }}</td>
                <td><?php $ratarata = $WaxData->Weight / $WaxData->Qty; 
                $angka_format = number_format($ratarata,4)?>{{$angka_format}}</td>
                <td>{{ $WaxData->Note }}</td>
                <?php $sum_totalPcs += $WaxData->Qty ?>
                <?php $sum_totalWeight += $WaxData->Weight ?>
                <?php $sum_totalratarata += $angka_format ?>
            </tr>
            @endforeach
        </tbody>
        <tr>
            <td colspan="5" style="text-align: center">Total</td>

            <td><b>{{ $sum_totalWeight }}</b></td>

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
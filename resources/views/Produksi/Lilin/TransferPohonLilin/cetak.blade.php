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
    <title>Transfer Pohon Lilin</title>

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

        #tbody1 {
            border-color: #090cd9 !important;
        }
    }

    #colom {
        text-align: center;
    }

    #Header,
    #Footer {
        display: none !important;
    }

    #bold {
        font-size: 16px;
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
            <td colspan="3" style="font-size:20px; padding-bottom: 10px; "><b>Transfer Pohon Lilin ke GLC </b>
            </td>
            <td colspan="2" style="text-align: right;"><b>{{ $TMwaxtree_tabelcetak[0]->Carat }}</b></td>
            <td>
                <div id="colorcadar" class="colorcadar"
                    style="background-color: {{ $TMwaxtree_tabelcetak[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                    &nbsp;
                </div>
            </td>
            <td colspan="4"></td>
            <td rowspan="5" style="height: 50px; text-align: center; padding-top: 10px;">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <br><span style="text-align: center; vertical-align: text-top;">{{ $TMwaxtree_Headercetak[0]->ID }}
                </span>
            </td>
        </tr>
        <tr>
            <td width="100">ID TM Pohon</td>
            <td width="2">:</td>
            <td width="140"><b>{{ $TMwaxtree_Headercetak[0]->ID }}</b> <input type="hidden" id="IDSPK"
                    value="{{ $TMwaxtree_Headercetak[0]->ID }}"></td>

            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($TMwaxtree_Headercetak[0]->TransDate); ?></td>

        </tr>
        <tr>
            <td>Admin Pers</td>
            <td>:</td>
            <td>{{ $TMwaxtree_Headercetak[0]->UserName }}</td>

        </tr>
        <tr>

            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{ $TMwaxtree_Headercetak[0]->Remarks }}</td>
        </tr>

        <tr>
            <td> </td>
        </tr>
    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
            style="border-bottom: 1px solid black; font-size : 12px; ">
            <tr>
                <th>No</th>
                <th>ID Pohon</th>
                <th>Tanggal</th>
                <th>NO Pohon</th>
                <th>Ukuran</th>
                <th>Qty</th>
                <th>Brt Lilin</th>
                <th>Brt Batu</th>
                <th>SPK PPIC</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody1" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            <?php $sum_Total1 = 0 ?>
            @foreach ($TMwaxtree_tabelcetak as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->TreeDate}}</td>
                <td>{{ $item->Plate }}</td>
                <td>{{ $item->TreeSize }}</td>
                <td>{{ $item->Qty }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += $item->Weight ?>
                <?php $sum_Total1 += $item->WeightStone ?>
            </tr>
            @endforeach
            <tr>
                <td colspan="6"></td>
                <td>
                    <b>{{ $sum_Total}}</b>
                </td>
                <td>
                    <b>{{ $sum_Total1}}</b>
                </td>
            </tr>
        </tbody>
        <table>
            <br>
            <tr>

                <td style="padding-left: 50px"> Diberikan Oleh </td>
                <td style="padding-left: 130px"> Diterimah Oleh</td>
                <td style="padding-left: 20px; height: 0px; padding-top: 0px;">
                    Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $TMwaxtree_Headercetak[0]->UserName }}
                </td>
            </tr>
            <tr>

                <td style="padding-left: 60px"><br><br>( ............... )</td>
                <td style="padding-left: 140px; vertical-align: text-top;"><br><br>( .............. )</td>
                <td style=" padding-left: 60px;"> </td>
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
    // var kadar9 = ('#totalpohon8').val();
    window.print();
}
</script>
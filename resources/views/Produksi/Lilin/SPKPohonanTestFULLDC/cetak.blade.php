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

    @media print {
        @page {
            size: A4 portrait;
            margin: 0mm;
        }

        b {
            font-weight: bold;
            font-size: 13px;
        }
    }

    b {
        font-weight: bold;
        font-size: 13px;
    }

    #colom {
        text-align: center;
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
            <td colspan="8" style="font-size:20px; padding-bottom: 10px; "><b>SPK Pohonan (Direct Casting)</b>
            </td>
            <td>
                <div id="colorcadar" class="colorcadar"
                    style="background-color: {{ $datacetak[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                    &nbsp;
                </div>
            </td>
        </tr>
        <tr>
            <td width="100">ID SPK</td>
            <td width="2">:</td>
            <td width="150"><b>{{ $datacetak[0]->ID }}</b></td>
            <input type="hidden" id="IDSPK" value="{{ $datacetak[0]->ID }}">

            <td width="80">Piringan</td>
            <td width="2">:</td>
            <td width="150"><b>{{ $datacetak[0]->pkaret }}</b> [{{ $datacetak[0]->RubberPlate }}] </td>

            <td width="80">Kadar</td>
            <td width="2">:</td>
            <td width="200"><b>{{ $datacetak[0]->kadar }}</b></td>
            <input type="hidden" id="color" value="{{ $datacetak[0]->HexColor }}">
        </tr>


        <tr>
            <td>Nama Operator</td>
            <td>:</td>
            <td>{{ $datacetak[0]->emp }} - [{{ $datacetak[0]->IDK }}]</td>

            <td>Admin Persiapan</td>
            <td>:</td>
            <td>{{ $datacetak[0]->UserName }}</td>

            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($datacetak[0]->TransDate); ?></td>
        </tr>

        <tr>
            <td>NO. Kotak</td>
            <td>:</td>
            <td><b>{{ $datacetak[0]->BoxNo }}</b></td>

            <td>Kelompok</td>
            <td>:</td>
            <td><b>{{ $datacetak[0]->WorkGroup }}</b></td>

            <td>Stick Pohon</td>
            <td>:</td>
            <td>{{ $datacetak[0]->stickpohon}}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{ $datacetak[0]->Remarks }}</td>
        </tr>


        <tr>
            <td> </td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2" style="center">
            <tr style="text-align:center;">
                <th>NO.</th>
                <th>WorkOrder</th>
                <th>SKU</th>
                <th>Descripsi</th>
                <th>Kadar</th>
                <th>ID TM</th>
                <th>QTy</th>
                <!-- <th>WaxOrder</th>
                <th>WaxOrderOrd</th>
                <th>WorkOrder</th>
                <th>WorkOrderOrd</th> -->
            </tr>
        </thead>
        <tfoot>
            <hr>

        </tfoot>
        {{-- {{ dd($DaftarProduct); }} --}}
        <tbody>
            @foreach ($datatabelcetak as $wax )
            <tr id="colom">
                <td id="colom">{{ $loop->iteration }}</td>
                <td id="colom"><span style="font-size: 14px" class="badge bg-dark">{{ $wax->swworkorder }}</span><br>
                </td>
                <td id="colom">{{ $wax->SKU}}</td>
                <td id="colom">{{ $wax->Description }}</td>
                <td id="colom">{{ $wax->Kadar }}</td>
                <td id="colom">{{ $wax->ID }}</td>
                <td id="colom">{{ $wax->Qty }}</td>
                <!-- <td id="colom">{{ $wax->WaxOrder }}</td>
                <td id="colom">{{ $wax->WaxOrderOrd }}</td>
                <td id="colom">{{ $wax->WorkOrder }}</td>
                <td id="colom">{{ $wax->WorkOrderOrd }}</td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table>
        <tr>
            <td style="height: 50px; text-align: center; padding-top: 10px;">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
            </td>
            <td style="padding-left: 50px"> Diberikan Oleh</td>
            <td style="padding-left: 130px"> Diterimah Oleh</td>
            <td style="padding-left: 20px">
                Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $datacetak[0]->UserName }}
            </td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: text-top;">{{ $datacetak[0]->ID }} </td>
            <td style="padding-left: 60px">( Linda )</td>
            <td style="padding-left: 140px">( Adrianus )</td>
            <td style="padding-left: 60px;"> </td>
        </tr>
    </table>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

<script>
idspkpohon = document.getElementById('IDSPK').value;
var qrcode = new QRCode("qrcode", {
    text: idspkpohon,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// muculcolor = $('#color').val();

window.onload = function() {
    window.print();
}
</script>
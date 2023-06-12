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
        #tabel1{
            width: 100%;
        }

        #tabel2{
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

        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 1mm 1mm 1mm;
            }
        }

        #Header,
        #Footer {
            display: none !important;
        }
    </style>

</head>

<body>

    <table class="tabel0">
        <tr>
            <td colspan="8"
                style="font-size:16px; padding: 8px; border: 2px solid black; background-color: #ddd;"><b>SPK Percobaan Tanpa Karet</b> </td>
        </tr>
        <tr>
            <td width="16%">ID Karyawan</td>
            <td width="1%">:</td>
            <td width="23%">{{ $iduser }}</td>
            <td width="9%">No Form</td>
            <td width="1%">:</td>
            <td width="18%">{{ $getheader[0]->SW }} <input type="hidden" id="id" value="{{ $id }}">
            </td>
            <td rowspan="2"
                style="border: 1px solid black; height: 55px; font-size: 30px; font-weight: bold; text-align: center;">
                {{ $getheader[0]->OtherDescription }}{{ $getheader[0]->Description }}</td>
            <td rowspan="2" width="55" align="center">
                <div id="qrcode1">{!! QrCode::size(50)->style("round")->generate($id) !!}</div>
            </td>
        </tr>

        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $fullname }}</td>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ tgl_indo($getheader[0]->TransDate) }}</td>
        </tr>

    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Kategori</th>
                <th>SKU / Produk ID</th>
                <th>ID Karet</th>
                <th>Qty</th>
                <th>Kepala</th>
                <th>Komponen</th>
                <th>Mainan</th>
            </tr>
        </thead>
        <tbody>
            {{-- {{ dd($data) }} --}}
            @php
                $i = 0;
                $totalKepala = 0;
                $totalMainan = 0;
                $totalKomponen = 0;
            @endphp
            @foreach ($getbodys as $data1)
                @php
                    $i++;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data1->Kategori }}</td>
                    <td>{{ $data1->SW }}</td>
                    <td>{{ $data1->Remarks }}</td>
                    <td>{{ $data1->Qty }}</td>
                    <td>{{ $getkepala[$i][0]->Serial }}</td>
                    <td>{{ $getcomponent[$i][0]->Serial }}</td>
                    <td>{{ $getmainan[$i][0]->Serial }}</td>
                </tr>
                @php
                    $totalKomponen += $getcomponent[$i][0]->Jumlahcomponent;
                    $totalKepala += $getkepala[$i][0]->JumlahKepala;
                    $totalMainan += $getmainan[$i][0]->Jumlahmainan;
                @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4">Total :</td>
                <td align="center">{{ $data1->TotalQty }}</td>
                <td>Total K : {{ $totalKepala }}</td>
                <td>Total K : {{ $totalKomponen }}</td>
                <td>Total M : {{ $totalMainan }}</td>
            </tr>
        </tfoot>
    </table>

    <br>

    <table class="table table-border table-hover table-sm" id="tabel2">
        <thead class="table-secondary zindex-2">
            <tr bgcolor="#ddd">
                <th class="text-center" style="font-size: 13px; font-weight: bold; color: black;">No</th>
                <th class="text-center" style="font-size: 13px; font-weight: bold; color: black;">Kode Batu</th>
                <th class="text-center" style="font-size: 13px; font-weight: bold; color: black;">Total</th>
            </tr>
        </thead>

        <body>
            @php
            $i = 0;
            @endphp

            @foreach ($getStone3 as $getStone)
            @php
            $i++;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $i }}</td>
                <td style="text-align: center;">{{ $getStone->SW }}</td>
                <td style="text-align: center;">{{ $getStone->Jumlah }}</td>
                </tr>
            @endforeach
        </body>
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

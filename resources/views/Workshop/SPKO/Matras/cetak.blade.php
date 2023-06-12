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
    <title>Cetak SPKO</title>

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
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b>SPKO Matras Baru </b>
            </td>
        </tr>
        <tr>
            <td width="120">ID Karyawan</td>
            <td width="2">:</td>
            <td>{{$matrasAllocation->Employee}}</td>
            <td width="120">ID SPKO</td>
            <td width="2">:</td>
            <td width="200">{{$matrasAllocation->ID}}</td>
            <input type="hidden" id="idspko" value="{{$matrasAllocation->ID}}">
        </tr>

        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{$matrasAllocation->Operator}}</td>
            <td>No SPKO</td>
            <td>:</td>
            <td>{{$matrasAllocation->SW}}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($matrasAllocation->TransDate); ?></td>
            <td>Proses</td>
            <td>:</td>
            <td>Pembuatan Matras Baru</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{$matrasAllocation->Remarks}}</td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Matras</th>
                <th>Jenis Matras</th>
                <th>Tipe Matras</th>
                <th>Produk Matras</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matrasAllocation->MatrasAllocationItems as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->Matras->SW}}</td>
                    <td>{{$item->Matras->JenisMatras}}</td>
                    <td>{{$item->Matras->TipeMatras}}</td>
                    <td>
                        @foreach ($item->Matras->Items as $itemProduct)
                            {{$itemProduct->Product->SW}} &nbsp;
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
            <td> {{$matrasAllocation->Admin}} </td>
            <td></td>
            <td> {{$matrasAllocation->Operator}} </td>
            <td></td>
        </tr>

    </table>

    <br><hr><br>
    <table class="tabel0" style="background-color: #F0FFF0;">
        <tr>
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b>Material Request Pembuatan Matras</b>
            </td>
        </tr>
        <tr>
            <td width="120">ID Karyawan</td>
            <td width="2">:</td>
            <td>{{$matrasAllocation->Employee}}</td>
            <td width="120">ID SPKO</td>
            <td width="2">:</td>
            <td width="200">{{$matrasAllocation->ID}}</td>
            <input type="hidden" id="idspko" value="{{$matrasAllocation->ID}}">
        </tr>

        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{$matrasAllocation->Operator}}</td>
            <td>No SPKO</td>
            <td>:</td>
            <td>{{$matrasAllocation->SW}}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($matrasAllocation->TransDate); ?></td>
            <td>Proses</td>
            <td>:</td>
            <td>Pembuatan Matras Baru</td>
        </tr>
        <tr>
            <td>SPK PCB</td>
            <td>:</td>
            <td colspan="4">{{implode($matrasAllocation->spkPCB)}}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{$matrasAllocation->Remarks}}</td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Nama Material</th>
                <th>Jumlah Material</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matrasAllocation->materialMatras as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->NamaMaterial}}</td>
                    <td>{{$item->Qty}}</td>
                </tr>
            @endforeach
        </tbody>
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
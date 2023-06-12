<?php

function tgl_indo($tanggal)
{
    $date = strtotime($tanggal);
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

    return date('d',$date).' '. $bulan[date('n',$date)].' '.date('Y',$date);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Form Gambar Teknik</title>

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
    <table class="tabel0" border="0" style="background-color: #F0FFF0;">
        <tr>
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b>Form Gambar Teknik </b>
            </td>
            <td rowspan="5" width="5%"><div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div></td>
        </tr>
        <tr>
            <td width="18%">ID Karyawan</td>
            <td width="1%">:</td>
            <td width="10%">1</td>
            <td width="18%">ID Gambar Teknik</td>
            <td width="1%">:</td>
            <td width="10%">{{$gambarTeknik->ID}}</td>
            <input type="hidden" id="idspko" value="{{$gambarTeknik->ID}}">
        </tr>
        <tr>
            <td width="18%">Nama Karyawan</td>
            <td width="1%">:</td>
            <td width="10%">{{$gambarTeknik->UserName}}</td>
            <td width="18%">Jenis Matras</td>
            <td width="1%">:</td>
            <td width="10%">{{$gambarTeknik->JenisMatras}}</td>
        </tr>
        <tr>
            <td width="18%">Tanggal</td>
            <td width="1%">:</td>
            <td width="10%"><?php echo tgl_indo($gambarTeknik->EntryDate); ?></td>
            <td width="18%">Jumlah Matras</td>
            <td width="1%">:</td>
            <td width="10%">{{$gambarTeknik->JumlahMatras}}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{$gambarTeknik->Remarks}}</td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Matras</th>
                <th>Gambar Matras</th>
                <th>Jenis Matras</th>
                <th>Tipe Matras</th>
                <th>Produk Matras</th>
                <th>Material Matras</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gambarTeknik->GambarTeknik as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->Matras->SW}}</td>
                    <td><img width="100" height="100" src="{{Session::get('hostfoto')}}/rnd2/Workshop/AutocadFile/{{$item->FotoGambarTeknik}}" alt=""></td>
                    <td>{{$item->Matras->JenisMatras}}</td>
                    <td>{{$item->Matras->TipeMatras}}</td>
                    <td style="vertical-align: top">
                        <table class="table table-border table-hover table-sm" id="tabel1">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr>
                                    <th>No</th>
                                    <th>Product</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->Items as $productItem)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$productItem->wipWorkshop->Product->SW}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td style="vertical-align: top">
                        <table class="table table-border table-hover table-sm" id="tabel1">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr>
                                    <th>Material</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->Matras->Materials as $MaterialMatras)
                                    <tr>
                                        <td>{{$MaterialMatras->rawMaterial->Name}}</td>
                                        <td>{{$MaterialMatras->Qty}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
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
    // setTimeout(window.close, 0);
}
</script>
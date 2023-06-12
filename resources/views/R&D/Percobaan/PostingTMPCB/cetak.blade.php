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
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b style="font-size: 30px;">WIP Grafis<span style="font-size: 8px;">(@if ($location == 47) Enamel @else Sepuh @endif)</span> </b>
            </td>
            <td rowspan="3"><div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div></td>
        </tr>
        <tr>
            <td width="120">Tanggal</td>
            <td width="2">:</td>
            <td><?php echo tgl_indo($datenow); ?></td>
            <td width="120">No. NTHKO</td>
            <td width="2">:</td>
            <td width="200">{{ $noNTHKO }}</td>
            <input type="hidden" id="noNTHKO" value="{{ $noNTHKO }}">
        </tr>

        <tr>
            <td>Jumlah Item</td>
            <td>:</td>
            <td>{{ $jumlahItem }}</td>
            <td>Total Berat Item</td>
            <td>:</td>
            <td>{{ $totalBeratItem }}</td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Gambar</th>
                <th>Kode Produk</th>
                <th>SKU</th>
                <th>Description</th>
                <th>Variasi</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>

            {{-- {{ dd($data) }} --}}

            @foreach ($wipItems as $item)
            <tr>
                <td width="5%">{{ $loop->iteration }}</td>
                <td width="15%"><img src="{{Session::get('hostfoto')}}/image/{{$item->Photo}}.jpg" style="height: 100px; width:100px" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></td>
                <td width="15%">{{ $item->productCategory }}-{{ $item->SerialNo }}-{{ $item->kadar }}</td>
                <td width="25%">{{ $item->SKU }}</td>
                <td width="25%">{{ $item->productDescription }}</td>
                {{-- Jika kadar 8K maka pakai variasi. Kalau bukan 8K maka hanya ada 1 variasi. (di PCB hanya ada kadar 8K dan 16K) --}}
                @if ($location == 47)
                    <td width="10%">Variasi 0{{$item->OrdinalVariation}}</td>
                @else
                    @if ($item->idKadar == 3)
                        @if ($item->stoneColor == "Putih")
                            <td width="10%">Variasi 1</td>
                        @elseif($item->stoneColor == "Merah")
                            <td width="10%">Variasi 2</td>
                        @elseif($item->stoneColor == "Merah Muda")
                            <td width="10%">Variasi 3</td>
                        @else
                            <td width="10%">Variasi 1</td>
                        @endif
                    @else
                        <td width="10%">Variasi 1</td>
                    @endif
                @endif
                <td width="15%">{{ $item->berat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>

<script>
let idtm = document.getElementById('noNTHKO').value;
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
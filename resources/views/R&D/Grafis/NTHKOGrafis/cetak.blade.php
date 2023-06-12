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
            <td colspan="6" style="font-size:15px; padding-bottom: 10px; "><b>Nota Hasil Kerja Operator Grafis </b>
            </td>
        </tr>
        <tr>
            <td width="120">ID Karyawan</td>
            <td width="2">:</td>
            <td>{{ $header[0]->idAdmin }}</td>
            <td width="120">No Workorder</td>
            <td width="2">:</td>
            <td width="200">{{ $items[0]->swwo }}</td>
        </tr>

        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $header[0]->adminName }}</td>
            <td width="120">No NTHKO</td>
            <td width="2">:</td>
            <td width="200">{{ $header[0]->WorkAllocation }}</td>
            <input type="hidden" id="idspko" value="{{ $header[0]->ID }}">

        </tr>
        <tr>
            <td>Qty Total</td>
            <td>:</td>
            <td>{{ $header[0]->Qty }}</td>
            <td>Tanggal</td>
            <td>:</td>
            <td>
                <?php echo tgl_indo($header[0]->TransDate); ?>
            </td>

        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td></td>
            <td>Berat Total</td>
            <td>:</td>
            <td>
                {{ $header[0]->Weight }}
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>Kode Produk</th>
                <th>Description</th>
                <th>Next Proses</th>
                <th>Kadar</th>
                <th>Qty</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
            @php   
                $previousProduct = null;
                $total = 0;
                $no = 0;
            @endphp
            @foreach ($items as $item)
                @if ($item->Product !== $previousProduct && $previousProduct !== null)
                    <tr style="background-color: #ddd; font-weight: bold;">
                        <td colspan="6" style="text-align: right">Total &emsp; </td>
                        <td>{{ $total }}</td>
                    </tr>
                    <tr style="background-color: white;"><td style="border: 0;"></td></tr>
                    @php
                        $total = 0;
                        $no = 0;
                    @endphp
                @endif
                @php
                    $no++;
                @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td><span class="badge bg-dark" style="font-size: 14px;">{{ $item->namaProduct }}</span></td>
                    <td>{{ $item->descriptionProduct }}</td>
                    <td>{{ $item->next }}</td>
                    <td>{{ $item->kadar }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->berat }}</td>
                </tr>
                @php
                    $total += $item->berat;
                    $previousProduct = $item->Product;
                @endphp
            @endforeach
            <tr style="background-color: #ddd; font-weight: bold;">
                <td colspan="6" style="text-align: right">Total &emsp; </td>
                <td>{{ $total }}</td>
            </tr>
            <tr style="background-color: white;"><td style="border: 0;"></td></tr>
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
                <div style="display: flex; justify-content: center; text-align: center;">{!! QrCode::size(60)->style("round")->generate($header[0]->WorkAllocation ) !!}
                </div>
            </td>
        </tr>

        <tr>
            <td></td>
            <td> {{ $header[0]->adminName }} </td>
            <td></td>
            <td> Admin PCB </td>
            <td></td>
        </tr>

    </table>

</body>

</html>

<script>


    window.onload = function() {
        window.print();
        // setTimeout(window.close, 0);
    }
</script>

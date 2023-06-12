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
        margin-bottom: 1px;
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

        }


        #tabel3 {
            page-break-before: always;
        }

        #gambar {
            display: inline-block;
            width: 142px;
            height: 200px;
            margin: 2px;
            border: 1px solid black;
        }

        #data {
            text-align: center;
            padding: 2px;
            margin: 2px;
            font-size: 12px;
        }
    }

    #colom {
        text-align: center;
    }

    #data {
        text-align: center;
        padding: 2px;
        margin: 2px;
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
            <td colspan="5" style="font-size:20px; padding-bottom: 3px; text-align: center;"><b>
                    {{ $infolemari[0]->Description }} laci 1 sampai 13
                </b>
            </td>
        </tr>
    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
            style="border-bottom: 1px solid black; font-size : 12px; ">
            <tr style="text-align: center">
                <th>Kolom 1</th>
                <th>Kolom 2</th>
                <th>Kolom 3</th>
                <th>Kolom 4</th>
                <th>Kolom 5</th>
                <th>Kolom 6</th>
                <th>Kolom 7</th>
                <th>Kolom 8</th>
                <th>Kolom 9</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody" style="border: 1px solid black; font-size : 16px; ">
            <table width="100%">
                <tr>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom1 AS $tess)
                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom2 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom3 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom4 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom5 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom6 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom7 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom8 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="p-4">
                            @foreach ($kolom9 AS $tess)

                            <tr>
                                <td>
                                    <div class="card p-2 my-2 center" id="data"
                                        style="border: 1px solid black; padding: 1px; font-size : 12px; ">
                                        {!! $tess->datamu !!}
                                    </div>
                                </td>
                            </tr>

                            @endforeach
                        </table>
                    </td>

                </tr>
            </table>
        </tbody>
        <table>
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
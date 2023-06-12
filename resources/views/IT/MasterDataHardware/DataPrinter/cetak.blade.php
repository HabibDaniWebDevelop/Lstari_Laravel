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

        @media print {

            table {
                width: 310px;
            }

            td {
                font-size: 11px;
            }


            @page {
                size: A4 portrait;
                margin: 5mm 5mm 5mm 5mm;
            }
        }

        #Header,
        #Footer {
            display: none !important;
        }
    </style>

</head>

<body>
	{{-- {{ dd($data); }} --}}
    @foreach ($data as $data1)
    @endforeach

   <table widht="50%" style="border: 3px solid black;">
        <tr>
            <td width="100%">
                <table width="100%" cellpadding="2">
                    <tr>
                        <td align="center" style="font-weight: bold">IDENTIFIKASI SPESIFIKASI PRINTER</td>
                    </tr>
                    <tr>
                        <td align="center" style="font-weight: bold; border-bottom: 2px solid black;">PT. Lestari Mulia
                            Sentosa</td>
                    </tr>
                </table>
                <table width="100%" cellpadding="2">
                    <tr>
                        <td width="40%">Kode Printer</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->SW }}
                        </td>
                    </tr>
                </table>
                <table width="100%" cellpadding="2">
                    <tr>
                        <td width="100%">Spesifikasi</td>
                    </tr>
                </table>
                <table width="100%" cellpadding="2">
                    <tr>
                        <td width="5%"></td>
                        <td width="35%">IP Address</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Var1 }}</td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="35%">Brand</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Brand }}</td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="35%">Type</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->SubType }}</td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="35%">Series</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Series }}</td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="35%">Serial Number</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->SerialNo }}</td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="35%">Catatan</td>
                        <td width="2%">:</td>
                        <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Note }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>

<script>
    window.onload = function() {
        window.print();
    }
</script>

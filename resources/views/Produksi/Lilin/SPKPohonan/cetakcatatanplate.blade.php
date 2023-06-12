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
            font-size: 16px;
        }


        @page {

            margin: 5mm 5mm 5mm 5mm;
        }
    }


    #tb,
    #box {
        border-bottom: 1px solid;
    }

    #tbu {
        border: 1px solid;
    }

    #box {
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
        font-size: 30px;
    }

    td {
        font-size: 16px;
    }

    #data {
        font-weight: bold;
    }

    #grub {
        font-weight: bold;
    }

    #Header,
    #Footer {
        display: none !important;
    }
    </style>

</head>

<body>
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            @foreach ($printbarcode as $pbc)
            <table id="tbu" width="100%" style="background-color : #fff;">
                <tr>
                    <td id="tb">Kelompok : <span id="grub">{{ $pbc->WorkGroup }}</span> </td>
                    <td id="box" rowspan="2">{{ $pbc->BoxNo }}</td>
                </tr>
                <tr>
                    <td id="tb">Stik : <span id="data">{{ $pbc->stickpohon }}</span> </td>
                </tr>
                <tr>
                    <td id="tb">No Pohon : <span id="data">{{ $pbc->pkaret }}</span> </td>
                    <td id="tb">SPK Inject : <span id="data"> {{ $pbc->ID }}</span> </td>
                </tr>
                <tr>
                    <td id="tb">Tanggal : <span id="data">{{ $pbc->TransDate }}</span> </td>
                    <td id="tb">QTY Total : <span id="data">{{ $pbc->Qty }}</span></td>
                </tr>
                <tr>
                    <td colspan="2"> jenis :
                        @foreach ($printbarcode1 as $pbc1)
                        <span id="data"> ({{ $pbc1->product }}) <span> </span></span>
                        @endforeach
                    </td>
                </tr>
            </table>
            @endforeach
        </tr>
    </thead>
</body>

</html>
<script>
let items = document.querySelectorAll('.currency');
items.forEach(items => {
    items.innerHTML = toRupiah(items.innerHTML, {
        floatingPoint: 0
    })
});
window.onload = function() {
    window.print();
}
</script>
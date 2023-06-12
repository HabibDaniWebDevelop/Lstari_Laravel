<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Cetak NTHKO Pohonan</title>
        <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.min.css') !!}">
        <style type="text/css">
            body {
                font-family: arial;
                font-size: 13px;
            }

            @media print {

                @page {
                    size: A4 portrait;
                    margin: 5mm 5mm 5mm 5mm;
                }
                div {
                    break-inside: avoid;
                }
            }
        </style>
    </head>
    <body>
        <table width="100%">
            <tr>
                <td align="center">
                    <h3>NTHKO Pohonan</h3>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <thead>
              <tr>
                <td width="20%">Operator</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->NamaOp}}</td>
                <td width="20%">Berat Batu</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->WeightStone}}</td>
                <td width="50%" rowspan="4">
                    <div id="qrcode"></div>
                </td>
              </tr>
              <tr>
                <td width="20%">No SPK <input type="hidden" value="{{$data->ID}}" id="idWaxTree"></td>
                <td width="2%">:</td>
                <td width="28%">{{$data->WorkOrder}}</td>
                <td width="20%">Kadar</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->Kadar}}</td>
              </tr>
              <tr>
                <td width="20%">No. Pohonan</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->SW}}</td>
                <td width="20%">Berat Lilin</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->Weight}}</td>
              </tr>
              <tr>
                <td width="20%">Tanggal</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->EntryDate}}</td>
                <td width="20%">Berat Pohon</td>
                <td width="2%">:</td>
                <td width="28%">{{$data->WeightWax}}</td>
              </tr>
            </thead>
            </table>
        <br><br>
        <table class="table table-bordered table-sm text-center">
            <thead>
                <th width="6%"> NO </th>
                <th width="10%">WorkOrder</th>
                <th width="10%">No SPK</th>
                <th width="15%">ID Barang</th>
                <th width="29%">Barang</th>
                <th width="8%">Kadar</th>
                <th width="6%">Jumlah</th>
                <th width="10%">Keterangan</th>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->WorkOrder}}</td>
                    <td>{{$item->SW}}</td>
                    <td>{{$item->Product}}</td>
                    <td>{{$item->Barang}}</td>
                    <td>{{$item->Kadar}}</td>
                    <td>{{$item->Qty}}</td>
                    <td>{{$item->Remarks}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.bundle.min.js') !!}"></script>
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
        <script>
            let idWaxTree = document.getElementById('idWaxTree').value
            var qrcode = new QRCode("qrcode", {
                text: idWaxTree,
                width: 60,
                height: 60,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        </script>
    </body>
</html>
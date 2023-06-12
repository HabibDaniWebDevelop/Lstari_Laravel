<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<style type="text/css" media="print">
@media print {
    body {
        font-family: arial;
    }

    table {
        width: 310px;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    #garisbawah {
        font-size: 15px;
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        border: 1px solid black;
    }

    #garistengah {
        font-size: 15px !important;
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        border: 1px solid black;
    }

    #garisatas {
        width: "100%";
        font-weight: bold;
        font-size: 13px;
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        border: 1px solid gray;
    }

    #rapikan {
        white-space: nowrap !important;
        width: 10px !important;
        overflow: hidden !important;
        text-overflow: clip !important;
        border: 1px solid #000000 !important;
    }

    td {
        font-size: 15px;
    }

    @page {

        margin: 2mm;
    }

}

#garisbawah {
    font-size: 15px;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border: 1px solid gray;
}

#garistengah {
    font-size: 15px !important;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border: 1px solid gray;

}

#garisatas {
    width: "100%";
    font-weight: bold;
    font-size: 13px;
    border-bottom: 1px solid black;
    border-right: 1px solid black;
    border: 1px solid gray;
}

#rapikan {
    white-space: nowrap !important;
    width: 10px !important;
    overflow: hidden !important;
    text-overflow: clip !important;
    border: 1px solid !important;
}

#hightlight {
    font-size: 30px;
}

#Header,
#Footer {
    display: none !important;
}

.table {
    border: 1px;
    padding: 0px;
}
</style>

<body>
    <input type="hidden" id="idWaxTree" value="{{$data->ID}}">
    <table width="100%" id="tebel" style="table-layout:fixed;">
        <tbody <tr>
            <td>
                <table width="100%" id="tebel" style="table-layout:fixed;">
                    <tbody>
                        <tr width="100%" style="font-size:18px;">
                            <b>{{$data->Remarks}}</b>
                        </tr>
                        <tr>
                            <td colspan="4" align="left" width="100%" style="font-size:18px;">POHONAN INJECT:
                                <b>{{$data->ID}}</b>
                            </td>
                        </tr>
                        <hr>
                        <tr>
                            <td colspan="2" id="hightlight" style="font-size:18px;"><b>{{$data->Kadar}}</b>
                            </td>
                            <td colspan="2">{{$pendinginan[0]->GipsNote}}</td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" cellpadding=4 cellspacing=4 id="tebel">
                    <tbody>
                        <tr id="garisbawah" style="font-size: 18px; border-bottom: 1px solid black">
                            <td width="30%">Operator</td>
                            <td width="1%">:</td>
                            <td width="25%">{{$data->NamaOp}}</td>
                            <td></td>
                            <td width="35%">B.Batu</td>
                            <td width="1%">:</td>
                            <td width="10%">{{$data->WeightStone}}</td>
                        </tr>
                        <tr id="garisbawah" style="font-size: 18px;">
                            <td width="30%">Kelompok</td>
                            <td width="1%">:</td>
                            <td width="25%">{{$data->WorkGroup}}</td>
                            <td></td>
                            <td width="35%">B.Piring</td>
                            <td width="1%">:</td>
                            <td width="10%">{{$data->BeratPiring}}</td>
                        </tr>
                        <tr id="garisbawah" style="font-size: 18px;">
                            <td width="30%">Transaksi</td>
                            <td width="1%">:</td>
                            <td width="25%" style="font-size: 12px">{{$data->TransDate}}</td>
                            <td></td>
                            <td width="35%">B.Lilin</td>
                            <td width="1%">:</td>
                            <td width="10%"><b>{{$data->Weight}}</b></td>
                        </tr>
                        <tr id="garisbawah" style="font-size: 18px;">
                            <td width="30%">No.Piring</td>
                            <td width="1%">:</td>
                            <td width="25%"><b>{{$data->SWplate}}</b></td>
                            <td></td>
                            <td width="35%">B.Total</td>
                            <td width="1%">:</td>
                            <td width="10%">{{$data->WeightWax}}</td>
                        </tr>

                    </tbody>
                </table>
                <table id="tebel" cellpadding=2 cellspacing=4 width="100%" style="table-layout:fixed; border: 1px;">
                    <thead width="100%">
                        <tr>
                            <th class="text-center" id="garisatas" style="font-weight: bold; color: black border: 1px;">
                                SPK PPIC |
                                Barang</th>
                            <th id="garisatas" class="text-center" width="8%" style=" font-weight: bold; color: black">
                                Qty</th>
                            <th id="garisatas" class="text-center" width="8%" style=" font-weight: bold; color: black">
                                TJ</th>
                            <th id="garisatas" class="text-center" width="8%" style=" font-weight: bold; color: black">
                                BL</th>
                            <th id="garisatas" class="text-center" width="8%" style=" font-weight: bold; color: black">
                                BP</th>
                            <th id="garisatas" class="text-center" width="8%" style=" font-weight: bold; color: black">
                                KR</th>
                            <th id="garisatas" class="text-center" width="8%" style=" font-weight: bold; color: black">
                                QR</th>
                        </tr>
                    </thead>
                    <tbody width="100%" cellpadding=4 cellspacing=4 style='table-layout:fixed;'>
                        @foreach ($items as $item)
                        <tr>
                            <td id="garistengah" align="left" width="20%"
                                style="font-size: 13px; font-weight: normal; color: black">
                                {{$item->SPKPPIC}}</td>
                            <td id="garistengah" align="center" width="10%"
                                style="font-size: 13px; font-weight: normal; color: black">{{$item->Qty}}
                            </td>
                            <td id="garistengah" align="center" width="10%"
                                style="font-size: 13px; font-weight: normal; color: black">
                            </td>
                            <td id="garistengah" align="center" width="10%"
                                style="font-size: 13px; font-weight: normal; color: black">
                            </td>
                            <td id="garistengah" align="center" width="10%"
                                style="font-size: 13px; font-weight: normal; color: black">
                            </td>
                            <td id="garistengah" align="center" width="10%"
                                style="font-size: 13px; font-weight: normal; color: black">
                            </td>
                            <td id="garistengah" align="center" width="10%"
                                style="font-size: 13px; font-weight: normal; color: black">
                            </td>
                        </tr>
                        <tr id="garistengah">
                            <td colspan="7">
                                <TABLE width=100% cellpadding=4 cellspacing=4 style='table-layout:fixed'>
                                    <TR>
                                        <TD colspan="6"
                                            style='text-overflow: clip; overflow: hidden; white-space: nowrap;'>
                                            {{$item->Barang}}
                                        </TD>

                                        <td class="text-wrap" style=" word-wrap: break-word;" width="120px">
                                            {{$item->NoteVariation}}
                                        </td>
                                        <td>{{$item->Logo}}</td>
                                    </TR>
                                </TABLE>
                            </td>

                        </tr>

                        @endforeach
                    </tbody>
                </table>
                <table width="100%" border="1" id="tebel">
                    <tbody>
                        <tr height="50px">
                            <td style="font-size: 13px; vertical-align: top;">Oven</td>
                            <td style="font-size: 13px; vertical-align: top;">Temp</td>
                            <td style="font-size: 13px; vertical-align: top;">Cast</td>
                            <td style="font-size: 13px; vertical-align: top;">Temp</td>
                            <td style="font-size: 13px; vertical-align: top;">Tgl</td>
                        </tr>
                        <tr height="50px">
                            <td colspan="2" style="font-size: 13px; vertical-align: top;">Op Ov/Cast</td>
                            <td colspan="2" style="font-size: 13px; vertical-align: top;">Op Semprot</td>
                            <td style="font-size: 13px; vertical-align: top;">Op Potong</td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div id="qrcode"
                                    style="display: flex; justify-content: center; padding-top: 10px; padding-bottom: 10px">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%">
                    <tbody>
                        <tr height="20px">
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            </tr>
        </tbody>
    </table>

    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>
    <script>
    window.onload = function() {
        window.print();
    }
    let idWaxTree = document.getElementById('idWaxTree').value
    var qrcode = new QRCode("qrcode", {
        text: idWaxTree,
        width: 60,
        height: 60,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    </script>

</body>

</html>
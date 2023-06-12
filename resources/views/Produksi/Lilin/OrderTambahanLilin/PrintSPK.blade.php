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
            margin: 7mm;
        }

        #tabel3 {
            page-break-before: always;
        }

        #gambar {
            display: inline-block;
            width: 137px;
            height: 190px;
            margin: 2px;
            border: 1px solid black;
        }

        #gambar2 {
            display: inline-block;
            width: 137px;
            height: 40px;
            margin: 2px;
            border: 1px solid black;
        }
    }

    #colom {
        text-align: center;
    }

    #Header,
    #Footer {
        display: none !important;
    }

    #bold {
        font-size: 16px;
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
            <td colspan="6" style="font-size:20px; padding-bottom: 10px; "><b>SPKO Inject Lilin </b> <span id="tanda"
                    class="rainbow-text">&nbsp;<b>Order Tambahan</b></span> <span style="display: none"
                    id="tandabatu">&nbsp;<b>TANPA
                        BATU</b></span>
            </td>
            <td colspan="2" style="text-align: right;"><b>{{ $printdataspk[0]->kadar }}</b></td>
            <td>
                <div id="colorcadar" class="colorcadar"
                    style="background-color: {{ $printdataspk[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                    &nbsp;
                </div>
            </td>
            <!-- <td colspan="2"></td> -->
            <td rowspan="5" style="height: 50px; text-align: center; padding-top: 10px;">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <br><span style="text-align: center; vertical-align: text-top;">{{ $printdataspk[0]->ID }} </span>

            </td>
        </tr>
        <tr>
            <td width="100">ID SPK</td>
            <td width="2">:</td>
            <td width="140"><b>{{ $printdataspk[0]->ID }}</b> <input type="hidden" id="IDSPK"
                    value=" {{  $printdataspk[0]->ID }}"></td>

            <td width="110">Piringan</td>
            <td width="2">:</td>
            <td width="150"><b>{{ $printdataspk[0]->pkaret }}</b> - [{{ $printdataspk[0]->RubberPlate }}]</td>

            <td width="100">Stick Pohon</td>
            <td width="2">:</td>
            <td width="130">{{ $printdataspk[0]->stickpohon}}<input type="hidden" id="color"
                    value="{{$printdataspk[0]->HexColor }}"></td>

        </tr>

        <tr>
            <td>Operator</td>
            <td>:</td>
            <td> <b>{{ $printdataspk[0]->emp }}</b> - [{{ $printdataspk[0]->IDK }}]</td>

            <td>RPH</td>
            <td>:</td>
            <td>{{ $tabelinjectlilin[0]->WorkScheduleID }}</td>

            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($printdataspk[0]->TransDate); ?></td>
        </tr>

        <tr>
            <td>Kelompok</td>
            <td>:</td>
            <td><b>{{ $printdataspk[0]->WorkGroup }}</b></td>

            <td>NO. Kotak</td>
            <td>:</td>
            <td><b>{{ $printdataspk[0]->BoxNo }}</b></td>

            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>

            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{ $printdataspk[0]->Remarks }}</td>
        </tr>

        <tr>
            <td> </td>
        </tr>
    </table>

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
            style="border-bottom: 1px solid black; font-size : 12px; ">
            <tr>
                <th>No</th>
                <th>SPK PPIC</th>
                <th>Item Produk</th>
                <th>Inject</th>
                <th>Qty</th>
                <th>Tok</th>
                <th>SC</th>
                <!-- <th>Tatakan</th> -->
                <th>Keterangan Batu</th>
                <th>Rekap Lilin (RL)</th>
                <th>Urutan RL</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody" style="border-bottom: 1px solid black; font-size : 12px; ">
            <?php $sum_totalInjectspk = 0 ?>
            <?php $sum_totalQtyspk = 0 ?>

            @foreach ($tabelinjectlilin as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $item->nospk }}</span>
                </td>
                <td>{{ $item->product }}</td>
                <td>{{ $item->Inject }}</td>
                <td>{{ $item->Qty1 }}</td>
                <td>{{ $item->Tok }}</td>
                <td>{{ $item->StoneCast }}</td>
                <!-- <td>{{ $item->Tatakan }}</td> -->
                <td>{{ $item->StoneNote }}</td>
                <td>{{ $item->WaxOrder }}</td>
                <td>{{ $item->WaxOrderOrd }}</td>
                <?php $sum_totalInjectspk += $item->Inject ?>
                <?php $sum_totalQtyspk += $item->Qty ?>

            </tr>
            @endforeach
        </tbody>
        <tr>
            <td colspan="3">Total</td>

            <td><b>{{ $sum_totalInjectspk }}</b></td>
            <td>{{ $jumlahqtydaninject[0]->TotalQty}}</td>
        </tr>
        <table>
            <br>
            @foreach ($printdataspk as $pda)
            <tr>

                <td style="padding-left: 50px"> Diberikan Oleh </td>
                <td style="padding-left: 130px"> Diterimah Oleh</td>
                <td style="padding-left: 20px; height: 0px; padding-top: 0px;">
                    Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $printdataspk[0]->UserName }}
                </td>
            </tr>
            @endforeach
            <tr>

                <td style="padding-left: 60px"><br><br>( ............... )</td>
                <td style="padding-left: 140px; vertical-align: text-top;"><br><br>( .............. )</td>
                <td style=" padding-left: 60px;"> </td>
            </tr>
        </table>
        <table>
            <thead>
                <tr>
                    <br>
                    <label for="">Gambar Product Jadi :</label>
                    @foreach ($tabelfoto as $item)
                    <th id="gambar" class="text-wrap">
                        <h5 class="card-title text-wrap" style=" word-wrap: break-word;"> {{ $item->product }}</h5>
                        <p> <img src="{{ $item->foto }}" width="90px" hight="90px" style="max-height: 95px !important;">
                        </p>
                        <p class="card-text">{{$item->nospk}}</p>
                    </th>
                    @endforeach
                </tr>
            </thead>
        </table>

    </table>
    <!-- =========================================================================================== tabel spk kebutuhan karet ==============================================================================================-->
    <br>
    <div id="karet">
        <table class="tabel0" style="background-color: #F0FFF0;">
            <tr>
                <td colspan="6" style="font-size:20px; padding-bottom: 10px; padding-right: 0px; "><b>
                        Kebutuhan karet </b>
                </td>
                <td style="text-align: right;"><b>{{ $printdataspk[0]->kadar }}</b></td>
                <td>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: {{ $printdataspk[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                        &nbsp;
                    </div>
                </td>

            </tr>
            <tr>
                <td width="200"></td>
                <td width="2"></td>
                <td width="40"></td>
                <td width="110"></td>
                <td width="2"></td>
                <td width="150"></td>
                <td width="100"></td>
                <td width="2"></td>
                <td width="130"></td>
            </tr>


        </table>
        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
                style="border-bottom: 1px solid black; font-size : 12px; ">
                <tr>
                    <th>No</th>
                    <th>SPK PPIC</th>
                    <th>Item Produk</th>
                    <th>Inject</th>
                    <th>ID Karet</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <?php $sum_totalInjectkaret = 0 ?>
            @foreach ($tabelinjectkbkaret as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td>{{ $item->nospk }}</td>
                <td>{{ $item->Product }}</td>
                <td>{{ $item->Qty }}</td>
                <td>{{ $item->Rubber }}</td>
                <td>{{ $item->lokasi }}</td>
                <?php $sum_totalInjectkaret += $item->Qty ?>
            </tr>
            @endforeach
            </tbody>
            <tr>
                <!-- <td colspan="3">Total</td> -->

                <!-- <td><b>{{$sum_totalInjectkaret}}</b></td> -->
            </tr>
            <table>
                <br>
                @foreach ($printdataspk as $pda)
                <tr>

                    <td style="padding-left: 50px"> Diberikan Oleh</td>
                    <td style="padding-left: 130px"> Diterimah Oleh</td>
                    <td style="padding-left: 20px">
                        Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $printdataspk[0]->UserName }}
                    </td>
                </tr>
                @endforeach
                <tr>

                    <td style="padding-left: 60px"><br><br>( ............... )</td>
                    <td style="padding-left: 140px"><br><br>( .............. )</td>
                    <td style="padding-left: 60px;"> </td>

                </tr>
            </table>
            <table>

            </table>
        </table>
    </div>

    <!-- =========================================================================================== tabel spk kebutuhan batu ==============================================================================================-->
    <!-- IF kebutuhan batu ksong ngga usah di print -->
    <div id="batu">
        <div id="tabel3" style=" padding-bottom: 10px; "></div>
        <table class="tabel0" style="background-color: #F0FFF0;">
            <tr>
                <td colspan="6" style="font-size:20px; padding-bottom: 10px; "><b>Kebutuhan batu </b>
                </td>
                <td colspan="2" style="text-align: right;"><b>{{ $printdataspk[0]->kadar }}</b></td>
                <td>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: {{ $printdataspk[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                        &nbsp;
                    </div>
                </td>
                <!-- <td colspan="4"></td> -->
                <td rowspan="5" style="height: 50px; text-align: center; padding-top: 10px;">
                    <div style="display: flex; justify-content: center; text-align: center;" id="qrcode2"></div>
                    <br><span style="text-align: center; vertical-align: text-top;">{{ $printdataspk[0]->ID }} </span>

                </td>
            </tr>
            <tr>
                <td width="100">ID SPK</td>
                <td width="2">:</td>
                <td width="140"><b>{{ $printdataspk[0]->ID }}</b>
                    <input type="hidden" id="IDSPK" value=" {{  $printdataspk[0]->ID }}">
                </td>


                <td width="110">Piringan</td>
                <td width="2">:</td>
                <td width="150"><b>{{ $printdataspk[0]->pkaret }}</b> - [{{ $printdataspk[0]->RubberPlate }}]</td>

                <td width="100">Stick Pohon</td>
                <td width="2">:</td>
                <td width="130">{{ $printdataspk[0]->stickpohon}}
                    <input type="hidden" id="color" value="{{$printdataspk[0]->HexColor }}">
                </td>

            </tr>

            <tr>
                <td>Operator</td>
                <td>:</td>
                <td> <b>{{ $printdataspk[0]->emp }}</b> - [{{ $printdataspk[0]->IDK }}]</td>

                <td>RPH</td>
                <td>:</td>
                <td>{{ $tabelinjectlilin[0]->WorkScheduleID }}</td>

                <td>Tanggal</td>
                <td>:</td>
                <td><?php echo tgl_indo($printdataspk[0]->TransDate); ?></td>
            </tr>
            <tr>
                <td>Kelompok</td>
                <td>:</td>
                <td><b>{{ $printdataspk[0]->WorkGroup }}</b></td>

                <td>NO. Kotak</td>
                <td>:</td>
                <td><b>{{ $printdataspk[0]->BoxNo }}</b></td>

                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Catatan</td>
                <td>:</td>
                <td colspan="4">{{ $printdataspk[0]->Remarks }}</td>
            </tr>

        </table>


        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
                style="border-bottom: 1px solid black; font-size : 12px; ">
                <tr>
                    <th>No</th>
                    <th>SPK PPIC</th>
                    <th width="20%">Product Jadi</th>
                    <th>Inject</th>
                    <th>Jenis Batu</th>
                    <th>Pesan</th>
                    <th>@</th>
                    <th>Total</th>
                    <th>Keterangan Batu</th>
                </tr>
            </thead>
            <tbody class="center" id="tbody" style="border-bottom: 1px solid black; font-size : 12px; ">

                <?php $sum_totalInjectbatu = 0 ?>
                @foreach ($tabelinjectkbbatu as $item)
                <tr id="{{ $item->IDM }}">
                    <td>{{ $loop->iteration }} </td>
                    <td>{{ $item->WorkOrder }}</td>
                    <td>{{ $item->Product }}</td>

                    <td>{{ $item->Inject }}</td>
                    <td>{!! $item->Stone !!}</td>
                    <td>{!! $item->Ordered !!}</td>
                    <td>{!! $item->EachQty !!}</td>
                    <td>{!! $item->Total !!}</td>
                    <td>{!! $item->StoneNote !!}</td>
                    <?php $sum_totalInjectbatu += $item->Inject ?>

                </tr>
                @endforeach
            </tbody>
            <tr>
                <td colspan="3">Total</td>
                <td><b>{{ $sum_totalInjectbatu}}</b></td>
                <td hidden> <input type="text" id="checkbatu" value="{{ $sum_totalInjectbatu}}"></td>
            </tr>
            <table>
                <br>
                @foreach ($printdataspk as $pda)
                <tr>

                    <td style="padding-left: 50px"> Diberikan Oleh</td>
                    <td style="padding-left: 130px"> Diterimah Oleh</td>
                    <td style="padding-left: 20px">
                        Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $printdataspk[0]->UserName }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding-left: 60px"><br><br>( ............... )</td>
                    <td style="padding-left: 140px"><br><br>( .............. )</td>
                    <td style="padding-left: 60px;"> </td>
                </tr>
            </table>
            <table>
                <thead>
                    <tr>
                        <br>
                        <label for="">Gambar Product Jadi :</label>
                        @foreach ($tabelfoto as $item)
                        <th id="gambar" class="text-wrap">
                            <h5 class="card-title text-wrap" style=" word-wrap: break-word;"> {{ $item->product }}</h5>
                            <p> <img src="{{ $item->foto }}" width="90px" hight="90px"
                                    style="max-height: 95px !important;">
                            <p class="card-text">{{$item->nospk}}</p>
                        </th>
                        @endforeach
                    </tr>
                </thead>
            </table>
            <table>
                <thead>
                    <tr>
                        <label for="">Komponen produk :</label>
                        @foreach ($fotokomponen as $item)
                        <th id="gambar2" class="text-wrap">
                            <h5> {{ $item->KomponenProuct }}</h5>
                        </th>
                        @endforeach
                    </tr>
                </thead>
            </table>
        </table>
        <!-- =========================================================================================== tabel spk total batu ==============================================================================================-->

        <br>
        <table class="tabel0" style="background-color: #F0FFF0;">
            <tr>
                <td colspan="6" style="font-size:20px; padding-bottom: 10px; padding-right: 0px;"><b>
                        Total Kebutuhan Batu
                    </b>
                </td>
                <td style="text-align: right;"><b>{{ $printdataspk[0]->kadar }}</b></td>
                <td>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: {{ $printdataspk[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                        &nbsp;
                    </div>
                    <input type="hidden" id="color" value="{{ $printdataspk[0]->HexColor }}">
                </td>

            </tr>
            <tr>
                <td width="200"></td>
                <td width="2"></td>
                <td width="40"></td>
                <td width="110"></td>
                <td width="2"></td>
                <td width="150"></td>
                <td width="100"></td>
                <td width="2"></td>
                <td width="130"></td>
            </tr>

        </table>

        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4 center" id="thead"
                style="border-bottom: 1px solid black; font-size : 12px; ">
                <tr>
                    <th width="10%">No</th>
                    <th width="10%">Jenis Batu</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody class=" center" id="tbody" style="border-bottom: 1px solid black; font-size : 12px; ">
                <?php $sum_Total = 0 ?>
                @foreach ($tabelinjecttkbbatu as $item)
                <tr id="{{ $item->Stone }}">
                    <td>{{ $loop->iteration }} </td>
                    <td>{{ $item->Stone }}</td>
                    <td>{{ $item->Total }}</td>
                    <td style="border: 1px solid white"></td>
                    <?php $sum_Total += $item->Total ?>
                </tr>
                @endforeach
            </tbody>
            <tr>
                <td colspan="2">Total</td>
                <td><b>{{ $sum_Total}}</b></td>
            </tr>
            <table>
                <br>
                @foreach ($printdataspk as $pda)
                <tr>

                    <td style="padding-left: 50px"> Diberikan Oleh</td>
                    <td style="padding-left: 130px"> Diterimah Oleh</td>
                    <td style="padding-left: 20px">
                        Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $printdataspk[0]->UserName }}
                    </td>
                </tr>
                @endforeach
                <tr>

                    <td style="padding-left: 60px"><br><br>( ............... )</td>
                    <td style="padding-left: 140px"><br><br>( .............. )</td>
                    <td style="padding-left: 60px;"> </td>

                </tr>
            </table>

        </table>
    </div>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>


<script>
batu = document.getElementById('checkbatu').value;
console.log(batu);
if (batu == 0) {
    console.log('kosong');
    document.getElementById("batu").style.display = "none";
    document.getElementById("tandabatu").style.display = "";
} else {
    console.log('ada');
};

idspkinject = document.getElementById('IDSPK').value;
var qrcode = new QRCode("qrcode", {
    text: idspkinject,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

var qrcode2 = new QRCode("qrcode2", {
    text: idspkinject,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

window.onload = function() {
    window.print();
}
</script>
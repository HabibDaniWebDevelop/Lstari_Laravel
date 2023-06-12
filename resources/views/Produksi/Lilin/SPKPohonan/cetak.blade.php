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
        width: 137px;
        height: 190px;
        margin: 2px;
        border: 1px solid black;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 7mm;
        }

        b {
            font-weight: bold;
            font-size: 13px;
        }

        #gambar {
            display: inline-block;
            width: 137px;
            height: 190px;
            margin: 2px;
            border: 1px solid black;
        }
    }

    b {
        font-weight: bold;
        font-size: 13px;
    }

    #colom {
        text-align: center;
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
            <td colspan="6" style="font-size:20px; padding-bottom: 10px; "><b>SPKO Pohon (Direct Casting)</b><span
                    style="display: none" id="tandabatu">&nbsp;<b>TANPA
                        BATU</b></span>
            </td>
            <td colspan="2" style="text-align: right;"><b>{{  $datacetak[0]->kadar }}</b></td>
            <td>
                <div id="colorcadar" class="colorcadar"
                    style="background-color: {{ $datacetak[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                    &nbsp;
                </div>
            </td>
            <!-- <td colspan="4"></td> -->
            <td rowspan="5" style="height: 50px; text-align: center; padding-top: 10px;">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <br><span style="text-align: center; vertical-align: text-top;">{{ $datacetak[0]->ID }} </span>

            </td>
        </tr>
        <tr>
            <td width="100">ID SPK</td>
            <td width="2">:</td>
            <td width="140"><b>{{ $datacetak[0]->ID }}</b>
                <input type="hidden" id="IDSPK" value=" {{  $datacetak[0]->ID }}">
            </td>


            <td width="110">Piringan</td>
            <td width="2">:</td>
            <td width="150"><b>{{ $datacetak[0]->pkaret }}</b> - [{{ $datacetak[0]->RubberPlate }}]</td>

            <td width="100">Stick Pohon</td>
            <td width="2">:</td>
            <td width="130">{{ $datacetak[0]->stickpohon}}
                <input type="hidden" id="color" value="{{$datacetak[0]->HexColor }}">
            </td>

        </tr>

        <tr>
            <td>Operator</td>
            <td>:</td>
            <td> <b>{{ $datacetak[0]->emp }}</b> - [{{ $datacetak[0]->IDK }}]</td>

            <td>TM</td>
            <td>:</td>
            <td>{{$datatabelcetak[0]->ID}}</td>

            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($datacetak[0]->TransDate); ?></td>
        </tr>
        <tr>
            <td>Kelompok</td>
            <td>:</td>
            <td><b>{{ $datacetak[0]->WorkGroup }}</b></td>

            <td>NO. Kotak</td>
            <td>:</td>
            <td><b>{{ $datacetak[0]->BoxNo }}</b></td>

            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{ $datacetak[0]->Remarks }}</td>
        </tr>


        <tr>
            <td> </td>
        </tr>
    </table>
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2" style="center">
            <tr style="text-align:center;">
                <th>NO.</th>
                <th>SPK.PPIC</th>
                <th>Produk Jadi</th>
                <th>SKU</th>
                <th>Descripsi</th>
                <th>SC</th>
                <th>Kadar</th>
                <th>Qty</th>
                <!-- <th>WaxOrder</th>
                <th>WaxOrderOrd</th>
                <th>WorkOrder</th>
                <th>WorkOrderOrd</th> -->
            </tr>
        </thead>
        <tfoot>
            <hr>

        </tfoot>
        {{-- {{ dd($DaftarProduct); }} --}}
        <tbody>
            @foreach ($datatabelcetak as $wax )
            <tr id="colom">
                <td id="colom">{{ $loop->iteration }}</td>
                <td id="colom"><span style="font-size: 14px" class="badge bg-dark">{{ $wax->swworkorder }}</span><br>
                </td>
                <td id="colom">{{ $wax->ProdukJadi}}</td>
                <td id="colom">{{ $wax->SKU}}</td>
                <td id="colom">{{ $wax->Description }}</td>
                <td id="colom">{{ $wax->StoneCast}}</td>
                <td id="colom">{{ $wax->Kadar }}</td>
                <td id="colom">{{ $wax->Qty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table>
        <br>

        <tr>

            <td style="padding-left: 50px"> Diberikan Oleh</td>
            <td style="padding-left: 130px"> Diterimah Oleh</td>
            <td style="padding-left: 20px">
                Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $datacetak[0]->UserName }}
            </td>
        </tr>

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
                    <p> <img src="{{ $item->foto }}" width="90px" hight="100px" style="max-height: 95px !important;">
                    <p class="card-text">{{$item->nospk}}</p>
                </th>
                @endforeach
            </tr>
        </thead>
    </table>

    <!-- =========================================================================================== tabel spk kebutuhan batu ==============================================================================================-->
    <!-- IF kebutuhan batu ksong ngga usah di print -->
    <div id="batu">
        <div id="tabel3" style=" padding-bottom: 10px; "></div>
        <table class="tabel0" style="background-color: #F0FFF0;">
            <tr>
                <td colspan="6" style="font-size:20px; padding-bottom: 10px; "><b>Kebutuhan batu </b>
                </td>
                <td colspan="2" style="text-align: right;"><b>{{ $datacetak[0]->kadar }}</b></td>
                <td>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: {{ $datacetak[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                        &nbsp;
                    </div>
                </td>
                <!-- <td colspan="4"></td> -->
                <td rowspan="5" style="height: 50px; text-align: center; padding-top: 10px;">
                    <div style="display: flex; justify-content: center; text-align: center;" id="qrcode2"></div>
                    <br><span style="text-align: center; vertical-align: text-top;">{{ $datacetak[0]->ID }} </span>

                </td>
            </tr>
            <tr>
                <td width="100">ID SPK</td>
                <td width="2">:</td>
                <td width="140"><b>{{ $datacetak[0]->ID }}</b>
                    <input type="hidden" id="IDSPK" value=" {{  $datacetak[0]->ID }}">
                </td>


                <td width="110">Piringan</td>
                <td width="2">:</td>
                <td width="150"><b>{{ $datacetak[0]->pkaret }}</b> - [{{ $datacetak[0]->RubberPlate }}]</td>

                <td width="100">Stick Pohon</td>
                <td width="2">:</td>
                <td width="130">{{ $datacetak[0]->stickpohon}}
                    <input type="hidden" id="color" value="{{$datacetak[0]->HexColor }}">
                </td>

            </tr>

            <tr>
                <td>Operator</td>
                <td>:</td>
                <td> <b>{{ $datacetak[0]->emp }}</b> - [{{ $datacetak[0]->IDK }}]</td>

                <td>TM</td>
                <td>:</td>
                <td>{{$datatabelcetak[0]->ID}}</td>

                <td>Tanggal</td>
                <td>:</td>
                <td><?php echo tgl_indo($datacetak[0]->TransDate); ?></td>
            </tr>
            <tr>
                <td>Kelompok</td>
                <td>:</td>
                <td><b>{{ $datacetak[0]->WorkGroup }}</b></td>

                <td>NO. Kotak</td>
                <td>:</td>
                <td><b>{{ $datacetak[0]->BoxNo }}</b></td>

                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Catatan</td>
                <td>:</td>
                <td colspan="4">{{ $datacetak[0]->Remarks }}</td>
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

                <tr>

                    <td style="padding-left: 50px"> Diberikan Oleh</td>
                    <td style="padding-left: 130px"> Diterimah Oleh</td>
                    <td style="padding-left: 20px">
                        Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $datacetak[0]->UserName }}
                    </td>
                </tr>

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
                <td style="text-align: right;"><b>{{ $datacetak[0]->kadar }}</b></td>
                <td>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: {{ $datacetak[0]->HexColor }}; height:20px; width: 70px; border: 1px solid;">
                        &nbsp;
                    </div>
                    <input type="hidden" id="color" value="{{ $datacetak[0]->HexColor }}">
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

                <tr>

                    <td style="padding-left: 50px"> Diberikan Oleh</td>
                    <td style="padding-left: 130px"> Diterimah Oleh</td>
                    <td style="padding-left: 20px">
                        Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $datacetak[0]->UserName }}
                    </td>
                </tr>

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
idspkpohon = document.getElementById('IDSPK').value;
console.log(batu);
if (batu == 0) {
    console.log('kosong');
    document.getElementById("batu").style.display = "none";
    document.getElementById("tandabatu").style.display = "";
} else {
    console.log('ada');
};

var qrcode2 = new QRCode("qrcode2", {
    text: idspkpohon,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

var qrcode = new QRCode("qrcode", {
    text: idspkpohon,
    width: 60,
    height: 60,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// muculcolor = $('#color').val();

window.onload = function() {
    window.print();
}
</script>
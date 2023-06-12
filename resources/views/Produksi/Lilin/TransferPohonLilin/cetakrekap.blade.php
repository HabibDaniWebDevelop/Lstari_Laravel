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
    <title>Cetak GIPS</title>

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

        #tbody1 {
            border-color: #090cd9 !important;
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
            <td colspan="3" style="font-size:20px; padding-bottom: 10px; "><b>SPKO GIPS - GIPS</b>
            </td>

            <td colspan="4"></td>
            <td rowspan="5" style="height: 50px; text-align: center; padding-top: 10px;">
                <div style="display: flex; justify-content: center; text-align: center;" id="qrcode"></div>
                <br><span style="text-align: center; vertical-align: text-top;">{{ $Gips_Headercetak[0]->ID }} </span>
            </td>
        </tr>
        <tr>
            <td width="100">ID SPKO GIPS</td>
            <td width="2">:</td>
            <td width="140"><b>{{ $Gips_Headercetak[0]->ID }}</b> <input type="hidden" id="IDSPK"
                    value="{{ $Gips_Headercetak[0]->ID }}"></td>

            <td>Tanggal</td>
            <td>:</td>
            <td><?php echo tgl_indo($Gips_Headercetak[0]->TransDate); ?></td>

        </tr>
        <tr>
            <td>Admin Pers</td>
            <td>:</td>
            <td>{{ $Gips_Headercetak[0]->UserName }}</td>

        </tr>
        <tr>

            <td>Catatan</td>
            <td>:</td>
            <td colspan="4">{{ $Gips_Headercetak[0]->Remarks }}</td>
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
                <th>NO Pohon</th>
                <th>ID Pohon</th>
                <th>Brt Lilin</th>
                <th>Brt Batu</th>
                <th>SPK PPIC</th>
            </tr>
        </thead>
        <tbody class="center" id="tbody1" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak06ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon1" colspan="6">Total Pohon 06K-300 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P1" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody11" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak06kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon1" colspan="6">Total Pohon 06K-300 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P11" value="{{ $sum_Total}}">
                </td>
            </tr>

        </tbody>
        <tbody id="tbody1111">
            <tr>
                <td id="totalpohon111" colspan="6">Total Pohon 06K-300
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak06k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #090cd9; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <input id="totalpohon1111" type="hidden" value="{{$Gips_tabelcetak06k[0]->jumlah}}">
                </td>
            </tr>
        </tbody>

        <tbody class="center" id="tbody2" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak08ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon2" colspan="6">Total Pohon 08K-375 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P2" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody22" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak08kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon2" colspan="6">Total Pohon 08K-375 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P22" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon222" colspan="6">Total Pohon 08K-375
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak08k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #02ba1e; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>


        <tbody class="center" id="tbody3" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak16ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon3" colspan="6">Total Pohon 16K-700 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P3" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody33" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak16kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon3" colspan="6">Total Pohon 16K-700 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P33" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon333" colspan="6">Total Pohon 16K-700
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak16k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #ff1a1a; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>


        <tbody class="center" id="tbody4" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak17ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon4" colspan="6">Total Pohon 17K-750 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P4" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody44" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak17kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon4" colspan="6">Total Pohon 17K-750 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P44" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon444" colspan="6">Total Pohon 17K-750
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak17k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #e65507; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>


        <tbody class="center" id="tbody5" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak17kpsedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon5" colspan="6">Total Pohon 17KP-750P sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P5" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody55" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak17kpbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon5" colspan="6">Total Pohon 17KP-750P besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P55" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon555" colspan="6">Total Pohon 17KP-750P
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak17kp[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #d909cb; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>


        <tbody class="center" id="tbody6" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak20ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon6" colspan="6">Total Pohon 20K-875 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P6" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody66" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak20kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon6" colspan="6">Total Pohon 20K-875 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P66" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon666" colspan="6">Total Pohon 20K-875
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak20k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #ffcba4; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>



        <tbody class="center" id="tbody7" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak10ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon7" colspan="6">Total Pohon 10K-450 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P7" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody77" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak10kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon7" colspan="6">Total Pohon 10K-450 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P77" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon777" colspan="6">Total Pohon 10K-450
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak10k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #f5fc0f; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>


        <tbody class="center" id="tbody8" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak08kpsedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon8" colspan="6">Total Pohon 08KP-375P sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P8" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody88" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak08kpbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon8" colspan="6">Total Pohon 08KP-375P besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P88" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon888" colspan="6">Total Pohon 08KP-375P
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak08kp[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #ebb52d; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>


        <tbody class="center" id="tbody9" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak19ksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon9" colspan="6">Total Pohon 19K-830 sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P9" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody99" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetak19kbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon9" colspan="6">Total Pohon 19K-830 besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P99" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon999" colspan="6">Total Pohon 19K-830
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetak19k[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #4908a3; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>

        <tbody class="center" id="tbody10" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetakperaksedang as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon10" colspan="6">Total Pohon Perak sedang
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P10" value="{{ $sum_Total}}">
                </td>
            </tr>
        </tbody>
        <tbody class="center" id="tbody1010" style="border: 1px; border-color: #090cd9 !important; font-size : 12px; ">
            <?php $sum_Total = 0 ?>
            @foreach ($Gips_tabelcetakperakbesar as $item)
            <tr id="{{ $item->IDM }}">
                <td>{{ $loop->iteration }} </td>
                <td> <span class="badge bg-dark" style="font-size:12px;">{{ $item->Plate }}</span></td>
                <td>{{ $item->WaxTree }}</td>
                <td>{{ $item->Weight }}</td>
                <td>{{ $item->WeightStone }}</td>
                <td>{{ $item->WorkOrder }}</td>
                <?php $sum_Total += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td id="totalpohon10" colspan="6">Total Pohon Perak besar
                    &nbsp;&nbsp;: <b>{{ $sum_Total}}</b>
                    <input type="hidden" id="P1010" value="{{ $sum_Total}}">
                </td>
            </tr>
            <tr>
                <td id="totalpohon101010" colspan="6">Total Pohon Perak
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetakperak[0]->jumlah}}</b>
                    <div id="colorcadar" class="colorcadar"
                        style="background-color: #000; height:20px; width: 70px; border: 1px solid; display: inline;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td id="totalpohon1010101010" colspan="6">Total seluruh Pohon
                    &nbsp;&nbsp;:<b> {{$Gips_tabelcetakallkadar[0]->jumlah}}</b>
                </td>
            </tr>
        </tbody>
        <table>
            <br>
            <tr>

                <td style="padding-left: 50px"> Diberikan Oleh </td>
                <td style="padding-left: 130px"> Diterimah Oleh</td>
                <td style="padding-left: 20px; height: 0px; padding-top: 0px;">
                    Dicetak:&nbsp;&nbsp;{{$datenow}}&nbsp;&nbsp;{{$timenow}}&nbsp;&nbsp;{{ $Gips_Headercetak[0]->UserName }}
                </td>
            </tr>
            <tr>

                <td style="padding-left: 60px"><br><br>( ............... )</td>
                <td style="padding-left: 140px; vertical-align: text-top;"><br><br>( .............. )</td>
                <td style=" padding-left: 60px;"> </td>
            </tr>
        </table>

    </table>

</body>

</html>

<script src="{!! asset('assets/sneatV1/assets/vendor/libs/qrcodejs/qrcode.min.js') !!}"></script>


<script>
var kadar1 = document.getElementById('P1').value;
var kadar2 = document.getElementById('P2').value;
var kadar3 = document.getElementById('P3').value;
var kadar4 = document.getElementById('P4').value;
var kadar5 = document.getElementById('P5').value;
var kadar6 = document.getElementById('P6').value;
var kadar7 = document.getElementById('P7').value;
var kadar8 = document.getElementById('P8').value;
var kadar9 = document.getElementById('P9').value;
var kadar11 = document.getElementById('P11').value;
var kadar22 = document.getElementById('P22').value;
var kadar33 = document.getElementById('P33').value;
var kadar44 = document.getElementById('P44').value;
var kadar55 = document.getElementById('P55').value;
var kadar66 = document.getElementById('P66').value;
var kadar77 = document.getElementById('P77').value;
var kadar88 = document.getElementById('P88').value;
var kadar99 = document.getElementById('P99').value;
var kadar10 = document.getElementById('P10').value;
var kadar1010 = document.getElementById('P1010').value;

var kadar111 = document.getElementById('totalpohon1111').value;

if (kadar111 == 0) {
    console.log('kosong');
    document.getElementById("tbody1111").style.display = "none";
} else {
    console.log('ada');
};
// var kadar9 = ('#totalpohon8').val();
// console.log(kadar9);

if (kadar10 == 0) {
    console.log('kosong');
    document.getElementById("tbody10").style.display = "none";
} else {
    console.log('ada');
};
if (kadar1010 == 0) {
    console.log('kosong');
    document.getElementById("tbody1010").style.display = "none";
} else {
    console.log('ada');
};
if (kadar9 == 0) {
    console.log('kosong');
    document.getElementById("tbody9").style.display = "none";
} else {
    console.log('ada');
};
//--------------------------------------------------------
if (kadar8 == 0) {
    console.log('kosong');
    document.getElementById("tbody8").style.display = "none";
} else {
    console.log('ada');
};
// --------------------------------------------------------
if (kadar7 == 0) {
    console.log('kosong');
    document.getElementById("tbody7").style.display = "none";
} else {
    console.log('ada');
};
//----------------------------------------------------------
if (kadar6 == 0) {
    console.log('kosong');
    document.getElementById("tbody6").style.display = "none";
} else {
    console.log('ada');
};
//------------------------------------------------------
if (kadar5 == 0) {
    console.log('kosong');
    document.getElementById("tbody5").style.display = "none";
} else {
    console.log('ada');
};
//----------------------------------------------------
if (kadar4 == 0) {
    console.log('kosong');
    document.getElementById("tbody4").style.display = "none";
} else {
    console.log('ada');
};
//--------------------------------------------------------
if (kadar3 == 0) {
    console.log('kosong');
    document.getElementById("tbody3").style.display = "none";
} else {
    console.log('ada');
};
//-----------------------------------------------------------
if (kadar2 == 0) {
    console.log('kosong');
    document.getElementById("tbody2").style.display = "none";
} else {
    console.log('ada');
};
//------------------------------------------------------------
if (kadar1 == 0) {
    console.log('kosong');
    document.getElementById("tbody1").style.display = "none";
} else {
    console.log('ada');
};
if (kadar99 == 0) {
    console.log('kosong');
    document.getElementById("tbody99").style.display = "none";
} else {
    console.log('ada');
};
//--------------------------------------------------------
if (kadar88 == 0) {
    console.log('kosong');
    document.getElementById("tbody88").style.display = "none";
} else {
    console.log('ada');
};
// --------------------------------------------------------
if (kadar77 == 0) {
    console.log('kosong');
    document.getElementById("tbody77").style.display = "none";
} else {
    console.log('ada');
};
//----------------------------------------------------------
if (kadar66 == 0) {
    console.log('kosong');
    document.getElementById("tbody66").style.display = "none";
} else {
    console.log('ada');
};
//------------------------------------------------------
if (kadar55 == 0) {
    console.log('kosong');
    document.getElementById("tbody55").style.display = "none";
} else {
    console.log('ada');
};
//----------------------------------------------------
if (kadar44 == 0) {
    console.log('kosong');
    document.getElementById("tbody44").style.display = "none";
} else {
    console.log('ada');
};
//--------------------------------------------------------
if (kadar33 == 0) {
    console.log('kosong');
    document.getElementById("tbody33").style.display = "none";
} else {
    console.log('ada');
};
//-----------------------------------------------------------
if (kadar22 == 0) {
    console.log('kosong');
    document.getElementById("tbody22").style.display = "none";
} else {
    console.log('ada');
};
//------------------------------------------------------------
if (kadar11 == 0) {
    console.log('kosong');
    document.getElementById("tbody11").style.display = "none";
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

window.onload = function() {
    // var kadar9 = ('#totalpohon8').val();
    window.print();
}
</script>
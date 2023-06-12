<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <table width="100%" border="0" style="page-break-after:always">
            <tbody>
                <tr>
                    <td>
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td align="center" width="100%" style="font-size: 18px; font-weight: bold; color: black">
                                        <u>Surat Pengantar Pengambilan Barang</u>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table width="100%" border="0">
                            <tbody>
                                <tr>
                                    <td width="17%">No. Referensi</td>
                                    <td width="1%">:</td>
                                    <td width="40%">{{$sw}}</td>
                                    <td width="10%">Kepada Yth</td>
                                    <td width="1%">:</td>
                                    <td width="33%">{{$recipient}}</td>
                                </tr>
                                <tr>
                                    <td width="17%">Tanggal</td>
                                    <td width="1%">:</td>
                                    <td width="40%">{{$tanggal}}</td>
                                    <td width="10%">Alamat</td>
                                    <td width="1%">:</td>
                                    <td width="33%">{{$alamat}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td align="left" width="100%">Dengan hormat, bersama ini mohon diberikan barang :</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="tabelbody">
                            <table width="100%" id="bodytable" border="0">
                                <thead>
                                    <tr>
                                        <th align="left" width="10%" style="border-bottom: 1px solid black;   font-weight: bold; color: black; padding-right:20px;">No</th>
                                        <th align="left" width="13%" style="border-bottom: 1px solid black;   font-weight: bold; color: black; padding-right:20px;">Jumlah</th>
                                        <th align="left" width="60%" style="border-bottom: 1px solid black;   font-weight: bold; color: black">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suratpengantaritem as $item)
                                    <tr>
                                        <td align="left" width="10%" style="border-bottom: 1px dashed black;  font-weight: normal; color: black; padding-right:20px; vertical-align: top;">{{$loop->iteration}}</td>
                                        <td align="left" width="13%" style="border-bottom: 1px dashed black;  font-weight: normal; color: black; padding-right:20px; vertical-align: top;">{{$item->Qty}} {{$item->Satuan}}</td>
                                        <td align="left" width="60%" style="border-bottom: 1px dashed black;  font-weight: normal; color: black; padding-right:10px; vertical-align: top;">{{$item->Item}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <table width="100%" border="0">
                                <tbody>
                                    <tr>
                                        <td width="16.5%">Kepada Sdr</td>
                                        <td width="1%">:</td>
                                        <td width="40%">{{$toemployee}}</td>
                                        <td width="25%"></td>
                                        <td width="2%"></td>
                                        <td width="28%"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table>
                                <tbody>
                                    <tr>
                                        <td align="left" width="100%">Terima kasih atas perhatian dan kerjasamanya.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td align="center" width="20%">
                                        <u>Menyerahkan</u>
                                    </td>
                                    <td align="center" width="20%">
                                        <u>Mengetahui</u>
                                    </td>
                                    <td align="center" width="20%">
                                        <u>Hormat Kami</u>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="idheight"></td>
                                </tr>
                                <tr>
                                    <td align="center">(.........................)</td>
                                    <td align="center">(.........................)</td>
                                    <td align="center">(.........................)</td>
                                </tr>
                            </tbody>
                        </table>
                        <script>
                            window.onload = function() {
                                window.print();
                                // setTimeout(window.close, 0);
                            }
                        </script>
                        <style type="text/css" media="print">
                            @media print {

                                html,
                                body {
                                    height: 140mm;
                                    font-family: Arial, Helvetica, sans-serif;
                                }
                            }

                            #idheight {
                                height: 1.3cm;
                            }

                            td {
                                font-size: 14px;
                            }

                            th {
                                font-size: 14px;
                            }

                            #tabelbody {
                                height: 280px;
                            }
                        </style>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
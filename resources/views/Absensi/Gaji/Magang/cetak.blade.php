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
            @media print { 
                table,
                table tr td,
                table tr th {
                    page-break-inside: avoid;
                }
            }
            #Header,
            #Footer {
                display: none !important;
            }
        </style>

    </head>
    <body>
        @foreach ($datapayroll as $data)
        <table width="100%" border="1" style="float: left; margin-left: 20px; margin-top: 10px">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td align="center" width="100%">
                                <b>Apresiasi Absensi Magang Sekolah <br>PT.Lestari Mulia Sentosa </b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="20%">Periode</td>
                            <td width="2%">:</td>
                            <td width="40%">{{$data['periode']}}</td>
                        </tr>
                        <tr>
                            <td width="20%">Nama</td>
                            <td width="2%">:</td>
                            <td width="40%">{{$data['NamaKaryawan']}}</td>
                        </tr>
                        <tr>
                            <td width="20%">Bagian</td>
                            <td width="2%">:</td>
                            <td width="40%">{{$data['Department']}}</td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="20%">Apresiasi</td>
                            <td width="1%">:</td>
                            <td width="20%"></td>
                            <td width="20%" style="text-align:right; padding-right:10px;" class="sallary, currency">{{$data['Sallary']}}</td>
                        </tr>
                        <tr>
                            <td width="20%">Kehadiran</td>
                            <td width="1%">:</td>
                            <td width="20%">{{$data['Kehadiran']}} <a> Hari</a>
                            </td>
                            <td width="20%"></td>
                        </tr>
                        @foreach ($data['absentDetail'] as $item)    
                            <tr>
                                <td width="20%">{{$item->AbsentType}}</td>
                                <td width="1%">:</td>
                                <td width="20%">{{$item->JumlahAbsent}}</td>
                                <td width="20%"></td>
                            </tr>
                        @endforeach
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="50%"></td>
                            <td width="46%">
                                <hr size="2px" style="color:black;">
                            </td>
                            <td width="2%"></td>
                            <td width="2%">
                                <a></a>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="20%">Total Apresiasi</td>
                            <td width="1%">:</td>
                            <td width="20%"></td>
                            <td width="20%" style="text-align:right; padding-right:20px;" class="totalSallary currency">{{$data['TotalSallary']}}</td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%">
                        <tr>
                            <td width="30%"></td>
                            <td width="20%"></td>
                            <td align="center" width="50%" style="padding-right:5px">Yang Menerima,</td>
                        </tr>
                        <tr>
                            <td width="30%"></td>
                            <td width="20%"></td>
                            <td align="center" width="50%" style="padding-right:5px">Sidoarjo, {{$datenow}}</td>
                        </tr>
                        <tr>
                            <td width="30%"></td>
                            <td width="20%"></td>
                            <td align="center" width="50%" height="30px" style="padding-right:5px"></td>
                        </tr>
                        <tr>
                            <td width="30%"></td>
                            <td width="20%"></td>
                            <td align="center" width="50%" height="50px" style="padding-right:5px">(........................) </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @endforeach
    </body>

</html>
<script src="{!! asset('assets/sneatV1/assets/js/angka_rupiah.min.js') !!}"></script>
<script>
    let items = document.querySelectorAll('.currency');
    items.forEach(items => {
        items.innerHTML = toRupiah(items.innerHTML,{floatingPoint: 0})
    });
    window.onload = function() {
        window.print();
    }
</script>

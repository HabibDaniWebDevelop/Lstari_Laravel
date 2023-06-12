<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cetak Penilaian Absensi</title>
    </head>
    <style>
        @page {
            size: <%= @size_card[0] %>cm  <%= @size_card[1] %>cm;
            margin: 5mm;
        } 

        html, body {
            height: 140mm;
            weight:95%;
            font-family: Arial, Helvetica, sans-serif;
            
        }

        table {
            border-collapse: collapse;
        }

        .td-surat {
            border: 1px solid black;
            
        }

        td{
            font-size:12px;
        }

        th{

            font-size:12px;
        }
        p{
            font-size:12px;
        }
    </style>
    <body>
        <h4 style="text-align: center">Penilaian Kehadiran Karyawan Periode {{$data_return['data']['TanggalAwal']}} - {{$data_return['data']['TanggalAkhir']}}</h4>
        <br><br>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th style="font-size : 12px; text-align: center; font-weight : bold "><strong>Nama Karyawan</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Bagian</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Divisi</strong></th> 
                    <th style="font-size : 12px; text-align: center; "><strong>Mulai Kerja</strong></th>    
                    <th style="font-size : 12px; text-align: center; "><strong>Sakit</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Ijin</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Ijin 60</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Terlambat</strong></th>  
                    <th style="font-size : 12px; text-align: center; "><strong>Absen</strong></th>  
                    <th style="font-size : 12px; text-align: center; "><strong>Absen Ijin</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Absen Sakit</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Cuti Tahunan</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Cuti Khusus</strong></th>
                    <th style="font-size : 12px; text-align: center; "><strong>Covid</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_return['data']['PenilaianAbsensi'] as $item)
                    <tr>
                        <td>{{$item->Employee}}</td>
                        <td align="center">{{$item->Department}}</td>
                        <td align="center">{{$item->HigherRank}}</td>
                        <td align="center">{{$item->StartWork}}</td>
                        <td align="center">{{$item->Sakit}}</td>
                        <td align="center">{{$item->Ijin}}</td>
                        <td align="center">{{$item->Ijin60}}</td>
                        <td align="center">{{$item->Telat}}</td>
                        <td align="center">{{$item->Absen}}</td>
                        <td align="center">{{$item->AbsenIjin}}</td>
                        <td align="center">{{$item->AbsenSakit}}</td>
                        <td align="center">{{$item->CutiTahunan}}</td>
                        <td align="center">{{$item->CutiKhusus}}</td>
                        <td align="center">{{$item->Covid}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td align="center" colspan="4"><strong>Total</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['Sakit']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['Ijin']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['Ijin60']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['Telat']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['Absen']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['AbsenIjin']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['AbsenSakit']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['CutiTahunan']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['CutiKhusus']}}</strong></td>
                        <td align="center"><strong>{{$data_return['data']['Total']['Covid']}}</strong></td>
                   </tr>
            </tbody>
        </table>
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
</html>
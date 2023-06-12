<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Tanda Terima</title>
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
            margin: 0px !important;
        }

        h1{
            font-size: 18px; 
            font-weight: bold; 
            color: black;
            margin: 5px 0px 5px 0px;
        }
    </style>
</head>
    <body>
        <h1 style="text-align: center;">Tanda Terima</h1>
        <table width="100%" border="0">	            
            <tbody>
                <tr>
                    <td width="8%" style="vertical-align:text-top;">No. Referensi</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="40%" style="vertical-align:text-top;">{{$tandaterima->SW}}</td>
                </tr>
                <tr>
                    <td width="8%" style="vertical-align:text-top;">Tanggal</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="40%" style="vertical-align:text-top;">{{$tandaterima->TransDate}}</td>    
                </tr>
            </tbody>
        </table>
        <table width="100%" border="0">	  
            <tbody>
                <tr>
                    <td width="8%">Telah terima dari</td>
                    <td width="1%">:</td>
                    <td width="40%">{{$tandaterima->FromUser}}</td>        		              	
                </tr>          
            </tbody>
        </table>
        <p>Bersama ini telah diterima barang - barang di bawah ini :</p>
        <table width="100%" border="1">
            <thead>
                <tr>
                    <th width="2%">No.</th>
                    <th width="9%">Jumlah</th>
                    <th width="25%">Nama Barang</th>
                    <th width="30%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tandaterimaitem as $item)
                <tr>
                    <td style="text-align: center">{{$loop->iteration}}</td>
                    <td>{{$item->Qty}} {{$item->Satuan}}</td>
                    <td>{{$item->Item}}</td>
                    <td>{{$item->Note}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div class="row">
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="60%" style="vertical-align: top">
                            <table width="100%" class="td-surat" border="1" style=" border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td align="center" class="td-surat">Admin</td>
                                        <td align="center" class="td-surat">Mengetahui</td>
                                        <td align="center" class="td-surat">Pengirim</td>
                                    </tr>
                                    <tr height="80px;">
                                        <td align="center" style="vertical-align:bottom;" class="td-surat">(.........................)</td>
                                        <td align="center" style="vertical-align:bottom;" class="td-surat">(.........................)</td>
                                        <td align="center" style="vertical-align:bottom;" class="td-surat">(.........................)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="10%" style="vertical-align: top"></td>
                        <td width="20%" align="right" style="vertical-align: top">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center">Penerima</td>
                                    </tr>
                                    <tr height="80px;">
                                        <td align="center" style="vertical-align:bottom; padding-bottom:3px;">(.........................)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="10%" style="vertical-align: top"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
<script>
    window.onload = function() {
        window.print();
        setTimeout(window.close, 0);
    }
</script>
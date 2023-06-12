<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cetak Jaminan Karyawan</title>
        <style>
            @media print {
                @page{
                    size: A4 portrait;
                    margin: 1mm;
                } 
                #Header, #Footer { 
                    display: none !important; 
                }
            }
        </style>
    </head>
    <body>
        <table width="100%" border="1">
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td style="font-size: 25px; color:blue; text-align:center">TANDA TERIMA</td>
                        </tr>
                        <tr>
                            <td style="font-size: 20px; color:red; text-align:center">PT. LESTARI MULIA SENTOSA</td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <table width="100%">
                        <tr>
                            <td width="70%" style="font-size: 16px;">Telah Terima dari Sdr/Sdri : <b>{{$jaminanKaryawan->NAME}}</b>
                            </td>
                            <td width="30%" style="font-size: 16px;">Bagian : <b>{{$jaminanKaryawan->Description}}</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%" style="font-size: 16px;">Berupa : <b>{{$jaminanKaryawan->Type}}&nbsp;{{$jaminanKaryawan->Remarks}}</b>&nbsp;&nbsp; <b style="color: red">(ASLI)</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%" style="font-size: 16px;">Nomor : <b>{{$jaminanKaryawan->SW}}</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%" style="font-size: 16px;">Tanda terima ini harap disimpan dengan baik sebagi BUKTI untuk pengambilan kembali.</td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%" style="font-size: 16px;">Perusahan menjamin keutuhannya (tidak akan rusak maupun hilang).</td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <table width="100%">
                        <tr>
                            <td width="70%"></td>
                            <td width="30%" align="center" style="font-size: 16px;">Sidoarjo, {{$datenow}}</td>
                        </tr>
                        <tr>
                            <td width="70%"></td>
                            <td width="30%" align="center" style="font-size: 16px;">HRD</td>
                        </tr>
                        <tr>
                            <td height="50"></td>
                        </tr>
                        <tr>
                            <td width="70%"></td>
                            <td width="30%" align="center" style="font-size: 16px;">(..............................)</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <script>
            window.onload = function() 
            { window.print(); }
        </script>
    </body>
</html>
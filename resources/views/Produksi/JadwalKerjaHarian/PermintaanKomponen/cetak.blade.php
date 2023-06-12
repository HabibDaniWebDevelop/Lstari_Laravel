<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Cetak</title>

    <style type="text/css" media="print">
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
        @page { 
            size: auto;
            margin: 3mm 5mm 24mm 5mm;
        }
        body {
            margin:0;
            padding:0;
        }

        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group; }

        @media print {
            .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
            .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                float: left;               
            }

            .col-sm-12 {
                width: 100%;
                height: 23%;
            }

            .col-sm-11 {
                width: 91.66666666666666%;
            }

            .col-sm-10 {
                width: 83.33333333333334%;
            }

            .col-sm-9 {
                    width: 75%;
            }

            .col-sm-8 {
                    width: 66.66666666666666%;
            }

            .col-sm-7 {
                    width: 58.333333333333336%;
            }

            .col-sm-6 {
                    width: 50%;
            }

            .col-sm-5 {
                    width: 41.66666666666667%;
            }

            .col-sm-4 {
                    width: 33.33333333333333%;
            }

            .col-sm-3 {
                    width: 25%;
            }

            .col-sm-2 {
                    width: 10%;
            }

            .col-sm-1 {
                    width: 8.333333333333332%;
                }            
        }
        .vendorListHeading th {
            background-color: #1a4567 !important;
            color: white !important;   
        }
    </style>
</head>
<body>

    <table width="100%">
        <tr>
            </td>
                <center><b>Detail Permintaan Area ID - {{$CRSW}}</b></center>
            </td>
        </tr>
    </table>
    <br>
    <div class="row">
        <div class="col-sm-8">
            <table width="100%" class="table table-striped" id="tampilitem" border="1">
                <thead bgcolor="#D2B48C">
                    <tr>
                        <th style="text-align:center; font-weight:bold; color: black; font-size:12px" width="20%">NTHKO</th>
                        <th style="text-align:center; font-weight:bold; color: black; font-size:12px" width="18%">Kode Produk</th>
                        <th style="text-align:center; font-weight:bold; color: black; font-size:12px" width="17%">Komponen</th>
                        <th style="text-align:center; font-weight:bold; color: black; font-size:12px" width="15%">Kadar</th>
                        <th style="text-align:center; font-weight:bold; color: black; font-size:12px" width="10%">Qty</th>
                        <th style="text-align:center; font-weight:bold; color: black; font-size:12px" width="10%">Weight</th>
                    </tr>			  		
                </thead>
                <tbody>
                    @foreach ($data2 as $datas2)
                    <tr>
                        <td align="center" style="font-size: 12px" >{{$datas2->NTKHO}}</td>
                        <td align="center" style="font-size: 12px" >{{$datas2->FG}}</td>
                        <td align="center" style="font-size: 12px" >{{$datas2->Komponen}}</td>
                        <td align="center" style="font-size: 12px" >{{$datas2->Kadar}}</td>
                        <td align="center" style="font-size: 12px" >{{$datas2->Qty}}</td>
                        <td align="center" style="font-size: 12px" >{{$datas2->Weight}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <table width="100%" class="table table-striped" id="tampiltotal" border="1">
                <thead bgcolor="#D2B48C">
                    <tr>
                        <th style="text-align:center; font-weight:bold; color : black; font-size: 12px">Komponen</th>
                        <th style="text-align:center; font-weight:bold; color : black; font-size: 12px">Kadar</th>
                        <th style="text-align:center; font-weight:bold; color : black; font-size: 12px">Total Qty</th>
                        <th style="text-align:center; font-weight:bold; color : black; font-size: 12px">Total Weight</th>
                    </tr>			  		
                </thead>
                <tbody>
                    @php
                        $totqty = 0;
                        $totbrt = 0;
                    @endphp
                    @foreach ($data3 as $datas3)
                    @php
                        $totqty += $datas3->jml;
                        $totbrt += $datas3->brt;
                    @endphp
                    <tr>
                        <td align="center" style="font-size: 12px">{{$datas3->SW}}</td> 
                        <td align="center" style="font-size: 12px">{{$datas3->kadar}}</td>
                        <td align="center" style="font-size: 12px">{{$datas3->jml}}</td>
                        <td align="center" style="font-size: 12px">{{$datas3->brt}}</td>
                    </tr>
                    @endforeach
                </tbody>
                    <tr>
                        <td align="right" style="font-weight: bold; font-size : 12px" colspan="2">Grand Total</td>
                        <td align="center" style="font-weight: bold; font-size : 12px; color: red">{{$totqty}}</td>
                        <td align="center" style="font-weight: bold; font-size : 12px; color: red">{{$totbrt}}</td>
                    </tr>
            </table>			  
        </div>		  
    </div>

</body>
</html>

<script>
    window.onload = function(){
        window.print();
        setTimeout(window.close, 0); 
    }
</script>

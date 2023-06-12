<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laboratorium Test</title>
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
</head>
    <body>
        <h3 style="text-align: center;">Laboratorium</h3>
        <br>
        <table width="100%" border="0">             
            <tbody>
                <tr>
                    <td width="10%" style="vertical-align:text-top;">ID Laboratorium</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$idLaboratorium}}</td>

                    <td width="10%" style="vertical-align:text-top;">Block</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$block}}</td>
                    
                </tr>
                <tr>
                    <td width="10%" style="vertical-align:text-top;">ID Laboratorium Xray</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$labtrx->ID}}</td>

                    <td width="6%" style="vertical-align:text-top;">Measuring Time</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$measuringTime}}</td>
                </tr>
                <tr>
                    <td width="10%" style="vertical-align:text-top;">Tanggal</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$date}}</td>

                    <td width="10%" style="vertical-align:text-top;">Operator</td>
                    <td width="1%" style="vertical-align:text-top;">:</td>
                    <td width="25%" style="vertical-align:text-top;">{{$operator}}</td>

                </tr>
                <tr></tr>
            </tbody>
        </table>
        <br><br>
        <table width="100%" border="1"> 
            <thead>
                <tr>
                    <th>No</th>
                    <th>NTHKO Lebur</th>
                    <th>Kadar</th>
                    <th>Batch No</th>
                    <th colspan="9">Berat</th>
                    <th colspan="2">Kadar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Awal</td>
                    <td>Timah</td>
                    <td>Perak</td>
                    <td>Emas</td>
                    <td>Tembaga</td>
                    <td>Hasil oven</td>
                    <td>Hasil Nitric</td>
                    <td>Sisa</td>
                    <td>Total</td>
                    <td>Emas</td>
                    <td>Perak</td>
                    <td></td>
                </tr>
                @foreach ($laboratorium as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->WorkAllocation}}-{{$item->Freq}}</td>
                    <td>{{$item->Kadar}}</td>
                    <td>{{$item->BatchNo}}</td>
                    <td>{{$item->WeightMelting}}</td>
                    <td>{{$item->WeightLead}}</td>
                    <td>{{$item->WeightSilver}}</td>
                    <td>{{$item->WeightGold}}</td>
                    <td>{{$item->WeightCopper}}</td>
                    <td>{{$item->WeightOven}}</td>
                    <td>{{$item->WeightNitric}}</td>
                    <td>{{$item->WeightLeft}}</td>
                    <td>{{$item->WeightTotal}}</td>
                    <td>{{$item->ContentGold}}</td>
                    <td>{{$item->ContentSilver}}</td>
                    <td>{{$item->Remarks}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
        <table width="100%" border="1">
            <thead>
                <tr>
                    <th>#</th>
                    @foreach ($datalabtestitem as $key => $item)
                    <th>{{$key}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{-- Loop Sebanyak Berapa baris data yang ada di test item. --}}
                @for ($i = 0; $i < count(array_values($datalabtestitem)[0]); $i++)
                    <tr>
                        <th>{{$i+1}}</th>
                        {{-- Loop Test Item Variable --}}
                        @foreach ($datalabtestitem as $key => $item)
                            <td>
                                {{-- Get Test Data Item By its Variable and index --}}
                                {{$datalabtestitem[$key][$i]}}
                            </td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
        <br><br>
        <table width="100%" border="1">
            <thead>
                <tr>
                    <th width="15%">#</th>
                    @foreach (array_values($datalabresultitem)[0] as $key => $value)
                    <th>{{$key}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($datalabresultitem as $key => $value)
                <tr>
                    <td>{{$key}}</td>
                    @foreach ($value as $item)
                        <td>{{$item}}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
<script>
    window.onload = function() {
        window.print();
    }
</script>
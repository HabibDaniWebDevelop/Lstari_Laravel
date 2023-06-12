<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cetak Lab Turun Kadar</title>
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
                text-align: center;
                border-collapse: collapse;
            }
    
            td{
                font-size:12px;
            }
    
            th{
                font-size:12px;
            }
            
            #info {
                text-align: left !important;
            }

        </style>
    </head>
    <body>
        @include('Produksi.Laboratorium.LabTurunKadar.table2')
    </body>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</html>
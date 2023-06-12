<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cetak Katalog Marketing</title>
        <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.min.css') !!}">
        <style>
            @page {
                margin: 5mm 5mm 5mm 5mm;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 330mm;
                }
                div {
                    break-inside: avoid;
                }
            }
        </style>
    </head>
    <body>
        <div class="row">
            <h1 class="text-center">FORM MARKETING</h1>
            @foreach ($data_return['data'] as $item)
            <div class="col-12 mt-1 mb-2">
                <div class="card border border-dark rounded-0">
                    <div class="card-body p-1">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="card border border-dark rounded-0">
                                    <div class="card-body" style="padding:5px;">
                                        {{$item['model']}} {{$item['serial_number']}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card border border-dark rounded-0">
                                    <div class="card-body" style="padding:5px;">
                                        Model
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12">
                                <div class="card border border-dark rounded-0">
                                    <div class="card-body" style="padding:5px;">
                                        <p style="position: absolute;">{{$item['tanggal_stp']}}</p>
                                        <div class="row text-center">
                                            <div class="col">
                                                <br>
                                                <img src="{{ Session::get('hostfoto') }}{{ $item['gambar2d'] }}" style="width: 75px; height: 75px; object-fit: contain" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                            </div>
                                            @foreach ($item['items'] as $varian)
                                                <div class="col">
                                                    {{$loop->iteration}} <br>
                                                    <img src="{{ Session::get('hostfoto') }}{{$varian['Photo']}}" style="width: 75px; height: 75px; object-fit: contain" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                </div>
                                            @endforeach
                                        </div>
                                        <hr class="mt-1 mb-1">
                                        Berat barang jadi : 
                                        <hr class="mt-1 mb-1">
                                        Keterangan : {{ implode(', ', $item['ukuran']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.bundle.min.js') !!}"></script>
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
</html>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cetak Model</title>
        {{-- <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/css/core2.css" class="template-customizer-core-css') !!}" /> --}}
        <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.min.css') !!}">
        <style type="text/css">
            body {
                font-family: arial;
                font-size: 13px;
            }

            @media print {

                @page {
                    size: A4 portrait;
                    margin: 5mm 5mm 5mm 5mm;
                }
                div {
                    break-inside: avoid;
                }
            }
        </style>
    </head>
    <body>
        <div class="row">
            @foreach ($data_return['data'] as $item)
            <div class="col-4">
                <div class="card border-dark mb-3">
                    <img src="{{Session::get('hostfoto')}}/image2/{{$item->Photo}}.jpg" style="object-fit: cover; margin: 0 auto;" class="card-img-top" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                    <div class="card-body">
                        <span>KODE : {{$item->SW}}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- <script src="{!! asset('assets/sneatV1/assets/vendor/js/bootstrap.js') !!}"></script> --}}
        {{-- Bootstrap --}}
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Bootstrap5Clean/bootstrap.bundle.min.js') !!}"></script>
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
</html>
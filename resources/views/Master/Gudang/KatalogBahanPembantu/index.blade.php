<?php $title = 'Katalog Bahan Pembantu'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Gudang </li>
        <li class="breadcrumb-item active">{{ $title }} </li>
    </ol>

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

@endsection

@section('css')
    <meta http-equiv="Cache-Control" content="no-store" />
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.css') !!}">

    <style type="text/css">
        .bgfront {
            background-image:
                linear-gradient(to bottom, rgba(240, 240, 240, 0.5), rgba(145, 48, 48, 0.95)),
                url('{!! asset('assets/images/GudangECatalog/gudang-bg.jpg') !!}');
            /* background-size: cover; */
        }

        .cat {
            padding: 20px;
            box-sizing: border-box;
            transform-style: preserve-3d;
            transform: perspective(1000px);
        }

        .cardy-satu {
            background-color: #fff;
            border-radius: 10%;
            width: 250px;
            height: 250px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .img-icon {
            max-width: 130px;
            max-height: 100px;
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate3d(-50%, -50%, 8000px);
            transition: 0.3s;
        }

        .cat:hover .img-icon {
            transform: translate3d(-50%, -50%, 8000px) scale(1.5);
        }

        .card-filter {
            width: 120px;
            height: 100px;
            border-radius: 20px;
            display: inline-block;
            margin: 10px;
            box-shadow: 0 4px 8px 0 #e9ecef;
            transition: 0.3s;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
        }

        .card-filter:hover {
            box-shadow: 5px 6px 6px 2px #e9ecef;
            transform: scale(1.1);
        }

        .type {
            position: absolute;
            top: 0;
            right: 0;
        }

        .tt {
            white-space: nowrap;
            max-width: 250px;
            padding: 0.5em 1em;
            overflow: hidden;
            text-overflow: ellipsis !important;
        }

        .btn-detail {
            opacity: 0;
        }

        .btn-action {
            position: relative;
        }

        .card-material {
            --card-gradient: rgba(0, 0, 0, 0.8);
            --card-blend-mode: overlay;
            box-shadow: 0.05rem 0.1rem 0.3rem -0.03rem rgba(0, 0, 0, 0.3);
            background-image: linear-gradient(var(--card-gradient), white max(9.5rem, 27vh));
            overflow: hidden;
            margin-bottom: 20px;
        }

        .img-material {
            border-radius: 0.5rem 0.5rem 0 0;
            width: 100%;
            object-fit: cover;
            max-height: max(10rem, 30vh);
            aspect-ratio: 4/3;
            mix-blend-mode: var(--card-blend-mode);

            ~* {
                margin-left: 1rem;
                margin-right: 1rem;
            }
        }

        .portfolio-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .portfolio-item .thumb {
            overflow: hidden;
        }

        .portfolio-item .thumb img {
            -webkit-transform: scale(1);
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            -o-transform: scale(1);
            transform: scale(1);
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .portfolio-item .details {
            /* background-color: rebeccapurple; */
            color: #FFF;
            position: absolute;
            padding: 0 25px;
            top: 25px;
            width: 100%;
            z-index: 1;
        }

        .portfolio-item .details h4 {
            color: #FFF;
            margin: 0 0 4px;
            opacity: 0;
            transform: translateY(30px);
            transition: all cubic-bezier(0.075, 0.82, 0.165, 1) 1s;
            font-size: 18px;
        }

        .portfolio-item .details span {
            font-size: 16px;
            opacity: 0;
            display: block;
            transform: translateY(40px);
            transition: all cubic-bezier(0.075, 0.82, 0.165, 1) 1s;
        }

        .portfolio-item .plus-icon {
            color: #1767E2;
            background: #FFF;
            border-radius: 10%;
            position: absolute;
            font-size: 12px;
            padding: 2px;

            opacity: 1;
            line-height: 32px;
            text-align: center;
            z-index: 1;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .portfolio-item .mask {
            background: #1767E2;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            height: 100%;
            width: 100%;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .portfolio-item:hover {
            -webkit-box-shadow: 0px 6px 15px 0px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0px 6px 15px 0px rgba(0, 0, 0, 0.15);
            box-shadow: 0px 6px 15px 0px rgba(0, 0, 0, 0.15);
        }

        .portfolio-item:hover .mask {
            opacity: 0.75;
        }

        .portfolio-item:hover .tt {
            opacity: 0;
        }


        .portfolio-item:hover .btn-detail {
            opacity: 1;

            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .portfolio-item:hover img {
            -webkit-transform: scale(1);
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            -o-transform: scale(1);
            transform: scale(1.1);
        }

        .portfolio-item:hover .details h4,
        .portfolio-item:hover .details span {
            opacity: 1;
            transform: translateY(0);
        }

        .portfolio-item:hover .plus-icon {
            opacity: 0;
        }
    </style>

@endsection


@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="mb-1" id="menu1">
                @include('Master.Gudang.KatalogBahanPembantu.data')
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>

    @endsection

    @section('script')

        {{-- Bootstrap Select --}}
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/vanilla-tilt/vanilla-tilt.min.js') !!}"></script>
        <script src="{!! asset('assets/sneatV1/assets/vendor/libs/Dropify/dropify.min.js') !!}"></script>

        <script>
            VanillaTilt.init(document.querySelectorAll(".cat"), {
                max: 30,
                speed: 250,
            });

            // $.ajaxSetup({
            //     // Disable caching of AJAX responses
            //     cache: false
            // });

            var selector2 = '.cat';
            $(selector2).on('click', function() {
                var menu = $(this).attr('id');

                $.get('/Master/Gudang/KatalogBahanPembantu/menu/' + menu, function(data) {
                    $("#menu1").html(data);
                });

            });

            var isikeranjang = '';
            var locpertama = '';

            //Menganti loocation
            $(document).on('change', '#Location', function() {
                let Location = $('#Location').val();
                // console.log(Location);

                $('.card-filter').removeClass('bg-primary text-white');
                $("#btn-all").addClass('bg-primary text-white');

                let datas = {
                    "menu": "btn-all",
                    "Location": Location,
                }

                $.ajax({
                    url: "/Master/Gudang/KatalogBahanPembantu/pagination",
                    data: datas,
                    headers: {
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    },
                    success: function(data) {
                        $('#table_data').html(data);
                    }
                });

                //cari_filter
                $.ajax({
                    url: "/Master/Gudang/KatalogBahanPembantu/carifilter",
                    data: datas,
                    success: function(data) {
                        $('#cari_filter').html(data);
                    }
                });

            });

            function ambil(item, id) {
                let Location = $('#Location').val();
                console.log(Location, item, id);
                // if (Location == 'Choose...' || Location != id) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'warning!',
                //         text: 'Location harus di pilih terlebih dahulu',
                //         showConfirmButton: false,
                //         timer: 1600
                //     })
                //     return;
                // }

                if (locpertama == '') {
                    locpertama = Location;
                }

                // if (Location != locpertama) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'warning!',
                //         text: 'Material Harus dari '+locpertama,
                //         showConfirmButton: false,
                //         timer: 1600
                //     })
                //     return;
                // }

                    isikeranjang = isikeranjang + item + ",";
                // console.log(isikeranjang);
                var count = parseInt($('#keranjangisi').text()) + 1;
                $('#keranjangisi').text(count);
                $('#keranjangisi').removeClass('d-none');
            };

            //tranfer ke mr
            function transfer() {
                // $.get('/Inventori/MaterialRequest/BahanPembantu', isikeranjang, function(data) {
                // });

                var newUrl = '/Inventori/MaterialRequest/BahanPembantu?loc='+locpertama+'&id=' + isikeranjang;
                window.location.href = newUrl;
            }

            function lihat(id) {
                $("#jodulmodal1").html('Detail Informasi');
                $('#modalformat').attr('class', 'modal-dialog modal-xl');
                $("#simpan1").addClass('d-none');
                $("#simpan2").addClass('d-none');
                $('#simpan1').val('Tambah');

                $.get('/Master/Gudang/KatalogBahanPembantu/lihat/' + id, function(data) {
                    $("#modal1").html(data);
                    $('#modalkatalog').modal('show');
                });

                // alert(id);
            }

            function ubah(id) {
                $("#jodulmodal1").html('Update Data Material');
                $('#modalformat').attr('class', 'modal-dialog modal-lg');
                $("#simpan1").removeClass('d-none');
                $("#simpan2").addClass('d-none');
                $('#simpan1').val('Tambah');

                $.get('/Master/Gudang/KatalogBahanPembantu/ubah/' + id, function(data) {
                    $("#modal1").html(data);
                    $('#modalkatalog').modal('show');
                });
            }

            function gambar(id) {
                $("#jodulmodal1").html('Gambar Data Material');
                $('#modalformat').attr('class', 'modal-dialog modal-xl');
                $("#simpan1").addClass('d-none');
                $("#simpan2").removeClass('d-none');

                $.get('/Master/Gudang/KatalogBahanPembantu/gambar/' + id, function(data) {
                    $("#modal1").html(data);
                    $('#modalkatalog').modal('show');
                });
            }

            // klik simpan pada modal untuk tambah data dan edit data
            function KlikSimpan1() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var id = $('#idnee').val();
                var operation = $('#simpan1').val();

                var idnee = $('#idnee').val();
                var Brand = $('#Brand').val();
                var Type = $('#Type').val();
                var Remarks = $('#Remarks').val();
                var MaterialFunction = $('#MaterialFunction').val();
                var department2 = $('#department2').val();

                let TDS = $('#TDS').prop('files')[0]
                let MSDS = $('#MSDS').prop('files')[0]
                data = new FormData()
                data.append('TDS', TDS)
                data.append('MSDS', MSDS)
                data.append('idnee', idnee)
                data.append('Brand', Brand)
                data.append('Type', Type)
                data.append('Remarks', Remarks)
                data.append('MaterialFunction', MaterialFunction)
                data.append('department2', department2)


                // let formData = new FormData('#formmodal1');

                // alert(formData);
                var type = "post";
                var ajaxurl = '/Master/Gudang/KatalogBahanPembantu/edit/';

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        Swal.fire({
                            icon: 'success',
                            title: 'success!',
                            text: 'Simpan Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modalkatalog').modal('hide');
                            }
                        });

                        // $('#modalinfo').modal('hide');
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.responseJSON.message,
                            showConfirmButton: false,
                            timer: 2400
                        })
                        console.log('Error:', data);
                    }
                });

            };

            // klik simpan pada modal untuk upload gambar
            function KlikSimpangbr() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var id = $('#idnee').val();
                var operation = $('#simpan1').val();

                var idnee = $('#idnee').val();
                let Image1 = $('#Image1').prop('files')[0]
                let Image2 = $('#Image2').prop('files')[0]
                let Image3 = $('#Image3').prop('files')[0]
                let Image4 = $('#Image4').prop('files')[0]
                let Image5 = $('#Image5').prop('files')[0]
                let Image6 = $('#Image6').prop('files')[0]
                let TechnicalImage = $('#TechnicalImage').prop('files')[0]

                data = new FormData()
                data.append('idnee', idnee)
                data.append('Image1', Image1)
                data.append('Image2', Image2)
                data.append('Image3', Image3)
                data.append('Image4', Image4)
                data.append('Image5', Image5)
                data.append('Image6', Image6)
                data.append('TechnicalImage', TechnicalImage)

                // let formData = new FormData('#formmodal1');
                var type = "post";
                var ajaxurl = '/Master/Gudang/KatalogBahanPembantu/uploadgambar/';

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        Swal.fire({
                            icon: 'success',
                            title: 'success!',
                            text: 'Simpan Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modalkatalog').modal('hide');
                            }
                        });

                        // $('#modalinfo').modal('hide');
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.responseJSON.message,
                            showConfirmButton: false,
                            timer: 2400
                        })
                        console.log('Error:', data);
                    }
                });

            };
        </script>

    @endsection

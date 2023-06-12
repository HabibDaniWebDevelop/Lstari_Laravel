<?php $title = 'Katalog'; ?>

<?php 
    if (Auth::check()) {
        $app = "app";
    } else {
        $app = "app2";
    }
?>


@extends('layouts.backend-Theme-3.'.$app)

@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item"> Master </li>
        <li class="breadcrumb-item"> Item </li>
        <li class="breadcrumb-item active"> Katalog </li>
    </ol>
@endsection

@section('css')
    {{-- Swiper.js --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/swiperjs/swiper-bundle.min.css') !!}">
    {{-- Lightbox.js --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/css/lightbox.min.css') !!}">
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
    {{-- Fancybox --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/fancybox/fancybox.min.css') !!}">

    <style>
        #CarouselSPK .swiper-slide img {  
            object-fit: contain;
            object-position: center;
            overflow: hidden;
            max-width: 80%;
            max-height:40vh;
        }

        #CarouselModel .swiper-slide img {  
            object-fit: contain;
            object-position: center;
            overflow: hidden;
            max-width: 80%;
            height:35vh;
        }

        #listGroupModel, #listGroupTukangLuar{
            height: 20vh;
        }

        @media only screen and (min-width: 1024px) {
            #listGroupModel, #listGroupTukangLuar{
                height: 55vh;
            }
        }

        @media (min-width: 1200px) {
            .spkItemCard {
                font-size: 12px !important;
            }
        }
        /* If the screen size is smaller than 1200px, set the font-size to 80px */
        @media (max-width: 1199.98px) {
            .spkItemCard {
                font-size: 8px !important;
            }
        }

        #myCard{
            min-height: calc(100vh - 200px);
        }

        a[data-fancybox] img {
            cursor: zoom-in;
        }
    </style>

@endsection

@section('container')
    @include('Master.Item.Catalog.data')
@endsection

@section('script')
    {{-- Swiper.js --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/swiperjs/swiper-bundle.min.js') !!}"></script>
    {{-- Lightbox.js --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/lightboxjs/js/lightbox.min.js') !!}"></script>
    {{-- Bootstrap Select --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
    {{-- Fancybox --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/fancybox/fancybox.umd.min.js') !!}"></script>


    <script>
        // Fungsi untuk cari spk
        function CariSPK() {
            let SWSPK = $('#cari').val()
            // Make request
            $.ajax({
                type: "GET",
                url: "/Master/Item/Katalog/cekspk?sw="+SWSPK,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $.get('/Master/Item/Katalog/spk/'+SWSPK,function (dataspk) {
                        $('#spkview').html(dataspk)
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "SPK Not Found",
                        confirmButtonColor: "#913030"
                    })

                    return;
                }
            })
        }

        // Function for checking and getting product exists or not in tab "No Model"
        function CariModel() {
            let idCategory = $('#productCategory').val()
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()

            // Make request
            $.ajax({
                type: "GET",
                url: "/Master/Item/Katalog/cekmodel?idcategory="+idCategory+"&fromnumber="+fromNumber+"&tonumber="+toNumber,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // If product Exists it will get the html data
                    $.get('/Master/Item/Katalog/model/'+idCategory+'/'+fromNumber+'/'+toNumber,function (datamodel) {
                        $('#noModelView').html(datamodel)
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    // Return if product didn't exists
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Model Not Found",
                        confirmButtonColor: "#913030"
                    })
                    return;
                }
            })
        }

        // Function for checking and getting product exists or not in tab "Tukang Luar"
        function CariModelTukangLuar() {
            let idCategory = $('#productCategoryTukangLuar').val()
            let fromNumber = $('#fromNumberTukangLuar').val()
            let toNumber = $('#toNumberTukangLuar').val()

            // Make request
            $.ajax({
                type: "GET",
                url: "/Master/Item/Katalog/cektukangluar?idcategory="+idCategory+"&fromnumber="+fromNumber+"&tonumber="+toNumber,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // If product Exists it will get the html data
                    $.get('/Master/Item/Katalog/tukangluar/'+idCategory+'/'+fromNumber+'/'+toNumber,function (datamodel) {
                        $('#tukangLuarView').html(datamodel)
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    // Return if product didn't exists
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Model Not Found",
                        confirmButtonColor: "#913030"
                    })
                    return;
                }
            })
        }

        // Function for checking and getting product exists or not by SPK PPIC in tab "Tukang Luar"
        function CariModelTukangLuarBySPK() {
            let noSPK = $('#nomorSPK').val()
            if (noSPK == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Nomor SPK Tidak Boleh Kosong",
                    confirmButtonColor: "#913030"
                })
                return;
            }

            // Make request
            $.ajax({
                type: "GET",
                url: "/Master/Item/Katalog/cektukangluarbyspk?noSPK="+noSPK,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // If product Exists it will get the html data
                    $.get('/Master/Item/Katalog/tukangluarbyspk/'+noSPK,function (datamodel) {
                        $('#tukangLuarView').html(datamodel)
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    // Return if product didn't exists
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Model Not Found",
                        confirmButtonColor: "#913030"
                    })
                    return;
                }
            })
        }

        // Function for getting Resin Lilin Photo by idKaret
        function CariLilin() {
            let idkaret = $('#idkaret').val()
            // Make request
            $.ajax({
                type: "GET",
                url: "/Master/Item/Katalog/ceklilin?idKaret="+idkaret,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $.get('/Master/Item/Katalog/lilin/'+idkaret,function (datalilin) {
                        $('#lilinview').html(datalilin)
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "ID karet tidak ditemukan",
                        confirmButtonColor: "#913030"
                    })

                    return;
                }
            })
        }

        // Function for getting product exists or not in tab "Marketing"
        function CariModelMarketing() {
            let idCategory = $('#productCategoryMarketing').val()
            let fromNumber = $('#fromNumberMarketing').val()
            let toNumber = $('#toNumberMarketing').val()

            let data = {idcategory:idCategory, fromnumber:fromNumber, tonumber:toNumber}

            // Make request
            $.ajax({
                type: "GET",
                url: "/Master/Item/Katalog/marketing",
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    console.log(data);
                    // set noModelViewMarketing
                    $('#noModelViewMarketing').html(data.data.html)
                    return;
                },
                error: function(xhr, textStatus, errorThrown){
                    // Return if product didn't exists
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Model Not Found",
                        confirmButtonColor: "#913030"
                    })
                    return;
                }
            })
        }

        // Fungsi untuk lihat detail produk Tab SPK
        function DetailProductSPK(index, ProductSW) {
            // Set previous active button to non active
            $('#listGroupSPK .active').removeClass('active')

            // Set clicked button to active
            $('#spkItem_'+index).addClass('active')

            // Hit Backend for getting detail product for showing Photo, part , accesories, and plate
            $.get('/Master/Item/Katalog/spk/detail/'+ProductSW,function (data) {
                $('#detailProductSPK').html(data)
            })
        }

        // Fungsi untuk lihat detail produk Tab SPK
        function DetailProductModel(index, ProductSW) {
            // Set previous active button to non active
            $('#listGroupModel .active').removeClass('active')

            // Set clicked button to active
            $('#productItemModel_'+index).addClass('active')

            // Hit Backend for getting detail product for showing Photo, part , accesories, and plate
            $.get('/Master/Item/Katalog/model/detail/'+ProductSW,function (data) {
                $('#detailProductModel').html(data)
            })
        }

        // Fungsi untuk lihat detail produk Tab TukangLuar
        function DetailProductTukangLuar(index, ProductSW) {
            // Set previous active button to non active
            $('#listGroupTukangLuar .active').removeClass('active')

            // Set clicked button to active
            $('#productItemTukangLuar_'+index).addClass('active')

            // Hit Backend for getting detail product for showing Photo, part , accesories, and plate
            $.get('/Master/Item/Katalog/tukangluar/detail/'+ProductSW,function (data) {
                $('#detailProductTukangLuar').html(data)
            })
        }

        // Function if Product Category change
        $('#productCategory').change(function () {   
            // Check if Start and Last number not null
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()
            if (fromNumber != '' && toNumber != ''){
                CariModel()
            }
            return false
        })

        // Function if fromNumber Change
        $('#fromNumber').change(function () {
            // Check if Start and Last number not null
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()
            if (fromNumber != '' && toNumber != ''){
                CariModel()
            }
            return false
        })
        
        // Function if toNumber Change
        $('#toNumber').change(function () {
            // Check if Start and Last number not null
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()
            if (fromNumber != '' && toNumber != ''){
                CariModel()
            }
            return false
        })

        // Function if Product Category Tukang Luar change
        $('#productCategoryTukangLuar').change(function () {   
            // Check if Start and Last number not null
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()
            if (fromNumber != '' && toNumber != ''){
                CariModelTukangLuar()
            }
            return false
        })

        // Function if fromNumber Tukang Luar Change
        $('#fromNumberTukangLuar').change(function () {
            // Check if Start and Last number not null
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()
            if (fromNumber != '' && toNumber != ''){
                CariModelTukangLuar()
            }
            return false
        })
        
        // Function if toNumber Tukang Luar Change
        $('#toNumberTukangLuar').change(function () {
            // Check if Start and Last number not null
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()
            if (fromNumber != '' && toNumber != ''){
                CariModelTukangLuar()
            }
            return false
        })

        // Function if button cetak on model clicked
        function KlikCetak() {
            let idCategory = $('#productCategory').val()
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()

            // if (idCategory == "" || fromNumber == "" || toNumber == "") {
            window.open("/Master/Item/Katalog/model/cetak?idcategory="+idCategory+"&fromnumber="+fromNumber+"&tonumber="+toNumber, '_blank');
            // }
        }

        // Function if button cetak item tertentu on model clicked
        function KlikCetakSelectedItem() {
            let idCategory = $('#productCategory').val()
            let fromNumber = $('#fromNumber').val()
            let toNumber = $('#toNumber').val()

            // if (idCategory == "" || fromNumber == "" || toNumber == "") {
            window.open("/Master/Item/Katalog/model/cetakselectedmodel?idcategory="+idCategory+"&fromnumber="+fromNumber+"&tonumber="+toNumber, '_blank');
            // }
        }

        function KlikCetakMarketing() {
            let idCategory = $('#productCategoryMarketing').val()
            let fromNumber = $('#fromNumberMarketing').val()
            let toNumber = $('#toNumberMarketing').val()

            // if (idCategory == "" || fromNumber == "" || toNumber == "") {
            window.open("/Master/Item/Katalog/marketing/cetak?idcategory="+idCategory+"&fromnumber="+fromNumber+"&tonumber="+toNumber, '_blank');
            // }
        }

    </script>
@endsection
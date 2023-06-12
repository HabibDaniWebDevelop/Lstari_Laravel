<?php $title = 'SPKO Percobaan Kuningan Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">SPKO Percobaan Kuningan Workshop </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Workshop.SPKO.PercobaanKuningan.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- Bootstrap Select --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
    <script>
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
        });

        // Function if btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }

        // Function for klik baru
        function KlikBaru() {
            // Getting idSPKOPercobaanKuningan from hidden input
            let idSPKOPercobaanKuningan = $('#idSPKOPercobaanKuningan').val()
            if (idSPKOPercobaanKuningan != "") {
                // If idSPKOPercobaanKuningan have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Disable button "Baru  & Ubah"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Disabled Cetak
            $("#btn_cetak").prop('disabled',true)
            // Enable input
            $("#employee").prop('disabled',false)
            $("#idMatras").prop('disabled',false)
            $("#catatan").prop('disabled',false)
            return
        }

        function searchMatras() {
            // Get Matras
            let idMatras = $('#idMatras').val()
            if (idMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "ID NTHKO Matras Tidak Boleh Blank",
                })
                return;
            }
            // Try to get Matras
            $.ajax({
                url: '/Workshop/SPKO/PercobaanKuningan/cari-matras',
                type: 'GET',
                data:{idMatras:idMatras},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    console.log(data);
                    // Empty row in tbody
                    $("#tabel1  tbody").empty()
                    
                    $("#tabel1 > tbody").append(data.data.matrasHTML);
                    return
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            });
        }

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan') {
                saveSPKOWorkshop()
                return
            }
            if (action == 'ubah') {
                updateSPKOWorkshop()
                return
            }
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Invalid Action",
            })
            return;
        }

        function KlikUbah() {
            // Disable button "Baru  & Ubah"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            // enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // disable Cetak
            $("#btn_cetak").prop('disabled',true)
            // enable input
            $("#employee").prop('disabled',false)
            $("#jenisMatras").prop('disabled',false)
            $("#ukuranMatras").prop('disabled',false)
            $("#catatan").prop('disabled',false)
            // enable Item
            enableBootstrapSelect()
            $('.btn_add_row').prop('disabled',false)
            $('.btn_remove').prop('disabled',false)
            return
        }

        function saveSPKOWorkshop() {
            // Get Input Value
            let idEmployee = $('#employee').val()
            let jenisMatras = $('#jenisMatras').val()
            let ukuranMatras = $('#ukuranMatras').val()
            let tanggal = $('#tanggalNow').val()
            let catatan = $('#catatan').val()
            let items = []
            // Get items
            let itemsElement = $('.product select')
            if (itemsElement.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Item Minimal adalah satu.",
                })
                return;
            }
            // Loop items length to get value
            for (let index = 0; index < itemsElement.length; index++) {
                let indexItem = index+1
                // Get value of item
                let item = $('#product_'+indexItem).val()
                // If item is 0, it means item not selected
                if (item == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Item baris "+indexItem+" Masih Belum Terpilih.",
                    })
                    return;
                }
                // If item is already in items, it means that item is selected above
                if (items.includes(item)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Item baris "+indexItem+" Sudah Ada Pada Baris Sebelumnya.",
                    })
                    return;
                }

                // Checking okee....
                items.push(item)
            }
            // set data to json
            let data = {
                idEmployee:idEmployee,
                jenisMatras:jenisMatras,
                ukuranMatras:ukuranMatras,
                tanggal:tanggal,
                catatan:catatan,
                items:items
            }

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/Workshop/SPKO/Matras',
                type: 'POST',
                data:data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hiddem Input
                    $('#idSPKOWorkshop').val(data.data.ID)
                    $('#action').val('ubah')

                    // Disable button "Baru  & Ubah"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Enable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#employee").prop('disabled',true)
                    $("#ukuranMatras").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
                    // Disable Item
                    disableBoostrapSelect()
                    $('.btn_add_row').prop('disabled',true)
                    $('.btn_remove').prop('disabled',true)
                    return
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            });

        }

        function updateSPKOWorkshop() {
            // Get Input Value
            let idSPKOWorkshop = $('#idSPKOWorkshop').val()
            let idEmployee = $('#employee').val()
            let ukuranMatras = $('#ukuranMatras').val()
            let tanggal = $('#tanggalNow').val()
            let catatan = $('#catatan').val()
            let items = []
            // Get items
            let itemsElement = $('.product select')
            if (itemsElement.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Item Minimal adalah satu.",
                })
                return;
            }
            // Loop items length to get value
            for (let index = 0; index < itemsElement.length; index++) {
                let indexItem = index+1
                // Get value of item
                let item = $('#product_'+indexItem).val()
                // If item is 0, it means item not selected
                if (item == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Item baris "+indexItem+" Masih Belum Terpilih.",
                    })
                    return;
                }
                // If item is already in items, it means that item is selected above
                if (items.includes(item)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Item baris "+indexItem+" Sudah Ada Pada Baris Sebelumnya.",
                    })
                    return;
                }

                // Checking okee....
                items.push(item)
            }
            // set data to json
            let data = {
                idSPKOWorkshop:idSPKOWorkshop,
                idEmployee:idEmployee,
                ukuranMatras:ukuranMatras,
                tanggal:tanggal,
                catatan:catatan,
                items:items
            }

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/Workshop/SPKO/Matras/',
                type: 'PUT',
                data:data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#action').val('ubah')

                    // Disable button "Baru  & Ubah"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Enable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#employee").prop('disabled',true)
                    $("#ukuranMatras").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
                    // Disable Item
                    disableBoostrapSelect()
                    $('.btn_add_row').prop('disabled',true)
                    $('.btn_remove').prop('disabled',true)
                    return
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            });
        }

        function KlikCari() {
            let keyword = $('#cari').val()

            if (keyword == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Cari tidak boleh blank!",
                })
                return;
            }
        
            // Search SPKO
            $.ajax({
                url: '/Workshop/SPKO/Matras/search',
                type: 'GET',
                data:{idSPKO:keyword},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hiddem Input
                    $('#idSPKOWorkshop').val(data.data.ID)
                    $('#action').val('ubah')

                    // Set Header Value
                    $('#tanggalNow').val(data.data.TransDate)
                    $("#employee").val(data.data.Employee).change();
                    $("#ukuranMatras").val(data.data.UkuranMatras).change();
                    $("#catatan").val(data.data.Remarks)

                    // Set Items Value
                    // Empty row in tbody
                    $("#tabel1  tbody").empty()
                    data.data.itemsHTML.forEach(function (element,index) {
                        let indexItem = index+1
                        $("#tabel1 > tbody").append(element);
                        $('#product_'+indexItem).val(data.data.items[index].ID).change();
                        $('#product_'+indexItem).selectpicker();
                    });
                    // Disable button "Baru  & Ubah"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Enable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#employee").prop('disabled',true)
                    $("#ukuranMatras").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
                    // Disable Item
                    disableBoostrapSelect()
                    $('.btn_add_row').prop('disabled',true)
                    $('.btn_remove').prop('disabled',true)
                    return
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            });
        }

        function klikCetak() {
            let idSPKOWorkshop = $('#idSPKOWorkshop').val()
            if (idSPKOWorkshop == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "SPKO Belum Terpilih",
                })
                return;
            }
            window.open('/Workshop/SPKO/Matras/cetak?idSPKO='+idSPKOWorkshop, '_blank');
            return
        }
    </script>
    

@endsection
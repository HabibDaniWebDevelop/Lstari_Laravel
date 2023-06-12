<?php $title = 'SPKO Matras Workshop'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">SPKO Matras Workshop </li>
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

                @include('Workshop.SPKO.Matras.data')
                
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

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function enableBootstrapSelect() {
            $('.product').removeAttr("disabled")
            $('.product').selectpicker('destroy')
            $('.product').selectpicker()
        }

        function disableBoostrapSelect() {
            $('.product').attr("disabled",true)
            $('.product').selectpicker('destroy')
            $('.product').selectpicker()
        }
        
        function refreshBootstrapSelect() {
            $('.product').selectpicker('destroy')
            $('.product').selectpicker()
        }

        // Function if btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }

        // Function for klik baru
        function KlikBaru() {
            // Getting idSPKOWorkshop from hidden input
            let idSPKOWorkshop = $('#idSPKOWorkshop').val()
            if (idSPKOWorkshop != "") {
                // If idSPKOWorkshop have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Disable button "Baru, Ubah, and Posting"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_posting").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Disabled Cetak
            $("#btn_cetak").prop('disabled',true)
            // Enable input
            $("#employee").prop('disabled',false)
            $("#idMasterGambarTeknik").prop('disabled',false)
            $("#catatan").prop('disabled',false)
            return
        }
        
        function FindMasterGambarTeknik() {
            let idMasterGambarTeknik = $('#idMasterGambarTeknik').val()
            if (idMasterGambarTeknik == '') {
                return
            }

            // Get Master Gambar Teknik
            $.ajax({
                url: '/Workshop/SPKO/Matras/gambarteknik',
                type: 'GET',
                data:{idMasterGambarTeknik:idMasterGambarTeknik},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $("#tabel1  tbody").empty()
                    $("#tabel1 > tbody").append(data.data.itemsHTML);
                    return
                },
                error: function(xhr){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    $('#idMasterGambarTeknik').val("")
                    return;
                }
            });
        }

        function MasterGambarTeknikPress(event){
            if (event.keyCode === 13 || event.keyCode === 9) {
                FindMasterGambarTeknik()
                return
            } else {
                return
            }
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
            // Disable button "Baru, Ubah, and Posting"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_posting").prop('disabled',true)
            // enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // disable Cetak
            $("#btn_cetak").prop('disabled',true)
            // enable input
            $("#employee").prop('disabled',false)
            $("#idMasterGambarTeknik").prop('disabled',false)
            $("#catatan").prop('disabled',false)
            return
        }

        function saveSPKOWorkshop() {
            // Get Input Value
            let idMasterGambarTeknik = $('#idMasterGambarTeknik').val()
            let idEmployee = $('#employee').val()
            let catatan = $('#catatan').val()

            if (idMasterGambarTeknik == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Gambar Teknik Tidak Boleh Kosong",
                })
                return;
            }

            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return;
            }
            
            // set data to json
            let data = {
                idEmployee:idEmployee,
                idMasterGambarTeknik:idMasterGambarTeknik,
                catatan:catatan
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
                    $('#idSPKOWorkshop').val(data.data.id)
                    $('#postingStatus').val('A')
                    $('#action').val('ubah')

                    // Disable button "Baru, Ubah and Posting"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_posting").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Disable Cetak
                    $("#btn_cetak").prop('disabled',true)
                    // Disable input
                    $("#idMasterGambarTeknik").prop('disabled',true)
                    $("#employee").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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
            let idSPKOMatras = $('#idSPKOWorkshop').val()
            let idMasterGambarTeknik = $('#idMasterGambarTeknik').val()
            let idEmployee = $('#employee').val()
            let catatan = $('#catatan').val()

            if (idSPKOMatras == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "SPKO Matras Belum Terpilih",
                })
                return;
            }

            if (idMasterGambarTeknik == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Gambar Teknik Tidak Boleh Kosong",
                })
                return;
            }

            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return;
            }
            
            // set data to json
            let data = {
                idSPKOMatras:idSPKOMatras,
                idEmployee:idEmployee,
                idMasterGambarTeknik:idMasterGambarTeknik,
                catatan:catatan
            }

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/Workshop/SPKO/Matras',
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
                    // Disable button "Baru, Ubah and Posting"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_posting").prop('disabled',false)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Disable Cetak
                    $("#btn_cetak").prop('disabled',true)
                    // Disable input
                    $("#idMasterGambarTeknik").prop('disabled',true)
                    $("#employee").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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

        function KlikPosting() {
            let idSPKOMatras = $('#idSPKOWorkshop').val()
            let postingStatus = $('#postingStatus').val()
            if (idSPKOMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "SPKO Belum Terpilih",
                })
                return;
            }
            if (postingStatus != "A") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Transaksi Sudah Terposting",
                })
                return;
            }
            // Posting SPKO
            $.ajax({
                url: '/Workshop/SPKO/Matras/posting',
                type: 'GET',
                data:{idSPKOMatras:idSPKOMatras},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#action').val('ubah')
                    $('#postingStatus').val('P')

                    // Disable button "Baru, Ubah, and Posting"
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',true)
                    $("#btn_posting").prop('disabled',true)
                    // Disable Button "Batal dan Simpan"
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    // Disable Cetak
                    $("#btn_cetak").prop('disabled',false)
                    // Disable input
                    $("#idMasterGambarTeknik").prop('disabled',true)
                    $("#employee").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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
            $(".preloader").show(); 
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
                data:{idSPKOMatras:keyword},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    console.log(data);
                    // return
                    $('#idSPKOWorkshop').val(data.data.matrasAllocation.ID)
                    $('#postingStatus').val(data.data.matrasAllocation.Active)
                    $('#action').val('ubah')

                    // Set Header Value
                    $('#tanggalNow').val(data.data.matrasAllocation.TransDate)
                    $("#employee").val(data.data.matrasAllocation.Employee).change()
                    $("#idMasterGambarTeknik").val(data.data.matrasAllocation.IDMasterGambarTeknik)
                    $("#catatan").val(data.data.Remarks)

                    $("#tabel1  tbody").empty()
                    $("#tabel1 > tbody").append(data.data.itemsHTML);
                    
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    if (data.data.matrasAllocation.Active == 'A') {
                        $("#btn_posting").prop('disabled',false)
                        $("#btn_ubah").prop('disabled',false)
                        $("#btn_cetak").prop('disabled',true)
                    }else{
                        $("#btn_posting").prop('disabled',true)
                        $("#btn_ubah").prop('disabled',true)
                        $("#btn_cetak").prop('disabled',false)
                    }

                    // Disable input
                    $("#tanggalNow").prop('disabled',true)
                    $("#employee").prop('disabled',true)
                    $('#idMasterGambarTeknik').prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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
            window.open('/Workshop/SPKO/Matras/cetak?idSPKOMatras='+idSPKOWorkshop, '_blank');
            return
        }
    </script>
    

@endsection
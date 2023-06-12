<?php $title = 'Laboratorium Xray'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Laboratorium </li>
        <li class="breadcrumb-item active">Laboratorium Xray</li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Produksi.Laboratorium.LaboratoriumXray.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function KlikBaru() {
        // Getting LabTransactionID from hidden input
        let LabTransactionID = $('#LabTransactionID').val()
        // Check if LabTransactionID have value
        if (LabTransactionID != "") {
            // If LabTransactionID have value. It will reload th page
            window.location.reload()
            return;
        }
        // Disable button "Baru"
        $("#btn_baru").prop('disabled',true)
        $("#btn_ubah").prop('disabled',true)
        $("#btn_cetak").prop('disabled',true)
        // Enable Button "Batal dan Simpan"
        $("#btn_simpan").prop('disabled',true)
        $("#btn_batal").prop('disabled',false)
        // Enable file and buttonCheck
        $("#workallocation").prop('disabled',false)
        $("#file").prop('disabled',false)
        $('#TestResult').prop('disabled',false)
        $('#btn_check').prop('disabled',false)
    }

    function klikUbah() {
        // Disable button "Baru"
        $("#btn_baru").prop('disabled',true)
        $("#btn_ubah").prop('disabled',true)
        $("#btn_cetak").prop('disabled',true)
        // Enable Button "Batal dan Simpan"
        $("#btn_simpan").prop('disabled',false)
        $("#btn_batal").prop('disabled',false)
        // Disable file and buttonCheck
        $("#workallocation").prop('disabled',false)
        $("#file").prop('disabled',true)
        $('#TestResult').prop('disabled',true)
        $('#btn_check').prop('disabled',true)
    }

    function klikBatal() {
        window.location.reload()
        return;
    }

    function klikCheck() {
        let workallocation = $('#workallocation').val()
        if (workallocation == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Nomor NTHKO Lebur tidak boleh kosong",
            })
            return
        }
        var data = new FormData()
        if ($('#file').get(0).files.length === 0) {
            let TestResult = $('#TestResult').val()
            if (TestResult == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Failed. Test Result Empty. Please Fill Test Result or select File.",
                })
                return
            } 
            data.append('TestResult', TestResult)
            data.append('inputType', 'text')
            data.append('workallocation', workallocation)
        }else{
            // Check with File
            let file = $('#file').prop('files')[0]
            data = new FormData()
            data.append('file', file)
            data.append('inputType', 'file')
            data.append('workallocation', workallocation)
        }
        // Setup CSRF TOKEN
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/Produksi/Laboratorium/LaboratoriumXray/check",
            data:data,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {
                $('#LabView').html(data.html)
                $("#workallocation").prop('disabled',true)
                $("#file").prop('disabled',true)
                $('#TestResult').prop('disabled',true)
                $("#btn_check").prop('disabled',true)
                $("#btn_simpan").prop('disabled',false)
                return;
            },
            error: function(xhr){
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                // It will executed if response from backend is error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        })
    }

    function klikSimpan() {
        let action = $('#action').val()
        if (action == 'simpan') {
            SimpanLaboratoriumXray()
            return
        } else if (action == 'ubah') {
            UbahLaboratoriumXray()
            return
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "action invalid",
            })
            return;
        }
        
    }

    function SimpanLaboratoriumXray() {
        let workallocation = $('#workallocation').val()
        if (workallocation == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Nomor NTHKO Lebur tidak boleh kosong",
            })
            return
        }
        var data = new FormData()
        if ($('#file').get(0).files.length === 0) {
            let TestResult = $('#TestResult').val()
            if (TestResult == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Failed. Test Result Empty. Please Fill Test Result or select File.",
                })
                return
            } 
            data.append('TestResult', TestResult)
            data.append('inputType', 'text')
            data.append('workallocation', workallocation)
        }else{
            // Check with File
            let file = $('#file').prop('files')[0]
            data = new FormData()
            data.append('file', file)
            data.append('inputType', 'file')
            data.append('workallocation', workallocation)
        }
        // Setup CSRF TOKEN
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "/Produksi/Laboratorium/LaboratoriumXray",
            data:data,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {
                $("#workallocation").prop('disabled',true)
                $("#file").prop('disabled',true)
                $("#buttonCheck").prop('disabled',true)
                $('#btn_baru').prop('disabled',false)
                $('#btn_cetak').prop('disabled',false)
                $('#btn_ubah').prop('disabled',false)
                $('#btn_simpan').prop('disabled',true)
                $('#btn_batal').prop('disabled',true)
                $('#LabTransactionID').val(data.LabTransactionID)
                $('#action').val('ubah')
            },
            error: function(xhr){
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                // It will executed if response from backend is error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        })
    }

    function UbahLaboratoriumXray() {
        let LabTransactionID = $('#LabTransactionID').val()
        let nomorNTHKO = $('#workallocation').val()
    
        // Check if LabTransactionID is not null or blank
        if (LabTransactionID == null || LabTransactionID == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "ID Laboratorium belum terpilih",
            })
            return;
        }

        // Check if nomorNTHKO not null or blank
        if (nomorNTHKO == null || nomorNTHKO == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Nomor NTHKO Tidak Boleh Kosong",
            })
            return;
        }

        let data = {
            "LabTransactionID":LabTransactionID,
            "nomorNTHKO":nomorNTHKO
        }

        // Setup CSRF TOKEN
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT",
            url: "/Produksi/Laboratorium/LaboratoriumXray",
            data:data,
            dataType: 'json',
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {
                $("#workallocation").prop('disabled',true)
                $("#file").prop('disabled',true)
                $("#buttonCheck").prop('disabled',true)
                $('#btn_baru').prop('disabled',false)
                $('#btn_cetak').prop('disabled',false)
                $('#btn_ubah').prop('disabled',false)
                $('#btn_simpan').prop('disabled',true)
                $('#btn_batal').prop('disabled',true)
                $('#LabView').html(data.data)
            },
            error: function(xhr){
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                // It will executed if response from backend is error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        })
    }

    function CariLabTest() {
        let keyword = $('#cari').val()
        if (keyword == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Search value can't be blank",
            })
            return;
        }

        $.ajax({
            type: "GET",
            url: "/Produksi/Laboratorium/LaboratoriumXray/cari/"+keyword,
            dataType: 'json',
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {
                // Set Hidden LabTransactionID value
                $('#LabTransactionID').val(data.LabTransactionID)
                // Set button cetak to enable
                $('#btn_cetak').prop('disabled',false)
                // set button baru to enable
                $('#btn_baru').prop('disabled',false)
                $("#file").prop('disabled',true)
                $("#buttonCheck").prop('disabled',true)
                $('#btn_simpan').prop('disabled',true)
                $('#btn_batal').prop('disabled',true)
                $('#btn_ubah').prop('disabled',false)
                $('#action').val('ubah')

                $('#LabView').html(data.html)
                return;
            },
            error: function(xhr){
                // Clear Table
                $('#LabView').html(" ")
                // Set input and button
                $('#cari').val("")
                // set tanggal
                $("#file").prop('disabled',false)
                $("#buttonCheck").prop('disabled',false)
                // Set button
                $('#btn_cetak').prop('disabled',true)
                $('#btn_simpan').prop('disabled',true)
                $('#btn_batal').prop('disabled',true)
                $('#btn_baru').prop('disabled',false)
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                // It will executed if response from backend is error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        })
    }

    function klikCetak() {
        let LabTransactionID = $('#LabTransactionID').val()
        if (LabTransactionID !== ''){
            window.open('/Produksi/Laboratorium/LaboratoriumXray/cetak/'+LabTransactionID, '_blank');
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'LabTransactionID belum terpilih',
            })
            return
        }
    }

</script>
@endsection
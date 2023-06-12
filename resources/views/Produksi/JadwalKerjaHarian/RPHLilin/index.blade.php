<?php $title = 'RPH Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Jadwal Kerja Harian </li>
        <li class="breadcrumb-item active">RPH Lilin </li>
    </ol>
@endsection

@section('css')

    <style>
        .dx-datagrid-headers{
            background-color: #D7F5FC;
            text-align: center!important; 
        }
        .dx-datagrid{  
            font-style: verdana; 
            font-size: 10px; 
        }  
        .dx-datagrid-action.dx-cell-focus-disabled {
            text-align: center !important;
            vertical-align: middle !important;
        }   
        .dx-data-row td.cls {  
            text-align: center!important;  
        }   
        .dx-data-row td.lss {  
            text-align: right!important;  
        }  
        tbody{
            color: black;
        }
    </style>
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Produksi.JadwalKerjaHarian.RPHLilin.data')

            </div>
        </div>
    </div>
    
    <div class="dropdown-menu dropdown-menu-end animate" id="klikMenu" style="display:none">
        <div class="text-center fw-bold mb-2" id="klikJudul"></div>
        <a class="dropdown-item" onclick="klikEdit()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" onclick="klikCetak()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
        <a class="dropdown-item" onclick="klikInfo()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a>
    </div> 
   
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>

        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }

        function klikClear(){
            document.getElementById("idcari").value = '';
            document.getElementById("idcari").focus();
        }

        function klikBatal(){
            location.reload();
        }

        function klikBaru() {
            var cek = document.getElementById("idmnya1").value;
            if (cek != '') {
                location.reload(true);
            } else {
                document.getElementById("btnsimpan").disabled = false;
                document.getElementById("btnbatal").disabled = false;
                document.getElementById("btnbaru").disabled = true;
                document.getElementById("tglRPH").disabled = false;
                document.getElementById("catatan").disabled = false;
                document.getElementById("btnform").disabled = false;
                document.getElementById("btnposting").disabled = true;
                document.getElementById("btnform").disabled = false;
                // $("#btnsimpan").removeClass("btn btn-info").addClass("btn btn-warning");
                // $("#btnbatal").removeClass("btn btn-info").addClass("btn btn-danger");
            }
        }

        function klikForm(){
            var update = $('#update').val();
            var idmnya1 = $('#idmnya1').val();
            var pilih = $('#pilih').val();
            var tgl = $('#tglRPH').val();
            var jenis = $('#jenis').val();

            // if(jenis == 1){
            //     var dataUrl = '/Produksi/JadwalKerjaHarian/RPHLilin/daftarListPPIC';
            // }else if(jenis == 2){
            //     var dataUrl = '/Produksi/JadwalKerjaHarian/RPHLilin/daftarListDC';
            // }

            data = {update: update, idmnya1: idmnya1, pilih: pilih, tgl: tgl, jenis: jenis};

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHLilin/daftarList',
                beforeSend: function(){
                    openModal();
                },          
                data : data,
                dataType : 'json',
                type : 'POST',
                success: function(data)
                {
                    var dataGrid = $("#tampil").dxDataGrid({
                        dataSource: data.datalist,
                        keyExpr: "ID",
                        columnsAutoWidth: true,
                        width: '100%',
                        height: 550,
                        allowColumnReordering: true,
                        showBorders: true,
                        headerFilter: {
                            visible: true
                        },
                        rowAlternationEnabled: true,
                        allowColumnResizing: true,     
                        selection: {
                            mode: "multiple",
                            allowSelectAll: true,
                            selectAllMode: 'page' // or "multiple" | "none"
                        },
                        searchPanel: {
                            visible: true
                        },
                        paging: {
                            enabled: false
                        },
                        grouping: {
                            autoExpandAll: false
                        },
                        groupPanel: {
                            visible: true
                        },
                        "export": {
                            enabled: true,
                            fileName: "RPH Lilin",
                            allowExportSelectedData: true
                        },
                        columns: [{
                                dataField: "SW",
                                dataType: "string",
                                caption: "SPK",
                                width: '15%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "TransDate",
                                sortIndex: 0,
                                sortOrder: "asc",
                                dataType: "date",
                                format: 'dd-MM-yyyy',
                                caption: "Tanggal",
                                width: '15%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Category",
                                dataType: "string",
                                caption: "Kategori",
                                width: '10%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Model",
                                dataType: "string",
                                caption: "Sub Kategori",
                                width: '10%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Product",
                                dataType: "string",
                                caption: "Kode Produk",
                                width: '30%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Karat",
                                dataType: "string",
                                caption: "Kadar",
                                width: '10%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Qty",
                                dataType: "number",
                                caption: "Qty",
                                width: '10%',
                                cssClass: "lss"
                            },
                            // {
                            //     dataField: "IDM",
                            //     dataType: "string",
                            //     caption: "ID",
                            //     width: '0%',
                            //     cssClass: "cls"
                            // },
                            // {
                            //     dataField: "Karat",
                            //     groupIndex: 0
                            // },
                        ],
                        summary: {
                            groupItems: [{
                                column: "Qty",
                                displayFormat: "Jumlah Qty : {0}",
                                summaryType: "sum",
                                valueFormat: "fixedPoint",
                                precision: "2"

                            }],
                            totalItems: [{
                                name: "SelectedRowsSummary",
                                showInColumn: "Qty",
                                displayFormat: "Tot : {0}",
                                summaryType: "custom",
                                valueFormat: "decimal"
                            }],
                            calculateCustomSummary: function(options) {
                                if (options.name === "SelectedRowsSummary") {
                                    if (options.summaryProcess === "start") {
                                        options.totalValue = 0;
                                    }
                                    if (options.summaryProcess === "calculate") {
                                        if (options.component.isRowSelected(options.value.ID)) {
                                            const count = parseFloat(options.value.Qty);
                                            options.totalValue = options.totalValue + count;
                                        }
                                    }
                                }
                            }
                        },
                        onSelectionChanged: function(selectedItems) {
                            var data = selectedItems.selectedRowsData;
                            if (data.length > 0)
                                $("#pilih").val(
                                    $.map(data, function(value) {
                                        return value.IDM;
                                    }).join(","));
                            selectedItems.component.refresh(true);
                        }
                    }).dxDataGrid("instance");
                    $("html, body").animate({
                        scrollTop: $(
                            'html, body').get(0).scrollHeight
                    }, 2000);
                    if (data.update > 0) {
                        $("#update").val('update1');
                    }
                },
                complete: function(){
                    closeModal();
                },      
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }       
            });  
	    }


        function cekSPK(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHLilin/cekSPK',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if(data.status == 'Sukses'){
                        klikSimpan();
                        // console.log('Sukses');
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Cek SPK',
                            text: "No SPK Tidak Boleh Campur Antara Awalan 'O' dan Selain 'O' ",
                            // timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return; 
                }
            });
        }
        

        function klikSimpan() {
            var cek = document.getElementById("update").value;

            if(cek == ''){
                klik_simpan();
            }else if(cek == 'update1') {
                klik_update1();
            }else{
                klik_update();
            }
        }

        function klik_simpan() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHLilin/simpan',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if(data.status == 'OK'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: "Data Berhasil Disimpan.",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        
                        $("#update").val(data.update);
                        $("#idmnya1").val(data.idmnya);
                        $("#idmnya").html(data.idmnya);
                        $("#idcari").val(data.idmnya);
                        // $("#proses").val(data.proses);
                        document.getElementById("btnposting").disabled = false;
                        document.getElementById("btnform").disabled = true;
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klik_update() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHLilin/update',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: "Data Berhasil Diupdate.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    $("#update").val(data.update);
                    $("#idmnya1").val(data.idmnya);
                    $("#idmnya").html(data.idmnya);
                    $("#idcari").val(data.idmnya);
                    // $("#proses").val(data.proses);
                    document.getElementById("btnposting").disabled = false;
                    document.getElementById("btnform").disabled = true;
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klik_update1() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHLilin/update1',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: "Data Berhasil Diupdate.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    $("#update").val(data.update);
                    $("#idmnya1").val(data.idmnya);
                    $("#idmnya").html(data.idmnya);
                    $("#idcari").val(data.idmnya);
                    // $("#proses").val(data.proses);
                    document.getElementById("btnposting").disabled = false;
                    document.getElementById("btnform").disabled = true;
                },

                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });
        }

        function klikPosting() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHLilin/posting',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: "Data Berhasil Diposting.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    if (data.active === 'P') {
                        document.getElementById("btnsimpan").disabled = true;
                        document.getElementById("btnbatal").disabled = false;
                        document.getElementById("btnbaru").disabled = true;
                        document.getElementById("tglRPH").disabled = true;
                        document.getElementById("catatan").disabled = true;
                        document.getElementById("btnform").disabled = true;
                        document.getElementById("btnubah").disabled = true;
                        document.getElementById("btnposting").disabled = true;
                        document.getElementById("btncetak").disabled = false;
                        // $("#btnsimpan").removeClass("btn btn-warning").addClass("btn btn-warning");
                        // $("#btnbatal").removeClass("btn btn-danger").addClass("btn btn-info");
                    }

                    var dataGrid = $("#tampil").dxDataGrid({
                        dataSource: data.datalist,
                        keyExpr: "ID",
                        columnsAutoWidth: true,
                        width: '100%',
                        height: 550,
                        allowColumnReordering: true,
                        showBorders: true,
                        headerFilter: {
                            visible: true
                        },
                        rowAlternationEnabled: true,
                        allowColumnResizing: true,    
                        selection: {
                            mode: "none",
                            allowSelectAll: true,
                            selectAllMode: 'page' // or "multiple" | "none"
                        },
                        searchPanel: {
                            visible: true
                        },
                        paging: {
                            enabled: false
                        },
                        grouping: {
                            autoExpandAll: true
                        },
                        groupPanel: {
                            visible: true
                        },
                        "export": {
                            enabled: true,
                            fileName: "Rencana Kerja Harian",
                            allowExportSelectedData: true
                        },
                        columns: [{
                                dataField: "SW",
                                dataType: "string",
                                caption: "SPK",
                                width: '15%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "TransDate",
                                sortIndex: 0,
                                sortOrder: "asc",
                                dataType: "date",
                                format: 'dd-MM-yyyy',
                                caption: "Tanggal",
                                width: '15%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Category",
                                dataType: "string",
                                caption: "Kategori",
                                width: '10%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Model",
                                dataType: "string",
                                caption: "Sub Kategori",
                                width: '10%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Product",
                                dataType: "string",
                                caption: "Kode Produk",
                                width: '30%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Karat",
                                dataType: "string",
                                caption: "Kadar",
                                width: '10%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Qty",
                                dataType: "number",
                                caption: "Qty",
                                width: '10%',
                                cssClass: "lss"
                            },
                            {
                                dataField: "IDM",
                                dataType: "string",
                                caption: "ID",
                                width: '0%',
                                cssClass: "cls"
                            },
                            {
                                dataField: "Karat",
                                groupIndex: 0
                            },
                        ],
                        summary: {
                            groupItems: [{
                                column: "Qty",
                                displayFormat: "Jumlah Qty : {0}",
                                summaryType: "sum",
                                valueFormat: "fixedPoint",
                                precision: "2"

                            }],
                            totalItems: [{
                                name: "SelectedRowsSummary",
                                showInColumn: "Qty",
                                displayFormat: "Tot : {0}",
                                summaryType: "custom",
                                valueFormat: "decimal"
                            }],
                            calculateCustomSummary: function(options) {
                                if (options.name === "SelectedRowsSummary") {
                                    if (options.summaryProcess === "start") {
                                        options.totalValue = 0;
                                    }
                                    if (options.summaryProcess === "calculate") {
                                        if (options.component.isRowSelected(options.value.ID)) {
                                            const count = parseFloat(options.value.Qty);
                                            options.totalValue = options.totalValue + count;
                                        }
                                    }
                                }
                            }
                        },
                        onSelectionChanged: function(selectedItems) {
                            var data = selectedItems.selectedRowsData;
                            if (data.length > 0)
                                $("#pilih").val(
                                    $.map(data, function(value) {
                                        return value.IDM;
                                    }).join(","));
                            selectedItems.component.refresh(true);
                        }
                    }).dxDataGrid("instance");
                    $("html, body").animate({
                        scrollTop: $(
                            'html, body').get(0).scrollHeight
                    }, 2000);
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return; 
                }
            });
	    }

        function klikLihat() {
            var x = document.getElementById("idcari").value;
            var jenis = document.getElementById("jenis").value; 
            data = {
                dropdownValue: x,
                jenis: jenis
            };
            if(x == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Oopps...!',
                    text: 'ID pencarian kosong',
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/JadwalKerjaHarian/RPHLilin/lihat',
                    beforeSend: function(){
                        openModal();
                    },
                    data: data,
                    dataType: "json",
                    type: 'POST',
                    success: function(data) {
                        $("#update").val(data.update);
                        $("#idmnya1").val(data.ID);
                        $("#idmnya").html(data.ID);
                        $("#idcari").val(data.ID);
                        $("#tglRPH").val(data.tglRPH);
                        $("#catatan").val(data.catatan);
                        // $("#proses").val(data.proses);
                        if (data.active === 'P') {
                            document.getElementById("btnsimpan").disabled = true;
                            document.getElementById("btnbatal").disabled = false;
                            document.getElementById("btnbaru").disabled = false;
                            document.getElementById("tglRPH").disabled = true;
                            document.getElementById("btncetak").disabled = false;
                            document.getElementById("catatan").disabled = true;
                            document.getElementById("btnform").disabled = true;
                            document.getElementById("btnposting").disabled = true;
                            // $("#btnsimpan").removeClass("btn btn-warning").addClass("btn btn-warning");
                            // $("#btnbatal").removeClass("btn btn-danger").addClass("btn btn-info");
                        } else {
                            document.getElementById("btnsimpan").disabled = true;
                            document.getElementById("btnbatal").disabled = false;
                            document.getElementById("btnbaru").disabled = true;
                            document.getElementById("btnubah").disabled = false;
                            document.getElementById("tglRPH").disabled = true;
                            document.getElementById("catatan").disabled = true;
                            document.getElementById("btnform").disabled = true;
                            document.getElementById("btnposting").disabled = false;
                            // $("#btnubah").removeClass("btn btn-info").addClass("btn btn-warning");
                            // $("#btnbatal").removeClass("btn btn-info").addClass("btn btn-danger");
                            Swal.fire({
                                icon: 'warning',
                                title: 'Tersimpan!',
                                text: "Data Belum Diposting.",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                        }

                        var dataGrid = $("#tampil").dxDataGrid({
                            dataSource: data.datalist,
                            keyExpr: "ID",
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 550,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: {
                                visible: true
                            },
                            rowAlternationEnabled: true,
                            allowColumnResizing: true,       
                            selection: {
                                mode: "multiple",
                                allowSelectAll: true,
                                selectAllMode: 'page' // or "multiple" | "none"
                            },
                            searchPanel: {
                                visible: true
                            },
                            paging: {
                                enabled: false
                            },
                            grouping: {
                                autoExpandAll: true
                            },
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "Rencana Kerja Harian",
                                allowExportSelectedData: true
                            },
                            columns: [{
                                    dataField: "SW",
                                    dataType: "string",
                                    caption: "SPK",
                                    width: '15%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "TransDate",
                                    sortIndex: 0,
                                    sortOrder: "asc",
                                    dataType: "date",
                                    format: 'dd-MM-yyyy',
                                    caption: "Tanggal",
                                    width: '14%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Category",
                                    dataType: "string",
                                    caption: "Kategori",
                                    width: '10%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Model",
                                    dataType: "string",
                                    caption: "Sub Kategori",
                                    width: '10%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Product",
                                    dataType: "string",
                                    caption: "Kode Produk",
                                    width: '30%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Karat",
                                    dataType: "string",
                                    caption: "Kadar",
                                    width: '10%',
                                    cssClass: "cls",
                                    groupIndex: 0
                                },
                                {
                                    dataField: "Qty",
                                    dataType: "number",
                                    caption: "Qty",
                                    width: '10%',
                                    cssClass: "lss"
                                },
                                // {
                                //     dataField: "IDM",
                                //     dataType: "string",
                                //     caption: "ID",
                                //     width: '0%',
                                //     cssClass: "cls"
                                // },
                                // {
                                //     dataField: "Karat",
                                //     groupIndex: 0
                                // },
                            ],
                            summary: {
                                groupItems: [{
                                    column: "Qty",
                                    displayFormat: "Jumlah Qty : {0}",
                                    summaryType: "sum",
                                    valueFormat: "fixedPoint",                
                                    precision: "2"				
                                }],
                                totalItems: [{
                                    name: "SelectedRowsSummaryccc",
                                    showInColumn: "Qty",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal"
                                }],
                                calculateCustomSummary: function(options) {
                                    if (options.name === "SelectedRowsSummaryccc") {
                                        if (options.summaryProcess === "start") {
                                            options.totalValue = 0;
                                        }
                                        if (options.summaryProcess === "calculate") {
                                            if (options.component.isRowSelected(options.value.ID)) {
                                                const count = parseFloat(options.value.Qty);
                                                options.totalValue = options.totalValue + count;
                                            }
                                        }
                                    }
                                }
                            },
                            onSelectionChanged: function(selectedItems) {
                                var data = selectedItems.selectedRowsData;
                                if (data.length > 0)
                                    $("#pilih").val(
                                        $.map(data, function(value) {
                                            return value.IDM;
                                        }).join(","));
                                selectedItems.component.refresh(true);
                            }
                        }).dxDataGrid("instance");
                        $("html, body").animate({
                            scrollTop: $(
                                'html, body').get(0).scrollHeight
                        }, 2000);
                    },
                    complete: function(){
                        closeModal();
                    },
                });
            }
        };


        function klikUbah() {
            document.getElementById("btnsimpan").disabled = false;
            document.getElementById("btnbatal").disabled = false;
            document.getElementById("btnbaru").disabled = true;
            document.getElementById("btnubah").disabled = true;
            document.getElementById("tglRPH").disabled = false;
            document.getElementById("catatan").disabled = false;
            document.getElementById("btnposting").disabled = false;
            document.getElementById("btnform").disabled = false;
            // $("#btnsimpan").removeClass("btn btn-info").addClass("btn btn-warning");
            // $("#btnbatal").removeClass("btn btn-info").addClass("btn btn-danger");
        }

        function klikCetak() {
            var idlihatkan = $("#idmnya1").val();
            var jenis = $("#jenis").val();
            var dataUrl = `/Produksi/JadwalKerjaHarian/RPHLilin/cetak?idrph=${idlihatkan}&jenis=${jenis}`;
            window.open(dataUrl, '_blank');
        }

        function fillRemarks(){
            var jenis = $("#jenis").val();
            $("#catatan").val(jenis);
        }

    </script>

@endsection

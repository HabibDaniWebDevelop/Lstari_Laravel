<?php $title = 'RPH Produksi'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Jadwal Kerja Harian </li>
        <li class="breadcrumb-item active">RPH Produksi </li>
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

                @include('Produksi.JadwalKerjaHarian.RPHProduksi.data')

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
                document.getElementById("proses").disabled = false;
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
            var proses = $('#proses').val();

            if(proses == ''){
                alert('Jenis Proses Wajib Dipilih');
            }else{

                data = {update: update, idmnya1: idmnya1, pilih: pilih, tgl: tgl, proses: proses};
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/JadwalKerjaHarian/RPHProduksi/daftarList',
                    beforeSend: function(){
                        openModal();
                    },          
                    data : data,
                    dataType : 'json',
                    type : 'POST',
                    success: function(data)
                    {
                        if(data.location == 47){

                            var dataGrid = $("#tampil").dxDataGrid({
                                dataSource: data.datalist,
                                keyExpr: "ID",
                                columnAutoWidth: true,
                                width: '100%',
                                height: 550,
                                allowColumnReordering: true,
                                showBorders: true,
                                headerFilter: {
                                    visible: true
                                },
                                rowAlternationEnabled: true,
                                filterRow: {
                                    visible: true,
                                    applyFilter: "auto"
                                },   
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
                                    fileName: "RPH Produksi",
                                    allowExportSelectedData: true
                                },
                                columns: [{
                                        dataField: "WS",
                                        dataType: "string",
                                        caption: "NTHKO",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Kadar",
                                        dataType: "string",
                                        caption: "Kadar",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "TransDate",
                                        sortIndex: 0,
                                        sortOrder: "asc",
                                        dataType: "date",
                                        format: 'dd-MM-yyyy',
                                        caption: "Tgl",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "TMno",
                                        dataType: "string",
                                        caption: "TM",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "WOSW",
                                        dataType: "string",
                                        caption: "SPK",
                                        width: '8%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "SubCategory",
                                        dataType: "string",
                                        caption: "SubKategori",
                                        width: '8%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Dari",
                                        dataType: "string",
                                        caption: "Dari",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "OPDescription",
                                        dataType: "string",
                                        caption: "Proses",
                                        width: '8%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "EnmSurfaceST",
                                        dataType: "string",
                                        caption: "Bidang",
                                        width: '5%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "EnmStepST",
                                        dataType: "string",
                                        caption: "Step",
                                        width: '5%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "EnmColorST",
                                        dataType: "string",
                                        caption: "Warna",
                                        width: '5%',
                                        cssClass: "cls"
                                    }, 
                                    {
                                        dataField: "Qty",
                                        dataType: "number",
                                        caption: "Qty",
                                        width: '4%',
                                        cssClass: "lss"
                                    },
                                    {
                                        dataField: "Pcs",
                                        dataType: "number",
                                        caption: "Pcs",
                                        width: '4%',
                                        cssClass: "lss"
                                    },
                                    {
                                        dataField: "Weight",
                                        dataType: "number",
                                        caption: "Berat",
                                        width: '6%',
                                        cssClass: "lss",
                                        format: "#,##0.00"
                                    },
                                    {
                                        dataField: "Kategori",
                                        dataType: "string",
                                        caption: "Kategori",
                                        width: '7%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Note",
                                        dataType: "string",
                                        caption: "Note",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Kadar",
                                        groupIndex: 0
                                    },
                                    {
                                        dataField: "Kategori",
                                        groupIndex: 1
                                    },

                                ],
                                summary: {
                                    groupItems: [{
                                        column: "Qty",
                                        displayFormat: "Total Qty : {0}",
                                        summaryType: "sum",
                                    }, {
                                        column: "Pcs",
                                        displayFormat: "Total Pcs : {0}",
                                        summaryType: "sum"

                                    }, {
                                        column: "Weight",
                                        displayFormat: "Total Berat : {0}",
                                        summaryType: "sum",
                                        valueFormat: "#,##0.00"
                                    }],
                                    totalItems: [{
                                        name: "SelectedRowsSummary",
                                        showInColumn: "Qty",
                                        displayFormat: "Tot : {0}",
                                        summaryType: "custom"
                                    }, {
                                        name: "SelectedRowsSummary1",
                                        showInColumn: "Pcs",
                                        displayFormat: "Tot : {0}",
                                        summaryType: "custom"
                                    }, {
                                        name: "SelectedRowsSummary2",
                                        showInColumn: "Weight",
                                        displayFormat: "Tot : {0}",
                                        summaryType: "custom",
                                        valueFormat: "#,##0.00"
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
                                        if (options.name === "SelectedRowsSummary1") {
                                            if (options.summaryProcess === "start") {
                                                options.totalValue = 0;
                                            }
                                            if (options.summaryProcess === "calculate") {
                                                if (options.component.isRowSelected(options.value.ID)) {
                                                    const count = parseFloat(options.value.Pcs);
                                                    options.totalValue = options.totalValue + count;
                                                }
                                            }
                                        }
                                        if (options.name === "SelectedRowsSummary2") {
                                            if (options.summaryProcess === "start") {
                                                options.totalValue = 0;
                                            }
                                            if (options.summaryProcess === "calculate") {
                                                if (options.component.isRowSelected(options.value.ID)) {
                                                    const count = parseFloat(options.value.Weight);
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
                                                return value.kirimkan;
                                            }).join(","));
                                    selectedItems.component.refresh(true);
                                },
                            }).dxDataGrid("instance");
                            $("html, body").animate({
                                scrollTop: $(
                                    'html, body').get(0).scrollHeight
                            }, 2000);
                            if (data.update > 0) {
                                $("#update").val('update1');
                            }

                        }else{

                            var dataGrid = $("#tampil").dxDataGrid({
                                dataSource: data.datalist,
                                keyExpr: "ID",
                                columnAutoWidth: true,
                                width: '100%',
                                height: 550,
                                allowColumnReordering: true,
                                showBorders: true,
                                headerFilter: {
                                    visible: true
                                },
                                rowAlternationEnabled: true,
                                filterRow: {
                                    visible: true,
                                    applyFilter: "auto"
                                }, 
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
                                    fileName: "RPH Produksi",
                                    allowExportSelectedData: true
                                },
                                columns: [{
                                        dataField: "WS",
                                        dataType: "string",
                                        caption: "NTHKO",
                                        width: '8%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Kadar",
                                        dataType: "string",
                                        caption: "Kadar",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "TransDate",
                                        sortIndex: 0,
                                        sortOrder: "asc",
                                        dataType: "date",
                                        format: 'dd-MM-yyyy',
                                        caption: "Tgl",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "TMno",
                                        dataType: "string",
                                        caption: "TM",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "WOSW",
                                        dataType: "string",
                                        caption: "SPK",
                                        width: '10%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "SubCategory",
                                        dataType: "string",
                                        caption: "SubKategori",
                                        width: '8%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Dari",
                                        dataType: "string",
                                        caption: "Dari",
                                        width: '6%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "OperationProcess", 
                                        dataType: "number", 
                                        caption: "Proses", 
                                        width: '8%', 
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Level2Process", 
                                        dataType: "number", 
                                        caption: "SubProses", 
                                        width: '8%', 
                                        cssClass: "cls"
                                    }, 
                                    {
                                        dataField: "Qty",
                                        dataType: "number",
                                        caption: "Qty",
                                        width: '6%',
                                        cssClass: "lss"
                                    },
                                    {
                                        dataField: "Pcs",
                                        dataType: "number",
                                        caption: "Pcs",
                                        width: '6%',
                                        cssClass: "lss"
                                    },
                                    {
                                        dataField: "Weight",
                                        dataType: "number",
                                        caption: "Berat",
                                        width: '7%',
                                        cssClass: "lss",
                                        format: "#,##0.00"
                                        // format: {type: 'decimal', precision: 2},
                                    },
                                    {
                                        dataField: "Kategori",
                                        dataType: "string",
                                        caption: "Kategori",
                                        width: '7%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Note",
                                        dataType: "string",
                                        caption: "Note",
                                        width: '8%',
                                        cssClass: "cls"
                                    },
                                    {
                                        dataField: "Kadar",
                                        groupIndex: 0
                                    },
                                    {
                                        dataField: "Kategori",
                                        groupIndex: 1
                                    },
                                ],
                                summary: {
                                    groupItems: [{
                                        column: "Qty",
                                        displayFormat: "Total Qty : {0}",
                                        summaryType: "sum"
                                    }, {
                                        column: "Pcs",
                                        displayFormat: "Total Pcs : {0}",
                                        summaryType: "sum"
                                    }, {
                                        column: "Weight",
                                        displayFormat: "Total Berat : {0}",
                                        summaryType: "sum",
                                        valueFormat: "#,##0.00"
                                    }],
                                    totalItems: [{
                                        name: "SelectedRowsSummary",
                                        showInColumn: "Qty",
                                        displayFormat: "Tot : {0}",
                                        summaryType: "custom",
                                        valueFormat: "decimal"
                                    }, {
                                        name: "SelectedRowsSummary1",
                                        showInColumn: "Pcs",
                                        displayFormat: "Tot : {0}",
                                        summaryType: "custom",
                                        valueFormat: "decimal"
                                    }, {
                                        name: "SelectedRowsSummary2",
                                        showInColumn: "Weight",
                                        displayFormat: "Tot : {0}",
                                        summaryType: "custom",
                                        valueFormat: "decimal",
                                        valueFormat: "#,##0.00"
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
                                        if (options.name === "SelectedRowsSummary1") {
                                            if (options.summaryProcess === "start") {
                                                options.totalValue = 0;
                                            }
                                            if (options.summaryProcess === "calculate") {
                                                if (options.component.isRowSelected(options.value.ID)) {
                                                    const count = parseFloat(options.value.Pcs);
                                                    options.totalValue = options.totalValue + count;
                                                }
                                            }
                                        }
                                        if (options.name === "SelectedRowsSummary2") {
                                            if (options.summaryProcess === "start") {
                                                options.totalValue = 0;
                                            }
                                            if (options.summaryProcess === "calculate") {
                                                if (options.component.isRowSelected(options.value.ID)) {
                                                    const count = parseFloat(options.value.Weight);
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
                                                return value.kirimkan;
                                            }).join(","));
                                    selectedItems.component.refresh(true);
                                },
                            }).dxDataGrid("instance");
                            $("html, body").animate({
                                scrollTop: $(
                                    'html, body').get(0).scrollHeight
                            }, 2000);
                            if (data.update > 0) {
                                $("#update").val('update1');
                            }
                            
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
	    }



        function cekSPK(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/JadwalKerjaHarian/RPHProduksi/cekSPK',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if(data.status == 'Sukses'){
                        $("#cekspk").val(data.cekspk);
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
            }else if(cek == 'update1'){
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
                url: '/Produksi/JadwalKerjaHarian/RPHProduksi/simpan',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if(data.status == 'success'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: "Data Berhasil Disimpan",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        
                        $("#update").val(data.update);
                        $("#idmnya1").val(data.idmnya);
                        $("#idmnya").html(data.idmnya);
                        $("#idcari").val(data.idmnya);
                        $("#proses").val(data.proses);
                        document.getElementById("btnposting").disabled = false;
                        document.getElementById("btnform").disabled = true;
                        document.getElementById("btnbaru").disabled = false;
                        document.getElementById("btnubah").disabled = false;
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
                url: '/Produksi/JadwalKerjaHarian/RPHProduksi/update',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: "Data Berhasil Diupdate",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    $("#update").val(data.update);
                    $("#idmnya1").val(data.idmnya);
                    $("#idmnya").html(data.idmnya);
                    $("#idcari").val(data.idmnya);
                    $("#proses").val(data.proses);
                    document.getElementById("btnposting").disabled = false;
                    document.getElementById("btnform").disabled = true;
                    document.getElementById("btnbaru").disabled = false;
                    document.getElementById("btnubah").disabled = false;
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
                url: '/Produksi/JadwalKerjaHarian/RPHProduksi/update1',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: "Data Berhasil Diupdate",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    $("#update").val(data.update);
                    $("#idmnya1").val(data.idmnya);
                    $("#idmnya").html(data.idmnya);
                    $("#idcari").val(data.idmnya);
                    $("#proses").val(data.proses);
                    document.getElementById("btnposting").disabled = false;
                    document.getElementById("btnform").disabled = true;
                    document.getElementById("btnbaru").disabled = false;
                    document.getElementById("btnubah").disabled = false;
                },

                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
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
                url: '/Produksi/JadwalKerjaHarian/RPHProduksi/posting',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: "Data Berhasil Diposting",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    if (data.active === 'P') {
                        document.getElementById("btnsimpan").disabled = true;
                        document.getElementById("btnbatal").disabled = false;
                        document.getElementById("btnbaru").disabled = false;
                        document.getElementById("tglRPH").disabled = true;
                        document.getElementById("catatan").disabled = true;
                        document.getElementById("proses").disabled = true;
                        document.getElementById("btnform").disabled = true;
                        document.getElementById("btnubah").disabled = true;
                        document.getElementById("btnposting").disabled = true;
                        document.getElementById("btncetak").disabled = false;
                        // $("#btnsimpan").removeClass("btn btn-warning").addClass("btn btn-warning");
                        // $("#btnbatal").removeClass("btn btn-danger").addClass("btn btn-info");
                    }

                    if(data.location == 47){

                        var dataGrid = $("#tampil").dxDataGrid({
                            dataSource: data.datalist,
                            keyExpr: "ID",
                            columnAutoWidth: true,
                            width: '100%',
                            height: 550,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: {
                                visible: true
                            },
                            rowAlternationEnabled: true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            }, 
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
                                fileName: "RPH Produksi",
                                allowExportSelectedData: true
                            },
                            columns: [{
                                    dataField: "WS",
                                    dataType: "string",
                                    caption: "NTHKO",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Kadar",
                                    dataType: "string",
                                    caption: "Kadar",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "TransDate",
                                    sortIndex: 0,
                                    sortOrder: "asc",
                                    dataType: "date",
                                    format: 'dd-MM-yyyy',
                                    caption: "Tgl",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "TMno",
                                    dataType: "string",
                                    caption: "TM",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "WSW",
                                    dataType: "string",
                                    caption: "SPK",
                                    width: '8%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "PSS",
                                    dataType: "string",
                                    caption: "SubKategori",
                                    width: '8%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Lnama",
                                    dataType: "string",
                                    caption: "Dari",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "OPDescription",
                                    dataType: "string",
                                    caption: "Proses",
                                    width: '8%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "",
                                    dataType: "string",
                                    caption: "Bidang",
                                    width: '5%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "EnmStep",
                                    dataType: "string",
                                    caption: "Step",
                                    width: '5%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "EnmColor",
                                    dataType: "string",
                                    caption: "Warna",
                                    width: '5%',
                                    cssClass: "cls"
                                }, 
                                {
                                    dataField: "Qty",
                                    dataType: "number",
                                    caption: "Qty",
                                    width: '4%',
                                    cssClass: "lss"
                                },
                                {
                                    dataField: "Pcs",
                                    dataType: "number",
                                    caption: "Pcs",
                                    width: '4%',
                                    cssClass: "lss"
                                },
                                {
                                    dataField: "Weight",
                                    dataType: "number",
                                    caption: "Berat",
                                    width: '6%',
                                    cssClass: "lss",
                                    format: { type: 'fixedPoint', precision: 2 }
                                },
                                {
                                    dataField: "Kategori",
                                    dataType: "string",
                                    caption: "Kategori",
                                    width: '7%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Note",
                                    dataType: "string",
                                    caption: "Note",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Kadar",
                                    groupIndex: 0
                                },
                            ],
                            summary: {
                                groupItems: [{
                                    column: "Qty",
                                    displayFormat: "Total Qty : {0}",
                                    summaryType: "sum",
                                }, {
                                    column: "Pcs",
                                    displayFormat: "Total Pcs : {0}",
                                    summaryType: "sum",
         
                                }, {
                                    column: "Weight",
                                    displayFormat: "Total Berat : {0}",
                                    summaryType: "sum",
                                    valueFormat: "#,##0.00"
                                }],
                                totalItems: [{
                                    name: "SelectedRowsSummary",
                                    showInColumn: "Qty",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal"
                                }, {
                                    name: "SelectedRowsSummary1",
                                    showInColumn: "Pcs",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal"
                                }, {
                                    name: "SelectedRowsSummary2",
                                    showInColumn: "Weight",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal",
                                    valueFormat: "#,##0.00"
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
                                    if (options.name === "SelectedRowsSummary1") {
                                        if (options.summaryProcess === "start") {
                                            options.totalValue = 0;
                                        }
                                        if (options.summaryProcess === "calculate") {
                                            if (options.component.isRowSelected(options.value.ID)) {
                                                const count = parseFloat(options.value.Pcs);
                                                options.totalValue = options.totalValue + count;
                                            }
                                        }
                                    }
                                    if (options.name === "SelectedRowsSummary2") {
                                        if (options.summaryProcess === "start") {
                                            options.totalValue = 0;
                                        }
                                        if (options.summaryProcess === "calculate") {
                                            if (options.component.isRowSelected(options.value.ID)) {
                                                const count = parseFloat(options.value.Weight);
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
                                            return value.kirimkan;
                                        }).join(","));
                                selectedItems.component.refresh(true);
                            },
                        }).dxDataGrid("instance");
                        $("html, body").animate({
                            scrollTop: $(
                                'html, body').get(0).scrollHeight
                        }, 2000);
                        if (data.update > 0) {
                            $("#update").val('update1');
                        }

                    }else{

                        var dataGrid = $("#tampil").dxDataGrid({
                            dataSource: data.datalist,
                            keyExpr: "ID",
                            columnAutoWidth: true,
                            width: '100%',
                            height: 550,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: {
                                visible: true
                            },
                            rowAlternationEnabled: true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            }, 
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
                                fileName: "RPH Produksi",
                                allowExportSelectedData: true
                            },
                            columns: [{
                                    dataField: "WS",
                                    dataType: "string",
                                    caption: "NTHKO",
                                    width: '8%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Kadar",
                                    dataType: "string",
                                    caption: "Kadar",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "TransDate",
                                    sortIndex: 0,
                                    sortOrder: "asc",
                                    dataType: "date",
                                    format: 'dd-MM-yyyy',
                                    caption: "Tgl",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "TMno",
                                    dataType: "string",
                                    caption: "TM",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "WSW",
                                    dataType: "string",
                                    caption: "SPK",
                                    width: '10%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "PSS",
                                    dataType: "string",
                                    caption: "SubKategori",
                                    width: '8%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Lnama",
                                    dataType: "string",
                                    caption: "Dari",
                                    width: '6%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "OperationProcess", 
                                    dataType: "number", 
                                    caption: "Proses", 
                                    width: '8%', 
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Level2Process", 
                                    dataType: "number", 
                                    caption: "SubProses", 
                                    width: '8%', 
                                    cssClass: "cls"
                                }, 
                                {
                                    dataField: "Qty",
                                    dataType: "number",
                                    caption: "Qty",
                                    width: '6%',
                                    cssClass: "lss"
                                },
                                {
                                    dataField: "Pcs",
                                    dataType: "number",
                                    caption: "Pcs",
                                    width: '6%',
                                    cssClass: "lss"
                                },
                                {
                                    dataField: "Weight",
                                    dataType: "number",
                                    caption: "Berat",
                                    width: '7%',
                                    cssClass: "lss",
                                    format: { type: 'fixedPoint', precision: 2 }
                                },
                                {
                                    dataField: "Kategori",
                                    dataType: "string",
                                    caption: "Kategori",
                                    width: '7%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Note",
                                    dataType: "string",
                                    caption: "Note",
                                    width: '8%',
                                    cssClass: "cls"
                                },
                                {
                                    dataField: "Kadar",
                                    groupIndex: 0
                                },
                            ],
                            summary: {
                                groupItems: [{
                                    column: "Qty",
                                    displayFormat: "Total Qty : {0}",
                                    summaryType: "sum"
                                }, {
                                    column: "Pcs",
                                    displayFormat: "Total Pcs : {0}",
                                    summaryType: "sum"
                                }, {
                                    column: "Weight",
                                    displayFormat: "Total Berat : {0}",
                                    summaryType: "sum",
                                    valueFormat: "#,##0.00"
                                }],
                                totalItems: [{
                                    name: "SelectedRowsSummary",
                                    showInColumn: "Qty",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal"
                                }, {
                                    name: "SelectedRowsSummary1",
                                    showInColumn: "Pcs",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal"
                                }, {
                                    name: "SelectedRowsSummary2",
                                    showInColumn: "Weight",
                                    displayFormat: "Tot : {0}",
                                    summaryType: "custom",
                                    valueFormat: "decimal",
                                    valueFormat: "#,##0.00"
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
                                    if (options.name === "SelectedRowsSummary1") {
                                        if (options.summaryProcess === "start") {
                                            options.totalValue = 0;
                                        }
                                        if (options.summaryProcess === "calculate") {
                                            if (options.component.isRowSelected(options.value.ID)) {
                                                const count = parseFloat(options.value.Pcs);
                                                options.totalValue = options.totalValue + count;
                                            }
                                        }
                                    }
                                    if (options.name === "SelectedRowsSummary2") {
                                        if (options.summaryProcess === "start") {
                                            options.totalValue = 0;
                                        }
                                        if (options.summaryProcess === "calculate") {
                                            if (options.component.isRowSelected(options.value.ID)) {
                                                const count = parseFloat(options.value.Weight);
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
                                            return value.kirimkan;
                                        }).join(","));
                                selectedItems.component.refresh(true);
                            },
                        }).dxDataGrid("instance");
                        $("html, body").animate({
                            scrollTop: $(
                                'html, body').get(0).scrollHeight
                        }, 2000);
                        if (data.update > 0) {
                            $("#update").val('update1');
                        }

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

        function klikLihat() {
            var x = document.getElementById("idcari").value;

            data = {dropdownValue: x};
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
                    url: '/Produksi/JadwalKerjaHarian/RPHProduksi/lihat',
                    beforeSend: function(){
                        openModal();
                    },
                    data: data,
                    dataType: "json",
                    type: 'POST',
                    success: function(data) {

                        if(data.success != false){

                            $("#update").val(data.update);
                            $("#idmnya1").val(data.ID);
                            $("#idmnya").html(data.ID);
                            $("#idcari").val(data.ID);
                            $("#tglRPH").val(data.tglRPH);
                            $("#catatan").val(data.catatan);
                            $("#proses").val(data.proses);
                            if (data.active === 'P') {
                                document.getElementById("btnsimpan").disabled = true;
                                document.getElementById("btnbatal").disabled = false;
                                document.getElementById("btnbaru").disabled = false;
                                document.getElementById("tglRPH").disabled = true;
                                document.getElementById("btncetak").disabled = false;
                                document.getElementById("catatan").disabled = true;
                                document.getElementById("proses").disabled = true;
                                document.getElementById("btnform").disabled = true;
                                document.getElementById("btnposting").disabled = true;
                                // $("#btnsimpan").removeClass("btn btn-warning").addClass("btn btn-warning");
                                // $("#btnbatal").removeClass("btn btn-danger").addClass("btn btn-info");
                            } else {
                                document.getElementById("btnsimpan").disabled = true;
                                document.getElementById("btnbatal").disabled = false;
                                document.getElementById("btnbaru").disabled = false;
                                document.getElementById("btnubah").disabled = false;
                                document.getElementById("tglRPH").disabled = true;
                                document.getElementById("catatan").disabled = true;
                                document.getElementById("proses").disabled = true;
                                document.getElementById("btnform").disabled = true;
                                document.getElementById("btnposting").disabled = false;
                                // $("#btnubah").removeClass("btn btn-info").addClass("btn btn-warning");
                                // $("#btnbatal").removeClass("btn btn-info").addClass("btn btn-danger");
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Tersimpan!',
                                    text: "Data Belum Diposting",
                                    timer: 2000,
                                    showCancelButton: false,
                                    showConfirmButton: true
                                });
                            }

                            if(data.location == 47){
                                var dataGrid = $("#tampil").dxDataGrid({
                                    dataSource: data.datalist,
                                    keyExpr: "ID",
                                    columnAutoWidth: true,
                                    width: '100%',
                                    height: 550,
                                    allowColumnReordering: true,
                                    showBorders: true,
                                    headerFilter: {
                                        visible: true
                                    },
                                    rowAlternationEnabled: true,
                                    filterRow: {
                                        visible: true,
                                        applyFilter: "auto"
                                    }, 
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
                                            dataField: "WS",
                                            dataType: "string",
                                            caption: "NTHKO",
                                            width: '10%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "TransDate",
                                            sortIndex: 2,
                                            sortOrder: "asc",
                                            dataType: "date",
                                            format: 'dd-MM-yyyy',
                                            caption: "Tgl",
                                            width: '6%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "TMno",
                                            dataType: "number",
                                            caption: "TM",
                                            width: '9%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Kategori",
                                            dataType: "string",
                                            caption: "Kategori",
                                            width: '9%',
                                            cssClass: "cls",
                                            sortOrder: 'asc',
                                            sortIndex: 1,
                                            groupIndex: 1,
                                            visible: true,
                                            showWhenGrouped: false
                                        },
                                        {
                                            dataField: "Kadar",
                                            dataType: "string",
                                            caption: "Kadar",
                                            width: '6%',
                                            cssClass: "cls",
                                            sortOrder: 'asc',
                                            sortIndex: 0,
                                            groupIndex: 0,
                                            visible: true,
                                            showWhenGrouped: false
                                        },
                                        {
                                            dataField: "WSW",
                                            dataType: "string",
                                            caption: "SPK",
                                            width: '10%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "PSS",
                                            dataType: "string",
                                            caption: "SubKategori",
                                            width: '7%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Lnama",
                                            dataType: "string",
                                            caption: "Dari",
                                            width: '7%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "OPDescription",
                                            dataType: "string",
                                            caption: "Proses",
                                            width: '7%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "EnmSurface",
                                            dataType: "string",
                                            caption: "Bidang",
                                            width: '5%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "EnmStep",
                                            dataType: "string",
                                            caption: "Step",
                                            width: '5%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "EnmColor",
                                            dataType: "string",
                                            caption: "Warna",
                                            width: '5%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Qty",
                                            dataType: "number",
                                            caption: "Qty",
                                            width: '5%',
                                            cssClass: "lss"
                                        },
                                        {
                                            dataField: "Pcs",
                                            dataType: "number",
                                            caption: "Pcs",
                                            width: '5%',
                                            cssClass: "lss"
                                        },
                                        {
                                            dataField: "Weight",
                                            dataType: "number",
                                            caption: "Berat",
                                            width: '6%',
                                            cssClass: "lss",
                                            format: { type: 'fixedPoint', precision: 2 }
                                        },
                                        {
                                            dataField: "Note",
                                            dataType: "string",
                                            caption: "Note",
                                            cssClass: "cls",
                                            width: '10%'
                                        }
                                    ],
                                    summary: {
                                        groupItems: [{
                                            column: "Qty",
                                            displayFormat: "Total Qty : {0}",
                                            summaryType: "sum"
                                        }, {
                                            column: "Weight",
                                            displayFormat: "Total Berat : {0}",
                                            summaryType: "sum",
                                            valueFormat: "#,##0.00"
                                        }, {
                                            column: "Pcs",
                                            displayFormat: "Total Pcs : {0}",
                                            summaryType: "sum"
                                        }],
                                    },
                                    onSelectionChanged: function(selectedItems) {
                                        var data = selectedItems.selectedRowsData;
                                        if (data.length > 0)
                                            $("#pilih").val(
                                                $.map(data, function(value) {
                                                    return value.kirimkan;
                                                }).join(","));
                                        selectedItems.component.refresh(true);
                                    },
                                }).dxDataGrid("instance");
                                $("html, body").animate({
                                    scrollTop: $(
                                        'html, body').get(0).scrollHeight
                                }, 2000);

                            }else{

                                var dataGrid = $("#tampil").dxDataGrid({
                                    dataSource: data.datalist,
                                    keyExpr: "ID",
                                    columnAutoWidth: true,
                                    width: '100%',
                                    height: 550,
                                    allowColumnReordering: true,
                                    showBorders: true,
                                    headerFilter: {
                                        visible: true
                                    },
                                    rowAlternationEnabled: true,
                                    filterRow: {
                                        visible: true,
                                        applyFilter: "auto"
                                    }, 
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
                                            dataField: "WS",
                                            dataType: "string",
                                            caption: "NTHKO",
                                            width: '10%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "TransDate",
                                            sortIndex: 2,
                                            sortOrder: "asc",
                                            dataType: "date",
                                            format: 'dd-MM-yyyy',
                                            caption: "Tgl",
                                            width: '6%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "TMno",
                                            dataType: "number",
                                            caption: "TM",
                                            width: '9%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Kategori",
                                            dataType: "string",
                                            caption: "Kategori",
                                            width: '9%',
                                            cssClass: "cls",
                                            sortOrder: 'asc',
                                            sortIndex: 1,
                                            groupIndex: 1,
                                            visible: true,
                                            showWhenGrouped: false
                                        },
                                        {
                                            dataField: "Kadar",
                                            dataType: "string",
                                            caption: "Kadar",
                                            width: '6%',
                                            cssClass: "cls",
                                            sortOrder: 'asc',
                                            sortIndex: 0,
                                            groupIndex: 0,
                                            visible: true,
                                            showWhenGrouped: false
                                        },
                                        {
                                            dataField: "WSW",
                                            dataType: "string",
                                            caption: "SPK",
                                            width: '10%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "PSS",
                                            dataType: "string",
                                            caption: "SubKategori",
                                            width: '7%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Lnama",
                                            dataType: "string",
                                            caption: "Dari",
                                            width: '7%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "OperationProcess",
                                            dataType: "string",
                                            caption: "Proses",
                                            width: '12%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Level2Process",
                                            dataType: "string",
                                            caption: "SubProses",
                                            width: '10%',
                                            cssClass: "cls"
                                        },
                                        {
                                            dataField: "Qty",
                                            dataType: "number",
                                            caption: "Qty",
                                            width: '5%',
                                            cssClass: "lss"
                                        },
                                        {
                                            dataField: "Pcs",
                                            dataType: "number",
                                            caption: "Pcs",
                                            width: '5%',
                                            cssClass: "lss"
                                        },
                                        {
                                            dataField: "Weight",
                                            dataType: "number",
                                            caption: "Berat",
                                            width: '6%',
                                            cssClass: "lss",
                                            format: { type: 'fixedPoint', precision: 2 }
                                        },
                                        {
                                            dataField: "Note",
                                            dataType: "string",
                                            caption: "Note",
                                            cssClass: "cls",
                                            width: '10%'
                                        }
                                    ],
                                    summary: {
                                        groupItems: [{
                                            column: "Qty",
                                            displayFormat: "Total Qty : {0}",
                                            summaryType: "sum"
                                        }, {
                                            column: "Weight",
                                            displayFormat: "Total Berat : {0}",
                                            summaryType: "sum",
                                            valueFormat: "#,##0.00"
                                        }, {
                                            column: "Pcs",
                                            displayFormat: "Total Pcs : {0}",
                                            summaryType: "sum"
                                        }],
                                    },
                                    onSelectionChanged: function(selectedItems) {
                                        var data = selectedItems.selectedRowsData;
                                        if (data.length > 0)
                                            $("#pilih").val(
                                                $.map(data, function(value) {
                                                    return value.kirimkan;
                                                }).join(","));
                                        selectedItems.component.refresh(true);
                                    },
                                }).dxDataGrid("instance");
                                $("html, body").animate({
                                    scrollTop: $(
                                        'html, body').get(0).scrollHeight
                                }, 2000);

                            }

                        }

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
            document.getElementById("btnbaru").disabled = false;
            document.getElementById("btnubah").disabled = true;
            document.getElementById("tglRPH").disabled = false;
            document.getElementById("catatan").disabled = false;
            document.getElementById("proses").disabled = false;
            document.getElementById("btnposting").disabled = false;
            document.getElementById("btnform").disabled = false;
            // $("#btnsimpan").removeClass("btn btn-info").addClass("btn btn-warning");
            // $("#btnbatal").removeClass("btn btn-info").addClass("btn btn-danger");
        }

        function klikCetak() {
            var idlihatkan = $("#idmnya1").val();
            var dataUrl = `/Produksi/JadwalKerjaHarian/RPHProduksi/cetak?idrph=${idlihatkan}`;
            window.open(dataUrl, '_blank');
        }

    </script>

@endsection

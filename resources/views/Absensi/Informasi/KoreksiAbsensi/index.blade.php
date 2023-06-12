<?php $title = 'Data Koreksi Absensi'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Koreksi Absensi </li>
    </ol>
@endsection

@section('css')

    <style>

        .dx-datagrid-headers{
            background-color: #e7e9ed;
        }

    </style>
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">
    
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Absensi.Informasi.KoreksiAbsensi.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchKoreksiAbsensi() {
            let periode = $('#periode').val()
            let jenis = $('#jenis').val()

            if (periode == "" || jenis == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/KoreksiAbsensi/Cari',
                data: {periode: periode, jenis:jenis},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    if (jenis == 1) {
                        // Get Ijin
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.tampil,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 500,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: { visible: true },
                            rowAlternationEnabled : true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            },         
                            selection: {
                                mode: "none" // or "multiple" | "none"
                            },  
    
                            searchPanel: {
                                visible: true
                            },
                            paging: { enabled: false },
                            grouping: {  
                                autoExpandAll: false  
                            },  
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "Informasi_KoreksiAbsensi_Ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '5%'
                                }, 
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "TransDate", width: '10%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Period", dataType: "date", format: 'dd-MM-yyyy', caption: "Periode", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '15%', cssClass: "cls"
                                },    
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '10%'
                                },   
                                {
                                    dataField: "Type", dataType: "string", caption: "Type", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "DateStart", dataType: "date", format: 'dd-MM-yyyy', caption: "Tgl Mulai", cssClass: "cls", width: '7.5%',
                                }, 
                                {
                                    dataField: "DateEnd", dataType: "date", format: 'dd-MM-yyyy', caption: "Tgl Selesai", cssClass: "cls", width: '7.5%',
                                }, 
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam Mulai", cssClass: "cls", width: '7.5%',
                                }, 
                                {
                                    dataField: "DateStart", dataType: "string", caption: "Jam Selesai", cssClass: "cls", width: '7.5%',
                                },		   				          	          		           		          		                                                                                        
                            ],
                            onRowClick: function(e) {  
                                if (e.rowType == 'group') {  
                                    if (e.isExpanded)  
                                        e.component.collapseRow(e.key);  
                                    else  
                                        e.component.expandRow(e.key);  
                                }  
                            },  
                        }).dxDataGrid("instance");    		
                    } else if (jenis == 2) {
                        // Get Lembur
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.tampil,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 500,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: { visible: true },
                            rowAlternationEnabled : true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            },         
                            selection: {
                                mode: "none" // or "multiple" | "none"
                            },  
    
                            searchPanel: {
                                visible: true
                            },
                            paging: { enabled: false },
                            grouping: {  
                                autoExpandAll: false  
                            },  
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "Informasi_KoreksiAbsensi_Lembur",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '5%'
                                }, 
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "TransDate", width: '10%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Period", dataType: "date", format: 'dd-MM-yyyy', caption: "Periode", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '15%', cssClass: "cls"
                                },    
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '10%'
                                },   
                                {
                                    dataField: "OverTimeDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Lembur", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam Mulai", cssClass: "cls", width: '10%',
                                }, 
                                {
                                    dataField: "TimeEnd", dataType: "string", caption: "Jam Selesai", cssClass: "cls", width: '10%',
                                }, 		            	          		           		          		                                                                                        
                            ],
                            onRowClick: function(e) {  
                                if (e.rowType == 'group') {  
                                    if (e.isExpanded)  
                                        e.component.collapseRow(e.key);  
                                    else  
                                        e.component.expandRow(e.key);  
                                }  
                            },  
                        }).dxDataGrid("instance");  
                    } else if (jenis == 3){
                        // Get Penggajian
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.tampil,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 500,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: { visible: true },
                            rowAlternationEnabled : true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            },         
                            selection: {
                                mode: "none" // or "multiple" | "none"
                            },  
    
                            searchPanel: {
                                visible: true
                            },
                            paging: { enabled: false },
                            grouping: {  
                                autoExpandAll: false  
                            },  
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "Informasi_KoreksiAbsensi_Penggajian",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '5%'
                                }, 
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "TransDate", width: '10%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Period", dataType: "date", format: 'dd-MM-yyyy', caption: "Periode", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '15%', cssClass: "cls"
                                },    
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '10%'
                                },   
                                {
                                    dataField: "Amount", dataType: "string", caption: "Gaji", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "Type", dataType: "string", caption: "Tipe", cssClass: "cls", width: '10%',
                                }, 
                                {
                                    dataField: "Note", dataType: "string", caption: "Catatan", cssClass: "cls", width: '10%',
                                },	 		            	          		           		          		                                                                                        
                            ],
                            onRowClick: function(e) {  
                                if (e.rowType == 'group') {  
                                    if (e.isExpanded)  
                                        e.component.collapseRow(e.key);  
                                    else  
                                        e.component.expandRow(e.key);  
                                }  
                            },  
                        }).dxDataGrid("instance");  
                    } else {
                        // Invalid Jenis
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "jenis Invalid",
                        })
                        return;
                    }
                },
                error:function(xhr){
                    // Invalid Jenis
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });   
        }
    </script>
@endsection
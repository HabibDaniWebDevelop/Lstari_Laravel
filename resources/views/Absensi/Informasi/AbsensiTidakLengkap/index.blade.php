<?php $title = 'Data Absensi Tidak Lengkap'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Absensi Tidak Lengkap </li>
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

                @include('Absensi.Informasi.AbsensiTidakLengkap.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchAbsensiTidakLengkap() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()
            let jenis = $('#jenis').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/AbsensiTidakLengkap/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir, jenis:jenis},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    if (jenis == 1) {
                        // Get CheckClock Tidak Lengkap
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '15%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Jam Masuk", width: '15%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Jam Keluar", width: '15%', cssClass: "cls"
                                },     
                                {
                                    dataField: "NAME", dataType: "string", caption: "Karyawan", width: '25%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '15%'
                                },
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '15%'
                                },  
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                }		   				          	          		           		          		                                                                                        
                            ],
                            summary: {
                                groupItems: [
                                    {
                                        column: "TransDate",
                                        summaryType: "count",
                                        displayFormat: "Total : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                ]
                            },
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
                        // Get Tidak Masuk Kerja
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", cssClass: "cls", width: '20%'
                                },
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '10%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", width: '20%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '20%', cssClass: "cls"
                                },      
                                {
                                    dataField: "NAME", dataType: "string", caption: "Karyawan", width: '30%', cssClass: "cls"
                                },  
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                } 		            	          		           		          		                                                                                        
                            ],
                            summary: {
                                groupItems: [
                                    {
                                        column: "TransDate",
                                        summaryType: "count",
                                        displayFormat: "Total : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                ]
                            },
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
                        // Get Tidak Ada Ijin Kerja
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                },
                                {
                                    dataField: "DAY", dataType: "string", caption: "Hari", width: '10%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Jam Masuk", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Jam Keluar", width: '10%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Late", dataType: "string", caption: "Terlambat", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '20%'
                                },
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '20%'
                                },		          		           		 				
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                }	 		            	          		           		          		                                                                                        
                            ],
                            summary: {
                                groupItems: [
                                    {
                                        column: "TransDate",
                                        summaryType: "count",
                                        displayFormat: "Total : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                ]
                            },
                            onRowClick: function(e) {  
                                if (e.rowType == 'group') {  
                                    if (e.isExpanded)  
                                        e.component.collapseRow(e.key);  
                                    else  
                                        e.component.expandRow(e.key);  
                                }  
                            },  
                        }).dxDataGrid("instance");  
                    } else if (jenis == 4){
                        // Get Masuk Kerja, Ada Ijin Kerja
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                },
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '5%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '20%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '25%', cssClass: "cls"
                                },    
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam MUlai", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "TimeEnd", dataType: "string", caption: "Jam Selesai", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Absen Masuk", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Absen Keluar", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                }          		           		          		                                                                                        
                            ],
                            summary: {
                                groupItems: [
                                    {
                                        column: "TransDate",
                                        summaryType: "count",
                                        displayFormat: "Total : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                ]
                            },
                            onRowClick: function(e) {  
                                if (e.rowType == 'group') {  
                                    if (e.isExpanded)  
                                        e.component.collapseRow(e.key);  
                                    else  
                                        e.component.expandRow(e.key);  
                                }  
                            },  
                        }).dxDataGrid("instance");  
                    } else if (jenis == 5){
                        // Get Lembur, Ada Ijin Kerja
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Absen Masuk", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Absen Keluar", cssClass: "cls", width: '5%'
                                },		                              
                                {
                                    dataField: "AbsentDay", dataType: "string", caption: "Absent", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Absent", dataType: "string", caption: "Ijin", width: '7%', cssClass: "cls"
                                },    
                                {
                                    dataField: "AbsentPaid", dataType: "string", caption: "Sakit", cssClass: "cls", width: '7%'
                                }, 
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '7%'
                                },
                                {
                                    dataField: "WorkTime", dataType: "string", caption: "Jam Kerja", cssClass: "cls", width: '7%'
                                },
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '15%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '13%'
                                },
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },		          		          		          		           		         
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                } 		            	          		           		          		                                                                                        
                            ],
                            summary: {
                                groupItems: [
                                    {
                                        column: "TransDate",
                                        summaryType: "count",
                                        displayFormat: "Total : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                ]
                            },
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
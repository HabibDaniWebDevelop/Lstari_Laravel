<?php $title = 'Data Lembur Kerja'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Lembur Kerja </li>
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

                @include('Absensi.Informasi.LemburKerja.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchLemburKerja() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()
            let jenis = $('#jenis').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/LemburKerja/Cari',
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "DAY", dataType: "string", caption: "Hari", width: '5%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam Mulai", width: '5%', cssClass: "cls"
                                },     
                                {
                                    dataField: "TimeEnd", dataType: "string", caption: "Jam Selesai", width: '5%', cssClass: "cls"
                                },    
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '20%'
                                }, 
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '15%'
                                }, 
                                {
                                    dataField: "Reason", dataType: "string", caption: "Catatan", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "ActualFrom", dataType: "string", caption: "Actual", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "ActualTo", dataType: "string", caption: "To", cssClass: "cls", width: '5%'
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
                        // Get Rekapitulasi
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
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '20%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", cssClass: "cls", width: '10%'
                                }, 		                              
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Jam Masuk", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Jam Keluar", width: '10%', cssClass: "cls"
                                },    
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "OverTimeAdd", dataType: "string", caption: "Tambahan Lembur", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "OverTimeRate", dataType: "string", caption: "Lembur Rate", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
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
                        // Get Koreksi
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
                                    dataField: "DAY", dataType: "string", caption: "Hari", width: '5%', cssClass: "cls"
                                },    		                              
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam Masuk", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "TimeEnd", dataType: "string", caption: "Jam Keluar", width: '10%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '20%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '15%'
                                },			          		          
                                {
                                    dataField: "Reason", dataType: "string", caption: "Catatan", cssClass: "cls", width: '10%'
                                }, 
                                {
                                    dataField: "ActualFrom", dataType: "string", caption: "Actual", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "ActualTo", dataType: "string", caption: "To", cssClass: "cls", width: '5%'
                                },	
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
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
                    } else if (jenis == 4){
                        // Get Belum Di-Entry
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
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '25%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '25%', cssClass: "cls"
                                },    
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },		          
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Absen Masuk", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Absen Keluar", cssClass: "cls", width: '10%'
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
                    } else if (jenis == 5){
                        // Get Beda Jam Lembur
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
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '8%', cssClass: "cls"
                                },  
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '15%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '13%'
                                },		
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '8%'
                                },		                    
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Absen Masuk", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Absen Keluar", cssClass: "cls", width: '8%'
                                },	
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '8%'
                                }, 		          
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam Masuk", width: '8%', cssClass: "cls"
                                },      
                                {
                                    dataField: "TimeEnd", dataType: "string", caption: "Jam Keluar", width: '8%', cssClass: "cls"
                                },   
                                {
                                    dataField: "ActualFrom", dataType: "string", caption: "Actual", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "ActualTo", dataType: "string", caption: "To", cssClass: "cls", width: '8%'
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
                    } else if (jenis == 6){
                        // Get Pulang Awal
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
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '8%', cssClass: "cls"
                                },  
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '15%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '13%'
                                },		
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '8%'
                                },		                    
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Absen Masuk", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Absen Keluar", cssClass: "cls", width: '8%'
                                },	
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '8%'
                                }, 		          
                                {
                                    dataField: "TimeStart", dataType: "string", caption: "Jam Masuk", width: '8%', cssClass: "cls"
                                },      
                                {
                                    dataField: "TimeEnd", dataType: "string", caption: "Jam Keluar", width: '8%', cssClass: "cls"
                                },   
                                {
                                    dataField: "ActualFrom", dataType: "string", caption: "Actual", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "ActualTo", dataType: "string", caption: "To", cssClass: "cls", width: '8%'
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
                    } else if (jenis == 7){
                        // Tambahan Uang Makan
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
                                    dataField: "ID", dataType: "string", caption: "ID", width: '5%', cssClass: "cls"
                                }, 		          
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '25%'
                                },
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", cssClass: "cls", width: '25%'
                                },		
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },		                    
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Absen Keluar", cssClass: "cls", width: '10%'
                                },	
                                {
                                    dataField: "Active", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                }, 		          
                                {
                                    dataField: "Ordinal", dataType: "string", caption: "Urutan", width: '5%', cssClass: "cls"
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
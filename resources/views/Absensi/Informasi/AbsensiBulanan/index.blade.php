<?php $title = 'Data Absensi Bulanan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Absensi Bulanan </li>
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

                @include('Absensi.Informasi.AbsensiBulanan.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchAbsensiBulanan() {
            let periode = $('#periode').val()
            let jenis = $('#jenis').val()

            if (periode == "" || jenis == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/AbsensiBulanan/Cari',
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
                        // Get Rekap
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
                                fileName: "Informasi_AbsensiBulanan_Rekap",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '10%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "Department", dataType: "string", caption: "Bagian", width: '10%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Absent", dataType: "string", caption: "Absen", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", width: '10%', cssClass: "cls"
                                },    
                                {
                                    dataField: "AbsentPaid", dataType: "string", caption: "Sakit", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Allowance", dataType: "string", caption: "Tunjangan", cssClass: "cls", width: '10%'
                                },  
                                {
                                    dataField: "Food", dataType: "string", caption: "Food", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "WORK", dataType: "string", caption: "Kerja", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "AbsentDay", dataType: "string", caption: "Ijin", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "Late", dataType: "string", caption: "Telat", cssClass: "cls", width: '5%'
                                },
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Bagian", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "WorkHour", dataType: "string", caption: "Jam Kerja", cssClass: "cls", width: '10%'
                                },			          	                 		          	          	            	           
                                {
                                    dataField: "Employee",
                                    caption: "Nama",
                                    groupIndex: 0
                                }		   				          	          		           		          		                                                                                        
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
                        // Get Lembur Kerja
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
                                fileName: "Informasi_AbsensiBulanan_LemburKerja",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '15%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "Department", dataType: "string", caption: "Bagian", width: '13%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "Total45", dataType: "string", caption: "Total 45", width: '8%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Total40", dataType: "string", caption: "Total 40", width: '8%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Total35", dataType: "string", caption: "Total 35", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "Total30", dataType: "string", caption: "Total 30", cssClass: "cls", width: '8%'
                                },  
                                {
                                    dataField: "Total25", dataType: "string", caption: "Total 25", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "Total20", dataType: "string", caption: "Total 20", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "Total15", dataType: "string", caption: "Total 15", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "Total10", dataType: "string", caption: "Total 10", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "AddFood", dataType: "string", caption: "Tambahan Makan", cssClass: "cls", width: '8%'
                                },       	          	            	           
                                {
                                    dataField: "Employee",
                                    caption: "Nama",
                                    groupIndex: 0
                                } 		            	          		           		          		                                                                                        
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
                        // Get Jam Kerja
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
                                fileName: "Informasi_AbsensiBulanan_JamKerja",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '15%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "Department", dataType: "string", caption: "Bagian", width: '13%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '8%', cssClass: "cls"
                                },      
                                {
                                    dataField: "WorkIn", dataType: "string", caption: "Masuk", width: '8%', cssClass: "cls"
                                },    
                                {
                                    dataField: "WorkOut", dataType: "string", caption: "Pulang", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "Late", dataType: "string", caption: "Terlambat", cssClass: "cls", width: '8%'
                                },  
                                {
                                    dataField: "Absent", dataType: "string", caption: "Ijin", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "WorkTime", dataType: "string", caption: "Jam Kerja", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "AbsentDay", dataType: "string", caption: "Absen", cssClass: "cls", width: '8%'
                                },
                                {
                                    dataField: "AbsentPaid", dataType: "string", caption: "Ijin Sakit", cssClass: "cls", width: '8%'
                                },       	          	            	           
                                {
                                    dataField: "TransDate",
                                    caption: "Tanggal",
                                    groupIndex: 0
                                }	 		            	          		           		          		                                                                                        
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